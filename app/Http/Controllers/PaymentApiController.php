<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\ApiCreds;
use App\Models\Currency;
use App\Models\Merchant;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MerchantPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentApiController extends Controller
{
    public function paymentProcess(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'currency_code'   => 'required|string|max:4',
            'amount'          => 'required|gt:0',
            'details'         => 'required|string|max:255',
            'web_hook'        => 'required|url',
            'custom'          => 'required|string|max:20',
            'cancel_url'      => 'required|url',  
            'success_url'     => 'required|url',  
            'customer_email'  => 'required|email|max:30'
        ]);

        if($validator->fails()) {
        	return [
                'code'=> 422,
                'status' => 'error',
                'message' => $validator->errors()->all()
            ] ;
        }

        $currency = Currency::where('code',$request->currency_code)->first();
        if(!$currency){
            return [
                'code'=> 404,
                'status' => 'error',
                'message' => 'Requested currency not found.'
            ] ;
        }

        $cred = ApiCreds::where('access_key',$request->bearerToken())->first();
        if(!$cred){
            return [
                'code'=> 401,
                'status' => 'error',
                'message' => 'Invalid API credentials.'
            ] ;
        }

        $data = $request->only('amount','details','web_hook','custom','cancel_url','success_url','customer_email');
        $data['payment_id'] = Str::random(32);
        $data['currency_id'] = $currency->id;
        $data['merchant_id'] = @$cred->merchant->id;
        

        if($cred->mode == 1)  $data['mode'] = 1;
        else                  $data['mode'] = 0;
      
        try {
            MerchantPayment::create($data);
            $url = route('process.payment.auth',['payment_id' => $data['payment_id']]);
        } catch (\Exception $e) {
            return [
                "code" => 500,
                "status" => "error",
                "message"=> "Server is not responding.",
            ] ;
        }
        
        return [
            "code" => 200,
            "status" => "OK",
            'payment_id' => $data['payment_id'] ,
            "message"=> "Your payment has been processed. Please follow the URL to complete the payment.",
            "url" => $url,
        ] ;

    }

    public function processCheckOut()
    {
        $payment = MerchantPayment::where('payment_id',request('payment_id'))->where('status','!=',1)->firstOrFail();
        session()->put('payment',encrypt($payment));
        return view('merchant_payment.auth',compact('payment'));
    }

    public function authenticate(Request $request)
    {
        $request->validate(['email'=>'required|email','password'=>'required']);
        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
           return redirect(route('payment.confirm'));
        }else{
            return back()->with('error','Wrong credentials');   
        }
    }

    public function confirmPayment()
    {
        try {
            $payment = decrypt(session('payment'));
            $payment->user_id = auth()->id();
            $payment->update();

        } catch (\Throwable $th) {
            return back()->with('error','Something went wrong');
        }

        if($payment->status == 1) return back()->with('error','This transaction is already successfully completed');
    
        $charge = charge('merchant-payment');
        if($payment->mode == 1) $res = $this->livePayment($payment,$charge);
        else $res = $this->curlRequest($payment,$charge,str_rand());

        if(isset($res['error']))  return back()->with('error',$res['error']);
        
        $payment->status = 1;
        $payment->save();

        session()->forget('payment');
        Auth::logout(auth()->user());
        $params = $res['success'];

        return redirect($payment->success_url."?$params");

    }


    public function livePayment($payment,$charge)
    {
        
        $userWallet = Wallet::where('user_type',1)->where('user_id',auth()->id())->where('currency_id',$payment->currency_id)->first();
      
        if(!$userWallet) return ['error' => 'Wallet not found'];
        
        $merchant = Merchant::find($payment->merchant_id);
        if(!$merchant) return ['error' => 'Invalid merchant'];
        
        $merchantWallet = Wallet::where('user_type',2)->where('user_id',$merchant->id)->where('currency_id',$payment->currency_id)->first();
       
        if(!$merchantWallet){
            $merchantWallet = Wallet::create([
                'user_id'     => $merchant->id,
                'user_type'   => 2,
                'currency_id' => $payment->currency_id,
                'balance'     => 0
            ]);
        }

        if($payment->amount  > $userWallet->balance) return ['error' => 'Sorry! insufficient balance'];
    
        $finalCharge = chargeCalc($charge,$payment->amount,$payment->currency->rate);
        $finalAmount =  numFormat($payment->amount + $finalCharge);

        $userWallet->balance -= $payment->amount;
        $userWallet->update();

        $trnx              = new Transaction();
        $trnx->trnx        = str_rand();
        $trnx->user_id     = auth()->id();
        $trnx->user_type   = 1;
        $trnx->currency_id = $payment->currency_id;
        $trnx->amount      = $payment->amount;
        $trnx->charge      = 0;
        $trnx->remark      = 'merchant_api_payment';
        $trnx->type        = '-';
        $trnx->details     = trans('Payment to merchant : ').  $merchant->email;
        $trnx->save();

        $merchantWallet->balance += $finalAmount;
        $merchantWallet->update();

        $receiverTrnx              = new Transaction();
        $receiverTrnx->trnx        = $trnx->trnx;
        $receiverTrnx->user_id     = $merchant->id;
        $receiverTrnx->user_type   = 2;
        $receiverTrnx->currency_id = $payment->currency_id;
        $receiverTrnx->amount      = $finalAmount;
        $receiverTrnx->charge      = $finalCharge;
        $receiverTrnx->type        = '+';
        $receiverTrnx->remark      = 'merchant_api_payment';
        $receiverTrnx->details     = trans('Payment received from : '). auth()->user()->email;
        $receiverTrnx->save();

        try {
            @mailSend('api_payment_user',[
                'amount'    => amount($payment->amount,$payment->currency->type,2),
                'curr'      => $payment->currency->code,
                'merchant'  => $merchant->business_name,
                'trnx'      => $receiverTrnx->trnx,
                'date_time' => dateFormat($receiverTrnx->created_at)
            ],auth()->user());

            @mailSend('api_payment_merchant',[
                'amount'    => amount($finalAmount,$payment->currency->type,2),
                'curr'      => $payment->currency->code,
                'user'      => auth()->user(),
                'trnx'      => $receiverTrnx->trnx,
                'charge'    => amount($finalCharge,$payment->currency->type,2),
                'date_time' => dateFormat($receiverTrnx->created_at)
            ], $merchant);

        } catch (\Throwable $th) {
           
        }

       $res = $this->curlRequest($payment,$charge,$trnx->trnx);
       return $res;
    }


    public function curlRequest($payment,$charge,$trnx)
    {
        $params = [
            'code'             =>  200,
            'status'           =>  'OK',
            'payment_id'       =>  $payment->payment_id,
            'transaction'      =>  $trnx,
            'amount'           =>  amount($payment->amount,$payment->currency->type,3),
            'charge'           =>  chargeCalc($charge,$payment->amount,$payment->currency->rate),
            'currency'         =>  $payment->currency->code,
            'custom'           =>  $payment->custom,
            'date'             =>  dateFormat($payment->updated_at,'d-m-Y')
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $payment->web_hook);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
        return ['success' => http_build_query($params)];
    }

    public function checkValidity(Request $request)
    {
        $cred = ApiCreds::where('access_key',$request->bearerToken())->first();
        if(!$cred){
            return [
                'code'=> 401,
                'status' => 'error',
                'message' => 'Invalid API credentials.'
            ] ;
        }

        $payment = MerchantPayment::where('payment_id',$request->payment_id)->first();
        if($payment && $payment->status == 1){
            return [
                'code'=> 200,
                'status' => 'OK',
                'message' => 'Transaction is valid'
            ];
        }
        return [
            'code'=> 404,
            'status' => 'error',
            'message' => 'Transaction Not Found.'
        ];
    }
}
