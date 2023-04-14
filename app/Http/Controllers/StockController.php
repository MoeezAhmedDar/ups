<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Vendor;
use App\Models\Ledger;
use App\Models\LedgerDetail;
use App\Models\Expense;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $product_id = $request->has('product_id') && $request->product_id != "" ? $request->product_id : null;
        $stocks = Stock::select('stocks.*', 'vendors.name as vname')->join('vendors', 'stocks.vendor_id', '=', 'vendors.id')->orderby('id', 'desc');
        if ($product_id != null) {
            $stocks = $stocks->where('product_id', $product_id);
        }
        if ($request->has('pdf')) {
            $stocks = $stocks->get();
            $data = [
                'title' => 'Superups-quotation-', date('m/d/Y'),
                'date' => date('m/d/Y'),
                'stocks' => $stocks,
                'product_id' => $product_id
            ];

            $pdf = PDF::loadView('stock.stockdetail_pdf', $data);

            return $pdf->download('Superups-stock-detail' . date('m/d/Y h:i:a') . '.pdf');
        }
        $stocks = $stocks->get();
        // dd($stocks);
        $products = Product::select('id', 'name')->orderby('name')->get();
        return view('stock.index', compact('stocks', 'products', 'product_id'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::oldest()->get();
        $vendors = Vendor::oldest()->get(); //where('type',0)->get();
        $banks = Bank::oldest()->get();
        return view('stock.create', compact('products', 'vendors', 'banks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate(
            [
                'product_id' => 'required|exists:products,id',
                'vendor_id' => 'required|exists:vendors,id',
                'price' => 'required',
                'qty' => 'required',
                'date' => 'required',
                'total' => 'required',
            ]
        );
        // dd($request);
        $data_arr = [];
        $ledger = 0;
        $detail = $request->has("detail") && $request->detail != "" ? $request->detail : "";
        $detail_hidden = $request->has("detail_hidden") && $request->detail_hidden != "" ? $request->detail_hidden : "";
        $ledger_arr = [];
        $created_at = date('Y-m-d H:i:s');
        $bank = $request->payment_type > 0 ? $request->bank : null;
        try {
            DB::beginTransaction();
            for ($i = 0; $i < count($request->product_id); $i++) {

                $product_name = $this->getProductNamebyID($request->product_id[$i]);
                // $detail .= $product_name." | Quantity = ".$request->qty[$i]." | Price = ".$request->price[$i]." | Total = ".$request->qty[$i] * $request->price[$i].",";
                $ledger_arr[] = ["lproduct" => $product_name, "lqty" => $request->qty[$i], "lprice" => $request->price[$i], "ltotal" => $request->qty[$i] * $request->price[$i], "type" => 0, "created_at" => $created_at];
                $data_arr[] = [
                    "product_id" => $request->product_id[$i],
                    "vendor_id" => $request->vendor_id,
                    "price" => $request->price[$i],
                    "sale_price" => $request->sprice[$i],
                    "qty" => $request->qty[$i],
                    "qty_received" => $request->qty[$i],
                    "date" => $request->date[$i],
                    "total" => $request->qty[$i] * $request->price[$i],
                    "type" => $request->type,
                    "created_at" => $created_at
                ];

                $ledger += $request->qty[$i] * $request->price[$i];
            }
            if (count($data_arr) > 0) {
                Stock::insert($data_arr);
            }
            $data = [];
            $expdata = [];
            if ($request['type'] == 1) {
                $data[] = ["type" => 1, "amount" => $ledger, "details" => $detail, "vendor_id" => $request->vendor_id, "created_at" => $created_at, "bank" => $bank, "payment_type" => $request->payment_type, "full" => 1];
                // $data[] = ["type"=>1, "amount" => -$ledger,"details" => $detail,"vendor_id" => $request->vendor_id,"created_at" => $created_at,"bank" => $bank, "payment_type" => $request->payment_type];
                $expdata = ["debit" => $ledger, "detail" => $detail, "detail_hidden" => $detail_hidden, "created_at" => $created_at, "bank" => $bank, "payment_type" => $request->payment_type, 'type' => 1];
            } else if ($request['type'] == 2) {
                $data[] = ["type" => 1, "amount" => $ledger, "details" => $detail, "vendor_id" => $request->vendor_id, "created_at" => $created_at, "bank" => $bank, "payment_type" => $request->payment_type, "full" => 2];

                $data[] = ["type" => 1, "amount" => -$request->paid_amount, "details" => $detail, "vendor_id" => $request->vendor_id, "created_at" => $created_at, "bank" => $bank, "payment_type" => $request->payment_type, "full" => 2];
                //add expense
                $expdata = ["debit" => $request->paid_amount, "detail" => $detail, "detail_hidden" => $detail_hidden, "created_at" => $created_at, "bank" => $bank, "payment_type" => $request->payment_type, 'type' => 2];
            } else {
                $data[] = ["type" => 1, "amount" => $ledger, "details" => $detail, "vendor_id" => $request->vendor_id, "created_at" => $created_at, "bank" => $bank, "payment_type" => $request->payment_type];
            }
            if (count($data) > 0) {
                Ledger::insert($data);
            }
            if (count($expdata) > 0) {
                Expense::insert($expdata);
            }

            if (count($ledger_arr) > 0) {
                LedgerDetail::insert($ledger_arr);
            }
            DB::commit();
            return redirect()->route('stock.index')->with('success', 'Stock Added');
        } //endtry
        catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
    function getProductNamebyID($product_id)
    {
        $product = Product::select('name')->where('id', $product_id)->get();
        return $product[0]->name;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        if ($stock->qty_received > $stock->qty) {
            return back()->with('error', 'Unable to delete. Items sold from this stock.');
        }

        $stock->expenseByCreatedAt()->delete();
        $stock->ledgerByCreatedAt()->delete();
        $stock->ledgerDetailByCreatedAt()->delete();
        $stock->delete();

        return redirect()->route('stock.index')->with('success', 'Stock Deleted');
    }
}
