<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\GeneralSetting;
use App\Models\Transaction;
use App\Models\Withdraw;
use App\Models\WithdrawLog;
use Illuminate\Http\Request;

class WithdrawMethodController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $withdraws = Withdraw::when($search, function($q) use($search){$q->where('name','LIKE','%'.$search.'%');})->latest()->paginate(15);
     
        return view('admin.withdraw.index', compact('withdraws'));
    }

    public function create()
    {
        $currencies = Currency::get();
        return view('admin.withdraw.create', compact('currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:withdraws,name',
            'min_amount' => 'required|numeric|gt:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'fixed_charge' => 'required|numeric|min:0',
            'percent_charge' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
            'currency' => 'required|integer',
            'withdraw_instruction' => 'required'
        ]);

        Withdraw::create([
            'name' => $request->name,
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'fixed_charge' => $request->fixed_charge,
            'percent_charge' => $request->percent_charge,
            'status' => $request->status,
            'currency_id' => $request->currency,
            'withdraw_instruction' => clean($request->withdraw_instruction)
        ]);

        return back()->with('success','Withdraw Method Created');
    }

    public function edit($id)
    {
        $currencies = Currency::get();
        $method = Withdraw::findOrFail($id);
        return view('admin.withdraw.edit', compact('currencies','method'));
    }

    public function update(Request $request, Withdraw $method)
    {
        $request->validate([
            'name' => 'required|unique:withdraws,name,'.$method->id,
            'min_amount' => 'required|numeric|gt:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'fixed_charge' => 'required|numeric|min:0',
            'percent_charge' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
            'currency' => 'required|integer',
            'withdraw_instruction' => 'required'
        ]);

        $method->update([
            'name' => $request->name,
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'fixed_charge' => $request->fixed_charge,
            'percent_charge' => $request->percent_charge,
            'status' => $request->status,
            'currency_id' => $request->currency,
            'withdraw_instruction' => clean($request->withdraw_instruction)
        ]);
        return back()->with('success','Withdraw Method Updated');
    }

}
