$(document).ready(function(){
	var dependentRows = $("#_family_count").val();
	var associateRows = $("#_associate_count").val();
	var requireRows = 1;
	var slug = 'social-welfare/senior-citizen-id';
	$('.hide-search').hide();
	citizenRewrite();
	//add new dependent
	$('#dependant-sec').on('click', '.add-dependent', function (e) {
		dependentRows = dependentRows + 1;
		var html = $('#addDependants').html();
		html = html.replace(/changeid/g, dependentRows);
		$('.tbl_dependant').find('tbody').append(html);
		var select = '#dependent-'+dependentRows;
		selectAjax(select); // call dynamic select
		selectNormal('#dependent-civil-'+dependentRows)
		citizenRewrite();
		callToggle();
	});

	$('#associate-sec').on('click', '.add-associate', function (e) {
		associateRows = associateRows + 1;

		var html = $('#addAssociates').html();
		html = html.replace(/changeid/g, dependentRows);
		$('.tbl_associate').find('tbody').append(html);
		callToggle();
	});

	//add new requirement row
	$('#req-sec').on('click', '.add-require', function (e) {
		requireRows = requireRows + 1;

		var html = $('#addRequirements').html();
		html = html.replace(/changeid/g, requireRows + 'n');
		html = html.replace(/changename/g, '');
		html = html.replace(/changereqid/g, '');

		appendToRequirementsTable(html,requireRows)
	});
	
	//remove row both dependent and requirement
	removeRow(slug);


	// if citizen has old records
	$('#search-sec').on('change', 'input[name="wsca_is_renewal"]', function (e) {
		$('.hide-search').hide();
		if ($('input#wsca_is_renewal_yes').is(':checked')) {
			$('.hide-search').show();
			$('#osca_search').val('');
			$('#wsca_new_osca_id_no').val('');
		} else if ($('input#wsca_is_renewal_no').is(':checked')){
			val = $('#wsca_new_osca_id_no').data('val');
			$('#osca_search').val(val);
			$('#wsca_new_osca_id_no').val(val);
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
	$('body').on('keyup', '.osca_id', function(){
		if ($(this).val().length >= 6) {
			$(this).val($(this).val().match(/\d+/g).join("").replace(/(\d{2})\-?(\d{2})/,'$1-$2'))
		}
	});

	$('#search-sec').on('click', '#searchBtn', function (e) {

			// clear first
			// $(".create-form").trigger('reset');
			$('.tbl_associate').find('tbody').empty();
			$('.tbl_dependant').find('tbody').empty();
			// $('.file-upload').next('a').remove();
			$('input#old-id').remove();
			$('.file-upload').find('i').addClass('ti-cloud-up');
			$('.file-upload').find('i').removeClass('ti-check');
				var urlCitizen = DIR + slug +'/getLastID';
				var token = $("#_csrf_token").val();	
				var value = $('#osca_search').val();
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
							var group = $('#wsca_name_of_spouse').closest('.citizen_group');
							citizenWrite(group,res.wsca_name_of_spouse);
							// rewrite select
							$('#wstor_id').next('span.select3-container').find('.select3-selection__rendered').text(res.wstor_name);
							$.each(res, function(resID,resData){
								$('#'+resID).val(resData);
							});			
							// for family
							$.each(res.family, function(familyID,familyData){
								dependentRows = dependentRows + 1;
								var add_citizen_el = '<a href="#dependant-sec" data-size="lg" data-url="'+DIR+'citizens/store?field=dependent-'+dependentRows+'" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-original-title="Add Citizen" class="btn btn-sm btn-primary add-citizen-btn"><i class="ti-plus"></i></a>';

								$('.tbl_dependant').find('tbody').append('<tr class="citizen_group new-row"><td class="select-contain"><input id="dependent-'+dependentRows+'" data-url="'+DIR+'citizens/getCitizens" data-placeholder="Search Dependent" data-value="" class="form-control ajax-select"  name="dependent['+dependentRows+'][cit_id]" type="text" value="'+familyData.wsfc_cit+'"></td><td><input id="dependent-relation-'+dependentRows+'" class="form-control" name="dependent['+dependentRows+'][relation]" type="text" value="'+familyData.wsfc_relation+'" ></td><td class="select_age"></td><td class="select_status"></td><td><input id="dependent-relation-'+dependentRows+'" class="form-control" name="dependent['+dependentRows+'][occupation]" type="text" value="'+familyData.wsfc_occupation+'" ></td><td><input id="dependent-relation-'+dependentRows+'" class="form-control" name="dependent['+dependentRows+'][income]" type="text" value="'+familyData.wsfc_monthly_income+'" ></td><td><a href="#dependant-sec" class="btn btn-sm btn-danger remove-row" data-bs-toggle="tooltip" title="Remove Dependent"><i class="ti-trash"></i></a>'+add_citizen_el+'</td></tr>');
								var select = '#dependent-'+dependentRows;
								selectAjax(select); // call dynamic select
								citizenRewrite();
								console.log(familyData.wsfc_cit);
								var group = $(select).closest('.citizen_group');
								citizenWrite(group,familyData.wsfc_cit);
							});
							// for associates
							$.each(res.associate, function(associateID,associateData){
								associateRows = associateRows + 1;
								$('.tbl_associate').find('tbody').append('<tr class="citizen_group new-row"><td class="select-contain"><input id="associate-name-'+associateRows+'" class="form-control" name="associate['+associateRows+'][name]" type="text" value="'+associateData.wsa_association_name+'" ></td><td><input id="associate-address-'+associateRows+'" class="form-control" name="associate['+associateRows+'][address]" type="text" value="'+associateData.wsa_assocation_address+'" ></td><td><input id="associate-position-'+associateRows+'" class="form-control" name="associate['+associateRows+'][position]" type="text" value="'+associateData.wsa_association_position+'" ></td><td><a href="#associate-sec" class="btn btn-sm btn-danger remove-row" data-bs-toggle="tooltip" title="Remove Dependent"><i class="ti-trash"></i></a></td></tr>');
								var select = '#associate-'+associateRows;
								selectAjax(select); // call dynamic select
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