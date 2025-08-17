<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Auth;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Token;

class stripeController extends Controller
{
    public function charge(Request $data, $id)
    {
        if (Auth::user()->account_status === 1) {
            if ($data->expectsJson()) {

                return response()->json(['error' => 'Please Contact Admin Your Account Is Suspended !'], 500);
            }

            return redirect()->back()->with('error', 'Please Contact Admin Your Account Is Suspended !');
        }

        if ($id != 0) {
            
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            header('Content-Type: application/json');

            $checkout_session = \Stripe\Checkout\Session::create([
              'line_items' => [[
                
                'price' => $data->price_id,
                'quantity' => 1,
              ]],
              'mode' => 'subscription',
              'success_url' => route('stripe.success', $id),
              'cancel_url' =>  route('stripe.cancel', $id)
            ]);


            // $subscriptionId = $checkout_session->subscription;
            // $subscription = \Stripe\Subscription::retrieve($subscriptionId);

            // $priceId = $subscription->items->data[0]->price;
            // $price = \Stripe\Price::retrieve($priceId);

            // $latestInvoiceId = $subscription->latest_invoice;
            // $latest_invoice = \Stripe\Invoice::retrieve($latestInvoiceId);

            // $packageName = $price->product;
            // $packageAmount = $price->unit_amount;
            // $packageStart = $subscription->current_period_start;
            // $packageEnd = $subscription->current_period_end;
            // $packageStatus = $subscription->status;

            

            return redirect()->to($checkout_session->url); 
        }

        return redirect()->back()->with('error','Package not subscriped Please try again !!');

    }

    public function success(Request $request, $id)
    {
        $package_start = date('Y-m-d');

        // Add 30 days to the current date
        $package_end = date('Y-m-d', strtotime($package_start . ' +30 days'));

        $user_id = Auth::id();

        $user = DB::table('users')->where('id', $user_id)->update([

            'package_id' => $id,
            'package_start' => $package_start,
            'package_end' => $package_end,
            'payment_status' => 1,
            'updated_at' => now()

        ]);

        return redirect()->route('packages')->with('success','Package subscripe successfully.'); 
    }

    public function cancel($id)
    {
        return redirect()->route('packages')->with('error','Package not subscriped Please try again !!'); 
    }

    public function handleWebhook(Request $resquest)
    {
        
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                config('services.stripe.webhook_secret')
            );
        } catch (\UnexpectedValueException $e) {
            
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        
        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object; 

            
            $subscriptionId = $session->subscription;
            $customerId = $session->customer;

            
            $subscription = \Stripe\Subscription::retrieve($subscriptionId);
            $package_start = $subscription->current_period_start;
            $package_end = $subscription->current_period_end;
            $payment_status = $subscription->status;
            $transaction_id = $session->payment_intent;

            $user_id = Auth::id();

            $user = DB::table('users')->where('id', $user_id)->update([

                'package_id' => 2,
                'package_start' => $package_start,
                'package_end' => $package_end,
                'payment_status' => $payment_status,
                'transaction_id' => $transaction_id,
                'updated_at' => now()

            ]);

        }

        return response()->json(['status' => 'success']);
    
    }
}
