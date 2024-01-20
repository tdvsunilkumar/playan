$(document).ready(function(){
  FormNormal();
    $('.step-contain').not('.show').hide()
    $('.next-btn').click(function(e){
        form = $(this).closest('.step-contain')
        step = form.data('step')
        cancel = 1
        $('.text-danger').empty()
        $('input[name="btn_step"]').remove()
        // require
        form.find('input.required, select.required').each(function(){
          if( !$(this).val() ) {
            $(this).next('.m-form__help').text('Required')
            cancel = 1
          } else {
            cancel = 0
          }
        })

        // valiation
        myform = $(this).closest('form');
        myform.append('<input type="hidden" name="btn_step" value="'+step+'">')
        var url = myform.attr("action");
        var data = myform.serialize().split("&");
        var obj={};
        for(var key in data){
            obj[decodeURIComponent(data[key].split("=")[0])] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :url+'/formValidation', // json datasource
            type: "POST", 
            data: obj,
            dataType: 'json',
            async: false,
            success: function(html){
                    cancel = 0
                if(html.ESTATUS != 0){
                    field = $("#"+html.field_name);
                    field.focus();
                    field.next('.m-form__help').text(html.error);
                    field.closest('div').find('.m-form__help').text(html.error);
                    cancel = 1
                } else {
                    cancel = 0
                }
                
            }
        })
        if (cancel === 0) {
          form.hide()
          next_step = step+1
          formTransition(step,next_step)
        }
  
      })
      $('.back-btn').click(function(e){
        form = $(this).closest('.step-contain')
        step = form.data('step')
        form.hide()
        next_step = step-1
        formTransition(step,next_step)
  
      })
});

function formTransition(step,next_step) {
    $('.step-contain[data-step="'+next_step+'"]').show()
    $('#form-progress-'+step).removeClass('bg-info')
    $('#form-progress-'+step).addClass('bg-light')
    $('#form-progress-'+next_step).addClass('bg-info')
    $('#form-progress-'+next_step).removeClass('bg-light')
  }