<?php


namespace App\Http\Controllers\Gateway;

use App\{
    Models\PaymentGateway
};
use App\Http\Controllers\Controller;
use App\Models\Generalsetting;

use PayPal\{
    Api\Item,
    Api\Payer,
    Api\Amount,
    Api\Payment,
    Api\ItemList,
    Rest\ApiContext,
    Api\Transaction,
    Api\RedirectUrls,
    Api\PaymentExecution,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\User\DepositController;

class Paypal extends Controller
{


    public static function initiate($request,$payment_data,$type)
    {
        
        $payment_amount = $payment_data['amount'];

        $cancel_url = '';
        switch ($type) {
            case 'deposit':
                Session::put('type',$type);
                $cancel_url = route('user.deposit.submit');
                break;
            
            default:
                # code...
                break;
        }
        
        $data = PaymentGateway::whereKeyword('paypal')->first();
        $paydata = $data->convertAutoData();
        $paypal_conf['settings']['mode'] = $paydata['sandbox_check'] == 1 ? 'sandbox' : 'live';
        $api_context = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $paydata['client_id'],
                $paydata['client_secret']   
            )
        );
        $api_context->setConfig($paypal_conf['settings']);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName('Payment Via Paypal') /** item name **/
            ->setCurrency(getCurrencyCode())
            ->setQuantity(1)
            ->setPrice($payment_amount); /** unit price **/
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency(getCurrencyCode())
            ->setTotal($payment_amount);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Payment Via Paypal');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(route('paypal.notify')) /** Specify return URL **/
            ->setCancelUrl($cancel_url);
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
     
        try {
            $payment->create($api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            $message = $ex->getMessage();
            $status  = 0;
            $txn_id  = '';
            return ['status' => $status, 'txn_id' => $txn_id,'message'=>$message];
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        Session::put('order_payment_id', $payment->getId());
        return ['status' => 1,'url'=> $redirect_url];

    }

    public function notify(Request $request)
    {
        
        $message = '';
        $status  = 0;
        $txn_id  = ''; 
        $payment_id = Session::get('order_payment_id');
        
        /** clear the session payment ID **/
        if (empty( $request['PayerID']) || empty( $request['token'])) {
            $message = __('Payment Field');
        } 

        $data = PaymentGateway::whereKeyword('paypal')->first();
        $paydata = $data->convertAutoData();
        
        $paypal_conf['settings']['mode'] = $paydata['sandbox_check'] == 1 ? 'sandbox' : 'live';
        $api_context = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $paydata['client_id'],
                $paydata['client_secret']   
            )
        );
        $api_context->setConfig($paypal_conf['settings']);

        $payment = Payment::get($payment_id, $api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request['PayerID']);
        /**Execute the payment **/
        $result = $payment->execute($execution, $api_context);
        if ($result->getState() == 'approved') {
            $resp = json_decode($payment, true);
            $txn_id = $resp['transactions'][0]['related_resources'][0]['sale']['id'];
            $status = 1;
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