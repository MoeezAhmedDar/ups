<?php

namespace App\Http\Controllers\Marble;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Invoice;
use App\Models\Subinvoice;
use PDF;
class ProductController extends Controller
{
   public function index(Request $request)
   {

    $company_id = $request->has('company_id') && $request->company_id != "" ? $request->company_id : null;
    $company_name = $request->has('company_name') && $request->company_name != "" ? $request->company_name : null;
       $product = Product::select('products.*','products.name as pname')->orderby('products.name');
       if($company_id != null){
        $product = $product->where('p_subcategory',$company_id);
         }
         if($company_name != null){
            $product = $product->join('subcategories','products.p_subcategory','subcategories.id')->where('subcategories.name',$company_name);
         }
         $product = $product->get();
         if($request->has('pdf')){
           // dd($this->pdf($product));
           $data = [
            'title' => 'Superups-quotation-',date('m/d/Y'),
            'date' => date('m/d/Y'),
            'product' => $product
        ];
          
        $pdf = PDF::loadView('marble.products.pdf', $data);
    
        return $pdf->download('Superups-Products-'.date('m/d/Y h:i:a').'.pdf');
         }
       $sub_categories = Subcategory::select('subcategories.*','categories.name as cname')->join('categories','subcategories.category','=','categories.id')->orderby('name')->get();
       $sub_categoriesbyname = Subcategory::select('subcategories.name')->join('categories','subcategories.category','=','categories.id')->orderby('name')->groupby('name')->get();
       return view('marble.products.product', compact('product','sub_categories','company_id','sub_categoriesbyname','company_name'));
   }


   public function addProduct(Request $request)
   {
      
       $category = Category::oldest()->get();
       $subcategory = Subcategory::oldest()->get();
       return view('marble.products.addProduct', compact('category','subcategory'));
   }

   public function sub_category_ajax($id)
   {
       $sub_categories=Subcategory::where('category', '=' , intval($id))->get();
      
      return response()->json([
          'subcategories' => $sub_categories,
      ]);
   }

   public function price_search_ajax($id)
   {
       $prices=Stock::where('product_id', '=' , intval($id))->where('qty', '>' , 0)->orderby('id','desc')->limit(1)->get();
      if(count($prices) > 0)
      {
        return response()->json([
            'prices' => $prices[0]->sale_price,
            'purchase_price'=>$prices[0]->price
        ]);
      }
        return response()->json([
            'prices' => 0,
        ]);
      
   
   }

   public function available_qty_search_ajax($id)
   {
       //$prices=Stock::where('id', '=' , intval($id))->get();
       $prices=Stock::where('product_id', '=' , intval($id))->sum('qty');
      return response()->json([
          'available' => $prices,
      ]);
   }

   public function insertProduct(Request $request)
   {
        $request->validate([
            'p_category'=>'required',
            'p_subcategory'=>'required',
            'name'=>'required',
            // 'price'=>'required',
            // 'p_color'=>'required',
        ]);
        $product = new Product();
        $product->p_category = $request->input('p_category');
        $product->p_subcategory = $request->input('p_subcategory');
        $product->name = $request->input('name');
        $product->price = 0;//$request->input('price');
       // $product->p_color = $request->input('p_color');
        $product->save();
        return redirect('product')->with('status', 'product added successfully');
   }

   public function updateProduct($id)
   {
       $product = Product::where('id', $id)->first();
       $category = Category::oldest()->get();
       return view('marble.products.edit',compact('product', 'category'));
   }

   public function editProduct(Request $request, $id)
   {
       $request->validate([
           'p_category' => 'required',
        //    'price' => 'required',
        //    'p_color' => 'required',
           'name' => 'required',
       ]);
    $product = Product::find($id);
    $product->p_category = $request->input('p_category');
    $product->name = $request->input('name');
    // $product->price = $request->input('price');
    // $product->p_color = $request->input('p_color');
    $product->update();
    return redirect('product')->with('status', 'product updated successfully');
}

public function deleteProduct($id)
{
    $check = Stock::where('product_id',$id)->count();
    if($check > 0){
        return redirect('product')->with('error', 'Product Exist in Stock!');
    }
    $product = Product::find($id);
    $product->delete();
    return redirect('product')->with('success', 'product deleted successfully');

}
    public function availblestock(Request $request)
    {
       
        $stocks = Stock::select('products.name','products.id','subcategories.name as company_name')->selectRaw('sum(stocks.qty) as total_qty')->join('products','stocks.product_id','=','products.id')->join('subcategories','products.p_subcategory','=','subcategories.id')->groupby('product_id')->orderby('products.name');
        if($request->has('pdf')){
            $stocks = $stocks->get();
            $data = [
             'title' => 'Superups-quotation-',date('m/d/Y'),
             'date' => date('m/d/Y'),
             'stocks' => $stocks
         ];
           
         $pdf = PDF::loadView('stock.availble_pdf', $data);
     
         return $pdf->download('Superups-Availble-stock-'.date('m/d/Y h:i:a').'.pdf');
          }
          $stocks = $stocks->get();
         
        return view('stock.stock', compact('stocks'));
    }
    //sale print
    public function sale_print(Request $request)
    {
        
        if($request->has('invoice_id') && $request->invoice_id != "")
        {
            $id = $request->invoice_id;

            $data = [];
            $invoice = Invoice::select('invoices.*','vendors.name','banks.bank_name')->leftjoin('vendors','invoices.customer_id','=','vendors.id')->leftjoin('banks','invoices.bank','=','banks.id')->where('invoices.id',$request->invoice_id)->get();
            //dd($invoice[0]);
            if(count($invoice) > 0)
            {
                $invoice_detail = Subinvoice::select('subinvoices.*','products.name as pname')->join('products','subinvoices.product_id','=','products.id')->where('invoice_id',$request->invoice_id)->get();
                //dd($invoice);
                $paidamount = $request->has('paidamount') && $request->paidamount != "" ? $request->paidamount : 0;
                return view('marble.customers.sale_print',compact('invoice','invoice_detail','id','paidamount'));     
            }
            
        }
    }
}