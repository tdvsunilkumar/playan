$(document).ready(function(){
	var select =".ajax-select";	
	selectAjax(select);
	// selectNormal('.select3')
	// $('select').not('#common_pagesize').select3();
});

function selectAjax(element){
	$(element).each(function(){
		var id = $(this).attr('id');
		var value = $(this).data('value');
		var value_id = $(this).data('value_id');
		var url = $(this).data('url');
		var contain = 'contain_'+id;    
			if (value) {
				select = '<option value='+value_id+' selected="selected">'+value+'</option>';
				$(this).append(select);
			}
			select3Ajax(id,contain,url);
	});
}
function selectNormal(element){
	$(element).each(function(){
		var id = $(this).attr('id');
		var contain = 'contain_'+id;  
			$("#"+id).select3();
	});
}
// function selectNormal(element){
// 	$(element).each(function(){
// 		$(this).next('span.select3-container').remove()
// 		var contain = $(this).data('contain'); 
// 		var id = $(this).attr('id');    
// 		var value = $(this).data('value');
// 		var value_id = $(this).data('value_id');
// 		var name = $(this).attr('name');   
// 		// if (contain == "undefined" && contain == null) {
// 		// 	contain = id+'_contain'
// 		// }
// 		// if ($(this).is('[readonly]')) {
// 		// 	$('#'+contain).find('#show-'+id).remove()
// 		// 	$('#'+contain).find('#hide-'+id).remove()
// 		// 	$(this).addClass('required-hide')
// 		// 	$(this).after('<input type="text" name="citizen" class="form-control" value="'+value+'" readonly id="show-'+id+'">');
// 		// 	$(this).after('<input type="hidden" name="'+name+'" class="form-control" value="'+value_id+'" id="hide-'+id+'">');
// 		// } else {    
// 		// 	if (value) {
// 		// 		select = '<option value='+value_id+' selected="selected">'+value+'</option>';
// 		// 		$(this).append(select);
// 		// 	}
// 			$("#"+id).select3({
// 				dropdownAutoWidth : false,
// 				dropdownParent: $("#"+contain),
// 			});
// 		// }
// 	});
// }
