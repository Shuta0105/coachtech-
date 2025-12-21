<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ItemController::class, 'index']);
Route::get('/items/search', [ItemController::class, 'search']);
Route::get('/item/{item_id}', [ItemController::class, 'detail']);
Route::post('/comment/{item_id}', [ItemController::class, 'comment']);

Route::get('/purchase/{item_id}', [ItemController::class, 'purchase']);
Route::post('/purchase/{item_id}', [ItemController::class, 'order']);
Route::get('/purchase/address/{item_id}', [ItemController::class, 'address']);
Route::post('/purchase/address/{item_id}', [ItemController::class, 'update']);

Route::post('/create-checkout-session/{itemId}', [StripeController::class, 'createCheckoutSession']);

Route::get('/sell', [ItemController::class, 'sell']);
Route::post('/sell', [ItemController::class, 'store']);

Route::post('/like/{item}', [LikeController::class, 'toggle']);

Route::get('/mypage', [UserController::class, 'index']);
Route::get('/mypage/profile', [UserController::class, 'edit']);
Route::post('/mypage/profile', [UserController::class, 'update']);

Route::get('/verify', function () {
    return view('auth.verify-email');
});
Route::post('/email/verification-notification', function () {
    request()->user()->sendEmailVerificationNotification();

    return back();
});