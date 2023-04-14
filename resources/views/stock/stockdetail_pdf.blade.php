@include('layouts.inc.pdfheader')
<div class="row">

    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">

                <table class="table table-bordered table-striped" id="datatable">
                    <thead class="th-color">
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Vendor</th>
                            <th>Purchased Price</th>
                            <th>Sale Price</th>
                            <th>Quantity Received</th>
                            <th>Quantity Remaining</th>
                            <th>Total Cost</th>
                            <th>Date</th>
                         

                        </tr>
                    </thead>
                    <tbody>
                        <?php $total_qty = 0;
                         $total_rqty = 0;?>
                        @forelse ($stocks as $item)
                        <?php $total_qty+=$item->qty;
                            $total_rqty+=$item->qty_received;?>
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->vendor->name }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->sale_price }}</td>
                            <td>{{ $item->qty_received }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->total }}</td>
                            <td>{{ $item->date }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">No data</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($product_id != null)
                    <tfoot>
                        <th colspan="2">Total Received Quantity</th>
                        <td colspan="2">{{$total_rqty}}</td>
                        <th colspan="2">Total Availble Quantity</th>
                        <td colspan="2">{{$total_qty}}</td>
                    </tfoot>
                    @endif
                </table>
   
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
