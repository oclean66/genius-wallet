<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use App\Models\Withdrawals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wallet;

class WithdrawalController extends Controller
{
    public function accepted()
    {
        $withdrawlogs = Withdrawals::where('status', 1)->latest()->with(['method','user','currency','merchant','agent'])->paginate(15);
        return view('admin.withdraw.withdraw_all',compact('withdrawlogs'));
    }
    public function pending()
    {
        $withdrawlogs = Withdrawals::where('status', 0)->latest()->with(['method','user','currency','merchant','agent'])->paginate(15);
        return view('admin.withdraw.withdraw_all',compact('withdrawlogs'));
    }
    public function rejected()
    {
        $withdrawlogs = Withdrawals::where('status', 2)->latest()->with(['method','user','currency','merchant','agent'])->paginate(15);
        return view('admin.withdraw.withdraw_all',compact('withdrawlogs'));
    }

    public function withdrawAccept(Withdrawals $withdraw)
    {
        $withdraw->status = 1;
        $withdraw->save();
       
        if($withdraw->user_id){
            $user = $withdraw->user;
           
            $trnx              = new Transaction();
            $trnx->trnx        = str_rand();
            $trnx->user_id     = $withdraw->user_id;
            $trnx->user_type   = 1;
            $trnx->currency_id = $withdraw->currency->id;
            $trnx->amount      = $withdraw->amount;
            $trnx->charge      = $withdraw->charge;
            $trnx->remark      = 'withdraw_money';
            $trnx->type        = '-';
            $trnx->details     = trans('Withdraw money');
            $trnx->save();
        }
        elseif ($withdraw->merchant_id){
            $user = $withdraw->merchant;
         
            $trnx              = new Transaction();
            $trnx->trnx        = str_rand();
            $trnx->user_id     = $withdraw->merchant_id;
            $trnx->user_type   = 2;
            $trnx->currency_id = $withdraw->currency->id;
            $trnx->amount      = $withdraw->amount;
            $trnx->charge      = $withdraw->charge;
            $trnx->remark      = 'withdraw_money';
            $trnx->type        = '-';
            $trnx->details     = trans('Withdraw money');
            $trnx->save();
        }
        else {
            $user = $withdraw->agent;
          
            $trnx              = new Transaction();
            $trnx->trnx        = str_rand();
            $trnx->user_id     = $withdraw->agent_id;
            $trnx->user_type   = 3;
            $trnx->currency_id = $withdraw->currency->id;
            $trnx->amount      = $withdraw->amount;
            $trnx->charge      = $withdraw->charge;
            $trnx->remark      = 'withdraw_money';
            $trnx->type        = '-';
            $trnx->details     = trans('Withdraw money');
            $trnx->save();
        }

        @mailSend('accept_withdraw',['amount'=>amount($withdraw->amount,$withdraw->currency->type,2), 'method' => $withdraw->withdraw->name,'trnx'=> $trnx->trnx,'curr' => $withdraw->currency->code,'method'=>$withdraw->method->name,'charge'=> amount($withdraw->charge,$withdraw->currency->type,2),'data_time'=> dateFormat($trnx->created_at)], $user);

        return back()->with('success','Withdraw Accepted Successfully');
    }


    public function withdrawReject(Request $request, Withdrawals $withdraw)
    {
        $request->validate(['reason_of_reject' => 'required']);
    
        $withdraw->status = 2;
        $withdraw->reject_reason = $request->reason_of_reject;
        $withdraw->save();

        if($withdraw->user_id){
            $user = $withdraw->user;
            $wallet = Wallet::where('user_id',$withdraw->user_id)->where('user_type',1)->where('currency_id',$withdraw->currency_id)->firstOrFail();

            $wallet->balance += $withdraw->total_amount;
            $wallet->save();

            $trnx              = new Transaction();
            $trnx->trnx        = str_rand();
            $trnx->user_id     = $withdraw->user_id;
            $trnx->user_type   = 1;
            $trnx->currency_id = $withdraw->currency->id;
            $trnx->amount      = $withdraw->amount;
            $trnx->charge      = 0;
            $trnx->remark      = 'withdraw_reject';
            $trnx->type        = '+';
            $trnx->details     = trans('Withdraw request rejected');
            $trnx->save();

        } elseif ($withdraw->merchant_id){
            $user = $withdraw->merchant;
            $wallet = Wallet::where('user_id',$withdraw->merchant_id)->where('user_type',2)->where('currency_id',$withdraw->currency_id)->firstOrFail();

            $wallet->balance += $withdraw->total_amount;
            $wallet->save();

            $trnx              = new Transaction();
            $trnx->trnx        = str_rand();
            $trnx->user_id     = $withdraw->merchant_id;
            $trnx->user_type   = 2;
            $trnx->currency_id = $withdraw->currency->id;
            $trnx->amount      = $withdraw->amount;
            $trnx->charge      = 0;
            $trnx->remark      = 'withdraw_reject';
            $trnx->type        = '+';
            $trnx->details     = trans('Withdraw request rejected');
            $trnx->save();

        } else {
            $user = $withdraw->agent;
            $wallet = Wallet::where('user_id',$withdraw->agent_id)->where('user_type',3)->where('currency_id',$withdraw->currency_id)->firstOrFail();

            $wallet->balance += $withdraw->total_amount;
            $wallet->save();

            $trnx              = new Transaction();
            $trnx->trnx        = str_rand();
            $trnx->user_id     = $withdraw->agent_id;
            $trnx->user_type   = 3;
            $trnx->currency_id = $withdraw->currency->id;
            $trnx->amount      = $withdraw->amount;
            $trnx->charge      = 0;
            $trnx->remark      = 'withdraw_reject';
            $trnx->type        = '+';
            $trnx->details     = trans('Withdraw request rejected');
            $trnx->save();

        }

        @mailSend('reject_withdraw',['amount'=> amount($withdraw->amount,$withdraw->currency->type,2), 'method' => $withdraw->withdraw->name,'trnx'=> $trnx->trnx,'curr' => $withdraw->currency->code,'method'=>$withdraw->method->name,'reason'=>$withdraw->reject_reason,'data_time'=> dateFormat($trnx->created_at)],$user);

        return back()->with('success','Withdraw Rejected Successfully');
    }
}
