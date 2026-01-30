<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\ItemSearchRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Condition;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\UserProfile;
use Exception;

class ItemController extends Controller
{
    /**
     * 商品一覧画面の表示・検索
     * @param ItemSearchRequest $request
     * @return \Illuminate\View\View
     */
    public function index(ItemSearchRequest $request)
    {
        try {
            // 検索キーワード
            $keyword = $request->input('keyword');

            // 検索種別（price / category / name）
            $select = $request->input('select');

            // 表示タブ 
            $tab = $request->input('tab');

            $items = Item::withCount('order')
                ->when($tab === 'mylist', function ($q) {
                    $q->whereHas('likes', function ($q) {
                        $q->where('user_id', auth()->id());
                    });
                })
                ->when($tab !== 'mylist', function ($q) {
                    $q->where(function ($q) {
                        $q->where('user_id', '!=', auth()->id())
                            ->orWhereNull('user_id');
                    });
                })
                ->when($keyword, function ($q) use ($keyword, $select) {
                    /**
                     * ---------価格検索-------------
                     * 例：1200 -> 1000~1999で検索
                     */
                    if ($select === 'price') {
                        // 数値以外は検索しない
                        if (!ctype_digit($keyword)) {
                            return;
                        }
                        $price = (int) $keyword;

                        // 0より小さい値は検索しない
                        if ($price < 0) {
                            return;
                        }

                        // 桁数から検索範囲を計算
                        $digits = strlen((string) $price);
                        $unit   = 10 ** ($digits - 1);

                        $min = (int) floor($price / $unit) * $unit;
                        $max = $min + $unit - 1;

                        $q->whereBetween('price', [$min, $max]);
                    } elseif ($select === 'category') {
                        $q->whereHas('categories', function ($q) use ($keyword) {
                            $q->where('content', 'like', "%{$keyword}%");
                        });
                    } else {
                        $q->where('name', 'like', "%{$keyword}%");
                    }
                })
                ->get();

            /**
             * Ajax検索時
             */
            if ($request->ajax()) {
                return view('search-list', compact('items'));
            }

            return view('index', compact('items'));
        } catch (Exception $e) {
            return view('error');
        }
    }

    /**
     * 商品詳細画面の表示
     * @param int $item_id
     * @return \Illuminate\View\View
     */
    public function detail($item_id)
    {
        try {
            $item = Item::with('condition')->withCount('likes')->find($item_id);
            $comments = Comment::with('user')->where('item_id', $item_id)->get();
            $commentCount = Comment::where('item_id', $item_id)->count();
            $item_categories = ItemCategory::with('category')->where('item_id', $item_id)->get();
            return view('product-detail', compact('item', 'comments', 'commentCount', 'item_categories'));
        } catch (Exception $e) {
            return view('error');
        }
    }

    /**
     * 商品に対してコメントを投稿する
     * @param CommentRequest $request
     * @param int $item_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function comment(CommentRequest $request, $item_id)
    {
        try {
            $param = [
                'user_id' => auth()->id(),
                'item_id' => $item_id,
                'content' => $request->comment
            ];
            Comment::create($param);
            return redirect("/item/{$item_id}");
        } catch (Exception $e) {
            return view('error');
        }
    }

    /**
     * 出品画面の表示
     * @return \Illuminate\View\View
     */
    public function sell()
    {
        try {
            $categories = Category::all();
            $conditions = Condition::all();
            return view('sell', compact('categories', 'conditions'));
        } catch (Exception $e) {
            return view('error');
        }
    }

    /**
     * 出品した商品を保存する
     * @param ExhibitionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ExhibitionRequest $request)
    {
        try {
            $item = $request->only('name', 'price', 'brand', 'detail', 'condition_id');
            $user_id = auth()->id();
            $item['user_id'] = $user_id;

            if ($request->hasFile('img')) {
                $file = $request->file('img');

                // public/items に保存
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
        } catch (Exception $e) {
            return view('error');
        }
    }

    /**
     * 商品購入画面を表示
     * @param int $item_id
     * @return \Illuminate\View\View
     */
    public function purchase($item_id)
    {
        try {
            $item = Item::find($item_id);
            $userProfile = UserProfile::where('user_id', auth()->id())->first();
            $defaultAddress = [
                'post_code' => $userProfile->post_code,
                'address' => $userProfile->address,
                'building' => $userProfile->building
            ];

            // セッション住所とマージ
            $address = array_merge(
                $defaultAddress,
                session('purchase_address', [])
            );
            return view('purchase', compact('item', 'address'));
        } catch (Exception $e) {
            return view('error');
        }
    }

    /**
     * 商品送り付け先住所変更画面の表示
     * @param int $item_id
     * @return \Illuminate\View\View
     */
    public function address($item_id)
    {
        return view('address-change', compact('item_id'));
    }

    /**
     * 商品送り付け先住所を変更する
     * @param AddressRequest $request
     * @param int $item_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AddressRequest $request, $item_id)
    {
        try {
            $address = [
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building' => $request->building,
            ];
            session(['purchase_address' => $address]);
            return redirect("/purchase/{$item_id}");
        } catch (Exception $e) {
            return view('error');
        }
    }
}
