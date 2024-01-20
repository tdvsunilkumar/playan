$(document).ready(function () {
    $(document).on('keyup','.decimalvalue',function(){
            this.value = this.value.replace(/[^0-9\.]/g,'');
        });
    
    $('form').submit(function(e) {
        e.preventDefault();
        $(".validate-err").html('');
        $("form input[name='submit']").unbind("click");
        var myform = $('form');
        var disabled = myform.find(':input:disabled').removeAttr('disabled');
        var data = myform.serialize().split("&");
        disabled.attr('disabled','disabled');
        var obj={};
        for(var key in data){
            obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :$(this).attr("action")+'/formValidation', // json datasource
            type: "POST", 
            data: obj,
            dataType: 'json',
            success: function(html){
                if(html.ESTATUS){
                    $("#err_"+html.field_name).html(html.error)
                }else{
                    var fileerror =0;
                   $("#requirementsDetails").find("input[type=file]").each(function(index, field){
                       const file = field.files[0];
                        if(file== "undefined"){
                            fileerror =1;
                        }
                    });
                    if(fileerror == 1){
                        const swalWithBootstrapButtons = Swal.mixin({
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                    cancelButton: 'btn btn-danger'
                                },
                                buttonsStyling: false
                            })

                            swalWithBootstrapButtons.fire({
                                title: 'Are you sure?',
                                text: "No/Lack of Uploading Files. Do you want To Save as Draft?",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'No',
                                reverseButtons: true
                            }).then((result) => {
                                if(result.isConfirmed)
                                {
                                    $('form').unbind('submit');
                                    $("form input[name='submit']").trigger("click");
                                    $("form input[name='submit']").attr("type","button");
                                }
                            })
                    }else{
                        $('form').unbind('submit');
                        $("form input[name='submit']").trigger("click");
                        $("form input[name='submit']").attr("type","button"); 
                    }
                }
            }
        })
    });
      $('.product-list').on('change', function() {
     $('.product-list').not(this).prop('checked', false);  
   });
});


