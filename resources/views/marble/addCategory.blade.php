@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card-header">
        <h4>Add Category</h4>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
<div class="row">
    <div class="col-md-12 mx-auto">
        <div class="card bg-white m-b-30">
            <div class="card-body new-user">
                
                <form action={{ asset("insert-category")}} method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Add category name">
                        @error('name')
                            <span class="text-danger">
                               {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-outline-success float-right">Add Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
@endsection