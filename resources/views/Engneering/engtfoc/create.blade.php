{{ Form::open(array('url' => 'engtfoc','class'=>'formDtls')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('gl_account_id',$data->gl_account_id, array('id' => 'gl_account_id')) }}
{{ Form::hidden('isessential','0', array('id' => 'isessential')) }}
{{ Form::hidden('is_business_tax_non_essential',$data->is_business_tax_non_essential, array('id' => 'is_business_tax_non_essential')) }}
<style type="text/css">
     .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 17px;
        color: #fff;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .btn{padding: 0.575rem 0.5rem;}
    .field-requirement-details-status label{padding-top:5px;}
    .nofile{width: 39px; text-align: center;}
    .accordion-button::after {
    background-image: url();
  }
</style>
<div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('fund_id', __('Fund Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('fund_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('fund_id',$getFundCodes,$data->fund_id, array('class' => 'form-control select3','id'=>'fund_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_fund_id"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group" id="chargesdiv">
                                {{ Form::label('ctype_id', __('Type of Charges'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('ctype_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('ctype_id',$arrChargestype,$data->ctype_id, array('class' => 'form-control','id'=>'ctype_id')) }}
                                </div>
                                <span class="validate-err" id="err_ebpa_application_no"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('tfoc_old_code', __('Code'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('tfoc_old_code') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('tfoc_old_code',$data->tfoc_old_code, array('class' => 'form-control','id'=>'tfoc_old_code')) }}
                                </div>
                                <span class="validate-err" id="err_ebpa_permit_no"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">   
                       <div class="col-md-12">
                            <div class="form-group" id="sliddiv">
                                {{ Form::label('sl_id', __('Chart of Account'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('sl_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('sl_id',$arrGeneralReader,$data->sl_id, array('class' => 'form-control','id'=>'sl_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_sl_id"></span>
                            </div>
                        </div>
                         <div class="col-md-8">
                            <div class="form-group">
                                {{ Form::label('gl_code', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('sl_id') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('gl_code',$arrSubsidiaryLeader,$data->gl_account_id, array('class' => 'form-control','id'=>'gl_code','required'=>'required','readonly'=>'readonly')) }}
                                </div>
                                <span class="validate-err" id="err_gl_account_id"></span>
                            </div>
                        </div>  
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('tfoc_short_name', __('Short Name'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('tfoc_short_name') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('tfoc_short_name',$data->tfoc_short_name, array('class' => 'form-control','id'=>'tfoc_short_name')) }}
                                </div>
                                <span class="validate-err" id="err_tfoc_short_name"></span>
                            </div>
                        </div>
                         <div class="col-md-8">
                            <div class="form-group">
                                {{ Form::label('tfoc_is_applicable', __('Applicable Department (Cashiering)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('tfoc_is_applicable') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('tfoc_is_applicable',$arrDepaertments,$data->tfoc_is_applicable, array('class' => 'form-control select3 ','id'=>'tfoc_is_applicable','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_tfoc_is_applicable"></span>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('tfoc_amount', __('Amount'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('tfoc_amount') }}</span>
                                <div class="form-icon-user">
                                     <div class="form-icon-user currency">
                                    {{ Form::number('tfoc_amount',$data->tfoc_amount, array('class' => 'form-control','id'=>'tfoc_amount')) }}
                                    <div class="currency-sign"><span>Php</span></div>
                                    </div>
                                </div>
                                <span class="validate-err" id="err_tfoc_short_name"></span>
                            </div>
                        </div>
                        
                          <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('tfoc_status', __('Status'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('tfoc_status') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('tfoc_status',array('1'=>'Active','0'=>'In Active'),$data->tfoc_status, array('class' => 'form-control select3 ','id'=>'tfoc_status','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_agl_code"></span>
                            </div>
                        </div>
                          <div class="col-md-12" id="totalofaccount" style="display:none;">
                            <div class="form-group">
                                <label for="total_of_sl_id" class="form-label">Total Of (<span id="totaldesc"></span>)</label>
                                <span class="validate-err">{{ $errors->first('total_of_sl_id') }}</span>
                                <div class="form-icon-user">
                                    {{Form::select('total_of_sl_id',$arrGeneralReadertotal,$data->total_of_sl_id, array('class' => 'form-control','id'=>'total_of_sl_id')) }}
                                </div>
                                <span class="validate-err" id="err_total_of_sl_id"></span>
                            </div>
                        </div>
                         <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('tfoc_remarks', __('Remarks'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('tfoc_remarks') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::textarea('tfoc_remarks',$data->tfoc_remarks, array('class' => 'form-control','id'=>'tfoc_remarks','rows'=>'2')) }}
                                </div>
                                <span class="validate-err" id="err_agl_code"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row hide" id="bussanfengblock">
                      <div class="row">
                         <div class="col-md-12">
                                     <div class="form-group">
                                        {{ Form::checkbox('tfoc_surcharge_fee','1', ($data->tfoc_surcharge_fee)?true:false, array('id'=>'tfoc_surcharge_fee','class'=>'form-check-input code2')) }} {{Form::label('',__('Surcharge Fee'),['class'=>'form-label'])}}
                                      </div>
                                       <span class="validate-err" id="err_tfoc_surcharge_fee"></span>
                              </div>
                            <div class="col-md-12 hide" id="tfoc_surcharge_sl_iddiv">
                               <div class="form-group">
                                <div class="form-icon-user">
                                    {{ Form::select('tfoc_surcharge_sl_id',$arrGeneralReader,$data->tfoc_surcharge_sl_id, array('class' => 'form-control select3 ','id'=>'tfoc_surcharge_sl_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_tfoc_surcharge_sl_id"></span>
                                </div>
                             </div>
                         </div>
                     </div>
                     <div class="row">
                            @if($data->tfoc_is_applicable =='9')
                                <div class="col-md-12">
                                     <div class="form-group">
                                        {{ Form::checkbox('is_taxpayer_charges','1', ($data->is_taxpayer_charges)?true:false, array('id'=>'is_taxpayer_charges','class'=>'form-check-input')) }} {{Form::label('',__('Taxpayers Charges'),['class'=>'form-label'])}}
                                      </div>
                                      <span class="validate-err" id="err_tfoc_interest_fee"></span>
                                 </div>
                             @else    
                               @if($data->id)
                                <div class="col-md-12">
                                     <div class="form-group">
                                        {{ Form::checkbox('is_taxpayer_charges','1', ($data->is_taxpayer_charges)?true:false, array('id'=>'is_taxpayer_charges','class'=>'form-check-input code2','disabled'=>'true')) }} {{Form::label('',__('Taxpayers Charges'),['class'=>'form-label'])}}
                                      </div>
                                      <span class="validate-err" id="err_tfoc_interest_fee"></span>
                                </div>
                              @else
                                 <div class="col-md-12">
                                     <div class="form-group">
                                        {{ Form::checkbox('is_taxpayer_charges','1', ($data->is_taxpayer_charges)?true:false, array('id'=>'is_taxpayer_charges','class'=>'form-check-input')) }} {{Form::label('',__('Taxpayers Charges'),['class'=>'form-label'])}}
                                      </div>
                                      <span class="validate-err" id="err_tfoc_interest_fee"></span>
                                 </div>
                              @endif
                            @endif  
                    </div> 
                    <div class="row hide" id="bussinesspermitblock">
                      <div class="row">
                            <div class="row">
                                 <div class="col-md-12">
                                     <div class="form-group">
                                        {{ Form::checkbox('tfoc_interest_fee','1', ($data->tfoc_interest_fee)?true:false, array('id'=>'tfoc_interest_fee','class'=>'form-check-input code2')) }} {{Form::label('',__('Interest Fee'),['class'=>'form-label'])}}
                                      </div>
                                      <span class="validate-err" id="err_tfoc_interest_fee"></span>
                                   </div>
                                   <div class="col-md-12" id="tfoc_interest_sl_iddiv">
                                   <div class="form-group">
                                    <div class="form-icon-user">
                                        {{ Form::select('tfoc_interest_sl_id',$arrGeneralReader,$data->tfoc_interest_sl_id, array('class' => 'form-control select3','id'=>'tfoc_interest_sl_id')) }}
                                    </div>
                                    <span class="validate-err" id="err_tfoc_surcharge_sl_id"></span>
                                    </div>
                                 </div> 
                            </div> 
                          <div class="col-sm-12">  
                          <div class="row">
                                <div class="col-md-6">
                                 <div class="form-group">
                                    {{ Form::checkbox('tfoc_divided_fee','1', ($data->tfoc_divided_fee)?true:false, array('id'=>'bff_req1','class'=>'form-check-input code2')) }} {{Form::label('',__('Divided Fee'),['class'=>'form-label'])}}
                                  </div>
                                   <span class="validate-err" id="err_tfoc_divided_fee"></span>
                               </div>
                               <div class="col-md-6">
                                 <div class="form-group">
                                    {{ Form::checkbox('tfoc_surcharge_interest_fee','1', ($data->tfoc_surcharge_interest_fee)?true:false, array('id'=>'tfoc_surcharge_interest_fee','class'=>'form-check-input code2')) }} {{Form::label('',__('Default Surcharge and Interest'),['class'=>'form-label'])}}
                                  </div>
                                   <span class="validate-err" id="err_tfoc_surcharge_interest_fee"></span>
                               </div>
                           </div>
                           <div class="row">
                                <div class="col-md-6">
                                 <div class="form-group">
                                    {{ Form::checkbox('tfoc_iterated_fee','1', ($data->tfoc_iterated_fee)?true:false, array('id'=>'bff_req1','class'=>'form-check-input code2')) }} {{Form::label('',__('Iterated Fee'),['class'=>'form-label'])}}
                                  </div>
                                  <span class="validate-err" id="err_tfoc_iterated_fee"></span>
                               </div>
                               <div class="col-md-6">
                                     <div class="form-group">
                                        {{ Form::checkbox('tfoc_fire_code_fee','1', ($data->tfoc_fire_code_fee)?true:false, array('id'=>'bff_req1','class'=>'form-check-input code2')) }} {{Form::label('',__('Is included in Fire Safety Inspection Fee'),['class'=>'form-label'])}}
                                       </div>
                                       <span class="validate-err" id="err_tfoc_fire_code_fee"></span>
                                </div>
                          </div>
                          <div class="row">
                                <div class="col-md-6">
                                     <div class="form-group">
                                        {{ Form::checkbox('tfoc_common_fee','1', ($data->tfoc_common_fee)?true:false, array('id'=>'bff_req1','class'=>'form-check-input code2')) }} {{Form::label('',__('Common Fee'),['class'=>'form-label'])}}
                                      </div>
                                       <span class="validate-err" id="err_tfoc_common_fee"></span>
                                 </div>
                                  <div class="col-md-6" id="optionalfeediv">
                                     <div class="form-group">
                                        {{ Form::checkbox('tfoc_is_optional_fee','1', ($data->tfoc_is_optional_fee)?true:false, array('id'=>'tfoc_is_optional_fee','class'=>'form-check-input code2')) }} {{Form::label('',__('Optional Fee'),['class'=>'form-label'])}}
                                       </div>
                                       <span class="validate-err" id="err_tfoc_is_optional_fee"></span>
                                </div>
                             </div>

                         </div>
                           </div>
                           
                        </div>
                         <div class="row hide" id="bussinessusage">   
                            <div class="col-md-6">
                                 <div class="form-group">
                                    {{ Form::checkbox('tfoc_usage_business_permit','1', ($data->tfoc_usage_business_permit)?true:false, array('id'=>'tfoc_usage_business_permit','class'=>'form-check-input code2')) }} {{Form::label('',__('Usage: Business Permit'),['class'=>'form-label'])}}
                                  </div>
                                   <span class="validate-err" id="err_tfoc_usage_business_permit"></span>
                              </div>
                              <div class="row">
                                <div class="col-md-6 hide">
                                     <div class="form-group">
                                        {{ Form::checkbox('tfoc_eachlineof_bussiness','1', ($data->tfoc_eachlineof_bussiness)?true:false, array('id'=>'tfoc_eachlineof_bussiness','class'=>'form-check-input code2')) }} {{Form::label('',__('Calculation : Each Line of Bussiness'),['class'=>'form-label'])}}
                                      </div>
                                       <span class="validate-err" id="err_tfoc_common_fee"></span>
                                 </div>
                             </div>
                             <div class="row hide" id="essentialdiv">
                             <div class="form-group" style="margin-bottom:-0.5rem">
                                <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('is_business_tax_essential', '1', ($data->is_business_tax_essential =='1')?true:false, array('id'=>'is_business_tax_essential','class'=>'form-check-input code')) }}
                                            {{ Form::label('essential', __('Essential'),['class'=>'form-label']) }}
                                 </div>
                             </div>
                              <div class="form-group">
                                <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('is_business_tax_essential', '2', ($data->is_business_tax_non_essential =='1')?true:false, array('id'=>'is_business_tax_nonessential','class'=>'form-check-input code')) }}
                                            {{ Form::label('nonessential', __('Non-Essential'),['class'=>'form-label']) }}
                                 </div>
                             </div>
                             </div>    
                        </div>
                         <div class="row hide" id="realproperty">   
                         <div class="col-md-6">
                                 <div class="form-group">
                                    {{ Form::checkbox('tfoc_usage_real_property','1', ($data->tfoc_usage_real_property)?true:false, array('id'=>'tfoc_usage_real_property','class'=>'form-check-input code2')) }} {{Form::label('',__('Usage: Real Property'),['class'=>'form-label'])}}
                                  </div>
                                   <span class="validate-err" id="err_tfoc_usage_real_property"></span>
                            </div>
                        </div>
                         <div class="row hide" id="engneeringfee"> 
                             <div class="col-md-6">
                                 <div class="form-group">
                                    {{ Form::checkbox('tfoc_usage_engineering','1', ($data->tfoc_usage_engineering)?true:false, array('id'=>'bff_req1','class'=>'form-check-input code2')) }} {{Form::label('',__('Usage Engineering'),['class'=>'form-label'])}}
                                  </div>
                                  <span class="validate-err" id="err_tfoc_usage_engineering"></span>
                               </div>
                           </div>
                        
                        <div class="row hide" id="engothertaxes">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row field-requirement-details-status">
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                    {{Form::label('subclass_id',__('No.'),['class'=>'form-label'])}}
                                </div>  
                                <div class="col-lg-7 col-md-7 col-sm-7">
                                    {{Form::label('subclass_id',__('Other Taxes'),['class'=>'form-label'])}}
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    {{Form::label('taxable_item_qty',__('Percentage'),['class'=>'form-label numeric'])}}
                                </div>
                               <div class="col-md-2"><span class="btn_addmore_othertaxes btn" id="btn_addmore_othertaxes" style="color:white;"><i class="ti-plus" style="font-size: 20px;font-weight:600;"></i></span></div>
                            </div>
                               <span class="otherfeeDetails" id="otherfeeDetails">
                                    @php $i=1; @endphp
                                    @foreach($othertaxesarr as $key=>$val)
                                    <div class="removeothertaxes row pt10">
                                        <div class="col-lg-1 col-md-1 col-sm-1">
                                            <div class="form-group">
                                                <div class="form-icon-user"><p style="text-align: center;">{{$i}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <div class="form-group"> <div class="form-icon-user">{{ Form::select('otaxes_sl_id[]',$arrGeneralReader,$val->otaxes_sl_id, array('class' => 'form-control otaxes_sl_id ','id'=>'otaxes_sl_idall'.$key,'required'=>'required')) }}</div>
                                            {{ Form::hidden('otid[]',$val->id, array('id' => 'otid')) }}
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">{{ Form::text('otaxes_percent[]',$val->otaxes_percent, array('class' => 'form-control ','id'=>'otaxes_percent','required'=>'required')) }}
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <button type="button" class="btn btn-danger btn_cancel_othertaxes" id="{{$val->id}}"><i class="ti-trash"></i></button>
                                        </div>
                                    </div>
                                    @php $i++; @endphp
                                    @endforeach
                                <soan>
                            </div>
                           </div>
                            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
                        <div  class="accordion accordion-flush" >
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-headingone" >
                                    <button class="accordion-button collapsed btn-primary btn-endorsementStatus" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                        <h6 class="sub-title accordiantitle">{{__("NOTE")}}
                                        </h6>
                                    </button>
                                        <div id="flush-collapseone" class="accordion-collapse collapse" aria-labelledby="flush-headingone" data-bs-parent="#accordionFlushExample" >
                                            <div class="basicinfodiv">
                                                <!--  <a data-toggle="modal" href="javascript:void(0)" id="loadAddserviceForm" class="btn btn-primary" type="add">Add Service</a> -->
                                                <!--------------- Land Apraisal Listing Start Here------------------>
                                                <div class="row"> 
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            {{ Form::label('divided', __('Divided Fee'),['class'=>'form-label']) }}
                                                            <div class="form-icon-user">
                                                               <p>Divided based on the payment schedule</p>
                                                               <p><span class="btn btn-success">Example:</span>Quarterly And Semi -Annual</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            {{ Form::label('interest', __('Interest'),['class'=>'form-label']) }}
                                                            <div class="form-icon-user">
                                                               <p>Used to basis to compute the interest</p>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row"> 
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            {{ Form::label('divided', __('Iterated Fee'),['class'=>'form-label']) }}
                                                            <div class="form-icon-user">
                                                               <p>Changed for certain number of times to multiple line of business</p>
                                                               <p><span class="btn btn-success">Example:</span>Business Tax</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            {{ Form::label('interest', __('Surcharge'),['class'=>'form-label']) }}
                                                            <div class="form-icon-user">
                                                               <p>Used to basis to compute the surcharge</p>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                 <div class="row"> 
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            {{ Form::label('divided', __('Common Fee'),['class'=>'form-label']) }}
                                                            <div class="form-icon-user">
                                                               <p>Common to all line of businesses</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            {{ Form::label('interest', __('Fire Code Fee'),['class'=>'form-label']) }}
                                                            <div class="form-icon-user">
                                                               <p>Considered in the computation of fees for the FSIC (15%)</p>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                 <div class="row"> 
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            {{ Form::label('divided', __('Optional Fee'),['class'=>'form-label']) }}
                                                            <div class="form-icon-user">
                                                               <p>Fee can be deleted in the assessment</p>
                                                               <p><span class="btn btn-success">Example:</span>Barangay Clearance Fee</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                    </div>
                    
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Save Changes')}}" class="btn  btn-primary"> -->
                    </div>
        </div>    
    {{Form::close()}}
<div id="hidenOthertaxesHtml" class="hide">
     <div class="row removeothertaxes" style="padding: 5px 0px;">
           <div class="col-md-8">
                <div class="form-group">
                    <div class="form-icon-user">
                        {{ Form::select('otaxes_sl_id[]',$arrGeneralReader,'', array('class' => 'form-control otaxes_sl_id ','id'=>'otaxes_sl_id0','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_otaxes_sl_id"></span>
                </div>
           </div>
         <div class="col-md-2">
                <div class="form-group">
                    <div class="form-icon-user">
                        {{ Form::text('otaxes_percent[]','', array('class' => 'form-control ','id'=>'otaxes_percent','required'=>'required')) }}
                    </div>
                </div>
           </div>
         <div class="col-lg-2 col-md-2 col-sm-2"><div class="form-group"><button type="button" class="btn btn-primary btn_cancel_othertaxes"><i class="ti-trash"></i></button></div></div>
    </div>
</div>
 <script src="{{ asset('js/ajax_validation_confir_mass.js') }}"></script>  
<script src="{{ asset('js/Engneering/add_ctotfoc.js') }}?rand={{ rand(000,999) }}"></script>  
 
           