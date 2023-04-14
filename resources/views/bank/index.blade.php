@extends('layouts.dashboard',['title'=>'View Vendors'])

@section('content')
<div class="row">
  <div class="col-6">
    <h4>Bank Detail</h4>

  </div>
  <div class="col-6">
    <button data-toggle="modal" data-target="#add_bank" class="my-1 btn btn-primary float-right">Add</button>

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
                {{-- <th>ID</th> --}}
                <th>Name</th>
                <th>Balance</th>
                <th>Detail</th>
                <th>Action</th>

              </tr>
            </thead>
            <tbody>
              {{-- @forelse ($bank as $item) --}}
              @for($i=0;$i<count($bank_data);$i++) <tr>
                {{-- <td>{{ $loop->index + 1 }}</td> --}}
                <td class="size">{{ $bank_data[$i]["bank_name"] }}</td>
                <td class="size">{{ $bank_data[$i]["total"] }}</td>
                <td class="size">{{ $bank_data[$i]["detail"] }}</td>
                <td width="30%">
                    <div class="d-flex text-center">
                        <a class="text-center" href="{{route('bank_detail')."?bank=".$bank_data[$i]["id"]}}">
                            <button class="btn btn-primary mr-2 ml-5 mt-2 mb-2">View</button>
                          </a>
                          <form action="{{ route('bank.destroy',$bank_data[$i]["id"]) }}" method="POST">
                          @csrf
                          @method('delete')
                          <a onclick="return confirm('Are you sure?')">
                            <button type="submit" class="btn btn-danger  mr-2 ml-2 mt-2 mb-2">Delete</button>
                          </a>
                          </form>
                          <a class="text-center" data-toggle="modal" data-target="#add_balance{{ $bank_data[$i]['id'] }}">
                            <button class="btn btn-success  mr-2 ml-2 mt-2 mb-2">Add Balance</button>
                          </a>
                    </div>


                </td>
                {{-- model --}}
                <div id="add_balance{{ $bank_data[$i]['id'] }}" class="modal fade" role="dialog">
                  <div class="modal-dialog">
                    <!-- Modal content-->

                    <div class="modal-content">
                      <div class="modal-header">
                        <h3 class="mb-0">Add Ledger</h3>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="col-lg-12 card-wrapper ct-example">
                          <!-- Styles -->
                          <div class="card">
                            <div class="card-body">
                              <form method="post" action="{{ route('add_bankbalance') }}">
                                @csrf

                                <input type="hidden" name="bank_id" value="{{ $bank_data[$i]['id']}}">
                                <div class="row">
                                  <div class="col-md-12">
                                    <label>Type</label>
                                  </div>
                                  <div class="col-md-12">
                                    <select name="type" required class="form-control">
                                      <option value="2">Credit</option>
                                      <option value="1">Debit</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    <label>Amount</label>
                                  </div>
                                  <div class="col-md-12">
                                    <input type="number" name="amount" class="form-control">
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    <label>Description</label>
                                  </div>
                                  <div class="col-md-12">
                                    <input type="text" name="description" class="form-control">
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
                                  <div class="col-md-12 mt-4">
                                    <button class="btn btn-success" type="submit">Add</button>
                                  </div>
                                </div>
                              </form>
                            </div>

                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
                {{-- model end --}}

                </tr>

                @endfor
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div> <!-- end col -->
</div> <!-- end row -->
<div id="add_bank" class="modal fade" role="dialog">
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
              <h3 class="mb-0">Add Bank</h3>
            </div>
            <div class="card-body">
              <form method="post" action="{{ route('bank.store') }}">
                @csrf

                <div class="row">
                  <div class="col-md-12">
                    <label>Name</label>
                  </div>
                  <div class="col-md-12">
                    <input required type="text" name="name" class="form-control">
                  </div>
                  <div class="col-md-12">
                    <label>Bank Detail</label>
                    <textarea class="form-control" name="detail"></textarea>
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