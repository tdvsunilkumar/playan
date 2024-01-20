$(document).ready(function () {
    $('form').submit(function(e) {
        e.preventDefault();
        $(".validate-err").html('');
        var data = $("form").serialize().split("&");
        var obj={};
        for(var key in data){
            obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
        }
        var oldrptsystem = $('#rptsystem').val();
        var currrptsystem = $('#mun_display_for_rpt').val();
        if(oldrptsystem != currrptsystem){
              const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "Are You Sure. Do you want to continue?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true
                  }).then((result) => {
                    if(result.isConfirmed)
                    {
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
                   }
               });
            } else {
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
            }
        $('.product-list').on('change', function() {
        $('.product-list').not(this).prop('checked', false);  
        });
    });  
   $('#mun_display_for_rpt').change(function(){
           if($(this).val() =='0'){
            $('#locationdetails').find(".mandatoryclass").attr('required',false);
            $('#locationdetails').find(".text-danger").text('');
            }else{  $('#locationdetails').find(".mandatoryclass").attr('required',true);
            $('#locationdetails').find(".text-danger").text('*');
            }
   }); 
           if($('#mun_display_for_rpt').val() =='0'){
            $('#locationdetails').find(".mandatoryclass").attr('required',false);
            $('#locationdetails').find(".text-danger").text('');
            }else{  $('#locationdetails').find(".mandatoryclass").attr('required',true);
            $('#locationdetails').find(".text-danger").text('*');
            }

});


