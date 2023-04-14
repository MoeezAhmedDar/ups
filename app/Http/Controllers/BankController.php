<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\Bankbalance;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Ledger;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $bank = Bank::select('banks.*')->selectRaw('sum(amount) as balance')->join('bankbalances','banks.id','=','bankbalances.bank_id')->groupby('bank_id')->get();
        $bank = Bank::with('bankBalances')->get();
        $bank_data = [];
        foreach($bank as $b){
            $debit = Expense::where('bank',$b->id)->where('bank_balance', 0)->sum('debit');
            $credit = Expense::where('bank',$b->id)->where('bank_balance', 0)->sum('credit');

            $total = ($b->bankBalances->sum('amount')+$credit) - $debit;
            $bank_data[] = ["id" => $b->id,"total" => $total,"bank_name" => $b->bank_name,"detail" => $b->detail];
        }
       // dd($bank_data[0]["id"]);
        return view('bank.index',compact('bank_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $request->validate(
            [
                'name' => 'required',
            ]
        );
        $add = new Bank;
        $add->bank_name = $request->name;
        $add->detail = $request->has('detail') && $request->detail != "" ? $request->detail : null;
        $add->created_at = date('Y-m-d H:i:s');
        $add->save();
        //$id = $add->id;
        //$b = new Bankbalance;
        //$b->bank_id = $id;
        //$b->amount = 0;
        //$b->detail = "add";
        //$b->created_at = date('Y-m-d H:i:s');
        //$b->save();
        return redirect(route('bank.index'))->with('success', 'Bank Added Succesfully!');
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
        dd($id);
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
        $check = Invoice::where('bank',$id)->count();
        if($check > 0){
            return redirect('dashboard')->with('error', 'Bank data is exist!');
        }
        Bank::where('id',$id)->delete();
        return redirect('dashboard')->with('success', 'Bank Deleted Succesfully!');

    }
    public function add_bank_balance(Request $request){

        $request->validate(
            [
                'bank_id' => 'required',
                'amount' => 'required',
                'type' => 'required',
            ]
        );
        $add = new Bankbalance;
        $add->bank_id = $request->bank_id;
        $add->amount = $request->type == '2' ?  $request->amount :'-'.$request->amount;
        $add->detail = $request->description;
        $add->created_at = date('Y-m-d H:i:s');
        $add->save();
        // Adding Expense
        $expense = new Expense();
        if($request->type ==2)
        {
            $expense->credit = $request->amount;
            $expense->detail =  "Credited With Amount ". $request->amount;
        }
        else{
            $expense->debit = $request->amount;
            $expense->detail =  "Debited With Amount ". $request->amount;
        }
        
        $expense->detail_hidden =  "TRANSFER";
        $expense->type = 0;
        $expense->bank = $request->bank_id;

        $expense->payment_type = 2;
        $expense->status = 0;
        $expense->bank_balance = 1;
        $expense->save();
        // Adding Ledger
        $ldgr = new Ledger();
        $ldgr->vendor_id = 0;
        $ldgr->amount = $request->amount;
        if($request->type ==1)
        {
            $ldgr->details =  "Credited With Amount ". $request->amount;
        }
        else
        {
            $ldgr->details =  "Debited With Amount ". $request->amount;
        }
        $ldgr->bank = $request->bank_id;
        $ldgr->walking_customer = "TRANSFER";
        $ldgr->payment_type = 2;
        $ldgr->save();
        //return redirect('dashboard')->with('success', 'Bank Balance Added Succesfully!');
        return redirect(route('bank.index'))->with('success', 'Bank Added Succesfully!');
    }
}
