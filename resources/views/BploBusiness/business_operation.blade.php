<div class="tab-pane fade" id="alob-details" role="tabpanel" aria-labelledby="request-details-tab">
{{ Form::open(array('url' => 'business-permit/application', 'class'=>'formDtls', 'name' => 'busnOptForm')) }}
    @csrf
    <h4 class="text-header">Business's Information</h4>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('busloc_id', 'Business Activity', ['class' => '']) }}
                {{
                    Form::select('busloc_id', $bsn_activity, $value = '', ['id' => 'busloc_id', 'class' => 'form-control select3', 'data-placeholder' => 'Please select'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('busn_bldg_area', 'Business Area (in Sq. m.)', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text('busn_bldg_area', null, [
                            'id' => 'busn_bldg_area',
                            'class' => 'form-control form-control-solid',
                            'pattern' => '^\d+(\.\d{1,3})?$' // Enforces the pattern
                        ]) 
                    }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('busn_bldg_total_floor_area', 'Total Floor Area', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::text($name = 'busn_bldg_total_floor_area', $value = '', 
                    $attributes = array(
                        'id' => 'busn_bldg_total_floor_area',
                        'class' => 'form-control form-control-solid',
                        'pattern' => '^\d+(\.\d{1,3})?$' // Enforces the pattern
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
                        'class' => 'form-control form-control-solid numeric-only'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="item-layer">
        <h4 class="text-header">Number of Delivery Vehicles (if applicable)</h4>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group m-form__group">
                    {{ Form::label('busn_vehicle_no_van_truck', 'Van/Truck', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_vehicle_no_van_truck', $value = '', 
                        $attributes = array(
                            'id' => 'busn_vehicle_no_van_truck',
                            'class' => 'form-control form-control-solid numeric-only'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group m-form__group">
                    {{ Form::label('busn_vehicle_no_motorcycle', 'Motorcycle', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_vehicle_no_motorcycle', $value = '', 
                        $attributes = array(
                            'id' => 'busn_vehicle_no_motorcycle',
                            'class' => 'form-control form-control-solid numeric-only'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group m-form__group required d-flex align-items-center" style="margin-top: 20px;">
                    <label class="fs-6 fw-bold me-3">Do you have tax incentives from any Government Entity?<span class="text-danger">*</span></label>
                    <div class="form-check me-3">
                        {{ Form::radio('busn_tax_incentive_enjoy', '1', false, ['id' => 'yes_radio_en', 'class' => 'form-check-input']) }}
                        <label class="form-check-label" for="yes_radio_en">Yes</label>
                    </div>
                    <div class="form-check">
                        {{ Form::radio('busn_tax_incentive_enjoy', '0', true, ['id' => 'no_radio_en', 'class' => 'form-check-input']) }}
                        <label class="form-check-label" for="no_radio_en">No</label>
                    </div>
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="item-layer">
        <h4 class="text-header">Building Declaration</h4>    
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
            <div class="col-sm-2">
                <div class="form-group m-form__group d-flex align-items-center" style="margin-top: 20px;"> 
                    <label class="fs-6 fw-bold me-3">Owned?<span class="text-danger">*</span></label>
                    <div class="form-check me-3">
                        {{ Form::radio('busn_bldg_is_owned', '1', false, ['id' => 'yes_radio', 'class' => 'form-check-input']) }}
                        <label class="form-check-label" for="yes_radio">Yes</label>
                    </div>
                    <div class="form-check">
                        {{ Form::radio('busn_bldg_is_owned', '0', true, ['id' => 'no_radio', 'class' => 'form-check-input']) }}
                        <label class="form-check-label" for="no_radio">No</label>
                    </div>
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group m-form__group">
                    {{ Form::label('busn_bldg_tax_declaration_no', 'Building Tax Declaration No.', ['class' => 'fs-6 fw-bold']) }}
                    <div class="form-icon-user d-flex align-items-center" id="parent_rp_code">
                        <div class="flex-grow-1" id="text_rp_code">
                            {{ 
                                Form::hidden($name = 'busn_bldg_tax_declaration_no', $value = '', 
                                $attributes = array(
                                    'id' => 'busn_bldg_tax_declaration_no',
                                    'class' => 'form-control form-control-solid'
                                )) 
                            }}
                            {{ 
                                Form::hidden($name = 'rp_property_code', $value = '', 
                                $attributes = array(
                                    'id' => 'rp_property_code',
                                    'class' => 'form-control form-control-solid'
                                )) 
                            }}
                            {{
                                Form::select('rp_code', $rpt_property, $value = '', ['id' => 'rp_code', 'class' => 'form-control select3', 'data-placeholder' => 'Please select'])
                            }}                       
                        </div>
                        <div class="ms-2" id="info_btn">
                            <button type="button" style="float: right;font-size: 9px;padding: 7px 9px 5px 10px;" class="btn  btn-primary" id="btnOrderofPayment"><i class="ti-info-alt text-white"></i></button>
                        </div>
                    </div>
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
            <div class="col-sm-3">
                <div class="form-group m-form__group">
                    {{ Form::label('floor_val_id', 'Building Floor No.', ['class' => 'fs-6 fw-bold']) }}
                    {{
                        Form::select('floor_val_id[]', $floor_val, $value = '', ['id' => 'floor_val_id', 'class' => 'form-control select3', 'multiple' => 'multiple', 'data-placeholder' => 'select'])
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
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
            <div class="col-sm-6">
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
            </div>
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
        <div class="row">
              <!---geo location links start-->   
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header text-header" id="flush-headingseven">
                        <button class="accordion-button collapsed" style="background: #20B7CC;" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseseeven" aria-expanded="false" aria-controls="flush-headingseven">
                            <h6 class="sub-title accordiantitle" style="padding-top: 5px;">
                            {{__("BUILDING MAP LOCATION")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapseseeven" class="accordion-collapse collapse" aria-labelledby="flush-headingseven" data-bs-parent="#accordionFlushExample7">
                    <div class="row">
                        
                        <div class="col-md-12">
                            <span class="validate-err" id="err_locationlink"></span>
                        </div>
                            <div class="col-md-12">
                                <div class="row field-requirement-details-status" style="background: #20B7CC; margin: 2px;">
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        {{ Form::label('id', __('NO.'), ['class' => 'form-label', 'style' => 'color: white;padding-top: 10px;']) }}
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                        {{ Form::label('link', __('LINK DESCRIPTION'), ['class' => 'form-label', 'style' => 'color: white;padding-top: 10px;']) }}
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        {{ Form::label('link', __('REMARKS'), ['class' => 'form-label', 'style' => 'color: white;padding-top: 10px;']) }}
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        {{ Form::label('action', __('ACTION'), ['class' => 'form-label', 'style' => 'color: white;padding-top: 10px;']) }}
                                    </div>
                                </div>
                                <span class="geolocationDetails activity-details" id="geolocationDetails" style="margin: 1px;">
                                    
                                </span>
                            </div>        
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-12">
                            <a href="#"  data-size="xl" data-url="{{ url('/rptproperty/savegeolocationdata') }}" data-for="L"  id="savelocations" data-bs-toggle="tooltip" title="{{__('Search')}}" class="btn btn-sm btn-primary savelocations" style="margin-top: 8px;">
                                        Apply Changes</a>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            <!---geo location links end--> 
        </div>    
    </div>
    <div class="item-layer">
        <h4 class="text-header">Business Location Address</h4>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group m-form__group">
                    {{ Form::checkbox($name = 'busn_office_is_same_as_main', $value = '1', $checked = "", $attributes = array(
                        'id' => 'busn_office_is_same_as_main',
                        'class' => 'form-check-input same_as_address'
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
                    <div class="form-icon-user d-flex align-items-center" id="parent_busn_office_barangay_id">
                        <div class="flex-grow-1">
                            {{
                                Form::select('busn_office_barangay_id', $barangay, $value = '', ['id' => 'busn_office_barangay_id', 'class' => 'form-control select3', 'data-placeholder' => 'Please select'])
                            }}                        
                        </div>
                        <!-- <div class="ms-2">
                            <a href="javascript:;" class="action-btn refresh-barangay bg-warning btn m-1 btn-sm align-items-center" title="Refresh">
                                <i class="ti-reload"></i>
                            </a>
                        </div> -->
                        <div class="ms-2">
                            <a href="{{ route('barangay.index') }}" target="_blank" title="{{ __('Add Barangay') }}" class="btn btn-sm btn-primary"><i class="ti-plus"></i></a>
                        </div>
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
                            'class' => 'form-control form-control-solid'
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
                            'class' => 'form-control form-control-solid'
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
                            'class' => 'form-control form-control-solid'
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
                            'class' => 'form-control form-control-solid'
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
                            'class' => 'form-control form-control-solid'
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
                            'class' => 'form-control form-control-solid'
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
<div class="modal fade" id="orderofpaymentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="
        position: relative;
            display: flex;
            flex-direction: column;
            width: 80%;
            pointer-events: auto;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            outline: 0;
           float: left;
           margin-left: 50%;
           margin-top: 50%;
           transform: translate(-50%, -50%);">
           <div class="modal-header">

                <h4 class="modal-title">Online Application Reference</h4>
                <button type="button" class="btn-close" onclick="closeModal();" aria-label="Close"></button>
           </div>
            <div class="container">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                {{ Form::label('online_busn_bldg_tax_declaration_no', __('Building Tax Declaration'), ['class'=>'form-label']) }}
                                <div class="input-group">
                                    {{ Form::text('online_busn_bldg_tax_declaration_no', '', ['class'=>'form-control', 'id'=>'online_busn_bldg_tax_declaration_no', 'readonly'=>true])}}  
                                   
                                        <!-- <button type="button" class="btn btn-primery" onclick="copyTaxDeclaration()">
                                            <i class="ti-magnet"></i>
                                        </button> -->
                                        <div class="ms-2">
                                            <button type="button" style="float: right;font-size: 9px;padding: 7px 9px 5px 10px;" class="btn  btn-primary" onclick="copyTaxDeclaration()"><i class="ti-magnet text-white"></i></button>
                                        </div>
                                    
                                </div>
                                <span class="validate-err" id=""></span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                {{ Form::label('online_busn_bldg_property_index_no', __('Property Index Number(PIN)'), ['class'=>'form-label']) }}
                                <div class="form-icon-user">
                                    {{ Form::text('online_busn_bldg_property_index_no', '', ['class'=>'form-control', 'id'=>'online_busn_bldg_property_index_no', 'readonly'=>true])}}  
                                </div>
                                <span class="validate-err" id=""></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
        $(document).ready(function(){
            $("#btnOrderofPayment").click(function(){
                 $("#orderofpaymentModal").modal('show');
            });
            $(".closeOrderModal").click(function(){
                 $("#orderofpaymentModal").modal('hide');
            });
        });
        function copyTaxDeclaration() {
            var taxDeclarationField = document.getElementById('online_busn_bldg_tax_declaration_no');
            taxDeclarationField.select();
            document.execCommand('copy');
        }
        function closeModal() {
            $('#orderofpaymentModal').modal('hide');
        }
    </script>
