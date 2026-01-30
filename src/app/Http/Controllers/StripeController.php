<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Mail\PurchaseItemForBuyerMail;
use App\Mail\PurchaseItemForSellerMail;
use App\Models\Order;
use App\Services\StripeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class StripeController extends Controller
{
    /**
     * Stripe購入画面の表示
     * @param PurchaseRequest $request
     * @param int $itemId
     * @param StripeService $stripe
     * @return Illuminate\Http\JsonResponse
     */
    public function createSession(PurchaseRequest $request, $itemId, StripeService $stripe)
    {
        try {
            $session = $stripe->createCheckoutSession($request, $itemId);

            return response()->json([
                'id' => $session->id,
            ]);
        } catch (Exception $e) {
            return view('error');
        }
    }

    /**
     * Stripe決済完了後に商品注文を確定する
     * @param Request $request
     * @param StripeService $stripe
     * @return \Illuminate\Http\RedirectResponse
     */
    public function order(Request $request, StripeService $stripe)
    {
        try {
            $sessionId = $request->query('session_id');

            if (! $sessionId) {
                return redirect('/');
            }

            $session = $stripe->retrieveSession($sessionId);

            if ($session->payment_status !== 'paid') {
                return redirect('/');
            }

            $order = Order::create([
                'user_id' => $session->metadata->user_id,
                'item_id' => $session->metadata->item_id,
                'paymethod' => $session->metadata->paymethod,
                'post_code' => $session->metadata->post_code,
                'address' => $session->metadata->address,
                'building' => $session->metadata->building
            ]);

            if ($order->user) {
                Mail::to($order->user->email)
                    ->send(new PurchaseItemForBuyerMail($order));
            }

            if ($order->item && $order->item->user) {
                Mail::to($order->item->user->email)
                    ->send(new PurchaseItemForSellerMail($order));
            }

            return redirect('/');
        } catch (Exception $e) {
            return view('error');
        }
    }
}
