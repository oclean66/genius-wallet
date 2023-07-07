<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use Datatables;

class FaqController extends Controller
{

   public function __construct(FaqResource $resource)
   {
       $this->resource = $resource;
   }

    public function datatables()
    {

        $datas = Faq::orderBy('id','desc');
  
         return Datatables::of($datas)
                            ->addColumn('action', function(Faq $data) {
                                return 
                                '<div class="actions-btn">
                                    <a href="' . route('admin.faq.edit',$data->id) . '" class="btn btn-primary btn-sm btn-rounded">
                                        <i class="fas fa-edit"></i> '.__("Edit").'
                                    </a>
                                    <button type="button" data-toggle="modal" data-target="#confirm-delete"  data-href="' . route('admin.faq.destroy',$data->id) . '" class="btn btn-danger btn-sm btn-rounded">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>';
                            })
                            ->rawColumns(['action'])
                            ->toJson();
                        }
   

  
    public function index()
    {
        return view('admin.faq.index');
    }

  
    public function create()
    {
        return view('admin.faq.create');
    }

   
    public function store(FaqRequest $request)
    {
        $this->resource->store($request->only('title','details'));
        return response()->json(__('Data Added successfully'));
    }

  
    public function edit(Faq $faq)
    {
        return view('admin.faq.edit',compact('faq'));
    }

    
    public function update(FaqRequest $request, Faq $faq)
    {
        $this->resource->update($request->only('title','details'),$faq);
        return response()->json(__('Data Updated successfully'));
    }

    public function destroy(Faq $faq)
    {
        $this->resource->destroy($faq);
        return response()->json(__('Data Deleted successfully'));
    }
}
