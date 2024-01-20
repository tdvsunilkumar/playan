{{ Form::open(array('url' => 'fees-master/business-sanitary-fee/store')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('bsf_category_code',$data->bsf_category_code, array('id' => 'bsf_category_code')) }}
     {{ Form::hidden('areamount',$data->bsf_fee_amount, array('id' => 'areamount')) }}
    {{ Form::hidden('prev_tax_type_id',$data->tax_type_id, array('id' => 'prev_tax_type_id')) }}
    <div class="modal-body">
        <div class="row"> 
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tax_class_id', __('Tax Class'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tax_class_id',$arrTaxClasses,$data->tax_class_id, array('class' => 'form-control select3','id'=>'tax_class_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tax_class_id"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tax_type_id', __('Tax Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('tax_type_id',$arrTaxTypes,$data->tax_type_id, array('class' => 'form-control select3','id'=>'tax_type_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tax_type_id"></span>
                </div>
            </div>  
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bbc_classification_code', __('Classification'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('bbc_classification_code',$arrClassificationCode,$data->bbc_classification_code, array('class' => 'form-control select3','id'=>'bbc_classification_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbc_classification_code"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bba_code', __('Business Activity'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{ Form::select('bba_code',$arrbbaCode,$data->bba_code, array('class' => 'form-control select3','id'=>'bba_code','required'=>'required')) }}
                        <!--  {{ Form::text('bba_code', $data->bba_code, array('class' => 'form-control','required'=>'required')) }} -->
                    </div>
                    <span class="validate-err" id="err_bbc_activity_code"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bsf_code', __('Business Classification Code'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('bsf_code', $data->bsf_code, array('class' => 'form-control','readonly' => 'true','required'=>'required','id'=>'bsf_code')) }}
                    </div>
                    <span class="validate-err" id="err_bbc_classification_code"></span>
                </div>
            </div>
             <div class="col-md-12">
                <div class="d-flex radio-check">
                <div class="form-check form-check-inline form-group col-md-2">
                    {{ Form::radio('bsf_fee_option', '0', ($data->bsf_fee_option=='0')?true:false, array('id'=>'none','class'=>'form-check-input feeoption','required'=>'required')) }}
                    {{ Form::label('feeoption', __('None'),['class'=>'form-label']) }}
                </div>
                <div class="form-check form-check-inline form-group col-md-3">
                    {{ Form::radio('bsf_fee_option', '1', ($data->bsf_fee_option=='1')?true:false, array('id'=>'basicfee','class'=>'form-check-input feeoption')) }}
                    {{ Form::label('feeoption', __('Basic By Activity'),['class'=>'form-label']) }}
                </div>
                <div class="form-check form-check-inline form-group col-md-3">
                    {{ Form::radio('bsf_fee_option', '2', ($data->bsf_fee_option=='2')?true:false, array('id'=>'bycategory','class'=>'form-check-input feeoption','disabled'=>'disabled')) }}
                    {{ Form::label('feeoption', __('Basic By Category'),['class'=>'form-label']) }}
                </div>
                <div class="form-check form-check-inline form-group col-md-2">
                    {{ Form::radio('bsf_fee_option', '3', ($data->bsf_fee_option=='3')?true:false, array('id'=>'byarea','class'=>'form-check-input feeoption')) }}
                    {{ Form::label('feeoption', __('By Area(Sq.M)'),['class'=>'form-label']) }}
                </div>
                </div>
            </div>
              <div class="col-lg-12 col-md-12 col-sm-12" id="basicfeediv">  
                <div  class="accordion accordion-flush">
                  <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" style="">
                            <h6 class="sub-title accordiantitle">{{__("Fee Schedule")}}</h6>
                        </button>
                     </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
                              <div class="row">    
                                   <div class="col-md-6">
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" style="padding-top: 30px;">
                                                <div class="form-icon-user">
                                                   {{ Form::label('bsf_fee_amount', __('Sanitary Fee'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group currency" style="padding-top: 20px;">
                                                 <div class="form-icon-user">
                                                      {{ Form::text('bsf_fee_amount', $data->bsf_fee_amount, array('class' => 'form-control','required'=>'required')) }}
                                                       <div class="currency-sign"><span>Php</span></div>
                                                    </div>
                                                    <span class="validate-err" id="err_bgf_fee_amoun"></span>
                                            </div>
                                        </div>
                                    </div>
                                  
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                        {{ Form::label('bsf_fee_schedule_option', __('Schedule Option'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        </div>

                                          <div class="d-flex radio-check">
                                            <div class="form-check form-group col-md-12">
                                                {{ Form::radio('bsf_fee_schedule_option', '1', ($data->bsf_fee_schedule_option=='1')?true:false, array('id'=>'basic','class'=>'form-check-input feeoption','required'=>'required')) }}
                                                {{ Form::label('feeoption', __('Indicated In SANITARY FEE'),['class'=>'form-label']) }}
                                            </div>
                                            </div>
                                           <div class="d-flex radio-check">
                                            <div class="form-check  form-group col-md-12">
                                                {{ Form::radio('bsf_fee_schedule_option', '2', ($data->bsf_fee_schedule_option=='2')?true:false, array('id'=>'byitem','class'=>'form-check-input feeoption')) }}
                                                {{ Form::label('feeoption', __('SANITARY FEE multiplied by items declared'),['class'=>'form-label']) }}
                                            </div>
                                            </div>
                                        </div>
                                        <span class="validate-err" id="err_bpt_fee_schedule_option"></span>
                               </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                    {{ Form::label('bsf_tax_schedule', __('Tax Schedule'),['class'=>'form-label']) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex radio-check">
                                        <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('bsf_tax_schedule', '1', ($data->bsf_tax_schedule =='1')?true:false, array('id'=>'annualy','class'=>'form-check-input code','required'=>'required')) }}
                                            {{ Form::label('bsf_tax_schedule', __('Annually'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('bsf_tax_schedule', '2', ($data->bsf_tax_schedule =='2')?true:false, array('id'=>'queterly','class'=>'form-check-input code')) }}
                                            {{ Form::label('bsf_tax_schedule', __('Quaterly'),['class'=>'form-label']) }}
                                        </div>
                                    </div>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 hide" id="bycategorydiv">  
                <div  class="accordion accordion-flush">
                  <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" style="">
                            <h6 class="sub-title accordiantitle">{{__("Basic By Category")}}</h6>
                        </button>
                    </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
                            <div class="row">
                             <table class="table-responsive">
                                <thead><tr><th style="padding-left: 10px">No</th><th>Code</th><th>Category Description</th><th>Fee Amount</th><th>Ached</th></tr></thead><tbody id="categorydynamic">
                                </tbody>
                             </table>
                          </div>
                        </div>
                    </div>
                </div>
           </div>

             <div class="col-lg-12 col-md-12 col-sm-12 hide" id="byareadiv">  
                <div  class="accordion accordion-flush">
                  <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" style="">
                            <h6 class="sub-title accordiantitle">{{__("Based On Area(Sq.M)")}}</h6>
                        </button>
                    </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
                            <div class="row">
                             <table class="table-responsive">
                                <thead><tr><th style="padding-left: 10px">No</th><th>Minimum</th><th>Maximum</th><th>Fee Amount</th><th>Ached</th></tr></thead><tbody id="areadynamic">
                                </tbody>
                             </table>
                          </div>
                        </div>
                    </div>
                </div>
           </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bsf_revenue_code', __('Revenue code description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::text('bsf_revenue_code', $data->bsf_revenue_code, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bsf_revenue_code"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bsf_remarks', __('Remarks'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::text('bsf_remarks', $data->bsf_remarks, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bsf_remarks"></span>
                </div>
            </div>
        </div>
        <!-- <div class="row">
            <div class="d-flex radio-check">
                <div class="form-check form-check-inline form-group col-md-1">
                    {{ Form::radio('is_active', '1', ($data->is_active)?true:false, array('id'=>'active','class'=>'form-check-input code')) }}
                    {{ Form::label('active', __('Active'),['class'=>'form-label']) }}
                </div>
                <div class="form-check form-check-inline form-group col-md-1">
                    {{ Form::radio('is_active', '0', (!$data->is_active)?true:false, array('id'=>'inactive','class'=>'form-check-input code')) }}
                    {{ Form::label('inactive', __('InActive'),['class'=>'form-label']) }}
                </div>
            </div>
        </div> -->
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/addBusinessSanitaryfee.js') }}"></script>



