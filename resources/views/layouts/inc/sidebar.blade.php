<div id="wrapper">

    <!-- ========== Left Sidebar Start ========== -->
    <div class="left side-menu">
        <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
            <i class="ion-close"></i>
        </button>

        <!-- LOGO -->
        <div class="topbar-left">
            <div class="text-center">
                <a href=" {{ url('/') }} " class="logo"><i class="mdi mdi-assistant"></i> MashaAllah</a>
                <!-- <a href="index.html" class="logo"><img src="assets/images/logo.png" height="24" alt="logo"></a> -->
            </div>
        </div>

        <div class="sidebar-inner slimscrollleft">

            <div id="sidebar-menu">
                <ul>
                    <li class="menu-title">Categories</li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-bullseye"></i>
                            <span> Categories </span> <span class="float-right"><i
                                    class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('addCategory') }}">Add Category</a></li>
                            <li><a href="{{ route('category') }}">View Category</a></li>
                        </ul>
                    </li>

                    <li class="menu-title">Sub Categories</li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-bullseye"></i>
                            <span>Sub Categories </span> <span class="float-right"><i
                                    class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('add_subcategory') }}">Add Sub Category</a></li>
                            <li><a href="{{ route('view_subcategory') }}">View Sub Category</a></li>
                        </ul>
                    </li>

                    <li class="menu-title">Products</li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-bullseye"></i>
                            <span> Products </span> <span class="float-right"><i
                                    class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('add-products') }}">Add Product</a></li>
                            <li><a href="{{ route('product') }}">View Product</a></li>
                        </ul>
                    </li>

                    <li class="menu-title">Vendors</li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-bullseye"></i>
                            <span> Vendors </span> <span class="float-right"><i
                                    class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('vendor.create') }}">Add Vendor</a></li>
                            <li><a href="{{ route('vendor.index') }}">View Vendor</a></li>
                        </ul>
                    </li>

                    <li class="menu-title">Stock</li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-bullseye"></i>
                            <span> Stock </span> <span class="float-right"><i
                                    class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('stock.create') }}">Add Stock</a></li>
                            <li><a href="{{ route('stock.index') }}">View Stock</a></li>
                        </ul>
                    </li>

                    <li class="menu-title">Customer</li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-bullseye"></i>
                            <span> Customer </span> <span class="float-right"><i
                                    class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('customer.create') }}">Sell Stock</a></li>
                            <li><a href="{{ route('customer.index') }}">View Reporting</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
            <div class="clearfix"></div>
        </div> <!-- end sidebarinner -->
    </div>
    <!-- Left Sidebar End -->