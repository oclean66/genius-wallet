<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ManageModuleController extends Controller
{
    public function index()
    {
        $modules = Module::get();
        return view('admin.module.index',compact('modules'));
    }

    public function update(Request $request)
    {
        $module = Module::where('module',$request->module)->first();
        if(!$module) return response()->json(['error' => __('Module not found')]);
       
        $name = ucwords(str_replace('-',' ',$module->module));
        if(!$request->kyc){
            if($module->status == 1) {
                $module->status = 0;
                $msg = $name.' is turned off';
            } else {
                $module->status = 1;
                $msg = $name.' is turned on';
            }
        }else{
            if($module->kyc == 1) {
                $module->kyc = 0;
                $msg = $name.' KYC restriction turned off';
            } else {
                $module->kyc = 1;
                $msg = $name.' KYC restriction turned on';
            }
        }
        
        $module->save();
        return response()->json(['success'=> __($msg)]);
    }
}
