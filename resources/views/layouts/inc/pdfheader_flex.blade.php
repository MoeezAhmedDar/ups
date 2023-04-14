<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>


<style>
        .brand-section{
           background-color: #b80000;
           padding: 10px 40px;
        }
        .logo{
            width: 50%;
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
        /* img{
            margin-top: -36px;
            padding: 2px;
            width: 92%;
            height: 148px;
            margin-left: 2px;
            
        } */
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
             /* img {
                width: 99%;
                 height: 171%;
                 margin-top: -50px;
                 margin-left: 8px;
} */
            

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
.table{
            background-color: #fff;
            width: 100%;
            border-collapse: collapse;
        }
        .table thead tr{
            border: 1px solid #111;
            background-color: #f2f2f2;
        }
        .table td {
            vertical-align: middle !important;
            text-align: center;
        }
        .table th, .table td {
            padding-top: 08px;
            padding-bottom: 08px;
        }
        .table-bordered{
            box-shadow: 0px 0px 5px 0.5px gray;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }

    </style>
    </head>
    <body>
        <div style="background-color: #b80000;color:white;margin:0px;border:5px solid white;border-radius:11px;" class="row">
         <div class="col-2">
            <img width="200px" height="150px" src="{{ asset("assets/images/app_logo.png") }}"/>
        </div>
         <div class="col-8 d-flex justify-content-center align-items-center" style="flex-direction: column">
            <div>
                <h1 style="font-size:40px" class="m-query1">Super UPS Center</h1>
            </div>
            <div>
            <h3 style="font-size:20px;" class="m-query2">Shop No 12, insaf Solar Market, Angle Road, opp Civic Center, Quetta.&nbsp; <br>Phone:&nbsp; 0300-3883054,&nbsp;0309-8105556,&nbsp;081-2827774</h3>
            </div>
        </div>
         <div class="col-2 d-flex justify-content-center align-items-center">
            <img width="120px" height="80px" src="{{ asset("assets/images/name.png") }}"/>
        </div>
            {{-- <div class="col-lg-3 col-4 text-center">
            <img width="200px" height="150px" src="{{ asset("assets/images/app_logo.png") }}"/>
            </div>
            <div class="col-lg-2 col-2 text-center">
                <img width="120px" height="80px" src="{{ asset("assets/images/name.png") }}"/>
                </div>
                <div class="col-lg-7 col-7 text-center">
                    <h1 class="m-query1">Super UPS Center</h1>
                    <h3 class="m-query2">Shop No 12, insaf Solar Market, Angle Road, opp Civic Center, Quetta. <br>Phone:&nbsp; 0300-3883054,&nbsp;0309-8105556,&nbsp;081-2827774</h3>
                    
                </div> --}}
                {{-- <table style="width:100%" class="text-center">
                    <tr>
                        <td colspan="3" style="text-align:center;"><img width="200px" height="150px" src="{{ asset("assets/images/app_logo.png") }}"/></td>
                        <td colspan="9" style="text-align:center;">
                            <h1 class="m-query1">Super UPS Center</h1>
                            <h3 style="font-size:12px;" class="m-query2">Shop No 12, insaf Solar Market, Angle Road, opp Civic Center, Quetta.&nbsp; <br>Phone:&nbsp; 0300-3883054,&nbsp;0309-8105556,&nbsp;081-2827774</h3>
                        </td>
                        <td colspan="3" style="text-align:center;"><img width="120px" height="80px" src="{{ asset("assets/images/name.png") }}"/></td>
                        <tr>
                </table> --}}
            {{-- <div class="container1">
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
            </div> --}}
        </div>