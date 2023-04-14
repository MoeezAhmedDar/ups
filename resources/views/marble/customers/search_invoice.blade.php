@extends('layouts.dashboard',['title'=>'Add Stock'])

@section('content')
@php
date_default_timezone_set("Asia/Karachi");
$date=date("Y-m-d");
@endphp
<hr>
<div class="card">
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <div class="card-header th-color">
        <h4>Invoice Detail</h4>
    </div>
 
</div>
<div class="card">

    <div class="card-body">
    
        <form method="get" id="form_submit" action="{{ route('searchinvoice') }}">
          
            @csrf
  
            <div class="row">
                <div class="col-md-4 offset-md-4">
                    <label>Enter Invoice No</label>
                    <input type="number" name="invoice_id" required class="form-control"/>
                </div>
                <div class="col-md-12 text-center">
                    <br>
                    <button class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>

</script>
@endsection
