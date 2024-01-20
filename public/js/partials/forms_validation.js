// rules:
// it creates button field when sending so if its submit or save 

function FormAjaxWithConfirm(message, triggerFunction = function(){}) {
    $('form').submit(function(e) {
        $(this).find("[type='submit']").prop('disabled',true);
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
                formSubmit($(this), triggerFunction);
            }
        });
    });
}
function FormAjax(triggerFunction = function(){} ) {
    formSubmit($('form'),triggerFunction, 'ajax')
};
function FormNormal(triggerFunction = function(){} ) {
    formSubmit($('form'),triggerFunction, 1)

};
function formSubmit(selectedform = $('form'), triggerFunction = function(){},type) {
    selectedform.submit(function(e) {
        myform = $(this);
        // myform.find("[type='submit']").prop('disabled',true);
        e.preventDefault();
        var submitbtn = e.originalEvent.submitter.value;
        var clicked = e.originalEvent.submitter;
        $(".validate-err").html('');
        $("form [type='submit']").unbind("click");
        var disabled = myform.find(':input:disabled').removeAttr('disabled');
        $('.amount_money').each(function(){
            price = $(this).val().replace(",", "");
            $(this).val(price);
        });
        myform.find('input[name="button"]').remove()
        myform.append('<input type="hidden" name="button" value="'+submitbtn+'">')
        disabled.attr('disabled',false);
        // myform.find("[type='submit']").prop('disabled',true);
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
                // myform.find("[type='submit']").prop('disabled',false);
                if(html.ESTATUS){
                    $("#err_"+html.field_name).html(html.error)
                    $("#"+html.field_name).focus();
                    myform.find('input[name="button"]').remove()
                }else{
                    obj['button'] = submitbtn;
                    errorMessage(submitbtn,function(){
                        if (type == 'ajax') {
                            sendToAjax(url, obj,triggerFunction)
                        } else {
                            triggerFunction(html);
                            myform.unbind('submit');
                            myform.trigger('submit');
                            myform.submit();
                            clicked.click()
                        }
                    })
                }
            }
        })
    });
}


function sendToAjax(url, obj,triggerFunction= function(){}) {
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
                $('#'+modal_id).modal('hide');
                triggerFunction(response);
                datatablefunction();
                myform.find("[type='submit']").prop('disabled',false);
            }else{
                if (response.msg) {
                    msg = response.msg
                } else {
                    msg = 'Error'
                }
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: msg,
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
}

function errorMessage(type, triggerFunction= function(){}){
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    if (type == 'submit') {
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
                    triggerFunction();
                }
        });
    } else if (type == 'has_message') {
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "Some Details may not be editable after saving.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
                if(result.isConfirmed)
                {
                    triggerFunction();
                }
        });
    } else {
        triggerFunction();
    }
}