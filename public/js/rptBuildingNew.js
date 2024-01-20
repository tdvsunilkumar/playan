$(document).ready(function(){	
    $('#commonModal').modal({backdrop: 'static', keyboard: false});
    $("#rptPropertySearchByStatus").select3({dropdownAutoWidth : false,dropdownParent : $(document.body)});
    $("#rptPropertySearchByBarangy").select3({dropdownAutoWidth : false,dropdownParent : $(document.body)});
    $("#rptPropertySearchByRevisionYear").select3({dropdownAutoWidth : false,dropdownParent : $(document.body)});
    $("select[name=selected_update_code]").select3({dropdownAutoWidth : false,dropdownParent : '#selectUpdateCode'});
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	

    $('#rptPropertySearchByStatus').on('change',function(){
        $('#rptPropertySerachFilter').submit();
    });
    $('#rptPropertySearchByRevisionYear').on('change',function(){
        $('#rptPropertySerachFilter').submit();
    });
    $('#rptPropertySearchByBarangy').on('change',function(){
        if($(this).val() != ''){
            $('.addNewProperty').attr('hidden',false);
            $('.uploadNewProperties').attr('hidden',false);
        }else{
            $('.addNewProperty').attr('hidden',true);
            $('.uploadNewProperties').attr('hidden',false);
        }
        $('#rptPropertySerachFilter').submit();
    });

    $('#rptPropertySearchByText').on('keyup',function(){
        $('#rptPropertySerachFilter').submit();
    });

    $('#rptPropertySerachFilter').on('submit',function(e){
        e.preventDefault();
        datatablefunction();
    });
    $('#datecreated').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'mm-dd-yy'
    });
$('.addNewProperty').on('click',function(){
                var url = DIR+'rptbuilding/store';
                var title1 = 'Real Property - Building | Improvements';
                var title2 = 'Real Property - Building | Improvements';
                var title = (title1 != undefined) ? title1 : title2;
                var size = 'xll';
                loadMainForm(url, title, size,'commonModal');
     });

     $(document).on('click','.realpropertyaction',function(){

        //var selected = $(this).find("option:selected");
        var actionName = $(this).data('actionname');
        var propertyId   = $(this).data('propertyid'); 
        var taxDeclaretion   = $(this).data('tax');
        var count   = $(this).data('count');

        if(actionName == 'edit'){
            var url = DIR+'rptbuilding/store?id='+propertyId;
            var title1 = 'Real Property - Building | Improvements';
            var title2 = 'Real Property - Building | Improvements';
            var title = (title1 != undefined) ? title1 : title2;
            var size = 'xll';
            loadMainForm(url, title, size,'commonModal');
        }else if(actionName == 'print'){
            printTaxDeclaration(propertyId);
        }else if(actionName == 'printfaas'){
            printTaxDeclarationFaas(propertyId);
        }else if(actionName == 'updatecode'){
            var propertyId   = $(this).data('propertyid');
            var taxDeclaretion   = $(this).data('tax');
            var count   = $(this).data('count');
            $('#selectUpdateCode').modal('show');
            $('input[name=selected_property_id]').val(propertyId);
            $('input[name=taxdeclaretion]').val(taxDeclaretion);
            $('input[name=count]').val(count);
            $('.closeUpdateCodeNodal').on('click',function(){
                 $('#selectUpdateCode').modal('hide');
            });
        }else{

        }
    });

     $('#updateCodeSekected').on('click',function(){
        var updateConstants    = JSON.parse(getLandUpdateCodes());
        var selectedUpdateCode = $('.selected_update_code option:selected');
        var updateCodeText     = selectedUpdateCode.text();
        var updateCodeId       = selectedUpdateCode.val();
        var updateCode         = updateConstants[updateCodeId];
        console.log(updateConstants[updateCodeId]);
        var propertyId         = $('input[name=selected_property_id]').val();
        if(updateCodeId == ''){
            $('#err_selected_update_code').html('Select at Least one Update Code');
            $('.selected_update_code').focus();
        }else if(propertyId == "" && updateCode != "DC"){
            Swal.fire({
            title: 'Error!',
            text: 'Please select property whose, '+updateCodeText+' you want to do!',
            icon: 'error',
            confirmButtonText: 'Ok'
                 })
        }else{
            $('#err_selected_update_code').html('');
            switch(updateCode){
                case "DC":
                var url = DIR+'rptproperty/store?updatecode='+updateCodeId;
                var title1 = 'Real Property - Building | Improvements';
                var title2 = 'Real Property - Building | Improvements';
                var title = (title1 != undefined) ? title1 : title2;
                var size = 'xll';
                loadMainForm(url, title, size);
                break;

                case "TR":
                callToTRFunctionlaity(propertyId,updateCodeId);
                break;

                case "SSD":
                callToSSDFunctionlaity(propertyId,updateCodeId);
                break;

                case "RC":
                callToRCFunctionlaity(propertyId,updateCodeId);
                break;

                case "PC":
                callToPCFunctionlaity(propertyId,updateCodeId);
                break;

                case "CS":
                callToCSFunctionlaity(propertyId,updateCodeId);
                break;

                case "SD":
                callToSDFunctionlaity(propertyId,updateCodeId);
                break;

                case "DP":
                callToDPFunctionlaity(propertyId,updateCodeId);
                break;

                case "RE":
                callToREFunctionlaity(propertyId,updateCodeId);
                break;

                case "DUP":
                callToDUPFunctionlaity(propertyId,updateCodeId);
                break;

                case "RF":
                callToRFFunctionlaity(propertyId,updateCodeId);
                break;

                case "DE":
                callToDEFunctionlaity(propertyId,updateCodeId);
                break;

                case "DT":
                callToDTFunctionlaity(propertyId,updateCodeId);
                break;

                default:

            }
        }
     });
});

