$(document).ready(function(){
	$("#or_field_form").select3({dropdownAutoWidth : false,dropdownParent: $(".or_field_group")});
	$("#ortype_id").select3({dropdownAutoWidth : false,dropdownParent: $(".ortype_group")});
	$("#copy_user_id").select3({dropdownAutoWidth : false,dropdownParent: $(".copy_user_id_group")});
	
	$('.numeric').numeric();
	$(".editDtls").unbind("click");
	$(".editDtls").click(function(){
		var h_id=$(this).attr('h_id');
		$("#formDtls_"+h_id).slideToggle();
		if($("#formDtls_"+h_id).hasClass("hide")){
			$("#formDtls_"+h_id).removeClass("hide");
			$(this).removeClass("ti-pencil");
			$(this).addClass("ti-close");
			
		}else{
			$("#formDtls_"+h_id).addClass("hide");
			$(this).addClass("ti-pencil");
			$(this).removeClass("ti-close");
		}
 	});

	$(".saveDetails").unbind("click");
 	$(".saveDetails").click(function(){
 		var h_id=$(this).attr('h_id');
 		saveData(h_id);
 	})
});

function saveData(h_id){
	var id = $("#id").val();
	var myform = $('#formDtls_'+h_id);
	var data = {};
	$('#formDtls_'+h_id+' :input').serializeArray().map(function(x){data[x.name] = x.value;}); 
    const swalWithBootstrapButtons = Swal.mixin({
	   customClass: {
		   confirmButton: 'btn btn-success',
		   cancelButton: 'btn btn-danger'
	   },
	   buttonsStyling: false
   })
   swalWithBootstrapButtons.fire({
	   title: 'Are you sure?',
	   text: "",
	   icon: 'warning',
	   showCancelButton: true,
	   confirmButtonText: 'Yes',
	   cancelButtonText: 'No',
	   reverseButtons: true
   }).then((result) => {
	   	if(result.isConfirmed){
		  	$.ajax({
			   url :DIR+'CtoPaymentOrSetup/saveDetails', // json datasource
			   type: "POST", 
			   data: {
				 "id": id,
				 "h_id":h_id,
				 "data": data,  
				 "_token": $("#_csrf_token").val(),
			   },
			   success: function(html){
				   	Swal.fire({
						position: 'center',
						icon: 'success',
						title: 'Update Successfully.',
						showConfirmButton: false,
						timer: 1500
				   });
				   let to_update_html = `<iframe id="pdf-iframe" src="${DIR}bplo-or-setup-sample/${id}" width="90%" height="1200px"></iframe>`;
				   $('#sample-frame').html(to_update_html);
			   }
		   })
	   }
   })
}

copyDetails = (or_field_form, id) =>{
	let user_id = $('#copy_user_id').val();
	if(user_id == null || user_id == ''){
		alert('Please Select The User To Copy');return;
	}
	let data = {
		or_field_form : or_field_form,
		user_id : user_id,
		id :id
	}

	$.ajax({
		url :DIR+'copy-or-setups', // json datasource
		type: "get", 
		data: data,
		success: function(html){
			let setup_details = html.setups;
			let data = html.data;
			setup_details.forEach((el, k) => {
				let name = `${el.key}_name`;
				let font_size = `${el.key}_font_size`;
				let position_top = `${el.key}_position_top`;
				let position_bottom = `${el.key}_position_bottom`;
				let position_left = `${el.key}_position_left`;
				let position_right = `${el.key}_position_right`;
				let font_is_bold = `${el.key}_font_is_bold`;
				let is_visible = `${el.key}_is_visible`;
				$('#' + name).val(el.values[name]);
				$('#' + font_size).val(el.values[font_size]);
				$('#' + position_top).val(el.values[position_top]);
				$('#' + position_bottom).val(el.values[position_bottom]);
				$('#' + position_left).val(el.values[position_left]);
				$('#' + position_right).val(el.values[position_right]);
				$('#' + font_is_bold).prop('checked', el.values[font_is_bold] == 1 ? true : false);
				$('#' + is_visible).prop('checked', el.values[is_visible] == 1 ? true : false);
			});
			console.log(data.is_portrait);
			$('#is_portrait').prop('checked', data.is_portrait == 1 ? true : false);
			$('#ors_remarks').val(data.ors_remarks);
			Swal.fire({
				position: 'center',
				icon: 'success',
				title: 'Update Successfully.',
				showConfirmButton: false,
				timer: 1500
			});
			let to_update_html = `<iframe id="pdf-iframe" src="${DIR}bplo-or-setup-sample/${id}" width="90%" height="1200px"></iframe>`;
			$('#sample-frame').html(to_update_html);
		}
	})

}
