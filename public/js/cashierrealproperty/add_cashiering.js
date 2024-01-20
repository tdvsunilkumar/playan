$(document).ready(function(){
    $("#cashier_or_date").datepicker({
              dateFormat: 'dd/mm/yy',
              minDate: -1,
              maxDate: 0
      });
    getPaymentDetails($("#top_transaction_id").val());
    loadAcceptedTds();
    loadCasheiringInfo();
    loadTopRemoteAjaxList();
    $("#total_paid_amount").keyup(function(){
               calculateChangeAmt();
            })
	//$("#top_transaction_id").select3({dropdownAutoWidth : false,dropdownParent : '#commonModal'});
    $(".numeric").numeric({ decimal : "." });
	
    $('input[type=radio][name=payment_terms]').change(function(){
        var id = this.value;
        managecheckdetails(id);  
    });

    $('#commonModal').on('hidden.bs.modal', function () {
        $('#Jq_datatablelist').DataTable().ajax.reload();
    });

    $(document).off('click',".acceptTdsFromHere").on('click',".acceptTdsFromHere",function(){
        acceptTdsForComputation($(this));
    });
    $(".acceptTdsFromHere").unbind('click');

    $(document).off('click',".removeTdsFromHere").on('click',".removeTdsFromHere",function(){
        removeTdsForComputation($(this));
    });
    $(".removeTdsFromHere").unbind('click');

    $(".btnCancelCheque").click(function(){
        $(this).closest(".removeChequeData").remove();
    });

    $("#btn_addmoreBank").click(function(){
        addMoreBankDetails();
    });

    $("#btn_addmoreCheque").click(function(){
        addMoreChequeDetails();
    })

    $(".btnCancelBank").click(function(){
        $(this).closest(".removeBankData").remove();
    });
    $("#top_transaction_id").change(function(){
        var mainId = $('#id').val();
        if(mainId <= 0){
            clearTaxRangeFields();
        }
        $("#cancelreason").select3({dropdownAutoWidth : false,dropdownParent: $("#orderCanceltModal")});
        getPaymentDetails($(this).val());
        
    });

    $("#jqCancelOr").click(function(){
        $("#orderCanceltModal").modal('show');
    })
    $("#jqApplyCredit").click(function(){
        applyCreditAmt();
    })
    $("#jqRemoveApplyCredit").click(function(){
        removeAppliedCreditAmt();
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
    if($("#id").val()<=0){
        getOrNumber(0);
    }
    $('#mainForm').submit(function(e) {
        e.preventDefault();
        $("#err_total_paid_amount").html('');
        if($("#top_transaction_id").val()<=0){
            $("#err_top_transaction_id").html("Please select transaction.");
            $("#top_transaction_id").focus();
            return false;
        }
        if($("#total_paid_amount").val()<=0){
            $("#err_total_paid_amount").html("Please enter amount paid.");
            return false;
        }
        var total_paid_amount = $("#total_paid_amount").val().replace(/\,/g,'');
        var net_tax_due_amount = $("#net_tax_due_amount").val().replace(/\,/g,'');
        if(parseFloat(total_paid_amount) == 0 || parseFloat(total_paid_amount) < parseFloat(net_tax_due_amount)){
            $("#err_total_paid_amount").html("Paid amount should be equal to or greater than Net Tax Due.");
            return false;
        }
        checkOrUsedOrNot(e);
    });

    $('#cancelOrForm').submit(function(e) {
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

    
    if($("#top_transaction_id").val()>0){
        getPaymentDetails($("#top_transaction_id").val());
        $("#cancelreason").select3({dropdownAutoWidth : false,dropdownParent: $("#orderCanceltModal")});
    }
});

function acceptTdsForComputation(ele) {
    showLoader();
    $('#previous_cashier_id').val('')
    var url       = ele.data('url');
    var td        = ele.data('td');
    var preCashId = $('#previous_cashier_id').val();
    var id        = $('#id').val();
     $.ajax({
        url :url, // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "td_no":td,
            "previous_cashier_id":preCashId,
            "id"                 :id,
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            hideLoader();
            if(data.status == 'success'){
                Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: data.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            clearTaxRangeFields(data.data.fromYear,data.data.toYear,data.data.srtQtr,data.data.endQur);
                            /* Refresh List of Billings */
                            getPaymentDetails($("#top_transaction_id").val(),1);
                            /* Refresh List of Billings */
                            loadAcceptedTds();
                            loadCasheiringInfo();
                            $('#total_paid_amount').val('0.00');
                            $('#total_amount_change').val('0.00');
                            $("#tcm_id").val(0);
                            $("#tax_credit_gl_id").val(0);
                            $("#tax_credit_sl_id").val(0);
                            $("#jqApplyCredit").addClass("hide");
                            $("#jqRemoveApplyCredit").addClass("hide");
                            /* Tax Credit Related information */
                            setTimeout(function() { 
                                //alert(data.data.tax_credit_amount);
                                    $('#loadCasheirngInfoHere').find("#prev_tax_credit_amount").val(data.data.tax_credit_amount);
                                    if(data.data.tax_credit_amount>0){
                                     var total = $('#net_tax_due_amount').val().replace(/\,/g,'');
                                     var dueAmt = +total - (+data.data.tax_credit_amount);
                                     $("#net_tax_due_amount").val(dueAmt.toFixed(2));
                                     $('.finalTotal').html('').html(numberWithCommas(dueAmt.toFixed(2)));
                             }
                                }, 5000);
                            $("#previous_tax_credit_amount").val(data.data.tax_credit_amount);
                            $("#previous_or_date").val(data.data.previous_or_date);
                            $("#previous_or_no").val(data.data.previous_or_no);
                            $("#previous_cashier_id").val(data.data.previous_cashier_id);
                            /* Tax Credit Related information */
                          }
                   });
            }
            
        },error:function() {
             clearTaxRangeFields();
            hideLoader();
        }
    });
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function removeTdsForComputation(ele) {
    showLoader();
    var url = ele.data('url');
    var td  = ele.data('td');
     $.ajax({
        url :url, // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "td_no":td,
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            hideLoader();
            if(data.status == 'success'){
                Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: data.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                            clearTaxRangeFields(data.data.fromYear,data.data.toYear,data.data.srtQtr,data.data.endQur);
                            /* Refresh List of Billings */
                            getPaymentDetails($("#top_transaction_id").val(),1);
                            /* Refresh List of Billings */
                            loadAcceptedTds();
                            loadCasheiringInfo();
                            $('#total_paid_amount').val('0.00');
                            $('#total_amount_change').val('0.00');
                            $("#tcm_id").val(0);
                            $("#tax_credit_gl_id").val(0);
                            $("#tax_credit_sl_id").val(0);
                            $("#jqApplyCredit").addClass("hide");
                            $("#jqRemoveApplyCredit").addClass("hide");
                            /* Tax Credit Related information */
                            $("#previous_tax_credit_amount").val(data.data.tax_credit_amount);
                            $("#previous_or_date").val(data.data.previous_or_date);
                            $("#previous_or_no").val(data.data.previous_or_no);
                            $("#previous_cashier_id").val(data.data.previous_cashier_id);
                            /* Tax Credit Related information */
                          }
                   });
                
            }
            
        },error:function() {
             clearTaxRangeFields();
            hideLoader();
        }
    });
}

