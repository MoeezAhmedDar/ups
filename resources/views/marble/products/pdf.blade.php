@include('../layouts.inc.pdfheader')

    <div class="row">
        <div class="col-md-12">
            <div class="card bg-white m-b-30">
                <div class="card-body new-user">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="th-color">
                                <tr>
                                    <th class="border-top-0">No</th>
                                    <th class="border-top-0">Category</th>
                                    <th class="border-top-0">Company</th>
                                    <th class="border-top-0">Product name</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product as $k=>$prod)     
                                <tr>
                                <td> {{ ++$k }} </td>    
                                <td> {{ $prod->Category->name }} </td>   
                                <td> {{ $prod->Subcategory->name }} </td>    
                                <td> {{ $prod->name }} </td>    
                         
                                </tr>                                
                                @endforeach
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    


      


    </body>
</html>
    
