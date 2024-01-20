{{ Form::open(array('url' => 'locationclearance','class'=>'formDtls','id'=>'locationclearanceForm')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('busn_id',$data->busn_id, array('id' => 'busn_id')) }}
    {{ Form::hidden('bend_id',$data->bend_id, array('id' => 'bend_id')) }}
    {{ Form::hidden('cashierd_id','', array('id' => 'cashierd_id')) }}
    {{ Form::hidden('cashier_id','', array('id' => 'cashier_id')) }}
    {{ Form::hidden('or_no','', array('id' => 'or_no')) }}
	@php
        $dclass = ($data->pend_status==2 || $data->pend_status==3)?'disabled-status':'';
    @endphp
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="accordion"   style="padding-top: 10px;">  
                    <div  class="accordion accordion-flush">
                        <div class="accordion-item">
                            <h6 class="accordion-header" id="flush-headingfive">
                                <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive" >
                                    <h6 class="sub-title accordiantitle">{{__("Business Information")}}</h6>
                                </button>
                            </h6>
                            
                            <div  class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5" style="margin-bottom:150px;">
                            <div class="basicinfodiv">
                            <div class="row">    
                                 <div class="col-md-8">
                                    <div class="form-group">
                                        {{ Form::label('businessname', __('Business Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('tfoc_id') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::text('businessname',$bussData->busn_name, array('class' => 'form-control ','id'=>'businessname','required'=>'required','readonly'=>'readonly')) }}
                                        </div>
                                        <span class="validate-err" id="err_businessname"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('pend_year', __('Year'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('year') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::text('pend_year',$data->pend_year, array('class' => 'form-control','id'=>'pend_year','required'=>'required','readonly'=>'readonly')) }}
                                        </div>
                                        <span class="validate-err" id="err_pend_year"></span>
                                    </div>
                                </div>
                             </div>
                             <div class="row"> 
                             <div class="col-md-8">
                                <div class="form-group">
                                    {{ Form::label('completeaddress', __('Complete Address'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('completeaddress') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::text('completeaddress',$complete_address, array('class' => 'form-control ','id'=>'completeaddress','required'=>'required','readonly'=>'readonly')) }}
                                    </div>
                                    <span class="validate-err" id="err_completeaddress"></span>
                                </div>
                            </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('date') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::date('pend_date',$data->pend_date, array('class' => 'form-control','id'=>'date','required'=>'required','readonly'=>'readonly')) }}
                                    </div>
                                    <span class="validate-err" id="err_pend_date"></span>
                                </div>
                            </div>
                           </div>
                           <div class="row"> 
                             <div class="col-md-8">
                                <div class="form-group">
                                    {{ Form::label('client_id', __('Taxpayer Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('client_id') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::select('client_id',$arrowners,$bussData->client_id, array('class' => 'form-control','id'=>'client_id','required'=>'required','readonly'=>'readonly')) }}
                                    </div>
                                    <span class="validate-err" id="err_client_id"></span>
                                </div>
                              </div>
                               <div class="col-md-4">
                                <div class="form-group">
                                   {{ Form::label('pend_status', __('Status'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <span class="validate-err">{{ $errors->first('pend_status') }}</span>
                                    <div class="form-icon-user">
                                        {{ Form::select('pend_status',array('1'=>'Active','0'=>'Inactive'),$data->pend_status, array('class' => 'form-control','id'=>'date','required'=>'required')) }}
                                    </div>
                                    <span class="validate-err" id="err_pend_status"></span>
                                </div>
                            </div>
                           </div>
                           
                           <div class="row">    
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::label('pend_remarks', __('Remarks'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('pend_remarks') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::text('pend_remarks',$data->pend_remarks, array('class' => 'form-control','id'=>'pend_remarks','required'=>'required')) }}
                                        </div>
                                        <span class="validate-err" id="err_pend_remarks"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row"> 
                            <div class="col-md-4">
									<div class="form-group">
                                        {{ Form::label('orno', __('O.R. No.'),['class'=>'form-label']) }}
                                        <span class="validate-err">{{ $errors->first('orno') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::text('or_no',$data->or_no, array('class' => 'form-control disabled-field','id'=>'orno')) }}
                                        </div>
                                        <span class="validate-err" id="err_orno"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('oramount', __('O.R. Amount'),['class'=>'form-label']) }}
                                        <span class="validate-err">{{ $errors->first('oramount') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::text('or_amount',$data->or_amount, array('class' => 'form-control disabled-field','id'=>'oramount')) }}
                                        </div>
                                        <span class="validate-err" id="err_oramount"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('ordate', __('O.R. Date'),['class'=>'form-label']) }}
                                        <span class="validate-err">{{ $errors->first('ordate') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::text('or_date',$data->or_date, array('class' => 'form-control disabled-field','id'=>'ordate')) }}
                                        </div>
                                        <span class="validate-err" id="err_ordate"></span>
                                    </div>
                                </div>
                           </div>
						   
						   
						   <div class="row">
                            @if($data->pend_remarks)
                                @if($data->pend_inspected_status == 1)
                                <div class="col-md-8">
                                    <div class="form-group" style="pointer-events: none;">
                                        {{ Form::label('pend_inspected_by', __('Zoning Inspector'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('pend_inspected_by') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('pend_inspected_by',$hremployees,$data->pend_inspected_by, array('class' => 'form-control','id'=>'pend_inspected_by','required'=>'required','readonly')) }}
                                        </div>
                                        <span class="validate-err" id="err_pend_inspected_by"></span>
                                    </div>
                                </div>
                                @elseif($data->pend_inspected_status == 0 && $current_user_id == $pendinspected_user_id)
                                <div class="col-md-8">
                                    <div class="form-group" style="pointer-events: none;">
                                        {{ Form::label('pend_inspected_by', __('Zoning Inspector'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('pend_inspected_by') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('pend_inspected_by',$hremployees,$data->pend_inspected_by, array('class' => 'form-control','id'=>'pend_inspected_by','required'=>'required','readonly')) }}
                                        </div>
                                        <span class="validate-err" id="err_pend_inspected_by"></span>
                                    </div>
                                </div>
                                @else
                                 <div class="col-md-8">
                                    <div class="form-group" id="pendapprovedbyparrent">
                                        {{ Form::label('pend_inspected_by', __('Zoning Inspector'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('pend_inspected_by') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('pend_inspected_by',$hremployees,$data->pend_inspected_by, array('class' => 'form-control select3','id'=>'pend_inspected_by','required'=>'required')) }}
                                        </div>
                                        <span class="validate-err" id="err_pend_inspected_by"></span>
                                    </div>
                                </div>
                                @endif  
                                @else
                                 <div class="col-md-8">
                                    <div class="form-group" id="pendapprovedbyparrent">
                                        {{ Form::label('pend_inspected_by', __('Zoning Inspector'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('pend_inspected_by') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('pend_inspected_by',$hremployees,$data->pend_inspected_by, array('class' => 'form-control select3','id'=>'pend_inspected_by','required'=>'required')) }}
                                        </div>
                                        <span class="validate-err" id="err_pend_inspected_by"></span>
                                    </div>
                                </div>
                                @endif 
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('pend_inspected_officer_position') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::text('pend_inspected_officer_position',$data->pend_inspected_officer_position, array('class' => 'form-control','id'=>'pend_inspected_officer_position','required'=>'required')) }}
                                        </div>
                                        <span class="validate-err" id="err_pend_inspected_officer_position"></span>
                                    </div>
                                </div>
                             </div>
                             @if($data->pend_remarks)
							 @if($data->id > 0 && $current_user_id == $pendinspected_user_id && $data->pend_inspected_status == 0)
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											{{ Form::checkbox('pend_inspected_status', '1', ($data->pend_inspected_status =='1')?true:false, array('id'=>'pend_inspected_status','class'=>'form-check-input code')) }}
											{{ Form::label('pend_inspected_status', __('Approved: Confirmation'),['class'=>'form-label','style'=>'color:red']) }}
										</div>
									</div>
								</div>
								@elseif($data->id > 0 && $data->pend_inspected_status == 1)
								<div class="row">
									<div class="col-md-4" style="pointer-events: none;">
										<div class="form-group">
											{{ Form::checkbox('pend_inspected_status', '1', ($data->pend_inspected_status =='1')?true:false, array('id'=>'pend_inspected_status','class'=>'form-check-input code')) }}
											{{ Form::label('pend_inspected_status', __('Approved: Confirmation'),['class'=>'form-label','style'=>'color:red']) }}
										</div>
									</div>
								</div>
							@endif
                            @else
                            @endif
							<div class="row">
                            @if($data->pend_remarks)
                                @if($data->pend_approved_status == 1)
                                <div class="col-md-8">
                                    <div class="form-group" style="pointer-events: none;">
                                        {{ Form::label('pend_approved_by', __('Zoning Officer'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('pend_approved_by') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('pend_approved_by',$hremployees,$data->pend_approved_by, array('class' => 'form-control','id'=>'pend_approved_by','required'=>'required','readonly')) }}
                                        </div>
                                        <span class="validate-err" id="err_pend_approved_by"></span>
                                    </div>
                                </div>
                                @elseif($data->pend_approved_status == 0 && $current_user_id == $pendapproved_user_id)
                                <div class="col-md-8" >
                                    <div class="form-group" style="pointer-events: none;">
                                        {{ Form::label('pend_approved_by', __('Zoning Officer'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('pend_approved_by') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('pend_approved_by',$hremployees,$data->pend_approved_by, array('class' => 'form-control','id'=>'pend_approved_by','required'=>'required','readonly')) }}
                                        </div>
                                        <span class="validate-err" id="err_pend_approved_by"></span>
                                    </div>
                                </div>
                                @else
                                 <div class="col-md-8">
                                    <div class="form-group" id="pendapprovedbyparrento">
                                        {{ Form::label('pend_approved_by', __('Zoning Officer'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('pend_approved_by') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('pend_approved_by',$hremployees,$data->pend_approved_by, array('class' => 'form-control select3','id'=>'pend_approved_by','required'=>'required')) }}
                                        </div>
                                        <span class="validate-err" id="err_pend_approved_by"></span>
                                    </div>
                                </div>
                                @endif  
                             @else
                                 <div class="col-md-8">
                                    <div class="form-group" id="pendapprovedbyparrento">
                                        {{ Form::label('pend_approved_by', __('Zoning Officer'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('pend_approved_by') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::select('pend_approved_by',$hremployees,$data->pend_approved_by, array('class' => 'form-control select3','id'=>'pend_approved_by','required'=>'required')) }}
                                        </div>
                                        <span class="validate-err" id="err_pend_approved_by"></span>
                                    </div>
                                </div>
                                @endif  
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('pend_officer_position') }}</span>
                                        <div class="form-icon-user">
                                            {{ Form::text('pend_officer_position',$data->pend_officer_position, array('class' => 'form-control','id'=>'position','required'=>'required')) }}
                                        </div>
                                        <span class="validate-err" id="err_pend_officer_position"></span>
                                    </div>
                                </div>
                             </div>
                        </div>
                        @if($data->pend_remarks)
                        @if($data->id > 0 && $current_user_id == $pendapproved_user_id && $data->pend_inspected_status == 1)
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::checkbox('pend_approved_status', '1', ($data->pend_approved_status =='1')?true:false, array('id'=>'pend_approved_status','class'=>'form-check-input code')) }}
                                    {{ Form::label('pend_approved_status', __('Approved: Confirmation'),['class'=>'form-label','style'=>'color:red']) }}
                                </div>
                            </div>
                        </div>
                        @elseif($data->id > 0 && $data->pend_approved_status == 1)
                        <div class="row">
                            <div class="col-md-4" style="pointer-events: none;">
                                <div class="form-group">
                                    {{ Form::checkbox('pend_approved_status', '1', ($data->pend_approved_status =='1')?true:false, array('id'=>'pend_approved_status','class'=>'form-check-input code')) }}
                                    {{ Form::label('pend_approved_status', __('Approved: Confirmation'),['class'=>'form-label','style'=>'color:red']) }}
                                </div>
                            </div>
                        </div>
                        @endif
                        @else
                        @endif
                    </div>
                </div>
            </div>
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
														   <?php echo $data->pend_document ?>
                                                            @if(empty($data->pend_document))
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
            <div class="modal-footer" style="float:left;">
                @if($data->pend_approved_status == 1 && $data->pend_inspected_status == 1 )
                 <!-- <button id="btnPrintclearance" type="button" style="float: right;" value="{{ url('/locationclearance/printreport?id=').''.$data->id }}"   class="btn btn-primary digital-sign-btn"><i class="ti-printer text-white"></i> Print</button> -->
                  <a href="{{ url('/locationclearance/printreport?id=').''.$data->id }}" title="Print Location Clearance" style="background: #20b7cc;padding: 10px;color: #fff;" data-title="Print Location Clearance" target="_blank" class="mx-3 btn btn-sm digital-sign-btn" id="{{$data->id}}" >
                            <i class="ti-printer text-white"></i> Print
                        </a>
                @endif
            </div>
            <div class="modal-footer" style="float:right;">
                <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
					<i class="fa fa-save icon"></i>
					<input type="submit" id="submit" name="submit" value="{{ ('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;border-radius: 5px;">
				</div>
            </div>
        </div>
    </div>
    
{{Form::close()}}
 <script src="{{ asset('js/ajax_validation.js') }}"></script>  
  <script src="{{ asset('js/Bplo/add_locationclerance.js') }}?rand={{ rand(0000,9999) }}"></script> 
<script>
$(document).ready(function() {
    var shouldSubmitForm = false;
    $('#submit').click(function (e) {
        if (!shouldSubmitForm) {
            var form = $('#locationclearanceForm');
            Swal.fire({
                title: "Are you sure?",
                html: '<span style="color: red;">This will save the current changes.</span>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    shouldSubmitForm = true;
                    form.submit();
                    $('#submit').click();
                } else {
                    console.log("Form submission canceled");
                }
            });

            e.preventDefault();
        }
    });
    

});

</script>
  
 
           