$(document).off('submit','#transferOfOwnershipIntermediateSubmission').on('submit','#transferOfOwnershipIntermediateSubmission',function(e){
    e.preventDefault();
    var url = $(this).attr('action');
        var method = $(this).attr('method');
        var data   = $(this).serialize();
        $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            if(html.status == 'success'){
                var url = DIR+'rptbuilding/store?updatecode='+html.data.updateCode+'&oldpropertyid='+html.data.oldpropertyid;
                var title = "Real Property - Building | Improvements (Transfer Of Ownership)";
                var size  = 'xll';
                loadMainForm(url, title, size);
                
            }if(html.status == 'error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            location.reload();
                          }
                   });
                
            }if(html.status == 'validation_error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            //location.reload();
                          }
                   });
            }
        }
    });

})

$(document).off('submit','#supersededIntermediateSubmission').on('submit','#supersededIntermediateSubmission',function(e){
    e.preventDefault();
    var url = $(this).attr('action');
        var method = $(this).attr('method');
        var data   = $(this).serialize();
        $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            if(html.status == 'success'){
                var url = DIR+'rptbuilding/store?updatecode='+html.data.updateCode+'&oldpropertyid='+html.data.oldpropertyid;
                var title = "Real Property - Building | Improvements (Superseded)";
                var size  = 'xll';
                loadMainForm(url, title, size, 'commonModal');
                
            }if(html.status == 'validation_error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            //location.reload();
                          }
                   });
            }if(html.status == 'error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            location.reload();
                          }
                   });
                
            }
        }
    });

})

$(document).on('click','#deleteTaxDeclaToConsolidate',function(){
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
                    if(result.isConfirmed){
                        showLoader();
            var selectedTaxDeclarationid        = $(this).data('id');
            var url = DIR+'rptbuilding/cs/deletetaxdeclaration';
            var data   = {
            selectedTaxDeclarationid:selectedTaxDeclarationid,
            "_token": $("#_csrf_token").val()
        };
        $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            if(html.status == 'success'){
                loadTaxDeclarationToConsolidate();
            }else{
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'Something went wrong!',
                      showConfirmButton: true,
                      timer: false
                    })
            }
        },error:function(){
            hideLoader();
        }
    });
                    }
                });
            
        
});  

$(document).on('click','#addTaxDeclarationToSession',function(){
    showLoader();
        var selectedProperty = $('#selectedPropertyId option:selected').val();
        var propertykind     = $('input[name=propertykind]').val();
        var revisionYear     = $('input[name=rvy_revision_year_id]').val();
        var barabgy          = $('input[name=brgy_code_id]').val();
        var url =  DIR+'rptbuilding/cs/addtaxdeclarationinlist';
        var data   = {
            id:selectedProperty,
            propertykind:propertykind,
            revisionYear:revisionYear,
            barabgy:barabgy,
            "_token": $("#_csrf_token").val()
        };
        $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            if(html.status == 'success'){
                $('#commonModal').find('.validate-err').html('');
                loadTaxDeclarationToConsolidate();
            }if(html.status == 'validation_error'){
                    $('#commonModal').find('.validate-err').html('');
                    $('#commonModal').find('#err_'+html.field_name).html(html.error);
                    $('#commonModal').find('.'+html.field_name).focus();
            }
        },error:function(){
            hideLoader();
        }
    });
});

