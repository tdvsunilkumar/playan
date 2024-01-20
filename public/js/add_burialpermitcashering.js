var isDuplicate=0;
$(document).ready(function(){
	
	if($("#tfoc_id0").val()>0 && $("#cashier_particulars").val()==''){
       $("#cashier_particulars").val("");
         addPerticulars(); 
    }
	
    $("#cashier_or_date").datepicker({
              dateFormat: 'dd/mm/yy',
              minDate: -1,
              maxDate: 0
      });
    
    if($("#id").val()<=0){
    select3Ajax("death_causedid","burialinformationdiv","cashier/burial-permit/getDeathcauses");
    $("#cm_id").select3({dropdownAutoWidth : false,dropdownParent: $("#burialinformationdiv")});
    $("#expired_id").select3({dropdownAutoWidth : false,dropdownParent: $("#burialinformationdiv")});
    }
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
        }else{
            $("#addtaxpayerorcitizen").attr('href',DIR+'citizens');
        }
        $("#addmoreNatureDetails").find(".removeNatureData").remove();
        $("#hiddenNatureDtls").find('.tfoc_id').attr('id','tfoc_id0');
        getUserList($(this).val());
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
        getUserDetails();
        addMoreNatureDetailsadd();
    })
     $("#expired_id").change(function(){
        getFullnameDetails();
    })

    $("#death_date").change(function(){
        calculateage();
    }) 

    $("#refreshCitizen").click(function(){
       var payeetype =  $("input[type='radio'][name='payee_type']:checked").val();
       getUserList(payeetype);
    });
    
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
        getFullnameDetails();
    }
    commonFunction();
});

function getUserDetails(){
    $.ajax({
        url :DIR+'cashier/burial-permit/getUserDetails', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "payee_type": '2', 
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
function getFullnameDetails(){
    $.ajax({
        url :DIR+'cashier/burial-permit/getUserDetails', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "payee_type": '2', 
            "user_id":$("#expired_id").val(),
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            if(!data.ESTATUS){
                $("#userDetails").removeClass("hide");
                $("#expiryaddress").val(data.address);
                $("#dateofbirth").val(data.cit_date_of_birth);
                $("#sex").val(data.cit_gender);
                $("#expired_name").val(data.name);
                $("#nationality").val(data.country);
                calculateage();
            }else{
                $("#ownar_address").val('');
                $("#dateofbirth").val('');
                $("#sex").val('');
                $("#nationality").val('');
            }
        }
    })
}

function calculateage(){
  var start = $('#dateofbirth').val();
  var end   = new Date($('#death_date').val());
  if(start!="" && end !=""){
    // Parse the birthdate string into a Date object
    var birthdateObj = new Date(start);
    
    // Get the current date
    var currentDate = new Date(end);
    
    // Calculate the time difference in milliseconds
    var timeDifference = currentDate - birthdateObj;
    
    // Calculate the age in years
    var age = Math.floor(timeDifference / (365.25 * 24 * 60 * 60 * 1000));
    if(isNaN(age)){$('#age').val('0'); } else{ $('#age').val(age);}
    // Display the age
    
   }else{
    $('#age').val('0');
   }
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
    $.ajax({
        url :DIR+'cashier/burial-permit/getUserList', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "payee_type": payee_type, 
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            if(!data.ESTATUS){
                $("#client_citizen_id").html(data.option)
            }
        }
    })
}
function getTfocDropdown(payee_type){
    $.ajax({
        url :DIR+'cashier/burial-permit/getTfocDropdown', // json datasource
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

function addMoreNatureDetailsadd(){
    var html = $("#hiddenNatureDtls").html();
    var prevLength = $("#addmoreNatureDetails").find(".removeNatureData").length;

    $("#addmoreNatureDetails").append(html);
    var feeid = $("#addmoreNatureDetails").find($('.tfoc_id >option:eq('+1+')').prop('selected', true)).val();
    var currid = $("#addmoreNatureDetails").find($('.tfoc_id>option:eq('+1+')'));
        $.ajax({
            url :DIR+'cashier/burial-permit/getAmountDetails', // json datasource
            type: "POST", 
            dataType: "JSON", 
            data: {
                "tfoc_id":feeid,
                "_token": $("#_csrf_token").val(),
            },
            success: function(data){
                currid.closest(".removeNatureData").find('.tfc_amount').val(data.amount)
                calculateTotalAmount();
            }
        })
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
            url :DIR+'cashier/burial-permit/getAmountDetails', // json datasource
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
        url :DIR+'cashier/burial-permit/checkOrUsedOrNot', // json datasource
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
        url :DIR+'cashier/burial-permit/getOrnumber', // json datasource
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
        url :DIR+'cashier/burial-permit/cancelNaturePaymentOption', // json datasource
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


