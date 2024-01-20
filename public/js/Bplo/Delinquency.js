$(document).ready(function(){	
	$('#search_year').select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
	$("#search_year").change(function(){
 		datatablefunction();
 	});	
	datatablefunction();
	$("#btn_search").click(function(){
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
			url :DIR+'treasury-business-delinquent/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				"year":$('#search_year').val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
        "columns": [
        	{ "data": "srno"},
        	{ "data": "busns_id_no" },
			{ "data": "ownar_name" },
			{ "data": "email" },
        	{ "data": "busn_name" },
        	{ "data": "app_code" },
        	/*{ "data": "last_paid_date" },*/
        	{ "data": "tax_due" },
        	{ "data": "penalty" },
        	{ "data": "interest" },
        	{ "data": "total_amount" },
            { "data": "is_approved" },
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
                    url :DIR+'treasury-business-delinquent/sendEmail', // json datasource
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
