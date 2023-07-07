<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\RequestMoney;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RequestMoneyController extends Controller
{
    public function requestForm()
    {
        $wallets = Wallet::where('user_id',auth()->id())->where('user_type',1)->get();
        $charge = charge('request-money');
        $recentRequests = RequestMoney::where('sender_id',auth()->id())->with('currency')->latest()->take(7)->get();

        return view('user.transfer.request_money',compact('wallets','charge','recentRequests'));
    }

    public function requestSubmit(Request $request)
    {
        $request->validate([
            'receiver'  => 'required|email',
            'wallet_id' => 'required|integer',
            'amount'    => 'required|numeric|gt:0' 
        ],
        [
            'wallet_id.required' => 'Wallet is required'
        ]);

        $receiver = User::where('email',$request->receiver)->first();
        if(!$receiver){
            return back()->with('error','Recipient not found');
        }

        $senderWallet = Wallet::where('id',$request->wallet_id)->where('user_type',1)->where('user_id',auth()->id())->first();
        if(!$senderWallet){
            return back()->with('error','Your wallet not found');
        }


        $currency = Currency::findOrFail($senderWallet->currency->id);

        $charge = charge('request-money');
        if(($charge->minimum *  $currency->rate) > $request->amount || ($charge->maximum *  $currency->rate) < $request->amount){
            return back()->with('error','Please follow the limit');
        }

        $finalCharge =  chargeCalc($charge,$request->amount,$currency->rate);
        $finalAmount =  numFormat($request->amount - $finalCharge);

        RequestMoney::create([
            'sender_id'       => auth()->id(),
            'receiver_id'     => $receiver->id,
            'currency_id'     => $currency->id,
            'request_amount'  => $request->amount,
            'charge'          => $finalCharge,
            'final_amount'    => $finalAmount,
        ]);

        return back()->with('success','Money Request has been submitted to '.$receiver->email);
    }

    public function moneyRequests()
    {
        $sentRequests = RequestMoney::where('sender_id',auth()->id())->latest()->paginate(15);
        $receivedRequests = RequestMoney::where('receiver_id',auth()->id())->where('status','!=',2)->latest()->paginate(15);
        return view('user.transfer.requests',compact('sentRequests','receivedRequests'));
    }

    public function acceptRequest(Request $request)
    {
        $request->validate(['id'=>'required','sender_id'=>'required']);
        $reqMoney = RequestMoney::where('id',$request->id)->where('sender_id',$request->sender_id)->where('receiver_id',auth()->id())->firstOrFail();

        $wallet = Wallet::where('user_id',auth()->id())->where('user_type',1)->where('currency_id',$reqMoney->currency_id)->first();

        if(!$wallet){
            return back()->with('error','Wallet not found.');
        }

        if($reqMoney->request_amount > $wallet->balance){
            return back()->with('error','Insufficient Balance.');
        }

        $receiverWallet = Wallet::where('user_id',$reqMoney->sender_id)->where('user_type',1)->where('currency_id',$reqMoney->currency_id)->first();

        if(!$receiverWallet){
            return back()->with('error','Receiver Wallet not found.');
        }

        $wallet->balance  -= $reqMoney->request_amount;
        $wallet->update();

        $trnx              = new Transaction();
        $trnx->trnx        = str_rand();
        $trnx->user_id     = auth()->id();
        $trnx->user_type   = 1;
        $trnx->currency_id = $reqMoney->currency_id;
        $trnx->wallet_id   = $wallet->id;
        $trnx->amount      = $reqMoney->request_amount;
        $trnx->charge      = 0;
        $trnx->type        = '-';
        $trnx->remark      = 'request_money';
        $trnx->details     = trans('Accept money request from '). $reqMoney->sender->email;
        $trnx->save();

        $receiverWallet->balance += $reqMoney->final_amount;
        $receiverWallet->update();

        $receiverTrnx              = new Transaction();
        $receiverTrnx->trnx        = $trnx->trnx;
        $receiverTrnx->user_id     = $reqMoney->sender_id;
        $receiverTrnx->user_type   = 1;
        $receiverTrnx->currency_id = $reqMoney->currency_id;
        $receiverTrnx->wallet_id   = $receiverWallet->id;
        $receiverTrnx->amount      = $reqMoney->final_amount;
        $receiverTrnx->charge      = $reqMoney->charge;
        $receiverTrnx->type        = '+';
        $receiverTrnx->remark      = 'request_money';
        $receiverTrnx->details     = trans('Money request accepted by '). auth()->user()->email;
        $receiverTrnx->save();

        $reqMoney->status = 1;
        $reqMoney->update();

        @mailSend('accept_request_money',["curr"=>$reqMoney->currency->code,'amount'=>amount($reqMoney->request_amount,$reqMoney->currency->type,3),"trnx"=>$trnx->trnx,"to_user"=>auth()->user()->email,"charge"=>amount($reqMoney->charge,$reqMoney->currency->type,3),'date_time'=> dateFormat( $receiverTrnx->created_at)],$reqMoney->sender);


        return back()->with('success','Money request has been accepted.');
    }

    public function rejectRequest(Request $request)
    {
        $request->validate(['id'=>'required','sender_id'=>'required']);
        $reqMoney = RequestMoney::where('id',$request->id)->where('sender_id',$request->sender_id)->where('receiver_id',auth()->id())->firstOrFail();

        $reqMoney->status = 2;
        $reqMoney->update();
        return back()->with('success','Money request has been rejected.');
    }
}
