<?php

namespace App\Http\Controllers\Api\User;


use Image;
use App\Models\Wallet;
use App\Models\Deposit;
use App\Models\KycForm;
use App\Models\Transaction;
use App\Models\Withdrawals;
use App\Helpers\MediaHelper;
use Illuminate\Http\Request;
use App\Models\Generalsetting;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\UserResource as User;

class UserController extends ApiController{

    public function __construct(UserResource $resource)
    {
        $this->resource = $resource;
    }

    public function trnx()
    {
       return Transaction::where('user_id',auth()->id())->where('user_type',1)->with('currency');
    }
    
    public function userInfo(){
        $success['user'] = new User(auth()->user());
        return $this->sendResponse($success,'success');
        
    }

    public function index()
    {
        $success['wallets'] = Wallet::where('user_id',auth()->id())->where('user_type',1)->with('currency')->get();
        $success['transactions'] = $this->trnx()->latest()->take(8)->get();

        $amount = collect([]);
        $this->trnx()->where('remark','transfer_money')->get()->map(function($q) use($amount){
            $amount->push((float) amountConv($q->amount,$q->currency));
        });
        $success['totalTransferMoney'] = $amount->sum();

        $exchange = collect([]);
        $this->trnx()->where('remark','money_exchange')->get()->map(function($q) use($exchange){
            $exchange->push((float) amountConv($q->amount,$q->currency));
        });
        $success['totalExchange'] = $exchange->sum();

        $deposit = collect([]);
        Deposit::where('user_id',auth()->id())->with('currency')->get()->map(function($q) use($deposit){
            $deposit->push((float) amountConv($q->amount,$q->currency));
        });
        $success['totalDeposit'] = $deposit->sum();

        $withdraw = collect([]);
        Withdrawals::where('user_id',auth()->id())->with('currency')->get()->map(function($q) use($withdraw){
            $withdraw->push((float) amountConv($q->amount,$q->currency));
        });
        $success['totalWithdraw'] = $withdraw->sum();

        return $this->sendResponse($success,'success');
    }



    public function profileSubmit(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'phone' => 'required',
            'photo' => 'image|mimes:jpg,jpeg,png',
            'city' => 'required',
            'zip' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error',$validator->errors());
        }

        $user          = auth()->user();
        $user->name    = $request->name;
        $user->phone   = $request->phone;
        $user->city    = $request->city;
        $user->zip     = $request->zip;
        $user->address = $request->address;
     
        if($request->photo){
            $user->photo = MediaHelper::handleMakeImage($request->photo,[300,300]);
        }

        $user->update();
        return $this->sendResponse(['success'],'Profile has been updated');
    } 

    public function changePass(Request $request)
    {
        $validator = Validator::make($request->all(),['old_pass'=>'required','password'=>'required|min:6|confirmed']);
        if($validator->fails()){
            return $this->sendError('Validation Error',$validator->errors());
        }
        $user = auth()->user();
        if (Hash::check($request->old_pass, $user->password)) {
            $password = bcrypt($request->password);
            $user->password = $password;
            $user->save();
            return $this->sendResponse(['success'], 'Password has been changed');
        } else {
            return $this->sendError('Error', ['The old password doesn\'t match!']);
        }
    }

   

    public function kycForm()
    {
        if(auth()->user()->kyc_status == 2) return $this->sendError('Error',['You have already submitted the KYC data.']);
        if(auth()->user()->kyc_status == 1) return $this->sendError('Error',['Your KYC data is already verified.']);
        $success['kyc_form_data'] = KycForm::where('user_type',1)->get();
        return $this->sendResponse($success,'success');
    }

    public function kycFormSubmit(Request $request)
    {
        if(auth()->user()->kyc_status == 2) return $this->sendError('Error',['You have already submitted the KYC data.']);
        if(auth()->user()->kyc_status == 1) return $this->sendError('Error',['Your KYC data is already verified.']);

        $data = $request->except('_token');
        $kycForm = KycForm::where('user_type',1)->get();
        $rules = [];
        foreach ($kycForm as $value) {
            if($value->required == 1){
                if($value->type == 2){
                    $rules[$value->name] = 'required|image|mimes:png,jpg,jpeg|max:5120';
                }
                $rules[$value->name] = 'required';
            }
            
            if($value->type == 2){
                $rules[$value->name] = 'image|mimes:png,jpg,jpeg|max:5120';
                if(request("$value->name")){
                    $filename = MediaHelper::handleMakeImage(request("$value->name"));
                }
                unset($data[$value->name]);
                $data['image'][$value->name] = $filename;
            }

            if($value->type == 3){
                unset($data[$value->name]);
                $data['details'][$value->name] = request("$value->name");
            }

        }

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors());       
        }

        $user = auth()->user();
        $user->kyc_info = $data;
        $user->kyc_status = 2;
        $user->save();

        return $this->sendResponse(['success'],'KYC data has been submitted for review.');
    }

    public function generateQR()
    {
        return $this->sendResponse(['qrcode_image' =>  generateQR(auth()->user()->email)],'QR code has been generated');
    }
    

    public function transactions()
    {
        $remark = request('remark');
        $search = request('search');

        $success['transactions'] = Transaction::where('user_id',auth()->id())->where('user_type',1)
        ->when($remark,function($q) use($remark){
            return $q->where('remark',$remark);
        })
        ->when($search,function($q) use($search){
            return $q->where('trnx',$search);
        })
        ->with('currency')->latest()->paginate(15);

        $success['remark_list'] = [
            'transfer_money','request_money','money_exchange','invoice_payment','merchant_payment','merchant_api_payment','escrow_return','make_escrow','withdraw_money','withdraw_reject','redeem_voucher',
            'create_voucher','deposit'
        ];
        $success['remark'] = $remark;
        $success['search'] = $search;
     
        return $this->sendResponse($success,'Transaction history');

    }

    public function trxDetails($id)
    {
        $success['transaction'] = Transaction::where('id',$id)->where('user_type',1)->where('user_id',auth()->id())->first();
        if(!$success['transaction']){
            return $this->sendError('Error',['Transaction not found']);
        }
        return $this->sendResponse($success,'Transaction details');
    }



    public function twoStepSendCode(Request $request)
    {
        $validator = Validator::make($request->all(),['password'=>'required|confirmed']);
        if( $validator->fails()){
            return $this->sendError('Validation Error',$validator->errors());
        }

        $user = auth()->user();
        if (Hash::check($request->password, $user->password)) {
            $code = randNum();
            $user->two_fa_code = $code;
            $user->update();
            sendSMS($user->phone,trans('Your two step authentication OTP is : ').$code,Generalsetting::value('contact_no'));
            return $this->sendResponse(['success'=>true,'code'=>$code],'OTP code is sent to your phone.');
        } else {
            return $this->sendError('Error', ['The password doesn\'t match!']);
        }

    }


    public function twoStepVerifySubmit(Request $request)
    {
        $validator = Validator::make($request->all(),['code'=>'required']);
        if( $validator->fails()){
            return $this->sendError('Validation Error',$validator->errors());
        }

        $user = auth()->user();
        if($request->code != $user->two_fa_code){
            return $this->sendError('Error',['Invalid OTP']);
        }
        if($user->two_fa_status == 1){
            $user->two_fa_status = 0;
            $user->two_fa= 0;
            $msg = 'Your two step authentication is de-activated';
        }else{
            $user->two_fa_status = 1;
            $msg = 'Your two step authentication is activated';
        }
        $user->two_fa_code = null;
        $user->save();
        return $this->sendResponse(['success'],$msg);
    }

}