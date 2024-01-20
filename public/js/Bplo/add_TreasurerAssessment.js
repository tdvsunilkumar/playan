var thisval='';
var typeChanged=1;
var checkedPeriod;
$(document).ready(function(){
    if (!$("#payment_mode").hasClass("select3-hidden-accessible")) {
        $("#payment_mode").select3({dropdownAutoWidth : false,dropdownParent: $("#accordionFlushExample2")});
    }
    if (!$("#assesment_period").hasClass("select3-hidden-accessible")) {
        $("#assesment_period").select3({dropdownAutoWidth : false,dropdownParent: $("#accordionFlushExample2")});
    }
    getAssmentDetails(thisval,typeChanged);
 	$("#payment_mode").change(function(){
 		getPeriodDetails();
 	})
 	$("#assesment_period").change(function(){
 		getAssmentDetails(thisval,typeChanged);
 	})
    $(".saveData").click(function(){
        var type = $(this).val();
        if(type==2){
            if($(".period_checkbox").length>0){
                checkedPeriod = $('.period_checkbox:checked').map(function(){
                    return this.value;
                }).get();
                if(checkedPeriod==""){
                    Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: 'Please select periods'
                    })
                    return false;
                }
            }
            displayTaxOrderOfPayment();
        }else{
            saveAssessDetails(type);
        }
    })
    $("#year_type").change(function(){
        if($(this).val()=='2'){
           $(".currentyearDetails").addClass("hide");
        }else{
            $(".currentyearDetails").removeClass("hide");
        }
        getAssmentDetails(thisval,typeChanged);
    })
});

function saveAssessDetails(type){
    var page_name = $("#page_name").val();
    var msg ="";
    if(type==1){
        msg = "Are you sure want to Re-Assess";
    }
    if(type==3){
        msg = "Are you sure want to Final Assessment";
    }

    if(type==1 && page_name=='delinquency'){
        msg = "Are you sure want to Re-Calculate";
    }
    const swalWithBootstrapButtons = Swal.mixin({
       customClass: {
           confirmButton: 'btn btn-success',
           cancelButton: 'btn btn-danger'
       },
       buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
       title:"Are you sure?",
       text: msg,
       icon: 'warning',
       showCancelButton: true,
       confirmButtonText: 'Yes',
       cancelButtonText: 'No',
       reverseButtons: true
    }).then((result) => {
        if(result.isConfirmed){
            if(type==3){
                saveFinalAssessDtls(type)
                /*swalWithBootstrapButtons.fire({
                   title:"Are you sure?",
                   text: "Are you sure want ess before Final Assessment",
                   icon: 'warning',
                   showCancelButton: true,
                   confirmButtonText: 'Yes',
                   cancelButtonText: 'No',
                   reverseButtons: true
                }).then((result) => {
                    if(result.isConfirmed){
                        saveFinalAssessDtls(type,1)
                    }else{
                        saveFinalAssessDtls(type)
                    }
                });*/
            }else{
               saveFinalAssessDtls(type)
            }
        }
    })
}

function saveFinalAssessDtls(type,isReassess){
    showLoader();
    $.ajax({
        url :DIR+'treasurer/assessment/saveFinalAssessDtls', // json datasource
        type: "POST", 
        dataType: "json", 
        data: {
         "type": type,
         "isReassess":isReassess,
          "id": $("#busn_id").val(), 
          "retire_id":$("#retire_id").val(),
          "year": $("#year").val(), 
          "pm_id": $("#payment_mode").val(), 
          "assesment_period": $("#assesment_period").val(), 
          "app_code":$("#app_code").val(),
          "isIndvidual":0,
          "year_type": 2, 
         "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            hideLoader();
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Update Successfully.',
                showConfirmButton: false,
                timer: 1500
            })
            if(type==3){
                // Send Email to Customer After Final Assessment
                sendEmail(data.transaction_no);
            }
            setTimeout(() => {
                location.reload(true);
            }, 500);
        }
    })
}
function sendEmail(transaction_no=''){
    $.ajax({
        url :DIR+'treasurer/assessment/sendEmail', // json datasource
        type: "POST", 
        data: {
          "id": $("#busn_id").val(), 
          "retire_id":$("#retire_id").val(),
          "year": $("#year").val(), 
          "pm_id": $("#payment_mode").val(), 
          "app_code":$("#app_code").val(),
          "transaction_no":transaction_no,
         "_token": $("#_csrf_token").val(),
        },
        success: function(html){
        }
    })
}

