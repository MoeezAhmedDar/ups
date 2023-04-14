@extends('layouts.dashboard',['title'=>'Edit Vendor'])

@section('content')
<div class="card">
    <div class="card-body">

        <form method="post" action="{{ route('vendor.update',$vendor) }}">
            @csrf
            @method('put')
            <div class="row">
                <div class="col-md-6">
                    <label for="name">Name</label>
                    <input required name="name" type="text" class="form-control"
                        value="{{ old('name',$vendor->name) }}">
                    @error('name')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="gender">Phone</label>
                    <input name="phone" minlength="11" maxlength="11" type="tel" class="form-control"
                        value="{{ old ('phone',$vendor->phone) }}">
                    @error('phone')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="address">Address</label>
                    <textarea name="address" rows="5"
                        class="form-control">{{ old ('address',$vendor->address) }}</textarea>
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