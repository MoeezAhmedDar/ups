<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Bank;
use App\Models\Ledger;
use App\Models\Vendor;
use App\Models\Expense;
use App\Models\Bankbalance;
use App\Models\LedgerDetail;
use App\Models\Subinvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        if ($request->has('pdf')) {
            $ledgers = Ledger::with(['invoice', 'bank', 'vendor'])->orderby('ledgers.id', 'desc');
            $vendor_name = "All Ledger";
            if ($id > 0) {
                $ledgers = $ledgers->where('vendor_id', '=', $id);
                $vendor_name = Vendor::select('name')->where('id', $id)->get();
                $vendor_name = $vendor_name[0]->name;
            }

            $from = '';
            $to = '';
            if ($request->has('from') && $request->from != "" && $request->has('to') && $request->to != "") {
                $from = $request->from;
                $to = $request->to;
                $ledgers = $ledgers->whereRaw('DATE(created_at) >="' . $request->from . '"')->whereRaw('DATE(created_at) <="' . $request->to . '"');
            } else if ($request->has('from') && $request->from != "") {
                $from = $request->from;
                $ledgers = $ledgers->whereRaw('DATE(created_at)="' . $request->from . '"');
            }
            $ledgers = $ledgers->get();
            //getting total amount sum
            $total_amount = Ledger::where('vendor_id', '=', $id)->where('full', 0)->sum('amount');

            $data = [
                'title' => 'Superups-quotation-', date('m/d/Y'),
                'date' => date('m/d/Y'),
                'ledgers' => $ledgers,
                'id' => $id,
                'from' => $from,
                'to' => $to,
                'total_amount' => $total_amount
            ];

            $pdf = PDF::loadView('vendor.pdf', $data);

            return $pdf->download('Superups-Ledger-' . date('m/d/Y h:i:a') . '.pdf');
        } else {
            $from = $request->has('from') && $request->from != "" ? $request->from : "";
            $to = $request->has('to') && $request->to != "" ? $request->to : "";
            $check = false;
            // return view('vendor.pdf_view',compact('id','from','to'));
            $data = [];
            $vendor_name = null;
            $vendorObj = null;
            if ($request->filled('vendor_id')) {
                $vendorObj = Vendor::find($request->vendor_id);
                $vendor_name = $vendorObj->name;
            } elseif ($id > 0) {

                $vendorObj = Vendor::find($id);
                if ($vendorObj) {
                    $vendor_name = $vendorObj->name;
                }
            }
            $ledgers = Ledger::with('invoice.customer')->select('ledgers.*', 'banks.bank_name', 'vendors.name as vname')
                ->leftjoin('banks', 'ledgers.bank', '=', 'banks.id')
                ->leftjoin('vendors', 'ledgers.vendor_id', '=', 'vendors.id');
            // dd($id,$request->all());
            if ($request->filled('vendor_id')) {
                $ledgers = $ledgers->where('vendor_id', $request->vendor_id);
            } elseif (isset($id) && $id > 0) {
                $ledgers = $ledgers->where('vendor_id', $request->id);
            }
            if ($request->has('from') && $request->from != "" && $request->has('to') && $request->to != "") {
                $ledgers = $ledgers->whereRaw('DATE(ledgers.created_at)>="' . $request->from . '"')->whereRaw('DATE(ledgers.created_at)<="' . $request->to . '"');
                $check = true;
            } else if ($request->has('from') && $request->from != "") {


                $from = $request->has('from') && $request->from != "" ? $request->from : date('Y-m-d');
                $ledgers = $ledgers->whereRaw('DATE(ledgers.created_at)="' . $from . '"');
                $check = true;
            }
            $ledgers = $ledgers->orderby('ledgers.id', 'asc');
            // if ($check == false) {
            //     $ledgers = $ledgers->limit(12);
            // }
            $ledgers = $ledgers->get();
            // $ledgers = $ledgers->orderby('ledgers.id','desc')->get();
            //dd($ledgers);
            $TOTAL_AMOUNT = 0;
            foreach ($ledgers as $ledger) {
                $ledger_detail = LedgerDetail::where('created_at', $ledger->created_at)->get();
                $AMOUNT = $ledger->amount;

                $TOTAL_AMOUNT += $AMOUNT;
                $data[] = [
                    "ledger_id" => $ledger->id,
                    "created_at" => $ledger->created_at,
                    "created_date" => date("d-m-Y h:i:s a", strtotime($ledger->created_at)),
                    "payment_type" => $ledger->payment_type,
                    "bank" => $ledger->bank_name ?? $ledger->vname,
                    "detail" => $ledger->details,
                    "amount" => $ledger->amount,
                    "ledger_detail" => $ledger_detail,

                    "full" => $ledger->full,
                    "vname" => $ledger->vendor->name ?? $ledger->invoice->customer->name ?? $ledger->invoice->customer_name ?? $ledger->walking_customer,
                    "return" => $ledger->return,
                    "invoice" => $ledger->invoice,
                    "vendor" => $ledger->vendor,
                    "total_amount" => $TOTAL_AMOUNT,
                ];
            }
            $data_arr_new = [];
            for ($i = count($data) - 1; $i >= 0; $i--) {
                $data_arr_new[] = $data[$i];
            }
            // dd($data_arr_new);
            //dd(count($data_arr[1]["ledger_detail"]));
            $total_amount = Ledger::where('vendor_id', '=', $id)->where('full', 0)->sum('amount');
            $dataA = [
                'title' => 'Superups-quotation-', date('m/d/Y'),
                'date' => date('m/d/Y'),
                'data' => $data_arr_new,
                'id' => $id,
                'from' => $from,
                'to' => $to,
                'vendor_name' => $vendor_name,
                'total_amount' => $total_amount
            ];
        }

        $vendors = Vendor::all();
        // dd($data, $id, $from, $to, $total_amount, $vendor_name);
        $index_customer_flag = false;

        return view('vendor.view_ledger', compact('index_customer_flag', 'data', 'dataA', 'id', 'from', 'to', 'total_amount', 'vendorObj', 'vendor_name', 'vendors'));
    }
    public function index_customer(Request $request, $id, $totalonly = false, $dataOnly = false)
    {
        $from = $request->has('from') && $request->from != "" ? $request->from : "";
        $to = $request->has('to') && $request->to != "" ? $request->to : "";
        $check = false;

        $data = [];
        $vendor_name = null;
        $vendorObj = null;
        if ($request->filled('vendor_id')) {
            $vendorObj = Vendor::find($request->vendor_id);
            $vendor_name = $vendorObj->name;
        } elseif ($id > 0) {

            $vendorObj = Vendor::find($id);
            if ($vendorObj) {
                $vendor_name = $vendorObj->name;
            }
        }
        $ledgers = Ledger::with(['invoice.customer', 'bank', 'vendor']);
        // dd($id,$request->all());
        if ($request->filled('vendor_id')) {
            $ledgers = $ledgers->where('vendor_id', $request->vendor_id)->orWhereHas('invoice', function ($q) use ($request) {
                $q->where('customer_id', $request->vendor_id);
            });
        } elseif (isset($id) && $id > 0) {
            $ledgers = $ledgers->where('vendor_id', $id)->orWhereHas('invoice', function ($q) use ($request) {
                $q->where('customer_id', $request->id);
            });
        }

        //commenting date filter, because need to calculate total balance before this date
        // if ($request->has('from') && $request->from != "" && $request->has('to') && $request->to != "") {
        //     $ledgers = $ledgers->whereRaw('DATE(ledgers.created_at)>="' . $request->from . '"')->whereRaw('DATE(ledgers.created_at)<="' . $request->to . '"');
        //     $check = true;
        // } else if ($request->has('from') && $request->from != "") {


        //     $from = $request->has('from') && $request->from != "" ? $request->from : date('Y-m-d');
        //     $ledgers = $ledgers->whereRaw('DATE(ledgers.created_at)="' . $from . '"');
        //     $check = true;
        // }
        $ledgers = $ledgers->orderby('ledgers.id', 'asc');
        // if ($check == false) {
        //     $ledgers = $ledgers->limit(12);
        // }
        $ledgers = $ledgers->get();
        $TOTAL_AMOUNT = 0;
        foreach ($ledgers as $ledger) {
            $ledger_detail = LedgerDetail::where('created_at', $ledger->created_at)->get();
            $AMOUNT = $ledger->amount;
            if ($ledger->return == 1) {
                // return entry 
                $TOTAL_AMOUNT += ($ledger_detail->sum('ltotal') + $ledger->amount); // $ledger->amount is negative so plus would be changed to minus auto
                if ($ledger->invoice->total_amount == 0 || $ledger->invoice->total_amount >  $ledger->invoice->paid_amount - $ledger->invoice->discount) {
                    // $TOTAL_AMOUNT += 0;
                } else {
                }
            } else {
                if ($ledger->type == '1') { // add stock
                    if ($ledger->full == '2') {
                        if ($ledger_detail->count()) {
                            $rem = $ledger_detail->sum('ltotal');
                            if ($AMOUNT > 0) {
                                $rem -= $AMOUNT;
                            }

                            $TOTAL_AMOUNT += $AMOUNT;
                        } else {
                            $TOTAL_AMOUNT += $AMOUNT;
                        }
                    } elseif ($ledger->full == 0) {
                        $TOTAL_AMOUNT += $AMOUNT;
                    }
                } elseif ($ledger_detail->count() && $ledger->full != 2) {
                    $rem = $ledger_detail->sum('ltotal') - ($ledger->invoice->discount ?? 0);

                    if ($AMOUNT > 0) {
                        $rem -= $AMOUNT;
                    }

                    $TOTAL_AMOUNT -= $rem;
                } else {
                    $TOTAL_AMOUNT += $AMOUNT;
                }
            }

            $color = 'black';
            if ($ledger->return == 1) {
                $color = 'red';
            } elseif ($ledger->full == 1) {
                $color = 'black';
            } elseif ($ledger->full == 2) {
                $color = 'blue';
            } elseif ($ledger->full == 0) {
                $color = 'green';
            }

            $data[] = [
                "ledger_id" => $ledger->id,
                "created_at" => $ledger->created_at,
                "created_date" => date("d-m-Y h:i:s a", strtotime($ledger->created_at)),
                "payment_type" => $ledger->payment_type,
                "bank" => $ledger->bank->bank_name ?? $ledger->vendor->name ?? '',
                "detail" => $ledger->details,
                "amount" => $ledger->amount,
                "ledger_detail" => $ledger_detail,

                "full" => $ledger->full,
                "vname" => $ledger->vendor->name ?? $ledger->invoice->customer->name ?? $ledger->invoice->customer_name ?? $ledger->walking_customer,
                "return" => $ledger->return,
                "invoice" => $ledger->invoice,
                "vendor" => $ledger->vendor,
                "total_amount" => $TOTAL_AMOUNT,
                'color' => $color
            ];
        }

        if ($totalonly) return $TOTAL_AMOUNT;
        if ($dataOnly) return $data;

        $data_arr_new = [];
        for ($i = count($data) - 1; $i >= 0; $i--) {
            $data_arr_new[] = $data[$i];
        }

        $total_amount = Ledger::where('vendor_id', '=', $id)->where('full', 0)->sum('amount');
        $dataA = [
            'title' => 'Superups-quotation-', date('m/d/Y'),
            'date' => date('m/d/Y'),
            'data' => $data_arr_new,
            'id' => $id,
            'from' => $from,
            'to' => $to,
            'vendor_name' => $vendor_name,
            'total_amount' => $total_amount
        ];

        $vendors = Vendor::all();

        $index_customer_flag = true;

        return view('vendor.view_ledger', compact('index_customer_flag', 'data', 'dataA', 'id', 'from', 'to', 'total_amount', 'vendorObj', 'vendor_name', 'vendors'));
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

        $request->validate(
            [
                'amount' => 'required',
                'type' => 'required',
                'description' => 'required',
                'vendor_id' => 'required',
                // 'bank' => 'required_if:payment_type,!=,0',
            ]
        );
        $ledger = new Ledger;
        if ($request['type'] == 1) {
            $ledger->amount = $request['amount'];
        } else {
            $ledger->amount = -1 * $request['amount'];
        }
        $ledger->details = $request['description'];
        $ledger->vendor_id = $request['vendor_id'];
        $ledger->payment_type = $request['payment_type'];
        $ledger->bank = $request['bank'];
        $ledger->status = 1;
        $ledger->full = 4; // vendor add cash 
        $ledger->save();

        $detail_hidden = null;
        if ($request['payment_type'] != 0) {
            $detail_hidden =  'Expense Bank';
            if ($request['type'] == 1) {
                $detail_hidden = 'Item Purchased';
            }
        }
        if ($request['type'] == 1) {
            // $expdata = ["credit" => $request["amount"],"detail" => $request["description"],"created_at" => date('Y-m-d H:i:s')];
            $expdata = ['bank_balance' => 1, 'type' => '4', 'bank' => $request['bank'], 'detail_hidden' => $detail_hidden, 'payment_type' => $request['payment_type'], "credit" => $request["amount"], "detail" => $request["description"], "created_at" => date('Y-m-d H:i:s')];
        } else {
            // $expdata = ["debit" => $request["amount"],"detail" => $request["description"],"created_at" => date('Y-m-d H:i:s')];
            $expdata = ['bank_balance' => 1, 'type' => '4', 'bank' => $request['bank'], 'detail_hidden' => $detail_hidden, 'payment_type' => $request['payment_type'], "debit" => $request["amount"], "detail" => $request["description"], "created_at" => date('Y-m-d H:i:s')];
        }
        Expense::insert($expdata);

        if ($request->payment_type != 0) {
            $add = new Bankbalance;
            $add->bank_id = $request->bank;
            $add->amount = $request->type != '1' ? '-'  . $request->amount : $request->amount;
            $add->detail = $request->description;
            $add->created_at = date('Y-m-d H:i:s');
            $add->save();
        }

        if ($request->check == "1") {
            return redirect(url('vendor?c'));
        }
        return redirect()->route('vendor.index')->with('success', 'Vendor Added');
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
    }
    //getting ladger
    public function getLedgerdata(Request $request)
    {
        $data_arr = [];
        $check = false;
        $ledgers = Ledger::with(['invoice', 'bank', 'vendor']);
        $total_amount = Ledger::where('full', 0);
        if ($request->has('vendor') && $request->vendor > 0) {
            $ledgers = $ledgers->where('vendor_id', $request->vendor);
            $total_amount = $total_amount->where('vendor_id', '=', $request->vendor);
        }
        $total_amount = $total_amount->sum('amount');
        if ($request->has('from') && $request->from != "" && $request->has('to') && $request->to != "") {
            $ledgers = $ledgers->whereRaw('DATE(ledgers.created_at)>="' . $request->from . '"')->whereRaw('DATE(ledgers.created_at)<="' . $request->to . '"');
            $check = true;
        } else if ($request->has('from') && $request->from != "") {


            $from = $request->has('from') && $request->from != "" ? $request->from : date('Y-m-d');
            // else if($request->has('from') && $request->from != "")
            // {
            $ledgers = $ledgers->whereRaw('DATE(ledgers.created_at)="' . $from . '"');
            // }
            $check = true;
        }
        $ledgers = $ledgers->orderby('ledgers.id', 'desc');
        // if ($check == false) {
        //     $ledgers = $ledgers->limit(12);
        // }
        $ledgers = $ledgers->get();

        foreach ($ledgers as $ledger) {
            $ledger_detail = LedgerDetail::where('created_at', $ledger->created_at)->get();
            $data_arr[] = [
                "ledger_id" => $ledger->id,
                "created_at" => $ledger->created_at,
                "created_date" => date("d-m-Y h:i:a", strtotime($ledger->created_at)),
                "payment_type" => $ledger->payment_type,
                "bank" => $ledger->bank->name ?? '-',
                "detail" => $ledger->details,
                "amount" => $ledger->amount,
                "full" => $ledger->full,
                "ledger_detail" => $ledger_detail,
                "vname" => $ledger->vendor->name ?? $ledger->invoice->customer_name ?? $ledger->walking_customer,
                "return" => $ledger->return,
                "invoice" => $ledger->invoice,
                "vendor" => $ledger->vendor,
                "bank" => $ledger->bank,
            ];
        }

        return response()->json([
            'data' => $data_arr,
            'total_amount' => $total_amount,
            'id' => $request->vendor
        ]);
    }
    //pdf view
    public function gnerated_pdf_view(Request $request)
    {
        $id = $request->id;
        $from = $request->has('from') && $request->from != "" ? $request->from : "";
        $to = $request->has('to') && $request->to != "" ? $request->to : "";
        $check = false;
        // return view('vendor.pdf_view',compact('id','from','to'));
        $data = [];
        $vendor_name = null;
        $vendorObj = null;
        if ($request->filled('vendor_id')) {
            $vendorObj = Vendor::find($request->vendor_id);
            $vendor_name = $vendorObj->name;
        } elseif ($id > 0) {

            $vendorObj = Vendor::find($id);
            if ($vendorObj) {
                $vendor_name = $vendorObj->name;
            }
        }
        $ledgers = Ledger::select('ledgers.*', 'banks.bank_name', 'vendors.name as vname')->leftjoin('banks', 'ledgers.bank', '=', 'banks.id')->leftjoin('vendors', 'ledgers.vendor_id', '=', 'vendors.id');
        // dd($id,$request->all());
        if ($request->filled('vendor_id')) {
            $ledgers = $ledgers->where('vendor_id', $request->vendor_id);
        } elseif (isset($id) && $id > 0) {
            $ledgers = $ledgers->where('vendor_id', $request->id);
        }
        if ($request->has('from') && $request->from != "" && $request->has('to') && $request->to != "") {
            $ledgers = $ledgers->whereRaw('DATE(ledgers.created_at)>="' . $request->from . '"')->whereRaw('DATE(ledgers.created_at)<="' . $request->to . '"');
            $check = true;
        } else if ($request->has('from') && $request->from != "") {


            $from = $request->has('from') && $request->from != "" ? $request->from : date('Y-m-d');
            $ledgers = $ledgers->whereRaw('DATE(ledgers.created_at)="' . $from . '"');
            $check = true;
        }
        $ledgers = $ledgers->orderby('ledgers.id', 'asc');
        // if ($check == false) {
        //     $ledgers = $ledgers->limit(12);
        // }
        $ledgers = $ledgers->get();
        // $ledgers = $ledgers->orderby('ledgers.id','desc')->get();
        //dd($ledgers);
        $TOTAL_AMOUNT = 0;
        foreach ($ledgers as $ledger) {
            $ledger_detail = LedgerDetail::where('created_at', $ledger->created_at)->get();
            $AMOUNT = $ledger->amount;

            $TOTAL_AMOUNT += $AMOUNT;
            $data[] = [
                "ledger_id" => $ledger->id,
                "created_at" => $ledger->created_at,
                "created_date" => date("d-m-Y h:i:s a", strtotime($ledger->created_at)),
                "payment_type" => $ledger->payment_type,
                "bank" => $ledger->bank_name ?? $ledger->vname,
                "detail" => $ledger->details,
                "amount" => $ledger->amount,
                "ledger_detail" => $ledger_detail,

                "full" => $ledger->full,
                "vname" => $ledger->vendor->name ?? $ledger->invoice->customer_name ?? $ledger->walking_customer,
                "return" => $ledger->return,
                "invoice" => $ledger->invoice,
                "vendor" => $ledger->vendor,
                "total_amount" => $TOTAL_AMOUNT,
            ];
        }
        //dd(count($data_arr[1]["ledger_detail"]));
        // $total_amount = Ledger::where('vendor_id', '=', $id)->where('full', 0)->sum('amount');
        $dataA = [
            'title' => 'Superups-quotation-', date('m/d/Y'),
            'date' => date('m/d/Y'),
            'data' => $data,
            'id' => $id,
            'from' => $from,
            'to' => $to,
            'vendor_name' => $vendor_name,
        ];
        return view('vendor.pdf', $dataA);
        $pdf = PDF::loadView('vendor.pdf', $data);
        $customPaper = array(0, 0, 700, 700);
        $pdf->set_paper($customPaper);
        return $pdf->download('Superups-Ledger-' . date('m/d/Y h:i:a') . '.pdf');
    }

    public function gneratedpdfview_customer(Request $request)
    {
        $id = $request->id;
        $data = $this->index_customer($request, $id, false, true);
        $vendor_name = $data[0]['vname'] ?? null;
        return view('vendor.pdf_customer', [
            'data' => $data,
            'id' => $id,
            'vendor_name' => $vendor_name,
            'from' => $request->from,
            'to' => $request->to
        ]);
    }
    //add incentive
    public function add_incentive(Request $request)
    {

        $request->validate(
            [
                'amount' => 'incentiveamount',
                'detail' => 'required',
            ]
        );

        $now = date('Y-m-d H:i:s');
        $ledger = new Ledger;
        $ledger->details = $request['detail'];
        $ledger->vendor_id = $request['vendor_id'];
        $ledger->amount = -$request['incentiveamount'];
        // $ledger->payment_type=$request['payment_type'];
        // $ledger->bank=$request['bank'];
        $ledger->incentive = 1;
        $ledger->status = 1;
        $ledger->created_at = $now;
        $ledger->save();

        $expdata = ['type' => 6, "credit" => $request["incentiveamount"], "detail_hidden" => 'incentive', "detail" => $request["detail"], "created_at" => $now];
        $subinvdata = ["invoice_id" => 0, "product_id" => 0, "stock_id" => 0, 'qty' => '1', 'price' => $request["incentiveamount"], 'total' => $request["incentiveamount"], 'purchase_price' => '0', "created_at" => $now];

        Expense::insert($expdata);
        Subinvoice::insert($subinvdata);
        return redirect()->route('vendor.index')->with('success', 'Vendor Added');
    }
}
