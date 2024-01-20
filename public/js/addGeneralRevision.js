$(document).ready(function(){
    $('#updateTaxRateScheduleModal').modal({backdrop: 'static', keyboard: false});
    $('#LandUnitValueScheduleModal').modal({backdrop: 'static', keyboard: false});
    $('#editLandUnitValueModal').modal({backdrop: 'static', keyboard: false});
    $('#plantsTreesUnitValueScheduleModal').modal({backdrop: 'static', keyboard: false});
    $('#editPlantTreesUnitValueModal').modal({backdrop: 'static', keyboard: false});
    $('#buildingUnitValueScheduleModal').modal({backdrop: 'static', keyboard: false});
    $('#editBuildingUnitValueModal').modal({backdrop: 'static', keyboard: false});
     $('#assessementLevelScheduleModal').modal({backdrop: 'static', keyboard: false});
     $('#editAssessementLevelModal').modal({backdrop: 'static', keyboard: false});

    getbarangayaDetails($('#brgy_code_id').val());
    loadRelatedTaxDeclarations();
    loadDraftedTaxDeclarations();
    $("#brgy_code_id").change(function(){
        var id=$(this).val();
            getbarangayaDetails(id);   
            loadRelatedTaxDeclarations();
            loadDraftedTaxDeclarations();
    });

    $("#pk_id").change(function(){
            loadRelatedTaxDeclarations();   
            loadDraftedTaxDeclarations();
    });
    $('#launchTaxRateScheduleModal').unbind('click');
    $('#launchTaxRateScheduleModal').click(function(){
        $('#updateTaxRateScheduleModal').modal('show');
    });

    $('.closeUpdateCodeNodal').unbind('click');
    $('.closeUpdateCodeNodal').click(function(){
        $('#updateTaxRateScheduleModal').modal('hide');
    });

    $('.closePlantTreeUnitValueScheduleModal').unbind('click');
    $(document).off('click','.closePlantTreeUnitValueScheduleModal').on('click','.closePlantTreeUnitValueScheduleModal',function(){
        $('#plantsTreesUnitValueScheduleModal').modal('hide');
    });

    $('.closeBuildingUnitValueScheduleView').unbind('click');
    $(document).off('click','.closeBuildingUnitValueScheduleView').on('click','.closeBuildingUnitValueScheduleView',function(){
        $('#buildingUnitValueScheduleModal').modal('hide');
    });

    $('.closeLandUnitValueScheduleView').unbind('click');
     $(document).off('click','.closeLandUnitValueScheduleView').on('click','.closeLandUnitValueScheduleView',function(){
        $('#LandUnitValueScheduleModal').modal('hide');
    });

     $('.closeAssessementLevelScheduleView').unbind('click');
     $(document).off('click','.closeAssessementLevelScheduleView').on('click','.closeAssessementLevelScheduleView',function(){
        $('#assessementLevelScheduleModal').modal('hide');
    });

    $(document).off('click','#showLandUnitValueScheduleModal').on('click','#showLandUnitValueScheduleModal',function(){
        loadLandUnitScheduleRatePanel();
        //$('#LandUnitValueScheduleModal').modal('show');

    });

    $(document).off('click','#showPlantsTressUnitValueScheduleModal').on('click','#showPlantsTressUnitValueScheduleModal',function(){
        loadPlantsTreesUnitScheduleRatePanel();
        //$('#LandUnitValueScheduleModal').modal('show');

    });

    $(document).off('click','#showBuildingUnitValueScheduleModal').on('click','#showBuildingUnitValueScheduleModal',function(){
        loadBuildingUnitScheduleRatePanel();
        //$('#LandUnitValueScheduleModal').modal('show');

    });

    $(document).off('click','#showAssessementLevelScheduleModal').on('click','#showAssessementLevelScheduleModal',function(){
        loadAssessementLevelScheduleRatePanel();
        //$('#LandUnitValueScheduleModal').modal('show');

    });

    $(document).off('click','.selectAllProperties').on('click','.selectAllProperties',function(){
         if(this.checked) {
         $('.propertiesNeedToRevise').each(function() {
            this.checked = true;                        
        });
    }else {
        $('.propertiesNeedToRevise').each(function() {
            this.checked = false;                       
        });
    }
    })

    $(document).off('click','#editLandUnitValue').on('click','#editLandUnitValue',function(){
        var url = $(this).data('url');
        var title1 = 'Manage Land Unit Value';
        var title2 = 'Manage Land Unit Value';
        var title = (title1 != undefined) ? title1 : title2;
        var size = 'lg';
        $("#editLandUnitValueModal .modal-title").html(title);
        $("#editLandUnitValueModal .modal-dialog").addClass('modal-' + size);
        $("#editLandUnitValueModal").modal('show');
        showLoader();
    $.ajax({
        url: url,
        success: function (data) {
            hideLoader();
            $('#editLandUnitValueModal .body').html(data);
            setTimeout(function(){ 
                $('#editLandUnitValueModal').find('#pau_actual_use_code').prop("disabled", true);
                $('#editLandUnitValueModal').find('#pc_class_code').prop("disabled", true);
                $('#editLandUnitValueModal').find('#ps_subclass_code').prop("disabled", true);
                $('#editLandUnitValueModal').find('#lav_unit_measure').prop("disabled", true);
                    }, 500);
            
            taskCheckbox();
            common_bind("#editLandUnitValueModal");
            commonLoader();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });

    });

    $(document).off('click','#editAssessementLevel').on('click','#editAssessementLevel',function(){
        var url = $(this).data('url');
        var title1 = 'Manage Assessement Level';
        var title2 = 'Manage Assessement Level';
        var title = (title1 != undefined) ? title1 : title2;
        var size = 'lg';
        $("#editAssessementLevelModal .modal-title").html(title);
        $("#editAssessementLevelModal .modal-dialog").addClass('modal-' + size);
        $("#editAssessementLevelModal").modal('show');
        showLoader();
    $.ajax({
        url: url,
        success: function (data) {
            hideLoader();
            $('#editAssessementLevelModal .body').html(data);
            setTimeout(function(){ 
                $('#editAssessementLevelModal').find('#pau_actual_use_code').prop("disabled", true);
                $('#editAssessementLevelModal').find('#pk_code').prop("disabled", true);
                    }, 500);
            
            taskCheckbox();
            common_bind("#editAssessementLevelModal");
            commonLoader();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });

    });

    $(document).off('click','#editBuildingUnitValue').on('click','#editBuildingUnitValue',function(){
        var url = $(this).data('url');
        var title1 = 'Manage Building Unit Value';
        var title2 = 'Manage Building Unit Value';
        var title = (title1 != undefined) ? title1 : title2;
        var size = 'lg';
        $("#editBuildingUnitValueModal .modal-title").html(title);
        $("#editBuildingUnitValueModal .modal-dialog").addClass('modal-' + size);
        $("#editBuildingUnitValueModal").modal('show');
        showLoader();
    $.ajax({
        url: url,
        success: function (data) {
            hideLoader();
            $('#editBuildingUnitValueModal .body').html(data);
            setTimeout(function(){ 
                $('#editBuildingUnitValueModal').find('#bk_building_kind_code').prop("disabled", true);
                $('#editBuildingUnitValueModal').find('#bt_building_type_code').prop("disabled", true);
                    }, 500);
            
            taskCheckbox();
            common_bind("#editBuildingUnitValueModal");
            commonLoader();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });

    });

    $(document).off('click','#editPlantTreesUnitValue').on('click','#editPlantTreesUnitValue',function(){
        var url = $(this).data('url');
        var title1 = 'Manage Plants/Trees Unit Value';
        var title2 = 'Manage Plants/Trees Unit Value';
        var title = (title1 != undefined) ? title1 : title2;
        var size = 'lg';
        $("#editPlantTreesUnitValueModal .modal-title").html(title);
        $("#editPlantTreesUnitValueModal .modal-dialog").addClass('modal-' + size);
        $("#editPlantTreesUnitValueModal").modal('show');
        showLoader();
    $.ajax({
        url: url,
        success: function (data) {
            hideLoader();
            $('#editPlantTreesUnitValueModal .body').html(data);
            setTimeout(function(){ 
                $('#editPlantTreesUnitValueModal').find('#pt_ptrees_code').prop("disabled", true);
                $('#editPlantTreesUnitValueModal').find('#ps_subclass_code').prop("disabled", true);
                 }, 500);
            
            taskCheckbox();
            common_bind("#editPlantTreesUnitValueModal");
            commonLoader();
        },
        error: function (data) {
            hideLoader();
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });

    });
    

    $(document).off('submit','#submitLandUnitValueForm').on('submit','#submitLandUnitValueForm',function(e){
        showLoader();
        e.preventDefault();
        var url =  $('#editLandUnitValueModal').find('form').attr('action');
        var method = $('#editLandUnitValueModal').find('form').attr('method');
        var data   = $('#editLandUnitValueModal').find('form').serialize();
        $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            if(html.status == 'success'){
                $('#editLandUnitValueModal').modal('hide');
                loadLandUnitValueListing();
            }
        },error:function(){
            hideLoader();
        }
    });

    });

    $(document).off('submit','#generalRevisionForm').on('submit','#generalRevisionForm',function(e){
        $('#brgy_code_id').prop('disabled',false);
        showLoader();
        e.preventDefault();
        var url =  $('#generalRevisionForm').attr('action');
        var method = $('#generalRevisionForm').attr('method');
        var data   = $('#generalRevisionForm').serialize();
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
                      timer: false
                    });
                loadRelatedTaxDeclarations();
                loadDraftedTaxDeclarations();
            }if(html.status == 'error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    });

            }
        },error:function(){
            hideLoader();
        }
    });

    });

    $(document).off('submit','#submitAssessementLevelForm').on('submit','#submitAssessementLevelForm',function(e){
        showLoader();
        e.preventDefault();
        var url =  $('#editAssessementLevelModal').find('form').attr('action');
        var method = $('#editAssessementLevelModal').find('form').attr('method');
        var data   = $('#editAssessementLevelModal').find('form').serialize();
        $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            if(html.status == 'success'){
                $('#editAssessementLevelModal').modal('hide');
                loadAssessmentLevelListing();
            }
        },error:function(){
            hideLoader();
        }
    });

    });

    $(document).off('submit','#submitBuildingUnitValueForm').on('submit','#submitBuildingUnitValueForm',function(e){
        showLoader();
        e.preventDefault();
        var url =  $('#editBuildingUnitValueModal').find('form').attr('action');
        var method = $('#editBuildingUnitValueModal').find('form').attr('method');
        var data   = $('#editBuildingUnitValueModal').find('form').serialize();
        $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            if(html.status == 'success'){
                $('#editBuildingUnitValueModal').modal('hide');
                loadBuildingUnitValueListing();
            }
        },error:function(){
            hideLoader();
        }
    });

    });

    $(document).off('submit','#submitPlantTreeUnitValueForm').on('submit','#submitPlantTreeUnitValueForm',function(e){
        showLoader();
        e.preventDefault();
        var url =  $('#editPlantTreesUnitValueModal').find('form').attr('action');
        var method = $('#editPlantTreesUnitValueModal').find('form').attr('method');
        var data   = $('#editPlantTreesUnitValueModal').find('form').serialize();
        $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            if(html.status == 'success'){
                $('#editPlantTreesUnitValueModal').modal('hide');
                loadPlantsTreesUnitValueListing();
            }
        },error:function(){
            hideLoader();
        }
    });

    })
    
});




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

