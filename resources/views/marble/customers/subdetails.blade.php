@extends('layouts.dashboard',['title'=>'View Details'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card-header">
        <h4>Latest Invoice Detail</h4>
        </div>
    </div>
    <div class="col-12">

        <div class="card m-b-30">
            <h4 class="float-right">{{$date}}</h4>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped text-center table-hover" id="datatable1">
                    <thead class="th-color">
                        <tr>
                            <th>S/No</th>
                            <th>Invoice No</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Total</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subinvoices as $item)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $item->invoice_id }}</td>
                            <td>{{ $item->invoice->customer->name ?? $item->invoice->customer_name ?? '' }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->total }}</td>
                            <td>{{ $total }}</td>

                        </tr>
                        @php $total = $total - $item->total @endphp
                        @empty
                        <tr>
                            <td colspan="8">No data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{-- @if($id == 0)
                {{ $subinvoices->links() }}
                @endif --}}

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
    "bSort": true,
    "bLengthChange": true,
    "bPaginate": true,
    "bFilter": true,
    "bInfo": true,

});
</script>
@endsection
