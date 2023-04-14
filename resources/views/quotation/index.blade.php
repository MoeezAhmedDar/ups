@extends('layouts.dashboard',['title'=>'View Stock'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card-header">
<div class="row">
    <div class="col-2">
        <h4>Quotation List</h4>
    </div>
    <div class="col-10">
        <form action="{{route('quotation')}}">
            <div class="row">
                <div class="col-3">
                    <label>From</label>
                    <input type="date" class="form-control" value="{{$from}}" required name="from"/>
                </div>
                <div class="col-3">
                    <label>To</label>
                    <input type="date" class="form-control" value="{{$to}}" required name="to"/>
                </div>
                <div class="col-3">
                    <br>
                    <button class="btn btn-primary mt-2">Search</button>
                    <a href="{{route('quotation')}}"><button type="button" class="btn btn-danger mt-2">Reset</button></a>
                </div>
            </div>
        </form>
    </div>
</div>
        </div>
    </div>
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
                
                <div class="table-responsive">
                <table class="table table-striped text-center table-bordered table-hover" id="datatable1">
                    <thead class="th-color">
                        <tr>
                            <th>No</th>
                            <th>Reference No</th>
                            <th>Description</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Date & Time</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($quotation as $k=>$item)
                        <tr>
                            <td>{{ ++$k }}</td>
                            <td>{{$item->invoice_no}}
                            {{-- <td>{{ $item->customer_name }}</td> --}}
                            <td>{{ $item->listing_name }}</td>
                            <td>{{ $item->contact }}</td>
                            <td>{{ $item->address }}</td>    
                            <td>{{ date("d-m-Y h:i a", strtotime($item->created_at)) }}</td> 
                            <td>
                                {{-- <a title="PDF" href="{{route('quotation_pdf')}}?q={{$item->id}}"><i class="fa fa-download"></i></a> --}}
                                <a title="PDF" href="{{route('print_quotation')}}?q={{$item->id}}"><i class="fa fa-download"></i></a>
                                <a title="Edit" href="{{route('edit-quotation',$item->id)}}"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">No data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
                {{-- {{ $quotation->links() }} --}}
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
