@include('../layouts.inc.pdfheader')
<div class="row">
    <div class="col-12">
        <div class="card-header">
        <h4>Sales Detail</h4>
        </div>
    </div>
    <div class="col-12">
        @if($from != "" && $to != "")
        <p>From : {{$from}} - To : {{$to}}</p>
        @elseif($from != "")
        <p> Date : {{$from}}</p>
        @endif
    </div>
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">

                <table class="table table-bordered table-striped" id="my-table">
                    <thead class="th-color">
                        <tr>
                            <th>S/No</th>
                            <th>Invoice No</th>
                            <th>Customer Name</th>
                            <th>Total Amount</th>
                            <th>Total Quantity</th>
                            <th>Discount</th>
                            <th>Paid Amount</th>
                            <th>Date Time</th>
                       

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invoices as $item)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name == null ? "Walking Customer" : $item->name }}</td>
                            <td>{{ $item->total_amount }}</td>
                            <td>{{ $item->total_qty }}</td>
                            <td>{{ $item->discount }}</td>
                            <td>{{ $item->paid_amount }}</td>
                            
                            <td>{{ $item->created_at }}</td>
                     
                            
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
<div style="text-align: right">
    <div class="mt-2" style="font-size: 10px">Powered by Diamond Software 03202565919</p>
</div>