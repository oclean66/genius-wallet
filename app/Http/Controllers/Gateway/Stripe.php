<?php 

namespace App\Http\Controllers\Gateway;

use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Config;
use Cartalyst\Stripe\Laravel\Facades\Stripe as PaymentStripe;
use Illuminate\Support\Str;

class Stripe {

    public static function initiate($request,$payment_data,$type)
    {

        // SERIALIZE DATA
        $payment_amount = $payment_data['amount'];
        $message = '';
        $status  = 0;
        $txn_id  = '';
        $data = PaymentGateway::whereKeyword('stripe')->first();
        $paydata = $data->convertAutoData();

        Config::set('services.stripe.key', $paydata['key']);
        Config::set('services.stripe.secret', $paydata['secret']);
        $stripe = PaymentStripe::make(Config::get('services.stripe.secret'));
          
            try{
                $token = $stripe->tokens()->create([
                    'card' =>[
                            'number' => $request->card_number,
                            'exp_month' => $request->month,
                            'exp_year' => $request->year,
                            'cvc' => $request->cvc,
                        ],
                    ]);

                 
                if (!isset($token['id'])) {
                    $message = __('Token Problem With Your Token.');
                }

                $charge = $stripe->charges()->create([
                    'card' => $token['id'],
                    'currency' => getCurrencyCode(),
                    'amount' => $payment_amount,
                    'description' => 'Pay Via Stripe',
                ]);

            if ($charge['status'] == 'succeeded') {
                $status = 1;
                $txn_id = $charge['balance_transaction'];
            }           
                
            }catch (Exception $e){
                $message = $e->getMessage();
            }catch (\Cartalyst\Stripe\Exception\CardErrorException $e){
                $message = $e->getMessage();
            }catch (\Cartalyst\Stripe\Exception\MissingParameterException $e){
                $message = $e->getMessage();
            }

            return ['status' => $status, 'txn_id' => $txn_id,'message'=>$message];
    }
}