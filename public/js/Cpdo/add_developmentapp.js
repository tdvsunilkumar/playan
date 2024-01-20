$(document).ready(function (){
  select3Ajax("client_id","ownernamediv","getEngTaxpayersAutoSearchList");
  //select3Ajax("locationofproject","applicationdiv","cpdoapplication/getBarngayList");
  select3Ajax("cir_approved_by","cirapprovediv","cpdoapplication/getEmployeeListAjax");
  select3Ajax("cir_noted_by","cirnoteddiv","cpdoapplication/getEmployeeListAjax");

   $("#cir_penalty").select3({dropdownAutoWidth : false,dropdownParent: $("#orderofpdiv")});
  $("#client_id").change(function(){
    var id=$(this).val();
    if(id){ getprofiledata(id); }
  })
  $("#requirementsDetails").find(".removerequirementsdata").each(function(){
      var idreq = $(this).find('.reqid').attr("id");
      var fromgroup = $(this).find('.form-group').attr("id");
      select3Ajax(idreq, fromgroup, "getRequirementsAjax");
  });
  if($("#id").val() > 0){
    getpaymentlines($("#id").val());
  }

  $("#btnOrderofPayment").click(function(){
		 $("#orderofpaymentModal").modal('show');
 	});	
    $(".closeOrderModal").click(function(){
		 $("#orderofpaymentModal").modal('hide');
 	});
 	$("#saveorder").click(function(e){
 		e.preventDefault();
 		SaveOrderofpayment();
 	})
 	$("#btnPrintOrderofPayment").click(function(e){
 		e.preventDefault();
 		PrintOrderofPayment();
 	})
 	$("#btnPrintInspection").unbind();
 	$("#btnPrintInspection").click(function(){
 		var id =$(this).val();
 		PrintInspection(id);	
 	})

 	$("#btnPrintCertificate").unbind();
 	$("#btnPrintCertificate").click(function(){
 		var url =$(this).val();
 		// window.location 
 		window.open(url, '_blank');
 		// PrintCertificate(id);	
 	})

  $("#caf_excempted").click(function(){
      if($('#caf_excempted').is(":checked")){
        $("#caf_amount").val('0');
        $("#caf_amount").addClass('disabled-field');
      }else{
        $("#caf_amount").removeClass('disabled-field');
      }
  });

   $('#cir_penalty').on('change', function() {
     const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "It will not change after the final save.",
        icon: 'warning',
        showCancelButton: false,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: false
    }).then((result) => {
        if(result.isConfirmed){  
          //$("#penaltyhidden").val($(this).val());
           return true;
        }
        //$("#mySelect2").select2("val", "0");
    });
  })


 	$("#btnPrintapplication").unbind();
 	$("#btnPrintapplication").click(function(){
 		var url =$(this).val();
 		// window.location 
 		window.open(url, '_blank');
 		// PrintCertificate(id);	
 	})
   $('body').on('keypress', '.numeric-double', function (event) {
      var $this = $(this);
      if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
        ((event.which < 48 || event.which > 57) &&
          (event.which != 0 && event.which != 8))) {
        event.preventDefault();
    }

    var text = $(this).val();
    if ((event.which == 46) && (text.indexOf('.') == -1)) {
      setTimeout(function () {
        if ($this.val().substring($this.val().indexOf('.')).length > 3) {
          $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
        }
      }, 1);
    }

    if ((text.indexOf('.') != -1) &&
      (text.substring(text.indexOf('.')).length > 2) &&
      (event.which != 0 && event.which != 8) &&
      ($(this)[0].selectionStart >= text.length - 2)) {
      event.preventDefault();
    }
    });

    $('body').on('keypress', '.numeric-only', function (event) {
      var charCode = (event.which) ? event.which : event.keyCode    

      if (String.fromCharCode(charCode).match(/[^0-9]/g)) {
        return false;             
      }
    });   

 	$("#prparedby").unbind();
 	$('#prparedby').on('change', function() {
 		var id =$(this).val();
 		var posid= "prparedbypostion";
 		Getposition(id,posid);	
 	})
 	$('#cc_recom_approval').on('change', function() {
 		var id =$(this).val();
 		var posid= "cc_recom_position";
 		Getposition(id,posid);	
 	})
 	$('#cc_noted').on('change', function() {
 		var id =$(this).val();
 		var posid= "notedpostion";
 		Getposition(id,posid);	
 	})

 	$('#cc_approved').on('change', function() {
 		var id =$(this).val();
 		var posid= "approved_position";
 		Getposition(id,posid);	
 	})
  $("#uploadAttachmentbtn").click(function(){
    uploadAttachment();
  });
  $(".deleteAttachment").click(function(){
    deleteAttachment($(this));
  })

 	 if($("#prparedby option:selected").val() > 0 ){
          var id = $("#prparedby option:selected").val();
          var posid= "prparedbypostion";
 		  Getposition(id,posid);	
     }

     if($("#cc_recom_approval option:selected").val() > 0 ){
          var id = $("#cc_recom_approval option:selected").val();
          var posid= "cc_recom_position";
 		  Getposition(id,posid);	
     }

     if($("#cc_noted option:selected").val() > 0 ){
          var id = $("#cc_noted option:selected").val();
          var posid= "notedpostion";
 		  Getposition(id,posid);	
     }

     if($("#cc_approved option:selected").val() > 0 ){
          var id = $("#cc_approved option:selected").val();
          var posid= "approved_position";
 		  Getposition(id,posid);	
     }
 	
 	$("#btnApproveInspection").click(function(){
 		var id =$("#caf_id").val();
 		ApproveInspection(id);	
 	})

 	$("#notedbyApproval").click(function(){
 		var id =$(this).val();
 		var button = "noted";
 		ApproveCerticate(id,button);	
 	})

 	$("#recommbyApproval").click(function(){
 		var id =$(this).val();
 		var button = "recommend";
 		ApproveCerticate(id,button);	
 	})

 	$("#Approvalby").click(function(){
 		var id =$(this).val();
 		var button = "approve";
 		ApproveCerticate(id,button);	
 	})
     
    // $("#cir_approved_by").select3({ dropdownAutoWidth : false,dropdownParent: $("#cirapprovediv") }); 
    // $("#cir_noted_by").select3({dropdownAutoWidth : false,dropdownParent: $("#cirnoteddiv")}); 
    //$("#caf_client_representative_id").select3({ dropdownAutoWidth : false,dropdownParent: $("#ownernamediv")}); 
    select3Ajax("prparedby","prparedbydiv","cpdoapplication/getEmployeeListAjax");
    select3Ajax("cc_noted","cc_noteddiv","cpdoapplication/getEmployeeListAjax");
    select3Ajax("cc_recom_approval","cc_recom_approvaldiv","cpdoapplication/getEmployeeListAjax");
    select3Ajax("cc_approved","cc_approveddiv","cpdoapplication/getEmployeeListAjax");
    // $("#cc_recom_approval").select3({dropdownAutoWidth : false,dropdownParent: $("#cc_recom_approvaldiv")}); 
    // $("#prparedby").select3({dropdownAutoWidth : false,dropdownParent: $("#prparedbydiv")}); 
    // $("#cc_noted").select3({dropdownAutoWidth : false,dropdownParent: $("#cc_noteddiv")}); 
    // $("#cc_approved").select3({dropdownAutoWidth : false,dropdownParent: $("#cc_approveddiv")}); 
    $("#cm_id").select3({dropdownAutoWidth : false,dropdownParent: $("#cmiddiv")});  
