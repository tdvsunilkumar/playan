$(document).ready(function(){
	
    $("#uploadAttachment").click(function(){
 		uploadAttachment();
    });	
    $(".deleteDocument").click(function(){
 		deleteDocument($(this));
    })
	select3Ajax("barangay_id","select-brgy","getBarngayList");
	$('form#citizen-send-json').find('.modal-footer').prepend('<input type="submit" type="submit"  value="Submit" class="btn btn-primary">');
	$("#bday_form").change(function(){
		var today = new Date();
		var birthDate = new Date(this.value);
		var age = today.getFullYear() - birthDate.getFullYear();
		var m = today.getMonth() - birthDate.getMonth();
		if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
			age--;
		}
		$("#age_form").val(age);
	});
	$('body').on('keyup', '#PhilhealthNo', function(){
		$(this).val($(this).val().match(/\d+/g).join("").replace(/(\d{2})\-?(\d{9})\-?(\d{1})/,'$1-$2-$3'))
	});
	$('body').on('keyup', '#TinNo', function(){
		$(this).val($(this).val().match(/\d+/g).join("").replace(/(\d{3})\-?(\d{3})\-?(\d{3})\-?(\d{3})/,'$1-$2-$3-$4'))
	});

	// $('form#citizen-send-json').submit(function(e) {
	// 	e.preventDefault();
	// 	$(".validate-err").html('');
    //     var myform = $(this);
    //     myform.find("input[type='submit']").unbind("click");
	// 	var link = $(this).attr("action");
    //     var disabled = myform.find(':input:disabled').removeAttr('disabled');
    //     var data = myform.serialize().split("&");
    //     disabled.attr('disabled','disabled');
    //     var obj={};
    //     for(var key in data){
    //         obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
    //     }
    //     $.ajax({
    //         url :link+'/formValidation', // json datasource
    //         type: "POST", 
    //         data: obj,
    //         dataType: 'json',
    //         success: function(html){
    //             if(html.ESTATUS){
	// 				$("#err_"+html.field_name).html(html.error)
	// 				myform.find("input[type='submit']").remove();
	// 				myform.find('.modal-footer').delay( 1000 ).prepend('<input type="submit" type="submit"  value="Submit" class="btn btn-primary">');
	// 				e.preventDefault();
	// 				return false;
	// 			}else{
	// 				var action = link;
	// 				var data = myform.serializeArray();
	// 				var formData = getFormData(data);
	// 				console.log(formData);
	// 				$.ajax({
	// 							url: action,
	// 							dataType: 'json',
	// 							type: 'POST',
	// 							contentType: 'application/json',
	// 							data: JSON.stringify(getFormData(data)),
	// 							success: function(data){
	// 								myform.find("input[type='submit']").remove();
	// 								console.log(data.id);
	// 								var group = $('#'+data.field).closest('.citizen_group');
	// 								var value = data.id;
	// 								group.find('.search-select-ajax').val(data.name);
	// 								$('#'+data.field).val(value);
	// 								citizenWrite(group, value);
	// 								$("#modal-close").trigger("click");
	// 							},
	// 							error: function( jqXhr, textStatus, errorThrown ){
	// 								console.log( jqXhr );
	// 								console.log( textStatus );
	// 								console.log( errorThrown );
	// 							}
	// 				});
	// 			}
    //         }
    //     })
	// });
});

function getFormData(data) {
    var unindexed_array = data;
    var indexed_array = {};
    $.map(unindexed_array, function(n, i) {
		indexed_array[n['name']] = n['value'];
    });
    return indexed_array;
}

function uploadAttachment(){
	$(".validate-err").html("");
	if (typeof $('#document_name')[0].files[0]== "undefined") {
		$("#err_document").html("Please upload Document");
		return false;
	}
	var formData = new FormData();
	formData.append('file', $('#document_name')[0].files[0]);
	formData.append('id', $("#id").val());
	showLoader();
	$.ajax({
       url : DIR+'citizens/uploadAttachment',
       type : 'POST',
       data : formData,
       processData: false,  // tell jQuery not to process the data
       contentType: false,  // tell jQuery not to set contentType
       success : function(data) {
       		hideLoader();
       		var data = JSON.parse(data);
       	    if(data.ESTATUS==1){
       	    	$("#err_end_requirement_id").html(data.message);
       	    }else{
	       	    $("#end_requirement_id").val(0);
	       	    $("#document_name").val(null);
		       	if(data!=""){
		       		$("#DocumentDtls").html(data.documentList);
		       	}
	          	Swal.fire({
					 position: 'center',
					 icon: 'success',
					 title: 'Document uploaded successfully.',
					 showConfirmButton: false,
					 timer: 1500
			    })
			    $(".deleteDocument").unbind("click");
			    $(".deleteDocument").click(function(){
			 		deleteDocument($(this));
			 	})
			}
       }
	});
}
function deleteDocument(thisval){
	var rid = thisval.attr('rid');
	var bbendo_id = thisval.attr('bbendo_id');
	const swalWithBootstrapButtons = Swal.mixin({
	   customClass: {
		   confirmButton: 'btn btn-success',
		   cancelButton: 'btn btn-danger'
	   },
	   buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
	   text: "Are you sure?",
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   	if(result.isConfirmed){
	   		showLoader();
		  	$.ajax({
			   url :DIR+'citizens/deleteAttachment', // json datasource
			   type: "POST", 
			   data: {
			   	 "id":$("#id").val(),
				 "rid": rid, 
				 "_token": $("#_csrf_token").val(),
			   },
			   success: function(html){
			   	hideLoader();
			   	thisval.closest("tr").remove();
				   Swal.fire({
					 position: 'center',
					 icon: 'success',
					 title: 'Delete Successfully.',
					 showConfirmButton: false,
					 timer: 1500
				   })
			   }
		   })
	   }
   })
}
