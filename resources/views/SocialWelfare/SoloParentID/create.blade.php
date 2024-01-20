
<div class="container-fluid card pt-4">
    {{ Form::open(array('url' => 'social-welfare/solo-parent-id/store','class'=>'formDtls create-form', 'files' => true)) }}
        {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
        {{ Form::hidden('family_count',$data->family_count, array('id' => '_family_count')) }}
        <input type="hidden" id="requirements_list" value='{!!json_encode($requirements)!!}'>
        <div class="row" id="search-sec">
            <div class="col-sm-3">

                <div class="form-group m-form__group ">
                    {{ 
                        Form::radio('wspa_is_renewal', 
                        0,
                        false, 
                        $attributes = array(
                        'id' => 'wspa_is_renewal_no',
                        'class' => 'form-check-input',
                        $data->wspa_is_renewal === '0'? 'checked':'',
                        $data->wspa_is_renewal != '0' && $data->id ? 'disabled':'',
                        )) 
                    }}
                    {{ Form::label('wspa_is_renewal_no', 'New Applicant', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::radio('wspa_is_renewal', 
                        1, 
                        false,
                        $attributes = array(
                        'id' => 'wspa_is_renewal_yes',
                        'class' => 'form-check-input',
                        $data->wspa_is_renewal === '1'? 'checked':'',
                        $data->wspa_is_renewal != '1' && $data->id ? 'disabled':'',
                        )) 
                    }}
                    {{ Form::label('wspa_is_renewal_yes', 'Renewal', ['class' => 'fs-6 fw-bold']) }}
                </div>
            </div>
            <div class="col-sm-3" id="search-id">
                <div class="form-group m-form__group">
                    {{ Form::label('id_search', 'Search ID', ['class' => 'fs-6 fw-bold']) }}
                    <div class="form-inline">
                        {{ 
                            Form::text('id_search',
                            $data->wspa_id_number,
                                $attributes = array(
                                'id' => 'id_search',
                                'maxlength' => 7,
                                'class' => 'form-control osca_id ',
                            )) 
                        }}
                        <input id="searchBtn" type="button" value="{{__('Search')}}" class="btn mx-3 btn-info">
                    </div>
                    <span class="validate-err"  id="err_id_search"></span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group">
                    {{ Form::label('wspa_id_number', 'ID Number', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wspa_id_number',
                        $data->wspa_id_number,
                            $attributes = array(
                            'id' => 'wspa_id_number',
                            'maxlength' => 7,
                            'data-val' => $data->wspa_id_number,
                            'class' => 'form-control osca_id',
                            'readonly'
                        )) 
                    }}
                </div>
                <span class="validate-err"  id="err_wspa_id_number"></span>
            </div>
        </div>
        <div class="row citizen_group">
            <div class="col-sm-4">
                <div class="form-group m-form__group required select-contain" id='contain_cit_id'>
                    <div>
                        {{ Form::label('cit_last_name', 'Name of Claimant', ['class' => 'required fs-6 fw-bold']) }}<span class="text-danger">*</span>
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
                        )) 
                    }}
                    <span class="validate-err"  id="err_cit_id"></span>
                </div>
            </div>
            
            <div class="col-sm-1">
                <div class="form-group m-form__group required">
                    {{ Form::label('claimant_age', 'Age', ['class' => 'required fs-6 fw-bold']) }}
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
                <div class="form-group m-form__group required">
                    {{ Form::label('claimant_sex', 'Sex', ['class' => 'required fs-6 fw-bold']) }}
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
                <div class="form-group m-form__group required">
                    {{ Form::label('claimant_bday', 'Date of Birth', ['class' => 'required fs-6 fw-bold']) }}
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
                <div class="form-group m-form__group required">
                    {{ Form::label('claimant_place_birth', 'Place of Birth', ['class' => 'required fs-6 fw-bold']) }}
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
                <div class="form-group m-form__group required">
                    {{ Form::label('claimant_blood', 'Blood Type', ['class' => 'required fs-6 fw-bold']) }}
                    {{ 
                        Form::text('claimant[cit_blood_type]',
                        isset($data->claimant->cit_blood_type) ? $data->claimant->cit_blood_type : '',
                        $attributes = array(
                        'id' => 'claimant_blood',
                        'class' => 'form-control form-control-solid select_cit_blood_type',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group m-form__group required">
                    {{ Form::label('claimant_address', 'Address', ['class' => 'required fs-6 fw-bold']) }}
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
            <div class="col-sm-3">
                <div class="form-group m-form__group required">
                    {{ Form::label('claimant_educ', 'Educational Attainment', ['class' => 'required fs-6 fw-bold']) }}
                    {{ Form::select('claimant[cea_id]',
                        $educ,
                        isset($data->claimant) ? $data->claimant->cea_id : '', 
                        array(
                            'class' => 'form-control form-control-solid select_cea_id select3',
                            'data-contain' => 'select-contain-citizen',
                            'id'=>'gender'
                            ))
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group required">
                    {{ Form::label('wspa_occupation', 'Occupation', ['class' => 'required fs-6 fw-bold']) }}
                    {{ 
                        Form::text('claimant[cit_occupation]',
                        isset($data->claimant) ? $data->claimant->cit_occupation : '',
                        $attributes = array(
                        'id' => 'wspa_occupation',
                        'class' => 'form-control form-control-solid select_cit_occupation',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group required">
                    {{ Form::label('wspa_monthly_income', 'Monthly Income', ['class' => 'required fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wspa_monthly_income',
                        currency_format($data->wspa_monthly_income),
                        $attributes = array(
                        'id' => 'wspa_monthly_income',
                        'class' => 'form-control form-control-solid',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group required">
                    {{ Form::label('wspa_total_income', 'Total Monthly Family Income', ['class' => 'required fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wspa_total_income',
                        currency_format($data->wspa_total_income),
                        $attributes = array(
                        'id' => 'wspa_total_income',
                        'class' => 'form-control form-control-solid',
                        )) 
                    }}
                </div>
            </div>
            
        </div> 
        <div class="row" id="dependant-sec">
            <table class="table tbl_dependant">
                <thead>
                    <tr>
                        <th style="width: 20%;">Name</th>
                        <th style="width: 10%;">Relation</th>
                        <th style="width: 5%;">Age</th>
                        <th >Status</th>
                        <th style="width: 10%;">Educational Attainment</th>
                        <th style="width: 10%;">Occupation</th>
                        <th style="width: 10%;">Monthly Income</th>
                        <th style="width: 10%;">
                            <a href="#dependant-sec" class="btn btn-primary add-dependent" data-id="0" data-bs-toggle="tooltip" title="{{__('Add Dependant')}}">
                            <i class="ti-plus"></i>
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
                                <input id="dependent-edit-relation-{{$dependent->id}}" class="form-control" name="dependent[{{$dependent->id}}][relation]" type="text" value="{{$dependent->wsfc_relation}}" required>
                            </td>
                            <td class="select_age">{{$dependent->info->age()}}</td>
                            <td>
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
                            {{ 
                                Form::select('dependent['.$dependent->id.'][data][cea_id]',
                                $educ,
                                $dependent->info->cea_id, 
                                array(
                                    'class' => 'form-control form-control-solid select3 select_cea_id',
                                    'id'=>'dependent-educ-'.$dependent->id.'',
                                    'data-contain'=>'dependent-'.$dependent->id.'-contain'
                                    )) 
                            }}
                            </td>
                            <td>
                                <input id="dependent-edit-relation-{{$dependent->id}}" class="form-control select_cit_occupation" name="dependent[{{$dependent->id}}][data][cit_occupation]" type="text" value="{{$dependent->info->cit_occupation}}">
                            </td>
                            <td>
                                <input id="dependent-edit-relation-{{$dependent->id}}" class="form-control" name="dependent[{{$dependent->id}}][income]" type="text" value="{{currency_format($dependent->wsfc_monthly_income)}}" required>
                            </td>
                            <td>
                                @if($dependent->wsfc_is_active == 0)
                                    <a href="#dependant-sec" class="btn btn-sm btn-info remove-row" data-bs-toggle="tooltip" title="{{__('Activate Dependent')}}" data-remove="dependent" data-id="{{$dependent->id}}" data-active="1">
                                        <i class="ti-reload"></i>
                                    </a>
                                @else
                                    <a href="#dependant-sec" class="btn btn-sm btn-danger remove-row" data-bs-toggle="tooltip" title="{{__('Remove Dependent')}}" data-remove="dependent" data-id="{{$dependent->id}}" data-active="0">
                                        <i class="ti-trash"></i>
                                    </a>
                                @endif
                                <a href="#" data-size="lg" data-url="{{ url('/citizens/store') }}?field=cit_id" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" data-title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary  add-citizen-btn btn_open_second_modal">
                                    <i class="ti-plus"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-sm-12">
                {{ Form::label('wspa_classification', 'Classification/Circumstances of being a Solo Parents', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::textarea('wspa_classification', 
                    $data->wspa_classification, 
                    $attributes = array(
                    'id' => 'wspa_classification',
                    'class' => 'form-control form-control-solid'
                    )) 
                }}
            </div>
            <span class="validate-err"  id="err_wswa_remarks"></span>
        </div>
        <div class="row">
            <div class="col-sm-12">
                {{ Form::label('wspa_needs_problem', 'Need/Problems of Solo Parents', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::textarea('wspa_needs_problem', 
                    $data->wspa_needs_problem, 
                    $attributes = array(
                    'id' => 'wspa_needs_problem',
                    'class' => 'form-control form-control-solid'
                    )) 
                }}
            </div>
            <span class="validate-err"  id="err_wswa_remarks"></span>
        </div>
        <div class="row">
            <div class="col-sm-12">
                {{ Form::label('wspa_family_resources', 'Family Resources', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::textarea('wspa_family_resources', 
                    $data->wspa_family_resources, 
                    $attributes = array(
                    'id' => 'wspa_family_resources',
                    'class' => 'form-control form-control-solid'
                    )) 
                }}
            </div>
            <span class="validate-err"  id="err_wswa_remarks"></span>
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
                                    <a class="btn btn-primary add-require" data-id="0" data-bs-toggle="tooltip" title="{{__('Add Requirement')}}">
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
                                                    @if($requirement->fwsc_path)
                                                    <i class="ti-check text-white"></i>
                                                    @else
                                                    <i class="ti-cloud-up text-white"></i>
                                                    @endif
                                                </label>
                                                <input type="file" class="form-control required-hide" id="require-old-{{$loop->iteration}}" value="" name="require[{{$requirement->id}}][file]">
                                            </div>
                                                    @if($requirement->fwsc_path)
                                            <a class="btn btn-sm bg-primary small-button" href="{{url($requirement->fwsc_path)}}" download="{{$data->claimant->cit_last_name}}_{{$requirement->req_name}}" data-bs-toggle="tooltip" title="Download File"><i class="ti-download text-white"></i></a>
                                                    @endif
                                            <input type="hidden" name="require[{{$requirement->id}}][req_id]" value="{{$requirement->req_id}}" >
                                            <input type="hidden" name="require[{{$requirement->id}}][req_type]" value="{{$requirement->req_type}}" >

                                            @if($requirement->fwsc_is_active == 0)
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
                                    <tr class="row-{{$loop->iteration}} new-row">
                                        <td>{{$requirement}}</td>
                                        <td>
                                            <div class="file-upload bg-info btn btn-sm small-button" data-bs-toggle="tooltip" title="Upload File">
                                                <label for="require-old-{{$loop->iteration}}">
                                                    <i class="ti-cloud-up text-white"></i>
                                                </label>
                                                <input type="file" class="form-control required-hide" id="require-old-{{$loop->iteration}}" value="" name="require[{{$loop->iteration}}nr][file]">
                                            </div>
                                            <span>
                                                <a class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove require">
                                                    <i class="ti-trash text-white"></i>
                                                </a>
                                            </span>
                                            <input type="hidden" name="require[{{$loop->iteration}}nr][req_id]" value="{{$loop->iteration}}" >
                                            <input type="hidden" name="require[{{$loop->iteration}}nr][req_type]" value="0" >
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            
        <div class="modal-footer">
            @if($data->id)
                <a href="{{route('soloparent.print',['id' => $data->id])}}"  target="_blank" class="btn btn-info">
                    <i class="ti-printer text-white"></i>
                    {{__('Print')}}
                </a>
            @endif
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
            <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
        </div>
        

    {{Form::close()}}
</div>


</div>

<script src="{{ asset('js/partials/select-ajax.js?v='.filemtime(getcwd().'/js/partials/select-ajax.js').'') }}"></script>
<script src="{{ asset('js/partials/citizen-ajax.js?v='.filemtime(getcwd().'/js/partials/citizen-ajax.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/social-welfare-create.js?v='.filemtime(getcwd().'/js/SocialWelfare/social-welfare-create.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/add-soloparent.js?v='.filemtime(getcwd().'/js/SocialWelfare/add-soloparent.js').'') }}"></script>
<script src="{{ asset('js/ajax_validation.js?v='.filemtime(getcwd().'/js/ajax_validation.js').'') }}"></script>

<!-- html append note: parent is id -->
<!-- dependants -->
<table>
    <tbody class="hidden" id="addDependants">
        <tr class="citizen_group new-row" id="dependent-{id}-contain">
            <td class="select-contain" id="contain_dependent-{id}">
                <select class="form-control select_id get-citizen " data-placeholder="Search Citizen" data-url="citizens/getCitizens" id="dependent-{id}" name="dependent[{id}][cit_id]"></select>
            </td>
            <td>
                <input id="dependent-relation-{id}" class="form-control get_relation" name="dependent[{id}][relation]" type="text" value="" required>
            </td>
            <td class="select_age"></td>
            <td id="dependent-civil-{id}-contain">
            {{ 
                Form::select('dependent[{id}][data][ccs_id]',
                $civilstat,
                '', 
                array(
                    'class' => 'form-control form-control-solid dependant-select select_ccs_id',
                    'id'=>'dependent-civil-{id}',
                    'data-contain'=>'dependent-{id}-contain'
                    )) 
            }}
            </td>
            <td id="dependent-educ-{id}-contain">
                {{ Form::select('dependent[{id}][data][cea_id]',
                    $educ,
                    '', 
                    array(
                        'class' => 'form-control dependant-select form-control-solid select_cea_id',
                        'id'=>'dependent-educ-{id}',
                        'data-contain'=>'dependent-{id}-contain'
                        ))
                }}
            </td>
            <td>
                <input id="dependent-occupation-{id}" class="form-control select_cit_occupation" name="dependent[{id}][data][cit_occupation]" type="text" value="">
            </td>
            <td>
                <input id="dependent-income-{id}" class="form-control get_income" name="dependent[{id}][income]" type="text" value="" required>
            </td>
            <td>
                <a href="#" class="btn btn-sm btn-danger remove-row m-0" data-bs-toggle="tooltip" title="Remove Dependent">
                    <i class="ti-trash"></i>
                </a>
                
                <a href="#" data-size="lg" data-url="{{ url('/citizens/store') }}?field=dependent-{id}" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" data-title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary add-citizen-btn btn_open_second_modal">
                    <i class="ti-plus"></i>
                </a>
            </td>
        </tr>
    </tbody>
        
</table>

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