$(document).off('submit','#reclassificationIntermediateSubmission').on('submit','#reclassificationIntermediateSubmission',function(e){
    e.preventDefault();
    var url = $(this).attr('action');
        var method = $(this).attr('method');
        var data   = $(this).serialize();
        $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            if(html.status == 'success'){
                var url = DIR+'rptbuilding/store?updatecode='+html.data.updateCode+'&oldpropertyid='+html.data.oldpropertyid;
                var title = "Real Property - Building | Improvements (Reclassification)";
                var size  = 'xll';
                loadMainForm(url, title, size, 'commonModal');
                
            }if(html.status == 'error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            location.reload();
                          }
                   });
                
            }if(html.status == 'validation_error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            //location.reload();
                          }
                   });
            }
        }
    });

})

$(document).off('submit','#physicalChangesIntermediateSubmission').on('submit','#physicalChangesIntermediateSubmission',function(e){
    e.preventDefault();
    var url = $(this).attr('action');
        var method = $(this).attr('method');
        var data   = $(this).serialize();
        $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            if(html.status == 'success'){
                var url = DIR+'rptbuilding/store?updatecode='+html.data.updateCode+'&oldpropertyid='+html.data.oldpropertyid;
                var title = "Real Property - Building | Improvements (Physical Changes)";
                var size  = 'xll';
                loadMainForm(url, title, size, 'commonModal');
                
            }if(html.status == 'error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            location.reload();
                          }
                   });
                
            }if(html.status == 'validation_error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            //location.reload();
                          }
                   });
            }
        }
    });

})

$(document).on('change','.subdividedtaxdeclarationid',function(){
   // alert($(this).val());
    $('.subdividedtaxdeclarationid').not(this).prop('checked', false); 
    var selectedLandAppraisalid        = $(this).val();
    if($(this).prop("checked") == true){
    loadNewTdFloorValues(selectedLandAppraisalid);
    }else{
        loadNewTdFloorValues(0);
    }
    
});

$(document).on('click','#addSubdividedTaxDeclaration',function(){
        showLoader();
        var updatecode = $('#subdivisionIntermediateSubmission').find('input[name="updateCode"]').val();
        var oldProperty = $('#subdivisionIntermediateSubmission').find('input[name="oldpropertyid"]').val();
        //var selectedLandAppraisal = $('#subdivisionIntermediateSubmission').find('input[name="selectedFloorValues[]"]').val();
        var selectedLandAppraisal = $('#subdivisionIntermediateSubmission').find('.landAppraisalIdForSubdivision:checked').map(function(){
      return $(this).val();
    }).get();
        //console.log(selectedLandAppraisal+'sssssss');
            var url = DIR+'rptbuilding/sd/submit';
            var data   = {
                oldProperty:oldProperty,
                selectedLandAppraisal:selectedLandAppraisal,
                updatecode:updatecode,
                action:'addNewTempTaxDeclaration',
                "_token": $("#_csrf_token").val()
            }
            $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            if(html.status == 'error'){
               Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'Please select at least one floor value!',
                      showConfirmButton: true,
                      timer: 3000
                    });
            }
            if(html.status == 'success'){
                var oldProperty = $('#subdivisionIntermediateSubmission').find('input[name="oldpropertyid"]').val();
                var selectedLandAppraisal = $('#subdivisionIntermediateSubmission').find('input[name="selectedlandappraisal"]').val();
               loadSubdividedTaxDeclarations(oldProperty,selectedLandAppraisal);
               loadPrevPropFloorValuyes();
            }
        },error:function(){
            hideLoader();
        }
    });
     });

$("#subdivisionIntermediateSubmission").unbind("submit");
$(document).on('submit','#subdivisionIntermediateSubmission',function(e){
    showLoader();
    e.preventDefault();
        var url =  $(this).attr('action');
        var method = $(this).attr('method');
        var data   = $(this).serialize();
        $.ajax({
        type: method,
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            if(html.status == 'success'){
                $('#commonUpDateCodeIntermediateModal1').modal('hide');
                $('#selectUpdateCode').modal('hide');
                Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    });
                $('#Jq_datatablelist').DataTable().ajax.reload();
            }if(html.status == 'error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    })
            }
        },error:function(){
            hideLoader();
        }
    });
});

