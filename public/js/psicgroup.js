$(document).ready(function(){
$("#Section").select3({ dropdownAutoWidth: false });
	select3Ajax("Section","this_is_filter","sectionAjaxList");
	$("#Division").select3({ dropdownAutoWidth: false });	
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
 	$('#Section').on('change', function() {
        var section_id =$(this).val();
        divisionId(section_id);
       $.ajax({
            url :DIR+'divisionAllData', // json datasource
            type: "POST", 
            data: {
                    "section_id": section_id,
                    "_token": $("#_csrf_token").val(),
                },
            success: function(html){
               $("#Division").html(html);       
                 }
        })
   });

});

function divisionId(section_id){
   $("#Division").select3({
    placeholder: 'Please Select',
    allowClear: true,
    dropdownParent: $("#this_is_filter").parent(),
    ajax: {
        url: DIR+'divisionAjaxList',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                "id": section_id, 
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
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
		"columnDefs": [{ orderable: false, targets: [0,6] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'psicgroup/getList', // json datasource
			type: "GET", 
			"data": {
				"Section":$("#Section").val(),
				"Division":$("#Division").val(),
				"q":$("#q").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
			{ "data": "srno" },
       	    { "data": "group_code" },
        	{ "data": "section_description" },
        	{ "data": "division_description" },
        	{ "data": "group_description" },
        	{ "data": "is_active" },
            { "data": "action" },
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        console.log(info);
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	       $("#common_pagesize").html(dropdown_html);
	        api.$('.deleterow').click(function() {
	            var recordid = $(this).attr('id');
	            DeleteRecord(recordid);
	        });

			api.$('.activeinactive').click(function() {
				var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
				 ActiveInactiveUpdate(recordid,is_activeinactive);
			});
	    }
		
	});  
}


// function DeleteRecord(id){
// 	// alert(id);
//         const swalWithBootstrapButtons = Swal.mixin({
//             customClass: {
//                 confirmButton: 'btn btn-success',
//                 cancelButton: 'btn btn-danger'
//             },
//             buttonsStyling: false
//         })
//         swalWithBootstrapButtons.fire({
//             title: 'Are you sure?',
//             text: "This action can not be undone. Do you want to continue?",
//             icon: 'warning',
//             showCancelButton: true,
//             confirmButtonText: 'Yes',
//             cancelButtonText: 'No',
//             reverseButtons: true
//         }).then((result) => {
//             if(result.isConfirmed
//             )
//             {
//                $.ajax({
// 			        url :DIR+'rptbuildingtype/delete', // json datasource
// 			        type: "POST", 
// 			        data: {
// 			          "id": id, 
// 			          "_token": $("#_csrf_token").val(),
// 			        },
// 			        success: function(html){
// 			        	Swal.fire({
// 	    				  position: 'center',
// 	    				  icon: 'success',
// 	    				  title: 'collector Deleted Successfully.',
// 	    				  showConfirmButton: false,
// 	    				  timer: 1500
// 	    				})
// 			           location.reload();
// 			        }
// 			    })
//             }
//         })
// }

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
			   url :DIR+'rptbuildingtype/ActiveInactive', // json datasource
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
