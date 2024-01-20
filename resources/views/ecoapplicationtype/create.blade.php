<style>
.modal-content {
   position: absolute;
   float: left;
   margin-left: 50%;
   margin-top: 50%;
  transform: translate(-50%, -50%);
}
</style>
{{ Form::open(array('url' => 'ecoapplicationtype','class'=>'formDtls')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <div class="modal-body" style="margin-bottom:150px;">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group" id="barangay_idparrent">
                    {{ Form::label('barangay_id', __('Location / Reception'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('barangay_id') }}</span>
                    <div class="form-icon-user">
                         {{ Form::select('barangay_id',$location, $data->barangay_id, array('class' => 'form-control','id'=>'barangay_id')) }}
                    </div>
                    <span class="validate-err" id="err_barangay_id"></span>
                </div>
            </div>
			<!--<div class="col-md-6">
                <div class="form-group" id="ecs_idparrent">
                    {{ Form::label('est_service_type', __('Service Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('est_service_type') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('est_service_type',$data->est_service_type, array('class' =>'form-control','id'=>'est_service_type','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_est_service_type"></span>
                </div>
            </div>
				-->			
        </div> 
       <div class="row">
		<div class="col-md-10"></div>
		  <div class="col-md-2">
			 <div class="form-group">
				<span class="validate-err">{{ $errors->first('est_addtional_info') }}</span>
				<div class="form-icon-user" style="float:left;">
				   {{ Form::checkbox('est_addtional_info', 1,($data->est_addtional_info == 1)? 'true':'',array('class' => 'form-check-input est_addtional_infos' ,'id'=>'est_addtional_info')) }}	   
				</div>
				<div class="form-icon-user" style="float:right;">
				{{ Form::label('est_addtional_info', __('Additional info?'),['class'=>'form-label']) }}
				</div>
				<span class="validate-err" id="err_est_addtional_info"></span>
			 </div>
		  </div>
	   </div>
		<div  class="accordion accordion-flush AdditionalProcess" >
		  <div class="accordion">
			 <h6 class="accordion-header" >
				<button class="button  btn-primary" type="button" style="width: 100%;text-align: left;">
				   <h6 class="sub-title accordiantitle">{{__("Additional Process")}}</h6>
				</button>
			 </h6>	   
			 <div class="row" style="margin-top:20px;">
				<div class="col-md-10"></div>
				  <div class="col-md-2">
				   <div class="form-group">
						<span class="validate-err">{{ $errors->first('eatd_discount') }}</span>
						<div class="form-icon-user" style="float:left;">
							{{ Form::checkbox('eatd_discount',1,(isset($typedetails->eatd_discount) == 1)? 'true':'',array('class' => 'form-check-input','id'=>'eatd_discount')) }}	   
						</div>
						<div class="form-icon-user" style="float:right;">
							{{ Form::label('eatd_discount', __('20% Discount'),['class'=>'form-label']) }}
						</div>
						<span class="validate-err" id="err_eatd_discount"></span>
					</div>
				</div>
			   </div>
			   <div class="row">
					<div class="col-md-6">
						<div class="form-group" id="">
							{{ Form::hidden('est_id',(isset($typedetails->id))?$typedetails->id:'', array('id' => 'est_id')) }}
							{{ Form::label('eatd_process_type', __('Description Type'),['class'=>'form-label']) }}
							<span class="validate-err">{{ $errors->first('eatd_process_type') }}</span>
							<div class="form-icon-user">
								 {{ Form::text('eatd_process_type',(isset($typedetails->eatd_process_type))?$typedetails->eatd_process_type:'', array('class' => 'form-control','id'=>'eatd_process_type')) }}
							</div>
							<span class="validate-err" id="err_eatd_process_type"></span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group" id="ecs_idparrent">
							{{ Form::label('eatd_amount_type', __('Additional Amout / Default Amount'),['class'=>'form-label']) }}			
							<span class="validate-err">{{ $errors->first('eatd_amount_type') }}</span>
							<div class="form-icon-user currency">
								{{ Form::number('eatd_amount_type',(isset($typedetails->eatd_amount_type))?$typedetails->eatd_amount_type:'', array('class' =>'form-control','id'=>'eatd_amount_type')) }}
								<div class="currency-sign"><span>Php</span></div>
							</div>
							<span class="validate-err" id="err_eatd_amount_type"></span>
						</div>
					</div>			
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group" id="ecs_idparrent">
							{{ Form::label('multiplier', __('Multiplier'),['class'=>'form-label']) }}		
							<span class="validate-err">{{ $errors->first('multiplier') }}</span>
							<div class="form-icon-user">
								{{ Form::number('multiplier',(isset($typedetails->multiplier))?$typedetails->multiplier:'', array('class' =>'form-control','id'=>'multiplier')) }}
							</div>
							<span class="validate-err" id="err_multiplier"></span>
						</div>
					</div>	
					<div class="col-md-4">
						<div class="form-group" id="ecs_idparrent">
							{{ Form::label('excess', __('Excess'),['class'=>'form-label']) }}		
							<span class="validate-err">{{ $errors->first('excess') }}</span>
							<div class="form-icon-user">
								{{ Form::number('excess',(isset($typedetails->excess))?$typedetails->excess:'', array('class' =>'form-control','id'=>'excess')) }}
							</div>
							<span class="validate-err" id="err_multiplier"></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group" id="ecs_idparrent">
							{{ Form::label('excess_amount', __('Excess Amount'),['class'=>'form-label']) }}			
							<span class="validate-err">{{ $errors->first('excess_amount') }}</span>
							<div class="form-icon-user currency">
								{{ Form::number('excess_amount',(isset($typedetails->excess_amount))?$typedetails->excess_amount:'', array('class' =>'form-control','id'=>'excess_amount')) }}
								<div class="currency-sign"><span>Php</span></div>
							</div>
							<span class="validate-err" id="err_multiplier"></span>
						</div>
					</div>					
				</div>
				<div class="row">
					  <div class="col-md-2">
						 <div class="form-group">
							<span class="validate-err">{{ $errors->first('est_year_month') }}</span>
							<div class="form-icon-user">
							   {{ Form::radio('est_year_month', 1,($data->est_year_month == 1)? 'true':'',array('class' => 'form-check-input')) }}
								{{ Form::label('est_year_month', __('Year'),['class'=>'form-label']) }}							   
							</div>
							<span class="validate-err" id="err_est_year_month"></span>
						 </div>
					  </div>
					  <div class="col-md-2">
						 <div class="form-group">
							<span class="validate-err">{{ $errors->first('est_year_month') }}</span>
							<div class="form-icon-user">
							   {{ Form::radio('est_year_month', 2,($data->est_year_month == 2)? 'true':'',array('class' => 'form-check-input')) }}	
							   {{ Form::label('est_year_month', __('Month'),['class'=>'form-label']) }}
							</div>
							<span class="validate-err" id="err_est_year_month"></span>
						 </div>
					  </div>
					  <div class="col-md-8"></div>
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
<script src="{{ asset('js/ajax_validation_confir_mass.js') }}"></script>
<script src="{{ asset('js/add_ecoapplicationtype.js') }}"></script> 
  