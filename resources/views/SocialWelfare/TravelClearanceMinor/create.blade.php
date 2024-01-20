<div class="container-fluid card pt-4">
    {{ Form::open(array('url' => 'social-welfare/travel-clearance-minor/store','class'=>'formDtls create-form', 'files' => true)) }}
        {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
        <div class="row">
            <!-- row 1 -->
            <div class="col-sm-6">
                <div class="form-group m-form__group ">
                    {{ 
                        Form::radio('wtcm_status', 
                        1, 
                        false,
                        $attributes = array(
                        'id' => 'wtcm_status_alone',
                        'class' => 'form-check-input',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':'',
                        $data->wtcm_status === 1? 'checked':''
                        )) 
                    }}
                    {{ Form::label('wtcm_status_alone', 'Travelling Alone', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::radio('wtcm_status', 
                        2,
                        false, 
                        $attributes = array(
                        'id' => 'wtcm_status_with',
                        'class' => 'form-check-input',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':'',
                        $data->wtcm_status === 2? 'checked':''
                        )) 
                    }}
                    {{ Form::label('wtcm_status_with', 'With Companion', ['class' => 'fs-6 fw-bold']) }}
                </div>
                <span class="validate-err"  id="err_wtcm_status"></span>
            </div>
            <div class="col-sm-6">
                <div class="form-group m-form__group ">
                    {{ 
                        Form::radio('wtcm_validity', 
                        1, 
                        false,
                        $attributes = array(
                        'id' => 'wtcm_validity_1yr',
                        'class' => 'form-check-input',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':'',
                        $data->wtcm_validity === 1? 'checked':''
                        )) 
                    }}
                    {{ Form::label('wtcm_validity_1yr', '1 year validity', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::radio('wtcm_validity', 
                        2,
                        false, 
                        $attributes = array(
                        'id' => 'wtcm_validity_2yr',
                        'class' => 'form-check-input',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':'',
                        $data->wtcm_validity === 2? 'checked':''
                        )) 
                    }}
                    {{ Form::label('wtcm_validity_2yr', '2 years validity', ['class' => 'fs-6 fw-bold']) }}
                </div>
                <span class="validate-err"  id="err_wtcm_validity"></span>
            </div>

            <!-- row 2 minors table -->
            <div  class="accordion accordion-flush" >
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-minors">
                        <button class="accordion-button collapsed btn-primary" type="button">
                            <h6 class="sub-title accordiantitle">{{__("Minor's Profile")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-minors" class="accordion-collapse collapse show">
                        <table class="table" id="minors-list">
                            <thead>
                                <tr>
                                    <th >Name</th>
                                    <th style="width: 5%;">Age</th>
                                    <th style="width: 10%;">Gender</th>
                                    <th style="width: 10%;">Civil Status</th>
                                    <th style="width: 10%;">Date of Birth</th>
                                    <th style="width: 10%;">Place of Birth</th>
                                    <th style="width: 10%;">
                                    <a class="btn btn-primary add-require" data-id="0" data-bs-toggle="tooltip" title="{{__('Add Requirement')}}">
                                        <i class="ti-plus"></i>
                                    </a>
                                </th>
                                </tr>
                            </thead>
                            <tbody id="minors-contain">
                                @if(isset($data->minors))
                                    @foreach($data->minors as $minor)
                                        <tr class="citizen_group new-row" id="minors-{{$minor->id}}-contain">
                                            <td class="select-contain" id="contain-select-relative-{{$minor->id}}">
                                            <select 
                                                class="form-control select_id ajax-select get-citizen" 
                                                data-placeholder="Search Citizen" 
                                                data-url="citizens/getCitizens" 
                                                id="minors-{{$minor->id}}" 
                                                data-contain="contain-select-relative-{{$minor->id}}" 
                                                name="minors[{{$minor->id}}][cit_id]"
                                                data-value="{{$minor->info->cit_fullname}}"
                                                data-value_id="{{$minor->info->id}}"
                                                ></select>
                                            </td>
                                            <td class="select_age">{{$minor->info->age()}}</td>
                                            <td id="minors-gender-{{$minor->id}}-contain">
                                            {{ 
                                                Form::select('minors['.$minor->id.'][data][cit_gender]',
                                                array('0'=>'Male','1'=>'Female'),
                                                $minor->info->cit_gender, 
                                                array(
                                                    'class' => 'form-control form-control-solid dependant-select select_cit_gender select3',
                                                    'id'=>'minors-civil-'.$minor->id.'',
                                                    'data-contain'=>'minors-'.$minor->id.'-contain'
                                                    )) 
                                            }}
                                            </td>
                                            <td id="minors-civil-{{$minor->id}}-contain">
                                            {{ 
                                                Form::select('minors['.$minor->id.'][data][ccs_id]',
                                                $civilstat,
                                                $minor->info->ccs_id, 
                                                array(
                                                    'class' => 'form-control form-control-solid dependant-select select_ccs_id select3',
                                                    'id'=>'minors-civil-'.$minor->id.'',
                                                    'data-contain'=>'minors-'.$minor->id.'-contain'
                                                    )) 
                                            }}
                                            </td>
                                            <td>
                                                <input class="form-control select_cit_date_of_birth" name="minors[{{$minor->id}}][data][cit_date_of_birth]" type="date" value="{{$minor->info->cit_date_of_birth}}">
                                            </td>
                                            <td>
                                                <input class="form-control selec_cit_place_of_birth" name="minors[{{$minor->id}}][data][cit_place_of_birth]" type="text" value="{{$minor->info->cit_place_of_birth}}">
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-danger remove-row m-0" data-bs-toggle="tooltip" title="Remove Minor">
                                                    <i class="ti-trash"></i>
                                                </a>
                                                
                                                <a href="#" data-size="lg" data-url="{{ url('/citizens/store') }}?field=minors-{{$minor->id}}" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" data-title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary add-citizen-btn btn_open_second_modal">
                                                    <i class="ti-plus"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    <span class="validate-err"  id="err_minors"></span>
                    </div>
                </div>
            </div>
            <!-- row 3 -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group m-form__group ">
                        {{ Form::label('wtcm_minor_address', "Minors' Address", ['class' => ' fs-6 fw-bold']) }}
                        {{ 
                            Form::text('wtcm_minor_address',
                            $data->wtcm_minor_address,
                            $attributes = array(
                            'id' => 'wtcm_minor_address',
                            'class' => 'form-control form-control-solid ',
                            )) 
                        }}
                        <span class="validate-err"  id="err_wtcm_minor_address"></span>
                    </div>
                </div>
            </div>
            <!-- row 4 -->
            <div class="row mb-2">
                <div class="col-sm-8">
                    <div class="form-group m-form__group ">
                        <span>If issued with Certificate of Finality of Adoption or under Legal Guardianship, please indicate special proceeding No.</span>
                    </div>
                </div>
                <div class="col-sm-4">
                        {{ 
                            Form::text('wtcm_adoption_no',
                            $data->wtcm_adoption_no,
                            $attributes = array(
                            'id' => 'wtcm_adoption_no',
                            'class' => 'form-control form-control-solid ',
                            )) 
                        }}
                </div>
            </div>
            <!-- row 5 -->
            <div class="row">
                <div class="col-sm-5">
                    <div class="form-group m-form__group ">
                        <span>If under Foster Care Placement, please indicate Foster Care License.</span>
                    </div>
                </div>
                <div class="col-sm-2">
                        {{ 
                            Form::text('wtcm_foster_liscense',
                            $data->wtcm_foster_liscense,
                            $attributes = array(
                            'id' => 'wtcm_foster_liscense',
                            'class' => 'form-control form-control-solid ',
                            )) 
                        }}
                </div>
                <div class="col-sm-2">
                    <div class="form-group m-form__group ">
                        <span>and validity period</span>
                    </div>
                </div>
                <div class="col-sm-3">
                        {{ 
                            Form::date('wtcm_foster_validity',
                            $data->wtcm_foster_validity,
                            $attributes = array(
                            'id' => 'wtcm_foster_validity',
                            'class' => 'form-control form-control-solid ',
                            )) 
                        }}
                </div>
            </div>
        
        <!-- father -->
        <div class="row citizen_group">
            <h3>PARENTS</h3>
            <div class="col-md-5">
                <div class="form-group m-form__group select-contain" id='select-contain-father'>
                    {{ Form::label('wtcm_father_cit_id',"Father's Name", ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::select('wtcm_father_cit_id', 
                            [],
                            $data->wtcm_father_cit_id, 
                            $attributes = array(
                            'id' => 'wtcm_father_cit_id',
                            'data-url' => 'citizens/getCitizens',
                            'data-placeholder' => 'Search Citizen',
                            'data-contain' => 'select-contain-father',
                            'data-value' =>isset($data->father->cit_fullname) ? $data->father->cit_fullname : '',
                            'data-value_id' =>$data->wtcm_father_cit_id,
                            'class' => 'form-control ajax-select get-citizen select_id',
                            (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':''
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_father_date_of_birth', 'Age', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wtcm_father_date_of_birth',
                        isset($data->father) ? $data->father->age() : '',
                        $attributes = array(
                        'id' => 'wtcm_father_date_of_birth',
                        'class' => 'form-control form-control-solid select_age',
                        'disabled'
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_father_occupation', 'Occupation', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('father[cit_occupation]',
                        isset($data->father) ? $data->father->cit_occupation : '',
                        $attributes = array(
                        'id' => 'wtcm_father_occupation',
                        'class' => 'form-control form-control-solid select_cit_occupation',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_father_contact_no', 'Contact No', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('father[cit_mobile_no]',
                        isset($data->father) ? $data->father->cit_mobile_no : '',
                        $attributes = array(
                        'id' => 'wtcm_father_contact_no',
                        'class' => 'form-control form-control-solid select_cit_mobile_no',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_father_id_num', 'ID Number', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wtcm_father_id_num',
                        $data->wtcm_father_id_num,
                        $attributes = array(
                        'id' => 'wtcm_father_id_num',
                        'class' => 'form-control form-control-solid ',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                        )) 
                    }}
                </div>
            </div>
            <!--  -->
            <div class="col-md-12">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_father_address', 'Address', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wtcm_father_address',
                        isset($data->father) ? $data->father->cit_full_address : '',
                        $attributes = array(
                            'id' => 'wtcm_father_address',
                            'class' => 'form-control form-control-solid select_cit_full_address',
                            'disabled'
                        )) 
                    }}
                </div>
            </div>
        </div>
        <!-- mother -->
        <div class="row citizen_group">
            <div class="col-md-5">
                <div class="form-group m-form__group select-contain" id='select-contain-mother'>
                    {{ Form::label('wtcm_mother_cit_id', "Mother's Name", ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::select('wtcm_mother_cit_id', 
                            [],
                            $data->wtcm_mother_cit_id, 
                            $attributes = array(
                            'id' => 'wtcm_mother_cit_id',
                            'data-url' => 'citizens/getCitizens',
                            'data-placeholder' => 'Search Citizen',
                            'data-contain' => 'select-contain-mother',
                            'data-value' =>isset($data->mother->cit_fullname) ? $data->mother->cit_fullname : '',
                            'data-value_id' =>$data->wtcm_mother_cit_id,
                            'class' => 'form-control ajax-select get-citizen select_id',
                            (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':''
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_mother_date_of_birth', 'Age', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wtcm_mother_date_of_birth',
                        isset($data->mother) ? $data->mother->age() : '',
                        $attributes = array(
                        'id' => 'wtcm_mother_date_of_birth',
                        'class' => 'form-control form-control-solid select_age',
                        'disabled'
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_mother_occupation', 'Occupation', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('mother[cit_occupation]',
                        isset($data->mother) ? $data->mother->cit_occupation : '',
                        $attributes = array(
                        'id' => 'wtcm_mother_occupation',
                        'class' => 'form-control form-control-solid select_cit_occupation',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_mother_contact_no', 'Contact No', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('mother[cit_mobile_no]',
                        isset($data->mother) ? $data->mother->cit_mobile_no : '',
                        $attributes = array(
                        'id' => 'wtcm_mother_contact_no',
                        'class' => 'form-control form-control-solid select_cit_mobile_no',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_mother_id_num', 'ID Number', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wtcm_mother_id_num',
                        $data->wtcm_mother_id_num,
                        $attributes = array(
                        'id' => 'wtcm_mother_id_num',
                        'class' => 'form-control form-control-solid ',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_mother_address', 'Address', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wtcm_mother_address',
                        isset($data->mother) ? $data->mother->cit_full_address : '',
                        $attributes = array(
                        'id' => 'wtcm_mother_address',
                        'class' => 'form-control form-control-solid select_cit_full_address',
                        'readonly'
                        )) 
                    }}
                </div>
            </div>
        </div>

        <!-- companion -->
        <div class="row citizen_group">
            <h3>TRAVELING COMPANION</h3>
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
                            'data-contain' => 'select-contain-companion',
                            'data-value' =>isset($data->companion->cit_fullname) ? $data->companion->cit_fullname : '',
                            'data-value_id' =>$data->wtcm_companion_name,
                            'class' => 'form-control ajax-select get-citizen select_id',
                            (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':''
                        )) 
                    }}
                    <span class="validate-err"  id="err_wtcm_companion_name"></span>
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
                        'class' => 'form-control form-control-solid ',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_companion_contact_no', 'Contact No', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('companion[cit_mobile_no]',
                        isset($data->companion) ? $data->companion->cit_mobile_no : '',
                        $attributes = array(
                        'id' => 'wtcm_companion_contact_no',
                        'class' => 'form-control form-control-solid select_cit_mobile_no',
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
        </div>
        <!-- sponsor -->
        <div class="row citizen_group">
            <h3>NAME OF SPONSOR</h3>
            <div class="col-md-4">
                <div class="form-group m-form__group select-contain" id='select-contain-sponsor'>
                    {{ Form::label('wtcm_sponsor', 'Name', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wtcm_sponsor',
                        $data->wtcm_sponsor,
                        $attributes = array(
                        'id' => 'wtcm_sponsor',
                        'class' => 'form-control form-control-solid',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_sponsor_relation', 'Relation to Minor', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wtcm_sponsor_relation',
                        isset($data->wtcm_sponsor_relation) ? $data->wtcm_sponsor_relation : '',
                        $attributes = array(
                        'id' => 'wtcm_sponsor_relation',
                        'class' => 'form-control form-control-solid',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_sponsor_age', 'Age', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wtcm_sponsor_age',
                        isset($data->wtcm_sponsor_age) ? $data->wtcm_sponsor_age : '',
                        $attributes = array(
                        'id' => 'wtcm_sponsor_age',
                        'class' => 'form-control form-control-solid',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_sponsor_occupation', 'Occupation', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wtcm_sponsor_occupation',
                        isset($data->wtcm_sponsor_occupation) ? $data->wtcm_sponsor_occupation : '',
                        $attributes = array(
                        'id' => 'wtcm_sponsor_occupation',
                        'class' => 'form-control form-control-solid',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                        )) 
                    }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_sponsor_contact', 'Contact No', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wtcm_sponsor_contact',
                        $data->wtcm_sponsor_contact,
                        $attributes = array(
                        'id' => 'wtcm_sponsor_contact',
                        'class' => 'form-control form-control-solid select_cit_mobile_no',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                        )) 
                    }}
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_sponsor_address', 'Address', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wtcm_sponsor_address',
                        $data->wtcm_sponsor_address,
                        $attributes = array(
                        'id' => 'wtcm_sponsor_address',
                        'class' => 'form-control form-control-solid select_cit_full_address',
                        (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                        )) 
                    }}
                </div>
            </div>
        </div>
        <!-- destination -->
        <div class="row mb-2">
            <div class="col-sm-6">
                    {{ Form::label('wtcm_destination', 'Destination', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('wtcm_destination',
                        $data->wtcm_destination,
                        $attributes = array(
                        'id' => 'wtcm_destination',
                        'class' => 'form-control form-control-solid ',
                        )) 
                    }}
            </div>
            <div class="col-sm-2">
                <p class="fs-6 fw-bold text-end">Lenght of Travel</p>
            </div>
            <div class="col-sm-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_travel_from', 'From', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::date('wtcm_travel_from',
                        $data->wtcm_travel_from,
                        $attributes = array(
                        'id' => 'wtcm_travel_from',
                        'class' => 'form-control form-control-solid ',
                        )) 
                    }}
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group m-form__group ">
                    {{ Form::label('wtcm_travel_to', 'To', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::date('wtcm_travel_to',
                        $data->wtcm_travel_to,
                        $attributes = array(
                        'id' => 'wtcm_travel_to',
                        'class' => 'form-control form-control-solid ',
                        )) 
                    }}
                </div>
            </div>
        </div>
        <!-- reasons -->
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group m-form__group ">
                {{ Form::label('wtcm_travel_purpose', 'Reason for Travel Abroad', ['class' => 'fs-6 fw-bold']) }}
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
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group m-form__group ">
                {{ Form::label('wtcm_reason_cant_accompany', 'Reason why parents or legal guardian cannot accompany minor', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::textarea('wtcm_reason_cant_accompany', 
                    $data->wtcm_reason_cant_accompany, 
                    $attributes = array(
                    'id' => 'wtcm_reason_cant_accompany',
                    'class' => 'form-control form-control-solid',
                    (isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'readonly':''
                    )) 
                }}
                </div>
            </div>
            <span class="validate-err"  id="err_wswa_remarks"></span>
        </div>
        <!-- Destination table -->
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-destination">
                        <button class="accordion-button collapsed btn-primary" type="button">
                            <h6 class="sub-title accordiantitle">{{__("Places where the minor intends to stay during his/her travel with whom")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-destination" class="accordion-collapse collapse show">
                        <table class="table" id="destination-list">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">Place</th>
                                    <th style="width: 20%;">Companion</th>
                                    <th >Address</th>
                                    <th style="width: 20%;">Contact No</th>
                                    <th style="width: 10%;">
                                    <a class="btn btn-primary add-require" data-id="0" data-bs-toggle="tooltip" title="{{__('Add Requirement')}}">
                                        <i class="ti-plus"></i>
                                    </a>
                                </th>
                                </tr>
                            </thead>
                            <tbody id="destination-contain">
                                @if(isset($data->destinations))
                                    @foreach($data->destinations as $destination)
                                    <tr class="old-row" id="destination-{{$destination->id}}-contain">
                                        <td>
                                            <input class="form-control" name="destination[{{$destination->id}}][wtcmd_place]" type="text" value="{{$destination->wtcmd_place}}">
                                        </td>
                                        <td>
                                            <input class="form-control" name="destination[{{$destination->id}}][wtcmd_companion]" type="text" value="{{$destination->wtcmd_companion}}">
                                        </td>
                                        <td>
                                            <input class="form-control" name="destination[{{$destination->id}}][wtcmd_address]" type="text" value="{{$destination->wtcmd_address}}">
                                        </td>
                                        <td>
                                            <input class="form-control" name="destination[{{$destination->id}}][wtcmd_contactno]" type="text" value="{{$destination->wtcmd_contactno}}">
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-danger remove-row m-0" data-bs-toggle="tooltip" title="Remove Destinaion">
                                                <i class="ti-trash"></i>
                                            </a>
                                            
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <!-- requirement row -->
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
                                        <td>{!!wordwrap($requirement->req_name, 135, "<br />\n",true)!!}</td>
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
                                                <a class="btn btn-sm bg-primary small-button" href="{{url($requirement->fwtm_path)}}" download="{{$data->companion->cit_fullname}}_{{$requirement->req_name}}" data-bs-toggle="tooltip" title="Download File"><i class="ti-download text-white"></i></a>
                                            @endif
                                                <input type="hidden" name="require[{{$requirement->id}}][req_id]" value="{{$requirement->req_id}}" >
                                                <input type="hidden" name="require[{{$requirement->id}}][req_type]" value="{{$requirement->req_type}}" >

                                            @if($requirement->fwtm_is_active == 0)
                                                <a  class="btn btn-sm btn-info remove-row" data-bs-toggle="tooltip" title="Remove Requirment" data-remove="requirement" data-id="{{$requirement->id}}" data-active='1'>
                                                    <i class="ti-reload"></i>
                                                </a>
                                            @else
                                                <a class="btn btn-sm btn-danger remove-row" data-bs-toggle="tooltip" title="Remove Requirment" data-remove="requirement" data-id="{{$requirement->id}}" data-active='0'>
                                                    <i class="ti-trash text-white"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            <!-- for add -->
                                @foreach($requirements as $id => $requirement)
                                    <tr class="row-{{$loop->iteration}} new-row">
                                        <td>{!!wordwrap($requirement, 135, "<br />\n",true)!!}</td>
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
        <!-- officer row -->
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
                <!-- or row -->
                <div class="col-md-4 select-contain">
                    <div class="form-group m-form__group " id="or-select-contain">
                        {{ Form::label('wtcm_cashier_id', 'OR No.', ['class' => 'fs-6 fw-bold']) }}
                        {{ Form::select('wtcm_cashier_id',
                            $or_num,
                            isset($data->wtcm_cashier_id) ? $data->wtcm_cashier_id : '', 
                            array(
                                'class' => 'form-control form-control-solid select3',
                                'id'=>'wtcm_cashier_id',
                                isset($data->tfoc_id) ? '':'disabled',
                                'data-contain' => 'or-select-contain',
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
            <input type="button" id="approveBtn" value="{{(isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'Approved':'Approve'}}" class="btn  btn-primary" {{(isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':''}}>
            <a href="{{route('tcm.print',['id' => $data->id])}}"  target="_blank" class="btn btn-info">
                    <i class="ti-printer text-white"></i>
                    {{__('Print')}}
                </a>
            @endif
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;" {{(isset($data->wtcm_is_approve) && $data->wtcm_is_approve === 1) ? 'disabled':''}}>
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
            <a href="{{route('tcm.index')}}"><input type="button" value="{{__('Cancel')}}" class="btn  btn-light"></a>
        </div>
    {{Form::close()}}
</div>



<script src="{{ asset('js/SocialWelfare/select-ajax.js?v='.filemtime(getcwd().'/js/SocialWelfare/select-ajax.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/citizen-ajax.js?v='.filemtime(getcwd().'/js/SocialWelfare/citizen-ajax.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/add-travel-minor.js?v='.filemtime(getcwd().'/js/SocialWelfare/add-travel-minor.js').'') }}"></script>
<script src="{{ asset('js/SocialWelfare/social-welfare-create.js?v='.filemtime(getcwd().'/js/SocialWelfare/social-welfare-create.js').'') }}"></script>
<script src="{{ asset('js/ajax_validation.js?v='.filemtime(getcwd().'/js/ajax_validation.js').'') }}"></script>
<!-- add Minors -->
<table>
    <tbody class="hidden" id="addMinors">
        <tr class="citizen_group new-row" id="minors-changeid-contain">
            <td class="select-contain" id="contain-select-relative-changeid">
                <select class="form-control select_id get-citizen " data-placeholder="Search Citizen" data-url="citizens/getCitizens" id="minors-changeid" data-contain="contain-select-relative-changeid" name="minors[changeid][cit_id]"></select>
            </td>
            <td class="select_age"></td>
            <td id="minors-gender-changeid-contain">
            {{ 
                Form::select('minors[changeid][data][cit_gender]',
                array('0'=>'Male','1'=>'Female'),
                '', 
                array(
                    'class' => 'form-control form-control-solid select3 select_cit_gender',
                    'id'=>'minors-gender-changeid',
                    'data-contain'=>'minors-changeid-contain'
                    )) 
            }}
            </td>
            <td id="minors-civil-changeid-contain">
            {{ 
                Form::select('minors[changeid][data][ccs_id]',
                $civilstat,
                '', 
                array(
                    'class' => 'form-control form-control-solid select3 select_ccs_id',
                    'id'=>'minors-civil-changeid',
                    'data-contain'=>'minors-changeid-contain'
                    )) 
            }}
            </td>
            <td>
                <input class="form-control select_cit_date_of_birth" name="minors[changeid][data][cit_date_of_birth]" type="date" value="">
            </td>
            <td>
                <input class="form-control selec_cit_place_of_birth" name="minors[changeid][data][cit_place_of_birth]" type="text" value="">
            </td>
            <td>
                <a href="#" class="btn btn-sm btn-danger remove-row m-0" data-bs-toggle="tooltip" title="Remove Minor">
                    <i class="ti-trash"></i>
                </a>
                
                <a href="#" data-size="lg" data-url="{{ url('/citizens/store') }}?field=minors-changeid" data-bs-toggle="tooltip" title="{{__('Add Citizen')}}" data-title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary add-citizen-btn btn_open_second_modal">
                    <i class="ti-plus"></i>
                </a>
            </td>
        </tr>
    </tbody>
</table>
<!-- add Destination -->
<table>
    <tbody class="hidden" id="addDestination">
        <tr class="new-row" id="destination-changeid-contain">
            <td>
                <input class="form-control" name="destination[changeid][wtcmd_place]" type="text" value="">
            </td>
            <td>
                <input class="form-control" name="destination[changeid][wtcmd_companion]" type="text" value="">
            </td>
            <td>
                <input class="form-control" name="destination[changeid][wtcmd_address]" type="text" value="">
            </td>
            <td>
                <input class="form-control" name="destination[changeid][wtcmd_contactno]" type="text" value="">
            </td>
            <td>
                <a href="#" class="btn btn-sm btn-danger remove-row m-0" data-bs-toggle="tooltip" title="Remove Destinaion">
                    <i class="ti-trash"></i>
                </a>
                
            </td>
        </tr>
    </tbody>
</table>
<!-- add requirements -->
<table>
    <tbody class="hidden" id="addRequirements">
        <tr class="new-row select_req" id="require-changeid-contain">

            <td class="select-contain" id="contain-select-requirement-changeid">
                <select class="form-control" data-placeholder="Search Requirement" data-url="social-welfare/assistance/getRequireList" id="require-newrow-changeid" data-contain="contain-select-requirement-changeid" name="require[changeida][req_id]"></select>
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