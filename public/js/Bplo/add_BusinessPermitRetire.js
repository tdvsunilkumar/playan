var submitAction='';
$(document).ready(function(){
    $("#sub_class_id").select3({dropdownAutoWidth : false,dropdownParent: $("#divSubclass")});
    $("#requirement_id").select3({dropdownAutoWidth : false,dropdownParent: $("#divRequirement")});
    $("#uploadAttachment").click(function(){
        uploadAttachment();
    });
    $(".deleteDocument").click(function(){
        deleteDocument($(this));
    })
    $("#busn_id").change(function(){
        getBusinessDetails();
    })
    $("#sub_class_id").change(function(){
        getRequirementList();
    })
    $(".check_all").unbind("click")
    $(".check_all").click(function(){
        if(this.checked){
            $("#entier_business").trigger('click');
            $('#jqLineOfBuss tbody input[type=\"checkbox\"]:not(:checked)').trigger('click');
        } else {
            $('#jqLineOfBuss tbody input[type=\"checkbox\"]:checked').trigger('click');
            $("#per_line").trigger('click');
        }
    })
    $('input[name="retire_application_type"]').unbind("click")
    $('input[name="retire_application_type"]').click(function(){
        if($(this).val()==2){
            $(".chk-box, .check_all").prop("checked",true)
        }else{
            $(".chk-box, .check_all").prop("checked",false)
        }
    });
    $('.saveData').click(function() {
        console.log('sub chek');
        submitAction = $(this).val()
        $("#submitAction").val(submitAction);
    });
   

    // Add click event handler for all submit buttons
// $('input[type=submit]').click(function() {
//     var submitAction = $(this).val();
//     $('#submitAction').val(submitAction);
// });

$('form').submit(function(e) {
    e.preventDefault();
    $(".validate-err").html('');
    // $("form input[name='submit']").unbind("click");
    var myform = $('form');
    var disabled = myform.find(':input:disabled').removeAttr('disabled');
    var data = myform.serialize().split("&");
    disabled.attr('disabled', 'disabled');
    var obj = {};
    for (var key in data) {
        obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
    }
    $.ajax({
        url: $(this).attr("action") + '/formValidation',
        type: "POST",
        data: obj,
        dataType: 'json',
        success: function(html) {
            if (html.ESTATUS) {
                if (html.ESTATUS == 2) {
                    $('#submitAction').val('');
                    Swal.fire({
                        title: "Oops...",
                        html: "Alert!<br/>You have to upload at least one document.",
                        icon: "warning",
                        type: "warning",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                    window.onkeydown = null;
                    window.onfocus = null;
                } else {
                    $("#err_" + html.field_name).html(html.error);
                    $("#" + html.field_name).focus();
                }
            } else {
                if ($('#submitAction').val() == 'Save As Draft') {
                    finalSubmit();
                } else {
                    setConfirmAlert(e);
                }
            }
        }
    });
});
    


    if($("#id").val()>0){
        getBusinessDetails();
    }
    $("#jqdeleteRetirement").click(function(){
        deleteRetirement();
    })
    commonFunction();
});

function deleteRetirement(){
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "Are you sure want to delete Retirement?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if(result.isConfirmed){
            deleteRetirementApplication();
        }
    });
}
function deleteRetirementApplication(){
     $.ajax({
        url :DIR+'business-permit-retire/deleteRetirementApplication', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "retirement_id":$("#id").val(),
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            if(!data.ESTATUS){
                location.reload();
            }
        }
    });
}

