@extends('layouts.dashboard',['title'=>'Add Stock'])

@section('content')
@php
date_default_timezone_set("Asia/Karachi");
$date=date("Y-m-d");
@endphp
<div class="card">
    <div class="card-body">

        <form method="post" id="form_submit" action="{{ route('sales.edit',$invoice->id) }}">
            @csrf
            @method('put')
            <div class="row mt-1 mb-4">
                <div class="col-md-3 col-3">
                    <button type="button" id="add_row_but" class="btn btn-primary" onclick="del_row()">-</button>
                </div>

                <div class="col-md-3 col-4">
                    <label>Select Customer</label>
                    <input disabled type="text" class="form-control" value="{{ $invoice->customer->name ?? $invoice->customer_name }}">
                    {{-- <select disabled onchange="checktype()" class="form-control select2" name="customer_name" id="customer_name"
                        required>
                        <option value="">Select</option>
                        <option value="0">Walking Cusomer</option>
                        @foreach ($vendors as $v)
                        <option {{ $v->id == $invoice->customer_id ? 'selected' : '' }} value="{{$v->id}}">{{$v->name}}</option>
                    @endforeach
                    </select> --}}
                    @error('customer_name')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div style="display: none;" class="col-md-3 col-4 hidden2">
                    <label>Enter Customer Name</label>
                    <input type="text" class="form-control" name="c_name">

                    @error('c_name')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="col-md-3 col-3 text-right">
                    <button type="button" id="del_row_but" class="btn btn-primary" onclick="add_row()">+</button>
                </div>
            </div>
            <div id='form'>
                @php
                    $loop_iteration = -1;
                @endphp
                @foreach ($invoice->sub_invoices as $subinvoice)
                @php
                    $loop_iteration = $loop->iteration;
                @endphp
                <div class="row" id="row{{ $loop->iteration }}">
                    <input type="hidden" name="subinvoice_id[]" value="{{ $subinvoice->id }}">
                    <div class="col-md-2 col-2">
                        <label for="product_id">Product</label>
                        <select required name="product_id[]" id="{{ $loop->iteration }}product_id" onchange="price_search('{{ $loop->iteration }}')"
                            class="form-control">
                            <option value="">Select Product</option>
                            @foreach ($products as $item)
                            <option {{ old('product_id',$subinvoice->product->id)==$item->id ? 'selected' : '' }} value="{{ $item->id }}">
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
                    <div class="col-md-2 col-2">
                        <label for="">Sale Price</label>
                        <input required type="number" readonly name="price[]" id="{{ $loop->iteration }}price"
                            onchange="available_qty_search('{{ $loop->iteration }}')" class="form-control" value="{{ $subinvoice->stock_id }}" />
                        @error('price')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-2 col-2">
                        <label for="">Price</label>
                        <input required name="price_org[]" id="{{ $loop->iteration }}price_org" onkeyup="calculate_total('{{ $loop->iteration }}')"
                            class="form-control" value="{{ $subinvoice->price }}">

                        <input name="purchase_price[]" id="{{ $loop->iteration }}purchase_price" type="hidden" value="{{ $subinvoice->purchase_price }}">

                        @error('price_org')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-2 col-2">
                        <label for="available">Available</label>
                        <input required name="available[]" data-purchased-qty="{{ $subinvoice->qty }}" readonly type="number" id="{{ $loop->iteration }}available" class="form-control">
                        @error('available')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-1 col-1">
                        <label for="qty">Quantity</label>
                        <input required name="qty[]" type="number" id="{{ $loop->iteration }}qty" onfocusout="calculate_total('{{ $loop->iteration }}')"
                            onkeyup="calculate_total('{{ $loop->iteration }}')" class="form-control" value="{{ $subinvoice->qty }}">
                        @error('qty')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-2 col-2">
                        <label for="total">Total</label>
                        <input required name="total[]" readonly type="number" id="{{ $loop->iteration }}total" class="form-control" value="{{ $subinvoice->total }}">
                        @error('total')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-1">
                        <br><button onclick="removerow({{ $loop->iteration }})" class="btn btn-danger btn-sm mt-2">-</button>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-3">
                <button type="button" onclick="check()" class="btn btn-primary">Save</button>
            </div>

            <div class="row mb-2 mt-4">
                <div class="col-md-1 mt-2">
                    <label>Total Amount :<label>
                </div>
                <div class="col-md-1 mt-2">
                    <label id="cal_amount">{{ $invoice->total_amount }}<label>
                </div>
                <div class="col-md-1 mt-2">
                    <label>Total Quantity :<label>
                </div>
                <div class="col-md-1 mt-2">
                    <label id="cal_qty">{{ $invoice->total_qty }}<label>
                </div>
                <div class="col-md-2 mt-2">
                    <label>Payment : <label>
                            <select onchange="checktype()" id="type" class="form-control" name="type" required>
                                <option {{ $invoice->paid_amount == ($invoice->total_amount - $invoice->discount) ? 'selected' : '' }} value="1">Paid</option>
                                <option {{ $invoice->paid_amount < ($invoice->total_amount - $invoice->discount) ? 'selected' : '' }} value="2">Partial</option>
                                <option {{ $invoice->paid_amount == '0' ? 'selected' : '' }} value="0">Unpaid</option>
                            </select>
                </div>

                <div style="display: none;" class="col-md-1 mt-2 hidden">
                    <label>Amount</label>
                </div>
                <div style="display: none;" class="col-md-2 mt-2 hidden">
                    <input type="text" class="form-control" name="paid_amount" value="{{ $invoice->paid_amount }}" />
                </div>
                <div class="col-md-1 mt-2">
                    <label>Payment Type : <label>
                </div>
                <div class="col-md-1 mt-2">

                    <select onchange="checkpaymenttype(this.value)" id="payment_type" class="form-control"
                        name="payment_type" required>
                        <option {{ $invoice->payment_type == '0' ? 'selected' : '' }} value="0">Cash</option>
                        <option {{ $invoice->payment_type == '1' ? 'selected' : '' }} value="1">Cheque</option>
                        <option {{ $invoice->payment_type == '2' ? 'selected' : '' }} value="2">Bank Deposit</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div style="display: none;" id="bank_section" class="col-md-4">
                    <label>BANK</label>
                    <select name="bank" class="form-control" id="bank__select">
                        <option value="">Select Bank</option>
                        @foreach ($banks as $item)
                        <option  {{ $invoice->bank == $item->id ? 'selected' : '' }} value="{{$item->id}}">{{$item->bank_name}}</option>
                        @endforeach
                    </select>

                </div>
                <div class="col-md-4">
                    <label>Payment Detail</label>
                    <textarea class="form-control" name="detail">{{ $invoice->detail }}</textarea>
                    <input type="hidden" class="form-control" name="detail_hidden" value="Item Purchased" />
                </div>
                <div class="col-md-4">
                    <label>Discount</label>
                    <input onkeyup="cal_amount()" type="number" class="form-control" name="discount" id="discount"
                        value="{{ $invoice->discount }}" />
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    var line_no= {{ $loop_iteration }};

    for (let i = 1; i <= line_no; i++) {
        console.log('dsfa')
        // available_qty_search(i);
        const product_id = $('#'+i+'product_id').val();
        $.ajax({
            url: "{{ route('available_qty_search_ajax') }}/"+product_id,
            type: "GET",
            dataType: "json",
            success: function(response){
                $("#"+i+"available").val(response.available);
                // $.each(response.available,function(key,item){
                //     $("#"+id+"available").val(item.qty);
                //     $("#"+id+"price_org").val(item.sale_price);
                // });     
            }
        });        
    }

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
            '<input type="hidden" name="subinvoice_id[]" value="-1">' +
                ' <div class="col-md-2">' +
                    '<label for="product_id">Product</label>'+
                    '<select required id="'+line_no+'product_id" name="product_id[]" onchange="price_search('+line_no+')" class="form-control">'+
                        '<option value="">Select Product</option>'+
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
                '<div class="col-md-2">' +
                    '<label for="">Sale Price</label>' +
                    '<input type="number" readonly required name="price[]" id="'+line_no+'price" onchange="available_qty_search('+line_no+')" class="form-control" />'+
                    '@error("price")'+
                    '<span class="text-danger">'+
                        '{{ $message }}'+
                    '</span>'+
                    '@enderror'+
                '</div>'+
                '<div class="col-md-2">'+
                    '<label for="">Price</label>'+
                    '<input required name="price_org[]" id="'+line_no+'price_org" onkeyup="calculate_total('+line_no+')" class="form-control" >'+
                    '<input  name="purchase_price[]" id="'+line_no+'purchase_price" type="hidden" >'+
                    '@error("price_org")'+
                    '<span class="text-danger">'+
                        '{{ $message }}'+
                    '</span>'+
                    '@enderror'+
                '</div>'+
                '<div class="col-md-2">'+
                    '<label for="available">Available</label>'+
                    '<input required name="available[]" data-purchased-qty="0" readonly type="number" id="'+line_no+'available" class="form-control" >'+
                    '@error("available")'+
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
    var price=$('#'+id+'price_org').val();
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
    let discount = $('#discount').val();
    total_amount = total_amount - discount;
    $('#cal_amount').html('');
    $('#cal_qty').html('');
    $('#cal_amount').html(total_amount);
     $('#cal_qty').html(total_qty);
    }

    function check(){
    //alert(id);
    var qty=document.getElementsByName("qty[]");
    var available=document.getElementsByName("available[]");
    
    for (var i = 0; i < available.length; i++) {
        // console.log(available[i].value);
        // console.log(qty[i].value);
        if($('#customer_name').val() == ""){
            alert("Select Cusomer");
            return;
        }
        if (  parseInt(qty[i].value)   <=   ( parseInt(available[i].value) + parseInt( available[i].getAttribute("data-purchased-qty") ) )) {
           
        }
        else{
            alert("Check That All Quantities Have To Be Smaller Or Equal than Available Quantity");
            return;
        }
        
    }

    if($('#payment_type').val() != '0'){
        if($('#bank__select').val() == ""){
            alert("Select Bank");
            return;
        }
    }
    
    $("#form_submit").submit();
    }


    function price_search(id){
 
 var pro_id=$("#"+id+"product_id").val();

 //alert(pro_id);

if(pro_id==""){

    $("#"+id+"price").html('');
   $("#"+id+"price").append( '<option value="">Select</option>'); 
   $("#"+id+"available").val('');
    $("#"+id+"price_org").val('');
    $("#"+id+"qty").val('');
    $("#"+id+"total").val('');
    cal_amount();
}
else{

    $.ajax({
   url: "{{ route('price_search_ajax') }}/"+pro_id,
   type: "GET",
   dataType: "json",
   success: function(response){
       
     $("#"+id+"price").html('');
     if(response.prices){
        $("#"+id+"purchase_price").val(response.purchase_price);
        $("#"+id+"price").val(response.prices);
        $("#"+id+"price_org").val(response.prices);
        available_qty_search(id,pro_id);
     }
     
//    $("#"+id+"price").append( '<option value="">Select</option>'); 
//    $.each(response.prices,function(key,item){
 
//      $("#"+id+"price").append( '<option value='+item.id+'>'+item.sale_price+'</option>'
//    ); 
 
  // });
     
     }
 });

}
   }


    function available_qty_search(id,product_id){
 
 var price_id=$("#"+id+"price").val();

 //alert(pro_id);

if(price_id==''){
    $("#"+id+"available").val('');
    $("#"+id+"price_org").val('');
    $("#"+id+"qty").val('');
    $("#"+id+"total").val('');
    cal_amount();
 }
else{

    $.ajax({
   url: "{{ route('available_qty_search_ajax') }}/"+product_id,
   type: "GET",
   dataType: "json",
   success: function(response){
    $("#"+id+"available").val(response.available);
     
//     $.each(response.available,function(key,item){
 
//         $("#"+id+"available").val(item.qty);
//         $("#"+id+"price_org").val(item.sale_price);
 
// });
   
     
     }
 });

}
   }

   $('#type').trigger('change');

</script>
@endsection