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
                    var ordererror =0; var arr = [];
                    $(".order").each(function(index, field){
                        var value = $(this).val();
                        if (arr.indexOf(value) == -1)
                            arr.push(value);
                        else
                            ordererror =1 ;  
                    });
                    if(ordererror == 1){
                        Swal.fire({
                          position: 'center',
                          icon: 'warning',
                          title: 'Order Number Should Be Unique.',
                          showConfirmButton: false,
                          timer: 1500
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


