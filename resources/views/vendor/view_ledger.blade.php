@extends('layouts.dashboard',['title'=>'View Ledgers'])

@section('content')
@php
$due_charges=0;
@endphp
<style>
    td {
        margin: 0px !important;
        padding: 0px !important;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="row">
                <div class="col-2">
                    @if (is_null($vendorObj))
                    <h4>Ledger Detail</h4>
                    @else
                    <h4>
                        {{ $vendorObj->type == '1' ? 'Customer ' : 'Vendor ' }} Detail
                        {{$vendor_name ? "(".$vendor_name.")" : ""}}
                    </h4>
                    @endif
                </div>
                <div class="col-10">
                    <input type="hidden" value="{{$id}}" id="vendor" />
                    <form>
                        <div class="row">
                            <div class="col-3">
                                <label>From</label>
                                <input value="{{$from}}" class="form-control" id="from" name="from" type="date" />
                            </div>
                            <div class="col-3">
                                <label>To</label>
                                <input value="{{$to}}" class="form-control" id="to" name="to" type="date" />
                            </div>
                            @if($id == 0)
                            <div class="col-3">
                                <label>Client</label>
                                <select class="form-control" id="vendor_id" name="vendor_id">
                                    <option value="">Select Vendor</option>
                                    @foreach ($vendors as $v)
                                    <option {{ request()->get('vendor_id')==$v->id ? 'selected' :'' }}
                                        value="{{ $v->id }}">
                                        {{ $v->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-3">
                                <br>

                                <button type="submit" onclick="getLedgerdata()"
                                    class="btn btn-primary mt-2">Search</button>
                                {{-- <a href="{{route('view_ledger',$id)}}"> --}}
                                <a class="text-white" href="{{ route('view_ledger',"0") }}"><button type="button"
                                        class="btn btn-danger mt-2">Reset</button></a>
                                {{-- </a> --}}
                                {{-- <form action="route('view_ledger')"> --}}
                                {{-- <button onclick="converHTMLFileToPDF()" type="button" name="pdf" class="btn btn-success mt-2">PDF</button> --}}
                                <button onclick="redirect()" type="button" name="pdf"
                                    class="btn btn-success mt-2">PDF</button>
                                {{-- </form> --}}
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="table-bordered">
                    <table class="table table-striped text-center table-hover table-bordered" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                {{-- @if($id == 0) --}}
                                <th>Vendor/Customer</th>
                                {{-- @endif --}}
                                <th>Details</th>
                                <th>Payment Type</th>

                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Credit</th>
                                <th>Debit</th>
                                <th>Total</th>

                            </tr>
                        </thead>
                        <tbody>
                            {{-- {{ dd($data) }} --}}
                            <?php
                       
                        $temp = null;
                        for($i=0; $i<count($data);$i++)
                        {
                            $TYPE = $data[$i]['full'] == '2' ? 'Partial' : ($data[$i]['full'] == '1' ? 'Paid' : ($data[$i]['full'] == '4' ? '' : 'Unpaid'));
                            $payment_type = "Cash";

                        if ($data[$i]["payment_type"] == 1){
                            $payment_type = "Cheque";
                        }
                        else if($data[$i]["payment_type"] == 2){
                            $payment_type = "Bank Deposit";
                        }
                    // if($temp != $data[$i]["created_at"])
                    // {

                        $color = '';
                        if ($data[$i]['return'] == 1) {
                            $color = 'red';
                        }
                        elseif ($data[$i]['full'] == 1) {
                            $color = 'black';
                        }
                        elseif($data[$i]['full'] == 2){
                            $color = 'blue';
                            
                        }
                        elseif($data[$i]['full'] == 0){
                            $color = 'green';

                        }
                       
                        if(count($data[$i]["ledger_detail"]) > 0)
                        {
                           
                            for($a = 0; $a<count($data[$i]["ledger_detail"]);$a++)
                            {
                                $ledger_detail = $data[$i]["ledger_detail"];
                             
                                $row = count($ledger_detail) > 1 ? count($ledger_detail) : null;
                               echo '<tr style="color:'. $color.'">
                                <td>'.($data[$i]['ledger_id']).'</td>';
                                if($a == 0){
                                    echo '<td rowspa ="'.$row.'">'.$data[$i]["created_date"].'</td>
                                    <td rowspa ="'.$row.'">'.$data[$i]["vname"].'</td>';
                                    echo '<td>'.$data[$i]["detail"].'</td>';
                                    
                                    if($data[$i]["bank"] == null){
                                        echo '<td rowspa ="'.$row.'">'.$payment_type. ' /'.$TYPE.'</td>';
                                    }else{
                                        echo '<td rowspa ="'.$row.'">'.$payment_type.' /'.$TYPE.'</td>';
                                    }
                                }else{
                                        echo '
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        ';
                                    }
                                    
                                    echo '<td>'.$ledger_detail[$a]["lproduct"].'</td>
                                    <td>'.$ledger_detail[$a]["lqty"].'</td>
                                    <td>'.$ledger_detail[$a]["lprice"].'</td>';
                                    if($a == 0){
                                        if($data[$i]["amount"] > 0){
                                            echo '<td>'.$data[$i]["amount"].'</td>';
                                        }else{
                                            echo '<td>0</td>';  
                                        }

                                        if($data[$i]["amount"] < 0){
                                            echo '<td>'.$data[$i]["amount"].'</td>';
                                        }else{
                                            echo '<td>0</td>';  
                                        }
                                        echo '
                                         <td>'.$data[$i]['total_amount'].'</td>
                                    ';     
                                    }else{
                                        echo '
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        ';
                                    }
                                    
                                
                                    echo '</tr>'; 
                            }
                        
                        }else{
                            echo '<tr style="color:'. $color.'">
                                <td>'.($data[$i]['ledger_id']).'</td>
                                    <td>'.$data[$i]["created_date"].'</td>
                                    <td>'.$data[$i]["vname"].'</td>
                                    <td>'.$data[$i]["detail"].'</td>
                                    ';
                                    if($data[$i]["bank"] == null){
                                        echo '<td>'.$payment_type. ' /'.$TYPE.'</td>';
                                    }else{
                                        echo '<td>'.$payment_type. ' /'.$TYPE.'</td>';
                                    }
                                 
                                   echo '
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>';
                                    if($data[$i]["amount"] > 0){
                                        echo '<td>'.$data[$i]["amount"].'</td>';
                                    }else{
                                        echo '<td>0</td>';  
                                    }
                                    if($data[$i]["amount"] < 0){
                                        echo '<td>'.$data[$i]["amount"].'</td>';
                                    }else{
                                        echo '<td>0</td>';  
                                    }
                                  echo '<td>'.$data[$i]['total_amount'].'</td>
                                    </tr>'; 
                                
                        }
                   // }
                    //$temp = $data[$i]["created_at"];
                    // $amount = $data[$i]["amount"] < 0 ? -($data[$i]["amount"]) : $data[$i]["amount"];
                //  
                //    $total_amount =$data[$i]["amount"] > 0 ? $total_amount - $amount : $total_amount + $amount;
                }
                 
                   
       ?>

                        </tbody>
                    </table>
                </div>
                {{-- <div class="row mb-2 mt-4">
            <div class="col-md-2 mt-2">
            <label>Due Amount :<label>
            </div>
            <div class="col-md-2 mt-2">
            <label class="{{$due_charges < 0 ? "text-danger" : "text-success"}}"
                id="cal_amount"><b>{{ $due_charges }}</b><label>
            </div>
            <div class="col-md-2 mt-2">
                <label>Total Amount :<label>
            </div>
            <div class="col-md-2 mt-2">
                <label class="{{$total_amount < 0 ? "text-danger" : "text-success"}}"
                    id="cal_amount"><b>{{ $total_amount }}</b><label>
            </div>
        </div> --}}
    </div>
</div>
</div> <!-- end col -->
</div> <!-- end row -->

<script>
    function redirect(){
        window.open('{{ $index_customer_flag ? route("gneratedpdfview_customer") : route("gneratedpdfview")}}?id='+$('#vendor').val()+'&from='+$('#from').val()+'&to='+$('#to').val(), "_blank");
    }
//     function converHTMLFileToPDF() {
//         window.html2canvas = html2canvas;
// 	const { jsPDF } = window.jspdf;
// 	var doc = new jsPDF('l', 'mm', [1500, 1210]);

// 	var pdfjs = document.querySelector('#my-table');

// 	// Convert HTML to PDF in JavaScript
// 	doc.html(pdfjs, {
// 		callback: function(doc) {
// 			doc.save("output.pdf");
// 		},
// 		x: 5,
// 		y: 10
// 	});
// }
//     function generatepdf(){
//         window.jsPDF = window.jspdf.jsPDF;
//         var doc = new jsPDF();
// var elementHandler = {
//   '#ignorePDF': function (element, renderer) {
//     return true;
//   }
// };
// var source = document.querySelector('#my-table');
// // doc.html(
// //     source,
// //     15,
// //     15,
// //     {
// //       'width': 180,'elementHandlers': elementHandler
// //     });
// // Convert HTML to PDF in JavaScript
// doc.html(source, {
// 		callback: function(doc) {
// 			doc.save("output.pdf");
// 		},
// 		x: 10,
// 		y: 10
// 	});

// doc.output("dataurlnewwindow");
//     }
    // getLedgerdata();
//   function getLedgerdata(){
//     $.ajax({
//         method: "GET",
//         url: "{{route('get_Ledgerdata')}}",
//         data: {vendor : $('#vendor').val(),from:$('#from').val(),"to":$("#to").val()},
//         dataType: "json",
//         success:function(dataa){
//             if(dataa.data.length > 0){
//                 let data = dataa.data;
//                 let html = ``;
//                 let temp = null;
//                 let total_amount = parseInt(dataa.total_amount);
//                 let id=dataa.id;
//                 for(let i=0; i<data.length;i++)
//                 {
//                     let payment_type = "Cash";

//                         if (data[i].payment_type == 1){
//                             payment_type = "Cheque";
//                         }
//                         else if(data[i].payment_type == 2){
//                             payment_type = "Bank Deposit";
//                         }
//                         let color = "";
//                         if(data[i].full == 1){
//                             color = "primary";
//                         }else if(data[i].amount < 0){
//                             color = "success";
//                         }else if(data[i].return == 1){
//                             color = "danger";
//                         }
//                     // if(temp != data[i].created_at)
//                     // {
//                         if(data[i].ledger_detail.length > 0)
//                         {
//                            // console.log(data[i].detail);
//                             for(let a = 0; a<data[i].ledger_detail.length;a++)
//                             {
//                                 let ledger_detail = data[i].ledger_detail;

//                                 let row = ledger_detail.length > 1 ? ledger_detail.length : null;
//                                 html+=`<tr class="text-${color}">`;
//                                     if(a == 0){
//                                     html+=`<td rowspan ="${row}">${a > 0 ? "//" : data[i].created_date}</td>`;
//                                         if(id == 0){
//                                             html+=`<td rowspan ="${row}">${data[i].vname}</td>`;
//                                         }
//                                     html+=`<td rowspan ="${row}">${data[i].return == 1 ? "return" : data[i].detail}</td>
//                                     <td rowspan ="${row}">${a > 0 ? "" : payment_type} ${data[i].full ==2 ? '(Partial Payment)' : ''}` ;
//                                     }

//                                     html+=`<td>${ledger_detail[a].lproduct}</td>
//                                     <td>${ledger_detail[a].lqty}</td>
//                                     <td>${ledger_detail[a].lprice}</td>`;
//                                     if(a == 0){
//                                         html+=`<td rowspan ="${row}">${data[i].amount > 0 ? data[i].amount : 0}</td>`;
//                                         html+=`<td rowspan ="${row}">${data[i].amount <  0 ? data[i].amount : 0}</td>`;
//                                         html+=`<td class="text-${color}" rowspan ="${row}">${total_amount}</td>`;
//                                     }


//                                     html+=`</tr>`;
//                             }

//                         }else{
//                             console.log(data[i].detail);
//                             html+=`<tr class="text-${color}">
//                                     <td>${data[i].created_date}</td>`;
//                                     if(id == 0){
//                                             html+=`<td>${data[i].vname}</td>`;
//                                         }
//                                     html+=`<td>${data[i].detail}</td>
//                                     <td>${payment_type} ${data[i].bank == null ? "" : (data[i].bank)}</td>
//                                     <td>-</td>
//                                     <td>-</td>
//                                     <td>-</td>
//                                     <td>${data[i].amount > 0 ? data[i].amount : 0}</td>
//                                     <td>${data[i].amount < 0 ? data[i].amount : 0}</td>
//                                     <td class="text-${color}">${total_amount}</td>

//                                     </tr>`;
//                         }
//                    // }
//                    // temp = data[i].created_at;
//                    let amount = parseInt(data[i].amount) < 0 ? -(parseInt(data[i].amount)) : parseInt(data[i].amount);
//                    //console.log(amount);
//                    total_amount =parseInt(data[i].amount) > 0 ? total_amount - amount : total_amount + amount;
//                 }
//                 //console.log(html);
//                 $('#result').html(html);

//             }
//         }

//     })
//   }
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
    "order": [[ 0, "desc" ]],
    'columnDefs': [
        { 'sortable': true, 'searchable': false, 'visible': false, 'type': 'num', 'targets': [0] }
    ],
    "bLengthChange": false,
    "bPaginate": true,
    "bFilter": false,
    "bInfo": false,
    "pageLength": 15
});
</script>
@endsection