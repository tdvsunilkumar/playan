var isDuplicate=0;
$(document).ready(function(){
     if($("#id").val()<=0){
           select3AjaxCommunity("client_citizen_id","communitytaxtaxpayers","community-tax/gettaxpayerssearch");
           select3Ajax("client_topno","topnumberajax","cashier/Miscellaneous/getTopNumbersAjax");
    }
    $("#cashier_or_date").datepicker({
              dateFormat: 'dd/mm/yy',
              minDate: -1,
              maxDate: 0
      });
    $(".numeric").numeric({ decimal : "." });
    $('input[type=radio][name=payment_terms]').change(function(){
        var id = this.value;
        managecheckdetails(id);  

    });
    $("#btn_addmoreCheque").click(function(){
        addMoreChequeDetails();
    })
    $(".btnCancelCheque").click(function(){
        $(this).closest(".removeChequeData").remove();
    });

    $("#btn_addmoreBank").click(function(){
        addMoreBankDetails();
    })
    $(".btnCancelBank").click(function(){
        $(this).closest(".removeBankData").remove();
    });
    $("#btn_addmoreNature").unbind("click");
    $("#btn_addmoreNature").click(function(){
        if(!isDuplicate){
            addMoreNatureDetails();
        }
    })
    $(".btnCancelNature").click(function(){
        var thisval = $(this);
        if($(this).attr("f_id")>0){
            cancelNatureConfirmAlert(thisval);
        }else{
            cancelNatureDetails(thisval);
        }
    });
    $("#total_paid_amount").keyup(function(){
       calculateChangeAmt();
    })
    $("#jqCancelOr").click(function(){
        $("#orderCanceltModal").modal('show');
    })
   
    $("#or_no").keyup(function(){
        $("#err_or_no").html('');
        $("#jqPaidAmount").attr("disabled",false);
        $("#or_number").html($(this).val())
    })

    $('#isuserrange').click(function() {
        $("#jqPaidAmount").attr("disabled",false);
        var checked = '0'; 
        $("#or_no").addClass("disabled-field");
        if ($(this).is(':checked')) {
            checked = '1'; 
           $("#or_no").removeClass("disabled-field");
        }
        getOrNumber(checked)
    });
    $(".payee_type").click(function(){
        $("#userDetails").addClass("hide");
        var id =$(this).val();
        if(id == 1){
            $("#addtaxpayerorcitizen").attr('href',DIR+'allclients');
            $('#topnumberajax').addClass('disabled-field');
        }else{
            $("#addtaxpayerorcitizen").attr('href',DIR+'citizens');
            $('#topnumberajax').removeClass('disabled-field');
        }
        $("#addmoreNatureDetails").find(".removeNatureData").remove();
        $("#hiddenNatureDtls").find('.tfoc_id').attr('id','tfoc_id0');
        $("#client_citizen_id").empty();
        getTfocDropdown($(this).val());

    })
    if($("#id").val()<=0){
        getOrNumber(0)
    }
    $('#mainForm').submit(function(e) {
        e.preventDefault();
        $(".validate-err").html("");
        if($("#client_citizen_id").val()>0){}else{
            $("#err_client_citizen_id").html("Please select name.");
            return false;
        }
        if($("#total_amount").val()<=0){
            $("#err_bfpas_total_amount").html("Please add Total Tax Due.");
            return false;
        }

        $("#err_total_paid_amount").html('');
        if($("#total_paid_amount").val()<=0){
            $("#err_total_paid_amount").html("Please enter amount paid.");
            return false;
        }
        var total_paid_amount = +$("#total_paid_amount").val().replace(",", "");
        var net_tax_due_amount = +$("#total_amount").val().replace(",", "");
        if(total_paid_amount<net_tax_due_amount){
            $("#err_total_paid_amount").html("Paid amount should be equal to or greater than Total Tax Due.");
            return false;
        }
        checkOrUsedOrNot(e);
    })

    if($("#id").val()>0){
        $("#cancelreason").select3({dropdownAutoWidth : false,dropdownParent: $("#orderCanceltModal")});
    }
    $("#client_citizen_id").change(function(){
        if($(this).val()==""){
          $("#topnumberajax").removeClass('disabled-field');
        }else{
          $("#topnumberajax").addClass('disabled-field');
        }
        getUserDetails();
    })

    var topno =  $("#client_topno option:selected").val(); 
    if(topno !=""){ getUserDetailstopid(); }
    $("#client_topno").change(function(){
        if($(this).val()==""){
             $('#btn_addmoreNature').show();
             $('#communitytaxtaxpayers').removeClass('disabled-field');
             $("#addmoreNatureDetails").find(".removeNatureData").remove();
             $("#ownar_name").html('');
             $("#total_amount").val('');
             $("#client_citizen_id").prop('required',true);
             $(".finalTotal").html('');
             $("#cashier_particulars").val('');
             $("#ownar_address").html('');
             $("#taxpayers").prop("disabled", false);
             $("#clientdivshow").addClass('hide');
             $("#clientdivselect").removeClass('hide');
        }else{
        getUserDetailstopid();
         $("#citizens").prop("checked", true);
         $("#taxpayers").prop("disabled", true);
         $('#communitytaxtaxpayers').addClass('disabled-field');
         $('#btn_addmoreNature').hide();
         $("#client_citizen_id").prop('required',false);
         $("#clientdivshow").removeClass('hide');
         $("#clientdivselect").addClass('hide');
        }
        
    })
    $("#refreshCitizen").click(function(){
       var payeetype =  $("input[type='radio'][name='payee_type']:checked").val();
       //getUserList(payeetype);
    });
    $("#uploadAttachment").click(function(){
        uploadAttachment();
    });
    $(".deleteEndrosment").click(function(){
        deleteEndrosment($(this));
    })
    $("#various_payment").click(function(){
        if ($(this).is(':checked')) {
            $("#cashier_particulars").val("Various Payments")
        }else{
            $("#cashier_particulars").val("");
            addPerticulars();
        }
    })
    if($("#id").val()>0){
        getUserDetails();
    }
    commonFunction();
});

