@extends('layouts.dashboard')

@section('content')
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
    <div class="row">
        <div class="col-12">
            <div class="card-header">
            <h4>Category Listing</h4>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card bg-white m-b-30">
                <div class="card-body table-responsive new-user">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                            <thead class="th-color">
                                <tr>
                                    <th class="border-top-0">Category ID</th>
                                    <th class="border-top-0">Category name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($category as $cate)     
                                <tr>
                                <td> {{ $cate->id }} </td>    
                                <td>{{ $cate->name }}</td>    
                                <td><a onclick="return confirm('Are you sure?')"  class="btn btn-danger" href="{{route('deletecategory',$cate->id)}}">Delete</a></</td>
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
    "bSort": true,
    "bLengthChange": true,
    "bPaginate": true,
    "bFilter": true,
    "bInfo": true,

});
</script>
@endsection