/*| ---------------------------------
| # keypress numeric double
| ---------------------------------
*/ 
  $("#btn_addmore_geolocation").click(function(){
      $('html, body').stop().animate({
        scrollTop: $("#btn_addmore_geolocation").offset().top
      }, 600);
      addmoreLocations();
    }); 

  $("#btn_addmore_requirements").click(function(){
		addmoreRequirements();
	});
	$(".btn_cancel_requirement").click(function(){
     var id = $(this).val();
     var fid = $(this).attr('fileid');
     var thisval = $(this);
     if(id >0){
      deleteRequirement(id,fid,thisval);
     }
	});

	$('#tfoc_idtype').on('change', function() {
      var tfoc_id =$(this).val();
      $.ajax({
        url :DIR+'cpdodevelopmentapp/getServicetype', // json datasource
        type: "POST", 
        data: {
          "tfocid": tfoc_id, "_token": $("#_csrf_token").val(),dataType: "html",
        },
        success: function(html){
          $("#paymentlinediv").html(html);
          $("#cdp_total_amount").val('0');
            $(".linecheckbox").click(function(){
              var line = $(this).attr('idval');
              var type = $("#type"+line).val();
              if($(this).is(':checked')){
               if(type=="None" || type==""){  }
                else{$("#number"+line).removeClass("disabled-field");}
             }
             else{ $("#number"+line).addClass("disabled-field");}
             calculatetotal();
          }) 

          $(".number").on('change', function(){
             var val = $(this).val();
             var line = $(this).attr('idval');
             var amount = $("#fixedamount"+line).val();
             amount = parseFloat(amount.replace(/\,/g,'') || 0);
             vartotalamount = parseFloat(val) * parseFloat(amount);
             $("#amount"+line).val(vartotalamount);
             calculatetotal();
          })     
         }
       })
      if($("#id").val() <= 0){
      Getrquirements(tfoc_id);
      }
    });
  $('#tfoc_id').on('change', function() {
     var tfoc_id =$(this).val();
     Getrquirements(tfoc_id);
   });
  
  if($("#cna_id option:selected").val() > 0 ){
        var val = $("#cna_id option:selected").val();
         if(val =='4'){
       makeactiveinactivefiels();
      }
   }

   $('#cna_id').on('change', function() {
      var id =$(this).val();
      if(id =='4'){
       makeactiveinactivefiels();
      }else{
        $('#caf_others_nature_of_applicant').addClass('disabled-field');
      }
 });  
 $('#cpt_id').on('change', function() {
      var id =$(this).val();
      if(id =='2'){
      $('#cpt_others').removeClass('disabled-field'); $('#cpt_others').val('');
      }else{
        $('#cpt_others').addClass('disabled-field');
      }
 }); 
  if($("#cpt_id option:selected").val() == 2 ){
       $('#cpt_others').removeClass('disabled-field'); 
   }

  $('#cit_id').on('change', function() {
      var id =$(this).val();
      if(id =='3'){
      $('#citother').removeClass('disabled-field'); $('#cpt_others').val('');
      }else{
        $('#citother').addClass('disabled-field');
      }
  }); 

  if($("#cit_id option:selected").val() == 3 ){
       $('#citother').removeClass('disabled-field');
 }  

});

