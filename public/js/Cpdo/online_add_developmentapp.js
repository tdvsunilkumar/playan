$(document).ready(function () {
    //alert();
      $("#clientsregistered").select3({dropdownAutoWidth : false,dropdownParent: $("#clientsregistered-group")});
      // $("#p_barangay_id").select3({dropdownAutoWidth : false,dropdownParent: $(".p_barangay_id_group")});
      $('#approve').html('Approve').prop('disabled', false).removeClass('disabled');
      $('#decline').html('decline').prop('disabled', false).removeClass('disabled');
      $('#approve').click(function(e) {
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
          //e.preventDefault();
          $(".validate-err").html('');
          //$("form input[name='submit']").unbind("click");
          var myform = $('#onlineCpdoApp');
          var id =  $('#id').val();
          var _self = $('#approve');
          var disabled = myform.find(':input:disabled').removeAttr('disabled');
          var data = myform.serialize().split("&");
          disabled.attr('disabled','disabled');
          _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right disabled');
          var obj={};
          for(var key in data){
              obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
          }
          $.ajax({
              url :$('#onlineCpdoApp').attr("action")+'/approve/'+id, // json datasource
              type: "POST", 
              data: obj,
              dataType: 'json',
              success: function(response){
                if(response.success == false){
                  $('#approve').html('Approve').prop('disabled', false).removeClass('disabled');
                    Swal.fire({
                        title: "Oops...",
                        html: response.msg,
                        icon: "warning",
                        type: "warning",
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
                    });
                }else{
                  hideLoader();
                 Swal.fire({
                 position: 'center',
                 icon: 'success',
                 title: 'Update Successfully.',
                 showConfirmButton: false,
                 timer: 1500
                 })
                 // window.location.href = $('#onlineCpdoApp').attr("action");
                }
              }
          })
        }
        })
      });
  
      $('#decline').click(function(e) {
        $("#err_cdf_remarks").val('');
        var remark = $("#cdf_remarks").val();
         if(remark ==""){
          $("#err_cdf_remarks").text('Remark is required');
          $("#cdf_remarks").focus();
         }else{
        //e.preventDefault();
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
              $(".validate-err").html('');
              //$("form input[name='submit']").unbind("click");
              var myform = $('#onlineCpdoApp');
              var id =  $('#id').val();
              var _self = $('#decline');
              var disabled = myform.find(':input:disabled').removeAttr('disabled');
              var data = myform.serialize().split("&");
              disabled.attr('disabled','disabled');
              _self.prop('disabled', true).html('wait.....').addClass('m-btn--custom m-loader m-loader--light m-loader--right disabled');
              var obj={};
              for(var key in data){
                  obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
              }
               $.ajax({
                url :$('#onlineCpdoApp').attr("action")+'/decline/'+id, // json datasource
                type: "POST", 
                data: obj,
                dataType: 'json',
                success: function(html){
                   hideLoader();
                   Swal.fire({
                   position: 'center',
                   icon: 'success',
                   title: 'Update Successfully.',
                   showConfirmButton: false,
                   timer: 1500
                   })
                  window.location.href = $('#onlineCpdoApp').attr("action");
                }
            })
         }
        })
      }
    });
   });
    


  
  
   
    
  