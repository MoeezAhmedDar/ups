@extends('layouts.dashboard',['title'=>'Expense'])

@section('content')
<div class="row">
    <div class="col-2">
        <h4>Expense Detail</h4>
    </div>
    <div class="col-8">
        <form action="{{route('expense.index')}}" method="get">
            <div class="row">

                <div class="col-3">
                    <label>From</label>
                    <input class="form-control" id="from" name="from" type="date" />
                </div>
                <div class="col-3">
                    <label>To</label>
                    <input class="form-control" id="to" name="to" type="date" />
                </div>
                <div class="col-3">
                    <br>
                    <button class="btn btn-primary mt-2">Search</button>

                    <button name="pdf" class="btn btn-success mt-2">PDF</button>
                </div>

            </div>
        </form>
    </div>
    <div class="col-2 text-right">
        <button data-toggle="modal" data-target="#addexpense" type="button" class="my-1 btn btn-primary float-right">Add
            Expense</button>

    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center table-hover" id="datatable1">
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

<div class="modal" id="addexpense">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Add Expense</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form method="post" action="{{route('expense.store')}}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label>Enter Amount</label>
                            <input required type="number" class="form-control" name="amount" />
                        </div>
                        <div class="col-md-12">
                            <label>Enter Detail</label>
                            <textarea required class="form-control" name="detail"></textarea>
                            <input id="detail_hidden" type="hidden" name="detail_hidden" value="Expense">
                        </div>
                        <div class="col-md-12 mt-2">
                            <label>Payment Type</label>
                            <select onchange="checkpaymenttype(this.value)" id="payment_type" class="form-control"
                                name="payment_type" required>
                                <option value="0">Cash</option>
                                <option value="1">Cheque</option>
                                <option value="2">Bank Deposit</option>
                            </select>
                        </div>
                        <div style="display: none;" id="bank_section" class="col-md-12">
                            <label>BANK</label>
                            <select name="bank" class="form-control">
                                <option value="">Select Bank</option>
                                @foreach ($banks as $item)
                                <option value="{{$item->id}}">{{$item->bank_name}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-md-12">

                            <button class="btn btn-primary mt-3">Add</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<script>
    function checkpaymenttype(value){
  $('#bank_section').hide();
  if(value != 0){
        $('#bank_section').show();
        $('#detail_hidden').val('Expense');
    }else{
        $('#detail_hidden').val('Expense')
    }
}
</script>
@endsection


@section('scripts')
<style>
    .dataTables_paginate {
        display: block
    }
</style>
<script>
    $('#datatable1').DataTable({
    "bSort": true,
    "bLengthChange": true,
    "bPaginate": true,
    "bFilter": true,
    "bInfo": true,

});
</script>
@endsection
