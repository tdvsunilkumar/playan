$(document).ready(function(){
	
   
    $("#citizen_id").change(function(){
        var citizen_id=$(this).val();
        // getCitizenDetails(citizen_id);
    });
    $(document).on('change',".guardian",function(){
        var guardian_id=$(this).val();
        // getGuardianDetails(guardian_id);
    });
    $("#saveData").click(function(){
        saveDeta($(this));
    })

    removeGuardian();
    removeMedRecord();
 
    $("#btn_add_guardian").click(function(){
    $('html, body').stop().animate({
        scrollTop: $("#btn_add_guardian").offset().top
        }, 600);
     addmoreguardian();
     });

     $("#btn_addmore_medical").click(function(){
        $('html, body').stop().animate({
            scrollTop: $("#btn_addmore_medical").offset().top
            }, 600);
         addmoremedical();
         });
    
    $("#btn_addmore_diagnosis").click(function(){
    $('html, body').stop().animate({
        scrollTop: $("#btn_addmore_diagnosis").offset().top
        }, 600);
        addmorediagnosis();
        });
    $("#btn_addmore_treatment").click(function(){
        $('html, body').stop().animate({
            scrollTop: $("#btn_addmore_treatment").offset().top
            }, 600);
            addmoretreatment();
        });

    $(".btn_cancel_treatment").click(function(){
        var inputVal = $(this).closest(".removetreatmentdata").find("#serviceid").val();
        $(this).closest(".removetreatmentdata").remove();
        $.ajax({
            type: "GET",
            url: DIR+'deleteTreatment/'+inputVal,
            data: inputVal,
            dataType: "json",
            success: function(html){ 
                console.log(inputVal);
            }
        }); 
        console.log(inputVal);
    });
    $(".btn_cancel_diagnosis").click(function(){
        var inputVal = $(this).closest(".removediagnosisdata").find("#serviceid").val();
        $(this).closest(".removediagnosisdata").remove();
        $.ajax({
            type: "GET",
            url: DIR+'deleteDiagnosis/'+inputVal,
            data: inputVal,
            dataType: "json",
            success: function(html){ 
                console.log(inputVal);
            }
        }); 
        console.log(inputVal);
    });
 
    // lab requeest modal
    $('#recordcard-form').on('click', '.btn_open_labreq_modal', function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        var title = $(this).data('title');
        console.log(url);
        if(url != '#'){
            modal = $('#addLabRequestmodal');
            modal.modal({backdrop: 'static', keyboard: false});
            modal.modal('show');
            showLoader();
            $('.loadingGIF').show();
            $.ajax({
                type: "get",
                url: url,
                dataType: "html",
                success: function(html){ 
                    hideLoader();
                    modal.find('.body').html(html);
                    modal.find('.modal-title').html(title);
                },
                error: function(){
                hideLoader();
                }
            });
        }
    });

    $('#addLabRequestmodal').on('hidden.bs.modal', function () {
        $('#addLabRequestmodal .body').empty();
    });

    // Issueance modal
    $('#recordcard-form').on('click', '.btn_open_issue_modal', function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        var title = $(this).data('title');
        console.log(url);
        if(url != '#'){
            modal = $('#issuanceModal');
            modal.modal({backdrop: 'static', keyboard: false});
            modal.modal('show');
            showLoader();
            $('.loadingGIF').show();
            $.ajax({
                type: "get",
                url: url,
                dataType: "html",
                success: function(html){ 
                    hideLoader();
                    modal.find('.body').html(html);
                    modal.find('.modal-title').html(title);
                },
                error: function(){
                hideLoader();
                }
            });
        }
    });
});  
    
