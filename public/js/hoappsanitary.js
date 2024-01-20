$(document).ready(function(){	
	$("#year").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
});

function HealthsanitaryPermitPrint(id){
	var id = id;
	$.ajax({
	  url: DIR+'healthy-and-safety/app-sanitary/hoapphealthsanitaryprint/'+id,
	  type: 'GET',
	
	  success: function (data) {
		 var url = data;
		 console.log(url);
		  window.open(url, '_blank');
	  }
	});
}

function HealthsanitaryRemvRest(id,is_activeinactive){
	var msg = is_activeinactive==1?'restored':'removed';
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "This record will be "+msg,
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
					url: DIR+'healthy-and-safety/app-sanitary/removeSanitary/'+id,
					type: 'GET',
				  
					success: function (data) {
					  datatablefunction();
					}
				  });
            }
        })
}




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
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'healthy-and-safety/app-sanitary/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				'year':$('#year').val(),
				'approve':$('#aproved').val(),
				'crdate':$('#datecreated1').val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
        "columns": [
        	{ "data": "srno"},
        	{ "data": "has_app_year" },
			{ "data": "busn_name" },
			{ "data": "busn_address" },
			{ "data": "tax_payer_name" },
			{ "data": "has_type_of_establishment" },
			{ "data": "has_issuance_date" },
        	{ "data": "has_expired_date" },
        	{ "data": "has_app_no" },
        	{ "data": "has_permit_no" },
        	{ "data": "has_status" },
            { "data": "action" }
        ],
        
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	        $("#common_pagesize").html(dropdown_html);
	         api.$('.print').click(function() {
	            var id = $(this).attr('id');
				
	            HealthsanitaryPermitPrint(id);
	        });
			api.$('.restore').click(function() {
	            var id = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
	            HealthsanitaryRemvRest(id,is_activeinactive);
	        });
			api.$('.remove').click(function() {
	            var id = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
	            HealthsanitaryRemvRest(id,is_activeinactive);
	        });
	    }
	});  
}
