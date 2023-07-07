<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Gateway\Classess\InstamojoApi;
use App\Http\Controllers\User\DepositController;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Instamojo{

    public function initiate($request,$payment_data,$type)
    {
        $payment_amount = $payment_data['amount'];
        $status = 0;
        $message = '';
        $url     = '';
        $data = PaymentGateway::whereKeyword('instamojo')->first();
        
        $paydata = $data->convertAutoData();
            if($paydata['sandbox_check'] == 1){
                $api = new InstamojoApi($paydata['key'], $paydata['token'], 'https://test.instamojo.com/api/1.1/');
            }
            else {
                $api = new InstamojoApi($paydata['key'], $paydata['token']);
            }
    
            try {
                $response = $api->paymentRequestCreate(array(
                    "purpose" => $type,
                    "amount" => $payment_amount,
                    "send_email" => false,
                    "email" => Auth::check() ? Auth::user()->email : '',
                    "redirect_url" => route('notify.instamojo')
                ));

                $redirect_url = $response['longurl'];
                /** add payment ID to session **/
                Session::put('order_payment_id', $response['id']);
                Session::put('type', $type);
                $url = $redirect_url;
                $status = 1;
            }
            catch (Exception $e) {
               $message = 'Error: ' . $e->getMessage();
            }

            return ['status' => $status , 'message' => $message , 'url' => $url];
    }

    public function notify(Request $request){
       $status = 0;
       $message = '';
       $txn_id = '';
    
       if(!$request->payment_id){
        $message = __('Payment Field');
       }
       $pay_id = Session::get('order_payment_id');
       if($pay_id == $request->payment_request_id){
           $txn_id = $request->payment_request_id;
           $status = 1;
       }else{
           $message = __('Payment Field');
       }

       switch (Session::get('type')) {
        case 'deposit':
            return (new DepositController)->notifyOperation(['message' => $message , 'status' => $status , 'txn_id' => $txn_id]) ;
            break;
        
        default:
            dd('wrong');
        break;
    }
    }
}