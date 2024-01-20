$(document).ready(function () {

  leave_type = $('#hrlt_id').val();
  employee_id = $('#hr_employeesid').val();
  

	$('form .modal-body').on('change', '#hrlt_id', function (e) {
    leave_type = $(this).val()
    getRemainLeaves(leave_type,employee_id)
    disableLeaveType()
  });

	$('form .modal-body').on('change', '.leave-date', function (e) {
    disableLeaveApp()
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
    $("[type='radio']").change(function(){
      $("[type='radio']").each(function () {
        textfield = $(this).data('textfield');
        if (textfield) {
          if ($(this).is(':checked')) {
            $(this).next().after('<input class="form-control new-field" id="'+textfield+'" style="width:150px" name="'+textfield+'" type="text">');
          } else {
            $(this).next().next().remove();
          }
        }
      })
    });

      $(".btn_cancel_documents").click(function(){
        var id = $(this).val();
        var thisval = $(this);
        if(id >0){
          deleteDocuments(id,thisval);
        }
      });
    $(".add").click(function(){
          var submit_type = $(this).val();
          if(submit_type == "Save as Draft"){
            $("#submit_type").val('0');
          } else if (submit_type == "Cancel"){
            $("#submit_type").val('1');
          } else{
            $("#submit_type").val('3');
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
               url :DIR+'hr-leaves/deleteAttachment', // json datasource
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
                url: DIR+'hr-leaves/approve',
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
              }
              });
        }
      })
   });

    $('#disapprove-btn').click(function(){
      sequence = $(this).attr('sequence');
      stat = sequence === '0' ? 'Cancelled' : 'Disapproved';
       var filtervars = {
          id:$(this).val(),
          sequence:sequence,
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
         text: "This record will be "+stat,
         icon: 'warning',
         showCancelButton: true,
         confirmButtonText: 'Yes',
         cancelButtonText: 'No',
         reverseButtons: true
       }).then((result) => {
       if(result.isConfirmed){
              $.ajax({
                type: "POST",
                url: DIR+'hr-leaves/disapprove',
                data: filtervars,
                dataType: "html",
                success: function(html){ 
                   hideLoader();
                   $("#disapprove-btn").attr('disabled',true);
                  Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'The request '+stat+' Succesfully',
                        showConfirmButton: false,
                        timer: 1500
                      })
              },error:function(){
              hideLoader();
              }
              });
        }
      })
   });  
});  

function getRemainLeaves(leave_type,employee_id) {
	var token = $("#_csrf_token").val();	
  $.ajax({
    type: "POST",
    url: DIR + 'hr-leaveearn-adjustment/getRemaining',
    data: {
      'type': leave_type,
      'employee': employee_id,
      _token: token
    },
    success: function(response){
      $('#remainingdays').val(response);
    }
  })
}

function disableLeaveApp() {
  startdate = $('#hrl_start_date').val();
  enddate = $('#hrl_end_date').val();
  if (startdate == enddate) {
    $('#hrla_id option:not(:first-child)').prop('disabled', false);
  } else {
    $('#hrla_id option:not(:first-child)').prop('disabled', true);

  }
}

function disableLeaveType() {
  $('.special-field').prop('disabled', true)

  selected = $('#hrlt_id').find('option:selected').text()
        console.log('change')
    switch (selected.toLowerCase()) {
      case 'sick leave':
        $('.sick-leave-grp').attr("disabled",false);
        $('.special-field').not('.sick-leave-grp').prop('checked', false)
        $('[type="text"].special-field').not('.sick-leave-grp').val('')
        break;
      case 'special privilege leave':
        $('.spl-grp').attr("disabled",false);
        $('.special-field').not('.spl-grp').prop('checked', false)
        $('[type="text"].special-field').not('.spl-grp').val('')
        break;
      case 'vacation leave':
        $('.spl-grp').attr("disabled",false);
        $('.special-field').not('.spl-grp').prop('checked', false)
        $('[type="text"].special-field').not('.spl-grp').val('')
        break;
      case 'maternity leave':
        $('.maternity-grp').attr("disabled",false);
        $('.special-field').not('.maternity-grp').prop('checked', false)
        $('[type="text"].special-field').not('.maternity-grp').val('')
        break;
      case 'study leave':
        $('.study-grp').attr("disabled",false);
        $('.special-field').not('.study-grp').prop('checked', false)
        $('[type="text"].special-field').not('.study-grp').val('')
        break;
      default:
        $('.special-field').prop('checked', false)
        $('[type="text"].special-field').val('')
        $('.new-field').remove()
        console.log('other')
        break;
    }
}