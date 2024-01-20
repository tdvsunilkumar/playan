$(document).ready(function(){
	function initializeSelect3() {
		$('.dec_id_s').select3({ dropdownAutoWidth: false, dropdownParent: $('#Deccadaver') });
	  }
	  // Initial initialization of select3
	  initializeSelect3();
    
    $("#health_officer_id").change(function(){
        var officer_id=$(this).val();
        getOfficerDetails(officer_id);
    });
	$("#btn_addmore_deccadaver").click(function(){
		$('html, body').stop().animate({
      scrollTop: $("#btn_addmore_deccadaver").offset().top
    }, 600);

		addmorehealthcert();
	});
	
	$(".btn_cancel_deccadaver").click(function(){
		var inputVal = $(this).closest(".removeDeccadaverdata").find("#deceaseid").val();
		$(this).closest(".removeDeccadaverdata").remove();
		isDuplicate=0;
		var bcnt=0;
		$("#Deccadaver").find(".removeDeccadaverdata").each(function(id){
			$(this).find('.dec_id').attr("id",'dec_id'+bcnt);
			bcnt++;
		});
		var cnt = $("#Deccadaver").find(".removeDeccadaverdata").length;
		$("#hidenDeccadaverHtml").find('.dec_id').attr('id','dec_id'+cnt);
		$.ajax({
			type: "GET",
			url: DIR+'healthy-and-safety/transfer-of-cadaver/deleteDeceased/'+inputVal,
			data: inputVal,
			dataType: "json",
			success: function(html){ 
				console.log(inputVal);
			}
		}); 
		console.log(inputVal);
	});
	$("#uploadAttachmentonly").click(function(){
   		uploadAttachmentonly();
   	});
   	$(".deleteAttachment").click(function(){
   		deleteAttachment($(this));
   	})
});

function uploadAttachmentonly(){
	$(".validate-err").html("");
	if (typeof $('#ora_document')[0].files[0]== "undefined") {
		$("#err_documents").html("Please upload Document");
		return false;
	}
	var formData = new FormData();
	formData.append('file', $('#ora_document')[0].files[0]);
	formData.append('healthCertId', $("#id").val());
	showLoader();
	$.ajax({
	   url : DIR+'civil-registrar/permits/uploadDocument',
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
				$("#ora_document").val(null);
				if(data!=""){
					$("#DocumentDtlsss").html(data.documentList);
				}
				Swal.fire({
					 position: 'center',
					 icon: 'success',
					 title: 'Document uploaded successfully.',
					 showConfirmButton: false,
					 timer: 1500
				})
				$(".deleteEndrosment").unbind("click");
				$(".deleteEndrosment").click(function(){
					deleteEndrosment($(this));
				})
			}
	   }
	});
}
function deleteAttachment(thisval){
	var healthCertid = thisval.attr('healthCertid');
	var doc_id = thisval.attr('doc_id');
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
			   url :DIR+'civil-registrar/permits/deleteAttachment', // json datasource
			   type: "POST", 
			   data: {
					"healthCertid": healthCertid,
					"doc_id": doc_id,  
			   },
			   success: function(html){
				hideLoader();
				thisval.closest("tr").remove();
				   Swal.fire({
					 position: 'center',
					 icon: 'success',
					 title: 'Update Successfully.',
					 showConfirmButton: false,
					 timer: 1500
				   })
			   }
		   })
	   }
   })
}

 function getOfficerDetails(id){
    $('.loadingGIF').show();
    var filtervars = {
        id:id
    };
    $.ajax({
        type: "GET",
        url: DIR+'healthy-and-safety/transfer-of-cadaver/getPosition',
        data: filtervars,
        dataType: "json",
        success: function(html){ 
            $('.loadingGIF').hide();
            $("#health_officer_position").val(html.health_officer_position);
        }
    }); 
 }
function addmorehealthcert(){
	var prevLength = $("#Deccadaver").find(".removeDeccadaverdata").length;

	$("#hidenDeccadaverHtml").find("#increment").html(prevLength+1);
	var html = $("#hidenDeccadaverHtml").html();
	$(".Deccadaver").append(html);
	$(".btn_cancel_deccadaver").click(function(){
		$(this).closest(".removeDeccadaverdata").remove();
		isDuplicate=0;
		var bcnt=0;
		$("#Deccadaver").find(".removeDeccadaverdata").each(function(id){
			$(this).find('.dec_id').attr("id",'dec_id'+bcnt);
			bcnt++;
		});
		var cnt = $("#Deccadaver").find(".removeDeccadaverdata").length;
		$("#hidenDeccadaverHtml").find('.dec_id').attr('id','dec_id'+cnt);
	});
	$("#dec_id"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#Deccadaver")});
	var classid = $("#Deccadaver").find(".removeDeccadaverdata").length;
	$("#hidenDeccadaverHtml").find('.dec_id').attr('id','dec_id'+classid);

	
}