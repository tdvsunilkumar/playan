$(document).ready(function () {
	
	$("#btn_download_spreadsheet").click(function(){
		var year = $("#year").val();
		var length = $('#common_pagesize option:last').val();
		window.location.href= DIR+"export-total-of-business-per-barangay?year="+ year +"&length=" + length; 
	});
	
	$("#year").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
    datatablefunction();
    $("#btn_search").click(function () {
        datatablefunction();
    });
});

function datatablefunction() {
    var dropdown_html = get_page_number('1');
    var table = $('#Jq_datatablelist').DataTable({
        "language": {
            "infoFiltered": "",
            "processing": "<img src='" + DIR + "public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
        },
        dom: "<'row'<'col-sm-12'f>>" + "<'row'<'col-sm-3'l><'col-sm-9'p>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12'p>>",
        oLanguage: {
            sLengthMenu: dropdown_html
        },
        "bProcessing": true,
        "serverSide": true,
        "bDestroy": true,
        "searching": false,
        "order": [],
        "columnDefs": [{ orderable: false, targets: [0,4] }],
        "pageLength": 10,
        "ajax": {
            url: DIR + 'reporttotalofbusines/getList', // json datasource
            type: "GET",
            "data": {
                "q": $("#q").val(),
                "year":$("#year").val(),
                "_token": $("#_csrf_token").val()
            },
            error: function (html) {
            }
        },
        "columns": [
            { "data": "srno" },
            { "data": "barangay" },
            { "data": "new_application" },
            { "data": "renewal" },
            { "data": "total" }
            
        ],
        drawCallback: function (s) {
            var api = this.api();
            var info=table.page.info();
            var dropdown_html=get_page_number(info.recordsTotal,info.length);
            $("#common_pagesize").html(dropdown_html);
        }
    });
}

