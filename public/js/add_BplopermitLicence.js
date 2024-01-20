$(document).ready(function(){
	$("#btn_addmore_nature").click(function(){
		$('html, body').stop().animate({
      scrollTop: $("#btn_addmore_nature").offset().top
    }, 600);
		addmoreNature();
	});

	$(".btn_cancel_nature").click(function(){
		$(this).closest(".removenaturedata").remove();
	}); 
	$("#ba_business_account_no").change(function(){
		var id=$(this).val();
		if(id){ getAssesmentData(id); }
		$("#banldetail").removeClass('hide');
	})
	if($("#bas_id").val()>0){
		getAssesmentData($("#bas_id").val());
		$("#banldetail").removeClass('hide');
	}

	$("#otherdeduction").focusout(function(){
		var subtotal = $('#subtotal').val();
		 subtotal = subtotal.replace(",", "");
		var newcalval = subtotal - $("#otherdeduction").val();
		var newcalval = formatNumber(newcalval);
		$('#nettax_due').val(newcalval);
		$("#amountdispaly").val(newcalval);
		$("#totalamt_due").val(newcalval);
	})

	
	$("#appliedtax_credit").focusout(function(){
		var subtotal = $('#subtotal').val();
		 subtotal = subtotal.replace(",", "");
		var newcalval = subtotal - $("#appliedtax_credit").val();
		var newcalval = formatNumber(newcalval);
		$('#nettax_due').val(newcalval);
		$("#amountdispaly").val(newcalval);
		$("#totalamt_due").val(newcalval);
	})
	
});


function formatNumber(nStr){
 nStr += '';
 var x = nStr.split('.');
 var x1 = x[0];
 var x2 = x.length > 1 ? '.' + x[1] : '';
 var rgx = /(\d+)(\d{3})/;
 while (rgx.test(x1)) {
  x1 = x1.replace(rgx, '$1' + ',' + '$2');
 }
 return x1 + x2;
}

function getAssesmentData(id){
	$('.loadingGIF').show();
	var filtervars = {
	    pid:id
	}; 
	$.ajax({
	    type: "GET",
	    url: DIR+'getAssesmentData',
	    data: filtervars,
	    dataType: "html",
	    success: function(html){ 
	    	$('.loadingGIF').hide();
	    	arr = $.parseJSON(html);
	    	console.log(arr.feetable);
	    	$("#feetable").html(arr.feetable);
	    	$("#taxpayername").val(arr.taxpayer);
	    	$("#totaltax_due").val(arr.finalamtview);
	    	$("#nettax_due").val(arr.nettaxdueview);
	    	$("#surcharge").val(arr.surcharge);
	    	$("#interest").val(arr.interest);
	    	$("#otherdeduction").val(arr.otherdeduction);
	    	$("#appliedtax_credit").val(arr.taxcredit);
	    	$("#subtotal").val(arr.subtotalview);
	    	$("#dated").val(arr.date);
	    	$("#applicationdate").val(arr.date);
	    	$("#amountdispaly").val(arr.subtotalview);
	    	$("#order_number").val(arr.ornumber);
	    	$("#ordernumber").val(arr.ornumber);
	    	$("#totalamt_due").val(arr.subtotalview);
	    	$("#accountnumber").val(arr.accountno);
	    }
	});
}

