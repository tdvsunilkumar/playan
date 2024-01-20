$(document).ready(function () {
    $('form').submit(function(e) {
        e.preventDefault();
        $(".validate-err").html('');
        var myform = $('form');
        var disabled = myform.find(':input:disabled').removeAttr('disabled');
        var data = myform.serialize();
        disabled.attr('disabled','disabled');
        var obj={};
        for(var key in data){
            obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :$(this).attr("action")+'/formValidation', // json datasource
            type: "POST", 
            data: data,
            dataType: 'json',
            success: function(html){
                if(html.ESTATUS){
                    $("#err_"+html.field_name).html(html.error)
                }else{
                    $('form').unbind('submit');
                    $("form input[name='submit']").trigger("click");
                }
            }
        })
    });
      $('.product-list').on('change', function() {
     $('.product-list').not(this).prop('checked', false);  
   });
});


