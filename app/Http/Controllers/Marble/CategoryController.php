<?php

namespace App\Http\Controllers\Marble;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;

class CategoryController extends Controller
{

  
    public function index()
    {
        $category = Category::oldest()->get();
        return view('marble.category', compact('category'));
    }

    public function addCategory()
    {
        return view('marble.addCategory');
    }   

    public function insertCategory(Request $request)
    {
        $request->validate([
            'name' => 'required',   
        ]);
        $category = new Category();
        $category->name = $request->input('name');
        $category->save();
        return redirect('category')->with('status', 'category added successfully');
    }
    public function deleteCategory($id)
    {
        $check = Subcategory::where('category',$id)->count();
        if($check > 0){
            return redirect('category')->with('error', 'Category Exist in Stock!');
        }
        $cat = Category::find($id);
        $cat->delete();
        return redirect('category')->with('success', 'Category deleted successfully');

    }

}