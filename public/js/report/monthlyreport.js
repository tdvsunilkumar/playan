$(document).ready(function(){   
    datatablefunction();
    $("#btn_search").click(function(){
        datatablefunction();
        var month = $('#Month').val();
        $("#btn_download_spreadsheet").attr('href','/export-monthlycollection?month='+month);
    }); 
    $(".datepicker").flatpickr({
        altInput: true,
        dateFormat: "Y/m",
        altFormat: "Y/m",
        ariaDateFormat: "Y/m"
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
        "columnDefs": [{ orderable: false, targets: [0] }],
        "pageLength": 25,
        "ajax":{ 
            url :DIR+'reports-monthly-collection/getList', // json datasource
            type: "GET", 
            "data": {
                "q":$("#q").val(),
                'month':$('#Month').val(),
                "_token":$("#_csrf_token").val()
            }, 
            error: function(html){
            }
        },
        "columns": [
            { "data": "srno"},
            { "data": "date" },
            { "data": "or_no" },
            { "data": "busns_id_no"},
            { "data": "taxpayername" },
            { "data": "businessname" },
            { "data": "total_amount" }
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
