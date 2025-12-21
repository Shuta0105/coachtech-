<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Like;

class LikeController extends Controller
{
    public function toggle(Item $item)
    {
        $user = auth()->user();

        if ($item->likedBy($user)) {
            $item->likes()->where('user_id', $user->id)->delete();
            $liked = false;
        } else {
            $item->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $item->likes()->count(),
        ]);
    }
}
