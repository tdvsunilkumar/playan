{{ Form::open(array('url' => 'hr-overtime','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('hr_employeesid',$data->hr_employeesid, array('id' => 'id')) }}
{{ Form::hidden('submit_type',"", array('id' => 'submit_type')) }}
<style>
      .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: #fff;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{padding-top:10px;}
    .permitclick{  cursor:pointer;color:skyblue; }
    .orpayment > .row{ padding:10px; }
    .btn{padding: 0.575rem 0.5rem;}
    .accordion-button::after {
    background-image: url();
  }
    select[readonly].select3-hidden-accessible + .select3-container {
        pointer-events: none;
        touch-action: none;
    }

    select[readonly].select3-hidden-accessible + .select3-container .select3-selection {
        background: #eee;
        box-shadow: none;
    }

    select[readonly].select3-hidden-accessible + .select3-container .select3-selection__arrow, select[readonly].select3-hidden-accessible + .select3-container .select3-selection__clear {
        display: none;
    }
 </style>
<div class="modal-body">
	<div class="row">
		 <div class="col-md-4">
			<div class="form-group">
				{{ Form::label('date', __('Date Filed'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('date') }}</span>
				<div class="form-icon-user">
					{{ Form::date('date',$date, array('class' => 'form-control','id'=>'date','readonly')) }}
				</div>
				<span class="validate-err" id="date"></span>
			</div>
		</div>
		 <div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrot_application_no', __('Application No'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrot_application_no') }}</span>
				<div class="form-icon-user">
					{{ Form::text('hrot_application_no',$data->hrot_application_no, array('class' => 'form-control disabled-field','id'=>'hrot_application_no')) }}
				</div>
				<span class="validate-err" id="err_hrot_application_no"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				{{ Form::label('status', __('Status'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('status') }}</span>
				<div class="form-icon-user">
					{{ Form::text('status',$status, array('class' => 'form-control','id'=>'status','required'=>'required','readonly')) }}
				</div>
				<span class="validate-err" id="err_status"></span>
			</div>
		</div>
	</div>
	<div class="row">
		 <div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrot_work_date', __('Work Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrot_work_date') }}</span>
				<div class="form-icon-user">
					{{ Form::date('hrot_work_date',$data->hrot_work_date, array('class' => 'form-control','id'=>'hrot_work_date','readonly'))}}
				</div>
				<span class="validate-err" id="err_hrot_work_date"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrot_start_time', __('Start time'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrot_start_time') }}</span>
				<div class="form-icon-user">
					{{ Form::time('hrot_start_time',$data->hrot_start_time, array('class' => 'form-control ','id'=>'hrot_start_time','readonly')) }}
				</div>
				<span class="validate-err" id="err_hrot_start_time"></span>
			</div>
		</div>
	   <div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrot_end_time', __('End Time'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrot_end_time') }}</span>
				<div class="form-icon-user">
					{{ Form::time('hrot_end_time',$data->hrot_end_time, array('class' => 'form-control','id'=>'hrot_end_time','readonly')) }}
				</div>
				<span class="validate-err" id="err_hrot_end_time"></span>
			</div>
	   </div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrwc_id', __('Work Credit'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrwc_id') }}</span>
				<div class="form-icon-user">
					{{ Form::select('hrwc_id',$arrHrWorkCredit,$data->hrwc_id, array('class' => 'form-control select3','id'=>'hrwc_id','readonly')) }}
				</div>
				<span class="validate-err" id="err_hrwc_id"></span>
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrot_considered_hours', __('Considered Hours'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrot_considered_hours') }}</span>
				<div class="form-icon-user">
					{{ Form::text('hrot_considered_hours',$data->hrot_considered_hours, array('class' => 'form-control disabled-field','id'=>'hrot_considered_hours','readonly')) }}
				</div>
				<span class="validate-err" id="err_hrot_considered_hours"></span>
			</div>
		</div>
	   <div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrot_following_day', __('Following Day'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrot_following_day') }}</span>
				<div class="form-icon-user">
					{{ Form::select('hrot_following_day',$arrDaystype,$data->hrot_following_day, array('class' => 'form-control select3','id'=>'hrot_following_day','readonly')) }}
				</div>
				<span class="validate-err" id="err_hrot_following_day"></span>
			</div>
	   </div>
	</div>
	 <div class="row">
		<div class="col-md-12">
			<div class="form-group">
				{{ Form::label('hro_reason', __('Reason'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hro_reason') }}</span>
				<div class="form-icon-user">
					{{ Form::textarea('hro_reason',$data->hro_reason, array('class' => 'form-control','id'=>'hro_reason','rows'=>'2','readonly')) }}
				</div>
				<span class="validate-err" id="err_hro_reason"></span>
			</div>
	   </div>
	 </div>
	 <div class="row">
		<div class="col-md-12">
		  <div class="row field-requirement-details-status">
			<div class="col-lg-1 col-md-1 col-sm-1">
				{{Form::label('id',__('Id'),['class'=>'form-label'])}}
			</div>
			<div class="col-lg-9 col-md-9 col-sm-9">
				{{Form::label('filename',__('File Name'),['class'=>'form-label'])}}
			</div>
			<div class="col-lg-2 col-md-2 col-sm-2">
				<span class="btn_addmore_document btn btn-primary" id="btn_addmore_document" style="color:white;"><i class="ti-plus"></i></span>
			</div>
		</div>
		 <span class="documentsDetails activity-details" id="documentsDetails">
			 @php $i=1; @endphp
			@foreach($arrDocuments as $key=>$val)
			<div class="removedocumentsdata row pt10">
				<div class="col-lg-1 col-md-1 col-sm-1">
				  <div class="form-group"><div class="form-icon-user">
					<p class="serialnoclass" style="text-align:center;">{{$i}}</p>
					{{ Form::hidden('totalfiles[]','1', array('id' => 'totalfiles')) }}
					@if(!empty($val->id)) 
					{{ Form::hidden('fileid[]',$val->id, array('id' => 'fileid')) }}
					@endif
					</div>
				  </div>
				 </div>
				<div class="col-lg-9 col-md-9 col-sm-9">
					<div class="form-group">
						<div class="form-icon-user"><input class="form-control" name="documents[]" type="file" value="">
						</div>
				   </div>
				</div>
				@if($i>=0)
					<div class="col-lg-2 col-md-2 col-sm-2">
						 <div class="form-group">
							@if(!empty($val->fhow_file_name))
							<a class="btn" href="{{asset('uploads/')}}/{{$val->fhow_file_path}}/{{$val->fhow_file_name}}" target='_blank'><i class='ti-download'></i></a>
							@endif
						 <button type="button" class="btn btn-danger btn_cancel_documents"  value="{{$val->id}}"><i class="ti-trash"></i></button>
					   </div>
				 </div>
				@endif
				@php $i++; @endphp
			</div>
		@endforeach
		 </span>
	   </div>  
	</div>
	<div class="modal-footer">
	@if($data->id > 0)
		@if($approve_btn['sequence'])
		<div class="button">
			<button  type="button" name="submit" value="{{$data->id}}"  id="approve-btn" sequence="{{$approve_btn['sequence']}}" class="btn btn-primary approve-btn {{ ($data->hro_status === $approve_btn['status'])>0?__(''):__('disabled -field')}}">Approve</button>
		</div>
		@endif
		@if($data->hro_status !=2)
		<div class="button">
			 <button  type="button" name="submit" value="{{$data->id}}"  id="disapprove-btn" sequence="1" class="btn  btn-warning approve-btn {{ ($data->hro_status) == 2?__('disabled -field'):__('')}}">Disapprove</button>
		</div>
		@endif
	@endif 
	</div>
</div>    
{{Form::close()}}
<div id="hiddendocumentsHtml" class="hide">
    <div class="removedocumentsdata row pt10">
         <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="form-group">
                    <div class="form-icon-user">
                        <p class="serialnoclass" style="text-align:center;"></p>
                         {{ Form::hidden('totalfiles[]','1', array('id' => 'totalfiles')) }}
                    </div>
            </div>
          </div>
         <div class="col-lg-9 col-md-9 col-sm-9">
                <div class="form-group">
                    <div class="form-icon-user"><input class="form-control" name="documents[]" type="file" value="">
                    </div>
               </div>
            </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
                     <div class="form-group">
                       <div class="form-icon-user"><button type="button" class="btn btn-danger btn_cancel_documents"><i class="ti-trash"></i></button>
                       </div>
                   </div>
             </div>
    </div>
</div>
<script src="{{ asset('js/ajax_validation.js') }}"></script> 
<script src="{{ asset('js/HR/add_overtime.js') }}"></script>   

  
 
           