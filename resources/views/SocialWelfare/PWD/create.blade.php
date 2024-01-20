<div class="container-fluid card pt-4">
    
    {{ Form::open(array('url' => 'social-welfare/pwd-id/store','class'=>'formDtls create-form', 'files' => true)) }}
        {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
        {{ Form::hidden('associate_count',$data->associate_count, array('id' => '_associate_count')) }}
        <input type="hidden" id="requirements_list" value='{!!json_encode($requirements)!!}'>
        <!-- row 1 -->
        <div class="row form-group m-form__group" id="search-sec">
            <div class="col-sm-6">
                {{ 
                    Form::radio('wpaf_application_type', 
                    0,
                    false, 
                    $attributes = array(
                    'id' => 'wpaf_application_type_new',
                    'class' => 'form-check-input',
                    $data->wpaf_application_type === 0? 'checked':'',
                    $data->wpaf_application_type != '0' && $data->id ? 'disabled':'',
                    )) 
                }}
                {{ Form::label('wpaf_application_type_new', 'New Applicant', ['class' => 'fs-6 fw-bold mx-2']) }}
                {{ 
                    Form::radio('wpaf_application_type', 
                    1, 
                    false,
                    $attributes = array(
                    'id' => 'wpaf_application_type_renew',
                    'class' => 'form-check-input',
                    $data->wpaf_application_type === 1? 'checked':'',
                    $data->wpaf_application_type != '1' && $data->id ? 'disabled':'',
                    )) 
                }}
                {{ Form::label('wpaf_application_type_renew', 'Renewal', ['class' => 'fs-6 fw-bold mx-2']) }}
                <div id="search-id" class="form-inline mt-2">
                    {{ Form::label('old_date_applied', 'Renewal Date', ['class' => 'fs-6 fw-bold mx-1']) }}
                    {{ 
                        Form::date('old_date_applied',
                        '',
                            $attributes = array(
                            'id' => 'wpaf_date_applied',
                            'placeholder' => 'Search ID',
                            'maxlength' => 19,
                            'class' => 'form-control ',
                            'disabled'
                        )) 
                    }}
                    {{ 
                        Form::text('id_search',
                        $data->wpaf_pwd_id_number,
                            $attributes = array(
                            'id' => 'id_search',
                            'placeholder' => 'Search ID',
                            'maxlength' => 19,
                            'class' => 'form-control pwd_id mx-4',
                        )) 
                    }}
                    <input id="searchBtn" type="button" value="{{__('Search')}}" class="btn  btn-info">
                </div>
            </div>
            <div class="col-sm-3">
                {{ Form::label('wpaf_pwd_id_number', 'PWD ID Number', ['class' => ' fs-6 fw-bold']) }}
                {{ 
                        Form::text('wpaf_pwd_id_number',
                        $data->wpaf_pwd_id_number,
                        $attributes = array(
                            'id' => 'wpaf_pwd_id_number',
                            'maxlength' => 19,
                            'data-val' => $data->wpaf_pwd_id_number,
                            'class' => 'form-control form-control-solid select-cit_id_pwd_id select_pwd_id',
                            'readonly'
                        )) 
                }}
                <span class="validate-err"  id="err_wpaf_pwd_id_number"></span>
            </div>
            <div class="col-sm-3">
                {{ Form::label('wpaf_date_applied', 'Date Applied', ['class' => ' fs-6 fw-bold']) }}
                {{ 
                        Form::date('wpaf_date_applied',
                        $data->wpaf_date_applied,
                        $attributes = array(
                        'id' => 'new_date_applied',
                        'class' => 'form-control form-control-solid',
                        )) 
                }}
                <span class="validate-err"  id="err_old_date_applied"></span>
            </div>
            
        </div>
        <!-- row 2 -->
        <div class="row citizen_group">
            <div class="col-sm-4">
                <div class="form-group m-form__group  select-contain" id='contain_cit_id'>
                    <div>
                        {{ Form::label('cit_id', 'Name of Claimant', ['class' => ' fs-6 fw-bold']) }}<span class="text-danger">*</span>
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
                            'data-url' => 'citizens/getCitizenMunicipalOnly',
                            'data-placeholder' => 'Search Citizen',
                            'data-value' =>isset($data->claimant->cit_fullname) ? $data->claimant->cit_fullname : '',
                            'data-value_id' =>$data->cit_id,
                            'class' => 'form-control ajax-select get-citizen select_id',
                        )) 
                    }}
                    
                    <span class="validate-err"  id="err_cit_id"></span>
                </div>
            </div>
            
            <div class="col-sm-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_bday', 'Date of Birth', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::date('claimant[cit_date_of_birth]',
                        isset($data->claimant->cit_date_of_birth) ? $data->claimant->cit_date_of_birth : '',
                        $attributes = array(
                        'id' => 'claimant_bday',
                        'class' => 'form-control form-control-solid select_cit_date_of_birth',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group " id="select-claimant_sex-contain">
                    {{ Form::label('claimant_sex', 'Sex', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::select('claimant[cit_gender]',
                        array('0'=>'Male','1'=>'Female'),
                        isset($data->claimant) ? $data->claimant->cit_gender : '', 
                        array(
                            'class' => 'form-control form-control-solid select_cit_gender select3',
                            'data-contain' => 'select-claimant_sex-contain',
                            'id'=>'claimant_sex'
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group" id="select-claimant_status-contain">
                    {{ Form::label('claimant_status', 'Civil Status', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::select('claimant[ccs_id]',
                        $civilstat,
                        isset($data->claimant) ? $data->claimant->ccs_id : '', 
                        array(
                            'class' => 'form-control form-control-solid select3 select_ccs_id',
                            'data-contain' => 'select-claimant_status-contain',
                            'id'=>'claimant_status'
                            )) 
                    }}
                </div>
            </div>
            <!-- row 3 -->
            <div class="col-sm-4">
                <div class="form-group m-form__group" id="select-disability-contain">
                    {{ Form::label('wptod_id', 'Type of disability', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::select('wptod_id',
                        $typeDisability,
                        $data->wptod_id, 
                        array(
                            'class' => 'form-control form-control-solid select3',
                            'data-contain' => 'select-disability-contain',
                            'id'=>'wptod_id'
                            )) 
                    }}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group m-form__group ">
                    {{ 
                        Form::radio('pwd_cause_type', 
                        0,
                        false, 
                        $attributes = array(
                        'id' => 'pwd_cause_type_inborn',
                        'class' => 'form-check-input',
                        $data->pwd_cause_type === 0? 'checked':''
                        )) 
                    }}
                    {{ Form::label('pwd_cause_type_inborn', 'Congenital / Inborn', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::radio('pwd_cause_type', 
                        1, 
                        false,
                        $attributes = array(
                        'id' => 'pwd_cause_type_aquire',
                        'class' => 'form-check-input',
                        $data->pwd_cause_type === 1? 'checked':''
                        )) 
                    }}
                    {{ Form::label('pwd_cause_type_aquire', 'Acquired', ['class' => 'fs-6 fw-bold']) }}<span class="text-danger">*</span>
                    <span class="validate-err"  id="err_pwd_cause_type"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div id="cause-inborn">
                    {{ 
                        Form::select('wpcodi_id',
                        $causeInborn,
                        $data->wpcodi_id, 
                        array(
                            'class' => 'form-control form-control-solid select3',
                            'data-contain' => 'select-disability-contain',
                            'id'=>'wpcodi_id'
                            )) 
                    }}
                </div>
                <div id="cause-aquire">
                    {{ 
                        Form::select('wpcoda_id',
                        $causeAcquire,
                        $data->wpcoda_id, 
                        array(
                            'class' => 'form-control form-control-solid select3',
                            'data-contain' => 'select-disability-contain',
                            'id'=>'wpcoda_id'
                            )) 
                    }}
                </div>
            </div>
            <!-- row 4 -->
            <div class="col-sm-3 brgy_group">
                <div class="form-group m-form__group  ">
                    {{ Form::label('claimant_address', 'House no.', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('claimant[cit_house_lot_no]',
                        isset($data->claimant) ? $data->claimant->cit_house_lot_no : '',
                        $attributes = array(
                        'id' => 'claimant_address',
                        'class' => 'form-control form-control-solid select_cit_house_lot_no',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-2 brgy_group">
                <div class="form-group m-form__group" id="select-brgy-contain">
                    {{ Form::label('claimant_brgy', 'Barangay', ['class' => ' fs-6 fw-bold']) }}
                    {{
                        Form::select('wpaf_brgy_id', 
                        $barangays, 
                        $data->wpaf_brgy_id, 
                        [
                            'id' => 'claimant_brgy', 
                            'class' => 'form-control select3 select_brgy_id select3', 
                            'data-contain' => 'select-brgy-contain',
                            'data-placeholder' => 'select a barangay...'
                        ])
                    }}

                    {{ Form::text('loc_local_code',$data->loc_local_code, array('id' => 'loc_local_code', 'class' => 'required-hide select_loc_local_code')) }}
                    {{ Form::text('barangay_uacs_code',$data->barangay_uacs_code, array('id' => 'barangay_uacs_code', 'class' => 'required-hide select_barangay_uacs_code')) }}
                    {{ Form::text('barangay_pwd_no',$data->barangay_pwd_no, array('id' => 'barangay_pwd_no', 'class' => 'required-hide select_barangay_pwd_no')) }}
                </div>
            </div>
            <div class="col-sm-2 brgy_group">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_municipality', 'Municipality', ['class' => ' fs-6 fw-bold']) }}
                    {{
                        Form::hidden('wpaf_municipal',$data->wpaf_municipal, array('id' => 'wpaf_municipal','class'=>'select_municipality_id'))
                    }}
                    {{
                        Form::text('claimant_municipal', 
                        isset($data->municipal) ? $data->municipal->mun_desc : '', 
                        [
                            'id' => 'claimant_municipal', 
                            'class' => 'form-control select_municipality', 
                            'disabled'
                        ])
                    }}
                </div>
            </div>
            <div class="col-sm-2 brgy_group">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_province', 'Province', ['class' => ' fs-6 fw-bold']) }}
                    {{
                        Form::hidden('wpaf_province',$data->wpaf_province, array('id' => 'wpaf_province','class'=>'select_province_id'))
                    }}
                    {{
                        Form::text('claimant_province', 
                        isset($data->province)? $data->province->prov_desc :'', 
                        [
                            'id' => 'claimant_province', 
                            'class' => 'form-control select_province', 
                            'disabled'
                        ])
                    }}
                </div>
            </div>
            <div class="col-sm-3 brgy_group">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_reg', 'Region', ['class' => ' fs-6 fw-bold']) }}
                    {{
                        Form::hidden('wpaf_region',$data->wpaf_region, array('id' => 'wpaf_region','class'=>'select_region_id'))
                    }}
                    {{
                        Form::text('claimant_reg', 
                        isset($data->region)? $data->region->reg_region :'', 
                        [
                            'id' => 'claimant_reg', 
                            'class' => 'form-control select_region', 
                            'disabled'
                        ])
                    }}
                </div>
            </div>
            <!-- row 5 -->
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_telno', 'Landline No.', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('claimant[cit_telephone_no]',
                        isset($data->claimant) ? $data->claimant->cit_telephone_no : '',
                        $attributes = array(
                        'id' => 'claimant_telno',
                        'class' => 'form-control form-control-solid select_cit_telephone_no',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_mobile', 'Contact no.', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('claimant[cit_mobile_no]',
                        isset($data->claimant) ? $data->claimant->cit_mobile_no : '',
                        $attributes = array(
                        'id' => 'claimant_mobile',
                        'class' => 'form-control form-control-solid select_cit_mobile_no',
                        )) 
                    }}
                </div>
            </div><div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_email', 'Email Address', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('claimant[cit_email_address]',
                        isset($data->claimant) ? $data->claimant->cit_email_address : '',
                        $attributes = array(
                        'id' => 'claimant_email',
                        'class' => 'form-control form-control-solid select_cit_email_address',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group " id="select-detail-contain">
                    {{ Form::label('claimant_educ', 'Educational Attainment', ['class' => ' fs-6 fw-bold']) }}
                    {{ Form::select('claimant[cea_id]',
                        $educ,
                        isset($data->claimant) ? $data->claimant->cea_id : '', 
                        array(
                            'class' => 'form-control form-control-solid select_cea_id select3',
                            'data-contain' => 'select-detail-contain',
                            'id'=>'claimant_educ'
                            ))
                    }}
                </div>
            </div>
            <!-- row 4 -->
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wpsoe_id', 'Status of Employment', ['class' => ' fs-6 fw-bold']) }}
                    {{ Form::select('wpsoe_id',
                        $employStatus,
                        $data->wpsoe_id, 
                        array(
                            'class' => 'form-control form-control-solid select3',
                            'data-contain' => 'select-detail-contain',
                            'id'=>'wpsoe_id'
                            ))
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wpcoe_id', 'Category of Employment', ['class' => ' fs-6 fw-bold']) }}
                    {{ Form::select('wpcoe_id',
                        $employCategory,
                        $data->wpcoe_id, 
                        array(
                            'class' => 'form-control form-control-solid select3',
                            'data-contain' => 'select-detail-contain',
                            'id'=>'wpcoe_id'
                            ))
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wptoe_id', 'Types of Employment', ['class' => ' fs-6 fw-bold']) }}
                    {{ Form::select('wptoe_id',
                        $employType,
                        $data->wptoe_id, 
                        array(
                            'class' => 'form-control form-control-solid select3',
                            'data-contain' => 'select-detail-contain',
                            'id'=>'wptoe_id'
                            ))
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wptoo_id', 'Occupation', ['class' => ' fs-6 fw-bold']) }}
                    {{ Form::select('wptoo_id',
                        $occupation,
                        $data->wptoo_id, 
                        array(
                            'class' => 'form-control form-control-solid select3',
                            'data-contain' => 'select-detail-contain',
                            'id'=>'wptoo_id'
                            ))
                    }}
                </div>
            </div>
        </div> 
        <div class="row mt-3" id="associate-sec">
            <h4>Organization Information</h4>
            <table class="table tbl_associate">
                <thead>
                    <tr>
                        <th>Organization Affliated</th>
                        <th >Contact Person</th>
                        <th>Office Address</th>
                        <th>Contact Number</th>
                        <th style="width: 5%;">
                            <a href="#associate-sec" class="btn btn-primary add-associate" data-id="0" data-bs-toggle="tooltip" title="{{__('Add Org')}}">
                                <i class="ti-plus"></i>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data->associate))
                        @foreach($data->associate as $associate)
                        <tr class="old-row">
                            <td class="">
                            {{ 
                                Form::text('associate['.$associate->id.'][name]',
                                $associate->wpo_organization,
                                $attributes = array(
                                'id' => 'associate-name-'.$associate->id,
                                'class' => 'form-control form-control-solid'
                                )) 
                            }}
                            </td>
                            <td>
                            {{ 
                                Form::text('associate['.$associate->id.'][person]',
                                $associate->wpo_contact_person,
                                $attributes = array(
                                'id' => 'associate-address-'.$associate->id,
                                'class' => 'form-control form-control-solid'
                                )) 
                            }}
                            </td>
                            <td>
                            {{ 
                                Form::text('associate['.$associate->id.'][address]',
                                $associate->wpo_office_address,
                                $attributes = array(
                                'id' => 'associate-position-'.$associate->id,
                                'class' => 'form-control form-control-solid'
                                )) 
                            }}
                            </td>
                            <td>
                            {{ 
                                Form::text('associate['.$associate->id.'][number]',
                                $associate->wpo_contact_number,
                                $attributes = array(
                                'id' => 'associate-position-'.$associate->id,
                                'class' => 'form-control form-control-solid'
                                )) 
                            }}
                            </td>
                            
                            <td>
                                @if($associate->wpo_is_active == 0)
                                    <a href="#associate-sec" class="btn btn-sm btn-info remove-row" data-bs-toggle="tooltip" title="{{__('Activate Associate')}}" data-remove="associate" data-id="{{$associate->id}}" data-active="1">
                                        <i class="ti-reload"></i>
                                    </a>
                                @else
                                    <a href="#associate-sec" class="btn btn-sm btn-danger remove-row" data-bs-toggle="tooltip" title="{{__('Remove Associate')}}" data-remove="associate" data-id="{{$associate->id}}" data-active="0">
                                        <i class="ti-trash"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-sm-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('wpaf_sss', 'SSS No.', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wpaf_sss',
                        $data->wpaf_sss,
                        $attributes = array(
                        'id' => 'wpaf_sss',
                        'class' => 'form-control form-control-solid select-cit_id_cit_sss_no',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wpaf_gsis', 'GSIS No.', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wpaf_gsis',
                        $data->wpaf_gsis,
                        $attributes = array(
                        'id' => 'wpaf_gsis',
                        'class' => 'form-control form-control-solid select-cit_id_cit_gsis_no',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('wpaf_pagibig', 'Pagibig No.', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wpaf_pagibig',
                        $data->wpaf_pagibig,
                        $attributes = array(
                        'id' => 'wpaf_pagibig',
                        'class' => 'form-control form-control-solid select-cit_id_cit_pagibig_no',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wpaf_psn', 'PSN No.', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wpaf_psn',
                        $data->wpaf_psn,
                        $attributes = array(
                        'id' => 'wpaf_psn',
                        'class' => 'form-control form-control-solid select-cit_id_cit_psn_no',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('wpaf_philhealth', 'Philhealth No.', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wpaf_philhealth',
                        $data->wpaf_philhealth,
                        $attributes = array(
                        'id' => 'wpaf_philhealth',
                        'class' => 'form-control form-control-solid select-cit_id_cit_philhealth_no',
                        )) 
                    }}
                </div>
            </div>
        </div>
        <div class="row">
            <h4>Family Background</h4>
            <div class="col-sm-4">
                <div class="form-group m-form__group  select-contain" id="contain_wpaf_fathersname">
                    <div>
                        {{ Form::label('wpaf_fathersname', "Father's name", ['class' => ' fs-6 fw-bold']) }}
                        <a href="#" data-size="lg" data-url="{{ url('/citizens/store') }}?field=cit_id" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary float-end add-citizen-btn btn_open_second_modal" id="modal-btn">
                        <i class="ti-plus"></i>
                        </a>
                        @if($data->id)
                        <a href="#" data-size="lg" data-url="{{ url('/citizens/store') }}?field=cit_id{{$data->wpaf_fathersname ? '&id='.$data->wpaf_fathersname : ''}}" data-bs-toggle="tooltip" title="{{__('Manage Citizen')}}" class="btn btn-sm btn-secondary float-end add-citizen-btn btn_open_second_modal" id="modal-btn">
                            <i class="ti-menu"></i>
                        </a>
                        @endif
                    </div>
                    {{ 
                        Form::select('wpaf_fathersname', 
                            [],
                            $data->wpaf_fathersname, 
                            $attributes = array(
                            'id' => 'wpaf_fathersname',
                            'data-url' => 'citizens/getCitizens',
                            'data-placeholder' => 'Search Citizen',
                            'data-value' =>isset($data->father->cit_fullname) ? $data->father->cit_fullname : '',
                            'data-value_id' =>$data->wpaf_fathersname,
                            'class' => 'form-control ajax-select get-citizen select_id',
                        )) 
                    }}
                    
                    <span class="validate-err"  id="err_wpaf_fathersname"></span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group m-form__group  select-contain" id="contain_wpaf_mothersname">
                    <div>
                        {{ Form::label('wpaf_mothersname', "Mother's name", ['class' => ' fs-6 fw-bold']) }}
                        <a href="#" data-size="lg" data-url="{{ url('/citizens/store') }}?field=cit_id" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary float-end add-citizen-btn btn_open_second_modal" id="modal-btn">
                        <i class="ti-plus"></i>
                        </a>
                        @if($data->id)
                        <a href="#" data-size="lg" data-url="{{ url('/citizens/store') }}?field=cit_id{{$data->wpaf_mothersname ? '&id='.$data->wpaf_mothersname : ''}}" data-bs-toggle="tooltip" title="{{__('Manage Citizen')}}" class="btn btn-sm btn-secondary float-end add-citizen-btn btn_open_second_modal" id="modal-btn">
                            <i class="ti-menu"></i>
                        </a>
                        @endif
                    </div>
                    {{ 
                        Form::select('wpaf_mothersname', 
                            [],
                            $data->wpaf_mothersname, 
                            $attributes = array(
                            'id' => 'wpaf_mothersname',
                            'data-url' => 'citizens/getCitizens',
                            'data-placeholder' => 'Search Citizen',
                            'data-contain' => 'select-wpaf_mothersname-contain',
                            'data-value' =>isset($data->mother->cit_fullname) ? $data->mother->cit_fullname : '',
                            'data-value_id' =>$data->wpaf_mothersname,
                            'class' => 'form-control ajax-select get-citizen select_id',
                        )) 
                    }}
                    
                    <span class="validate-err"  id="err_wpaf_mothersname"></span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group m-form__group  select-contain" id="contain_wpaf_guardiansname">
                    <div>
                        {{ Form::label('wpaf_guardiansname', "Guardian's name", ['class' => ' fs-6 fw-bold']) }}
                        <a href="#" data-size="lg" data-url="{{ url('/citizens/store') }}?field=cit_id" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary float-end btn_open_second_modal add-citizen-btn" id="modal-btn">
                        <i class="ti-plus"></i>
                        </a>
                        @if($data->id)
                        <a href="#" data-size="lg" data-url="{{ url('/citizens/store') }}?field=cit_id{{$data->wpaf_guardiansname ? '&id='.$data->wpaf_guardiansname : ''}}" data-bs-toggle="tooltip" title="{{__('Manage Citizen')}}" class="btn btn-sm btn-secondary float-end btn_open_second_modal add-citizen-btn" id="modal-btn">
                            <i class="ti-menu"></i>
                        </a>
                        @endif
                    </div>
                    
                    {{ 
                        Form::select('wpaf_guardiansname', 
                            [],
                            $data->wpaf_guardiansname, 
                            $attributes = array(
                            'id' => 'wpaf_guardiansname',
                            'data-url' => 'citizens/getCitizens',
                            'data-placeholder' => 'Search Citizen',
                            'data-contain' => 'select-wpaf_guardiansname-contain',
                            'data-value' =>isset($data->guardian->cit_fullname) ? $data->guardian->cit_fullname : '',
                            'data-value_id' =>$data->wpaf_guardiansname,
                            'class' => 'form-control ajax-select get-citizen select_id',
                        )) 
                    }}
                    <span class="validate-err"  id="err_wpaf_guardiansname"></span>
                </div>
            </div>
        </div>
        <div class="row">
            
            <div class="col-sm-4 form-group m-form__group">
                <h4>Accomplished by<span class="text-danger">*</span>
                    <span class="form-group m-form__group">
                        {{ 
                            Form::radio('wpaf_accomplished_type', 
                            0,
                            false, 
                            $attributes = array(
                            'id' => 'wpaf_accomplished_type_applicant',
                            'class' => 'form-check-input',
                            $data->wpaf_accomplished_type === '0'? 'checked':''
                            )) 
                        }}
                        {{ Form::label('wpaf_accomplished_type_applicant', 'Applicant', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::radio('wpaf_accomplished_type', 
                            1, 
                            false,
                            $attributes = array(
                            'id' => 'wpaf_accomplished_type_guardian',
                            'class' => 'form-check-input',
                            $data->wpaf_accomplished_type === '1'? 'checked':''
                            )) 
                        }}
                        {{ Form::label('wpaf_accomplished_type_guardian', 'Guardian', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::radio('wpaf_accomplished_type', 
                            2, 
                            false,
                            $attributes = array(
                            'id' => 'wpaf_accomplished_type_rep',
                            'class' => 'form-check-input',
                            $data->wpaf_accomplished_type === '2'? 'checked':''
                            )) 
                        }}
                        {{ Form::label('wpaf_accomplished_type_rep', 'Representative', ['class' => 'fs-6 fw-bold']) }}
                    </span>
                </h4>
                <span class="validate-err"  id="err_wpaf_accomplished_type"></span>
            </div>
            <div class="col-sm-4 form-group m-form__group">
                {{ 
                    Form::text('wpaf_accomplished_by',
                    $data->wpaf_accomplished_by,
                    $attributes = array(
                    'id' => 'wpaf_accomplished_by',
                    'class' => 'form-control form-control-solid',
                    )) 
                }}
                <span class="validate-err"  id="err_wpaf_accomplished_by"></span>
            </div>
            <div class="col-sm-4 form-group m-form__group">
            </div>
            <div class="col-sm-4 form-group m-form__group">
                {{ Form::label('wpaf_physician', 'Name of the Certifying Physician', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::text('wpaf_physician',
                    $data->wpaf_physician,
                    $attributes = array(
                    'id' => 'wpaf_physician',
                    'class' => 'form-control form-control-solid',
                    )) 
                }}
            </div>
            <div class="col-sm-4 form-group m-form__group">
                {{ Form::label('wpaf_physician_license', 'Licence No.', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::text('wpaf_physician_license',
                    $data->wpaf_physician_license,
                    $attributes = array(
                    'id' => 'wpaf_physician_license',
                    'class' => 'form-control form-control-solid',
                    )) 
                }}
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
                                <th>Requirements</th>
                                <th style="width: 10%;">
                                    <a href="#req-sec" class="btn btn-primary add-require" data-id="0" data-bs-toggle="tooltip" title="{{__('Add Requirement')}}">
                                        <i class="ti-plus"></i>
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="reqirements-contain">
                            @if($data->id)
                            <!-- for update [need to recode] -->
                                @foreach($data->files as $id => $requirement)
                                    <tr class="row-{{$loop->iteration}}">
                                        <td>{{$requirement->req_name}}</td>
                                        <td>
                                            <div class="file-upload bg-info btn btn-sm small-button" data-bs-toggle="tooltip" title="Upload File">
                                                <label for="require-old-{{$loop->iteration}}">
                                                    @if($requirement->fwp_path)
                                                    <i class="ti-check text-white"></i>
                                                    @else
                                                    <i class="ti-cloud-up text-white"></i>
                                                    @endif
                                                </label>
                                                <input type="file" class="form-control required-hide" id="require-old-{{$loop->iteration}}" value="" name="require[{{$requirement->id}}][file]">
                                            </div>
                                                    @if($requirement->fwp_path)
                                            <a class="btn btn-sm bg-primary small-button" href="{{url($requirement->fwp_path)}}" download="{{$data->claimant->cit_last_name}}_{{$requirement->req_name}}" data-bs-toggle="tooltip" title="Download File"><i class="ti-download text-white"></i></a>
                                                    @endif
                                            <input type="hidden" name="require[{{$requirement->id}}][req_id]" value="{{$requirement->req_id}}" >
                                            <input type="hidden" name="require[{{$requirement->id}}][req_type]" value="{{$requirement->req_type}}" >

                                            @if($requirement->fwp_is_active == 0)
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
                                        <td>{{$requirement}}</td>
                                        <td>
                                            <div class="file-upload bg-info btn btn-sm small-button" data-bs-toggle="tooltip" title="Upload File">
                                                <label for="require-old-{{$loop->iteration}}">
                                                    <i class="ti-cloud-up text-white"></i>
                                                </label>
                                                <input type="file" class="form-control required-hide" id="require-old-{{$loop->iteration}}" value="" name="require[{{$loop->iteration}}][file]">
                                            </div>
                                            <span>
                                                <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove require">
                                                    <i class="ti-trash text-white"></i>
                                                </a>

                                            </span>
                                            <input type="hidden" name="require[{{$loop->iteration}}][req_id]" value="{{$loop->iteration}}" >
                                            <input type="hidden" name="require[{{$loop->iteration}}][req_type]" value="0" >
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
            <div class="col-sm-4 select-contain">
                <div class="form-group m-form__group " id="contain_wpaf_processing_officer">
                    {{ Form::label('wpaf_processing_officer', 'Processing Office', ['class' => ' fs-6 fw-bold']) }}<span class="text-danger">*</span>
                    {{ 
                        Form::select('wpaf_processing_officer', 
                            [],
                            $data->wpaf_processing_officer, 
                            $attributes = array(
                            'id' => 'wpaf_processing_officer',
                            'data-url' => 'citizens/selectEmployee',
                            'data-placeholder' => 'Search Social Worker',
                            'data-contain' => 'select-contain-officer',
                            'data-value' =>isset($data->processing->fullname) ? $data->processing->fullname : '',
                            'data-value_id' =>$data->wpaf_processing_officer,
                            'class' => 'form-control ajax-select',
                        )) 
                    }}

                    
                    <span class="validate-err"  id="err_wpaf_processing_officer"></span>
                </div>
            </div>
            <div class="col-sm-4 select-contain">
                <div class="form-group m-form__group " id="contain_wpaf_approving_officer">
                    {{ Form::label('wpaf_approving_officer', 'Approving Officer', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::select('wpaf_approving_officer', 
                            [],
                            $data->wpaf_approving_officer, 
                            $attributes = array(
                            'id' => 'wpaf_approving_officer',
                            'data-url' => 'citizens/selectEmployee',
                            'data-placeholder' => 'Search Social Worker',
                            'data-value' =>isset($data->approver->fullname) ? $data->approver->fullname : '',
                            'data-value_id' =>$data->wpaf_approving_officer,
                            'class' => 'form-control ajax-select',
                        )) 
                    }}
                    
                    <span class="validate-err"  id="err_wpaf_approving_officer"></span>
                </div>
            </div>
            <div class="col-sm-4 select-contain">
                <div class="form-group m-form__group " id="contain_wpaf_encoder">
                    {{ Form::label('wpaf_encoder', 'Encoder', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::select('wpaf_encoder', 
                            [],
                            $data->wpaf_encoder, 
                            $attributes = array(
                            'id' => 'wpaf_encoder',
                            'data-url' => 'citizens/selectEmployee',
                            'data-placeholder' => 'Search Social Worker',
                            'data-value' =>isset($data->encoder->fullname) ? $data->encoder->fullname : '',
                            'data-value_id' =>$data->wpaf_encoder,
                            'class' => 'form-control ajax-select',
                        )) 
                    }}
                    
                    <span class="validate-err"  id="err_wpaf_encoder"></span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group m-form__group ">
                    {{ Form::label('wpaf_reporting_unit', 'Name of reporting unit', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wpaf_reporting_unit',
                        $data->wpaf_reporting_unit,
                        $attributes = array(
                        'id' => 'wpaf_reporting_unit',
                        'class' => 'form-control form-control-solid',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group m-form__group ">
                    {{ Form::label('wpaf_control_no', 'Control No.', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wpaf_control_no',
                        $data->wpaf_control_no,
                        $attributes = array(
                        'id' => 'wpaf_control_no',
                        'class' => 'form-control form-control-solid',
                        )) 
                    }}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            @if($data->id)
                <a href="{{route('pwd.print',['id' => $data->id])}}"  target="_blank" class="btn btn-info">
                    <i class="ti-printer text-white"></i>
                    {{__('Print')}}
                </a>
            @endif
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        </div>
    {{Form::close()}}
</div>


</div>

<script src="{{ asset('js/partials/select-ajax.js?v='.filemtime(getcwd().'/js/partials/select-ajax.js').'') }}"></script>
<script src="{{ asset('js/partials/citizen-ajax.js?v='.filemtime(getcwd().'/js/partials/citizen-ajax.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/social-welfare-create.js?v='.filemtime(getcwd().'/js/SocialWelfare/social-welfare-create.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/add-pwd.js?v='.filemtime(getcwd().'/js/SocialWelfare/add-pwd.js').'') }}"></script>
<script src="{{ asset('js/ajax_validation.js?v='.filemtime(getcwd().'/js/ajax_validation.js').'') }}"></script>

<!-- requirements -->
<table>
    <tbody class="hidden" id="addRequirements">
        <tr class="new-row select_req" id="require-changeid-contain">

            <td class="select-contain" id="contain_require-newrow-changeid">
                <select class="form-control" data-placeholder="Search Requirement" data-url="social-welfare/assistance/getRequireList" id="require-newrow-changeid" name="require[changeida][req_id]"></select>
                changename
            </td>   

            <td>
                <div class="file-upload bg-info btn btn-sm small-button" data-bs-toggle="tooltip" title="Upload File">
                    <label for="require-changeid">
                        <i class="ti-cloud-up text-white"></i>
                    </label>

                    <input type="file" class="form-control required-hide" id="require-changeid" name="require[changeida][file]">

                    <input type="hidden" class="select_req_id" name="require[changeida][req_id]" type="text" value="">

                    <input type="hidden" name="require[changeida][req_type]" type="text" value="1">
                </div>
                
                <span>
                    <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove require">
                        <i class="ti-trash text-white"></i>
                    </a>
                </span>
            </td>
        </tr>
    </tbody>
</table>