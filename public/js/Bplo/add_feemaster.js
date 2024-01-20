$(document).ready(function(){
	$("#btn_addmore_checkboxes").click(function(){
		$('html, body').stop().animate({
      scrollTop: $("#btn_addmore_checkboxes").offset().top
    }, 600);
		addmoreCheckbox();
	});
	$('.numeric').numeric();
	$(".btn_cancel_checkboxes").click(function(){
		 $(this).closest(".removeCheckboxdata").remove();
	});
});


function addmoreCheckbox(){
	var prevLength = $("#CheckboxDetails").find(".removeCheckboxdata").length;
	prevLength = parseFloat(prevLength) + 1;
	$("#hidenCheckboxHtml").find('.srno').text(prevLength);
	var html = $("#hidenCheckboxHtml").html();
	$("#CheckboxDetails").append(html);
	$(".btn_cancel_checkboxes").click(function(){
		$(this).closest(".removeCheckboxdata").remove();
	});
	var prevLength = $("#CheckboxDetails").find(".checkboxesdynamic").attr('required','required');
}

