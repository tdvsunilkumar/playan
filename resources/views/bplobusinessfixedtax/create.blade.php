{{ Form::open(array('url' => 'administrative/taxation-schedule/fixed-taxes-and-fees/store')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('prev_tax_type_id',$data->tax_type_id, array('id' => 'prev_tax_type_id')) }}
     {{ Form::hidden('prev_bba_code',$data->bba_code, array('id' => 'prev_bba_code')) }}
      {{ Form::hidden('tax_type_id',$data->tax_type_id, array('id' => 'tax_type_id')) }}
     {{ Form::hidden('tax_class_id',$data->tax_class_id, array('id' => 'tax_class_id')) }}
    <div class="modal-body" id="myModal">
        <div class="row"> 
          
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('tax_class_code', __('Tax Class'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{Form::text('tax_class_code','',array('class'=>'form-control','id'=>'tax_class_code','readonly'))}} 
                    </div>
                    <span class="validate-err" id="err_bgf_fee_amoun"></span>
                </div>
            </div>
          
            
            
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('tax_class_desc', __('Tax Type'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{Form::text('tax_class_desc','',array('class'=>'form-control','id'=>'tax_class_desc','readonly'))}} 
                    </div>
                    <span class="validate-err" id="err_bgf_fee_amoun"></span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('bbc_classification_code', __('Classification'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('bbc_classification_code',$arrClassificationCode,$data->bbc_classification_code, array('class' => 'form-control select3','id'=>'bbc_classification_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbc_classification_code"></span>
                </div>
            </div>
           
             <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('bba_code', __('Business Activity Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{ Form::select('bba_code',$arrbbaCode,$data->bba_code, array('class' => 'form-control select3','id'=>'bba_code','required'=>'required')) }}
                    <span class="validate-err" id="err_bba_code"></span>
                    </div>
               </div>
            </div>
             <!-- <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bba_code2', __('Activitiy Code'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{Form::text('bba_code2','',array('class'=>'form-control','id'=>'bba_code2'))}} 
                    </div>
                    <span class="validate-err" id="err_bgf_fee_amoun"></span>
                </div>
            </div> -->
          
            <!-- <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('bba_desc', __('Activitiy Desc'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{Form::text('bba_desc','',['class'=>'form-control','id'=>'bba_desc'])}} 
                       
                    </div>
                    <span class="validate-err" id="err_bgf_fee_amoun"></span>
                </div>
            </div> -->
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('bft_taxation_procedure', __('Taxation Procedure'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('bft_taxation_procedure',array('' =>'Please Select','1'=>'Tax Rate is indicated in TAX AMOUNT','2' =>'ANNUAL TAX + Excess of count is multiplied by ADDITIONAL TAX','3'=>'Rate indicated in Tax Amount is multiplied by the number of taxable items in business'), $data->bft_taxation_procedure, array('class' => 'form-control spp_type','id'=>'bft_taxation_procedure')) }}
                    </div>
                    <span class="validate-err" id="err_bgf_fee_option"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bft_taxation_schedule', __('Tax Schedule'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('bft_taxation_schedule',array('' =>'Select Schedule','1' =>'Annually','2' =>'Quaterly'), $data->bft_taxation_schedule, array('class' => 'form-control spp_type','id'=>'bft_taxation_schedule','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bsf_tax_schedule"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group currency">
                    {{ Form::label('bft_tax_amount', __('Tax Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                      {{ Form::number('bft_tax_amount', $data->bft_tax_amount, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
                       <div class="currency-sign"><span>Php</span></div>
                    </div>
                    <span class="validate-err" id="err_bgf_fee_amoun"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group currency">
                    {{ Form::label('bft_item_count', __('Item Count'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                      {{ Form::number('bft_item_count', $data->bft_item_count, array('class' => 'form-control','required'=>'required')) }}
                       <div class="currency-sign"><span>Php</span></div>
                    </div>
                    <span class="validate-err" id="err_bgf_fee_amoun"></span>
                </div>
            </div>
          
            <div class="col-md-4">
                <div class="form-group currency">
                    {{ Form::label('bft_additional_tax', __('Additional Tax'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                      {{ Form::number('bft_additional_tax', $data->bft_additional_tax, array('class' => 'form-control','required'=>'required')) }}
                       <div class="currency-sign"><span>Php</span></div>
                    </div>
                    <span class="validate-err" id="err_bgf_fee_amoun"></span>
                </div>
            </div>
             
             
            
          
             
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" mid="" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn btn-primary savebusinessDetails"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/addBusinessTaxFixed.js') }}"></script>



