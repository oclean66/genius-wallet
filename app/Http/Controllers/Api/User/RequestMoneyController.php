<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\RequestMoney;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;


class RequestMoneyController extends ApiController
{
    public function requestForm()
    {
        $success['wallets'] = Wallet::where('user_id',auth()->id())->where('user_type',1)->with('currency')->get();
        $success['charge'] = charge('request-money');
        $success['recent_requests'] = RequestMoney::where('sender_id',auth()->id())->with('currency')->latest()->take(7)->get();
        return $this->sendResponse($success,'success');
    }

    public function requestSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver'  => 'required|email',
            'wallet_id' => 'required|integer',
            'amount'    => 'required|numeric|gt:0' 
        ],
        [
            'wallet_id.required' => 'Wallet is required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors());       
        }

        $receiver = User::where('email',$request->receiver)->first();
        if(!$receiver){
            return $this->sendError('Error',['Recipient not found']);
        }

        $senderWallet = Wallet::where('id',$request->wallet_id)->where('user_type',1)->where('user_id',auth()->id())->first();
        if(!$senderWallet){
            return $this->sendError('Error',['Your wallet not found']);
        }


        $currency = Currency::find($senderWallet->currency->id);
        if(!$currency){
            return $this->sendError('Error',['Currency not found']);
        }

        $charge = charge('request-money');
        if(($charge->minimum *  $currency->rate) > $request->amount || ($charge->maximum *  $currency->rate) < $request->amount){
            return $this->sendError('Error',['Please follow the limit']);
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

        return $this->sendResponse(['success'],'Money Request has been submitted to '.$receiver->email);
    }

    public function sentRequests()
    {
        $success['sent_money_requests'] = RequestMoney::with('receiver:id,name,email,photo')->where('sender_id',auth()->id())->latest()->paginate(15);
        return $this->sendResponse($success,__('Sent Money Requests'));
    }

    public function receivedRequests()
    {
       $success['received_money_requests'] = RequestMoney::with('sender:id,name,email,photo')->where('receiver_id',auth()->id())->where('status','!=',2)->latest()->paginate(15);
        return $this->sendResponse($success,__('Received Money Requests'));
    }

    public function acceptRequest(Request $request)
    {
      
        $validator = Validator::make($request->all(), ['id'=>'required','sender_id'=>'required'],
        [
            'id.required' => 'Request money data id is required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors());       
        }

        $reqMoney = RequestMoney::where('id',$request->id)->where('sender_id',$request->sender_id)->where('receiver_id',auth()->id())->first();

        if(!$reqMoney){
            return $this->sendError('Error',['Request money data not found.']);
        }

        if($reqMoney->status == 1){
            return $this->sendError('Error',['This request has been already accepted.']);
        }
        if($reqMoney->status == 2){
            return $this->sendError('Error',['This request has been already rejected.']);
        }
     

        $wallet = Wallet::where('user_id',auth()->id())->where('user_type',1)->where('currency_id',$reqMoney->currency_id)->first();

        if(!$wallet){
            return $this->sendError('Error',['Wallet not found.']);
        }

        if($reqMoney->request_amount > $wallet->balance){
            return $this->sendError('Error',['Insufficient Balance.']);
        }

        $receiverWallet = Wallet::where('user_id',$reqMoney->sender_id)->where('user_type',1)->where('currency_id',$reqMoney->currency_id)->first();

        if(!$receiverWallet){
            return $this->sendError('Error',['Receiver Wallet not found.']);
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

        try {

            @mailSend('accept_request_money',["curr"=>$reqMoney->currency->code,'amount'=>amount($reqMoney->request_amount,$reqMoney->currency->type,3),"trnx"=>$trnx->trnx,"to_user"=>auth()->user()->email,"charge"=>amount($reqMoney->charge,$reqMoney->currency->type,3),'date_time'=> dateFormat( $receiverTrnx->created_at)],$reqMoney->sender);

        } catch (\Throwable $th) {
            
        }
        
        return $this->sendResponse(['success'],'Money request has been accepted.');
    }

    public function rejectRequest(Request $request)
    {
        $validator = Validator::make($request->all(), ['id'=>'required','sender_id'=>'required'],
        [
            'id.required' => 'Request money data id is required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors());       
        }

        $reqMoney = RequestMoney::where('id',$request->id)->where('sender_id',$request->sender_id)->where('receiver_id',auth()->id())->first();
     
        if(!$reqMoney){
            return $this->sendError('Error',['Request money data not found.']);
        }

        if($reqMoney->status == 1){
            return $this->sendError('Error',['This request has been already accepted.']);
        }
        if($reqMoney->status == 2){
            return $this->sendError('Error',['This request has been already rejected.']);
        }

        $reqMoney->status = 2;
        $reqMoney->update();
        return $this->sendResponse(['success'],'Money request has been rejected.');
    }
}
