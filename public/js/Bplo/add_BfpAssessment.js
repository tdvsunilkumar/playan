var isDuplicate=0;
var submitAction='';
$(document).ready(function(){
    $('input[type=radio][name=bfpas_payment_type]').change(function(){
        var id = this.value;
        managecheckdetails(id);  
    });
    $("#btn_addmoreCheque").click(function(){
        addMoreChequeDetails();
    })
    $("#btn_addmoreNature").unbind("click");
    $("#btn_addmoreNature").click(function(){
        if(!isDuplicate){
            addMoreNatureDetails();
        }
    })

    $(".btnCancelNature").click(function() {
        console.log("rmv");
        var thisval = $(this);
        var f_id = $(this).attr("f_id");
        
        if (f_id === "" || f_id === undefined || f_id == 0) {
            cancelNatureDetails(thisval);
        } else {
            cancelNatureConfirmAlert(thisval);
        }
    });

    $("#btn_addmoreBank").click(function(){
        addMoreBankDetails();
    })
    $(".btnCancelBank").click(function(){
        $(this).closest(".removeBankData").remove();
    });
     
    $("#jqCancelOr").click(function(){
        $("#orderCanceltModal").modal('show');
    })
  
    $("#or_no").keyup(function(){
        $("#err_or_no").html('');
        $("#jqPaidAmount").attr("disabled",false);
    })
    $("#bff_application_no, #bfpas_payment_or_no").keyup(function(){
        $("#jqPaidAmount").attr("disabled",false);
    })

    $("#uploadAttachment").click(function(){
        uploadAttachment();
    });
    $(".deleteEndrosment").click(function(){
        deleteEndrosment($(this));
    })

    if($("#id").val()>0){
        $("#cancelreason").select3({dropdownAutoWidth : false,dropdownParent: $("#orderCanceltModal")});
    }
    if($("#id").val()<=0){
        getOrNumber(0)
    }
    $('.saveData').click(function() {
        submitAction = $(this).val()
        $("#submitAction").val(submitAction);
    });
    $('#mainForm').submit(function(e) {
        $(".validate-err").html("");
        e.preventDefault();

        var bfpas_total_amount = +$("#bfpas_total_amount").val().replace(",", "");
        var bfpas_total_amount_paid = +$("#bfpas_total_amount_paid").val().replace(",", "");

        if(bfpas_total_amount<=0){
            $("#err_bfpas_total_amount").html("Please enter total tax due.");
            return false;
        }
        if(bfpas_total_amount_paid>0){
            if(bfpas_total_amount_paid<bfpas_total_amount){
                $("#err_bfpas_total_amount_paid").html("Paid amount should be equal to or greater than Total Tax Due.");
                return false;
            }
        }

        if(submitAction=='Make Payment'){
            if($("#bff_application_no").val()==''){
                $("#bff_application_no").focus();
                $("#err_bff_application_no").html("Please enter Application No.");
                return false;
            }else if(bfpas_total_amount_paid<=0){
                $("#err_bfpas_total_amount_paid").html("Please enter Amount Paid.");
                $("#bfpas_total_amount_paid").focus();
                return false;
            }else if($("#bfpas_payment_or_no").val()==''){
                $("#err_bfpas_payment_or_no").html("Please enter O.R. No.");
                $("#bfpas_payment_or_no").focus();
                return false;
            }
        }
        checkOrAppNoUsedOrNot(e);
    })
    $("#bfpas_payment_or_no").keyup(function(){
        $("#or_number").html($(this).val())
    })
    commonFunction();
});

