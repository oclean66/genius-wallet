<?php

namespace App\Http\Controllers\Admin;

use ZipArchive;
use App\Models\Addon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class AddonController extends Controller
{
    public function index()
    {
        $addons = Addon::get();
        return view('admin.addon.index', compact('addons'));
    }

    public function install(Request $request)
    {
        $request->validate([
            'addon'        => 'required|mimes:zip',
            'purchase_key' => 'required'
        ]);
        
        $script_key = @file_get_contents(base_path('..').'/project/vendor/markury/license.txt');
        if(!$script_key){
            return back()->with('error','Please activate your system first.');
        }
        $res = addonLicenceCheck($script_key,$request->purchase_key);
        if($res && $res['status'] == false){
            return back()->with('error',$res['message']);
        }

        if(class_exists('ZipArchive')) {
            if ($request->hasFile('addon')) {
                $path = Storage::disk('local')->put('addons', $request->addon);
              
                $zip = new ZipArchive;
                $result = $zip->open(storage_path('app/' . $path));
                $random_dir = strtolower(Str::random(10));
    
                if ($result === true) {
                   $result = $zip->extractTo(base_path('temp/' . $random_dir . '/addons'));
                   $zip->close();
                } else {
                    return back()->with('error','Can\'t open the zip file.');
                }
    
                try {
                        $str = file_get_contents(base_path('temp/' . $random_dir . '/addons/addon.json'));
                        $config = json_decode($str, true);
                } catch (\Throwable $th) {
                        return back()->with('error','Inavlid addon package.');
                }

                if($config['min_version'] > sysVersion()){
                     Storage::delete($path);
                    \File::deleteDirectory(base_path('temp'));
                    return back()->with('error','Minimum required system version is '.$config['min_version'].'. Your system version is '.sysVersion());
                }

                if(!empty($config['directory'])) {
                    foreach ($config['directory'][0]['path'] as $directory) {
                        if (is_dir(base_path($directory)) == false) {
                            mkdir(base_path($directory), 0777, true);
    
                        } else {
                           echo "error on creating directory";
                        }
                      
                    }

                }

                if(!empty($config['files'])) {
                    foreach ($config['files'] as $file) {
                        copy(base_path('temp/' . $random_dir . '/' . $file['root_path']), base_path($file['update_path']));
                    }
    
                }

                if(!empty($config['assets'])) {
                    foreach ($config['assets'] as $file) {
                        copy(base_path('temp/' . $random_dir . '/' . $file['root_path']), str_replace('project','assets',base_path($file['update_path'])));
                    }
    
                }
             
                try {
                    $sql_path = base_path('temp/' . $random_dir . '/addons/sql/update.sql');
                    if (file_exists($sql_path)) {
                        DB::unprepared(file_get_contents($sql_path));
                    }
                } catch (\Exception $e) {
                    
                }
                
            
                 Storage::delete($path);
                \File::deleteDirectory(base_path('temp'));

               try {
                    $addn = Addon::where('code', $config['code'])->first();
                    if($addn) $addn->delete();
                  
                    $addon = new Addon;
                    $addon->code    = $config['code'];
                    $addon->name    = $config['name'];
                    $addon->version = $config['version'];
                    $addon->status  = 1;
                    $addon->save();

               } catch (\Throwable $th) {
                     return back()->with('error', 'Something went wrong.');
               }
               Artisan::call('optimize:clear');
               return back()->with('success','Addon installed successfully.');

            }
        }
    }

    public function changeStatus($id)
    {
        $addon = Addon::findOrFail($id);
        if($addon->status == 1){ 
            $addon->status = 0;
            $msg = 'Addon disabled successfully.';
        }
        else {
            $addon->status = 1;
            $msg = 'Addon enabled successfully.';
        }
        $addon->save();
        return back()->with('success',$msg);
    }

    public function update()
    {
        return view('admin.addon.update');
    }

    public function updateSystem(Request $request)
    {
        $request->validate([
            'addon'=> 'required|mimes:zip'
        ]);
        
          
        if(class_exists('ZipArchive')) {
            if ($request->hasFile('addon')) {
                $path = Storage::disk('local')->put('addons', $request->addon);
              
                $zip = new ZipArchive;
                $result = $zip->open(storage_path('app/' . $path));
                $random_dir = strtolower(Str::random(10));
    
                if ($result === true) {
                   $result = $zip->extractTo(base_path('temp/' . $random_dir . '/addons'));
                   $zip->close();
                } else {
                    return back()->with('error','Can\'t open the zip file.');
                }
    
                $str = file_get_contents(base_path('temp/' . $random_dir . '/addons/update.json'));
                $config = json_decode($str, true);

                if($config['min_version'] != sysVersion()){
                     Storage::delete($path);
                    \File::deleteDirectory(base_path('temp'));
                    return back()->with('error','Minimum required system version is '.$config['min_version'].'. Your system version is '.sysVersion());
                }

                if(!empty($config['directory'])) {
                    foreach ($config['directory'][0]['path'] as $directory) {
                        if (is_dir(base_path($directory)) == false) {
                            mkdir(base_path($directory), 0777, true);
                        } else {
                           echo "error on creating directory";
                          
                        }
                      
                    }

                }

                if(!empty($config['assets'])) {
                    foreach ($config['assets'] as $file) {
                        copy(base_path('temp/' . $random_dir . '/' . $file['root_path']), str_replace('project','assets',base_path($file['update_path'])));
                    }
    
                }

                if(!empty($config['files'])) {
                    foreach ($config['files'] as $file) {
                        copy(base_path('temp/' . $random_dir . '/' . $file['root_path']), base_path($file['update_path']));
                    }
    
                }

            
                try {
                    $sql_path = base_path('temp/' . $random_dir . '/addons/sql/update.sql');
                    if (file_exists($sql_path)) {
                        DB::unprepared(file_get_contents($sql_path));
                    }
                } catch (\Exception $e) {
                    
                }
                
                Artisan::call('optimize:clear');
                Storage::delete($path);
                \File::deleteDirectory(base_path('temp'));

                return back()->with('success','System updated successfully.');

            }
        }
    }

}

