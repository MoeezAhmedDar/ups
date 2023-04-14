@include('layouts.inc.pdfheader')

<div class="row">
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
                <table class="table table-bordered table-striped " id="my-table">
                    <thead class="th-color">
                        <tr>
                            <th>No</th>
                            <th>Company</th>
                            <th>Product</th>
                            <th>Quantity </th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        @forelse ($stocks as $item)
                       
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $item->company_name }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->total_qty }}</td>
                          
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">No data</td>
                        </tr>
                        @endforelse
                    </tbody>
                  
                </table>
                
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

