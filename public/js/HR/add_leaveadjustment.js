$(document).ready(function () {
  $("#commonModal").find('.body').css({overflow: 'unset'})
  adjustmenShow();
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
    $("#btn_addmore_adjustments").click(function(){
            addmoreadjustments();
    });

    $("#hrlp_id").change(function(){
        var pid = $(this).val();  var filtervars = {
              pid:pid
            }; 
          $.ajax({
              type: "post",
              url: DIR+'hr-leaveearn-adjustment/getAdjustmentdetails',
              data: filtervars,
              dataType: "html",
              success: function(html){ 
                $("#AdjustmentDetails").empty();
                $('.loadingGIF').hide();
                  $("#AdjustmentDetails").append(html); 
              }
        }); 
      });

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
                url: DIR+'hr-leaveearn-adjustment/approve',
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
                   location.reload();
              },error:function(){
              hideLoader();
              }
              });
        }
      })
   });
     
});

   function addmoreadjustments(){
        var classid = $("#AdjustmentDetails").find(".removeadjustmentdata").length;
        $("#hiddenLeaveAdjustmentHtml").find('.hrlt_id').attr('id','hrlt_id'+classid);
     var prevLength = $("#AdjustmentDetails").find(".removeadjustmentdata").length;
     var html = $("#hiddenLeaveAdjustmentHtml").html();
        $("#AdjustmentDetails").append(html);
        $(".btn_cancel_adjustment").click(function(){
        $(this).closest(".removeadjustmentdata").remove();
            var cnt = $("#AdjustmentDetails").find(".removeadjustmentdata").length;
            $("#hiddenLeaveAdjustmentHtml").find('.hrlt_id').attr('id','hrlt_id'+cnt);
        });
        $("#hrlt_id"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#AdjustmentDetails")}); 
      }    

    function adjustmenShow(params) {
      adjustSum = 0;
      $('form input.adjustment').each(function(){
        adjustSum += parseInt(this.value);
      });
      console.log(adjustSum)
      // if (adjustSum == 0) {
      //   $('#approve-btn').hide()
      // } else {
      //   $('#approve-btn').show()
      // }
      
    }