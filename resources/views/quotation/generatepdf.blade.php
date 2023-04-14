 <!DOCTYPE html>


	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
	body{

 
}

.padding{

  padding: 2rem !important;
}

.card {
    /* margin-bottom: 30px; */
    border: none;
    -webkit-box-shadow: 0px 1px 2px 1px rgba(154, 154, 204, 0.22);
    -moz-box-shadow: 0px 1px 2px 1px rgba(154, 154, 204, 0.22);
    box-shadow: 0px 1px 2px 1px rgba(154, 154, 204, 0.22);
}

.card-header {
    background-color: red;
    border-bottom: 1px solid #e6e6f2;
    border: 4px solid white;
    color: white;
}

h3 {
    font-size: 20px;
}

h5 {
    font-size: 15px;
    line-height: 26px;
    color: #3d405c;
    margin: 0px 0px 15px 0px;
    font-family: 'Circular Std Medium';
}

.text-dark {
    color: #3d405c !important;
}
	.rectangle {
  height: 50px;
  width: 100px;
  border-right: 1px solid black;
  background-color: red;
}
.parallelogram {
	    width: 30px;
    height: 39px;
    transform: skew(-32deg);
    background: #555;
    color: white;
}
.sign{
  position: fixed; left: 0px; bottom: -100px; right: 0px;
}
    @page { margin: 180px 50px; }
    #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px; text-align: center; }
    #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 50px; }
    #footer .page:after { content: counter(page, upper-roman); }
  </style>
<body>
  <div id="header">
	<br>
	<div class="card-header p-4">
		<img style="float: left !important;" width="190px" height="150px" src="{{ asset("assets/images/app_logo.png") }}"/>
		<h1 class="mb-0">Super Ups Center</h1>
		<div class="float-right"> <h3 class="mb-0"><p><b>Shop No 12, insaf Solar Market, Angle Road,opp Civic Center, Quetta.</b></p>
      <p>contact1,contact2</p>
		</div>
		</div>
  </div>
  <div id="footer">
   <div class="card-header">
	
	<center>
		<p><b>Thanks for your business</b></p>
	</center>
   </div>
  </div>
  <div id="content">
    {{-- <p>the first page</p>
    <p style="page-break-before: always;">the second page</p> --}}
		 <div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
<div class="card ">

<div class="card-body">
<div class="row mb-4">
<div class="col-sm-6">
  <center>
    <br><br>
  <h2>Quotation</h2>
  </center>
<h3 class="text-dark mb-1">{{$quotation->customer_name}}</h3>
<div style="float:right">Date: {{date('d-m-Y')}}</div>
<div>{{$quotation->address}}</div>

<div>Phone: {{$quotation->contact}}</div>
</div>
<div class="col-sm-6 ">

</div>
<div class="">
<table width="100%" border="1" class="">
<thead>
<tr>
<th class="center" style="background-color: red; color:white;">#</th>
<th class="right" style="background-color: red; color:white;">Item</th>
<th class="right" style="background-color: red; width: 50%; color:white;">Description</th>
<th class="right" style="background-color: red; color:white;">Price</th>
<th class="center" style="background-color: red; color:white;">Qty</th>
<th class="center" style="background-color: red; color:white;">Discount</th>
<th class="right" style="background-color: red; color:white;">Total</th>
</tr>
</thead>
<tbody>
	@php
		$total = 0;
		$total_discount = 0;
	@endphp
	@foreach ($quotation_detail as $k =>  $q)
	@php
	$total += $q->price*$q->quantity;
	$discount = $q->discount == null ? 0 : $q->discount;
	$total_discount+=$discount;
  $grandtotal = ($q->price*$q->quantity) - $discount;
	@endphp
  
	<tr style="border: 1px solid black;" class="item">
		
		<?php $discount = $q->discount == null ? 0 : $q->discount;?>
			<td style="border-rigth:1px solid black;">{{++$k}}</td>
			<td style="border-rigth:1px solid black;">{{$q->item}}</td>
			<td>{{$q->description}}</td>
			<td>{{number_format((float)$q->price, 1, '.', '')}}</td>
			<td>{{$q->quantity}}</td>
			<td>{{$discount}}</td>
			<td>{{number_format((float)$grandtotal, 1, '.', '')}}</td>
		
	</tr>
	@endforeach

</tbody>
</table>
</div>
<hr>
<div class="row">
<div class="col-lg-4 col-sm-5">
</div>
<div class="col-lg-4 col-sm-5 ml-auto">
<table class="table table-clear">
<tbody>
<tr>
  <td colspan="2"> </td>
  <td colspan="2"> </td>
  <td colspan="2"> </td>
  <td colspan="2"> </td>
<td class="left">
	
<strong class="text-dark"><br>Subtotal</strong>
<br>
</td>
<td class="right"><br>Rs {{$total}}</td>
<br>
</tr>
<tr>
  <td colspan="2"> </td>
  <td colspan="2"> </td>
  <td colspan="2"> </td>
  <td colspan="2"> </td>
<td class="left">
<strong class="text-dark"><br>Discount</strong>
<br>
</td>
<td class="right"><br>Rs {{$total_discount}}</td>
<br>
</tr>

<tr>
  <td colspan="2"> </td>
  <td colspan="2"> </td>
  <td colspan="2"> </td>
  <td colspan="2"> </td>
<td class="left">
<strong class="text-dark"><br>Total</strong>
<br>
 </td>
<td class="right">
<strong class="text-dark"><br>Rs {{$total - $total_discount }}</strong>
<br>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<div>
	{{-- <br><br><br><br><br><br><br><br> --}}
	<div class="sign">
AUTHORIZE SIGNATURE
________________________________
	</div>
</div>
</div>

</div>
  </div>
  <div style="text-align: right">
    <div class="mt-2" style="font-size: 10px">Powered by Diamond Software 03202565919</p>
</div>
</body>
</html>



