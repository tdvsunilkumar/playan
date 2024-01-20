$(document).ready(function(){
	var dependentRows = $("#_family_count").val();
	var slug = 'social-welfare/solo-parent-id';
	var searchform = $('#search-id');
	var requireRows = 1;
	searchform.hide();
	citizenRewrite();
	//add new dependent
	$('#dependant-sec').on('click', '.add-dependent', function (e) {
		dependentRows = dependentRows + 1;
		
		var html = $('#addDependants').html();
		html = html.replace(/{id}/g, dependentRows);
		$('.tbl_dependant').find('tbody').append(html);
		var select = '#dependent-'+dependentRows;
		selectAjax(select);
		citizenRewrite();
		selectNormal('#dependent-civil-'+dependentRows)
		selectNormal('#dependent-educ-'+dependentRows)
		callToggle();
	});
	
	//add new requirement row
	$('#file-requirements').on('click', '.add-require', function (e) {
		requireRows = requireRows + 1;

		var html = $('#addRequirements').html();
		html = html.replace(/changeid/g, requireRows+'n');
		html = html.replace(/changename/g, '');
		html = html.replace(/changereqid/g, '');

		appendToRequirementsTable(html,requireRows)
	});
	
	//remove row both dependent and requirement
	removeRow(slug);

	// if citizen has old records
	$('#search-sec').on('change', 'input[name="wspa_is_renewal"]', function (e) {
		searchform.hide();
		if ($('input#wspa_is_renewal_yes').is(':checked')) {
			searchform.show();
			$('#id_search').val('');
			$('#wspa_id_number').val('');
		} else if ($('input#wspa_is_renewal_no').is(':checked')){
			val = $('#wspa_id_number').data('val');
			$('#id_search').val(val);
			$('#wspa_id_number').val(val);
			$(".create-form").trigger('reset');
			$('.tbl_associate').find('tbody').empty();
			$('.tbl_dependant').find('tbody').empty();
			// $('.file-upload').next('a').remove();
			$('input#old-id').remove();
			$('.file-upload').find('i').addClass('ti-cloud-up');
			$('.file-upload').find('i').removeClass('ti-check');

			$('#reqirements-contain').empty()
			requirements = $('#requirements_list').val();
			$.each($.parseJSON(requirements), function(reqid,reqname){
				requireRows = requireRows + 1;
				var html = $('#addRequirements').html();
				html = html.replace(/changeid/g, requireRows+'n');
				html = html.replace(/changename/g, reqname);
				html = html.replace(/changereqid/g, reqid);

				appendToRequirementsTable(html,requireRows)
				$('#reqirements-contain').find('.select-contain').find('select').remove()
				$('#reqirements-contain').find('.select-contain').find('.select3').remove()
			});
		}
	});

	// rewrite for id
	$('.create-form').on('keyup', '.osca_id', function(){
		if ($(this).val().length >= 6) {
			$(this).val($(this).val().match(/\d+/g).join("").replace(/(\d{2})\-?(\d{2})/,'$1-$2'))
		}
	});

	$('#search-sec').on('click', '#searchBtn', function (e) {
			// clear first
			$('.tbl_associate').find('tbody').empty();
			$('.tbl_dependant').find('tbody').empty();
			// $('.file-upload').next('a').remove();
			$('input#old-id').remove();
			$('.file-upload').find('i').addClass('ti-cloud-up');
			$('.file-upload').find('i').removeClass('ti-check');
				var urlCitizen = DIR + slug +'/getLastID';
				var token = $("#_csrf_token").val();	
				var value = $('#id_search').val();
				// $('.create-form input[name="submit"]').val();
				$.ajax({
					type: "POST",
					url: urlCitizen,
					data: {
						'id': value,
						_token: token
					},
					success: function(response){
						var res = JSON.parse(response); 
						if(response != 'null'){
							$(".create-form").append('<input type="hidden" id="old-id" name="old_id" value="'+res.id+'">');
							$('#wspa_needs_problem_yes').prop('checked', true);
							var group = $('#cit_id').closest('.citizen_group');
							citizenWrite(group,res.cit_id);
							var group = $('#wspa_name_of_spouse').closest('.citizen_group');
							citizenWrite(group,res.wspa_name_of_spouse);
							// rewrite select
							$('#wstor_id').next('span.select3-container').find('.select3-selection__rendered').text(res.wstor_name);
							$.each(res, function(resID,resData){
								$('#'+resID).val(resData);
							});			
							// for family
							$.each(res.family, function(familyID,familyData){
								rowId = familyData.id;

								var html = $('#addDependants').html();
								html = html.replace(/{id}/g, rowId);
								$('.tbl_dependant').find('tbody').append(html);
								var select = '#dependent-'+rowId;
								selectAjax(select);
								citizenRewrite();
								var group = $(select).closest('.citizen_group');
								group.find('.select-contain').append('<input name="dependent['+rowId+'][cit_id]" type="hidden" value="'+familyData.wsfc_cit+'">');
								group.find('.get_relation').val(familyData.wsfc_relation)
								group.find('.get_income').val(familyData.wsfc_monthly_income)
								citizenWrite(group,familyData.wsfc_cit);
								selectNormal('#dependent-civil-'+rowId)
								selectNormal('#dependent-educ-'+rowId)
								callToggle();
							});
							
							// for requirements
							$('#reqirements-contain').empty()
							$.each(res.files, function(fileID,fileData){
								fileID = fileData.id;
								if (fileData.fwsc_path) {
									check = '<i class="ti-check text-white"></i>';
									download = '<a class="btn btn-sm bg-primary small-button" href="'+DIR+fileData.fwsc_path+'" download="'+res.claimant.cit_last_name+'_'+fileData.req_name+'" data-bs-toggle="tooltip" title="Download File"><i class="ti-download text-white"></i></a>';
								} else {
									check = '<i class="ti-cloud-up text-white"></i>';
									download = '';
								}
								if (fileData.fwsc_is_active == 0) {
									removeBtn = '<a class="btn btn-sm btn-info remove-row" data-bs-toggle="tooltip" title="Remove Requirment" data-remove="requirement" data-id="'+fileID+'" data-active="1">'
										+'<i class="ti-reload text-white"></i>'
									+'</a>'
								} else {
									removeBtn = '<a class="btn btn-sm btn-danger remove-row" data-bs-toggle="tooltip" title="Remove Requirment" data-remove="requirement" data-id="'+fileID+'" data-active="0">'
										+'<i class="ti-reload text-white"></i>'
									+'</a>'
								}
								html = '<tr class="row-'+fileID+'">'
										+'<td>'+fileData.req_name+'</td>'
										+'<td>'
											+'<div class="file-upload bg-info btn btn-sm small-button" data-bs-toggle="tooltip" title="Upload File">'
											+'<label for="require-old-'+fileID+'">'
											+check
											+'</label>'
											+'<input type="file" class="form-control required-hide" id="require-old-'+fileID+'" value="" name="require['+fileID+'][file]">'
											+'</div>'
											+download
											+'<input type="hidden" name="require['+fileID+'][req_id]" value="'+fileData.req_id+'" ><input type="hidden" name="require['+fileID+'][req_type]" value="'+fileData.req_type+'" >'
											+removeBtn
										+'</td>'
									+'</tr>'

								$('#reqirements-contain').append(html);
							});			
							uploadFile()

						}
					}
				})
			
	});
});

