<div class="tab-pane fade" id="alob-details" role="tabpanel" aria-labelledby="request-details-tab">
{{ Form::open(array('url' => 'business-online-application', 'class'=>'formDtls', 'name' => 'busnOptForm')) }}
    @csrf
    <h4 class="text-header">Business's Information</h4>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('busloc_id', 'Business Activity', ['class' => '']) }}
                {{
                    Form::select('busloc_id', $bsn_activity, $value = '', ['id' => 'busloc_id', 'class' => 'form-control select3', 'data-placeholder' => 'Please select','disabled' => 'disabled'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('busn_bldg_area', 'Business Area(in Sq. m.)', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::text($name = 'busn_bldg_area', $value = '', 
                    $attributes = array(
                        'id' => 'busn_bldg_area',
                        'class' => 'form-control form-control-solid numeric-only',
                        'readonly' => 'true'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('busn_bldg_total_floor_area', 'Total Floor Area', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::text($name = 'busn_bldg_total_floor_area', $value = '', 
                    $attributes = array(
                        'id' => 'busn_bldg_total_floor_area',
                        'class' => 'form-control form-control-solid numeric-only',
                        'readonly' => 'true'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('busn_employee_no_female', 'No. of Female Employees in Establishment', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::text($name = 'busn_employee_no_female', $value = '0', 
                    $attributes = array(
                        'id' => 'busn_employee_no_female',
                        'class' => 'form-control form-control-solid numeric-only',
                        'min' => 0,
                        'readonly' => 'true'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('busn_employee_no_male', 'No. of Male Employees in Establishment', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::text($name = 'busn_employee_no_male', $value = '0', 
                    $attributes = array(
                        'id' => 'busn_employee_no_male',
                        'class' => 'form-control form-control-solid numeric-only',
                        'min' => 0,
                        'readonly' => 'true'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('busn_employee_no_lgu', 'No. of Employees Residing in LGU', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::text($name = 'busn_employee_no_lgu', $value = '', 
                    $attributes = array(
                        'id' => 'busn_employee_no_lgu',
                        'class' => 'form-control form-control-solid numeric-only',
                        'readonly' => 'true'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="item-layer">
        <h4 class="text-header">Number of Delivery Vehicles (if applicable)</h4>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('busn_vehicle_no_van_truck', 'Van/Truck', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_vehicle_no_van_truck', $value = '', 
                        $attributes = array(
                            'id' => 'busn_vehicle_no_van_truck',
                            'class' => 'form-control form-control-solid numeric-only',
                            'readonly' => 'true'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('busn_vehicle_no_motorcycle', 'Motorcycle', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_vehicle_no_motorcycle', $value = '', 
                        $attributes = array(
                            'id' => 'busn_vehicle_no_motorcycle',
                            'class' => 'form-control form-control-solid numeric-only',
                            'readonly' => 'true'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- <div class="col-sm-6">
                <div class="form-group m-form__group"> 
                    {{ Form::label('busn_bldg_is_owned', 'Owned?', ['class' => 'fs-6 fw-bold']) }} <span class="text-danger">*</span>
                    {{ Form::radio($name = 'busn_bldg_is_owned', $value = '1', $checked = false, $attributes = array(
                        'id' => 'yes_radio',
                        'class' => 'form-control form-check-input'
                    )) }}
                    {{ Form::label('yes_radio', 'Yes', ['class' => 'form-check-label']) }}
                   
                    {{ Form::radio($name = 'busn_bldg_is_owned', $value = '0', $checked = true, $attributes = array(
                        'id' => 'no_radio',
                        'class' => 'form-control form-check-input'
                    )) 
					}}
                    {{ Form::label('no_radio', 'No', ['class' => 'form-check-label']) }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div> -->
            <div class="col-sm-6">
                <div class="form-group m-form__group d-flex align-items-center" style="margin-top: 20px;"> 
                    <label class="fs-6 fw-bold me-3">Owned?<span class="text-danger">*</span></label>
                    <div class="form-check me-3">
                        {{ Form::radio('busn_bldg_is_owned', '1', false, ['id' => 'yes_radio', 'class' => 'form-check-input','disabled'=>'disabled']) }}
                        <label class="form-check-label" for="yes_radio">Yes</label>
                    </div>
                    <div class="form-check">
                        {{ Form::radio('busn_bldg_is_owned', '0', true, ['id' => 'no_radio', 'class' => 'form-check-input','disabled'=>'disabled']) }}
                        <label class="form-check-label" for="no_radio">No</label>
                    </div>
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
          
            <div class="col-sm-3">
                <div class="form-group m-form__group">
                    {{ Form::label('busn_bldg_tax_declaration_no', 'Building Tax Declaration No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_bldg_tax_declaration_no', $value = '', 
                        $attributes = array(
                            'id' => 'busn_bldg_tax_declaration_no',
                            'class' => 'form-control form-control-solid',
                            'readonly' => "true"
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group">
                    {{ Form::label('busn_bldg_property_index_no', 'Property Index Number(PIN)', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_bldg_property_index_no', $value = '', 
                        $attributes = array(
                            'id' => 'busn_bldg_property_index_no',
                            'class' => 'form-control form-control-solid',
                            'readonly' => "true"
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group m-form__group required d-flex align-items-center">
                    <label class="fs-6 fw-bold me-3">Do you have tax incentives from any Government Entity?<span class="text-danger">*</span></label>
                    <div class="form-check me-3">
                        {{ Form::radio('busn_tax_incentive_enjoy', '1', false, ['id' => 'yes_radio_en', 'class' => 'form-check-input','readonly' => 'true','disabled'=>'disabled']) }}
                        <label class="form-check-label" for="yes_radio_en">Yes</label>
                    </div>
                    <div class="form-check">
                        {{ Form::radio('busn_tax_incentive_enjoy', '0', true, ['id' => 'no_radio_en', 'class' => 'form-check-input','readonly' => 'true','disabled'=>'disabled']) }}
                        <label class="form-check-label" for="no_radio_en">No</label>
                    </div>
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
            <!-- <div class="col-sm-4">
                <div class="form-group m-form__group">
                    {{ Form::label('buld_owner', 'Building Owner', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'buld_owner', $value = '', 
                        $attributes = array(
                            'id' => 'buld_owner',
                            'class' => 'form-control form-control-solid',
                            'readonly' => "true"
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group m-form__group">
                    {{ Form::label('buld_tax_status', 'Building Tax Status', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'buld_tax_status', $value = '', 
                        $attributes = array(
                            'id' => 'buld_tax_status',
                            'class' => 'form-control form-control-solid',
                            'readonly' => "true"
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div> -->
            <!-- <div class="col-sm-6">
                <div class="form-group m-form__group required">
                    {{ Form::label('busn_tax_incentive_enjoy', 'Do you have tax incentives from any Government Entity?', ['class' => 'fs-6 fw-bold']) }} <span class="text-danger">*</span>
                    {{ Form::radio($name = 'busn_tax_incentive_enjoy', $value = '1', $checked = false, $attributes = array(
                        'id' => 'yes_radio_en',
                        'class' => 'form-control form-check-input'
                    )) }}
                    {{ Form::label('yes_radio_en', 'Yes', ['class' => 'form-check-label']) }}

                    {{ Form::radio($name = 'busn_tax_incentive_enjoy', $value = '0', $checked = true, $attributes = array(
                        'id' => 'no_radio_en',
                        'class' => 'form-control form-check-input'
                    )) }}
                    {{ Form::label('no_radio_en', 'No', ['class' => 'form-check-label']) }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div> -->
        </div>
    </div>
    <div class="item-layer">
        <h4 class="text-header">Business Location Address</h4>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group m-form__group">
                    {{ Form::checkbox($name = 'busn_office_is_same_as_main', $value = '1', $checked = "", $attributes = array(
                        'id' => 'busn_office_is_same_as_main',
                        'class' => 'form-check-input same_as_address',
                        'disabled'=>'disabled'
                    )) }}
                    {{ Form::label('busn_office_is_same_as_main', 'Same as Main Office', ['class' => 'form-check-label same_as_address' ]) }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
        </div>

        <div class="row">
           
            <div class="col-md-12">
                <div class="form-group m-form__group required">
                    {{ Form::label('busn_office_barangay_id', 'Barangay|Municipality|Province|Region', ['class' => '']) }}
                    <div class="form-icon-user d-flex align-items-center">
                        <div class="flex-grow-1">
                            {{
                                Form::select('busn_office_barangay_id', $barangay, $value = '', ['id' => 'busn_office_barangay_id', 'class' => 'form-control select3', 'data-placeholder' => 'Please select','disabled' => 'disabled'])
                            }}                        
                        </div>
                        <!-- <div class="ms-2">
                            <a href="javascript:;" class="action-btn refresh-barangay bg-warning btn m-1 btn-sm align-items-center" title="Refresh">
                                <i class="ti-reload"></i>
                            </a>
                        </div>
                        <div class="ms-2">
                            <a href="{{ route('barangay.index') }}" target="_blank" title="{{ __('Add Barangay') }}" class="btn btn-sm btn-primary"><i class="ti-plus"></i></a>
                        </div> -->
                    </div>
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('busn_office_building_name', 'Name of Building', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_office_building_name', $value = '', 
                        $attributes = array(
                            'id' => 'busn_office_building_name',
                            'class' => 'form-control form-control-solid',
                            'readonly' => 'true'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('busn_office_building_no', 'House/Bldg. No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_office_building_no', $value = '', 
                        $attributes = array(
                            'id' => 'busn_office_building_no',
                            'class' => 'form-control form-control-solid',
                            'readonly' => 'true'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
           
            
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('busn_office_add_block_no', 'Block No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_office_add_block_no', $value = '', 
                        $attributes = array(
                            'id' => 'busn_office_add_block_no',
                            'class' => 'form-control form-control-solid',
                            'readonly' => 'true'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('busn_office_add_lot_no', 'Lot No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_office_add_lot_no', $value = '', 
                        $attributes = array(
                            'id' => 'busn_office_add_lot_no',
                            'class' => 'form-control form-control-solid',
                            'readonly' => 'true'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('busn_office_add_subdivision', 'Subdivision', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_office_add_subdivision', $value = '', 
                        $attributes = array(
                            'id' => 'busn_office_add_subdivision',
                            'class' => 'form-control form-control-solid',
                            'readonly' => 'true'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('busn_office_add_street_name', 'Street Name', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_office_add_street_name', $value = '', 
                        $attributes = array(
                            'id' => 'busn_office_add_street_name',
                            'class' => 'form-control form-control-solid',
                            'readonly' => 'true'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="modalToast" data-bs-autohide="true" class="toast hide bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body">
            Hello, world! This is a toast message.
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>