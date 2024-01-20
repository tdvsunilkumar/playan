$(document).ready(function () {

	datatablefunction();
	$("#btn_search").click(function(){
 		datatablefunction();
 	});
	
  $("#ecs_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ecs_idparrent")});
  $("#ec_id").select3({dropdownAutoWidth : false,dropdownParent: $("#ec_idparrent")});
  
  $('#ec_id').on('change', function() {
 		var id =$(this).val();
 		Getbrgyid(id);	
  })
  
   if($("#ec_id option:selected").val() > 0 ){
		var id = $("#ec_id option:selected").val();
		Getbrgyid(id);	
   }
   $("#btnofLotModal").click(function(){
	 var slotno =$("#ecl_slot").val();
	 var filtervars = {
		id:$("#id").val(),
		ifo:$("#ecl_lot_no_from").val(),
	    to:$("#ecl_lot_no_to").val(),
		"_token": $("#_csrf_token").val()
	  }; 
	  $.ajax({
		type: "POST",
		url: DIR+'cemeterylist-lotnolist',
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
	 var ecl_lot_no_from =$("#ecl_lot_no_from").val();
	 var ecl_lot_no_to =$("#ecl_lot_no_to").val();
	  var final = ecl_lot_no_to - ecl_lot_no_from + 1;
	  $("#ecl_slot").val('');
	  $("#ecl_slot").val(final);
	});
	
})


function Getbrgyid(id){
  var filtervars = {
    id:id,
    "_token": $("#_csrf_token").val()
  }; 
  $.ajax({
    type: "POST",
    url: DIR+'cemeterylist-getbrgyid',
    data: filtervars,
    dataType: "html",
    success: function(html){ 
      hideLoader();
      $("#brgy_id").val(html);
    },error:function(){
      hideLoader();
    }
  });
}

function SaveNumberofLot(){
	 var filtervars = {
		  //id:$("#id").val(),
          ecl_id:$("#id").val(),
		  ecl_block:$("#ecl_block").val(),
          ecl_lot:$("#ecl_lot").val(),
          "_token": $("#_csrf_token").val()
        }; 
        $.ajax({
          type: "post",
          url: DIR+'cemeterieslistdetails/savenumberoflot',
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
			url :DIR+'cemeterieslistdetails/getList', // json datasource
			type: "GET", 
			"data": {
				"ecl_id":$("#id").val(),
				"q":$("#q").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
			{ "data": "srno" },
			{ "data": "ecl_block" },
			{ "data": "ecl_lot" },
        	{ "data": "ecl_status" },
			{ "data": "status" },
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
				var ecl_status = $(this).attr('ecl_status');
				if(ecl_status == 0){
					ActiveInactiveUpdates(recordid,is_activeinactive,ecl_status);
				}else{
					var is_activeinactive = $(this).attr('value');
				 	ActiveInactiveUpdate(recordid,is_activeinactive,ecl_status);
				}
				
			});
	    }
	});  
}

function ActiveInactiveUpdates(id,is_activeinactive,ecl_status){
   var msg = ecl_status==0?'Sorry the Lot No. occupied it cannot be removed':'';
   const swalWithBootstrapButtons = Swal.mixin({
	   customClass: {
		   confirmButton: 'btn btn-success',
		   cancelButton: 'btn btn-danger'
	   },
	   buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
	   text: " "+msg,
	   icon: 'warning',
	   reverseButtons: true
   }).then((result) => {
	   	
   })
}


function ActiveInactiveUpdate(id,is_activeinactive,ecl_status){
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
			   url :DIR+'cemeterieslistdetails/ActiveInactives', // json datasource
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