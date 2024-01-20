$(document).ready(function(){	
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});
	$("#btn_download_spreadsheet").click(function(){
		var length_limit = $('#common_pagesize option:last-child').val();
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		window.location.href= DIR+"export-reportsmasterlists?from_date="+ from_date + "&to_date=" + to_date +"&length_limit=" + length_limit; 
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
		"columnDefs": [{ orderable: false, targets: [0,9,10,13,14,29] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'reportsmasterlists/getList', // json datasource
			type: "POST", 
			"data": {
				"q":$("#q").val(),
				"from_date":$("#from_date").val(),
                "to_date":$("#to_date").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
			{ "data": "srno" },
			{ "data": "busns_id_no" },
        	{ "data": "bpi_permit_no" },
        	{ "data": "busn_name" },
         	{ "data": "rpo_first_name" },
			{ "data": "rpo_middle_name" },
        	{ "data": "rpo_custom_last_name" },
        	{ "data": "suffix" },
			{ "data": "gender" },
        	{ "data": "location_address" },
			{ "data": "ownar_address" },
        	{ "data": "app_date" },
        	{ "data": "app_type" },
        	{ "data": "capital_investment" },
			{ "data": "busp_total_gross" },
        	{ "data": "payment_type" },
        	{ "data": "btype_desc" },
        	{ "data": "total_paid_surcharge" },
			{ "data": "total_paid_interest" },
        	{ "data": "total_paid_amount" },
        	{ "data": "or_no" },
        	{ "data": "cashier_or_date" },
			{ "data": "busn_tin_no" },
        	{ "data": "busn_registration_no" },
			{ "data": "busn_employee_no_male" },
        	{ "data": "busn_employee_no_female" },
        	{ "data": "busn_employee_total_no" },
        	{ "data": "p_mobile_no" },
			{ "data": "p_email_address" },
			{ "data": "nature_of_business" },
			{ "data": "bpi_remarks" },
			{ "data": "busn_plate_number" },
			{ "data": "busn_app_method" },
			{ "data": "bpi_issued_date" },
			{ "data": "busn_bldg_area" },
		    { "data": "busn_bldg_total_floor_area" }
        ],
        drawCallback: function(s){ 
	       var api = this.api();
            var info=table.page.info();
            var dropdown_html=get_page_number(info.recordsTotal,info.length);
            $("#common_pagesize").html(dropdown_html); 
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
			   url :DIR+'reportsmasterlists/ActiveInactive', // json datasource
			   type: "POST", 
			   data: {
				 "id": id,
				 "from_date":$("#from_date").val(),
                 "to_date":$("#to_date").val(),
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
