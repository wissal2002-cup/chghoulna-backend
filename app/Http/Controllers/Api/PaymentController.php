<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

 public function checkout(Request $request)
{
    try {
        $request->validate([
            'service' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'success_url' => 'required|url',
            'cancel_url' => 'required|url',
        ]);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'mad',
                    'product_data' => ['name' => $request->service],
                    'unit_amount' => $request->amount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $request->success_url,
            'cancel_url' => $request->cancel_url,
        ]);

        return response()->json(['url' => $session->url]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Payment error',
            'message' => $e->getMessage()
        ], 500);
    }
}

}
