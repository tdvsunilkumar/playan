$(document).ready(function(){
    var yearpickerInput = $('input[name="year"]').val();
      $('.yearpicker').yearpicker();
      $('.yearpicker').val(yearpickerInput).trigger('change');
    $('#commonModal').modal({backdrop: 'static', keyboard: false});
    datatablefunction();
    $("#quarter").select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
    $("#btn_search").click(function(){
        datatablefunction();
    });
    $('#rptPropertySearchByText').on('keyup',function(){
        datatablefunction();
    });

    $('#rptPropertySearchByBarangy').on('change',function(e){
        if($(this).val() != ''){
            $('.addNewProperty').attr('hidden',false);
        }else{
            $('.addNewProperty').attr('hidden',true);
        }
        datatablefunction();
    });
    $("#btn_download_spreadsheet").click(function(){
        var length_limit = $('#common_pagesize option:last-child').val();
        window.location.href= DIR+"export-reportsmasterlists?length_limit=" + length_limit; 
    });
    /*$('#quarter').on('change',function(e){
        datatablefunction();
    });*/

})
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
      url :DIR+'report-on-real-property/getList', // json datasource
      type: "GET", 
      "data": {
        "q":$("#rptPropertySearchByText").val(),
        'qtr':$('#quarter').val(),
        'year':$('#year').val(),
                "_token":$("#_csrf_token").val()
        }, 
      error: function(html){
      }
    },
        "columns": [
            { "data": "no" },
            { "data": "class" },
            { "data": "units" },
            { "data": "landarea" , "className": "align-right" },
            { "data": "land_market_value" , "className": "align-right" },
            { "data": "build_market_value_less" , "className": "align-right" },
            { "data": "build_market_value_above" , "className": "align-right" },
            { "data": "machine_market_value" , "className": "align-right" },
            { "data": "other_mvalue" , "className": "align-right" },
            { "data": "total_market_value" , "className": "align-right" },
            { "data": "land_assess_value" , "className": "align-right" },
            { "data": "build_assess_value_less" , "className": "align-right" },
            { "data": "build_assess_value_above" , "className": "align-right" },
            { "data": "machine_assess_value" , "className": "align-right" },
            { "data": "other_avalue" , "className": "align-right" },
            { "data": "total_assess_value" , "className": "align-right" },
            
            /*{ "data": "other"}*/
        ],
      drawCallback: function(s){ 
          var api = this.api();
          var info=table.page.info();
          var dropdown_html=get_page_number(info.recordsTotal,info.length);
          $("#common_pagesize").html(dropdown_html);
            api.$('.print').click(function() {
              var rowid = $(this).attr('id');
              grosssaleReceipt(rowid);
            });
           
            api.$(".showLess2").shorten({
                "showChars" : 2,
                "moreText"    : "More",
                "lessText"    : "Less",
            });
        
      }
    

  });  
}






