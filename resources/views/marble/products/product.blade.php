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
            <div class="row">
                <div class="col-2">
                    <h4>Product List</h4>

                </div>
                <div class="col-10">
                    <form action="{{route('product')}}">
                        <div class="row">
                            <div class="col-3">
                                <label>Search by Indivduals</label>
                                <select class="form-control" name="company_id">
                                    <option value="">Select Company</option>
                                    @foreach ($sub_categories as $item)
                                    <option {{$company_id == $item->id ? "selected" : ""}} value="{{$item->id}}">
                                        {{$item->name}}( {{$item->cname}} )</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <label>Search by Company</label>
                                <select class="form-control" name="company_name">
                                    <option value="">Select Company</option>
                                    @foreach ($sub_categoriesbyname as $item)
                                    <option {{$company_name == $item->name ? "selected" : ""}} value="{{$item->name}}">
                                        {{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <br>
                                <button class="btn btn-primary mt-2">Search</button>
                                <a href="{{route('product')}}"><button type="button"
                                        class="btn btn-danger mt-2">Reset</button></a>
                                <button name="pdf" class="btn btn-success mt-2">PDF</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body new-user">
               
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">No</th>
                                <th class="border-top-0">Category</th>
                                <th class="border-top-0">Company</th>
                                <th class="border-top-0">Product name</th>
                                {{-- <th class="border-top-0">Product Price</th>
                                    <th class="border-top-0">Product Color</th> --}}
                                <th class="border-top-0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product as $k=>$prod)
                            <tr>
                                <td> {{ ++$k }} </td>
                                <td> {{ $prod->Category->name }} </td>
                                <td> {{ $prod->Subcategory->name }} </td>
                                <td> {{ $prod->pname }} </td>
                                {{-- <td> {{ $prod->price }} </td>
                                <td> {{ $prod->p_color }} </td> --}}
                                <td>
                                    {{-- <a href={{ url("update-products/".$prod->id)}} class="btn btn-outline-info"
                                    >...</a> --}}

                                    <a href="{{ route('stock.index')}}?product_id={{$prod->id}}"><button
                                            class="btn btn-primary btn-sm">View Detail</button></a>
                                    <a href={{ url("delete-products/".$prod->id)}} onclick="return confirm('Are you sure?')"  class="btn btn-danger">Delete</a>
                                </td>
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
