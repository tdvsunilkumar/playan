$(document).ready(function () {
    datatablefunction();
	$("#year").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
    $("#btn_search").click(function () {
        datatablefunction();
    });
});

 function printapp(id) {
    var appliid = id;
    $.ajax({
        url: DIR + 'allaplicant/printapplication',
        type: 'POST',
        data: {
            "applicantid": appliid, "_token": $("#_csrf_token").val(),
        },
        success: function (data) {
            var url = data;
            window.open(url, '_blank');
        }
    });
} 



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
        "columnDefs": [{ orderable: false, targets: [0,9] }],
        "pageLength": 10,
        "ajax": {
            url: DIR + 'environmental-clearance/getList', // json datasource
            type: "GET",
            "data": {
                "year": $("#year").val(),
				"q": $("#q").val(),
                "_token": $("#_csrf_token").val()
            },
            error: function (html) {
            }
        },
        "columns": [
            { "data": "srno" },
            { "data": "ebac_app_year" },
            { "data": "busn_name" },
            { "data": "brgy_name" },
            { "data": "taxper" },
			{ "data": "ebac_date" },
            { "data": "ebac_issuance_date" },
			{ "data": "ebac_approved_by"},
			{ "data": "ebac_status"},
            { "data": "action" }
        ],
        drawCallback: function (s) {
            var api = this.api();
            var info = table.page.info();
            var dropdown_html = get_page_number(info.recordsTotal, info.length);
            $("#common_pagesize").html(dropdown_html);
            api.$('.print').click(function() {
                var id = $(this).attr('id');
                inspectionPrints(id);
            });
			
			api.$('.activeinactive').click(function() {
				var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
				 	ActiveInactiveUpdate(recordid,is_activeinactive);
			});
        }
    });
}




function inspectionPrints(id){
  var id = id;
  $.ajax({
	url: DIR+'environmental-clearance/printreportsss',
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

function ActiveInactiveUpdate(id,is_activeinactive){
   var msg = is_activeinactive==1?'restored':'removed';
   const swalWithBootstrapButtons = Swal.mixin({
	   customClass: {
		   confirmButton: 'btn btn-success',
		   cancelButton: 'btn btn-danger'
	   },
	   buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
	   title: 'Are you sure?',
	   text: "This record will be "+msg,
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   	if(result.isConfirmed){
		  	$.ajax({
			   url :DIR+'environmental-clearance/ActiveInactive', // json datasource
			   type: "POST", 
			   data: {
				 "id": id,
				 "is_activeinactive": is_activeinactive,  
				 "_token": $("#_csrf_token").val(),
			   },
			   success: function(html){
				   Swal.fire({
					 position: 'center',
					 icon: 'success',
					 title: 'Update Successfully.',
					 showConfirmButton: false,
					 timer: 1500
				   })
				   datatablefunction();
				   setInterval(function(){
				  
					  });
				   //location.reload();
			   }
		   })
	   }
   })
}
