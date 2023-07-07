<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\SmsController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\KycManageController;
use App\Http\Controllers\Admin\ManageRoleController;
use App\Http\Controllers\Admin\ManageUserController;
use App\Http\Controllers\Admin\SeoSettingController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\ManageAgentController;
use App\Http\Controllers\Admin\ManageStaffController;
use App\Http\Controllers\Admin\SiteContentController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\ManageChargeController;
use App\Http\Controllers\Admin\ManageEscrowController;
use App\Http\Controllers\Admin\ManageModuleController;
use App\Http\Controllers\Admin\ManageTicketController;
use App\Http\Controllers\Admin\AdminLanguageController;
use App\Http\Controllers\Admin\ManageCountryController;
use App\Http\Controllers\Admin\ManageDepositController;
use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Controllers\Admin\ManageCurrencyController;
use App\Http\Controllers\Admin\ManageMerchantController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\WithdrawMethodController;
use App\Http\Controllers\Admin\AddonController;
use Illuminate\Support\Facades\Artisan;

// ************************** ADMIN SECTION START ***************************//

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login',            [LoginController::class,'showLoginForm'])->name('login');
    Route::post('/login',           [LoginController::class,'login']);
    
    Route::get('/forgot-password',   [LoginController::class,'forgotPasswordForm'])->name('forgot.password');
    Route::post('/forgot-password',   [LoginController::class,'forgotPasswordSubmit']);
    Route::get('forgot-password/verify-code',     [LoginController::class,'verifyCode'])->name('verify.code');
    Route::post('forgot-password/verify-code',     [LoginController::class,'verifyCodeSubmit']);
    Route::get('reset-password',     [LoginController::class,'resetPassword'])->name('reset.password');
    Route::post('reset-password',     [LoginController::class,'resetPasswordSubmit']);

    Route::middleware('auth:admin')->group(function(){
        Route::get('/logout',[LoginController::class,'logout'])->name('logout');

        //------------ ADMIN DASHBOARD & PROFILE SECTION ------------
        Route::get('/',                 [AdminController::class,'index'])->name('dashboard');
        Route::get('/profile',          [AdminController::class,'profile'])->name('profile');
        Route::post('/profile/update',  [AdminController::class,'profileupdate'])->name('profile.update');
        Route::get('/password',         [AdminController::class,'passwordreset'])->name('password');
        Route::post('/password/update', [AdminController::class,'changepass'])->name('password.update');
        //------------ ADMIN DASHBOARD & PROFILE SECTION ENDS ------------


        //profit report
        Route::get('/profit-reports',                 [AdminController::class,'profitReports'])->name('profit.report')->middleware('permission:profit report');

        //transactions
        Route::get('/transaction-report',                 [AdminController::class,'transactions'])->name('transactions')->middleware('permission:transactions');

        //==================================== PAGE SECTION  ==============================================//

        Route::get('page',       [PageController::class,'index'])->name('page.index')->middleware('permission:manage page');
        Route::get('page/create',       [PageController::class,'create'])->name('page.create')->middleware('permission:page create');
        Route::post('page/store',       [PageController::class,'store'])->name('page.store')->middleware('permission:page store');
        Route::get('page/edit/{page}',       [PageController::class,'edit'])->name('page.edit')->middleware('permission:page edit');
        Route::put('page/update/{page}',       [PageController::class,'update'])->name('page.update')->middleware('permission:page update');
        Route::post('page/remove',       [PageController::class,'destroy'])->name('page.remove')->middleware('permission:page remove');

        //==================================== PAGE SECTION END ==============================================//

        //cookie
        Route::get('/manage-cookie',                 [AdminController::class,'cookie'])->name('cookie')->middleware('permission:manage cookie');
        Route::post('/manage-cookie',                 [AdminController::class,'updateCookie'])->name('update.cookie')->middleware('permission:update cookie');

        //manage blogs

        Route::get('blog-category',       [BlogCategoryController::class,'index'])->name('bcategory.index')->middleware('permission:manage blog-category');
     
        Route::post('blog-category/store',       [BlogCategoryController::class,'store'])->name('bcategory.store')->middleware('permission:store blog-category');
      
        Route::put('blog-category/update/{id}',       [BlogCategoryController::class,'update'])->name('bcategory.update')->middleware('permission:update blog-category');

        Route::get('blog',       [BlogController::class,'index'])->name('blog.index')->middleware('permission:manage blog');
        Route::get('blog/create',       [BlogController::class,'create'])->name('blog.create')->middleware('permission:blog create');
        Route::post('blog/store',       [BlogController::class,'store'])->name('blog.store')->middleware('permission:blog store');
        Route::get('blog/edit/{blog}',       [BlogController::class,'edit'])->name('blog.edit')->middleware('permission:blog edit');
        Route::put('blog/update/{blog}',       [BlogController::class,'update'])->name('blog.update')->middleware('permission:blog update');
        Route::delete('blog-delete/{blog}',[BlogController::class,'destroy'])->name('blog.destroy')->middleware('permission:blog destroy');
        //==================================== Manage Currency ==============================================//

        Route::get('/manage-currency',[ManageCurrencyController::class,'index'])->name('currency.index')->middleware('permission:manage currency');

        Route::get('/add-currency',[ManageCurrencyController::class,'addCurrency'])->name('currency.add')->middleware('permission:add currency');

        Route::post('/add-currency',[ManageCurrencyController::class,'store'])->middleware('permission:add currency');

        Route::get('/edit-currency/{id}',[ManageCurrencyController::class,'editCurrency'])->name('currency.edit')->middleware('permission:edit currency');

        Route::post('/update-currency/{id}',[ManageCurrencyController::class,'updateCurrency'])->name('currency.update')->middleware('permission:update currency');

        Route::post('/update-currency-api',[ManageCurrencyController::class,'updateCurrencyAPI'])->name('currency.api.update')->middleware('permission:update currency api');


        // manage charges

            Route::get('/manage-charges',[ManageChargeController::class,'index'])->name('manage.charge')->middleware('permission:manage charges');

            Route::get('/edit-charge/{id}',[ManageChargeController::class,'editCharge'])->name('edit.charge')->middleware('permission:edit charge');

            Route::post('/update-charge/{id}',[ManageChargeController::class,'updateCharge'])->name('update.charge')->middleware('permission:update charge');


        //manage Country

            Route::get('/manage-country',[ManageCountryController::class,'index'])->name('country.index')->middleware('permission:manage country');

            Route::post('/add-country',[ManageCountryController::class,'store'])->name('country.store')->middleware('permission:add country');

            Route::post('/update-country',[ManageCountryController::class,'update'])->name('country.update')->middleware('permission:update country');


        //==================================== Manage Module ==============================================//

            Route::get('/manage-module',[ManageModuleController::class,'index'])->name('manage.module')->middleware('permission:manage modules');

            Route::post('/update-module',[ManageModuleController::class,'update'])->name('update.module')->middleware('permission:update module');



        //Manage Kyc

            Route::get('/manage-kyc-form',[KycManageController::class,'index'])->name('manage.kyc')->middleware('permission:manage kyc');

            Route::get('/manage-kyc-form/{user}',[KycManageController::class,'userKycForm'])->name('manage.kyc.user')->middleware('permission:manage kyc form');

            Route::post('/manage-kyc-form/{user}',[KycManageController::class,'kycForm'])->middleware('permission:kyc form add');

            Route::post('/kyc-form/update',[KycManageController::class,'kycFormUpdate'])->name('kyc.form.update')->middleware('permission:kyc form update');

            Route::post('/kyc-form/delete',[KycManageController::class,'deletedField'])->name('kyc.form.delete')->middleware('permission:kyc form delete');

            Route::get('/kyc-info/{user}',[KycManageController::class,'kycInfo'])->name('kyc.info')->middleware('permission:kyc info');
            Route::get('/kyc-info/{user}/{id}',[KycManageController::class,'kycDetails'])->name('kyc.details')->middleware('permission:kyc details');

            Route::post('/kyc-reject/{user}/{id}',[KycManageController::class,'rejectKyc'])->name('kyc.reject')->middleware('permission:kyc reject');

            Route::post('/kyc-approve/{user}/{id}',[KycManageController::class,'approveKyc'])->name('kyc.approve')->middleware('permission:kyc approve');




        //==================================== GENERAL SETTING SECTION ==============================================//


            Route::get('/general-settings',            [GeneralSettingController::class,'siteSettings'])->name('gs.site.settings')->middleware('permission:general setting');

            Route::post('/general-settings/update',     [GeneralSettingController::class,'update'])->name('gs.update')->middleware('permission:general settings update');

            Route::get('/general-settings/logo-favicon',[GeneralSettingController::class,'logo'])->name('gs.logo')->middleware('permission:general settings logo favicon');

            Route::get('/general-settings/menu-builder',  [GeneralSettingController::class,'menu'])->name('front.menu')->middleware('permission:menu builder');

            Route::get('/general-settings/maintenance', [GeneralSettingController::class,'maintenance'])->name('gs.maintenance')->middleware('permission:maintainance');

            Route::get('/general-settings/status/update/{value}', [GeneralSettingController::class,'StatusUpdate'])->name('gs.status')->middleware('permission:general settings status update');


        //==================================== GENERAL SETTING SECTION ==============================================//




        //==================================== EMAIL SETTING SECTION ==============================================//

            Route::get('/email-templates',      [EmailController::class,'index'])->name('mail.index')->middleware('permission:email templates');

            Route::get('/email-templates/{id}', [EmailController::class,'edit'])->name('mail.edit')->middleware('permission:template edit');

            Route::post('/email-templates/{id}',[EmailController::class,'update'])->name('mail.update')->middleware('permission:template update');

            Route::get('/email-config',         [EmailController::class,'config'])->name('mail.config')->middleware('permission:email config');

            Route::get('/group-email',           [EmailController::class,'groupEmail'])->name('mail.group.show')->middleware('permission:group email');

            Route::post('/groupemailpost',      [EmailController::class,'groupemailpost'])->name('group.submit')->middleware('permission:group email');



        //==================================== EMAIL SETTING SECTION END ==============================================//


        //sms settings
        Route::get('/sms-gateways',             [SmsController::class,'index'])->name('sms.index')->middleware('permission:sms gateways');

        Route::get('/sms-gateway/edit/{id}',    [SmsController::class,'edit'])->name('sms.edit')->middleware('permission:sms gateway edit');

        Route::post('/sms-gateway/update/{id}', [SmsController::class,'update'])->name('sms.update')->middleware('permission:sms gateway update');

        Route::get('/sms-templates',            [SmsController::class,'templates'])->name('sms.templates')->middleware('permission:sms templates');

        Route::get('/sms-template/edit/{id}',   [SmsController::class,'editTemplate'])->name('sms.template.edit')->middleware('permission:sms template edit');

        Route::post('/sms-template/update/{id}', [SmsController::class,'updateTemplate'])->name('sms.template.update')->middleware('permission:sms template update');

        //==================================== PAYMENTGATEWAY SETTING SECTION ==============================================//

        Route::get('/deposits',             [ManageDepositController::class,'deposits'])->name('deposit')->middleware('permission:manage deposit');

        Route::post('/approve-deposit',             [ManageDepositController::class,'approve'])->name('approve.deposit')->middleware('permission:approve deposit');

        Route::post('/reject-deposit',             [ManageDepositController::class,'reject'])->name('reject.deposit')->middleware('permission:reject deposit');


        Route::get('/payment-gateways',        [PaymentGatewayController::class,'index'])->name('gateway')->middleware('permission:manage payment gateway');

        Route::get('add/payment-gateway',        [PaymentGatewayController::class,'create'])->name('gateway.create')->middleware('permission:add payment gateway');

        Route::post('/store/payment-gateway',        [PaymentGatewayController::class,'store'])->name('gateway.store')->middleware('permission:store payment gateway');

        Route::get('/payment-gateway/edit/{paymentgateway}',        [PaymentGatewayController::class,'edit'])->name('gateway.edit')->middleware('permission:edit payment gateway');

        Route::post('/payment-gateway/update/{gateway}',        [PaymentGatewayController::class,'update'])->name('gateway.update')->middleware('permission:update payment gateway');

        //==================================== PAYMENTGATEWAY SETTING SECTION END ==============================================//


        //==================================== LANGUAGE SETTING SECTION ==============================================//

        // webiste language
        Route::resource('language', LanguageController::class)->middleware('permission:manage language');

        Route::post('add-translate/{id}', [LanguageController::class,'storeTranslate'])->name('translate.store')->middleware('permission:manage language');

        Route::post('update-translate/{id}', [LanguageController::class,'updateTranslate'])->name('translate.update')->middleware('permission:manage language');

        Route::post('remove-translate', [LanguageController::class,'removeTranslate'])->name('translate.remove')->middleware('permission:manage language');

        Route::post('language/status-update', [LanguageController::class,'statusUpdate'])->name('update-status.language')->middleware('permission:manage language');

        Route::post('language/remove', [LanguageController::class,'destroy'])->name('remove.language')->middleware('permission:manage language');

        // admin language
        Route::get('adminlanguage/status/{id1}/{id2}', [AdminLanguageController::class,'status'])->name('adminlanguage.status')->middleware('permission:manage language');

    
        //==================================== LANGUAGE SETTING SECTION END =============================================//



        //==================================== ADMIN SEO SETTINGS SECTION ====================================
            Route::resource('seo-setting', SeoSettingController::class)->middleware('permission:seo settings');
        //==================================== ADMIN SEO SETTINGS SECTION ENDS ====================================



        //==================================== USER SECTION  ==============================================//

            Route::get('manage-users', [ManageUserController::class,'index'])->name('user.index')->middleware('permission:manage user');

            Route::get('user-details/{id}', [ManageUserController::class,'details'])->name('user.details')->middleware('permission:edit user');

            Route::post('user-profile/update/{id}', [ManageUserController::class,'profileUpdate'])->name('user.profile.update')->middleware('permission:update user');

            Route::post('balance-modify', [ManageUserController::class,'modifyBalance'])->name('user.balance.modify')->middleware('permission:user balance modify');

            Route::get('user-login/{id}', [ManageUserController::class,'login'])->name('user.login')->middleware('permission:user login');

            Route::get('user-login/info/{id}', [ManageUserController::class,'loginInfo'])->name('user.login.info')->middleware('permission:user login logs');


        //merchant

            Route::get('manage-merchants', [ManageMerchantController::class,'index'])->name('merchant.index')->middleware('permission:manage merchant');

            Route::get('merchant-details/{id}', [ManageMerchantController::class,'details'])->name('merchant.details')->middleware('permission:edit merchant');

            Route::post('merchant/balance-modify', [ManageMerchantController::class,'modifyBalance'])->name('merchant.balance.modify')->middleware('permission:merchant balance modify');

            Route::post('merchant-profile/update/{id}', [ManageMerchantController::class,'profileUpdate'])->name('merchant.profile.update')->middleware('permission:update merchant');

            Route::get('merchant-login/{id}', [ManageMerchantController::class,'login'])->name('merchant.login')->middleware('permission:merchant login');

            Route::get('merchant-login/info/{id}', [ManageMerchantController::class,'loginInfo'])->name('merchant.login.info')->middleware('permission:merchant login logs');


        //================= Site Contents ======================

            Route::get('/frontend-sections',[SiteContentController::class,'index'])->name('frontend.index')->middleware('permission:site contents');

            Route::get('/frontend-section/edit/{id}',[SiteContentController::class,'edit'])->name('frontend.edit')->middleware('permission:edit site contents');

            Route::post('/frontend-section/content-update/{id}',[SiteContentController::class,'contentUpdate'])->name('frontend.content.update')->middleware('permission:site content update');

            Route::post('/frontend-section/sub-content-update/{id}',[SiteContentController::class,'subContentUpdate'])->name('frontend.sub-content.update')->middleware('permission:site sub-content update');

            Route::post('/frontend-section/sub-content/update-single',[SiteContentController::class,'subContentUpdateSingle'])->name('frontend.sub-content.single.update')->middleware('permission:site sub-content update');

            Route::post('/frontend-section/sub-content/remove',[SiteContentController::class,'subContentRemove'])->name('frontend.sub-content.remove')->middleware('permission:site sub-content update');

            Route::post('/frontend-section/status-update',[SiteContentController::class,'statusUpdate'])->name('frontend.status.update')->middleware('permission:section status update');


        //withdraw

            Route::get('withdraw/method',[WithdrawMethodController::class,'index'])->name('withdraw')->middleware('permission:withdraw method');

            Route::get('withdraw/method-create',[WithdrawMethodController::class,'create'])->name('withdraw.create')->middleware('permission:withdraw method create');

            Route::post('withdraw/method-create',[WithdrawMethodController::class,'store'])->middleware('permission:withdraw method create');

            Route::get('withdraw/method/search',[WithdrawMethodController::class,'index'])->name('withdraw.search')->middleware('permission:withdraw method search');

            Route::get('withdraw/edit/{id}',[WithdrawMethodController::class,'edit'])->name('withdraw.edit')->middleware('permission:withdraw method edit');

            Route::post('withdraw/update/{method}',[WithdrawMethodController::class,'update'])->name('withdraw.update')->middleware('permission:withdraw method update');

            Route::get('withdraw/pending',[WithdrawalController::class,'pending'])->name('withdraw.pending')->middleware('permission:pending withdraw');

            Route::get('withdraw/accepted',[WithdrawalController::class,'accepted'])->name('withdraw.accepted')->middleware('permission:accepted withdraw');

            Route::get('withdraw/rejected',[WithdrawalController::class,'rejected'])->name('withdraw.rejected')->middleware('permission:rejected withdraw');

            Route::post('withdraw/accept/{withdraw}',[WithdrawalController::class,'withdrawAccept'])->name('withdraw.accept')->middleware('permission:withdraw accept');

            Route::post('withdraw/reject/{withdraw}',[WithdrawalController::class,'withdrawReject'])->name('withdraw.reject')->middleware('permission:withdraw reject');




          //manage escrow

              Route::get('manage/escrow',[ManageEscrowController::class,'index'])->name('escrow.manage')->middleware('permission:manage escrow');

              Route::get('escrow/on-hold',[ManageEscrowController::class,'onHold'])->name('escrow.onHold')->middleware('permission:escrow on-hold');

              Route::get('escrow-disputed',[ManageEscrowController::class,'disputed'])->name('escrow.disputed')->middleware('permission:escrow disputed');

              Route::get('escrow-details/{id}',[ManageEscrowController::class,'details'])->name('escrow.details')->middleware('permission:escrow details');

              Route::post('escrow-details/{id}',[ManageEscrowController::class,'disputeStore'])->middleware('permission:dispute store');

              Route::get('file-download/{id}',   [ManageEscrowController::class,'fileDownload'])->name('escrow.file.download')->middleware('permission:escrow file download');

              Route::post('return-payment',   [ManageEscrowController::class,'returnPayment'])->name('escrow.return.payment')->middleware('permission:escrow return payment');

              Route::get('escrow-close/{id}',[ManageEscrowController::class,'close'])->name('escrow.close')->middleware('permission:escrow close');



          //role manage

              Route::get('manage/role',[ManageRoleController::class,'index'])->name('role.manage')->middleware('permission:manage role');

              Route::get('create/role',[ManageRoleController::class,'create'])->name('role.create')->middleware('permission:create role');

              Route::post('create/role',[ManageRoleController::class,'store'])->middleware('permission:create role');

              Route::get('edit/permissions/{id}',[ManageRoleController::class,'edit'])->name('role.edit')->middleware('permission:edit permissions');

              Route::post('update/permissions/{id}',[ManageRoleController::class,'update'])->name('role.update')->middleware('permission:update permissions');



             //manage staff

              Route::get('manage/staff',[ManageStaffController::class,'index'])->name('staff.manage')->middleware('permission:manage staff');

              Route::post('add/staff',[ManageStaffController::class,'addStaff'])->name('staff.add')->middleware('permission:add staff');

              Route::post('update/staff/{id}',[ManageStaffController::class,'updateStaff'])->name('staff.update')->middleware('permission:update staff');

            //support ticket
              Route::get('manage/tickets/{type}',[ManageTicketController::class,'index'])->name('ticket.manage')->middleware('permission:manage ticket');

              Route::post('reply/ticket/{ticket_num}',   [ManageTicketController::class,'replyTicket'])->name('ticket.reply')->middleware('permission:manage ticket')->middleware('permission:reply ticket');

          


            //manage Agent
            Route::middleware('permission:manage agent list')->group(function(){
                Route::get('agent/list', [ManageAgentController::class,'agentList'])->name('agent.list');
                Route::get('agent/register/request', [ManageAgentController::class,'agentRequest'])->name('agent.request');

                Route::get('agent/request/details/{agent_id}', [ManageAgentController::class,'agentRequestDetails'])->name('agent.request.details');

                Route::post('agent/request/accept/{agent_id}', [ManageAgentController::class,'acceptAgent'])->name('agent.request.accept');

                Route::post('agent/request/reject/{agent_id}', [ManageAgentController::class,'rejectAgent'])->name('agent.request.reject');

                Route::post('agent/add', [ManageAgentController::class,'addAgent'])->name('agent.add');
            });
         
            Route::middleware('permission:manage agent profile')->group(function(){
                Route::get('agent/details/{id}', [ManageAgentController::class,'agentDetails'])->name('agent.details');
      
                Route::post('agent/add/wallet', [ManageAgentController::class,'addAgentWallet'])->name('agent.add.wallet');
                
                Route::get('agent/login/{id}', [ManageAgentController::class,'agentLogin'])->name('agent.login');
                Route::get('agent/login-info/{id}', [ManageAgentController::class,'loginInfo'])->name('agent.login.info');
                Route::post('agent/profile/update/{id}', [ManageAgentController::class,'profileUpdate'])->name('agent.profile.update');
                Route::post('agent/balance/modify', [ManageAgentController::class,'modifyBalance'])->name('agent.balance.modify');
            });
          
            Route::middleware('permission:agent fund request')->group(function(){
                Route::get('agent/fund-requests', [ManageAgentController::class,'fundRequests'])->name('agent.fund.requests');
                Route::post('agent/fund-request/accept/{id}', [ManageAgentController::class,'fundRequestAccept'])->name('agent.fund.accept');
                Route::post('agent/fund-collect/{id}', [ManageAgentController::class,'fundCollect'])->name('agent.fund.collect');
                Route::post('agent/fund-request/reject/{id}', [ManageAgentController::class,'fundRequestReject'])->name('agent.fund.reject');
            });

            Route::middleware('permission:manage addon')->group(function(){
                Route::get('addon',  [AddonController::class,'index'])->name('addon');
                Route::post('addon', [AddonController::class,'install'])->name('addon.install');
                Route::post('addon-status/{id}', [AddonController::class,'changeStatus'])->name('addon.status');
            });
          
            Route::get('system-update',  [AddonController::class,'update'])->name('update')->middleware('permission:system update');

            Route::post('system-update', [AddonController::class,'updateSystem'])->name('update.install')->middleware('permission:system update');

            Route::get('/clear-cache',function(){
                Artisan::call('optimize:clear');
                return back()->with('success','Cache cleared successfully');
            })->name('clear.cache');
            

    });

});


    
Route::get('/activation', [AdminController::class,'activation'])->name('admin-activation-form');
Route::post('/activation', [AdminController::class,'activation_submit'])->name('admin-activate-purchase');


