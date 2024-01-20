$(document).ready(function(){	
	$("#year").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
	 $(".activeinactive").click(function(){
		datatablefunction();
	});	
	$('.numeric-only').on('keypress', function(event) {
		var charCode = (event.which) ? event.which : event.keyCode;
		
		if (String.fromCharCode(charCode).match(/[^0-9]/g)) {
			return false;             
		}
	});
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
		"columnDefs": [{ orderable: false, targets: [0,10] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'healthy-and-safety/health-certificate/getList', // json datasource
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
        	{ "data": "hahc_app_year" },
			{ "data": "citizen_name" },
			{ "data": "gend_age" },
        	{ "data": "hahc_issuance_date" },
        	{ "data": "hahc_expired_date" },
        	{ "data": "applied_date" },
        	{ "data": "busn_name" },
			{ "data": "hahc_registration_no" },
			{ "data": "is_active" },
            { "data": "action" }
        ],
        
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	        $("#common_pagesize").html(dropdown_html);
	         api.$('.print').click(function() {
	            var id = $(this).attr('id');
	            HealthcertificatePrint(id);
	        });
	        api.$('.restore').click(function() {
	            var id = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
	            ActiveInactiveUpdate(id,is_activeinactive);
	        });
			api.$('.remove').click(function() {
	            var id = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
	            ActiveInactiveUpdate(id,is_activeinactive);
	        });
	    }
	});  
}

function ActiveInactiveUpdate(id,is_activeinactive){
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
					url: DIR+'healthy-and-safety/health-certificate/ActiveInactive/'+id,
					type: 'GET',
				  
					success: function (data) {
					  datatablefunction();
					}
				  });
            }
        })
}
