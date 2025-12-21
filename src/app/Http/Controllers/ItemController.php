<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Condition;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Order;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $param = $request->input('tab');
        if ($param == 'mylist') {
            $items = Item::withCount('order')
                ->whereHas('likes', function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->when($keyword, function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                })
                ->get();

            return view('index', compact('items'));
        } else {
            $items = Item::withCount('order')
                ->where(function ($q) {
                    $q->where('user_id', '!=', auth()->id())
                        ->orWhereNull('user_id');
                })
                ->when($keyword, function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                })
                ->get();

            return view('index', compact('items'));
        }
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $tab = $request->input('tab');

        if ($tab == 'mylist') {
            $items = Item::withCount('order')
                ->whereHas('likes', function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->when($keyword, function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                })
                ->get();

            return view('search-list', compact('items'));
        } else {
            $items = Item::withCount('order')
                ->where(function ($q) {
                    $q->where('user_id', '!=', auth()->id())
                        ->orWhereNull('user_id');
                })
                ->when($keyword, function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                })->get();

            return view('search-list', compact('items'));
        }
    }

    public function detail($item_id)
    {
        $item = Item::with('condition')->withCount('likes')->find($item_id);
        $comments = Comment::with('user')->where('item_id', $item_id)->get();
        $commentCount = Comment::where('item_id', $item_id)->count();
        return view('product-detail', compact('item', 'comments', 'commentCount'));
    }

    public function comment(CommentRequest $request, $item_id)
    {
        $param = [
            'user_id' => auth()->id(),
            'item_id' => $item_id,
            'content' => $request->comment
        ];
        Comment::create($param);
        return redirect("/item/$item_id");
    }

    public function sell()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        return view('sell', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        $item = $request->only('name', 'price', 'brand', 'detail', 'condition_id');
        $user_id = auth()->id();
        $item['user_id'] = $user_id;
        if ($request->hasFile('img')) {
            $file = $request->file('img');

            $path = $file->store('items', 'public');

            $item['img'] = $path;
        }
        $new_item = Item::create($item);
        $category_ids = $request->category_ids ?? [];
        foreach ($category_ids as $category_id) {
            ItemCategory::create([
                'item_id' => $new_item['id'],
                'category_id' => $category_id
            ]);
        };
        return redirect('/');
    }

    public function purchase($item_id)
    {
        $item = Item::find($item_id);
        $userProfile = UserProfile::where('user_id', auth()->id())->first();
        $defaultAddress = [
            'post_code' => $userProfile->post_code,
            'address' => $userProfile->address,
            'building' => $userProfile->building
        ];
        $address = array_merge(
            $defaultAddress,
            session('purchase_address', [])
        );
        return view('purchase', compact('item', 'address'));
    }

    public function address($item_id)
    {
        return view('address-change', compact('item_id'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        $address = [
            'post_code' => $request->post_code,
            'address' => $request->address,
            'building' => $request->building,
        ];
        session(['purchase_address' => $address]);
        return redirect("/purchase/{$item_id}");
    }

    public function order(PurchaseRequest $request, $item_id)
    {
        Order::create([
            'user_id' => auth()->id(),
            'item_id' => $item_id,
            'paymethod' => $request->paymethod,
            'post_code' => $request->post_code,
            'address' => $request->address,
            'building' => $request->building
        ]);
        return redirect('/');
    }
}
