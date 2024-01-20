{{ Form::open(array('url' => 'type-of-transaction','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <style type="text/css">
    .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{padding-top:5px;}
</style> 
<div class="modal-body">
    <div class="row">
            <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('type_of_transaction', __('Type of Transaction'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <span class="validate-err">{{ $errors->first('type_of_transaction') }}</span>
                <div class="form-icon-user">
                    {{ Form::text('type_of_transaction',$data->type_of_transaction, array('class' => 'form-control','id'=>'type_of_transaction')) }}
                </div>
                <span class="validate-err" id="err_type_of_transaction"></span>
            </div>
        </div>
    </div>
    <div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				{{ Form::label('trigger_type', __('Triggers'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('trigger_type') }}</span>
				<div class="form-icon-user">
					{{ Form::select(
						'trigger_type',
						[
							'daily'=>'Daily',
							'monthly'=>'Monthly',
							'annually'=>'Annually',
						],
						$data->trigger_type,
						array(
							'class'=>'form-control select3',
							'id'=>'trigger_type',
						)
					) }}
				</div>
				<span class="validate-err" id="err_trigger_type"></span>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				{{ Form::label('trigger_count', __('Every'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('trigger_count') }}</span>
				<div class="form-icon-user">
					{{ Form::number(
						'trigger_count',
						$data->trigger_count,
						array(
							'class'=>'form-control',
							'id'=>'trigger_count',
							'min'=>1
						)
					) }}
				</div>
				<span class="validate-err" id="err_trigger_count"></span>
			</div>
		</div>
        <div class="col-sm-6">
			<div class="form-group">
				{{ Form::label('penalties', __('Penalty'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('penalties') }}</span>
				<div class="form-icon-user">
                    {{ Form::select(
						'penalties',
						$penalties,
						$data->penalties,
						array(
							'class'=>'form-control select3',
							'id'=>'penalties',
						)
					) }}
				</div>
				<span class="validate-err" id="err_penalties"></span>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				{{ Form::label('computation', __('Computation'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<p>
					<b>Legend: </b>([total_amount],[initial_monthly],[penalty],[month_terms],[monthly_pay],[remaining_amount])
				</p>
				<span class="validate-err">{{ $errors->first('computation') }}</span>
				<div class="form-icon-user">
					{{ Form::textarea(
						'computation',
						$data->computation,
						array(
							'class'=>'form-control',
							'id'=>'computation',
						)
					) }}
				</div>
				<span class="validate-err" id="err_computation"></span>
			</div>
		</div>
    </div>
    <div class="modal-footer">
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

  
 
           