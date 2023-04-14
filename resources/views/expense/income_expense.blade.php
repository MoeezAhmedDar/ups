@extends('layouts.dashboard',['title'=>'View Ledgers'])

@section('content')
    @php
        $due_charges=0;
    @endphp
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="row">
                <div class="col-2">

                    <h4>Income & Expense Detail</h4>
                </div>
                <div class="col-10">
                    <div class="row">
                    
                        <div class="col-3">
                            <label>From</label>
                            <input class="form-control" id="from" name="from" type="date"/> 
                        </div>
                        <div class="col-3">
                            <label>To</label>
                            <input  class="form-control" id="to" name="to" type="date"/> 
                        </div>
                        <div class="col-3">
                            <br>
                            <button type="button" onclick="getLedgerdata()" class="btn btn-primary mt-2">Search</button>
                            <button type="button" class="btn btn-danger mt-2">Reset</button>
                            <button type="button" onclick="redirect()" name="pdf" class="btn btn-success mt-2">PDF</button>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
              
                
                   
                
                <div class="table-bordered">
                <table class="table table-striped text-center table-hover table-bordered" id="datatable">
                    <thead class="th-color">
                        <tr>
                            {{-- <th>ID</th> --}}
                            <th>Date</th>
                            <th>Customer/Vendor</th>
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
                    <tbody id="result">
                        
                    </tbody>
                </table>
                </div>
  
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<script>
     function redirect(){
        window.open('{{route("generateincomeexpense_pdf")}}?from='+$('#from').val()+'&to='+$('#to').val());
    }
    getLedgerdata();
  function getLedgerdata(){
    $.ajax({
        method: "GET",
        url: "{{route('get_Expensedata')}}",
        data: {from:$('#from').val(),"to":$("#to").val()},
        dataType: "json",
        success:function(dataa){
            if(dataa.data.length > 0){
                let total_expense = parseInt(dataa.total_exp);
                //alert(total_expense);
                let data = dataa.data;
                let html = ``;
                let temp = null;
                //console.log(data[0].credit);
                for(let i=0; i<data.length;i++)
                {
                    
                    let payment_type = "Cash";

                        if (data[i].payment_type == 1){
                            payment_type = "Cheque";
                        }
                        else if(data[i].payment_type == 2){
                            payment_type = "Bank Deposit";
                        }

                        if(i!=0){
                            total_expense = data[i-1].detail_hidden == 'TRANSFER' ? total_expense : 
                            (data[i-1].credit > 0 ? total_expense - data[i-1].credit : parseInt(total_expense) + parseInt(data[i-1].debit));
                        }
                 
                        if(data[i].ledger_detail.length > 0)
                        {
                            let vname = "";
                            for(let a = 0; a<data[i].ledger_detail.length;a++)
                            {
                                let ledger_detail = data[i].ledger_detail;
                             
                                let row = ledger_detail.length > 1 ? ledger_detail.length : null;
                                vname = data[i].vname ?? data[i]?.invoice?.customer_name ?? data[i].detail_hidden;
                                vname = vname == 'Expense Bank' ? 'Expense' : vname;
                                html+=`<tr>`;
                                    if(a == 0){
                                    html+=`<td rowspan ="${row}">${a > 0 ? "//" : data[i].created_date}</td>
                                    <td rowspan ="${row}">${vname}</td>
                                    <td rowspan ="${row}"></td>
                                    <td rowspan ="${row}">${a > 0 ? "" : payment_type} (${a > 0 ? "" : data[i].bank_name == null ? "None" : data[i].bank_name})</td>`;
                                    }
                                   
                                    html+=`<td>${ledger_detail[a].lproduct}</td>
                                    <td>${ledger_detail[a].lqty}</td>
                                    <td>${ledger_detail[a].lprice}</td>
                                    <td>${parseInt(ledger_detail[a].lqty) * parseInt(ledger_detail[a].lprice)}</td>`;
                                    if(a == 0){
                                        html+=`<td rowspan ="${row}">${data[i].credit > 0 ? data[i].credit : 0}</td>`;
                                        html+=`<td rowspan ="${row}">${data[i].debit > 0 ? data[i].debit : 0}</td>`;
                                        html+=`<td rowspan ="${row}">${total_expense}</td>`;
                                    }
                                    
                                
                                    html+=`</tr>`; 
                            }
                        
                        }else{
                            html+=`<tr>
                                    <td>${data[i].created_date}</td>
                                    <td>${data[i].vname || data[i]?.invoice?.customer_name || data[i].detail_hidden || "-"}</td>
                                    <td>${data[i].detail}</td>
                                    <td>${payment_type} (${data[i].bank_name == null ? "None" : data[i].bank_name})</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>${data[i].credit > 0 ? data[i].credit : data[i].debit}</td>
                                    <td>${data[i].credit}</td>
                                    <td>${data[i].debit}</td>
                                    <td>${total_expense}</td>
                                    </tr>`; 
                        }
                }
                //console.log(html);
                $('#result').html(html);

            }
        }

    })
  }

  $('body').on('change','select[name="datatable_length"]',function(){
    getLedgerdata();
  })
</script>
@endsection