function cancelNatureDetails(thisval){
    // Get the parent row of the clicked button
    var $row = thisval.closest('.removeNatureData');
    var rmv_amt = parseFloat($row.find('.baaf_amount_fee').val());
    console.log(rmv_amt);
    var pre_total_amt=parseFloat($("#bfpas_total_amount").val());
    var new_t_amt=pre_total_amt - rmv_amt;
    $("#bfpas_total_amount").val(new_t_amt);
    $(".finalTotal").html(new_t_amt);
    thisval.closest(".removeNatureData").remove();
    isDuplicate=0;
    var bcnt=0;
    $("#addmoreNatureDetails").find(".removeNatureData").each(function(id){
        $(this).find('.fmaster_id').attr("id",'fmaster_id'+bcnt);
        bcnt++;
    });
    var cnt = $("#addmoreNatureDetails").find(".removeNatureData").length;
    $("#hiddenNatureDtls").find('.fmaster_id').attr('id','fmaster_id'+cnt);
}
function deleteEndrosment(thisval){
    var fname = thisval.attr('fname');
    var aid = thisval.attr('aid');
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
               url :DIR+'fire-protection/cashiering/deleteAttachment', // json datasource
               type: "POST", 
               data: {
                    "id":$("#id").val(),
                    "fname": fname,
                    "_token": $("#_csrf_token").val(),
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
       url : DIR+'fire-protection/cashiering/uploadDocument',
       type : 'POST',
       data : formData,
       processData: false,  // tell jQuery not to process the data
       contentType: false,  // tell jQuery not to set contentType
       success : function(data) {
               hideLoader();
               var data = JSON.parse(data);
               if(data.ESTATUS==1){
                   $("#err_document").html(data.message);
               }else{
                   $("#document_name").val(null);
                   if(data!=""){
                       $("#DocumentDtls").html(data.documentList);
                        if ($('.showLess')) {
                            $('.showLess').shorten({
                                "showChars" : 25,
                                "moreText"    : "More",
                                "lessText"    : "Less"
                            });
                        }

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


function getOptionDetails(thisVar){
    var current_id = thisVar.val();
    var thisvar = thisVar;
    var attrid = thisVar.attr('id');
    isDuplicate=0;
    $('#addmoreNatureDetails').find('.fmaster_id').each(function(i, obj) {
       thisVar.closest(".removeNatureData").find('.jqOptionDetails').addClass("hide");
       if(attrid!=$(this).attr('id')){
           if(current_id==$(this).val() || current_id==""){
                isDuplicate=1;
                return false;
           }
       }
    });
    if(isDuplicate){
        thisVar.val('').trigger('change');
        thisVar.closest(".removeNatureData").find('.jqOptionDetails').removeClass("hide");
        thisVar.closest(".removeNatureData").find('.jqOptionDetails').html('<span class="validate-err">Don\'t select duplicate variables</span>');
        return false;
    }else{
        $.ajax({
        url :DIR+'fire-protection/cashiering/getOptionDetails', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "bf_id":thisVar.val(),
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            thisVar.closest(".removeNatureData").find('.jqOptionDetails').removeClass("hide");
            thisVar.closest(".removeNatureData").find('.jqOptionDetails').html(data.option);
            if ($('.showLess')) {
                thisVar.closest(".removeNatureData").find('.jqOptionDetails').find('.showLess').shorten({
                    "showChars" : 25,
                    "moreText"    : "More",
                    "lessText"    : "Less"
                });
            }
        }
    })
    }
}



async function checkOrAppNoUsedOrNot(e){
     await $.ajax({
        url :DIR+'fire-protection/cashiering/checkOrAppNoUsedOrNot', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "id":$("#id").val(),
            "or_no":$('#bfpas_payment_or_no').val(),
            "app_no":$('#bff_application_no').val(),
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            $("#err_bfpas_payment_or_no").html('');
            $("#err_bff_application_no").html('');
            $("#jqPaidAmount").attr("disabled",false);

            if(data.isOrUsed){
                $("#err_bfpas_payment_or_no").html(data.errORMsg);
                $("#jqPaidAmount").attr("disabled",true);
            }
            if(data.isAppUsed){
                $("#err_bff_application_no").html(data.errAppMsg);
                $("#jqPaidAmount").attr("disabled",true);
            }
            if(!data.isOrUsed && !data.isAppUsed){
                if(submitAction=='Make Payment'){
                    setConfirmAlert(e);
                }else{
                    finalSubmit();
                }
                
            }
        }
    })
}

function finalSubmit(){
    $('#mainForm').unbind('submit');
    $("#mainForm #jqSaveDraft").trigger("click");
    $("#mainForm #jqSaveDraft").attr("type","button");
}
function getOrNumber(checked){
    $.ajax({
        url :DIR+'fire-protection/cashiering/getOrnumber', // json datasource
        type: "POST", 
        data: {
            "orflag": checked, "_token": $("#_csrf_token").val(),
        },
        success: function(html){
            $('#or_no').val(html)
            $("#or_number").html(html)
        }
    })
}
function cancelNaturePaymentOption(thisval){
    $.ajax({
        url :DIR+'fire-protection/cashiering/cancelNaturePaymentOption', // json datasource
        type: "POST", 
        data: {
            "f_id":thisval.attr('f_id'),
            "_token": $("#_csrf_token").val(),
        },
        success: function(html){
            cancelNatureDetails(thisval)
        }
    })
}

function cancelNatureConfirmAlert(thisval){
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
            cancelNaturePaymentOption(thisval);
        }
    });
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
            $('#mainForm').unbind('submit');
            $("#mainForm input[name='submit']").trigger("click");
            $("#mainForm input[name='submit']").attr("type","button");
        }
    });
}

