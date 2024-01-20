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
    $("#location").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
    $("#cemetery").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
	 
    $('.select_all').on('click', function(e){
        var id=$(this).attr('id_name');
        if(this.checked){
            $('#'+id+' tbody input[type=\"checkbox\"]:not(:checked)').trigger('click');
        } else {
            $('#'+id+' tbody input[type=\"checkbox\"]:checked').trigger('click');
        }
    });
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
            url :DIR+'cemetery-ar/getList', // json datasource
            type: "GET", 
            "data": {
                "q":$("#q").val(),
                'fromdate':$('#fromdate').val(),
                'todate':$('#todate').val(),
                'cemetery':$('#cemetery').val(),
                'location':$('#location').val(),
                "_token":$("#_csrf_token").val()
            }, 
            error: function(html){
            }
        },
        "columns": [
            { "data": "srno"},
            { "data": "transactionno" },
            { "data": "topno" },
            { "data": "orno" },
            { "data": "name" },
            { "data": "address" },
            { "data": "location" },
            { "data": "totalamt" },
            { "data": "remainingamt" },
            { "data": "oramount" },
            { "data": "status" },
            { "data": "action" }
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
                var id = $(this).attr('data-row-id');
                var code = $(this).attr('data-row-code');
                $("#itemNo").text('['+code+']');
                $('#viewdetails').modal('show');
                viewdetails(id,code);
            });  
        }
    });  
}

function viewdetails(id,code){
    var dropdown_html=get_page_number('1'); 
    var table = $('#Jq_summarydetails').DataTable({ 
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
        "columnDefs": [{ orderable: false, targets: [0] }],
        "pageLength": 10,
        "ajax":{ 
            url :DIR+'cemetery-ar/getpaymentlist', // json datasource
            type: "GET", 
            "data": {
                "q":$("#q").val(),
                "id":id,
                "_token":$("#_csrf_token").val()
            }, 
            error: function(html){
            }
        },
        "columns": [
            { "data": "srno"},
            { "data": "ordate" },
            { "data": "orno" },
            { "data": "amount" },
            { "data": "payment" },
            { "data": "balance" },
            { "data": "status" }
        ],
        
        drawCallback: function(s){ 
            var api = this.api();
            var info=table.page.info();
            var dropdown_html=get_page_number(info.recordsTotal,info.length);
            $("#common_pagesize").html(dropdown_html);
        }
    });  
}