<?php
namespace App\Http\Controllers\Gateway;

use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Auth;
use MercadoPago as MercadoPagoApi;
class Mercadopago {

    public function initiate($request,$payment_data,$type)
    {
     
        $status = 0;
        $message = '';
        $txn_id  = '';

        $payment_amount = $payment_data['amount'];
        $data = PaymentGateway::whereKeyword('mercadopago')->first();
        $paydata = $data->convertAutoData();
        MercadoPagoApi\SDK::setAccessToken($paydata['token']);
        $payment = new MercadoPagoApi\Payment();
        $payment->transaction_amount = $payment_amount;
        $payment->token = $request->token;
        $payment->description = $type;
        $payment->installments = 1;
        $payment->payer = array(
        "email" => Auth::check() ? Auth::user()->email : 'example@gmail.com'
        );
        $payment->save();
  
        if ($payment->status == 'approved') {
            $status = 1;
            $txn_id = $payment->id;
        }else{
            $message = __('Payment Fail');
        }

        return ['status' => $status,'message' => $message , 'txn_id' => $txn_id];
    }
}