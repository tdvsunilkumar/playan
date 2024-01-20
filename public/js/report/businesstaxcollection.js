$(document).ready(function(){
    select3Ajax("barangayid","multiCollapseExample1","getBarangayAjax");
    $('#application_status').select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
    $('#status').select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
    // select3Ajax("flt_busn_office_barangay","this_is_filter","getBarngayLisByRptFlt");
    var yearpickerInput = $('input[name="search_year"]').val();
      $('.yearpicker').yearpicker();
      $('.yearpicker').val(yearpickerInput).trigger('change');
    datatablefunction();
    $("#btn_search").click(function(){
        datatablefunction();
    }); 
    $("#search_year").change(function(){
        datatablefunction();
    }); 
    $("#status").change(function(){
        datatablefunction();
    });
    $("#barangayid").change(function(){
        datatablefunction();
    });
    $("#application_status").change(function(){
        datatablefunction();
    });
    $(".add_health_cert").click(function(){
    console.log("zdcdsxxxxxxxxx");
    }); 
     
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
        "columnDefs": [{ orderable: false, targets: [0] }],
        "pageLength": 10,
        "ajax":{ 
            url :DIR+'business-tax-collection/getList', // json datasource
            type: "GET", 
            "data": {
                "pageTitle":$("#pageTitle").val(),
                "bbendo_id":$("#bbendo_id").val(),
                "q":$("#q").val(),
                'fromdate':$('#fromdate').val(),
                'todate':$('#todate').val(),
                'barangayid':$('#barangayid').val(),
                "year":$('#search_year').val(),
                "brgy":$("#flt_busn_office_barangay").val(),
                "status":$('#status').val(),
                "application_status":$('#application_status').val(),
                "_token":$("#_csrf_token").val()
            }, 
            error: function(html){
            }
        },
       "columns": [
            { "data": "srno" },
            { "data": "cashier_or_date" },
            { "data": "or_no" },
            { "data": "taxpayers_name" },
            { "data": "busns_id_no" },
            { "data": "busn_name" },
            { "data": "net_tax_due_amount" },
            { "data": "tax_credit_amount" }
            
        ],
        drawCallback: function(s){ 
            var api = this.api();
            var info=table.page.info();
            var dropdown_html=get_page_number(info.recordsTotal,info.length);
            $("#common_pagesize").html(dropdown_html);
            api.$('.print').click(function() {
                var id = $(this).attr('id');
                inspectionPrints(id);
            });
        }
    });  
}


function inspectionPrints(id){
              var id = id;
              $.ajax({
                url: DIR+'firePrint',
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

