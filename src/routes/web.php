<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

Route::middleware('cache.headers:no_store;max_age=0')->group(function () {
    Route::get('/', [ItemController::class, 'index']);
    Route::get('/item/{item_id}', [ItemController::class, 'detail']);
});

Route::middleware(['auth', 'cache.headers:no_store;max_age=0'])->group(function () {
    Route::post('/comment/{item_id}', [ItemController::class, 'comment']);

    Route::get('/purchase/{item_id}', [ItemController::class, 'purchase']);
    Route::get('/purchase/address/{item_id}', [ItemController::class, 'address']);
    Route::post('/purchase/address/{item_id}', [ItemController::class, 'update']);

    Route::post('/create-session/{itemId}', [StripeController::class, 'createSession']);
    Route::get('/stripe/order', [StripeController::class, 'order'])->name('stripe.order');

    Route::get('/sell', [ItemController::class, 'sell']);
    Route::post('/sell', [ItemController::class, 'store']);

    Route::post('/like/{item}', [LikeController::class, 'toggle']);

    Route::get('/mypage', [UserController::class, 'index']);
    Route::get('/mypage/profile', [UserController::class, 'edit']);
    Route::post('/mypage/profile', [UserController::class, 'update']);

    Route::get('/update/shipping/status/{item_id}', [OrderController::class, 'index']);
    Route::post('/update/shipping/status/{order_id}', [OrderController::class, 'update']);
    Route::get('/shipping/status/{item_id}', [OrderController::class, 'detail']);
});

Route::get('/verify', function () {
    return view('auth.verify-email');
});
Route::post('/email/verification-notification', function () {
    request()->user()->sendEmailVerificationNotification();
    return back();
});
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    $user = $request->user();

    if ($user->last_login_at === null) {
        return redirect('/mypage/profile');
    }

    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::get('/error', function () {
    return view('error');
});
