function FormAjaxWithConfirm(message, triggerFunction = function(){}) {
    $('form').submit(function(e) {
        $(this).find("[type='submit']").prop('disabled','disabled');
        e.preventDefault();
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
                if(result.isConfirmed)
                {
                    sendAjax(e,$(this), triggerFunction);
                }
        });
    });
}
function FormAjax(triggerFunction = function(){} ) {
    $('form').submit(function(e) {
        myform = $(this);
        // myform.find("[type='submit']").prop('disabled',true);
        e.preventDefault();
        var submitbtn = e.originalEvent.submitter.value;
        if (submitbtn == 'submit') {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "Once submit, This form can't be updated.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                    if(result.isConfirmed)
                    {
                        sendAjax(e,myform, triggerFunction);
                    }
            });
        } else {
            sendAjax(e,myform, triggerFunction);
        }
    });
};

function sendAjax(e,myform, triggerFunction = function(){} ) {
    e.preventDefault();
    
    var submitbtn = e.originalEvent.submitter.value;
    $(".validate-err").html('');
    $("form [type='submit']").unbind("click");
    var disabled = myform.find(':input:disabled').removeAttr('disabled');
	$('.amount_money').each(function(){
        price = $(this).val().replace(",", "");
        $(this).val(price);
	});
    
    disabled.attr('disabled',false);
    myform.find("[type='submit']").prop('disabled',true);
    var data = myform.serialize().split("&");
    var url = myform.attr("action");
    var obj={};
    modal_id = myform.closest('.modal').attr('id');
    for(var key in data){
        obj[decodeURIComponent(data[key].split("=")[0])] = decodeURIComponent(data[key].split("=")[1]);
    }

    $.ajax({
        url :url+'/formValidation', // json datasource
        type: "POST", 
        data: obj,
        dataType: 'json',
        success: function(html){
            if(html.ESTATUS){
                $("#err_"+html.field_name).html(html.error)
                $("#"+html.field_name).focus();
                myform.find("[type='submit']").prop('disabled',false);
            }else{
                obj['button'] = submitbtn;
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
                    $.ajax({
                    url :url, // json datasource
                    type: "POST", 
                    data: obj,
                    dataType: 'json',
                    success: function(response){
                        if(response.ESTATUS === 0){
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Saved Successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            triggerFunction(response);
                            $('#'+modal_id).modal('hide');
                            datatablefunction();
                            myform.find("[type='submit']").prop('disabled',false);
                        }else{
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Error.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            myform.find("[type='submit']").prop('disabled',false);
                        }
                        myform.find("[type='submit']").prop('disabled',false);
                        myform.find("[type='submit']").prop('disabled',false);
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Error.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        myform.find("[type='submit']").prop('disabled',false);
                    }
                })
                    } else {
                        console.log("Form submission canceled");
                    }
                });
                
            }
        }
    })
}