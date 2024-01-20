<div class="container-fluid card pt-4">
    {{ Form::open(array('url' => 'social-welfare/assistance/store','class'=>'formDtls create-form', 'files' => true)) }}
        {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
        {{ Form::hidden('wswa_is_active',$data->wswa_is_active, array('id' => 'wswa_is_active')) }}
        {{ Form::hidden('dependent_count',$data->dependent_count, array('id' => '_dependent_count')) }}
        {{ Form::hidden('file_count',$data->file_count, array('id' => '_file_count')) }}
        {{ Form::hidden('amount_limit',$data->amount_limit, array('id' => '_amount_limit')) }}
        {{ Form::hidden('socialcase[id]',isset($data->casestudy) ? $data->casestudy->id : '') }}
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
                                'data-value' =>isset($data->claimant->cit_fullname) ? $data->claimant->cit_fullname : '',
                                'data-value_id' =>$data->cit_id,
                                'class' => 'form-control ajax-select get-citizen select_id',
                                (isset($data->wswa_approved_by) && ($data->wswa_approved_by) ) ? 'readonly':'',
                            )) 
                        }}
                        <span class="validate-err"  id="err_cit_id"></span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group m-form__group">
                        {{ Form::label('claimant_address', 'Address', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text('claimant_address',
                            isset($data->claimant) ? $data->claimant->cit_full_address : '',
                            $attributes = array(
                                'id' => 'claimant_address',
                                'class' => 'form-control form-control-solid select_cit_full_address',
                                'disabled'=>'disabled'
                            )) 
                        }}
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group">
                        {{ Form::label('claimant_age', 'Age', ['class' => 'fs-6 fw-bold']) }}
                        {{ 
                            Form::text('claimant_age',
                            isset($data->claimant->cit_age) ? $data->claimant->cit_age : '',
                            $attributes = array(
                                'id' => 'claimant_age',
                                'class' => 'form-control form-control-solid select_age',
                                'disabled'=>'disabled'
                            )) 
                        }}
                    </div>
                </div>
            </div> 

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group m-form__group" id="assistance-type-contain">
                        {{ Form::label('wsat_id', 'Type of Assistance', ['class' => ' fs-6 fw-bold']) }}<span class="text-danger">*</span>
                        
                        {{ 
                            Form::select(
                                'wsat_id',
                                $assistanceType,
                                $data->wsat_id, 
                                array(
                                    'class' => 'form-control  select3',
                                    'data-value_id' =>$data->wsat_id,
                                    'data-contain' => 'assistance-type-contain',
                                    'data-value' => isset($data->assistanceType) ? $data->assistanceType->wsat_description : '',
                                    'id'=>'wsat_id',
                                    (isset($data->wswa_approved_by) && ($data->wswa_approved_by) ) ? 'readonly':'',
                                    )
                            ) 
                        }}
                        <span class="validate-err"  id="err_wsat_id"></span>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group m-form__group ">
                        {{ Form::label('wswa_amount', 'Amount of Financial Assistance', ['class' => 'fs-6 fw-bold']) }}<span class="text-danger">*</span>
                        {{ 
                            Form::text('wswa_amount', ($data->wswa_amount) ? number_format($data->wswa_amount,2) : '', 
                            $attributes = array(
                                'id' => 'wswa_amount',
                                'class' => 'form-control form-control-solid amount_money',
                                (isset($data->wswa_approved_by) && ($data->wswa_approved_by) ) ? 'readonly':'',
                            )) 
                        }}
                        <span class="validate-err"  id="err_wswa_amount"></span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group m-form__group ">
                        {{ Form::label('wswa_date_applied', 'Date Applied', ['class' => 'fs-6 fw-bold']) }}<span class="text-danger">*</span>
                        
                        {{ 
                            Form::date('wswa_date_applied', 
                            $data->wswa_date_applied, 
                            $attributes = array(
                                'id' => 'wswa_date_applied',
                                'class' => 'form-control form-control-solid',
                                (isset($data->wswa_approved_by) && ($data->wswa_approved_by) ) ? 'readonly':'',
                            )) 
                        }}
                        <span class="validate-err"  id="err_wswa_date_applied"></span>
                    </div>
                </div>
            </div> 

            <div class="border border-dark container-fluid py-3">
                <div class="row">
                    <div class="col-md-12">
                        <table class='table'>
                            @foreach($assistanceStatus as $chunk)
                                <tr>
                                    @foreach($chunk as $status)
                                        <td>
                                            <input type="checkbox" name="wsst_id[]" id="stat_type-{{$status->id}}" value="{{$status->id}}" {{ in_array($status->id,explode(',',$data->wsst_id)) ? 'checked': '' }} onclick="return {{(isset($data->wswa_approved_by) && ($data->wswa_approved_by) ) ? 'false':'true'}}">
                                            {{ Form::label('stat_type-'.$status->id, $status->wsst_description) }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                <div class="row citizen_group" id="head-sec">
                    <div class="col-sm-4" style="padding-left: 0;">
                        <div class="form-group m-form__group select-contain mb-0" id='contain_head_cit_id'>
                            {{ Form::label('head_cit_id', 'Family Head', ['class' => ' fs-6 fw-bold']) }}<span class="text-danger">*</span>
                            <a href="#head-sec" data-size="lg" data-url="{{ url('/citizens/store') }}?field=head_cit_id" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" data-title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary float-end add-citizen-btn btn_open_second_modal">
                                <i class="ti-plus"></i>
                            </a>
                            @if($data->id)
                            <a href="#head-sec" data-size="lg" data-url="{{ url('/citizens/store') }}?field=head_cit_id{{$data->head_cit_id ? '&id='.$data->head_cit_id : ''}}" data-bs-toggle="tooltip" title="{{__('Manage Citizen')}}" data-title="{{__('Manage Citizen')}}" class="btn btn-sm btn-secondary float-end add-citizen-btn btn_open_second_modal">
                                <i class="ti-menu"></i>
                            </a>
                            @endif
                            {{ 
                                Form::select('head_cit_id', 
                                    [],
                                    $data->head_cit_id, 
                                    $attributes = array(
                                    'id' => 'head_cit_id',
                                    'data-url' => 'citizens/getCitizens',
                                    'data-placeholder' => 'Search Family Head',
                                    'data-contain' => 'select-contain-famhead',
                                    'data-value' =>isset($data->head->cit_fullname) ? $data->head->cit_fullname : '',
                                    'data-value_id' =>$data->head_cit_id,
                                    'class' => 'form-control ajax-select get-citizen select_id',
                                    (isset($data->wswa_approved_by) && ($data->wswa_approved_by) ) ? 'readonly':'',
                                )) 
                            }}
                            <span class="validate-err"  id="err_head_cit_id"></span>
                        </div>
                    </div>
                    <div class="col-sm-8" style="padding-right: 0;">
                        <div class="form-group m-form__group  ">
                            {{ Form::label('family_head_address', 'Address', ['class' => ' fs-6 fw-bold']) }}
                            {{ 
                                Form::text('family_head_address',
                                isset($data->head) ? $data->head->full_add() : '',
                                $attributes = array(
                                    'id' => 'family_head_address',
                                    'class' => 'form-control form-control-solid select_cit_full_address',
                                    'disabled'=>'disabled'
                                )) 
                            }}
                        </div>
                    </div>
                </div> 
                <div class="row" id="dependant-sec">
                    <table class="table tbl_dependant">
                        <thead>
                            <tr>
                                <th>Dependent Name</th>
                                <th style="width: 10%;">Relation to Head</th>
                                <th style="width: 5%;">Age</th>
                                <th style="width: 10%;">
                                    <a href="#dependant-sec" class="btn btn-primary add-dependent" data-id="0" data-bs-toggle="tooltip" title="{{__('Add Dependant')}}">
                                        <i class="ti-plus"></i>
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data->dependents))
                                @foreach($data->dependents as $dependent)
                                    <tr class="citizen_group old-row" id="contain_dependent-edit-{{$dependent->id}}">
                                        <td class="select-contain" >
                                        {{ 
                                            Form::select('dependent['.$dependent->id.'][cit_id]', 
                                                [],
                                                $dependent->dependent->id, 
                                                $attributes = array(
                                                'id' => 'dependent-edit-'.$dependent->id,
                                                'data-url' => 'citizens/getCitizens',
                                                'data-placeholder' => 'Search Family Head',
                                                'data-contain' => 'select-contain-dependant-'.$dependent->id,
                                                'data-value' =>isset($dependent->dependent->cit_fullname) ? $dependent->dependent->cit_fullname : '',
                                                'data-value_id' =>$dependent->dependent->id,
                                                'class' => 'form-control ajax-select get-citizen select_id',
                                            )) 
                                        }}
                                        </td>
                                        <td>
                                            <input id="dependent-edit-relation-{{$dependent->id}}" class="form-control" name="dependent[{{$dependent->id}}][relation]" type="text" value="{{$dependent->wsd_relation}}" >
                                        </td>
                                        <td class="select_age">{{$dependent->dependent->cit_age}}</td>
                                        <td>
                                            @if($dependent->wsd_is_active == 0)
                                            <a href="#dependant-sec" class="btn btn-sm btn-info add-citizen-btn remove-row" data-bs-toggle="tooltip" title="{{__('Activate Dependent')}}" data-remove="dependent" data-id="{{$dependent->id}}" data-active="1">
                                                <i class="ti-reload"></i>
                                            </a>
                                            @else
                                            <a href="#dependant-sec" class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="{{__('Remove Dependent')}}" data-remove="dependent" data-id="{{$dependent->id}}" data-active="0">
                                                <i class="ti-trash"></i>
                                            </a>
                                            @endif
                                            
                                            <a href="#dependant-sec" data-size="lg" data-url="{{ url('/citizens/store') }}?field=dependent-edit-{{$dependent->id}}" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary add-citizen-btn btn_open_second_modal">
                                                <i class="ti-plus"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div> 

            <div  class="accordion accordion-flush {{($data->wswa_amount >= $data->amount_limit) ? '' : 'hidden'}}" id="social-case-sec">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-social-case">
                        <button class="accordion-button collapsed btn-primary" type="button">
                            <h6 class="sub-title accordiantitle">{{__("Social Case Study")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-social-case" class="accordion-collapse collapse show">
                        <div class="form-group m-form__group  ">
                            {{ Form::label('wswsc_health_status', 'Health Status', ['class' => ' fs-6 fw-bold']) }}
                            {{ 
                                Form::text('socialcase[wswsc_health_status]',
                                isset($data->casestudy) ? $data->casestudy->wswsc_health_status : '',
                                $attributes = array(
                                    'id' => 'wswsc_health_status',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                        </div>
                        {{ Form::label('family_composition', 'Family Composition', ['class' => ' fs-6 fw-bold']) }}
                        <table class="table" id="family-social-case">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th style="width: 5%;">Age</th>
                                    <th style="width: 10%;">Gender</th>
                                    <th style="width: 10%;">Civil Status</th>
                                    <th style="width: 10%;">Relation to Client</th>
                                    <th style="width: 10%;">Educational Attainment</th>
                                    <th style="width: 10%;">Occupation / Income</th>
                                    <th style="width: 10%;">Health Status</th>
                                    <th style="width: 10%;">
                                        <a class="btn btn-primary add-require" data-id="0" data-bs-toggle="tooltip" title="{{__('Add Requirement')}}">
                                            <i class="ti-plus"></i>
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="sc-reqirements-contain">
                                @if(isset($data->casestudy->family))
                                @foreach($data->casestudy->family as $family)
                                <tr class="citizen_group" id="sc-dependent-{{$family->id}}-contain">
                                    <td class="select-contain" id="contain_dependent-sc-{{$family->id}}">
                                        {{ 
                                            Form::select('socialcase[family]['.$family->id.'][cit_id]', 
                                                [],
                                                $family->info->id, 
                                                $attributes = array(
                                                'id' => 'dependent-sc-'.$family->id,
                                                'data-url' => 'citizens/getCitizens',
                                                'data-placeholder' => 'Search Citizen',
                                                'data-value' =>isset($family->info->cit_fullname) ? $family->info->cit_fullname : '',
                                                'data-value_id' =>$family->info->id,
                                                'class' => 'form-control ajax-select get-citizen select_id',
                                            )) 
                                        }}
                                    </td>
                                    <td class="select_age">{{$family->info->age()}}</td>
                                    <td>
                                        {{ 
                                            Form::select('socialcase[family]['.$family->id.'][data][cit_gender]',
                                            array('0'=>'Male','1'=>'Female'),
                                            $family->info->cit_gender, 
                                            array(
                                                'class' => 'form-control form-control-solid select3 select_cit_gender',
                                                'id'=>'dependent-gender-'.$family->id.'',
                                                'data-contain'=>'sc-dependent-'.$family->id.'-contain'
                                                )) 
                                        }}
                                    </td>
                                    <td>
                                        {{ 
                                            Form::select('socialcase[family]['.$family->id.'][data][ccs_id]',
                                            $civilstat,
                                            $family->info->ccs_id, 
                                            array(
                                                'class' => 'form-control form-control-solid select3 select_ccs_id',
                                                'id'=>'dependent-civil-'.$family->id.'',
                                                'data-contain'=>'sc-dependent-'.$family->id.'-contain'
                                                )) 
                                        }}
                                    </td>
                                    <td>
                                        <input id="dependent-sc-relation-{{$family->id}}" class="form-control" name="socialcase[family][{{$family->id}}][relation]" type="text" value="{{$family->wswscd_relation}}" >
                                    </td>
                                    <td>
                                        {{ Form::select('socialcase[family]['.$family->id.'][data][cea_id]',
                                            $educ,
                                            $family->info->cea_id, 
                                            array(
                                                'class' => 'form-control select3 form-control-solid select_cea_id',
                                                'id'=>'dependent-educ-'.$family->id.'',
                                                'data-contain'=>'sc-dependent-'.$family->id.'-contain'
                                                ))
                                        }}
                                    </td>
                                    <td>
                                        <input id="dependent-sc-occupation-{{$family->id}}" class="form-control select_cit_occupation" name="socialcase[family][{{$family->id}}][data][cit_occupation]" type="text" value="{{$family->info->cit_occupation}}" >
                                    </td>
                                    <td>
                                        <input id="dependent-sc-health-{{$family->id}}" class="form-control" name="socialcase[family][{{$family->id}}][health]" type="text" value="{{$family->wswscd_health_status}}" >
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-danger remove-row m-0" data-bs-toggle="tooltip" title="Remove Dependent">
                                            <i class="ti-trash"></i>
                                        </a>
                                        
                                        <a href="#" data-size="lg" data-url="{{ url('/citizens/store') }}?field=dependent-sc-{{$family->id}}" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" data-title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary add-citizen-btn btn_open_second_modal">
                                            <i class="ti-plus"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        </br>
                        <div class="form-group m-form__group  ">
                            {{ Form::label('wswsc_problem_presented', 'Problems Presented', ['class' => ' fs-6 fw-bold']) }}
                            {{ 
                                Form::textarea('socialcase[wswsc_problem_presented]',
                                isset($data->casestudy) ? $data->casestudy->wswsc_problem_presented : '',
                                $attributes = array(
                                    'id' => 'wswsc_problem_presented',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                        </div>

                        <div class="form-group m-form__group  ">
                            {{ Form::label('wswsc_family_background', 'Family Background', ['class' => ' fs-6 fw-bold']) }}
                            {{ 
                                Form::textarea('socialcase[wswsc_family_background]',
                                isset($data->casestudy) ? $data->casestudy->wswsc_family_background : '',
                                $attributes = array(
                                    'id' => 'wswsc_family_background',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                        </div>

                        <div class="form-group m-form__group  ">
                            {{ Form::label('wswsc_diagnostic_impression', 'Diagnostic Impression', ['class' => ' fs-6 fw-bold']) }}
                            {{ 
                                Form::textarea('socialcase[wswsc_diagnostic_impression]',
                                isset($data->casestudy) ? $data->casestudy->wswsc_diagnostic_impression : '',
                                $attributes = array(
                                    'id' => 'wswsc_diagnostic_impression',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                        </div>

                        {{ Form::label('treatment_plan', 'Treatment Plan', ['class' => ' fs-6 fw-bold']) }}
                        <table class="table" id="treatment-social-case">
                            <thead>
                                <tr>
                                    <th>Objective</th>
                                    <th>Activities</th>
                                    <th>Strategies</th>
                                    <th>Timeframe</th>
                                    <th style="width: 10%;">
                                        <a class="btn btn-primary add-require" data-id="0" data-bs-toggle="tooltip" title="{{__('Add Requirement')}}">
                                            <i class="ti-plus"></i>
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="sc-treatment-contain">
                                @if(isset($data->casestudy->treatment))
                                @foreach($data->casestudy->treatment as $treatment)
                                <tr id="sc-treatment-{{$treatment->id}}-contain">
                                    <td>
                                        <input id="dependent-sc-objective-{{$treatment->id}}" class="form-control" name="socialcase[treatment][{{$treatment->id}}][wswsc_treatment_plan_objectives]" type="text" value="{{$treatment->wswsc_treatment_plan_objectives}}">
                                    </td>
                                    <td>
                                        <input id="dependent-sc-activities-{{$treatment->id}}" class="form-control" name="socialcase[treatment][{{$treatment->id}}][wswsc_treatment_plan_activities]" type="text" value="{{$treatment->wswsc_treatment_plan_activities}}">
                                    </td>
                                    <td>
                                        <input id="dependent-sc-stratigies-{{$treatment->id}}" class="form-control" name="socialcase[treatment][{{$treatment->id}}][wswsc_treatment_plan_strategies]" type="text" value="{{$treatment->wswsc_treatment_plan_strategies}}">
                                    </td>
                                    <td>
                                        {{
                                            Form::date(
                                                'socialcase[treatment]['.$treatment->id.'][wswsc_treatment_plan_timeframe]',
                                                $treatment->wswsc_treatment_plan_timeframe,
                                                array(
                                                    'class' => 'form-control',
                                                )
                                            )
                                        }}
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-danger remove-row m-0" data-bs-toggle="tooltip" title="Remove Dependent">
                                            <i class="ti-trash"></i>
                                        </a>
                                        
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        </br>
                        <div class="form-group m-form__group  ">
                            {{ Form::label('wswsc_reco', 'Recommendations', ['class' => ' fs-6 fw-bold']) }}
                            {{ 
                                Form::textarea('socialcase[wswsc_reco]',
                                isset($data->casestudy) ? $data->casestudy->wswsc_reco : '',
                                $attributes = array(
                                    'id' => 'wswsc_reco',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                        </div>
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
                                    <th style="width: 10%;">File</th>
                                    <th style="width: 10%;">
                                        <a href="#req-sec" class="btn btn-primary add-require" data-id="0" data-bs-toggle="tooltip" title="{{__('Add Requirement')}}">
                                            <i class="ti-plus"></i>
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="reqirements-contain">
                                @if(isset($data->requirements))
                                    @foreach($data->requirements as $requirement)
                                        <tr class="old-row">
                                            <td>{{$requirement->req_name}}</td>
                                            <td>
                                                <div class="file-upload bg-info btn btn-sm small-button" data-bs-toggle="tooltip" title="Upload File">
                                                    <label for="require-old-{{$requirement->id}}">
                                                    @if($requirement->fwa_path)
                                                    <i class="ti-check text-white"></i>
                                                    @else
                                                    <i class="ti-cloud-up text-white"></i>
                                                    @endif
                                                    </label>
                                                    <input type="file" class="form-control required-hide" id="require-old-{{$requirement->id}}" value="C:\xampp\htdocs\palayan\public\uploads\socialwelfare\1682946794.jpg" name="require[{{$requirement->id}}][file]">
                                                </div>
                                                @if($requirement->fwa_path)
                                                <a class="btn btn-sm bg-primary small-button" href="{{url($requirement->fwa_path)}}" download="{{$requirement->requirement->wsr_description}}_{{$data->claimant->cit_last_name}}" data-bs-toggle="tooltip" title="Download File"><i class="ti-download text-white"></i></a>
                                                @endif
                                                
                                                <input type="hidden" name="require[{{$requirement->id}}][req_id]" value="{{$requirement->requirement->id}}" >
                                                <input type="hidden" name="require[{{$requirement->id}}][req_type]" value="{{$requirement->wsr_type}}" >
                                                
                                                <input type="hidden" name="require[{{$requirement->id}}][requirement]" value="{{$requirement->id}}"></td>
                                            <td>
                                                @if($requirement->fwa_is_active == 0)
                                                <a href="#req-sec" class="btn btn-sm btn-info add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove Requirment" data-remove="requirement" data-id="{{$requirement->id}}" data-active='1'><i class="ti-reload"></i></a>
                                                @else
                                                <a href="#req-sec" class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove Requirment" data-remove="requirement" data-id="{{$requirement->id}}" data-active='0'><i class="ti-trash"></i></a>
                                                @endif
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
                <div class="col-sm-12">
                    {{ Form::label('wswa_remarks', 'Remarks', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::textarea('wswa_remarks', 
                        $data->wswa_remarks, 
                        $attributes = array(
                            'id' => 'wswa_remarks',
                            'class' => 'form-control form-control-solid'
                        )) 
                    }}
                </div>
                <span class="validate-err"  id="err_wswa_remarks"></span>
            </div>
            <div class="row">
                <div class="col-md-7">
                </div>
                <div class="col-md-5 select-contain" id='contain_wswa_social_worker'>
                    {{ Form::label('wswa_social_worker', 'Prepared By (Social Worker)', ['class' => 'fs-6 fw-bold']) }}<span class="text-danger">*</span>
                    {{ 
                        Form::select('wswa_social_worker', 
                            [],
                            $data->wswa_social_worker, 
                            $attributes = array(
                            'id' => 'wswa_social_worker',
                            'data-url' => 'citizens/selectEmployee',
                            'data-placeholder' => 'Search Social Worker',
                            'data-value' =>isset($data->wswa_social_worker_name) ? $data->wswa_social_worker_name : '',
                            'data-value_id' =>$data->wswa_social_worker,
                            'class' => 'form-control ajax-select',
                            (isset($data->wswa_approved_by) && ($data->wswa_approved_by) ) ? 'readonly':'',
                        )) 
                    }}
                </div>
                <span class="validate-err"  id="err_wswa_social_worker"></span>
            </div>

            <div class="modal-footer">
                @if($data->id)
                <a data-url="{{route('assistance.requestLetter', ['id' => $data->id])}}" data-bs-toggle="tooltip" class="btn_open_second_modal" title="{{__('Request Letter')}}" data-title="{{__('Request Letter')}}">
                    <input  type="button" value="{{__('Request Letter')}}" class="btn btn-info">
                </a>
                
                <div class="col-md-2" id="select-print-contain">
                    {{ 
                        Form::select('select_print', 
                            $prints,
                            '', 
                            $attributes = array(
                            'id' => 'select_print',
                            'data-contain' => 'select-print-contain',
                            'class' => 'form-control select3',
                        )) 
                    }}
                </div>
                <button type="button" value="{{__('Print')}}" class="btn btn-primary digital-sign-btn" id="print_assistance">
                 <i class="ti-printer text-white"></i> Print </button>
                @if($approveBtn['sequence'])
                    
                    <button class="btn btn-primary" name="button" type="submit" value="submit" >
                        {{__('Approve')}}
                    </button>
                    <input type="hidden" name="approve_sequence" value="{{$approveBtn['sequence']}}">
                @endif

                <!-- <input type="button" id="approveBtn" value="{{__('Approve')}}" class="btn  btn-light" {{($data->wswa_approved_by)?'disabled':''}}> -->
                @endif
                <button class="btn btn-primary " type="submit" value="save">
                    <i class="fa fa-save icon" ></i>
                    {{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}
                </button>
                <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal" >
            </div>
    {{Form::close()}}
</div>


</div>

<script src="{{ asset('js/partials/select-ajax.js?v='.filemtime(getcwd().'/js/partials/select-ajax.js').'') }}"></script>
<script src="{{ asset('js/partials/citizen-ajax.js?v='.filemtime(getcwd().'/js/partials/citizen-ajax.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/add-assistance.js?v='.filemtime(getcwd().'/js/SocialWelfare/add-assistance.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/social-welfare-create.js?v='.filemtime(getcwd().'/js/SocialWelfare/social-welfare-create.js').'') }}"></script>
<script src="{{ asset('/js/partials/forms_validation.js?v='.filemtime(getcwd().'/js/partials/forms_validation.js').'') }}"></script>
<!-- <script src="{{ asset('js/ajax_validation.js?v='.filemtime(getcwd().'/js/ajax_validation.js').'') }}"></script> -->

<!-- html append note: parent is id -->
<!-- requirements -->
<table>
    <tbody class="hidden" id="addRequirements">
        <tr class="new-row select_req" id="require-changeid-contain">

            <td class="select-contain" id="contain_require-newrow-changeidn">
                <select class="form-control" data-placeholder="Search Requirement" data-url="social-welfare/assistance/getRequireList" id="require-newrow-changeidn" name="require[changeida][req_id]"></select>
            </td>

            <td>
                <div class="file-upload bg-info btn btn-sm small-button" data-bs-toggle="tooltip" title="Upload File" >
                    <label for="require-changeid">
                        <i class="ti-cloud-up text-white"></i>
                    </label>
                    <input type="file" class="form-control required-hide" id="require-changeid" name="require[changeida][file]">
                    <input type="hidden" class="select_req_id" name="require[changeida][req_id]" type="text" value="">
                    <input type="hidden" name="require[changeida][req_type]" type="text" value="1">
                </div>
            </td>
            <td>
                <a href="#req-sec" class="btn btn-sm btn-danger add-citizen-btn remove-row" data-bs-toggle="tooltip" title="Remove Requirement">
                    <i class="ti-trash"></i>
                </a>
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
                <input id="dependent-relation-changeid" class="form-control" name="dependent[changeid][relation]" type="text" value="" required >
            </td>
            <td class="select_age"></td>
            <td>
                <a href="#" class="btn btn-sm btn-danger remove-row m-0" data-bs-toggle="tooltip" title="Remove Dependent">
                    <i class="ti-trash"></i>
                </a>
                
                <a href="#" data-size="lg" data-url="{{ url('/citizens/store') }}?field=dependent-changeid" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" data-title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary add-citizen-btn btn_open_second_modal">
                    <i class="ti-plus"></i>
                </a>
            </td>
        </tr>
    </tbody>
        
</table>

<!-- Social Case family composition -->
<table>
    <tbody class="hidden" id="addSCDependants">
        <tr class="citizen_group new-row" id="sc-dependent-changeid-contain">
            <td class="select-contain" id="contain_dependent-sc-changeid">
                <select class="form-control select_id get-citizen" data-placeholder="Search Citizen" data-url="citizens/getCitizens" id="dependent-sc-changeid" name="socialcase[family][changeid][cit_id]"></select>
            </td>
            <td class="select_age"></td>
            <td id="sc-dependent-gender-changeid-contain">
                {{ 
                    Form::select('socialcase[family][changeid][data][cit_gender]',
                    array('0'=>'Male','1'=>'Female'),
                    '', 
                    array(
                        'class' => 'form-control form-control-solid dependant-select select_cit_gender',
                        'id'=>'dependent-gender-changeid',
                        'data-contain'=>'sc-dependent-gender-changeid-contain'
                        )) 
                }}
            </td>
            <td>
                {{ 
                    Form::select('socialcase[family][changeid][data][ccs_id]',
                    $civilstat,
                    '', 
                    array(
                        'class' => 'form-control form-control-solid dependant-select select_ccs_id',
                        'id'=>'dependent-civil-changeid',
                        'data-contain'=>'sc-dependent-changeid-contain'
                        )) 
                }}
            </td>
            <td>
                <input id="dependent-sc-relation-changeid" class="form-control" name="socialcase[family][changeid][relation]" type="text" value="" required>
            </td>
            <td>
                {{ Form::select('socialcase[family][changeid][data][cea_id]',
                    $educ,
                    '', 
                    array(
                        'class' => 'form-control dependant-select form-control-solid select_cea_id',
                        'id'=>'dependent-educ-changeid',
                        'data-contain'=>'sc-dependent-changeid-contain'
                        ))
                }}
            </td>
            <td>
                <input id="dependent-sc-occupation-changeid" class="form-control select_cit_occupation" name="socialcase[family][changeid][data][cit_occupation]" type="text" value="" >
            </td>
            <td>
                <input id="dependent-sc-health-changeid" class="form-control" name="socialcase[family][changeid][health]" type="text" value="" >
            </td>
            <td>
                <a href="#" class="btn btn-sm btn-danger remove-row m-0" data-bs-toggle="tooltip" title="Remove Dependent">
                    <i class="ti-trash"></i>
                </a>
                
                <a href="#" data-size="lg" data-url="{{ url('/citizens/store') }}?field=dependent-sc-changeid" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" data-title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary add-citizen-btn btn_open_second_modal">
                    <i class="ti-plus"></i>
                </a>
            </td>
        </tr>
    </tbody>
        
</table>

<!-- Social Case Treatment plan -->
<table>
    <tbody class="hidden" id="addSCTreatment">
        <tr class="new-row" id="sc-treatment-changeid-contain">
            <td>
                <input id="dependent-sc-objective-changeid" class="form-control" name="socialcase[treatment][changeid][wswsc_treatment_plan_objectives]" type="text" value="">
            </td>
            <td>
                <input id="dependent-sc-activities-changeid" class="form-control" name="socialcase[treatment][changeid][wswsc_treatment_plan_activities]" type="text" value="">
            </td>
            <td>
                <input id="dependent-sc-stratigies-changeid" class="form-control" name="socialcase[treatment][changeid][wswsc_treatment_plan_strategies]" type="text" value="">
            </td>
            <td>
                {{
                    Form::date(
                        'socialcase[treatment][changeid][wswsc_treatment_plan_timeframe]',
                        '',
                        array(
                            'class' => 'form-control',
                        )
                    )
                }}
            </td>
            <td>
                <a href="#" class="btn btn-sm btn-danger remove-row m-0" data-bs-toggle="tooltip" title="Remove Dependent">
                    <i class="ti-trash"></i>
                </a>
            </td>
        </tr>
    </tbody>
        
</table>
<script type="text/javascript">
    $(document).ready(function(){
    $('body').on('click', '.digital-sign-btn', function (e) {
        console.log("Button clicked");
        btn = $(this);
        console.log("Button href:", btn.attr('href'));
        e.preventDefault();
        window.open(DIR + 'digital-sign?url=' + btn.attr('href'), '_blank');
        return false;
    });
});
</script>