@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card-header">
        <h4>Update Product</h4>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
<div class="row">
    <div class="col-md-12 mx-auto">
   
        <div class="card bg-white m-b-30">
            <div class="card-body new-user">
                <form action={{ asset("edit-Product/".$product->id)}} method="post">
                    @method('put')
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Category</label>
                                <select class="form-select form-control" name='p_category' aria-label="Default select example">
                                    <option value="">select product</option>
                                    @foreach ($category as $cate)
                                    <option value=" {{ $cate->id }} ">{{ $cate->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Product Name</label>
                                <input type="text" name="name"  value=" {{ $product->name }} "  class="form-control" placeholder="Add category name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Product Price</label>
                                <input type="text" name="price"  value=" {{ $product->price }} "  class="form-control" placeholder="Add category name">
                            </div>
                        </div> --}}
                        {{-- <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Product Color</label>
                                <input type="text" name="p_color" value=" {{ $product->p_color }} " class="form-control" placeholder="Add category name">
                            </div>
                        </div> --}}
                    </div>
                    
                    <div class="mb-3">
                        <button type="submit" class="btn btn-outline-success float-right">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
@endsection