    @if(isset($data->field))
    <!-- to send as json -->
    {{ Form::open(array('url' => 'citizens/store','id'=>'citizen-send-json')) }}
    {{ Form::hidden('field',$data->field, array('id' => 'field')) }}
    {{ Form::hidden('submit','submit', array('id' => 'submit')) }}
    @else
    {{ Form::open(array('url' => 'citizens/store','class'=>'formDtls', )) }}
    @endif
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <div class="modal-body" >
        <div class="row" id="citizen-contain">
            <div class="col-sm-4">
                <div class="form-group m-form__group required mb-0">
                    {{ Form::label('cit_last_name', 'Last Name', ['class' => 'required fs-6 fw-bold']) }}<span class="text-danger">*</span>
                    {{ 
                        Form::text('cit_last_name', $data->cit_last_name, 
                        $attributes = array(
                            'class' => 'form-control',
                            'maxlength'=>'50',
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_last_name">{{ $errors->first('cit_last_name') }}</span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group m-form__group required mb-0">
                    {{ Form::label('cit_first_name', 'First Name', ['class' => 'required fs-6 fw-bold']) }}<span class="text-danger">*</span>
                    {{ 
                        Form::text('cit_first_name', $data->cit_first_name, 
                        $attributes = array(
                            'id' => 'FName',
                            'class' => 'form-control form-control-solid',
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_first_name">{{ $errors->first('cit_first_name') }}</span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group required mb-0">
                    {{ Form::label('cit_middle_name', 'Middle Name', ['class' => 'required fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_middle_name', $data->cit_middle_name, 
                        $attributes = array(
                            'id' => 'MName',
                            'class' => 'form-control form-control-solid',
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_middle_name">{{ $errors->first('cit_middle_name') }}</span>
                </div>
            </div>
            <div class="col-sm-1">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_suffix_name', 'Suffix', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_suffix_name', $data->cit_suffix_name, 
                        $attributes = array(
                            'id' => 'Suffix',
                            'class' => 'form-control form-control-solid',
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_suffix_name">{{ $errors->first('cit_suffix_name') }}</span>
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_house_lot_no', 'House/Lot No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_house_lot_no', $data->cit_house_lot_no, 
                        $attributes = array(
                            'id' => 'HouseNo',
                            'class' => 'form-control form-control-solid',
                            'rows' => 3
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_house_lot_no">{{ $errors->first('cit_house_lot_no') }}</span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_street_name', 'Street Name', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_street_name', $data->cit_street_name, 
                        $attributes = array(
                            'id' => 'StName',
                            'class' => 'form-control form-control-solid',
                            'rows' => 3
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_street_name">{{ $errors->first('cit_street_name') }}</span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_subdivision', 'Subdivision', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_subdivision', $data->cit_subdivision, 
                        $attributes = array(
                            'id' => 'Subdivision',
                            'class' => 'form-control form-control-solid',
                            'rows' => 3
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_subdivision">{{ $errors->first('cit_subdivision') }}</span>
                </div>
            </div>
            
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group m-form__group required mb-0 select-contain" id="select-brgy">
                    {{ Form::label('brgy_id', 'Barangay , Municipality, Province, Region', ['class' => 'required fs-6 fw-bold']) }}<span class="text-danger">*</span>
                    {{
                        Form::select('brgy_id', 
                        $barangays, 
                        $data->brgy_id, 
                        [
                            'id' => 'barangay_id', 
                            'class' => 'form-control', 
                            'data-placeholder' => 'select a barangay...'
                        ])
                    }}
                    
                    <span class="validate-err" id="err_brgy_id">{{ $errors->first('brgy_id') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_gender', 'Gender', ['class' => 'fs-6 fw-bold']) }}<span class="text-danger">*</span>
                    {{ 
                        Form::select('cit_gender',
                        array('0'=>'Male','1'=>'Female'),
                        $data->cit_gender, 
                        array(
                            'class' => 'form-control select3',
                            'id'=>'cit_gender',
                            'data-contain' => 'citizen-contain'
                            )) 
                    }}
                    <span class="validate-err" id="err_cit_gender">{{ $errors->first('cit_gender') }}</span>
                </div>
            </div>
            <div class="col-sm-1">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_age', 'Age', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_age', $data->cit_age, 
                        $attributes = array(
                            'id' => 'age_form',
                            'class' => 'form-control form-control-solid',
                            'readonly' => 'true'
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_age">{{ $errors->first('cit_age') }}</span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('ccs_id', 'Civil Status', ['class' => 'fs-6 fw-bold']) }}<span class="text-danger">*</span>
                    {{ 
                        Form::select('ccs_id',
                        $civilstat,
                        $data->ccs_id, 
                        array(
                            'class' => 'form-control select3',
                            'id'=>'civilstat',
                            'data-contain' => 'citizen-contain'
                            )) 
                    }}
                    <span class="validate-err" id="err_ccs_id">{{ $errors->first('ccs_id') }}</span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_date_of_birth', 'Date of Birth', ['class' => 'fs-6 fw-bold']) }}<span class="text-danger">*</span>
                    {{ 
                        Form::date('cit_date_of_birth', $data->cit_date_of_birth, 
                        $attributes = array(
                            'id' => 'bday_form',
                            'class' => 'form-control form-control-solid',
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_date_of_birth">{{ $errors->first('cit_date_of_birth') }}</span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_place_of_birth', 'Place of Birth', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_place_of_birth', $data->cit_place_of_birth, 
                        $attributes = array(
                            'id' => 'PlaceBirth',
                            'class' => 'form-control form-control-solid',
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_place_of_birth">{{ $errors->first('cit_place_of_birth') }}</span>
                </div>
            </div>
            
        </div>
        <div class="row">
        <div class="col-sm-3">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_blood_type', 'Blood Type', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_blood_type', $data->cit_blood_type, 
                        $attributes = array(
                            'id' => 'BloodType',
                            'class' => 'form-control form-control-solid',
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_blood_type">{{ $errors->first('cit_blood_type') }}</span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_mobile_no', 'Mobile No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_mobile_no', $data->cit_mobile_no, 
                        $attributes = array(
                            'id' => 'MobileNo',
                            'class' => 'form-control form-control-solid',
                            'rows' => 3
                        )) 
                    }}
                    <!-- <span class="validate-err" id="err_cit_mobile_no">{{ $errors->first('cit_mobile_no') }}</span> -->
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_telephone_no', 'Telephone', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_telephone_no', $data->cit_telephone_no, 
                        $attributes = array(
                            'id' => 'Telephone',
                            'class' => 'form-control form-control-solid',
                            'rows' => 3
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_telephone_no">{{ $errors->first('cit_telephone_no') }}</span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_fax_no', 'Fax No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_fax_no', $data->cit_fax_no, 
                        $attributes = array(
                            'id' => 'FaxNo',
                            'class' => 'form-control form-control-solid',
                            'rows' => 3
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_fax_no">{{ $errors->first('cit_fax_no') }}</span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_tin_no', 'TIN No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_tin_no', $data->cit_tin_no, 
                        $attributes = array(
                            'id' => 'TinNo',
                            'class' => 'form-control form-control-solid',
                            'maxlength' => 17
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_tin_no">{{ $errors->first('cit_tin_no') }}</span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group mb-0 select-contain">
                    {{ Form::label('country_id', 'Nationality', ['class' => 'fs-6 fw-bold']) }}
                    
                    {{
                        Form::select('country_id', 
                        $nationality, 
                        $data->country_id, 
                        [
                            'id' => 'country_id', 
                            'class' => 'form-control select3', 
                            'data-placeholder' => 'select a nationality...',
                            'data-contain' => 'citizen-contain'
                        ])
                    }}
                    <span class="validate-err" id="err_country_id">{{ $errors->first('country_id') }}</span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('irc_no', 'IRC No. [if Alien]', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('irc_no', $data->irc_no, 
                        $attributes = array(
                            'id' => 'IrcNo',
                            'class' => 'form-control form-control-solid',
                            'rows' => 3
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_tin_no">{{ $errors->first('cit_tin_no') }}</span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_email_address', 'Email Address', ['class' => 'fs-6 fw-bold']) }}
                    <!-- <span class="text-danger">*</span> -->
                    {{ 
                        Form::email('cit_email_address', $data->cit_email_address, 
                        $attributes = array(
                            'id' => 'Email',
                            'class' => 'form-control form-control-solid',
                            'rows' => 3
                        )) 
                    }}
                    <!-- <span class="validate-err" id="err_cit_email_address">{{ $errors->first('cit_email_address') }}</span> -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_height', 'Height', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_height', $data->cit_height, 
                        $attributes = array(
                            'id' => 'Height',
                            'class' => 'form-control form-control-solid',
                            'rows' => 3
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_height">{{ $errors->first('cit_height') }}</span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_weight', 'Weight', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_weight', $data->cit_weight, 
                        $attributes = array(
                            'id' => 'Weight',
                            'class' => 'form-control form-control-solid',
                            'rows' => 3
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_weight">{{ $errors->first('cit_weight') }}</span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_sss_no', 'SSS No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_sss_no', $data->cit_sss_no, 
                        $attributes = array(
                            'id' => 'SSSNo',
                            'class' => 'form-control form-control-solid',
                            'rows' => 3
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_sss_no">{{ $errors->first('cit_sss_no') }}</span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_gsis_no', 'GSIS No.', ['class' => 'fs-6 fw-bold']) }}   
                    {{ 
                        Form::text('cit_gsis_no', $data->cit_gsis_no, 
                        $attributes = array(
                            'id' => 'GSISNo',
                            'class' => 'form-control form-control-solid',
                            'rows' => 3
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_gsis_no">{{ $errors->first('cit_gsis_no') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_pagibig_no', 'Pag-IBIG No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_pagibig_no', $data->cit_pagibig_no, 
                        $attributes = array(
                            'id' => 'PagibigNo',
                            'class' => 'form-control form-control-solid',
                            'rows' => 3
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_pagibig_no">{{ $errors->first('cit_pagibig_no') }}</span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_psn_no', 'PSN No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_psn_no', $data->cit_psn_no, 
                        $attributes = array(
                            'id' => 'PSNNo',
                            'class' => 'form-control form-control-solid',
                            'rows' => 3
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_psn_no">{{ $errors->first('cit_psn_no') }}</span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_philhealth_no', 'PhilHealth No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_philhealth_no', $data->cit_philhealth_no, 
                        $attributes = array(
                            'id' => 'PhilhealthNo',
                            'class' => 'form-control form-control-solid',
                            'maxlength' => 14,
                            'minlength' => 13,
                        )) 
                    }}
                    <span class="validate-err" id="err_cit_philhealth_no"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cea_id', 'Educational Attainment', ['class' => 'fs-6 fw-bold']) }}
                    {{ Form::select('cea_id',
                        $educ,$data->cea_id, 
                        array(
                            'class' => 'form-control select3',
                            'id'=>'educ',
                            'data-contain' => 'citizen-contain'
                            )) }}
                    <span class="validate-err" id="err_cea_id">{{ $errors->first('cea_id') }}</span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group m-form__group mb-0">
                    {{ Form::label('cit_occupation', 'Occupation', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('cit_occupation', $data->cit_occupation, 
                        $attributes = array(
                            'id' => 'Occupation',
                            'class' => 'form-control form-control-solid',
                        )) 
                    }}
                    <span class="validate-err" id="err_cea_id">{{ $errors->first('cea_id') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <br>
            <br>
            <br>
        </div>
       @if($data->id>0)
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
                                
                                
                                <div id="flush-collapse3" class="accordion-collapse collapse" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                                
                                    <div class="basicinfodiv">
                                        <div class="row">
                                           
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    {{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('file','document_name','',array('class'=>'form-control '))}}  
                                                    </div>
                                                    <span class="validate-err" id="err_document"></span>
                                                </div>
                                            </div>
                                            
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                    &nbsp;
                                                    <div class="form-icon-user">
                                                      <button type="button" style="float: right;" class="btn btn-primary " id="uploadAttachment">Upload File</button>   
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                           <tr>
                                                               <th>File Name</th>
                                                                <th>Attachment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <thead id="DocumentDtls">
                                                         <?php echo $arrdocDtls?>
                                                            @if(empty($arrdocDtls))
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
			<input type="button" id="modal-close" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
            @if(!isset($data->field))
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;"> -->
            <button class="btn btn-primary" type="submit" value="submit" >
            {{__('Save Changes')}}
            </button>
            @endif
            
        </div>
    </div>
{{Form::close()}}
<!-- <script src="{{ asset('js/ajax_validation_confir_mass.js') }}"></script> -->
{{--@if(!isset($data->field))--}}
<script src="{{ asset('js/partials/forms_validation.js?v='.filemtime(getcwd().'/js/partials/forms_validation.js').'') }}"></script>
{{--@endif--}}
<script src="{{ asset('js/SocialWelfare/add-citizen.js?v='.filemtime(getcwd().'/js/SocialWelfare/add-citizen.js').'') }}"></script>
<script src="{{ asset('js/partials/select-ajax.js?v='.filemtime(getcwd().'/js/partials/select-ajax.js').'') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
    // citizens sends as ajax if its opened in other module
    // but if its in Citizen module it submit normaly
    @if(isset($data->field))
    formSubmit(
        $('#citizen-send-json'),
        function (data) {
            var group = $('#'+data.field).closest('.citizen_group');
            var value = data.id;
            group.find('.search-select-ajax').val(data.name);
            $('#'+data.field).val(value);
            try {
                citizenTable()
            } catch (error) {
                console.log(error)
            }
            try {
                citizenWrite(group, value);
            } catch (error) {
                console.log(error)
                
            }
        }, 
        'ajax'
    );
    @else
    FormNormal()
    @endif
	$("#commonModal").find('.body').css({overflow: 'unset'}) 
});
</script> 