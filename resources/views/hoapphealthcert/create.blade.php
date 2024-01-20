{{ Form::open(array('url' => 'healthy-and-safety/health-certificate','id'=>'healthcertificate')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('hahc_app_no',$data->hahc_app_no, array('id' => 'hahc_app_no')) }}
{{ Form::hidden('hahc_app_code',$data->hahc_app_code, array('id' => 'hahc_app_code')) }}
<style type="text/css">
   .modal-body {
       position: relative;
       flex: 1 1 auto;
       padding: 1.25rem;
       padding-bottom: 5%;
       overflow: hidden;
   }
   .accordion-button::after{background-image: url();}
   .field-requirement-details-status {
   border-bottom: 1px solid #f1f1f1;
   font-size: 13px;
   color: black;
   background: #8080802e;
   text-transform: uppercase;
   margin: 20px 0px 6px 0px;
   margin-top: 20px;
   }
   .field-requirement-details-status label{padding-top:5px;}
   .modal-lg, .modal-xl {
   max-width: 975px !important;
   }
</style>

<div class="modal-body">
   <div class="row">
      <div class="col-md-6">
         <div class="form-group" id="citizen_id_group">
            {{ Form::label('citizen_id', __('Name'),['class'=>'form-label']) }}
            <span class="text-danger">*</span>
            <span class="validate-err">{{ $errors->first('citizen_id') }}</span>
            <div class="form-icon-user d-flex align-items-center">
               <div class="flex-grow-1">
                  {{ Form::select('citizen_id', $citizen, $data->citizen_id, ['class' => 'form-control ', 'id' => 'citizen_id']) }}
               </div>
               <!-- <div class="ms-2">
                  @if($data->id == NULL)
                  <a href="#" data-size="lg" data-url="{{ url('/healthy-and-safety/health-certificate/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Reload')}}" class="btn btn-sm btn-primary">
                  <i class="ti-reload"></i>
                  </a>
                  @else
                  <a href="#" data-size="lg" data-url="{{ url('/healthy-and-safety/health-certificate/store?id='.$data->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Reload')}}" class="btn btn-sm btn-primary">
                  <i class="ti-reload"></i>
                  </a>
                  @endif
               </div> -->
               <div class="ms-2">
                  <a href="{{ route('citizen.index') }}" target="_blank" title="{{ __('Add Citizen') }}" class="btn btn-sm btn-primary" style="    padding: 9px 7px;"><i class="ti-plus"></i></a>
               </div>
            </div>
            <span class="validate-err" id="err_citizen_id"></span>
         </div>
      </div>
      <div class="col-md-3">
         <div class="form-group">
            {{ Form::label('age', __('Age'),['class'=>'form-label']) }}
            <span class="validate-err">{{ $errors->first('age') }}</span>
            <div class="form-icon-user">
               {{ Form::text('age', $age, array('class' => 'form-control','id'=>'age','readonly'=>'true')) }}
            </div>
            <span class="validate-err" id="err_age"></span>
         </div>
      </div>
      <div class="col-md-3">
         <div class="form-group">
            {{ Form::label('hahc_app_year', __('Year Applied'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            <span class="validate-err">{{ $errors->first('hahc_app_year') }}</span>
            <div class="form-icon-user">
               {{ Form::text('hahc_app_year', $data->hahc_app_year, array('class' => 'form-control numeric-only','id'=>'hahc_app_year')) }}
            </div>
            <span class="validate-err" id="err_hahc_app_year"></span>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-6">
         <div class="form-group">
            {{ Form::label('complete_address', __('Complete Address'),['class'=>'form-label']) }}
            <span class="validate-err">{{ $errors->first('complete_address') }}</span>
            <div class="form-icon-user">
               {{ Form::text('complete_address', str_replace(',', ', ', $complete_address), array('class' => 'form-control','id'=>'complete_address','readonly'=>'true')) }}
            </div>
            <span class="validate-err" id="err_complete_address"></span>
         </div>
      </div>
      <div class="col-md-3">
         <div class="form-group">
            {{ Form::label('hahc_transaction_no', __('Control No.'),['class'=>'form-label']) }}
            <span class="validate-err">{{ $errors->first('hahc_transaction_no') }}</span>
            <div class="form-icon-user">
               {{ Form::text('hahc_transaction_no', $data->hahc_transaction_no, array('class' => 'form-control','readonly'=>'true')) }}
            </div>
            <span class="validate-err" id="err_hahc_transaction_no"></span>
         </div>
      </div>
      <div class="col-md-3">
         <div class="form-group">
            {{ Form::label('hahc_registration_no', __('Registration No.'),['class'=>'form-label']) }}
            <span class="validate-err">{{ $errors->first('hahc_registration_no') }}</span>
            <div class="form-icon-user">
               {{ Form::text('hahc_registration_no', $data->hahc_registration_no, array('class' => 'form-control numeric-only','readonly'=>'true')) }}
            </div>
            <span class="validate-err" id="err_hahc_registration_no"></span>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-4">
         <div class="form-group">
            {{ Form::label('nationality', __('Nationality'),['class'=>'form-label']) }}
            <span class="validate-err">{{ $errors->first('nationality') }}</span>
            <div class="form-icon-user">
               {{ Form::text('nationality', $nationality, array('class' => 'form-control','id' => 'nationality','readonly'=>'true')) }}
            </div>
            <span class="validate-err" id="err_c_code"></span>
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group">
            {{ Form::label('gender', __('Gender'),['class'=>'form-label']) }}
            <span class="validate-err">{{ $errors->first('gender') }}</span>
            <div class="form-icon-user">
               {{ Form::text('gender', $gender, array('class' => 'form-control','id'=>"gender",'readonly'=>'true')) }}
            </div>
            <span class="validate-err" id="err_gender"></span>
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group">
            {{ Form::label('employee_occupation', __('Occupation'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            <span class="validate-err">{{ $errors->first('employee_occupation') }}</span>
            <div class="form-icon-user">
               {{ Form::text('employee_occupation', $data->employee_occupation, array('class' => 'form-control','id' => 'employee_occupation')) }}
               <div id="empOccupSuggestionsDiv"></div>
            </div>
            <span class="validate-err" id="err_employee_occupation"></span>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-8">
         <div class="form-group" id="bend_id_group">
            {{ Form::label('bend_id', __('Business Name'),['class'=>'form-label']) }}
            <span class="validate-err">{{ $errors->first('bend_id') }}</span>
            <div class="form-icon-user">
               {{ Form::select('bend_id',$busn_name,$data->bend_id, array('class' => 'form-control','id'=>'bend_id')) }}
            </div>
            <span class="validate-err" id="err_bend_id"></span>
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group">
            {{ Form::label('hahc_place_of_work', __('Address of the workplace'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            <div class="form-icon-user">
               {{ Form::text('hahc_place_of_work',$data->hahc_place_of_work, array('class' => 'form-control','id'=>'hahc_place_of_work')) }}
            </div>
            <div id="addressSuggestionsDiv"></div>
            <span class="validate-err" id="err_hahc_place_of_work"></span>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-4">
         <div class="form-group">
            {{ Form::label('hahc_issuance_date', __('Issuance Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            <span class="validate-err">{{ $errors->first('hahc_issuance_date') }}</span>
            <div class="form-icon-user">
               {{ Form::date('hahc_issuance_date', $data->hahc_issuance_date, array('class' => 'form-control')) }}
            </div>
            <span class="validate-err" id="err_hahc_issuance_date"></span>
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group">
            {{ Form::label('hahc_expired_date', __('Expiration Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            <span class="validate-err">{{ $errors->first('hahc_expired_date') }}</span>
            <div class="form-icon-user">
               {{ Form::date('hahc_expired_date', $data->hahc_expired_date, array('class' => 'form-control')) }}
            </div>
            <span class="validate-err" id="err_hahc_expired_date"></span>
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group">
            {{ Form::label('hahc_remarks', __('Remarks or Additional Instruction'),['class'=>'form-label']) }}
            <span class="validate-err">{{ $errors->first('hahc_remarks') }}</span>
            <div class="form-icon-user">
               {{ Form::text('hahc_remarks', $data->hahc_remarks, array('class' => 'form-control')) }}
            </div>
            <span class="validate-err" id="err_p_address_street_name"></span>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-6">
         <div class="form-group" id="hahc_recommending_approver_group">
            {{ Form::label('hahc_recommending_approver', __('Prepared By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            <span class="validate-err">{{ $errors->first('hahc_recommending_approver') }}</span>
            <div class="form-icon-user">
               @if($data->employee_occupation)
               {{ Form::select('hahc_recommending_approver',$employee,$data->hahc_recommending_approver, array('class' => 'form-control select3','id'=>'hahc_recommending_approver',($auth->id == $data->hahc_recommending_approver || $auth->id == $data->hahc_approver || $data->hahc_recommending_approver_status == 1)? 'disabled':'')) }}
               @else
                {{ Form::select('hahc_recommending_approver',$employee,$data->hahc_recommending_approver, array('class' => 'form-control select3','id'=>'hahc_recommending_approver',($auth->id == $data->hahc_recommending_approver || $auth->id == $data->hahc_approver || $data->hahc_recommending_approver_status == 1)? '':'')) }}
                @endif
            </div>
            <span class="validate-err" id="err_hahc_recommending_approver"></span>
         </div>
      </div>
      <div class="col-md-6">
         <div class="form-group">
            {{ Form::label('hahc_recommending_approver_position', __('Position'),['class'=>'form-label']) }}
            <span class="validate-err">{{ $errors->first('hahc_recommending_approver_position') }}</span>
            <div class="form-icon-user">
               {{ Form::text('hahc_recommending_approver_position', $data->hahc_recommending_approver_position, array('class' => 'form-control','id'=>'hahc_recommending_approver_position',($auth->id == $data->hahc_recommending_approver || $auth->id == $data->hahc_approver || $data->hahc_recommending_approver_status == 1)? '':'')) }}
            </div>
            <span class="validate-err" id="err_hahc_recommending_approver_position"></span>
         </div>
      </div>
   </div>
   <div class="row">
      @if($data->employee_occupation)
      @if($data->id > 0 && $auth->id == $data->hahc_recommending_approver || $data->hahc_recommending_approver_status == 1)
      <div class="col-md-12">
         <div class="form-group">
            <span class="validate-err">{{ $errors->first('hahc_recommending_approver_status') }}</span>
            <div class="form-icon-user">
               {{ Form::checkbox('hahc_recommending_approver_status', '0', ($data->hahc_recommending_approver_status)?true:false, array('id'=>'hahc_recommending_approver_status','class'=>'form-check-input code',(isset($data->id) && $auth->id == $data->hahc_recommending_approver  && $data->hahc_recommending_approver_status == 0) ? '' : 'disabled')) }}
               Approved: Confirmation
            </div>
            <span class="validate-err" id="errhahc_recommending_approver_status"></span>
         </div>
      </div>
      @endif 
      @else
      @endif 
   </div>
   <div class="row">
      <div class="col-md-6">
         <div class="form-group" id="hahc_approver_group">
            {{ Form::label('hahc_approver', __('Approved By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            <span class="validate-err">{{ $errors->first('hahc_approver') }}</span>
            <div class="form-icon-user">
               @if($data->employee_occupation)
               {{ Form::select('hahc_approver',$employee,$data->hahc_approver, array('class' => 'form-control select3','id'=>'hahc_approver',($auth->id == $data->hahc_approver || $data->hahc_approver_status == 1)? 'disabled':'')) }}
               @else
               {{ Form::select('hahc_approver',$employee,$data->hahc_approver, array('class' => 'form-control select3','id'=>'hahc_approver',($auth->id == $data->hahc_approver || $data->hahc_approver_status == 1)? '':'')) }}
               @endif
            </div>
            <span class="validate-err" id="err_hahc_approver"></span>
         </div>
      </div>
      <div class="col-md-6">
         <div class="form-group">
            {{ Form::label('hahc_approver_position', __('Position'),['class'=>'form-label']) }}
            <span class="validate-err">{{ $errors->first('hahc_approver_position') }}</span>
            <div class="form-icon-user">
               {{ Form::text('hahc_approver_position',  $data->hahc_approver_position, array('class' => 'form-control','id'=>'hahc_approver_position',($auth->id == $data->hahc_recommending_approver || $auth->id == $data->hahc_approver || $data->hahc_approver_status == 1)? '':'')) }}
            </div>
            <span class="validate-err" id="err_hahc_approver_position"></span>
         </div>
      </div>
   </div>
   <div class="row">
      @if($data->employee_occupation)
      @if($data->id > 0 && $auth->id == $data->hahc_approver || $data->hahc_approver_status == 1)
      <div class="col-md-12">
         <div class="form-group">
            <span class="validate-err">{{ $errors->first('hahc_approver_status') }}</span>
            <div class="form-icon-user">
               {{ Form::checkbox('hahc_approver_status', '0', ($data->hahc_approver_status)?true:false, array('id'=>'hahc_approver_status','class'=>'form-check-input code',(isset($data->id) && $auth->id == $data->hahc_approver  && $data->hahc_approver_status == 0) ? '' : 'disabled')) }}
               Approved: Confirmation
            </div>
            <span class="validate-err" id="err_hahc_approver_status"></span>
         </div>
      </div>
      @endif 
      @else
      @endif
   </div>
   <div class="row">
      <div class="row field-requirement-details-status" style="background-color : #20B7CC;">
         <div class="col-lg-1 col-md-1 col-sm-1">
            {{Form::label('no',__('No.'),['class'=>'form-label','style' =>'color: #fcfcfc;'])}}
         </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::label('code',__('Kind'),['class'=>'form-label','style' =>'color: #fcfcfc;'])}}
         </div>
         <div class="col-lg-3 col-md-3 col-sm-3">
            {{Form::label('category',__('Category'),['class'=>'form-label','style' =>'color: #fcfcfc;'])}}
         </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::label('date',__('Exam Date'),['class'=>'form-label numeric','style' =>'color: #fcfcfc;'])}}
         </div>
         <div class="col-lg-1 col-md-1 col-sm-1">
            {{Form::label('result',__('Result'),['class'=>'form-label','style' =>'color: #fcfcfc;'])}}
         </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::label('remark',__('Remark'),['class'=>'form-label','style' =>'color: #fcfcfc;'])}}
         </div>
         <div class="col-lg-1 col-md-1 col-sm-1">
            <!-- {{Form::label('Action',__('Action'),['class'=>'form-label','style' =>'color: #fcfcfc;'])}} -->
            <a href="#" id="btn_addmore_healthcert" class="btn btn-sm btn-primary action-item" role="button">
            <i class="ti-plus"></i>
            </a>
         </div>
      </div>
      <span class="Healthcerti nature-details" id="Healthcerti">
         @php $i=0; $j=0;  @endphp  
         @foreach($healthcertreq as $key=>$val)
         @php $j=$j+1; @endphp
         <div class="row removehealthcertidata pt10">
            <div class="col-lg-1 col-md-1 col-sm-1">
               <div class="form-group">
                  <div class="form-icon-user">
                     {{$j}}
                  </div>
               </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
               <div class="form-group">
                  <div class="form-icon-user">
                     {{ Form::select('req_id[]',$requirements, $val->req_id, array('class' => 'form-control req_id_s','id'=>'req_id'.$i)) }}
                     {{ Form::hidden('healthreqid[]',$val->id, array('id' => 'healthreqid','class' => 'healthcl')) }}
                  </div>
               </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
               <div class="form-group">
                  <div class="form-icon-user">
                     {{ Form::select('hahcr_category[]',array('0'=>'Immunization','1'=>'X-Ray','2'=>'Stool and Other Exam'),$val->hahcr_category, array('class' => 'form-control naofbussi')) }} 
                  </div>
               </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
               <div class="form-group">
                  <div class="form-icon-user">
                     {{Form::date('hahcr_exam_date[]',$val->hahcr_exam_date,array('class'=>'form-control'))}}
                  </div>
               </div>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1">
               <div class="form-group">
                  <div class="form-icon-user" >
                     {{Form::text('hahcr_exam_result[]',$val->hahcr_exam_result,array('class'=>'form-control'))}}
                  </div>
               </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
               <div class="form-group">
                  <div class="form-icon-user">
                     {{Form::text('hahcr_remarks[]',$val->hahcr_remarks,array('class'=>'form-control','placeholder'=>'','id'=>'hahcr_remarks'))}}
                  </div>
               </div>
            </div>
            <div class='action-btn bg-danger col-sm-1'>
               <a href='#' class='mx-3 btn btn-sm btn_cancel_healthcert ti-trash text-white text-white'></a>
            </div>
            @php $i++; @endphp
         </div>
         @endforeach
      </span>
   </div>
   @if($data->id > 0)
   <div class="row">
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
               <div id="flush-collapse3" class="accordion-collapse collapse" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                  <div class="basicinfodiv">
                     <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                           <div class="form-group">
                              {{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
                              <div class="form-icon-user">
                                 {{ Form::input('file','document_name','',array('class'=>'form-control'))}}  
                              </div>
                              <span class="validate-err" id="err_document"></span>
                           </div>
                        </div>
                           <div class="col-lg-2 col-md-2 col-sm-2" style="padding-top:26px;">
                              <button type="button" style="float: right;" class="btn btn-primary" id="uploadAttachment">Upload File</button>
                           </div>
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
                                 <thead id="DocumentDtls">
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
   </div>
   @endif
   <div class="row" >
      <div class="col-md-3" style="padding-top:17px;">
         @if($data->hahc_recommending_approver_status == 1 && $data->hahc_approver_status == 1)
         <a href="{{ url('healthy-and-safety/health-certificate/hoapphealthcertPrint?id=').''.$data->id }}" target="_blank" title="Print Application Form" style="background: #20b7cc;padding: 10px;color: #fff;" data-title="Print" class="mx-3 btn print btn-sm digital-sign-btn" id="{{$data->id}}">
              <i class="ti-printer text-white"></i> Print
          </a>
         @endif
      </div>
      <div class="col-md-9">
         <div class="modal-footer" style="">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="submit" name="submit" value="{{($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
         </div>
      </div>
   </div>
</div>
</div>
{{Form::close()}}
@php 
$ii=(count($healthcertreq)>0)?count($healthcertreq):0;
@endphp
<div id="hidenhealthcertiHtml" class="hide">
   <div class="removehealthcertidata row pt10">
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="form-group">
            <div class="form-icon-user">
               <div id="increment"></div>
            </div>
         </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2">
         <div class="form-group">
            <div class="form-icon-user">
               {{Form::select('req_id[]',$requirements,'',array('class'=>'form-control req_id','id'=>'req_id'.$ii,'required'=>'required'))}}
            </div>
         </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3">
         <div class="form-group">
            <div class="form-icon-user"> 
               {{ Form::select('hahcr_category[]',array('0'=>'Immunization','1'=>'X-Ray','2'=>'Stool and Other Exam'), '', array('class' => 'form-control naofbussi')) }} 
            </div>
         </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2">
         <div class="form-group">
            <div class="form-icon-user">
               {{Form::date('hahcr_exam_date[]','',array('class'=>'form-control numeric'))}}
            </div>
         </div>
      </div>
      <div class="col-lg-1 col-md-1 col-sm-1">
         <div class="form-group">
            <div class="form-icon-user">
               {{Form::text('hahcr_exam_result[]','',array('class'=>'form-control','placeholder'=>''))}}
            </div>
         </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2">
         <div class="form-group">
            <div class="form-icon-user">
               {{Form::text('hahcr_remarks[]','',array('class'=>'form-control','placeholder'=>'','id'=>'hahcr_remarks'))}}
            </div>
         </div>
      </div>
      <div class='action-btn bg-danger col-sm-1'>
         <a href='#' class='mx-3 btn btn-sm btn_cancel_healthcert ti-trash text-white text-white'></a>
      </div>
   </div>
</div>

<script src="{{ asset('js/hoapphealthcert.js') }}"></script>
<!-- <script src="{{ asset('js/ajax_validation.js') }}"></script> -->
<script src="{{ asset('js/add_hoapphealthcert.js') }}?rand={{ rand(000,999) }}"></script>
<script>
$(document).ready(function () {
    $('form').submit(function(e) {
        e.preventDefault();
        $(".validate-err").html('');
        var myForm = $(this);
        myForm.find("input[name='submit']").unbind("click");
        // var myform = $('form');
        var disabled = myForm.find(':input:disabled').removeAttr('disabled');
        var data = myForm.serialize().split("&");
        disabled.attr('disabled','disabled');
        var obj={};
        for(var key in data){
            obj[decodeURIComponent(data[key].split("=")[0])] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :$(this).attr("action")+'/formValidation', // json datasource
            type: "POST", 
            data: obj,
            dataType: 'json',
            success: function(html){
                if(html.ESTATUS){
                    $("#err_"+html.field_name).html(html.error)
                    $("#"+html.field_name).focus();
                    $("#main_error_msg").html(html.error)

                }else{
					setConfirmAlert(e); 
                }
            }
        })
    });
	function setConfirmAlert(e){
		$("#healthcertificate input[name='submit']").unbind("click");
		const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: 'btn btn-success',
				cancelButton: 'btn btn-danger'
			},
			buttonsStyling: false
		})
		swalWithBootstrapButtons.fire({
			title: 'Are you sure?',
			text: "This will save the current changes.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes',
			cancelButtonText: 'No',
			reverseButtons: true
		}).then((result) => {
			if(result.isConfirmed){
				$('#healthcertificate').unbind('submit');
				$("#healthcertificate input[name='submit']").trigger("click");
				$("#healthcertificate input[name='submit']").attr("type","button");
			}
		});
   }
	
});
</script>
