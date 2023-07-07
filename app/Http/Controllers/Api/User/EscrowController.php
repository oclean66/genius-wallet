<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use App\Models\Escrow;
use App\Models\Wallet;
use App\Models\Dispute;
use App\Models\Currency;
use App\Models\Transaction;
use App\Helpers\MediaHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;

class EscrowController extends ApiController
{
    public function index()
    {
        $escrows = Escrow::where('user_id',auth()->id())->paginate(15);
        return $this->sendResponse(['escrows' => $escrows],'My escrows');  
    }

    public function create()
    {
        $success['wallets'] = Wallet::where('user_id',auth()->id())->where('user_type',1)->where('balance', '>', 0)->with('currency')->get();
        $success['charge'] = charge('make-escrow');
        return $this->sendResponse($success,'Make escrow');  
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'receiver'  => 'required|email',
            'wallet_id' => 'required|integer',
            'amount'    => 'required|numeric|gt:0',
            'description'    => 'required' 
        ],
        [
            'wallet_id.required' => 'Wallet is required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error',$validator->errors());
        }
    
        $receiver = User::where('email',$request->receiver)->first();
        if(!$receiver) return $this->sendError('Error',['Recipient not found']);
        
        $senderWallet = Wallet::where('id',$request->wallet_id)->where('user_type',1)->where('user_id',auth()->id())->first();

        if(!$senderWallet) return $this->sendError('Error',['Your wallet not found']);
        
        $currency = Currency::findOrFail($senderWallet->currency->id);
        $charge = charge('make-escrow');  

        $finalCharge = amount(chargeCalc($charge,$request->amount,$currency->rate),$currency->type);
        if($request->pay_charge) $finalAmount =  amount($request->amount + $finalCharge, $currency->type);
        else  $finalAmount =  amount($request->amount, $currency->type);
        
        if($senderWallet->balance < $finalAmount) return $this->sendError('Error',['Insufficient balance.']);
        
        $senderWallet->balance -= $finalAmount;
        $senderWallet->update();

        $escrow               = new Escrow();
        $escrow->trnx         = str_rand();
        $escrow->user_id      = auth()->id();
        $escrow->recipient_id = $receiver->id;
        $escrow->description  = $request->description;
        $escrow->amount       = $request->amount;
        $escrow->pay_charge   = $request->pay_charge ? 1 : 0;
        $escrow->charge       = $finalCharge;
        $escrow->currency_id  = $currency->id;
        $escrow->save();

        $trnx              = new Transaction();
        $trnx->trnx        = $escrow->trnx;
        $trnx->user_id     = auth()->id();
        $trnx->user_type   = 1;
        $trnx->currency_id = $currency->id;
        $trnx->wallet_id   = $senderWallet->id;
        $trnx->amount      = $finalAmount;
        $trnx->charge      = $finalCharge;
        $trnx->remark      = 'make_escrow';
        $trnx->type        = '-';
        $trnx->details     = trans('Made escrow to '). $receiver->email;
        $trnx->save();

        return $this->sendResponse(['success'],'Escrow has been created successfully');

    }

    public function disputeForm($id)
    {
        $escrow = Escrow::where('id',$id)->first();
        if(!$escrow){
            return $this->sendError('Error',['Escrow not found']);
        }
        
        if (auth()->id() != $escrow->recipient_id && auth()->id() != $escrow->user_id){
            return $this->sendError('Error',['Invalid access']);
        }

        $success['escrow']   = $escrow;
        $success['messages'] = Dispute::where('escrow_id',$escrow->id)->with('user')->get();

        return $this->sendResponse($success,'success');
    }

    public function disputeStore(Request $request,$escrow_id)
    {
  
        $validator = Validator::make($request->all(),['message'=>'required','file' => 'mimes:png,jpeg,jpg|max:5186']);

        if($validator->fails()){
            return $this->sendError('Validation Error',$validator->errors());
        }

        $escrow = Escrow::where('id',$escrow_id)->first();

        if(!$escrow){
            return $this->sendError('Error',['Escrow not found']);
        }

        if (auth()->id() != $escrow->recipient_id && auth()->id() != $escrow->user_id){
            return $this->sendError('Error',['Invalid access']);
        }

        if($escrow->status == 4) return $this->sendError('Error',['Dispute has been closed']);
        
        $escrow->status = 3;
        if($escrow->dispute_created == null) $escrow->dispute_created = auth()->id();
        $escrow->save();

        $dispute = new Dispute;
        $dispute->user_id = auth()->id();
        $dispute->escrow_id = $escrow_id;
        $dispute->message = $request->message;
        if($request->file) $dispute->file = MediaHelper::handleMakeImage($request->file);
        $dispute->save();

        $success['dispute_id'] = $dispute->id;
        if($request->file) $success['file_download'] = route('user.escrow.file.download',$dispute->id);

        return $this->sendResponse($success,'Replied successfully');
    }

    

    public function release($id)
    {

        $escrow = Escrow::where('id',$id)->where('user_id',auth()->id())->whereIn('status',[0,3])->first();
        if(!$escrow){
            return $this->sendError('Error',['Escrow not found']);
        }
        $recipient = User::findOrFail($escrow->recipient_id);
        $recipientWallet = Wallet::where('user_id',$recipient->id)
                            ->where('user_type',1)
                            ->where('currency_id',$escrow->currency_id)
                            ->first();

        if(!$recipientWallet){
            $recipientWallet =  Wallet::create(
                [
                    'user_id'      => $recipient->id,
                    'user_type'    => 1,
                    'currency_id'  => $escrow->currency_id,
                    'balance'      => 0
                ]
            );
        } 

        $amount = $escrow->amount;
        if($escrow->pay_charge == 0) $amount -= $escrow->charge;
        
        $recipientWallet->balance += $amount;
        $recipientWallet->update();

        $trnx              = new Transaction();
        $trnx->trnx        = $escrow->trnx;
        $trnx->user_id     = $recipient->id;
        $trnx->user_type   = 1;
        $trnx->currency_id = $escrow->currency_id;
        $trnx->amount      = $amount;
        $trnx->charge      = $escrow->pay_charge == 0 ? $escrow->charge : 0;
        $trnx->remark      = 'make_escrow';
        $trnx->type        = '+';
        $trnx->details     = trans('Received escrow money '). $recipient->email;
        $trnx->save();

        $escrow->status = 1;
        $escrow->save();

        return $this->sendResponse(['success'],'Escrow has been released');

    }

    public function pending()
    {
        $escrows = Escrow::where('recipient_id',auth()->id())->latest()->paginate(15);
        return $this->sendResponse(['escrows' => $escrows],'My escrows');  
    }
}
