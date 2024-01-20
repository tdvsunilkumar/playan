$(document).ready(function () {
    $(document).on('keyup','.decimalvalue',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
        });
    
    $('form').submit(function(e) {
        e.preventDefault();
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
                    $("#main_error_msg").html(html.error)

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
                myForm.unbind('submit');
                    myForm.find("input[name='submit']").trigger("click");
                    myForm.find("input[name='submit']").attr("type","button");
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


