$(document).ready(function () {
	
	$("#btn_download_spreadsheet").click(function(){
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		window.location.href= DIR+"export-listof-business-per-barangay?from_date="+ from_date + "&to_date=" + to_date; 
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
        "columnDefs": [{ orderable: false, targets: [0,10] }],
        "pageLength": 10,
        "ajax": {
            url: DIR + 'reportsmasterlistslistofbusines/getList', // json datasource
            type: "GET",
            "data": {
                "q": $("#q").val(),
				'from_date':$('#from_date').val(),
                'to_date':$('#to_date').val(),
                "_token": $("#_csrf_token").val()
            },
            error: function (html) {
            }
        },
        "columns": [
            { "data": "srno" },
            { "data": "busns_id_no" },
            { "data": "taxpayer_name" },
            { "data": "busn_name" },
            { "data": "location_address" },
			{ "data": "app_type_id" },
			{ "data": "bpi_issued_date"},
			{ "data": "mode_of_payment"},
			{ "data": "total_assessment"},
			{ "data": "amount_paid"},
			{ "data": "remarks"}
            
        ],
        drawCallback: function (s) {
            var api = this.api();
            var info=table.page.info();
            var dropdown_html=get_page_number(info.recordsTotal,info.length);
            $("#common_pagesize").html(dropdown_html); 
        }
    });
}



