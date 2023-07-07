<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Module;
use Illuminate\Http\Request;
use App\Models\Generalsetting;

class KycRestriction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $gs = Generalsetting::first(['kyc']);
        $key = collect(request()->segments())->last();
        $module = Module::where('module',$key)->firstOrFail();
      
        if($request->routeIs('user.*')){
            $user = auth()->user();
            $pref = 'user';
        }else{
            $user = merchant();
            $pref = 'merchant';
        }

        if($request->expectsJson()){
            $user = $request->user();
        }

        if($gs->kyc){
            if($module->kyc == 1)
            {
                if($user->kyc_status == 0){
                    if($request->expectsJson()){
                        $response = [
                            'success' => true,
                            'message' => 'Please submit the KYC data.',
                            'response'   => [
                               'success'
                            ],
                        ];
                
                        return response()->json($response);
                    }
                    return redirect(route($pref.'.kyc.form'));
                }
                elseif( $user->kyc_status == 2){
                    if($request->expectsJson()){
                        $response = [
                            'success' => true,
                            'message' => 'Your KYC information is under reviewing',
                            'response'   => [
                               'success'
                            ],
                        ];
                
                        return response()->json($response);
                    }
                    return redirect(route($pref.'.dashboard'))->with('error','Your KYC information is under reviewing.');
                }
                elseif( $user->kyc_status == 3){
                    if($request->expectsJson()){
                        $response = [
                            'success' => true,
                            'message' => 'Your KYC information is rejected. Please re-submit.',
                            'response'   => [
                               'success'
                            ],
                        ];
                
                        return response()->json($response);
                    }
                    return redirect(route($pref.'.kyc.form'))->with('error','Your KYC information is rejected. Please re-submit.');
                }
                else{
                    return $next($request);
                } 
            }
            return $next($request);
           
        }
        return $next($request);
        
    }
}
