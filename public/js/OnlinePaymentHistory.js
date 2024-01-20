$(document).ready(function(){	
	datatablefunction();
	 $("#btn_search").click(function(){
 		 datatablefunction();
 	  });	
    if($("#remote_cashier_id").val()>0){
        updateCashierBillHistoryTaxpayers();
    }
    if($("#remote_cashier_id_for_rpt").val()>0){
        updateRPTCashierBillHistoryTaxpayers();
    }
});

function updateCashierBillHistoryTaxpayers(){
    $.ajax({
      url: DIR+'cashier/cashier-business-permit/updateCashierBillHistoryTaxpayers',
      type: 'POST',
      data: {
          "remote_cashier_id": $("#remote_cashier_id").val(), 
          "_token": $("#_csrf_token").val(),
      },
      success: function (data) {
         console.log(data);
      }
    });
  }
  function updateRPTCashierBillHistoryTaxpayers(){
    $.ajax({
      url: DIR+'cashier-real-property/updateCashierBillHistoryTaxpayers',
      type: 'POST',
      data: {
          "remote_cashier_id": $("#remote_cashier_id_for_rpt").val(), 
          "_token": $("#_csrf_token").val(),
      },
      success: function (data) {
         console.log(data);
      }
    });
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
		"columnDefs": [{ orderable: false, targets: [0,10] }],
		"pageLength": 10,
		"ajax":{ 
			url :DIR+'online-payment-history/getList', // json datasource
			type: "GET", 
			"data": {
				"q":$("#q").val(),
				"flt_Status":$("#flt_Status").val(),
                "department_flt":$("#department_flt").val(),
                "_token":$("#_csrf_token").val()
		    }, 
			error: function(html){
			}
		},
       "columns": [
       	    { "data": "srno" },
        	{ "data": "department_id" },
        	{ "data": "full_name" },
			{ "data": "bill_year" },
			{ "data": "bill_month" },
        	{ "data": "total_amount" },
			{ "data": "total_paid_amount" },
            { "data": "transaction_no" },
            { "data": "payment_date" },
        	{ "data": "payment_status" },
        	{ "data": "action" }
        ],
        drawCallback: function(s){ 
	        var api = this.api();
	        var info=table.page.info();
	        var dropdown_html=get_page_number(info.recordsTotal,info.length);
          $("#common_pagesize").html(dropdown_html);
            api.$('.declined').click(function() {
				var recordid = $(this).attr('id');
				 Declined(recordid);
	
			});
	        api.$('.approved').click(function() {
				var recordid = $(this).attr('id');
				 Approved(recordid);
			});
      api.$('.viewpayment').click(function() {
        var recordid = $(this).attr('id');
        var deptid = $(this).attr('deptid');
        $("#department").val(deptid);
        $("#approve").val(recordid);
         viewpaymentfunc(recordid);
         Getornumebr(0);
      });    
	    }
	});  
}

 $('#isuserrange').click(function() {
        if ($(this).is(':checked')) {
          var checked = '1';   $("#or_no").removeClass("disabled-field");
        }else{
             var checked = '0';    $("#or_no").addClass("disabled-field");
        }
       Getornumebr(checked)
  });

$('.approved').click(function() {
 var recordid = $(this).val();
 //alert(recordid);
  $('#showrequiremets').modal('hide');
         Approved(recordid);
  });


  function Getornumebr(checked){
         var department = $("#department").val();
         var dynadept = "";
         if(department == 5){
            dynadept = 'cpdocashering'
         }
         if(department == 3){
            dynadept = 'engcashering'
         }
         if(department == 4){
            dynadept = 'occupancycashering'
         }
         if(department == 1){
            dynadept = 'cashier/cashier-business-permit'
         }
         $.ajax({
                url :DIR+dynadept+'/getOrnumber', // json datasource
                type: "POST", 
                data: {
                        "orflag": checked, "_token": $("#_csrf_token").val(),
                    },
                    success: function(html){
                    $('#or_no').val(html)
                    $("#or_number").html(html)
                }
          })
  }

 function viewpaymentfunc(id){
   $('#showrequiremets').modal('show');
         // $.ajax({
         //      url :DIR+'cpdoservice/viewrequiremets', // json datasource
         //      type: "POST", 
         //      data: {
         //        "id": id, 
         //        "_token": $("#_csrf_token").val(),
         //      },
         //      success: function(html){
         //        $('#showrequiremets').modal('show');
         //        $("#dynamicreq").html(html);
         //      }
         //  })
}

function DeleteRecord(id){
	//alert(id);
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
			        url :DIR+'online-payment-history/delete', // json datasource
			        type: "POST", 
			        data: {
			          "id": id, 
			          "_token": $("#_csrf_token").val(),
			        },
			        success: function(html){
			        	Swal.fire({
	    				  position: 'center',
	    				  icon: 'success',
	    				  title: 'Business Tax Deleted Successfully.',
	    				  showConfirmButton: false,
	    				  timer: 1500
	    				})
			           location.reload();
			        }
			    })
            }
        })
}
function Approved(id){
   const swalWithBootstrapButtons = Swal.mixin({
	   customClass: {
		   confirmButton: 'btn btn-success',
		   cancelButton: 'btn btn-danger'
	   },
	   buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
	   title: 'Are you sure?',
	   text: "You want to Approve?",
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   if(result.isConfirmed)
	   {
		  $.ajax({
			   url :DIR+'online-payment-history/approve', // json datasource
			   type: "POST", 
			   data: {
				 "id": id,
				 "_token": $("#_csrf_token").val(),
			   },
			   success: function(html){
				   Swal.fire({
					 position: 'center',
					 icon: 'success',
					 title: 'Approved Successfully.',
					 showConfirmButton: false,
					 timer: 1500
				   })
				   //datatablefunction();
				   location.reload();
			   }
		   })
	   }
   })
}
function Declined(id){
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You want to Decline?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if(result.isConfirmed)
        {
           $.ajax({
                url :DIR+'online-payment-history/decline', // json datasource
                type: "POST", 
                data: {
                  "id": id,
                  "_token": $("#_csrf_token").val(),
                },
                success: function(html){
                    Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: 'Declined Successfully.',
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
 

