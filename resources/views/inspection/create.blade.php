{{ Form::open(array('url' => 'bfpinspectionorder')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('bend_id',$bend_id, array('id' => 'bend_ids')) }}
	{{ Form::hidden('bff_id',$data->bff_id, array('id' => 'bff_id')) }}
	{{ Form::hidden('client_id',$arrbploBusiness->client_id, array('id' =>'client_id')) }}
	{{ Form::hidden('brgy_id',$arrbploBusiness->busn_office_main_barangay_id,array('id' =>'brgy_id')) }}
	{{ Form::hidden('busn_id',$busn_id, array('id'=>'busn_ids')) }}
	
    @if($data->id > 0)
	@php
        $dclass = ($data->is_active==2 || $data->is_active==3)?'disabled-status':'';
    @endphp
    @endif
    <style type="text/css">
        .modal.show .modal-dialog {
            display: flex;
            transform: none;
            justify-content: center;
            align-items: center;
        }
        .accordion-button::after{background-image: url();}
		.action-btn.bg-info.ms-4 {height: 44px; width: 79px;}
		.modal-content {  width: 70% !important;}
        .modal-footer {
            display: flex;
            flex-wrap: wrap;
            flex-shrink: 0;
            align-items: center;
            justify-content: flex-end;
            padding-right: 41px;
            /* padding: 1rem; */
            border-top: 1px solid #f1f1f1;
            border-bottom-right-radius: 14px;
            border-bottom-left-radius: 14px;
        }
    </style>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    {{ Form::label('busn_name', __('Bussiness Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('busn_name', $arrbploBusiness->busn_name, array('class' => 'form-control','required'=>'required','id'=>'busn_name','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_busn_name"></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('bio_year', __('Covered Year'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bio_year') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('bio_year', $data->bio_year, array('class' => 'form-control','required'=>'required','id'=>'bio_year','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_p_bio_year"></span>
                </div>
            </div>
            @if($data->id >0)
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bio_inspection_no', __('Application No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bio_inspection_no') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('bio_inspection_no', $data->bio_inspection_no, array('class' => 'form-control','required'=>'required','id'=>'bio_inspection_no','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_p_bio_inspection_no"></span>
                </div>
            </div>
            @endif
         </div>
		 <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    {{ Form::label('complete_address', __('Complete Address'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
						{{ Form::text('complete_address',$complete_address, array('class' => 'form-control','required'=>'required','id'=>'complete_address','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_complete_address"></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('bio_date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bio_date') }}</span>
                    <div class="form-icon-user">
                        @if($data->id >0)
                        {{ Form::date('bio_date', $data->bio_date, array('class' => 'form-control','required'=>'required','id'=>'bio_date','readonly')) }}
                        @else
                        {{ Form::date('bio_date',date('Y-m-d'), array('class' => 'form-control','required'=>'required','id'=>'bio_date','readonly')) }}
                        @endif
                    </div>
                    <span class="validate-err" id="err_p_bio_date"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('busns_id_no', __('Bussiness identification No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('busns_id_no') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('busns_id_no', $arrbploBusiness->busns_id_no, array('class' => 'form-control','required'=>'required','id'=>'','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_p_busns_id_no"></span>
                </div>
            </div>
         </div>
		 <div class="row">
			<div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('tex_payer', __('Taxpayer Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('tex_payer') }}</span>
                    <div class="form-icon-user">
                        @if($arrbploBusiness->suffix)
                        {{ Form::text('tex_payer', $arrbploBusiness->rpo_first_name." ".$arrbploBusiness->rpo_middle_name." ".$arrbploBusiness->rpo_custom_last_name.", ".$arrbploBusiness->suffix, array('class' => 'form-control','required'=>'required','id'=>'tex_payer','readonly')) }}
                        @else
                        {{ Form::text('tex_payer', $arrbploBusiness->rpo_first_name." ".$arrbploBusiness->rpo_middle_name." ".$arrbploBusiness->rpo_custom_last_name, array('class' => 'form-control','required'=>'required','id'=>'tex_payer','readonly')) }}
                        @endif
                    </div>
                </div>
            </div>
		 </div>
		 <div class="row">
            @if($data->id >0)
			<div class="col-md-8" style="pointer-events:none;">
                <div class="form-group">
                    {{ Form::label('bio_assigned_to', __('To'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bio_assigned_to') }}</span>
                    <div class="form-icon-user">
					    {{ Form::select('bio_assigned_to',$arrHrEmplyees,$data->bio_assigned_to, array('class' => 'form-control','id'=>'bio_assigned_to','readonly')) }}
                    </div>
                </div>
            </div>
            @else
            <div class="col-md-8">
                <div class="form-group" id="bioassignedparrent">
                    {{ Form::label('bio_assigned to', __('To'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bio_assigned to') }}</span>
                    <div class="form-icon-user">
                        {{ Form::select('bio_assigned_to',$arrHrEmplyees,$data->bio_assigned_to, array('class' => 'form-control','id'=>'bio_assigned_to','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_p_bio_assigned_to"></span>
                </div>
            </div>
            @endif
			<div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bio_inspection_duration', __('Duration[No. of Days]'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bio_inspection_duration') }}</span>
                    <div class="form-icon-user">
                        @if($data->id > 0)
                        {{ Form::number('bio_inspection_duration', $data->bio_inspection_duration, array('class' => 'form-control','required'=>'required','id'=>'bio_inspection_purpose','readonly')) }}
                        @else
                        {{ Form::number('bio_inspection_duration', $data->bio_inspection_duration, array('class' => 'form-control','required'=>'required','id'=>'bio_inspection_purpose')) }}
                        @endif
                    </div>
                    <span class="validate-err" id="err_p_bio_inspection_duration"></span>
                </div>                
            </div>
		 </div>
         
		 <div class="row">
			<div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('bio_inspection_proceed', __('Proceed'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bio_inspection_proceed') }}</span>
                    <div class="form-icon-user">
                         @if($arrbploBusiness->suffix)
                        {{ Form::textarea('bio_inspection_proceed',$arrbploBusiness->busn_name.",\n ".$arrbploBusiness->rpo_first_name." ".$arrbploBusiness->rpo_middle_name." ".$arrbploBusiness->rpo_custom_last_name.", ".$arrbploBusiness->suffix.",\n ".$complete_address, array('rows'=>'3','class' => 'form-control','required'=>'required','id'=>'bio_inspection_proceed','readonly')) }}
                        @else
                        {{ Form::textarea('bio_inspection_proceed',$arrbploBusiness->busn_name.",\n ".$arrbploBusiness->rpo_first_name." ".$arrbploBusiness->rpo_middle_name." ".$arrbploBusiness->rpo_custom_last_name.",\n ".$complete_address, array('rows'=>'3','class' => 'form-control','required'=>'required','id'=>'bio_inspection_proceed','readonly')) }}
                        @endif
                    </div>
                    <span class="validate-err" id="err_p_bio_inspection_proceed"></span>
                </div>
            </div>
		 </div>
		 <div class="row">
			<div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('bio_inspection_purpose', __('Purpose'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bio_inspection_purpose') }}</span>
                    <div class="form-icon-user">
                    @if($data->id >0)
                        {{ Form::text('bio_inspection_purpose', $data->bio_inspection_purpose, array('class' => 'form-control','required'=>'required','id'=>'bio_inspection_purpose','readonly')) }}
                    @else
                        {{ Form::text('bio_inspection_purpose', $data->bio_inspection_purpose, array('class' => 'form-control','required'=>'required','id'=>'bio_inspection_purpose')) }}
                    @endif
                    </div>
                    <span class="validate-err" id="err_p_bio_inspection_purpose"></span>
                </div>
            </div>
		 </div>
		 <div class="row">
			<div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('bio_remarks', __('Remarks'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('brgy_id') }}</span>
                    <div class="form-icon-user">
                    @if($data->id > 0)
                        {{ Form::text('bio_remarks', $data->bio_remarks, array('class' => 'form-control','required'=>'required','id'=>'bio_remarks','readonly')) }}
                    @else
                        {{ Form::text('bio_remarks', $data->bio_remarks, array('class' => 'form-control','required'=>'required','id'=>'bio_remarks')) }}
                    @endif
                    </div>
                    <span class="validate-err" id="err_p_bio_remarks"></span>
                </div>
            </div>
		 </div>
		  <div class="row">
		  @if($data->bio_recommending_status == 0 && $data->created_by == $current_user_login )
			<div class="col-md-6" id="bioapprovalparrent">
                <div class="form-group">
                    {{ Form::label('bio_recommending_approval', __('Recommending Approval'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bio_recommending_approval') }}</span>
                    <div class="form-icon-user">
					    {{ Form::select('bio_recommending_approval',$arrHrEmplyees,$data->bio_recommending_approval, array('class' => 'form-control','id'=>'bio_recommending_approval','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bio_recommending_approval"></span>
                </div>
            </div>
          @elseif($data->id == 0)
            <div class="col-md-6" id="bioapprovalparrent">
                <div class="form-group">
                    {{ Form::label('bio_recommending_approval', __('Recommending Approval'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bio_recommending_approval') }}</span>
                    <div class="form-icon-user">
                        {{ Form::select('bio_recommending_approval',$arrHrEmplyees,$data->bio_recommending_approval, array('class' => 'form-control','id'=>'bio_recommending_approval','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bio_recommending_approval"></span>
                </div>
            </div>
		  @else
			<div class="col-md-6">
				<div class="form-group">
                    {{ Form::label('bio_recommending_approval', __('Recommending Approval'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bio_recommending_approval') }}</span>
                    <div class="form-icon-user">
					    {{ Form::select('bio_recommending_approval',$arrHrEmplyees,$data->bio_recommending_approval, array('class' => 'form-control','id'=>'bio_recommending_approval','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_bio_recommending_approval"></span>
                </div>	
			</div>
		  @endif
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('bio_recommending_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bio_recommending_position') }}</span>
                    <div class="form-icon-user">
                    @if($data->bio_recommending_status == 1)
                       {{ Form::text('bio_recommending_position',$data->bio_recommending_position, array('class' => 'form-control','required'=>'required','id'=>'bio_recommending_position','readonly')) }}
                    @else
                        {{ Form::text('bio_recommending_position',$data->bio_recommending_position, array('class' => 'form-control','required'=>'required','id'=>'bio_recommending_position')) }} 
                    @endif
                    </div>
                    <span class="validate-err" id="err_p_address_street_name"></span>
                </div>
            </div>
		 </div>
		 @if($recommending_user_id == $current_user_login && $data->bio_recommending_status == 0)
		 <div class="row">
			<div class="col-md-4">
                <div class="form-group">
					{{ Form::checkbox('bio_recommending_status', '1', ($data->bio_recommending_status =='1')?true:false, array('id'=>'bio_recommending_status','class'=>'form-check-input code')) }}
                    {{ Form::label('bio_recommending_status', __('Approved: Confirmation'),['class'=>'form-label','style'=>'color:red']) }}
                </div>
            </div>
		 </div> 
		@elseif($data->bio_recommending_status == 1)
		<div class="row" style="pointer-events:none;">
			<div class="col-md-4">
                <div class="form-group">
					{{ Form::checkbox('bio_recommending_status', '1', ($data->bio_recommending_status =='1')?true:false, array('id'=>'bio_recommending_status','class'=>'form-check-input code')) }}
                    {{ Form::label('bio_recommending_status', __('Approved: Confirmation'),['class'=>'form-label','style'=>'color:red','readonly']) }}
                </div>
            </div>
		 </div>
		@endif
		 <div class="row">
		  @if($data->bio_approved_status == 0 && $data->created_by == $current_user_login )
			<div class="col-md-6">
                <div class="form-group" id="bioapprovedparrent">
                    {{ Form::label('bio_approved', __('Approved By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bio_approved') }}</span>
                    <div class="form-icon-user">
                        {{ Form::select('bio_approved',$arrHrEmplyees,$data->bio_approved, array('class' => 'form-control','id'=>'bio_approved','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_p_bio_approved"></span>
                </div>
            </div>
          @elseif($data->id == 0)
            <div class="col-md-6">
                <div class="form-group" id="bioapprovedparrent">
                    {{ Form::label('bio_approved', __('Approved By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('bio_approved') }}</span>
                    <div class="form-icon-user">
                        {{ Form::select('bio_approved',$arrHrEmplyees,$data->bio_approved, array('class' => 'form-control','id'=>'bio_approved','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_p_bio_approved"></span>
                </div>
            </div>
		  @else
			 <div class="col-md-6">
				<div class="form-group">
                    {{ Form::label('bio_approved', __('Approved By'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('bio_approved') }}</span>
                    <div class="form-icon-user">
                        {{ Form::select('bio_approved',$arrHrEmplyees,$data->bio_approved, array('class' => 'form-control','id'=>'bio_approved','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_p_bio_approved"></span>
                </div>
			</div>
            
		  @endif
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('bio_approved_position', __('Position'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('Approval_By') }}</span>
                    <div class="form-icon-user">
                    @if($data->bio_approved_status ==1)
                       {{ Form::text('bio_approved_position',$data->bio_approved_position, array('class' => 'form-control','id'=>'bio_approved_position','readonly')) }}
                    @else
                        {{ Form::text('bio_approved_position',$data->bio_approved_position, array('class' => 'form-control','id'=>'bio_approved_position')) }}
                    @endif
                    </div>
                    <span class="validate-err" id="err_p_Approval_By"></span>
                </div>
            </div>
		</div>
		@if($data->bio_recommending_status == 1 && $approved_user_id == $current_user_login && $data->bio_approved_status == 0)
		<div class="row">
			<div class="col-md-12">
                <div class="form-group">
					{{ Form::checkbox('bio_approved_status', '1', ($data->bio_approved_status =='1')?true:false, array('id'=>'bio_approved_status','class'=>'form-check-input code')) }}
                    {{ Form::label('bio_approved_status', __('Approved: Confirmation'),['class'=>'form-label','style'=>'color:red']) }}
                </div>
            </div>
		 </div>
		@elseif($data->bio_recommending_status == 1 && $data->bio_approved_status ==1)
		 <div class="row" style="pointer-events:none;">
			<div class="col-md-12">
				<div class="form-group">
					{{ Form::checkbox('bio_approved_status', '1', ($data->bio_approved_status =='1')?true:false, array('id'=>'bio_approved_status','class'=>'form-check-input code','readonly')) }}
					{{ Form::label('bio_approved_status', __('Approved: Confirmation'),['class'=>'form-label','style'=>'color:red']) }}
				</div>
			</div>
		 </div>
		@endif
		@if($data->id > 0)
		<div class="col-md-12">
            <!--- Start Status--->
            <div class="row" >
                <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample3">  
                    <div  class="accordion accordion-flush">
                        <div class="accordion-item">
                            <h6 class="accordion-header" id="flush-heading3">
                                <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3">
                                    <h6 class="sub-title accordiantitle">
                                        <i class="ti-menu-alt text-white fs-12"></i>
                                        <span class="accordiantitle-icon">{{__("Uploads")}}
                                        </span>
                                    </h6>
                                </button>
                            </h6>
							@if($data->id > 0)
							<div id="flush-collapse3" class="accordion-collapse collapse" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
							@else
							<div id="flush-collapse3" class="accordion-collapse collapse show" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
							@endif
                                <div class="basicinfodiv">
                                    <div class="row">
									  <div class="row">
											<div class="col-lg-8 col-md-8 col-sm-8">
												<div class="form-group">
													{{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
													<div class="form-icon-user">
														{{ Form::input('file','document_name','',array('class'=>'form-control $dclass'))}}  
													</div>
													<span class="validate-err" id="err_document"></span>
												</div>
											</div>
											
											<div class="col-lg-4 col-md-4 col-sm-4 mt-4">
												<button type="button" style="float: right;" class="btn btn-primary {{$dclass}}" id="uploadAttachmentInspection">Upload File</button>
											</div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Document Title</th>
                                                            <th>Action</th>
                                                           <!-- <th>Action</th> -->
                                                        </tr>
                                                    </thead>
                                                    <thead id="DocumentDtls">
													   <?php echo $data->bio_document ?>
                                                        @if(empty($data->bio_document))
                                                        <tr>
                                                            <td colspan="3"><i>No results found.</i></td>
                                                        </tr>
                                                        @endif
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--- End Status --->
        </div> 
		@endif


        <div class="row" class="col-md-12" style="text-align: left;">
            <div class="col-md-3" style="padding-top:17px;padding-left: 0px;padding-right: 0px;">
                @if($data->bio_recommending_status == 1 && $data->bio_approved_status == 1 )
                    <a href="#" title="Print Inspection Order" style="background: #20b7cc;padding: 10px;color: #fff;" data-title="Print Inspection Order" class="mx-3 btn printsss btn-sm" id="{{$data->id}}">
                            <i class="ti-printer text-white"></i> Print
                    </a>
                @endif
            </div>
            <div class="col-md-9" style="padding-left: 0px;padding-right: 0px;">       
            <div class="modal-footer" style="padding-right: 8px;">
        			<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                    <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                        <i class="fa fa-save icon"></i>
                        <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                    </div>
        		</div>
            </div>   
        </div>    
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/addbfpinspectionorder.js') }}"></script>




