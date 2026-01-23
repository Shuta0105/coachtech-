<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Mail\PurchaseItemForBuyerMail;
use App\Mail\PurchaseItemForSellerMail;
use App\Models\Order;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class StripeController extends Controller
{
    public function createSession(PurchaseRequest $request, $itemId, StripeService $stripe)
    {
        $session = $stripe->createCheckoutSession($request, $itemId);

        return response()->json([
            'id' => $session->id,
        ]);
    }

    public function order(Request $request, StripeService $stripe)
    {
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
    }
}
