$(document).ready(function(){ 

	$("#btn_download_spreadsheet").click(function(){
		var department = $("#department").val();
		var fromdate = $("#fromdate").val();
		var todate = $("#todate").val();
		window.location.href= DIR+"export-departmentalcollection?fromdate="+ fromdate + "&todate=" + todate +"&department="+ department; 
	});
	
	
    datatablefunction();
    $("#btn_search").click(function(){
        datatablefunction();
    });
		
    $(document).on('click','.closeReqModal',function(){
          $('#viewdetails').modal('hide');
    }); 
    $("#department").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
	 
});

function BploBusinessPermitPrint(id){
              var id = id;
              $.ajax({
                url: DIR+'/bplobusinesspermitPrint',
                type: 'POST',
                data: {
                    "id": id, "_token": $("#_csrf_token").val(),
                },
                success: function (data) {
                   var url = data;
                   console.log(url);
                    window.open(url, '_blank');
                }
              });
}

function datatablefunction()
{
    if($("#department").val() == '2'){
        $("#busiheading").text('Tax Declartion No');
    }else{
        $("#busiheading").text('Business Permit');
    }
    var dropdown_html=get_page_number('1'); 
    var table = $('#Jq_datatablelist').DataTable({ 
        "language": {
            "infoFiltered":"",
            "processing": "<img src='"+DIR+"public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
        },
        dom: "<'row'<'col-sm-12'f>>" +"<'row'<'col-sm-3'l><'col-sm-9'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-12'p>>",
            oLanguage: {
                sLengthMenu: dropdown_html
        },
        "bProcessing": true,
        "serverSide": true,
        "bDestroy": true,
        "searching": false,
        "order": [],
        "columnDefs": [{ orderable: false, targets: [0,10] }],
        "pageLength": 10,
        "ajax":{ 
            url :DIR+'reports-departmental-collection/getList', // json datasource
            type: "GET", 
            "data": {
                "q":$("#q").val(),
                'fromdate':$('#fromdate').val(),
                'todate':$('#todate').val(),
                'department':$('#department').val(),
                "_token":$("#_csrf_token").val()
            }, 
            error: function(html){
            }
        },
        "columns": [
            { "data": "srno"},
            { "data": "taxpayername" },
            { "data": "businessname" },
            { "data": "tdno" },
            { "data": "perticulars" },
            { "data": "ortype" },
            { "data": "topno" },
            { "data": "or_no" },
            { "data": "date" },
            { "data": "total_amount" },
            { "data": "details" },
            { "data": "status" },
            { "data": "cashier" }
        ],
        
        drawCallback: function(s){ 
            var api = this.api();
            var info=table.page.info();
            var dropdown_html=get_page_number(info.recordsTotal,info.length);
            $("#common_pagesize").html(dropdown_html);
             api.$('.print').click(function() {
                var id = $(this).attr('id');
                BploBusinessPermitPrint(id);
            });
           api.$('.viewdetails').click(function() {
                var id = $(this).attr('id');
                var department = $(this).attr('department');
                var payeetype = $(this).attr('payeetype');
                var userid = $(this).attr('userid');
                 var itemNo = $(this).attr('itemNo');
                 viewdetails(id,department,payeetype,userid,itemNo);
    
            });  
        }
    });  
}

function viewdetails(id,department,payeetype,userid,itemNo){

     $.ajax({
		url :DIR+'reports-departmental-collection/viewdetails', // json datasource
		type: "POST", 
		data: {
		  "id": id,
		  "department": department, 
		  "payeetype": payeetype,
		  "userid": userid,
		  "_token": $("#_csrf_token").val(),
		},
		success: function(html){
			$('#viewdetails').modal('show');
			arr = $.parseJSON(html);
			$("#dynamicdetails").html(arr.dynamicdata);
			$("#orno").html(arr.orno);
			$("#taxpayername").html(arr.taxpayer);
            $("#itemNo").html(itemNo);
		}
	})
}