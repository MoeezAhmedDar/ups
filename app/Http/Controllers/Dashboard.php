<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Subcategory;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Subinvoice;
use App\Models\Ledger;
use App\Models\Expense;
use App\Models\Bank;
use App\Models\Bankbalance;

class Dashboard extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::count('id');
        $vendors = Vendor::where('type', 0)->count('id');
        $customers = Vendor::where('type', 1)->count('id');
        $products = Product::count('id');
        $company = Subcategory::count('id');
        // $invoices = Invoice::select('invoices.*','vendors.name')->leftjoin('vendors','invoices.customer_id','=','vendors.id')->orderby('id','desc')->limit(10)->get();
        //getting invoice detail
        $invoices = Subinvoice::select('subinvoices.*', 'vendors.name as cname', 'products.name as pname', 'invoices.customer_name as wname')->join('invoices', 'subinvoices.invoice_id', '=', 'invoices.id')->leftjoin('vendors', 'invoices.customer_id', '=', 'vendors.id')->join('products', 'subinvoices.product_id', '=', 'products.id')->orderby('subinvoices.id', 'asc')->limit(30)->get();
        //dd($invoices);
        //getting expense
        $ExpenseController = new ExpenseController();
        $expenses = $ExpenseController->incomeexpense(new Request(), $onlyData = true);
        //Expense::with('invoice')->select('expenses.*', 'banks.bank_name', 'vendors.name as vname', 'invoices.customer_name')->leftjoin('invoices', 'invoices.id', '=', 'expenses.invoice_id')->leftjoin('banks', 'expenses.bank', '=', 'banks.id')->leftjoin('ledgers', 'expenses.created_at', '=', 'ledgers.created_at')->leftjoin('vendors', 'ledgers.vendor_id', '=', 'vendors.id')->orderby('id', 'asc')->limit(10)->get();
        $credit = Expense::whereRaw('NOT(detail_hidden <=> "TRANSFER")')->sum('credit');
        $debit = Expense::whereRaw('NOT(detail_hidden <=> "TRANSFER")')->sum('debit');
        $total_exp = $credit - $debit;

        $debit_exp = Expense::where('type', 1)->sum('debit');
        $todaydebit_exp = Expense::where('type', 1)->whereRaw('DATE(created_at)="' . date('Y-m-d') . '"')->sum('debit');
        $subcategories = Subcategory::oldest()->get();
        $vendors = Vendor::where('type', 1)->get();
        //dd($ledgers);
        // $total_ledger = Ledger::sum('amount');
        // $total_ledger = $total_ledger-$debit_exp;
        $ledgers = Ledger::with('invoice.customer')->select('ledgers.*', 'banks.bank_name', 'vendors.name as vname')
            ->leftjoin('banks', 'ledgers.bank', '=', 'banks.id')
            ->leftjoin('vendors', 'ledgers.vendor_id', '=', 'vendors.id')
            ->orderby('ledgers.id', 'desc')->limit(10)->get();

        $total_ledger = 0;
        $lc = new LedgerController();
        foreach ($vendors as $v) {
            // $unpaid = Ledger::where('full', 0)->where('vendor_id', $v->id)->sum('amount');

            // $patial_invoices_total = 0;
            // $partial_paid_ledgers = Ledger::with('invoice')->where('full', 2)->where('vendor_id', $v->id)->get();
            // foreach ($partial_paid_ledgers as $item) {
            //     $patial_invoices_total += $item->invoice->total_amount;
            // }
            // $partial_paid = Ledger::where('full', 2)->where('vendor_id', $v->id)->sum('amount');
            // $partial_remaining = $patial_invoices_total - $partial_paid;
            // $balance =  $unpaid + $partial_remaining;

            // $total_ledger += $balance;

            $balance = $lc->index_customer(new Request(), $v->id, true);
            $total_ledger += $balance;
        }
        $total_ledger = $total_ledger > 0 ? -$total_ledger : - ($total_ledger);

        $todaysale = Invoice::whereRaw('DATE(created_at)="' . date('Y-m-d') . '"')->sum('total_amount');
        $todaysalediscount = Invoice::whereRaw('DATE(created_at)="' . date('Y-m-d') . '"')->sum('discount');
        $todaysale = $todaysale - $todaysalediscount;
        $today_ledger = Ledger::join('vendors', 'ledgers.vendor_id', '=', 'vendors.id')->where('vendors.type', 1)->whereRaw('DATE(ledgers.created_at)="' . date('Y-m-d') . '"')->where('amount', '>', 0)->sum('amount');
        $today_ledger = $today_ledger - $todaydebit_exp;
        //getting waking customer sale
        $todaywakingcustomersale = Invoice::where('customer_id', null)->whereRaw('DATE(created_at)="' . date('Y-m-d') . '"')->sum('total_amount');
        // $todaycash = $today_ledger+$todaywakingcustomersale;
        //getting bank balance
        $bankamount = Bankbalance::sum('amount');
        $bankledgeramount = Ledger::where('payment_type', '!=', 0)->sum('amount');
        $bankexpense = Expense::where('payment_type', '!=', 0)->where('status', 1)->sum('debit');
        //$banktotal = ($bankamount + $bankledgeramount) -$bankexpense; 

        // total cash 
        $totalcashcredit = Expense::where('payment_type', 0)->whereRaw('NOT(detail_hidden <=> "incentive")')->sum('credit');
        $totalcashdebit = Expense::where('payment_type', 0)->whereRaw('NOT(detail_hidden <=> "incentive")')->sum('debit');

        $debitFromBank = Expense::whereRaw('bank IS NOT NULL')->whereRaw('NOT(detail_hidden <=> "incentive")')->whereRaw('NOT(detail_hidden <=> "Expense Bank")')->whereRaw('NOT(detail_hidden <=> "Stock Added")')->sum('debit');
        $creditToBank = Expense::whereRaw('bank IS NOT NULL')->whereRaw('NOT(detail_hidden <=> "incentive")')->whereRaw('NOT(detail_hidden <=> "Item Purchased")')->sum('credit');
        $totalcash = ($totalcashcredit + $debitFromBank) - ($totalcashdebit  + $creditToBank);

        $todaycash_credit = Expense::whereRaw('DATE(created_at)="' . date('Y-m-d') . '"')->where('payment_type', 0)->whereRaw('NOT(detail_hidden <=> "incentive")')->sum('credit');
        $todaycash_debit = Expense::whereRaw('DATE(created_at)="' . date('Y-m-d') . '"')->where('payment_type', 0)->whereRaw('NOT(detail_hidden <=> "incentive")')->sum('debit');
        $creditToBanktoday = Expense::whereRaw('bank IS NOT NULL')->whereRaw('NOT(detail_hidden <=> "incentive")')->whereRaw('NOT(detail_hidden <=> "Item Purchased")')->whereRaw('DATE(created_at)="' . date('Y-m-d') . '"')->sum('credit');
        $debitFromBanktoday = Expense::whereRaw('bank IS NOT NULL')->whereRaw('NOT(detail_hidden <=> "incentive")')->whereRaw('NOT(detail_hidden <=> "Expense Bank")')->whereRaw('NOT(detail_hidden <=> "Stock Added")')->whereRaw('DATE(created_at)="' . date('Y-m-d') . '"')->sum('debit');

        $todaycash = ($todaycash_credit + $debitFromBanktoday) - ($todaycash_debit + $creditToBanktoday);


        // // $banktotal += ($b->bankBalances->sum('amount') + $credit) - $debit;
        // $banktotal += ($credit) - $debit;


        //dd($banktotal);
        // $bank = Bank::select('banks.id')->selectRaw('sum(amount) as balance')->join('bankbalances', 'banks.id', '=', 'bankbalances.bank_id')->groupby('banks.id')->get();
        $bank = Bank::with('bankBalances')->get();
        $banktotal = 0;
        $banktotaltoday = 0;
        foreach ($bank as $b) {
            $debit = Expense::where('bank', $b->id)->sum('debit');
            $credit = Expense::where('bank', $b->id)->sum('credit');

            // $banktotal += ($b->bankBalances->sum('amount') + $credit) - $debit;
            $banktotal += ($credit) - $debit;

            $bankledgeramounttoday = Expense::where('bank', $b->id)->whereRaw('DATE(created_at)="' . date('Y-m-d') . '"')->sum('credit');
            $bankexpensetoday = Expense::where('bank', $b->id)->whereRaw('DATE(created_at)="' . date('Y-m-d') . '"')->sum('debit');
            // $banktotaltoday = ($bankamount + $bankledgeramounttoday) -$bankexpensetoday; 
            $banktotaltoday += $bankledgeramounttoday - $bankexpensetoday;
        }
        // dd($expenses[0]['created_date']);
        return view('dashboard', compact('invoices', 'subcategories', 'users', 'vendors', 'customers', 'products', 'company', 'ledgers', 'total_ledger', 'todaysale', 'todaycash', 'expenses', 'total_exp', 'banktotal', 'banktotaltoday', 'totalcash'));
    }
    public function users()
    {
        $users = User::oldest()->get();

        $pc = new ProfitController();
        $total_profit = $pc->index(new Request(), true);

        return view('users', compact('users', 'total_profit'));
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
}
