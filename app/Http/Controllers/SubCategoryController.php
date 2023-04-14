<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Category;
use App\Models\Product;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $subcategory = Subcategory::select('subcategories.*','categories.name as cname')->join('categories','subcategories.category','=','categories.id')->orderBy('categories.name','asc')->get();
        return view('marble.subcategory', compact('subcategory'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::oldest()->get();
        return view('marble.add_subcategory', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',  
            'category' => 'required',
        ]);
        $subcategory = new Subcategory();
        $subcategory->name = $request->input('name');
        $subcategory->category = $request->input('category');
        $subcategory->save();
        return redirect('/view_subcategory')->with('status', 'subcategory added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $check = Product::where('p_subcategory',$id)->get();
        if(count($check) > 0){
            return redirect('view_subcategory')->with('error', 'Product Exist in this Company!');
        }
        $delete = Subcategory::where('id',$id)->delete();
        return redirect('view_subcategory')->with('success', 'Deleted Successfuly!');
    }
}
