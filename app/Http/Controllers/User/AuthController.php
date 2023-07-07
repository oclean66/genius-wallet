<?php

namespace App\Http\Controllers\User;

use Auth;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Country;
use App\Models\LoginLogs;
use Illuminate\Http\Request;
use App\Models\Generalsetting;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AuthRequest;
use Illuminate\Support\Facades\Config;

class AuthController extends Controller
{
    public function __construct()
    {  
        $this->middleware('guest:web', ['except' => ['logout']]);
    }

    public function registerForm()
    {
      $gs = Generalsetting::first();
      if($gs->registration == 0){
        return back()->with('error','Registration is currently off.');
      }
      $countries = Country::get();
      $info = @loginIp();
      
      return view('user.register',compact('countries','info'));
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
        'name' => 'required',
        'email' => ['required','email','unique:users',$gs->allowed_email != null ? 'email_domain:'.$request->email:''],
        'phone' => 'required',
        'country' => 'required|in:'.implode(',',$name),
        'address' => 'required',
        'password' => 'required|min:6|confirmed',
        'g-recaptcha-response' => [$gs->recaptcha ? 'required':'']
      ],
      [
        'email.email_domain'=>'Allowed emails are only within : '.$gs->allowed_email,
        'g-recaptcha-response.required'=>'Please verify that you are not a robot.'
       ]);

      $currency = $countries->where('name',$request->country)->value('currency_id');
      $data['phone'] = $request->dial_code.$request->phone;
      $data['password'] = bcrypt($request->password);
      $data['email_verified'] = $gs->is_verify == 1 ? 0:1;
      $user = User::create($data);

      Wallet::create([
        'user_id' => $user->id,
        'user_type' => 1,
        'currency_id' => $currency,
        'balance' => 0
      ]);

      session()->flush('success','Registration successful');
      Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password]);

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

      return redirect(route('user.dashboard'));
    }

    public function showLoginForm()
    {
      return view('user.login');
    }

    public function login(AuthRequest $request)
    {
      if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
        LoginLogs::create([
            'user_id' => auth()->id(),
            'ip' => @loginIp()->geoplugin_request,
            'country' => @loginIp()->geoplugin_countryName,
            'city' => @loginIp()->geoplugin_city,
        ]);  
     }else{
        return back()->with('error',__("Credentials Doesn\'t Match !"));   
     }

     return redirect(route('user.dashboard'));

    }

    public function logout()
    {
        $auth = Auth::guard('web');
        if($auth->user()->two_fa_status == 1){
          $auth->user()->two_fa = 1;
          $auth->user()->save();
        }
        Auth::guard('web')->logout();
        return redirect('/user/login');
    }

    public function forgotPassword()
    {
      return view('user.forgot_password');
    }

    public function forgotPasswordSubmit(Request $request)
    {
        $request->validate(['email'=>'required|email']);
        $exist = User::where('email',$request->email)->first();
        if(!$exist){
          return back()->with('error','Sorry! Email doesn\'t exist');
        }

        $exist->verify_code = randNum();
        $exist->save();

        @email([
          'email'   => $exist->email,
          'name'    => $exist->name,
          'subject' => __('Password Reset Code'),
          'message' => __('Password reset code is : ').$exist->verify_code,
        ]);
        session()->put('email',$exist->email);
        return redirect(route('user.verify.code'))->with('success','A password reset code has been sent to your email.');
    }

    public function verifyCode()
    {
      return view('user.verify_code');
    }

    public function verifyCodeSubmit(Request $request)
    {
        $request->validate(['code' => 'required|integer']);
        $user = User::where('email',session('email'))->first();
        if(!$user){
            return back()->with('error','User doesn\'t exist');
        }

        if($user->verify_code != $request->code){
            return back()->with('error','Invalid verification code.');
        }
        session()->put('reset',true);
        return redirect(route('user.reset.password'));
    }

    public function resetPassword()
    {
        if(!session('reset')){
            return redirect(route('user.verify.code'));
        }
        return view('user.reset_password');
    }

    public function resetPasswordSubmit(Request $request)
    {
        if(!session('reset')){
            return redirect(route('user.forgot.password'));
        }
        $request->validate(['password'=>'required|confirmed|min:6']);
        $user = User::where('email',session('email'))->first();
        $user->password = bcrypt($request->password);
        $user->update();
        session()->forget('reset');
        return redirect(route('user.login'))->with('success','Password reset successful.');
    }


}