function loadRelatedTaxDeclarations() {
    showLoader();
    var revisionYear = $('input[name=from_rvy_revision_year_id]').val();
    var brngyCode    = $('select[name=brgy_code_id]').val();
    var propertyKind = $('select[name=pk_id]').val();
    var filtervars = {
        revisionYear:revisionYear,
        brngyCode:brngyCode,
        propertyKind:propertyKind,
        "_token": $("#_csrf_token").val()
    };
    $.ajax({
        type: "POST",
        url: DIR+'generalrevision/loadtaxdeclarations',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#oldTaxDeclarations').html(html);
            /*$('input[name=brangay_name]').val(html.brgy_name);
            $('input[name=mun_desc]').val(html.mun_desc);*/
            
        },error:function(){
            hideLoader();
        }
    });
}

function loadDraftedTaxDeclarations() {
    showLoader();
    var revisionYear = $('input[name=to_rvy_revision_year_id]').val();
    var brngyCode    = $('select[name=brgy_code_id]').val();
    var propertyKind = $('select[name=pk_id]').val();
    var filtervars = {
        revisionYear:revisionYear,
        brngyCode:brngyCode,
        propertyKind:propertyKind,
        "_token": $("#_csrf_token").val()
    };
    $.ajax({
        type: "POST",
        url: DIR+'generalrevision/loaddraftedtaxdeclarations',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#newTaxDeclarations').html(html);
            /*$('input[name=brangay_name]').val(html.brgy_name);
            $('input[name=mun_desc]').val(html.mun_desc);*/
            
        },error:function(){
            hideLoader();
        }
    });
}

