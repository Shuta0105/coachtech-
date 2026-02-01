<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * ユーザープロフィール画面の表示
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $user_id = auth()->id();
            $user_profile = UserProfile::with('user')->where('user_id', $user_id)->firstOrFail();
            $param = $request->page ?? 'sell';
            if ($param === 'buy') {
                $items = Item::whereHas('order', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                })->get();
            } else {
                $items = Item::withCount('order')
                    ->where('user_id', $user_id)
                    ->get();
            }
            return view('profile', compact('items', 'user_profile', 'param'));
        } catch (Exception $e) {
            return view('error');
        }
    }

    /**
     * ユーザープロフィール変更画面の表示
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        try {
            $user_profile = UserProfile::with('user')->where('user_id', auth()->id())->first();
            return view('profile-edit', compact('user_profile'));
        } catch (Exception $e) {
            return view('error');
        }
    }

    /**
     * ユーザープロフィールを更新する
     * @param ProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        try {
            $user = auth()->user();
            $user_profile = UserProfile::where('user_id', $user->id)->first();
            $path = $user_profile ? $user_profile->avatar : null;

            // 画像がアップロードされている場合のみ保存処理を行う
            if ($request->hasFile('avatar')) {
                // public/avatars に保存
                $path = $request->file('avatar')->store('avatars', 'public');

                // 更新前のユーザーのアバターをストレージから削除
                if ($user_profile && $user_profile->avatar) {
                    Storage::disk('public')->delete($user_profile->avatar);
                }
            }
            $param = $request->only('post_code', 'address', 'building');
            $param['user_id'] = $user->id;
            $param['avatar'] = $path;

            // ユーザー登録後のプロフィール作成時
            if (!$user_profile) {
                UserProfile::create($param);
            } else {
                $user_profile->update($param);
            }

            $user = User::find($user->id);
            $user->update(['name' => $request->name]);

            return redirect('/');
        } catch (Exception $e) {
            return view('error');
        }
    }
}
