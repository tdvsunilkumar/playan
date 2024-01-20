$(document).ready(function(){	
	datatablefunction();
	$("#departmentnew").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
	$("#division").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
	$("#btn_search").click(function(){
 		datatablefunction();
 	});

	//  $("#reload-btn").click(function(){
	// 	$.ajax({
    //         url :'/hr-timecard/refresh', // json datasource
    //         type: "POST", 
    //         data: {
	// 			"fromdate":$("#fromdate").val(),
	// 			"todate":$("#todate").val(),
    //             "_token":$("#_csrf_token").val()
	// 		},
    //         dataType: 'json',
    //         success: function(html){
    //             if(html.ESTATUS){

    //             }else{
	// 				datatablefunction();
    //             }
    //         }
    //     })
	// });
});

function datatablefunction()
{
	var dropdown_html=get_page_number('1'); 
	var url = $('#Jq_datatablelist').data('url');
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
		"columnDefs": [{ orderable: false, targets: [0,1] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+url, // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				"department":$("#departmentnew").val(),
				"division":$("#division").val(),
				"hr_employeesid":$("#hr_employeesid").val(),
				"fromdate":$("#fromdate").val(),
				"todate":$("#todate").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
            { "data": "srno" },
			{ "data": "userid" },
			{ "data": "employeename" },
			{ "data": "department" },
			{ "data": "division" },
        	{ "data": "hrtc_date" },
        	{ "data": "schedule" },
        	{ "data": "hrtc_time_in" },
        	{ "data": "hrtc_time_out" },
        	{ "data": "hrtc_late" },
        	{ "data": "hrtc_undertime" },
        	// { "data": "hrtc_ot" },
        	{ "data": "holiday" },
        	{ "data": "action" }
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	         $("#common_pagesize").html(dropdown_html);
			api.$('.activeinactive').click(function() {
				var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
				 ActiveInactiveUpdate(recordid,is_activeinactive);
	
			});
	    }
	});  
}

function ActiveInactiveUpdate(id,is_activeinactive){
	// alert(id);
	// alert(is_activeinactive);
   const swalWithBootstrapButtons = Swal.mixin({
	   customClass: {
		   confirmButton: 'btn btn-success',
		   cancelButton: 'btn btn-danger'
	   },
	   buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
	   title: 'Are you sure?',
	   text: "You want to Active/InActive?",
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   if(result.isConfirmed)
	   {
		  $.ajax({
			   url :DIR+'hr-timecard/ActiveInactive', // json datasource
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
