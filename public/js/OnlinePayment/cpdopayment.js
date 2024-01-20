$(document).ready(function(){
  $("#cancelreason").select3({dropdownAutoWidth : false,dropdownParent: $("#cancelreasondiv")});
     $("#applicationdate").datepicker({
              dateFormat: 'dd/mm/yy',
              minDate: -1,
              maxDate: 0
      });

    $('form').submit(function(e) {
        e.preventDefault();
         checkOrUsedOrNot(e);
      });
    });
   
    //$("#clientid").select3({});
          // calculatetotal();
            $("#permittype").change(function(){
                var id=$(this).val();
                if(id){ gettotransactions(id); }
            })

            if($("#id").val() > 0) {
               //addmoretaxfeesonload();
            }
            // $('#cancelor').click(function() {
            //     if ($(this).is(':checked')) {
            //          $("#orderCanceltModal").modal('show');
            //          $("#submit").val('Cancel Payment');
            //     }else{
            //         $("#orderCanceltModal").modal('hide');
            //         $("#submit").val('Save Payment');
            //     }
            // })
            $("#jqCancelOr").click(function(){
                $("#orderCanceltModal").modal('show');
            })

            if($("#id").val() > 0 ){
              var id = $("#top_transaction_id option:selected").val();
               gettransaction(id);
               getAmountinword($("#total_amount").val());
            }
            if ($("#isuserrangenew").is(':checked')) {
              var checked = '1';   Getornumebr(checked);
            }else{
                 var checked = '0'; Getornumebr(checked);
            } 

           $("#btn_addmore_feetaxes").click(function(){
            addmoretaxfees();
            });
            $('#isuserrangenew').click(function() {
                if ($(this).is(':checked')) {
                  var checked = '1';   $("#or_noshow").removeClass("disabled-field");
                }else{
                     var checked = '0';    $("#or_noshow").addClass("disabled-field");
                }
               Getornumebr(checked)
          });
         //  $("#cancelorbutton").click(function(e){
         //    //e.preventDefault();
         //    CancelORpayment();
         // })
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

    async function checkOrUsedOrNot(e){
     await $.ajax({
        url :DIR+'cashering/checkOrInrange', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
            "or_no":$('#or_noshow').val(),
            "id":'5',
            "_token": $("#_csrf_token").val(),
        },
        success: function(data){
            if(data.isUsed == null || data.isUsed ==""){
                $("#err_or_no").html(data.errMsg);
                $("#or_noshow").attr("disabled",false);
            }else{
                //setConfirmAlert(e);
                 var recordid = $("#pid").val();
                  Approved(recordid);
                $("#err_or_no").html('');
                $("#or_noshow").attr("disabled",false);
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
        text: "You want to Approve?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if(result.isConfirmed){
          var recordid = $("#pid").val();
          Approved(recordid);
        }
     });
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
           "payment_history_id": id,
           "or_num": $("#or_noshow").val(),
            "cashier_or_date": $("#applicationdate").val(),
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
            //location.reload();
           }
         })
        }
      })
   }

  function getAmountinword(amount){
        var paidamount = amount;
        var filtervars = {
          amount:paidamount,
          "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
          type: "POST",
          url: DIR+'cpdocashering/getamountinword',
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
        $("#totalamount").val(total);  $("#total_amount").val(total);
    }

    function Getornumebr(checked){
         $.ajax({
                url :DIR+'cpdocashering/getOrnumber', // json datasource
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

    function gettransaction(id){
      $('.loadingGIF').show();
      var filtervars = {
        pid:id,
        typeid: $("#permittype option:selected").val(),
      }; 
      $.ajax({
        type: "post",
        url: DIR+'cpdocashering/getTransactionid',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          $('.loadingGIF').hide();
          arr = $.parseJSON(html);
            //$("#top_transaction_id").html(html);
             // var arr = html.split('#');
             $('#permittype>option[value='+arr.permittype+']').prop('selected', true);
             $('#clientid>option[value='+arr.client_id+']').prop('selected', true);
             $('#taxfeescpdo>option[value='+arr.tfoc_id+']').prop('selected', true);
             $("#maintfoc_id").val(arr.tfoc_id); 
             $("#controlno").val(arr.caf_control_no);  $("#appdate").val(arr.caf_date); $("#apptype").val(arr.cm_id);
             $("#cpdoamount").val(arr.caf_total_amount);  $("#totalamount").val(arr.caf_amount); $("#total_amount").val(arr.caf_amount);
             $(".finalTotal").html(arr.caf_amount.toFixed(2));
             var apptype= 1;
             if($("#permittype option:selected").val() =='2'){ $("#apptype").val('Development Permit'); var  apptype= 2;}

             var appid = arr.appid;  var filtervars = {
                    appid:appid,
                    cashierid:arr.topid,
                    apptype:apptype
                  }; 
                $.ajax({
                    type: "post",
                    url: DIR+'cpdocashering/getpenaltyfee',
                    data: filtervars,
                    dataType: "html",
                    success: function(html){ 
                      $('.loadingGIF').hide();
                       $("#FeesDetailspenalty").append(html); 
                    }
                  });
             
          }
      });
    }

    function gettotransactions(id){
      $('.loadingGIF').show();
      var filtervars = {
        typeid:id,
      }; 
      $.ajax({
        type: "post",
        url: DIR+'cpdocashering/getTransactionbytype',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
          $('.loadingGIF').hide();
            $('#top_transaction_id').html(html);
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