function deleteAttachment(thisval){
  var rid = thisval.attr('rid');
  var id = thisval.attr('id');
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
         url :DIR+'cpdodevelopmentapp/insepectiondeleteAttachment', // json datasource
         type: "POST", 
         data: {
         "rid": rid,
         "id": id,  
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
   if (typeof $('#documentname')[0].files[0]== "undefined") {
    $("#err_document").html("Please upload Document");
    return false;
  }
  var formData = new FormData();
  formData.append('file', $('#documentname')[0].files[0]);
  formData.append('id', $("#caf_id").val());
  showLoader();
  $.ajax({
       url : DIR+'cpdodevelopmentapp/uploadDocument',
       type : 'POST',
       data : formData,
       processData: false,  // tell jQuery not to process the data
       contentType: false,  // tell jQuery not to set contentType
       success : function(data) {
          hideLoader();
          var data = JSON.parse(data);
            if(data.ESTATUS==1){
              $("#err_end_requirement_id").html(data.message);
            }else{
              $("#documentname").val(null);
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
          $(".deleteAttachment").unbind("click");
           $(".deleteAttachment").click(function(){
          deleteAttachment($(this));
        })
      }
       }
  });
}

function getpaymentlines(id){
  var id =id;
      $.ajax({
        url :DIR+'cpdodevelopmentapp/getpaymentlinedit', // json datasource
        type: "POST", 
        data: {
          "appid": id, "_token": $("#_csrf_token").val(),dataType: "html",
        },
        success: function(html){
          $("#paymentlinediv").html(html);
              $(".linecheckbox").click(function(){
                var line = $(this).attr('idval');
                var type = $("#type"+line).val();
                if($(this).is(':checked')){
                 if(type=="None" || type==""){  }
                 else{$("#number"+line).removeClass("disabled-field");}
               }
               else{ $("#number"+line).addClass("disabled-field");}
               calculatetotal();
            }) 

            $(".number").on('change', function(){
               var val = $(this).val();
               var line = $(this).attr('idval');
               var amount = $("#fixedamount"+line).val();
               amount = parseFloat(amount.replace(/\,/g,'') || 0);
               vartotalamount = parseFloat(val) * parseFloat(amount);
               $("#amount"+line).val(vartotalamount);
               calculatetotal();
            })     
         }
       })
}
 function calculatetotal(){
        var total = 0;
        $( "#paymentlinediv .linecheckbox" ).each(function() {
          var checkid = $(this).attr('idval');
           var curramount = 0;
          if ($('input[name=checkbox'+checkid+']').is(':checked')) {
            var  curramount = $('#amount'+checkid).val();
            curramount = parseFloat(curramount.replace(/\,/g,'') || 0);
          }
          total =  parseFloat(total) + parseFloat(curramount); 
        });
        
        total = total.toFixed(2);
        $("#cdp_total_amount").val(total); 
      
  }

function deleteRequirement(id,fid,thisval){
  var rid = id; var eid = fid;
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
         url :DIR+'cpdodevelopmentapp/deleteAttachment', // json datasource
         type: "POST", 
         data: {
         "rid": rid,
         "eid": eid,  
         "_token": $("#_csrf_token").val(),
         },
         success: function(html){
          hideLoader();
          thisval.closest(".removerequirementsdata").remove();
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

function makeactiveinactivefiels(){
  
   $('#caf_others_nature_of_applicant').removeClass('disabled-field');
}

function ApproveCerticate(id,button){
   var filtervars = {
          id:id,
          cafid:$("#caf_id").val(),
          button:button,
          "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
          type: "POST",
          url: DIR+'cpdodevelopmentapp/ApproveCertificate',
          data: filtervars,
          dataType: "json",
          success: function(html){ 
             hideLoader();
                   if(html.status="success"){
                        Swal.fire({
                          position: 'center',
                          icon: 'success',
                          title: 'Certificate Approved Successfully.',
                          showConfirmButton: false,
                          timer: 1500
                        })
                        $("#btnApproveInspection").text('Inspected');
                        location.reload();
                   }
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
        url: DIR+'cpdodevelopmentapp/getClientsDetails',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          $('.loadingGIF').hide();
          arr = $.parseJSON(html);
          if(arr){
            console.log(arr);
            $("#cdp_address").val(arr.fulladdress);
            $("#cdp_phone_no").val(arr.p_mobile_no);
            $("#cdp_email_address").val(arr.p_email_address);
          }
        }
      });
 } 

function addmoreRequirements() {
    var prevLength = $("#requirementsDetails").find(".removerequirementsdata").length;
    var html = $("#hidenRequirementHtml").html();
    var newHtml = html.replace(/reqid0/g, 'reqid' + prevLength);
    $("#requirementsDetails").append(newHtml);
    $("#reqid_group").attr('id', 'reqid_group' + prevLength);
    $("#reqid" + prevLength).attr('id', 'reqid' + prevLength);
    select3Ajax("reqid" + prevLength, "reqid_group" + prevLength, "getRequirementsAjax");
    $(".btn_cancel_requirement").click(function() {
        $(this).closest(".removerequirementsdata").remove();
        var cnt = $("#requirementsDetails").find(".removerequirementsdata").length;
        $("#requirementsDetails").find('.removerequirementsdata').each(function(index) {
            $(this).find('select.reqid').attr('id', 'reqid' + index);
            $(this).find('.form-group').attr('id', 'reqid_group' + index);
        });
    });
}

function SaveOrderofpayment(){
	 var filtervars = {
          id:$("#transid").val(),
          appid:$("#caf_id").val(),
          tfocid:$("#tfoc_id").val(), 
          amount:$("#total").val(),
          "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
          type: "POST",
          url: DIR+'cpdodevelopmentapp/saveorderofpayment',
          data: filtervars,
          dataType: "json",
          success: function(html){ 
             hideLoader();
                   if(html.status="success"){
                        Swal.fire({
                          position: 'center',
                          icon: 'success',
                          title: 'Order Of Payment Saved Successfully.',
                          showConfirmButton: false,
                          timer: 1500
                        })
                        $("#transaction_no").val(html.transactionno);
                        $("#orderofpaymentModal").modal('hide'); 
                        if(html.transid > 0){
                          storeCpdobillSummary(html.transid);
                        }
                        //location.reload(); 
                   }
                
      },error:function(){
        hideLoader();
      }
      });
}

function  storeCpdobillSummary(transaction_no) {
     $.ajax({
        url :DIR+'cpdodevelopmentapp/storeDevelopbillSummary', // json datasource
        type: "POST", 
        data: {
          "appid": $("#caf_id").val(), 
          "transactionno":transaction_no,
          "_token": $("#_csrf_token").val()
        },
        success: function(html){

        }
    });
}
function Getposition(id,posid){
	var filtervars = {
          id:id,
          posid:posid,
          "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
          type: "POST",
          url: DIR+'cpdodevelopmentapp/positionbyid',
          data: filtervars,
          dataType: "html",
          success: function(html){ 
             hideLoader();
                        $("#"+posid).val(html);
                       
                
      },error:function(){
        hideLoader();
      }
     });

}
function ApproveInspection(id){
   var filtervars = {
          id:id,
          "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
          type: "POST",
          url: DIR+'cpdodevelopmentapp/ApproveInspection',
          data: filtervars,
          dataType: "json",
          success: function(html){ 
             hideLoader();
                   if(html.status="success"){
                        Swal.fire({
                          position: 'center',
                          icon: 'success',
                          title: 'Inspection Approved Successfully.',
                          showConfirmButton: false,
                          timer: 1500
                        })
                        $("#btnApproveInspection").text('Inspected');
                        location.reload();
                   }
                
      },error:function(){
        hideLoader();
      }
     });
}

