
<div class="tab-pane fade show active" id="request-details" role="tabpanel" aria-labelledby="request-details-tab">
    {{ Form::open(array('url' => 'business-online-application', 'class'=>'formDtls', 'name' => 'requisitionForm')) }}
    @csrf
    <h4 class="text-header">Business Information and Registration</h4>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('busn_name', 'Business Name', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::text($name = 'busn_name', $value = '', 
                    $attributes = array(
                        'id' => 'busn_name',
                        'class' => 'form-control form-control-solid strong',
                        'readonly' => 'true'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('busn_trade_name', 'Trade Name/Franchise (if applicable)', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::text($name = 'busn_trade_name', $value = '', 
                    $attributes = array(
                        'id' => 'busn_trade_name',
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
            <div class="form-group m-form__group required">
                {{ Form::label('btype_id', 'Business Type', ['class' => '']) }}
                {{
                    Form::select('btype_id', $bsn_type, $value = '', ['id' => 'btype_id', 'class' => 'form-control select3', 'data-placeholder' => 'Please select','disabled' => 'disabled'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('busn_registration_no', 'DTI/SEC/CDA Registration No.', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::text($name = 'busn_registration_no', $value = '', 
                    $attributes = array(
                        'id' => 'busn_registration_no',
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
            <div class="form-group m-form__group required">
            {{ Form::label('busn_tin_no', 'Tax Identification Number(TIN)', ['class' => 'fs-6 fw-bold']) }}
            {{ 
                Form::text($name = 'busn_tin_no', $value = '', 
                $attributes = array(
                    'id' => 'busn_tin_no',
                    'class' => 'form-control form-control-solid',
                    'pattern' => '[0-9]{3}-[0-9]{3}-[0-9]{3}-[0-9]{3}',
                    'title' => 'Please enter the TIN in the format 000-000-000-000',
                    'readonly' => 'true'
                )) 
            }}
            <span id="tin_error" class="form__help text-danger" style="display: none;">Invalid TIN format. Please enter in the format 000-000-000-000.</span>
    
            </div>
        </div>
    </div>
    <div class="item-layer">
        <h4 class="text-header">Main Office Address</h4>
        <div class="row">
            <div class="col-sm-12" id="div_office_barangay">
                    <div class="form-group m-form__group required">
                        {{ Form::label('busn_office_main_barangay_id', 'Barangay|Municipality|Province|Region', ['class' => '']) }}
                        <div class="form-icon-user d-flex align-items-center">
                            <div class="flex-grow-1">
                                    {{
                                    Form::select('busn_office_main_barangay_id', $barangay, $value = '', ['id' => 'busn_office_main_barangay_id', 'class' => 'form-control select3', 'data-placeholder' => 'Please select','disabled' => 'disabled'])
                                }}                       
                            </div>
                            <!-- <div class="ms-2">
                                <a href="javascript:;" class="action-btn refresh-barangay-main bg-warning btn m-1 btn-sm align-items-center" title="Refresh">
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
                    {{ Form::label('busn_office_main_building_name', 'Name of Building', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_office_main_building_name', $value = '', 
                        $attributes = array(
                            'id' => 'busn_office_main_building_name',
                            'class' => 'form-control form-control-solid',
                            'readonly' => 'true'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('busn_office_main_building_no', 'House/Bldg. No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_office_main_building_no', $value = '', 
                        $attributes = array(
                            'id' => 'busn_office_main_building_no',
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
                    {{ Form::label('busn_office_main_add_block_no', 'Block No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_office_main_add_block_no', $value = '', 
                        $attributes = array(
                            'id' => 'busn_office_main_add_block_no',
                            'class' => 'form-control form-control-solid',
                            'readonly' => 'true'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('busn_office_main_add_lot_no', 'Lot No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_office_main_add_lot_no', $value = '', 
                        $attributes = array(
                            'id' => 'busn_office_main_add_lot_no',
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
                    {{ Form::label('busn_office_main_add_street_name', 'Street Name', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_office_main_add_street_name', $value = '', 
                        $attributes = array(
                            'id' => 'busn_office_main_add_street_name',
                            'class' => 'form-control form-control-solid',
                            'readonly' => 'true'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('busn_office_main_add_subdivision', 'Subdivision', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'busn_office_main_add_subdivision', $value = '', 
                        $attributes = array(
                            'id' => 'busn_office_main_add_subdivision',
                            'class' => 'form-control form-control-solid',
                            'readonly' => 'true'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="item-layer">
        <h4 class="text-header">Taxpayer Information</h4>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group m-form__group required">
                    {{ Form::label('client_id', "Taxpayer's Name", ['class' => '']) }}
                    
                    <div class="form-icon-user d-flex align-items-center">
                        <div class="flex-grow-1">
                            {{
                                Form::select('client_id', $client, $value = '', ['id' => 'client_id', 'class' => 'form-control select3', 'data-placeholder' => 'Please select','disabled' => 'disabled'])
                            }}                      
                        </div>
                        <!-- <div class="ms-2">
                            <a href="javascript:;" class="action-btn refresh-client bg-warning btn m-1 btn-sm align-items-center" title="Refresh">
                                <i class="ti-reload"></i>
                            </a>
                        </div>
                        <div class="ms-2">
                            <a href="{{ route('bploclients.index') }}" target="_blank" title="{{ __('Add Client') }}" class="btn btn-sm btn-primary"><i class="ti-plus"></i></a>
                        </div> -->
                    </div>
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('c_mobile_no', 'Mobile No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'c_mobile_no', $value = '', 
                        $attributes = array(
                            'id' => 'c_mobile_no',
                            'class' => 'form-control form-control-solid',
                            'readonly' => 'true'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('c_tel_no', 'Telephone No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'c_tel_no', $value = '', 
                        $attributes = array(
                            'id' => 'c_tel_no',
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
                    {{ Form::label('c_email_address', 'Email Address', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'c_email_address', $value = '', 
                        $attributes = array(
                            'id' => 'c_email_address',
                            'class' => 'form-control form-control-solid',
                            'readonly' => 'true'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('c_gender', 'Gender', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'c_gender', $value = '', 
                        $attributes = array(
                            'id' => 'c_gender',
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