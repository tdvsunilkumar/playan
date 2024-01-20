$(document).ready(function(){	

	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
 	$('#rptPropertySearchByAlphabet').change(function(){
 		datatablefunction();
 	});

 	$(".showTaxDeclarationsDetails").unbind("click");
	$(document).on('click','.showTaxDeclarationsDetails',function(){
		var url = $(this).data('url');
        var title1 = 'Summary Of Real Property';
        var title2 = 'Summary Of Real Property';
        var title = (title1 != undefined) ? title1 : title2;
        var size = 'xl';
        $("#taxDeclarationSummary .modal-title").html(title);
        $("#taxDeclarationSummary .modal-dialog").addClass('modal-' + size);
        
        showLoader();
    $.ajax({
        url: url,
        success: function (data) {
        	hideLoader();
        	$('#taxDeclarationSummary .body').html("");
            $('#taxDeclarationSummary .body').html(data);
            $("#taxDeclarationSummary").modal('show');
            var selectedLandAppraisals = $('#summaryOfTaxDeclarations').find("tbody tr .selectedTdForHistory:checkbox:checked");
            if(selectedLandAppraisals.length != 0){
                var selectedLandAppraisalid        = selectedLandAppraisals.val();
                var selectedTdNo = selectedLandAppraisals.closest('tr').find('.property_kind').text();  
	            $('.tax-declaration-history-of').text('Tax Declaration History['+selectedTdNo+']'); 
                loadHistory(selectedLandAppraisalid);
            }
            taskCheckbox();
            //common_bind("#addPropertyOwnerModal");
            commonLoader();
        },
        error: function (data) {
        	hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });

	});

 	$(".addNewPropertyOwner").unbind("click");
	$(document).on('click','.addNewPropertyOwner',function(){
		var url = $(this).data('url');
        var title1 = 'Manage Property Owner';
        var title2 = 'Manage Property Owner';
        var title = (title1 != undefined) ? title1 : title2;
        var size = 'xl';
        $("#addPropertyOwnerModal .modal-title").html(title);
        $("#addPropertyOwnerModal .modal-dialog").addClass('modal-' + size);
        
        showLoader();
    $.ajax({
        url: url,
        success: function (data) {
        	hideLoader();
        	$('#addPropertyOwnerModal .body').html("");
            $('#addPropertyOwnerModal .body').html(data);
            $("#addPropertyOwnerModal").modal('show');
            setTimeout(function(){ 
		    	$("#p_barangay_id_no").select3({dropdownParent : '#p_barangay_id_no_div'});
		    	$("#country").select3({dropdownParent : '#country_div'});
			        }, 500);
            taskCheckbox();
            //common_bind("#addPropertyOwnerModal");
            commonLoader();
        },
        error: function (data) {
        	hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });

	});
    $("#storePropertyOwnerForm").unbind("submit");
	$(document).off('submit','#storePropertyOwnerForm').on('submit','#storePropertyOwnerForm',function(e){
		showLoader();
		e.preventDefault();
		var url =  $('#addPropertyOwnerModal').find('form').attr('action');
		var method = $('#addPropertyOwnerModal').find('form').attr('method');
		var data   = $('#addPropertyOwnerModal').find('form').serialize();
		$.ajax({
	    type: "POST",
	    url: url,
	    data: data,
	    dataType: "json",
	    success: function(html){ 
	    	hideLoader();
	    	if(html.status == 'success'){
	    		$('#addPropertyOwnerModal').modal('hide');
	    		Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    });
                datatablefunction();
	    		//loadPropertyOwners();
	    	}
	    },error:function(){
	    	hideLoader();
	    }
	});

	});

	$(document).on('click', '.selectedTdForHistory', function() {   
	  var selectedTdNo = $(this).closest('tr').find('.property_kind').text();  
	  $('.tax-declaration-history-of').text('Tax Declaration History['+selectedTdNo+']'); 
      $('.selectedTdForHistory').not(this).prop('checked', false); 
      var rpPropertyCode = $(this).val();
      loadHistory(rpPropertyCode);
         
});
});


function loadHistory(rpPropertyCode) {
	showLoader();  
	var url = DIR+'rptop/loadHistory';
      var data = {
      	id:rpPropertyCode,
      	"_token":$("#_csrf_token").val()
      };
      $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#loadHistoryHere').html(html);
        },error:function(){
            hideLoader();
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
         "bStateSave": true,
		"bProcessing": true,
		"serverSide": true,
		"bDestroy": true,
		"searching": false,
		"order": [[1,'desc']],
		"columnDefs": [{ orderable: false, targets: [0,5] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'rptop/getlist', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				"alphabet":$('#rptPropertySearchByAlphabet').val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
       	    { "data": "no" },
        	{ "data": "rpt_owner" },
        	{ "data": "rpo_address_house_lot_no" },
        	{ "data": "p_mobile_no" },
        	{ "data": "email" },
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
	        api.$('.activeinactive').click(function() {
				var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
				 ActiveInactiveUpdate(recordid,is_activeinactive);
	
			});
	    }
	});  
}

function DeleteRecord(id){
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
			        url :DIR+'rptpropertyowner/delete', // json datasource
			        type: "POST", 
			        data: {
			          "id": id, 
			          "_token": $("#_csrf_token").val(),
			        },
			        success: function(html){
			        	Swal.fire({
	    				  position: 'center',
	    				  icon: 'success',
	    				  title: 'Business Tax Deleted Successfully.',
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
			   url :DIR+'rptpropertyowner/ActiveInactive', // json datasource
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

