$(document).ready(function(){
    datatablefunction();

    $('#updateTaxRateScheduleModal').modal({backdrop: 'static', keyboard: false});
    $('#LandUnitValueScheduleModal').modal({backdrop: 'static', keyboard: false});
    $('#editLandUnitValueModal').modal({backdrop: 'static', keyboard: false});
    $('#plantsTreesUnitValueScheduleModal').modal({backdrop: 'static', keyboard: false});
    $('#editPlantTreesUnitValueModal').modal({backdrop: 'static', keyboard: false});
    $('#buildingUnitValueScheduleModal').modal({backdrop: 'static', keyboard: false});
    $('#editBuildingUnitValueModal').modal({backdrop: 'static', keyboard: false});
    $('#assessementLevelScheduleModal').modal({backdrop: 'static', keyboard: false});
    $('#editAssessementLevelModal').modal({backdrop: 'static', keyboard: false});
    loadSelectdTdsForTaxClearance($('input[name=id]').val());
    $('#commonModal').find("#rptc_owner_code").select3({
    placeholder: 'Select Property Owner',
    allowClear: true,
    dropdownParent: $('#commonModal').find("#rptc_owner_code").parent(),
    ajax: {
        url: DIR+'rptpropertyowner/getallclients',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
    $('#commonModal').find("#rptc_requestor_code").select3({
    placeholder: 'Select Requested By',
    allowClear: true,
    dropdownParent: $('#commonModal').find("#rptc_requestor_code").parent(),
    ajax: {
        url: DIR+'rptpropertyowner/getallclients',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
    $('#commonModal').find("#rptc_checked_by").select3({
    placeholder: 'Select Checked By',
    allowClear: true,
    dropdownParent: $('#commonModal').find("#rptc_requestor_code").parent(),
    ajax: {
        url: DIR+'taxclearance/gethremployees',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
    $('#commonModal').find("#rptc_prepared_by").select3({
    placeholder: 'Select Prepared By',
    allowClear: true,
    dropdownParent: $('#commonModal').find("#rptc_requestor_code").parent(),
    ajax: {
        url: DIR+'taxclearance/gethremployees',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});

    loadTdRemoteSelectList();
    
    $(document).on('keyup','.decimalvalue',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
        });
    /*$("#rptc_checked_by").change(function(){
        var id=$(this).val();
        chechById(id);
    })*/

    $('#rptc_checked_by').on('select3:select', function (e) {
        var data = e.params.data;
        $("#rptc_checked_position").val(data.description)
    });

    $('#rptc_or_no').on('select3:select', function (e) {
        var data = e.params.data;
        var amount   = parseFloat(data.total_paid_amount).toFixed(2);
        var ordate   = data.cashier_or_date;
        var cashierId = data.cashier_id;
        var cashierDId = data.ccdid;
        $('#rptc_or_amount').val(amount);
        $('#rptc_or_date').val(ordate);
        $('input[name=cashier_id]').val(cashierId);
        $('input[name=cashier_detail_id]').val(cashierDId);
    });
    /*$("#rptc_or_no").change(function(){
        var selected = $(this).find("option:selected");
        var amount   = parseFloat(selected.data('amount')).toFixed(2);
        var ordate   = selected.data('ordate');
        var cashierId = selected.data('cashierid');
        var cashierDId = selected.data('cashierdetailid');
        $('#rptc_or_amount').val(amount);
        $('#rptc_or_date').val(ordate);
        $('input[name=cashier_id]').val(cashierId);
        $('input[name=cashier_detail_id]').val(cashierDId);
    })*/
    /*$("#rptc_prepared_by").change(function(){
        var id=$(this).val();
        preparedById(id);
    })*/
    $('#rptc_prepared_by').on('select3:select', function (e) {
        var data = e.params.data;
        $("#rptc_prepared_position").val(data.description)
    });
    /*if($("#rptc_owner_code").val() > 0){
        var id=$("#rptc_owner_code").val();
        getprofiledata(id);
        loadOrnoRemoteSelectList();
    }*/
    $(document).on('change','.selectForPaymentDetails',function(){
        $('.selectForPaymentDetails').not(this).prop('checked', false); 
        datatablefunction($(this).val());
    });


    $("#rptc_owner_code").change(function(){
        var id=$(this).val();
        var text = $(this).find(':selected').text();
        getprofiledata(id);
        loadOrnoRemoteSelectList();
        var mainId = $('#id').val();
        $("#rptc_requestor_code").select3("trigger", "select", {
           data: { id: id ,text:text}
               });
            
        if(mainId == ''){

        $('.myCheckbox').prop('checked', true);
       
        $('#rptc_or_amount').val('');
        $('#rptc_or_date').val('');
        $('input[name=cashier_id]').val('');
        $('input[name=cashier_detail_id]').val('');
        loadTdRemoteSelectList();
    }
    });

    $('#rptc_requestor_code').change(function(){
        var mainId = $('#id').val();
        loadOrnoRemoteSelectList();
        $('#rptc_or_amount').val('');
        $('#rptc_or_date').val('');
        $('input[name=cashier_id]').val('');
        $('input[name=cashier_detail_id]').val('');
    });
    $(document).on('click','.myCheckbox',function() {
        if($(this).is(":checked")) {
            var id=$("#rptc_owner_code").val();
        } else {
         
        }
        loadTdRemoteSelectList();
    });
    $("#rp_td_no").change(function(){
        var id=$(this).val();
        getRptClient(id);
        datatablefunction(id);
        
    });
    $(document).off('click','.deleteSelectedTd').on('click','.deleteSelectedTd',function(){
        var url = $(this).data('url');
        var tdId = $(this).data('id');
        var parentId = $(this).data('parentid');
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
                      deleteSelectedTd(parentId,tdId,url);
                    }
                })
        
    });

    $(document).off('click','#searchTdNo').on('click','#searchTdNo',function(e){
        showLoader();
        e.preventDefault();
        var rpTdNo = $('select[name=rp_td_no]').val();
        var parentId = $('input[name=id]').val();
        var brngyCode    = $('input[name=brgy_code]').val();
        var url =  DIR+'taxclearance/searchbytd';
        var method = 'post';
        var data   = {
            rp_td_no:rpTdNo,
            parent_id:parentId,
            brgy_no:brngyCode,
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
                loadSelectdTdsForTaxClearance($('input[name=id]').val());
            }if(html.status == 'validation_error'){
                $('.validate-err').html('');
                    $('#err_'+html.field_name).html(html.error);
                    $('.'+html.field_name).focus();
            }
        },error:function(){
            hideLoader();
        }
    });

    });

    $(document).off('submit','#taxClearanceForm').on('submit','#taxClearanceForm',function(e){
         e.preventDefault();
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "Details will be save in the system.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then((result) => {
            if(result.isConfirmed){
        showLoader();
       
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
                $('#commonModal').modal('hide');
                Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    })
                $('#Jq_datatablelist').DataTable().ajax.reload();
                
            }if(html.status == 'error'){
                Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: html.msg,
                      showConfirmButton: true,
                      timer: false
                    })
                
            }if(html.status == 'validation_error'){
                $('.validate-err').html('');
                    $('#err_'+html.field_name).html(html.error);
                    $('.'+html.field_name).focus();
            }
        },error:function(){
            hideLoader();
        }
    });

               
            }
        })
        

    });

}); 

