<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function createCheckoutSession(PurchaseRequest $request, $itemId)
    {
        $item = Item::findOrFail($itemId);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card', 'konbini'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',

            'metadata' => [
                'user_id' => auth()->id(),
                'item_id' => $item->id,
                'paymethod' => $request->paymethod,
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building' => $request->building
            ],

            'success_url' => route('stripe.order') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => url('/purchase/' . $item->id),
        ]);

        return response()->json(['id' => $session->id]);
    }

    public function order(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (! $sessionId) {
            return redirect('/');
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::retrieve($sessionId);

        if ($session->payment_status === 'paid') {
            Order::create([
                'user_id' => $session->metadata->user_id,
                'item_id' => $session->metadata->item_id,
                'paymethod' => $session->metadata->paymethod,
                'post_code' => $session->metadata->post_code,
                'address' => $session->metadata->address,
                'building' => $session->metadata->building
            ]);
        }
        return redirect('/');
    }
}
