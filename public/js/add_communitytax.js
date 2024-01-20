 $(document).ready(function(){
    $("#cancelreason").select3({dropdownAutoWidth : false,dropdownParent: $("#cancelreasondiv")});
           //$("#clientid").select3({});
           if($("#id").val()<=0){
           select3AjaxCommunity("client_citizen_id","communitytaxtaxpayers","community-tax/gettaxpayerssearch");
           }
           $("#applicationdate").datepicker({
              dateFormat: 'dd/mm/yy',
              minDate: -1,
              maxDate: 0
          });
           $('form').submit(function(e) {
              e.preventDefault();
              if($(this).attr('id') !='formdtlcancelid'){
              $(".validate-err").html('');
              var myForm = $(this);
              myForm.find("input[name='submit']").unbind("click");
              // var myform = $('form');
              var disabled = myForm.find(':input:disabled').removeAttr('disabled');
              var data = myForm.serialize().split("&");
              disabled.attr('disabled','disabled');
              var obj={};
              for(var key in data){
                  obj[decodeURIComponent(data[key].split("=")[0])] = decodeURIComponent(data[key].split("=")[1]);
              }
              $.ajax({
                  url :$(this).attr("action")+'/formValidation', // json datasource
                  type: "POST", 
                  data: obj,
                  dataType: 'json',
                  success: function(html){
                      if(html.ESTATUS){
                          $("#err_"+html.field_name).html(html.error)
                          $("#"+html.field_name).focus();
                      }else{
                          checkOrUsedOrNot(e);
                      }
                  }
              })
            }
          });
           $('body').on('keypress', '.numeric-only', function (event) {
              var charCode = (event.which) ? event.which : event.keyCode    

              if (String.fromCharCode(charCode).match(/[^0-9]/g)) {
                return false;             
              }
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
            calculatetotal();
            $("#client_citizen_id").change(function(){
             var id=$(this).val();
              if(id){ 
					getprofiledata(id);
					Getclientbussibness(id); }
            })
            if($("#id").val() ==""){
               addmoretaxfeesonload();
            }
           if($("#id").val() ==""){ 
            if ($("#isuserrange").is(':checked')){
             // $("#or_no").removeClass("disabled-field");
              var checked = '1'; Getornumebr(checked);
            }else{
                 var checked = '0'; Getornumebr(checked);
                // $("#or_no").addClass("disabled-field");
            }
          }
            // $('#cancelor').click(function() {
            //     if ($(this).is(':checked')) {
            //          $("#orderCanceltModal").modal('show');
            //          $("#submit").val('Cancel Payment');
            //     }else{
            //         $("#orderCanceltModal").modal('hide');
            //         $("#submit").val('Save Payment');
            //     }
            // });

            $("#jqCancelOr").click(function(){
                $("#orderCanceltModal").modal('show');
            })
            
            $(".closeCancelModal").click(function(){
                 $("#orderCanceltModal").modal('hide');
            });

           $("#btn_addmore_feetaxes").click(function(){
            addmoretaxfees();
            });
            $(".btn_cancel_feetax").click(function(){
                 $(this).closest(".removefeetaxdata").remove();
            });
            $('input[type=radio][name=payee_type]').change(function(){
                var id = this.value;   $("#client_citizen_id").empty();
               // refreshRequestor(id);  
            });
            $("#refeshclient").click(function(){
                var id = $('input[type=radio][name=payee_type]:checked').val();
                //refreshRequestor(id);  
            })
            $('#isuserrange').click(function() {
                if ($(this).is(':checked')) {
                   $("#or_no").removeClass("disabled-field");
                     var checked = '1'; 
                }else{
                $("#or_no").addClass("disabled-field");               
                     var checked = '0'; 
                }
               Getornumebr(checked)
           });
           if($("#client_citizen_id option:selected").val() > 0 ){
           var id = $("#client_citizen_id option:selected").val();
           getprofiledata(id);    
           } 
          $('.amount').on('change keyup',function(){
             calculatetotal(); 
          }) 
          $('#total_paid_amount').on('change keyup',function(){
             calculatechange(); 
          })
          $('#total_amount').on('change keyup',function(){
             calculatechange(); 
          }) 
          
          $("#cancelreason").select3({dropdownAutoWidth : false,dropdownParent: $("#cancelreasondiv")}); 
         //  $("#cancelorbutton").click(function(e){
         //    //e.preventDefault();
         //    CancelORpayment();
         // }) 
    });

     async function checkOrUsedOrNot(e){
         await $.ajax({
            url :DIR+'cashering/checkOrInrange', // json datasource
            type: "POST", 
            dataType: "JSON", 
            data: {
                "or_no":$('#or_no').val(),
                "id":"7",
                "_token": $("#_csrf_token").val(),
            },
            success: function(data){
                if(data.isUsed == null || data.isUsed ==""){
                    $("#err_or_no").html(data.errMsg);
                    $("#or_no").attr("disabled",false);
                }else{
                    setConfirmAlert(e);
                    $("#err_or_no").html('');
                    $("#or_no").attr("disabled",false);
                }
            }
        })
      }

    function setConfirmAlert(e){
      $("form input[name='submit']").unbind("click");
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
              $('form').unbind('submit');
              $("form input[name='submit']").trigger("click");
              $("form input[name='submit']").attr("type","button");
              // $("form input[name='submit']").unbind("click");
              // $("#mainForm input[name='submit']").trigger("click");
          }
      });
     }
     $('.formDtlscancel').submit(function(e) {
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

    function CancelORpayment(){
     var filtervars = {
          id:$("#id").val(),
          ocr_id:$("#ocr_id option:selected").val(),
          remark:$("#remarkother").val(), 
          "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
          type: "POST",
          url: DIR+'community-tax/cancelorpayment',
          data: filtervars,
          dataType: "json",
          success: function(html){ 
             hideLoader();
                   if(html.status="success"){
                        Swal.fire({
                          position: 'center',
                          icon: 'success',
                          title: 'O.R Cancelled Successfully.',
                          showConfirmButton: false,
                          timer: 1500
                        })
                        $("#orderCanceltModal").modal('hide'); 
                        location.reload(); 
                   }
                
      },error:function(){
        hideLoader();
      }
      });
    }
    function calculatechange(){
        var change = 0;
        var curramount = +$("#total_amount").val();
        var paidamount = +$("#total_paid_amount").val();
        chnage = parseFloat(curramount) - parseFloat(paidamount);
        chnage = chnage.toFixed(2);
        chnage =Math.abs(chnage);
        $("#total_amount_change").val(chnage); 
    }

    function Getclientbussibness(id){
         var filtervars = {
            cid:id  
          }; 
          $.ajax({
            type: "post",
            url: DIR+'community-tax/getClientsbussiness',
            data: filtervars,
            dataType: "html",
            success: function(html){ 
              $('.loadingGIF').hide();
                //console.log(arr);
                $("#profession").html(html);
             }
          });
    }
    function formatNumber(nStr){
     nStr += '';
     var x = nStr.split('.');
     var x1 = x[0];
     var x2 = x.length > 1 ? '.' + x[1] : '';
     var rgx = /(\d+)(\d{3})/;
     while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + ',' + '$2');
     }
     return x1 + x2;
  }

    function calculatetotal(){
        var total = 0;
        $( "#FeesDetails .amount" ).each(function() {
          var curramount = +$(this).val();
          total =  parseFloat(total) + parseFloat(curramount);
        });
        total = total.toFixed(2);
        $("#totalamount").val(total);  $("#total_amount").val(total);
         $(".finalTotal").html(formatNumber(total));
    }

    function Getornumebr(checked){
         $.ajax({
                url :DIR+'community-tax/getOrnumber', // json datasource
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

    function getprofiledata(id){
      $('.loadingGIF').show();
      var clienttype = $('input[type=radio][name=payee_type]:checked').val();
      var filtervars = {
        pid:id,
        ctype:clienttype,
      }; 
      $.ajax({
        type: "post",
        url: DIR+'community-tax/getClientsDetails',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          $('.loadingGIF').hide();
          arr = $.parseJSON(html);
          if(arr){
            console.log(arr);
            $("#Address").val(arr.address);
            $("#tinno").val(arr.tinno);  $("#sex").val(arr.gender); $("#dob").val(arr.dateofbirth);
            $("#alien").val(arr.icr_no); 
            $("#status>option[value="+arr.civil_status+"]").prop('selected', true); 
            $("#height").val(arr.height);
            $("#weight").val(arr.weight);   $("#birthplace").val(arr.birth_place); $("#citizenship").val(arr.nationality)
          }
        }
      });
    }

     function addmoretaxfeesonload(){
     var prevLength = $("#FeesDetails").find(".removefeetaxdata").length;
     var html = $("#hidenTaxfeesHtml").html();
        $("#FeesDetails").append(html);
         var classid = $("#FeesDetails").find(".removefeetaxdata").length;
        $("#taxfees"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreTaxfeeDetails")});
        $("#hidenTaxfeesHtml").find('.taxfees').attr('id','taxfees'+classid);
        $(".btn_cancel_feetax").click(function(){
        $(this).closest(".removefeetaxdata").remove();
        });
        var feeid = $("#FeesDetails").find($('#taxfees>option:eq('+1+')').prop('selected', true)).val();
        var currid = $("#FeesDetails").find($('#taxfees>option:eq('+1+')'));
           $.ajax({
                url :DIR+'community-tax/getfeeamount', // json datasource
                type: "POST", 
                data: {
                        "tfocid": feeid, "_token": $("#_csrf_token").val(),
                    },
                success: function(html){
                    currid.closest('.removefeetaxdata').find('.amount').val(html);
                     $(".finalTotal").html(html);
                    $("#total_amount").val(html);
                    $("#totalamount").val(html);
                    // $('.amount').on('change keyup',function(){
                    //      calculatetotal(); 
                    // })
                    // calculatetotal(); 
                     $('.taxfees').on('change', function() { 
                         var feeid =$(this).val();
                         var currid = $(this);
                           $.ajax({
                                url :DIR+'community-tax/getfeeamount', // json datasource
                                type: "POST", 
                                data: {
                                        "tfocid": feeid, "_token": $("#_csrf_token").val(),
                                    },
                                success: function(html){
                                    currid.closest('.removefeetaxdata').find('.amount').val(html);
                                    $(".finalTotal").html(html);
                                    $('.amount').on('change keyup',function(){
                                         calculatetotal(); 
                                    })
                                    calculatetotal(); 
                                }
                            })
                       });
                }
            })
      
    }

    function addmoretaxfees(){
     var prevLength = $("#FeesDetails").find(".removefeetaxdata").length;
     var html = $("#hidenTaxfeesHtml").html();
        $("#FeesDetails").append(html);
         var classid = $("#FeesDetails").find(".removefeetaxdata").length;
        $("#taxfees"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreTaxfeeDetails")});
        $("#hidenTaxfeesHtml").find('.taxfees').attr('id','taxfees'+classid);
        $(".btn_cancel_feetax").click(function(){
        $(this).closest(".removefeetaxdata").remove();
        });
         $('.taxfees').on('change', function() { 
         var feeid =$(this).val();
         var currid = $(this);
           $.ajax({
                url :DIR+'community-tax/getfeeamount', // json datasource
                type: "POST", 
                data: {
                        "tfocid": feeid, "_token": $("#_csrf_token").val(),
                    },
                success: function(html){
                    currid.closest('.removefeetaxdata').find('.amount').val(html);
                    $(".finalTotal").html(html);
                    $('.amount').on('change keyup',function(){
                         calculatetotal(); 
                    })
                    calculatetotal(); 
                }
            })
       });
    }
   function refreshRequestor(id){
   $.ajax({
 
        url :DIR+'community-tax/getClientsDropdown', // json datasource
        type: "POST", 
        data: {
           "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#clientid").html(html);
           // $("#bba_code").html('<option>Please Select</option>');
          }
        }
      })
   }