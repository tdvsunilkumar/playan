{{ Form::open(array('url' => 'bplosystemparameters')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  
   
 <style>
    .modal-xl {
        max-width: 1350px !important;
    }
    .accordion-button{
        margin-bottom: 12px;
    }
    .form-group{
        margin-bottom: unset;
    }
    .form-group label {
        font-weight: 600;
        font-size: 12px;
    }
    .form-control, .custom-select{
        padding-left: 5px;
        font-size: 12px;
    }
    .pt10{
        padding-top:10px;
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
    
 </style>

    <div class="modal-body">

         <div class="row">
             <div class="col-lg-12 col-md-12 col-sm-12" id="accordionFlushExample2">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                        <h6 class="sub-title accordiantitle">{{__('System Parameters')}}</h6>
                        </button>
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                        <div class="row"  id="otheinfodiv">
                <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('bsp_local_code', __('Locality'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('bsp_local_code', $data->bsp_local_code, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-8">
               <div class="form-group">
                    {{ Form::label('bsp_local_name', __('Name'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('bsp_local_name', $data->bsp_local_name, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('bsp_address', __('Address'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('bsp_address', $data->bsp_address, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('bsp_telephone_no', __('Telephone No.(s)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('bsp_telephone_no', $data->bsp_telephone_no, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_fee_option"></span>
                </div>
            </div>
               <div class="col-md-6">
                <div class="form-group ">
                    {{ Form::label('bsp_fax_no', __('Fax No(s)'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                      {{ Form::text('bsp_fax_no', $data->bsp_fax_no, array('class' => 'form-control','required'=>'required')) }}
                     
                    </div>
                    <span class="validate-err" id="err_bbef_fee_amount"></span>
                </div>
            </div>
              
                        <div class="col-md-12">
               <div class="form-group">
                    {{ Form::label('bsp_governor_mayor', __('Governor/Mayor'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('bsp_governor_mayor', $data->bsp_governor_mayor, array('class' => 'form-control','value'=>'Street & Number')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>      
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



       <div class="row">
             <div class="col-lg-6 col-md-6 col-sm-6" id="accordionFlushExample2">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                        <h6 class="sub-title accordiantitle">{{__('Name')}}</h6>
                        </button>
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                        <div class="row"  id="otheinfodiv">
              
             <div class="col-md-12">
               <div class="form-group">
                    {{ Form::label('bsp_administrator_name', __('Administrator'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('bsp_administrator_name', $data->bsp_administrator_name, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('bsp_budget_officer_name', __('Budget Officer'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('bsp_budget_officer_name', $data->bsp_budget_officer_name, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('bsp_treasurer_name', __('Treasurer'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('bsp_treasurer_name', $data->bsp_treasurer_name, array('class' => 'form-control','required'=>'required','value'=>'000')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_fee_option"></span>
                </div>
            </div>
               <div class="col-md-12">
                <div class="form-group ">
                    {{ Form::label('bsp_accountant_name', __('Accountant'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                      {{ Form::text('bsp_accountant_name', $data->bsp_accountant_name, array('class' => 'form-control','required'=>'required')) }}
                       
                    </div>
                    <span class="validate-err" id="err_bbef_fee_amount"></span>
                </div>
            </div>
               </div>
               <div class="col-md-12">
                <div class="form-group ">
                    {{ Form::label('bsp_chief_bplo_name', __('Chief BPLO'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                      {{ Form::text('bsp_chief_bplo_name', $data->bsp_chief_bplo_name, array('class' => 'form-control','required'=>'required')) }}
                      
                    </div>
                    <span class="validate-err" id="err_bbef_fee_amount"></span>
                </div>
            </div>
               
                        
                    </div>
                </div>
            </div>
        </div>



             <div class="col-lg-6 col-md-6 col-sm-6" id="accordionFlushExample2">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                        <h6 class="sub-title accordiantitle">{{__('Position')}}</h6>
                        </button>
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                        <div class="row"  id="otheinfodiv">
                <div class="col-md-12">
               <div class="form-group">
                   
                </div>
            </div>
            
             <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('bsp_budget_officer_position', __('Budget Officer Position'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('bsp_budget_officer_position', $data->bsp_budget_officer_position, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
             <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('bsp_treasurer_position', __('Treasurer Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('bsp_treasurer_position', $data->bsp_treasurer_position, array('class' => 'form-control','required'=>'required','value'=>'000')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_fee_option"></span>
                </div>
            </div>
               <div class="col-md-12">
                <div class="form-group ">
                    {{ Form::label('bsp_accountant_position', __('Accountant Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                      {{ Form::text('bsp_accountant_position', $data->bsp_accountant_position, array('class' => 'form-control','required'=>'required')) }}
                       
                    </div>
                    <span class="validate-err" id="err_bbef_fee_amount"></span>
                </div>
            </div>
               </div>
               <div class="col-md-12">
                <div class="form-group ">
                    {{ Form::label('bsp_chief_bplo_position', __('Chief BPLO Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                      {{ Form::text('bsp_chief_bplo_position', $data->bsp_chief_bplo_position, array('class' => 'form-control','required'=>'required')) }}
                       
                    </div>
                    <span class="validate-err" id="err_bbef_fee_amount"></span>
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
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<!-- <script src="{{ asset('js/addBusinessEnvfee.js') }}"></script> -->



