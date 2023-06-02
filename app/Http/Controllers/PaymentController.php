<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Token;
use App\Models\Products;
use Auth;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        // Retrieve product details and payment information from the request
        $productId = $request->input('product_id');
        $paymentAmount = 1000; // Assuming the payment amount is $10.00 (in cents)
        $paymentCurrency = 'usd';
        try {
            // Set up Stripe with your secret key
            Stripe::setApiKey('sk_test_51NE8YlSIS5dQ7W0Teb3z112mYJcyrK2rXy5bF1laBbw8FwvhEbNefUES7XcyzblH1p6jF2gpP3qUmevHqTcmfvFQ00uiHyai6o');

            $paymentMethod = \Stripe\PaymentMethod::create([
                'type' => 'card',
                'card' => [
                    'token' => $request->stripe_token,
                ],
            ]);
            
            // Create a new Stripe charge
            $charge = PaymentIntent::create([
                'amount' => $paymentAmount,
                'currency' => $paymentCurrency,
                'description' => 'Charge for product ID: ' . $productId,
                'payment_method' => $paymentMethod,
                'confirm' => false,
            ]);
            // Handle the successful charge and return a response to the user
            // You can update your database, send order confirmation, etc.
            if ($charge->status === 'requires_confirmation') {
                // Additional authentication is required
                return view('authenticate_payment', [
                    'clientSecret' => $charge->client_secret,
                ]);
            } elseif ($charge->status === 'succeeded') {
                // Payment succeeded
                // You can update your database, send order confirmation, etc.
                return response()->json(['message' => 'Payment successful!']);
            } else {
                // Handle other status as needed
                return response()->json(['message' => 'Payment failed.'], 400);
            }
        } catch (CardException $e) {
            // Handle card errors (e.g., insufficient funds, card declined)
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function checkout()
    {
        Stripe::setApiKey('sk_test_51NE8YlSIS5dQ7W0Teb3z112mYJcyrK2rXy5bF1laBbw8FwvhEbNefUES7XcyzblH1p6jF2gpP3qUmevHqTcmfvFQ00uiHyai6o');

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'inr',
                        'product_data' => [
                            'name' => "test",
                        ],
                        'unit_amount' => 500,
                    ],
                        'quantity' => 1
                ]
            ],
            'mode' => 'payment',
            'success_url' => 'https://google.com'
        ]);

        return redirect()->away($session->url);
    }

    public function charge(Request $request)
    {
        $product = Products::getProductDetails(base64_decode($request->product_id));
        $amount = $product->Price * 100;
        $paymentMethod = $request->payment_method;
        $user = Auth::user();
        try
        {
            //Creating the user in stripe or getting the already existing stripe user
            $user->createOrGetStripeCustomer();
            //adding payment method
            $paymentMethod = $user->addPaymentMethod($paymentMethod);
            $charge = $user->charge($amount, $paymentMethod->id, ['off_session' => true]);
        } catch(\Exception $e)
        {
            return redirect('payment/failure/'. base64_encode($product->id));
        }
        
        return redirect('payment/success');

    }

    public function success()
    {
        return view('payment_success');
    }

    public function failure(Request $request)
    {
        return view('payment_failure', ['product_id' => base64_decode($request->id)]);
    }
}
