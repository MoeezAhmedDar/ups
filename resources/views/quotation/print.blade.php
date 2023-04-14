<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super UPS Center {{date('Y-m-d H:i:s')}}</title>
    <style>
              @media print {
body {-webkit-print-color-adjust: exact;}
}
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
           background-color: hwb(0 3% 24%);
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
            border-left: 2px solid rgb(184, 0, 0);
            border-right: 2px solid  rgb(184, 0, 0);
            
        }
        .body-section1{
            background-color:  rgb(184, 0, 0);
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
        }
        .w-20{
            width: 10%;
        }
        .w-5{
            width: 5%;
        }
        .w-10{
           width: 14%;
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
            width: 18%;
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


#myDiv {
    width: 128px;
    font-size: 18px;
    margin-top: 21px;
    

}
.dot {
   
    height: 55px;
    width: 60px;
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
                    
                    <span class="dot"><p style="margin-top: 10px;">خوشحال خان</p></span>
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
                <h2 style="text-align: center;">Quotation</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <!-- <h2 class="heading">Invoice No.: 001</h2> -->
                    <h3 class="sub-heading">Customer Name: {{$quotation[0]->customer_name}} </h3>
                    <h3 class="sub-heading">Contact: {{$quotation[0]->contact}} </h3>
                    <p class="sub-heading"><b>Address:</b> {{$quotation[0]->address}} </p>
                </div>
                <div class="col-6">
                    <div class="company-details">
                        <h3 class="sub-heading">Date: {{date('d-m-Y')}} </h3>
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
                        <th class="w-10">Item</th>
                        <th>Discription</th>
                        <th class="w-20">Price</th>
                        <th class="w-20">Quantity</th>
                        <th class="w-20">Discount</th>
                        <th class="w-10">Total</th>
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
                   
                    
                    <tr>
                        <td colspan="6" class="text-right">Sub Total</td>
                        <td>{{$total}}</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right">Discount</td>
                        <td>{{$total_discount}}</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right"><h3>Total PKR</h3></td>
                        <td><h3>{{$total - $total_discount }}</h3></td>
                    </tr>
                </tbody>
            </table>
            <br>
            <br>
            <h4 class="heading">Authorize Signature ___________________</h4>
            <p style="text-align:right;margin-right:2px;">superupscenter@gmail.com</p>
        </div>
       
        <div class="body-section body-section1">
            <p style="text-align: center;">Thank You For Your Business 
            </p>
        </div>      
    </div>      

</body>
</html>
<script>
	window.print();
	setTimeout(function(){window.location.href="{{ route('quotation') }}";},1000);

  </script>
