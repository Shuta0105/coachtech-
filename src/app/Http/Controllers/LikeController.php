<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Exception;

class LikeController extends Controller
{
    /**
     * 商品に対する「いいね」の切り替え処理
     * @param Item $item
     * @return Illuminate\Http\JsonResponse
     */
    public function toggle(Item $item)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return redirect('/login');
            }

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
        } catch (Exception $e) {
            return view('error');
        }
    }
}
