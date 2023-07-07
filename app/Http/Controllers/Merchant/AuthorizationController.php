<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;
use App\Models\Generalsetting;
use App\Http\Controllers\Controller;

class AuthorizationController extends Controller
{
  public function __construct()
  {
      $this->middleware('guest', ['except' => ['logout']]);
  }
    public function verifyEmail()
    {
      return view('merchant.auth.email_verify_code');
    }

    public function verifyEmailSubmit(Request $request)
    {
        $request->validate(['code' => 'required|integer']);
        $user = merchant();
        if(!$user){
          return back()->with('error','User doesn\'t exist');
        }

        if($user->verify_code != $request->code){
          return back()->with('error','Invalid verification code.');
        }

        $user->verify_code = null;
        $user->email_verified = 1;
        $user->save();
        return redirect(route('merchant.dashboard'));

    }

    public function verifyEmailResendCode()
    {
        $code = randNum();
        $user = merchant();
        $user->verify_code = $code;
        $user->update();
       
        @email([
          'email'   => $user->email,
          'name'    => $user->name,
          'subject' => __('Email Verification Code'),
          'message' => __('Email Verification Code is : '). $user->verify_code,
         ]);

        return back()->with('success','Verify code resent to your email.');
    }

    public function twoStep()
    {
      return view('merchant.twostep.otp_code');
    }

    public function twoStepVerify(Request $request)
    {
        $request->validate(['code'=>'required']);
        $user = merchant();
        if($request->code != $user->two_fa_code){
            return back()->with('error','Invalid OTP');
        }
        $user->two_fa = 0;
        $user->save();
        return redirect(route('merchant.dashboard'));
    }

    public function twoStepResendCode()
    {
        $code = randNum();
        $user = merchant();
        $user->two_fa_code = $code;
        $user->update();
        sendSMS($user->phone,'Your two step authentication OTP is : '.$code,Generalsetting::value('contact_no'));
        return back()->with('success','OTP code is sent to your phone.');
    }
}
