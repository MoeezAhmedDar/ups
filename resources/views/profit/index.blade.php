@extends('layouts.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="row d-flex justify-content-between">
                <div class="col-6">
                    <h4>Profit Listing</h4>
                </div>
                <div class="col-6 text-right">
                    <a target="_blank" href="{{ route('profit.pdf') }}">
                        <button class="btn btn-primary">PDF</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body new-user">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Product</th>
                                <th>Purchase Price</th>
                                <th>Sell Price</th>
                                <th>Qty</th>
                                <th>Amount</th>
                                <th>Profit</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($arr_new as $k => $item)
                            <tr>
                                <td> {{ $item['date'] }} </td>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['product'] }}</td>
                                <td>{{ $item['purchase_price'] }}</td>
                                <td>{{ $item['price'] }}</td>
                                <td>{{ $item['qty'] ?? '1' }}</td>
                                <td>{{ $item['amount'] }}</td>
                                <td>{{ $item['profit'] }}</td>
                                <td>{{ $item['total'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

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