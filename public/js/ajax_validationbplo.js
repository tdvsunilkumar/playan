$(document).ready(function () {
    $('form').submit(function(e) {
        e.preventDefault();
         $("#err_natureofbussiness").text('');
         var ids='';
             $('.natureofbussiness').each(function () {
                    if($(this).val()!='')
                        ids +=$(this).val()+',';
                });
               ids = ids.replace(/,\s*$/, '');
              var id = ids;
              var natureArray = id.split(',');
              let len1 = natureArray.length; //get the length of your array
                let len2 = $.unique(natureArray).length; //the length of array removing the duplicates

                if (len1 > len2) {
                var currid =$(this).attr('id');
                 //  $("#err_natureofbussiness").text(');
                 // $('html, modal-body').animate({scrollTop: '+=200px'}, 800);
                  Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'Nature Of Bussiness Should not be dublicate.',
                      showConfirmButton: false,
                      timer: 3000
                    })
                 return false;
                }
              $('.codeabbrevation').each(function () {
                    if($(this).val()!='')
                        ids +=$(this).val()+',';
                });
               ids = ids.replace(/,\s*$/, '');
              var id = ids;
              var requireArray = id.split(',');
              let len3 = requireArray.length; //get the length of your array
                let len4 = $.unique(requireArray).length; //the length of array removing the duplicates

                if (len4 > len4) {
                   var currid =$(this).attr('id');
                 //  $("#err_requirements").text('Requirements Should not be dublicate');
                 // $('html, modal-body').animate({scrollTop: '+=200px'}, 800);
                    Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'Requirements Should not be dublicate.',
                      showConfirmButton: false,
                      timer: 3000
                    })
                 return false;
                }

        $(".validate-err").html('');
        var data = $("form").serialize().split("&");
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