function getAssmentDetails(thisval='',typeChanged=''){
    var payment_mode =$("#payment_mode").val();
    var assesment_period =$("#assesment_period").val();
    var year_type= $("#year_type").val();
    var year= $("#year").val();
    var isIndvidual=0;
    if(typeChanged==1 && year_type==1){
        year= $("#current_year").val();
    }
    
    if(thisval!=""){
        year = thisval.attr("year");
        isIndvidual=1;
        payment_mode =$("#payment_mode"+year).val();
        assesment_period =$("#assesment_period"+year).val();
    }
    showLoader();
    $.ajax({
        url :DIR+'treasurer/assessment/getAssessmentDetails', // json datasource
        type: "POST", 
        dataType: "html", 
        data: {
          "id": $("#busn_id").val(), 
          "retire_id":$("#retire_id").val(),
          "isIndvidual":isIndvidual,
          "year_type": year_type, 
          "year": year, 
          "pm_id": payment_mode, 
          "assesment_period": assesment_period, 
          "is_deleteFee": $("#is_deleteFee").val(), 
          "app_code":$("#app_code").val(),
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
            hideLoader();
            if(!isIndvidual){
                $("#assesmentDetails").html(html);
                eventFucntion();
            }else{
                var spl = html.split("#######");
                $("#jqperticularDetails"+year).html(spl[0]);
                $("#jqPaymentSchedule"+year).html(spl[1]);
            }
            deleteFeesEvents();
        }
    })
}
function deleteFeesEvents(){
    $(".restoreDeleteAssesFee").click(function(){
        var payModePartition=[];
        payModePartition[1] = [{val:1,name:'Annual'}];
        payModePartition[2] = [{val:1,name:'1 st Semester'},{val:2,name:'2 nd Semester'}];
        payModePartition[3] = [{val:1,name:'1 st Quarter'},{val:2,name:'2 nd Quarter'},{val:3,name:'3 rd Quarter'},{val:4,name:'4 th Quarter'}];

        var postData = {
            tfoc_id:$(this).attr('tfoc_id'),
            type:$(this).attr('type'),
            app_code:$(this).attr('app_code'),
            pm_id:$(this).attr('pm_id'),
            year:$(this).attr('year'),
            subclass_id:$(this).attr('subclass_id')
        }
        var html='';
        if(postData.pm_id>0){
            for (var item in payModePartition[postData.pm_id]) {
                var val = payModePartition[postData.pm_id][item]['val'];
                var name = payModePartition[postData.pm_id][item]['name'];
                html +='<input type="checkbox" class="deletedPeriod" name="deletedPeriod[]" disabled checked id="deletedPeriod'+val+'" value="'+val+'"> <label for="deletedPeriod'+val+'">'+name+'</label><br>';
            }
        }
               
        if(html!=''){
            Swal.fire({
              title: 'Please Select Periods.',
              html:html,
              showCancelButton: true,
              preConfirm: () => {
                var deletedPeriod = $('.deletedPeriod:checked').map(function(){
                    return this.value;
                }).get();
                if(deletedPeriod==""){
                    Swal.fire({icon: 'error',title: 'Oops...',text: 'Please select periods'})
                    return false;
                }  
                restoreDeleteFees(deletedPeriod,postData);         
              }
            });
        }else{
            deletedPeriod = [1];
            restoreDeleteFees(deletedPeriod,postData);  
        }
    })
}
function restoreDeleteFees(deletedPeriod,postData){
    var msg = "Are you sure want to restore this fee.";
    if(postData.type=='delete'){
        msg = "Are you sure want to delete this fee.";
    }
    const swalWithBootstrapButtons = Swal.mixin({
       customClass: {
           confirmButton: 'btn btn-success',
           cancelButton: 'btn btn-danger'
       },
       buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
       title:"Are you sure?",
       text: msg,
       icon: 'warning',
       showCancelButton: true,
       confirmButtonText: 'Yes',
       cancelButtonText: 'No',
       reverseButtons: true
    }).then((result) => {
        if(result.isConfirmed){
            $.ajax({
                url :DIR+'treasurer/assessment/restoreDeleteAssessmentFee', // json datasource
                type: "POST", 
                dataType: "json", 
                data: {
                  "id": $("#busn_id").val(), 
                  "year": postData.year, 
                  "type": postData.type, 
                  "pm_id": postData.pm_id, 
                  "assesment_period": deletedPeriod, 
                  "app_code":postData.app_code,
                  "tfoc_id":postData.tfoc_id,
                  "subclass_id":postData.subclass_id,
                  "_token": $("#_csrf_token").val(),
                },
                success: function(html){
                  console.log("html",html)
                  getAssmentDetails(thisval,typeChanged);
                }
            })
        }
    });
}

