@include('layouts.inc.pdfheader')
@php
$due_charges=0;
@endphp
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <h4>Profit Detail</h4>
        </div>
    </div>
    <div class="col-12">
        {{-- @if($from != "" && $to != "")
        <p>From : {{$from}} - To : {{$to}}</p>
        @elseif($from != "")
        <p> Date : {{$from}}</p>
        @endif --}}
    </div>
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">

                <table class="table" id="my-table">
                    <thead class="th-color">
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Product</th>
                            <th>Purchase Price</th>
                            <th>Sell Price</th>
                            <th>Profit</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($arr as $k => $item)
                        <tr>
                            <td> {{ $item['date'] }} </td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['product'] }}</td>
                            <td>{{ $item['purchase_price'] }}</td>
                            <td>{{ $item['price'] }}</td>
                            <td>{{ $item['profit'] }}</td>
                            <td>{{ $item['total'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>
        </div>
    </div>
</div>
<div style="text-align: right">
    <div class="mt-2" style="font-size: 10px">Powered by Diamond Software 03202565919</p>
</div>
<script>
    $(function(){
    // $("tbody").each(function(elem,index){
    //   var arr = $.makeArray($("tr",this).detach());
    //   arr.reverse();
    //     $(this).append(arr);
    // });

    window.print()
    setTimeout(function(){window.close()},1000);
});

</script>