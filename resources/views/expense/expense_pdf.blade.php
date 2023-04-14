@include('layouts.inc.pdfheader')


<div class="row">
    
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="row">
                   <div class="col-12 text-center">
                    <h4>Expense Detail PDF</h4>
                   </div>
                        <div class="col-12">
                            @if($from != "" && $to != "")
                            <p>From : {{$from}} - To : {{$to}}</p>
                            @elseif($from != "")
                            <p> Date : {{$from}}</p>
                            @endif
                        </div>
             
                    
                   </div>

                   <br>
                
                   <div class="table-responsive">
                <table class="table table-bordered table-striped text-center table-hover" id="datatable">
                    <thead class="th-color">
                        <tr>
                            <th>NO</th>
                            <th>Date</th>
                            <th>Detail</th>
                            <th>Payment Type</th>
                        
                            <th>Amount</th>
                            <th>Total</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php
                           $total = $exp_total; 
                        @endphp
                        @forelse ($expenses as $item)
                       
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td style="width: 120px;">{{$item->created_at}}</td>
                            <td class="size">{{ $item->detail }}</td>
                            <td>
                                @if ($item->payment_type == 1)
                                Cheque ({{$item->bank_name == null ? "None" : $item->bank_name}})
                                @elseif($item->payment_type == 2)
                                Bank Deposit ({{$item->bank_name == null ? "None" : $item->bank_name}})
                                @else
                                Cash
                            @endif
                            </td>
                            
                            <td>{{ $item->debit }}</td>
                            <td class="size">{{ $total }}</td>
                        </tr>
                        @php
                        $total -= $item->debit; 
                        @endphp
                        @empty
                        <tr>
                            <td colspan="6">No data</td>
                        </tr>
                    
                        @endforelse
                    </tbody>
                </table>
                {{-- {{ $expenses->links() }} --}}
                   </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

