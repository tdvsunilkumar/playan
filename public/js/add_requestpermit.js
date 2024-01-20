$(document).ready(function(){
    FormAjax()
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    $("#btn_addmore_healthcert").click(function(){
         $('html, body').stop().animate({
             scrollTop: $("#btn_addmore_healthcert").offset().top
         }, 600);
         addmorehealthcert();
     });
	select3Ajax("payor_id","contain_payor_id","getCitizenAjax");
     $('#lab-fees').on('click', '.btn_cancel_healthcert', function (e) {
        if ($(this).hasClass('new-btn')) {
            var row = $(this).closest(".removehealthcertidata");
            row.remove();
            computeTotal()
         } else {
            var row = $(this).closest(".removenaturedata");
            var inputVal = row.find(".healthreqid").val();
            swalWithBootstrapButtons.fire({
                text: "Are you sure?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if(result.isConfirmed){
                    $.ajax({
                        type: "GET",
                        url: DIR+'OccupationalPermit/del-fees/'+inputVal,
                        dataType: "json",
                        success: function(html){ 
                            row.remove();
                            computeTotal()
                        }
                    }); 
                }
            })
         }
     });
 
     $('.row').on('change', '.select-service', function (e) {
         var select = $(this);
         var id = select.val();
         $.ajax({
             type: "POST",
             url: DIR+'OccupationalPermit/getService',
             data: {
                 'id':id
             },
             dataType: "json",
             success: function(response){ 
                 $.each(response, function(resID,resData){
                     select.closest('.row').find('.select_'+resID).val(resData);
                     if(resID === 'ho_service_amount'){
                         select.closest('.row').find('.select_ho_service_amount').data('value',resData);
                         if ($('input#free').is(':checked') || select.closest('.row').find('.fee[type="checkbox"]').is(':checked')) {
                             select.closest('.row').find('.select_'+resID).val('0.00');
                         } else {
                             computeTotal()
                         }
                     }
                 });	
             }
         }); 
     });
 
     $('body').on('keyup', '.fee', function (e) {
         computeTotal()
     });
         
     $('body').on('change', '#free', function (e) {
         if ($(this).is(':checked')){
             $('.fee').val('0.00')
             $('.fee[type="checkbox"]').prop("checked",true)
             $('.fee[type="checkbox"]').prop("disabled",true)
             $('.fee').prop('readOnly', true)
         }else{
             $('.fee').each(function(i) {
                 var val = $(this).data('value');
                $('.fee[type="checkbox"]').prop("checked",false)
                $('.fee[type="checkbox"]').prop("disabled",false)
                $(this).val(val);
             });
             $('.fee').not('#lab_req_amount').prop('readOnly', false)
             computeTotal()
         }
     });
     $('.row').on('change', '.fee[type="checkbox"]', function (e) {
        checked = $(this);
        fee_input = checked.closest('.row').find('.fee[type="text"]');
        var val = fee_input.data('value');
        if (checked.is(':checked')) {
            fee_input.val('0.00')
            fee_input.prop('readOnly', true)
        } else {
            fee_input.prop('readOnly', false)
            fee_input.val(val);
        }
        computeTotal()
    });
     // $("button").not('[type="submit"]').unbind("click");

     $('.removenaturedata').on('click', '.btn_open_labreqform_modal', function (e) {
		 
         var url = $(this).data('url');
         var title = $(this).data('title');
         if(url != '#'){
             $('#addLabRequestFormModal').modal({backdrop: 'static', keyboard: false});
             $('#addLabRequestFormModal').modal('show');
             showLoader();
             $('.loadingGIF').show();
             loadLabRequestModal(url,title);
         }
     });
    
    $('#addLabRequestFormModal').on('hidden.bs.modal', function () {
        $('#addLabRequestFormModal .body').empty();
    });

    //   confirm button
	$('body').on('click', '.btn-confirm', function (e) {
        button = $(this);
        var token = $("#_csrf_token").val();	
        href = button.data('href');
        Swal.fire({
        title: 'Are you sure?',
        text: "This record will be Approve",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    type: "GET",
                    url: href,
                    data: {
                        _token: token
                    },
                    success: function(response){
                        var data = JSON.parse(response); 
                        if (data.ESTATUS === 0) {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Approved Successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            
                            if (button.hasClass('labreq-confirm') ) {
                                reloadCommonModal()
                            } else {
                                $('#addLabRequestFormModal .body').empty();
                                loadLabRequestModal($('#LabRequest #labrequest-form-url').val());
                            }
                            datatablefunction();
                            button.prop('disabled', true);
                        }
                        
                    }
                });
            }
        })
    });
 
    
 });  
 
 
 
 function addmorehealthcert(){
     var prevLength = $("#Healthcerti").find(".removehealthcertidata").length;
	 var idser = $("#Healthcerti").find(".removehealthcertidata").length +1;
     prevLength = prevLength+'new'
     $('#Healthcerti').append('<div class="removehealthcertidata row pt10">'+
			'<div class="col-md-1">'+
			   '<div class="form-group">'+
				  '<div class="form-icon-user text-center">'+
				   '<span> ' + idser +' </span>'+
				  '</div>'+
			   '</div>'+
			'</div>'+
            '<div class="col-md-3">'+
                '<div class="form-group">'+
                    '<div class="form-icon-user" id="contain-select-service-'+prevLength+'">'+
                        '<select class="form-control select-service" id="select-service-'+prevLength+'" name="fees['+prevLength+'][service_id]"></select>'+
                        '<span class="validate-err" id="err_fees-'+prevLength+'-service_id"></span>'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<div class="col-md-3">'+
                '<div class="form-group">'+
                    '<div class="form-icon-user">'+
                        '<input class="form-control select_service_name " name="fees['+prevLength+'][service_name]" type="text" readonly>'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<div class="col-md-1">'+
                '<div class="form-group">'+
                    '<div class="form-icon-user">'+
                        '<input class="form-check-input code fee" name="fees['+prevLength+'][hlf_is_free]" type="checkbox" >'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<div class="col-md-2">'+
                '<div class="form-group">'+
                    '<div class="form-icon-user">'+
                        '<input class="form-control select_ho_service_amount fee" name="fees['+prevLength+'][fee]" data-value="" type="text" >'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<div class="col-sm-1">'+
                '<button class="btn btn-info btn_open_labreqform_modal" disabled style="padding: 0.4rem 1rem !important;" type="button">Result</button>'+                               
            '</div>'+
            '<div class="col-sm-1">'+
                '<button class="btn btn-danger btn_cancel_healthcert new-btn ti-trash text-white text-white" style="padding: 0.4rem 1rem !important;" type="button"></button>'+                               
            '</div>'+
        '</div>');
     select3Ajax('select-service-'+prevLength,'contain-select-service-'+prevLength,'OccupationalPermit/getServices');
     if ($('input#free').is(':checked')) {
        $('.fee').prop('readOnly', true)
        $('.fee[type="checkbox"]').prop("checked",true)
        $('.fee[type="checkbox"]').prop("disabled",true)
        $('.fee').val('0.00')
     }
 }
 
 function computeTotal() {
     var total = 0;
     $('.fee[type="text"]').not('#request_amount').each(function(i) {
         var val = $(this).val();
         if (val) {
            total += removeComma(val);
         }
     });
     $('#request_amount').val(numFormat(total));
     $('#request_amount').prop('readOnly', true);
 }
 function numFormat(num) {
     
     return Number(parseFloat(num).toFixed(2)).toLocaleString('en', {
         minimumFractionDigits: 2
     });
 }
 
 function removeComma(num) {
     return parseFloat(num.replaceAll(',', ''));
 }

function loadLabRequestModal(url,title = null) {
    $.ajax({
        type: "get",
        url: url,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#LabRequest .body').html(html);
            $('#LabRequest #labrequest-form-url').val(url);
            if (title) {
                $('#LabRequestTitle').html(title);
            }
            $('#LabRequest .select3').select3();
        },
        error: function(){
        hideLoader();
        }
    });
}

function reloadCommonModal() {
    var url = $('#commonModal #labrequest-url').val()
    $('#commonModal .body').empty();
    $.ajax({
        type: "get",
        url: url,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#commonModal .body').html(html);
            $('#commonModal .select3').select3();
        },
        error: function(){
        hideLoader();
        }
    });
}