function getUserDetailstopid(){
     $.ajax({
        url :DIR+'cashier/Miscellaneous/getUserbytoid', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "topid":$("#client_topno").val(),
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            if(!data.ESTATUS){
                $("#userDetails").removeClass("hide");
                $("#ownar_name").html(data.name);
                $("#total_amount").val(data.amount.toFixed(2));
                $(".finalTotal").html(data.amount.toFixed(2))
                // $('#client_citizen_id>option[value='+data.citid+']').prop('selected', true);
                // $('#client_citizen_id').select3().val($("#client_citizen_id").val());
                $('#client_citizen_id').val(data.citid);
                $("#clientcitizenidshow").val(data.name);
                $("#clientdivshow").removeClass('hide');
                $("#clientdivselect").addClass('hide');
                $('#clientdiv').addClass('disabled-field');
                $('#btn_addmoreNature').hide();
                $("#ownar_address").html(data.address);
                $("#cashier_particulars").val("Civil Register Permit");
                   var hoid = data.hoid;  var filtervars = {
                    hoid:hoid
                  }; 
                $.ajax({
                    type: "post",
                    url: DIR+'cashier/Miscellaneous/getallFees',
                    data: filtervars,
                    dataType: "html",
                    success: function(html){ 
                      $('.loadingGIF').hide();
                       $("#FeesDetails").html(html); 
                    }
                  });
            }else{
                $("#ownar_name").html('');
                $("#ownar_address").html('');
            }
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
       url : DIR+'cashier/Miscellaneous/uploadDocument',
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
               url :DIR+'cashier/Miscellaneous/deleteAttachment', // json datasource
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

function getUserDetails(){
    $.ajax({
        url :DIR+'cashier/Miscellaneous/getUserDetails', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "payee_type": $('input[name="payee_type"]:checked').val(), 
            "user_id":$("#client_citizen_id").val(),
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            if(!data.ESTATUS){
                $("#userDetails").removeClass("hide");
                $("#ownar_name").html(data.name);
                $("#ownar_address").html(data.address);
            }else{
                $("#ownar_name").html('');
                $("#ownar_address").html('');
            }
        }
    })
}
function calculateChangeAmt(){
    var netAmt = $("#total_amount").val();
    netAmt = +netAmt.replace(",", "");
    var paidAmt = +$("#total_paid_amount").val();
    
    if(paidAmt>netAmt){
        var dueAmt = +paidAmt - (+netAmt);
        $("#total_amount_change").val(dueAmt.toFixed(2))
    }else{
        $("#total_amount_change").val('0.00')
    }
}
function getUserList(payee_type){
    $("#client_citizen_id").empty();
    showLoader();
    $.ajax({
        url :DIR+'cashier/Miscellaneous/getUserList', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "payee_type": payee_type, 
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            if(!data.ESTATUS){
                $("#client_citizen_id").html(data.option)
                hideLoader();
            }
        },
        error: function () {
            // Hide the loader in case of an error
            hideLoader();
        }
    })
}
function getTfocDropdown(payee_type){
    $.ajax({
        url :DIR+'cashier/Miscellaneous/getTfocDropdown', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "payee_type": payee_type, 
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            if(!data.ESTATUS){
                $("#hiddenNatureDtls").find(".tfoc_id").html(data.option)
            }
        }
    })
}