$(document).on('click','#loadLandApprisalFormForSubDivision',function(){
        var updateCode = $('#subdivisionIntermediateSubmission').find('input[name=updateCode]').val();
        var oldpropertyid = $('#subdivisionIntermediateSubmission').find('input[name=oldpropertyid]').val();
        var selectedTaxDecl = $('#sdSubdividedTaxDeclarations').find("tbody tr .subdividedtaxdeclarationid:checkbox:checked");
        var length                 = selectedTaxDecl.length;
        var owner = selectedTaxDecl.parent().parent().find('.set_property_owner_for_tasdeclaration').val();
        
        //console.log(selectedTaxDecl.parent().parent());
        if(length == 0){
            Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'Please select at least one Tax Declaration',
                      showConfirmButton: true,
                      timer: false
                    })
        }else if(length > 1){
            Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'Please select only one record!',
                      showConfirmButton: true,
                      timer: 3000
                    })
        }else if(owner == ""){
            selectedTaxDecl.parent().parent().find('div').text('Required Field');

        }else{
            var selectedTaxDeclId = selectedTaxDecl.val();
            var url = DIR+'rptbuilding/store?id='+selectedTaxDeclId+'&updatecode='+updateCode+'&oldpropertyid='+oldpropertyid;
            var title = "Sub Division";
            var size  = 'xll';
            loadMainForm(url, title, size,'commonModal');

        }
     });

$(document).on('click','#processsubdivisionIntermediateSubmissionform',function(e){
    var selectedLandAppraisals = $('#new_added_land_apraisal').find("tbody tr .landAppraisalIdForSubdivision:checkbox:checked");
    var length                 = selectedLandAppraisals.length;
        if(length == 0){
            Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'Please select at least one Land Appraisal',
                      showConfirmButton: true,
                      timer: false
                    })
        }else if(length > 1){
            Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'Please select only one record!',
                      showConfirmButton: true,
                      timer: 3000
                    })
        }else{
            var selectedLandAppraisalid        = selectedLandAppraisals.val();
            $('#subdivisionIntermediateSubmission').find('input[name="selectedlandappraisal"]').val(selectedLandAppraisalid);
            //$('#landAppraisalAdjustmentFactorsmodal').modal('show');
            callToSubdivisionStep2();

            /*$(document).on('click','.closelandAppraisalAdjustmentFactorsmodal',function(){
                $('#landAppraisalAdjustmentFactorsmodal').modal('hide');

            });*/
        }
});

$(document).off('click', '.deleteSubdividedTaxDeclaration').on('click', '.deleteSubdividedTaxDeclaration', function() {
    var selectedTaxDeclarationid = $(this).data('id');
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
        if (result.isConfirmed) {
            showLoader();
            var url = DIR + 'rptbuilding/sd/deletetaxdeclaration';
            var data = {
                selectedTaxDeclarationid: selectedTaxDeclarationid,
                "_token": $("#_csrf_token").val()
            };
            $.ajax({
                type: "post",
                url: url,
                data: data,
                dataType: "json",
                success: function(html) {
                    hideLoader();
                    if (html.status == 'success') {
                        var oldProperty = $('#subdivisionIntermediateSubmission').find('input[name="oldpropertyid"]').val();
                        var selectedLandAppraisal = $('#subdivisionIntermediateSubmission').find('input[name="selectedlandappraisal"]').val();
                        loadSubdividedTaxDeclarations(oldProperty, selectedLandAppraisal);
                        loadPrevPropFloorValuyes();
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Something went wrong!',
                            showConfirmButton: true,
                            timer: false
                        })
                    }
                },
                error: function() {
                    hideLoader();
                }
            });
        }
    })
});

$(".set_property_owner_for_tasdeclaration").unbind("change");
$(document).on('change','.set_property_owner_for_tasdeclaration',function(){
    showLoader();
    var propertyid = $(this).parent().data('id');
    var declaredOwner = $(this).val();
    var url =  DIR+'rptbuilding/sd/updateTaxDeclaration';
        var method = 'post';
        var data   = {
            propertyid:propertyid,
            declaredOwner:declaredOwner,
            update:'taxDecla',
            "_token": $("#_csrf_token").val()
        };
        $.ajax({
        type: method,
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            if(html.status == 'success'){
                var oldProperty = $('#subdivisionIntermediateSubmission').find('input[name="oldpropertyid"]').val();
                var selectedLandAppraisal = $('#subdivisionIntermediateSubmission').find('input[name="selectedlandappraisal"]').val();
                loadSubdividedTaxDeclarations(oldProperty,selectedLandAppraisal);
            }
        },error:function(){
            hideLoader();
        }
    });


});

$('#disputeIntermediateSubmission').unbind('submit');
$(document).off('submit','#disputeIntermediateSubmission').on('submit','#disputeIntermediateSubmission',function(e){
    showLoader();
    e.preventDefault();
    var url = $(this).attr('action');
        var method = $(this).attr('method');
        var data   = $(this).serialize();
        $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            if(html.status == 'success'){
                Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            location.reload();
                          }
                   });
                
            }if(html.status == 'error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            location.reload();
                          }
                   });
                
            }if(html.status == 'validation_error'){
                Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: html.error,
                      showConfirmButton: true,
                      timer: 3000
                    })
                
            }
        },error:function(){
            hideLoader();
        }
    });

})

