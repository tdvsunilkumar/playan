<div class="container-fluid card pt-4">
    {{ Form::open(array('url' => 'social-welfare/travel-clearance-minor/store','class'=>'formDtls create-form', 'files' => true)) }}
        {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
        <div class="row citizen_group">
            <div class="col-sm-6">
                <div class="form-group m-form__group ">
                {{ Form::label('', 'Status', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::radio('wtcm_case', 
                        0, 
                        false,
                        $attributes = array(
                        'id' => 'wtcm_case_legit',
                        'class' => 'form-check-input',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':'',
                        )) 
                    }}
                    {{ Form::label('wtcm_case_legit', 'Travelling Alone', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::radio('wtcm_case', 
                        1,
                        false, 
                        $attributes = array(
                        'id' => 'wtcm_case_illegit',
                        'class' => 'form-check-input',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':'',
                        )) 
                    }}
                    {{ Form::label('wtcm_case_illegit', 'With Companion', ['class' => 'fs-6 fw-bold']) }}
                </div>
                <span class="validate-err"  id="err_wtcm_child_status"></span>
            </div>
            <div class="col-sm-6">
                <div class="form-group m-form__group ">
                {{ Form::label('', 'Status', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::radio('wtcm_child_status', 
                        0, 
                        false,
                        $attributes = array(
                        'id' => 'wtcm_child_status_legit',
                        'class' => 'form-check-input',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':'',
                        $data->wtcm_child_status === 0? 'checked':''
                        )) 
                    }}
                    {{ Form::label('wtcm_child_status_legit', 'Legitimate', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::radio('wtcm_child_status', 
                        1,
                        false, 
                        $attributes = array(
                        'id' => 'wtcm_child_status_illegit',
                        'class' => 'form-check-input',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':'',
                        $data->wtcm_child_status === 1? 'checked':''
                        )) 
                    }}
                    {{ Form::label('wtcm_child_status_illegit', 'Illegitimate', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::radio('wtcm_child_status', 
                        2,
                        false, 
                        $attributes = array(
                        'id' => 'wtcm_child_status_adopt',
                        'class' => 'form-check-input',
                        $data->wtcm_child_status === 2? 'checked':'',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':'',
                        )) 
                    }}
                    {{ Form::label('wtcm_child_status_adopt', 'Adopted / Adoption Degree', ['class' => 'fs-6 fw-bold']) }}
                </div>
                <span class="validate-err"  id="err_wtcm_child_status"></span>
            </div>
            <!-- row 1 -->
            <div class="col-sm-4">
                <div class="form-group m-form__group required select-contain" id='select-contain-citizen'>
                    <div>
                        {{ Form::label('cit_last_name', 'Minor Name', ['class' => 'required fs-6 fw-bold']) }}<span class="text-danger">*</span>
                        <a href="#" data-size="lg" data-url="{{ url('/citizens/store') }}?field=cit_id" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" data-title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary float-end add-citizen-btn btn_open_second_modal">
                        <i class="ti-plus"></i>
                        </a>
                        @if($data->id)
                        <a href="#" data-size="lg" data-url="{{ url('/citizens/store') }}?field=cit_id{{$data->cit_id ? '&id='.$data->cit_id : ''}}" data-bs-toggle="tooltip" title="{{__('Manage Citizen')}}" data-title="{{__('Manage Citizen')}}" class="btn btn-sm btn-secondary float-end add-citizen-btn btn_open_second_modal">
                            <i class="ti-menu"></i>
                        </a>
                        @endif
                    </div>
                    {{ 
                        Form::select('cit_id', 
                            [],
                            $data->cit_id, 
                            $attributes = array(
                            'id' => 'cit_id',
                            'data-url' => 'citizens/getCitizens',
                            'data-placeholder' => 'Search Citizen',
                            'data-contain' => 'select-contain-citizen',
                            'data-value' =>isset($data->claimant->cit_fullname) ? $data->claimant->cit_fullname : '',
                            'data-value_id' =>$data->cit_id,
                            'class' => 'form-control ajax-select get-citizen select_id',
                            (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':''
                        )) 
                    }}
                    <span class="validate-err"  id="err_cit_id"></span>
                </div>
            </div>
            
            <div class="col-sm-1">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_age', 'Age', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('claimant[cit_age]',
                        isset($data->claimant->cit_age) ? $data->claimant->age() : '',
                        $attributes = array(
                        'id' => 'claimant_age',
                        'class' => 'form-control form-control-solid select_age',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':'',
                        'disabled'=>'disabled'
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-1">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_sex', 'Sex', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::select('claimant[cit_gender]',
                        array('0'=>'Male','1'=>'Female'),
                        isset($data->claimant) ? $data->claimant->cit_gender : '', 
                        array(
                            'class' => 'form-control form-control-solid select_cit_gender select3',
                            (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':'',
                            'id'=>'claimant_sex'
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_bday', 'Date of Birth', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::date('claimant[cit_date_of_birth]',
                        isset($data->claimant->cit_date_of_birth) ? $data->claimant->cit_date_of_birth : '',
                        $attributes = array(
                        'id' => 'claimant_bday',
                        'class' => 'form-control form-control-solid select_cit_date_of_birth',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':'',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_place_birth', 'Place of Birth', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('claimant[cit_place_of_birth]',
                        isset($data->claimant->cit_place_of_birth) ? $data->claimant->cit_place_of_birth : '',
                        $attributes = array(
                        'id' => 'claimant_place_birth',
                        'class' => 'form-control form-control-solid select_cit_place_of_birth',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':'',
                        )) 
                    }}
                </div>
            </div>
            <!-- row 2 -->
            <div class="col-sm-12">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_address', 'Address', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('claimant_address',
                        isset($data->claimant) ? $data->claimant->brgy_name() : '',
                        $attributes = array(
                        'id' => 'claimant_address',
                        'class' => 'form-control form-control-solid select_cit_full_address',
                        'disabled'=>'disabled'
                        )) 
                    }}
                </div>
            </div>
            <!-- row 3 -->
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_educ', 'Educational Attainment', ['class' => ' fs-6 fw-bold']) }}
                    {{ Form::select('claimant[cea_id]',
                        $educ,
                        isset($data->claimant) ? $data->claimant->cea_id : '', 
                        array(
                            'class' => 'form-control form-control-solid select_cea_id select3',
                            (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':'',
                            'id'=>'gender'
                            ))
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_date_interviewed', 'Date Interviewed', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::date('wtcm_date_interviewed',
                        $data->wtcm_date_interviewed,
                        $attributes = array(
                        'id' => 'wtcm_date_interviewed',
                        'class' => 'form-control form-control-solid',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':'',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group m-form__group ">
                {{ Form::label('', 'Status', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::radio('wtcm_child_status', 
                        0, 
                        false,
                        $attributes = array(
                        'id' => 'wtcm_child_status_legit',
                        'class' => 'form-check-input',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':'',
                        $data->wtcm_child_status === 0? 'checked':''
                        )) 
                    }}
                    {{ Form::label('wtcm_child_status_legit', 'Legitimate', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::radio('wtcm_child_status', 
                        1,
                        false, 
                        $attributes = array(
                        'id' => 'wtcm_child_status_illegit',
                        'class' => 'form-check-input',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':'',
                        $data->wtcm_child_status === 1? 'checked':''
                        )) 
                    }}
                    {{ Form::label('wtcm_child_status_illegit', 'Illegitimate', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::radio('wtcm_child_status', 
                        2,
                        false, 
                        $attributes = array(
                        'id' => 'wtcm_child_status_adopt',
                        'class' => 'form-check-input',
                        $data->wtcm_child_status === 2? 'checked':'',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':'',
                        )) 
                    }}
                    {{ Form::label('wtcm_child_status_adopt', 'Adopted / Adoption Degree', ['class' => 'fs-6 fw-bold']) }}
                </div>
                <span class="validate-err"  id="err_wtcm_child_status"></span>
            </div>
        </div> 
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group m-form__group ">
                {{ Form::label('wtcm_background_info', "Background Information of Minor's Family", ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::textarea('wtcm_background_info', 
                    $data->wtcm_background_info, 
                    $attributes = array(
                    'id' => 'wtcm_background_info',
                    'class' => 'form-control form-control-solid',
                    (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                    )) 
                }}
                </div>
            </div>
            <span class="validate-err"  id="err_wswa_remarks"></span>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group m-form__group ">
                {{ Form::label('wtcm_present_situation', 'Present Situation of Minor', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::textarea('wtcm_present_situation', 
                    $data->wtcm_present_situation, 
                    $attributes = array(
                    'id' => 'wtcm_present_situation',
                    'class' => 'form-control form-control-solid',
                    (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                    )) 
                }}
                </div>
            </div>
            <span class="validate-err"  id="err_wswa_remarks"></span>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group m-form__group ">
                {{ Form::label('wtcm_travel_purpose', 'Purpose of Travel and Reason why parent/legal guardian are unable to accompany Minor', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::textarea('wtcm_travel_purpose', 
                    $data->wtcm_travel_purpose, 
                    $attributes = array(
                    'id' => 'wtcm_travel_purpose',
                    'class' => 'form-control form-control-solid',
                    (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                    )) 
                }}
                </div>
            </div>
            <span class="validate-err"  id="err_wswa_remarks"></span>
        </div>
        <div class="row citizen_group">
            <h3>Travelling Companion</h3>
            <div class="col-md-4">
                <div class="form-group m-form__group select-contain" id='select-contain-companion'>
                    {{ Form::label('wtcm_companion_name', 'Name', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::select('wtcm_companion_name', 
                            [],
                            $data->wtcm_companion_name, 
                            $attributes = array(
                            'id' => 'wtcm_companion_name',
                            'data-url' => 'citizens/getCitizens',
                            'data-placeholder' => 'Search Citizen',
                            'data-contain' => 'select-contain-citizen',
                            'data-value' =>isset($data->companion->cit_fullname) ? $data->companion->cit_fullname : '',
                            'data-value_id' =>$data->wtcm_companion_name,
                            'class' => 'form-control ajax-select get-citizen select_id',
                            (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':''
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_companion_date_of_birth', 'Date of Birth', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::date('wtcm_companion_date_of_birth',
                        isset($data->wtcm_companion_date_of_birth) ? $data->wtcm_companion_date_of_birth : '',
                        $attributes = array(
                        'id' => 'wtcm_companion_date_of_birth',
                        'class' => 'form-control form-control-solid select_cit_date_of_birth',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_relation_to_minor', 'Relation to Minor', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wtcm_relation_to_minor',
                        isset($data->wtcm_relation_to_minor) ? $data->wtcm_relation_to_minor : '',
                        $attributes = array(
                        'id' => 'wtcm_relation_to_minor',
                        'class' => 'form-control form-control-solid',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_companion_address', 'Address', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wtcm_companion_address',
                        isset($data->wtcm_companion_address) ? $data->wtcm_companion_address : '',
                        $attributes = array(
                        'id' => 'wtcm_companion_address',
                        'class' => 'form-control form-control-solid select_cit_full_address',
                        'readonly'
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group m-form__group ">
                {{ Form::label('wtcm_recommendation', 'Recommendation', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::textarea('wtcm_recommendation', 
                    $data->wtcm_recommendation, 
                    $attributes = array(
                    'id' => 'wtcm_recommendation',
                    'class' => 'form-control form-control-solid',
                    (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                    )) 
                }}
                </div>
            </div>
        </div>
        <div  class="accordion accordion-flush" id="req-sec">
            <div class="accordion-item">
                <h6 class="accordion-header" id="flush-requirements">
                    <button class="accordion-button collapsed btn-primary" type="button">
                        <h6 class="sub-title accordiantitle">{{__("Requirements")}}</h6>
                    </button>
                </h6>
                <div id="flush-requirements" class="accordion-collapse collapse show">
                    <table class="table" id="file-requirements">
                        <thead>
                            <tr>
                                <th width="">Requirements</th>
                                <th width="300px">File</th>
                            </tr>
                        </thead>
                        <tbody id="reqirements-contain">
                            @if($data->id)
                            <!-- for update [need to recode] -->
                                @foreach($data->files as $id => $requirement)
                                    <tr class="row-{{$loop->iteration}}">
                                        <td>{!!wordwrap($requirement->desc(), 135, "<br />\n",true)!!}</td>
                                        <td>
                                            <div class="file-upload bg-info btn btn-sm small-button" data-bs-toggle="tooltip" title="Upload File">
                                                <label for="require-old-{{$loop->iteration}}">
                                                    @if($requirement->fwtm_path)
                                                    <i class="ti-check text-white"></i>
                                                    @else
                                                    <i class="ti-cloud-up text-white"></i>
                                                    @endif
                                                </label>
                                                <input type="file" class="form-control required-hide" id="require-old-{{$loop->iteration}}" value="" name="require[{{$requirement->id}}][file]">
                                            </div>
                                                    @if($requirement->fwtm_path)
                                            <a class="btn btn-sm bg-primary small-button" href="{{url($requirement->fwtm_path)}}" download="{{$data->claimant->cit_fullname}}_{{$requirement->desc()}}" data-bs-toggle="tooltip" title="Download File"><i class="ti-download text-white"></i></a>
                                                    @endif
                                            <input type="hidden" name="require[{{$requirement->id}}][req_id]" value="{{$requirement->req_id}}" >

                                            @if($requirement->fwtm_is_active == 0)
                                                <a href="#req-sec" class="btn btn-sm btn-info remove-row" data-bs-toggle="tooltip" title="Remove Requirment" data-remove="requirement" data-id="{{$requirement->id}}" data-active='1'>
                                                    <i class="ti-reload"></i>
                                                </a>
                                            @else
                                                <a href="#req-sec" class="btn btn-sm btn-danger remove-row" data-bs-toggle="tooltip" title="Remove Requirment" data-remove="requirement" data-id="{{$requirement->id}}" data-active='0'>
                                                    <i class="ti-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            <!-- for add -->
                                @foreach($requirements as $id => $requirement)
                                    <tr class="row-{{$loop->iteration}}">
                                        <td>{!!wordwrap($requirement, 135, "<br />\n",true)!!}</td>
                                        <td>
                                            <div class="file-upload bg-info btn btn-sm small-button" data-bs-toggle="tooltip" title="Upload File">
                                                <label for="require-old-{{$loop->iteration}}">
                                                    <i class="ti-cloud-up text-white"></i>
                                                </label>
                                                <input type="file" class="form-control required-hide" id="require-old-{{$loop->iteration}}" value="" name="require[{{$loop->iteration}}][file]">
                                            </div>
                                            <input type="hidden" name="require[{{$loop->iteration}}][req_id]" value="{{$loop->iteration}}" >
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
                <div class="col-md-4 select-contain">
                    <div class="form-group m-form__group " id='select-contain-prepared'>
                        {{ Form::label('wtcm_prepared_by', 'Prepared By', ['class' => 'fs-6 fw-bold']) }}<span class="text-danger">*</span>
                        
                        {{ 
                            Form::select('wtcm_prepared_by', 
                                [],
                                $data->wtcm_prepared_by, 
                                $attributes = array(
                                'id' => 'wtcm_prepared_by',
                                'data-url' => 'citizens/selectEmployee',
                                'data-placeholder' => 'Search Social Worker',
                                'data-contain' => 'select-contain-prepared',
                                'data-value' =>isset($data->prepared_by) ? $data->prepared_by->fullname : '',
                                'data-value_id' =>$data->wtcm_prepared_by,
                                'class' => 'form-control ajax-select',
                            )) 
                        }}
                        <span class="validate-err"  id="err_wtcm_prepared_by"></span>
                    </div>
                </div>

                <div class="col-md-4 select-contain">
                    <div class="form-group m-form__group ">
                        {{ Form::label('wtcm_reviewed_by', 'Reviewed By', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::select('wtcm_reviewed_by', 
                                [],
                                $data->wtcm_reviewed_by, 
                                $attributes = array(
                                'id' => 'wtcm_reviewed_by',
                                'data-url' => 'citizens/selectEmployee',
                                'data-placeholder' => 'Search Social Worker',
                                'data-contain' => 'select-contain-prepared',
                                'data-value' =>isset($data->reviewed_by) ? $data->reviewed_by->fullname : '',
                                'data-value_id' =>$data->wtcm_reviewed_by,
                                'class' => 'form-control ajax-select',
                            )) 
                        }}
                        <span class="validate-err"  id="err_wswa_social_worker"></span>
                    </div>
                </div>
                <div class="col-md-4 select-contain">
                    <div class="form-group m-form__group ">
                        {{ Form::label('approve', 'Approved By (Social Worker)', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text('approve', 
                            isset($data->approved_by) ? $data->approved_by->fullname : '', 
                            $attributes = array(
                                'id' => 'approve',
                                'class' => 'form-control',
                                'readonly'
                            )) 
                        }}
                        <span class="validate-err"  id="err_wswa_social_worker"></span>
                    </div>
                </div>
                <div class="col-md-4 select-contain">
                    <div class="form-group m-form__group ">
                        {{ Form::label('wtcm_cashier_id', 'OR No.', ['class' => 'fs-6 fw-bold']) }}
                        {{ Form::select('wtcm_cashier_id',
                            $or_num,
                            isset($data->wtcm_cashier_id) ? $data->wtcm_cashier_id : '', 
                            array(
                                'class' => 'form-control form-control-solid select3',
                                'id'=>'wtcm_cashier_id',
                                isset($data->tfoc_id) ? '':'disabled',
                                ($data->wtcm_cashier_id) ? 'disabled':''
                                ))
                        }}
                        <span class="validate-err"  id="err_wtcm_cashier_id"></span>
                    </div>
                </div>
                <div class="col-md-4 select-contain">
                    <div class="form-group m-form__group ">
                        {{ Form::label('or_amount', 'OR Amount.', ['class' => 'fs-6 fw-bold ']) }}
                        {{ 
                            Form::text('or_amount', 
                            isset($data->transaction) ? $data->transaction->total_amount : '', 
                            $attributes = array(
                                'id' => 'or_amount',
                                'class' => 'form-control select_amount',
                                'readonly'
                            )) 
                        }}
                        <span class="validate-err"  id="err_wswa_social_worker"></span>
                    </div>
                </div>
                <div class="col-md-4 select-contain">
                    <div class="form-group m-form__group ">
                        {{ Form::label('or_date', 'OR Date.', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::date('or_date', 
                            isset($data->transaction) ? $data->transaction->cashier_or_date : '', 
                            $attributes = array(
                                'id' => 'or_date',
                                'class' => 'form-control select_or_date',
                                'readonly'
                            )) 
                        }}
                        <span class="validate-err"  id="err_wswa_social_worker"></span>
                    </div>
                </div>
            </div>
        <div class="modal-footer">
            @if($data->id)
            {{-- dd($data->wtcm_is_approve) --}}
            <input type="button" id="approveBtn" value="{{(isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'Approved':'Approve'}}" class="btn  btn-light" {{(isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':''}}>
            <a href="{{route('tcm.print',['id' => $data->id])}}" target="_blank">
                <input type="button" value="{{__('Print')}}" class="btn  btn-light">
            </a>
            @endif
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
            <a href="{{route('tcm.index')}}"><input type="button" value="{{__('Cancel')}}" class="btn  btn-light"></a>
        </div>
    {{Form::close()}}
</div>



<script src="{{ asset('js/SocialWelfare/select-ajax.js?v='.filemtime(getcwd().'/js/SocialWelfare/select-ajax.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/citizen-ajax.js?v='.filemtime(getcwd().'/js/SocialWelfare/citizen-ajax.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/add-travel-minor.js?v='.filemtime(getcwd().'/js/SocialWelfare/add-travel-minor.js').'') }}"></script>
<script src="{{ asset('js/ajax_validation.js?v='.filemtime(getcwd().'/js/ajax_validation.js').'') }}"></script>
