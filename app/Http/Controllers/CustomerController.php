<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Bank;
use App\Models\Stock;
use App\Models\Ledger;
use App\Models\Vendor;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Subinvoice;
use Illuminate\Support\Arr;
use App\Models\LedgerDetail;
use App\Models\Returndetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// this controller is for invoices
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $from = $request->has('from') && $request->from != "" ? $request->from : date('Y-m-d', strtotime(date('Y-m-d') . "-7 days"));
        $to = $request->has('to') && $request->to != "" ? $request->to : date('Y-m-d');
        $invoices = Invoice::select('invoices.*', 'vendors.name', 'banks.bank_name')->leftjoin('vendors', 'invoices.customer_id', '=', 'vendors.id')->leftjoin('banks', 'invoices.bank', '=', 'banks.id');
      	if($request->has('from') && $request->has('to')){
      		$invoices->whereRaw('DATE(invoices.created_at) >="' . $from . '"')->whereRaw('DATE(invoices.created_at) <="' . $to . '"');
        }
        if ($request->has('pdf')) {
            $invoices = $invoices->orderBy('invoices.id', 'asc')->get();
            $data = [
                'title' => 'Superups-quotation-', date('m/d/Y'),
                'date' => date('m/d/Y'),
                'invoices' => $invoices,
                'from' => $from,
                'to' => $to
            ];

            $pdf = PDF::loadView('marble.customers.sale_pdf', $data);

            return $pdf->download('Superups-Sales-' . date('m/d/Y h:i:a') . '.pdf');
        }
        $invoices = $invoices->orderBy('invoices.id', 'desc')->get();
        // dd($invoices);
        return view('marble.customers.index', compact('invoices', 'from', 'to'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::oldest()->get();
        //getting all cusomer
        $vendors = Vendor::where('type', 1)->get();
        $banks = Bank::oldest()->get();
        return view('marble.customers.create', compact('products', 'vendors', 'banks'));
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
                'customer_name' => 'required',
                'price' => 'required',
                'product_id' => 'required',
                'price' => 'required',
                'price_org' => 'required',
                'qty' => 'required',
                'total' => 'required',
                'bank' => 'requiredIf:payment_type,==,2',
            ]
        );

        DB::beginTransaction();

        try {

            $has_duplicates = count($request['product_id']) !== count(array_unique($request['product_id']));

            if ($has_duplicates) {
                throw new \Exception("Please remove duplicate products");
            }
            $created_at = $request->filled('date') ? Carbon::parse($request->date)->format('Y-m-d') . Carbon::now()->format(' H:i:s') :  Carbon::now()->format('Y-m-d') . Carbon::now()->format(' H:i:s');
           	
          	$overaall_amount = 0;
            $overaall_qty = 0;
            $BANK = $request->has("bank") && $request->bank != "" ? $request->bank : null;
            $discount = $request->has("discount") && $request->discount != "" ? $request->discount : 0;
            $invoice = new Invoice;
            $invoice->customer_id = $request['customer_name'] == 0 ? null : $request['customer_name'];
            $invoice->customer_name = $request->has('c_name') && $request->c_name != "" ? $request['c_name'] : 'Walking Customer';
            $invoice->total_amount = 0;
            $invoice->total_qty = 0;
            $invoice->payment_type = $request->payment_type;
            $invoice->detail = $request->detail;
            $invoice->bank = $BANK;
            $invoice->discount = $request->has("discount") && $request->discount != "" ? $request->discount : 0;
            $invoice->created_at = $created_at;

            $invoice->save();
            $detail = $request->has("detail") && $request->detail != "" ? $request->detail : "";
            $ledger_arr = [];

            for ($i = 0; $i < count($request['product_id']); $i++) {
                $find_subinvoice = Subinvoice::where('invoice_id', '=', $invoice->id)->where('product_id', '=', $request['product_id'][$i])->where('price', '=', $request['price_org'][$i])->get();
                if (count($find_subinvoice) > 0) {


                    $upd_subinvoice = Subinvoice::find($find_subinvoice[0]->id);
                    $upd_subinvoice->qty = $find_subinvoice[0]->qty + $request['qty'][$i];
                    $upd_subinvoice->total = $find_subinvoice[0]->total + ($request['qty'][$i] * $request['price_org'][$i]);
                    $upd_subinvoice->created_at = $created_at;
                    $upd_subinvoice->save();

                    $overaall_amount = $overaall_amount + (intval($request['qty'][$i]) * intval($request['price_org'][$i]));
                    $overaall_qty = $overaall_qty + intval($request['qty'][$i]);

                    // $stock=Stock::find($request['price'][$i]);
                    // $deduct_qty=$stock->qty-$request['qty'][$i];
                    // $stock->qty=$deduct_qty;
                    // $stock->save();
                    $this->deductstock($request['product_id'][$i], $request['qty'][$i]);
                } else {
                    // echo "<br>";
                    // echo "new";echo "<br>";
                    // echo $find_subinvoice;
                    $product_name = $this->getProductNamebyID($request->product_id[$i]);
                    // $detail .= $product_name." | Quantity = ".$request->qty[$i]." | Price = ".$request->price_org[$i]." | Total = ".$request->qty[$i] * $request->price_org[$i].",";
                    $ledger_arr[] = ["lproduct" => $product_name, "lqty" => $request->qty[$i], "lprice" => $request->price_org[$i], "ltotal" => $request->qty[$i] * $request->price_org[$i], "type" => 0, "created_at" => $created_at];

                    $subinvoice = new Subinvoice;
                    $subinvoice->invoice_id = $invoice->id;
                    $subinvoice->product_id = $request['product_id'][$i];
                    $subinvoice->price = $request['price_org'][$i];
                    $subinvoice->qty = $request['qty'][$i];
                    $subinvoice->stock_id = $request['price'][$i];
                    $subinvoice->purchase_price = $request['purchase_price'][$i];
                    $subinvoice->total = $request['qty'][$i] * $request['price_org'][$i];
                  	$subinvoice->created_at = $created_at;
                    $subinvoice->save();

                    $overaall_amount = $overaall_amount + (intval($request['qty'][$i]) * intval($request['price_org'][$i]));
                    $overaall_qty = $overaall_qty + intval($request['qty'][$i]);
                    //managing stock
                    $this->deductstock($request['product_id'][$i], $request['qty'][$i]);
                }
            }
            $paidamount = 0;
            // if($request->customer_name != 0)
            // {
            //add ledger
            $walking_customer = $request->customer_name != 0 ? null : $request->c_name;
            $paidamount = $request->has('paid_amount') && $request->paid_amount != "" ? $request->paid_amount : 0;
            $ldgr_id = $this->addCusomerLedger($request->customer_name, ($overaall_amount - $discount), $request->type, $invoice->id, $detail, $paidamount, $BANK, $request->payment_type, $created_at, $walking_customer . 'test');
            if ($ldgr_id) {
                // Arr::add($ledger_arr, 'ledger_id' , $ldgr_id);
                $ldgr_id_arr = array('ledger_id' => $ldgr_id);
                $ledger_arr = array_map(function ($item) use ($ldgr_id) {
                    $item['ledger_id'] = $ldgr_id;
                    return $item;
                }, $ledger_arr);
            }
            // }
            if ($request->type != 0) {
                $amount = $request->type == 2 ? $paidamount : ($overaall_amount - $discount);
                if ($amount > 0) {
                    $this->insertExpensedata($amount, $detail, $BANK, $request->payment_type, $created_at, $invoice->id, $request->detail_hidden, $request->type);
                }
            }
            if (count($ledger_arr) > 0) {
                LedgerDetail::insert($ledger_arr);
            }
            $paidamount = $request->type == 1 ? ($overaall_amount - $discount) : $paidamount;

            $update_invoice = Invoice::find($invoice->id);
            $update_invoice->total_amount = $overaall_amount;
            $update_invoice->total_qty = $overaall_qty;
            $update_invoice->paid_amount = $paidamount;
            $update_invoice->created_at = $created_at;

            $update_invoice->save();

            DB::commit();
            return redirect(route('sale_print') . "?invoice_id=" . $invoice->id . "&paidamount=" . ($paidamount));
            //return redirect('customer')->with('success', 'Stock Sold');
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()->with(['error' => $th->getMessage()]);
        }
    }
    //insert in expense table
    public function insertExpensedata($amount, $detail, $bank, $payment_type, $created_at, $invoice_id, $detail_hidden, $type = 0)
    {
        $data = ["credit" => $amount, "detail" => $detail, "detail_hidden" => $detail_hidden, "bank" => $bank, "payment_type" => $payment_type, "invoice_id" => $invoice_id, "created_at" => $created_at, 'type' => $type];
        Expense::insert($data);
    }
    public function deductstock($product_id, $qty)
    {
        //deduct from earliest stock
        $stock = Stock::select('id')->where('product_id', $product_id)->where('qty', '>=', $qty)->orderby('id', 'asc')->limit(1)->get();
        if (count($stock) > 0) {
            $this->updatestockqty($stock[0]->id, $qty);
        } else {
            $quantity = $qty;
            $count = 0;
            //iteration when qty meets to zero
            while ($quantity > 0) {
                $count++;
                $stock = Stock::select('id', 'qty')->where('product_id', $product_id)->orderby('qty', 'desc')->limit(1)->get();

                if (count($stock) > 0) {

                    if ($quantity > $stock[0]->qty) {
                        $update_stock = Stock::where('id', $stock[0]->id)->update(['qty' => 0]);
                    } else {
                        $update_stock = Stock::where('id', $stock[0]->id)->update(['qty' => ($stock[0]->qty - $quantity)]);
                    }
                    $quantity = $quantity - $stock[0]->qty;
                } else {
                    $quantity = 0;
                }
            }
        }
    }
    public function updatestockqty($stock_id, $qty)
    {
        $stock = Stock::find($stock_id);
        $deduct_qty = $stock->qty - $qty;
        $stock->qty = $deduct_qty;
        $stock->save();
    }
    public function getProductNamebyID($product_id)
    {
        $product = Product::select('name')->where('id', $product_id)->get();
        return $product[0]->name;
    }
    public function addCusomerLedger($id, $amount, $type, $invoice_id, $detail, $paidamount, $bank, $payment_type, $created_at, $walking_customer)
    {
        $data = [];

        $type = $type == 2 && $paidamount < 1 ? 0 : $type;
        //dd($type);
        if ($type == 2) {
            $data[] = ["walking_customer" => $walking_customer, "amount" => -$amount, "details" => $detail, "vendor_id" => $id, "bank" => $bank, "payment_type" => $payment_type, "created_at" => $created_at, "invoice_id" => $invoice_id, "full" => 2];

            $data[] = ["walking_customer" => $walking_customer, "amount" => $paidamount, "details" => $detail, "vendor_id" => $id, "bank" => $bank, "payment_type" => $payment_type, "created_at" => $created_at, "invoice_id" => $invoice_id, "full" => 2];
            //$amount = $type == 2 ? $paidamount : $amount;

        } else if ($type == 1) {
            $data[] = ["walking_customer" => $walking_customer, "amount" => $amount, "details" => $detail, "vendor_id" => $id, "bank" => $bank, "payment_type" => $payment_type, "created_at" => $created_at, "invoice_id" => $invoice_id, "full" => 1];
        } else {
            $data[] = ["walking_customer" => $walking_customer, "amount" => -$amount, "details" => $detail, "vendor_id" => $id, "bank" => $bank, "payment_type" => $payment_type, "created_at" => $created_at, "invoice_id" => $invoice_id];
        }
        if (count($data) > 0) {
            //dd($data);
            Ledger::insert($data);
            $id = DB::getPdo()->lastInsertId();
            return $id;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $subinvoices = Subinvoice::has('invoice')->orderby('id', 'desc');
        $total = Subinvoice::where('invoice_id', '>', 0);
        if ($id == 0) {
            if ($request->has("date") && $request->date != "") {
                $subinvoices = $subinvoices->whereRaw("DATE(created_at) = '" . $request->date . "'");
                $total = $total->whereRaw("DATE(created_at) = '" . $request->date . "'");
            }

            $subinvoices = $subinvoices->get();
        } else {
            $subinvoices = $subinvoices->where('invoice_id', '=', $id)->get();
            $total = $total->where('invoice_id', '=', $id);
        }
        $total = $total->sum('total');

        $date = $request->has("date") && $request->date != "" ? $request->date : "";
        return view('marble.customers.subdetails', compact('subinvoices', 'id', 'date', 'total'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * Return function
     */
    public function edit($id)
    {
        //getting invoice detail
        $invoice = Invoice::select('invoices.*', 'vendors.name', 'banks.bank_name')->leftjoin('vendors', 'invoices.customer_id', '=', 'vendors.id')->leftjoin('banks', 'invoices.bank', '=', 'banks.id')->where('invoices.id', $id)->get();
        if (count($invoice) < 1) {
            return redirect(route('searchinvoice'))->with('error', 'Not Found!');
        }
        //dd($invoice);
        $subinvoice = Subinvoice::select('subinvoices.*', 'products.name')->join('products', 'subinvoices.product_id', '=', 'products.id')->where('invoice_id', $id)->get();
        $banks = Bank::oldest()->get();

        return view('marble.customers.return', compact('invoice', 'subinvoice', 'id', 'banks'));
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
        $invoice = Invoice::findOrFail($id);
        $subinvoices = Subinvoice::where('invoice_id', $invoice->id)->get();

        // Update Stock Records
        foreach ($subinvoices as $sub) {
            $stock = Stock::where('product_id', $sub->product_id)->first();
            $stock->qty = $stock->qty + $sub->qty;
            $stock->update();
        }

        // Delete Expense
        $expense = Expense::where('invoice_id', $invoice->id)->first();
        if ($expense) $expense->delete();

        // Ledger
        $ldgrs = Ledger::where('invoice_id', $invoice->id)->get();
        foreach ($ldgrs as $ldgr) {
            if ($ldgr) {
                // Delete Ledger Details
                $ldgr_details = LedgerDetail::where('ledger_id', $ldgr->id)->get();
                foreach ($ldgr_details as $details) {
                    $details->delete();
                }
                // Delete Ledger
                $ldgr->delete();
            }
        }

        // Delete Sub Invoices
        foreach ($subinvoices as $sub) {
            $sub->delete();
        }
        // Finally Delete Invoice
        $invoice->delete();
        return redirect()->back()->with('success', 'Wrong Entry Deleted Succesfully!');
    }
    //search invoice id for return
    public function search_invoice(Request $request)
    {

        if ($request->has('invoice_id') && $request->invoice_id != "") {
            return redirect(route('customer.edit', $request->invoice_id));
        }
        return view('marble.customers.search_invoice');
    }
    public function returnstock(Request $request)
    {
        $request->validate(
            [
                'invoice_id' => 'required'
            ]
        );
        // dd($request->all());
        $total_amount = 0;
        $total = $request->invoice_total;
        $total_qty = $request->invoice_qty;
        $check = false;
        $paid_amount = $request->paid_amount;
        // $paid_amount = $paid_amount < 0 ? 0 : $paid_amount;

        DB::beginTransaction();
        try {
            //
            $return_data = [];
            $detail = "Return = ";
            $created_at = date('Y-m-d H:i:s');
            $ledger_arr = [];

            for ($i = 0; $i < count($request->product_id); $i++) {
                if ($request->qty[$i] > 0) {
                    $check = true;
                    $total = $total - ($request->qty[$i] * $request->price[$i]);
                    $total_qty = $total_qty - $request->qty[$i];

                    $detail .= $request->product_name[$i] . " | Quantity = " . $request->qty[$i] . " | Price = " . $request->price[$i] . " | Total = " . $request->qty[$i] * $request->price[$i] . ",";

                    $ledger_arr[] = ["lproduct" => $request->product_name[$i], "lqty" => $request->qty[$i], "lprice" => $request->price[$i], "ltotal" => $request->qty[$i] * $request->price[$i], "type" => 0, "created_at" => $created_at];

                    $subinvoice = Subinvoice::where('id', $request->subinvoice_id[$i])->first();

                    if ($subinvoice->invoice->paid_amount == 0 || $subinvoice->invoice->total_amount > $subinvoice->invoice->paid_amount - $subinvoice->invoice->discount) {
                        $total_amount += 0;
                    } else {
                        $total_amount += $request->qty[$i] * $request->price[$i];
                    }

                    if ($request->qty[$i] == $request->prev_qty[$i]) {
                        //delete invoice detail
                        $subinvoice->delete();
                    } else {
                        //update invoice detail
                        $newqty = $request->prev_qty[$i] - $request->qty[$i];
                        $newtotal = $newqty * $request->price[$i];
                        $subinvoice->update(["qty" => $newqty, "total" => $newtotal]);
                    }
                    $return_data[] = ["product_id" => $request->product_id[$i], "qty" => $request->qty[$i], "invoice_id" => $request->invoice_id, "created_at" => date('Y-m-d H:i:s')];
                    //update stock
                    $stock = Stock::where('product_id', $request->product_id[$i])->orderBy('id', 'DESC')->first();
                    $new_stock_qty = $stock->qty + $request->qty[$i];
                    $stock->qty = $new_stock_qty;
                    $stock->save();
                }
            }

            if ($check == true) {
                //insert return data
                Returndetail::insert($return_data);
                //update invoice
                Invoice::where("id", $request->invoice_id)->update(["total_amount" => $total, "total_qty" => $total_qty, "paid_amount" => $paid_amount - $total_amount]);
                //add ledger
                $invoice = Invoice::find($request->invoice_id);
                // if($request->vendor != null){
                Ledger::insert(["invoice_id" => $invoice->id, "walking_customer" => $invoice->customer_name, "amount" => -1 * $total_amount, "details" => $detail, "vendor_id" => $request->vendor, "bank" => $request->bank, "payment_type" => $request->payment_type, "created_at" => $created_at, "return" => "1"]);

                //
                $detail_hidden = null;
                if ($request['payment_type'] != 0) {
                    $detail_hidden =  'Expense Bank';
                }
                //
                Expense::insert(['type' => 5, "detail_hidden" => $detail_hidden, "invoice_id" => $invoice->id, "debit" => ($total_amount), "detail" => $detail, "bank" => $request->bank, "payment_type" => $request->payment_type, "created_at" => $created_at]);
                // }
                if (count($ledger_arr) > 0) {
                    $lid = Ledger::latest()->limit(1)->first()->id;
                    $ledger_arr = array_map(function ($item) use ($lid) {
                        $item['ledger_id'] = $lid;
                        return $item;
                    }, $ledger_arr);
                    LedgerDetail::insert($ledger_arr);
                }
            }
            DB::commit();
            return redirect('dashboard')->with('success', 'Invoice Returned Succesfully!');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
