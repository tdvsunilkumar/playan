<div class="container-fluid card pt-4">
    
    {{ Form::open(array('url' => 'social-welfare/senior-citizen-id/store','class'=>'formDtls create-form', 'files' => true)) }}
        {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
        {{ Form::hidden('family_count',$data->family_count, array('id' => '_family_count')) }}
        {{ Form::hidden('associate_count',$data->associate_count, array('id' => '_associate_count')) }}
        <input type="hidden" id="requirements_list" value='{!!json_encode($requirements)!!}'>
        <div class="row" id="search-sec">
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ 
                        Form::radio('wsca_is_renewal', 
                        0,
                        false, 
                        $attributes = array(
                        'id' => 'wsca_is_renewal_no',
                        'class' => 'form-check-input',
                        $data->wsca_is_renewal === '0'? 'checked':'',
                        $data->wsca_is_renewal != '0' && $data->id ? 'disabled':'',
                        )) 
                    }}
                    {{ Form::label('wsca_is_renewal_no', 'New Applicant', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::radio('wsca_is_renewal', 
                        1, 
                        false,
                        $attributes = array(
                        'id' => 'wsca_is_renewal_yes',
                        'class' => 'form-check-input',
                        $data->wsca_is_renewal === '1'? 'checked':'',
                        $data->wsca_is_renewal != '1' && $data->id ? 'disabled':'',
                        )) 
                    }}
                    {{ Form::label('wsca_is_renewal_yes', 'Renewal', ['class' => 'fs-6 fw-bold']) }}
                </div>
            </div>
            <div class="col-sm-3 hide-search">
                <div class="form-group m-form__group ">
                        {{ Form::label('osca_search', 'Search OSCA ID', ['class' => ' fs-6 fw-bold']) }}
                    <div class="form-inline">
                        {{ 
                            Form::text('osca_search',
                            $data->wsca_new_osca_id_no,
                                $attributes = array(
                                'id' => 'osca_search',
                                'maxlength' => 7,
                                'class' => 'form-control osca_id',
                            )) 
                        }}
                        <input id="searchBtn" type="button" name="search" value="{{__('Search')}}" class="btn mx-3 btn-info">
                    </div>
                    <span class="validate-err"  id="err_osca_search"></span>
                </div>
            </div>
        </div>
        <div class="row citizen_group" id="claimant-sec">
            <div class="col-sm-4">
                <div class="form-group m-form__group required select-contain" id='contain_cit_id'>
                    <div>
                        {{ Form::label('cit_last_name', 'Name of Claimant', ['class' => 'required fs-6 fw-bold']) }}<span class="text-danger">*</span>
                        <a  data-size="lg" data-url="{{ url('/citizens/store') }}?field=cit_id" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" data-title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary float-end add-citizen-btn btn_open_second_modal">
                        <i class="ti-plus text-white"></i>
                        </a>
                        @if($data->id)
                        <a  data-size="lg" data-url="{{ url('/citizens/store') }}?field=cit_id{{$data->cit_id ? '&id='.$data->cit_id : ''}}" data-bs-toggle="tooltip" title="{{__('Manage Citizen')}}" data-title="{{__('Manage Citizen')}}" class="btn btn-sm btn-secondary float-end add-citizen-btn btn_open_second_modal">
                            <i class="ti-menu text-white"></i>
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
                            'data-value' =>isset($data->claimant->cit_fullname) ? $data->claimant->cit_fullname : '',
                            'data-value_id' =>$data->cit_id,
                            'class' => 'form-control ajax-select get-citizen select_id',
                            (isset($data->wswa_approved_by) && ($data->wswa_approved_by) ) ? 'readonly':'',
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
                            'data-contain' => 'select-contain-citizen',
                            'id'=>'claimant_sex'
                        )) 
                    }}
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
            <div class="col-sm-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_place_birth', 'Place of Birth', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('claimant[cit_place_of_birth]',
                        isset($data->claimant->cit_place_of_birth) ? $data->claimant->cit_place_of_birth : '',
                        $attributes = array(
                        'id' => 'claimant_place_birth',
                        'class' => 'form-control form-control-solid select_cit_place_of_birth',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_status', 'Civil Status', ['class' => ' fs-6 fw-bold']) }}
                    
                    {{ 
                        Form::select('claimant[ccs_id]',
                        $civilstat,
                        isset($data->claimant) ? $data->claimant->ccs_id : '', 
                        array(
                            'class' => 'form-control form-control-solid select_ccs_id select3',
                            'data-contain' => 'select-contain-citizen',
                            'id'=>'claimant_status'
                            )) 
                    }}
                </div>
            </div>
            <!-- row -->
            <div class="col-sm-10">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_address', 'Address', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('claimant_address',
                        isset($data->claimant) ? $data->claimant->full_add() : '',
                        $attributes = array(
                        'id' => 'claimant_address',
                        'class' => 'form-control form-control-solid select_cit_full_address',
                        'disabled'=>'disabled'
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_since_when', 'Since When', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wsca_since_when',
                        isset($data->wsca_since_when) ? $data->wsca_since_when : '',
                        $attributes = array(
                        'id' => 'wsca_since_when',
                        'class' => 'form-control form-control-solid',
                        )) 
                    }}
                </div>
            </div>
            <!-- row -->
            <div class="col-sm-10">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_previous_address', 'Previous Address', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wsca_previous_address',
                        isset($data->wsca_previous_address) ? $data->wsca_previous_address : '',
                        $attributes = array(
                        'id' => 'wsca_previous_address',
                        'class' => 'form-control form-control-solid select_cit_full_address',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('wstor_id', 'Type of Residency', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::select(
                            'wstor_id',
                            $residencyType,
                            $data->wstor_id, 
                            array(
                                'class' => 'form-control  select3',
                                'data-contain' => 'select-contain-citizen',
                                'id'=>'wstor_id',
                                )
                        ) 
                    }}

                </div>
            </div>
            <!-- row -->
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_height', 'Height', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('claimant[cit_height]',
                        isset($data->claimant->cit_height) ? $data->claimant->cit_height : '',
                        $attributes = array(
                        'id' => 'claimant_height',
                        'class' => 'form-control form-control-solid select_cit_height',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_weight', 'Weight', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('claimant[cit_weight]',
                        isset($data->claimant->cit_weight) ? $data->claimant->cit_weight : '',
                        $attributes = array(
                        'id' => 'claimant_weight',
                        'class' => 'form-control form-control-solid select_cit_weight',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('claimant_educ', 'Educational Attainment', ['class' => ' fs-6 fw-bold']) }}
                    
                    {{ Form::select('claimant[cea_id]',
                        $educ,
                        isset($data->claimant) ? $data->claimant->cea_id : '', 
                        array(
                            'class' => 'form-control form-control-solid select_cea_id select3',
                            'data-contain' => 'select-contain-citizen',
                            'id'=>'claimant_educ'
                            ))
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_skill', 'Skill', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wsca_skill',
                        $data->wsca_skill,
                        $attributes = array(
                        'id' => 'wsca_skill',
                        'class' => 'form-control form-control-solid',
                        )) 
                    }}
                </div>
            </div>
            <!-- row -->
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_occupation', 'Occupation', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wsca_occupation',
                        $data->wsca_occupation,
                        $attributes = array(
                        'id' => 'wsca_occupation',
                        'class' => 'form-control form-control-solid',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_monthly_income', 'Monthly Income', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wsca_monthly_income',
                        currency_format($data->wsca_monthly_income),
                        $attributes = array(
                        'id' => 'wsca_monthly_income',
                        'class' => 'form-control form-control-solid',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_pension_amount', 'Pension Amount', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wsca_pension_amount',
                        currency_format($data->wsca_pension_amount),
                        $attributes = array(
                        'id' => 'wsca_pension_amount',
                        'class' => 'form-control form-control-solid',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                
            </div>
        </div>
            <!-- row -->
        <div class="row citizen_group" id="spouse-sec">
            <div class="col-sm-3">
                <div class="form-group m-form__group  select-contain" id="contain_wsca_name_of_spouse"> 
                    <div>
                        {{ Form::label('cit_last_name', 'Name of Spouse', ['class' => ' fs-6 fw-bold']) }}

                        <a data-size="lg" data-url="{{ url('/citizens/store') }}?field=wsca_name_of_spouse" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" data-title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary float-end add-citizen-btn btn_open_second_modal">
                            <i class="ti-plus text-white"></i>
                        </a>
                        @if($data->id)
                        <a data-size="lg" data-url="{{ url('/citizens/store') }}?field=wsca_name_of_spouse{{$data->wsca_name_of_spouse ? '&id='.$data->wsca_name_of_spouse : ''}}" data-bs-toggle="tooltip" title="{{__('Manage Citizen')}}" data-title="{{__('Manage Citizen')}}" class="btn btn-sm btn-secondary float-end add-citizen-btn btn_open_second_modal">
                            <i class="ti-menu text-white"></i>
                        </a>
                        @endif
                    </div>

                    {{ 
                        Form::select('wsca_name_of_spouse', 
                            [],
                            $data->wsca_name_of_spouse, 
                            $attributes = array(
                            'id' => 'wsca_name_of_spouse',
                            'data-url' => 'citizens/getCitizens',
                            'data-placeholder' => 'Search Citizen',
                            'data-value' =>isset($data->spouse->cit_fullname) ? $data->spouse->cit_fullname : '',
                            'data-value_id' =>$data->wsca_name_of_spouse,
                            'class' => 'form-control ajax-select get-citizen select_id',
                            (isset($data->wswa_approved_by) && ($data->wswa_approved_by) ) ? 'readonly':'',
                        )) 
                    }}
                    
                    
                    <span class="validate-err"  id="err_wsca_name_of_spouse"></span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_date_of_marriage', 'Date of Marriage', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::date('wsca_date_of_marriage',
                        $data->wsca_date_of_marriage,
                        $attributes = array(
                        'id' => 'wsca_date_of_marriage',
                        'class' => 'form-control form-control-solid',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_place_of_marriage', 'Place of Marriage', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wsca_place_of_marriage',
                        $data->wsca_place_of_marriage,
                        $attributes = array(
                        'id' => 'wsca_place_of_marriage',
                        'class' => 'form-control form-control-solid',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('spouse_bday', 'Birthdate of Spouse', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::date('spouse[cit_date_of_birth]',
                        isset($data->spouse->cit_date_of_birth) ? $data->spouse->cit_date_of_birth : '',
                        $attributes = array(
                        'id' => 'spouse_bday',
                        'class' => 'form-control form-control-solid select_cit_date_of_birth',
                        )) 
                    }}
                </div>
            </div>
        </div> 
        <div class="row" id="dependant-sec">
            <h4>Family Composition</h4>
            <table class="table tbl_dependant">
                <thead>
                    <tr>
                        <th style="width: 45%;">Name</th>
                        <th>Relation</th>
                        <th>Age</th>
                        <th>Civil Status</th>
                        <th>Occupation</th>
                        <th>Monthly Income</th>
                        <th style="width: 5%;">
                            <a class="btn btn-primary add-dependent" data-id="0" data-bs-toggle="tooltip" title="{{__('Add Dependant')}}">
                            <i class="ti-plus text-white"></i>
                        </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data->family))
                        @foreach($data->family as $dependent)
                        <tr class="citizen_group old-row" id="dependent-{{$dependent->id}}-contain">
                            <td class="select-contain" id="contain_dependent-{{$dependent->id}}">
                                <select 
                                    class="form-control select_id ajax-select get-citizen" 
                                    data-placeholder="Search Citizen" 
                                    data-url="citizens/getCitizens" 
                                    id="dependent-{{$dependent->id}}" 
                                    name="dependent[{{$dependent->id}}][cit_id]"
                                    data-value="{{$dependent->info->cit_fullname}}"
                                    data-value_id="{{$dependent->info->id}}"
                                    ></select>
                            </td>
                            <td>
                                <input id="dependent-edit-relation-{{$dependent->id}}" class="form-control" name="dependent[{{$dependent->id}}][relation]" type="text" value="{{$dependent->wsfc_relation}}" >
                            </td>
                            <td class="select_age">{{$dependent->info->cit_age}}</td>
                            <td class="select_status">
                            {{ 
                                Form::select('dependent['.$dependent->id.'][data][ccs_id]',
                                $civilstat,
                                $dependent->info->ccs_id, 
                                array(
                                    'class' => 'form-control form-control-solid select3 select_ccs_id',
                                    'id'=>'dependent-civil-'.$dependent->id.'',
                                    'data-contain'=>'dependent-'.$dependent->id.'-contain'
                                    )) 
                            }}
                            </td>
                            <td>
                                <input id="dependent-edit-relation-{{$dependent->id}}" class="form-control" name="dependent[{{$dependent->id}}][occupation]" type="text" value="{{$dependent->wsfc_occupation}}" >
                            </td>
                            <td>
                                <input id="dependent-edit-relation-{{$dependent->id}}" class="form-control" name="dependent[{{$dependent->id}}][income]" type="text" value="{{currency_format($dependent->wsfc_monthly_income)}}" >
                            </td>
                            <td>
                                @if($dependent->wsfc_is_active == 0)
                                    <a  class="btn btn-sm btn-info remove-row" data-bs-toggle="tooltip" title="{{__('Activate Dependent')}}" data-remove="dependent" data-id="{{$dependent->id}}" data-active="1">
                                        <i class="ti-reload text-white"></i>
                                    </a>
                                @else
                                    <a  class="btn btn-sm btn-danger remove-row" data-bs-toggle="tooltip" title="{{__('Remove Dependent')}}" data-remove="dependent" data-id="{{$dependent->id}}" data-active="0">
                                        <i class="ti-trash text-white"></i>
                                    </a>
                                @endif
                                <a  data-size="lg" data-url="{{ url('/citizens/store?field=dependent-'.$dependent->id.'') }}" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary add-citizen-btn btn_open_second_modal">
                                    <i class="ti-plus text-white"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="row mt-3" id="associate-sec">
            <h4>Membership in other Senior Citizen Association</h4>
            <table class="table tbl_associate">
                <thead>
                    <tr>
                        <th style="width: 45%;">Name of Association</th>
                        <th style="width: 45%;">Address</th>
                        <th>Position (if officer, date elected)</th>
                        <th style="width: 5%;">
                            <a  class="btn btn-primary add-associate" data-id="0" data-bs-toggle="tooltip" title="{{__('Add Association')}}">
                                <i class="ti-plus text-white"></i>
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
                                $associate->wsa_association_name,
                                $attributes = array(
                                'id' => 'associate-name-'.$associate->id,
                                'class' => 'form-control form-control-solid'
                                )) 
                            }}
                            </td>
                            <td>
                            {{ 
                                Form::text('associate['.$associate->id.'][address]',
                                $associate->wsa_assocation_address,
                                $attributes = array(
                                'id' => 'associate-address-'.$associate->id,
                                'class' => 'form-control form-control-solid'
                                )) 
                            }}
                            </td>
                            <td>
                            {{ 
                                Form::text('associate['.$associate->id.'][position]',
                                $associate->wsa_association_position,
                                $attributes = array(
                                'id' => 'associate-position-'.$associate->id,
                                'class' => 'form-control form-control-solid'
                                )) 
                            }}
                            </td>
                            
                            <td>
                                @if($associate->wsa_is_active == 0)
                                    <a class="btn btn-sm btn-info remove-row" data-bs-toggle="tooltip" title="{{__('Activate Associate')}}" data-remove="associate" data-id="{{$associate->id}}" data-active="1">
                                        <i class="ti-reload text-white"></i>
                                    </a>
                                @else
                                    <a class="btn btn-sm btn-danger remove-row" data-bs-toggle="tooltip" title="{{__('Remove Associate')}}" data-remove="associate" data-id="{{$associate->id}}" data-active="0">
                                        <i class="ti-trash text-white"></i>
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
            <div class="col-sm-12">
                {{ Form::label('', 'With existing senior citizens ID', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::radio('wsca_existing_senior', 
                    1, 
                    false,
                    $attributes = array(
                    'id' => 'wspa_needs_problem_yes',
                    'class' => 'form-check-input',
                    $data->wsca_existing_senior === 1? 'checked':''
                    )) 
                }}
                {{ Form::label('wspa_needs_problem_yes', 'Yes', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::radio('wsca_existing_senior', 
                    0,
                    false, 
                    $attributes = array(
                    'id' => 'wspa_needs_problem_no',
                    'class' => 'form-check-input',
                    $data->wsca_existing_senior === 0? 'checked':''
                    )) 
                }}
                {{ Form::label('wspa_needs_problem_no', 'No', ['class' => 'fs-6 fw-bold']) }}
                <span class="validate-err"  id="err_wswa_remarks"></span>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_existing_id', 'If yes, ID NO.', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wsca_existing_id',
                        $data->wsca_existing_id,
                        $attributes = array(
                        'id' => 'wsca_existing_id',
                        'class' => 'form-control form-control-solid',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_existing_place_of_issue', 'Place of Issue', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wsca_existing_place_of_issue',
                        $data->wsca_existing_place_of_issue,
                        $attributes = array(
                        'id' => 'wsca_existing_place_of_issue',
                        'class' => 'form-control form-control-solid',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_existing_date_of_issue', 'Date of Issue', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::date('wsca_existing_date_of_issue',
                        $data->wsca_existing_date_of_issue,
                        $attributes = array(
                        'id' => 'wsca_existing_date_of_issue',
                        'class' => 'form-control form-control-solid',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_remarks', 'Remarks', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wsca_remarks',
                        $data->wsca_remarks,
                        $attributes = array(
                        'id' => 'wsca_remarks',
                        'class' => 'form-control form-control-solid',
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
                                <th>Requirements</th>
                                <th style="width: 10%;">
                                    <a href="#req-sec" class="btn btn-primary add-require" data-id="0" data-bs-toggle="tooltip" title="{{__('Add Requirement')}}">
                                        <i class="ti-plus text-white"></i>
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
                                                    @if($requirement->fwsc_path)
                                                    <i class="ti-check text-white"></i>
                                                    @else
                                                    <i class="ti-cloud-up text-white"></i>
                                                    @endif
                                                </label>
                                                <input type="file" class="form-control required-hide" id="require-old-{{$loop->iteration}}" value="" name="require[{{$requirement->id}}][file]">
                                            </div>
                                                    @if($requirement->fwsc_path)
                                            <a class="btn btn-sm bg-primary small-button" href="{{url($requirement->fwsc_path)}}" download="{{$data->claimant->cit_last_name}}_{{$requirement->req_name}}"data-bs-toggle="tooltip" title="Download File"><i class="ti-download text-white"></i></a>
                                                    @endif
                                            <input type="hidden" name="require[{{$requirement->id}}][req_id]" value="{{$requirement->req_id}}" >
                                            <input type="hidden" name="require[{{$requirement->id}}][req_type]" type="text" value="{{$requirement->req_type}}">
                                            <span>
                                                @if($requirement->fwsc_is_active == 0)
                                                    <a  class="btn btn-sm btn-info remove-row" data-bs-toggle="tooltip" title="Remove Requirment" data-remove="requirement" data-id="{{$requirement->id}}" data-active='1'>
                                                        <i class="ti-reload text-white"></i>
                                                    </a>
                                                @else
                                                    <a class="btn btn-sm btn-danger remove-row" data-bs-toggle="tooltip" title="Remove Requirement" data-remove="requirement" data-id="{{$requirement->id}}" data-active='0'>
                                                        <i class="ti-trash text-white"></i>
                                                    </a>
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            <!-- for add -->
                                @foreach($requirements as $id => $requirement)
                                    <tr class="row-{{$loop->iteration}} new-row">
                                        <td>{{$requirement}}</td>
                                        <td>
                                            <div class="file-upload bg-info btn btn-sm small-button" data-bs-toggle="tooltip" title="Upload File">
                                                <label for="require-old-{{$loop->iteration}}">
                                                    <i class="ti-cloud-up text-white"></i>
                                                </label>
                                                <input type="file" class="form-control required-hide" id="require-old-{{$loop->iteration}}" value="" name="require[{{$loop->iteration}}][file]">
                                            <input type="hidden" name="require[{{$loop->iteration}}][req_type]" type="text" value="0">
                                            </div>
                                            <span>
                                                <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove require">
                                                    <i class="ti-trash text-white"></i>
                                                </a>

                                            </span>
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
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_new_osca_id_no', 'OSCA ID NO.', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wsca_new_osca_id_no',
                        $data->wsca_new_osca_id_no,
                        $attributes = array(
                        'id' => 'wsca_new_osca_id_no',
                        'maxlength' => 7,
                        'data-val' => $data->wsca_new_osca_id_no,
                        'class' => 'form-control form-control-solid osca_id',
                        'readonly'
                        )) 
                    }}
                    <span class="validate-err"  id="err_wsca_new_osca_id_no"></span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_new_osca_id_no_date_issued', 'Date Issued', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::date('wsca_new_osca_id_no_date_issued',
                        $data->wsca_new_osca_id_no_date_issued,
                        $attributes = array(
                        'id' => 'wsca_new_osca_id_no_date_issued',
                        'class' => 'form-control form-control-solid',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
            </div>
            <div class="col-sm-3">
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_fscap_id_no', 'FSCAP ID NO', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wsca_fscap_id_no',
                        $data->wsca_fscap_id_no,
                        $attributes = array(
                        'id' => 'wsca_fscap_id_no',
                        'class' => 'form-control form-control-solid ',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_fscap_id_no_date_issued', 'Date of Issue', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::date('wsca_fscap_id_no_date_issued',
                        $data->wsca_fscap_id_no_date_issued,
                        $attributes = array(
                        'id' => 'wsca_fscap_id_no_date_issued',
                        'class' => 'form-control form-control-solid',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
            </div>
            <div class="col-sm-3">
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wsca_philhealth_no', 'Philhealth No.', ['class' => ' fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wsca_philhealth_no',
                        $data->wsca_philhealth_no,
                        $attributes = array(
                        'id' => 'wsca_philhealth_no',
                        'class' => 'form-control form-control-solid select-cit_id_cit_philhealth_no',
                        )) 
                    }}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            @if($data->id)
                <a href="{{route('senior.print',['id' => $data->id])}}"  target="_blank" class="btn btn-info">
                    <i class="ti-printer text-white"></i>
                    {{__('Print')}}
                </a>
            @endif
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal" >
        </div>
    {{Form::close()}}
</div>


</div>
<script src="{{ asset('js/partials/select-ajax.js?v='.filemtime(getcwd().'/js/partials/select-ajax.js').'') }}"></script>
<script src="{{ asset('js/partials/citizen-ajax.js?v='.filemtime(getcwd().'/js/partials/citizen-ajax.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/social-welfare-create.js?v='.filemtime(getcwd().'/js/SocialWelfare/social-welfare-create.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/add-seniorcitizen.js?v='.filemtime(getcwd().'/js/SocialWelfare/add-seniorcitizen.js').'') }}"></script>
<script src="{{ asset('js/ajax_validation.js?v='.filemtime(getcwd().'/js/ajax_validation.js').'') }}"></script>

<!-- html append note: changeid is id -->
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

                    <input type="hidden" class="select_req_id" name="require[changeida][req_id]" type="text" value="changereqid">

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

<!-- dependants -->
<table>
    <tbody class="hidden" id="addDependants">
        <tr class="citizen_group new-row" id="dependent-changeid-contain">
            <td class="select-contain" id="contain_dependent-changeid">
                <select class="form-control select_id get-citizen" data-placeholder="Search Citizen" data-url="citizens/getCitizens" id="dependent-changeid" name="dependent[changeid][cit_id]"></select>
            </td>
            <td>
                <input id="dependent-relation-changeid" class="form-control" name="dependent[changeid][relation]" type="text" value="" >
            </td>
            <td class="select_age"></td>
            <td>
                {{ 
                    Form::select('dependent[changeid][data][ccs_id]',
                    $civilstat,
                    '', 
                    array(
                        'class' => 'form-control form-control-solid select3 select_ccs_id',
                        'id'=>'dependent-civil-changeid',
                        'data-contain'=>'dependent-changeid-contain'
                        )) 
                }}
            </td>
            <td>
                <input id="dependent-sc-occupation-changeid" class="form-control select_cit_occupation" name="dependent[changeid][data][cit_occupation]" type="text" value="" >
            </td>
            <td>
                <input id="dependent-sc-occupation-changeid" class="form-control " name="dependent[changeid][income]" type="text" value="" >
            </td>
            <td>
                <a  class="btn btn-sm btn-danger remove-row m-0" data-bs-toggle="tooltip" title="Remove Dependent">
                    <i class="ti-trash text-white"></i>
                </a>
                
                <a  data-size="lg" data-url="{{ url('/citizens/store') }}?field=dependent-civil-changeid" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" data-title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary add-citizen-btn btn_open_second_modal">
                    <i class="ti-plus text-white"></i>
                </a>
            </td>
        </tr>
    </tbody>
        
</table>

<table>
    <tbody class="hidden" id="addAssociates">
        <tr class="new-row">
            <td class="select-contain">
                <input id="associate-name-changeid" class="form-control" name="associate[changeid][name]" type="text" value="" >
            </td>
            <td>
                <input id="associate-address-changeid" class="form-control" name="associate[changeid][address]" type="text" value="" >
            </td>
            <td>
                <input id="associate-position-changeid" class="form-control" name="associate[changeid][position]" type="text" value="" >
            </td>
            <td>
                <a class="btn btn-sm btn-danger remove-row" data-bs-toggle="tooltip" title="Remove Dependent">
                    <i class="ti-trash text-white"></i>
                </a>
            </td>
        </tr>
    </tbody>
        
</table>
