<?php 

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\DepositController;
use App\Traits\Paytm as TraitsPaytm;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
class Paytm  extends Controller{

    use TraitsPaytm;

    public static function initiate($request,$payment_data,$type)
    {
        $payment_amount = $payment_data['amount'];
	    $data_for_request = self::handlePaytmRequest(Str::random(8), $payment_amount, $type );
	    $paytm_txn_url = 'https://securegw-stage.paytm.in/theia/processTransaction';
	    $paramList = $data_for_request['paramList'];
        $checkSum = $data_for_request['checkSum'];
        $status = 1;
        $view = 'other.paytm-merchant-form';
        $prams   = ['paytm_txn_url' => $paytm_txn_url, 'paramList' => $paramList ,'checkSum' => $checkSum ];
	    return ['status' => $status , 'view' => $view , 'prams' => $prams];
    }


    public function notify(Request $request ) {
	    $status = 0;
        $message = '';
        $txn_id = '';
		if ( 'TXN_SUCCESS' === $request['STATUS'] ) {
			$transaction_id = $request['TXNID'];
            $status = 1;
            $txn_id = $transaction_id;
		} else if( 'TXN_FAILURE' === $request['STATUS'] ){
            $message = __('Payment Field Please Try again');
		}
        return (new DepositController)->notifyOperation(['message' => $message , 'status' => $status , 'txn_id' => $txn_id]) ;
        
    }

}