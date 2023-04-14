<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Ledger;
use App\Models\Bank;
use Illuminate\Http\Request;
use PDF;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = 0;
        if ($request->has('c')) {
            $type = 1;
        }
        $banks = Bank::oldest()->get();
        // $vendors = Vendor::select('vendors.*')->selectRaw('sum(ledgers.amount) as balance')->leftjoin('ledgers','vendors.id','=','ledgers.vendor_id')->where('type',$type)->groupby('ledgers.vendor_id');
        $vendors = Vendor::where('type', $type)->get();
        if ($request->has('pdf')) {

            $data_arr = [];
            foreach ($vendors as $v) {
                $balance = Ledger::where('vendor_id', $v->id)->sum('amount');
                $data_arr[] = ["id" => $v->id, "name" => $v->name, "phone" => $v->phone, "address" => $v->address, "balance" => $balance];
            }

            $data = [
                'title' => 'Superups-quotation-', date('m/d/Y'),
                'date' => date('m/d/Y'),
                'vendors' => $vendors,
                'type' => $type,
                'data_arr' => $data_arr
            ];

            $pdf = PDF::loadView('vendor.vendordetail', $data);

            return $pdf->download('Superups-Clients-' . date('m/d/Y h:i:a') . '.pdf');
        }
        $data_arr = [];
        $lc = new LedgerController();
        foreach ($vendors as $v) {
            // $unpaid = Ledger::where('full', 0)->where('vendor_id', $v->id)->sum('amount');

            // $patial_invoices_total = 0;
            // $partial_paid_ledgers = Ledger::with('invoice')->where('full', 2)->where('vendor_id', $v->id)->get();
            // foreach ($partial_paid_ledgers as $item) {
            //     $patial_invoices_total += $item->invoice->total_amount;
            // }
            // $partial_paid = Ledger::where('full', 2)->where('vendor_id', $v->id)->sum('amount');
            // $fully_paid = Ledger::where('full', 1)->where('vendor_id', $v->id)->sum('amount');
            // $partial_remaining = $patial_invoices_total - $partial_paid;
            // $balance = ($unpaid + $partial_remaining) - $fully_paid;
            // //$balance = Ledger::where('vendor_id', $v->id)->sum('amount');

            $balance = $lc->index_customer(new Request(), $v->id, true);
            $data_arr[] = ["id" => $v->id, "name" => $v->name, "phone" => $v->phone, "address" => $v->address, "balance" => $balance];
        }
        // dd($data_arr[0]["name"]);
        $view_ledger_route = /*$type == 1 ?*/ 'view_ledger_customer'; // : 'view_ledger';
        return view('vendor.index', compact('vendors', 'type', 'banks', 'data_arr', 'view_ledger_route'));
    }


    public function insert_ledger(Request $request)
    {

        $request->validate(
            [
                'amount' => 'required|exists:products,id',
                'type' => 'required',
                'description' => 'required',
            ]
        );
        $ledger = new Ledger;
        $ledger->amount = $request['amount'];
        $ledger->details = $request['descrpition'];
        $ledger->vendor_id = $request['vendor_id'];
        // $ledger->payment_type=$request['payment_type'];
        // $ledger->bank=$request['bank'];

        return redirect()->route('vendor.index')->with('success', 'Vendor Added');
    }


    public function view_ledger($id)
    {
        $ledgers = Ledger::where('vendor_id', '=', $id)->get();

        return view('vendor.view_ledger', compact('ledgers'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vendor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Vendor::create(
            $request->validate([
                'name' => 'required|string',
                'phone' => 'nullable|string',
                'address' => 'nullable|string',
                'type' => 'required'
            ])
        );
        if ($request->type == 1) {
            return redirect(url('vendor?c'));
        }
        return redirect()->route('vendor.index')->with('success', 'Vendor Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        return view('vendor.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
        $vendor->update(
            $request->validate([
                'name' => 'required|string',
                'phone' => 'nullable',
                'address' => 'nullable|string',
            ])
        );

        return redirect()->route('vendor.index')->with('success', 'Vendor Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        try {

            $ledger = Ledger::where('vendor_id', $vendor->id)->count();
            if ($ledger > 0) {
                return redirect('dashboard')->with('error', 'Vendor/Customer data exist!');
            }
            $vendor->delete();

            return redirect('dashboard')->with('success', 'Deleted Successfully');
        } catch (\Exception $e) {
            // dd($e);
            return redirect('dashboard')->with('error', 'Vendor/Customer data exist!');
        }
    }
}
