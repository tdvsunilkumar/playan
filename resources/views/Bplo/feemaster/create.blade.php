{{ Form::open(array('url' => 'business-fee-master','class'=>'formDtls','id'=>'excavationgroundtype')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
 <style>
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
    .permitclick{  cursor:pointer;color:skyblue; }
    .orpayment > .row{ padding:10px; }
    .btn{padding: 0.575rem 0.5rem;}
    .accordion-button::after {
    background-image: url();
  }
 </style>
    <div class="modal-body">
        <div class="row">
            
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('fmaster_description', __('Fee Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('fmaster_description') }}</span>
                    <div class="form-icon-user">
                          {{Form::textarea('fmaster_description',$data->fmaster_description,array('class'=>'form-control','rows'=>'5'))}}
                    </div>
                    <span class="validate-err" id="err_fmaster_description"></span>
                </div>
            </div> 
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('fmaster_code', __('Account Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('fmaster_code') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('fmaster_code', $data->fmaster_code, array('class' => 'form-control','maxlength'=>'50')) }}
                    </div>
                    <span class="validate-err" id="err_fmaster_code"></span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('fmaster_shortname', __('Shortname'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('fmaster_shortname') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('fmaster_shortname', $data->fmaster_shortname, array('class' => 'form-control','maxlength'=>'50')) }}
                    </div>
                    <span class="validate-err" id="err_fmaster_shortname"></span>
                </div>
            </div>
            <div class="col-md-12">
                  <div class="row">
                    <div class="row field-requirement-details-status">
                        <div class="col-lg-1 col-md-1 col-sm-1">
                            {{Form::label('',__('No.'),['class'=>'form-label btn btn-primary'])}}
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            {{Form::label('amount',__('Sub-Details'),['class'=>'form-label btn btn-primary'])}}
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <span class="btn_addmore_checkboxes btn btn-primary" id="btn_addmore_checkboxes" style="color:white;"><i class="ti-plus"></i></span>
                        </div>
                   </div>
                 <span class="checkboxesdata activity-details" id="CheckboxDetails">
                       @php      
					   $checkboxes= array();
						if(!empty($data->fmaster_subdetails_json)){
						 $checkboxes  = json_decode($data->fmaster_subdetails_json);
						} 
						@endphp 
					    @php $i=1; @endphp
						@foreach($checkboxes AS $key=>$val)
						  @if($val->value)
							  <div class="removeCheckboxdata row pt10">
										<div class="col-lg-1 col-lg-1 col-lg-1">
											 <div class="form-group">
												<p style="text-align: center;" class="srno">{{$i}}</p>
											 </div>
										</div>
										<div class="col-lg-9 col-md-9 col-sm-9">
											<div class="form-group">
												<div class="form-icon-user">
												{{ Form::text('checkboxesdynamic[]',$val->value, array('class' => 'form-control naofbussi checkboxesdynamic','required'=>'required','id'=>'checkboxesdynamic')) }}
												</div>
											</div>
										</div>

										<div class="col-lg-2 col-lg-2 col-lg-2">
											 <button type="button" class="btn btn-danger btn_cancel_checkboxes" value=""><i class="ti-trash"></i></button>
										</div>
							</div>
							@endif
							@php $i++; @endphp
						@endforeach
                 </span>
            </div>
        </div> 
       
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<div id="hidenCheckboxHtml" class="hide">
    <div class="removeCheckboxdata row pt10">
         <div class="col-lg-1 col-lg-1 col-lg-1">
			 <div class="form-group">
				  <p style="text-align: center;" class="srno"></p>
			 </div>
		</div>
        <div class="col-lg-9 col-md-9 col-sm-9">
            <div class="form-group">
                <div class="form-icon-user">
                {{ Form::text('checkboxesdynamic[]','', array('class' => 'form-control naofbussi checkboxesdynamic','id'=>'checkboxesdynamic')) }}
                </div>
            </div>
        </div>
        <div class="col-sm-2">
             <button type="button" class="btn btn-danger btn_cancel_checkboxes" value=""><i class="ti-trash"></i></button>
        </div>
    </div>
</div>
<script src="{{ asset('js/feemasterajax_validation.js') }}"></script>
<script src="{{ asset('js/Bplo/add_feemaster.js') }}"></script>