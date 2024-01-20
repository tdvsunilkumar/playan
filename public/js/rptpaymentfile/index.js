$(document).ready(function(){	
	$('#rptPropertySearchByBarangy').select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
    $('#rptPropertySearchByKind').select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
    //$('#rptPropertySearchByTD').select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});

  $("#rptPropertySearchByTD").select3({
    placeholder: 'Tax Declaration',
    allowClear: true,
    ajax: {
        url: DIR+'rpt-payments-file/getalltds',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});



  $('#commonModal').off('click',"#uploadAttachmentbtn").on('click',"#uploadAttachmentbtn",function(){
        uploadAttachment('upload');
    });

  $('#commonModal').off('click',"#copyFile").on('click',"#copyFile",function(){
    if ($('#commonModal').find('#rp_code_from_copy').val() == null) {
            $('#commonModal').find("#err_copy").html("Please Select Tax Declaration to Copy");
            return false;
        }
    const swalWithBootstrapButtons = Swal.mixin({
           customClass: {
               confirmButton: 'btn btn-success',
               cancelButton: 'btn btn-danger'
           },
           buttonsStyling: false
       })
       swalWithBootstrapButtons.fire({
           title: 'Are you sure?',
           text: "This action can not be undone. Do you want to continue?",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonText: 'Yes',
           cancelButtonText: 'No',
           reverseButtons: true
       }).then((result) => {
            if(result.isConfirmed){

                uploadAttachment('copy');
           }
       })
        
    });

    $('#commonModal').off('click','#checkHistoryOfTd').on('click','#checkHistoryOfTd',function(){
        loadHistory();
    });

    $('#commonModal').off('click','.btn_delete_documents').on('click','.btn_delete_documents',function(){
        var id = $(this).data('id');
        deletePaymentFile(id);
    });

    $( "#commonModal" ).on('shown.bs.modal', function(){
    loadPaymentFiles();
    $('#commonModal').find("#rp_code_from_copy").select3({
    dropdownParent : $('#commonModal').find("#rp_code_from_copy").parent(),
    placeholder: 'Tax Declaration',
    allowClear: true,
    ajax: {
        url: DIR+'rpt-payments-file/maketdremoteselectlist',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                idnottodiplay:$('#commonModal').find("#rpCodeDocument").val(),
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
    });

    $("#search_year").change(function(){
 		datatablefunction();
 	});	
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
    $("#rptPropertySearchByTD").change(function(){
        datatablefunction();
    });
    $("#rptPropertySearchByBarangy").change(function(){
        datatablefunction();
    });
    $("#rptPropertySearchByKind").change(function(){
        datatablefunction();
    });
});

function datatablefunction()
{
	var dropdown_html=get_page_number('1'); 
	var table = $('#Jq_datatablelist').DataTable({ 
		"language": {
            "infoFiltered":"",
            "processing": "<img src='"+DIR+"public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
        },
        dom: "<'row'<'col-sm-12'f>>" +"<'row'<'col-sm-3'l><'col-sm-9'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-12'p>>",
            oLanguage: {
	         	sLengthMenu: dropdown_html
        },
		"bProcessing": true,
		"serverSide": true,
		"bDestroy": true,
		"searching": false,
		"order": [],
		"columnDefs": [{ orderable: false, targets: [0,11] }],
		"pageLength": 25,
		"ajax":{ 
			url :DIR+'rpt-payments-file/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				"td_no":$('#rptPropertySearchByTD').val(),
                "bgy_id":$('#rptPropertySearchByBarangy').val(),
                "kind":$('#rptPropertySearchByKind').val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
        "columns": [
        	{ "data": "srno"},
        	{ "data": "taxDeclarationNo" },
			{ "data": "ownar_name" },
            { "data": "email" },
			{ "data": "brgy_name" },
        	{ "data": "prop_type" },
        	{ "data": "area" },
        	{ "data": "assessedValue" },
        	{ "data": "last_or_no" },
        	{ "data": "last_or_amount" },
        	{ "data": "last_or_date" },
            { "data": "action" }
        ],
        
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	        $("#common_pagesize").html(dropdown_html);
	        api.$(".sendEmail").click(function(){
		        sendEmails($(this).attr('d_id'),$(this).attr('email'));
		    })
	    }
	});  
}
function isEmailValid(email) {
    var regex =/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function loadHistory() {
    var id = $('#commonModal').find('#checkHistoryOfTd').data('id');
    var url = $('#commonModal').find('#checkHistoryOfTd').data('url');
    showLoader();
    var filtervars = {
        id:id
    }; 
    $.ajax({
        type: "get",
        url: url,
        data: filtervars,
        dataType: "json",
        success: function(html){ 
            hideLoader();
            $('#commonModal').find('#loadHistoryViewHere').html(html.view);
            
        },
        error: function(err) {
            hideLoader();
        }
    });
}

function sendEmails(id,email){
     if(email==''){
        Swal.fire({
            title: "Oops...",
            html: "Email Id not found, Please add email id first.",
            icon: "warning",
            type: "warning",
            showCancelButton: false,
            closeOnConfirm: true,
            confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
        });
        return false;
    }else if (isEmailValid(email) == false) {
        Swal.fire({
            title: "Oops...",
            html: email+' This is invalid email address',
            icon: "warning",
            type: "warning",
            showCancelButton: false,
            closeOnConfirm: true,
            confirmButtonClass: "btn btn-warning btn-focus m-btn m-btn--pill m-btn--air m-btn--custom"
        });
        return false;
    }else{
        const swalWithBootstrapButtons = Swal.mixin({
           customClass: {
               confirmButton: 'btn btn-success',
               cancelButton: 'btn btn-danger'
           },
           buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
           title:"Are you sure?",
           text: 'Are you sure want to send email.',
           icon: 'warning',
           showCancelButton: true,
           confirmButtonText: 'Yes',
           cancelButtonText: 'No',
           reverseButtons: true
        }).then((result) => {
            if(result.isConfirmed){
                showLoader();
                $.ajax({
                    url :DIR+'rpt-deliquency/sendEmail', // json datasource
                    type: "POST", 
                    data: {
                      "id": id, 
                     "_token": $("#_csrf_token").val(),
                    },
                    success: function(html){
                    }
                })

                setTimeout(() => {
                    hideLoader();
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Email Send Successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }, 500);
            }
        });
    }
}

function loadPaymentFiles() {
    var id = $('#commonModal').find('#rpCodeDocument').val();
    showLoader();
    var filtervars = {
        id:id
    }; 
    $.ajax({
        type: "get",
        url: DIR+'rpt-payments-file/loadpaymentfiles',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            $('#commonModal').find('#loadPaymentFilesHere').html(html);
        },
        error: function(err) {
            hideLoader();
        }
    });
}

function deletePaymentFile(id) {
    const swalWithBootstrapButtons = Swal.mixin({
           customClass: {
               confirmButton: 'btn btn-success',
               cancelButton: 'btn btn-danger'
           },
           buttonsStyling: false
       })
       swalWithBootstrapButtons.fire({
           title: 'Are you sure?',
           text: "This action can not be undone. Do you want to continue?",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonText: 'Yes',
           cancelButtonText: 'No',
           reverseButtons: true
       }).then((result) => {
            if(result.isConfirmed){
                showLoader();
    var filtervars = {
        id:id
    }; 
    $.ajax({
        type: "get",
        url: DIR+'rpt-payments-file/deletepaymentfile',
        data: filtervars,
        dataType: "html",
        success: function(html){ 
            hideLoader();
            loadPaymentFiles();
        },
        error: function(err) {
            hideLoader();
        }
    });
           }
       })
    
}

function uploadAttachment(action){
        $(".validate-err").html("");
        var formData = new FormData();
        formData.append('rp_code', $('#commonModal').find('#rpCodeDocument').val());
        formData.append('action', action);
         if(action == 'upload'){
            formData.append('file', $('#commonModal').find('#documentname')[0].files[0]);
            if (typeof $('#commonModal').find('#documentname')[0].files[0]== "undefined") {
            $('#commonModal').find("#err_document").html("Please upload Document");
            return false;
        }
         }
         if(action == 'copy'){
            formData.append('id_copy_from', $('#commonModal').find('#rp_code_from_copy').val());
            if ($('#commonModal').find('#rp_code_from_copy').val() == null) {
            $('#commonModal').find("#err_copy").html("Please Select Tax Declaration to Copy");
            return false;
        }
         }
        showLoader();
        $.ajax({
           url : DIR+'rpt-payments-file/uploadDocument',
           type : 'POST',
           data : formData,
           processData: false,  // tell jQuery not to process the data
           contentType: false,  // tell jQuery not to set contentType
           success : function(data) {
                hideLoader();
                var data = JSON.parse(data);
                if(data.ESTATUS == 'error'){
                    Swal.fire({
                         position: 'center',
                         icon: 'error',
                         title: data.message,
                         showConfirmButton: false,
                         timer: 1500
                    });
                }if(data.ESTATUS == 'success'){
                    $('#commonModal').find('#documentname').val('');
                    $('#commonModal').find('#rp_code_from_copy').val(null).trigger('change');
                    Swal.fire({
                         position:'center',
                         icon: 'success',
                         title: data.message,
                         showConfirmButton: false,
                         timer: 1500
                    });
                    loadPaymentFiles();
                }
           },
           error:function() {
              hideLoader();
           }
        });
     }
