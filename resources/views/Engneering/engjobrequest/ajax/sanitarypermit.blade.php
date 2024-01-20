{{Form::open(array('name'=>'forms','url'=>'jobrequest/storesanitarypermit','method'=>'post','id'=>'storesanitarypermit'))}}
 {{ Form::hidden('jobrequest_id',(isset($JobRequest->id))?$JobRequest->id:NULL, array('id' => 'jobrequest_id')) }}
 {{ Form::hidden('application_id',(isset($sanitaryappdata->id))?$sanitaryappdata->id:NULL, array('id' => 'application_id')) }}
  {{ Form::hidden('p_code',(isset($pcode))?$pcode:'', array('id' => 'p_code')) }}
 <style type="text/css">
     .row{ padding: 0px 10px; }
 </style>
<div class="modal-header">
<h4 class="modal-title">Sanitary/Plumbing Permit Application</h4>
	<a class="close closeSanitaryModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true" style="cursor:pointer;">X</a>
</div>
<div class="container"></div>
<div class="modal-body" style="overflow-y: scroll; height: 800px;">
  <div id="page1">  
	<div  class="accordion accordion-flush">
		<div class="accordion-item">
			<h6 class="accordion-header" id="flush-headingfive">
				<button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
					<h6 class="sub-title accordiantitle">{{__("Application Information")}}</h6>
				</button>
			</h6>
			 <div class="row">
				<div class="col-md-4">
					<div class="form-group">
						{{ Form::label('mum_no', __('Municipal'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
						<span class="validate-err">{{ $errors->first('mum_no') }}</span>
						<div class="form-icon-user">
							{{ Form::select('mum_no',$GetMuncipalities,$sanitaryappdata->mum_no, array('class' => 'form-control mum_no disabled-field','id'=>'ebpa_mun_no','required'=>'required')) }}
						</div>
						<span class="validate-err" id="err_mum_no"></span>
					</div>
				</div>
				 <div class="col-md-4">
					<div class="form-group">
						{{ Form::label('espa_application_no', __('Application No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
						<span class="validate-err">{{ $errors->first('espa_application_no') }}</span>
						<div class="form-icon-user">
							{{ Form::text('espa_application_no',$sanitaryappdata->espa_application_no, array('class' => 'form-control disabled-field espa_application_no','id'=>'espa_application_no','required'=>'required')) }}
						</div>
						<span class="validate-err" id="err_espa_application_no"></span>
					</div>
				</div>
				 <div class="col-md-4">
					<div class="form-group" id="permitnodiv">
						{{ Form::label('ebpa_permit_no', __('Building Permit No.'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('ebpa_permit_no') }}</span>
						<div class="form-icon-user">
							{{ Form::select('ebpa_permit_no',$arrPermitno,$sanitaryappdata->ebpa_permit_no, array('class' => 'form-control ebpa_permit_no','id'=>'ebpa_permit_no')) }}
						</div>
						<span class="validate-err" id="err_ebpa_permit_no"></span>
					</div>
				</div>
				 <div class="col-md-4">
					<div class="form-group">
						{{ Form::label('espa_application_date', __('Date of Application'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('espa_application_date') }}</span>
						<div class="form-icon-user">
							{{ Form::date('espa_application_date',$sanitaryappdata->espa_application_date, array('class' => 'form-control espa_application_date','id'=>'espa_application_date')) }}
						</div>
						<span class="validate-err" id="err_ebpa_application_date"></span>
					</div>
				</div>
				 <div class="col-md-4">
					<div class="form-group">
						{{ Form::label('espa_issued_date', __('Date Issued'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('espa_issued_date') }}</span>
						<div class="form-icon-user">
							{{ Form::date('espa_issued_date',$sanitaryappdata->espa_issued_date, array('class' => 'form-control espa_issued_date','id'=>'espa_issued_date')) }}
						</div>
						<span class="validate-err" id="err_ebpa_issued_date"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div  class="accordion accordion-flush">
		<div class="accordion-item">
			<h6 class="accordion-header" id="flush-headingfive">
				<button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
					<h6 class="sub-title accordiantitle">{{__("Building Permit Details")}}</h6>
				</button>
			</h6>
			 <div class="row">
				<div class="col-md-8">
					<div class="form-group">
						{{ Form::label('permit_owner_name', __('Permit Owner Name'),['class'=>'form-label bold']) }}
						<span class="validate-err">{{ $errors->first('permit_owner_name') }}</span>
						<div class="form-icon-user">
							{{ Form::text('permit_owner_name','', array('class' => 'form-control disabled-field permit_owner_name','id'=>'permit_owner_name')) }}
						</div>
						<span class="validate-err" id="err_mum_no"></span>
					</div>
				</div>
				 <div class="col-md-4">
					<div class="form-group">
						{{ Form::label('job_re_reference', __('Job Request Reference'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('job_re_reference') }}</span>
						<div class="form-icon-user">
							{{ Form::text('job_re_reference','', array('class' => 'form-control disabled-field job_re_reference','id'=>'job_re_reference')) }}
						</div>
						<span class="validate-err" id="err_eda_application_no"></span>
					</div>
				</div>
				 <div class="col-md-8">
					<div class="form-group">
						{{ Form::label('owner_complete_address', __('Owner Complete Address'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('owner_complete_address') }}</span>
						<div class="form-icon-user">
							{{ Form::text('owner_complete_address','', array('class' => 'form-control disabled-field owner_complete_address','id'=>'owner_complete_address')) }}
						</div>
						<span class="validate-err" id="err_ebpa_permit_no"></span>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						{{ Form::label('building_permit_location', __('Building Permit Location'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('building_permit_location') }}</span>
						<div class="form-icon-user">
						 {{ Form::text('building_permit_location','', array('class' => 'form-control disabled-field building_permit_location','id'=>'building_permit_location')) }}
						</div>
						<span class="validate-err" id="err_ebpa_permit_no"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div  class="accordion accordion-flush">
		<div class="accordion-item">
			<h6 class="accordion-header" id="flush-headingfive">
				<button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
					<h6 class="sub-title accordiantitle">{{__("Owner Information")}}</h6>
				</button>
			</h6>
			 <div class="row">
				<div class="col-md-4">
					<div class="form-group">
						{{ Form::label('ebpa_owner_last_name', __('Last Name'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
						<span class="validate-err">{{ $errors->first('ownerlastname') }}</span>
						<div class="form-icon-user">
							{{ Form::text('ebpa_owner_last_name',$sanitaryappdata->rpo_custom_last_name, array('class' => 'form-control ebpa_owner_last_name','id'=>'ebpa_owner_last_name','required'=>'required')) }}
						</div>
						<span class="validate-err" id="err_ebpa_owner_last_name"></span>
					</div>
				</div>
				 <div class="col-md-4">
					<div class="form-group">
						{{ Form::label('ebpa_owner_first_name', __('First Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
						<span class="validate-err">{{ $errors->first('ownerfirstname') }}</span>
						<div class="form-icon-user">
							{{ Form::text('ebpa_owner_first_name',$sanitaryappdata->rpo_first_name, array('class' => 'form-control ebpa_owner_first_name','id'=>'ebpa_owner_first_name','required'=>'required')) }}
						</div>
						<span class="validate-err" id="err_ebpa_owner_first_name"></span>
					</div>
				</div>
				 <div class="col-md-2">
					<div class="form-group">
						{{ Form::label('ebpa_owner_mid_name', __('Middle Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
						<span class="validate-err">{{ $errors->first('ebpa_owner_mid_name') }}</span>
						<div class="form-icon-user">
							{{ Form::text('ebpa_owner_mid_name',$sanitaryappdata->rpo_middle_name, array('class' => 'form-control ebpa_owner_mid_name','id'=>'ebpa_owner_mid_name','required'=>'required')) }}
						</div>
						<span class="validate-err" id="err_ebpa_owner_mid_name"></span>
					</div>
				</div>
				  <div class="col-md-2">
					<div class="form-group">
						{{ Form::label('ebpa_owner_suffix_name', __('Suffix'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('suffix') }}</span>
						<div class="form-icon-user">
							{{ Form::text('ebpa_owner_suffix_name',$sanitaryappdata->suffix, array('class' => 'form-control suffix','id'=>'suffix')) }}
						</div>
						<span class="validate-err" id="err_ebpa_owner_suffix_name"></span>
					</div>
				</div>
				 <div class="col-md-4">
					<div class="form-group">
						{{ Form::label('taxacctno', __('Tax Acct. No.'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('taxacctno') }}</span>
						<div class="form-icon-user">
							{{ Form::text('taxacctno',$sanitaryappdata->taxacctno, array('class' => 'form-control taxacctno','id'=>'taxacctno')) }}
						</div>
						<span class="validate-err" id="err_taxacctno"></span>
					</div>
				</div>
				 <div class="col-md-4">
					<div class="form-group">
						{{ Form::label('formofowner', __('Form of Ownership'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('formofowner') }}</span>
						<div class="form-icon-user">
							{{ Form::text('formofowner',$sanitaryappdata->formofowner, array('class' => 'form-control formofowner','id'=>'formofowner')) }}
						</div>
						<span class="validate-err" id="err_ebpa_form_of_own"></span>
					</div>
				</div>
				 <div class="col-md-4">
					<div class="form-group">
						{{ Form::label('maineconomy', __('Main Economic Activity/Kind Business'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('maineconomy') }}</span>
						<div class="form-icon-user">
							{{ Form::text('maineconomy',$sanitaryappdata->maineconomy, array('class' => 'form-control maineconomy','id'=>'maineconomy')) }}
						</div>
						<span class="validate-err" id="err_ebpa_economic_act"></span>
					</div>
				</div>
				  <div class="col-md-4">
					<div class="form-group">
						{{ Form::label('ebpa_address_house_lot_no', __('House Lot No.'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('ebpa_address_house_lot_no') }}</span>
						<div class="form-icon-user">
							{{ Form::text('ebpa_address_house_lot_no',$sanitaryappdata->rpo_address_house_lot_no, array('class' => 'form-control ebpa_address_house_lot_no','id'=>'ebpa_address_house_lot_no')) }}
						</div>
						<span class="validate-err" id="err_ebpa_address_house_lot_no"></span>
					</div>
				</div>
				  <div class="col-md-4">
					<div class="form-group">
						{{ Form::label('', __('Street Name'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('ebpa_address_street_name') }}</span>
						<div class="form-icon-user">
							{{ Form::text('ebpa_address_street_name',$sanitaryappdata->rpo_address_street_name, array('class' => 'form-control ebpa_address_street_name','id'=>'ebpa_address_street_name')) }}
						</div>
						<span class="validate-err" id="err_ebpa_address_street_name"></span>
					</div>
				</div>
				 <div class="col-md-4">
					<div class="form-group">
						{{ Form::label('ebpa_address_subdivision', __('Subdivision'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('ebpa_address_subdivision') }}</span>
						<div class="form-icon-user">
							{{ Form::text('ebpa_address_subdivision',$sanitaryappdata->rpo_address_subdivision, array('class' => 'form-control ebpa_address_subdivision','id'=>'ebpa_address_subdivision')) }}
						</div>
						<span class="validate-err" id="err_ebpa_address_subdivision"></span>
					</div>
				</div>
				 <div class="col-md-8">
					<div class="form-group">
						{{ Form::label('appbrgy_code', __('Barangay, Municipality, Province, Region'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('brgy_code') }}</span>
						<div class="form-icon-user">
							{{ Form::text('appbrgy_code','', array('class' => 'form-control disabled-field appbrgy_code','id'=>'appbrgy_code')) }}
						</div>
						<span class="validate-err" id="err_brgy_code"></span>
					</div>
				</div>
				 <div class="col-md-4">
					<div class="form-group">
						{{ Form::label('espa_location', __('City/Municipality of'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('espa_location') }}</span>
						<div class="form-icon-user">
							{{ Form::text('espa_location',$sanitaryappdata->espa_location, array('class' => 'form-control espa_location','id'=>'espa_location')) }}
						</div>
						<span class="validate-err" id="err_espa_location"></span>
					</div>
				</div>
			</div>
			
		</div>
	</div>
	<div  class="accordion accordion-flush">
		 <div class="accordion-item">
			<h6 class="accordion-header" id="flush-headingfive">
				<button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
					<h6 class="sub-title accordiantitle">{{__("Location of Construction")}}</h6>
				</button>
			</h6>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						{{ Form::label('tdno', __('Tax Dec. No.'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('tdno') }}</span>
						<div class="form-icon-user">
							{{ Form::text('taxdcno',$sanitaryappdata->taxdcno, array('class' => 'form-control select3 tdno','id'=>'tdno')) }}
						</div>
						<span class="validate-err" id="err_tdno"></span>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						{{ Form::label('totno', __('TCT No'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('totno') }}</span>
						<div class="form-icon-user">
							{{ Form::text('totno',$sanitaryappdata->totno, array('class' => 'form-control select3 totno','id'=>'totno')) }}
						</div>
						<span class="validate-err" id="err_Street"></span>
					</div>
				</div>
				 
				 <div class="col-md-2">
					<div class="form-group">
						{{ Form::label('lotno', __('LOT No.'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('lotno') }}</span>
						<div class="form-icon-user">
							{{ Form::text('lotno',$sanitaryappdata->lotno, array('class' => 'form-control select3 lotno','id'=>'lotno')) }}
						</div>
						<span class="validate-err" id="err_Street"></span>
					</div>
				</div>
				 <div class="col-md-2">
					<div class="form-group">
						{{ Form::label('blkno', __('BLK No.'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('blkno') }}</span>
						<div class="form-icon-user">
							{{ Form::text('blkno',$sanitaryappdata->blkno, array('class' => 'form-control select3 blkno','id'=>'blkno')) }}
						</div>
						<span class="validate-err" id="err_Street"></span>
					</div>
				</div>
				 
			</div>
			<div class="row">
				 <div class="col-md-4">
					<div class="form-group">
						{{ Form::label('Street', __('Street'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('Street') }}</span>
						<div class="form-icon-user">
							{{ Form::text('Street',$sanitaryappdata->Street, array('class' => 'form-control  Street','id'=>'Street')) }}
						</div>
						<span class="validate-err" id="err_Street"></span>
					</div>
				</div>
				 <div class="col-md-4">
					<div class="form-group">
						{{ Form::label('locappbrgy_code', __('Barangay'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('locbrgy_code') }}</span>
						<div class="form-icon-user">
							{{ Form::text('locappbrgy_code',$sanitaryappdata->locbarangay, array('class' => 'form-control disabled-field locappbrgy_code','id'=>'locappbrgy_code')) }}
						</div>
						<span class="validate-err" id="err_brgy_code"></span>
					</div>
				</div>
				 <div class="col-md-4">
					<div class="form-group">
						{{ Form::label('locespa_location', __('City / Municipality of'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('espa_location') }}</span>
						<div class="form-icon-user">
							{{ Form::text('locespa_location',$sanitaryappdata->locmunicipality, array('class' => 'form-control disabled-field','id'=>'locespa_location')) }}
						</div>
						<span class="validate-err" id="err_espa_location"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	 <div  class="accordion accordion-flush">
		<div class="accordion-item">
			<h6 class="accordion-header" id="flush-headingfive">
				<button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
					<h6 class="sub-title accordiantitle">{{__("Scope of Work")}}</h6>
				</button>
			</h6>
			 <div class="row">
				<div class="col-md-4">
					<div class="form-group" id="ebs_id_group">
						{{ Form::label('ebs_id', __('Scope of Work'),['class'=>'form-label bold']) }}
						<span class="validate-err">{{ $errors->first('ebs_id') }}</span>
						<div class="form-icon-user">
							{{ Form::select('ebs_id',$arrscopeofwork,$sanitaryappdata->ebs_id, array('class' => 'form-control','id'=>'ebs_id')) }}
						</div>
						<span class="validate-err" id="err_ebs_id"></span>
					</div>
				</div>
				 <div class="col-md-8">
					<div class="form-group">
						{{ Form::label('ebpa_scope_remarks', __('Other Remarks'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('ebpa_scope_remarks') }}</span>
						<div class="form-icon-user">
							{{ Form::text('ebsa_scope_remarks',$sanitaryappdata->ebsa_scope_remarks, array('class' => 'form-control ','id'=>'ebpa_scope_remarks')) }}
						</div>
						<span class="validate-err" id="err_ebpa_scope_remarks"></span>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group" id="ebot_id_group">
						{{ Form::label('ebot_id', __('USE OR TYPE OF OCCUPANCY'),['class'=>'form-label bold']) }}
						<span class="validate-err">{{ $errors->first('ebot_id') }}</span>
						<div class="form-icon-user">
							{{ Form::select('ebot_id',$arrTypeofOccupancy,$sanitaryappdata->ebot_id, array('class' => 'form-control','id'=>'ebot_id')) }}
						</div>
						<span class="validate-err" id="err_ebs_id"></span>
					</div>
				</div>
				 <div class="col-md-8">
					<div class="form-group">
						{{ Form::label('otherOccupancy', __('Other Remark (Occupancy)'),['class'=>'form-label']) }}
						<span class="validate-err">{{ $errors->first('other Occupancy') }}</span>
						<div class="form-icon-user">
							{{ Form::text('otheroccupancy',$sanitaryappdata->otheroccupancy, array('class' => 'form-control disabled-field','id'=>'otherOccupancy')) }}
						</div>
						<span class="validate-err" id="err_no_of_units"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div  class="accordion accordion-flush">
		<div class="accordion-item">
			<h6 class="accordion-header" id="flush-headingfive">
				<button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
					<h6 class="sub-title accordiantitle">{{__("FIXTURES TO BE INSTALLED")}}</h6>
				</button>
			</h6>
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						{{ Form::label('qty', __('QTY.'),['class'=>'form-label bold']) }}
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						{{ Form::label('fixturetype', __('FIXTURES TYPE'),['class'=>'form-label bold']) }}
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						{{ Form::label('kindoffixtures', __('KINDS OF FIXTURES'),['class'=>'form-label bold']) }}
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						{{ Form::label('qty', __('QTY.'),['class'=>'form-label bold']) }}
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						{{ Form::label('fixturetype', __('FIXTURES TYPE'),['class'=>'form-label bold']) }}
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						{{ Form::label('kindoffixtures', __('KINDS OF FIXTURES'),['class'=>'form-label bold']) }}
					</div>
				</div>
			</div>
			@php  $Mixturetype =array('1'=>'New Fixtures','2'=>'Existing Fixtures'); @endphp
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_water_closet_qty',$sanitaryappdata->espa_water_closet_qty, array('class' => 'form-control numeric-double','id'=>'espa_water_closet_qty')) }}
							<span class="validate-err" id="err_es_id"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_water_closet_type',$Mixturetype,$sanitaryappdata->espa_water_closet_type, array('class' => 'form-control numeric-double','id'=>'espa_water_closet_type')) }}
							<span class="validate-err" id="err_es_id"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Water Closet'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_es_id"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_bidette_qty',$sanitaryappdata->espa_bidette_qty, array('class' => 'form-control numeric-only','id'=>'espa_bidette_qty')) }}  
							<span class="validate-err" id="espa_bidette_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_bidettet_type',$Mixturetype,$sanitaryappdata->espa_bidettet_type, array('class' => 'form-control ','id'=>'espa_bidettet_type')) }}
							<span class="validate-err" id="err_espa_bidettet_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Bidet'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_es_id"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_floor_drain_qty',$sanitaryappdata->espa_floor_drain_qty, array('class' => 'form-control numeric-only','id'=>'espa_floor_drain_qty')) }}  
							<span class="validate-err" id="err_es_id"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_floor_drain_type',$Mixturetype,$sanitaryappdata->espa_floor_drain_type, array('class' => 'form-control ','id'=>'espa_floor_drain_type')) }}
							<span class="validate-err" id="err_es_id"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Floor Drain'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_es_id"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_laundry_trays_qty',$sanitaryappdata->espa_laundry_trays_qty, array('class' => 'form-control numeric-only','id'=>'espa_laundry_trays_qty')) }}  
							<span class="validate-err" id="err_es_id"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_laundry_trays_type',$Mixturetype,$sanitaryappdata->espa_laundry_trays_type, array('class' => 'form-control ','id'=>'espa_laundry_trays_type')) }}
							<span class="validate-err" id="err_espa_laundry_trays_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Laundry Trays'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_lavatories_qty',$sanitaryappdata->espa_lavatories_qty, array('class' => 'form-control numeric-only','id'=>'espa_lavatories_qty')) }}  
							<span class="validate-err" id="err_espa_lavatories_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_lavatories_type',$Mixturetype,$sanitaryappdata->espa_lavatories_type, array('class' => 'form-control ','id'=>'espa_lavatories_type')) }}
							<span class="validate-err" id="err_espa_lavatories_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Lavatories'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_es_id"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_dental_cuspidor_qty',$sanitaryappdata->espa_dental_cuspidor_qty, array('class' => 'form-control numeric-only','id'=>'espa_dental_cuspidor_qty')) }}  
							<span class="validate-err" id="err_espa_dental_cuspidor_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_dental_cuspidor_type',$Mixturetype,$sanitaryappdata->espa_dental_cuspidor_type, array('class' => 'form-control ','id'=>'espa_dental_cuspidor_type')) }}
							<span class="validate-err" id="err_espa_dental_cuspidor_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Dental Cuspidor'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_kitchen_sink_qty',$sanitaryappdata->espa_kitchen_sink_qty, array('class' => 'form-control numeric-only','id'=>'espa_kitchen_sink_qty')) }}  
							<span class="validate-err" id="err_espa_kitchen_sink_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_kitchen_sink_type',$Mixturetype,$sanitaryappdata->espa_kitchen_sink_type, array('class' => 'form-control ','id'=>'espa_kitchen_sink_type')) }}
							<span class="validate-err" id="err_espa_kitchen_sink_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Kitchen Sink'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_es_id"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_gas_heater_qty',$sanitaryappdata->espa_gas_heater_qty, array('class' => 'form-control numeric-only','id'=>'espa_gas_heater_qty')) }}  
							<span class="validate-err" id="err_espa_gas_heater_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_gas_heater_type',$Mixturetype,$sanitaryappdata->espa_gas_heater_type, array('class' => 'form-control ','id'=>'espa_gas_heater_type')) }}
							<span class="validate-err" id="err_espa_gas_heater_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Gas Heater'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_faucet_qty',$sanitaryappdata->espa_faucet_qty, array('class' => 'form-control numeric-only','id'=>'espa_faucet_qty')) }}  
							<span class="validate-err" id="err_espa_faucet_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_faucet_type',$Mixturetype,$sanitaryappdata->espa_faucet_type, array('class' => 'form-control ','id'=>'espa_faucet_type')) }}
							<span class="validate-err" id="err_espa_faucet_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Faucet'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_es_id"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_electric_heater_qty',$sanitaryappdata->espa_electric_heater_qty, array('class' => 'form-control numeric-only','id'=>'espa_electric_heater_qty')) }}  
							<span class="validate-err" id="err_espa_electric_heater_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_electric_heater_type',$Mixturetype,$sanitaryappdata->espa_electric_heater_type, array('class' => 'form-control ','id'=>'espa_electric_heater_type')) }}
							<span class="validate-err" id="err_espa_electric_heater_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Electric Heater'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_es_id"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_shower_head_qty',$sanitaryappdata->espa_shower_head_qty, array('class' => 'form-control numeric-only','id'=>'espa_shower_head_qty')) }}  
							<span class="validate-err" id="err_espa_shower_head_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_shower_head_type',$Mixturetype,$sanitaryappdata->espa_shower_head_type, array('class' => 'form-control ','id'=>'espa_shower_head_type')) }}
							<span class="validate-err" id="err_espa_shower_head_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Shower Head'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_water_boiler_qty',$sanitaryappdata->espa_water_boiler_qty, array('class' => 'form-control numeric-only','id'=>'espa_water_boiler_qty')) }}  
							<span class="validate-err" id="err_espa_water_boiler_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_water_boiler_type',$Mixturetype,$sanitaryappdata->espa_water_boiler_type, array('class' => 'form-control ','id'=>'espa_water_boiler_type')) }}
							<span class="validate-err" id="err_espa_water_boiler_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Water Boiler'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_water_meter_qty',$sanitaryappdata->espa_water_meter_qty, array('class' => 'form-control numeric-only','id'=>'espa_water_meter_qty')) }}  
							<span class="validate-err" id="err_espa_water_meter_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_water_meter_type',$Mixturetype,$sanitaryappdata->espa_water_meter_type, array('class' => 'form-control ','id'=>'espa_water_meter_type')) }}
							<span class="validate-err" id="err_espa_water_meter_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Water Meter'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_drinking_fountain_qty',$sanitaryappdata->espa_drinking_fountain_qty, array('class' => 'form-control numeric-only','id'=>'espa_drinking_fountain_qty')) }}  
							<span class="validate-err" id="err_espa_drinking_fountain_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_drinking_fountain_type',$Mixturetype,$sanitaryappdata->espa_drinking_fountain_type, array('class' => 'form-control ','id'=>'espa_drinking_fountain_type')) }}
							<span class="validate-err" id="err_espa_drinking_fountain_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Drinking Fountain'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_grease_trap_qty',$sanitaryappdata->espa_grease_trap_qty, array('class' => 'form-control numeric-only','id'=>'espa_grease_trap_qty')) }}  
							<span class="validate-err" id="err_espa_grease_trap_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_grease_trap_type',$Mixturetype,$sanitaryappdata->espa_grease_trap_type, array('class' => 'form-control ','id'=>'espa_grease_trap_type')) }}
							<span class="validate-err" id="err_espa_grease_trap_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Grease Trap'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_bar_sink_qty',$sanitaryappdata->espa_bar_sink_qty, array('class' => 'form-control numeric-only','id'=>'espa_bar_sink_qty')) }}  
							<span class="validate-err" id="err_espa_bar_sink_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_bar_sink_type',$Mixturetype,$sanitaryappdata->espa_bar_sink_type, array('class' => 'form-control ','id'=>'espa_bar_sink_type')) }}
							<span class="validate-err" id="err_espa_bar_sink_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Bar Sink'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_bath_tubs_qty',$sanitaryappdata->espa_bath_tubs_qty, array('class' => 'form-control numeric-only','id'=>'espa_bath_tubs_qty')) }}  
							<span class="validate-err" id="err_espa_bath_tubs_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_bath_tubs_type',$Mixturetype,$sanitaryappdata->espa_bath_tubs_type, array('class' => 'form-control ','id'=>'espa_bath_tubs_type')) }}
							<span class="validate-err" id="err_espa_bath_tubs_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Bath Tubs'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_soda_fountain_qty',$sanitaryappdata->espa_soda_fountain_qty, array('class' => 'form-control numeric-only','id'=>'espa_soda_fountain_qty')) }}  
							<span class="validate-err" id="err_espa_soda_fountain_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_soda_fountain_type',$Mixturetype,$sanitaryappdata->espa_soda_fountain_type, array('class' => 'form-control ','id'=>'espa_soda_fountain_type')) }}
							<span class="validate-err" id="err_espa_soda_fountain_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Soda Fountain Sink'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_slop_sink_qty',$sanitaryappdata->espa_slop_sink_qty, array('class' => 'form-control numeric-only','id'=>'espa_slop_sink_qty')) }}  
							<span class="validate-err" id="err_espa_slop_sink_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_slop_sink_type',$Mixturetype,$sanitaryappdata->espa_slop_sink_type, array('class' => 'form-control ','id'=>'espa_slop_sink_type')) }}
							<span class="validate-err" id="err_espa_slop_sink_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Slop sink'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_laboratory_qty',$sanitaryappdata->espa_laboratory_qty, array('class' => 'form-control numeric-only','id'=>'espa_laboratory_qty')) }}  
							<span class="validate-err" id="err_espa_laboratory_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_laboratory_type',$Mixturetype,$sanitaryappdata->espa_laboratory_type, array('class' => 'form-control ','id'=>'espa_laboratory_type')) }}
							<span class="validate-err" id="err_espa_laboratory_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Laboratory Sink'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_urinal_qty',$sanitaryappdata->espa_urinal_qty, array('class' => 'form-control numeric-only','id'=>'espa_urinal_qty')) }}  
							<span class="validate-err" id="err_espa_urinal_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_urinal_type',$Mixturetype,$sanitaryappdata->espa_urinal_type, array('class' => 'form-control ','id'=>'espa_urinal_type')) }}
							<span class="validate-err" id="err_espa_urinal_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Urinal'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_sterilizer_qty',$sanitaryappdata->espa_sterilizer_qty, array('class' => 'form-control numeric-only','id'=>'espa_sterilizer_qty')) }}  
							<span class="validate-err" id="err_espa_soda_fountain_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_sterilizer_type',$Mixturetype,$sanitaryappdata->espa_sterilizer_type, array('class' => 'form-control ','id'=>'espa_sterilizer_type')) }}
							<span class="validate-err" id="err_espa_sterilizer_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Sterilize'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_airconditioning_unit_qty',$sanitaryappdata->espa_airconditioning_unit_qty, array('class' => 'form-control numeric-only','id'=>'espa_airconditioning_unit_qty')) }}  
							<span class="validate-err" id="err_espa_airconditioning_unit_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_airconditioning_unit_type',$Mixturetype,$sanitaryappdata->espa_airconditioning_unit_type, array('class' => 'form-control ','id'=>'espa_airconditioning_unit_type')) }}
							<span class="validate-err" id="err_espa_airconditioning_unit_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Air Conditioning Unit'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_swimmingpool_qty',$sanitaryappdata->espa_swimmingpool_qty, array('class' => 'form-control numeric-only','id'=>'espa_swimmingpool_qty')) }}  
							<span class="validate-err" id="err_espa_swimmingpool_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_swimmingpool_type',$Mixturetype,$sanitaryappdata->espa_swimmingpool_type, array('class' => 'form-control ','id'=>'espa_swimmingpool_type')) }}
							<span class="validate-err" id="err_espa_swimmingpool_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Swimming Pool'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_water_tank_qty',$sanitaryappdata->espa_water_tank_qty, array('class' => 'form-control numeric-only','id'=>'espa_water_tank_qty')) }}  
							<span class="validate-err" id="err_espa_water_tank_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_water_tank_type',$Mixturetype,$sanitaryappdata->espa_water_tank_type, array('class' => 'form-control ','id'=>'espa_water_tank_type')) }}
							<span class="validate-err" id="err_espa_water_tank_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Water Tank Reservoir'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::text('espa_others_qty',$sanitaryappdata->espa_others_qty, array('class' => 'form-control numeric-only','id'=>'espa_others_qty')) }}  
							<span class="validate-err" id="err_espa_others_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::select('espa_others_type',$Mixturetype,$sanitaryappdata->espa_others_type, array('class' => 'form-control ','id'=>'espa_others_type')) }}
							<span class="validate-err" id="err_espa_others_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<div class="form-icon-user">
							{{ Form::label('qty', __('Other Specify'),['class'=>'form-label']) }}
							<span class="validate-err" id="err_Laundray"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row"> {{ Form::label('watersupp', __('Water Supply *'),['class'=>'form-label bold']) }}
			</div>
			<div class="row">
				@foreach($waterSupplyarray as  $key => $val)
				<div class="col-md-3">
					<div class="form-check form-check-inline form-group col-md-12">
						{{ Form::radio('ewst_id', $key,($sanitaryappdata->ewst_id == $key)?true:false, array('id'=>'ewst_id'.$key,'class'=>'form-check-input code')) }}
						{{ Form::label('ewst_id'.$key, __($val),['class'=>'form-label']) }}
					</div>
				</div>  
				@endforeach
				<span class="validate-err" id="err_ewst_id"></span>
			</div>
			<div class="row"> {{ Form::label('sisposal', __('System of Disposal *'),['class'=>'form-label bold']) }}
			</div>
			<div class="row">
				@foreach($disposalarray as  $key => $val)
				<div class="col-md-3">
					<div class="form-check form-check-inline form-group col-md-12">
						{{ Form::radio('edst_id', $key,($sanitaryappdata->edst_id == $key)?true:false, array('id'=>'edst_id'.$key,'class'=>'form-check-input code')) }}
						{{ Form::label('edst_id'.$key, __($val),['class'=>'form-label']) }}
					</div>
				</div>  
				@endforeach
				<span class="validate-err" id="err_edst_id"></span>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						{{ Form::label('espa_no_of_storey', __('Number of Storeys Building'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
						<span class="validate-err">{{ $errors->first('ownerlastname') }}</span>
						<div class="form-icon-user">
							{{ Form::text('espa_no_of_storey',$sanitaryappdata->espa_no_of_storey, array('class' => 'form-control espa_no_of_storey numeric-only','id'=>'espa_no_of_storey','required'=>'required')) }}
						</div>
						<span class="validate-err" id="err_espa_no_of_storey"></span>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						{{ Form::label('espa_floor_area', __('Total No. of Building / Subdivision'),['class'=>'form-label']) }}<span class="text-danger">*</span>
						<span class="validate-err">{{ $errors->first('espa_floor_area') }}</span>
						<div class="form-icon-user">
							{{ Form::text('espa_floor_area',$sanitaryappdata->espa_floor_area, array('class' => 'form-control espa_floor_area','id'=>'espa_floor_area','required'=>'required')) }}
						</div>
						<span class="validate-err" id="err_espa_floor_area"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						{{ Form::label('espa_installation_date', __('Proposed Date Start of Installation'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
						<span class="validate-err">{{ $errors->first('espa_installation_date') }}</span>
						<div class="form-icon-user">
							{{ Form::date('espa_installation_date',$sanitaryappdata->espa_installation_date, array('class' => 'form-control espa_installation_date numeric-only','id'=>'espa_installation_date','required'=>'required')) }}
						</div>
						<span class="validate-err" id="err_espa_installation_date"></span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						{{ Form::label('espa_installation_cost', __('Total Cost of Installation'),['class'=>'form-label']) }}<span class="text-danger">*</span>
						<span class="validate-err">{{ $errors->first('espa_installation_cost') }}</span>
						<div class="form-icon-user">
							{{ Form::number('espa_installation_cost',number_format($sanitaryappdata->espa_installation_cost,2), array('class' => 'form-control espa_installation_cost','step'=>'any','id'=>'espa_installation_cost','required'=>'required')) }}
						</div>
						<span class="validate-err" id="err_espa_installation_cost"></span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						{{ Form::label('espa_completion_date', __('Expected Date of Completation'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
						<span class="validate-err">{{ $errors->first('espa_completion_date') }}</span>
						<div class="form-icon-user">
							{{ Form::date('espa_completion_date',$sanitaryappdata->espa_completion_date, array('class' => 'form-control espa_completion_date numeric-only','id'=>'espa_completion_date','required'=>'required')) }}
						</div>
						<span class="validate-err" id="err_espa_completion_date"></span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group" id="PreparedBy">
						{{ Form::label('espa_preparedby', __('Prepared By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
						<span class="validate-err">{{ $errors->first('espa_preparedby') }}</span>
						<div class="form-icon-user">
							{{ Form::select('espa_preparedby',$hremployees,$sanitaryappdata->espa_preparedby, array('class' => 'form-control espa_preparedby','id'=>'espa_preparedby','required'=>'required')) }}
						</div>
						<span class="validate-err" id="err_espa_preparedby"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div  class="accordion accordion-flush">
		<div class="accordion-item">
			<h6 class="accordion-header" id="flush-headingfive">
				<button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
					<h6 class="sub-title accordiantitle">{{__("Building Official")}}</h6>
				</button>
			</h6>
			 <div class="row">
				<div class="col-md-6">
					<div class="form-group">
						{{ Form::label('espa_building_official', __('Building Official Full Name'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
						<span class="validate-err">{{ $errors->first('espa_building_official') }}</span>
						<div class="form-icon-user">
							{{ Form::select('espa_building_official',$buildofficial,$sanitaryappdata->espa_building_official, array('class' => 'form-control','id'=>'espa_building_official','required'=>'required')) }}
						</div>
						<span class="validate-err" id="err_espa_building_official"></span>
					</div>
				</div>
			</div>
		</div>
	 </div>
	<div class="modal-footer">
	<!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
		<a href="#" data-dismiss="modal" class="btn closeSanitaryModal" mid=""  type="edit">Close</a>
		<a  class="btn btn-primary nextpageModal" id="nextpage">Next</a> 
		<!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
	</div>
</div>
<div id="page2" style="display:none;">
   <div class="row">
	 <div class="col-lg-12">
		   <div  class="accordion accordion-flush">
			<div class="accordion-item">
				<h6 class="accordion-header" id="flush-headingfive">
					<button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
						<h6 class="sub-title accordiantitle">{{__("Assessed Fees")}}</h6>
					</button>
				</h6>
				 <div class="row">
					<div class="col-md-3">
						<div class="form-group">
							{{ Form::label('espa_amount_due', __('Amount Due'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
							<span class="validate-err">{{ $errors->first('ebfd_no_of_storey') }}</span>
							<div class="form-icon-user">
								{{ Form::text('espa_amount_due',$sanitaryappdata->espa_amount_due, array('class' => 'form-control numeric-only','id'=>'espa_amount_due','required'=>'required')) }}
							</div>
							<span class="validate-err" id="err_espa_amount_due"></span>
						  </div>
					</div>
					  <div class="col-md-3">
						<div class="form-group">
							{{ Form::label('espa_assessed_by', __('Assessed By'),['class'=>'form-label bold']) }}<span class="text-danger">*</span>
							<span class="validate-err">{{ $errors->first('espa_assessed_by') }}</span>
							<div class="form-icon-user">
								{{ Form::select('espa_assessed_by',$hremployees,$sanitaryappdata->espa_assessed_by, array('class' => 'form-control','id'=>'espa_assessed_by')) }}
							</div>
							<span class="validate-err" id="err_espa_assessed_by"></span>
						  </div>
					</div>
					 <div class="col-md-3">
						<div class="form-group">
							{{ Form::label('espa_or_no', __('O.R. Number'),['class'=>'form-label']) }}
							<span class="validate-err">{{ $errors->first('espa_or_no') }}</span>
							<div class="form-icon-user">
								{{ Form::text('espa_or_no',$sanitaryappdata->espa_or_no, array('class' => 'form-control ','id'=>'espa_or_no')) }}
							</div>
							<span class="validate-err" id="err_espa_or_no"></span>
						</div>
					   </div>
					   <div class="col-md-3">
						<div class="form-group">
							{{ Form::label('espa_date_paid', __('Paid Date'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('espa_date_paid') }}</span>
							<div class="form-icon-user">
								{{ Form::date('espa_date_paid',$sanitaryappdata->espa_date_paid, array('class' => 'form-control','id'=>'espa_date_paid')) }}
							</div>
							<span class="validate-err" id="err_espa_date_paid"></span>
						  </div>
					   </div>
				</div>
				 
			   </div>
		   </div>
		 </div>
	 </div>
	  <div class="row">
		 <div class="col-sm-6">
		   <div  class="accordion accordion-flush">
			<div class="accordion-item">
				<h6 class="accordion-header" id="flush-headingfive">
					<button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive" style="padding-right:0px;">
						<!-- <h6 class="sub-title accordiantitle">{{__("SANITARY ENGINEER/MASTER PLUMBING")}}</h6> -->
						<div class="row" style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                        <div class="col-md-11">
                                            <h6 class="sub-title accordiantitle " style="padding-top: 12px;">{{__("SANITARY ENGINEER/MASTER PLUMBING")}}</h6>
                                        </div>
                                        <div class="col-md-1" >
                                        	<a href="{{ url('/eng/consultantexternal') }}" target="_blank">
                                             <span class="btn_electricalrevision btn btn-primary hide" id="btn_electricalrevision2" style="color:white;float: right;padding: 5px;"><i class="ti-plus"></i></span>
                                           </a>
                                         </div>
                                        </div>
					</button>
				</h6>
				 <div class="row">
					<div class="col-md-4">
						<div class="form-group">
							{{ Form::label('espa_sign_category', __('Consultant Category'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('espa_sign_category') }}</span>
							<div class="form-icon-user">
								{{ Form::select('espa_sign_category',array(''=>'Please Select','1'=>'Employee','2'=>'External Consultant'),$sanitaryappdata->espa_sign_category, array('class' => 'form-control numeric-only','id'=>'espa_sign_category')) }}
							<span class="validate-err" id="err_tfoc_id"></span>
						  </div>
					   </div>
				   </div>
					 <div class="col-md-8">
						<div class="form-group">
							{{ Form::label('espa_sign_consultant_id', __('Name'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('espa_sign_consultant_id') }}</span>
							<div class="form-icon-user">
								{{ Form::select('espa_sign_consultant_id',$signdropdown,$sanitaryappdata->espa_sign_consultant_id, array('class' => 'form-control','id'=>'espa_sign_consultant_id')) }}
							<span class="validate-err" id="err_espa_sign_consultant_id"></span>
						  </div>
					   </div>
				   </div>
			   </div>
			   <div class="row">
					<div class="col-md-12">
						<div class="form-group">
							{{ Form::label('address', __('Address'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('validity') }}</span>
							<div class="form-icon-user">
								{{ Form::text('signaddress',$sanitaryappdata->signaddress, array('class' => 'form-control','id'=>'signaddress')) }}
							<span class="validate-err" id="err_tfoc_id"></span>
						  </div>
					   </div>
				   </div>
			   </div>
			   <div class="row">
					<div class="col-md-6">
						<div class="form-group">
							{{ Form::label('ebfd_sign_prc_reg_no', __('PRC No.'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('ebfd_sign_prc_reg_no') }}</span>
							<div class="form-icon-user">
								{{ Form::text('signprcno',$sanitaryappdata->signprcno, array('class' => 'form-control numeric-only','id'=>'signprcno')) }}
							<span class="validate-err" id="err_tfoc_id"></span>
						  </div>
					   </div>
				   </div>
				   <div class="col-md-6">
						<div class="form-group">
							{{ Form::label('validity', __('Validity'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('validity') }}</span>
							<div class="form-icon-user">
								{{ Form::date('signvalidity',$sanitaryappdata->signvalidity, array('class' => 'form-control','id'=>'signvalidity')) }}
							<span class="validate-err" id="err_tfoc_id"></span>
						  </div>
					   </div>
				   </div>
			   </div>
				<div class="row">
				   <div class="col-md-6">
						<div class="form-group">
							{{ Form::label('ebfd_sign_ptr_no', __('PTR No.'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
							<div class="form-icon-user">
								{{ Form::text('signptrno',$sanitaryappdata->signptrno, array('class' => 'form-control numeric-only','id'=>'signebfd_sign_ptr_no')) }}
							<span class="validate-err" id="err_tfoc_id"></span>
						  </div>
					   </div>
				   </div>
					<div class="col-md-6">
						<div class="form-group">
							{{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
							<div class="form-icon-user">
								{{ Form::date('signdateissued',$sanitaryappdata->signdateissued, array('class' => 'form-control','id'=>'signdateissued')) }}
							<span class="validate-err" id="err_tfoc_id"></span>
						  </div>
					   </div>
				   </div>
			   </div>
				<div class="row">
				   <div class="col-md-6">
						<div class="form-group">
							{{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('placeissued') }}</span>
							<div class="form-icon-user">
								{{ Form::text('signplaceissued',$sanitaryappdata->signplaceissued, array('class' => 'form-control','id'=>'signplaceissued')) }}
							<span class="validate-err" id="err_tin"></span>
						  </div>
					   </div>
				   </div>
					<div class="col-md-6">
						<div class="form-group">
							{{ Form::label('tin', __('TIN.'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
							<div class="form-icon-user">
								{{ Form::text('signtin',$sanitaryappdata->signtin, array('class' => 'form-control ','id'=>'signtin')) }}
							<span class="validate-err" id="err_tin"></span>
						  </div>
					   </div>
				   </div>
			   </div>
		   </div>
		</div>
	 </div>
	 <div class="col-sm-6">
		   <div  class="accordion accordion-flush">
			<div class="accordion-item">
				<h6 class="accordion-header" id="flush-headingfive">
					<button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive" style="padding-right:0px;">
						<!-- <h6 class="sub-title accordiantitle">{{__("SANITARY ENGINEER/MASTER PLUMBING")}}</h6> -->
						<div class="row" style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                        <div class="col-md-11">
                                            <h6 class="sub-title accordiantitle " style="padding-top: 12px;">{{__("SANITARY ENGINEER/MASTER PLUMBING")}}</h6>
                                        </div>
                                        <div class="col-md-1" >
                                        	<a href="{{ url('/eng/consultantexternal') }}" target="_blank">
                                             <span class="btn_electricalrevision btn btn-primary hide" id="btn_electricalrevision" style="color:white;float: right;padding: 5px;"><i class="ti-plus"></i></span>
                                           </a>
                                         </div>
                                        </div>
					</button>
				</h6>
				 <div class="row">
					<div class="col-md-4">
						<div class="form-group">
							{{ Form::label('espa_incharge_category', __('Consultant Category'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('espa_incharge_category') }}</span>
							<div class="form-icon-user">
								{{ Form::select('espa_incharge_category',array(''=>'Please Select','1'=>'Employee','2'=>'External Consultant'),$sanitaryappdata->espa_incharge_category, array('class' => 'form-control numeric-only','id'=>'espa_incharge_category')) }}
							<span class="validate-err" id="err_tfoc_id"></span>
						  </div>
					   </div>
				   </div>
				   
					 <div class="col-md-8">
						<div class="form-group">
							{{ Form::label('espa_incharge_consultant_id', __('Name'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('espa_incharge_consultant_id') }}</span>
							<div class="form-icon-user">
								{{ Form::select('espa_incharge_consultant_id',$inchargedropdown,$sanitaryappdata->espa_incharge_consultant_id, array('class' => 'form-control','id'=>'espa_incharge_consultant_id')) }}
							<span class="validate-err" id="err_espa_incharge_consultant_id"></span>
						  </div>
					   </div>
				   </div>
			   </div>
			   <div class="row">
					<div class="col-md-12">
						<div class="form-group">
							{{ Form::label('address', __('Address'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('validity') }}</span>
							<div class="form-icon-user">
								{{ Form::text('inchargenaddress',$sanitaryappdata->inchargenaddress, array('class' => 'form-control','id'=>'inchargenaddress')) }}
							<span class="validate-err" id="err_tfoc_id"></span>
						  </div>
					   </div>
				   </div>
			   </div>
			   <div class="row">
					<div class="col-md-6">
						<div class="form-group">
							{{ Form::label('ebfd_sign_prc_reg_no', __('PRC No.'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('ebfd_sign_prc_reg_no') }}</span>
							<div class="form-icon-user">
								{{ Form::text('inchargeprcregno',$sanitaryappdata->inchargeprcregno, array('class' => 'form-control numeric-only','id'=>'inchargeprcregno')) }}
							<span class="validate-err" id="err_tfoc_id"></span>
						  </div>
					   </div>
				   </div>
				   <div class="col-md-6">
						<div class="form-group">
							{{ Form::label('validity', __('Validity'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('validity') }}</span>
							<div class="form-icon-user">
								{{ Form::date('inchargevalidity',$sanitaryappdata->inchargevalidity, array('class' => 'form-control','id'=>'inchargevalidity')) }}
							<span class="validate-err" id="err_tfoc_id"></span>
						  </div>
					   </div>
				   </div>
			   </div>
				<div class="row">
				   <div class="col-md-6">
						<div class="form-group">
							{{ Form::label('ebfd_sign_ptr_no', __('PTR No.'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
							<div class="form-icon-user">
								{{ Form::text('inchargeptrno',$sanitaryappdata->inchargeptrno, array('class' => 'form-control','id'=>'inchargeebfd_sign_ptr_no')) }}
							<span class="validate-err" id="err_tfoc_id"></span>
						  </div>
					   </div>
				   </div>
					<div class="col-md-6">
						<div class="form-group">
							{{ Form::label('dateissued', __('Date Issued'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
							<div class="form-icon-user">
								{{ Form::date('inchargedateissued',$sanitaryappdata->inchargedateissued, array('class' => 'form-control','id'=>'inchargedateissued')) }}
							<span class="validate-err" id="err_tfoc_id"></span>
						  </div>
					   </div>
				   </div>
			   </div>
				<div class="row">
				   <div class="col-md-6">
						<div class="form-group">
							{{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('placeissued') }}</span>
							<div class="form-icon-user">
								{{ Form::text('inchargeplaceissued',$sanitaryappdata->inchargeplaceissued, array('class' => 'form-control','id'=>'inchargeplaceissued')) }}
							<span class="validate-err" id="err_tin"></span>
						  </div>
					   </div>
				   </div>
					<div class="col-md-6">
						<div class="form-group">
							{{ Form::label('tin', __('TIN.'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('ebfd_sign_ptr_no') }}</span>
							<div class="form-icon-user">
								{{ Form::text('inchargetin',$sanitaryappdata->inchargetin, array('class' => 'form-control','id'=>'inchargetin')) }}
							<span class="validate-err" id="err_tin"></span>
						  </div>
					   </div>
				   </div>
			   </div>
		   </div>
		</div>
	 </div>
	</div>
	 <div class="col-sm-12">
		   <div  class="accordion accordion-flush">
			<div class="accordion-item">
				<h6 class="accordion-header" id="flush-headingfive">
					<button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
						<h6 class="sub-title accordiantitle">{{__("Applicant")}}</h6>
					</button>
				</h6>
				 <div class="row">
				   <div class="col-md-6">
					   <div class="form-group">
							{{ Form::label('espa_applicant_consultant_id', __('Full Name'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('espa_applicant_consultant_id') }}</span>
							<div class="form-icon-user">
								{{ Form::select('espa_applicant_consultant_id',$arrlotOwner,$sanitaryappdata->espa_applicant_consultant_id,array('class' => 'form-control numeric-only','id'=>'espa_applicant_consultant_id')) }}
							<span class="validate-err" id="err_espa_applicant_consultant_id"></span>
						  </div>
					   </div>
				   </div>
					<div class="col-md-3">
					   <div class="form-group">
							{{ Form::label('regcertno', __('Reg. Cert. No.'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('regcertno') }}</span>
							<div class="form-icon-user">
								{{ Form::text('rescertno',$sanitaryappdata->rescertno, array('class' => 'form-control numeric-only','id'=>'applicant_comtaxcert')) }}
							<span class="validate-err" id="err_regcertno"></span>
						  </div>
					   </div>
				   </div>
				   <div class="col-md-3">
						<div class="form-group">
							{{ Form::label('ebfd_applicant_date_issued', __('Date Issued'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('ebfd_applicant_date_issued') }}</span>
							<div class="form-icon-user">
								{{ Form::date('dateissued',$sanitaryappdata->dateissued, array('class' => 'form-control','id'=>'applicant_date_issued')) }}
							<span class="validate-err" id="err_dateissued"></span>
						  </div>
					   </div>
				   </div>
				   <div class="col-md-3">
						<div class="form-group">
							{{ Form::label('placeissued', __('Place Issued'),['class'=>'form-label bold']) }}
							<span class="validate-err">{{ $errors->first('placeissued') }}</span>
							<div class="form-icon-user">
								{{ Form::text('placeissued',$sanitaryappdata->placeissued, array('class' => 'form-control','id'=>'applicant_place_issued')) }}
							<span class="validate-err" id="err_placeissued"></span>
						  </div>
					   </div>
				   </div>
			   </div>
		</div>
		</div>
	 </div>
	  <div class="modal-footer">
	<!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
	<a href="#" data-dismiss="modal" class="btn closeSanitaryModal" mid=""  type="edit">Close</a>
	 <a  class="btn btn-primary previouspageModal" id="previouspageModal">Previous</a> 
	<button class="btn btn-primary" id="saveServiceDetails">Save Changes</button>
	<!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
	</div>
</div>
</div>

{{Form::close()}}
<script type="text/javascript">
 $(document).ready(function(){
	$("#ebs_id").select3({ dropdownAutoWidth: false });
	$("#ebot_id").select3({ dropdownAutoWidth: false });
	$("#espa_sign_category").select3({ dropdownAutoWidth: false });
	$("#espa_incharge_category").select3({ dropdownAutoWidth: false });

 });
</script>
<script>
                    var elements = document.querySelectorAll('.accordiantitle');
                    elements.forEach(function(element) {
                        element.textContent = element.textContent.toLowerCase().replace(/\b./g, function(m) {
                            return m.toUpperCase();
                        });
                    });
                </script>