function loadLandUnitScheduleRatePanel() {
    showLoader();
    var fromRevisionYear = $('input[name=from_rvy_revision_year_id]').val();
    var toRevisionYear = $('input[name=to_rvy_revision_year_id]').val();
    var brngyCode    = $('select[name=brgy_code_id]').val();
    var propertyKind = $('select[name=pk_id]').val();
    var filtervars = {
        fromRevisionYear:fromRevisionYear,
        toRevisionYear:toRevisionYear,
        brngyCode:brngyCode,
        propertyKind:propertyKind,
        "_token": $("#_csrf_token").val()
    };
    $.ajax({
        type: "POST",
        url: DIR+'generalrevision/showlandunitvaluescheduleview',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#LandUnitValueScheduleModal').modal('show');
            $('#landunitvaluesceduleview').html(html);
            loadLandUnitValueListing();
        },error:function(){
            hideLoader();
        }
    });
}

function loadPlantsTreesUnitScheduleRatePanel() {
    showLoader();
    var fromRevisionYear = $('input[name=from_rvy_revision_year_id]').val();
    var toRevisionYear = $('input[name=to_rvy_revision_year_id]').val();
    var brngyCode    = $('select[name=brgy_code_id]').val();
    var propertyKind = $('select[name=pk_id]').val();
    var filtervars = {
        fromRevisionYear:fromRevisionYear,
        toRevisionYear:toRevisionYear,
        brngyCode:brngyCode,
        propertyKind:propertyKind,
        "_token": $("#_csrf_token").val()
    };
    $.ajax({
        type: "POST",
        url: DIR+'generalrevision/showplantstreesunitvaluescheduleview',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#plantsTreesUnitValueScheduleModal').modal('show');
            $('#plantstreesunitvaluesceduleview').html(html);
            loadPlantsTreesUnitValueListing();
        },error:function(){
            hideLoader();
        }
    });
}

