$(document).ready(function () {
    $(document).off('click','#submitPlantTreeUnitValueButton').on('click','#submitPlantTreeUnitValueButton',function(e) {
        e.preventDefault();
        //alert();
        $('#submitPlantTreeUnitValueForm').find("#pt_ptrees_code").prop("disabled", false);
        $('#submitPlantTreeUnitValueForm').find("#ps_subclass_code").prop("disabled", false);
        $(".validate-err").html('');
        var data = $("form").serialize();
        var obj={};
        for(var key in data){
            obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :DIR+'rptplanttressunitvalue/formValidation', // json datasource
            type: "POST", 
            data: $('#submitPlantTreeUnitValueForm').serialize(),
            dataType: 'json',
            success: function(html){
                if(html.ESTATUS){
                  
                    $("#err_"+html.field_name).html(html.error);
                    $("."+html.field_name).focus();
                }else{
                    Swal.fire({
                    title: "Are you sure?",
                    html: '<span style="color: red;">This will save the current changes.</span>',
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
                       $('#submitPlantTreeUnitValueForm').unbind('submit');
                    $('#submitPlantTreeUnitValueForm').submit();
                    } else {
                        console.log("Form submission canceled");
                    }
                });
                    
                }
            }
        })
      });
      $('.product-list').on('change', function() {
     $('.product-list').not(this).prop('checked', false);  
   });
});


