$(document).ready(function(){
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
  
    $(".top_transaction_id").change(function(){
        getPaymentDetails($(this).val());
    });
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
   
    getOrNumber(0)

    $('#mainForm').submit(function(e) {
        e.preventDefault();
        $("#err_total_paid_amount").html('');
        if($("#top_transaction_id").val()<=0){
            $("#err_top_transaction_id").html("Please select transaction.");
            return false;
        }
        if($("#total_paid_amount").val()<=0 && $("#total_paid_amount").attr("readonly")!='readonly'){
            $("#err_total_paid_amount").html("Please enter amount paid.");
            return false;
        }
        var total_paid_amount = +$("#total_paid_amount").val().replace(",", "");
        var net_tax_due_amount = +$("#net_tax_due_amount").val().replace(",", "");
        if(total_paid_amount<net_tax_due_amount){
            $("#err_total_paid_amount").html("Paid amount should be equal to or greater than Net Tax Due.");
            return false;
        }
        checkOrUsedOrNot(e);
    })
    if($(".top_transaction_id").val()>0){
        getPaymentDetails($(".top_transaction_id").val());
        $("#cancelreason").select3({dropdownAutoWidth : false,dropdownParent: $("#orderCanceltModal")});
    }
});

async function checkOrUsedOrNot(e){
     await $.ajax({
        url :DIR+'cashier/cashier-business-permit/checkOrUsedOrNot', // json datasource
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
            }else{
                //setConfirmAlert(e);
                 var recordid = $("pid").val();
                  Approved(recordid);
                $("#err_or_no").html('');
                $("#jqPaidAmount").attr("disabled",false);
            }
        }
    })
}

 function Approved(id){
     const swalWithBootstrapButtons = Swal.mixin({
       customClass: {
         confirmButton: 'btn btn-success',
         cancelButton: 'btn btn-danger'
       },
       buttonsStyling: false
     })
     swalWithBootstrapButtons.fire({
       title: 'Are you sure?',
       text: "You want to Approve?",
       icon: 'warning',
       showCancelButton: true,
       confirmButtonText: 'Yes',
       cancelButtonText: 'No',
       reverseButtons: true
     }).then((result) => {
       if(result.isConfirmed)
       {
        $.ajax({
           url :DIR+'online-payment-history/approve', // json datasource
           type: "POST", 
           data: {
           "id": id,
           "_token": $("#_csrf_token").val(),
           },
           success: function(html){
             Swal.fire({
             position: 'center',
             icon: 'success',
             title: 'Approved Successfully.',
             showConfirmButton: false,
             timer: 1500
             })
             //datatablefunction();
             location.reload();
           }
         })
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
        url :DIR+'cashier/cashier-business-permit/creditAmountApply', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            if(data.isValid){
                $("#tcm_id").val(data.tcm_id);
                $("#tax_credit_gl_id").val(data.tax_credit_gl_id);
                $("#tax_credit_sl_id").val(data.tax_credit_sl_id);
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Tax Credit Applied.',
                    showConfirmButton: false,
                    timer: 1500
                })
                $("#jqApplyCredit").addClass("hide");
                $("#jqRemoveApplyCredit").removeClass("hide");
                $("#commonNote").addClass("hide")
                $("#commonNote").html('');

            }else{
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
        url :DIR+'cashier/cashier-business-permit/getOrnumber', // json datasource
        type: "POST", 
        data: {
            "orflag": checked, "_token": $("#_csrf_token").val(),
        },
        success: function(html){
            $('#or_noshow').val(html)
            $("#or_number").html(html)
        }
    })
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
        text: "You want to Approve?",
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
            // $("form input[name='submit']").unbind("click");
            // $("#mainForm input[name='submit']").trigger("click");
        }
    });
}
function getPaymentDetails(transactionId){
    $.ajax({
        url :DIR+'cashier/cashier-business-permit/getPaymentDetails', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
          "id": $("#id").val(), 
          "previous_cashier_id": $("#previous_cashier_id").val(), 
          "transactionId": transactionId, 
          "hidden_ctype_id":$("#hidden_ctype_id").val(),
          "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            console.log("html",data)
            if(data.finalTotal<=0){
                $("#jqPaidAmount").attr("disabled",true);
            }else{
                $("#jqPaidAmount").attr("disabled",false);
            }

            $("#jqFeeDetails").html(data.html);

            $("#sub_total").val(data.totalAmount)
            $("#total_surcharges").val(data.totalSurcharge)
            $("#total_interest").val(data.totalInterest)
            $("#total_amount").val(data.finalTotal)
            $("#from_year").val(data.from_year);
            $("#to_year").val(data.to_year);
            //$("#total_paid_amount").val(data.finalTotal)

            $("#from_period").html(data.from_period);
            $("#to_period").html(data.to_period);
            $(".finalTotal").html(data.finalTotal)
            if(data.app_code_name!=""){
                var color = data.app_code_name=='Retire'?'#ff0000':'#29b6c9';
                data.busn_name = data.busn_name+' - (<span style="color:'+color+'">'+data.app_code_name+'</span>)';
            }
            $("#busn_name").html(data.busn_name)
            $("#busn_address").html(data.busn_address)
            $("#ownar_name").html(data.ownar_name)
            $("#previous_cashier_id").val(data.previous_cashier_id)
            $("#prev_tax_credit_amount").val(data.tax_credit_amount)

            $("#previous_tax_credit_amount").html(data.tax_credit_amount)
            $("#previous_or_date").html(data.previous_or_date)
            $(".previous_or_no").html(data.previous_or_no)
            
            if($("#id").val()=='' || $("#id").val()==0){
                $("#cashier_date").html(data.cashier_date)
            }
            $("#net_tax_due_amount").val(data.finalTotal);


            data.tax_credit_amount = data.tax_credit_amount.replace(",", "");
            if(data.tax_credit_amount>0){
                var total = data.finalTotal.replace(",", "");
                var dueAmt = +total - (+data.tax_credit_amount);
                if(dueAmt<0){
                    $("#net_tax_due_amount").val(0);
                    $("#total_amount_change").val(Math.abs( dueAmt.toFixed(2)));
                    $("#total_paid_amount").attr("readonly",true)
                    if($("#id").val()==''){
                         creditAmountApply();
                    }
                   
                }else{
                    $("#net_tax_due_amount").val(dueAmt.toFixed(2))
                }
                
            }
            $("#total_paid_amount").keyup(function(){
                if($("#total_paid_amount").attr("readonly")!='readonly'){
                    calculateChangeAmt();
                }
            })
        }
    })
}
function calculateChangeAmt(){
    var netAmt = $("#net_tax_due_amount").val();
    netAmt = +netAmt.replace(",", "");
    var paidAmt = +$("#total_paid_amount").val();
    
    if(paidAmt>netAmt){
        $("#jqApplyCredit").removeClass("hide");
        var dueAmt = +paidAmt - (+netAmt);
        $("#total_amount_change").val(dueAmt.toFixed(2))
    }else{
        $("#total_amount_change").val('0.00')
        $("#jqApplyCredit").addClass("hide");
    }
}