function removeMedRecord() {
    $(".btn_cancel_medical").click(function(){
        var selected = $(this).closest(".removemedicaldata");
        var inputVal = selected.data('id');
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "Once submit, This form can't be updated.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
                if(result.isConfirmed)
                {
	                var token = $("#_csrf_token").val();	
                    $.ajax({
                        type: "POST",
                        url: DIR+'medical/recordActiveInactive',
                        data: {
                            'id': inputVal,
                            'is_activeinactive':0,
                            _token: token
                        },
                        dataType: "json",
                        success: function(html){ 
                            selected.remove();
                        }
                    }); 
                }
        });
            
        console.log(inputVal);
    });
}
 function getCitizenDetails(id){
    $('.loadingGIF').show();
    var filtervars = {
        id:id
    };
    $.ajax({
        type: "GET",
        url: DIR+'getCitizenDetailsRecord',
        data: filtervars,
        dataType: "json",
        success: function(html){ 
            $('.loadingGIF').hide();
            $("#age").val(html.age);
            $("#complete_address").val(html.complete_address);
            $("#gender").val(html.gender);
            $("#philhealth_no").val(html.philhealth_no);
            $("#occupation").val(html.occupation);
            $("#nationality").val(html.nationality);
        }
    }); 
 }
 function getGuardianDetails(id){
    $('.loadingGIF').show();
    var filtervars = {
        id:id
    };
    $.ajax({
        type: "GET",
        url: DIR+'getGuardianDetailsRecord',
        data: filtervars,
        dataType: "json",
        success: function(html){ 
            $('.loadingGIF').hide();
            $("#get_complete_address").val(html.get_complete_address);
            $("#cit_mobile_no").val(html.mobile_no);
            $("#cit_is_active").val(html.status);
        }
    }); 
 }

 function saveDeta(thisval){
	const swalWithBootstrapButtons = Swal.mixin({
	   customClass: {
		   confirmButton: 'btn btn-primary',
		   cancelButton: 'btn btn-dark' 
	   },
	   buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
	   text: "Are you sure you want to continue?",
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   	if(result.isConfirmed){
	   		showLoader();
		  	$.ajax({
				url :DIR+'store', // json datasource
				type: "POST", 
				dataType: "json",
				data: {
                    "emp_id":$("#emp_id").val(),
					"date":$("#date").val(),
					"diagnosis": $("#diagnosis").val(),
					"medication": $("#medication").val(),
					"management": $("#management").val(),
					"notes": $("#notes").val(),
					
					"_token": $("#_csrf_token").val(),
			   },
			   success: function(html){
			   		hideLoader();
				   if(html.ESTATUS){
					   	Swal.fire({
						 position: 'center',
						 icon: 'success',
						 title: 'Submit Successfully.',
						 showConfirmButton: false,
						 timer: 1500
					   })
					   	
				   		location.reload(true);
				   }
			   }
		   })
	   }
   })
}

 getInitialGuardians = () =>{
    $.ajax({
        type: "GET",
        url: DIR+'getGuardianDetailsRecord',
        data: filtervars,
        dataType: "json",
        success: function(html){ 
            $('.loadingGIF').hide();
            $("#get_complete_address").val(html.get_complete_address);
            $("#cit_mobile_no").val(html.mobile_no);
            $("#cit_is_active").val(html.status);
        }
    }); 
 }
 function addmoreguardian(){
     var prevLength = $("#guardian-group").find(".removehealthcertidata").length;
     prevLength = prevLength+'new'
     $('#guardian-group').append('<div class="removehealthcertidata row pt10 citizen_group">'+
     '<div class="col-md-4">'+
            '<div class="form-group form-icon-user align-items-center">'+
                '<div class="row">'+
                    '<div class="m-form__group required col-md-10" id="contain-select-guardian-'+prevLength+'">'+
                        '<select class="form-control get-citizen select-guardian guardian ajax-select select_id" data-placeholder="Search Citizen" data-url="citizens/getCitizens" id="select-guardian-'+prevLength+'" data-contain="contain-select-guardian-'+prevLength+'" name="guardian['+prevLength+'][cit_id]"></select>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<button type="button" class="btn btn-sm btn-info btn_open_labreq_modal" data-url="'+DIR +'citizens/store?field=select-guardian-'+prevLength+'" data-title="Add Citizen">'+
                            '<i class="ti-plus"></i>'+
                        '</button>'+
                    '</div>'+
                '</div>'+
            '</div>'+
     '</div>'+
     '<div class="col-md-3">'+
         '<div class="form-group">'+
             '<div class="form-icon-user">'+
                 '<input class="form-control select_cit_full_address " name="guardian['+prevLength+'][comaddress]" type="text" readonly>'+
             '</div>'+
         '</div>'+
     '</div>'+
     '<div class="col-md-2">'+
         '<div class="form-group">'+
             '<div class="form-icon-user">'+
                 '<input class="form-control select_cit_mobile_no" name="guardian['+prevLength+'][contactno]" data-value="" type="text" readonly>'+
             '</div>'+
         '</div>'+
     '</div>'+
     '<div class="col-md-1">'+
         '<div class="form-group">'+
             '<div class="form-icon-user">'+
                 '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">New</span>'+
             '</div>'+
         '</div>'+
     '</div>'+
         '<div class="col-sm-2">'+
             '<button class="btn btn-danger btn_cancel_healthcert" style="padding: 0.4rem 1rem !important;" type="button"><i class="ti-trash text-white"></i></button>'+                               
         '</div>'+
     '</div>');
     selectAjax('.select-guardian');
     citizenRewrite();
     removeGuardian();
     
 }
 function addmoremedical(){
    var prevLength = $("#Medical").find(".removemedicaldata").length;

    $("#hidenmedicalHtml").find("#increment").html(prevLength+1);
    var html = $("#hidenmedicalHtml").html();
    $(".Medical").append(html);
    $(".btn_cancel_medical").click(function(){
        $(this).closest(".removemedicaldata").remove();
    });
    
}
function addmorediagnosis(){
    var prevLength = $("#diagnosis").find(".removediagnosisdata").length;

    $("#hidendiagnosisHtml").find("#increment").html(prevLength+1);
    var html = $("#hidendiagnosisHtml").html();
    $(".diagnosis").append(html);
    $(".btn_cancel_diagnosis").click(function(){
        $(this).closest(".removediagnosisdata").remove();
    });
}
function addmoretreatment(){
    var prevLength = $("#treatment").find(".removetreatmentdata").length;

    $("#hidentreatmentHtml").find("#increment").html(prevLength+1);
    var html = $("#hidentreatmentHtml").html();
    $(".treatment").append(html);
    $(".btn_cancel_treatment").click(function(){
        $(this).closest(".removetreatmentdata").remove();
    });
}

