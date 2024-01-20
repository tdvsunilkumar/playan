$(document).ready(function(){
  $("#cancelreason").select3({dropdownAutoWidth : false,dropdownParent: $("#cancelreasondiv")});
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
            $("#top_transaction_id").change(function(){
                var id=$(this).val();
                if(id){ gettransaction(id); }
            })

            if($("#id").val() =="") {
              // addmoretaxfeesonload();
            }
            $('#cancelor').click(function() {
                if ($(this).is(':checked')) {
                     $("#orderCanceltModal").modal('show');
                     $("#submit").val('Cancel Payment');
                }else{
                    $("#orderCanceltModal").modal('hide');
                    $("#submit").val('Save Payment');
                }
            });

            if($("#id").val() > 0 ){
              var id = $("#top_transaction_id option:selected").val();
               gettransactionedit(id);
            }
            if($("#id").val() <= 0){ 
            if ($("#isuserrange").is(':checked')) {
                 var checked = '1'; Getornumebr(checked); 
            }else{
                 var checked = '0'; Getornumebr(checked);   
            } }

            $('input[type=radio][name=payment_terms]').change(function(){
                var id = this.value;
                managecheckdetails(id);  
            });
            
            var radionid = $('input[type=radio][name=payment_terms]:checked').val();
            if(radionid > 1){
              managecheckdetails(radionid);  
            }

           $("#btn_addmore_feetaxes").click(function(){
            addmoretaxfees();
            });
            $(".btn_cancel_feetax").click(function(){
                 $(this).closest(".removefeetaxdata").remove();
            });
            $("#btn_addmore_check").click(function(){
            addmorecheck();
            });
            $(".btn_cancel_check").click(function(){
                 $(this).closest(".removecheckdata").remove();
            });
            $("#btn_addmore_bank").click(function(){
            addmorebank();
            });
            $(".btn_cancel_bank").click(function(){
                 $(this).closest(".removebankdata").remove();
            });
            $('#isuserrange').click(function() {
                if ($(this).is(':checked')) {
                  var checked = '1'; $("#or_no").removeClass("disabled-field");
                }else{
                     var checked = '0';  $("#or_no").addClass("disabled-field");
                }
               Getornumebr(checked)
          });
          
          $('.amount').on('change keyup',function(){
             calculatetotal(); 
          })
          $('#total_paid_amount').on('change keyup',function(){
             calculatechange(); //getAmountinword();
          })
          if($("#total_paid_amount").val() > 0){
            //getAmountinword();
          }
          $('#total_amount').on('change keyup',function(){
             calculatechange(); 
          })
         //   $("#cancelorbutton").click(function(e){
         //    //e.preventDefault();
         //    CancelORpayment();
         // })
         $("#jqCancelOr").click(function(){
                $("#orderCanceltModal").modal('show');
            })
     });

       async function checkOrUsedOrNot(e){
         await $.ajax({
            url :DIR+'cashering/checkOrInrange', // json datasource
            type: "POST", 
            dataType: "JSON", 
            data: {
                "or_no":$('#or_no').val(),
                "id":'3',
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
              var myForm = $('#casherform');
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
                  url :myForm.attr("action")+'/formValidation', // json datasource
                  type: "POST", 
                  data: obj,
                  dataType: 'json',
                  success: function(html){
                      if(html.ESTATUS){
                          $("#err_"+html.field_name).html(html.error)
                          $("#"+html.field_name).focus();
                      }else{
                         $('form').unbind('submit');
                          $("form input[name='submit']").trigger("click");
                          $("form input[name='submit']").attr("type","button");
              // $("form input[name='submit']").unbind("click");
                      }
                  }
              })
          }
      });
     }

    function getAmountinword(amount){
        var paidamount = amount;
        var filtervars = {
          amount:paidamount,
          "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
          type: "POST",
          url: DIR+'engcashering/getamountinword',
          data: filtervars,
          dataType: "html",
          success: function(html){ 
             hideLoader();
                   $("#amountinword").val(html);
              },error:function(){
                hideLoader();
           }
      });
    }

    function CancelORpayment(){
     var filtervars = {
          id:$("#id").val(),
          toptno:$("#top_transaction_id").val(),
          ocr_id:$("#ocr_id option:selected").val(),
          remark:$("#remarkother").val(), 
          "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
          type: "POST",
          url: DIR+'engcashering/cancelorpayment',
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

    function managecheckdetails(id){
      if(id =='3'){ $("#checkdetaildiv").removeClass('hide');
       if($("#id").val() <= 0){ 
       $("#checkDetails").html('');addmorecheck();
     } }
      else{ $("#checkdetaildiv").addClass('hide'); }
      if(id =='2'){ $("#bankdetaildiv").removeClass('hide');
       if($("#id").val() <= 0){ 
      $("#bankDetails").html('');addmorebank();
      } }
      else{ $("#bankdetaildiv").addClass('hide'); }
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

    function calculatetotal(){
        var total = 0;
        $( "#FeesDetails .amount" ).each(function() {
          var curramount = +$(this).val();
          total =  parseFloat(total) + parseFloat(curramount);
        });
        total = total.toFixed(2);
        //$("#totalamount").val(total);  $("#total_amount").val(total);
    }
   var commaCounter = 10; var x, y, z;
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

    function Getornumebr(checked){
         $.ajax({
                url :DIR+'engcashering/getOrnumber', // json datasource
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

     function gettransactionedit(id){
      $('.loadingGIF').show(); var cashierid =$("id").val();
      var filtervars = {
        pid:id
      }; 
      $.ajax({
        type: "post",
        url: DIR+'engcashering/getTransactionid',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          $('.loadingGIF').hide();
            arr = $.parseJSON(html);
            //$("#top_transaction_id").html(html);
             // var arr = html.split('#');
             //   $("#top_transaction_id").html(arr[0]);
             $('#clientid>option[value='+arr.client_id+']').prop('selected', true);
             $('#taxfeescpdo>option[value='+arr.tfoc_id+']').prop('selected', true);
             $("#maintfoc_id").val(arr.tfoc_id);   $("#engjobrequestno").val(arr.id);
             $("#controlno").val(arr.ejr_jobrequest_no);  $("#appdate").val(arr.created_at); $("#apptype").val(arr.eat_module_desc);
             $("#amountdefault").val(arr.amountdefault);  $("#surcharge").val(arr.ejr_surcharge_fee);
             getAmountinword(arr.ejr_totalfees); //$("#totalamount").val(arr.ejr_totalfees); 
             //$("#total_amount").val(arr.ejr_totalfees); 
             var ejrid = arr.id;  var filtervars = {
                    ejrid:ejrid,
                    cashierid:arr.topid
                  }; 
                $.ajax({
                    type: "post",
                    url: DIR+'engcashering/getallFeesejredit',
                    data: filtervars,
                    dataType: "html",
                    success: function(html){ 
                      $('.loadingGIF').hide();
                       $("#FeesDetails").html(html); 
                    }
                  });
          }
      });
    }

    function gettransaction(id){
      $('.loadingGIF').show();
      var filtervars = {
        pid:id
      }; 
      $.ajax({
        type: "post",
        url: DIR+'engcashering/getTransactionid',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          $('.loadingGIF').hide();
          arr = $.parseJSON(html);
            //$("#top_transaction_id").html(html);
             // var arr = html.split('#');
             //   $("#top_transaction_id").html(arr[0]);
             $('#clientid>option[value='+arr.client_id+']').prop('selected', true);
             $('#taxfeescpdo>option[value='+arr.tfoc_id+']').prop('selected', true);
             $("#maintfoc_id").val(arr.tfoc_id);  $("#engjobrequestno").val(arr.id);
             $("#controlno").val(arr.ejr_jobrequest_no);  $("#appdate").val(arr.created_at); $("#apptype").val(arr.eat_module_desc);
             $("#amountdefault").val(arr.amountdefault);  $("#totalamount").val(arr.ejr_totalfees); $("#total_amount").val(arr.ejr_totalfees);
             $("#surcharge").val(arr.ejr_surcharge_fee);
             var showTotal = formatNumber(arr.ejr_totalfees.toFixed(2));
             $(".finalTotal").html(formatNumber(showTotal));

             getAmountinword(arr.ejr_totalfees);
             var ejrid = arr.id;  var filtervars = {
                    ejrid:ejrid
                  }; 
                $.ajax({
                    type: "post",
                    url: DIR+'engcashering/getallFeesejr',
                    data: filtervars,
                    dataType: "html",
                    success: function(html){ 
                      $('.loadingGIF').hide();
                       $("#FeesDetails").html(html); 
                    }
                  });
          }
      });
    }

     function addmoretaxfeesonload(){
     var prevLength = $("#hidaddmoretaxfeesenTaxfeesHtml").find(".removefeetaxdata").length;
     var html = $("#hidenTaxfeesHtml").html();
        $("#FeesDetails").append(html);
        $(".btn_cancel_feetax").click(function(){
        $(this).closest(".removefeetaxdata").remove();
        });
        var feeid = $("#FeesDetails").find($('#taxfees>option:eq('+1+')').prop('selected', true)).val();
        var currid = $("#FeesDetails").find($('#taxfees>option:eq('+1+')'));
           $.ajax({
                url :DIR+'cpdocashering/getfeeamount', // json datasource
                type: "POST", 
                data: {
                        "tfocid": feeid, "_token": $("#_csrf_token").val(),
                    },
                success: function(html){
                    currid.closest('.removefeetaxdata').find('.amount').val(html);
                     $('.taxfees').on('change', function() { 
                     var feeid =$(this).val();
                     var currid = $(this);
                       $.ajax({
                            url :DIR+'cpdocashering/getfeeamount', // json datasource
                            type: "POST", 
                            data: {
                                    "tfocid": feeid, "_token": $("#_csrf_token").val(),
                                },
                            success: function(html){
                                currid.closest('.removefeetaxdata').find('.amount').val(html);
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
     var prevLength = $("#hidaddmoretaxfeesenTaxfeesHtml").find(".removefeetaxdata").length;
     var html = $("#hidenTaxfeesHtml").html();
        $("#FeesDetails").append(html);
        $(".btn_cancel_feetax").click(function(){
        $(this).closest(".removefeetaxdata").remove();
        });
         $('.taxfees').on('change', function() { 
         var feeid =$(this).val();
         var currid = $(this);
           $.ajax({
                url :DIR+'cpdocashering/getfeeamount', // json datasource
                type: "POST", 
                data:{
                        "tfocid": feeid, "_token": $("#_csrf_token").val(),
                    },
                success: function(html){
                    currid.closest('.removefeetaxdata').find('.amount').val(html);
                    $('.amount').on('change keyup',function(){
                         calculatetotal(); 
                    })
                    calculatetotal(); 
                }
            })
       });
    }
     function addmorecheck(){
     var prevLength = $("#checkDetails").find(".removecheckdata").length;
     var html = $("#hidencheckHtml").html();
        $("#checkDetails").append(html);
        $(".btn_cancel_check").click(function(){
        $(this).closest(".removecheckdata").remove();
            var cnt = $("#checkDetails").find(".removecheckdata").length;
            $("#hidencheckHtml").find('.fundid').attr('id','fundid'+cnt);
            $("#hidencheckHtml").find('.bankid').attr('id','bankid'+cnt);
        });
        var classid = $("#checkDetails").find(".removecheckdata").length;
        $("#fundid"+prevLength).select3({}); $("#bankid"+prevLength).select3({});
        $("#hidencheckHtml").find('.fundid').attr('id','fundid'+classid);
        $("#hidencheckHtml").find('.bankid').attr('id','bankid'+classid);
    }
    function addmorebank(){
       var prevLength = $("#bankDetails").find(".removebankdata").length;
       var html = $("#hidenbankHtml").html();
        $("#bankDetails").append(html);
        $(".btn_cancel_bank").click(function(){
        $(this).closest(".removebankdata").remove();
            var cnt = $("#bankDetails").find(".removebankdata").length;
            $("#hidenbankHtml").find('.fundidbank').attr('id','fundidbank'+cnt);
            $("#hidenbankHtml").find('.bankidbk').attr('id','bankidbk'+cnt);
        });
        var classid = $("#bankDetails").find(".removebankdata").length;
        $("#fundidbank"+prevLength).select3({}); $("#bankidbk"+prevLength).select3({});
        $("#hidenbankHtml").find('.fundidbank').attr('id','fundidbank'+classid);
        $("#hidenbankHtml").find('.bankidbk').attr('id','bankidbk'+classid);
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