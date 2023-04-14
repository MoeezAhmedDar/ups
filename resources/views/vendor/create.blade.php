@extends('layouts.dashboard',['title'=>'Add Vendor'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card-header">
        <h4>Add Vendor/Customer</h4>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">

        <form method="post" action="{{ route('vendor.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-5">
                    <label for="name">Name</label>
                    <input required name="name" type="text" class="form-control" value="{{ old('name') }}">
                    @error('name')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="col-md-5">
                    <label for="gender">Phone</label>
                    <input name="phone" minlength="11" maxlength="11" type="tel" class="form-control"
                        value="{{ old ('phone') }}">
                    @error('phone')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label for="type">Type</label>
                  <select class="form-control" name="type" required>
                    <option value="1">Customer</option>
                    <option value="0">Vendor</option>
                   
                  </select>
                    @error('type')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="address">Address</label>
                    <textarea name="address" rows="5" class="form-control">{{ old ('address') }}</textarea>
                    @error('address')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div style="margin-top:10px;margin-left:15px;" class="text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection