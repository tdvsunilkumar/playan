$(document).ready(function () {
	
	$("#btn_download_spreadsheet").click(function(){
		var q = $("#q").val();
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		window.location.href= DIR+"export-retiredbusiness?q="+ q + "&from_date=" + from_date + "&to_date=" + to_date; 
		
	});
	
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
        "columnDefs": [{ orderable: false, targets: [0,12] }],
        "pageLength": 10,
        "ajax": {
            url: DIR + 'retiredbusiness/getList', // json datasource
            type: "GET",
            "data": {
                "from_date": $("#from_date").val(),
				"to_date": $("#to_date").val(),
				"q": $("#q").val(),
                "_token": $("#_csrf_token").val()
            },
            error: function (html) {
            }
        },
        "columns": [
            { "data": "srno" },
            { "data": "busn_name" },
            { "data": "complete_address" },
            { "data": "retirement_for" },
            { "data": "business_line" },
			{ "data": "bin" },
			{ "data": "retire_reason_remarks" },
			{ "data": "retire_date_start"},
			{ "data": "established_date"},
			{ "data": "retire_date_closed"},
			{ "data": "owner_name"},
			{ "data": "p_mobile_no"},
			{ "data": "retire_application_type"}
            
        ],
        drawCallback: function (s) {
            var api = this.api();
            var info=table.page.info();
            var dropdown_html=get_page_number(info.recordsTotal,info.length);
            $("#common_pagesize").html(dropdown_html); 
        }
    });
}



