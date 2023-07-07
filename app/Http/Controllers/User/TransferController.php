<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransferController extends Controller
{
    public function checkReceiver(Request $request){
        $receiver['data'] = User::where('email',$request->receiver)->first();
        $user = auth()->user(); 
        if(@$receiver['data'] && $user->email == @$receiver['data']->email){
            return response()->json(['self'=>__('Can\'t transfer or request in self wallet.')]);
        }
        return response($receiver);
    }

    public function transferForm()
    {
        $wallets = Wallet::where('user_id',auth()->id())->where('user_type',1)->where('balance', '>', 0)->get();
        $charge = charge('transfer-money');
        $recentTransfers = Transaction::where('user_id',auth()->id())->where('user_type',1)->where('remark','transfer_money')->with('currency')->latest()->take(7)->get();
        return view('user.transfer.transfer_money',compact('wallets','charge','recentTransfers'));
    }

    public function submitTransfer(Request $request)
    {
        $request->validate([
            'receiver'  => 'required|email',
            'wallet_id' => 'required|integer',
            'amount'    => 'required|numeric|gt:0' 
        ],
        [
            'wallet_id.required' => 'Wallet is required'
        ]);

        if(auth()->user()->email == $request->receiver) return back()->with('error','Can\'t transfer to your own wallet');
        
        $receiver = User::where('email',$request->receiver)->first();
        if(!$receiver) return back()->with('error','Receiver not found');
        
        $senderWallet = Wallet::where('id',$request->wallet_id)->where('user_type',1)->where('user_id',auth()->id())->first();
        if(!$senderWallet) return back()->with('error','Your wallet not found');

        $currency = Currency::findOrFail($senderWallet->currency->id);

        $charge = charge('transfer-money');

        if($charge->daily_limit != 0 && auth()->user()->dailyLimit() >= $charge->daily_limit){
            return back()->with('error','Your Daily transfer limit has been reached');
        }

        if($charge->monthly_limit != 0 && auth()->user()->monthlyLimit() >= $charge->monthly_limit){
            return back()->with('error','Your monthly transfer limit has been reached');
        }

        if(($charge->minimum *  $currency->rate) > $request->amount || ($charge->maximum *  $currency->rate) < $request->amount){
            return back()->with('error','Please follow the limit');
        }

        $recieverWallet = Wallet::where('currency_id',$currency->id)->where('user_type',1)->where('user_id',$receiver->id)->first();

        if(!$recieverWallet){
            $recieverWallet = Wallet::create([
                'user_id'     => $receiver->id,
                'user_type'   => 1,
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
        $trnx->remark      = 'transfer_money';
        $trnx->type        = '-';
        $trnx->details     = trans('Transfer money to '). $receiver->email;
        $trnx->save();

        $recieverWallet->balance += $request->amount;
        $recieverWallet->update();

        $receiverTrnx              = new Transaction();
        $receiverTrnx->trnx        = $trnx->trnx;
        $receiverTrnx->user_id     = $receiver->id;
        $receiverTrnx->user_type   = 1;
        $receiverTrnx->currency_id = $currency->id;
        $receiverTrnx->wallet_id   = $recieverWallet->id;
        $receiverTrnx->amount      = $request->amount;
        $receiverTrnx->charge      = 0;
        $receiverTrnx->remark      = 'transfer_money';
        $receiverTrnx->type        = '+';
        $receiverTrnx->details     = trans('Received money from '). auth()->user()->email;
        $receiverTrnx->save();

        //to sender
        @mailSend('transfer_money',['trnx'=>$trnx->trnx,'amount'=>amount($request->amount,$currency->type,3),'curr'=>$currency->code,'charge'=> numFormat($finalCharge),'after_balance'=> amount($senderWallet->balance,$currency->type,3),'trans_to'=> $receiver->email,'date_time'=> dateFormat($trnx->created_at)],auth()->user());

        //to receiver
        @mailSend('received_money',['trnx'=>$trnx->trnx,'amount'=> amount($request->amount,$currency->type,3),'curr'=>$currency->code,'charge'=> 0,'after_balance'=> amount($recieverWallet->balance,$currency->type,3),'trans_from'=> auth()->user()->email,'date_time'=> dateFormat($trnx->created_at)],$receiver);
        
        return back()->with('success','Money has been transferred successfully');

    }

    public function transferHistory()
    {
        $search = request('search');
        $transfers = Transaction::where('user_id',auth()->id())->where('user_type',1)->where('remark','transfer_money')->when($search,function($q) use($search){return $q->where('trnx',$search);})->with('currency')->latest()->paginate(15);

        return view('user.transfer.history',compact('transfers','search'));
    }
}
