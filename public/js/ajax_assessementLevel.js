$(document).ready(function () {
    $(document).off('click','#submitAssessementLevelFormButton').on('click','#submitAssessementLevelFormButton',function(e) {
        e.preventDefault();
        $('#submitAssessementLevelForm').find("#mun_no").attr("disabled", false);
        $('#submitAssessementLevelForm').find("#loc_group_brgy_no").attr("disabled", false);
        $('#submitAssessementLevelForm').find("#rvy_revision_year").attr("disabled", false);
        $('#submitAssessementLevelForm').find("#pk_code").attr("disabled", false);
        $('#submitAssessementLevelForm').find("#pau_actual_use_code").prop("disabled", false);
        $(".validate-err").html('');
        var data = $("form").serialize();
        var obj={};
        for(var key in data){
            obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :DIR+'assessmentlevel/formValidation', // json datasource
            type: "POST", 
            data: $('#submitAssessementLevelForm').serialize(),
            dataType: 'json',
            success: function(html){
                if(html.ESTATUS){
                    if(html.field_name.indexOf('.') != -1){
                        var checkedNew = html.field_name.split('.').join("");
                         $("#err_"+checkedNew).html(html.error);
                    }
                    $("#err_"+html.field_name).html(html.error);
                    $("."+html.field_name).focus();
                }else{
                    const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
         swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "This will Save the Current Changes.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true,
        }).then((result) => {
            if(result.isConfirmed){
               $('#submitAssessementLevelForm').unbind('submit');
                    $('#submitAssessementLevelForm').submit();
            }
        })
                    
                }
            }
        })
      });
      $('.product-list').on('change', function() {
     $('.product-list').not(this).prop('checked', false);  
   });
});


