$(document).ready(function(){   
    datatablefunction();
    $("#btn_search").click(function(){
        datatablefunction();
    }); 
    $(document).on('click','.closeReqModal',function(){
          $('#viewdetails').modal('hide');
    }); 
    $("#subclass").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
});


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
        "columnDefs": [{ orderable: false, targets: [0,5] }],
        "pageLength": 25,
        "ajax":{ 
            url :DIR+'reports-composition-lgu-fees/getList', // json datasource
            type: "GET", 
            "data": {
                "q":$("#q").val(),
                'subclass':$('#subclass').val(),
                "_token":$("#_csrf_token").val()
            }, 
            error: function(html){
            }
        },
            "columns": [
                { "data": "srno", "className": "top-align-row" },
                { "data": "description", "className": "top-align-row" },
                { "data": "transaction", "className": "top-align-row" },
                { "data": "type", "className": "top-align-row" },
                { "data": "effectivitydate", "className": "top-align-row" },
                { "data": "cctype_desc", "className": "top-align-row" },
                { "data": "amount" }
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
        }
    });  
}

function viewdetails(id,department){
         $.ajax({
                    url :DIR+'reports-composition-lgu-fees/viewdetails', // json datasource
                    type: "POST", 
                    data: {
                      "id": id,
                      "department": department, 
                      "_token": $("#_csrf_token").val(),
                    },
                    success: function(html){
                        $('#viewdetails').modal('show');
                        arr = $.parseJSON(html);
                        $("#dynamicdetails").html(arr.dynamicdata);
                        $("#orno").html(arr.orno);
                        $("#taxpayername").html(arr.taxpayer);
                    }
                })
}