$(document).ready(function(){
    if($('input[name=readyForSubmission]').val() == 0){
    }
    $('.yearpicker').yearpicker({dropdownAutoWidth: false, dropdownParent: $("#toyear")});
    $("select[name=rp_td_no]").select3({
    placeholder: 'Select Tax Declaration No.',
    allowClear: true,
    dropdownParent: $("select[name=rp_td_no]").parent(),
    ajax: {
        url: DIR+'rptproperty/gettdsforajaxselectlist',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                callfrom:'billing',
                billingmode:$('input[name=cb_billing_mode]').val(),
                pk_id:0,
                rpo_code:0,
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
    $('.cb_covered_from_year').yearpicker({
        /*dropdownAutoWidth: false, 
        dropdownParent: $("#fromyear"),*/
        onChange : function(value){
              const d = new Date();
              let year = d.getFullYear();
              if(value != null && value > year){
                $('.waive_penalty').prop('disabled',true);
                $('.waive_discount').prop('disabled',false);
              }if(value != null && value < year){
                $('.waive_discount').prop('disabled',true);
                $('.waive_penalty').prop('disabled',false);
              }if(value != null && value == year){
                $('.waive_discount').prop('disabled',false);
                $('.waive_penalty').prop('disabled',false);
              }
        }
    });
    $('#updateTaxRateScheduleModal').modal({backdrop: 'static', keyboard: false});
    $('#LandUnitValueScheduleModal').modal({backdrop: 'static', keyboard: false});
    $('#editLandUnitValueModal').modal({backdrop: 'static', keyboard: false});
    $('#plantsTreesUnitValueScheduleModal').modal({backdrop: 'static', keyboard: false});
    $('#editPlantTreesUnitValueModal').modal({backdrop: 'static', keyboard: false});
    $('#buildingUnitValueScheduleModal').modal({backdrop: 'static', keyboard: false});
    $('#editBuildingUnitValueModal').modal({backdrop: 'static', keyboard: false});
    $('#assessementLevelScheduleModal').modal({backdrop: 'static', keyboard: false});
    $('#editAssessementLevelModal').modal({backdrop: 'static', keyboard: false});

    $(document).off('click','#searchTdNo').on('click','#searchTdNo',function(e){
        showLoader();
        e.preventDefault();
        var rpTdNo = $("#rp_td_no option:selected").val();
        var revisionYear = $('input[name=rvy_revision_year]').val();
        var brngyCode    = $('input[name=brgy_code]').val();
        var cb_billing_mode    = $('input[name=cb_billing_mode]').val();
        var url =  DIR+'billingform/searchbytd';
        var method = 'post';
        var data   = {
            rp_td_no:rpTdNo,
            rvy_revision_code:revisionYear,
            brgy_no:brngyCode,
            billingmode:cb_billing_mode,
            "_token": $("#_csrf_token").val()
    };
        $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            $('input[name=readyForSubmission]').val(0);
            if(html.status == 'success'){
                $('.validate-err').html('');
                $('input[name=rpo_code_desc]').val(html.data.land_owner);
                $('input[name=rpo_code]').val(html.data.rpo_code);
                $('input[name=owner_address]').val(html.data.land_location);
                $('input[name=cb_assessed_value]').val(parseFloat(html.data.assessed_value).toFixed(2));
                $('input[name=pk_code_desc]').val(html.data.kind);
                $('input[name=rp_code]').val(html.data.rp_code);
                $('input[name=prop_class]').val(html.data.class);
                $('input[name=pc_class_code]').val(html.data.pc_class_code);
                $('input[name=rp_pindcno]').val(html.data.rp_pindcno);
                $('#noteNeedsToAppearHere').html(html.data.note);
                loadComputationData();
                /* Show prompt to confirm, are you want to pay annually 
                const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        
        swalWithBootstrapButtons.fire({
            title: 'You have chosen to accept RPT Payments.',
            text: "Do you want to compute ANNUALLY?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then((result) => {
            if(result.isConfirmed){
                $('#sd_mode').prop('disabled',true);
                $('#sd_mode_to').prop('disabled',true);
                $('input[name=cb_all_quarter_paid]').val(1);
            }else{
                $('#sd_mode').prop('disabled',false);
                $('#sd_mode_to').prop('disabled',false);
                $('input[name=cb_all_quarter_paid]').val(0);
            }
        })*/
                /* Show prompt to confirm, are you want to pay annually */
            }if(html.status == 'validation_error'){
                $('.validate-err').html('');
                    $('#err_'+html.field_name).html(html.error);
                    $('.'+html.field_name).focus();
               $('input[name=rpo_code_desc]').val('');
                $('input[name=rpo_code]').val('');
                $('input[name=owner_address]').val('');
                $('input[name=cb_assessed_value]').val('');
                $('input[name=pk_code_desc]').val('');
                $('input[name=rp_code]').val('');
                $('input[name=prop_class]').val('');
                $('input[name=pc_class_code]').val('');
            }
        },error:function(){
            hideLoader();
        }
    });

    });

    $(document).on('click','.cb_all_quarter_paid_checkbox',function() {
        if($(this).is(":checked")) {
            $('#sd_mode').val('11').change();
            $('#sd_mode_to').val('44').change();
            $('#sd_mode').prop('disabled',true);
            $('#sd_mode_to').prop('disabled',true);
            $('input[name=cb_all_quarter_paid]').val(1);
        } else {
            $('#sd_mode').prop('disabled',false);
            $('#sd_mode_to').prop('disabled',false);
            $('#sd_mode').select3({dropdownAutoWidth : false,dropdownParent: $('#sd_mode').parent()});
            $('#sd_mode_to').select3({dropdownAutoWidth : false,dropdownParent: $('#sd_mode_to').parent()});
            $('input[name=cb_all_quarter_paid]').val(0);
        }
    });

    $(document).on('click','.waive_discount',function() {
        if($(this).is(":checked")) {
            $('input[name=compute_for_discount]').val(0);
        } else {
            $('input[name=compute_for_discount]').val(1);
        }
    });

    $(document).on('click','.waive_penalty',function() {
        if($(this).is(":checked")) {
            $('input[name=compute_for_penalty]').val(0);
        } else {
            $('input[name=compute_for_penalty]').val(1);
        }
    });

    $("#rp_td_no").change(function(){
        var url =  DIR+'billingform/getbarangaybyid';
        var method = 'post';
        var rpTdNo = $("#rp_td_no option:selected").val();
        var data   = {
            rp_td_no:rpTdNo,
        "_token": $("#_csrf_token").val()
        };
          $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            if(html.status == 'success'){
               $('input[name=currentbarangay]').val(html.data.barangay);
            }
        },error:function(){
            hideLoader();
        }
      });
   });

    $(document).off('click','#commonModal #generateBillFromTemporaryData').on('click','#commonModal #generateBillFromTemporaryData',function(e){
        if($('input[name=readyForSubmission]').val() == 0){
            generateBillFromTemporaryData();
        }if($('input[name=readyForSubmission]').val() == 1){
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
         swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "Continue the Billing?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true,
        }).then((result) => {
            if(result.isConfirmed){
               generateBillFromTemporaryData();
            }
        })
            
        }
        
        
    });

    $(document).off('submit','#generateBilling').on('submit','#generateBilling',function(e){
        $('input[name=cb_all_quarter_paid_checkbox]').prop('disabled',true);
        showLoader();
        e.preventDefault();
        var url =  $(this).attr('action');
        var method = $(this).attr('method');
        var data   = $(this).serialize();
        $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            $('.validate-err').html('');
            hideLoader();
            if(html.status == 'success'){
                $('input[name=readyForSubmission]').val(1);
                loadComputationData(html.id);
                
            }if(html.status == 'error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    })
                
            }if(html.status == 'pending'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    });
                $('input[name=cb_covered_from_year]').val(html.year);
                $('input[name=cb_covered_to_year]').val(html.year);
                $('#sd_mode').val(html.qtr);
                $('#sd_mode').trigger('change');
                $('#sd_mode_to').val('44');
                $('#sd_mode_to').trigger('change');
            }if(html.status == 'validation_error'){
                $('.validate-err').html('');
                    $('#err_'+html.field_name).html(html.error);
                    $('.'+html.field_name).focus();
            }/*if(html.status == 'for_discount'){
                const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        
        swalWithBootstrapButtons.fire({
            title: html.msg,
            text: "",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then((result) => {
            if(result.isConfirmed){
                $('input[name=compute_for_discount]').val(1);
            }else{
                $('input[name=compute_for_discount]').val(0);
            }
            $('#generateBilling').submit();
        })
                
            }*/
        },error:function(){
            hideLoader();
        }
    });

    });

});

