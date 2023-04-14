@extends('layouts.dashboard',['title'=>'Expense'])

@section('content')
<div class="row">
    <div class="col-2">

        <h4>Bank Income and Expenses</h4>
    </div>
    <div class="col-10">
        <form method="get" action="{{route('bank_detail')}}">
            <input type="hidden" value="{{$date}}" />
            <div class="row">
                <div class="col-lg-4 col-4">
                    <div id="bank_section" class="col-md-12">
                        <label>BANK</label>
                        <select name="bank" class="form-control">
                            <option value="">All</option>
                            @foreach ($banks as $item)
                            <option value="{{$item->id}}">{{$item->bank_name}}</option>
                            @endforeach
                        </select>

                    </div>

                </div>
                <div class="col-lg-4 col-4">
                    <br>
                    <button class="btn btn-primary mt-2">Search</button>
                </div>

            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center table-hover" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th>Date</th>
                                <th>Customer/Vendor</th>
                                <th>Detail</th>
                                <th>Payment Type</th>
                                <th>Credit</th>
                                <th>Debit</th>
                                <th>Total</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php

                            @endphp
                            @forelse ($expenses as $expense)
                            @php
                            if ($expense->detail_hidden=='TRANSFER') {
                                if ($expense->credit > 0) {
                                    $name = 'Cash In';
                                }
                                elseif ($expense->debit > 0) {
                                    $name = 'Cash Out';
                                }
                                else {
                                    $name = 'TRANSFER';
                                }
                            }elseif ($expense->detail_hidden=='Expense Bank' || $expense->detail_hidden=='Expense') {
                                $name = 'EXPENSE';
                            }elseif(is_null($expense->invoice->customer_id ?? null)){
                                $name = 'Walking Customer';
                            }else {
                                $name = null;
                            }
                            @endphp
                            <tr>
                                <td>{{ date("d-m-Y h:i:s a", strtotime($expense->created_at)) }}</td>
                                <td>{{ $expense->ledger->vendor->name ?? $name ?? 'INCOME/EXPENSE'}}</td>
                                <td>{{$expense->detail}}</td>
                                <td>
                                    @if ($expense->payment_type == 1)
                                    Cheque({{$expense->bank_name == null ? "None" : $expense->bank_name}})
                                    @elseif($expense->payment_type == 2)
                                    Bank Deposit({{$expense->bank_name == null ? "None" : $expense->bank_name}})
                                    @else
                                    Cash
                                    @endif
                                </td>

                                <td>{{$expense->credit}}</td>
                                <td>{{$expense->debit}}</td>

                                <td class="size">{{$total_exp}}</td>

                                @php
                                $total_exp = $expense->credit > 0 ? $total_exp-$expense->credit :
                                $total_exp+$expense->debit;
                                @endphp
                            </tr>

                            @empty
                            <tr>
                                <td colspan="6">No data</td>
                            </tr>

                            @endforelse
                        </tbody>
                    </table>
                    {{-- {{ $expenses->links() }} --}}
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<script>
</script>
@endsection


@section('scripts')
<style>
    .dataTables_paginate {
        display: block
    }
</style>
<script>
    $('#datatable1').DataTable({
    "bSort": false,
    "bLengthChange": true,
    "bPaginate": true,
    "bFilter": true,
    "bInfo": true,

});
</script>
@endsection