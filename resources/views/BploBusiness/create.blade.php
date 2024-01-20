{{ Form::hidden('loc_id',$locality->id, array('id' => 'loc_id')) }}
{{ Form::hidden('loc_code',$locality->loc_local_code, array('id' => 'loc_code')) }}
{{ Form::hidden('pm_id_ref',$pm_id, array('id' => 'pm_id_ref')) }}
{{ Form::hidden('app_code_ref',$app_code, array('id' => 'app_code_ref')) }}
{{ Form::hidden('busn_tax_year_ref',$busn_tax_year, array('id' => 'busn_tax_year_ref')) }}
{{ Form::hidden('busn_app_status',"0", array('id' => 'busn_app_status')) }}
{{ Form::hidden('tab',1, array('id' => 'tab')) }}

<link href="{{ asset('assets/js/yearpicker.css')}}" rel="stylesheet">

<div class="modal form fade" id="departmental-requisition-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="departmentalRequisitionLabel" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
    {{ Form::open(array('url' => 'business-permit/application', 'class'=>'formDtls', 'name' => 'appDetailsForm')) }}
    @csrf
        <div class="modal-content">
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="departmentalRequisitionLabel">Manage Application</h5>
            </div>
            <div class="modal-body">
                <div class="row">
               
                     <!-- RIGHT COLUMN DETAILS START -->
                    <div class="col-md-3 border-right  space-right" >
                        <!-- ITEM DETAILS START -->
                        {{ Form::open(array('url' => 'business-permit/application', 'class'=>'formDtls', 'name' => 'appDetailsForm')) }}
                        @csrf
                        <div id="datatable-3" class="dataTables_wrapper">
                            <div class="row">
                                <h4 class="text-header">Application Details</h4>

                                <div class="col-md-12">
                                    <div class="form-group m-form__group required">
                                        {{ Form::label('app_code', 'Application Type', ['class' => '']) }} 
                                        {{
                                            Form::select('app_code', $app_type, $value = '', ['id' => 'app_code', 'class' => 'form-control select3'])
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group m-form__group required"> 
                                        {{ Form::label('pm_id', 'Payment Mode', ['class' => '']) }} 
                                        {{
                                            Form::select('pm_id', $pay_mode, $value = '', ['id' => 'pm_id', 'class' => 'form-control'])
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group m-form__group required"> 
                                        {{ Form::label('busn_tax_year', 'Tax Year', ['class' => 'fs-6 fw-bold']) }}
                                        {{ 
                                            Form::text($name = 'busn_tax_year', $value = '', 
                                            $attributes = array(
                                                'id' => 'busn_tax_year',
                                                'class' => 'yearpicker form-control disabled-field',
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('loc_local_id', 'Locality', ['class' => 'fs-6 fw-bold']) }}
                                        {{ 
                                            Form::text($name = 'loc_local_id', $value = $locality->loc_local_code, 
                                            $attributes = array(
                                                'id' => 'loc_local_id',
                                                'class' => 'form-control form-control-solid',
                                                'readonly' => 'true'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('busns_id_no', 'Business Identification Number', ['class' => 'fs-6 fw-bold']) }}
                                        {{ 
                                            Form::text($name = 'busns_id_no', $value = '', 
                                            $attributes = array(
                                                'id' => 'busns_id_no',
                                                'class' => 'form-control form-control-solid',
                                                'readonly' => 'true'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group m-form__group">
                                        {{ 
                                            Form::text($name = 'locality_id', $value = $locality->id, 
                                            $attributes = array(
                                                'id' => 'locality_id',
                                                'class' => 'form-control form-control-solid hidden'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row hide" id="testingDetails">
                                <h4 class="text-header">For Testing Only</h4>
                                <div class="col-md-12">
                                    <div class="form-group m-form__group"> 
                                        {{ Form::hidden('test_busn_id',0, array('id' => 'test_busn_id')) }}
                                        {{ Form::label('test_tax_date', 'Tax Date', ['class' => 'fs-6 fw-bold']) }}
                                        {{ 
                                            Form::date($name = 'test_tax_date', $value = '', 
                                            $attributes = array(
                                                'id' => 'test_tax_date',
                                                'class' => 'form-control form-control-solid numeric-only'
                                            )) 
                                        }}
                                    </div>
                                </div>
                                 <div class="col-md-12">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('test_app_code', 'Application Type', ['class' => '']) }} 
                                        {{
                                            Form::select('test_app_code', $app_type, $value = '', ['id' => 'test_app_code', 'class' => 'form-control select3'])
                                        }}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group m-form__group"> 
                                        {{ Form::label('test_pm_id', 'Payment Mode', ['class' => '']) }} 
                                        {{
                                            Form::select('test_pm_id', $pay_mode, $value = '', ['id' => 'test_pm_id', 'class' => 'form-control disabled-field'])
                                        }}
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary JqSaveTestingData">Update</button>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }} 
                        <!-- ITEM DETAILS END -->
                    </div>
                   
                    <!-- RIGHT COLUMN DETAILS END -->
                    <!-- LEFT COLUMN DETAILS START -->
                    <div class="col-md-9 border-left space-left" style="padding-right: 30px;">
                        <ul class="nav nav-pills mb-3 d-flex justify-content-center" id="pills-tab" role="tablist">
                            <li class="nav-item" role="departmental-request">
                                <button class="nav-link active" id="request-details-tab" data-bs-toggle="pill" data-bs-target="#request-details" type="button" role="tab" aria-controls="request-details" aria-selected="true">1) Business Information and Registration </button>
                            </li>
                            <li class="nav-item" role="for-alob">
                                <button class="nav-link" id="alob-details-tab" data-bs-toggle="pill" data-bs-target="#alob-details" type="button" role="tab" aria-controls="alob-details" aria-selected="false">2) Business Operation</button>
                            </li>
                            <li class="nav-item" role="for-pr">
                                <button class="nav-link disabled" id="pr-details-tab" data-bs-toggle="pill" data-bs-target="#pr-details" type="button" role="tab" aria-controls="pr-details" aria-selected="false">3) Business Activity/Requirements</button>
                            </li>
                            <li class="nav-item" role="for-bidding">
                                <button class="nav-link disabled" id="bidding-details-tab" data-bs-toggle="pill" data-bs-target="#bidding-details" type="button" role="tab" aria-controls="bidding-details" aria-selected="false">4) Summary & Submission</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <!-- REQUEST DETAILS START -->
                            @include('BploBusiness.business_info') 
                            <!-- REQUEST DETAILS END -->
                            <!-- ALOB DETAILS START -->
                            @include('BploBusiness.business_operation') 
                            <!-- ALOB DETAILS END -->
                            <!-- ALOB DETAILS START -->
                            @include('BploBusiness.business_activity') 
                            <!-- ALOB DETAILS END -->
                            <!-- Summary START -->
                            @include('BploBusiness.summary') 
                            <!-- Summary END -->
                            <div class="tab-pane fade" id="abstract-details" role="tabpanel" aria-labelledby="abstract-details-tab">ABSTRACT</div>
                            <div class="tab-pane fade" id="resolution-details" role="tabpanel" aria-labelledby="resolution-details-tab">RESOLUTION</div>
                            <div class="tab-pane fade" id="po-details" role="tabpanel" aria-labelledby="po-details-tab">PO</div>
                        </div>
                    </div>
                    <!-- LEFT COLUMN DETAILS END -->
                   
                </div>
            </div>
            <div class="modal-footer" style="padding-right: 30px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn save-btn btn-primary">Save Draft</button>
                <button type="button" class="btn submit-btn bg-success btn-primary">Submit</button>
            </div>
        </div>
    {{ Form::close() }} 
    </div>
</div>

<script src="{{ asset('assets/js/yearpicker.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){   
      var yearpickerInput = $('input[name="busn_tax_year"]').val();
      $('.yearpicker').yearpicker();
      $('.yearpicker').val(yearpickerInput).trigger('change');
    });
</script>