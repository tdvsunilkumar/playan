$(document).ready(function(){
	var associateRows = $("#_associate_count").val();
	var slug = 'social-welfare/pwd-id';
	var searchform = $('#search-id');
	var requireRows = 1;
	searchform.hide();

	//add new requirement row
	$('#file-requirements').on('click', '.add-require', function (e) {
		requireRows = requireRows + 1;

		var html = $('#addRequirements').html();
		html = html.replace(/changeid/g, requireRows+'n');
		html = html.replace(/changename/g, '');
		
		appendToRequirementsTable(html,requireRows)
	});
	// pwd cause
	$('#cause-inborn').hide();
	$('#cause-aquire').hide();
	if ($('input#pwd_cause_type_inborn').is(':checked')) {
			$('#cause-inborn').show();
		} else if ($('input#pwd_cause_type_aquire').is(':checked')){
			$('#cause-aquire').show();
		}
	$('.modal-content .container-fluid').on('change', 'input[name="pwd_cause_type"]', function(){
		$('#cause-inborn').hide();
		$('#cause-aquire').hide();
		if ($('input#pwd_cause_type_inborn').is(':checked')) {
			$('#cause-inborn').show();
		} else if ($('input#pwd_cause_type_aquire').is(':checked')){
			$('#cause-aquire').show();
		}
	});

	// accomplished
	$('.modal-content .container-fluid').on('change', 'input[name="wpaf_accomplished_type"]', function(){
		if ($('input#wpaf_accomplished_type_applicant').is(':checked')) {
			var applicant = $('#select3-cit_id-container').text();
			$('input#wpaf_accomplished_by').val(applicant);
		} else if ($('input#wpaf_accomplished_type_guardian').is(':checked')){
			var applicant = $('#select3-wpaf_guardiansname-container').text();
			$('input#wpaf_accomplished_by').val(applicant);
		}
	});

	citizenRewrite();
	
	// generate pwd id when select brgy
	$('.modal-content .container-fluid').on('change', '#claimant_brgy', function(){
		console.log($(this).val());
		var urlCitizen = DIR + slug +'/getBrgyDetails';
		var token = $("#_csrf_token").val();	
		var value = $(this).val();
		var id = $('#id').val();
		$.ajax({
			type: "POST",
			url: urlCitizen,
			data: {
				'brgy': value,
				'id': id,
				_token: token
			},
			success: function(response){
				var res = JSON.parse(response);
				$.each(res, function(resID,resData){
					$('.select_'+resID).val(resData);
				});	
			}
		});
		
	});

	$('.modal-content .container-fluid').on('click', '.add-associate', function (e) {
		associateRows = associateRows + 1;

		$('.tbl_associate').find('tbody').append('<tr class="citizen_group new-row"><td class="select-contain"><input id="associate-name-'+associateRows+'" class="form-control" name="associate['+associateRows+'][name]" type="text" value="" ></td><td><input id="associate-position-'+associateRows+'" class="form-control" name="associate['+associateRows+'][person]" type="text" value="" ></td><td><input id="associate-address-'+associateRows+'" class="form-control" name="associate['+associateRows+'][address]" type="text" value="" ></td><td><input id="associate-position-'+associateRows+'" class="form-control" name="associate['+associateRows+'][number]" type="text" value="" ></td><td><a href="#associate-sec" class="btn btn-sm btn-danger remove-row" data-bs-toggle="tooltip" title="Remove Dependent"><i class="ti-trash"></i></a></td></tr>');
		var select = '#associate-'+associateRows;
		selectAjax(select); // call dynamic select
		callToggle();
	});

	//remove row both dependent and requirement
	removeRow(slug);
	
	$('.modal-content .container-fluid').on('keyup', '.pwd_id', function(){
		if ($(this).val().length >= 19) {
			$(this).val($(this).val().match(/\d+/g).join("").replace(/(\d{2})\-?(\d{4})\-?(\d{3})\-?(\d{1})/,'$1-$2-$3-$4'))
		}
	});
	
	// if citizen has old records
	$('#search-sec').on('change', 'input[name="wpaf_application_type"]', function (e) {
		searchform.hide();
		if ($('input#wpaf_application_type_renew').is(':checked')) {
			searchform.show();
			$('#id_search').val('');
			$('#wpaf_pwd_id_number').val('');
		} else if ($('input#wpaf_application_type_new').is(':checked')){
			val = $('#wpaf_pwd_id_number').data('val');
			$('#id_search').val(val);
			$('#wpaf_pwd_id_number').val(val);
			$(".create-form").trigger('reset');
			$('.tbl_associate').find('tbody').empty();
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

				appendToRequirementsTable(html,requireRows)
				$('#reqirements-contain').find('.select-contain').find('select').remove()
				$('#reqirements-contain').find('.select-contain').find('.select3').remove()
			});
		}
	});
	$('#search-sec').on('click', '#searchBtn', function (e) {
			// clear first
			// $(".create-form").trigger('reset');
			$('.tbl_associate').find('tbody').empty();
			// $('.file-upload').next('a').remove();
			$('input#old-id').remove();
			$('.file-upload').find('i').addClass('ti-cloud-up');
			$('.file-upload').find('i').removeClass('ti-check');

			//trigger ajax
				var urlCitizen = DIR + slug +'/getLastID';
				var token = $("#_csrf_token").val();	
				var value = $('#id_search').val();
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

							// citizen select
							var group = $('#cit_id').closest('.citizen_group');
							citizenWrite(group,res.cit_id,{'#wpaf_pwd_id_number':res.wpaf_pwd_id_number,'#barangay_pwd_no':res.barangay_pwd_no});
							var group = $('#wpaf_fathersname').closest('.select-contain');
							citizenWrite(group,res.wpaf_fathersname);
							var group = $('#wpaf_mothersname').closest('.select-contain');
							citizenWrite(group,res.wpaf_mothersname);
							var group = $('#wpaf_guardiansname').closest('.select-contain');
							citizenWrite(group,res.wpaf_guardiansname);

							// select
							$.each(res.select, function(id,data){
								$('#'+id).next('span.select3-container').find('.select3-selection__rendered').text(data);
							});			
							
							// radio button
							if(res.wpaf_accomplished_type === '0'){
								$('#wpaf_accomplished_type_applicant').prop('checked', true);
							} else if(res.wpaf_accomplished_type === '1'){
								$('#wpaf_accomplished_type_guardian').prop('checked', true);
							} else if(res.wpaf_accomplished_type === '2'){
								$('#wpaf_accomplished_type_rep').prop('checked', true);
							}
							if(res.pwd_cause_type === 0){
								$('#pwd_cause_type_inborn').prop('checked', true);
								$('#cause-inborn').show();
							} else if(res.pwd_cause_type === 1){
								$('#pwd_cause_type_aquire').prop('checked', true);
								$('#cause-aquire').show();
							}

							// for associates
							$.each(res.associate, function(associateID,associateData){
								associateRows = associateData.id;
								$('.tbl_associate').find('tbody').append('<tr class="citizen_group new-row"><td class="select-contain"><input id="associate-name-'+associateRows+'" class="form-control" name="associate['+associateRows+'][name]" type="text" value="'+associateData.wpo_organization+'" ></td><td><input id="associate-address-'+associateRows+'" class="form-control" name="associate['+associateRows+'][person]" type="text" value="'+associateData.wpo_contact_person+'" ></td><td><input id="associate-position-'+associateRows+'" class="form-control" name="associate['+associateRows+'][address]" type="text" value="'+associateData.wpo_office_address+'" ></td><td><input id="associate-number-'+associateRows+'" class="form-control" name="associate['+associateRows+'][number]" type="text" value="'+associateData.wpo_contact_number+'" ></td><td><a href="#associate-sec" class="btn btn-sm btn-danger remove-row" data-bs-toggle="tooltip" title="Remove Dependent"><i class="ti-trash"></i></a></td></tr>');
								var select = '#associate-'+associateRows;
								selectAjax(select); // call dynamic select
							});

							// textbox [no need to edit]
							$.each(res, function(resID,resData){
								$('#'+resID).val(resData);
							});			

							// for requirements table
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
	// selectAjax(select);

});
