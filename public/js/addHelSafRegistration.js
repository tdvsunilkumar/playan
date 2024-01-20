$(document).ready(function(){	
	datatablefunction();
	$(".refeshbuttonselect").click(function(){
 		 refreshCitizen();
 	});
});

function refreshCitizen(){
   $.ajax({
 
        url :DIR+'getRefreshHelSaf', // json datasource
        type: "POST", 
        data: {
          // "id": id, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          if(html !=''){
           $("#cit_id").html(html);
            //$("#bba_code").html('<option>Please Select</option>');
          }
        }
    })
}
