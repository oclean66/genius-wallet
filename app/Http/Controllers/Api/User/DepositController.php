<?php
namespace  App\Http\Controllers\Api\User;

use App\Models\Wallet;
use App\Models\Deposit;
use App\Models\Currency;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use App\Models\ApiPaymentProcess;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;

class DepositController extends ApiController {

    public function index()
    {
        Session::forget('deposit_data');
        $deposits = Deposit::where('user_id',auth()->id())->where('user_type',1)->latest()->take(7)->get();
      
        return $this->sendResponse([
            'wallets' =>  Currency::where('status',1)->get(),
            'recent_deposits' => $deposits
        ],'Money deposit');
    }

    public function methods(Request $request)
    {
        $methods = PaymentGateway::whereJsonContains('currency_id',$request->currency_id)->where('status',1)->get([
            'id','name','fixed_charge','percent_charge','details','type'
        ]);
       
        if($methods->isEmpty()){
            return $this->sendError('Error',['No methods found associate with this currency']);
        }
        return $this->sendResponse(['methods' => $methods],'Payment methods');
    }
    
    public function depositProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'wallet_id' => 'required|numeric',
            'method_id' => 'required|numeric',
            'user_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error', $validator->errors());
        }

      $wallet = Wallet::where('user_id',auth()->id())->where('user_type',1)->where('currency_id',$request->wallet_id)->first();
      
        if(!$wallet){
            $wallet =  Wallet::create([
                'user_id'     => auth()->id(),
                'user_type'   => 1,
                'currency_id' => $request->wallet_id,
                'balance'     => 0
            ]);
        }

        $method = PaymentGateway::find($request->method_id);
        if(!$method){
            return $this->sendError('Error',['Method not found']);
        }

        $payment = new ApiPaymentProcess();
        $payment->pay_id = (string) Str::uuid();
        $payment->amount = $request->amount;
        $payment->wallet_id = $request->wallet_id;
        $payment->method_id = $request->method_id;
        $payment->user_id = $request->user_id;
        $payment->currency_id = $wallet->currency_id;
        $payment->save();

        return $this->sendResponse(['url' => route('payment-init',['deposit_id' => $payment->pay_id])],'deposit-init');
    }


    public function dipositHistory()
    {
        $deposits = Deposit::where('user_id',auth()->id())->where('user_type',1)->latest()->paginate(15);
        return view('user.deposit.history',[
            'deposits' => $deposits
        ]);
    }
    
    

    public function depositSubmit(Request $request)
    {

        $validator = Validator::make($request->all(),['amount'=>'required|gt:0','curr_code'=>'required','gateway_id'=>'required']);

        if($validator->fails()){
            return $this->sendError('Validation Error',$validator->errors());
        }

        $currency = Currency::where('code',$request->curr_code)->first();
        if(!$currency){
            return $this->sendError('Error',['Currency not found']);
        }

        $gateway = PaymentGateway::find($request->gateway_id);
        if(!$gateway){
            return $this->sendError('Error',['Gateway not found']);
        }

        $charge = null;
        
        if($gateway->type == 'manual'){
            $charge = $gateway->fixed_charge + (($request->amount * $gateway->percent_charge)/100);
            $totalAmount =  $request->amount + $charge; 
            $request['amount'] = $totalAmount;
            $request['charge'] = $charge;
        }

        $input = $request->merge(['keyword' => $gateway->keyword])->except('_token');
        $input['charge'] = $charge;
        $input['currency_id'] = $currency->id;
       
        unset($input['curr_code']);
        unset($input['keyword']);
   
        return $this->sendResponse([
            'deposit_data' => $input,
            'webview_url' => route('user.payment.finalize',encrypt($input))
        ],'Payment data');
    }

}