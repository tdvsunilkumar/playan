{{Form::open(array('name'=>'forms','onsubmit'=>'return check()','url'=>'bploapplication','method'=>'post'))}}
 {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  {{ Form::hidden('isreneval',$isreneval, array('id' => 'isreneval')) }}
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
    .field-requirement-details-status label{margin-top: 7px;}
    #flush-collapsetwo{
/*        padding-bottom: 80px;*/
    }
 </style>

<div class="modal-body">
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                {{Form::label('ba_cover_year',__('Covered Year'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{Form::text('ba_cover_year',$data->ba_cover_year,array('class'=>'form-control','required'=>'required','readonly'=>'readonly'))}}
                </div>
                <span class="validate-err" id="err_ba_cover_year"></span>
            </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8"></div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                {{Form::label('ba_business_account_no',__('Business Account No.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                <div class="form-icon-user">
                    {{Form::text('ba_business_account_no',$data->ba_business_account_no,array('class'=>'form-control','readonly'=>'readonly'))}}
                </div>
            </div>
        </div>
    </div>

    <div class="row pt10" >
        <!--------------- Owners Information Start Here---------------->
        <div class="col-lg-6 col-md-6 col-sm-6"  id="accordionFlushExample">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle">{{__("Owner's Information")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                        <div class="basicinfodiv">
                            <div class="row">
                                   <div class="col-lg-7 col-md-7 col-sm-7">
                                    <div class="form-group">
                                         {{ Form::select('profile_id',$profile,$data->profile_id, array('class' => 'form-control select3','id'=>'profile')) }}
                                    </div>
                                  </div>

                                  
                                   <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <a href="#"  data-size="xl" data-url="{{ url('/profileuser/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary" style="margin-top: 8px;">
                                        <i class="ti-plus"></i></a>
                                    </div>
                                  </div>
                            </div><br>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        {{Form::label('ba_p_first_name',__('First Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{Form::text('ba_p_first_name','',array('class'=>'form-control','required'=>'required'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_p_first_name"></span>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        {{Form::label('ba_p_middle_name',__('Middle Name'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_p_middle_name','',array('class'=>'form-control'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_p_middle_name"></span>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        {{Form::label('ba_p_last_name',__('Last Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{Form::text('ba_p_last_name','',array('class'=>'form-control','required'=>'required'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_p_last_name"></span>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        {{Form::label('ba_telephone_no',__('Tel. No.'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_telephone_no','',array('class'=>'form-control numeric'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        {{Form::label('ba_fax_no',__('Fax'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_fax_no','',array('class'=>'form-control'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        {{Form::label('ba_tin_no',__('TIN'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_tin_no','',array('class'=>'form-control','required'=>'required'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        {{Form::label('ba_p_address',__('Address'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{Form::textarea('ba_p_address','',array('class'=>'form-control','rows'=>1))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_p_address"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Owners Information Start Here---------------->

        <!--------------- Business Information Start Here---------------->
        <div class="col-lg-6 col-md-6 col-sm-6" id="accordionFlushExample2">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                        <h6 class="sub-title accordiantitle">{{__('Business Information')}}</h6>
                        </button>
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                        <div class="row"  id="otheinfodiv">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    {{Form::label('ba_business_name',__('Trade Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                       {{ Form::select('ba_business_name',$tradearray, $data->ba_business_name, array('class' => 'form-control','required'=>'required','id'=>'ba_business_name')) }}
                                    </div>
                                    <span class="validate-err" id="err_ba_business_name"></span>
                                </div>
                            </div>
                             <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                       {{Form::label('ba_type_id',__('Application Type'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                        {{ Form::select('app_type_id',array(1=>'New', 2=>'Renew'), $data->app_type_id, array('class' => 'form-control','required'=>'required','id'=>'app_type_id')) }}
                                      </div>
                                      <span class="validate-err" id="err_ba_type_id"></span>
                                  </div>
                            </div>            
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('ba_address_house_lot_no',__('Bldg/Hse #'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('ba_address_house_lot_no',$data->ba_address_house_lot_no,array('class'=>'form-control'))}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('ba_address_street_name',__('Street'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('ba_address_street_name',$data->ba_address_street_name,array('class'=>'form-control'))}}
                                    </div>
                                    <span class="validate-err" id="err_ba_address_street_name"></span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('barangay_id',__('Barangay'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                    {{ Form::select('barangay_id',$arrBarangay, $data->barangay_id, array('class' => 'form-control select3','id'=>'barangay_id','required'=>'required')) }}
                                    </div>
                                    <span class="validate-err" id="err_barangay_id"></span>
                                </div>
                            </div>
                            {{ Form::hidden('brgy_name',$data->brgy_name, array('id' => 'brgy_name')) }}
                            <!-- <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('brgy_name',__('Barangay Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                        {{Form::text('brgy_name',$data->brgy_name,array('class'=>'form-control','required'=>'required','id'=>'brgy_name'))}}
                                    </div>
                                    <span class="validate-err" id="err_brgy_name"></span>
                                </div>
                            </div> -->
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('ba_city_name',__('Municipality City'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                        {{ Form::select('ba_city_name',$arrCity,$data->ba_city_name, array('class' => 'form-control select3')) }}
                                    </div>
                                    <span class="validate-err" id="err_ba_address_street_name"></span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('ba_telephone_no',__('Tel. No.'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::number('ba_telephone_no',$data->ba_telephone_no,array('class'=>'form-control'))}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('ba_fax_no',__('Fax'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('ba_fax_no',$data->ba_fax_no,array('class'=>'form-control'))}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('business_phone',__('Phone No.'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::number('business_phone',$data->business_phone,array('class'=>'form-control'))}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('business_tin',__('Tin No.'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('business_tin',$data->business_tin,array('class'=>'form-control'))}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('business_email',__('Email Address'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::email('business_email',$data->business_email,array('class'=>'form-control'))}}
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Business Information End Here------------------>
    </div>
    <div class="row" >
        <!--------------- Building Information Start Here---------------->
        <div class="col-lg-4 col-md-4 col-sm-4"  id="accordionFlushExample3" style="padding-top: 10px;">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingthree">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsethree" aria-expanded="false" aria-controls="flush-headingthree">
                            <h6 class="sub-title accordiantitle">{{__("Building Information")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapsethree" class="accordion-collapse collapse show" aria-labelledby="flush-headingthree" data-bs-parent="#accordionFlushExample3" style="padding-bottom: 38px;">
                        <div class="basicinfodiv">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('ba_building_total_area_occupied',__('Area (sq. m.)'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{Form::text('ba_building_total_area_occupied',$data->ba_building_total_area_occupied,array('class'=>'form-control numeric','required'=>'required'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_building_total_area_occupied"></span>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="d-flex radio-check" style="padding-top: 10px;"><br>
                                        <div class="form-check form-check-inline form-group">
                                            {{ Form::radio('ba_building_is_owned', '1', ($data->ba_building_is_owned)?true:false, array('id'=>'Owned','class'=>'form-check-input code')) }}
                                            {{ Form::label('Owned', __('Owned'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="form-check form-check-inline form-group">
                                            {{ Form::radio('ba_building_is_owned', '0', (!$data->ba_building_is_owned)?true:false, array('id'=>'Rented','class'=>'form-check-input code')) }}
                                            {{ Form::label('Rented', __('Rented/Leased'),['class'=>'form-label']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row field-requirement-details-status">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                    {{Form::label('',__('Building/Lessor Detail'),['class'=>'form-label'])}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('ba_building_permit_no',__('Permit No.'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_building_permit_no',$data->ba_building_permit_no,array('class'=>'form-control'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_building_permit_no"></span>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('ba_building_permit_issued_date',__('Issue Date'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::date('ba_building_permit_issued_date',$data->ba_building_permit_issued_date,array('class'=>'form-control'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_building_permit_issued_date"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('ba_building_certificate_occupancy_number',__('Cert. Occu. No.'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_building_certificate_occupancy_number',$data->ba_building_permit_no,array('class'=>'form-control'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_building_certificate_occupancy_number"></span>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('ba_building_info_date_updated',__('Date'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::date('ba_building_info_date_updated',$data->ba_building_info_date_updated,array('class'=>'form-control'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_building_info_date_updated"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('ba_building_property_index_number',__('P.I.N'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_building_property_index_number',$data->ba_building_property_index_number,array('class'=>'form-control numeric'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_building_property_index_number"></span>
                                    </div>
                                </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        {{Form::label('ba_building_assessed_value',__('Assessed Value'),['class'=>'form-label'])}}
                                        <div class="form-icon-user currency">
                                            {{Form::number('ba_building_assessed_value',$data->ba_building_assessed_value,array('class'=>'form-control','placeholder'=>'0.00'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                        <span class="validate-err" id="err_ba_building_assessed_value"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Building Information End Here------------------>

        <!--------------- Ownership and Other Information Start Here---------------->
        <div class="col-lg-8 col-md-8 col-sm-8"  id="accordionFlushExample4" style="padding-top: 10px;">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfour">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefour" aria-expanded="false" aria-controls="flush-headingfour">
                            <h6 class="sub-title accordiantitle">{{__("Ownership and Other Information")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapsefour" class="accordion-collapse collapse show" aria-labelledby="flush-headingfour" data-bs-parent="#accordionFlushExample4">
                        <div class="basicinfodiv">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_type_id',__('Type'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                        {{ Form::select('ba_type_id',array(''=>'Select Type',1=>'Single Proprietorship', 2=>'Partnership', 3=>'Corporation'), $data->ba_type_id, array('class' => 'form-control','required'=>'required')) }}
                                        </div>
                                        <span class="validate-err" id="err_ba_type_id"></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_plate_is_issued',__('Plate Issued'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                        {{ Form::select('ba_plate_is_issued',array(''=>'Please Select',1=>'Yes', 0=>'No'), $data->ba_plate_is_issued, array('class' => 'form-control')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_plate_big_small',__('Big/Small'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                        {{ Form::select('ba_plate_big_small',array(''=>'Please Select',1=>'Big', 0=>'Small'), $data->ba_plate_big_small, array('class' => 'form-control','id'=>'barangay_id')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_no_of_personnel',__('No. of personnel'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{Form::text('ba_no_of_personnel',$data->ba_no_of_personnel,array('class'=>'form-control numeric','required'=>'required'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_no_of_personnel"></span>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="d-flex radio-check" style="padding-top: 30px;"><br>
                                        {{Form::label('ba_office_type',__('Office Type:'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-check form-check-inline form-group" style="padding-left: 44px;">
                                            {{ Form::radio('ba_office_type', '1', ($data->ba_office_type)?true:false, array('id'=>'Main','class'=>'form-check-input code')) }}
                                            {{ Form::label('Main', __('Main Office'),['class'=>'form-label']) }}
                                        </div>
                                        <div class="form-check form-check-inline form-group">
                                            {{ Form::radio('ba_office_type', '0', (!$data->ba_office_type)?true:false, array('id'=>'Branch','class'=>'form-check-input code')) }}
                                            {{ Form::label('Branch', __('Branch Office'),['class'=>'form-label']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_registration_sss_number',__('SSS No.'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_registration_sss_number',$data->ba_registration_sss_number,array('class'=>'form-control'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_registration_sss_date_issued',__('SSS No Date Issued'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::date('ba_registration_sss_date_issued',$data->ba_registration_sss_date_issued,array('class'=>'form-control'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row field-requirement-details-status">
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                    {{Form::label('business_registration',__('Business Registration'),['class'=>'form-label'])}}
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_sec_registration_no',__('SEC Registration No'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_sec_registration_no',$data->ba_sec_registration_no,array('class'=>'form-control'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_sec_registration_no"></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_sec_registration_date_issued',__('Date Issued'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::date('ba_sec_registration_date_issued',$data->ba_sec_registration_date_issued,array('class'=>'form-control'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_sec_registration_date_issued"></span>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_dti_no',__('DTI No'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_dti_no',$data->ba_dti_no,array('class'=>'form-control'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_dti_no"></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_dti_date_issued',__('Date Issued'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::date('ba_dti_date_issued',$data->ba_dti_date_issued,array('class'=>'form-control'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_dti_date_issued"></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_registration_ctc_no',__('Community Tax Cert. No.'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_registration_ctc_no',$data->ba_registration_ctc_no,array('class'=>'form-control'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_registration_ctc_issued_date',__('Issue Date'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::date('ba_registration_ctc_issued_date',$data->ba_registration_ctc_issued_date,array('class'=>'form-control'))}}
                                        </div>
                                    </div>
                                </div>
                                
                                  <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_registration_ctc_place_of_issuance',__('Place of Issue'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_registration_ctc_place_of_issuance',$data->ba_registration_ctc_place_of_issuance,array('class'=>'form-control'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group currency">
                                        {{Form::label('ba_registration_ctc_amount_paid',__('Amount Paid'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{Form::number('ba_registration_ctc_amount_paid',$data->ba_registration_ctc_amount_paid,array('class'=>'form-control','required'=>'required','placeholder'=>'0.00'))}}
                                            <div class="currency-sign"><span>Php</span></div>
                                        </div>
                                        <span class="validate-err" id="err_ba_registration_ctc_amount_paid"></span>
                                    </div>
                                </div>
                            

                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_locational_clearance_no',__('Locational Clearance'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_locational_clearance_no',$data->ba_locational_clearance_no,array('class'=>'form-control'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_locational_clearance_date_issued',__('Locational Date Issued'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::date('ba_locational_clearance_date_issued',$data->ba_locational_clearance_date_issued,array('class'=>'form-control'))}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_bureau_domestic_trade_no',__('Bureau Of Domestic Trade'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_bureau_domestic_trade_no',$data->ba_bureau_domestic_trade_no,array('class'=>'form-control'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_bureau_domestic_trade_date_issued',__('Date Issued'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::date('ba_bureau_domestic_trade_date_issued',$data->ba_bureau_domestic_trade_date_issued,array('class'=>'form-control'))}}
                                        </div>
                                    </div>
                                </div>

                                



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Ownership and Other Information End Here------------------>
    </div>

    <div class="row" >
        <!--------------- Taxable Items Start Here---------------->
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="padding-top: 10px;">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefive" aria-expanded="false" aria-controls="flush-headingfive">
                            <h6 class="sub-title accordiantitle">{{__("Taxable Items")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                        <div class="basicinfodiv">
                            <div class="row">
                                {{Form::label('ba_taxable_owned_truck_wheeler_10above',__('Truck/Van owned '),['class'=>'form-label'])}}
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_taxable_owned_truck_wheeler_10above',__('10 wheelers & Above'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_taxable_owned_truck_wheeler_10above',$data->ba_taxable_owned_truck_wheeler_10above,array('class'=>'form-control numeric'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_taxable_owned_truck_wheeler_6above',__('6 Wheelers'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_taxable_owned_truck_wheeler_6above',$data->ba_taxable_owned_truck_wheeler_6above,array('class'=>'form-control numeric'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('ba_taxable_owned_truck_wheeler_4above',__('4 Wheelers'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_taxable_owned_truck_wheeler_4above',$data->ba_taxable_owned_truck_wheeler_4above,array('class'=>'form-control numeric'))}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                            <div class="row field-requirement-details-status">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        {{Form::label('subclass_id',__('Nature Of Business'),['class'=>'form-label'])}}
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::label('taxable_item_name',__('Item Name'),['class'=>'form-label'])}}
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        {{Form::label('taxable_item_qty',__('Item Qty'),['class'=>'form-label numeric'])}}
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::label('capital_investment',__('Capital Investment'),['class'=>'form-label'])}}
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::label('date_started',__('Issue Date'),['class'=>'form-label'])}}
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <input type="button" id="btn_addmore_nature" class="btn btn-success" value="Add More" style="padding: 0.4rem 0.76rem !important;">
                                    </div>
                                </div>
                                <span class="natureDetails nature-details" id="natureDetails">
                                    <span class="validate-err" id="err_natureofbussiness"></span>
                                    @php $i=0; @endphp
                                    @foreach($arrNature as $key=>$val)
                                        <div class="row removenaturedata pt10">
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                    {{ Form::select('psic_subclass_id[]',$arrSubclasses, $val['psic_subclass_id'], array('class' => 'form-control natureofbussiness','id'=>'psic_subclass_id'.$i)) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{Form::text('taxable_item_name[]',$val['taxable_item_name'],array('class'=>'form-control'))}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-1 col-md-1 col-sm-1">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{Form::text('taxable_item_qty[]',$val['taxable_item_qty'],array('class'=>'form-control numeric','onkeyup'=>'this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null'))}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-icon-user currency" >
                                                        {{Form::number('capital_investment[]',$val['capital_investment'],array('class'=>'form-control','placeholder'=>'0.00'))}}
                                                        <div class="currency-sign"><span>Php</span></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{Form::date('date_started[]',$val['date_started'],array('class'=>'form-control'))}}
                                                    </div>
                                                </div>
                                            </div>
                                            @if($i>0)
                                                <div class="col-sm-1">
                                                    <input type="button" name="btn_cancel_nature" class="btn btn-success btn_cancel_nature" value="Delete" style="padding: 0.4rem 1rem !important;">
                                                </div>
                                            @endif
                                            <script type="text/javascript">
                                                $(document).ready(function(){
                                                    $("#psic_subclass_id<?=$i?>").select3({dropdownAutoWidth : false,dropdownParent: $("#natureDetails")});
                                                });
                                            </script>
                                            @php $i++; @endphp
                                        </div>
                                        
                                    @endforeach
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Taxable Items End Here------------------>
    </div>

    <div class="row" >
        <!--------------- Requirement Details Status Start Here---------------->
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample6" style="padding-top: 10px;">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingsix">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsesix" aria-expanded="false" aria-controls="flush-headingsix">
                            <h6 class="sub-title accordiantitle">{{__("Requirement Details Status")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapsesix" class="accordion-collapse collapse show" aria-labelledby="flush-headingsix" data-bs-parent="#accordionFlushExample6">
                        <div class="basicinfodiv">
                            <div class="row">
                                <div class="row field-requirement-details-status">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        {{Form::label('business_description',__('Business Description'),['class'=>'form-label'])}}
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::label('completed_mark',__('Status'),['class'=>'form-label'])}}
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        {{Form::label('completed_mark',__('Date Submited'),['class'=>'form-label'])}}
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        {{Form::label('remark_s',__('Remarks'),['class'=>'form-label'])}}
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <input type="button" id="btn_addmore_requirement" class="btn btn-success" value="Add More" style="padding: 0.4rem 0.76rem !important;">
                                    </div>
                                </div>
                                <p id="message" style="color:red;font-size:15px;"></p>
                                @php $i=0; @endphp
                                @foreach($arrBusiness as $key=>$val)
                                 <span class="validate-err" id="err_requirements"></span>
                                    <div class="removerequirementdata row pt10">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{ Form::hidden('appreqrelid[]',$val['id']) }}
                                                    {{ Form::select('bplo_code_abbreviation[]',$arrrequirement,$val['bplo_code_abbreviation'], array('class' => 'form-control codeabbrevation','id'=>'bplo_code_abbreviation','required'=>'required')) }}
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="d-flex radio-check"><br>
                                                <div class="form-check form-check-inline form-group">
                                                    {{ Form::checkbox($i.'_bar_is_complied', '1', ($val['bar_is_complied'])?true:false, array('id'=>'Completed'.$i,'class'=>'form-check-input bariscompleted code')) }}
                                                    {{ Form::label('Completed'.$i, __('Completed'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::date('bar_date_sumitted[]',$val['bar_date_sumitted'],array('class'=>'form-control'))}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::text('bar_remarks[]',$val['bar_remarks'],array('class'=>'form-control'))}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1">
                                             <input type="button" name="btn_cancel" class="btn btn-success btn_cancel_requirementedit" id="{{$val['id']}}" value="Delete" style="padding: 0.4rem 1rem !important;">
                                        </div>
                                    </div>
                                    @php $i++; @endphp
                                @endforeach
                                <span  id="dynamicrequirements"></span>
                                @if(empty($data->id))
                                <span id="defaultrequirements">
                                      <div class="row removerequirementdata pt10">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                 {{ Form::hidden('bplo_requirement_id[]','') }}
                                                <div class="form-icon-user">
                                                     {{ Form::select('bplo_code_abbreviation[]',$arrrequirement,'', array('class' => 'form-control select3 codeabbrevation','id'=>'bplo_code_abbreviationdefault','required'=>'required')) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="d-flex radio-check"><br>
                                                <div class="form-check form-check-inline form-group">
                                                    {{ Form::checkbox($i.'_bar_is_complied', '1', ($data->id)?true:false, array('id'=>'Completed'.$i,'class'=>'form-check-input bariscompleted code')) }}
                                                    {{ Form::label('Completed'.$i, __('Completed'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::date('bar_date_sumitted[]','',array('class'=>'form-control','required'=>'required'))}}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::text('bar_remarks[]','',array('class'=>'form-control','required'=>'required'))}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1">
                                             <input type="button" name="btn_cancel" class="btn btn-success btn_cancel_requirement" cid="" value="Delete" style="padding: 0.4rem 1rem !important;">
                                        </div>
                                    </div>  
                                    </span> 
                                @endif 
                                  <span  class="requirementDetails require-details" id="requirementDetails">
                            </div>
                            <div class="row" style="padding-top:20px">
                                <div class="col-lg-10 col-md-10 col-sm-10">
                                    <div class="d-flex radio-check" style="padding-top: 30px;"><br>
                                        <div class="form-check form-check-inline form-group">
                                            {{ Form::checkbox('is_approved', '1', ($data->is_approved)?true:false, array('id'=>'approved','class'=>'form-check-input code')) }}
                                            {{ Form::label('approved', __('Approved By Assesment & Billing'),['class'=>'form-label']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        {{Form::label('ba_date_started',__('Date'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{Form::date('ba_date_started',$data->ba_date_started,array('class'=>'form-control','required'=>'required'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_date_started"></span>
                                    </div>
                                </div> 
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Requirement Details Status End Here------------------>
    </div>

</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
     @if($isreneval > 0)
        <input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Renew'):__('Create')}}" class="btn  btn-primary">
     @else
     <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
        <i class="fa fa-save icon"></i>
        <input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
    </div>
     <!-- <input type="submit" name="submit" id="submit"  value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
     @endif  
</div>
{{Form::close()}}
<input type="hidden" name="dynamicid" value="3" id="dynamicid">
<div id="hidennatureHtml" class="hide">
    <div class="removenaturedata row pt10">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <div class="form-icon-user">
                     @php 
                        $i=(count($arrNature)>0)?count($arrNature):0;
                    @endphp
                {{ Form::select('psic_subclass_id[]',$arrSubclasses, '', array('class' => 'form-control naofbussi natureofbussiness','id'=>'psic_subclass_id'.$i)) }}
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                    {{Form::text('taxable_item_name[]','',array('class'=>'form-control','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="form-group">
                <div class="form-icon-user">
                    {{Form::text('taxable_item_qty[]','',array('class'=>'form-control numeric','onkeyup'=>'this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null'))}}
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user currency">
                    {{Form::number('capital_investment[]','',array('class'=>'form-control','placeholder'=>'0.00','required'=>'required'))}}
                    <div class="currency-sign"><span>Php</span></div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                    {{Form::date('date_started[]',date("Y-m-d"),array('class'=>'form-control','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-sm-1">
            <input type="button" name="btn_cancel" class="btn btn-success btn_cancel_nature" cid="" value="Delete" style="padding: 0.4rem 1rem !important;">
        </div>
    </div>
</div>

<div id="hidenrequirementHtml" class="hide">    
    <div class="removerequirementdata row pt10">
    <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="form-group">
            {{ Form::hidden('bplo_requirement_id[]','') }}
            <div class="form-icon-user">
                 @php 
                        $z=(count($arrBusiness)>0)?count($arrBusiness):0;
                    @endphp
                 {{ Form::select('bplo_code_abbreviation[]',$arrrequirement,'', array('class' => 'form-control  codeabbrevation','id'=>'bplo_code_abbreviation'.$z,'required'=>'required')) }}
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2">
        <div class="d-flex radio-check"><br>
            <div class="form-check form-check-inline form-group">
                {{ Form::checkbox($i.'_bar_is_complied', '1', '', array('id'=>'Completed'.$i,'class'=>'form-check-input bariscompleted code')) }}
                {{ Form::label('Completed'.$i, __('Completed'),['class'=>'form-label']) }}
            </div>
        </div>
        
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2">
        <div class="form-group">
            <div class="form-icon-user">
                {{Form::date('bar_date_sumitted[]','',array('class'=>'form-control','required'=>'required'))}}
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3">
        <div class="form-group">
            <div class="form-icon-user">
                {{Form::text('bar_remarks[]','',array('class'=>'form-control','required'=>'required'))}}
            </div>
        </div>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1">
        <input type="button" name="btn_cancel" class="btn btn-success btn_cancel_requirement" cid="" value="Delete" style="padding: 0.4rem 1rem !important;">
    </div>
    </div> 
</div>

<script type="text/javascript">
//     $("#submit").on("click",function(){
//     if (($("input[name*='Completed']:checked").length)<=0) {
//         alert("You must check at least 1 box");
//     }
//     return true;
// });
    // function check(){
    //           var ckCompleted0=document.getElementById("Completed0");
    //           var ckCompleted1=document.getElementById("Completed1");
    //           var ckCompleted2=document.getElementById("Completed2");
              
              
               
    //                 if(ckCompleted0.checked || ckCompleted1.checked || ckCompleted2.checked){
                     
    //                  return true;
    //                 }
    //                 else {    
    //                     // document.getElementById("message").innerHTML="Please check at least one field"
    //                    alert("Please check at least one field");
    //                   return false;   
    //                 }
                     
                
</script>
<script src="{{ asset('js/addApplication.js') }}"></script>
<script src="{{ asset('js/ajax_validationbplo.js') }}"></script>



