<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\Generalsetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\UserResource;

class AuthController extends ApiController
{
     /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $gs        = Generalsetting::first();
        $countries = Country::query();
        $name      = $countries->pluck('name')->toArray();

        if($gs->registration == 0){
          return $this->sendError('Registration Error',['Registration is currently off.' ]);
        }

        $validator = Validator::make($request->all(), [
            'name'                => 'required',
            'email'               => ['required','email','unique:users',$gs->allowed_email != null ? 'email_domain:'.$request->email:''],
            'dial_code'           => 'required',
            'phone'               => 'required',
            'country'             => 'required|in:'.implode(',',$name),
            'address'             => 'required',
            'password'            => 'required|min:6|confirmed',
        ],
        [
          'email.email_domain'    => 'Allowed emails are only within : '.$gs->allowed_email,
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors());       
        }
   
        $data = $request->only('name','email','dial_code','phone','country','address','password');

        $currency               = $countries->where('name',$request->country)->value('currency_id');
        $data['phone']          = $request->dial_code.$request->phone;
        $data['password']       = bcrypt($request->password);
        $data['email_verified'] = $gs->is_verify == 1 ? 0:1;
        $user = User::create($data);
        
        $success['token'] =  $user->createToken('wallet')->plainTextToken;
        $success['user']  =  new UserResource($user);

        Wallet::create([
            'user_id'     => $user->id,
            'user_type'   => 1,
            'currency_id' => $currency,
            'balance'     => 0
        ]);

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

       return $this->sendResponse($success, 'User registered successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('wallet')->plainTextToken; 
            $success['user']  =  new UserResource($user);
            return $this->sendResponse($success, 'Login successful.');
        } 
        else{ 
            return $this->sendError('Error.', ['Unauthorised access']);
        } 
    }

    public function logout(Request $request)
    {
        if(!$request->user()){
            return $this->sendError('Error.', ['Unauthorised access']);
        }
        $request->user()->currentAccessToken()->delete();
        if($request->user()->two_fa_status == 1){
          $request->user()->two_fa = 1;
          $request->user()->save();
        }
        Auth::logout();
        return $this->sendResponse(['success'], 'Logout successful.');
    }

    public function forgotPasswordSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors());       
        }
   
        $exist = User::where('email',$request->email)->first();
        if(!$exist){
            return $this->sendError('error',['Sorry! Email doesn\'t exist']);
        }

        $exist->verify_code = randNum();
        $exist->save();

        @email([
          'email'   => $exist->email,
          'name'    => $exist->name,
          'subject' => __('Password Reset Code'),
          'message' => __('Password reset code is : ').$exist->verify_code,
        ]);

        $success['email'] = $exist->email;
        return $this->sendResponse($success,'Reset code has been sent to email.');
    }

    public function verifyCodeSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|integer',
            'email'=> 'required|email'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors());       
        }

        $user = User::where('email',$request->email)->first();
        if(!$user){
            return $this->sendError('error',['User doesn\'t exist']);
        }

        if($user->verify_code != $request->code){
            return $this->sendError('error',['Invalid verification code.']);
        }
        $success['code'] = $request->code;
        $success['email'] = $request->email;
        return $this->sendResponse($success,'Reset code has been verified');
    }

    public function resetPasswordSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=>'required|email',
            'code'=>'required|integer',
            'password'=>'required|confirmed|min:6'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors());       
        }
        $user = User::where('email',$request->email)->first();
        if(!$user || !$request->code){
          return $this->sendError('Error', ['Invalid request']);
        }
        $user->password = bcrypt($request->password);
        $user->update();
        return $this->sendResponse(['success'],'Password reset successful.');
    }

    public function twoStepVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'=>'required|integer',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors());       
        }
        $user = auth()->user();
        if($request->code != $user->two_fa_code){
            return $this->sendError('Error',['Invalid OTP']);
        }
        $user->two_fa = 0;
        $user->save();

        return $this->sendResponse(['success'],'Two step authentication verified');
    }

    public function twoStepResendCode()
    {
        $code = randNum();
        $user = auth()->user();
        $user->two_fa_code = $code;
        $user->update();
        sendSMS($user->phone,'Your two step authentication OTP is : '.$code,Generalsetting::value('contact_no'));
        return $this->sendResponse(['success'=>true,'code'=>$code],'OTP code is sent to your phone.');
    }


    public function verifyEmailSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'=>'required|integer',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors());       
        }
        $user = auth()->user();
        if(!$user){
          return $this->sendError('Error',['User doesn\'t exist']);
        }

        if($user->verify_code != $request->code){
          return $this->sendError('Error',['Invalid verification code.']);
        }

        $user->verify_code = null;
        $user->email_verified = 1;
        $user->save();

        return $this->sendResponse(['success'],'Email has been verified');

    }
    
    public function verifyEmailResendCode()
    {
        $code = randNum();
        $user = auth()->user();
        $user->verify_code = $code;
        $user->update();
       
        @email([
          'email'   => $user->email,
          'name'    => $user->name,
          'subject' => __('Email Verification Code'),
          'message' => __('Email Verification Code is : '). $user->verify_code,
         ]);

        return $this->sendResponse(['success'],'Verify code resent to your email.');
    }

}
