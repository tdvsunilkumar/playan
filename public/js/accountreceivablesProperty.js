$(document).ready(function(){	
	 var yearpickerInput = $('input[name="year"]').val();
    $('.yearpicker').yearpicker();
    $('#commonModal').modal({backdrop: 'static', keyboard: false});
    datatablefunction();
    //$("#barangay").select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
    $("#barangay").select3({
    placeholder: 'Select Barangay',
    allowClear: true,
    dropdownParent: $("#this_is_filter").parent(),
    ajax: {
        url: DIR+'rptproperty/gebrgyforajaxselectlist',
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
	//$("#tax").select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
	$("#tax").select3({
    placeholder: 'Select Tax Declaration No.',
    allowClear: true,
    dropdownParent: $("#this_is_filter").parent(),
    ajax: {
        url: DIR+'rptproperty/gettdsforajaxselectlist',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
            	column:'tdwithcustomer',
                pk_id:0,
                rpo_code:0,
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
	//$("#taxpayer").select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
	$("#taxpayer").select3({
    placeholder: 'Select Taxpayer',
    allowClear: true,
    dropdownParent: $("#this_is_filter").parent(),
    ajax: {
        url: DIR+'rptpropertyowner/getallclients',
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
	$("#rptPropertySearchByKind").select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	

 	$('#barangay').change(function(){
 		datatablefunction();
 	});

 	$('#taxpayer').change(function(){
 		datatablefunction();
 	});

 	$('#tax').change(function(){
 		datatablefunction();
 	});
 	$('#rptPropertySearchByKind').change(function(){
 		datatablefunction();
 	});

 	$(document).on('change','#year',function(){
 		datatablefunction();
 	});
    $(document).on('click','.sendEmailDtlsIndex',function(){
    	var id = this.getAttribute("data-rp_code");
	    var email = this.getAttribute("data-user_email");
	    var receivId = this.getAttribute("data-receiableId");
         sendEmailDetails(id,email,receivId);
    });
 	$(document).on('click','.sendEmailDtls',function(){
         sendEmailDetails($('#commonModal').find("input[name=rp_code]").val(),$('#commonModal').find("input[name=user_email]").val(),$('#commonModal').find("input[name=receiableId]").val());
    });
    $('#commonModal').on('change','#cbd_is_paid_status',function(){
         loadReceiableDetailsTable();
    });

 	$(document).on('click','.showDetails',function() {
 		showLoader();
 		var url = $(this).data('url');
        var title1 = 'Manage Property Accounts Receivables';
        var title2 = 'Manage Property Accounts Receivables';
        var title = (title1 != undefined) ? title1 : title2;
        var size = 'xll';
		$("#commonModal").unbind("click");
		$("#commonModal .modal-title").html(title);
		$("#commonModal .modal-dialog").addClass('modal-' + size);

		$.ajax({
		    url: url,
		    success: function (data) {
		        hideLoader();
		        if(typeof data.status !== 'undefined' && data.status == 'error'){
		            Swal.fire({
		                  position: 'center',
		                  icon: 'error',
		                  title: data.msg,
		                  showConfirmButton: true,
		                  timer: 3000
		                })

		        }else{
		            $('#commonModal .body').html('');
		            $('#commonModal .body').html(data);
		            $("#commonModal").modal('show');
		            taskCheckbox();
		            loadReceiableDetailsTable();
		            commonLoader();
		        }
		        
		    },
		    error: function (data) {
		        hideLoader();
		        $('#commonModal').modal('hide');
		        data = data.responseJSON;
		        show_toastr('Error', data.error, 'error')
		    }
		});
 	})
});

function sendEmailDetails(id,email,receivId){
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
    }else if (isEmail(email) == false) {
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
                url :DIR+'account-receivables-property/sendEmail', // json datasource
                type: "POST", 
                data: {
                  "prop_id": id, 
                  "type": 'deliqnuencyOutstanding',
                  "id" : receivId,
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

function isEmail(email) {
    var regex =/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function loadReceiableDetailsTable() {
	    showLoader();
	    var propertyCode = $('#commonModal').find('input[name=rp_property_code]').val();
	    var paidstatus   = $('#commonModal').find('select[name=cbd_is_paid_status]').val();
	    var rpCOde       = $('#commonModal').find('input[name=rp_code]').val();
	    var arID         = $('#commonModal').find('input[name=receiableId]').val();
        var id = $('#commonModal').find('input[name=receiableId]').val();
        var url =  DIR+'account-receivables-property/getdetailslist';
        var data   = {
            id:propertyCode,
            cbd_is_paid:paidstatus,
            rp_code:rpCOde,
            ar_id:arID
        };
        $.ajax({
        type: "get",
        url: url,
        data: data,
        dataType: "html",
        success: function(html){ 
        	$('#commonModal').find('#accountReceiableDetails').html(html);
        	var totalAmount = 0;
        	$('#commonModal').find('#accountReceiableDetails').find('table tbody tr .alltotal').each(function(index,ele){
        		var amount = $(this).text().replace(/\,/g,'').replace('₱','');
        		//console.log(amount);
               totalAmount += parseFloat(amount);
        	});
        	
        	$('#commonModal').find('#landAppraisalTotalValueToDisplay').val(parseFloat(totalAmount).toFixed(2));
        	$('#commonModal').find('#cbd_is_paid_status').select3({dropdownParent:$('#commonModal').find('#cbd_is_paid_status').parent()});
            hideLoader();
            
        },error:function(){
            hideLoader();
        }
    });
}


function datatablefunction()
{
	var dropdown_html=get_page_number('1'); 
	var table;

    function updateTableColumns() {
        table.columns().visible(false);

        // Loop through the checkboxes and show/hide columns based on their state
        $('.toggle-column').each(function () {
            var column = table.column($(this).data('column'));
            if ($(this).prop('checked')) {
                column.visible(true);
            }
        });
    }
	table = $('#Jq_datatablelist').DataTable({ 
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
		"columnDefs": [{ orderable: false, targets: [0] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'account-receivables-property/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#rptPropertySearchByText").val(),
				"barangay" : $('#barangay').val(),
				"taxpayer" : $('#taxpayer').val(),
				"tax"      : $('#tax').val(),
				"year"     : $('#year').val(),
				"kind"     : $('#rptPropertySearchByKind').val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
			{ "data": "srno" },
        	{ "data": "arpno" },
           	{ "data": "taxpayer" },
           	{ "data": "Address" },
           	{ "data": "location" },
           	{ "data": "pin" },
           	{ "data": "lot" },
           	{ "data": "class" },
           	{ "data": "updatecode" },
           	{ "data": "effectivity" },
           	{ "data": "assessedvalue" },
           	{ "data": "orno" },
           	{ "data": "ordate" },
           	{ "data": "oramount" },
           	
           	{ "data": "out_basictax" },
           	{ "data": "out_sef" },
           	{ "data": "out_sht" },
           	{ "data": "out_total" },
           	{ "data": "del_basictax" },
           	{ "data": "del_sef" },
           	{ "data": "del_sht" },
           	{ "data": "del_total" },
           	{ "data": "total" },
            { "data": "action" }
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	       $("#common_pagesize").html(dropdown_html);
	        api.$('.deleterow').click(function() {
	            var recordid = $(this).attr('id');
	            DeleteRecord(recordid);
	        });
			api.$('.activeinactive').click(function() {
				var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
				 ActiveInactiveUpdate(recordid,is_activeinactive);
	
			});
			api.$(".showLess2").shorten({
                "showChars" : 30,
                "moreText"    : "More",
                "lessText"    : "Less",
            });
            api.$(".showLess3").shorten({
                "showChars" : 2,
                "moreText"    : "More",
                "lessText"    : "Less",
            });
	    }
	}); 
	 updateTableColumns();

    // Update table columns when the checkbox state changes
    $('.toggle-column').on('change', function () {
        updateTableColumns();
    }); 
}
function DeleteRecord(id){
	// alert(id);
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
            if(result.isConfirmed
            )
            {
               $.ajax({
			        url :DIR+'bfpoccupancytype/delete', // json datasource
			        type: "POST", 
			        data: {
			          "id": id, 
			          "_token": $("#_csrf_token").val(),
			        },
			        success: function(html){
			        	Swal.fire({
	    				  position: 'center',
	    				  icon: 'success',
	    				  title: 'BFP Occupancy Type Deleted Successfully.',
	    				  showConfirmButton: false,
	    				  timer: 1500
	    				})
			           location.reload();
			        }
			    })
            }
        })
}

function ActiveInactiveUpdate(id,is_activeinactive){
	// alert(id);
	// alert(is_activeinactive);
   const swalWithBootstrapButtons = Swal.mixin({
	   customClass: {
		   confirmButton: 'btn btn-success',
		   cancelButton: 'btn btn-danger'
	   },
	   buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
	   title: 'Are you sure?',
	   text: "You want to Active/InActive?",
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   if(result.isConfirmed)
	   {
		  $.ajax({
			   url :DIR+'administrative/fire-protection/occupancy-type/ActiveInactive', // json datasource
			   type: "POST", 
			   data: {
				 "id": id,
				 "is_activeinactive": is_activeinactive,  
				 "_token": $("#_csrf_token").val(),
			   },
			   success: function(html){
				   Swal.fire({
					 position: 'center',
					 icon: 'success',
					 title: 'Update Successfully.',
					 showConfirmButton: false,
					 timer: 1500
				   })
				   datatablefunction();
				   setInterval(function(){
				  
					  });
			   }
		   })
	   }
   })
}
