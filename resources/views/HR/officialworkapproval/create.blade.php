{{ Form::open(array('url' => 'hr-official-work','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('hr_employeesid',$data->hr_employeesid, array('id' => 'id')) }}
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
 </style>
<div class="modal-body">
	<div class="row">
		 <div class="col-md-4">
			<div class="form-group">
				{{ Form::label('date', __('Applied Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('date') }}</span>
				<div class="form-icon-user">
					{{ Form::date('date',$date, array('class' => 'form-control','id'=>'date','readonly')) }}
				</div>
				<span class="validate-err" id="date"></span>
			</div>
		</div>
		 <div class="col-md-4">
			<div class="form-group">
				{{ Form::label('appno', __('Application No'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('appno') }}</span>
				<div class="form-icon-user">
					{{ Form::text('appno',$data->applicationno, array('class' => 'form-control disabled-field','id'=>'appno','readonly')) }}
				</div>
				<span class="validate-err" id="err_hrcos_start_date"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				{{ Form::label('status', __('Status'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('status') }}</span>
				<div class="form-icon-user">
					{{ Form::text('cos_status',
						$status, 
						array(
							'class' => 'form-control ',
							'id'=>'cos_status',
							'readonly'=>'readonly'
							)) }}
				</div>
				<span class="validate-err" id="err_hml_status"></span>
			</div>
		</div>
	</div>
	<div class="row">
		 <div class="col-md-6">
			<div class="form-group">
				{{ Form::label('hrow_work_date', __('Work Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrow_work_date') }}</span>
				<div class="form-icon-user">
					{{ Form::date('hrow_work_date',$data->hrow_work_date, array('class' => 'form-control','id'=>'hrow_work_date','readonly')) }}
				</div>
				<span class="validate-err" id="err_hrow_work_date"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('hrwt_id', __('Work Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrwt_id') }}</span>
				<div class="form-icon-user">
					{{ Form::select('hrwt_id',$arrWorktype,$data->hrwt_id, array('class' => 'form-control ','id'=>'hrwt_id','readonly')) }}
				</div>
				<span class="validate-err" id="err_hrwt_id"></span>
			</div>
		</div>
		
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('hrow_time_in', __('IN time'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrow_time_in') }}</span>
				<div class="form-icon-user">
					{{ Form::time('hrow_time_in',$data->hrow_time_in, array('class' => 'form-control ','id'=>'hrow_time_in','readonly')) }}
				</div>
				<span class="validate-err" id="err_hrow_time_in"></span>
			</div>
		</div>
	   <div class="col-md-6">
			<div class="form-group">
				{{ Form::label('hrow_time_out', __('OUT Time'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrow_time_out') }}</span>
				<div class="form-icon-user">
					{{ Form::time('hrow_time_out',$data->hrow_time_out, array('class' => 'form-control','id'=>'hrow_time_out','readonly')) }}
				</div>
				<span class="validate-err" id="err_hrow_time_out"></span>
			</div>
	   </div>
	 </div>
	 <div class="row">
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('hrow_reason', __('Reason'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrow_reason') }}</span>
				<div class="form-icon-user">
					{{ Form::textarea('hrow_reason',$data->hrow_reason, array('class' => 'form-control','id'=>'hrow_reason','rows'=>'2','readonly')) }}
				</div>
				<span class="validate-err" id="err_hrow_reason"></span>
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
				<!-- <span class="btn_addmore_document btn btn-primary" id="btn_addmore_document" style="color:white;"><i class="ti-plus"></i></span> -->
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
						<button  type="button" name="submit" value="{{$data->id}}"  id="approve-btn" sequence="{{$approve_btn['sequence']}}" class="btn btn-primary approve-btn {{ ($data->hrow_status === $approve_btn['status'])>0?__(''):__('disabled -field')}}">Approve</button>
				</div>
			@endif
			@if($data->hrow_status !=2)
			<div class="button">
				<button  type="button" name="submit" value="{{$data->id}}"  id="disapprove-btn" sequence="1" class="btn  btn-warning approve-btn {{ ($data->hrow_status) == 2?__('disabled -field'):__('')}}">Disapprove</button>
			</div>
			@endif 
	   @endif 
		<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
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
<script src="{{ asset('js/HR/add_officialwork.js') }}"></script>   

  
 
           