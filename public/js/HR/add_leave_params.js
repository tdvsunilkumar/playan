$(document).ready(function(){
    $("#commonModal").find('.body').css({overflow: 'unset'})
    //add Params
    requireRows = 0;
    $("#leave-params tr .select3").each(function(){
        triggerSelect($(this))
    });
    rmRow()
	$('#leave-params thead').on('click', '.btn_addmore_params', function (e) {
		requireRows = requireRows + 1;

		var html = $('#addLeaveParams').html();
		html = html.replace(/changeid/g, requireRows+'n');
		// html = html.replace(/changename/g, '');
        console.log(requireRows);

		$('#leave-params').find('tbody').append(html);

        $('#contain_'+requireRows+'n'+" .select3").each(function(){
            triggerSelect($(this))
        });

        rmRow()
    });

})

function rmRow() {
	var token = $("#_csrf_token").val();	
	$('#leave-params').on('click', '.btn_remove_params', function (e) {
        btn = $(this);
        btn_id = btn.data('id');
        btn_status = btn.data('status');
        if (btn_id) {
		    var msg = btn_status==1?'restored':'removed';
            Swal.fire({
				title: 'Are you sure?',
				text: "This record will be "+msg,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Yes',
				cancelButtonText: 'No',
				reverseButtons: true
			}).then((result) => {
					if(result.isConfirmed){
                        $.ajax({
                            type: "POST",
                            url: DIR + 'HrleaveParameter/remove-params',
                            data: {
                                'id': btn_id,
                                'hrlpc_is_active': btn_status,
                                _token: token
                            },
                            success: function(response){
                                Swal.fire({
									position: 'center',
									icon: 'success',
									title: 'Update Successfully.',
									showConfirmButton: false,
									timer: 1500
								});
								if (btn_status == 0) {
                                    btn.removeClass('btn-danger')
                                    btn.addClass('btn-primary')
                                    btn.find('i').removeClass('ti-trash')
                                    btn.find('i').addClass('ti-reload');
                                    btn.data('status',1);
                                    btn.prev('.hrlpc_is_active').val(0)
								} else {
									btn.removeClass('btn-primary')
									btn.addClass('btn-danger');
									btn.find('i').removeClass('ti-reload')
									btn.find('i').addClass('ti-trash');
                                    btn.prev('.hrlpc_is_active').val(1)
									btn.data('status',0);
                                }
                            }
                            
                        });
					}
			    })
        } else {
            btn.closest('tr').remove();
        }
    });
    
}
function triggerSelect(select) {
    var id = select.attr('id');    
    var contain = select.closest('tr').attr('id');    
    $("#"+id).select3({
        dropdownAutoWidth : false,
        dropdownParent: $("#"+contain),
    });
}