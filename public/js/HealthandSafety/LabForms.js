function FormAjaxWithConfirm(message, triggerFunction = function(){}){
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
            text: 'message',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
            if(result.isConfirmed)
            {
                sendForm(e,$(this), triggerFunction);
            }
        });
    });
}
function FormAjax(triggerFunction = function(){} ) {
    formSubmit(triggerFunction, 'ajax')
};
function FormNormal(triggerFunction = function(){} ) {
    formSubmit(triggerFunction, 1)
};

function formSubmit(triggerFunction = function(){},type) {
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
                        sendForm(e,myform, triggerFunction,type);
                    }
            });
        } else {
            sendForm(e,myform, triggerFunction,type);
        }
    });
}
function sendForm(e,myform, triggerFunction = function(){},type = 'ajax' ) {
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
            
                if (type == 'ajax') {
                    sendToAjax(url, obj,triggerFunction)
                } else {
                console.log('normal send');
                    triggerFunction(html);
                    myform.unbind('submit');
                    myform.trigger('submit');

                }
            }
        }
    })
}

function sendToAjax(url, obj, triggerFunction = function(){}) {
    // Show confirmation message
    Swal.fire({
        title: "Are you sure?",
        html: '<span style="color: red;">Some Details may not be editable after saving</span>',
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
            // If confirmed, proceed with AJAX request
            $.ajax({
                url: url,
                type: "POST",
                data: obj,
                dataType: 'json',
                success: function(response){
                    if (response.ESTATUS === 0) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Saved Successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#' + modal_id).modal('hide');
                        triggerFunction(response);
                        datatablefunction();
                        myform.find("[type='submit']").prop('disabled', false);
                        console.log(modal_id);
                    } else {
                        let msg = response.msg || 'Error';
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: msg,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        myform.find("[type='submit']").prop('disabled', false);
                    }
                    myform.find("[type='submit']").prop('disabled', false);
                    myform.find("[type='submit']").prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Error.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    myform.find("[type='submit']").prop('disabled', false);
                }
            });
        } else {
            console.log("Form submission canceled");
        }
    });
}