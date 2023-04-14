@extends('layouts.dashboard')

@section('content')
    <style>
        td {
            font-size: 15px !important
        }

        table-responsive {
            height: 600px ! important overflow:scroll;
        }
    </style>
    <div class="card">
        {{-- Top card section --}}
        <div class="card-body">
            <div class="row">
                <div class="col-xl-2 col-md-2">
                    <div class="card border-left-info shadow  py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Customer DUES</div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ @$total_ledger }} </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i style="color: red" class="fa fa-money fa-2x text-red text-red-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-2 mb-0">
                    <div class="card border-left-info shadow  py-2">
                        <a style="text-decoration:none;" href="{{ route('customer.show', 0) }}?date={{ date('Y-m-d') }}">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Today Sale</div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                    {{ @$todaysale }} </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i style="color: grey" class="fa fa-list-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-2 col-md-2">
                    <div class="card border-left-info shadow  py-2">
                        <a style="text-decoration:none;" href="{{ route('cash_detail') }}">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Cash</div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                    {{ @$totalcash }} </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i style="color: red" class="fa fa-money fa-2x text-red text-red-300"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-2 col-md-2 mb-0">
                    <div class="card border-left-info shadow  py-2">
                        <a style="text-decoration:none;" href="{{ route('cash_detail') . '?date=' . date('Y-m-d') }}">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Today Cash</div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                    {{ @$todaycash }}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i style="color: purple" class="fa fa-building fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-2 col-md-2 mb-0">
                    <div class="card border-left-info shadow  py-2">
                        <a href="{{ route('bank_detail') }}">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Bank</div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                    {{ @$banktotal }} </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i style="color: blue" class="fa fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-2 col-md-2 mb-0">
                    <div class="card border-left-info shadow  py-2">
                        <a style="text-decoration:none;" href="{{ route('bank_detail') }}?date={{ date('Y-m-d') }}">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Today Bank</div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                    {{ @$banktotaltoday }} </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i style="color: green" class="fa fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- End Top card section --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-6">
                    <h5 class="text-danger">Ledger</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center" id="datatable">
                            <thead class="th-color">
                                <tr>
                                    {{-- <th>ID</th> --}}
                                    <th>Date</th>
                                    <th>Vendor/Customer</th>
                                    <th>Payment Type</th>

                                    <th>Details</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (@$ledgers)
                                    @php
                                        $temp = null;
                                    @endphp
                                    @forelse ($ledgers as $item)
                                        @php
                                            // $due_charges=$due_charges+intval($item->amount);
                                            $detail = explode(',', $item->details);
                                            //dd(count($detail)-1);
                                            $TYPE = $item['full'] == '2' ? 'Partial' : ($item['full'] == '1' ? 'Paid' : ($item['full'] == '4' ? '' : 'Unpaid'));
                                            $color = 'black';
                                            if ($item->return == 1) {
                                                $color = 'red';
                                            }
                                            elseif ($item->full == 1) {
                                                $color = 'black';
                                            }
                                            elseif($item->full == 2){
                                                $color = 'blue';
                                                
                                            }
                                            elseif($item->full == 0){
                                                $color = 'green';
                                            }
                                        @endphp
                                        {{-- @if ($temp != $item->created_at) --}}
                                        <tr style="color:{{ $color }}">
                                            {{-- <td>{{ $item->id }}</td> --}}
                                            <td>{{ date('d-m-Y h:i:s a', strtotime($item->created_at)) }}</td>
                                            <td class="size">{{ $item->vendor->name ?? $item->invoice->customer->name ?? $item->invoice->customer_name ?? $item->walking_customer }}</td>
                                            <td>
                                                 @if ($item->walking_customer == 'EXPENSE')
                                                    @if ($item->payment_type == 1)
                                                        Cheque
                                                        {{-- ({{ $item->bank_name == null ? 'None' : $item->bank_name }}) --}}
                                                    @elseif($item->payment_type == 2)
                                                        Bank Deposit
                                                        {{-- ({{ $item->bank_name == null ? 'None' : $item->bank_name }}) --}}
                                                    @else
                                                        Cash
                                                    @endif
                                                @else
                                                    @if ($item->payment_type == 1)
                                                        Cheque / {{ $TYPE }}
                                                        {{-- ({{ $item->bank_name == null ? 'None' : $item->bank_name }}) --}}
                                                    @elseif($item->payment_type == 2)
                                                        Bank Deposit / {{ $TYPE }}
                                                        {{-- ({{ $item->bank_name == null ? 'None' : $item->bank_name }}) --}}
                                                    @else
                                                        Cash / {{ $TYPE }}
                                                    @endif
                                                @endif

                                            </td>

                                            <td>
                                                @if (count($detail) - 1 > 0)
                                                    @for ($i = 0; $i < count($detail) - 1; $i++)
                                                        <p>{{ $detail[$i] }}</p>
                                                    @endfor
                                                @else
                                                    {{ $item->details }}
                                                @endif
                                            </td>
                                            <td class="size">{{ $item->amount }}</td>

                                        </tr>
                                        {{-- @endif --}}
                                        @php
                                            $temp = $item->created_at;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="6">No data</td>
                                        </tr>
                                    @endforelse
                                    <tr>
                                        <th class="text-center" colspan="12"><a
                                                href="{{ route('view_ledger', 0) }}"><button class="btn btn-success"> View
                                                    All</button></a></th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="text-danger">Income & Expense Detail</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center" id="datatable">
                            <thead class="th-color">
                                <tr>
                                    <th>Date</th>
                                    <th>Customer/Vendor</th>
                                    <th>Details</th>
                                    <th>Payment Type</th>
    
                                    {{-- <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Amount</th> --}}
                                    <th>Credit</th>
                                    <th>Debit</th>
                                    <th>Total</th>
    
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $total_expense = intval($total_exp);
                                $data = $expenses;
                                $temp = null;
                                @endphp
                                @for($i=0; $i<count($data);$i++) 
                                @php $payment_type="Cash" ;
                                $color=$data[$i]['color'];
                                    if($data[$i]['payment_type']==1){ $payment_type="Cheque" ; }
                                    elseif($data[$i]['payment_type']==2){ $payment_type="Bank Deposit" ; } 
                                    if($i!=0){
                                    $total_expense=$data[$i-1]['detail_hidden']=='TRANSFER' ? $total_expense :
                                    ($data[$i-1]['credit']> 0 ? $total_expense - $data[$i-1]['credit'] :
                                    intval($total_expense) + intval($data[$i-1]['debit']));
                                    }
                                    @endphp
                                    @if(count($data[$i]['ledger_detail']) > 0)
                                    @php
                                    $vname = "";
                                    @endphp
                                    @for($a = 0; $a<count($data[$i]['ledger_detail']);$a++) @php
                                        $ledger_detail=$data[$i]['ledger_detail']; $row=count($ledger_detail)> 1 ?
                                        count($ledger_detail) : null;
                                        $vname = $data[$i]['vname'] ?? $data[$i]['invoice']['customer_name'] ??
                                        $data[$i]['detail_hidden'];
                                        $vname = $vname == 'Expense Bank' ? 'Expense' : $vname;
                                        @endphp
                                        <tr style="color:{{ $color }}">
                                            @if($a == 0)
                                            <td >{{ $a > 0 ? "//" : $data[$i]['created_date'] }}</td>
                                            <td >{{ $vname }}</td>
                                            <td >{{ $data[$i]['detail'] }}</td>
                                            <td >{{($a > 0 ? "" : $payment_type) }} / {{
                                            ($a > 0 ? "" :
                                                ($data[$i]['type']))
                                                }}</td>
                                            @else
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            @endif
    
                                            {{-- <td>{{ $ledger_detail[$a]['lproduct'] }}</td>
                                            <td>{{ $ledger_detail[$a]['lqty'] }}</td>
                                            <td>{{ $ledger_detail[$a]['lprice'] }}</td>
                                            <td>{{ intval($ledger_detail[$a]['lqty']) * intval($ledger_detail[$a]['lprice']) }}
                                            </td> --}}
                                            @if($a == 0)
                                            <td >
                                                {{ $data[$i]['credit'] > 0 ? $data[$i]['credit'] : "0" }}
                                            </td>
                                            <td >{{ $data[$i]['debit'] > 0 ? $data[$i]['debit'] : "0" }}
                                            </td>
    
                                            <td >{{ $data[$i]['total_expense'] }}</td>
                                            @else
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            @endif
    
                                        </tr>
                                        @endfor
    
                                        @else
                                        <tr  style="color:{{ $color }}">
                                            <td>{{ $data[$i]['created_date'] }}</td>
                                            <td>{{$data[$i]['vname'] ?? $data[$i]['invoice']['customer_name'] ??
                                                $data[$i]['detail_hidden'] ?? "-"}}</td>
                                            <td>{{ $data[$i]['detail'] }}</td>
                                            <td>{{$payment_type }} {{
                                            ($data[$i]['type'])
                                                }}</td>
                                            {{-- <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>{{ $data[$i]['credit'] > 0 ? $data[$i]['credit'] : $data[$i]['debit'] }}
                                            </td> --}}
                                            <td>{{ $data[$i]['credit'] }}</td>
                                            <td>{{ $data[$i]['debit'] }}</td>
                                            <td>{{ $data[$i]['total_expense'] }}</td>
                                        </tr>
                                        @endif
                                        @endfor
    @if(count($expenses) <= 0)
    <tr>
        <td colspan="7">No data</td>
    </tr>
    @endif
    <tr>
        <th class="text-center" colspan="12"><a
                href="{{ route('income_expense') }}"><button class="btn btn-success">
                    View All</button></a></th>
    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </div>
@endsection
