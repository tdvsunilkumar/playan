$(document).ready(function(){
	$("#bill_details").click(function(){
			$('#myModal').modal('show');
	})
	$(".closeModel").click(function(){
			$('#myModal').modal('hide');
	})
	$("#Printbill").click(function(){
			// $('#myModal1').modal('show');
			//$("#paccountno").text($("#accountnumber").val());
			// $("#pdate").text($("#date").val());
			// $("#pownername").text($("#ownar_name").val());
			// $("#ptradingname").text($("#business_name").val());
			// $("#paddress").text($("#address").val());
			printDiv();
			$('#myModal').modal('hide');
	})
	$("#printinv").click(function(){
				
	});
	$("#btn_addmore_feedetails").click(function(){
		$('html, body').stop().animate({
      scrollTop: $("#btn_addmore_feedetails").offset().top
    }, 600);
		addmoreFees();
	});
	$('.numeric').numeric();
	$(".btn_cancel_feemaster").click(function(){
		 $(this).closest(".removefeemasterdata").remove();
	});
	 $('#ebfd_bldg_est_cost').on('change keyup',function(){
             claculateTotalEstimate();
     })
});

function addmoreFees(){
	 var prevLength = $("#feedetails").find(".removefeemasterdata").length;
	 var html = $("#hidenCheckboxHtml").html();
		$("#feedetails").append(html);
		$(".btn_cancel_feemaster").click(function(){
		$(this).closest(".removefeemasterdata").remove();
		 var cnt = $("#feedetails").find(".removefeemasterdata").length;
		$("#hidenCheckboxHtml").find('select').attr('id','arrayoffee'+cnt);
		});
		//$('#feedetails .arrayoffee:last-child').select3({});
		var classid = $("#feedetails").find(".removefeemasterdata").length;
		$("#arrayoffee"+prevLength).select3({});
		$("#hidenCheckboxHtml").find('select').attr('id','arrayoffee'+classid);

	 $('.arrayoffee').on('change', function() { 
	 	 var feeid =$(this).val();
	 	 var currid = $(this);
	       $.ajax({
	            url :DIR+'business-fee-master/getcheckboxes', // json datasource
	            type: "POST", 
	            data: {
	                    "feeid": feeid, "_token": $("#_csrf_token").val(),
	                },
	            success: function(html){
	            	alert();
	                currid.closest('.removefeemasterdata').find('.checkboxarea').html(html)
	            }
	        })
	 });
}

function printDiv() 
{
   // var divToPrint=document.getElementById('DivIdToPrint');
    var contents = $('#printtaxdiv').html();
    //alert(contents);
	var htmlToPrint = '' +
	'<style type="text/css">' +
	'.print-table-list th{' +
		'border:0.5px solid gray;'+
		'border-style: dashed; border-width: 0.5px 0.1px 0.5px 0.5px;' +
	'}' +
	'.print-table-list td{' +
		'padding-top:8px;'+
	'}' +
	'</style>';

    var frame1 = document.createElement('iframe');
    frame1.name = "frame1";
    frame1.style.position = "absolute";
    frame1.style.top = "-1000000px";
    document.body.appendChild(frame1);
    var frameDoc = (frame1.contentWindow) ? frame1.contentWindow : (frame1.contentDocument.document) ? frame1.contentDocument.document : frame1.contentDocument;
    frameDoc.document.open();
    frameDoc.document.write('</head><body>');
    frameDoc.document.write(htmlToPrint+contents);
    frameDoc.document.write('</body></html>');
    frameDoc.document.close();
    setTimeout(function () {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        document.body.removeChild(frame1);
    }, 500);
    return false;

}

