@extends('layouts.dashboard',['title'=>'View Vendors'])

@section('content')
<div class="row">
    <div class="col-6">
        <h4>{{$type == 1 ? "Customers" : "Vendors"}}</h4>
    </div>
    <div class="col-6">
        @if ($type == 1)
        <a href="{{route('vendor.index')}}?c&pdf"><button class="btn btn-success float-right">PDF</button></a>
        @else
        <a href="{{route('vendor.index')}}?pdf"><button class="btn btn-success float-right">PDF</button></a>
        @endif
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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Balance</th>
                            <th>Edit</th>
                            <th>Delete</th>
                            <th>View / Add Ledger</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @forelse ($vendors as $item) --}}
                        @for($i=0;$i<count($data_arr);$i++)
                        <tr>
                            <td>{{ $a = $i+1; }}</td>
                            <td class="size">{{ $data_arr[$i]["name"] }}</td>
                            <td class="size">{{ $data_arr[$i]["phone"] }}</td>
                            <td class="size">{{ $data_arr[$i]["address"] }}</td>
                            <td class="size">{{ $data_arr[$i]["balance"] }}</td>
                            <td>
                                <a href="{{ route('vendor.edit',$data_arr[$i]['id']) }}">
                                    <button class="btn btn-success">Edit</button>
                                </a>
                            </td>
                            <td>
                                <form action="{{ route('vendor.destroy',$data_arr[$i]['id']) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <a onclick="return confirm('Are you sure?')" >
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </a>
                                </form>
                            </td>
                            <td>
                                @if($type == 0)
                                <a data-toggle="modal" data-target="#add_incentive{{ $data_arr[$i]['id'] }}">
                                    <button class="btn btn-info">Incentive</button>
                                </a>
                                @endif
                                <a href="{{ route($view_ledger_route,['id'=>$data_arr[$i]['id']]) }}">
                                    <button class="btn btn-success">View</button>
                                </a>
                                <a data-toggle="modal" data-target="#add_ledger{{ $data_arr[$i]['id'] }}">
                                    <button class="btn btn-success">$</button>
                                </a>
     <div id="add_ledger{{ $data_arr[$i]['id'] }}" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
          <div class="col-lg-12 card-wrapper ct-example">
          <!-- Styles -->
          <div class="card">
          <div class="card-header">
                <h3 class="mb-0">Add Ledger</h3>
            </div>
            <div class="card-body">
            <form method="post" action="{{ route('insert_ledger') }}" >
            @csrf
            <input type="hidden" value="{{$type}}" name="check"/>
            <input type="hidden" name="vendor_id" value="{{ $data_arr[$i]['id'] }}" >
            <div class="row">      
      <div class="col-md-12">
        <label>Amount</label>
      </div>
      <div class="col-md-12">
      <input  required type="number" name="amount" class="form-control">
      </div>
      </div>
      <div class="row">      
      <div class="col-md-12">
        <label>Description</label>
      </div>
      <div class="col-md-12">
      <input required type="text" name="description"  class="form-control">
      </div>
      </div>
      <div class="row">
              
              <div class="col-md-12">
             <span class="text-danger">
              @error('description')
                   {{ $message }}
             @enderror
              </span>
             </div>
           </div>
      <div class="row">
      <div class="col-md-12">
        <label>Type</label>
      </div>
      <div class="col-md-12">
      <select required class="form-control" name="type">
      <option value="">Select</option>
      <option value="0">Debit </option>
      <option value="1">Credit</option>
     
      </select>
      </div>
      <div class="col-md-12 mt-2">
                <label>Payment Type</label>
        <select onchange="checkpaymenttype(this.value,{{ $data_arr[$i]['id'] }})" id="payment_type" class="form-control" name="payment_type" required>
            <option value="0">Cash</option>
            <option value="1">Cheque</option>
            <option value="2">Bank Deposit</option>
        </select>
        </div>
        <div style="display: none;" id="bank_section{{ $data_arr[$i]['id'] }}" class="col-md-12">
            <label>BANK</label>
            <select name="bank" class="form-control">
                <option value="">Select Bank</option>
                @foreach ($banks as $item)
                    <option value="{{$item->id}}">{{$item->bank_name}}</option>
                @endforeach
            </select>
         
        </div>
      </div>

      <div class="col-md-12 mt-4">
      <button class="btn btn-success" type="submit">Add</button> 
      </div>
</form>
      </div>
      
      </div>
      </div>
      </div>
      
    </div>
  </div>
</div>
{{-- add incentive --}}
<div id="add_incentive{{ $data_arr[$i]['id'] }}" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      
      <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="col-lg-12 card-wrapper ct-example">
            <!-- Styles -->
            <div class="card">
            <div class="card-header">
                  <h3 class="mb-0">Add Incentive</h3>
              </div>
              <div class="card-body">
              <form method="post" action="{{ route('addincentive') }}" >
              @csrf
              <input type="hidden" value="{{$type}}" name="check"/>
              <input type="hidden" name="vendor_id" value="{{ $data_arr[$i]['id'] }}" >
                <div class="col-md-12">
                    <label>Amount</label>
                    <input required type="number" class="form-control" name="incentiveamount"/>
                </div>
                <div class="col-md-12">
                    <label>Detail</label>
                   <textarea class="form-control" name="detail" required></textarea>
                </div>
        <div class="col-md-12 mt-4">
        <button class="btn btn-success" type="submit">Add</button> 
        </div>
  </form>
        </div>
        
        </div>
        </div>
        </div>
        
      </div>
    </div>
  </div>
                            </td>
                        </tr>
                    
                        @endfor
                        {{-- @endforelse --}}

                    </tbody>
                </table>
                   </div>
                {{-- {{ $vendors->links() }} --}}
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
<script>
      function checkpaymenttype(value,id){
    $('#bank_section'+id).hide();
    $('#bank_section'+id+' > select').attr('required',false);
    $('#bank_section'+id+' > select').val('');
    if(value != 0){
        $('#bank_section'+id).show();
        $('#bank_section'+id+' > select').attr('required',true);
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
