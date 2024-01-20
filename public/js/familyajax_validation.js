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
					//console.log(html.field_name);
					if(html.error =="Citizen is Required"){
						$("#err_cit_id").html(html.error);
						$("#err_cit_id1").html(html.error);
					}
                    $("#err_"+html.field_name).html(html.error);
                    $("#"+html.field_name).focus();
                    $("#main_error_msg").html(html.error)
					

                }else{
					setConfirmAlert(e); 
                }
            }
        })
    });
	function setConfirmAlert(e){
		$("form input[name='submit']").unbind("click");
		const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: 'btn btn-success',
				cancelButton: 'btn btn-danger'
			},
			buttonsStyling: false
		})
		swalWithBootstrapButtons.fire({
			title: 'Are you sure?',
			text: "This will save the current changes.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes',
			cancelButtonText: 'No',
			reverseButtons: true
		}).then((result) => {
			if(result.isConfirmed){
				$('form').unbind('submit');
				$("form input[name='submit']").trigger("click");
				$("form input[name='submit']").attr("type","button");
			}
		});
   }
	
	
    $('.product-list').on('change', function() {
     $('.product-list').not(this).prop('checked', false);  
   });
});