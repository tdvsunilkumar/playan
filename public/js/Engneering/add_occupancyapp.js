$(document).ready(function () {
  //select3Ajax("brgy_code","divBarngayList","getBarngayList");
  select3Ajax("ebpa_id","ebpa_id_group","engoccupancyapp/GetBuildingpermitsAjax");
 // select3Ajax("location_brgy_id","divLocbarangay","engoccupancyapp/getBarngayList");
  $("#eoa_application_type").select3({dropdownAutoWidth : false,dropdownParent: $("#eoa_application_type_group")});

  $("#ebpa_id").change(function(){
    var id=$(this).val();
    if(id){ getbuildingpermitdata(id); }
  })
  $("#requirementsDetails").find(".removerequirementsdata").each(function(){
      var idreq = $(this).find('.reqid').attr("id");
      var fromgroup = $(this).find('.form-group').attr("id");
      select3Ajax(idreq, fromgroup, "engoccupancyapp/getSercviceRequirementsAjax");
  });
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
   var commaCounter = 10; var x, y, z;

    function numberSeparator (Number) {
        Number += '';

        for (var i = 0; i < commaCounter; i++) {
            Number = Number.replace(',', '');
        }

        x = Number.split('.');
        y = x[0];
        z = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;

        while (rgx.test(y)) {
            y = y.replace(rgx, '$1' + ',' + '$2');
        }
        commaCounter++;
        return y + z;
    }

   $(document).on('keypress , paste', '.amountpattern', function (e) {
            if (/^-?\d*[,.]?(\d{0,3},)*(\d{3},)?\d{0,3}$/.test(e.key)) {
                $('.amountpattern').on('input', function () {
                    e.target.value = numberSeparator(e.target.value);
                });
            } else {
                e.preventDefault();
                return false;
            }
    });  

  $("#btn_addmore_activity").click(function(){
    $('html, body').stop().animate({
      scrollTop: $("#btn_addmore_activity").offset().top
    }, 600);
    addmoreRequirements();
  });

   $(".btn_cancel_requiremets").click(function(){
     var id = $(this).val();  var fid = $(this).attr('fileid');
     var thisval = $(this);
     if(id >0){
      deleteRequirement(id,fid,thisval);
     }
  });

  if($("#id").val() >0 ){
    $("#eoa_building_structure").prop("required",true);
    $("#starbuildingstructure").text('*');
  }

   $("#saveorderpayment").click(function(e){
    e.preventDefault();
    var  appid = $("#id").val();
    SaveOrderofpayment(appid);
  })

  $("#declinebtn").click(function(){
         DeclineApplication();
  })

  $("#approvebtn").click(function(){
         ApproveApplication();
  })

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
         url :DIR+'engoccupancyapp/deleteAttachment', // json datasource
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

  function SaveOrderofpayment(appid){
   var filtervars = {
          appid:appid,
          tfocid:$("#tfoc_id").val(), 
          amount:$("#eoa_total_fees").val(),
          "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
          type: "POST",
          url: DIR+'engoccupancyapp/saveorderofpayment',
          data: filtervars,
          dataType: "json",
          success: function(html){ 
             hideLoader();
                   if(html.status="success"){
                        if(html.transid > 0){
                          storeOccubillSummary(html.transid);
                        }
                        Swal.fire({
                          position: 'center',
                          icon: 'success',
                          title: 'Order Of Payment Saved Successfully.',
                          showConfirmButton: false,
                          timer: 1500
                        })
                        //location.reload(); 
                   }
                
      },error:function(){
        hideLoader();
      }
   });
}

function  storeOccubillSummary(transaction_no) {
     $.ajax({
        url :DIR+'engoccupancyapp/storeOccubillSummary', // json datasource
        type: "POST", 
        data: {
          "appid": $("#id").val(), 
          "transactionno":transaction_no,
          "_token": $("#_csrf_token").val()
        },
        success: function(html){

        }
    });
}

/*| ---------------------------------
| # keypress numeric double
| ---------------------------------
*/
  $(".btn_cancel_requiremets").click(function(){
     $(this).closest(".removerequirementsdata").remove();
  });


  function addmoreRequirements(){
    var prevLength = $("#requirementsDetails").find(".removerequirementsdata").length;
    var html = $("#hidenarequirementHtml").html();
    var newHtml = html.replace(/reqid0/g, 'reqid' + prevLength);
    $("#requirementsDetails").append(newHtml);
    $("#reqid_group").attr('id', 'reqid_group' + prevLength);
    $("#reqid" + prevLength).attr('id', 'reqid' + prevLength);
    select3Ajax("reqid" + prevLength, "reqid_group" + prevLength, "engoccupancyapp/getSercviceRequirementsAjax");
    $(".btn_cancel_requiremets").click(function() {
        $(this).closest(".removerequirementsdata").remove();
        var cnt = $("#requirementsDetails").find(".removerequirementsdata").length;
        $("#requirementsDetails").find('.removerequirementsdata').each(function(index) {
            $(this).find('select.reqid').attr('id', 'reqid' + index);
            $(this).find('.form-group').attr('id', 'reqid_group' + index);
        });
    });
}
   $('#makeApprove').click(function(){
       var filtervars = {
          id:$(this).val(),
          serviceid:$(this).attr('serviceid'),
          "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
          type: "POST",
          url: DIR+'engoccupancyapp/MakeapprovePermit',
          data: filtervars,
          dataType: "html",
          success: function(html){ 
             hideLoader();
            Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Permit Approved Successfully.',
                  showConfirmButton: false,
                  timer: 1500
                })
      },error:function(){
        hideLoader();
      }
      });
    })

     $('#printorder').click(function(){
      var url =$(this).val();
      window.open(url, '_blank');
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

    function getprofiledata(id){
      $('.loadingGIF').show();
      var filtervars = {
        pid:id
      }; 
      $.ajax({
        type: "GET",
        url: DIR+'engjobrequest/getClientsDetails',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          $('.loadingGIF').hide();
          arr = $.parseJSON(html);
          if(arr){
            console.log(arr);
            $("#rpo_address_house_lot_no").val(arr.rpo_address_house_lot_no);
            $("#rpo_address_street_name").val(arr.rpo_address_street_name);
            $("#rpo_address_subdivision").val(arr.rpo_address_subdivision);
            $("#p_mobile_no").val(arr.p_mobile_no);

            // $('#brgy_code>option:eq('+arr.p_barangay_id_no+')').prop('selected', true);
            // $('#ebot_id>option:eq('+arr.ebot_id+')').prop('selected', true);
          }
        }
      });
    }

    function getbuildingpermitdata(id){
       var filtervars = {
        permitid:id
      }; 
      $.ajax({
        type: "GET",
        url: DIR+'engoccupancyapp/getbuidingdata',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          $('.loadingGIF').hide();
          arr = $.parseJSON(html);
          if(arr){
            console.log(arr);
            $("#ebfd_floor_area").val(arr.ebfd_floor_area);
            $("#ebfd_no_of_storey").val(arr.ebfd_no_of_storey); 
            $("#location_brgy_id").val(arr.location_brgy_id);
            $("#location_brgytext").val(arr.locationofconstr);
            $('#clientidnew>option[value='+arr.id+']').prop('selected', true);
            //$('#clientidnew>option:eq('')').prop('selected', true);
            $("#rpo_address_house_lot_no").val(arr.rpo_address_house_lot_no);
            $("#rpo_address_street_name").val(arr.rpo_address_street_name);
            $("#rpo_address_subdivision").val(arr.rpo_address_subdivision);
            $("#p_mobile_no").val(arr.p_mobile_no);
            $("#brgy_code").val(arr.p_barangay_id_no);
            $("#brgy_codetext").val(arr.barangaytext);
            $('#ebot_id>option[value='+arr.ebot_id+']').prop('selected', true);
            $("#nameofproject").val(arr.ejr_project_name);
            $("#eoa_date_of_completion").val(arr.ebfd_completion_date);
            $("#eoa_floor_area").val(arr.dimension);
            $("#eoa_firstfloorarea").val(arr.ejr_firstfloorarea);
            $("#eoa_secondfloorarea").val(arr.ejr_secondfloorarea);
            $("#eoa_lotarea").val(arr.ejr_lotarea);
            $("#eoa_perimeter").val(arr.ejr_perimeter);
            $("#eoa_projectcost").val(arr.ejr_projectcost);
            $("#no_of_units").val(arr.no_of_units);
          }
        }
      });
    } 

     $("#btn_addmore_feedetails").click(function(){
      $('html, body').stop().animate({
      scrollTop: $("#eoa_total_net_amount").offset().top
    }, 600);
    addmorefees();
    });
    $(".btn_cancel_feesdetail").click(function(){
      var id = $(this).val();
      deleteFeeDetails(id,$(this))
      
    });

    $('.amount').on('change keyup',function(){
           calculatetotal(); 
           calculatechange();
    })
    $('#eoa_surcharge_fee').on('change keyup',function(){
           calculatechange(); 
    })

  function addmorefees(){
     var prevLength = $("#hidenFeesHtml").find(".removerfeesdata").length;
     var html = $("#hidenFeesHtml").html();
        $("#defaultfeesDetails").append(html);
        $(".btn_cancel_feesdetail").click(function(){
        $(this).closest(".removerfeesdata").remove();
        });
         $('.amount').on('change keyup',function(){
           calculatetotal(); 
           calculatechange();
        })
    }

  function calculatechange(){
        var totalfinal = 0;
        var curramount = +$("#eoa_total_net_amount").val();
        var curramount = parseFloat($("#eoa_total_net_amount").val().replace(/\,/g,'') || 0);
        var surchargeamount = parseFloat($("#eoa_surcharge_fee").val().replace(/\,/g,'') || 0);
       
        totalfinal = parseFloat(curramount) + parseFloat(surchargeamount);
        totalfinal = totalfinal.toFixed(2);
        $("#eoa_total_fees").val(totalfinal); 
  }
  function calculatetotal(){
        var total = 0;
        $( "#defaultfeesDetails .amount" ).each(function() {
          var curramount = parseFloat($(this).val().replace(/\,/g,'') || 0);;
          total =  parseFloat(total) + parseFloat(curramount);
        });
        total = total.toFixed(2);
        $("#eoa_total_fees").val(total);  $("#eoa_total_net_amount").val(total);
  }

  function deleteFeeDetails(id,curr){
  var id = id; 
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
        $.ajax({
         url :DIR+'engoccupancyapp/deleteFeedetails', // json datasource
         type: "POST", 
         data: {
         "id": id,
         "_token": $("#_csrf_token").val(),
         },
         success: function(html){
           Swal.fire({
           position: 'center',
           icon: 'success',
           title: 'Update Successfully.',
           showConfirmButton: false,
           timer: 1500
           })
           curr.closest(".removerfeesdata").remove();
            calculatetotal(); 
            calculatechange();
         }
       })
     }
    })
   }

   function Getrquirements(tfocid){
       var tfocid = tfocid
        $.ajax({
        url :DIR+'engjobrequest/getRequirements', // json datasource
        type: "POST", 
        data: {
          "tfocid": tfocid, "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          $("#requirementsDetails").html(html);
        }
       })
   }

    function DeclineApplication(thisval){
  var appid = $("#id").val();
  const swalWithBootstrapButtons = Swal.mixin({
     customClass: {
       confirmButton: 'btn btn-success',
       cancelButton: 'btn btn-danger'
     },
     buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
     text: "Are you sure want to decline application?",
     icon: 'warning',
     showCancelButton: true,
     confirmButtonText: 'Yes',
     cancelButtonText: 'No',
     reverseButtons: true
   }).then((result) => {
      if(result.isConfirmed){
        showLoader();
        $.ajax({
         url :DIR+'engoccupancyapponline/Declineapplication', // json datasource
         type: "POST", 
         data: {
         "appid": appid,
         "_token": $("#_csrf_token").val(),
         },
         success: function(html){
          hideLoader();
           Swal.fire({
           position: 'center',
           icon: 'success',
           title: 'Update Successfully.',
           showConfirmButton: false,
           timer: 1500
           })
           location.reload(true);
         }
       })
     }
   })
  } 

  function ApproveApplication(thisval){
  var appid = $("#id").val();
  const swalWithBootstrapButtons = Swal.mixin({
     customClass: {
       confirmButton: 'btn btn-success',
       cancelButton: 'btn btn-danger'
     },
     buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
     text: "Are you sure want to Approve application?",
     icon: 'warning',
     showCancelButton: true,
     confirmButtonText: 'Yes',
     cancelButtonText: 'No',
     reverseButtons: true
   }).then((result) => {
      if(result.isConfirmed){
        showLoader();
        $.ajax({
         url :DIR+'engoccupancyapponline/approve', // json datasource
         type: "POST", 
         data: {
         "appid": appid,
         "_token": $("#_csrf_token").val(),
         },
         success: function(html){
          hideLoader();
             if(html.data){
             Swal.fire({
             position: 'center',
             icon: 'success',
             title: 'Update Successfully.',
             showConfirmButton: false,
             timer: 1500
             })
              location.reload(true);
            }else{
              Swal.fire({
             position: 'center',
             icon: 'error',
             title: 'Something Went Wrong Try After Sometime.',
             showConfirmButton: false,
             timer: 1500
             }) 
            }
         }
       })
     }
   })
  } 

 $('#releasepermit').click(function(){
       var filtervars = {
          id:$(this).val(),
          "_token": $("#_csrf_token").val()
        };
        var smsalert = 'Permit Issued Successfully.';
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
                  type: "POST",
                  url: DIR+'engoccupancyapp/UpdatePermitIssued',
                  data: filtervars,
                  dataType: "html",
                  success: function(html){ 
                     hideLoader();
                     $("#releasepermit").attr('disabled',true);
                    Swal.fire({
                          position: 'center',
                          icon: 'success',
                          title: smsalert,
                          showConfirmButton: false,
                          timer: 1500
                        })
              },error:function(){
                hideLoader();
              }
              });
             }
           })  
    })
});


