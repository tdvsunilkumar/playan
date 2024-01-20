$(document).ready(function(){	
	$('#healthCert').select3({dropdownAutoWidth : false,dropdownParent: $("#healthcert_show")});
	datatablefunction(1);
	getSelectHealthCert();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
     $("#btn_add").click(function(){
        storeHealthCert();
        datatablefunction();
		getSelectHealthCert();
    });	
    $('#healthcert_add').click(function() {
        var div = document.getElementById("healthcert_show");
        if (div.style.display === "none") {
            div.style.display = "block";
        } else {
            div.style.display = "none";
        }
	});
	$('#healthcert_add').on('click', function() {
		getSelectHealthCert();
   }); 
    
	 $('.numeric-only').on('keypress', function(event) {
		var charCode = (event.which) ? event.which : event.keyCode;
		
		if (String.fromCharCode(charCode).match(/[^0-9]/g)) {
			return false;             
		}
	});
});
function getSelectHealthCert()
{
	$.ajax({
		url :DIR+'Endrosement/getSelectHealthCert', // json datasource
		type: "POST", 
		data: {
				'busn_id':$('#busn_id').val(),
				'bbendo_id':$('#bbendo_id').val(), 
				"_token": $("#_csrf_token").val(),
			},
		success: function(html){
		  $("#healthCert").html(html);
		}
	})
}
function storeHealthCert()
{
        var inputVal = $("#healthCert").val();
		$.ajax({
			type: "GET",
			url: DIR+'Endrosement/storeHealthCert/'+inputVal,
			data: {
				"healthCert_id":$("#healthCert").val(),
				'busn_id':$('#busn_id').val(),
				'bbendo_id':$('#bbendo_id').val(),
				'end_id':$('#end_id').val(),
		    },
			dataType: "json",
			success: function(html){ 
				console.log(inputVal);
			}
		}); 
		console.log(inputVal);
		getSelectHealthCert()
}
function removeHealthCert(id){
	var id = id;
	$.ajax({
	  url: DIR+'Endrosement/removeHealthCert/'+id,
	  type: 'GET',
	  success: function (data) {
		 console.log(data);
	  }
	});
	getSelectHealthCert()
}

function approveHealthCert(id){
	var id = id;
	const swalWithBootstrapButtons = Swal.mixin({
		customClass: {
			confirmButton: 'btn btn-success',
			cancelButton: 'btn btn-danger'
		},
		buttonsStyling: false
	})

	swalWithBootstrapButtons.fire({
		title: 'Are you sure?',
		text: "Are you sure want Verify Applications?",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		reverseButtons: true
	}).then((result) => {
		if(result.isConfirmed)
		{
			$.ajax({
			url: DIR+'Endrosement/approveHealthCert/'+id,
			type: 'GET',
			success: function (data) {
				getSelectHealthCert();
				console.log(data);
				datatablefunction();
			}
			});
			
		}
	})

	
}
function apvRcmHealthCert(id){
	var id = id;
	const swalWithBootstrapButtons = Swal.mixin({
		customClass: {
			confirmButton: 'btn btn-success',
			cancelButton: 'btn btn-danger'
		},
		buttonsStyling: false
	})

	swalWithBootstrapButtons.fire({
		title: 'Are you sure?',
		text: "Are you sure want Verify Applications?",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		reverseButtons: true
	}).then((result) => {
		if(result.isConfirmed)
		{
			$.ajax({
			url: DIR+'Endrosement/apvRcmHealthCert/'+id,
			type: 'GET',
			success: function (data) {
				getSelectHealthCert();
				datatablefunction();
				console.log(data);
			}
			});
			
		}
	})
}


 // when you click to  VerifyApplications
 this.$body.on('click', '#VerifyApplications', function (e) {
	

	
	
});


function HealthcertificatePrint(id){
              var id = id;
              $.ajax({
                url: DIR+'healthy-and-safety/health-certificate/hoapphealthcertPrint/'+id,
                type: 'GET',
              
                success: function (data) {
                   var url = data;
                   console.log(url);
                    window.open(url, '_blank');
                }
              });
}

 /*
        | ---------------------------------
        | # keypress numeric only
        | ---------------------------------
        */
        this.$body.on('keypress', '.numeric-only', function (event) {
            var charCode = (event.which) ? event.which : event.keyCode    
    
            if (String.fromCharCode(charCode).match(/[^0-9]/g)) {
                return false;             
            }
        });


function datatablefunction(remv = 0)
{
	var remv = remv;
	var dropdown_html=get_page_number('NULL'); 
	var table = $('#Jq_datatablelist1').DataTable({ 
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
		"columnDefs": [{ orderable: false, targets: [0,1,2,3,4,5] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'Endrosement/getHealthCertificateList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				'year':$('#year').val(),
				'approve':$('#aproved').val(),
				'crdate':$('#datecreated1').val(),
                "_token":$("#_csrf_token").val(),
                'busn_id':$('#busn_id').val(),
				'bbendo_id':$('#bbendo_id').val(),
				'end_id':$('#end_id').val(),
		    }, 
			error: function(html){
			}
		},
        "columns": [
        	{ "data": "srno"},
			{ "data": "citizen_name" },
            { "data": "hahc_registration_no" },
        	{ "data": "hahc_issuance_date" },
        	{ "data": "hahc_approver_status" },
            { "data": "action" }
        ],
        
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
			$('#t_emp').val(info.recordsTotal);
	         api.$('.print').click(function() {
	            var id = $(this).attr('id');
	            HealthcertificatePrint(id);
	        });
			api.$('.remove').click(function() {
	            var id = $(this).attr('id');
	            removeHealthCert(id);
				datatablefunction();
				getSelectHealthCert();
	        });
			api.$('.approve').click(function() {
	            var id = $(this).attr('id');
	            approveHealthCert(id);
				datatablefunction();
				getSelectHealthCert();
	        });
			api.$('.rcm_approve').click(function() {
	            var id = $(this).attr('id');
	            apvRcmHealthCert(id);
				datatablefunction();
				getSelectHealthCert();
	        });
	    }
	});  
}
