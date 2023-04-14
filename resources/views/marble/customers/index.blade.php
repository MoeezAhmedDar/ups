@extends('layouts.dashboard',['title'=>'View Reporting'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <form method="get" action="{{route('customer.index')}}">

                <div class="row">
                    <div class="col-2">
                        <h4>Sales List</h4>
                    </div>
                    <div class="col-10 text-right">
                        <div class="row">
                            <div class="col-1">
                                <label>From</label>

                            </div>
                            <div class="col-2">
                                <input type="date" class="form-control"
                                    value="{{date('Y-m-d',strtotime($from . "-7 days"))}}" name="from" />

                            </div>
                            <div class="col-1 text-right">
                                <label>To</label>

                            </div>
                            <div class="col-2 text-right">
                                <input type="date" class="form-control" value="{{$to}}" name="to" />

                            </div>
                            <div class="col-3 text-right">
                                <button class="btn btn-primary mt-2">Search</button>
                                <a href="{{route('quotation')}}"><button type="button"
                                        class="btn btn-danger mt-2">Reset</button></a>
                                <button name="pdf" class="btn btn-success mt-2">PDF</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
                {{-- <div class="dataTables_length" id="datatable_length">
                    <label>Show
                        <select onchange="perPage(this.value)" name="datatable_length" aria-controls="datatable" class="">
                            <option {{ Request::get('perPage')=='10' ? 'selected' : '' }} value="10">10</option>
                            <option {{ Request::get('perPage')=='25' ? 'selected' : '' }} value="25">25</option>
                            <option {{ Request::get('perPage')=='50' ? 'selected' : '' }} value="50">50</option>
                            <option {{ Request::get('perPage')=='100' ? 'selected' : '' }} value="100">100</option>
                        </select> entries
                    </label>
                </div>
                <script>
                    function perPage(p) {
                        console.log(p);
                        window.location.href = "{{ route('customer.index') }}?perPage="+p
                    }
                </script> --}}
                <table class="table table-bordered text-center table-striped table-hover mt-1" id="datatable1">
                    <thead class="th-color">
                        <tr>
                            <th>Date Time</th>
                            <th>Invoice No</th>
                            <th>Customer Name</th>
                            <th>Payment Type</th>
                            <th>Bank</th>
                            <th>Total Quantity</th>
                            <th>Total Amount</th>
                            <th>Discount</th>
                            <th>Paid Amount</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invoices as $item)
                        @php
                        $color='black';
                        $typebecome = "";
                        if($item->paid_amount > 0 && ($item->total_amount - $item->discount) != $item->paid_amount){
                            $color = 'blue';
                            $typebecome = "(Partial)";
                        }else if($item->paid_amount == 0){
                            $color = 'green';
                        $typebecome = "(Unpaid)";
                        }

                        @endphp
                        <tr  style="color:{{ $color }}">
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name == null ? $item->customer_name." (Walking Customer)" : $item->name }}
                            </td>
                            <td>
                                @if ($item->payment_type == 1)
                                Cheque
                                @elseif($item->payment_type == 2)
                                Bank Deposit
                                @else
                                Cash {{$typebecome}}
                                @endif
                            </td>
                            <td>{{$item->bank_name == null ? "None" : $item->bank_name}}</td>
                            <td>{{ $item->total_qty }}</td>
                            <td>{{ $item->total_amount }}</td>
                            <td>{{ $item->discount }}</td>
                            <td>{{ $item->paid_amount }}</td>

                            <!--<td>{{ $item->updated_at }}</td>-->

                            <td class="d-flex">
                                <a href="{{ route('sales.edit',$item->id) }}" data-toggle="tooltip" data-placement="top" title="Edit">
                                    {{-- <button class="btn btn-success btn-sm mr-2 ml-2"> --}}
                                        <i class="m-2 fa-solid fa-edit"></i>
                                    {{-- </button> --}}
                                </a>
                                <a href="{{ route('customer.show',$item->id) }}"  data-toggle="tooltip" data-placement="top" title="Detail">
                                    {{-- <button class="btn btn-success btn-sm mr-2 ml-2"> --}}
                                        <i class="m-2 fa-solid fa-list"></i>
                                    {{-- </button> --}}
                                </a>
                                <a
                                    href="{{ route('sale_print')}}?invoice_id={{$item->id}}&paidamount={{$item->paid_amount}}"  data-toggle="tooltip" data-placement="top" title="Print">
                                    {{-- <button class="btn btn-primary btn-sm mr-2 ml-2"> --}}
                                        <i class="m-2 fa-solid fa-print"></i>
                                    {{-- </button> --}}
                                </a>
                                <form onsubmit="return confirm('Are you sure?')" action="{{route('customer.destroy', $item->id)}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    {{-- <button class="btn btn-danger btn-sm mr-2 ml-2" type="submit"> --}}
                                    <button style="all:unset; cursor:pointer" type="submit">
                                        <i class="m-2 fa-solid fa-trash text-danger"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">No data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-2 d-flex justify-content-center">
                    {{-- {{ $invoices->appends($_GET)->links() }} --}}
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


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