$('#formdtlcancelid').submit(function(e) {
        e.preventDefault();
        showLoader();
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
                location.reload();
            }if(html.status == 'verifypsw'){
                $('#verifyPsw').modal('show');
                $('#verifyPsw').find('.validate-err').html('');
                $('#verifyPsw').find('input[name=verify_psw]').val('');
                
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

    $('#commonModal').off('submit','#verifyPswForm').on('submit','#verifyPswForm',function(e){
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
                  timer: false
                }).then(function() {
                    $('#verifyPsw').modal('hide');
                });
                
            }if(html.status == 'validation_error'){
                $('#verifyPswForm').find("#err_"+html.field_name).html(html.error);
                $('#verifyPswForm').find("."+html.field_name).focus();
                
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
    
function cancelNatureDetails(thisval){
    thisval.closest(".removeNatureData").remove();
    isDuplicate=0;
    var bcnt=0;
    $("#addmoreNatureDetails").find(".removeNatureData").each(function(id){
        $(this).find('.tfoc_id').attr("id",'tfoc_id'+bcnt);
        bcnt++;
    });
    var cnt = $("#addmoreNatureDetails").find(".removeNatureData").length;
    $("#hiddenNatureDtls").find('.tfoc_id').attr('id','tfoc_id'+cnt);
}

function addMoreNatureDetails(){
    var html = $("#hiddenNatureDtls").html();
    var prevLength = $("#addmoreNatureDetails").find(".removeNatureData").length;

    $("#addmoreNatureDetails").append(html);
    $(".btnCancelNature").unbind("click");
    $(".btnCancelNature").click(function(){
        isDuplicate=0;
        var thisval = $(this);
        if($(this).attr("f_id")>0){
            cancelNatureConfirmAlert(thisval);
        }else{
            cancelNatureDetails(thisval);
        }
        
    });
    var classid = $("#addmoreNatureDetails").find(".removeNatureData").length;
    $("#tfoc_id"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreNatureDetails")});
    $("#hiddenNatureDtls").find('.tfoc_id').attr('id','tfoc_id'+classid);
    commonFunction();
    
}
function commonFunction(){
    $(".numeric").numeric({ decimal : "." });
    $(".tfc_amount").keyup(function(){
        calculateTotalAmount();
    })
    $(".tfoc_id").change(function(){
        addPerticulars();
        getAmountDetails($(this));
    })
    if ($('.showLess')) {
        $('.showLess').shorten({
            "showChars" : 25,
            "moreText"    : "More",
            "lessText"    : "Less"
        });
    }
}
function addPerticulars(){
    var perticulars="";
    if($("#tfoc_id0").val()>0 && $("#cashier_particulars").val()==''){
        perticulars=$("#tfoc_id0  option:selected").text();
        $("#cashier_particulars").val(perticulars);
    }
    
}
function getAmountDetails(thisVar){
    var current_id = thisVar.val();
    var thisvar = thisVar;
    var attrid = thisVar.attr('id');
    isDuplicate=0;
    $('#addmoreNatureDetails').find('.tfoc_id').each(function(i, obj) {
       if(attrid!=$(this).attr('id')){
           if(current_id==$(this).val() || current_id==""){
                isDuplicate=1;
                return false;
           }
       }
    });
    if(isDuplicate){
        thisVar.val('').trigger('change');
        thisVar.closest(".removeNatureData").find('.tfoc_id').next('<span class="validate-err">Don\'t select duplicate variables</span>');
        return false;
    }else{
        $.ajax({
            url :DIR+'cashier/Miscellaneous/getAmountDetails', // json datasource
            type: "POST", 
            dataType: "JSON", 
            data: {
                "tfoc_id":thisVar.val(),
                "_token": $("#_csrf_token").val(),
            },
            success: function(data){
                thisVar.closest(".removeNatureData").find('.tfc_amount').val(data.amount)
                calculateTotalAmount();
            }
        })
    }
}

function calculateTotalAmount(){
    var totalAmount=0;
    $("#addmoreNatureDetails").find(".tfc_amount").each(function(id){
        if($(this).val()>0){
            totalAmount += +$(this).val();
        }
    });
    $("#total_amount").val(totalAmount.toFixed(2))
    $(".finalTotal").html(totalAmount.toFixed(2))
}

async function checkOrUsedOrNot(e){
     await $.ajax({
        url :DIR+'cashier/Miscellaneous/checkOrUsedOrNot', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "or_no":$('#or_no').val(),
            "cashier_id":$('#id').val(),
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            if(data.isUsed){
                $("#err_or_no").html(data.errMsg);
                $("#jqPaidAmount").attr("disabled",true);
            }else{
                setConfirmAlert(e);
                $("#err_or_no").html('');
                $("#jqPaidAmount").attr("disabled",false);
            }
        }
    })
}


function getOrNumber(checked){
    $.ajax({
        url :DIR+'cashier/Miscellaneous/getOrnumber', // json datasource
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
        text: "Are you sure want to delete this?",
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
function cancelNaturePaymentOption(thisval){
    $.ajax({
        url :DIR+'cashier/Miscellaneous/cancelNaturePaymentOption', // json datasource
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
