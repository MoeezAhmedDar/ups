@include('layouts.inc.header')
        <!-- Begin page -->
      {{-- @include('layouts.inc.sidebar')
            <!-- Start right Content here -->
      @include('layouts.inc.topbar') --}}
                    <div class="page-content-wrapper ">

                        <div class="container-fluid" style="height: 100%">

                            {{-- <div class="row">
                                <div class="col-md-12">
                                    <div class="page-title-box">
                                        <div class="btn-group float-right">
                                            <ol class="breadcrumb hide-phone p-0 m-0">
                                              
                                                <li class="breadcrumb-item active">Dashboard</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title">Dashboard</h4>
                                    </div>
                                </div>
                            </div>  --}}
                            @yield('content')                       
                        </div><!-- container -->
                        <hr>
                    </div> <!-- Page content Wrapper -->
                  
@include('layouts.inc.footer')