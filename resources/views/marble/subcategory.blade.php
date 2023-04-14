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
            <h4>Company Listing</h4>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card bg-white m-b-30">
                <div class="card-body new-user">
                   
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                            <thead class="th-color">
                                <tr>
                                    <th class="border-top-0">No</th>
                                    <th class="border-top-0">Company name</th>
                                    <th class="border-top-0">Category name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subcategory as $k => $subcate)     
                                <tr>
                                <td> {{ ++$k }} </td>    
                                <td>{{ $subcate->name }}</td>
                                <td>{{ $subcate->cname }}</td>  
                                <td><a onclick="return confirm('Are you sure?')"  class="btn btn-danger" href="{{route('deletecompany',$subcate->id)}}">Delete</a></td>  
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
