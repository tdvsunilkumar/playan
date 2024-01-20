$(document).ready(function(){	
	$("#ora_to").change(function(){
		var count = 0;
        var from = +$("#ora_from").val();
        var to = +$("#ora_to").val();
        totalcount = parseFloat(to)- parseFloat(from);
        totalcount = totalcount.toFixed(2);
        $("#or_count").val(totalcount); 
	})
	$("#coa_no").change(function(){
        var shortname = $("#shortname").val();
        var coa_no = $("#coa_no").val();
        var cpor_series = shortname+',#'+coa_no;
        $("#cpor_series").val(cpor_series); 
	})

	$('#ortype_id').on('change', function() {
     var id = $(this).val();
     getShortname(id);
     getOptionsOrDesc(id);
    });

    if($("#id").val() > 0 ){
    	var id = $("#ortype_id option:selected").val();;
       getShortname(id);
       var corid = $("#cpor_id option:selected").val();;
       getOrDetails(corid);
    }
    $('#cpor_id').on('change', function(){
    	var id = $(this).val();
    	getOrDetails(id);
    })
   
});

function getShortname(id){
	 var id =id;
      $.ajax({
        url :DIR+'ctoorregister/getShortname', // json datasource
        type: "POST", 
        data: {
          "id": id, "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          $("#shortname").val(html);
        }
       })
}

function getOptionsOrDesc(id){
	var id =id;
  showLoader();
      $.ajax({
        url :DIR+'CtoPaymentOrAssignment/getOrDescoption', // json datasource
        type: "POST", 
        data: {
          "id": id, "_token": $("#_csrf_token").val(),
        },
        success: function(html){
          $("#cpor_id").html(html);
          hideLoader();
         }
       })
}

function getOrDetails(id){
	var id =id;
      $.ajax({
        url :DIR+'CtoPaymentOrAssignment/getOrDetails', // json datasource
        type: "POST", 
        data: {
          "id": id, "_token": $("#_csrf_token").val(),
        },datatype:"json",
        success: function(html){
        	 arr = $.parseJSON(html);
           $("#ora_from").val(arr.ora_from);
           $("#ora_to").val(arr.ora_to);
           $("#or_count").val(arr.or_count);
           $("#tagno").val(arr.coa_no);
          }
     })
}












