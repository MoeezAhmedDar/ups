@extends('layouts.dashboard',['title'=>'Expense'])

@section('content')
<h4>Cash Detail</h4>
<div class="row">
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">

                <br>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center table-hover" id="datatable">
                        <thead class="th-color">
                            <tr>
                                <th>Date</th>
                                <th>Customer/Vendor</th>
                                <th>Detail</th>
                                <th>Credit</th>
                                <th>Debit</th>
                                <th>Total</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php

                            @endphp
                            @forelse ($expenses as $expense)

                            <tr>
                                @php
                                $name = is_null($expense->invoice->customer_id ?? null) ? 'Expense' : null;
                                @endphp
                                <td>{{ date("d-m-Y h:i a", strtotime($expense->created_at)) }}</td>
                                @if($expense->detail_hidden != 'TRANSFER')
                                <td>{{ $expense->ledger->invoice->customer->name ?? $expense->ledger->vendor->name ?? $name ?? 'INCOME/EXPENSE'}}
                                </td>
                                <td>{{$expense->detail}}</td>
                                <td>{{$expense->credit}}</td>
                                <td>{{$expense->debit}}</td>
                                @else
                                <td>{{ $expense->debit > 0 ? 'Cash In' : 'Cash Out' }}</td>
                                @php
                                if ($expense->debit > 0 && isset($expense->bank_relation->bank_name)) {
                                $detail = 'Transfer from '. $expense->bank_relation->bank_name;
                                }elseif($expense->credit > 0 && isset($expense->bank_relation->bank_name)) {
                                $detail = 'Transfer to '. $expense->bank_relation->bank_name;
                                }else{
                                $detail = 'TRANSFER';
                                }
                                @endphp
                                <td>{{ $detail }}</td>
                                <td>{{$expense->debit}}</td>
                                <td>{{$expense->credit}}</td>
                                @endif
                                <td class="size">{{$total}}</td>
                            </tr>
                            @php
                            if($expense->detail_hidden == 'TRANSFER'){
                            $cr = $expense->debit;
                            $db = $expense->credit;
                            $total = $cr > 0 ? $total-$cr : $total+$db;
                            }else{
                            $total = $expense->credit > 0 ? $total-$expense->credit : $total+$expense->debit;
                            }
                            @endphp
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