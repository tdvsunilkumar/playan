{{ Form::open(array('url' => 'social-welfare/assistance-type-requirements','class'=>'formDtls')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <div class="modal-body" style="overflow-x: hidden;">
        <div class="row">
            <div class="col-md-6">
				<div class="form-group m-form__group required">
					{{ Form::label('wsat_id', 'Assistance Type', ['class' => '']) }}<span class="text-danger">*</span>
					{{
						Form::select('wsat_id', $wsat_id, $data->wsat_id, ['id' => 'wsat_id', 'class' => 'form-control select select3', 'data-placeholder' => 'Please select'])
					}}
					<span class="validate-err" id="err_wsat_id"></span>
				</div>
            </div>   
			<div class="col-md-6">
				<div class="form-group m-form__group required">
					{{ Form::label('wsr_id', 'Assistance Requirements', ['class' => '']) }}<span class="text-danger">*</span>
					{{
						Form::select('wsr_id', $wsr_id, $data->wsr_id, ['id' => 'wsr_id', 'class' => 'form-control select select3', 'data-placeholder' => 'Please select'])
					}}
					<span class="validate-err" id="err_wsr_id"></span>
				</div>
            </div>			
        </div> 
       
        <div class="modal-footer" style="margin-bottom:150px;">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
			<div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation_confir_mass.js') }}"></script>
  