@include('layouts.inc.pdfheader_flex')
@php
$due_charges=0;
@endphp

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <h4>{{$vendor_name == null ? "Vendors" : $vendor_name}} Ledger Detail</h4>
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

                <table class="table" id="my-table">
                    <thead class="th-color">
                        <tr style="text-align: center">
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
                        if($data[$i]['full'] == 1){
                            $color = "success";
                                            }elseif($data[$i]['amount'] > 0){
                                                $color ='danger';
                                            }else if($data[$i]['amount'] <= 0){
                                                $color = "danger";
                                            }
                                            if($data[$i]['return'] == 1){
                                                $color = "primary";
                                            }
                                        
                                            if(count($data[$i]["ledger_detail"]) > 0)
                                            {
                                            
                                                for($a = 0; $a<count($data[$i]["ledger_detail"]);$a++)
                                                {
                                                    $ledger_detail = $data[$i]["ledger_detail"];
                                                
                                                    $row = count($ledger_detail) > 1 ? count($ledger_detail) : null;
                                                echo '<tr class="text-'.$color.'">
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
                                                echo '<tr  class="text-'.$color.'">
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



                <br>
                <br>
                <h4 class="heading">Authorize Signature ___________________</h4>
                <p style="text-align:right;margin-right:2px;">superupscenter@gmail.com</p>


                <div class="body-section body-section1">
                    <p style="text-align: center;">Thank You For Your Business
                    </p>
                </div>



            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
<div style="text-align: right">
    <p  class="mt-2" style="font-size: 10px">Powered by Diamond Software 03202565919</p>
</div>
<script>
    //  $('#datatable1').DataTable({
    //     "order": [[ 0, "desc" ]],
    //     'columnDefs': [
    //         { 'sortable': true, 'searchable': false, 'visible': false, 'type': 'num', 'targets': [0] }
    //     ],
    //     "bLengthChange": false,
    //     "bPaginate": false,
    //     "bFilter": false,
    //     "bInfo": false,
    //     "pageLength": 100000000000
    // });
    $(function(){
    // $("tbody").each(function(elem,index){
    //   var arr = $.makeArray($("tr",this).detach());
    //   arr.reverse();
    //     $(this).append(arr);
    // });
   

    window.print();
    setTimeout(function(){window.close()},1000);
});

</script>
<?php 
//die();
?>