<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Generalsetting;

class TwoStepApi
{

    public function handle(Request $request, Closure $next)
    {
        $two_fa = Generalsetting::value('two_fa');
       
        if($two_fa){
          
            $user = $request->user();
            if($user->two_fa_status == 1 && $user->two_fa == 1){
                $code = randNum();
                $user->two_fa_code = $code;
                $user->update();
                sendSMS($user->phone,'Your two step authentication OTP is : '.$code,Generalsetting::value('contact_no'));

                 $response = [
                    'success'    => true,
                    'message'    => 'Your two step authentication OTP is sent to your phone number.',
                    'response'   => ['two_step' => true,'code'=>$code],
                ];
                
                 return response()->json($response);
            }
            return $next($request);
        }
        return $next($request);
    }
}
