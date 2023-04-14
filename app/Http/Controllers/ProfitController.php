<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Expense;
use App\Models\Subinvoice;
use App\Models\Invoice;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ProfitController extends Controller
{
    public function index(Request $request, $totalonly = false, $get_arr_only = false)
    {
        $expense = Expense::where('detail_hidden', 'Expense')->get();

        $subinvoices = Subinvoice::with(['ledger', 'ledger.vendor', 'product', 'invoice'])->orderby('id', 'asc')->get();

        $subinvoices = $subinvoices->merge($expense);
        $subinvoices = $subinvoices->sortBy('created_at');
        $arr = [];
        $total = 0;
        foreach ($subinvoices as $item) {
            if ($item->getTable() === 'subinvoices') {
                $total += ($item->price - $item->purchase_price) * $item->qty;

                if (isset($item->invoice) && is_null($item->invoice->customer_id)) {
                    $name = 'Walking Customer';
                } else {
                    $id = $item->invoice->customer_id ?? 0;
                    $name = Vendor::where('id', $id)->value('name') ?? $item->ledger->vendor->name ?? 'N/A';
                }
 				$invoice = Invoice::where('id', $item->id)->select('discount')->first();
              
                $arr[] = [
                    'date' => date('d-m-Y h:i:s a', strtotime($item->created_at)),
                    'name' => $name,
                    'product' => $item->product->name ?? 'Incentive',
                    'purchase_price' => $item->purchase_price,
                    'price' => $item->price,
                    'qty' => $item->qty,
                    'amount' => $item->qty * $item->price,
                    'profit' => (($item->price - $item->purchase_price) * $item->qty) - ($invoice ? $invoice['discount']:0),
                    'total' => $total
                ];
            } else {
                $total -= ($item->debit);

                if (isset($item->invoice) && is_null($item->invoice->customer_id)) {
                    $name = 'Walking Customer';
                } else {
                    $id = $item->invoice->customer_id ?? 0;
                    $name = Vendor::where('id', $id)->value('name') ?? $item->ledger->vendor->name ?? 'EXPENSE';
                }

                $arr[] = [
                    'date' => date('d-m-Y h:i:s a', strtotime($item->created_at)),
                    'name' => $name,
                    'product' => $item->detail,
                    'purchase_price' => 'N/A',
                    'price' => 'N/A',
                    'qty' => 'N/A',
                    'amount' => -$item->debit,
                    'profit' =>  -$item->debit,
                    'total' => $total
                ];
            }
        }
		$invoice = Invoice::sum('discount');
        if ($totalonly) {
            return $total-$invoice;
        }

        if ($get_arr_only) {
            return $arr;
        }

        $arr_new = [];
        for ($i = count($arr) - 1; $i >= 0; $i--) {
            $arr_new[] = $arr[$i];
        }

        return view('profit.index', compact('arr_new'));
    }

    public function pdf(Request $request)
    {
        $arr = $this->index($request, false, true);

        return view('profit.pdf', compact('arr'));
    }
}
