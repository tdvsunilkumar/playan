$(document).ready(function(){	
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
});



function bploClearancePrint(id){
              var id = id;
              $.ajax({
                url: DIR+'bploappclearanceprint',
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
function PrintReport(id){
              var id = id;
              $.ajax({
                url: DIR+'enroinspectionreportprint',
                type: 'POST',
                data: {
                    "id": id, "_token": $("#_csrf_token").val(),
                },
                success: function (data) {
                   var url = data;
                    window.open(url, '_blank');
                }
              });
}

function GenerateReport(id){
        var id = id;
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
                url: DIR+'bploappclearancereport',
                type: 'POST',
                data: {
                    "id": id, "_token": $("#_csrf_token").val(),
                },
                success: function (data) {
                  Swal.fire({
	    				  position: 'center',
	    				  icon: 'success',
	    				  title: 'Report generated successfully.',
	    				  showConfirmButton: false,
	    				  timer: 1500
	    				})
			           location.reload();
                    
                }
              });
            }
        })
             
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
		"columnDefs": [{ orderable: false, targets: [0,6] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'rptpropertyactualuse/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				'year':$('#yeardate').val(),
				'approve':$('#aproved').val(),
				'crdate':$('#datecreated1').val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
        "columns": [
        	{ "data": "srno"},
        	{ "data": "pc_class_codeDetails" },
			{ "data": "pau_actual_use_code" },
        	{ "data": "pau_actual_use_desc" },
        	{ "data": "pau_with_land_stripping" },
            { "data": "is_active" },
            { "data": "action" }
        ],
        
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	        $("#common_pagesize").html(dropdown_html);
	         api.$('.print').click(function() {
	            var id = $(this).attr('id');
	            bploClearancePrint(id);
	        });
	         api.$('.report').click(function() {
	            var id = $(this).attr('id');
	            GenerateReport(id);
	        });
	         api.$('.printreport').click(function() {
	            var id = $(this).attr('id');
	            PrintReport(id);
	        }); 
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
			   url :DIR+'rptpropertyactualuse/ActiveInactive', // json datasource
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