function clearTaxRangeFields(from = '',to = '',startQtr = '',endQtr = '') {
    $('input[name=cb_covered_from_year]').val(from);
    $('input[name=cb_covered_to_year]').val(to);
    $('select[name=sd_mode]').val(startQtr);
    $('select[name=sd_mode_to]').val(endQtr);
}

function loadAcceptedTds() {
    var id = $('#id').val();
    showLoader();
    var url = DIR+'cashier-real-property/loadacceptedtds';
     $.ajax({
        url :url, // json datasource
        type: "get", 
        dataType: "html", 
        data: {
            id:id
        },
        success: function(data){
            hideLoader();
            $('#acceptedTdsDetails').html(data);
            
        },error:function() {
            hideLoader();
        }
    });
}

function loadCasheiringInfo() {
    showLoader();
    var id = $('#id').val();
    var url = DIR+'cashier-real-property/loadcasheringinfo';
     $.ajax({
        url :url, // json datasource
        type: "get", 
        dataType: "html", 
        data: {
            id:id
        },
        success: function(data){
            hideLoader();
            $('#loadCasheirngInfoHere').html(data);
            var netDue = $('#net_tax_due_amount').val();
            //alert(netDue);
            $('.finalTotal').html(netDue);
            
        },error:function() {
            hideLoader();
        }
    });
}

 function checkOrUsedOrNot(e){
      $.ajax({
        url :DIR+'cashier-real-property/checkOrUsedOrNot', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "or_no":$('#or_no').val(),
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            if(data.isUsed){
                $("#err_or_no").html(data.errMsg);
                $("#jqPaidAmount").attr("disabled",true);
                return false;
            }else{
                $("#err_or_no").html('');
                $("#jqPaidAmount").attr("disabled",false);
                setConfirmAlert(e);
            }
        }
    })
}
function removeAppliedCreditAmt(){
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "Are you sure want to remove applied credit?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if(result.isConfirmed){
            $("#tcm_id").val(0);
            $("#tax_credit_gl_id").val(0);
            $("#tax_credit_sl_id").val(0);
            $("#jqApplyCredit").removeClass("hide");
            $("#jqRemoveApplyCredit").addClass("hide");
        }
    });
}
function applyCreditAmt(){
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "Are you sure want to apply credit?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if(result.isConfirmed){
           creditAmountApply();
        }
    });
}
function creditAmountApply(){
    $.ajax({
        url :DIR+'cashier-real-property/creditAmountApply', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            if(data.isValid == 1){
                $("#tcm_id").val(data.tcm_id);
                $("#tax_credit_gl_id").val(data.tax_credit_gl_id);
                $("#tax_credit_sl_id").val(data.tax_credit_sl_id);
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Applied Successfully.',
                    showConfirmButton: true,
                    timer: false
                })
                $("#jqApplyCredit").addClass("hide");
                $("#jqRemoveApplyCredit").removeClass("hide");
                $("#commonNote").addClass("hide")
                $("#commonNote").html('');

            }else if(data.isValid == 2){
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data.msg,
                    showConfirmButton: true,
                    timer: false
                });
                $('#total_paid_amount').val('0.00');
                $("#total_amount_change").val('0.00');
                $("#jqApplyCredit").addClass("hide");
                $("#jqRemoveApplyCredit").addClass("hide");
                $("#commonNote").removeClass("hide")
                $("#commonNote").html(data.errMsg);
                $("#tcm_id").val(0);
                $("#tax_credit_gl_id").val(0);
                $("#tax_credit_sl_id").val(0);
            }
            else{
                $('#total_paid_amount').val('0.00');
                $("#total_amount_change").val('0.00');
                $("#commonNote").removeClass("hide")
                $("#commonNote").html(data.errMsg);
                $("#tcm_id").val(0);
                $("#tax_credit_gl_id").val(0);
                $("#tax_credit_sl_id").val(0);
            }
        }
    })
}
function getOrNumber(checked){
    $.ajax({
        url :DIR+'cashier-real-property/getOrnumber', // json datasource
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

function setConfirmAlert(e){
    
    //$("#mainForm input[name='submit']").unbind("click");
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
            showLoader();
            //$('#mainForm').unbind('submit');
        var url = $('#mainForm').attr('action');
        var method = $('#mainForm').attr('method');
        var data   = $('#mainForm').serialize();
         $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            $('#total_paid_amount').val('00.00');
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
                        $('#commonModal').modal('hide'); 
                        updateCashierBillHistoryTaxpayers(html.id);
                        updateRptOnlineAccess(html.id);
                        location.reload(); // Don't Remove this reload function here managed automatic print
                        //$('#Jq_datatablelist').DataTable().ajax.reload();
                      }
               });
            }if(html.status == 'partial'){
                Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: html.msg,
                      showConfirmButton: true,
                      allowOutsideClick: false,
                      timer: false
                    }).then((result) => {
                          if (result.isConfirmed) {
                           $(".removeChequeData").remove();
                           $(".removeBankData").remove();
                           $("#tcm_id").val(0);
                           $("#tax_credit_gl_id").val(0);
                           $("#tax_credit_sl_id").val(0);
                           $("#jqApplyCredit").addClass("hide");
                           $("#jqRemoveApplyCredit").addClass("hide");
                           calculateChangeAmt();
                           getPaymentDetails($("#top_transaction_id").val(),1);
                           getOrNumber(0);

                           /* Tax Credit Related information */
                            $("#previous_tax_credit_amount").val(data.data.tax_credit_amount);
                            $("#previous_or_date").val(data.data.previous_or_date);
                            $("#previous_or_no").val(data.data.previous_or_no);
                            $("#previous_cashier_id").val(data.data.previous_cashier_id);
                            /* Tax Credit Related information */
                          }
                   });
            }if(html.status == 'validation_error'){

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
        }
    });
}

