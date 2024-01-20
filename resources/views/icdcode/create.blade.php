{{ Form::open(array('url' => 'icdcode')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  
   <style type="text/css">
       .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 800px;
            pointer-events: auto;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            outline: 0;
        }
   </style>

    <div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('icd10_code', __('ICD10 Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
    
                <div class="form-icon-user">
                    {{ Form::text('icd10_code', $data->icd10_code, array('class' => 'form-control','required'=>'required')) }}
                </div>
                <span class="validate-err" id="err_bbef_code"></span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('icd10_case_rate', __('Case Rate'),['class'=>'form-label']) }}<span class="text-danger">*</span>
    
                <div class="form-icon-user">
                    {{ Form::text('icd10_case_rate', $data->icd10_case_rate, array('class' => 'form-control','required'=>'required')) }}
                </div>
                <span class="validate-err" id="err_bbef_code"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('icd10_pro_fee', __('Professional Rate'),['class'=>'form-label']) }}<span class="text-danger">*</span>
    
                <div class="form-icon-user">
                    {{ Form::text('icd10_pro_fee', $data->icd10_pro_fee, array('class' => 'form-control','required'=>'required')) }}
                </div>
                <span class="validate-err" id="err_bbef_code"></span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('icd10_institution_fee', __('Health Care Institurion Fee'),['class'=>'form-label']) }}<span class="text-danger">*</span>
    
                <div class="form-icon-user">
                    {{ Form::text('icd10_institution_fee', $data->icd10_institution_fee, array('class' => 'form-control','required'=>'required')) }}
                </div>
                <span class="validate-err" id="err_bbef_code"></span>
            </div>
        </div>
    </div>
    <div class="row">
            <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('icd10_group_id', __('ICD10 Group'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::select('icd10_group_id',$arrIcdGroupCode,$data->icd10_group_id, array('class' => 'form-control select3','id'=>'icd10_group_id')) }}
                                    
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
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
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>




