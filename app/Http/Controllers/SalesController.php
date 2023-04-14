<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Stock;
use App\Models\Ledger;
use App\Models\Vendor;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Subinvoice;
use App\Models\LedgerDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function edit(Request $request, $id)
    {
        $products = Product::oldest()->get();
        //getting all cusomer
        $vendors = Vendor::where('type', 1)->get();
        $banks = Bank::oldest()->get();

        $invoice = Invoice::with(['customer', 'sub_invoices', 'sub_invoices.product'])->find($id);
        // dd($invoice);
        if ($invoice->count() < 1) {
            return back()->with('error', 'Not Found!');
        }

        return view('sales.edit', compact('products', 'vendors', 'banks', 'invoice'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                // 'customer_name' => 'required',
                'price' => 'required',
                'product_id' => 'required',
                'price' => 'required',
                'price_org' => 'required',
                'qty' => 'required',
                'total' => 'required',
                'bank' => 'requiredIf:payment_type,==,2',
            ]
        );

        $CustomerController = new CustomerController();
        DB::beginTransaction();
        try {

            $invoice = Invoice::findOrFail($id);
            $created_at = $invoice->created_at->format('Y-m-d H:i:s');
            $overaall_amount = 0;
            $overaall_qty = 0;
            $BANK = $request->has("bank") && $request->bank != "" ? $request->bank : null;
            $discount = $request->has("discount") && $request->discount != "" ? $request->discount : 0;
            $invoice->total_amount = 0;
            $invoice->total_qty = 0;
            $invoice->payment_type = $request->payment_type;
            $invoice->detail = $request->detail;
            $invoice->bank = $BANK;
            $invoice->discount = $request->has("discount") && $request->discount != "" ? $request->discount : 0;
            $invoice->save();

            //adjust stock
            foreach ($invoice->sub_invoices as $subinvoice) {
                $stock = Stock::where('product_id', $subinvoice->product_id)->first();
                $stock->qty = $stock->qty + $subinvoice->qty;
                $stock->save();

                $subinvoice->delete();
            }

            $detail = $request->has("detail") && $request->detail != "" ? $request->detail : "";
            $ledger_arr = [];

            for ($i = 0; $i < count($request['product_id']); $i++) {
                $find_subinvoice = Subinvoice::where('invoice_id', '=', $invoice->id)->where('product_id', '=', $request['product_id'][$i])->where('price', '=', $request['price_org'][$i])->get();
                if (count($find_subinvoice) > 0) {


                    $upd_subinvoice = Subinvoice::find($find_subinvoice[0]->id);
                    $upd_subinvoice->qty = $find_subinvoice[0]->qty + $request['qty'][$i];
                    $upd_subinvoice->total = $find_subinvoice[0]->total + ($request['qty'][$i] * $request['price_org'][$i]);
                    $upd_subinvoice->save();

                    $overaall_amount = $overaall_amount + (intval($request['qty'][$i]) * intval($request['price_org'][$i]));
                    $overaall_qty = $overaall_qty + intval($request['qty'][$i]);

                    $CustomerController->deductstock($request['product_id'][$i], $request['qty'][$i]);
                } else {

                    $product_name = $CustomerController->getProductNamebyID($request->product_id[$i]);
                    $ledger_arr[] = ["lproduct" => $product_name, "lqty" => $request->qty[$i], "lprice" => $request->price_org[$i], "ltotal" => $request->qty[$i] * $request->price_org[$i], "type" => 0, "created_at" => $created_at];

                    $subinvoice = new Subinvoice;
                    $subinvoice->invoice_id = $invoice->id;
                    $subinvoice->product_id = $request['product_id'][$i];
                    $subinvoice->price = $request['price_org'][$i];
                    $subinvoice->qty = $request['qty'][$i];
                    $subinvoice->stock_id = $request['price'][$i];
                    $subinvoice->purchase_price = $request['purchase_price'][$i];
                    $subinvoice->total = $request['qty'][$i] * $request['price_org'][$i];
                    $subinvoice->save();

                    $overaall_amount = $overaall_amount + (intval($request['qty'][$i]) * intval($request['price_org'][$i]));
                    $overaall_qty = $overaall_qty + intval($request['qty'][$i]);
                    //managing stock
                    $CustomerController->deductstock($request['product_id'][$i], $request['qty'][$i]);
                }
            }
            $paidamount = 0;

            //add ledger
            $walking_customer = $request->customer_name != 0 ? null : $request->c_name;
            $paidamount = 0;
            if ($request->type != 0) {
                $paidamount = $request->has('paid_amount') && $request->paid_amount != "" ? $request->paid_amount : 0;
            }
            $this->deleteOldLedger($invoice->id);
            $ldgr_id = $CustomerController->addCusomerLedger($invoice->customer_id, ($overaall_amount - $discount), $request->type, $invoice->id, $detail, $paidamount, $BANK, $request->payment_type, $created_at, $walking_customer);
            if ($ldgr_id) {
                $ldgr_id_arr = array('ledger_id' => $ldgr_id);
                $ledger_arr = array_map(function ($item) use ($ldgr_id) {
                    $item['ledger_id'] = $ldgr_id;
                    return $item;
                }, $ledger_arr);
            }

            if (count($ledger_arr) > 0) {
                LedgerDetail::insert($ledger_arr);
            }

            $this->deleteOldExpense($invoice->id);

            if ($request->type != 0) {
                $amount = $request->type == 2 ? $paidamount : ($overaall_amount - $discount);
                if ($amount > 0) {
                    $CustomerController->insertExpensedata($amount, $detail, $BANK, $request->payment_type, $created_at, $invoice->id, $request->detail_hidden, $request->type);
                }
            }

            $paidamount = $request->type == 1 ? ($overaall_amount - $discount) : $paidamount;

            $update_invoice = Invoice::find($invoice->id);
            $update_invoice->total_amount = $overaall_amount;
            $update_invoice->total_qty = $overaall_qty;
            $update_invoice->paid_amount = $paidamount;
            $update_invoice->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            dd('Something went wrong!', $th);
        }
        return redirect(route('sale_print') . "?invoice_id=" . $invoice->id . "&paidamount=" . ($paidamount));
    }

    public function deleteOldLedger($invoice_id)
    {
        //delete old data
        $toDelete = Ledger::with('ledger_details')->where('invoice_id', $invoice_id)->get();
        foreach ($toDelete as $delete) {
            foreach ($delete->ledger_details as $item) {
                $item->delete();
            }
            $delete->delete();
        }
    }

    public function deleteOldExpense($invoice_id)
    {
        //delete old data
        $toDelete = Expense::where('invoice_id', $invoice_id)->get();
        foreach ($toDelete as $delete) {
            $delete->delete();
        }
    }
}
