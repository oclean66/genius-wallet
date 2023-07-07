<?php

namespace App\Http\Controllers\User;

use App\Models\Agent;
use App\Models\Wallet;
use App\Models\Currency;
use App\Models\Withdraw;
use App\Models\Transaction;
use App\Models\Withdrawals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class WithdrawalController extends Controller
{
    public function withdrawForm()
    {
        $wallets = Wallet::where('user_id',auth()->id())->where('user_type',1)->where('balance', '>', 0)->get();
        return view('user.withdraw.withdraw_form',compact('wallets'));
    }

    public function methods(Request $request)
    {
        $methods = Withdraw::where('currency_id',$request->currency)->where('status',1)->get();
        if($methods->isEmpty()){
            return response('empty');
        }
        return response($methods);
    }

    public function withdrawSubmit(Request $request)
    {
        $request->validate([
            'amount'    => 'required|numeric|gt:0',
            'wallet_id' => 'required',
            'method_id' => 'required',
            'user_data' => 'required'
        ]);

        $method = Withdraw::findOrFail($request->method_id);
        if(!$method){
            return back()->with('error','Withdraw method not found');
        }

        $wallet = Wallet::where('id',$request->wallet_id)->where('user_type',1)->first();
        if(!$wallet){
            return back()->with('error','Wallet not found');
        }

        if($request->amount < $method->min_amount || $request->amount > $method->max_amount){
            return back()->with('error','Please follow the limit');
        }

        $charge = chargeCalc($method,$request->amount);
        $finalAmount = numFormat($request->amount + $charge);

        if($wallet->balance < $finalAmount){
            return back()->with('error','Insufficient balance');
        }

        $wallet->balance -=  $finalAmount;
        $wallet->save();
        
        $withdraw              = new Withdrawals();
        $withdraw->trx         = str_rand();
        $withdraw->user_id     = auth()->id();
        $withdraw->method_id   = $method->id;
        $withdraw->currency_id = $wallet->currency_id;
        $withdraw->amount      = $request->amount;
        $withdraw->charge      = $charge;
        $withdraw->total_amount= $finalAmount;
        $withdraw->user_data   = $request->user_data;
        $withdraw->save();

        return back()->with('success','Withdraw request has been submitted successfully.');
    }

    public function history()
    {
        $withdrawals = Withdrawals::where('user_id',auth()->id())->latest()->paginate(15);
        return view('user.withdraw.history',compact('withdrawals'));
    }

    public function checkAgent(Request $request){
        $receiver['data'] = Agent::where('email',$request->receiver)->first();
        if($receiver['data']){
            return response($receiver);
        }
        return response(null);
    }

    public function cashOutForm()
    {
        $wallets = Wallet::where('user_id',auth()->id())->where('user_type',1)->where('balance', '>', 0)->get();
        $charge = charge('cashout');
        return view('user.withdraw.cashout',compact('wallets','charge'));
    }
    
    public function cashOut(Request $request)
    {
        $request->validate([
            'receiver'  => 'required|email',
            'wallet_id' => 'required|integer',
            'amount'    => 'required|numeric|gt:0' 
        ],
        [
            'wallet_id.required' => 'Wallet is required'
        ]);

      
        $agent = Agent::where('email',$request->receiver)->first();
        if(!$agent) return back()->with('error','Agent not found');
        
        $senderWallet = Wallet::where('id',$request->wallet_id)->where('user_type',1)->where('user_id',auth()->id())->first();
        if(!$senderWallet) return back()->with('error','Your wallet not found');

        $currency = Currency::findOrFail($senderWallet->currency->id);

        $charge = charge('cashout');

        if($charge->daily_limit != 0 && auth()->user()->cashOutDailyLimit() >= $charge->daily_limit){
            return back()->with('error','Your daily cashout limit has been reached');
        }

        if($charge->monthly_limit != 0 && auth()->user()->cashOutMonthlyLimit() >= $charge->monthly_limit){
            return back()->with('error','Your monthly cashout limit has been reached');
        }

        if(($charge->minimum *  $currency->rate) > $request->amount || ($charge->maximum *  $currency->rate) < $request->amount){
            return back()->with('error','Please follow the limit');
        }

        $agentWallet = Wallet::where('currency_id',$currency->id)->where('user_type',3)->where('user_id',$agent->id)->first();

        if(!$agentWallet){
            $agentWallet = Wallet::create([
                'user_id'     => $agent->id,
                'user_type'   => 3,
                'currency_id' => $currency->id,
                'balance'     => 0
            ]);
        }

        $finalCharge = amount(chargeCalc($charge,$request->amount,$currency->rate),$currency->type);
        $finalAmount =  amount($request->amount + $finalCharge, $currency->type);
    
        if($senderWallet->balance < $finalAmount) return back()->with('error','Insufficient balance.');
        
        $senderWallet->balance -= $finalAmount;
        $senderWallet->update();

        $trnx              = new Transaction();
        $trnx->trnx        = str_rand();
        $trnx->user_id     = auth()->id();
        $trnx->user_type   = 1;
        $trnx->currency_id = $currency->id;
        $trnx->wallet_id   = $senderWallet->id;
        $trnx->amount      = $request->amount;
        $trnx->charge      = $finalCharge;
        $trnx->remark      = 'cash_out';
        $trnx->type        = '-';
        $trnx->details     = trans('Cash out to '). $agent->email;
        $trnx->save();

        $agentWallet->balance += $request->amount;
        $agentWallet->update();

        $receiverTrnx              = new Transaction();
        $receiverTrnx->trnx        = $trnx->trnx;
        $receiverTrnx->user_id     = $agent->id;
        $receiverTrnx->user_type   = 3;
        $receiverTrnx->currency_id = $currency->id;
        $receiverTrnx->amount      = $request->amount;
        $receiverTrnx->charge      = 0;
        $receiverTrnx->remark      = 'cash_out';
        $receiverTrnx->type        = '+';
        $receiverTrnx->details     = trans('Cash out from '). auth()->user()->email;
        $receiverTrnx->save();

        try {
            @mailSend('cash_out_user',[
                'amount'    => amount($request->amount,$senderWallet->currency->type,3),             
                'curr'      => $senderWallet->currency->code,
                'agent'     => $agent->email,
                'trnx'      => $trnx->trnx,
                'charge'    => amount($finalCharge,$senderWallet->currency->type,3),
                'date_time' => dateFormat($trnx->created_at),
                'balance'   => amount($senderWallet->balance,$senderWallet->currency->type,3)
            ],auth()->user());
    
            @mailSend('cash_out_agent',[
                'amount'    => amount($request->amount,$senderWallet->currency->type,3),             
                'curr'      => $senderWallet->currency->code,
                'user'      => auth()->user()->email,
                'trnx'      => $trnx->trnx,
                'date_time' => dateFormat($trnx->created_at),
                'balance'   => amount($senderWallet->balance,$senderWallet->currency->type,3)
            ],$agent);
        } catch (\Throwable $th) {
            
        }
  
        return back()->with('success','Cash out successfully');
    
       
    }
}
