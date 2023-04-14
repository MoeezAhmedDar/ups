@extends('layouts.dashboard',['title'=>'Add Stock'])

@section('content')
@php
date_default_timezone_set("Asia/Karachi");
$date=date("Y-m-d");
@endphp
<hr>
<div class="card">
    <div class="card-header th-color">
        <h4>Invoice Detail</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="th-color">
                    <th>Created At</th>
                    <th>Invoice ID</th>
                    <th>Customer</th>
                    <th>Quantity</th>
                    <th>Bank</th>
                    <th>Payment By</th>
                    <th>Detail</th>
                    <th>Total</th>
                    <th>Paid</th>
                </thead>
                <tbody>
                    @foreach ($invoice as $item)
                    @php
                    $typebecome = "";
                    if($item->paid_amount > 0 && ($item->total_amount - $item->discount) != $item->paid_amount)
                    {
                    $typebecome = "(Partial)";
                    }else if($item->paid_amount == 0){
                    $typebecome = "(Unpaid)";
                    }

                    @endphp
                    <tr>
                        <td>{{$item->created_at}}</td>
                        <td>{{$id}}</td>
                        <td>@if($item->customer_name == null && $item->customer_id == null) Walking Customer
                            @elseif($item->customer_id == null) {{$item->customer_name}} @else {{$item->name}} @endif
                        </td>
                        <td>{{$item->total_qty}}</td>
                        <td>{{$item->bank_name == null ? "None" : $item->bank_name}}</td>
                        <td>@if($item->payment_type == 1) Cheque @elseif($item->payment_type == 2) Bank Deposit @else
                            Cash {{$typebecome}} @endif</td>
                        <td>{{$item->detail}}</td>
                        <td>{{$item->total_amount}}</td>
                        <td>{{$item->paid_amount}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
<div class="card">

    <div class="card-body">

        <form method="post" id="form_submit" action="{{ route('return') }}">
            <input type="hidden" value="{{$id}}" name="invoice_id" />
            <input type="hidden" value="{{$invoice[0]->total_amount}}" name="invoice_total" />
            <input type="hidden" value="{{$invoice[0]->total_qty}}" name="invoice_qty" />
            <input type="hidden" value="{{$invoice[0]->customer_id}}" name="vendor" />
            <input type="hidden" value="{{$invoice[0]->paid_amount}}" name="paid_amount" />

            @csrf
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="th-color">
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Return Quantity</th>

                    </thead>
                    <tbody>
                        @foreach ($subinvoice as $item)
                        <tr>
                            <td>{{$item->name}}</td>
                            <td>{{$item->price}}</td>
                            <td>{{$item->qty}}</td>
                            <td>{{$item->total}}</td>
                            <td>
                                <input type="hidden" name="product_name[]" value="{{$item->name}}" />
                                <input type="hidden" name="subinvoice_id[]" value="{{$item->id}}" />
                                <input type="hidden" name="price[]" value="{{$item->price}}" />
                                <input type="hidden" name="product_id[]" value="{{$item->product_id}}" />
                                <input type="hidden" name="prev_qty[]" value="{{$item->qty}}" />
                                <input type="number" name="qty[]" value="0" />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row mb-5">
                {{-- <div class="col-md-4">
                    <label>Amount</label>
                    <input required class="form-control" type="number" name="returned_amount"
                        value="0">
                </div> --}}
                <div class="col-md-4">
                    <label>Payment Type</label>
                    <select onchange="checkpaymenttype(this.value)" id="payment_type" class="form-control"
                        name="payment_type" required>
                        <option value="0">Cash</option>
                        <option value="1">Cheque</option>
                        <option value="2">Bank Deposit</option>
                    </select>
                </div>
                <div style="display: none;" id="bank_section" class="col-md-4">
                    <label>BANK</label>
                    <select name="bank" class="form-control">
                        <option value="">Select Bank</option>
                        @foreach ($banks as $item)
                        <option value="{{$item->id}}">{{$item->bank_name}}</option>
                        @endforeach
                    </select>

                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary" style="margin-top: 30px">Return</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function checkpaymenttype(value){
  $('#bank_section').hide();
  $('#bank_section > select').attr('required',false);
  if(value != 0){
      $('#bank_section').show();
      $('#bank_section > select').attr('required',true);
  }
}
</script>
@endsection