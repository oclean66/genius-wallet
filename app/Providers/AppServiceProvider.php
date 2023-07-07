<?php

namespace App\Providers;

use App\Models\Agent;
use App\Models\User;
use App\Models\Escrow;
use App\Models\Deposit;
use App\Models\FundRequest;
use App\Models\Merchant;
use App\Models\SiteContent;
use App\Models\Withdrawals;
use App\Models\RequestMoney;
use App\Models\SupportTicket;
use App\Models\Generalsetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        view()->composer('*',function($settings){
            $settings->with('gs', cache()->remember('generalsettings', now()->addDay(), function () {
                return Generalsetting::first();
            }));

        });

        view()->composer('admin.partials.sidebar', function ($view) {
            $view->with([
                'disputed'                =>  Escrow::whereStatus(3)->count(),
                'pending_user_kyc'        =>  User::whereStatus(1)->where('kyc_status',2)->count(),
                'pending_merchant_kyc'    =>  Merchant::whereStatus(1)->where('kyc_status',2)->count(),
                'pending_withdraw'        =>  Withdrawals::whereStatus(0)->count(),
                'pending_user_ticket'     =>  SupportTicket::where('user_type',1)->whereStatus(0)->whereHas('messages')->count(),
                'pending_merchant_ticket' =>  SupportTicket::where('user_type',2)->whereStatus(0)->whereHas('messages')->count(),
                'pending_agent_ticket'    =>  SupportTicket::where('user_type',3)->whereStatus(0)->whereHas('messages')->count(),
                'pending_deposits'        =>  Deposit::where('status','pending')->count(),
                
            ]);
        });

        view()->composer('user.partials.header', function ($view) {
            $view->with([
                'request_money'  =>  RequestMoney::where('receiver_id',auth()->id())->whereStatus(0)->count(),
                'pending_escrow' =>  Escrow::where('recipient_id',auth()->id())->whereStatus(0)->count(),
            ]);
        });
        
        view()->composer(['frontend.partials.header','frontend.contact','user.invoice.invoice','user.invoice.view'], function ($view) {
            $view->with([
                'contact'  =>  SiteContent::where('slug','contact')->first(),
            ]);
        });


        Validator::extend('email_domain', function($attribute, $value, $parameters, $validator) {
            $gs = Generalsetting::first();
        	$allowedEmailDomains = explode(',',$gs->allowed_email);
        	return in_array(explode('@', $parameters[0])[1] , $allowedEmailDomains);
        });

       
    }
}