$('#transferOfOwnershipIntermediateSubmission').unbind('submit');
$(document).off('submit','#transferOfOwnershipIntermediateSubmission').on('submit','#transferOfOwnershipIntermediateSubmission',function(e){
    showLoader();
    e.preventDefault();
    var url = $(this).attr('action');
        var method = $(this).attr('method');
        var data   = $(this).serialize();
        $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            if(html.status == 'success'){
                var url = DIR+'rptbuilding/store?updatecode='+html.data.updateCode+'&oldpropertyid='+html.data.oldpropertyid;
                var title = "Real Property - Building | Improvements (Transfer Of Ownership)";
                var size  = 'xll';
                loadMainForm(url, title, size, 'commonModal');
                
            }if(html.status == 'error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            //location.reload();
                          }
                   });
                
            }if(html.status == 'validation_error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            //location.reload();
                          }
                   });
            }
        },error:function(){
            hideLoader();
        }
    });

})

$("#consolidationIntermediateSubmission").unbind("submit");
$(document).on('submit','#consolidationIntermediateSubmission',function(e){
    showLoader();
    e.preventDefault();
        var url =  $(this).attr('action');
        var method = $(this).attr('method');
        var data   = $(this).serialize();
        $.ajax({
        type: method,
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            if(html.status == 'success'){
                var url = DIR+'rptbuilding/store?updatecode='+html.data.updateCode+'&oldpropertyid='+html.data.oldpropertyid;
                var title = "Real Property - Building ...(Consolidation)";
                var size  = 'xll';
                loadMainForm(url, title, size, 'commonModal');
            }if(html.status == 'error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    })
            }
        },error:function(){
            hideLoader();
        }
    });
});

$('#duplicatecopyIntermediateSubmission').unbind('submit');
$(document).off('submit','#duplicatecopyIntermediateSubmission').on('submit','#duplicatecopyIntermediateSubmission',function(e){
    showLoader();
    e.preventDefault();
    var url = $(this).attr('action');
        var method = $(this).attr('method');
        var data   = $(this).serialize();
        $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            if(html.status == 'success'){
                var url = DIR+'rptbuilding/store?updatecode='+html.data.updateCode+'&oldpropertyid='+html.data.oldpropertyid;
                var title = "Real Property - Building | Improvements (Duplicate Copy)";
                var size  = 'xll';
                loadMainForm(url, title, size, 'commonModal');
                
            }if(html.status == 'error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            location.reload();
                          }
                   });
                
            }if(html.status == 'validation_error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            //location.reload();
                          }
                   });
            }
        },error:function(){
            hideLoader();
        }
    });

})