function removeGuardian() {
    $(".btn_cancel_healthcert").click(function(){
        var inputVal = $(this).data('value');
        var inputid = $(this).data('id');
        let msg = "";
        if(inputVal == 1){
            msg = "Deleted";
        }else{
            msg = "Restored";
        }
        
        if (inputVal == 0 || inputVal == 1) {
            Swal.fire({
				title: 'Are you sure?',
				text: "This record will be "+msg,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Yes',
				cancelButtonText: 'No',
				reverseButtons: true
			}).then((result) => {
                if(result.isConfirmed){
                    showLoader();
                    $.ajax({
                            type: "GET",
                            url: DIR+'guardian/activate/'+inputid,
                            data: {is_activeinactive : inputVal == 0 ? 1 : 0},
                            success: function(active_status){
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Update Successfully.',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                $('.delete-' + inputid).hide();
                                $('.restore-' + inputid).hide();
                                $('.delete-grd-status-' + inputid).hide();
                                $('.restore-grd-status-' + inputid).hide();

                                if(active_status == 1){
                                    $('.delete-' + inputid).show();
                                    $('.delete-grd-status-' + inputid).show();
                                }else{
                                    $('.restore-' + inputid).show();
                                    $('.restore-grd-status-' + inputid).show();
                                }
                                hideLoader();
                                // $('.guardian-active-icon-' + inputid).removeClass('ti-trash, ti-reload');
                                // $('.guardian-status-' + inputid).removeClass('btn-success, btn-warning');
                                // console.log(active_status);
                                // $('.btn_cancel_healthcert').attr('data-value', active_status);

                                // if(active_status == 1){
                                //     $('.guardian-status-' + inputid).text('Active');
                                //     $('.guardian-status-' + inputid).addClass('btn-success');

                                //     $('.guardian-active-icon-' + inputid).addClass('ti-trash');
                                // }else{
                                //     $('.guardian-status-' + inputid).text('InActive');
                                //     $('.guardian-status-' + inputid).addClass('btn-warning');

                                //     $('.guardian-active-icon-' + inputid).addClass('ti-reload');
                                // }
                            }
                        }); 
                    }
                })
        } else {
            $(this).closest(".removehealthcertidata").remove();
        }
     });
}
 
 