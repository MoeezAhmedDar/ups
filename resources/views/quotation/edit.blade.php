@extends('layouts.dashboard',['title'=>'Add Vendor'])

@section('content')
<div class="card">
    <div class="card-body">
        <h4>Generate Quotation</h4><hr>
        <form method="post" action="{{ route('store_quotation') }}">
            @csrf
            <input name="id" type="hidden" value="{{$quotation[0]->id}}"/>
            <div class="row">
                <div class="col-md-3 col-3">
                    <label>Invoice/Quotation No</label>
                    <input type="text" value="{{$quotation[0]->invoice_no}}" name="invoice_no" class="form-control" />
                </div>
                <div class="col-md-3 col-3">
                    <label>Customer/Company Name</label>
                    <input type="text" value="{{$quotation[0]->customer_name}}" name="name" class="form-control"/>
                </div>
                <div class="col-md-3 col-3">
                    <label>Customer/Company Contact</label>
                    <input type="text" name="contact" value="{{$quotation[0]->contact}}" class="form-control" />
                </div>
                <div class="col-md-3 col-3">
                    <label>Address</label>
                    <textarea name="address" class="form-control">{{$quotation[0]->address}}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <button type="button" id="add_row_but" class="btn btn-primary" onclick="del_row()">-</button>
                </div>
                <div class="col-md-4 offset-md-4 text-right">
                    <button type="button" id="del_row_but" class="btn btn-primary" onclick="add_row()">+</button>
                </div>
            </div>
            <hr>
            <div class="row">
               
                    <div class="col-md-2 col-2">
                        <label>Item Name</label>   
                    </div>
                    <div class="col-md-3 col-3">
                        <label>Description</label>    
                    </div>
                    <div class="col-md-1 col-1">
                        <label>Quantity</label>    
                    </div>
                    <div class="col-md-2 col-2">
                        <label>Price</label>     
                    </div>
                    <div class="col-md-1 col-1">
                        <label>Discount</label>   
                    </div>
                    
                   
                    <div class="col-md-2 col-2">
                        <label>Total</label>     
                    </div>
                    
            </div>
            @foreach ($quotation_detail as $key => $item)
            <div id="row{{$key}}" class="row">
                
                <div class="col-md-2 col-2">
                    <input placeholder="Enter Item Name" type="text" value="{{$item->item}}" name="item[]" class="form-control" required/>    
                </div>
                <div class="col-md-3 col-3">
                    <input placeholder="Enter Item Description" type="text" value="{{$item->description}}" name="des[]" class="form-control" required/>    
                </div>
                <div class="col-md-1 col-1">
                    <input placeholder="0" id="{{$key}}qty" onkeyup="calculate_total('{{$key}}')" type="number" value="{{$item->quantity}}" name="qty[]" class="form-control" required/>    
                </div>
                <div class="col-md-2 col-2">
                    <input placeholder="Price" type="number" id="{{$key}}price" onkeyup="calculate_total('{{$key}}')" value="{{number_format((float)$item->price, 1, '.', '')}}"  name="price[]" class="form-control" required/>    
                </div>
                <div class="col-md-1 col-1">
                    <input placeholder="Discount" type="number" name="discount[]" value="{{$item->discount == null ? 0 : $item->discount }}" class="form-control"/>    
                </div>
                
               
                <div class="col-md-2 col-2">
                    <input readonly id="{{$key}}total"  type="number" class="form-control" value="{{$item->price * $item->quantity}}" required/>    
                </div>
                @if ($key > 0)
                    <div class="col-md-1">
                        <button type="button" onclick="removerow('{{$key}}')" class="btn btn-danger btn-sm mt-2">-</button>
                    </div>
                @endif
               
             
            </div>
            <hr>
            @endforeach
                <div id="form"></div>
                <div class="row">
                <div style="margin-top:10px;margin-left:15px;" class="text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>

        </form>
    </div>
</div>
<script>

var line_no={{count($quotation_detail)}};

//add_row();
 function add_row() {
    line_no++;
let html = `<div id="row${line_no}" style="margin-top:10px;" class="row">
    <div class="col-md-2 col-2">
        <input placeholder="Enter Item Name" type="text" name="item[]" class="form-control" required/>    
    </div>
    <div class="col-md-3 col-3">
        <input placeholder="Enter Item Description" type="text" name="des[]" class="form-control" required/>    
    </div>
    <div class="col-md-1 col-1">
        <input placeholder="0" id="${line_no}qty" onkeyup="calculate_total(${line_no})"  type="number" name="qty[]" class="form-control" required/>    
    </div>
    <div class="col-md-2 col-2">
        <input placeholder="Price" id="${line_no}price" onkeyup="calculate_total(${line_no})" type="number" name="price[]" class="form-control" required/>    
    </div>
    <div class="col-md-1 col-1">
        <input placeholder="Discount" type="number" name="discount[]" class="form-control"/>    
    </div>
    
   
    <div class="col-md-2 col-2">
        <input readonly  id="${line_no}total" type="number" class="form-control" required/>    
    </div>
    <div class="col-md-1">
                <button type="button" onclick="removerow(${line_no})" class="btn btn-danger btn-sm mt-2">-</button>
                </div>
    </div>`;
    $("#form").append(html).show();
     
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


</script>
@endsection