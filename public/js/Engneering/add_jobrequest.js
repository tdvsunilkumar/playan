$(document).ready(function () {
  select3Ajax("brgy_code","divBarngayList","getBarngayList");
  select3Ajax("client_id","ownernamediv","getEngTaxpayersAutoSearchList");
  select3Ajax("location_brgy_id","locationdiv","engjobrequest/getBarngayList");
  select3AjaxFalcno("zoning_cert_id","zoningcertdiv","engjobrequest/getFalcnobyAjax");
  $("#client_id").change(function(){
    var id=$(this).val();
    if(id){ getprofiledata(id); }
  })

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

  if($("#id").val() > 0){
    isreferencevalid();
  }

  function price_separator (input) {
        var output = input
        if (parseFloat(input)) {
            input = new String(input); // so you can perform string operations
            var parts = input.split("."); // remove the decimal part
            parts[0] = parts[0].split("").reverse().join("").replace(/(\d{3})(?!$)/g, "$1,").split("").reverse().join("");
            output = parts.join(".");
        }

        return output;
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
         url :DIR+'engjobrequest/deleteAttachment', // json datasource
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

  function addmoreRequirements(){
  var prevLength = $("#requirementsDetails").find(".removerequirementsdata").length;
  var html = $("#hidenarequirementHtml").html();
  $(".activity-details").append(html);
  $(".btn_cancel_requiremets").click(function(){
    $(this).closest(".removerequirementsdata").remove();
     var cnt = $("#requirementsDetails").find(".removerequirementsdata").length;
       $("#reqid"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#requirementsDetails")});
     $("#hidenarequirementHtml").find('select').attr('id','reqid'+cnt);
    });
    $("#reqid0").select3({dropdownAutoWidth : false,dropdownParent: $("#requirementsDetails")});
     var cnt = $("#requirementsDetails").find(".removerequirementsdata").length;
       $("#reqid"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#requirementsDetails")});
     $("#hidenarequirementHtml").find('select').attr('id','reqid'+cnt);
     
  } 
  
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
            $("#rpo_address_subdivision1").val(arr.rpo_address_subdivision);
            $("#pmobileno").val(arr.p_mobile_no);
            $('#brgy_code').html(arr.p_barangay_id_no);
            select3Ajax("brgy_code","divBarngayList","getBarngayList");
            //$('#brgy_code>option[value='+arr.p_barangay_id_no+']').prop('selected', true);
           // $('#brgy_code>option:eq('+arr.p_barangay_id_no+')').prop('selected', true);
          }
        }
      });
    } 

    $("#loadAddserviceForm").unbind("click");
    $("#loadAddserviceForm").click(function(){
      $('#addServicemodal').modal('show');
      loadAddServiceForm(id = '');
      $(document).on('click','.closeServiceModal',function(){
        $('#addServicemodal').modal('hide');
      });
    });



    function loadServiceData(id){
        showLoader();
        $('.loadingGIF').show();
        var filtervars = {
          id:id,
          "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
          type: "POST",
          url: DIR+'rptproperty/getlandappraisal',
          data: filtervars,
          dataType: "html",
          success: function(html){ 
            hideLoader();
            $('#landAppraisalListing').html(html);
            calculateTotalMarketValue();
      //$('#myModal'+mid).find(".pc_class_code_description").val(html.pc_class_description);
      /*$('#myModal'+mid).find(".tax_type_desc").val(html.tax_type_description);
      $('#myModal'+mid).find(".tax_type_id").val(html.id);*/
      },error:function(){
        hideLoader();
      }
      });
    }

    $('#makeApprove').click(function(){
       var filtervars = {
          id:$(this).val(),
          serviceid:$(this).attr('serviceid'),
          ejrid:$('#id').val(),
          "_token": $("#_csrf_token").val()
        };
        if($(this).attr('serviceid') =='1'){
          var smsalert = 'Permit Number Created Successfully.';
        } else{
          var smsalert = 'Order Of Payment Approved Successfully.';
        }
        
        $.ajax({
          type: "POST",
          url: DIR+'jobrequest/MakeapprovePermit',
          data: filtervars,
          dataType: "html",
          success: function(html){ 
             hideLoader();
             $("#makeApprove").attr('disabled',true);
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
    })

    $('#releasepermit').click(function(){
       var filtervars = {
          id:$(this).val(),
          serviceid:$(this).attr('serviceid'),
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
                  url: DIR+'jobrequest/UpdatePermitIssued',
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

     $('#printorder').click(function(){
        var btn = $(this); // Assuming btn is defined somewhere in your code
        var filtervars = {
            id: btn.val(),
            serviceid: btn.attr('serviceid'),
            "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
            type: "POST",
            url: DIR + 'jobrequest/Printorder',
            data: filtervars,
            dataType: "html",
            success: function(html){ 
                hideLoader();
                var url = html;
                var decodedUrl = decodeURIComponent(url);
                var digitalSignUrl = DIR + 'digital-sign?url=' + decodedUrl;
                window.open(digitalSignUrl, '_blank');
            },
            error: function(){
                hideLoader();
            }
        });
    });
    
    $(document).off('submit','#storebuildingpermit').on('submit','#storebuildingpermit',function(e){
      showLoader();
      e.preventDefault();
      e.stopPropagation();
      var url = $(this).attr('action');
      var method = $(this).attr('method');
      var data   = $(this).serialize();
      $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
          hideLoader();
          if(html.status == 'validation_error'){
            $('.validate-err').html('');

            $('#err_'+html.field_name).html(html.error);
            $('.'+html.field_name).focus();
          }if(html.status == 'success'){
            $('.validate-err').html('');
            //$('#addBuildingPermitmodal').modal('hide');
              Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Data Saved Successfully.',
                  showConfirmButton: false,
                  timer: 1500
                })
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

    $(document).off('submit','#storesanitarypermit').on('submit','#storesanitarypermit',function(e){
      showLoader();
      e.preventDefault();
      e.stopPropagation();
      var url = $(this).attr('action');
      var method = $(this).attr('method');
      var data   = $(this).serialize();
      $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
          hideLoader();
          if(html.status == 'validation_error'){
            $('.validate-err').html('');

            $('#err_'+html.field_name).html(html.error);
            $('.'+html.field_name).focus();
          }if(html.status == 'success'){
            $('.validate-err').html('');
            //$('#addSanitaryPermitmodal').modal('hide');
            Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Data Saved Successfully.',
                  showConfirmButton: false,
                  timer: 1500
                })
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

     $(document).off('submit','#storeelecticpermit').on('submit','#storeelecticpermit',function(e){
      showLoader();
      e.preventDefault();
      e.stopPropagation();
      var url = $(this).attr('action');
      var method = $(this).attr('method');
      var data   = $(this).serialize();
      $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
          hideLoader();
          if(html.status == 'validation_error'){
            $('.validate-err').html('');

            $('#err_'+html.field_name).html(html.error);
            $('.'+html.field_name).focus();
          }if(html.status == 'success'){
            $('.validate-err').html('');
            //$('#addElecticPermitmodal').modal('hide');
            Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Data Saved Successfully.',
                  showConfirmButton: false,
                  timer: 1500
                })
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
     
     $(document).off('submit','#storeelectrronicpermit').on('submit','#storeelectrronicpermit',function(e){
      showLoader();
      e.preventDefault();
      e.stopPropagation();
      var url = $(this).attr('action');
      var method = $(this).attr('method');
      var data   = $(this).serialize();
      $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
          hideLoader();
          if(html.status == 'validation_error'){
            $('.validate-err').html('');

            $('#err_'+html.field_name).html(html.error);
            $('.'+html.field_name).focus();
          }if(html.status == 'success'){
            $('.validate-err').html('');
            //$('#addElectronicPermitmodal').modal('hide');
            Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Data Saved Successfully.',
                  showConfirmButton: false,
                  timer: 1500
                })
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

     $(document).off('submit','#storecivilpermit').on('submit','#storecivilpermit',function(e){
      showLoader();
      e.preventDefault();
      e.stopPropagation();
      var url = $(this).attr('action');
      var method = $(this).attr('method');
      var data   = $(this).serialize();
      $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
          hideLoader();
          if(html.status == 'validation_error'){
            $('.validate-err').html('');

            $('#err_'+html.field_name).html(html.error);
            $('.'+html.field_name).focus();
          }if(html.status == 'success'){
            $('.validate-err').html('');
            //$('#addCivilPermitmodal').modal('hide');
            Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Data Saved Successfully.',
                  showConfirmButton: false,
                  timer: 1500
                })
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


   $(document).off('submit','#storemechanicalpermit').on('submit','#storemechanicalpermit',function(e){
      showLoader();
      e.preventDefault();
      e.stopPropagation();
      var url = $(this).attr('action');
      var method = $(this).attr('method');
      var data   = $(this).serialize();
      $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
          hideLoader();
          if(html.status == 'validation_error'){
            $('.validate-err').html('');

            $('#err_'+html.field_name).html(html.error);
            $('.'+html.field_name).focus();
          }if(html.status == 'success'){
            $('.validate-err').html('');
            //$('#addMechanicalPermitmodal').modal('hide');
            Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Data Saved Successfully.',
                  showConfirmButton: false,
                  timer: 1500
                })
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

    $(document).off('submit','#storeexcavationpermit').on('submit','#storeexcavationpermit',function(e){
      showLoader();
      e.preventDefault();
      e.stopPropagation();
      var url = $(this).attr('action');
      var method = $(this).attr('method');
      var data   = $(this).serialize();
      $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
          hideLoader();
          if(html.status == 'validation_error'){
            $('.validate-err').html('');

            $('#err_'+html.field_name).html(html.error);
            $('.'+html.field_name).focus();
          }if(html.status == 'success'){
            $('.validate-err').html('');
            //$('#addExcavationPermitmodal').modal('hide');
            Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Data Saved Successfully.',
                  showConfirmButton: false,
                  timer: 1500
                })
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


    $(document).off('submit','#storearchitecturalpermit').on('submit','#storearchitecturalpermit',function(e){
      showLoader();
      e.preventDefault();
      e.stopPropagation();
      var url = $(this).attr('action');
      var method = $(this).attr('method');
      var data   = $(this).serialize();
      $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
          hideLoader();
          if(html.status == 'validation_error'){
            $('.validate-err').html('');

            $('#err_'+html.field_name).html(html.error);
            $('.'+html.field_name).focus();
          }if(html.status == 'success'){
            $('.validate-err').html('');
            //$('#addArchitecturalPermitmodal').modal('hide');
            Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Data Saved Successfully.',
                  showConfirmButton: false,
                  timer: 1500
                })
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

    $(document).off('submit','#electricalrevision').on('submit','#electricalrevision',function(e){
      showLoader();
      e.preventDefault();
      e.stopPropagation();
      var url = $(this).attr('action');
      var method = $(this).attr('method');
      var data   = $(this).serialize();
      $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
          hideLoader();
          if(html.status == 'validation_error'){
            $('.validate-err').html('');

            $('#err_'+html.field_name).html(html.error);
            $('.'+html.field_name).focus();
          }if(html.status == 'success'){
            $('.validate-err').html('');
            //$('#addArchitecturalPermitmodal').modal('hide');
            Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Data Saved Successfully.',
                  showConfirmButton: false,
                  timer: 1500
                })
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

    $(document).off('submit','#buildingrevision').on('submit','#buildingrevision',function(e){
      showLoader();
      e.preventDefault();
      e.stopPropagation();
      var url = $(this).attr('action');
      var method = $(this).attr('method');
      var data   = $(this).serialize();
      $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
          hideLoader();
          if(html.status == 'validation_error'){
            $('.validate-err').html('');

            $('#err_'+html.field_name).html(html.error);
            $('.'+html.field_name).focus();
          }if(html.status == 'success'){
            $('.validate-err').html('');
            //$('#addArchitecturalPermitmodal').modal('hide');
            Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Data Saved Successfully.',
                  showConfirmButton: false,
                  timer: 1500
                })
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

    $(document).off('submit','#storefencingpermit').on('submit','#storefencingpermit',function(e){
      showLoader();
      e.preventDefault();
      e.stopPropagation();
      var url = $(this).attr('action');
      var method = $(this).attr('method');
      var data   = $(this).serialize();
      $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
          hideLoader();
          if(html.status == 'validation_error'){
            $('.validate-err').html('');

            $('#err_'+html.field_name).html(html.error);
            $('.'+html.field_name).focus();
          }if(html.status == 'success'){
            $('.validate-err').html('');
            //$('#addFencingPermitmodal').modal('hide');
            Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Data Saved Successfully.',
                  showConfirmButton: false,
                  timer: 1500
                })
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

     $(document).off('submit','#storesigngpermit').on('submit','#storesigngpermit',function(e){
      showLoader();
      e.preventDefault();
      e.stopPropagation();
      var url = $(this).attr('action');
      var method = $(this).attr('method');
      var data   = $(this).serialize();
      $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
          hideLoader();
          if(html.status == 'validation_error'){
            $('.validate-err').html('');

            $('#err_'+html.field_name).html(html.error);
            $('.'+html.field_name).focus();
          }if(html.status == 'success'){
            $('.validate-err').html('');
            //$('#addSignPermitmodal').modal('hide');
            Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Data Saved Successfully.',
                  showConfirmButton: false,
                  timer: 1500
                })
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

     $(document).off('submit','#storedemolitionpermit').on('submit','#storedemolitionpermit',function(e){
      showLoader();
      e.preventDefault();
      e.stopPropagation();
      var url = $(this).attr('action');
      var method = $(this).attr('method');
      var data   = $(this).serialize();
      $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        success: function(html){ 
          hideLoader();
          if(html.status == 'validation_error'){
            $('.validate-err').html('');

            $('#err_'+html.field_name).html(html.error);
            $('.'+html.field_name).focus();
          }if(html.status == 'success'){
            $('.validate-err').html('');
            //$('#addDemolitionPermitmodal').modal('hide');
             Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Data Saved Successfully.',
                  showConfirmButton: false,
                  timer: 1500
                })
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
         


    function loadAddServiceForm(id, sessionId){
      showLoader();
      $('.loadingGIF').show();
      var filtervars = {
        id:id,
        sessionId:sessionId,
        request_id:$('input[name=id]').val()
      }; 
      $.ajax({
        type: "get",
        url: DIR+'engjobrequest/showserviceform',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          hideLoader();
          $('#serviceform').html(html);
          setTimeout(function(){ 
            $('#loadAddserviceForm').find(".pc_class_code").select3({});
          }, 500);
        },
        error: function(){
          hideLoader();
        }
      });
    }  

    $('#es_id').on('change', function() {
      var es_id =$(this).val();
      $("#es_idhidden").val(es_id);
      $.ajax({
        url :DIR+'engjobrequest/getApplicationType', // json datasource
        type: "POST", 
        data: {
          "es_id": es_id, "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          var arr = html.split('#');
          $("#applicationtype").val(arr[0]);
          $("#tfoc_idhidden").val(arr[1]);
          $("#istaxpayersref").val(arr[2]);
          Getrquirements(arr[1]);
          isreferencevalid();
        }
       })
      
    });

    $("#clientrefenere").click(function(){
      //isclientrefernce();
           $("#applicantname").val('');
            $("#nameofproject").val('');
            $("#issueddate").val('');
            $("#completeaddress").val('');
     });

  function isreferencevalid(){
    if($("#istaxpayersref").val() == 1)
    {
      $("#clientrefenere").prop('disabled',false);
      $("#zoningcertid").removeClass('disabled-field');
    }else{
      $("#clientrefenere").prop('disabled',true);
      $("#zoningcertid").addClass('disabled-field');
      if($("#id").val() <= 0){
      $('#zoning_cert_id').val(''); // Select the option with a value of '1'
      $('#zoning_cert_id').trigger('change'); // Notify any JS components that the value changed
      }
    }
  }
    function isclientrefernce(){
      if($('#clientrefenere').is(":checked")){
        var isrefrence = 1; 
      }else{
        var isrefrence = 0;
      }
       var cleintid = $("#client_id option:selected").val(); ;
          $.ajax({
            url :DIR+'engjobrequest/getFalcnobycleint', // json datasource
            type: "POST",
            dataType: "html", 
            data: {
              "cleintid": cleintid,"isrefrence": isrefrence, "_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#zoning_cert_id").html(html);
            }
           })
     }

     $('#zoning_cert_id').on('change', function() {
      var certid = $(this).val();
        getZoninginfo(certid);
        if(certid > 0){
          $('#locationdiv').addClass('disabled-field');
          $.ajax({
            url :DIR+'engjobrequest/getbarngaybyfalcno', // json datasource
            type: "POST",
            dataType: "html", 
            data: {
              "certid": certid,"_token": $("#_csrf_token").val(),
            },
            success: function(html){
              $("#location_brgy_id").html(html);
            }
          })
        }else{ $('#locationdiv').removeClass('disabled-field'); }
         
    });

     var zoningcertid =  $("#zoning_cert_id option:selected").val(); 
        if(zoningcertid > 0){
           getZoninginfo(zoningcertid);
         // getApplicantDetails(applicantid);
        } 

     function getZoninginfo(certid){
         $("#applicantname").val('');
          $("#nameofproject").val('');
          $("#issueddate").val('');
          $("#completeaddress").val('');
        var certid = certid;
        if(certid > 0){
          $.ajax({
            url :DIR+'engjobrequest/getZoninginfo', // json datasource
            type: "POST", 
            data: {
              "certid": certid, "_token": $("#_csrf_token").val(),
            },
            dataType: "json",
            success: function(html){
               arr = html;
              $("#applicantname").val(arr.full_name);
              $("#nameofproject").val(arr.cc_name_project);
              $("#issueddate").val(arr.cc_date);
              $("#completeaddress").val(arr.brgy_name);
            }
           })
        }
     }

    $("#btn_addmore_feedetails").click(function(){
      $('html, body').stop().animate({
      scrollTop: $("#ejr_total_net_amount").offset().top
    }, 600);
    addmorefees();
    });
    $(".btn_cancel_feesdetail").click(function(){
      var id = $(this).val();
      deleteFeeDetails(id,$(this))
      
    });

    $('.amount').on('change keyup',function(){
      var _self = $(this);
          //_self.val(price_separator(_self.val()));
           calculatetotal(); 
           //calculatechange();
    })
    $('#ejr_surcharge_fee').on('change keyup',function(){
           calculatechange(); 
    })

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

   //  $('#ejr_lineandgrade,#ejr_excavation,#ejr_building,#ejr_electrical,#ejr_plumbing,#ejr_mechanical,#ejr_fence,#ejr_sign,#ejr_inspection,#ejr_surcharge,#ejr_others').on('change keyup',function(){
   //           claculateTotalORPayment(); 
   //  })

   // function claculateTotalORPayment(){
   //           var lainefee = +$("#ejr_lineandgrade").val();
   //           var excafee = +$("#ejr_excavation").val();
   //           var buildfee = +$("#ejr_building").val();
   //           var electrifee = +$("#ejr_electrical").val();
   //           var plumfee = +$("#ejr_plumbing").val();
   //           var mechfee = +$("#ejr_mechanical").val();
   //           var fenchfee = +$("#ejr_fence").val();
   //           var signfee = +$("#ejr_sign").val();
   //           var inspfee = +$("#ejr_inspection").val();
   //           var surfee = +$("#ejr_surcharge").val();
   //           var otherfee = +$("#ejr_others").val();
              
   //           var total = parseInt(lainefee) + parseInt(excafee) + parseInt(buildfee) +parseInt(electrifee) +parseInt(plumfee) + parseInt(mechfee) + parseInt(fenchfee) + parseInt(signfee) +parseInt(inspfee) +parseInt(surfee) + parseInt(otherfee);
              
   //           $("#ejr_totalfees").val(total);
   // }
  $("#saveorderpayment").click(function(e){
    e.preventDefault();
    var  appid = $("#id").val();
    var serviceid = $(this).attr('serviceid');
    SaveOrderofpayment(appid,serviceid);
  })

  function SaveOrderofpayment(appid, serviceid) {
    Swal.fire({
        title: "Are you sure?",
        html: '<span style="color: red;">It will not change details after the confirmation?</span>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // User clicked 'Yes', proceed with the AJAX call
            var filtervars = {
                appid: appid,
                serviceid: serviceid,
                tfocid: $("#tfoc_idhidden").val(),
                amount: $("#ejr_totalfees").val(),
                "_token": $("#_csrf_token").val()
            };

            $.ajax({
                type: "POST",
                url: DIR + 'engjobrequest/saveorderofpayment',
                data: filtervars,
                dataType: "json",
                success: function (html) {
                    hideLoader();
                    if (html.status == "success") {
                        if (html.transid > 0) {
                            storeEngbillSummary(html.transid);
                        }
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Order Of Payment Saved Successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        //location.reload();
                    }

                },
                error: function () {
                    hideLoader();
                }
            });
        }
    });
}


function  storeEngbillSummary(transaction_no) {
     $.ajax({
        url :DIR+'engjobrequest/storeEngbillSummary', // json datasource
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

 
  function addmorefees(){
     var prevLength = $("#hidenFeesHtml").find(".removerfeesdata").length;
     var html = $("#hidenFeesHtml").html();
        $("#defaultfeesDetails").append(html);
        $(".btn_cancel_feesdetail").click(function(){
        $(this).closest(".removerfeesdata").remove();
        });
         $('.amount').on('change keyup',function(){
          var _self = $(this);
         // _self.val(price_separator(_self.val()));
           calculatetotal(); 
           //calculatechange();
        })
    }

  function calculatechange(){
        var totalfinal = 0;
        //var curramount = +$("#ejr_total_net_amount").val();
        var curramount = parseFloat($("#ejr_total_net_amount").val().replace(/\,/g,'') || 0);
        //var surchargeamount = +$("#ejr_surcharge_fee").val();
        var surchargeamount = parseFloat($("#ejr_surcharge_fee").val().replace(/\,/g,'') || 0);
        totalfinal = parseFloat(curramount)+ parseFloat(surchargeamount);
        totalfinal = totalfinal.toFixed(2);
        $("#ejr_totalfees").val(totalfinal); 
  }
  function calculatetotal(){
        var total = 0;
        $( "#defaultfeesDetails .amount" ).each(function() {
          var curramount = parseFloat($(this).val().replace(/\,/g,'') || 0);
          total =  parseFloat(total) + parseFloat(curramount);
        });
        total = total.toFixed(2);
        $("#ejr_total_net_amount").val(total);
        var surchargeamount = parseFloat($("#ejr_surcharge_fee").val().replace(/\,/g,'') || 0);
        var totalfinal = parseFloat(total)+ parseFloat(surchargeamount);
        $("#ejr_totalfees").val(totalfinal);
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
         url :DIR+'engjobrequest/deleteFeedetails', // json datasource
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
});


