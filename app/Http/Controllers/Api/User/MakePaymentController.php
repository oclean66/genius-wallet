<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Wallet;
use App\Models\Currency;
use App\Models\Merchant;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;

class MakePaymentController extends ApiController
{
    public function checkMerchant(Request $request){
        $receiver['data'] = Merchant::where('email',$request->receiver)->first();
        if($receiver['data']){
            return $this->sendResponse(['Merchant exists'],__('Valid merchant found.'));
        }
        return $this->sendError('Merchant not found',[__('Merchant doesn\'t exists')]);
    }

    public function paymentForm()
    {
        $success['wallets'] = Wallet::where('user_id',auth()->id())->where('user_type',1)->where('balance', '>', 0)->with('currency')->get();
        $success['recent_payments'] = Transaction::where('user_id',auth()->id())->where('user_type',1)->where('remark','merchant_payment')->with('currency')->paginate(10);
        $success['charge'] = charge('merchant-payment');
        return $this->sendResponse($success,__('success'));
    }

    public function submitPayment(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'receiver'  => 'required|email',
            'wallet' => 'required|integer',
            'amount'    => 'required|numeric|gt:0' 
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors());       
        }

        $receiver = Merchant::where('email',$request->receiver)->first();
        if(!$receiver) return $this->sendError('Error',['Merchant not found']);
        
        $senderWallet = Wallet::where('id',$request->wallet)->where('user_type',1)->where('user_id',auth()->id())->first();
        if(!$senderWallet) return $this->sendError('Error',['Your wallet not found']);
        
        $currency = Currency::findOrFail($senderWallet->currency->id);
        $charge = charge('merchant-payment');
       
        $merchantWallet = Wallet::where('currency_id',$currency->id)->where('user_id',$receiver->id)->where('user_type',2)->where('user_id',$receiver->id)->first();

        if(!$merchantWallet){
            $merchantWallet = Wallet::create([
                'user_id'     => $receiver->id,
                'user_type'   => 2,
                'currency_id' => $currency->id,
                'balance'     => 0
            ]);
        }

        $finalCharge = chargeCalc($charge,$request->amount,$currency->rate);
        if($finalCharge > $request->amount) $finalCharge = 0;
        $finalAmount =  numFormat($request->amount - $finalCharge);

        if($finalAmount < 0)return $this->sendError('Error',['Amount can not be less than 0.']);
        if($senderWallet->balance < $finalAmount) return $this->sendError('Error',['Insufficient balance.']);
    
        $senderWallet->balance -= $request->amount;
        $senderWallet->update();

        $trnx              = new Transaction();
        $trnx->trnx        = str_rand();
        $trnx->user_id     = auth()->id();
        $trnx->user_type   = 1;
        $trnx->currency_id = $currency->id;
        $trnx->wallet_id   = $senderWallet->id;
        $trnx->amount      = $request->amount;
        $trnx->charge      = 0;
        $trnx->type        = '-';
        $trnx->remark      = 'merchant_payment';
        $trnx->details     = trans('Payment to merchant : '). $receiver->email;
        $trnx->save();

        $merchantWallet->balance += $finalAmount;
        $merchantWallet->update();

        $receiverTrnx              = new Transaction();
        $receiverTrnx->trnx        = $trnx->trnx;
        $receiverTrnx->user_id     = $receiver->id;
        $receiverTrnx->user_type   = 2;
        $receiverTrnx->currency_id = $currency->id;
        $receiverTrnx->wallet_id   = $merchantWallet->id;
        $receiverTrnx->amount      = $finalAmount;
        $receiverTrnx->charge      = $finalCharge;
        $receiverTrnx->type        = '+';
        $receiverTrnx->remark      = 'merchant_payment';
        $receiverTrnx->details     = trans('Payment received from : '). auth()->user()->email;
        $receiverTrnx->save();

        try {
            //mail to user
            @mailSend('make_payment',["curr"=>$currency->code,'amount'=>amount($request->amount,$currency->type,3),"trnx"=>$trnx->trnx,"to_merchant"=>$receiver->email,'date_time'=> dateFormat( $receiverTrnx->created_at),"after_balance"=>amount($senderWallet->balance,$currency->type,3)],auth()->user());

            //mail to merchant
            @mailSend('make_payment',["curr"=>$currency->code,'amount'=>amount($request->amount,$currency->type,3),"trnx"=>$trnx->trnx,"from_user"=>auth()->user()->email,'date_time'=> dateFormat( $receiverTrnx->created_at),"after_balance"=>amount($merchantWallet->balance,$currency->type,3),"charge"=>$finalCharge],auth()->user());
            
        } catch (\Throwable $th) {
            
        }
        
        return $this->sendResponse(['success'],__('Payment successfull'));
    }

    public function paymentHistory()
    {
        $search = request('search');
        $success['payments'] = Transaction::where('user_id',auth()->id())->where('user_type',1)->where('remark','merchant_payment')->when($search,function($q) use($search){return $q->where('trnx',$search);})->with('currency')->latest()->paginate(15);
        $success['search'] = $search;
        return $this->sendResponse($success,__('Merchant payment history'));
    }

}