function finalSubmit(){
    $('#mainForm').unbind('submit');
    $("#mainForm #jqSaveDraft").trigger("click");
    $("#mainForm #jqSaveDraft").attr("type","button");
}
function setConfirmAlert(e){
    $("#mainForm input[name='submit']").unbind("click");
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "It will not change details after the confirmation.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if(result.isConfirmed){
            checkPendingAmountForRetire();
            // $('#mainForm').unbind('submit');
            // $("#mainForm input[name='submit']").trigger("click");
            // $("#mainForm input[name='submit']").attr("type","button");
        }
    });
}
function checkPendingAmountForRetire(){
    showLoader();
    $.ajax({
        url :DIR+'business-permit-retire/checkPreviousPendingAmt', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "busn_id":$("#busn_id").val(),
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            hideLoader();
            if(data.ESTATUS){
                Swal.fire({
                    title: "Oops...",
                    html: data.message,
                    icon: "warning",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                });
            }else{
                $('#mainForm').unbind('submit');
                $("#mainForm input[name='submit']").trigger("click");
                $("#mainForm input[name='submit']").attr("type","button");
            }
        }
    });
}
function getRequirementList(){
     $.ajax({
        url :DIR+'business-permit-retire/getRequirementList', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "sub_class_id":$("#sub_class_id").val(),
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            if(!data.ESTATUS){
                $("#requirement_id").html(data.html)
            }
        }
    });
}
function getBusinessDetails(){
    $.ajax({
        url :DIR+'business-permit-retire/getBusinessDetails', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "busn_id":$("#busn_id").val(),
            "id":$("#id").val(),
            "retire_application_type":$('input[name="retire_application_type"]:checked').val(),
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            if(!data.ESTATUS){
                $("#jqFeeDetails").html(data.html);
                $('input[name="busn_name"]').val(data.busn_name)
                $('input[name="busn_id_no"]').val(data.busn_id_no)
                if($("#id").val()<=0){
                    $('input[name="retire_bldg_area"]').val(data.busn_bldg_area)
                    $('input[name="retire_bldg_total_floor_area"]').val(data.busn_bldg_total_floor_area)
                    $('input[name="retire_employee_no_female"]').val(data.busn_employee_no_female)
                    $('input[name="retire_employee_no_male"]').val(data.busn_employee_no_male)
                    $('input[name="retire_employee_total_no"]').val(data.busn_employee_total_no)
                    $('input[name="retire_employee_no_lgu"]').val(data.busn_employee_no_lgu)
                    $('input[name="retire_vehicle_no_van_truck"]').val(data.busn_vehicle_no_van_truck)
                    $('input[name="retire_vehicle_no_motorcycle"]').val(data.busn_vehicle_no_motorcycle)
                }
                if(!data.isPrevPermitIssuance){
                    $(".saveData").attr("disabled",true);
                    $(".note").css({'color':'red'})
                    $(".note").html('No Licensed Issued');
                }else{
                    $(".saveData").attr("disabled",false);
                    $(".note").css({'color':'green'})
                    $(".note").html('License Issued Dated: '+data.issue_date);
                }
                commonFunction();
            }
        }
    });
}
function commonFunction(){
    $(".numeric").numeric({ decimal : "." });
    if ($('.showLess')) {
        $('.showLess').shorten({
            "showChars" : 25,
            "moreText"    : "More",
            "lessText"    : "Less"
        });
    }
    if ($('.showLessDoc')) {
        $('.showLessDoc').shorten({
            "showChars" : 50,
            "moreText"    : "More",
            "lessText"    : "Less"
        });
    }

    
    $(".chk-box").unbind('click');
    $(".chk-box").click(function(){
        var totalCnt = $(".chk-box").length;
        var totalChecked = $(".chk-box:checked").length;
        if(totalChecked>0 && (totalCnt==totalChecked)){
            $("#entier_business").prop("checked",true)
        }else{
          $("#per_line").prop("checked",true)
        }
    })
    $(".calculateEmp").keyup(function(){
        var male =+$("#retire_employee_no_male").val();
        var female =+$("#retire_employee_no_female").val();
        var total = male+female;
        $("#retire_employee_total_no").val(total);
    })
}
function uploadAttachment(){
    $(".validate-err").html("");
    if($("#sub_class_id").val()==""){
        $("#err_sub_class_id").html("Please select Line Of Business");
        return false;
    }else if($("#requirement_id").val()==""){
        $("#err_requirement_id").html("Please select requirement");
        return false;
    }else if (typeof $('#document_name')[0].files[0]== "undefined") {
        $("#err_document").html("Please upload Document");
        return false;
    }
    var formData = new FormData();
    formData.append('file', $('#document_name')[0].files[0]);
    formData.append('id', $("#id").val());
    formData.append('sub_class_id', $("#sub_class_id").val());
    formData.append('sub_class_name', $("#sub_class_id option:selected").text());
    formData.append('requirement_name', $("#requirement_id option:selected").text());
    formData.append('requirement_id', $("#requirement_id").val());
    showLoader();
    $.ajax({
       url : DIR+'business-permit-retire/uploadDocument',
       type : 'POST',
       data : formData,
       processData: false,  // tell jQuery not to process the data
       contentType: false,  // tell jQuery not to set contentType
       success : function(data) {
           hideLoader();
           var data = JSON.parse(data);
           if(data.ESTATUS==1){
               $("#err_requirement_id").html(data.message);
           }else{
                $("#requirement_id").val(0);
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
                commonFunction();
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
               url :DIR+'business-permit-retire/deleteAttachment', // json datasource
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
                     title: 'Document Removed Successfully.',
                     showConfirmButton: false,
                     timer: 1500
                   })
               }
           })
       }
   })
}
