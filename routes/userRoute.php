<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\EscrowController;
use App\Http\Controllers\User\DepositController;
use App\Http\Controllers\User\VoucherController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\User\TransferController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\WithdrawalController;
use App\Http\Controllers\User\MakePaymentController;
use App\Http\Controllers\User\RequestMoneyController;
use App\Http\Controllers\User\AuthorizationController;
use App\Http\Controllers\User\ExchangeMoneyController;
use App\Http\Controllers\User\ManageInvoiceController;

Route::prefix('user')->name('user.')->middleware('maintenance')->group(function () {
    Route::get('register',  [AuthController::class,'registerForm'])->name('register');
    Route::post('register',  [AuthController::class,'register']);
    Route::get('login',     [AuthController::class,'showLoginForm'])->name('login');
    Route::post('login',    [AuthController::class,'login'])->name('login');
    Route::get('logout',     [AuthController::class,'logout'])->name('logout');
    Route::get('forgot-password',     [AuthController::class,'forgotPassword'])->name('forgot.password');
    Route::post('forgot-password',     [AuthController::class,'forgotPasswordSubmit']);
    Route::get('forgot-password/verify-code',     [AuthController::class,'verifyCode'])->name('verify.code');
    Route::post('forgot-password/verify-code',     [AuthController::class,'verifyCodeSubmit']);

    Route::get('reset-password',     [AuthController::class,'resetPassword'])->name('reset.password');
    Route::post('reset-password',     [AuthController::class,'resetPasswordSubmit']);

    Route::get('verify-email',     [AuthorizationController::class,'verifyEmail'])->name('verify.email')->middleware('auth');
    Route::post('verify-email',     [AuthorizationController::class,'verifyEmailSubmit'])->middleware('auth');
    Route::get('resend/verify-email/code',     [AuthorizationController::class,'verifyEmailResendCode'])->name('verify.email.resend')->middleware('auth');


    Route::get('two-step/verification',     [AuthorizationController::class,'twoStep'])->name('two.step.verification')->middleware('auth');

    Route::post('two-step/verification',     [AuthorizationController::class,'twoStepVerify'])->middleware('auth');

    Route::get('resend/two-step/verify-code', [AuthorizationController::class,'twoStepResendCode'])->name('two.step.resend')->middleware('auth');

    Route::middleware(['auth','email_verify','twostep'])->group(function () {
        Route::get('/', [UserController::class,'index'])->name('dashboard');
        Route::get('profile',   [UserController::class,'profile'])->name('profile');
        Route::post('profile',  [UserController::class,'profileSubmit']);
        Route::post('change-password',  [UserController::class,'changePass'])->name('change.pass');
        Route::post('check-receiver', [TransferController::class,'checkReceiver'])->name('check.receiver');
        Route::post('check-merchant', [MakePaymentController::class,'checkMerchant'])->name('check.merchant');
        Route::post('check-agent', [WithdrawalController::class,'checkAgent'])->name('check.agent');
        Route::get('transactions', [UserController::class,'transactions'])->name('transactions');
        Route::get('transaction/details/{id}', [UserController::class,'trxDetails'])->name('trx.details');
     
        //off module
        Route::get('module/{module}',   [UserController::class,'moduleOff'])->name('module.off');

        Route::middleware(['module','kyc'])->group(function () {

            //transfer-money
            Route::get('transfer-money',  [TransferController::class,'transferForm'])->name('transfer.money');
            Route::post('transfer-money', [TransferController::class,'submitTransfer']);

            //Request Money
            Route::get('request-money',  [RequestMoneyController::class,'requestForm'])->name('request.money');
            Route::post('request-money', [RequestMoneyController::class,'requestSubmit']);

            //exchange money
            Route::get('exchange-money',   [ExchangeMoneyController::class,'exchangeForm'])->name('exchange.money');
            Route::post('exchange-money',  [ExchangeMoneyController::class,'submitExchange']);

            // merchant payment
            Route::get('make-payment',   [MakePaymentController::class,'paymentForm'])->name('make.payment');
            Route::post('make-payment',  [MakePaymentController::class,'submitPayment']);

            //voucher
            Route::get('create-voucher',   [VoucherController::class,'create'])->name('create.voucher');
            Route::post('create-voucher',   [VoucherController::class,'submit']);

            //withdraw

            Route::get('withdraw-money',    [WithdrawalController::class,'withdrawForm'])->name('withdraw.form');
            Route::post('withdraw-money',   [WithdrawalController::class,'withdrawSubmit']);

            //invoice
            Route::get('create-invoice',   [ManageInvoiceController::class,'create'])->name('invoice.create');
            Route::post('create-invoice',   [ManageInvoiceController::class,'store']);

            //escrow
            Route::get('make-escrow',   [EscrowController::class,'create'])->name('escrow.create');
            Route::post('make-escrow',   [EscrowController::class,'store']);

            //deposit
            Route::get('deposit',[DepositController::class,'index'])->name('deposit.index');


        });

        //transfer-money
        Route::get('transfer-money/history',  [TransferController::class,'transferHistory'])->name('transfer.history');

        //exchange money
        Route::get('exchange-money/history',  [ExchangeMoneyController::class,'exchangeHistory'])->name('exchange.history');

        //payment history
        Route::get('payment/history',  [MakePaymentController::class,'paymentHistory'])->name('payment.history');

        //Request Money
        Route::get('money-request',  [RequestMoneyController::class,'moneyRequests'])->name('money.requests');
        Route::get('sent-requests',  [RequestMoneyController::class,'moneyRequests'])->name('sent.requests');
        Route::get('received-requests', [RequestMoneyController::class,'moneyRequests'])->name('received.requests');
        Route::post('accept-request', [RequestMoneyController::class,'acceptRequest'])->name('accept.request');
        Route::post('reject-request', [RequestMoneyController::class,'rejectRequest'])->name('reject.request');

        //Reedem voucher
        Route::get('vouchers',  [VoucherController::class,'vouchers'])->name('vouchers');
        Route::get('reedem-voucher',  [VoucherController::class,'reedemForm'])->name('reedem.voucher');
        Route::post('reedem-voucher',  [VoucherController::class,'reedemSubmit']);
        Route::get('reedemed-history',  [VoucherController::class,'reedemHistory'])->name('reedem.history');

        //withdraw
        Route::get('withdraw-methods',  [WithdrawalController::class,'methods'])->name('withdraw.methods');
        Route::get('withdraw-history',  [WithdrawalController::class,'history'])->name('withdraw.history');

        //invoice
        Route::get('invoices',   [ManageInvoiceController::class,'index'])->name('invoice.index');
        Route::post('invoice/pay-status',   [ManageInvoiceController::class,'payStatus'])->name('invoice.pay.status');
        Route::post('invoice/publish-status',   [ManageInvoiceController::class,'publishStatus'])->name('invoice.publish.status');

        Route::get('invoices-edit/{id}',   [ManageInvoiceController::class,'edit'])->name('invoice.edit');
        Route::post('invoices-update/{id}',   [ManageInvoiceController::class,'update'])->name('invoice.update');
        Route::get('invoice-cancel/{id}',   [ManageInvoiceController::class,'cancel'])->name('invoice.cancel');
        Route::get('invoice/send-mail/{id}',   [ManageInvoiceController::class,'sendToMail'])->name('invoice.send.mail');
        Route::get('invoice/view/{number}',   [ManageInvoiceController::class,'view'])->name('invoice.view');

        Route::get('invoices-payment/{number}',   [ManageInvoiceController::class,'invoicePayment'])->name('invoice.payment');
        Route::post('invoices-payment/{number}',   [ManageInvoiceController::class,'invoicePaymentSubmit']);
        Route::get('pay-invoice',   [DepositController::class,'invoicePayment'])->name('pay.invoice');


        //escrow
        Route::get('my-escrow',   [EscrowController::class,'index'])->name('escrow.index');
        Route::get('escrow-pending',   [EscrowController::class,'pending'])->name('escrow.pending');
        Route::get('escrow-dispute/{id}',   [EscrowController::class,'disputeForm'])->name('escrow.dispute');
        Route::post('escrow-dispute/{id}',   [EscrowController::class,'disputeStore']);
        Route::get('release-escrow/{id}',   [EscrowController::class,'release'])->name('escrow.release');
        Route::get('file-download/{id}',   [EscrowController::class,'fileDownload'])->name('escrow.file.download');

        //kyc form
        Route::get('kyc-form',   [UserController::class,'kycForm'])->name('kyc.form');
        Route::post('kyc-form',   [UserController::class,'kycFormSubmit']);

        Route::get('/generate-qrcode', [UserController::class,'generateQR'])->name('qr');

        //deposit
        Route::post('payment/submit',[DepositController::class,'depositSubmit'])->name('deposit.submit');
        Route::post('payment-submit',[DepositController::class,'depositPayment'])->name('deposit.payment');
        Route::get('deposit/history',[DepositController::class,'dipositHistory'])->name('deposit.history');
        Route::get('gateway-methods',  [DepositController::class,'methods'])->name('gateway.methods');
        Route::get('finalize-payment/{data}',  [DepositController::class,'finalize'])->name('payment.finalize');

        //twostep
        Route::get('/two-step/authentication', [UserController::class,'twoStep'])->name('two.step');
        Route::get('/two-step/verify', [UserController::class,'twoStepVerifyForm'])->name('two.step.verify');
        Route::post('/two-step/verify', [UserController::class,'twoStepVerifySubmit']);
        Route::post('/two-step/authentication', [UserController::class,'twoStepSendCode']);

        Route::get('download-qr/{email}',  [UserController::class,'downloadQR'])->name('download.qr');

        // support ticket
        Route::get('support/tickets',   [SupportTicketController::class,'index'])->name('ticket.index');
        Route::post('open/support/tickets',   [SupportTicketController::class,'openTicket'])->name('ticket.open');
        Route::post('reply/ticket/{ticket_num}',   [SupportTicketController::class,'replyTicket'])->name('ticket.reply');

    });
});
Route::get('deposit-init',   [DepositController::class,'paymentInit'])->name('payment-init');

