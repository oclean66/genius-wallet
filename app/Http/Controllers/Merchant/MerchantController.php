<?php

namespace App\Http\Controllers\Merchant;

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
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class MerchantController extends Controller
{
    public function dashboard()
    {
        $wallets            = Wallet::where('user_type',2)->where('user_id',merchant()->id)->get();
        $recentWithdraw     = Withdrawals::where('merchant_id',merchant()->id)->take(7)->get();
        $recentTransactions = Transaction::where('user_id',merchant()->id)->where('user_type',2)->take(7)->get();
        return view('merchant.dashboard',compact('wallets','recentWithdraw','recentTransactions'));
    }

    public function generateQR()
    {
        return view('merchant.qr');
    }

    public function apiKeyForm()
    {
        $cred = ApiCreds::whereMerchantId(merchant()->id)->first();
        if(!$cred){
            $cred = ApiCreds::create([
                'merchant_id' => merchant()->id,
                'access_key'  => (string) Str::uuid(),
                'mode'        => 0
            ]); 
        }
        return view('merchant.api_key_form',compact('cred'));
    }

    public function apiKeyGenerate()
    {
        $cred = ApiCreds::whereMerchantId(merchant()->id)->first();
        if(!$cred){
            ApiCreds::create([
                'merchant_id' => merchant()->id,
                'access_key'  => (string) Str::uuid(),
                'mode'        => 0
            ]); 
        }
        $cred->access_key = (string) Str::uuid();
        $cred->update();
        return back()->with('success','New api key has been generated');
    }

    public function serviceMode()
    {
        $cred = ApiCreds::whereMerchantId(merchant()->id)->first();
        if($cred->mode == 0){
            $cred->mode = 1;
            $msg = 'Service selected as Active Mode';
        } else{
            $cred->mode = 0;
            $msg = 'Service selected as Test Mode';
        }
        $cred->update();
        return response(__($msg));
    }

    public function profileSetting()
    {
        $user = merchant();
        return view('merchant.profile_setting',compact('user'));
    }

    public function profileUpdate(Request $request)
    {

        $request->validate([
            'business_name' => 'required',
            'name' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'address' => 'required',
            'photo' => 'image|mimes:png,jpeg,JPG,PNG'
            
        ]);

        $user          = merchant();
        $user->business_name    = $request->business_name;
        $user->name    = $request->name;

        $user->city    = $request->city;
        $user->zip     = $request->zip;
        $user->address = $request->address;
     
        if($request->photo){
            $user->photo = MediaHelper::handleMakeImage($request->photo,[300,300]);
        }
        $user->update();
        return back()->with('success','Profile Updated');
    }

    public function changePassword()
    {
        return view('merchant.change_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate(['old_pass'=>'required','password'=>'required|min:6|confirmed']);
        $user = merchant();
        if (Hash::check($request->old_pass, $user->password)) {
            $password = bcrypt($request->password);
            $user->password = $password;
            $user->save();
            return back()->with('success', 'Password has been changed');
        } else {
            return back()->with('error', 'The old password doesn\'t match!');
        }
    }

    public function transactions()
    {
        $remark = request('remark');
        $search = request('search');
        $transactions = Transaction::where('user_id',merchant()->id)->where('user_type',2)
                        ->when($remark,function($q) use($remark){
                            return $q->where('remark',$remark);
                        })
                        ->when($search,function($q) use($search){
                            return $q->where('trnx',$search);
                        })
                       ->latest()
                       ->paginate(15);

        return view('merchant.transactions',compact('transactions'));
    }

    public function trxDetails($id)
    {
        $transaction = Transaction::find($id);
        if(!$transaction){
            return response('empty');
        }
        return view('merchant.trx_details',compact('transaction'));
    }

    public function downloadQR($email)
    {
        $file = generateQR($email);
        $file = file_get_contents($file);
        $image = Image::make($file);
        $extension = str_replace('image/','',$image->mime);
        $filename = 'QrCode_'.$email.'_.'.$extension;
        $qrCode = $image->opacity(100)->fit(350,350);
        $qrCode->encode('jpg');
    
        $headers = [
            'Content-Type' => $image->mime,
            'Content-Disposition' => 'attachment; filename='.$filename,
        ];
        return response()->stream(function() use ($qrCode) {
            echo $qrCode;
        }, 200, $headers);
    }

    public function twoStep()
    {
        return view('merchant.twostep.two_step');
    }

    public function twoStepSendCode(Request $request)
    {
        $request->validate(['password'=>'required|confirmed']);
        $user = merchant();
        if (Hash::check($request->password, $user->password)) {
            $code = randNum();
            $user->two_fa_code = $code;
            $user->update();
            sendSMS($user->phone,trans('Your two step authentication OTP is : ').$code,Generalsetting::value('contact_no'));
            return redirect(route('merchant.two.step.verify'))->with('success','OTP code is sent to your phone.');
        } else {
            return back()->with('error', 'The password doesn\'t match!');
        }

    }
    public function twoStepVerifyForm()
    {
        return view('merchant.twostep.verify');
    }

    public function twoStepVerifySubmit(Request $request)
    {
        $request->validate(['code'=>'required']);
        $user = merchant();
        if($request->code != $user->two_fa_code){
            return back()->with('error','Invalid OTP');
        }
        if($user->two_fa_status == 1){
            $user->two_fa_status = 0;
            $msg = __('Your two step authentication is de-activated');
        }else{
            $user->two_fa_status = 1;
            $msg = __('Your two step authentication is activated');
        }
        $user->save();
        return redirect(route('merchant.two.step'))->with('success',$msg);
    }

    public function kycForm()
    {
        if(merchant()->kyc_status == 2) return back()->with('error','You have already submitted the KYC data.');
        if(merchant()->kyc_status == 1) return back()->with('error','Your KYC data is already verified.');
        $kycForm = KycForm::where('user_type',2)->get();
        return view('merchant.kyc.kyc_form',compact('kycForm'));
    }

    public function kycFormSubmit(Request $request)
    {
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

        $request->validate($rules);
        $user = merchant();
        $user->kyc_info = $data;
        $user->kyc_status = 2;
        $user->save();
        return redirect(route('merchant.dashboard'))->with('success','KYC data has been submitted for review.');
    }

    public function moduleOff($module)
    {
        $key = str_replace('_','-',$module);
        $mod = Module::where('module',$key)->first();
        if($mod &&  $mod->status == 1){
            return redirect(url('merchant/'.$key));
        }
        return view('merchant.module_off',compact('module'));
    }

    

}

