<?php

namespace App\Http\Controllers\User;

use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\ExchangeMoney;

class ExchangeMoneyController extends Controller
{
    public function exchangeForm()
    {
        $wallets = Wallet::where('user_id',auth()->id())->where('user_type',1)->where('balance', '>', 0)->get();
        $currencies = Currency::where('status',1)->get();
        $charge  = charge('money-exchange');
        $recentExchanges = ExchangeMoney::where('user_id',auth()->id())->with(['fromCurr','toCurr'])->latest()->take(7)->get();
        return view('user.exchange.exchange',compact('wallets','charge','currencies','recentExchanges'));
    }

    public function submitExchange(Request $request)
    {
        $request->validate([
            'amount' => 'required|gt:0',
            'from_wallet_id' => 'required|integer',
            'to_wallet_id' => 'required|integer'
        ],[
            'from_wallet_id.required' => 'From currency is required',
            'to_wallet_id.required' => 'To currency is required',
        ]);

        $charge  = charge('money-exchange');

        $fromWallet = Wallet::where('id',$request->from_wallet_id)->where('user_id',auth()->id())->where('user_type',1)->firstOrFail();

        $toWallet = Wallet::where('currency_id',$request->to_wallet_id)->where('user_id',auth()->id())->where('user_type',1)->first();

        if(!$toWallet){
            $toWallet = Wallet::create([
                'user_id'     => auth()->id(),
                'user_type'   => 1,
                'currency_id' => $request->to_wallet_id,
                'balance'     => 0
            ]);
        }

        $defaultAmount = $request->amount / $fromWallet->currency->rate;
        $finalAmount   = amount($defaultAmount * $toWallet->currency->rate,$toWallet->currency->type);

        $charge = amount(chargeCalc($charge,$request->amount,$fromWallet->currency->rate),$fromWallet->currency->type);
        $totalAmount = amount(($request->amount +  $charge),$fromWallet->currency->type);

        if($fromWallet->balance < $totalAmount){
            return back()->with('error','Insufficient balance to your '.$fromWallet->currency->code.' wallet');
        }

        $fromWallet->balance -=  $totalAmount;
        $fromWallet->update();

        $toWallet->balance += $finalAmount;
        $toWallet->update();

        $exchange = new ExchangeMoney();
        $exchange->trnx = str_rand();
        $exchange->from_currency = $fromWallet->currency->id;
        $exchange->to_currency = $toWallet->currency->id;
        $exchange->user_id = auth()->id();
        $exchange->charge = $charge;
        $exchange->from_amount = $request->amount;
        $exchange->to_amount = $finalAmount;
        $exchange->save();

        @mailSend('exchange_money',['from_curr'=>$fromWallet->currency->code,'to_curr'=>$toWallet->currency->code,'charge'=> amount($charge,$fromWallet->currency->type,3),'from_amount'=> amount($request->amount,$fromWallet->currency->type,3),'to_amount'=> amount($finalAmount,$toWallet->currency->type,3),'date_time'=> dateFormat($exchange->created_at)],auth()->user());

        return back()->with('success','Money exchanged successfully.'); 
    }

    public function exchangeHistory()
    {
        $search = request('search');
        $exchanges = ExchangeMoney::where('user_id',auth()->id())->when($search,function($q) use($search){return $q->where('trnx',$search);})->with(['fromCurr','toCurr'])->latest()->paginate(15);
        return view('user.exchange.history',compact('exchanges','search'));
    }
}
