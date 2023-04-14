<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Quotationdetail;
use PDF;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        //\DB::enableQueryLog();
        $from = "";$to = "";
        $quotation = Quotation::orderby('id','DESC');
        if($request->has('from') && $request->from != "" && $request->has('to') && $request->to != ""){
            $from =$request->from;   $to =$request->to; 
            $quotation = $quotation->whereRaw('DATE(created_at) >="'.$from.'"')->whereRaw('DATE(created_at) <="'.$to.'"');
        }
        $quotation = $quotation->get();
        // $from = $request->has('from') && $request->from != "" ? $request->from : date('Y-m-d');
        // $to = $request->has('to') && $request->to != "" ? $request->to : date('Y-m-d');
        
        //dd(\DB::getQueryLog());
        return view('quotation.index',compact('quotation','from','to'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
       return view('quotation.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //dd($request);
        // $request->validate(
        //     [
        //         'invoice_no' => 'required',
        //         //'name' => 'required',
        //         'contact' => 'required',
        //         'address' => 'required'
        //     ]         
        // );
        if($request->has('id') && $request->id != ""){
            //update
            Quotation::where('id',$request->id)->update(["invoice_no" => $request->invoice_no, "customer_name" => $request->name,"address" => $request->address, "contact" => $request->contact,"listing_name" => $request->des[0]]);
            //delete previous quotation detail
            Quotationdetail::where('quotation_id',$request->id)->delete();
            $quotation_id = $request->id;
        }else{
            $quotation = new Quotation();
            $quotation->invoice_no = $request->invoice_no;
            $quotation->customer_name = $request->name;//$request->item[0];
            $quotation->address = $request->address;
            $quotation->contact = $request->contact;
            $quotation->created_at = date('Y-m-d H:i:s');
            $quotation->listing_name =  $request->des[0];
            $quotation->save();
            $quotation_id = $quotation->id;
        }
       
        $data_arr = [];
        for($i = 0;$i<count($request->item);$i++)
        {
            $data_arr[] = ["quotation_id" => $quotation_id,"item" => $request->item[$i], "quantity" => $request->qty[$i],"price" => $request->price[$i],"description" => $request->des[$i],"discount" => $request->discount[$i],"created_at" => date('Y-m-d H:i:s')]; 
        }
        if(count($data_arr) > 0){
            Quotationdetail::insert($data_arr);
            return redirect('print_quotation?q='.$quotation_id)->with('success', 'Quotation Updated Succesfully!');
        }

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
        $quotation = Quotation::where("id",$id)->get();
        if(count($quotation) < 1)
        {
            return redirect('dashboard')->with('error', 'Quotation Not exist!');
        }
        $quotation_detail = Quotationdetail::where('quotation_id',$id)->get();
        return view('quotation.edit',compact('quotation','quotation_detail'));
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
    }
    //generating pdf
    public function generatePDF(Request $request)
    {
        if(!$request->has('q') || $request->q == ""){
            dd("Invalid");
        }
        $quotation_id = $request->q;
        $quotation = Quotation::where('id',$quotation_id)->get();
        $quotation_detail = Quotationdetail::where('quotation_id',$quotation_id)->get();
        $data = [
            'title' => 'Superups-quotation-',date('m/d/Y'),
            'date' => date('m/d/Y'),
            'quotation' => $quotation[0],
            'quotation_detail' => $quotation_detail,
        ];
          
        $pdf = PDF::loadView('quotation.generatepdf', $data);
    
        return $pdf->download('Superups-quotation-'.date('m/d/Y').'.pdf');
    }
    public function printquotation(Request $request){
        if(!$request->has('q') || $request->q == ""){
            dd("Invalid");
        }
        $quotation_id = $request->q;
        $quotation = Quotation::where('id',$quotation_id)->get();
        $quotation_detail = Quotationdetail::where('quotation_id',$quotation_id)->get();
        return view('quotation.print',compact('quotation_id','quotation','quotation_detail'));
    }
}
