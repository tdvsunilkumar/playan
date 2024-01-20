$(document).ready(function () {
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

     $(".btn_cancel_documents").click(function(){
         var id = $(this).val();
         var thisval = $(this);
         if(id >0){
          deleteDocuments(id,thisval);
         }
      });

     $("#btn_addmore_document").click(function(){
      $('html, body').stop().animate({
        scrollTop: $("#btn_addmore_document").offset().top
      }, 600);
      addmoreDocuments();
    }); 

     function addmoreDocuments(){
      var srcount = $("#documentsDetails").find(".serialnoclass").length;
      srcount =parseFloat(srcount) + 1;
      $("#hiddendocumentsHtml").find('.serialnoclass').text(srcount);
       var html = $("#hiddendocumentsHtml").html();
      $("#documentsDetails").append(html);
      $(".btn_cancel_documents").click(function(){
        $(this).closest(".removedocumentsdata").remove();
         });
    }

    function deleteDocuments(id,thisval){
        var rid = id; 
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
               url :DIR+'hr-leaves-approval/deleteAttachment', // json datasource
               type: "POST", 
               data: {
               "rid": rid,
               "_token": $("#_csrf_token").val(),
               },
               success: function(html){
                hideLoader();
                thisval.closest(".removedocumentsdata").remove();
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

    $('.approve-btn').click(function(){
       var filtervars = {
          id:$(this).val(),
          sequence:$(this).attr('sequence'),
          "_token": $("#_csrf_token").val()
        };

      const swalWithBootstrapButtons = Swal.mixin({
         customClass: {
           confirmButton: 'btn btn-success',
           cancelButton: 'btn btn-danger'
         },
         buttonsStyling: false
       })
       swalWithBootstrapButtons.fire({
         title: 'Are you sure?',
         text: "This record will be Approved",
         icon: 'warning',
         showCancelButton: true,
         confirmButtonText: 'Yes',
         cancelButtonText: 'No',
         reverseButtons: true
       }).then((result) => {
       if(result.isConfirmed){
              $.ajax({
                type: "POST",
                url: DIR+'hr-leaves-approval/approve',
                data: filtervars,
                dataType: "html",
                success: function(html){ 
                   hideLoader();
                   $(".approve-btn").attr('disabled',true);
                  Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'The request approved Succesfully',
                        showConfirmButton: false,
                        timer: 1500
                      })
              },error:function(){
                 hideLoader();
                //  location.reload();
                }
              });
        }
      })
   });

    $('#disapprove-btn').click(function(){
    disapprove_reason = $('#hrla_disapproved_remarks').val()
    var filtervars = {
      id:$(this).val(),
      sequence:$(this).attr('sequence'),
      reason:disapprove_reason,
      "_token": $("#_csrf_token").val()
    };
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
      },
      buttonsStyling: false
    })
    if (disapprove_reason) {
      swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "This record will be Disapproved",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
      }).then((result) => {
        if(result.isConfirmed){
          $.ajax({
            type: "POST",
            url: DIR+'hr-leaves-approval/disapprove',
            data: filtervars,
            dataType: "html",
            success: function(html){ 
                hideLoader();
                $("#disapprove-btn").attr('disabled',true);
              Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'The request disapproved Succesfully',
                    showConfirmButton: false,
                    timer: 1500
                  })
          },error:function(){
          hideLoader();
          }
          });
        }
      })
    } else {
      $('#err_hrla_disapproved_remarks').text('Required')
      $('#err_hrla_disapproved_remarks').addClass('required')
    }
    
   });   
    
});  