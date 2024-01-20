{{ Form::open(array('url' => 'fees-master/business-permit-fee/store')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
     {{ Form::hidden('bpt_cagetory_code',$data->bpt_cagetory_code, array('id' => 'bpt_cagetory_code')) }}
     {{ Form::hidden('areamount',$data->bpt_permit_fee_amount, array('id' => 'areamount')) }}
    {{ Form::hidden('prev_tax_type_id',$data->tax_type_id, array('id' => 'prev_tax_type_id')) }}
    <style type="text/css">
        .accordion-button::after{background-image: url();}
    </style>
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
                    </div>
                    <span class="validate-err" id="err_bba_code"></span>
                </div>
            </div>
              <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bpf_code', __('Business Classification Code'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('bpf_code', $data->bpf_code, array('class' => 'form-control','readonly' => 'true','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bpf_code"></span>
                </div>
            </div>
             <div class="col-md-12">
                <div class="d-flex radio-check">
                <div class="form-check form-check-inline form-group col-md-2">
                    {{ Form::radio('bpt_fee_option', '0', ($data->bpt_fee_option=='0')?true:false, array('id'=>'none','class'=>'form-check-input feeoption','required'=>'required')) }}
                    {{ Form::label('feeoption', __('None'),['class'=>'form-label']) }}
                </div>
                <div class="form-check form-check-inline form-group col-md-2">
                    {{ Form::radio('bpt_fee_option', '1', ($data->bpt_fee_option=='1')?true:false, array('id'=>'basicfee','class'=>'form-check-input feeoption')) }}
                    {{ Form::label('feeoption', __('Basic Fee'),['class'=>'form-label']) }}
                </div>
                <div class="form-check form-check-inline form-group col-md-2">
                    {{ Form::radio('bpt_fee_option', '2', ($data->bpt_fee_option=='2')?true:false, array('id'=>'bycategory','class'=>'form-check-input feeoption')) }}
                    {{ Form::label('feeoption', __('By Category'),['class'=>'form-label']) }}
                </div>
                <div class="form-check form-check-inline form-group col-md-2">
                    {{ Form::radio('bpt_fee_option', '3', ($data->bpt_fee_option=='3')?true:false, array('id'=>'byarea','class'=>'form-check-input feeoption')) }}
                    {{ Form::label('feeoption', __('By Area'),['class'=>'form-label']) }}
                </div>
                <div class="form-check form-check-inline form-group col-md-2">
                    {{ Form::radio('bpt_fee_option', '4', ($data->bpt_fee_option=='4')?true:false, array('id'=>'bytaxpaid','class'=>'form-check-input feeoption')) }}
                    {{ Form::label('feeoption', __('By Tax Paid'),['class'=>'form-label']) }}
                </div>
                </div>
            </div>
             <div class="col-lg-12 col-md-12 col-sm-12 hide" id="basicfeediv">  
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
                                                   {{ Form::label('bpt_permit_fee_amount', __('Permit Fee'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" style="padding-top: 20px;">
                                                <div class="form-icon-user">
                                                   {{ Form::text('bpt_permit_fee_amount', $data->bpt_permit_fee_amount, array('class' => 'form-control')) }}
                                                </div>
                                                <span class="validate-err" id="err_bpt_permit_fee_amount"></span>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" style="padding-top: 30px;">
                                                <div class="form-icon-user">
                                                  {{ Form::label('bpt_item_count', __('Item Count'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" style="padding-top: 20px;">
                                                <div class="form-icon-user">
                                                    {{ Form::text('bpt_item_count', $data->bpt_item_count, array('class' => 'form-control')) }}
                                                </div>
                                                <span class="validate-err" id="err_capitalization"></span>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" style="padding-top: 30px;">
                                                <div class="form-icon-user">
                                                    {{ Form::label('bpt_additional_fee', __('Additional Fee'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" style="padding-top: 20px;">
                                                  <div class="form-icon-user">
                                                       {{ Form::text('bpt_additional_fee', $data->bpt_additional_fee, array('class' => 'form-control')) }}
                                                       <div class="currency-sign"><span>Php</span></div>
                                                    </div>
                                                <span class="validate-err" id="err_bpt_additional_fee"></span>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                        {{ Form::label('bpt_fee_schedule_option', __('Schedule Option'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        </div>

                                          <div class="d-flex radio-check">
                                            <div class="form-check form-group col-md-12">
                                                {{ Form::radio('bpt_fee_schedule_option', '0', ($data->bpt_fee_schedule_option=='0')?true:false, array('id'=>'none','class'=>'form-check-input feeoption','required'=>'required')) }}
                                                {{ Form::label('feeoption', __('Indicated In PERMIT FEE'),['class'=>'form-label']) }}
                                            </div>
                                            </div>
                                           <div class="d-flex radio-check">
                                            <div class="form-check  form-group col-md-12">
                                                {{ Form::radio('bpt_fee_schedule_option', '1', ($data->bpt_fee_schedule_option=='1')?true:false, array('id'=>'basicfee','class'=>'form-check-input feeoption')) }}
                                                {{ Form::label('feeoption', __('PERMIT FEE multiplied by items declared'),['class'=>'form-label']) }}
                                            </div>
                                            </div>
                                           <div class="d-flex radio-check">
                                            <div class="form-check  form-group col-md-12">
                                                {{ Form::radio('bpt_fee_schedule_option', '2', ($data->bpt_fee_schedule_option=='2')?true:false, array('id'=>'bycategory','class'=>'form-check-input feeoption')) }}
                                                {{ Form::label('feeoption', __('PERMIT FEE + in excess of count is multiplied by ADDL FEE'),['class'=>'form-label']) }}
                                            </div>
                                            </div>
                                        </div>
                                        <span class="validate-err" id="err_bpt_fee_schedule_option"></span>
                               </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                    {{ Form::label('bpt_tax_schedule', __('Tax Schedule'),['class'=>'form-label']) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex radio-check">
                                        <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('bpt_tax_schedule', '1', ($data->bpt_tax_schedule =='1')?true:false, array('id'=>'annualy','class'=>'form-check-input code','required'=>'required')) }}
                                            {{ Form::label('bpt_tax_schedule', __('Annually'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="form-check form-check-inline form-group col-md-3">
                                            {{ Form::radio('bpt_tax_schedule', '2', ($data->bpt_tax_schedule =='2')?true:false, array('id'=>'queterly','class'=>'form-check-input code')) }}
                                            {{ Form::label('bpt_tax_schedule', __('Quaterly'),['class'=>'form-label']) }}
                                        </div>
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
             
            <div class="col-lg-12 col-md-12 col-sm-12">  
                <div  class="accordion accordion-flush">
                  <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" style="">
                            <h6 class="sub-title accordiantitle">{{__("Fee Not Specified In Revenue Code")}}</h6>
                        </button>
                    </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
                            <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('bpt_amount_w_machine', __('With Machine'),['class'=>'form-label']) }}
                                    <div class="form-icon-user">
                                      {{ Form::text('bpt_amount_w_machine', $data->bpt_amount_w_machine, array('class' => 'form-control','id'=>'bpt_amount_w_machine')) }} 
                                    </div>
                                    <span class="validate-err" id="err_bbc_classification_code"></span>
                                </div>
                            </div>    
                             <div class="col-md-6">
                                 <div class="form-group">
                                    {{ Form::label('bpt_amount_wo_machine', __('W/O Machine'),['class'=>'form-label']) }}
                                    <div class="form-icon-user">
                                        {{ Form::text('bpt_amount_wo_machine', $data->bpt_amount_wo_machine, array('class' => 'form-control','id'=>'bpt_amount_wo_machine')) }} 
                                    </div>
                                    <span class="validate-err" id="err_bbc_classification_code"></span>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
           </div>
           <div class="row">
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bpt_capital_asset_minimum', __('Minimum Asset'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{ Form::number('bpt_capital_asset_minimum', $data->bpt_capital_asset_minimum, array('class' => 'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_bpt_capital_asset_minimum"></span>
                </div>
            </div>
              <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bpt_capital_asset_maximum', __('Maximum Asset'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{ Form::number('bpt_capital_asset_maximum', $data->bpt_capital_asset_maximum, array('class' => 'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_bpt_capital_asset_maximum"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bpt_workers_no_minimum', __('Workers(minimum number)'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{ Form::number('bpt_workers_no_minimum', $data->bpt_workers_no_minimum, array('class' => 'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_bpt_workers_no_minimum"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bpt_workers_no_maximum', __('Workers(maximum number)'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{ Form::number('bpt_workers_no_maximum', $data->bpt_workers_no_maximum, array('class' => 'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_bpt_workers_no_maximum"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bpt_revenue_code', __('Revenue code description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::text('bpt_revenue_code', $data->bpt_revenue_code, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bpt_revenue_code"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bpt_remarks', __('Remarks'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::text('bpt_remarks', $data->bpt_remarks, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bpt_remarks"></span>
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
<script src="{{ asset('js/addBusinessPermitfee.js') }}"></script>



