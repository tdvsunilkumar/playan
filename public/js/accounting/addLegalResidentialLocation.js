$(document).ready(function () {

	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});
	
  $("#residential_id").select3({dropdownAutoWidth : false,dropdownParent: $("#residential_id_parrent")});
  
   $("#btnofLotModal").click(function(){
	 var slotno =$("#lot_slot").val();
	 var filtervars = {
		id:$("#id").val(),
		ifo:$("#lot_from").val(),
	    to:$("#lot_to").val(),
		"_token": $("#_csrf_token").val()
	  }; 
	  $.ajax({
		type: "POST",
		url: DIR+'residential-housing-list/lotnolist',
		data: filtervars,
		dataType: "json",
		success: function(data){
		console.log(data);
		     hideLoader();
			 $('#ecl_lot').empty('');
		      for (i=0; i <= slotno; i++){
				  if(data[i] != undefined){
					var lotlistrarr = '<option value="'+ data[i] +'">'+ data[i] +'</option>'
					$('#ecl_lot').append(lotlistrarr);
				  }
			 }
			 $("#NumberofLotModal").modal('show');
		},error:function(){
		  hideLoader();
		}
	  });  
	   
	 
   });
   
    $(".closeOrderModal").click(function(){
		 $("#NumberofLotModal").modal('hide');
 	});
	$("#saveofLot").click(function(e){
 		e.preventDefault();
 		SaveNumberofLot();
 	})
	
	$(".lotnumbersub").on("keyup", function() {
		console.log("hdchfdsh");
	 var ecl_lot_no_from =$("#lot_from").val();
	 var ecl_lot_no_to =$("#lot_to").val();
	  var final = ecl_lot_no_to - ecl_lot_no_from + 1;
	  $("#lot_to").attr('min', ecl_lot_no_from);
	  $("#lot_slot").val('');
	  $("#lot_slot").val(final);
	});
	
})


function SaveNumberofLot(){
	 var filtervars = {
		  //id:$("#id").val(),
          residential_location_id:$("#id").val(),
		  lot_number:$("#ecl_lot").val(),
          "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
          type: "post",
          url: DIR+'residential-housing-list/savenumberoflot',
          data: filtervars,
          dataType: "json",
          success: function(html){ 
             hideLoader();
                   if(html.status="success"){
                        Swal.fire({
                          position: 'center',
                          icon: 'success',
                          title: 'Number of Lot Saved Successfully.',
                          showConfirmButton: false,
                          timer: 1500
                        })
                        $("#NumberofLotModal").modal('hide'); 
                        //location.reload();
						datatablefunction();
                   }
                
      },error:function(){
        hideLoader();
      }
      });
}

function datatablefunction()
{
	var dropdown_html=get_page_number('1'); 
	var table = $('#Jq_datatablelistpnmodle').DataTable({ 
		"language": {
            "infoFiltered":"",
            "processing": "<img src='"+DIR+"public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
        },
        // dom: "<'row'<'col-sm-12'f>>" +"<'row'<'col-sm-3'l><'col-sm-9'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-12'p>>",
        //     oLanguage: {
	    //      	sLengthMenu: dropdown_html
        // },
		dom: "rtip",
		"bProcessing": true,
		"serverSide": true,
		"bDestroy": true,
		"searching": false,
		"order": [],
		"columnDefs": [{ orderable: false, targets: [0,5] }],
		"pageLength": 5,
		"ajax":{ 
			url :DIR+'LegalResidentialLocDetails/getList', // json datasource
			type: "GET", 
			"data": {
				"residential_location_id":$("#id").val(),
				"q":$("#q").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
			{ "data": "srno" },
			{ "data": "block" },
			{ "data": "lot_number" },
        	{ "data": "lot_status" },
			{ "data": "is_active" },
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
	   	if(result.isConfirmed){
		  	$.ajax({
			   url :DIR+'LegalResidentialLocDetails/ActiveInactive', // json datasource
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
				   //location.reload();
			   }
		   })
	   }
   })
}