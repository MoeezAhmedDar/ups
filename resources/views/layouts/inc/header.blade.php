<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>Super UPS Center</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="Mannatthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href= {{ asset("assets/images/app_logo.png" ) }}>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
     
        <!-- jvectormap -->
        <link href= {{ asset("assets/plugins/jvectormap/jquery-jvectormap-2.0.2.css") }} rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script defer src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>


        <link href= {{ asset("assets/css/bootstrap.min.css") }} rel="stylesheet" type="text/css">
        <link href= {{ asset("assets/css/icons.css") }} rel="stylesheet" type="text/css">
        <link href= {{ asset("assets/css/style.css") }} rel="stylesheet" type="text/css">
        {{-- data table --}}
        <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
        {{-- data table --}}
        <style>

          td{
              margin:0px !important;
              padding: 0px !important;
          }
          thead{
            position: -webkit-sticky; 
            position: sticky;
            top: 0;
            z-index: 1;
          }
          .table-responsive
          {
            height: 70vh !important;
            /* overflow:scroll; */
          }
          
          
      </style>

<style>
  .page-item.active .page-link{
      background-color: #ea0a0a ;
      border-color: #ea0a0a ;
  }

  .page-link{
      color: #ea0a0a ;
  }
  /* .dataTables_paginate {
      display: none
  } */
</style>
    </head>

<?php $role = \Auth::user()->role;?>

    <body class="fixed-left">

        <!-- Loader -->
        <div id="preloader"><div id="status"><div class="spinner"></div></div></div>
        <nav style="background: linear-gradient(to bottom, #ea0a0a 0%, #a13a1d 100%) !important" class="navbar navbar-expand-lg navbar-light bg-light">
            <a style="width: 17%;" class="navbar-brand" href="#">
              <img class="logo-nav" src="{{ asset("assets/images/app_logo.png") }}"/>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav">
                <li class="nav-item active">
                  <a class="nav-link" href="{{url('/dashboard')}}">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    Sales
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{route('customer.create')}}">Sell</a>
                    @if($role == 0)
                    <a class="dropdown-item" href="{{route('customer.index')}}">Sale Detail</a>
                    <a class="dropdown-item" href="{{route('customer.show',0)}} ">Complete Sale Detail</a>
                    <a class="dropdown-item" href="{{route('searchinvoice')}}">Return Invoice</a>
                    @endif
                  </div>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    Vendors/Customers
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{route('vendor.create')}}">Add Vendor/Customer</a>
                    @if($role == 0)
                    <a class="dropdown-item" href="{{route('vendor.index')}}">Vendor Detail</a>
                    <a class="dropdown-item" href="{{route('vendor.index')}}?c">Customer Detail</a>
                    @endif                   
                  </div>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    Stock
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{route('stock.create')}}">Add Stock</a>
                    @if($role == 0)
                    <a class="dropdown-item" href="{{route('availblestock')}}">Availble Stock</a>
                    <a class="dropdown-item" href="{{route('stock.index')}}">Stock Detail</a>
                   @endif
                  </div>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    Quotation
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{route('create_quotation')}}">Generate</a>
                    @if($role == 0)
                    <a class="dropdown-item" href="{{route('quotation')}}">Quotation Listing</a>
                    @endif
                  </div>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link" href="{{route('expense.index')}}" id="navbardrop">
                    Expense
                  </a>
            
                </li>
              
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    Bank
                  </a>
                  <div class="dropdown-menu">
                   
                    @if($role == 0)
                    <a class="dropdown-item" href="{{route('bank.index')}}">Banks</a>
                    <a class="dropdown-item" href="{{route('bank_detail')}}">Bank Income & expense</a>
                
                    @endif
                  </div>
                </li>
              
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    Category
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{route('add-category')}}">Add Category</a>
                    <a class="dropdown-item" href="{{route('category')}}">Category Listing</a>
                  </div>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    Company
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{route('add_subcategory')}}">Add Company</a>
                    <a class="dropdown-item" href="{{route('view_subcategory')}}">Company Listing</a>
                  </div>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    Products
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{route('add-products')}}">Add Product</a>
                    @if($role == 0)
                    <a class="dropdown-item" href="{{route('product')}}">Product Listing</a>
                    @endif
                  </div>
                </li>
              
              
                
           
              
              
              @if($role == 0)
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                  Users
                </a>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="{{route('register-form')}}">Add User</a>
                 
                  <a class="dropdown-item" href="{{route('users')}}">User Listing</a>
                  
                </div>
              </li>
              @endif
           
              </ul>
              
              
                <form method="POST" action="{{ route('logout') }}">
                  @csrf

                   <a class="btn btn-primary" href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="fa fa-power-off" aria-hidden="true"></i>
                      {{-- <i class="la la-caret-right"></i>   &nbsp; <span> {{ __('Log Out') }} </span> --}}
                   </a>
                 
              </form>  
              
            </div>
            
          </nav>
          
