@include('layouts.inc.pdfheader')
<h4>{{$type == 1 ? "Customers" : "Vendors"}}</h4>
<div class="row">
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover text-center" id="my-table">
                    <thead class="th-color">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_arr as $item)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['phone'] }}</td>
                            <td>{{ $item['address'] }}</td>
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
