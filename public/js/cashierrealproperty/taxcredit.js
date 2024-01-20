$(document).ready(function(){   
    datatablefunction();
    $("#btn_search").click(function(){
        datatablefunction();
    }); 
    $(document).on('click','.closeReqModal',function(){
          $('#viewdetails').modal('hide');
    }); 
    $("#businessids").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
    $(document).on('change','#businessids',function(){
        datatablefunction();
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
        "columnDefs": [{ orderable: false, targets: [0,12,15] }],
        "pageLength": 25,
        "ajax":{ 
            url :DIR+'rpt-tax-credit-file/getList', // json datasource
            type: "GET", 
            "data": {
                "q":$("#q").val(),
                'fromdate':$('#fromdate').val(),
                'todate':$('#todate').val(),
                'businessid':$('#businessids').val(),
                "_token":$("#_csrf_token").val()
            }, 
            error: function(html){
            }
        },
        "columns": [
            { "data": "srno"},
            { "data": "tdno"},
            { "data": "taxpayername" },
            { "data": "brgy" },
            { "data": "prop_type" },
            { "data": "area" },
            { "data": "assessed_value" },
            { "data": "top_no" },
            { "data": "or_no" },
            { "data": "total_amount" },
            { "data": "date" },
            { "data": "credit_amount" },
            { "data": "description" },
            { "data": "payment_type" },
            { "data": "status" },
            { "data": "details" }
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
                var previouscashierid = $(this).attr('previouscashierid');
                 viewdetails(id,previouscashierid);
    
            });  
        }
    });  
}

function viewdetails(id,previouscashierid){
         $.ajax({
                    url :DIR+'rpt-tax-credit-file/viewdetails', // json datasource
                    type: "POST", 
                    data: {
                      "id": id,
                      "precashid": previouscashierid, 
                      "_token": $("#_csrf_token").val(),
                    },
                    success: function(html){
                        $('#viewdetails').modal('show');
                        $('#viewDetailsBody').html('').html(html);
                        /*arr = $.parseJSON(html);
                        $("#reforno").val(arr.reforno);
                        $("#oramount").val(arr.oramount);
                        $("#ordate").val(arr.ordate);
                        $("#chartofaccount").val(arr.chartofaccount);
                        $("#cashier").val(arr.cashier);
                        $("#paymenttype").val('Excess of '+arr.type);
                        $("#from").val(arr.from);
                        $("#to").val(arr.to);
                        if(arr.precashid > 0){ $("#currentapplieddetail").show();
                            $("#currentorno").val(arr.currentreforno);
                            $("#currentcreditamt").val(arr.currentoramount);
                            $("#currentordate").val(arr.currentordate);
                            $("#currentchartofaccount").val(arr.currentchartofaccount);
                            $("#currentcashier").val(arr.currentcashier); 
                        }else{ $("#currentapplieddetail").hide();}*/
                    }
                })
}