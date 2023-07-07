<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Addon;
use Illuminate\Http\Request;

class AgentAddon
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
        $code = collect(request()->segments())->first();
        $addon = Addon::where('code', $code)->first();
        if($addon && $addon->status == 0){
            auth()->guard('agent')->logout();
            return redirect()->route('agent.off_module');
        }
        return $next($request);
    }
}
