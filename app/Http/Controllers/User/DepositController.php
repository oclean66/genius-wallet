<?php
namespace  App\Http\Controllers\User;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Deposit;
use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use App\Models\ApiPaymentProcess;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DepositController extends Controller {

    public function invoice() {
        if(session('invoice')){
            try {
                return decrypt(session('invoice'));
            } catch (\Throwable $th) {
               return false;
            }
        }
    }

    public function invoicePayment()
    {
        $invoice = $this->invoice();
        $methods = PaymentGateway::whereJsonContains('currency_id',"$invoice->currency_id")->where('status',1)->get();
      
        if($methods->isEmpty()){
            return back()->with('error','No gateways found associate with this currency.');
        }

        return view('user.invoice.gateway_payment',compact('invoice','methods'));
    }
    

    public function index()
    {
        Session::forget('deposit_data');
        $deposits = Deposit::where('user_id',auth()->id())->where('user_type',1)->latest()->take(7)->get();
        return view('user.deposit.index',[
            'gatewayes' =>  PaymentGateway::whereStatus(1)->get(),
            'wallets' =>  Currency::where('status',1)->get(),
            'deposits' => $deposits
        ]);
    }

    public function methods(Request $request)
    {
        $methods = PaymentGateway::whereJsonContains('currency_id',$request->currency)->where('status',1)->get();
        if($methods->isEmpty()){
            return response('empty');
        }
        return response($methods);
    }

    public function dipositHistory()
    {
        $deposits = Deposit::where('user_id',auth()->id())->where('user_type',1)->latest()->paginate(15);
        return view('user.deposit.history',[
            'deposits' => $deposits
        ]);
    }

    public function paymentInit(Request $request)
    {
        $payment = ApiPaymentProcess::where('pay_id',$request->deposit_id)->first();
      
        if(!$payment){
            return back()->with('error','Payment not found');
        }

        $user = User::findOrFail($payment->user_id);
        Auth::guard('web')->loginUsingId($user->id);

        $gateway = PaymentGateway::findOrFail($payment->method_id);
        $charge = 0;
        if($gateway->type == 'manual'){
            $charge = $gateway->fixed_charge + (($request->amount * $gateway->percent_charge)/100);
        }
        $totalAmount =  $payment->amount + $charge; 
        $currency = Currency::findOrFail($payment->currency_id);
        session()->put('api_depo',$payment->pay_id);
        return view('user.deposit.api_payment',[
            'gateway'      => $gateway,
            'currency'     => $currency,
            'payment'     => $payment,
            'totalAmount' => round($totalAmount,3),
            'charge' => round($charge,2)
        ]);
    }

    public function finalize($data)
    {
        try {
            $deposit_data = decrypt($data);
            $gateway  = PaymentGateway::findOrFail($deposit_data['gateway_id']);
            $currency = Currency::findOrFail($deposit_data['currency_id']);
            $charge   = $deposit_data['charge'];
            $deposit_data['gateway'] = $gateway->id;
            $deposit_data['wallet'] = $currency->id;
            $deposit_data['curr_code'] = $currency->code;
            $deposit_data['keyword'] = $gateway->keyword;
            Session::put('deposit_data',$deposit_data);
            Session::put('currency',$currency);

            return view('user.deposit.payment',[
                'gateway'      => $gateway,
                'deposit_data' => $deposit_data,
                'currency'     => $currency,
                'charge'       => $charge
            ]);

        } catch (\Throwable $th) {
            return back()->with('error','Something went wrong.');
        }
    }

    public function depositSubmit(Request $request)
    {
        $request->validate(['amount'=>'required|gt:0','curr_code'=>'required','gateway'=>'required']);
        $currency = Currency::where('code',$request->curr_code)->firstOrFail();
        $gateway = PaymentGateway::findOrFail($request->gateway);
        $charge = null;
        if($gateway->type == 'manual'){
            $charge = $gateway->fixed_charge + (($request->amount * $gateway->percent_charge)/100);
            $totalAmount =  $request->amount + $charge; 
            $request['amount'] = $totalAmount;
            $request['charge'] = $charge;
        }
        $input = $request->merge(['keyword' => $gateway->keyword])->except('_token');
        if($this->invoice()){
           $input['amount'] = amount($this->invoice()->final_amount,$currency->type,2);
        }
        Session::put('deposit_data',$input);
        Session::put('currency',$currency);
        
        
        return view('user.deposit.payment',[
            'gateway'      => $gateway,
            'deposit_data' => Session::get('deposit_data'),
            'currency'    => $currency,
            'charge'  => $charge
        ]);
    }


    public function balanceUpdate($user,$deposit_data)
    {
        $wallet = Wallet::where('currency_id',$deposit_data['wallet'])->where('user_id',auth()->id())->where('user_type',1)->first();
    
        if(!$wallet){
            $wallet =  Wallet::create([
                'user_id'     => auth()->id(),
                'user_type'   => 1,
                'currency_id' => session('currency')->id,
                'balance'     => 0
            ]);
        }
        $wallet->balance += $deposit_data['amount'];
        $wallet->update();

        $trnx              = new Transaction();
        $trnx->trnx        = str_rand();
        $trnx->user_id     = $user->id;
        $trnx->user_type   = 1;
        $trnx->currency_id = $wallet->currency->id;
        $trnx->wallet_id   = $wallet->id;
        $trnx->amount      = $deposit_data['amount'];
        $trnx->charge      = 0;
        $trnx->remark      = 'deposit';
        $trnx->type        = '+';
        $trnx->details     = trans('Deposit balance to wallet : '). $deposit_data['curr_code'];
        $trnx->save();
    }

    public function invoicePaymentUpdate($deposit_data,$user,$request = null)
    {   
        $invoice = $this->invoice();
       
        if($request && $request->type == 'manual'){
            $data = new Deposit();
            $data->user_info    = json_encode($user);
            $data->user_id      = $user->id;
            $data->currency_id  = session('currency')->id;
            $data->user_type    = 1;
            $data->amount       = $deposit_data['amount'];
            $data->method       = $deposit_data['gateway'];
            $data->currency_info = sessionCurrency();
            $data->status       = $request->type == 'manual' ? 'pending': 'completed';
            $data->txn_id       = str_rand();
            $data->trx_details  = $request->trx_details;
            $data->charge       = $deposit_data['charge'] ?? null;
            $data->invoice      = $invoice->id;
            $data->save();
        }
        else{

            $trnx              = new Transaction();
            $trnx->trnx        = str_rand();
            $trnx->user_id     = auth()->id();
            $trnx->user_type   = 1;
            $trnx->currency_id = $invoice->currency_id;
            $trnx->amount      = $invoice->final_amount;
            $trnx->charge      = 0;
            $trnx->remark      = 'invoice_payment';
            $trnx->invoice_num = $invoice->number;
            
            $trnx->details     = trans('Payment to invoice : '). $invoice->number;
            $trnx->save();

            $rcvWallet = Wallet::where('user_id',$invoice->user_id)->where('user_type',1)->where('currency_id',$invoice->currency_id)->first();
        
            if(!$rcvWallet){
                $rcvWallet =  Wallet::create([
                    'user_id'     => $invoice->user_id,
                    'user_type'   => 1,
                    'currency_id' => $invoice->currency_id,
                    'balance'     => 0
                ]);
            }

            $rcvWallet->balance += $invoice->get_amount;
            $rcvWallet->update();

            $rcvTrnx              = new Transaction();
            $rcvTrnx->trnx        = $trnx->trnx;
            $rcvTrnx->user_id     = $invoice->user_id;
            $rcvTrnx->user_type   = 1;
            $rcvTrnx->currency_id = $invoice->currency_id;
            $rcvTrnx->amount      = $invoice->get_amount;
            $rcvTrnx->charge      = $invoice->charge;
            $rcvTrnx->remark      = 'invoice_payment';
            $rcvTrnx->invoice_num = $invoice->number;
            $rcvTrnx->type        = '+';
            $rcvTrnx->details     = trans('Receive Payment from invoice : '). $invoice->number;
            $rcvTrnx->save();

            $invoice->payment_status = 1;
            $invoice->update();


            @mailSend('received_invoice_payment',[
                'amount' => amount($invoice->get_amount,$invoice->currency->type,2),
                'curr'   => $invoice->currency->code,
                'trnx'   => $rcvTrnx->trnx,
                'from_user' => $invoice->email,
                'inv_num'  => $invoice->number,
                'after_balance' => amount($rcvWallet->balance,$invoice->currency->type,2),
                'charge' => amount($invoice->charge,$invoice->currency->type,2),
                'date_time' => dateFormat($rcvTrnx->created_at)
            ],$invoice->user);
      }
    }


    public function depositPayment(Request $request)
    {
        $request_data = $request;
        $deposit_data = Session::get('deposit_data');
        $service =  str_replace('User','',__NAMESPACE__). 'Gateway'. '\\'. ucwords($deposit_data['keyword']);
        $deposit = $service::initiate($request_data,$deposit_data,'deposit');

        if($deposit['status'] == 1 && in_array($deposit_data['keyword'],['paytm','razorpay'])){
            return view($deposit['view'],$deposit['prams']);
        }

        if($deposit['status'] == 1 && isset($deposit['url'])){
            return redirect($deposit['url']);
        }

        try {
            if($deposit['status'] == 1){
                $user = auth()->user();
                if($this->invoice()){
                    $this->invoicePaymentUpdate($deposit_data,$user,$request);
                    $msg = __('Invoice has been paid successfully');
                }else{

                    $data = new Deposit();
                    $data->user_info    = json_encode($user);
                    $data->user_id      = $user->id;
                    $data->currency_id  = session('currency')->id;
                    $data->user_type    = 1;
                    $data->amount       = $deposit_data['amount'];
                    $data->method       = $deposit_data['gateway'];
                    $data->currency_info = sessionCurrency();
                    $data->status       = $request->type == 'manual' ? 'pending': 'completed';
                    $data->txn_id       = $deposit['txn_id'];
                    $data->trx_details  = $request->trx_details;
                    $data->charge       = $deposit_data['charge'] ?? null;
                    $data->save();
                    if($request->type != 'manual'){
                        $this->balanceUpdate($user,$deposit_data);
                        $msg = __('Your balance added successfully');
                    }else{
                        $msg = __('Your deposit request is taken. Wait for the admin approval.');
                    }
                }

                if(session('api_depo')){
                    $payment = ApiPaymentProcess::where('pay_id',session('api_depo'))->first();
                    if($payment) $payment->update(['status'=>1]);
                    session()->forget('api_depo');
                    return view('user.deposit.api_res');
                }

                Session::forget(['deposit_data','currency','invoice']);
                return redirect(route('user.deposit.index'))->with('success',$msg);
            }
        } catch (\Throwable $th) {
            return redirect(route('user.deposit.index'))->with('error',__('Somthing went wrong please try again'));
        }
        return redirect(route('user.deposit.index'))->with('error',$deposit['message']);
    }


    public function notifyOperation($deposit)
    {
        $deposit_data = Session::get('deposit_data');
         try {
            if($deposit['status'] == 1 && $deposit['txn_id']){
                $user = auth()->user();
                
                if($this->invoice()){
                    $this->invoicePaymentUpdate($deposit_data,$user);
                    $msg = __('Invoice has been paid successfully');
                }else{
                    $data = new Deposit();
                    $data->user_info = json_encode($user);
                    $data->user_id  = $user->id;
                    $data->currency_id  = session('currency')->id;
                    $data->user_type  = 1;
                    $data->amount   = $deposit_data['amount'];
                    $data->method   = $deposit_data['gateway'];
                    $data->currency_info  = sessionCurrency();
                    $data->status  = 'completed';
                    $data->txn_id  = $deposit['txn_id'];
                    $data->save();

                    $this->balanceUpdate($user,$deposit_data);
                    $msg = __('Your balance added successfully');
                }

                Session::forget('deposit_data');
                return redirect(route('user.deposit.index'))->with('success',$msg);
            }
        } catch (\Throwable $th) {
            return redirect(route('user.deposit.index'))->with('error',__('Somthing want wront please try again'));
        }
    }
}