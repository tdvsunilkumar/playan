$(document).ready(function(){
	var select =".ajax-select";	
	selectAjax(select);
	selectNormal('.select3')
	// $('select').not('#common_pagesize').select3();
});

function selectAjax(element){
	$(element).each(function(){
		var id = $(this).attr('id');
		var name = $(this).attr('name');
		var value = $(this).data('value');
		var value_id = $(this).data('value_id');
		var url = $(this).data('url');
		var contain = $(this).data('contain');    
		if ($(this).is('[readonly]')) {
			$('#'+contain).find('#show-'+id).remove()
			$('#'+contain).find('#hide-'+id).remove()
			$(this).addClass('required-hide')
			$(this).after('<input type="text" name="citizen" class="form-control" value="'+value+'" readonly id="show-'+id+'">');
			$(this).after('<input type="hidden" name="'+name+'" class="form-control" value="'+value_id+'" id="hide-'+id+'">');
		} else {    
			if (value) {
				select = '<option value='+value_id+' selected="selected">'+value+'</option>';
				$(this).append(select);
			}
			select3Ajax(id,contain,url);
		}
	});

	// for upload file
	$('input[type="file"]').change(function(e){
		var file = $(this);
		if(file[0].files.length != 0){
			file.closest('.file-upload').find('label').find('i').addClass('ti-check');
			file.closest('.file-upload').find('label').find('i').removeClass('ti-cloud-up');
		
			if (objectURL) {
				// revoke the old object url to avoid using more memory than needed
				URL.revokeObjectURL(objectURL);  
			}
			const upload = file[0].files[0];
			var objectURL = URL.createObjectURL(e.target.files[0]);
			file.closest('.file-upload').next('a').remove();
			file.closest('.file-upload').after('<a href="'+objectURL+'" download="'+upload.name+'" class="btn btn-sm bg-primary small-button" data-bs-toggle="tooltip" title="Download File"><i class="ti-download text-white"></i></a>');
			callToggle();
		}
	});
}
function selectNormal(element){
	$(element).each(function(){
		$(this).next('span.select3-container').remove()
		var contain = $(this).data('contain');    
		var id = $(this).attr('id');    
		var value = $(this).data('value');
		var value_id = $(this).data('value_id');
		var name = $(this).attr('name');
		if ($(this).is('[readonly]')) {
			console.log('hello');
			$('#'+contain).find('#show-'+id).remove()
			$('#'+contain).find('#hide-'+id).remove()
			$(this).addClass('required-hide')
			$(this).after('<input type="text" name="citizen" class="form-control" value="'+value+'" readonly id="show-'+id+'">');
			$(this).after('<input type="hidden" name="'+name+'" class="form-control" value="'+value_id+'" id="hide-'+id+'">');
		} else {    
			$("#"+id).select3({
				dropdownAutoWidth : false,
				dropdownParent: $("#"+contain),
			});
		}
	});
}
