$(document).ready(function(){
    $('#commonModal').find("#rpo_code").select3({
    placeholder: 'Select Taxpayer',
    allowClear: true,
    dropdownParent: $('#commonModal').find("#rpo_code").parent(),
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
    loadRelatedByControlNumber();
	$('#commonUpDateCodeIntermediateModal1').modal({backdrop: 'static', keyboard: false});
	 $(document).off('click','#addBillingDetails').on('click','#addBillingDetails',function(){
	 	var url =  $(this).data('url');
	 	var selectedOwner = $('#rpo_code').val();
	 	var controlNo = $('input[name=cb_control_no]').val();
	 	if(selectedOwner == ""){
	 		$('#err_rpo_code').text('Required Field');
	 	}else{
	 		$('#err_rpo_code').text('');
	 		loadBillingForm(url,selectedOwner,controlNo);
	 	}
        

     });

     

     $(document).off('change','#rpo_code').on('change','#rpo_code',function(){
        var id = $(this).val();
        updateAllRecordsWithNewOwner(id);
        showLoader();
    $('.loadingGIF').show();
    var filtervars = {
        pid:id,
    }; 
    $.ajax({
        type: "GET",
        url: DIR+'rptproperty/getprofiledata',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('.loadingGIF').hide();
            arr = $.parseJSON(html);
                $('input[name="owner_address"]').val(arr.standard_address);
        },error:function(){
            hideLoader();
        }
    });
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

     $(document).off('click','.showBillingDetails').on('click','.showBillingDetails',function(){
        showLoader();
        $("#commonUpDateCodeIntermediateModal1").unbind("click");
        $("#commonUpDateCodeIntermediateModal1 .modal-title").html('Billing Details');
        $("#commonUpDateCodeIntermediateModal1 .modal-dialog").addClass('modal-xll');
        var url =  $(this).data('url');
        var id  = $(this).data('id');
        var data   = {
            id:id
        }
        $.ajax({
        type: "get",
        url: url,
        data: data,
        dataType: "html",
        success: function(html){ 
            hideLoader();
                $('#commonUpDateCodeIntermediateModal1 .body').html('');
                $('#commonUpDateCodeIntermediateModal1 .body').html(html);
                $("#commonUpDateCodeIntermediateModal1").modal('show');
        },error:function(){
            hideLoader();
        }
       });
     });

     $(document).off('click','.deleteBillingDetails').on('click','.deleteBillingDetails',function(){
        
        var url =  DIR+'billingform/delete';
        var id  = $(this).data('id');
        var data   = {
            id:id,
            "_token": $("#_csrf_token").val()
        }

         const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "Are you sure want to Delete Billing Row?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if(result.isConfirmed){
                    showLoader();
                    $.ajax({
                        type: "Post",
                        url: url,
                        data: data,
                        dataType: "html",
                        success: function(html){ 
                            hideLoader();
                            Swal.fire({
                              position: 'center',
                              icon: 'success',
                              title: 'Billing Detail Deleted Successfully.',
                              showConfirmButton: true,
                              timer: false
                            })
                            loadRelatedByControlNumber();  
                        },error:function(){
                            hideLoader();
                        }
                       });
                }
            });
               
     });



     $(document).off('click','#generateBillFromTemporaryData').on('click','#generateBillFromTemporaryData',function(e){
        if($('input[name=readyForSubmission]').val() == 0){
            generateBillFromTemporaryDataMultiple();
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
               generateBillFromTemporaryDataMultiple();
            }
        })
            
        }
     	
});
     });

function loadBillingForm(url,rpoCode,controlNo){
	showLoader();
        $("#commonUpDateCodeIntermediateModal1").unbind("click");
        $("#commonUpDateCodeIntermediateModal1 .modal-title").html('Billing Details');
        $("#commonUpDateCodeIntermediateModal1 .modal-dialog").addClass('modal-xll');
        
        //var id  = $(this).data('id');
        var data   = {
            rpo_code:rpoCode,
            cb_control_no:controlNo
        }
        $.ajax({
        type: "get",
        url: url,
        data: data,
        dataType: "html",
        success: function(html){ 
            hideLoader();
                $('#commonUpDateCodeIntermediateModal1 .body').html('');
                $('#commonUpDateCodeIntermediateModal1 .body').html(html);
                $("#commonUpDateCodeIntermediateModal1").modal('show');
                $("#rp_td_no").select3({dropdownAutoWidth : false,dropdownParent: $("#tdlistingdiv")});
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

function generateBillFromTemporaryDataMultiple() {
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
                $('#generateMultipleBilling').find('input[name=cb_control_no]').val(html.cno);
                $('#commonUpDateCodeIntermediateModal1').modal('hide');
                loadRelatedByControlNumber();
                storRemoteRptBillReceipt(html.txnNo);
                storRemoteRptOnlineAccess(html.txnNo);
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


function loadRelatedByControlNumber(argument) {
    showLoader();
    var cNo = $('input[name=cb_control_no]').val();
    var filtervars = {
        cb_control_no: cNo
    };
    $.ajax({
        type: "get",
        url: DIR+'billingform/loadmultipleprops',
        data: filtervars,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            $('#computedBillingDataForMultiple').html(html.view);
            var printUrl = DIR+'billingform/multiplepropertiesprintbill/'+html.cno+'&pageNo=1';
            $('#commonModal').find('.printSInglePropertyBill').attr('href',printUrl);
        },error:function(){
            hideLoader();
        }
    });
}

function updateAllRecordsWithNewOwner(id) {
    showLoader();
    var cNo = $('input[name=cb_control_no]').val();
    var filtervars = {
        id:id,
        cb_control_no: cNo
    };
    $.ajax({
        type: "post",
        url: DIR+'billingform/updateowner',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            loadRelatedByControlNumber();
        },error:function(){
            hideLoader();
        }
    });
}