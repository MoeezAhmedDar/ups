<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Bank;
use App\Models\LedgerDetail;
use PDF;
use App\Models\Ledger;
use App\Models\Vendor;
use phpDocumentor\Reflection\Types\Boolean;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $from = $request->has('from') && $request->from != "" ? $request->from : date('Y-m-d');

        $expenses = Expense::whereRaw('NOT(detail_hidden <=> "Stock Added")')
            ->select('expenses.*', 'banks.bank_name')
            ->leftjoin('banks', 'expenses.bank', '=', 'banks.id')
            ->where('type', 1)->where('debit', '!=', 0)
            ->orderBy('id', 'desc');

        $exp_total = Expense::where('type', 1);

        if ($request->has('from') && $request->from != "" && $request->has('to') && $request->to != "") {
            $expenses = $expenses->whereRaw('DATE(expenses.created_at)>="' . $request->from . '"')->whereRaw('DATE(expenses.created_at)<="' . $request->to . '"');
            //exp_ttotal
            $exp_total = $exp_total->whereRaw('DATE(expenses.created_at)>="' . $request->from . '"')->whereRaw('DATE(expenses.created_at)<="' . $request->to . '"');
        } else {

            $expenses = $expenses->whereRaw('DATE(expenses.created_at)="' . $from . '"');
            $exp_total = $exp_total->whereRaw('DATE(expenses.created_at)="' . $from . '"');
        }

        $expenses = $expenses->get();
        $exp_total = $exp_total->sum('debit');

        if ($request->has('pdf')) {

            $data = [
                'title' => 'Superups-quotation-', date('m/d/Y'),
                'date' => date('m/d/Y'),
                'expenses' => $expenses,
                'from' => $from,
                'to' => $request->to,
                'exp_total' => $exp_total
            ];

            $pdf = PDF::loadView('expense.expense_pdf', $data);

            return $pdf->download('Superups-Expense-' . date('m/d/Y h:i:a') . '.pdf');
        }
        // dd($exp_total);
        $banks = Bank::oldest()->get();
        // dd($exp_total);
        return view('expense.index', compact('expenses', 'exp_total', 'banks'));
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
                'amount' => 'required',
                'detail' => 'required',
            ]
        );
        $exp = new Expense;
        $exp->debit = $request->amount;
        $exp->detail = $request->detail;
        $exp->detail_hidden = $request->detail_hidden;
        $exp->type = 1;
        $exp->payment_type = $request->payment_type;
        $exp->bank = $request->has('bank') && $request->bank != "" ? $request->bank : null;
        $exp->status = 1;
        $exp->created_at = date('Y-m-d H:i:s');
        $exp->save();

        $ledger = new Ledger;
        $ledger->amount = -1 * $request['amount'];
        $ledger->details = $request['detail'];
        $ledger->payment_type = $request['payment_type'];
        $ledger->bank = $request['bank'];
        $ledger->status = 1;
        $ledger->walking_customer = 'EXPENSE';
        $ledger->save();

        return redirect(route('expense.index'));
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
    //getting banks Income and expense
    public function bankdetails(Request $request)
    {

        $expenses = Expense::with('invoice')->select('expenses.*', 'banks.bank_name')->join('banks', 'expenses.bank', '=', 'banks.id');
        if ($request->has('bank') && $request->bank != "") {
            $expenses = $expenses->where('bank', $request->bank);
        }
        if ($request->has('date') && $request->date != "") {
            $expenses = $expenses->whereRaw('DATE(expenses.created_at)="' . $request->date . '"');
        }
        $expenses = $expenses->orderby('id', 'desc')->get();
        $credit = Expense::where('id', '>', 0);
        if ($request->has('bank') && $request->bank != "") {
            $credit = $credit->where('bank', $request->bank);
        } else {
            $credit = $credit->where('payment_type', '>', 0);
        }
        $debit = Expense::where('id', '>', 0);
        if ($request->has('date') && $request->date != "") {
            $credit = $credit->whereRaw('DATE(created_at)="' . $request->date . '"');
            $debit = $debit->whereRaw('DATE(created_at)="' . $request->date . '"');
        }
        $credit = $credit->sum('credit');


        if ($request->has('bank') && $request->bank != "") {
            $debit = $debit->where('bank', $request->bank);
        } else {
            $debit = $debit->where('payment_type', '>', 0);
        }
        $debit = $debit->sum('debit');

        $total_exp = $credit - $debit;
        $banks = Bank::oldest()->get();
        $date = $request->has('date') && $request->date != "" ? $request->date : "";
        return view('expense.bank_detail', compact('expenses', 'total_exp', 'banks', 'date'));
    }
    public function cashdetail(Request $request)
    {
        $expenses = Expense::with(['ledger.invoice.customer', 'bank_relation'])->whereRaw('NOT(detail_hidden <=> "incentive")')->where(function ($q) {
            $q->where(function ($q) {
                $q->where('payment_type', 0)->whereRaw('bank IS NULL');
            })->orWhere(function ($q) {
                $q->where('payment_type', 2)->where('detail_hidden', '=', 'TRANSFER');
            });
        });

        if ($request->has('date') && $request->date != "") {
            $expenses = $expenses->whereRaw('DATE(created_at)="' . date('Y-m-d') . '"');
        }

        $expenses = $expenses->orderby('id', 'desc')->get();

        $credit = $expenses->where('payment_type', 0)->whereNull('bank')->sum('credit');
        $credit += $expenses->where('payment_type', 2)->where('detail_hidden', '=', 'TRANSFER')->sum('debit');

        $debit = $expenses->where('payment_type', 0)->whereNull('bank')->sum('debit');
        $debit += $expenses->where('payment_type', 2)->where('detail_hidden', '=', 'TRANSFER')->sum('credit');

        $total = $credit - $debit;
        return view('expense.cash_detail', compact('expenses', 'total'));
    }
    public function incomeexpense(Request $request, $onlyData = false, $pdf = false)
    {
        $data_arr = [];
        $check = false;
        $from = $request->has('from') && $request->from != "" ? $request->from : date('Y-m-d');
        $to = $request->has('to') && $request->to != "" ? $request->to : "";
        $expenses = Expense::with('invoice.customer')->select('expenses.*', 'banks.bank_name', 'vendors.name as vname')->leftjoin('banks', 'expenses.bank', '=', 'banks.id')->leftjoin('ledgers', 'expenses.created_at', '=', 'ledgers.created_at')->leftjoin('vendors', 'ledgers.vendor_id', '=', 'vendors.id');
        // if ($request->has('from') && $request->from != "" && $request->has('to') && $request->to != "") {
        //     $expenses = $expenses->whereRaw('DATE(expenses.created_at)>="' . $request->from . '"')->whereRaw('DATE(expenses.created_at)<="' . $request->to . '"');
        //     $check = true;
        // } else if ($request->has('from') && $request->from != "") {
        //     $expenses = $expenses->whereRaw('DATE(expenses.created_at)="' . $from . '"');
        //     $check = true;
        // }
        $expenses = $expenses->orderby('expenses.id', 'asc');
        // if ($check == false) {
        //     $expenses = $expenses->limit(12);
        // }

        if ($request->filled('vendor_id')) {
            $expenses = $expenses->where('vendors.id', $request->vendor_id);
        }

        $expenses = $expenses->get()->unique('id');
        $credit = Expense::whereRaw('NOT(detail_hidden <=> "TRANSFER")')->sum('credit');
        $debit = Expense::whereRaw('NOT(detail_hidden <=> "TRANSFER")')->sum('debit');
        $total_exp = $credit - $debit;

        $total_expense = 0;

        foreach ($expenses as $expense) {
            if ($expense->detail_hidden != 'incentive') {
                if ($expense->detail_hidden != 'TRANSFER') {
                    $total_expense =  $expense->credit > 0 ? (intval($total_expense) + intval($expense->credit)) : (intval($total_expense) - intval($expense->debit));
                }
            }

            $ledger_detail = LedgerDetail::where('created_at', $expense->created_at)->get();
            $data_arr[] = [
                "expense_id" => $expense->id,
                "created_at" => $expense->created_at,
                "created_date" => date("d-m-Y h:i:a", strtotime($expense->created_at)),
                "payment_type" => $expense->payment_type,
                "bank" => $expense->bank,
                "detail" => $expense->detail,
                "detail_hidden" => $expense->detail_hidden,
                "credit" => $expense->credit,
                "debit" => $expense->debit,
                "ledger_detail" => $ledger_detail,
                "vname" => ($expense->vname ?? $expense->invoice->customer->name ?? $expense->invoice->customer_name ?? ($expense->detail_hidden == 'Expense Bank' || $expense->detail_hidden == 'Expense' ? 'EXPENSE' : $expense->detail_hidden
                )),
                "invoice" => $expense->invoice,
                'total_expense' => $total_expense,
                'type' => $expense->getType(),
                'color' => $expense->getColor(),
            ];
        }

        $data_arr_new = [];
        for ($i = count($data_arr) - 1; $i >= 0; $i--) {
            $data_arr_new[] = $data_arr[$i];
        }

        if ($onlyData) {
            return array_slice($data_arr_new, 0, 10);
        }

        if ($pdf) {
            return $data_arr;
        }

        return view('expense.income_expense_new', [
            'data_arr' => $data_arr_new,
            "total_exp" => $total_exp,
            "vendors" => Vendor::all()
        ]);
    }
    //getting ladger
    public function getExpensedata(Request $request)
    {
        $data_arr = [];
        $check = false;
        $from = $request->has('from') && $request->from != "" ? $request->from : date('Y-m-d');
        $to = $request->has('to') && $request->to != "" ? $request->to : "";
        $expenses = Expense::with('invoice')->select('expenses.*', 'banks.bank_name', 'vendors.name as vname')->leftjoin('banks', 'expenses.bank', '=', 'banks.id')->leftjoin('ledgers', 'expenses.created_at', '=', 'ledgers.created_at')->leftjoin('vendors', 'ledgers.vendor_id', '=', 'vendors.id');
        if ($request->has('from') && $request->from != "" && $request->has('to') && $request->to != "") {
            $expenses = $expenses->whereRaw('DATE(expenses.created_at)>="' . $request->from . '"')->whereRaw('DATE(expenses.created_at)<="' . $request->to . '"');
            $check = true;
        } else if ($request->has('from') && $request->from != "") {
            $expenses = $expenses->whereRaw('DATE(expenses.created_at)="' . $from . '"');
            $check = true;
        }
        $expenses = $expenses->orderby('expenses.id', 'desc');
        // if ($check == false) {
        //     $expenses = $expenses->limit(12);
        // }
        $expenses = $expenses->get();
        $credit = Expense::whereRaw('NOT(detail_hidden <=> "TRANSFER")')->sum('credit');
        $debit = Expense::whereRaw('NOT(detail_hidden <=> "TRANSFER")')->sum('debit');
        $total_exp = $credit - $debit;
        foreach ($expenses as $expense) {
            $ledger_detail = LedgerDetail::where('created_at', $expense->created_at)->get();
            $data_arr[] = [
                "expense_id" => $expense->id,
                "created_at" => $expense->created_at,
                "created_date" => date("d-m-Y h:i:a", strtotime($expense->created_at)),
                "payment_type" => $expense->payment_type,
                "bank" => $expense->bank,
                "detail" => $expense->detail,
                "detail_hidden" => $expense->detail_hidden,
                "credit" => $expense->credit,
                "debit" => $expense->debit,
                "ledger_detail" => $ledger_detail,
                "vname" => ($expense->vname ?? $expense->invoice->customer_name ?? ($expense->detail_hidden == 'Expense Bank' || $expense->detail_hidden == 'Expense' ? 'EXPENSE' : $expense->detail_hidden
                )),
                "invoice" => $expense->invoice,
            ];
        }
        return response()->json([
            'data' => $data_arr,
            "total_exp" => $total_exp,
        ]);
    }
    //pdf
    public function generate_income_expense_pdf(Request $request)
    {
        $data_arr = $this->incomeexpense($request, false, true);

        $from = /*$request->has('from') && $request->from != "" ? $request->from :*/ date('Y-m-d');
        $to = /*$request->has('to') && $request->to != "" ? $request->to : */ "";

        $data = [
            'title' => 'Superups-income-expense-', date('m/d/Y'),
            'date' => date('m/d/Y'),
            'data' => $data_arr,
            'from' => $from,
            'to' => $to,
            // 'total_exp' => $total_exp
        ];


        return view('expense.pdf', $data);
        // $pdf = PDF::loadView('expense.pdf', $data);
        // $customPaper = array(0, 0, 700, 700);
        // $pdf->set_paper($customPaper);
        // return $pdf->download('Superups-income-expense-' . date('m/d/Y h:i:a') . '.pdf');
    }
}
