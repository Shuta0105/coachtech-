<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function createCheckoutSession(Request $request, $itemId)
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
            'success_url' => env('APP_URL'),
            // 'cancel_url' => route('purchase.cancel', ['item' => $item->id]),
            'metadata' => [
                'item_id' => $itemId,
                'user_id' => auth()->id(),
            ]
            ]);

            return response()->json(['id' => $session->id]);
    }
}
