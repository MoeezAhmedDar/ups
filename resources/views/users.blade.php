@extends('layouts.dashboard')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-header">
                <div class="row d-flex justify-content-between">
                    <div class="col-6">
                        <h4>Users Listing</h4>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-left-info shadow">
                                    <a style="text-decoration:none;"
                                        href="{{ route('profit') }}?date={{ date('Y-m-d') }}">
                                        <div class="card-body p-3">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-info text-uppercase">
                                                        Total Profit : {{ @$total_profit }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
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
                                    <th class="border-top-0">NO</th>
                                    <th class="border-top-0">User name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $k => $item)     
                                <tr>
                                <td> {{ ++$k }} </td>    
                                <td>{{ $item->name }}</td>  
                                <td>{{ $item->email }}</td>  
                                <td>{{ $item->role == 1 ? "Operator" : "Admin" }}</td>        
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