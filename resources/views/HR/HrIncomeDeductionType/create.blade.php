{{ Form::open(array('url' => 'hr-income-deduction-type','class'=>'formDtls')) }}
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
		<div class="col-md-3">
			<div class="form-group">
				{{ Form::label('hridt_description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<div class="form-icon-user">
					{{ Form::text('hridt_description',$data->hridt_description, array('class' => 'form-control','id'=>'hridt_description','required'=>'required')) }}
				</div>
				<span class="validate-err" id="err_hridt_description"></span>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				{{ Form::radio('hridt_type',
					0,
					false,
					array(
						'class' => 'form-check-input',
						'id'=>'hridt_type_deduct',
						'required'=>'required',
						$data->hridt_type === 0 ? 'checked' : '', 
						)) }}
				{{ Form::label('hridt_type_deduct', __('Deduction'),['class'=>'form-label']) }}

				{{ Form::radio('hridt_type',
					1,
					false, 
					array(
						'class' => 'form-check-input',
						'id'=>'hridt_type_income',
						'required'=>'required',
						$data->hridt_type === 1 ? 'checked' : '', 
						)) }}
				{{ Form::label('hridt_type_income', __('Income'),['class'=>'form-label']) }}
					</br>
				{{ Form::radio('hridt_type',
					2,
					false, 
					array(
						'class' => 'form-check-input',
						'id'=>'hridt_type_gov',
						'required'=>'required',
						$data->hridt_type === 2 ? 'checked' : '', 
						)) }}
				{{ Form::label('hridt_type_gov', __('Gov Share'),['class'=>'form-label']) }}

				{{ Form::radio('hridt_type',
					3,
					false, 
					array(
						'class' => 'form-check-input',
						'id'=>'hridt_type_ps',
						'required'=>'required',
						$data->hridt_type === 3 ? 'checked' : '', 
						)) }}
				{{ Form::label('hridt_type_ps', __('Gov Deduction'),['class'=>'form-label']) }}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('gl_id', __('GL Code [for Credit]'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<div class="form-icon-user" id="contain_gl_id">
					{{ Form::select('gl_id',
						$gl,
						$data->gl_id, 
						array(
							'class' => 'form-control ajax-select',
							'data-url' => 'general-ledgers/getGL',
							'id'=>'gl_id',
							)) }}
				</div>
				<span class="validate-err" id="err_sl_id"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('sl_id', __('SL Code [for Credit]'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<div class="form-icon-user" id="contain_sl_id">
					{{ Form::select('sl_id',
						$sl,
						$data->sl_id, 
						array(
							'class' => 'form-control ajax-select',
							'data-url' => 'subsidiary-ledgers/getSL/'.$data->gl_id,
							'id'=>'sl_id',
							)) }}
				</div>
				<span class="validate-err" id="err_sl_id"></span>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('gl_id_debit', __('GL Code [for Debit]'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<div class="form-icon-user" id="contain_gl_id_debit">
					{{ Form::select('gl_id_debit',
						$gl_debit,
						$data->gl_id_debit, 
						array(
							'class' => 'form-control ajax-select',
							'data-url' => 'general-ledgers/getGL',
							'id'=>'gl_id_debit',
							)) }}
				</div>
				<span class="validate-err" id="err_sl_id"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('sl_id_debit', __('SL Code [for Debit]'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<div class="form-icon-user" id="contain_sl_id_debit">
					{{ Form::select('sl_id_debit',
						$sl_debit,
						$data->sl_id_debit, 
						array(
							'class' => 'form-control ajax-select',
							'data-url' => 'subsidiary-ledgers/getSL/'.$data->gl_id_debit,
							'id'=>'sl_id_debit',
							)) }}
				</div>
				<span class="validate-err" id="err_sl_id"></span>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
		<div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
			<i class="fa fa-save icon"></i>
			<input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
		</div>
	</div>
</div>    
    {{Form::close()}}
<script src="{{ asset('js/partials/select-ajax.js?v='.filemtime(getcwd().'/js/partials/select-ajax.js').'') }}"></script>
 <script src="{{ asset('js/ajax_validation.js') }}"></script>  
<script>
$(document).ready(function(){
	$("#commonModal").find('.body').css({overflow: 'unset'})
	$('#contain_gl_id').on('change','#gl_id',(function(){
		console.log($(this).val())
		select3Ajax('sl_id','contain_sl_id','subsidiary-ledgers/getSL/'+$(this).val());
		$('#sl_id').empty()

	}))
	$('#contain_gl_id_debit').on('change','#gl_id_debit',(function(){
		console.log($(this).val())
		select3Ajax('sl_id_debit','contain_sl_id_debit','subsidiary-ledgers/getSL/'+$(this).val());
		$('#sl_id_debit').empty()

	}))
});
</script>
  
 
           