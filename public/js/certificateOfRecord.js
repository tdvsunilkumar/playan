$(document).ready(function(){
	var yearpickerInput = $('input[name="year"]').val();
      $('.yearpicker').yearpicker();
      $('.yearpicker').val(yearpickerInput).trigger('change');
    $("#allType").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1").css('display', 'block')});
    $("#rpc_cert_type").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1").css('display', 'block')});	
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
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
		"bSortable": true,
		"bDestroy": true,
		"searching": false,
		"order": [ 0, 'desc' ],
		"columnDefs": [{ orderable: false, targets: [0,10] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'rptpropertycertofrecord/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				"year":$("#year").val(),
				"rpc_cert_type":$("#rpc_cert_type").val(),
				"allType":$("#allType").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
			{ "data": "srno" },
			{ "data": "year" },
			{ "data": "rp_tax_declaration_no" },
        	{ "data": "ownername" },
        	{ "data": "requestor" },
        	{ "data": "assessor" },
        	{ "data": "remarks" },
        	{ "data": "type" },
        	{ "data": "or_no" },
        	{ "data": "date" },
        	{ "data": "action" }
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	       $("#common_pagesize").html(dropdown_html);
	        api.$('.deleterow').click(function() {
	            var recordid = $(this).attr('id');
	            DeleteRecord(recordid);
	        });
	        api.$('.print').click(function() {
	            var id = $(this).attr('id');
	            CertPholdingPrint(id);
	        });
	        api.$('.printNoLand').click(function() {
	            var id = $(this).attr('id');
	            CertPholdingPrintNoLand(id);
	        });
	        api.$('.printImprovemnt').click(function() {
	            var id = $(this).attr('id');
	            CertPholdingPrintImprovemnt(id);
	        });
			api.$('.activeinactive').click(function() {
				var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
				 ActiveInactiveUpdate(recordid,is_activeinactive);
	
			});
			api.$('.approveunapprove').click(function() {
				var recordid = $(this).attr('id');
				var is_approve = $(this).attr('value');
				 ApproveUnapproveUpdate(recordid,is_approve);
	
			});
	    }
	});  
	
}



function CertPholdingPrintNoLand(id){
              var id = id;
              $.ajax({
                url: DIR+'CertPholdingPrintNoLand',
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

function CertPholdingPrint(id){
              var id = id;
              $.ajax({
                url: DIR+'CertPholdingPrint',
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
function CertPholdingPrintImprovemnt(id){
              var id = id;
              $.ajax({
                url: DIR+'CertPholdingPrintImprovement',
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

function DeleteRecord(id){
	// alert(id);
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "This action can not be undone. Do you want to continue?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
            if(result.isConfirmed
            )
            {
               $.ajax({
			        url :DIR+'rptlandunitvalue/delete', // json datasource
			        type: "POST", 
			        data: {
			          "id": id, 
			          "_token": $("#_csrf_token").val(),
			        },
			        success: function(html){
			        	Swal.fire({
	    				  position: 'center',
	    				  icon: 'success',
	    				  title: 'property Subclass Deleted Successfully.',
	    				  showConfirmButton: false,
	    				  timer: 1500
	    				})
			           location.reload();
			        }
			    })
            }
        })
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
			   url :DIR+'rptlandunitvalue/ActiveInactive', // json datasource
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
			   }
		   })
	   }
   })
}

function ApproveUnapproveUpdate(id,is_approve){
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
	   text: "You wont to Approve/Unapprove?",
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   if(result.isConfirmed)
	   {
		  $.ajax({
			   url :DIR+'rptlandunitvalue/ApproveUnapprove', // json datasource
			   type: "POST", 
			   data: {
				 "id": id,
				 "is_approve": is_approve,  
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
			   }
		   })
	   }
   })
}
