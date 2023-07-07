<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Wallet;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\ExchangeMoney;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Validator;

class ExchangeMoneyController extends ApiController
{
    public function exchangeForm()
    {
        $success['wallets'] = Wallet::where('user_id',auth()->id())->where('user_type',1)->where('balance', '>', 0)->with('currency')->get();
        $success['currencies'] = Currency::where('status',1)->get();
        $success['charge']  = charge('money-exchange');
        $success['recent_exchanges'] = ExchangeMoney::where('user_id',auth()->id())->with(['fromCurr','toCurr'])->latest()->take(7)->get();
        return $this->sendResponse($success,'success');
    }

    public function submitExchange(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'amount' => 'required|gt:0',
            'from_wallet_id' => 'required|integer',
            'to_currency_id' => 'required|integer'
        ],
        [
            'from_wallet_id.required' => 'From currency is required',
            'to_wallet_id.required' => 'To currency is required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors());       
        }
      
        $charge  = charge('money-exchange');
        $fromWallet = Wallet::where('id',$request->from_wallet_id)->where('user_id',auth()->id())->where('user_type',1)->first();

        if(!$fromWallet){
            return $this->sendError('Error', ['Your from currency wallet not found.']); 
        }

        $toWallet = Wallet::where('currency_id',$request->to_currency_id)->where('user_id',auth()->id())->where('user_type',1)->first();

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
            return $this->sendError('Error',['Insufficient balance to your '.$fromWallet->currency->code.' wallet']);
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

        try {
            @mailSend('exchange_money',['from_curr'=>$fromWallet->currency->code,'to_curr'=>$toWallet->currency->code,'charge'=> amount($charge,$fromWallet->currency->type,3),'from_amount'=> amount($request->amount,$fromWallet->currency->type,3),'to_amount'=> amount($finalAmount,$toWallet->currency->type,3),'date_time'=> dateFormat($exchange->created_at)],auth()->user());
        } catch (\Throwable $th) {
            
        }

        return $this->sendResponse(['success'],__('Money exchanged successfully.')); 
    }

    public function exchangeHistory()
    {
        $search = request('search');
        $success['exchanges'] = ExchangeMoney::where('user_id',auth()->id())->when($search,function($q) use($search){return $q->where('trnx',$search);})->with(['fromCurr','toCurr'])->latest()->paginate(15);
        $success['search'] = $search;

        return $this->sendResponse($success,'Exchange Money History');
    }
}
