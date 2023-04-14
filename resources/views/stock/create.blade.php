@extends('layouts.dashboard',['title'=>'Add Stock'])

@section('content')
@php
date_default_timezone_set("Asia/Karachi");
$date=date("Y-m-d");
@endphp
<div class="card">
    <div class="card-body">
    
        <form id="actual-form" method="post" action="{{ route('stock.store') }}">
            @csrf
            <div class="row mt-1 mb-4">
                <div class="col-md-4">
                    {{-- <button type="button" id="add_row_but" class="btn btn-primary" onclick="del_row()">-</button> --}}
                </div>
                <div class="col-md-1 mt-2">
                <label for="vendor_id">Vendor/Customer</label>
                </div>
                <div class="col-md-3">  
                    <select required name="vendor_id" class="form-control select2">
                        <option value="">Select</option>
                        @foreach ($vendors as $item)
                        <option {{ old('vendor_id')==$item->id ? 'selected' : '' }} value="{{ $item->id }}">
                            {{ $item->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('vendor_id')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="col-md-4 text-right">
                    {{-- <button type="button" id="del_row_but" class="btn btn-primary" onclick="add_row()">+</button> --}}
                </div>
            </div>
            <div id='form'>
            <div class="row">
                <div class="col-md-2">
                    <label for="product_id">Product</label>
                    <select required name="product_id[]" class="form-control">
                        <option value="">Please select Product</option>
                        @foreach ($products as $item)
                        <option {{ old('product_id')==$item->id ? 'selected' : '' }} value="{{ $item->id }}">
                            {{ $item->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('product_id')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                
                <div class="col-md-2">
                    <label for="date">Date</label>
                    <input required name="date[]" type="date" value="{{ $date }}" class="form-control">
                    @error('date')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label for="price">Purchase Price</label>
                    <input required name="price[]" type="number" id="1price" onkeyup="calculate_total('1')" class="form-control" >
                    @error('price')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label for="sprice">Sale Price</label>
                    <input required name="sprice[]" type="number"  class="form-control" >
                    @error('sprice')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="col-md-1">
                    <label for="qty">Quantity</label>
                    <input required name="qty[]" type="number" id="1qty" onfocusout="calculate_total('1')" onkeyup="calculate_total('1')" class="form-control" >
                    @error('qty')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label for="qty">Total</label>
                    <input required name="total[]" readonly type="number" id="1total" class="form-control" >
                    @error('total')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                
            </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
       
            <div class="row mb-2 mt-4">
                <div class="col-md-1 mt-2">
                <label>Total Amount :<label>
                </div>
                <div class="col-md-1 mt-2">
                <label id="cal_amount">0<label>
                </div>
                <div class="col-md-1 mt-2">
                <label>Total Quantity :<label>
                </div>
                <div class="col-md-1 mt-2">
                <label id="cal_qty">0<label>
                </div>
                <div class="col-md-2 mt-2">
                    <label>Payment : <label>
                        <select onchange="checktype()" id="type" class="form-control" name="type" required>
                            <option value="1">Paid</option>
                            <option value="2">Partial</option>
                            <option value="0">Unpaid</option>
                        </select>
                </div>
                
                <div style="display: none;" class="col-md-1 mt-2 hidden">
                    <label>Amount</label>
                </div>
                <div style="display: none;" class="col-md-2 mt-2 hidden">
                    <input id="amount-field" type="text" class="form-control" name="paid_amount"/>
                </div>
                <div class="col-md-1 mt-2">
                    <label>Payment Type : <label>
                    </div>
                <div class="col-md-1 mt-2">
                    
                        <select onchange="checkpaymenttype(this.value)" id="payment_type" class="form-control" name="payment_type" required>
                            <option value="0">Cash</option>
                            <option value="1">Cheque</option>
                            <option value="2">Bank Deposit</option>
                        </select>
                </div>
            </div>
            <div class="row">
                <div style="display: none;" id="bank_section" class="col-md-4">
                    <label>BANK</label>
                    <select name="bank" class="form-control" id="bank__select">
                        <option value="">Select Bank</option>
                        @foreach ($banks as $item)
                            <option value="{{$item->id}}">{{$item->bank_name}}</option>
                        @endforeach
                    </select>
                 
                </div>
                <div class="col-md-4">
                    <label>Payment Detail</label>
                    <textarea class="form-control" name="detail">Stock Added</textarea>
                    <input type="hidden" name="detail_hidden" value="Stock Added">
                </div>
            </div>
        </form>
    </div>
</div>

<script>

var line_no=1;
function checktype(){
    $('.hidden').hide();
    $('.hidden2').hide();
    //alert($('#customer_name').val());
    if($('#type').val() == "" || $('#customer_name').val() == "") return;
    if($('#customer_name').val() == 0){
        $('.hidden2').show();
    }
    //alert($('#type').val());
    if($('#type').val() == 2 && $('#customer_name').val() != 0)
    {
        $('.hidden').show();
    }
}
  function checkpaymenttype(id){
    $('#bank_section').hide();
    if(id != 0){
        $('#bank_section').show();
        $('#bank__select').attr('required',true);
    }else{
        $('#bank__select').attr('required',false);
    }
  }

  

 function add_row() {
    line_no++;

    $("#form").append(
        '<div id="row'+line_no+'" class="row">' +
                ' <div class="col-md-2">' +
                    '<label for="product_id">Product</label>'+
                    '<select required id="'+line_no+'product_id" name="product_id[]" class="form-control">'+
                        '<option value="">Please select Product</option>'+
                        '@foreach ($products as $item)'+
                        '<option {{ old("product_id")==$item->id ? "selected" : "" }} value="{{ $item->id }}">' +
                            '{{ $item->name }}'+
                        '</option>'+
                       '@endforeach'+
                    '</select>'+
                   '@error("product_id")'+
                    '<span class="text-danger">'+
                        '{{ $message }}'+
                    '</span>'+
                    '@enderror'+
                '</div>'+
                '<div class="col-md-2">'+
                    '<label for="date">Date</label>'+
                    '<input required name="date[]" type="date" value="{{ $date }}" class="form-control">'+
                    '@error("date")'+
                    '<span class="text-danger">'+
                        '{{ $message }}'+
                    '</span>'+
                    '@enderror'+
                '</div>'+
                '<div class="col-md-2">' +
                    '<label for="price">Perchase Price</label>' +
                    '<input required name="price[]" id="'+line_no+'price" type="number" onkeyup="calculate_total('+line_no+')" class="form-control">'+
                    '@error("price")'+
                    '<span class="text-danger">'+
                        '{{ $message }}'+
                    '</span>'+
                    '@enderror'+
                '</div>'+
                '<div class="col-md-2">' +
                    '<label for="price">Sale Price</label>' +
                    '<input required name="sprice[]" type="number" class="form-control">'+
                    '@error("price")'+
                    '<span class="text-danger">'+
                        '{{ $message }}'+
                    '</span>'+
                    '@enderror'+
                '</div>'+
                '<div class="col-md-1">'+
                    '<label for="qty">Quantity</label>'+
                    '<input required name="qty[]" type="number" id="'+line_no+'qty" onfocusout="calculate_total('+line_no+')" onkeyup="calculate_total('+line_no+')" class="form-control" >'+
                    '@error("qty")'+
                    '<span class="text-danger">'+
                        '{{ $message }}'+
                    '</span>'+
                    '@enderror'+
                '</div>'+
                '<div class="col-md-2">' +
                    '<label for="total">Total</label>' +
                    '<input required name="total[]" readonly type="number" id="'+line_no+'total" class="form-control">'+
                    '@error("total")'+
                    '<span class="text-danger">'+
                        '{{ $message }}'+
                    '</span>'+
                    '@enderror'+
                '</div>' +
                '<div class="col-md-1">' +
                    '<br><button onclick="removerow('+line_no+')" class="btn btn-danger btn-sm mt-2">-</button>'+
                '</div>' +
            '</div>' 
            
        ).show();
     
 }

 function removerow(id)
{
    $('#row'+id).remove();
}

 function del_row() {
    if(line_no>1){
$("#form").find("div.row:last").remove();
line_no--;
cal_amount();
}
 }

 function calculate_total(id){
    //alert(id);
    var qty=$('#'+id+'qty').val();
    var price=$('#'+id+'price').val();
     if(qty!='' && price!=''){
        $('#'+id+'total').val(price*qty);
        cal_amount(); 
    }
     else{
        $('#'+id+'total').val('');
    cal_amount();
     }
 }

 function cal_amount(){
    //alert(id);
    var qty=document.getElementsByName("qty[]");
    var amount=document.getElementsByName("total[]");
    var total_amount=0;
    var total_qty=0;
  
    for (var i = 0; i < amount.length; i++) {
        console.log(amount[i].value);;
        if (amount[i].value=='') {
            
        }
        else{
            total_amount+=parseInt(amount[i].value);
        }
        if (qty[i].value=='') {
            
        }
        else{
        total_qty+=parseInt(qty[i].value);
        }
    }
    $('#cal_amount').html('');
    $('#cal_qty').html('');
    $('#cal_amount').html(total_amount);
     $('#cal_qty').html(total_qty);
    }


$('#actual-form').on('submit',function(e){
    e.preventDefault();

    if ($('#type').val() == '2' && $('#amount-field').val() == '') {
        alert('Please enter amount')
        return; 
    }

    $(this).submit()
});
</script>
@endsection
