$(document).ready(function () {
	$('#diagnosis-section').on('click', '#add-diagnosis', function (e) {
        addDiagnosis()
	});
    $('#treatment-section').on('click', '#add-treatment', function (e) {
        addTreatment()
	});
    $('.new-row .diagnosis-specify').hide()
    showOthers()
    FormAjax(function (response) {
        if (myform.closest('.modal').attr('id') != 'commonModal') {
                medicalRecordTable()
        }
    });

    $('.remove-diagnosis').click(function (e) { 
        var inputVal = $(this).data('value');
        var inputid = $(this).data('id');
        let msg = "";
        if(inputVal == 1){
            msg = "Deleted";
        }else{
            msg = "Restored";
        }
        
        if (inputVal == 0 || inputVal == 1) {
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
                    showLoader();
                    $.ajax({
                            type: "GET",
                            url: DIR+'medical-record/diagnosis/activate/'+inputid,
                            data: {is_activeinactive : inputVal == 0 ? 1 : 0},
                            success: function(active_status){
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Update Successfully.',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                $('.diag-delete-' + inputid).hide();
                                $('.diag-restore-' + inputid).hide();

                                if(active_status == 1){
                                    $('.diag-delete-' + inputid).show();
                                }else{
                                    $('.diag-restore-' + inputid).show();
                                }
                                hideLoader();
                                // $('.guardian-active-icon-' + inputid).removeClass('ti-trash, ti-reload');
                                // $('.guardian-status-' + inputid).removeClass('btn-success, btn-warning');
                                // console.log(active_status);
                                // $('.btn_cancel_healthcert').attr('data-value', active_status);

                                // if(active_status == 1){
                                //     $('.guardian-status-' + inputid).text('Active');
                                //     $('.guardian-status-' + inputid).addClass('btn-success');

                                //     $('.guardian-active-icon-' + inputid).addClass('ti-trash');
                                // }else{
                                //     $('.guardian-status-' + inputid).text('InActive');
                                //     $('.guardian-status-' + inputid).addClass('btn-warning');

                                //     $('.guardian-active-icon-' + inputid).addClass('ti-reload');
                                // }
                            }
                        }); 
                    }
                })
        } else {
            $(this).closest(".removehealthcertidata").remove();
        }
    });

    $('.remove-treatment').click(function (e) { 
        var inputVal = $(this).data('value');
        var inputid = $(this).data('id');
        let msg = "";
        if(inputVal == 1){
            msg = "Deleted";
        }else{
            msg = "Restored";
        }
        
        if (inputVal == 0 || inputVal == 1) {
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
                    showLoader();
                    $.ajax({
                            type: "GET",
                            url: DIR+'medical-record/treatment/activate/'+inputid,
                            data: {is_activeinactive : inputVal == 0 ? 1 : 0},
                            success: function(active_status){
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Update Successfully.',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                $('.treatment-delete-' + inputid).hide();
                                $('.treatment-restore-' + inputid).hide();

                                if(active_status == 1){
                                    $('.treatment-delete-' + inputid).show();
                                }else{
                                    $('.treatment-restore-' + inputid).show();
                                }
                                hideLoader();
                            }
                        }); 
                    }
                })
        } else {
            $(this).closest(".removehealthcertidata").remove();
        }
    });
});

function addDiagnosis() {
     var prevLength = $("#diagnosis-contain").find(".row").length;
     prevLength = prevLength+'new'
     html = '<div class="row new-row diagnosis mt-3">'+
                '<div class="col-md-10" id="contain_select-diagnosis-'+prevLength+'">'+
                    '<select class="form-control diagnosis-select" name="diagnosis['+prevLength+'][disease]" id="select-diagnosis-'+prevLength+'"></select><br>'+
                    '<input class="form-control diagnosis-specify" name="diagnosis['+prevLength+'][specify]" type="text" value="">'+
                '</div>'+
                '<div class="col-md-2">'+
                    '<button type="button" class="btn btn-sm btn-danger remove-row"><i class="ti-trash text-white"></i></button>'+
                '</div>'+
            '</div>';
    $('#diagnosis-contain').append(html);
    select3Ajax('select-diagnosis-'+prevLength,'contain_select-diagnosis-'+prevLength,'medical-record/selectDiagnosis')
    removeRow('');
    $('#contain_select-diagnosis-'+prevLength+' .diagnosis-specify').hide()
    showOthers()
}

function addTreatment() {
    var prevLength = $("#treatment-contain").find(".row").length;
    prevLength = prevLength+'new'
    html = '<div class="row new-row mt-3">'+
                '<div class="col-md-5">'+
                    '<input class="form-control" name="treatment['+prevLength+'][treat_medication]" type="text" value="">'+
                '</div>'+
                '<div class="col-md-5">'+
                    '<input class="form-control" name="treatment['+prevLength+'][treat_management]" type="text" value="">'+
                '</div>'+
                '<div class="col-md-2 text-center">'+
                    '<button type="button" class="btn btn-sm btn-danger remove-row"><i class="ti-trash text-white"></i></button>'+
                '</div>'+
            '</div>';
    $('#treatment-contain').append(html);
    removeRow('');
}


// $('#recordcard-form').on('click', '.btn_open_labreq_modal', function (e) {
//     e.preventDefault();
//     var url = $(this).data('url');
//     var title = $(this).data('title');
//     console.log(url);
//     if(url != '#'){
//         modal = $('#addLabRequestmodal');
//         modal.modal({backdrop: 'static', keyboard: false});
//         modal.modal('show');
//         showLoader();
//         $('.loadingGIF').show();
//         $.ajax({
//             type: "get",
//             url: url,
//             dataType: "html",
//             success: function(html){ 
//                 hideLoader();
//                 modal.find('.body').html(html);
//                 modal.find('.modal-title').html(title);
//             },
//             error: function(){
//             hideLoader();
//             }
//         });
//     }
// });

// $('#addLabRequestmodal').on('hidden.bs.modal', function () {
//     $('#addLabRequestmodal .body').empty();
// });

function  showOthers() {
	$('.diagnosis').on('change', '.diagnosis-select', function (e) {
        field = $(this).parent().find('.diagnosis-specify');
        selected = $(this).find('option:selected').text()
        if (selected == 'Others (Specify)') {
            field.show()
        }
    });
}