function loadBuildingUnitScheduleRatePanel() {
    showLoader();
    var fromRevisionYear = $('input[name=from_rvy_revision_year_id]').val();
    var toRevisionYear = $('input[name=to_rvy_revision_year_id]').val();
    var brngyCode    = $('select[name=brgy_code_id]').val();
    var propertyKind = $('select[name=pk_id]').val();
    var filtervars = {
        fromRevisionYear:fromRevisionYear,
        toRevisionYear:toRevisionYear,
        brngyCode:brngyCode,
        propertyKind:propertyKind,
        "_token": $("#_csrf_token").val()
    };
    $.ajax({
        type: "POST",
        url: DIR+'generalrevision/showbuildingunitvaluescheduleview',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#buildingUnitValueScheduleModal').modal('show');
            $('#buildingunitvaluesceduleview').html(html);
            loadBuildingUnitValueListing();
        },error:function(){
            hideLoader();
        }
    });
}

function loadAssessementLevelScheduleRatePanel() {
    showLoader();
    var fromRevisionYear = $('input[name=from_rvy_revision_year_id]').val();
    var toRevisionYear = $('input[name=to_rvy_revision_year_id]').val();
    var brngyCode    = $('select[name=brgy_code_id]').val();
    var propertyKind = $('select[name=pk_id]').val();
    var filtervars = {
        fromRevisionYear:fromRevisionYear,
        toRevisionYear:toRevisionYear,
        brngyCode:brngyCode,
        propertyKind:propertyKind,
        "_token": $("#_csrf_token").val()
    };
    $.ajax({
        type: "POST",
        url: DIR+'generalrevision/showassessmentscheduleview',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#assessementLevelScheduleModal').modal('show');
            $('#assessementlevelsceduleview').html(html);
            loadAssessmentLevelListing();
        },error:function(){
            hideLoader();
        }
    });
}

