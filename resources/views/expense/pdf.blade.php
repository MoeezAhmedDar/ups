@include('layouts.inc.pdfheader_flex')
@php
$due_charges=0;
@endphp
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <h4>Income & Expense Detail</h4>
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
                        <tr>

                            <th>Date</th>
                            <th>Vendor/Customer</th>
                            <th>Details</th>
                            <th>Payment Type</th>

                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Amount</th>
                            <th>Credit</th>
                            <th>Debit</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                    //    $total_expense = $total_exp;
                        $temp = null;
                        for($i=0; $i<count($data);$i++)
                        {
              
                            $payment_type = "Cash";

                        if ($data[$i]["payment_type"] == 1){
                            $payment_type = "Cheque";
                        }
                        else if($data[$i]["payment_type"] == 2){
                            $payment_type = "Bank Deposit";
                        }
                   
                        // if($i!=0){
                        //     $total_expense = $data[$i-1]['detail_hidden'] == 'TRANSFER' ? $total_expense : 
                        //     ($data[$i-1]['credit'] > 0 ? $total_expense - $data[$i-1]['credit'] : $total_expense + $data[$i-1]['debit']);
                        // }

                        if(count($data[$i]["ledger_detail"]) > 0)
                        {
                           
                            for($a = 0; $a<count($data[$i]["ledger_detail"]);$a++)
                            {
                                $ledger_detail = $data[$i]["ledger_detail"];
                             
                                $row = count($ledger_detail) > 1 ? count($ledger_detail) : null;
                               echo '<tr>';
                                    if($a == 0){
                                        if($a > 0){
                                            echo '<td rowspa ="'.$row.'">-</td>';
                                        }else{
                                            echo '<td rowspa ="'.$row.'">'.$data[$i]["created_date"].'</td>';
                                        }
                                    
                                        echo '<td rowspa ="'.$row.'">'.$data[$i]['vname'].'</td>';
                                        echo '<td rowspa ="'.$row.'">'.$data[$i]['detail'].'</td>';
                                        if($data[$i]["bank"] == null){
                                            echo '<td rowspa ="'.$row.'">'.$payment_type.'</td>';
                                        }else{
                                            echo '<td rowspa ="'.$row.'">'.$payment_type.' /'.$data[$i]["type"].'</td>';
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
                                    <td>'.$ledger_detail[$a]["lprice"].'</td>
                                    <td>'.$ledger_detail[$a]["lqty"] * $ledger_detail[$a]["lprice"].'</td>';
                                    if($a == 0){
                                      
                                        if($data[$i]["credit"] > 0){
                                            echo '<td rowspa ="'.$row.'">'.$data[$i]["credit"].'</td>';
                                            echo '<td rowspa ="'.$row.'">0</td>';
                                        }else{
                                            echo '<td rowspa ="'.$row.'">0</td>';
                                            echo '<td rowspa ="'.$row.'">'.$data[$i]["debit"].'</td>';
                                           
                                        }
                                        echo '<td rowspa="'.$row.'">'.$data[$i]['total_expense'].'</td>';
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
                            $amount = $data[$i]["credit"] > 0 ? $data[$i]["credit"] : $data[$i]["debit"];
                            echo '<tr>
                                    <td>'.$data[$i]["created_date"].'</td>
                                    <td>'.$data[$i]['vname'].'</td>
                                    <td>'.$data[$i]["detail"].'</td>';
                                    if($data[$i]["bank"] == null){
                                        echo '<td>'.$payment_type.'</td>';
                                    }else{
                                        echo '<td>'.$payment_type." /".$data[$i]["type"].'</td>';
                                    }
                                 
                                   echo '<td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>'. $amount .'</td>
                                    <td>'.$data[$i]["credit"].'</td>
                                    <td>'.$data[$i]["debit"].'</td>
                                    <td>'.$data[$i]['total_expense'].'</td>
                                    </tr>'; 
                        }
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