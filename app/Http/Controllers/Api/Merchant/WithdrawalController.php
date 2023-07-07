<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Models\Wallet;
use App\Models\Withdraw;
use App\Models\Withdrawals;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Validator;

class WithdrawalController extends ApiController
{
    public function withdrawForm()
    {
        $success['wallets'] = Wallet::where('user_id',request()->user()->id)->where('user_type',2)->where('balance', '>', 0)->get();
        return $this->sendResponse($success,__('success'));
    }

    public function methods(Request $request)
    {
        $success['methods'] = Withdraw::where('currency_id',$request->currency)->where('status',1)->get();
        if($success['methods']->isEmpty()){
            return $this->sendError('Error',['No withdraw methods found']);
        }
        return $this->sendResponse($success,__('Withdraw methods'));
    }

    public function withdrawSubmit(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'amount'    => 'required|numeric|gt:0',
            'wallet_id' => 'required',
            'method_id' => 'required',
            'user_data' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error',$validator->errors());
        }

        $wallet = Wallet::where('id',$request->wallet_id)->where('user_type',2)->first();
        if(!$wallet){
            return $this->sendError('Error',['Wallet not found']);
        }

        $method = Withdraw::where('id',$request->method_id)->where('currency_id',$wallet->currency_id)->first();
        if(!$method){
            return $this->sendError('Error',['Withdraw method not found']);
        }

        if($request->amount < $method->min_amount || $request->amount > $method->max_amount){
            return $this->sendError('Error',['Please follow the limit']);
        }

        $charge = chargeCalc($method,$request->amount);
        $finalAmount = numFormat($request->amount + $charge);

        if($wallet->balance < $finalAmount){
            return $this->sendError('Error',['Insufficient balance']);
        }

        $wallet->balance -=  $finalAmount;
        $wallet->save();
        
        $withdraw              = new Withdrawals();
        $withdraw->trx         = str_rand();
        $withdraw->merchant_id = request()->user()->id;
        $withdraw->method_id   = $method->id;
        $withdraw->currency_id = $wallet->currency_id;
        $withdraw->amount      = $request->amount;
        $withdraw->charge      = $charge;
        $withdraw->total_amount= $finalAmount;
        $withdraw->user_data   = $request->user_data;
        $withdraw->save();

        return $this->sendResponse(['success'],__('Withdraw request has been submitted successfully.'));
    }

    public function history()
    {
        $success['withdrawals'] = Withdrawals::where('merchant_id',request()->user()->id)->latest()->paginate(15);
        return $this->sendResponse($success,__('Withdraw history'));
    }
}