function loadLandUnitValueListing() {
    showLoader();
    var fromRevisionYear = $('input[name=from_rvy_revision_year_id]').val();
    var toRevisionYear = $('input[name=to_rvy_revision_year_id]').val();
    var brngyCode    = $('select[name=brgy_code_id]').val();
    var propertyKind = $('select[name=pk_id]').val();
    var filtervars = {
        fromRevisionYear:fromRevisionYear,
        toRevisionYear:toRevisionYear,
        brngyCode:brngyCode,
        propertyKind:propertyKind,
        "_token": $("#_csrf_token").val()
    };
    $.ajax({
        type: "POST",
        url: DIR+'generalrevision/landunitvaluelisting',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#landUnitValueListing').html(html);
        },error:function(){
            hideLoader();
        }
    });
}

function loadPlantsTreesUnitValueListing() {
    showLoader();
    var fromRevisionYear = $('input[name=from_rvy_revision_year_id]').val();
    var toRevisionYear = $('input[name=to_rvy_revision_year_id]').val();
    var brngyCode    = $('select[name=brgy_code_id]').val();
    var propertyKind = $('select[name=pk_id]').val();
    var filtervars = {
        fromRevisionYear:fromRevisionYear,
        toRevisionYear:toRevisionYear,
        brngyCode:brngyCode,
        propertyKind:propertyKind,
        "_token": $("#_csrf_token").val()
    };
    $.ajax({
        type: "POST",
        url: DIR+'generalrevision/planttreesunitvaluelisting',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#plantstreesUnitValueListing').html(html);
        },error:function(){
            hideLoader();
        }
    });
}

function loadBuildingUnitValueListing() {
    showLoader();
    var fromRevisionYear = $('input[name=from_rvy_revision_year_id]').val();
    var toRevisionYear = $('input[name=to_rvy_revision_year_id]').val();
    var brngyCode    = $('select[name=brgy_code_id]').val();
    var propertyKind = $('select[name=pk_id]').val();
    var filtervars = {
        fromRevisionYear:fromRevisionYear,
        toRevisionYear:toRevisionYear,
        brngyCode:brngyCode,
        propertyKind:propertyKind,
        "_token": $("#_csrf_token").val()
    };
    $.ajax({
        type: "POST",
        url: DIR+'generalrevision/buildingunitvaluelisting',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#buildingUnitValueListing').html(html);
        },error:function(){
            hideLoader();
        }
    });
}

function loadAssessmentLevelListing() {
    showLoader();
    var fromRevisionYear = $('input[name=from_rvy_revision_year_id]').val();
    var toRevisionYear = $('input[name=to_rvy_revision_year_id]').val();
    var brngyCode    = $('select[name=brgy_code_id]').val();
    var propertyKind = $('select[name=pk_id]').val();
    var filtervars = {
        fromRevisionYear:fromRevisionYear,
        toRevisionYear:toRevisionYear,
        brngyCode:brngyCode,
        propertyKind:propertyKind,
        "_token": $("#_csrf_token").val()
    };
    $.ajax({
        type: "POST",
        url: DIR+'generalrevision/assessementlevellisting',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#assessementLevelListing').html(html);
        },error:function(){
            hideLoader();
        }
    });
}
