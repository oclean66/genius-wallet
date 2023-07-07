<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Wallet;
use App\Models\InvItem;
use App\Models\Invoice;
use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Validator;

class ManageInvoiceController extends ApiController
{
    public function index()
    {
        $success['invoices'] = Invoice::where('user_id',auth()->id())->latest()->paginate(15);
        return $this->sendResponse($success,__('success'));
    }

    public function create()
    {
        $success['currencies'] = Currency::where('status', 1)->get();
        return $this->sendResponse($success,__('Invoice currencies'));
    }

    public function store(Request $request)
    {  
   
        $validator = Validator::make($request->all(),[
            'invoice_to' => 'required',
            'email'      => 'required|email',
            'address'    => 'required',
            'currency_id'   => 'required',
            'item'       => 'required',
            'item.*'     => 'required',
            'amount'     => 'required',
            'amount.*'   => 'required|numeric|gt:0' 
        ],['amount.*.gt'=>'Amount must be greater than 0']);

        if($validator->fails()){
            return $this->sendError('Validation Error',$validator->errors());
        }

        $charge = charge('create-invoice');
        $currency = Currency::find($request->currency_id);
        if(!$currency){
            return $this->sendError('Error',['Currency not found']);
        }
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

        return $this->sendResponse(['invoice_number' => $invoice->number],__('Invoice has been created'));
    }

    public function edit($id)
    {
        $success['invoice'] = Invoice::where('id',$id)->where('user_id',auth()->id())->first();

        if(!$success['invoice']){
            return $this->sendError('Error',['Invoice not found']);
        }
        if($success['invoice']->status == 1){
            return $this->sendError('Error',['Sorry! can\'t edit published invoice.']);
        }
        $success['invoice_items'] =   InvItem::where('invoice_id',$id)->get();
        $success['currencies'] = Currency::where('status', 1)->get();
        return $this->sendResponse($success,'Edit Invoice');
    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(),[
            'invoice_to' => 'required',
            'email'      => 'required|email',
            'address'    => 'required',
            'currency_id'   => 'required',
            'item'       => 'required',
            'item.*'     => 'required',
            'amount'     => 'required',
            'amount.*'   => 'required|numeric|gt:0' 
        ],['amount.*.gt'=>'Amount must be greater than 0']);

        if($validator->fails()){
            return $this->sendError('Validation Error',$validator->errors());
        }

        $charge = charge('create-invoice');
        $currency = Currency::find($request->currency_id);
        if(!$currency){
            return $this->sendError('Error',['Currency not found']);
        }
        $finalCharge = chargeCalc($charge,array_sum($request->amount),$currency->rate);
        $willGetAmount = numFormat(array_sum($request->amount) - $finalCharge);

        $invoice = Invoice::find($id);
        if(!$invoice){
            return $this->sendError('Error',['Invoice not found']);
        }
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
        return $this->sendResponse(['success'],'Invoice has been updated');
    }

    public function payStatus(Request $request)
    {
        $invoice = Invoice::where('id',$request->id)->where('user_id',auth()->id())->first();
        if(!$invoice) return $this->sendError('Error',['Invoice not found']);
        
        if($invoice->payment_status == 1){
            $invoice->payment_status = 0;
            $invoice->update();
            return $this->sendResponse(['unpaid'],'Payment status changed to un-paid');
        }else{
            $invoice->payment_status = 1;
            $invoice->update();
            return $this->sendResponse(['paid'],'Payment status changed to paid');
        }
        
    }
    public function publishStatus(Request $request)
    {
        $invoice = Invoice::where('id',$request->id)->where('user_id',auth()->id())->first();
        if(!$invoice) return $this->sendError('Error',['Invoice not found']);
        
        if($invoice->status == 1){
            $invoice->status = 0;
            $invoice->update();
            return $this->sendResponse(['unpublish'] ,trans('Status changed to un-published'));
        }else{
            $invoice->status = 1;
            $invoice->update();
            return $this->sendResponse(['publish'],trans('Status changed to published'));
        }

    }
       
    public function cancel($id)
    {
        $invoice = Invoice::where('id',$id)->where('user_id',auth()->id())->first();
        if(!$invoice) return $this->sendError('Error',['Invoice not found']);
        $invoice->status = 2;
        $invoice->save();
        return $this->sendResponse(['success'],'Invoice has been cancelled');
    }


    public function view($number)
    {
        $invoice = Invoice::where('user_id',auth()->id())->where('number',$number)->first();
        if(!$invoice) return $this->sendError('Error',['Invoice not found']);
        $items = InvItem::where('invoice_id',$invoice->id)->get();
        return $this->sendResponse(['invoice' => $invoice,'invoice_items' => $items],'View invoice');
    }

    public function sendToMail($id)
    {
        $invoice = Invoice::where('id',$id)->where('user_id',auth()->id())->first();
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

        return $this->sendResponse(['success'],'Invoice has been sent to the recipient');
    }

}
