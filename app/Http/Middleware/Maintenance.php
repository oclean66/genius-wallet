<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Generalsetting;

class Maintenance
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
        $gs = Generalsetting::first();
        if($gs->is_maintenance == 1){
            if($request->expectsJson()){
                return response()->json([
                    'success' => false,
                    'message' => 'Site is in now maintainance mode',
                    'response'=> [
                        'maintainace' => true
                    ]
                ]);
            }
            return redirect(route('front.maintenance'));
        }
        return $next($request);
    }
}
