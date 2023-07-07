<?php

namespace App\Http\Controllers\Admin;

use App\Models\Escrow;
use App\Models\Dispute;
use App\Helpers\MediaHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;

class ManageEscrowController extends Controller
{
    public function index()
    {
        $title = "Manage Escrow";
        $escrows = Escrow::with(['user','recipient','currency'])->latest()->paginate(15);
        return view('admin.escrow.index',compact('escrows','title'));
    }

    public function details($id)
    {
        $escrow = Escrow::with(['user','recipient','currency'])->findOrFail($id); 
        $messages = Dispute::where('escrow_id',$escrow->id)->with('user')->get();
        return view('admin.escrow.details',compact('escrow','messages'));
    }

    public function onHold()
    {
        $title = "On Hold Escrows";
        $escrows = Escrow::whereStatus(0)->with(['user','recipient','currency'])->latest()->paginate(15);
        return view('admin.escrow.index',compact('escrows','title'));
    }
    public function disputed()
    {
        $title = "Disputed Escrows";
        $escrows = Escrow::whereStatus(3)->with(['user','recipient','currency'])->latest()->paginate(15);
        return view('admin.escrow.index',compact('escrows','title'));
    }

    public function fileDownload($id)
    {
        $dispute = Dispute::findOrFail($id);
        $filepath = 'assets/images/'.$dispute->file;
        $extension = pathinfo($filepath, PATHINFO_EXTENSION);
        $fileName =  @$dispute->user->email.'_file.'.$extension;
        header('Content-type: application/octet-stream');
        header("Content-Disposition: attachment; filename=".$fileName);
        while (ob_get_level()) {
            ob_end_clean();
        }
        readfile($filepath);
    }

    public function disputeStore(Request $request,$escrow_id)
    {
        $request->validate(['message'=>'required','file' => 'mimes:png,jpeg,jpg|max:5186']);

        $escrow = Escrow::findOrFail($escrow_id);
        if($escrow->status == 4){
            return back()->with('error','Dispute has been closed');
        }

        $dispute = new Dispute;
        $dispute->admin_id = admin()->id;
        $dispute->escrow_id = $escrow_id;
        $dispute->message = $request->message;
        if($request->file){
            $dispute->file = MediaHelper::handleMakeImage($request->file);
        }
        $dispute->save();
        return back()->with('success','Reply submitted');
    }

    public function returnPayment(Request $request)
    {
        $request->validate(['id'=>'required','escrow_id'=>'required']);

        $escrow = Escrow::findOrFail($request->escrow_id);
        $wallet = Wallet::where('user_id',$request->id)->where('user_type',1)->where('currency_id',$escrow->currency_id)->first();
        if(!$wallet){
            $wallet = Wallet::create([
                'user_id' => $request->id,
                'user_type' => 1,
                'currency_id' => $escrow->currency_id,
                'balance' => 0
            ]);
        }
        
        $wallet->balance += $escrow->amount;
        $wallet->update();

        $trnx              = new Transaction();
        $trnx->trnx        = str_rand();
        $trnx->user_id     = $request->id;
        $trnx->user_type   = 1;
        $trnx->currency_id = $escrow->currency_id;
        $trnx->amount      = $escrow->amount;
        $trnx->charge      = 0;
        $trnx->type        = '+';
        $trnx->remark      = 'escrow_return';
        $trnx->details     = trans('Escrow fund returned');
        $trnx->save();

        $escrow->returned_to = @$wallet->user->email;
        $escrow->status = 4;
        $escrow->update();

        @mailSend('escrow_return',['amount'=>amount($escrow->amount,$escrow->currency->type,2), 'trnx'=> $trnx->trnx,'curr' => $escrow->currency->code,'data_time'=> dateFormat($trnx->created_at)], $wallet->user);

        return back()->with('success','Payment has been returned to '.@$wallet->user->email);

    }

    public function close($id)
    {
        $escrow = Escrow::findOrFail($id);
        $escrow->status = 4;
        $escrow->save();
        return back()->with('success','Escrow has been closed');
    }


}
