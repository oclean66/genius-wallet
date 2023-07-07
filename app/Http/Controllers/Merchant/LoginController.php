<?php

namespace App\Http\Controllers\Merchant;

use App\Models\Wallet;
use App\Models\Country;
use App\Models\Merchant;
use App\Models\LoginLogs;
use Illuminate\Http\Request;
use App\Models\Generalsetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


class LoginController extends Controller
{
    public function __construct()
    {
      $this->middleware('guest:merchant', ['except' => ['logout']]);
    }

    public function registerForm()
    {
      $gs = Generalsetting::first();
      if($gs->registration == 0){
        return back()->with('error','Registration is currently off.');
      } 
      $countries = Country::get();
      $info = loginIp();
      return view('merchant.auth.register',compact('countries','info'));
    }

    public function register(Request $request)
    {
      $gs = Generalsetting::first();
      if($gs->registration == 0){
        return back()->with('error','Registration is currently off.');
      } 

      $countries = Country::query();
      $name = $countries->pluck('name')->toArray();
      $data = $request->validate([
        'business_name' => 'required',
        'name' => 'required',
        'email' => ['required','email','unique:merchants',$gs->allowed_email != null ? 'email_domain:'.$request->email:''],
        'dial_code' => 'required',
        'phone' => 'required',
        'country' => 'required|in:'.implode(',',$name),
        'address' => 'required',
        'password' => 'required|min:4|confirmed'
      ],['email.email_domain'=>'Allowed emails are only within : '.$gs->allowed_email]);
      
      $currency = $countries->where('name',$request->country)->value('currency_id');
      $data['phone'] = $request->dial_code.$request->phone;
      $data['password'] = bcrypt($request->password);
      $data['email_verified	'] = $gs->is_verify == 1 ? 0:1;
      $user = Merchant::create($data);
   
      Wallet::create([
        'user_id' => $user->id,
        'user_type' => 2,
        'currency_id' => $currency,
        'balance' => 0
      ]);
      
      session()->flush('success','Registration successful');
      Auth::guard('merchant')->attempt(['email' => $request->email, 'password' => $request->password]);

      if($gs->is_verify == 1){
        $user->verify_code = randNum();
        $user->save();
        
        @email([
          'email'   => $user->email,
          'name'    => $user->name,
          'subject' => __('Email Verification Code'),
          'message' => __('Email Verification Code is : '). $user->verify_code,
        ]);

      }
      return redirect(route('merchant.dashboard'));
    }


    public function showLoginForm()
    {
      return view('merchant.auth.login');
    }

    public function login(Request $request)
    {
      $request->validate( [
        'email'   => 'required|email',
        'password' => 'required'
      ]);


      if (Auth::guard('merchant')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
        if($request->remember){
          Cache::put('remember_login',['email' => $request->email, 'password' => $request->password], 60000);
        }
        LoginLogs::create([
          'merchant_id' => merchant()->id,
          'ip' => @loginIp()->geoplugin_request,
          'country' => @loginIp()->geoplugin_countryName,
          'city' => @loginIp()->geoplugin_city,
        ]);
        return redirect(route('merchant.dashboard'));
      }
      return back()->with('error','Sorry! Credentials Mismatch.');
    }

    public function forgotPasswordForm()
    {
      return view('merchant.auth.forgot_passowrd');
    }

    public function forgotPasswordSubmit(Request $request)
    {
       $request->validate(['email'=>'required|email']);
       $existMerchant = Merchant::where('email',$request->email)->first();
       if(!$existMerchant){
         return back()->with('error','Sorry! Email doesn\'t exist');
       }

       $existMerchant->verify_code = randNum();
       $existMerchant->save();

      @email([
        'email'   => $existMerchant->email,
        'name'    => $existMerchant->name,
        'subject' => __('Password Reset Code'),
        'message' => __('Password reset code is : ').$existMerchant->verify_code,
      ]);
      session()->put('email',$existMerchant->email);
      return redirect(route('merchant.verify.code'))->with('success','A password reset code has been sent to your email.');
    }

    public function verifyCode()
    {
       return view('merchant.auth.verify_code');
    }

    public function verifyCodeSubmit(Request $request)
    {
       $request->validate(['code' => 'required|integer']);
       $user = Merchant::where('email',session('email'))->first();
       if(!$user){
         return back()->with('error','User doesn\'t exist');
       }

       if($user->verify_code != $request->code){
         return back()->with('error','Invalid verification code.');
       }
       return redirect(route('merchant.reset.password'));
    }

    public function resetPassword()
    {
      return view('merchant.auth.reset_password');
    }

    public function resetPasswordSubmit(Request $request)
    {
       $request->validate(['password'=>'required|confirmed|min:6']);
       $merchant = Merchant::where('email',session('email'))->first();
       $merchant->password = bcrypt($request->password);
       $merchant->update();
       return redirect(route('merchant.login'))->with('success','Password reset successful.');
    }

    public function logout()
    {
      $auth = Auth::guard('merchant');
      if($auth->user()->two_fa_status == 1){
        $auth->user()->two_fa = 1;
        $auth->user()->save();
      }
      $auth->logout();
      return redirect('/merchant/login');
    }

   
}