function callToDUPFunctionlaity(pId, upDateCode) {
    showLoader();
    var url = DIR+'rptbuilding/dup';
    var title1 = 'Property to Cancel ...(Duplicate Copy)';
    var title2 = 'Physical Changes';
    var title = (title1 != undefined) ? title1 : title2;
    var size = 'lg';
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        method:'post',
        data:{
            updatecode:upDateCode,
            selectedproperty:pId
        },
        success: function (data) {
            hideLoader();
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            taskCheckbox();
            //common_bind("#commonModal");
            commonLoader();
            //loadTaxDeclarationToConsolidate();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
}
function callToRFFunctionlaity(pId, upDateCode) {
    showLoader();
    var url = DIR+'rptbuilding/dp';
    var title1 = 'Property to Cancel ...(Razed By Fire)';
    var title2 = 'Physical Changes';
    var title = (title1 != undefined) ? title1 : title2;
    var size = 'lg';
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        method:'post',
        data:{
            updatecode:upDateCode,
            selectedproperty:pId
        },
        success: function (data) {
            hideLoader();
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            taskCheckbox();
            //common_bind("#commonModal");
            commonLoader();
            loadTaxDeclarationToConsolidate();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
}
function callToDEFunctionlaity(pId, upDateCode) {
    showLoader();
    var url = DIR+'rptbuilding/dp';
    var title1 = 'Property to Cancel ...(Demolished)';
    var title2 = 'Physical Changes';
    var title = (title1 != undefined) ? title1 : title2;
    var size = 'lg';
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        method:'post',
        data:{
            updatecode:upDateCode,
            selectedproperty:pId
        },
        success: function (data) {
            hideLoader();
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            taskCheckbox();
            //common_bind("#commonModal");
            commonLoader();
            loadTaxDeclarationToConsolidate();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
}

function callToCSFunctionlaity(pId, upDateCode) {
    showLoader();
    var url = DIR+'rptbuilding/cs';
    var title1 = 'Property to Cancel ...(Consolidation)';
    var title2 = 'Property to Cancel ...(Consolidation)';
    var title = (title1 != undefined) ? title1 : title2;
    var size = 'xll';
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        method:'post',
        data:{
            updatecode:upDateCode,
            selectedproperty:pId
        },
        success: function (data) {
            hideLoader();
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            //$("#selectedPropertyId").select3({dropdownAutoWidth : false,dropdownParent : '#accordionFlushExample2'});
            makeTdListRemoteAjax();
            taskCheckbox();
            //common_bind("#commonModal");
            commonLoader();
            loadTaxDeclarationToConsolidate();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
} 

function callToSDFunctionlaity(pId, upDateCode) {
    showLoader();
    var url = DIR+'rptbuilding/sd';
    var title1 = 'Subdivision';
    var title2 = 'Subdivision';
    var title = (title1 != undefined) ? title1 : title2;
    var size = 'xl';
    $("#commonUpDateCodeIntermediateModal1 .modal-title").html(title);
    $("#commonUpDateCodeIntermediateModal1 .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        method:'post',
        data:{
            updatecode:upDateCode,
            selectedproperty:pId
        },
        success: function (data) {
            hideLoader();
            $('#commonUpDateCodeIntermediateModal1 .body').html(data);
            $("#commonUpDateCodeIntermediateModal1").modal('show');
            loadPrevPropFloorValuyes();
            callToSubdivisionStep2();
            taskCheckbox();
            //common_bind("#commonModal");
            commonLoader();
            var selectedLandAppraisals = $('#prevPropertyLandAppraisal').find("tbody tr .landAppraisalIdForSubdivision:checkbox:checked");
            if(selectedLandAppraisals.length != 0){
                var selectedLandAppraisalid        = selectedLandAppraisals.val();
                $('#subdivisionIntermediateSubmission').find('input[name="selectedlandappraisal"]').val(selectedLandAppraisalid);
                callToSubdivisionStep2();

            }
            


        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
}

function loadPrevPropFloorValuyes() {
    showLoader();
    var url = DIR+'rptbuilding/sd/loadfloorvalues';
    var updateCode  = $('#subdivisionIntermediateSubmission').find('input[name="updateCode"]').val();
    var oldProperty = $('#subdivisionIntermediateSubmission').find('input[name="oldpropertyid"]').val();
    $.ajax({
        url: url,
        method:'get',
        data:{
            updatecode:updateCode,
            oldProperty:oldProperty,
        },
        success: function (data) {
            hideLoader();
            $('#prevPropertyFloorValues').html(data);
            //loadSubdividedTaxDeclarations();
            taskCheckbox();
            //common_bind("#subdivisionstep2modal");
            commonLoader();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
    
}

function callToSubdivisionStep2() {
    showLoader();
    var url = DIR+'rptbuilding/sd/step2';
    /*var title1 = 'New Tax Declaration Details...(Subdivision)';
    var title2 = 'New Tax Declaration Details...(Subdivision)';
    var title = (title1 != undefined) ? title1 : title2;
    var size = 'lg';
    $("#subdivisionstep2modal .modal-title").html(title);
    $("#subdivisionstep2modal .modal-dialog").addClass('modal-' + size);*/
    var updateCode  = $('#subdivisionIntermediateSubmission').find('input[name="updateCode"]').val();
    var oldProperty = $('#subdivisionIntermediateSubmission').find('input[name="oldpropertyid"]').val();
    var selectedLandAppraisal = $('#subdivisionIntermediateSubmission').find('input[name="selectedlandappraisal"]').val();
    $.ajax({
        url: url,
        method:'post',
        data:{
            updatecode:updateCode,
            oldProperty:oldProperty,
            selectedLandApp:selectedLandAppraisal
        },
        success: function (data) {
            hideLoader();
            $('#newSubDividedTaxDeclarationDetails').html(data);
            //$("#subdivisionstep2modal").modal('show');
            loadSubdividedTaxDeclarations();
            taskCheckbox();
            //common_bind("#subdivisionstep2modal");
            commonLoader();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
    
}

function loadNewTdFloorValues(id) {
    showLoader();
    var url = DIR+'rptbuilding/sd/loadnewtdfloorvalues';
    $.ajax({
        url: url,
        method:'get',
        data:{
            id:id,
        },
        success: function (data) {
            hideLoader();
            $('#sdappraisallisting').html(data);
            taskCheckbox();
            //common_bind("#subdivisionstep2modal");
            commonLoader();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
    
}

function loadSubdividedTaxDeclarations() {
    showLoader();
    var oldProperty = $('#subdivisionIntermediateSubmission').find('input[name="oldpropertyid"]').val();
    var selectedLandAppraisal = $('#subdivisionIntermediateSubmission').find('input[name="selectedlandappraisal"]').val();
    $('.loadingGIF').show();
    var filtervars = {
        oldProperty:oldProperty,
        selectedLandAppraisal:selectedLandAppraisal,
        "_token": $("#_csrf_token").val()
    }; 
    $.ajax({
        type: "post",
        url: DIR+'rptbuilding/sd/getlisting',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            var data = JSON.parse(html);
            $('#commonUpDateCodeIntermediateModal1').find('#sdSubdividedTaxDeclarations').html(data.view1);
            var selectedLandAppraisals = $('#sdSubdividedTaxDeclarations').find("tbody tr .subdividedtaxdeclarationid:checkbox:checked");
            if(selectedLandAppraisals.length != 0){
                var selectedLandAppraisalid        = selectedLandAppraisals.data('subdividedtaxdeclarationid');
                loadNewTdFloorValues(selectedLandAppraisalid);
            }else{
                loadNewTdFloorValues(0);
            }
            //updateRemainingArea();
        },error:function(){
            hideLoader();
        }
    });
}

function callToDTFunctionlaity(pId, upDateCode) {
    showLoader();
    var url = DIR+'rptbuilding/dp';
    var title1 = 'Property to Cancel ...(Destruction)';
    var title2 = 'Physical Changes';
    var title = (title1 != undefined) ? title1 : title2;
    var size = 'lg';
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        method:'post',
        data:{
            updatecode:upDateCode,
            selectedproperty:pId
        },
        success: function (data) {
            hideLoader();
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            taskCheckbox();
            //common_bind("#commonModal");
            commonLoader();
            //loadTaxDeclarationToConsolidate();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
}

function callToDPFunctionlaity(pId, upDateCode) {
    showLoader();
    var url = DIR+'rptbuilding/dp';
    var title1 = 'Property to Cancel ...(Dispute)';
    var title2 = 'Physical Changes';
    var title = (title1 != undefined) ? title1 : title2;
    var size = 'lg';
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        method:'post',
        data:{
            updatecode:upDateCode,
            selectedproperty:pId
        },
        success: function (data) {
            hideLoader();
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            taskCheckbox();
            //common_bind("#commonModal");
            commonLoader();
            //loadTaxDeclarationToConsolidate();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
}

function callToREFunctionlaity(pId, upDateCode) {
    showLoader();
    var url = DIR+'rptbuilding/dp';
    var title1 = 'Property to Cancel ...(Removed)';
    var title2 = 'Physical Changes';
    var title = (title1 != undefined) ? title1 : title2;
    var size = 'lg';
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        method:'post',
        data:{
            updatecode:upDateCode,
            selectedproperty:pId
        },
        success: function (data) {
            hideLoader();
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            taskCheckbox();
            //common_bind("#commonModal");
            commonLoader();
            loadTaxDeclarationToConsolidate();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
}

function loadMainForm(url, title, size,modalId) {
    showLoader();
    $("#"+modalId).unbind("click");
    $("#"+modalId+" .modal-title").html(title);
    $("#"+modalId+" .modal-dialog").addClass('modal-' + size);
    
    $.ajax({
        url: url,
        success: function (data) {
            hideLoader();
            if(typeof data.status !== 'undefined' && data.status == 'error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: data.msg,
                      showConfirmButton: true,
                      timer: 3000
                    })

            }else{
                $('#'+modalId+' .body').html('');
                $('#'+modalId+' .body').html(data);
                $("#"+modalId).modal('show');
                taskCheckbox();
                //common_bind("#"+modalId);
                commonLoader();
            }
            
        },
        error: function (data) {
            hideLoader();
            $('#'+modalId).modal('hide');
                /*Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: false,
                      timer: 3000
                    })*/
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
}

function loadTaxDeclarationToConsolidate() {
    showLoader();
    
    var filtervars = {
        "_token": $("#_csrf_token").val()
    }; 
    $.ajax({
        type: "post",
        url: DIR+'rptbuilding/cs/loadtaxdecltoconsoldate',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#taxDeclarationsToConsolidate').html(html);
        },error:function(){
            hideLoader();
        }
    });
}

function callToTRFunctionlaity(pId, upDateCode) {
    var url = DIR+'rptbuilding/tr';
    var title1 = 'Property to Cancel ...(Transfer Of Ownership)';
    var title2 = 'Property to Cancel ...(Transfer Of Ownership)';
    var title = (title1 != undefined) ? title1 : title2;
    var size = 'lg';
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        method:'post',
        data:{
            updatecode:upDateCode,
            selectedproperty:pId
        },
        success: function (data) {
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            taskCheckbox();
            //common_bind("#commonModal");
            commonLoader();
        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
}

function callToSSDFunctionlaity(pId, upDateCode) {
    showLoader();
    var url = DIR+'rptbuilding/ssd';
    var title1 = 'Property to Cancel ...(Superseded)';
    var title2 = 'Property to Cancel ...(Superseded)';
    var title = (title1 != undefined) ? title1 : title2;
    var size = 'lg';
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        method:'post',
        data:{
            updatecode:upDateCode,
            selectedproperty:pId
        },
        success: function (data) {
            hideLoader();
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            taskCheckbox();
            //common_bind("#commonModal");
            commonLoader();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
}

function callToRCFunctionlaity(pId, upDateCode) {
    showLoader();
    var url = DIR+'rptbuilding/rc';
    var title1 = 'Property to Cancel ...(Reclassification)';
    var title2 = 'Property to Cancel ...(Reclassification)';
    var title = (title1 != undefined) ? title1 : title2;
    var size = 'lg';
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        method:'post',
        data:{
            updatecode:upDateCode,
            selectedproperty:pId
        },
        success: function (data) {
            hideLoader();
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            taskCheckbox();
            //common_bind("#commonModal");
            commonLoader();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
}

function callToPCFunctionlaity(pId, upDateCode) {
    showLoader();
    var url = DIR+'rptbuilding/pc';
    var title1 = 'Property to Cancel ...(Physical Changes)';
    var title2 = 'Property to Cancel ...(Physical Changes)';
    var title = (title1 != undefined) ? title1 : title2;
    var size = 'lg';
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        method:'post',
        data:{
            updatecode:upDateCode,
            selectedproperty:pId
        },
        success: function (data) {
            hideLoader();
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            taskCheckbox();
            //common_bind("#commonModal");
            commonLoader();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
}

function printTaxDeclaration(id) {
      var _id = id;
      $.ajax({
            url: _baseUrl + 'real-property/inquiries/printTaxDec',
            type: 'GET',
            data: {
                "id": _id,
            },
            success: function (data) {
               var url = data;
               console.log(data);
                window.open(url, '_blank');
            }
          });
  }

  function printTaxDeclarationFaas(id) {
      var _id = id;
      $.ajax({
            url: _baseUrl + 'real-property/inquiries/printFAASBuilding',
            type: 'GET',
            data: {
                "id": _id,
            },
            success: function (data) {
               var url = data;
               console.log(data);
                window.open(url, '_blank');
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
		"columnDefs": [
            { orderable: false, targets: [0,13] }
            ],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'rptbuilding/getList', // json datasource
			type: "GET", 
			"data": {
                "q":$("#rptPropertySearchByText").val(),
                'year':$('#rptPropertySearchByRevisionYear').val(),
                /*'approve':$('#aproved').val(),
                'crdate':$('#datecreated1').val(),*/
                'status':$('#rptPropertySearchByStatus').val(),
                'barangay':$('#rptPropertySearchByBarangy').val(),
                "_token":$("#_csrf_token").val()
            },
			error: function(html){
			}
		},
        "columns": [
            { "data": "no" },
            { "data": "td_no" },
            { "data": "taxpayer_name" },
            { "data": "brgy_name" },
            { "data": "pin" },
            { "data": "cct_unit_no" },
            { "data": "market_value" },
            { "data": "assessed_value" },
            { "data": "uc_code" },
            { "data": "effectivity" },
            { "data": "reg_emp_name" },
            { "data": "created_date" },
            { "data": "pk_is_active" },
            { "data": "action" }
            /*{ "data": "other"}*/
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	        $("#common_pagesize").html(dropdown_html);
	         api.$('.print').click(function() {
	            var rowid = $(this).attr('id');
	            grosssaleReceipt(rowid);
	        });
             api.$(".showLess2").shorten({
                "showChars" : 10,
                "moreText"    : "More",
                "lessText"    : "Less",
            });
	    }
	});  
}

function makeTdListRemoteAjax() {
    $('#commonModal').find("#selectedPropertyId").select3({
    placeholder: 'Select Tax Declaration No.',
    allowClear: true,
    dropdownParent: $('#commonModal').find("#selectedPropertyId").parent(),
    ajax: {
        url: DIR+'rptproperty/gettdsforajaxselectlist',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                column:'id',
                callfrom:'consolidation',
                pk_id:1,
                rpo_code:0,
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
 }
