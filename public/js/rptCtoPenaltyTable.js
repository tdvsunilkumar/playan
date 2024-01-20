$(document).ready(function(){	
	$("#setFilterType").select3({dropdownAutoWidth : false,dropdownParent : $("#this_is_filter")});
	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});	
    $(document).on('change','#setFilterType',function(){
 		if($(this).val() == 0){
 			$('#rptPropertySearchByEffectiveYearLabel').html('Year Encoded');
 			$('#rptPropertySearchByEffectiveYear').val('');
 			$('#rptPropertySearchByEffectiveYear').attr('name','year_encoded');
 			$('#rptPropertySearchByEffectiveYear').attr('placeholder','Year Encoded');
 		}else{
            $('#rptPropertySearchByEffectiveYearLabel').html('Effectivity');
            $('#rptPropertySearchByEffectiveYear').val('');
            $('#rptPropertySearchByEffectiveYear').attr('name','year_effective');
            $('#rptPropertySearchByEffectiveYear').attr('placeholder','Effectivity');
 		}
 		
 	});

 	$(document).on('change','#rptPropertySearchByEffectiveYear',function(){
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
		"columnDefs": [{ orderable: false, targets: [0,16] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'rptctopenaltytable/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				"current_year":$("input[name=year_encoded]").val(),
				"effect_year":$("input[name=year_effective]").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
       	    { "data": "sr_no" },
       	    { "data": "cpt_current_year" },
        	{ "data": "cpt_effective_year" },
        	{ "data": "cpt_month_1" },
        	{ "data": "cpt_month_2" },
        	{ "data": "cpt_month_3" },
        	{ "data": "cpt_month_4" },
        	{ "data": "cpt_month_5" },
        	{ "data": "cpt_month_6" },
        	{ "data": "cpt_month_7" },
        	{ "data": "cpt_month_8" },
        	{ "data": "cpt_month_9" },
        	{ "data": "cpt_month_10" },
        	{ "data": "cpt_month_11" },
        	{ "data": "cpt_month_12" },
        	{ "data": "is_active" },
            { "data": "action" }
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
	       $("#common_pagesize").html(dropdown_html);
	        api.$('.activeinactive').click(function() {
				var recordid = $(this).attr('id');
				var is_activeinactive = $(this).attr('value');
				 ActiveInactiveUpdate(recordid,is_activeinactive);
	
			});
	    }
	});  
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
			   url :DIR+'rptctopenaltytable/activeinactive', // json datasource
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
			        url :DIR+'rptctopenaltytable/delete', // json datasource
			        type: "POST", 
			        data: {
			          "id": id, 
			          "_token": $("#_csrf_token").val(),
			        },
			        success: function(html){
			        	Swal.fire({
	    				  position: 'center',
	    				  icon: 'success',
	    				  title: 'collector Deleted Successfully.',
	    				  showConfirmButton: false,
	    				  timer: 1500
	    				})
			           location.reload();
			        }
			    })
            }
        })
}
