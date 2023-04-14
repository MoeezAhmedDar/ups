{{-- 
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script> -->
  <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">

  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
	<title>

	</title>
</head>
<style type="">
	@media print {
body {-webkit-print-color-adjust: exact;}
}
</style>
<body>
	<nav class="navbar"></nav>
	<a style="color: red;font-size: 50px;" class="navbar-brand" href="#">
		<img width="300px" height="100px" src="{{ asset("assets/images/app_logo.png") }}"/></a>
<div class="container-fluid ">

<div class="row"> 

	<div style="padding-left: 5%;" class=" col-md-6">
<h1>Invoice to:<br>{{$invoice[0]->customer_id != null ? $invoice[0]->name : $invoice[0]->customer_name}}</h1>
 </div> 
<div style="background: linear-gradient(to bottom, #ea0a0a 0%, #a13a1d 100%) !important;text-align: center;border-top-left-radius: 30px ;border-bottom-left-radius: 30px;" class="col-md-6">
	<br>
	<div class="row">
		<div style="color: white;" class="col-md-6">
		<h3>Invoice# </h3>
	<h4>Date: </h4>
</div>
<div style="color:white;" class="col-md-6">
 <p> {{$id}}</p>
<p>{{date("d-m-Y h:i a", strtotime($invoice[0]->created_at))}}</p>
</div>
</div>
</div>
</div>

</div>
<table style="margin-top: 30px;" class="table table-bordered table-striped">
  <thead class="th-color">
    <tr>
      <th scope="col">No</th>
      <th scope="col">Item</th>
      <th scope="col">Price</th>
      <th scope="col">Qty.</th>
      <th scope="col">Total</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($invoice_detail as $key => $item)
    <tr>
      <th scope="row">{{++$key}}</th>
      <td>{{$item->pname}}</td>
      <td>{{$item->price}}</td>
      <td>{{$item->qty}}</td>
      <td>{{$item->price*$item->qty}}</td>
    </tr>
    @endforeach
   
    
  </tbody>
  </table>
  <hr>
  <div class="container-fluid">
    	<div class="row">
<div class="pl-5 pt-5 col-md-6">
<h1 style="font-size: 20px;color: red;"> Paymemt info:</h1>
	<div  class="row">
		<div style="font-size: 20px; color:black;" class="col-md-3">
<p style="font-size: 11px;"> <b>Payment Type:</b></p>


<hr>
<p style="font-size: 11px;"> <b>Bank:</b></p>
<hr>
<p style="font-size: 11px;"> <b>Details:</b></p>

	 </div>
	 <div style="font-size: 20px;color:black;" class="col-md-3">
<p style="font-size: 11px;"> @if ($invoice[0]->payment_type == 1)
    Cheque
    @elseif($invoice[0]->payment_type == 2)
    Bank Deposit
    @else
    Cash
@endif</p>
<hr>
<p style="font-size: 11px;"> {{$invoice[0]->bank_name == null ? "NONE" : $invoice[0]->bank_name}}</p>
<hr>
<p style="font-size: 11px;"> {{$invoice[0]->detail}}</p>
	 </div>

	 <hr style="color:black;height: 5px;">
	</div>

 </div>

		<div style="padding-left: 5%; font-size: 20px; color:black;" class="col-md-2">
			<b>
<p> Total :</p>

<hr style="color:black">
<p> Paid Amount:</p></b>
	 </div>
	 <div style="padding-left: 5%; font-size: 20px;color:black;" class="col-md-2">
<p> {{$invoice[0]->total_amount}}</p>

<hr style="color:black">
<p> {{$paidamount}}</p>
	 </div>

 	
 </div>
  	 </div>
  	 <div class="container-fluid">
    	<div class="row">
<div class="pl-5 pt-5 col-md-6">
<h1 style="font-size: 20px;color: red;"> Terms & Condition:</h1>
	
		<div style="font-size: 20px; color:black;" class="col-md-3">
<p> </p>

	 </div>
	 	 </div>
	 	 <div class="col-md-6">
	 	 	<hr  style="margin-top:120px;">

	 	 	<h4>Authorized Signature</h4>
	 	 </div>
  </div>
  <div class="container-fluid">
  	<div class="row">
  		<div style="background-color: red !important; color:white;padding: 30px; border-right: solid black 5px;font-weight: 600;font-size: 22px;" class="col-md-6">
  			<div>Thank you for you buisness</div>
  		</div>
  		<div class="col-md-6"></div>
  	</div>
  </div>

</body>
</html>
<script>
  window.print();
  setTimeout(function(){window.location.href="/public/dashboard";},3000);
// $(document).ready(function(){
  


// });
</script> --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super UPS CENTER</title>
    <style>
		/* @media screen {
 
} */
        /* @media print {
			.container1 {
				position: fixed;
				top: 20;
				padding:5opx !important;
	
  } */
body {-webkit-print-color-adjust: exact;}



        body{
            background-color: #F6F6F6; 
            margin: 0;
            padding: 0;
        }
        h1,h2,h3,h4,h5,h6{
            margin: 0;
            padding: 0;
        }
        p{
            margin: 0;
            padding: 0;
        }
        .container{
            width: 100%;
            margin-right: auto;
            margin-left: auto;
        }
        .brand-section{
           background-color: #b80000;
           padding: 10px 40px;
        }
        .logo{
            width: 50%;
        }

        .row{
            display: flex;
            flex-wrap: wrap;
        }
        .col-6{
            width: 50%;
            flex: 0 0 auto;
        }
        .text-white{
            color: #fff;
        }
        .company-details{
            float: right;
            text-align: right;
        }
        .body-section{
            padding: 16px;
            border-left: 2px solid #b80000;
            border-right: 2px solid  #b80000;
            
        }
        .body-section1{
            background-color: #b80000;
            color: white;
            border-radius: 4px;
        }
        .heading{
            font-size: 20px;
            margin-bottom: 08px;
        }
        .sub-heading{
            color: #262626;
            margin-bottom: 05px;
        }
        table{
            background-color: #fff;
            width: 100%;
            border-collapse: collapse;
        }
        table thead tr{
            border: 1px solid #111;
            background-color: #f2f2f2;
        }
        table td {
            vertical-align: middle !important;
            text-align: center;
        }
        table th, table td {
            padding-top: 08px;
            padding-bottom: 08px;
        }
        .table-bordered{
            box-shadow: 0px 0px 5px 0.5px gray;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }
        .text-right{
            text-align: end;
            padding-right: 3px;;
        }
        .w-20{
            width: 10%;
        }
		.w-15{
            width: 22%;
        }
        .w-5{
            width: 5%;
        }
        .w-10{
           width: 18%;
        }
        .float-right{
            float: right;
        }
        .container1{
            border: 2px solid rgb(184, 0, 0);
            color: #ffffff;
            height: 90px;
            border-radius: 6px;
			
          
        

        }
        .sub-container{
            background-color:  #b80000;;
            margin: 5px;
            padding-bottom: 2px;
            display: flex;
            height: 78px;
            border-radius: 6px;
            

        }
        .m-query1{
                font-size: 22px;
            }
            .m-query2{
                font-size: 11px;
            }
        img{
            margin-top: -36px;
            padding: 2px;
            width: 92%;
            height: 148px;
            margin-left: 2px;
            
        }
        .text1{
            text-align: center;
            width:70%; 
            padding-top: 11px;
        }
        .qoute{
            width: 21%;
            margin: auto;
            text-align: center;
            background-color: #b80000;
            color: white;
            border-radius: 5px;
            font-size: 12px;
        }
        @media screen and (max-width: 1014px) {
            .m-query1{
                margin-top: 6PX;
                font-size: 28px;
            }
            .m-query2{
                font-size: 11px;
            }
}
@media screen and (max-width: 900px) {
            .m-query1{
                font-size: 24px;
            }
            .m-query2{
                font-size: 14px;
            }
             img {
                width: 99%;
                 height: 171%;
                 margin-top: -50px;
                 margin-left: 8px;
}
            

}
.div3 {

 
}

#myDiv {
    width: 128px;
    font-size: 18px;
    margin-top: 19px;
    

}
.dot {
   
    height: 60px;
    width: 65px;
    background-color: #b80000;
    color: white;
    /* color: #b80000; */
    border-radius: 50%;
    display: inline-block;
    border: 5px solid white;
    margin: -14px;
    margin-left: 7px;
    text-align: center;
}


    </style>
</head>
<body>

    <div class="container">
        <div class="container1">
            <div class="sub-container">
                <div class="logo" style="width: 37%;">
                    <img src="{{ asset("assets/images/app_logo.png") }}" alt="logo">
                </div>
                <div3 id="myDiv">
                    
                    <span class="dot"><p style="margin-top: 15px;">خوشحال خان</p></span>
                    </div3>
                <div class="text1">
                    <h1 class="m-query1">Super UPS Center</h1>
                    <h3 class="m-query2">Shop No 12, insaf Solar Market, Angle Road, opp Civic Center, Quetta. <br>Phone:&nbsp; 0300-3883054,&nbsp;0309-8105556,&nbsp;081-2827774</h3>
                    
                </div>
            </div>
        </div>
		
        <!-- <div class="brand-section">
            <div class="row">
                <div class="col-6">
                    <h1 class="text-white">FABCART</h1>
                </div>
                <div class="col-6">
                    <div class="company-details">
                        <p class="text-white">assdad asd  asda asdad a sd</p>
                        <p class="text-white">assdad asd asd</p>
                        <p class="text-white">+91 888555XXXX</p>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="body-section">
            <div class="row">
                <div class="qoute">
                <h2 style="text-align: center;">INVOICE# &nbsp; {{$invoice[0]->id}}</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <!-- <h2 class="heading">Invoice No.: 001</h2> -->
                    <h3 class="sub-heading">Invoice to: {{$invoice[0]->customer_id != null ? $invoice[0]->name : $invoice[0]->customer_name}}</h3>
                
                   
                </div>
                <div class="col-6">
                    <div class="company-details">
                        <h3 class="text-dark">Date: {{date("d-m-Y h:i:a", strtotime($invoice[0]->created_at))}}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="body-section">
            <!-- <h3 class="heading">Ordered Items</h3>
            <br> -->
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th class="w-5">#</th>
                        <th class="w-15">Item</th>
                        <th class="w-10">Price</th>
                        <th class="w-10">Quantity</th>
                        <th class="w-10">Total</th>
                    </tr>
                </thead>
                <tbody>
					@php
					$total = 0;
					@endphp
					@foreach ($invoice_detail as $key => $item)
					<tr>
					  <th scope="row">{{++$key}}</th>
					  <td>{{$item->pname}}</td>
					  <td>{{$item->price}}</td>
					  <td>{{$item->qty}}</td>
					  <td>{{$item->price*$item->qty}}</td>
					</tr>
					@php
					$total += $item->price*$item->qty;
					@endphp
					@endforeach
					
                   
                   
                   @if($invoice[0]->discount > 0) 
                    <tr>
                        <td colspan="4" class="text-right"><h3>Sub Total</h3></td>
                        <td> <h3>{{$total}}</h3></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><h3>Discount</h3></td>
                        <td> <h3>{{$invoice[0]->discount}}</h3></td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="4" class="text-right"><h3>Total</h3></td>
                        <td> <h3>{{$total - $invoice[0]->discount}}</h3></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><h3>Paid Amount</h3></td>
                        <td> <h3>{{$paidamount}}</h3></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><h3>Remaining</h3></td>
                        <td><h3> {{$total - ($paidamount + $invoice[0]->discount)}}</h3></td>
                    </tr>
                </tbody>
            </table>
            <br>
            <h3  class="heading" style="display: inline;width: 100px; height: 100px;">Payment Type:</h3> <span style=" margin-left:48px;">@if ($invoice[0]->payment_type == 1)
				Cheque
				@elseif($invoice[0]->payment_type == 2)
				Bank Deposit
				@else
				Cash
			@endif</span><br>
            <h3  class="heading" style="display: inline;width: 100px; height: 100px;">Bank:</h3> <span style=" margin-left:125px;">{{$invoice[0]->bank_name == null ? "NONE" : $invoice[0]->bank_name}}</span><br>
            <h3  class="heading" style="display: inline;width: 100px; height: 100px;">Detail:</h3><span style=" margin-left:120px;"> {{$invoice[0]->detail}}</span><br>
            @if($invoice[0]->customer_id != null)
            @php
                $lc = new \App\Http\Controllers\LedgerController();
                $currentBalance = $lc->index_customer(new \Illuminate\Http\Request(), $invoice[0]->customer_id, true);
                $currentBill = $total - $invoice[0]->discount;
                $paidAmount = $paidamount;

                if ($paidAmount == 0) {
                    $billType = "unpaid";
                    
                    $previousBalance = $currentBalance + $currentBill;
                } else if ($paidAmount < $currentBill) {
                    $billType = "partially paid";
                    
                    $previousBalance = $currentBalance + $currentBill - $paidAmount;
                } else if ($paidAmount >= $currentBill) {
                    $billType = "paid";

                    // If the bill is paid, the previous balance is the same as the current balance
                    $previousBalance = $currentBalance;
                }

            @endphp
            <h3  class="heading" style="display: inline;width: 100px; height: 100px;">Previous Balance:</h3><span style=" margin-left:24px;"> {{$previousBalance ?? '0'}}</span><br>
            <h3  class="heading" style="display: inline;width: 100px; height: 100px;">Current Bill:</h3><span style=" margin-left:66px;"> {{ $currentBill ?? '0'}}</span><br>
            <h3  class="heading" style="display: inline;width: 100px; height: 100px;">Current Balance:</h3><span style=" margin-left:29px;"> {{$currentBalance ?? '0'}}</span><br>
            @endif
            <br><br>
            <h4 class="">Authorize Signature ___________________</h4>
            <p style="text-align:right;margin-right:2px;">superupscenter@gmail.com</p>
            <br>
        </div>

        <div class="body-section body-section1">
            <p style="text-align: center;">Thank You For Your Business 
            </p>
        </div>      
    </div>      
    <div style="text-align: right">
        <div class="mt-2" style="font-size: 10px">Powered by Diamond Software 03202565919</p>
    </div>
</body>
</html>
<script>
	window.print();
	setTimeout(function(){window.location.href="{{ route('customer.index') }}";},1000);

  </script> 
