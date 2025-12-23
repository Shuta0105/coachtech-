<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user_id = auth()->id();
        $user_profile = UserProfile::with('user')->where('user_id', $user_id)->first();
        $param = $request->page;
        if ($param === 'sell') {
            $items = Item::withCount('order')->where('user_id', $user_id)->get();
            return view('profile', compact('items', 'user_profile'));
        } elseif ($param === 'buy') {
            $items = Item::whereHas('order', function ($q) use ($user_id) {
                $q->where('user_id', $user_id);
            })->get();
            return view('profile', compact('items', 'user_profile'));
        }
    }

    public function edit()
    {
        $user_profile = UserProfile::with('user')->where('user_id', auth()->id())->first();
        return view('profile-edit', compact('user_profile'));
    }

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();
        $user_profile = UserProfile::where('user_id', $user->id)->first();
        $path = $user_profile ? $user_profile->avatar : null;

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');

            if ($user_profile && $user_profile->avatar) {
                Storage::disk('public')->delete($user_profile->avatar);
            }
        }
        $param = $request->only('post_code', 'address', 'building');
        $param['user_id'] = $user->id;
        $param['avatar'] = $path;
        if (!$user_profile) {
            UserProfile::create($param);
        } else {
            $user_profile->update($param);
        }

        $user = User::find($user->id);
        $user->update(['name' => $request->name]);
        return redirect('/');
    }
}
