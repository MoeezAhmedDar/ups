@extends('layouts.dashboard',['title'=>'View Ledgers'])

@section('content')
@include('layouts.inc.newpdfheader')
    @php
        $due_charges=0;
    @endphp
<div class="row">
    <div class="col-12">
        <div class="card-header">
        <h4>Ledger Detail PDF</h4>
        </div>
    </div>
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
                <input type="hidden" value="{{$id}}" id="vendor" />
                <input type="hidden" value="{{$from}}" id="from" />
                <input type="hidden" value="{{$to}}" id="to" />
                {{-- <form action="{{route('view_ledger',$id)}}"> --}}
                    
                {{-- </form> --}}
                <hr>
                <div class="table-bordered">
                <table class="table table-striped text-center table-hover table-bordered" id="my-table">
                    <thead class="th-color">
                        <tr>
                            {{-- <th>ID</th> --}}
                            <th>Date</th>
                            <th>Details</th>
                            <th>Payment Type</th>
                            
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Amount</th>
                           
                            </tr>
                    </thead>
                    <tbody id="result">
                        
                    </tbody>
                </table>
                </div>
                {{-- <div class="row mb-2 mt-4">
            <div class="col-md-2 mt-2">
            <label>Due Amount :<label>
            </div>
            <div class="col-md-2 mt-2">
            <label class="{{$due_charges < 0 ? "text-danger" : "text-success"}}" id="cal_amount"><b>{{ $due_charges }}</b><label>
            </div>
            <div class="col-md-2 mt-2">
                <label>Total Amount :<label>
                </div>
                <div class="col-md-2 mt-2">
                <label class="{{$total_amount < 0 ? "text-danger" : "text-success"}}" id="cal_amount"><b>{{ $total_amount }}</b><label>
                </div>
        </div> --}}
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<script>
   
    function converHTMLFileToPDF() {
        window.html2canvas = html2canvas;
	const { jsPDF } = window.jspdf;
	var doc = new jsPDF('l', 'mm', [1500, 1210]);

	var pdfjs = document.querySelector('#my-table');

	// Convert HTML to PDF in JavaScript
	doc.html(pdfjs, {
		callback: function(doc) {
			doc.save("output.pdf");
		},
		x: 5,
		y: 10
	});
   // window.location.href = "/dashboard'";
}
    
    getLedgerdata();
   
  function getLedgerdata(){
    $.ajax({
        method: "GET",
        url: "{{route('get_Ledgerdata')}}",
        data: {vendor : $('#vendor').val(),from:$('#from').val(),"to":$("#to").val()},
        dataType: "json",
        success:function(dataa){
            if(dataa.data.length > 0){
                let data = dataa.data;
                let html = ``;
                let temp = null;
                for(let i=0; i<data.length;i++)
                {
                    let payment_type = "Cash";

                        if (data[i].payment_type == 1){
                            payment_type = "Cheque";
                        }
                        else if(data[i].payment_type == 2){
                            payment_type = "Bank Deposit";
                        }
                    if(temp != data[i].created_at)
                    {
                        if(data[i].ledger_detail.length > 0)
                        {
                            for(let a = 0; a<data[i].ledger_detail.length;a++)
                            {
                                let ledger_detail = data[i].ledger_detail;
                             
                                let row = ledger_detail.length > 1 ? ledger_detail.length : null;
                                html+=`<tr>`;
                                    if(a == 0){
                                    html+=`<td rowspan ="${row}">${a > 0 ? "//" : data[i].created_date}</td>
                                    <td rowspan ="${row}"></td>
                                    <td rowspan ="${row}">${a > 0 ? "" : payment_type} (${a > 0 ? "" : data[i].bank == null ? "None" : data[i].bank})</td>`;
                                    }
                                   
                                    html+=`<td>${ledger_detail[a].lproduct}</td>
                                    <td>${ledger_detail[a].lqty}</td>
                                    <td>${ledger_detail[a].lprice}</td>`;
                                    if(a == 0){
                                        html+=`<td rowspan ="${row}">${i < data.length && data[i].created_at == data[i+1].created_at ?  -data[i].amount : data[i].amount}</td>`;
                                    }
                                    
                                
                                    html+=`</tr>`; 
                            }
                        
                        }else{
                            html+=`<tr>
                                    <td>${data[i].created_date}</td>
                                    <td>${data[i].detail}</td>
                                    <td>${payment_type} (${data[i].bank == null ? "" : data[i].bank})</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>${data[i].amount}</td>
                                
                                    </tr>`; 
                        }
                    }
                    temp = data[i].created_at;
                }
                //console.log(html);
                $('#result').html(html);
                converHTMLFileToPDF();

            }
        }

    })
  }
</script>
@endsection