function PrintOrderofPayment(){
      var filtervars = {
          id:$("#transid").val(),
          appid:$("#id").val(),
          "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
          type: "POST",
          url: DIR+'cpdodevelopmentapp/printorderofpayment',
          data: filtervars,
          dataType: "html",
          success: function(html){ 
              hideLoader();
             	   var url =  html;
                   window.open(url, '_blank');
                
      },error:function(){
        hideLoader();
      }
      });
}

function PrintInspection(id){
    var filtervars = {
          id:id,
          "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
          type: "POST",
          url: DIR+'cpdodevelopmentapp/printinspection',
          data: filtervars,
          dataType: "html",
          success: function(html){ 
              hideLoader();
             	   var url =  html;
                   window.open(url, '_blank');
                
      },error:function(){
        hideLoader();
      }
      });
}

function PrintCertificate(id){
	// var filtervars = {
 //          id:id,
 //          "_token": $("#_csrf_token").val()
 //        }; 
 //        $.ajax({
 //          type: "POST",
 //          url: DIR+'cpdoapplication/printcertificate',
 //          data: filtervars,
 //          dataType: "html",
 //          success: function(html){ 
 //              hideLoader();
 //             	   var url =  html;
 //                   window.open(url, '_blank');
                
 //      },error:function(){
 //        hideLoader();
 //      }
 //      });
}

  function Getrquirements(tfocid){
       var tfocid = tfocid
        $.ajax({
        url :DIR+'cpdodevelopmentapp/getRequirements', // json datasource
        type: "POST", 
        data: {
          "tfocid": tfocid, "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          $("#requirementsDetails").html(html);
          	$(".btn_cancel_requirement").click(function(){
				 $(this).closest(".removerequirementsdata").remove();
			});
        } 
       })
   }

   function addmoreLocations(){
      var srcount = $("#geolocationDetails").find(".serialnoclass").length;
      srcount =parseFloat(srcount) + 1;
      $("#hiddenlocationHtml").find('.serialnoclass').text(srcount);
       var html = $("#hiddenlocationHtml").html();
      $("#geolocationDetails").append(html);
      $(".btn_cancel_locations").click(function(){
        $(this).closest(".removelocationdata").remove();
         });
   }

