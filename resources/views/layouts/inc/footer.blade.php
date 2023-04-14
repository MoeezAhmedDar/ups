
                </div> <!-- content -->

                <footer class="footer">
                    © 2022 All Right Reserved By diamondsoftwareqta.com
                </footer>

            </div>
            <!-- End Right content here -->

        </div>
        <!-- END wrapper -->

        <!-- jQuery  -->
        <script src= {{ asset("assets/js/jquery.min.js" ) }}></script>
        <script src= {{ asset("assets/js/popper.min.js" ) }}></script>
        <script src= {{ asset("assets/js/bootstrap.min.js" ) }}></script>
        <script src= {{ asset("assets/js/modernizr.min.js" ) }}></script>
        <script src= {{ asset("assets/js/detect.js" ) }}></script>
        <script src= {{ asset("assets/js/fastclick.js" ) }}></script>
        <script src= {{ asset("assets/js/jquery.slimscroll.js" ) }}></script>
        <script src= {{ asset("assets/js/jquery.blockUI.js" ) }}></script>
        <script src= {{ asset("assets/js/waves.js" ) }}></script>
        <script src= {{ asset("assets/js/jquery.nicescroll.js" ) }}></script>
        <script src= {{ asset("assets/js/jquery.scrollTo.min.js" ) }}></script>

        <!--Morris Chart-->
        <script src= {{ asset("assets/plugins/flot-chart/jquery.flot.min.js" ) }}></script>
        <script src= {{ asset("assets/plugins/flot-chart/jquery.flot.time.js" ) }}></script>
        <script src= {{ asset("assets/plugins/flot-chart/curvedLines.js" ) }}></script>
        <script src= {{ asset("assets/plugins/flot-chart/jquery.flot.pie.js" ) }}></script>
        <script src= {{ asset("assets/plugins/morris/morris.min.js" ) }}></script>
        <script src= {{ asset("assets/plugins/raphael/raphael-min.js" ) }}></script>
        <script src= {{ asset("assets/plugins/jquery-sparkline/jquery.sparkline.min.js" ) }}></script>
        
        <script src= {{ asset("assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js" ) }}></script>
        <script src= {{ asset("assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" ) }}></script>


        {{-- <script src= {{ asset("assets/pages/crypto-dash.init.js" ) }}></script> --}}
{{-- data table --}}
<script src= "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

{{-- data table --}}
        <!-- App js -->
        <script src= {{ asset("assets/js/app.js" ) }}></script>
        <script>
             
            $(document).ready(function() {        
                
    $('#datatable').DataTable({
        "bSort": false,
        // "bLengthChange": true,
        // "bPaginate": false,
        // "bFilter": false,
        // "bInfo": false,

    });
    $('.select2').select2();

            $("#boxscroll").niceScroll({cursorborder:"",cursorcolor:"#cecece",boxzoom:true});
            $("#boxscroll2").niceScroll({cursorborder:"",cursorcolor:"#cecece",boxzoom:true}); 
            });

        </script>

@yield('scripts')
<script src="https://kit.fontawesome.com/c1e69a781a.js" crossorigin="anonymous"></script>
    </body>
</html>