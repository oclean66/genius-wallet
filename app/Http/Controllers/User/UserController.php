<?php

namespace App\Http\Controllers\User;

https://demo.royalscripts.com/wallet_api/etc
use Image;
use App\Models\Module;
use App\Models\Wallet;
use App\Models\Deposit;
use App\Models\KycForm;
use App\Models\Transaction;
use App\Models\Withdrawals;
use App\Helpers\MediaHelper;
use Illuminate\Http\Request;
use App\Models\Generalsetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User\UserResource;

class UserController extends Controller{

    public function __construct(UserResource $resource)
    {
        $this->resource = $resource;

    }

    public function trnx()
    {
       return Transaction::where('user_id',auth()->id())->where('user_type',1)->with('currency');
    }

    public function index()
    {
        $wallets = Wallet::where('user_id',auth()->id())->where('user_type',1)->with('currency')->get();
        $transactions = $this->trnx()->latest()->take(8)->get();

        $amount = collect([]);
        $this->trnx()->where('remark','transfer_money')->get()->map(function($q) use($amount){
            $amount->push((float) amountConv($q->amount,$q->currency));
        });
        $totalTransferMoney = $amount->sum();

        $exchange = collect([]);
        $this->trnx()->where('remark','money_exchange')->get()->map(function($q) use($exchange){
            $exchange->push((float) amountConv($q->amount,$q->currency));
        });
        $totalExchange = $exchange->sum();

        $deposit = collect([]);
        Deposit::where('user_id',auth()->id())->with('currency')->get()->map(function($q) use($deposit){
            $deposit->push((float) amountConv($q->amount,$q->currency));
        });
        $totalDeposit = $deposit->sum();

        $withdraw = collect([]);
        Withdrawals::where('user_id',auth()->id())->with('currency')->get()->map(function($q) use($withdraw){
            $withdraw->push((float) amountConv($q->amount,$q->currency));
        });
        $totalWithdraw = $withdraw->sum();

        return view('user.dashboard',compact('wallets','transactions','totalTransferMoney','totalDeposit','totalWithdraw','totalExchange'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('user.profile',compact('user'));
    }


    public function profileSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
        ]);

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

        return back()->with('success','Profile has been updated');
    } 

    public function changePass(Request $request)
    {
        $request->validate(['old_pass'=>'required','password'=>'required|min:6|confirmed']);
        $user = auth()->user();
        if (Hash::check($request->old_pass, $user->password)) {
            $password = bcrypt($request->password);
            $user->password = $password;
            $user->save();
            return back()->with('success', 'Password has been changed');
        } else {
            return back()->with('error', 'The old password doesn\'t match!');
        }
    }

    public function moduleOff($module)
    {
        $key = str_replace('_','-',$module);
        $mod = Module::where('module',$key)->first();
        if($mod &&  $mod->status == 1){
            return redirect(url('user/'.$key));
        }
        return view('user.module_off',compact('module'));
    }


    public function kycForm()
    {
        if(auth()->user()->kyc_status == 2) return back()->with('error','You have already submitted the KYC data.');
        if(auth()->user()->kyc_status == 1) return back()->with('error','Your KYC data is already verified.');
        $kycForm = KycForm::where('user_type',1)->get();
        return view('user.kyc_form',compact('kycForm'));
    }

    public function kycFormSubmit(Request $request)
    {
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

        $request->validate($rules);
        $user = auth()->user();
        $user->kyc_info = $data;
        $user->kyc_status = 2;
        $user->save();
        return redirect(route('user.dashboard'))->with('success','KYC data has been submitted for review.');
    }

    public function generateQR()
    {
        return view('user.qr');
    }

    public function transactions()
    {
        $remark = request('remark');
        $search = request('search');

        $transactions = Transaction::where('user_id',auth()->id())->where('user_type',1)
        ->when($remark,function($q) use($remark){
            return $q->where('remark',$remark);
        })
        ->when($search,function($q) use($search){
            return $q->where('trnx',$search);
        })
        ->with('currency')->latest()->paginate(15);
    
        return view('user.transactions',compact('transactions','search'));
    }

    public function trxDetails($id)
    {
        
        $transaction = Transaction::where('id',$id)->where('user_id',auth()->id())->first();
        if(!$transaction){
            return response('empty');
        }
        return view('user.trx_details',compact('transaction'));
    }

    public function twoStep()
    {
        return view('user.twostep.two_step');
    }

    public function twoStepSendCode(Request $request)
    {
        $request->validate(['password'=>'required|confirmed']);
        $user = auth()->user();
        if (Hash::check($request->password, $user->password)) {
            $code = randNum();
            $user->two_fa_code = $code;
            $user->update();
            sendSMS($user->phone,trans('Your two step authentication OTP is : ').$code,Generalsetting::value('contact_no'));
            return redirect(route('user.two.step.verify'))->with('success','OTP code is sent to your phone.');
        } else {
            return back()->with('error', 'The password doesn\'t match!');
        }

    }
    public function twoStepVerifyForm()
    {
        return view('user.twostep.verify');
    }

    public function twoStepVerifySubmit(Request $request)
    {
        $request->validate(['code'=>'required']);
        $user = auth()->user();
        if($request->code != $user->two_fa_code){
            return back()->with('error','Invalid OTP');
        }
        if($user->two_fa_status == 1){
            $user->two_fa_status = 0;
            $user->two_fa= 0;
            $msg = 'Your two step authentication is de-activated';
        }else{
            $user->two_fa_status = 1;
            $msg = 'Your two step authentication is activated';
        }
        $user->save();
        return redirect(route('user.two.step'))->with('success',$msg);
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

   
}