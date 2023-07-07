<?php

namespace App\Http\Controllers\User;

use App\Models\Wallet;
use App\Models\InvItem;
use App\Models\Invoice;
use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManageInvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::where('user_id',auth()->id())->latest()->paginate(15);
        return view('user.invoice.index',compact('invoices'));
    }

    public function create()
    {
        $currencies = Currency::where('status', 1)->get();
        return view('user.invoice.create',compact('currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_to' => 'required',
            'email'      => 'required|email',
            'address'    => 'required',
            'currency'   => 'required',
            'item'       => 'required',
            'item.*'     => 'required',
            'amount'     => 'required',
            'amount.*'   => 'required|numeric|gt:0' 
        ]);

        $charge = charge('create-invoice');
        $currency = Currency::findOrFail($request->currency);

        $amount = array_sum($request->amount);
        $finalCharge = chargeCalc($charge,$amount,$currency->rate);
        $willGetAmount = numFormat($amount - $finalCharge);

        $invoice = new Invoice();
        $invoice->user_id      = auth()->id();
        $invoice->number       = 'INV-'.randNum(8);
        $invoice->invoice_to   = $request->invoice_to;
        $invoice->email        = $request->email;
        $invoice->address      = $request->address;
        $invoice->currency_id  = $currency->id;
        $invoice->charge       = $finalCharge;
        $invoice->final_amount = $amount;
        $invoice->get_amount   = $willGetAmount;
        $invoice->save();

        $items = array_combine($request->item,$request->amount);
        foreach($items as $item => $amount){
            $invItem             = new InvItem();
            $invItem->invoice_id = $invoice->id;
            $invItem->name       = $item;
            $invItem->amount	 = $amount;
            $invItem->save();
        }
     
        $route = route('invoice.view',encrypt($invoice->number));
        @email([

            'email'   => $invoice->email,
            "subject" => trans('Invoice Payment'),
            'message' => trans('Hello')." $invoice->invoice_to,<br/></br>".

                trans('You have pending payment of invoice')." <b>$invoice->number</b>.".trans('Please click the below link to complete your payment') .".<br/></br>".
                
                trans('Invoice details').": <br/></br>".
                
                trans('Amount')  .":  $amount $currency->code <br/>".
                trans('Payment Link')." :  <a href='$route' target='_blank'>".trans('Click To Payment')."</a><br/>".
                trans('Time')." : $invoice->created_at,

            " 
        ]);

        return back()->with('success','Invoice has been created');
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        if($invoice->status == 1){
            return back()->with('error','Sorry! can\'t edit published invoice.');
        }
        $currencies = Currency::where('status', 1)->get();
        return view('user.invoice.edit',compact('invoice','currencies'));
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'invoice_to' => 'required',
            'email'      => 'required|email',
            'address'    => 'required',
            'currency'   => 'required',
            'item'       => 'required',
            'item.*'     => 'required',
            'amount'     => 'required',
            'amount.*'   => 'required|numeric|gt:0' 
        ],['amount.*.gt'=>'Amount must be greater than 0']);

        $charge = charge('create-invoice');
        $currency = Currency::findOrFail($request->currency);

        $finalCharge = chargeCalc($charge,array_sum($request->amount),$currency->rate);
        $willGetAmount = numFormat(array_sum($request->amount) - $finalCharge);

        $invoice = Invoice::findOrFail($id);
        $invoice->user_id      = auth()->id();
        $invoice->invoice_to   = $request->invoice_to;
        $invoice->email        = $request->email;
        $invoice->address      = $request->address;
        $invoice->currency_id  = $currency->id;
        $invoice->charge       = $finalCharge;
        $invoice->final_amount = array_sum($request->amount);
        $invoice->get_amount   = $willGetAmount;
        $invoice->update();

        $invoice->items()->delete();
        $items = array_combine($request->item,$request->amount);
        foreach($items as $item => $amount){
            $invItem             = new InvItem();
            $invItem->invoice_id = $invoice->id;
            $invItem->name       = $item;
            $invItem->amount	 = $amount;
            $invItem->save();
        }
        return back()->with('success','Invoice has been updated');
    }

    public function payStatus(Request $request)
    {
        $invoice = Invoice::findOrFail($request->id);
        if(!$invoice) return response(['error'=>'Invalid request']);
        
        if($invoice->payment_status == 1){
            $invoice->payment_status = 0;
            $invoice->update();
            return response(['unpaid'=>'Payment status changed to un-paid']);
        }else{
            $invoice->payment_status = 1;
            $invoice->update();
            return response(['paid'=>'Payment status changed to paid']);
        }
        
        
    }
    public function publishStatus(Request $request)
    {
        $invoice = Invoice::findOrFail($request->id);
        if(!$invoice) return response(['error'=>'Invalid request']);
        
        if($invoice->status == 1){
            $invoice->status = 0;
            $invoice->update();
            return response(['unpublish'=>trans('Status changed to un-published')]);
        }else{
            $invoice->status = 1;
            $invoice->update();
            return response(['publish'=>trans('Status changed to published')]);
        }

    }
       
    public function cancel($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->status = 2;
        $invoice->save();
        return redirect(route('user.invoice.index'))->with('success','Invoice has been cancelled');
    }

    public function invoiceView($number)
    {
        try {
            $invoice = Invoice::where('number',decrypt($number))->firstOrFail();
        } catch (\Throwable $th) {
            return back()->with('error','Something went wrong.');
        }

        if($invoice->status == 0) return back()->with('error','Invoice not published yet.');
        if($invoice->status == 2) return back()->with('error','Invoice has been cancelled.');
        return view('user.invoice.view',compact('invoice'));

    }
    public function view($number)
    {
        $invoice = Invoice::where('number',$number)->firstOrFail();
        return view('user.invoice.invoice',compact('invoice'));
    }

    public function sendToMail($id)
    {
        $invoice = Invoice::findOrFail($id);
        $currency = $invoice->currency;
        $amount = amount($invoice->final_amount,$currency->type,3);
        $route = route('invoice.view',encrypt($invoice->number));
     
        @email([

            'email'   => $invoice->email,
            "subject" => trans('Invoice Payment'),
            'message' => trans('Hello')." $invoice->invoice_to,<br/></br>".

                trans('You have pending payment of invoice')." <b>$invoice->number</b>.".trans('Please click the below link to complete your payment') .".<br/></br>".
                
                trans('Invoice details').": <br/></br>".
                
                trans('Amount')  .":  $amount $currency->code <br/>".
                trans('Payment Link')." :  <a href='$route' target='_blank'>".trans('Click To Payment')."</a><br/>".
                trans('Time')." : $invoice->created_at,

            " 
        ]);

        return back()->with('success','Invoice has been sent to the recipient');
    }

    public function invoicePayment($number)
    {
        try {
            $invoice = Invoice::where('number',decrypt($number))->firstOrFail();
            if($invoice->payment_status == 1){
                return back()->with('error','Invoice already been paid');
            }
            session()->put('invoice',encrypt($invoice));
        } catch (\Throwable $th) {
           return back()->with('error','Something went wrong');
        }

        if($invoice->user_id == auth()->id()){
            return back()->with('error','You can not pay your own invoice.');
        }

        return view('user.invoice.invoice_payment',compact('invoice'));
    }

    public function invoicePaymentSubmit(Request $request,$number)
    {
        if($request->payment == 'gateway'){
            return redirect(route('user.pay.invoice'));
        }
        elseif($request->payment == 'wallet'){
            try {
                $invoice = decrypt(session('invoice'));
            } catch (\Throwable $th) {
               return back()->with('error','Something went wrong');
            }

            $wallet = Wallet::where('user_id',auth()->id())->where('user_type',1)->where('currency_id',$invoice->currency_id)->first();

            if(!$wallet){
                $wallet =  Wallet::create([
                    'user_id'     => auth()->id(),
                    'user_type'   => 1,
                    'currency_id' => $invoice->currency_id,
                    'balance'     => 0
                ]);
            }

            if($wallet->balance < $invoice->final_payment) {
                return back()->with('error','Insufficient balance to your wallet');
            }

            $wallet->balance -= $invoice->final_payment;
            $wallet->update();

            $trnx              = new Transaction();
            $trnx->trnx        = str_rand();
            $trnx->user_id     = auth()->id();
            $trnx->user_type   = 1;
            $trnx->currency_id = $invoice->currency_id;
            $trnx->wallet_id   = $wallet->id;
            $trnx->amount      = $invoice->final_amount;
            $trnx->charge      = 0;
            $trnx->remark      = 'invoice_payment';
            $trnx->invoice_num = $invoice->number;
            $trnx->type        = '-';
            $trnx->details     = trans('Payemnt to invoice : '). $invoice->number;
            $trnx->save();

            $rcvWallet = Wallet::where('user_id',$invoice->user_id)->where('user_type',1)->where('currency_id',$invoice->currency_id)->first();
        
            if(!$rcvWallet){
                $rcvWallet =  Wallet::create([
                    'user_id'     => $invoice->user_id,
                    'user_type'   => 1,
                    'currency_id' => $invoice->currency_id,
                    'balance'     => 0
                ]);
            }

            $rcvWallet->balance += $invoice->get_amount;
            $rcvWallet->update();

            $rcvTrnx              = new Transaction();
            $rcvTrnx->trnx        = $trnx->trnx;
            $rcvTrnx->user_id     = $invoice->user_id;
            $rcvTrnx->user_type   = 1;
            $rcvTrnx->currency_id = $invoice->currency_id;
            $rcvTrnx->wallet_id   = $rcvWallet->id;
            $rcvTrnx->amount      = $invoice->get_amount;
            $rcvTrnx->charge      = $invoice->charge;
            $rcvTrnx->remark      = 'invoice_payment';
            $rcvTrnx->invoice_num = $invoice->number;
            $rcvTrnx->type        = '+';
            $rcvTrnx->details     = trans('Receive Payemnt from invoice : '). $invoice->number;
            $rcvTrnx->save();

            $invoice->payment_status = 1;
            $invoice->update();


            @mailSend('received_invoice_payment',[
                'amount' => amount($invoice->get_amount,$invoice->currency->type,2),
                'curr'   => $invoice->currency->code,
                'trnx'   => $rcvTrnx->trnx,
                'from_user' => $invoice->email,
                'inv_num'  => $invoice->number,
                'after_balance' => amount($rcvWallet->balance,$invoice->currency->type,2),
                'charge' => amount($invoice->charge,$invoice->currency->type,2),
                'date_time' => dateFormat($rcvTrnx->created_at)
            ],$invoice->user);
            
            session()->forget('invoice');
            return redirect(route('user.dashboard'))->with('success','Payment completed');
        }
        else{
            abort(404);
        }
    }
}