function deleteSelectedTd(parentid,id,url) {
    showLoader();
    $('.loadingGIF').show();
    var filtervars = {
        id:id,
        parent_id:parentid,
        "_token": $("#_csrf_token").val()
    }; 
    $.ajax({
        type: "post",
        url: url,
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
           loadSelectdTdsForTaxClearance($('input[name=id]').val());
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

function loadSelectdTdsForTaxClearance(id) {
    showLoader();
    var revisionYear = $('input[name=from_rvy_revision_year_id]').val();
    var brngyCode    = $('select[name=brgy_code_id]').val();
    var propertyKind = $('select[name=pk_id]').val();
    var filtervars = {
        id: id
    };
    $.ajax({
        type: "get",
        url: DIR+'taxclearance/loadselectedtds',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#loadSelectedTdsForTaxClearance').html(html);
            /*$('input[name=brangay_name]').val(html.brgy_name);
            $('input[name=mun_desc]').val(html.mun_desc);*/
            
        },error:function(){
            hideLoader();
        }
    });
}
function getprofiledata(id){
    $('.loadingGIF').show();
    var filtervars = {
        pid:id
    }; 
    $.ajax({
        type: "GET",
        url: DIR+'getClientTaxClearance',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            $('.loadingGIF').hide();
            arr = $.parseJSON(html);
            if(arr.length > 0){
                console.log(arr[0]['id']);
               
                 var address = '';

                if (arr[0]['rpo_address_house_lot_no']) {
                    address += arr[0]['rpo_address_house_lot_no'] + ',';
                }
                if (arr[0]['rpo_address_street_name']) {
                    address += arr[0]['rpo_address_street_name'] + ',';
                }

                if (arr[0]['rpo_address_subdivision']) {
                    address += arr[0]['rpo_address_subdivision'] + ',';
                }

                address += arr[0]['brgy_name'] + ',' + arr[0]['mun_desc'] + ',' + arr[0]['prov_desc'] + ',' + arr[0]['reg_region'];

           }
           $("#address").val(address);
           $("input[name=rptc_owner_tin_no]").val(arr[0]['p_tin_no']);
        }
    });

}

function getRptClient(id){
    $('.loadingGIF').show();
    var filtervars = {
        id:id
    }; 
    $.ajax({
        type: "GET",
        url: DIR+'getPropertyClientName',
        data: filtervars,
        dataType: "json",
        success: function(html){ 
            $('.loadingGIF').hide();
            if (html.suffix) {
    var clientName = '';

    if (html.rpo_first_name) {
        clientName += html.rpo_first_name + ' ';
    }

    if (html.rpo_middle_name) {
        clientName += html.rpo_middle_name + ' ';
    }

    if (html.rpo_custom_last_name) {
        clientName += html.rpo_custom_last_name + ', ';
    }

    if (html.suffix) {
        clientName += html.suffix;
    }

    $("#clientName").val(clientName);
} else {
    var clientName = '';

    if (html.rpo_first_name) {
        clientName += html.rpo_first_name + ' ';
    }

    if (html.rpo_middle_name) {
        clientName += html.rpo_middle_name + ' ';
    }

    if (html.rpo_custom_last_name) {
        clientName += html.rpo_custom_last_name;
    }

    $("#clientName").val(clientName);
   
}
    rp_tax_declaration_no = html.rp_tax_declaration_no;
    $('#paymentRecordTitle').text("Payment Recode [ " + rp_tax_declaration_no +" ]");
    $("#rptc_owner_tin_no").val(html.p_tin_no);     
        }
    });
}
function getTaxNo(id){
   $.ajax({
 
        url :DIR+'getTDNoTaxClearance', // json datasource
        type: "POST", 
        data: {
          "id": id,
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#rp_td_no").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function getTaxDeAll(){
   $.ajax({
 
        url :DIR+'getTDNoTaxClearanceAll', // json datasource
        type: "POST", 
        data: {
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#rp_td_no").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
function getOrNumbers(){
    var citizenId = $('select[name=rptc_owner_code]').val();
    var requesterId = $('#rptc_requestor_code').val();
   $.ajax({
        url :DIR+'getOrNoForOwner', // json datasource
        type: "POST", 
        data: {
            citizen_id:citizenId,
            requester:requesterId,
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#rptc_or_no").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}

function preparedById(id){
    $('.loadingGIF').show();
    var filtervars = {
        id:id
    }; 
    $.ajax({
        type: "GET",
        url: DIR+'getEmployeeTaxApproved',
        data: filtervars,
        dataType: "json",
        success: function(html){ 
            $('.loadingGIF').hide();
            $("#rptc_prepared_position").val(html.description)
        }
    });
}
function chechById(id){
    $('.loadingGIF').show();
    var filtervars = {
        id:id
    }; 
    $.ajax({
        type: "GET",
        url: DIR+'getEmployeeTaxRecommendin',
        data: filtervars,
        dataType: "json",
        success: function(html){ 
            $('.loadingGIF').hide();
            $("#rptc_checked_position").val(html.description)
        }
    });
}

function datatablefunction(id = 0)
{

    var dropdown_html=get_page_number('1'); 
    var table = $('#loadSelectedTdsPayment').DataTable({ 
        dom:'rtip',
        "bProcessing": true,
        "serverSide": true,
        "bDestroy": true,
        "searching": false,
        "order": [],
        "columnDefs": [{ orderable: false, targets: [0,8] }],
        
        "pageLength": 10,
        "ajax":{ 
            url :DIR+'taxclearance/getpaymentlist', // json datasource
            type: "GET", 
            "data": {
                id:id,
                "_token":$("#_csrf_token").val()
            }, 
            error: function(html){
            }
        },
        "columns": [
            { "data": "no" },
            { "data": "tax_payer" },
            { "data": "td_no" },
            { "data": "period_covered" },
            { "data": "assessed_value" },
            { "data": "or_no" },
            { "data": "or_amount" },
            { "data": "or_date" },
            { "data": "status" },
        ],
        drawCallback: function(s){ 
            var api = this.api();
            var info=table.page.info();
            var dropdown_html=get_page_number(info.recordsTotal,info.length);
          
        }
    });  
}

function loadTdRemoteSelectList() {
    var ownerCode = $("#rptc_owner_code").val();
    if($('.myCheckbox').is(":checked")){

        var ownerCode = $("#rptc_owner_code").val();
    }else{
        var ownerCode = 0;
    }
    $('#commonModal').find("#rp_td_no").select3({
    placeholder: 'Select Tax Declaration No.',
    allowClear: true,
    dropdownParent: $('#commonModal').find("#rp_td_no").parent(),
    ajax: {
        url: DIR+'tax-clearance/gettdsforajaxselectlist',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                rpo_code:ownerCode,
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
}

function loadOrnoRemoteSelectList() {
    var citizenId = $('select[name=rptc_owner_code]').val();
    var requesterId = $('#rptc_requestor_code').val();
    $('#commonModal').find("#rptc_or_no").select3({
    placeholder: 'Select OR No.',
    allowClear: true,
    dropdownParent: $('#commonModal').find("#rptc_or_no").parent(),
    ajax: {
        url: DIR+'getOrNoForOwner',
        dataType: 'json',
        method:'POST',
        delay: 250,
        data: function(params) {
            return {
                citizen_id:citizenId,
                requester:requesterId,
                "_token": $("#_csrf_token").val(),
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
}