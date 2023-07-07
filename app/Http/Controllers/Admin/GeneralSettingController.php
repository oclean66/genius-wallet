<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GeneralSettingRequest;
use App\Http\Resources\GeneralSettingResource;
use App\Models\Generalsetting;
use Illuminate\Support\Facades\Cache;

class GeneralSettingController extends Controller
{

    public function __construct(GeneralSettingResource $resource)
    {
        $this->resource = $resource;
    }

    public function update(GeneralSettingRequest $request)
    {
       $this->resource->update($request->all());
       return response()->json(__('Data update successfully'));
    }


    public function StatusUpdate($value)
    {
        $value = explode(',',$value);
        $status = $value[0];
        $field = $value[1];
        $data = Generalsetting::findOrFail(1);
        $data->$field = $status;
        $data->update();
        Cache::forget('generalsettings');
        if($status == 1){
            return response()->json(['status'=>1,'success' => __('Data Updated Successfully.')]);
        }else{
            return response()->json(['status'=>0,'success' => __('Data Updated Successfully.')]);
        }
    }


    public function logo()
    {
        return view('admin.generalsetting.logo');
    }

    public function favicon()
    {
        return view('admin.generalsetting.favicon');
    }

    public function loader()
    {
        return view('admin.generalsetting.loader');
    }

    public function cookie()
    {
        return view('admin.generalsetting.cookie');
    }
    public function menu()
    {
        return view('admin.generalsetting.menu_section');
    }
  
    public function maintenance()
    {
        return view('admin.generalsetting.maintenance');
    }
    public function siteSettings()
    {
        return view('admin.generalsetting.settings');
    }
 
}