function generateBillFromTemporaryData() {
    showLoader();
        var url =  DIR+'billingform/genratebill';
        var method = 'post';
        var data   = {
        "_token": $("#_csrf_token").val()
    };
        $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            if(html.status == 'success'){
                $('#commonModal').modal('hide');
                Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            storRemoteRptBillReceipt(html.txnNo);
                            storRemoteRptOnlineAccess(html.txnNo);
                            $('#Jq_datatablelist').DataTable().ajax.reload();
                          }
                   });
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
}


function getbarangayaDetails(id){
    showLoader();
    $('.loadingGIF').show();
    var filtervars = {
        id:id
    }; 
    $.ajax({
        type: "GET",
        url: DIR+'rptproperty/getbarangycodedetails',
        data: filtervars,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            $('input[name=brangay_name]').val(html.brgy_name);
            $('input[name=mun_desc]').val(html.mun_desc);
            
        },error:function(){
            hideLoader();
        }
    });
}

function  storRemoteRptBillReceipt(transaction_no) {
     $.ajax({
        url :DIR+'billingform/storRemoteRptBillReceipt', // json datasource
        type: "POST", 
        data: {
          "transaction_no":transaction_no,
          "_token": $("#_csrf_token").val()
        },
        success: function(html){

        }
    });
}

function storRemoteRptOnlineAccess(transaction_no) {
    $.ajax({
        url :DIR+'billingform/storRemoteRptOnlineAccess', // json datasource
        type: "POST", 
        data: {
          "transaction_no":transaction_no,
          "_token": $("#_csrf_token").val()
        },
        success: function(html){

        }
    });
}

function loadComputationData(id) {
    showLoader();
    var revisionYear = $('input[name=from_rvy_revision_year_id]').val();
    var brngyCode    = $('select[name=brgy_code_id]').val();
    var propertyKind = $('select[name=pk_id]').val();
    var filtervars = {
        id: id
    };
    $.ajax({
        type: "get",
        url: DIR+'billingform/computebillingdata',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#computedBillingData').html(html);
            /*$('input[name=brangay_name]').val(html.brgy_name);
            $('input[name=mun_desc]').val(html.mun_desc);*/
            
        },error:function(){
            hideLoader();
        }
    });
}