<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogCategoryController extends Controller
{

    public function index()
    {
        $categories = BlogCategory::latest()->paginate(15);
        return view('admin.cblog.index',compact('categories'));
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:blog_categories',
            'status' => 'required'
        ]);

        $cat = new BlogCategory();
        $cat->name = $request->name;
        $cat->slug = Str::slug($request->name);
        $cat->status = $request->status;
        $cat->save();

        return back()->with('success',__('Category added successfully'));
    }

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:blog_categories,name,'.$id,
            'status' => 'required'
        ]);

        $cat = BlogCategory::findOrFail($id);
        $cat->name = $request->name;
        $cat->slug = Str::slug($request->name);
        $cat->status = $request->status;
        $cat->update();

        return back()->with('success',__('Category updated successfully'));
    }
}
