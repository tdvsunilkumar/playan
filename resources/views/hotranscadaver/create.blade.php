{{ Form::open(array('url' => 'civil-registrar/permits','class'=>'formDtls','id'=>'permit')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('req_permit_id',$data->req_permit_id, array('id' => 'req_permit_id')) }}

<div class="modal-body">
   <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample">
         <div class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingone">
                  <button class="accordion-button  btn-primary" type="button">
                     <h6 class="sub-title accordiantitle">Requestor Information</h6>
                  </button>
               </h6>
               <div id="flush-collapseone" class="accordion-collapse collapse show">
                  <div class="basicinfodiv">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('requester_id', __('Requestor Name'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('requester_id') }}</span>
                              <div class="form-icon-user" id="">
                                 {{ Form::hidden('requester_id',$data->requester_id, array('id' => 'requester_id')) }}
                                 {{ 
                                 Form::text('requester_name',
                                 isset($data->permit)?$data->permit->requestor->cit_fullname:'',
                                 array(
                                    'id'=>'citizen_id',
                                    'class' => 'form-control',
                                    'readonly'=>'readonly',
                                 )
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_requester_id"></span>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('issue_date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                              <div class="form-icon-user">
                              {{ Form::date('issue_date', $data->issue_date, array('id'=>'issue_date','class' => 'form-control')) }}
                              </div>
                              <span class="validate-err" id="err_issue_date"></span>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('brgy_id', __('Complete Address'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('brgy_id') }}</span>
                              <div class="form-icon-user" id="select-contain-brgy">
                              {{ Form::hidden('brgy_id',$data->brgy_id, array('id' => '')) }}
                              {{ 
                                 Form::text('brgy_address', 
                                 isset($data->permit)?$data->permit->requestor->cit_full_address:'', 
                                    array(
                                    'id' => 'brgy_id',
                                    'class' => 'form-control',
                                    'readonly'
                                 )
                                 ) 
                              }}
                              </div>
                              <span class="validate-err" id="err_complete_address"></span>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('form_type', __('Type of Permit'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('form_type') }}</span>
                              <div class="form-icon-user" id="">
							         {{ Form::hidden('form_type',$data->form_type, array('id' => '')) }}
                                 {{ 
                                 Form::select('form_type', $getformtype,
                                 $data->form_type, 
                                 array(
                                 'id'=>'form_type',
                                 'data-contain' => 'select-contain-brgy',
                                 'class' => 'form-control select3',
                                 'required'=>'required',
									($data->form_type > 0)?'disabled':''
								 )
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_cit_id"></span>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('relation_type', __('Relation to Deceased Person'),['class'=>'form-label']) }}
                              <span class="text-danger">*</span>
                              <span class="validate-err">{{ $errors->first('relation_type') }}</span>
                              <div class="form-icon-user" id="">
                                 {{ 
                                 Form::text('relation_type',
                                 $data->relation_type,
                                 array(
                                 'id'=>'relation_type',
                                 'class' => 'form-control')
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_relation_type"></span>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('or_no', __('O.R. No.'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('or_no') }}</span>
                              <div class="form-icon-user" id="">
                                 {{ 
                                 Form::text('or_no',
                                 $data->or_no,
                                 array(
                                 'id'=>'or_no',
                                 'class' => 'form-control',
								 (isset($data->or_no) && $data->or_no != 0)?'readonly':'readonly'
								 )
                                 ) 
                                 }}
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingone">
                  <button class="accordion-button  btn-primary" type="button">
                     <h6 class="sub-title accordiantitle">Officer Information</h6>
                  </button>
               </h6>
               <div id="flush-collapseone" class="accordion-collapse collapse show">
                  <div class="basicinfodiv">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('health_officer_id', __('Health Officer'),['class'=>'form-label']) }}
                              <span class="text-danger">*</span>
                              <span class="validate-err">{{ $errors->first('health_officer_id') }}</span>
                              <div class="form-icon-user" id="">
                                 {{ 
                                 Form::select('health_officer_id', 
                                 $gethealthofficer, 
								 ($data->id > 0 && isset($data->health_officer_id))?$data->health_officer_id:$last_user_data->health_officer_id,
                                 array(
                                 'data-contain' => 'select-contain-brgy',
                                 'id'=>'health_officer_id',
                                 'class' => 'form-control select3',
                                 )
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_cit_id"></span>
                           </div>
                        </div>
                        <div class="col-md-6">
						@if($is_approveduser == \Auth::user()->id)
                           <div class="form-group" id="is_approved">
                              {{ Form::label('is_approved', __('Approve Certificate'),['class'=>'form-label']) }}
                              <span class="validate-err">{{ $errors->first('is_approved') }}</span>
                              <div class="form-icon-user">
                                 {{ Form::checkbox('is_approved', 
                                    1, 
                                    ($data->is_approved === 1)?true:false, 
                                    ['id' => 'is_approved']) 
                                 }}
                              </div>
                           </div>
						@endif
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('health_officer_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                              <div class="form-icon-user" id="">
                                 {{ 
                                 Form::text('health_officer_position', 
								 ($data->id > 0 && isset($data->health_officer_position))?$data->health_officer_position:$last_user_data->health_officer_position,
                                 array(
                                 'id'=>'health_officer_position',
                                 'class' => 'form-control',
                                 )
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_health_officer_position"></span>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="accordion accordion-flush">
            <div class="accordion-item">
               <h6 class="accordion-header" id="flush-headingone">
                  <button class="accordion-button  btn-primary" type="button">
                     <h6 class="sub-title accordiantitle">Deceased Information</h6>
                  </button>
               </h6>
               <div id="flush-collapseone" class="accordion-collapse collapse show">
                  <div class="basicinfodiv">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('deceased_id', __('Name of Deceased Person'),['class'=>'form-label']) }}
                              <span class="text-danger">*</span>
                              <span class="validate-err">{{ $errors->first('deceased_id') }}</span>
                              <div class="form-icon-user" id="">
                                 {{ 
                                 Form::select('deceased_id', 
                                 $getcitizens, 
                                 $data->deceased_id,
                                 array(
                                 'id'=>'disease_name',
                                 'data-contain' => 'select-contain-brgy',
                                 'class' => 'form-control select3',
                                 )
                                 ) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_deceased_id"></span>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('place_of_death_id', __('Place Of Death'),['class'=>'form-label']) }}
                              <span class="text-danger">*</span>
                              <span class="validate-err">{{ $errors->first('place_of_death_id') }}</span>
                              <div class="form-icon-user" id="place_of_death_id_contain">
                                 {{ 
                                 Form::select('place_of_death_id', 
                                       [],
                                       $data->place_of_death_id, 
                                       $attributes = array(
                                       'id' => 'place_of_death_id',
                                       'data-url' => 'getBarngayList',
                                       'data-placeholder' => 'Search Brarangay',
                                       'data-contain' => 'place_of_death_id_contain',
                                       'data-value' =>($data->place_of_death_id)?$data->brgy_transfer:'',
                                       'data-value_id' =>$data->place_of_death_id,
                                       'class' => 'form-control ajax-select',
                                    )) 
                                 }}
                              </div>
                              <span class="validate-err" id="err_place_of_death_id"></span>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('transfer_location', __('Transfer Location'),['class'=>'form-label']) }}
                              <div class="form-icon-user" id="">
                              @if($data->form_type == 14)
                                 {{ 
                                 Form::text('transfer_location', 
                                 $data->transfer_location, 
                                 array(
                                 'id'=>'transfer_location',
                                 'class' => 'form-control',
                                 'readonly' => 'readonly' )
                                 ) 
                                 }}
                              @else
                                 {{ 
                                 Form::text('transfer_location', 
                                 $data->transfer_location, 
                                 array(
                                 'id'=>'transfer_location',
                                 'class' => 'form-control')
                                 ) 
                                 }}
                              @endif
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('death_date', __('Date Of Death'),['class'=>'form-label']) }}
                              <span class="text-danger">*</span>
                              <span class="validate-err">{{ $errors->first('death_date') }}</span>
                              <div class="form-icon-user" id="">
                                 {{ 
                                 Form::date('death_date', 
                                 $data->death_date, 
                                 array(
                                 'id'=>'death_date',
                                 'class' => 'form-control',

                                 )
                                 ) 
                                 }}
                              </div>
                           </div>
						   <span class="validate-err" id="err_death_date"></span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {{ Form::label('transfer_add_id', __('Transfer Address'),['class'=>'form-label']) }}
                              <div class="form-icon-user" id="transfer_add_id_contain">
                              @if($data->form_type == 14)  
                                 {{ 
                                 Form::select('transfer_add_id', 
                                    [],
                                    $data->transfer_add_id, 
                                    $attributes = array(
                                    'id' => 'transfer_add_id',
                                    'data-url' => 'getBarngayList',
                                    'data-placeholder' => 'Search Brarangay',
                                    'data-contain' => 'transfer_add_id_contain',
                                    'data-value' =>($data->transfer_add_id)?$data->brgy_transfer:'',
                                    'data-value_id' =>$data->transfer_add_id,
                                    'class' => 'form-control ajax-select',
                                    'readonly' => 'readonly'
                                 )) 
                              }}
                                 @else 
                                 {{ 
                                    Form::select('transfer_add_id', 
                                       [],
                                       $data->transfer_add_id, 
                                       $attributes = array(
                                       'id' => 'transfer_add_id',
                                       'data-url' => 'getBarngayList',
                                       'data-placeholder' => 'Search Brarangay',
                                       'data-contain' => 'transfer_add_id_contain',
                                       'data-value' =>($data->transfer_add_id)?$data->brgy_transfer:'',
                                       'data-value_id' =>$data->transfer_add_id,
                                       'class' => 'form-control ajax-select'
                                    )) 
                                 }}
                              @endif
<!-- data-value you must display the brgy_name from baragay id -->
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- @if($data->service->ho_service_name === 'Permit to Open Niche')
                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-group">
                              {{ Form::label('requester_id', __('Additional'),['class'=>'form-label']) }}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="row field-requirement-details-status">
                           <div class="col-lg-1 col-md-1 col-sm-1">
                              {{Form::label('no',__('No.'),['class'=>'form-label','style' =>'color: #1c232f;'])}}
                           </div>
                           <div class="col-lg-3 col-md-3 col-sm-3">
                              {{Form::label('code',__('Name'),['class'=>'form-label','style' =>'color: #1c232f;'])}}
                           </div>
                           <div class="col-lg-3 col-md-3 col-sm-3">
                              {{Form::label('category',__('Date Of Death'),['class'=>'form-label','style' =>'color: #1c232f;'])}}
                           </div>
                           <div class="col-lg-3 col-md-3 col-sm-3">
                              {{Form::label('status',__('Status'),['class'=>'form-label','style' =>'color: #1c232f;'])}}
                           </div>
                           <div class="col-lg-2 col-md-2 col-sm-2">
                              {{Form::label('Action',__('Action'),['class'=>'form-label','style' =>'color: #1c232f;'])}}
                              <a href="#" id="btn_addmore_deccadaver" class="btn btn-sm btn-primary action-item" role="button">
                              <i class="ti-plus"></i>
                              </a>
                           </div>
                        </div>
                        <span class="Deccadaver nature-details" id="Deccadaver">
                           @php $i=0; $j=0;  @endphp
                           @foreach($adddeceasedata as $key=>$val)  
                           @php $j=$j+1; @endphp
                           <div class="row removeDeccadaverdata pt10">
                              <div class="col-lg-1 col-md-1 col-sm-1">
                                 <div class="form-group">
                                    <div class="form-icon-user">
                                       {{$j}}
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-3">
                                 <div class="form-group">
                                    <div class="form-icon-user">
                                       {{ Form::select('dec_id[]',$getcitizens, $val->deceased_cert_id, array('class' => 'form-control dec_id_s','id'=>'dec_id'.$i)) }}
                                       {{ Form::hidden('deceaseid[]',$val->id, array(
                                          'id' => 'deceaseid','class' => 'healthcl')) }}
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-3">
                                 <div class="form-group">
                                    <div class="form-icon-user">
                                       {{Form::date('death_date_data[]',$val->death_date,array('class'=>'form-control'))}}
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-3">
                                 <div class="form-group">
                                    <div class="form-icon-user" >
                                       {{ Form::select('status[]',array('0'=>'Inactive','1'=>'Active'),$val->status, array('class' => 'form-control naofbussi','id'=>'stat_id'.$i)) }} 
                                    </div>
                                 </div>
                              </div>
                              <div class='action-btn bg-danger col-sm-2'>
                                 <a href='#' class='mx-3 btn btn-sm btn_cancel_deccadaver ti-trash text-white text-white'></a>
                              </div>
                              @php $i++; @endphp
                           </div>
                           @endforeach
                        </span>
                     </div>
                     @endif -->
                  </div>
               </div>
            </div>
         </div>
		 @if($data->id > 0)
			   <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample3">
				  <div  class="accordion accordion-flush">
					 <div class="accordion-item">
						<h6 class="accordion-header" id="flush-heading4">
						   <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse4" aria-expanded="false" aria-controls="flush-collapse4">
							  <h6 class="sub-title accordiantitle">
								 <i class="ti-menu-alt text-white fs-12"></i>
								 <span class="accordiantitle-icon">{{__("Upload")}}
								 </span>
							  </h6>
						   </button>
						</h6>
						<div id="flush-collapse4" class="accordion-collapse collapse" aria-labelledby="flush-heading4" data-bs-parent="#accordionFlushExample3">
						   <div class="basicinfodiv">
							  <div class="row">
								 <div class="col-lg-6 col-md-6 col-sm-6">
									<div class="form-group">
									   {{ Form::label('ora_document', __('Document'),['class'=>'form-label']) }}
									   <div class="form-icon-user">
										  {{ Form::input('file','ora_document','',array('class'=>'form-control'))}}  
									   </div>
									   <span class="validate-err" id="err_documents"></span>
									</div>
								 </div>
								 <div class="col-lg-6 col-md-6 col-sm-6 mt-4">
									<button type="button" style="float: right;" class="btn btn-primary" id="uploadAttachmentonly">Upload File</button>
								 </div>
							  </div>
							  <div class="row">
								 <div class="col-lg-12 col-md-12 col-sm-12">
									<br>
									<div class="table-responsive">
									   <table class="table">
										  <thead>
											 <tr>
												<th>Attachment</th>
												<th>Action</th>
											 </tr>
										  </thead>
										  <thead id="DocumentDtlsss">
											 <?php echo $data->arrDocumentDetailsHtml?>
											 @if(empty($data->arrDocumentDetailsHtml))
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
			   @endif
		<div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            @if($data->id)
               <a 
               @switch($data->top_trans_type)
                  @case(40)
                  href="{{route('hotranscadaver.print.openNiche',['id'=>$data->id])}}" 
                  @break
                  @case(41)
                  href="{{route('hotranscadaver.print.transferRemain',['id'=>$data->id])}}" 
                  @break
                  @case(43)
                  href="{{route('hotranscadaver.print.transferCadaver',['id'=>$data->id])}}" 
                  @break
               @default
               href="#" 
               @endswitch
               target="_blank">
               <button class="btn btn-primary" type="button" >
                  <i class="fa fa-print icon" ></i>
                  Print
               </button>
               </a>
            @endif
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
               <i class="fa fa-save icon"></i>
               <input type="submit" id="savechanges" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
         </div>
      </div>
   </div>
</div>
{{Form::close()}}
@php 
$ii=(count($adddeceasedata)>0)?count($adddeceasedata):0;
@endphp
<div id="hidenDeccadaverHtml" class="hide">
   <div class="removeDeccadaverdata row pt10">
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="form-group">
            <div class="form-icon-user">
               <div id="increment"></div>
            </div>
         </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3">
         <div class="form-group">
            <div class="form-icon-user">
               {{ Form::select('dec_id[]',$getcitizens, '', array('class' => 'form-control dec_id','id'=>'dec_id'.$ii,'required'=>'required')) }}
            </div>
         </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3">
         <div class="form-group">
            <div class="form-icon-user">
               {{Form::date('death_date_data[]','',array('class'=>'form-control numeric'))}}
            </div>
         </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3">
         <div class="form-group">
            <div class="form-icon-user">
               {{ Form::select('status[]',array('0'=>'Inactive','1'=>'Active'),'', array('class' => 'form-control naofbussi')) }} 
            </div>
         </div>
      </div>
      <div class='action-btn bg-danger col-sm-2'>
         <a href='#' class='mx-3 btn btn-sm btn_cancel_deccadaver ti-trash text-white text-white'></a>
      </div>
   </div>
</div>
<script src="{{ asset('js/ajax_validation_confir_mass.js') }}"></script>
<script src="{{ asset('js/add_hotranscadaver.js') }}"></script>
<script src="{{ asset('js/SocialWelfare/select-ajax.js?v='.filemtime(getcwd().'/js/SocialWelfare/select-ajax.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/citizen-ajax.js?v='.filemtime(getcwd().'/js/SocialWelfare/citizen-ajax.js').'') }}"></script>

