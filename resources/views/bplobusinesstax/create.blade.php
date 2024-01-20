<style type="text/css">
    .form-check-inline {
    display: inline-block;
    margin-right: 2rem;
}
.form-check-inline {
    display: inline-block;
    margin-right: 3rem;
}
.modal-lg, .modal-xl {
    max-width: 1150px;
}
.field-requirement-details-status {
    border-bottom: 1px solid #f1f1f1;
    font-size: 13px;
    color: black;
    background: #8080802e;
    text-transform: uppercase;
    margin: 20px 0px 6px 0px;
    margin-top: 20px;
}
.choices__inner {
    min-height: 35px;
    padding:5px ;
    padding-left:5px;
}
.field-requirement-details-status label{margin-top: 7px;}
#flush-collapsetwo{
    padding-bottom: 80px;
}
</style>

{{ Form::open(array('url' => 'administrative/taxation-schedule/graduated-new-tax/store')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('prev_tax_type_id',$data->tax_type_id, array('id' => 'prev_tax_type_id')) }}
    <div class="modal-body">
        <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tax_class_id', __('Class code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
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
                        {{ Form::select('tax_type_id',$arrTaxTypes,$data->tax_type_id, array('class' => 'form-control select3 ','id'=>'tax_type_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_tax_type_id"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('bbc_classification_code', __('Business code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('bbc_classification_code',$arrClassificationCode,$data->bbc_classification_code, array('class' => 'form-control select3','id'=>'bbc_classification_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbc_classification_code"></span>
                </div>
            </div>
             <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {!! Form::textarea('description','', ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
                    </div>
                    <span class="validate-err" id="err_tax_class_id"></span>
                </div>
            </div>
        </div>
           <div class="row">
           
             <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('bsf_code', __('Taxation Procedure'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::select('bbt_taxation_procedure',$optionarray, $data->bbt_taxation_procedure, array('class' => 'form-control spp_type','id'=>'bbt_taxation_procedure')) }}
                    </div>
                    <span class="validate-err" id="err_bbt_taxation_procedure"></span>
                </div>
            </div>
           
        </div>
          <div class="row">
             <div class="col-lg-1 col-md-1 col-sm-1">
                        <input type="button" id="btn_addmore_option" class="btn btn-success" value="Add Option" style="padding: 0.4rem 1rem !important;">
                        </div>
                     <div class="row field-requirement-details-status">
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            {{Form::label('minimumgross',__('Minimum Gross'),['class'=>'form-label'])}}
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            {{Form::label('maximumgross',__('Maximum Gross'),['class'=>'form-label'])}}
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            {{Form::label('taxamount',__('Tax Amount'),['class'=>'form-label'])}}
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1">
                            {{Form::label('initialtaxamount',__('Iniatial Amount'),['class'=>'form-label'])}}
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1">
                            {{Form::label('taxationpercent',__('Taxation Percent'),['class'=>'form-label'])}}
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            {{Form::label('taxprocedure',__('Tax Procedure'),['class'=>'form-label'])}}
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            {{Form::label('taxschedule',__('Tax Schedule'),['class'=>'form-label'])}}
                        </div>
                    </div>
                    <span class="optionDetails option-details">
                        @php $i=0; @endphp
                        @foreach($garduatesoptionArr as $key=>$val)
                            <div class="row removenaturedata pt10">
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                        {{Form::text('minimumgross[]',$val['minimumgross'],array('class'=>'form-control numeric','required'=>'required','placeholder'=>'0.00'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                             {{Form::text('maximumgross[]',$val['maximumgross'],array('class'=>'form-control numeric','required'=>'required','placeholder'=>'0.00'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                           {{Form::text('taxamount[]',$val['taxamount'],array('class'=>'form-control numeric','required'=>'required','placeholder'=>'0.00'))}},
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-1 col-md-1 col-sm-1">
                                    <div class="form-group">
                                        <div class="form-icon-user" >
                                            {{Form::text('initialtaxamount[]',$val['initialtaxamount'],array('class'=>'form-control numeric','required'=>'required','placeholder'=>'0.00'))}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-1 col-md-1 col-sm-1">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                             {{Form::text('taxationpercent[]',$val['taxationpercent'],array('class'=>'form-control numeric','required'=>'required','placeholder'=>'0.00'))}}
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                             {{ Form::select('taxationprocedure[]',array('0'=>'Tax Amount','1'=>'Graduated % of Gross','2'=>'Tax By % Of Gross','3'=>' % Of Gross Of Min Amount'),$val['taxationprocedure'], array('class' => 'form-control naofbussi ','id'=>'taxationprocedure')) }}
                                        </div>
                                    </div>
                                </div>
                                  <div class="col-lg-1 col-md-1 col-sm-1">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                             {{ Form::select('taxschedule[]',array('1'=>'Quaterly','0'=>'Annualy'),$val['taxschedule'], array('class' => 'form-control naofbussi ','id'=>'taxschedule')) }}
                                        </div>
                                    </div>
                                </div>
                                
                                @if($i>0)
                                    <div class="col-sm-1">
                                        <input type="button" name="btn_cancel_option" class="btn btn-success btn_cancel_option" value="Delete" style="padding: 0.4rem 1rem !important;">
                                    </div>
                                    @endif
                                @php $i++; @endphp
                            </div>
                        @endforeach
                    </span>
            </div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<div id="hidenoptionHtml" class="hide">
    <div class="removeoptiondata row pt10">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                {{Form::text('minimumgross[]','',array('class'=>'form-control numeric','required'=>'required','placeholder'=>'0.00'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                    {{Form::text('maximumgross[]','',array('class'=>'form-control numeric','required'=>'required','placeholder'=>'0.00'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                    {{Form::text('taxamount[]','',array('class'=>'form-control numeric','required'=>'required','placeholder'=>'0.00'))}}
                </div>
            </div>
        </div>

        <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="form-group">
                <div class="form-icon-user">
                    {{Form::text('initialtaxamount[]','',array('class'=>'form-control numeric','required'=>'required','placeholder'=>'0.00'))}}
                    
                </div>
            </div>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="form-group">
                <div class="form-icon-user">
                    {{Form::text('taxationpercent[]','',array('class'=>'form-control numeric','required'=>'required','placeholder'=>'0.00'))}}
                </div>
            </div>
        </div>
         <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                    {{ Form::select('taxationprocedure[]',array('0'=>'Tax Amount','1'=>'Graduated % of Gross','2'=>'Tax By % Of Gross','3'=>' % Of Gross Of Min Amount'),'', array('class' => 'form-control naofbussi ','id'=>'taxationprocedure')) }}
                </div>
            </div>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="form-group">
                <div class="form-icon-user">
                   {{ Form::select('taxschedule[]',array('1'=>'Quaterly','0'=>'Annualy'), '', array('class' => 'form-control naofbussi ','id'=>'taxschedule','required'=>'required')) }}
                </div>
            </div>
        </div>
        <div class="col-sm-1">
            <input type="button" name="btn_cancel" class="btn btn-success btn_cancel_option" cid="" value="Delete" style="padding: 0.4rem 1rem !important;">
        </div>
    </div>
</div>
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/addBusinessTax.js') }}"></script>



