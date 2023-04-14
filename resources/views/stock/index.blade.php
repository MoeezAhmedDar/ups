@extends('layouts.dashboard',['title'=>'View Stock'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <form action="{{route('stock.index')}}">

                <div class="row">
                    <div class="col-2">
                        <h4>Stock Listing</h4>
                    </div>
                    <div class="col-10">
                        <div class="row">
                            <div class="col-5">
                                <label>Products</label>
                                <select class="form-control" name="product_id">
                                    <option value="">Select Product</option>
                                    @foreach ($products as $item)
                                    <option {{$product_id == $item->id ? "selected" : ""}} value="{{$item->id}}">
                                        {{$item->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-3">
                                <br>
                                <button class="btn btn-primary mt-2">Search</button>
                                <a href="{{route('stock.index')}}"><button type="button"
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
            @if(session()->has('error'))
            <div class="card-header">
                <div class="alert alert-danger">
                    <p>{{ session('error') }}</p>
                </div>
            </div>
            @elseif (session()->has('success'))
            <div class="card-header">
                <div class="alert alert-success">
                    <p>{{ session('success') }}</p>
                </div>
            </div>
            @endif
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center table-hover" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Vendor/Customer</th>
                                <th>Purchased Price</th>
                                <th>Sale Price</th>
                                <th>Quantity Received</th>
                                <th>Quantity Remaining</th>
                                <th>Total Cost</th>
                                <th>Date</th>
                                <!--<th>Edit</th>-->
                                <th>Delete</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_qty = 0;
                         $total_rqty = 0;?>
                            @forelse ($stocks as $item)
                            <?php $total_qty+=$item->qty;
                            $total_rqty+=$item->qty_received;?>
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $item->product->name }}</td>
                                {{-- <td>{{ $item->vendor->name }}</td> --}}
                                <td>{{ $item->vname }}</td>
                                <td>{{ $item->price }}</td>
                                <td>{{ $item->sale_price }}</td>
                                <td>{{ $item->qty_received }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>{{ $item->total }}</td>
                                <td>{{ $item->date }}</td>

                                <!--<td>
                                <a href="{{ route('stock.edit',$item) }}">
                                    <button class="btn btn-success">Edit</button>
                                </a>
                            </td>-->
                                <td>
                                    <form action="{{ route('stock.destroy',$item->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <a onclick="return confirm('Are you sure?')">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </a>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">No data</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($product_id != null)
                        <tfoot>
                            <th colspan="2">Total Received Quantity</th>
                            <td colspan="2">{{$total_rqty}}</td>
                            <th colspan="2">Total Availble Quantity</th>
                            <td colspan="2">{{$total_qty}}</td>
                        </tfoot>
                        @endif
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