function updateRptOnlineAccess(id){
  $.ajax({
    url: DIR+'cashier-real-property/updateRptOnlineAccessTaxpayers',
    type: 'POST',
    data: {
        "remote_cashier_id": id, 
        "_token": $("#_csrf_token").val(),
    },
    success: function (data) {
       console.log(data);
    }
  });
}

function updateCashierBillHistoryTaxpayers(id){
  $.ajax({
    url: DIR+'cashier-real-property/updateCashierBillHistoryTaxpayers',
    type: 'POST',
    data: {
        "remote_cashier_id": id, 
        "_token": $("#_csrf_token").val(),
    },
    success: function (data) {
       console.log(data);
    }
  });
}

function getPaymentDetails(transactionId,forRefresh = 0){
    showLoader();
    $.ajax({
        url :DIR+'cashier-real-property/getPaymentDetails', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
          "id": $("#id").val(), 
          "previous_cashier_id": $("#previous_cashier_id").val(), 
          "transactionId": transactionId, 
          "hidden_ctype_id":$("#hidden_ctype_id").val(),
          "forRefresh":forRefresh,
          "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            hideLoader();
            loadAcceptedTds();
            loadCasheiringInfo();
            $('input[name=client_citizen_name]').val(data.data.taxpayer);
            $('input[name=client_citizen_id]').val(data.data.client_id);
        	$('#relatedTdsOfTOP').html('');
            $('#relatedTdsOfTOP').html(data.data.view);
        },error:function(){
            $('input[name=client_citizen_name]').val('');
            $('input[name=client_citizen_id]').val('');
            hideLoader();
        }
    })
}
function calculateChangeAmt(){
    var netAmt = $("#net_tax_due_amount").val();
    netAmt = netAmt.replace(/\,/g,'');
    var paidAmt = +$("#total_paid_amount").val();
    //alert(netAmt);
    if(netAmt != 0 && paidAmt>netAmt){
        $("#jqApplyCredit").removeClass("hide");
        var dueAmt = +paidAmt - (+netAmt);
        $("#total_amount_change").val(dueAmt.toFixed(2))
    }else{
        $("#total_amount_change").val('0.00')
        $("#jqApplyCredit").addClass("hide");
    }
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
        /*$("#fund_id2_"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreBankDetails")});
        $("#bank_id2_"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreBankDetails")});*/
        initiateFundRemoteSelectList('#fund_id2_'+prevLength,"cashier-real-property/geAjaxfundselectlist");
        initiateFundRemoteSelectList('#bank_id2_'+prevLength,"cashier-real-property/geAjaxbankselectlist");
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
    /*$("#fund_id3_"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreChequeDetails")});
    $("#bank_id3_"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreChequeDetails")});
    $("#check_type_id3_"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreChequeDetails")});*/
    initiateFundRemoteSelectList('#fund_id3_'+prevLength,"cashier-real-property/geAjaxfundselectlist");
    initiateFundRemoteSelectList('#bank_id3_'+prevLength,"cashier-real-property/geAjaxbankselectlist");
    initiateFundRemoteSelectList('#check_type_id3_'+prevLength,"cashier-real-property/geAjaxchequeselectlist");

    $("#hiddenChequeDtls").find('.fund_id3').attr('id','fund_id3_'+classid);
    $("#hiddenChequeDtls").find('.bank_id3').attr('id','bank_id3_'+classid);
    $("#hiddenChequeDtls").find('.check_type_id3').attr('id','check_type_id3_'+classid);

}
function managecheckdetails(id){
	if(id =='1'){
		$(".paymentcash").css("display", "none");
        removeChequeRequiredAttribute(false);
        removeBankRequiredAttribute(false);
	}else{
		$(".paymentcash").css("display", "block");
	}
	
    if(id =='3'){
		$("#addmoreChequeDetails").removeClass('hide');
        removeChequeRequiredAttribute(true);
        removeBankRequiredAttribute(false);
	}
    else{
		$("#addmoreChequeDetails").addClass('hide'); 
	}

    if(id =='2'){
		$("#addmoreBankDetails").removeClass('hide');
        removeChequeRequiredAttribute(false);
        removeBankRequiredAttribute(true);
		}
    else{
		$("#addmoreBankDetails").addClass('hide'); 
	}
}

function removeChequeRequiredAttribute(ele){
    $('#addmoreChequeDetails').find('input').prop('required',ele);
    $('#addmoreChequeDetails').find('select').prop('required',ele);
}

function removeBankRequiredAttribute(ele){
    $('#addmoreBankDetails').find('input').prop('required',ele);
    $('#addmoreBankDetails').find('select').prop('required',ele);
}

function initiateFundRemoteSelectList(id,url) {
    $(id).select3({
    placeholder: 'Select',
    allowClear: true,
    dropdownParent: $(id).parent(),
    ajax: {
        url: DIR+url,
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
}

function loadTopRemoteAjaxList() {
    $('#top_transaction_id').select3({
    placeholder: 'Select Top No',
    allowClear: true,
    dropdownParent: $('#top_transaction_id').parent(),
    ajax: {
        url: DIR+'cashier-real-property/geAjaxtopnoselectlist',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                id  : $('#id').val(),
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
}
