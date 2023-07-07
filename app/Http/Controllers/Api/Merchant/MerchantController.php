<?php

namespace App\Http\Controllers\Api\Merchant;

use Image;
use App\Models\Module;
use App\Models\Wallet;
use App\Models\KycForm;
use App\Models\ApiCreds;
use App\Models\Transaction;
use App\Models\Withdrawals;
use Illuminate\Support\Str;
use App\Helpers\MediaHelper;
use Illuminate\Http\Request;
use App\Models\Generalsetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;

class MerchantController extends ApiController
{
    public function dashboard()
    {
        $user = request()->user();
        $success['wallets'] = Wallet::where('user_type',2)->where('user_id',$user->id)->get();
        $success['recent_withdraw']  = Withdrawals::where('merchant_id',$user->id)->take(7)->get();
        $success['recent_transactions'] = Transaction::where('user_id',$user->id)->where('user_type',2)->take(7)->get();
        return $this->sendResponse($success,'success');
    }

    public function generateQR()
    {
        return $this->sendResponse(['qrcode_image' =>  generateQR(request()->user()->email)],'QR code has been generated');
    }


    public function apiKeyGenerate()
    {
        $user = request()->user();
        $cred = ApiCreds::whereMerchantId($user->id)->first();
        if(!$cred){
            ApiCreds::create([
                'merchant_id' => merchant()->id,
                'access_key'  => (string) Str::uuid(),
                'mode'        => 0
            ]); 
        }
        $cred->access_key = (string) Str::uuid();
        $cred->update();
        return $this->sendResponse(['access_key' =>  $cred->access_key],'New api key has been generated');
    }

    public function serviceMode()
    {
        $user = request()->user();
        $cred = ApiCreds::whereMerchantId($user->id)->first();
        if($cred->mode == 0){
            $cred->mode = 1;
            $msg = 'Service selected as Active Mode';
        } else{
            $cred->mode = 0;
            $msg = 'Service selected as Test Mode';
        }
        $cred->update();
        return $this->sendResponse(['success'],__($msg));
    }


    public function profileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'phone' => 'required',
            'photo' => 'image|mimes:jpg,jpeg,png',
            'city' => 'required',
            'zip' => 'required',
            'business_name' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error',$validator->errors());
        }

        $user          = $request->user();
        $user->business_name = $request->business_name;
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

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(),['old_pass'=>'required','password'=>'required|min:6|confirmed']);
        if($validator->fails()){
            return $this->sendError('Validation Error',$validator->errors());
        }
        $user = $request->user();
        if (Hash::check($request->old_pass, $user->password)) {
            $password = bcrypt($request->password);
            $user->password = $password;
            $user->save();
            return $this->sendResponse(['success'], 'Password has been changed');
        } else {
            return $this->sendError('Error', ['The old password doesn\'t match!']);
        }
    }



    public function transactions()
    {
        $remark = request('remark');
        $search = request('search');
        $user = request()->user();
        $success['transactions'] = Transaction::where('user_id',$user->id)->where('user_type',2)
                        ->when($remark,function($q) use($remark){
                            return $q->where('remark',$remark);
                        })
                        ->when($search,function($q) use($search){
                            return $q->where('trnx',$search);
                        })
                       ->latest()
                       ->paginate(15);

        $success['remark_list'] = [
            'merchant_payment','merchant_api_payment','withdraw_money'
        ];
        $success['remark'] = $remark;
        $success['search'] = $search;
        
        return $this->sendResponse($success,'Transaction history');
    }

    public function trxDetails($id)
    {
        $user = request()->user();
        $success['transaction'] = Transaction::where('id',$id)->where('user_type',2)->where('user_id',$user->id)->first();
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

        $user = $request->user();
        if (Hash::check($request->password, $user->password)) {
            $code = randNum();
            $user->two_fa_code = $code;
            $user->update();
            sendSMS($user->phone,trans('Your two step authentication OTP is : ').$code,Generalsetting::value('contact_no'));
            return $this->sendResponse(['success'],'OTP code is sent to your phone.');
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

        $user = $request->user();
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

    public function kycForm()
    {
        $user = request()->user();
        if($user->kyc_status == 2) return $this->sendError('Error',['You have already submitted the KYC data.']);
        if($user->kyc_status == 1) return $this->sendError('Error',['Your KYC data is already verified.']);
        $success['kyc_form_data'] = KycForm::where('user_type',2)->get();
        return $this->sendResponse($success,'success');
    }

    public function kycFormSubmit(Request $request)
    {
        $user = request()->user();
        if($user->kyc_status == 2) return $this->sendError('Error',['You have already submitted the KYC data.']);
        if($user->kyc_status == 1) return $this->sendError('Error',['Your KYC data is already verified.']);

        $data = $request->except('_token');
        $kycForm = KycForm::where('user_type',2)->get();
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

        $user->kyc_info = $data;
        $user->kyc_status = 2;
        $user->save();

        return $this->sendResponse(['success'],'KYC data has been submitted for review.');
    }



    

}

