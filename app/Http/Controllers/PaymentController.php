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