function eventFucntion(){
    $(".assesment_period").unbind("change");
    $(".assesment_period").change(function(){
         getAssmentDetails($(this));
     })
}
function getPeriodDetails(){
	var payment_mode =$("#payment_mode").val();
    $.ajax({
        url :DIR+'treasurer/assessment/getPeriodDetails', // json datasource
        type: "POST", 
        dataType: "html", 
        data: {
          "pm_id": payment_mode, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
            $("#assesment_period").html(html)
            getAssmentDetails('',1);
        }
    })
}

function displayTaxOrderOfPayment(){
    const swalWithBootstrapButtons = Swal.mixin({
       customClass: {
           confirmButton: 'btn btn-success',
           cancelButton: 'btn btn-danger'
       },
       buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
       title:"Are you sure?",
       text: 'Are you sure want to create Tax Order of Payment.',
       icon: 'warning',
       showCancelButton: true,
       confirmButtonText: 'Yes',
       cancelButtonText: 'No',
       reverseButtons: true
    }).then((result) => {
        if(result.isConfirmed){
            showLoader();
            $.ajax({
                url :DIR+'treasurer/assessment/displayTaxOrderOfPayment', // json datasource
                type: "POST", 
                data: {
                  "id": $("#busn_id").val(), 
                  "retire_id":$("#retire_id").val(),
                  "year": $("#year").val(), 
                  "pm_id": $("#payment_mode").val(), 
                  "app_code":$("#app_code").val(),
                  "chk_hidden_final_total":$("#chk_hidden_final_total").val(),
                  "chk_hidden_final_total_1":$("#chk_hidden_final_total_1").val(),
                  "chk_hidden_final_total_2":$("#chk_hidden_final_total_2").val(),
                  "chk_hidden_final_total_3":$("#chk_hidden_final_total_3").val(),
                  "chk_hidden_final_total_4":$("#chk_hidden_final_total_4").val(),
                  "_token": $("#_csrf_token").val(),
                  "checkedPeriod":checkedPeriod
                },
                success: function(html){
                    hideLoader();
                    if(typeof checkedPeriod == 'undefined'){
                        checkedPeriod='';
                    }
                    var href = $("#jqPrintPayment").attr('href');
                    $("#jqPrintPayment").attr('href',href+'&checkedPeriod='+checkedPeriod)
                    var spl = html.split('#####');
                    $("#feeDetails").html(spl[1])
                    if(html=='###MISSMATCHING###'){
                         Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'The amount is mismatching, please reassess it first.'
                        })
                        return false;
                    }
                    if(spl[0]>0){
                        storRemoteBploBillReceipt(spl[0]);
                    }else{
                        $("#feeDetails").html(html)
                    }

                    $("#assessmentFeeModal").modal('show');
                }
            })
        }
    });
}
function  storRemoteBploBillReceipt(transaction_no) {
     $.ajax({
        url :DIR+'treasurer/assessment/storRemoteBploBillReceipt', // json datasource
        type: "POST", 
        data: {
          "id": $("#busn_id").val(), 
          "transaction_no":transaction_no,
          "pm_id": $("#payment_mode").val(), 
          "app_code":$("#app_code").val(),
          "_token": $("#_csrf_token").val(),
          "checkedPeriod":checkedPeriod
        },
        success: function(html){

        }
    });
}