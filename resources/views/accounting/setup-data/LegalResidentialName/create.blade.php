{{ Form::open(array('url' => 'residential-name','class'=>'formDtls')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<div class="modal-body" style="overflow-x: hidden;">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group" id="div_office_barangay">
				{{ Form::label('barangay_id', __('Barangay'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('barangay_id') }}</span>
				<div class="form-icon-user">
					{{ Form::select('barangay_id',$barangay,$data->barangay_id,array('class'=>'form-control select3','id'=>'barangay_id')) }}
				</div>
				<span class="validate-err" id="err_barangay_id"></span>
			</div>
		</div>            
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('residential_name', __('Transaction Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('residential_name') }}</span>
				<div class="form-icon-user">
					{{ Form::select('residential_name',$arrTypeTrans,$data->residential_name,array('class'=>'form-control select3','id'=>'residential_name')) }}
				</div>
				<span class="validate-err" id="err_residential_name"></span>
			</div>
		</div>
	</div>
	
	<div class="modal-footer" style="margin-bottom:150px;">
		<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
		<div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
			<i class="fa fa-save icon"></i>
			<input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
		</div>
		<!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
	</div>
</div>    
{{Form::close()}}
 <script src="{{ asset('js/ajax_validation_confir_mass.js') }}"></script>  
 <script src="{{ asset('js/accounting/addLegalResidentialName.js') }}"></script>

  
 
           