function addMoreBankDetails(){
    var html = $("#hiddenBankDtls").html();
    var prevLength = $("#addmoreBankDetails").find(".removeBankData").length;
    $("#addmoreBankDetails").append(html);
    $(".btnCancelBank").unbind("click");
    $(".btnCancelBank").click(function(){
        $(this).closest(".removeBankData").remove();
        var bcnt=0;
        $("#addmoreBankDetails").find(".removeBankData").each(function(id){
            $(this).find('.fund_id2').attr("id",'fund_id2_'+bcnt);
            $(this).find('.bank_id2').attr("id",'bank_id2_'+bcnt);
            bcnt++;
        });
        var cnt = $("#addmoreBankDetails").find(".removeBankData").length;

        $("#hiddenBankDtls").find('.fund_id2').attr('id','fund_id2_'+cnt);
        $("#hiddenBankDtls").find('.bank_id2').attr('id','bank_id2_'+cnt);

    });
    var classid = $("#addmoreBankDetails").find(".removeBankData").length;
    if (!$("#fund_id2_"+prevLength).hasClass("select3-hidden-accessible")){
        $("#fund_id2_"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreBankDetails")});
        $("#bank_id2_"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreBankDetails")});
    }
    $("#hiddenBankDtls").find('.fund_id2').attr('id','fund_id2_'+classid);
    $("#hiddenBankDtls").find('.bank_id2').attr('id','bank_id2_'+classid);

}
function addMoreNatureDetails(){
    var html = $("#hiddenNatureDtls").html();
    var prevLength = $("#addmoreNatureDetails").find(".removeNatureData").length;
    $("#addmoreNatureDetails").append(html);
    $(".btnCancelNature").unbind("click");
    $(".btnCancelNature").click(function(){
        isDuplicate=0;
        var thisval = $(this);
        // Find the closest parent row (removeNatureData)
        var $row = thisval.closest('.removeNatureData');

        // Find the .baaf_amount_fee input in the same row
        var $baafAmountField = $row.find('.baaf_amount_fee');

        // Retrieve the value from the .baaf_amount_fee input
        var rmv_amt = parseFloat($baafAmountField.val());
        console.log(rmv_amt);
        var pre_total_amt = parseFloat($("#bfpas_total_amount").val());
        var new_t_amt = pre_total_amt - rmv_amt;
        $("#bfpas_total_amount").val(new_t_amt);
        $(".finalTotal").html(new_t_amt);
        $(this).closest(".removeNatureData").remove();
         var bcnt=0;
        $("#addmoreNatureDetails").find(".removeNatureData").each(function(id){
            $(this).find('.fmaster_id').attr("id",'fmaster_id'+bcnt);
            bcnt++;
        });
        var cnt = $("#addmoreNatureDetails").find(".removeNatureData").length;
        $("#hiddenNatureDtls").find('.fmaster_id').attr('id','fmaster_id'+cnt);
        
    });
    var classid = $("#addmoreNatureDetails").find(".removeNatureData").length;
    $("#fmaster_id"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreNatureDetails")});
    $("#hiddenNatureDtls").find('.fmaster_id').attr('id','fmaster_id'+classid);
    commonFunction();
    
}
function commonFunction(){
    $(".fmaster_id").change(function(){
        getOptionDetails($(this));
    })
    $(".numeric").numeric({ decimal : "." });
    $(".baaf_amount_fee").keyup(function(){
        calculateTotalAmount();
    })
    $('.showLess').shorten({
        "showChars" : 25,
        "moreText"    : "More",
        "lessText"    : "Less"
    });
}

function calculateTotalAmount(){
    var totalAmount=0;
    $("#addmoreNatureDetails").find(".baaf_amount_fee").each(function(id){
        if($(this).val()>0){
            totalAmount += +$(this).val();
        }
    });
    $("#bfpas_total_amount").val(totalAmount.toFixed(2))
    $(".finalTotal").html(totalAmount.toFixed(2))
}
function addMoreChequeDetails(){
    var html = $("#hiddenChequeDtls").html();
    var prevLength = $("#addmoreChequeDetails").find(".removeChequeData").length;
    $("#addmoreChequeDetails").append(html);
    $(".btnCancelCheque").unbind("click");
    $(".btnCancelCheque").click(function(){
        $(this).closest(".removeChequeData").remove();
         var bcnt=0;
        $("#addmoreChequeDetails").find(".removeChequeData").each(function(id){
            $(this).find('.fund_id3').attr("id",'fund_id3_'+bcnt);
            $(this).find('.bank_id3').attr("id",'bank_id3_'+bcnt);
            $(this).find('.check_type_id3').attr("id",'check_type_id3_'+bcnt);
            bcnt++;
        });
        var cnt = $("#addmoreChequeDetails").find(".removeChequeData").length;
        
        $("#hiddenChequeDtls").find('.fund_id3').attr('id','fund_id3_'+cnt);
        $("#hiddenChequeDtls").find('.bank_id3').attr('id','bank_id3_'+cnt);
        $("#hiddenChequeDtls").find('.check_type_id3').attr('id','check_type_id3_'+cnt);
    });
    var classid = $("#addmoreChequeDetails").find(".removeChequeData").length;
    $("#fund_id3_"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreChequeDetails")});
    $("#bank_id3_"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreChequeDetails")});
    $("#check_type_id3_"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreChequeDetails")});

    $("#hiddenChequeDtls").find('.fund_id3').attr('id','fund_id3_'+classid);
    $("#hiddenChequeDtls").find('.bank_id3').attr('id','bank_id3_'+classid);
    $("#hiddenChequeDtls").find('.check_type_id3').attr('id','check_type_id3_'+classid);

}
function managecheckdetails(id){
    if(id =='3'){ $("#addmoreChequeDetails").removeClass('hide');}
    else{ $("#addmoreChequeDetails").addClass('hide'); }

    if(id =='2'){ $("#addmoreBankDetails").removeClass('hide');}
    else{ $("#addmoreBankDetails").addClass('hide'); }
}
