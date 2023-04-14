@extends('layouts.dashboard',['title'=>'View Stock'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h4>Availble Stock Listing</h4>
                </div>
                <div class="col-6">
                    <a href="{{route('availblestock')}}?pdf"><button class="btn btn-success float-right">PDF</button></a>
                </div>
            </div>
        
        </div>
    </div>
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
               <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped text-center" id="datatable1">
                    <thead class="th-color">
                        <tr>
                            <th>No</th>
                            <th>Company</th>
                            <th>Product</th>
                            <th>Quantity </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        @forelse ($stocks as $item)
                       
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $item->company_name }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->total_qty }}</td>
                            <td><a href="{{route('stock.index')}}?product_id={{$item->id}}"><button class="btn btn-primary btn-sm">View Detail</button></a></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">No data</td>
                        </tr>
                        @endforelse
                    </tbody>
                  
                </table>
               </div>
                {{-- {{ $stocks->links() }} --}}
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
