<div class="tab-pane fade" id="pr-details" role="tabpanel" aria-labelledby="request-details-tab">

    <h4 class="text-header">Line of Business</h4>

        

    <div id="datatable-1" class="dataTables_wrapper">
        <!-- <div class="row">
            <div class="col-md-12">
                <div class="float-end">
                    <a href="#" id="add_bsn_plan" class="btn btn-sm btn-primary action-item retire-btn" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ti-plus" style="margin-right: 5px;"></i>Line of Business
                    </a>
                </div>
            </div>
        </div> -->
        <div class="row mt-4" style="display:none;" id="bsn_plan_div">
            {{ Form::open(array('url' => 'business-online-application', 'class'=>'formDtls', 'name' => 'addBusinessPlan')) }}
                @csrf
                {{ Form::hidden('plan_id',0, array('id' => 'plan_id')) }}
                        <div class="fv-row row">
                            <div class="col-sm-12" id="div_psic_subclass">
                                <div class="form-group m-form__group required">
                                    {{ Form::label('subclass_id', 'Select PSIC Subclass', ['class' => '']) }}
                                    {{
                                        Form::select('subclass_id', $psicSubclass, $value = '', ['id' => 'subclass_id', 'class' => 'form-control select3', 'data-placeholder' => 'Please select'])
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="fv-row row">
                            <div class="col-sm-6">
                                <div class="form-group m-form__group required">
                                    {{ Form::label('busp_no_units', 'Units', ['class' => 'fs-6 fw-bold']) }}
                                    <span class="text-danger">*</span>
                                    {{ 
                                        Form::text($name = 'busp_no_units', $value = '0', 
                                        $attributes = array(
                                            'id' => 'busp_no_units',
                                            'class' => 'form-control form-control-solid numeric-only'
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group m-form__group">
                                    {{ Form::label('busp_capital_investment', 'Capital Investment', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'busp_capital_investment', $value = '0.00', 
                                        $attributes = array(
                                            'id' => 'busp_capital_investment',
                                            'class' => 'form-control form-control-solid numeric-double min'
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="fv-row row">
                            <div class="col-sm-6">
                                <div class="form-group m-form__group">
                                    {{ Form::label('busp_essential', 'Essential', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'busp_essential', $value = '0.00', 
                                        $attributes = array(
                                            'id' => 'busp_essential',
                                            'class' => 'form-control form-control-solid numeric-double min'
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group m-form__group">
                                    {{ Form::label('busp_non_essential', 'Non-Essential', ['class' => 'fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'busp_non_essential', $value = '0.00', 
                                        $attributes = array(
                                            'id' => 'busp_non_essential',
                                            'class' => 'form-control form-control-solid numeric-double min' 
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn submit-btn-add-busn-plan btn-primary">Save</button>
            {{ Form::close() }}
        </div>
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="subClassTable" class="display dataTable table w-100 table-striped" aria-describedby="groupMenuInfo">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Code') }}</th>
                                                                <th>{{ __('Description') }}</th>
                                                                <th>{{ __('Units') }}</th>
                                                                <th>{{ __('Capital Investment') }}</th>
                                                                <th>{{ __('Essential') }}</th>
                                                                <th>{{ __('Non-essential') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="odd">
                                                                <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>                     
    </div>
    <div class="item-layer">
    <h4 class="text-header">Measure and Pax</h4>
        <div id="datatable-2" class="dataTables_wrapper">
            <!-- <div class="row">
                <div class="col-md-12">
                    <div class="float-end">
                        <a href="#" id="filter_box1" class="btn btn-sm btn-primary action-item retire-btn" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti-plus" style="margin-right: 5px;"></i> Measure and Pax
                        </a>
                    </div>
                </div>
            </div> -->
            <div class="row mt-4" style="display:none;" id="this_is_filter1">
                {{ Form::open(array('url' => 'business-online-application', 'class'=>'formDtls', 'name' => 'addMeasurePax')) }}
                    @csrf
                    {{ Form::hidden('id',0, array('id' => 'id')) }}
                            <div class="fv-row row">
                                <div class="col-sm-6">
                                    <div class="form-group m-form__group required">
                                        {{ Form::label('busn_psic_id', 'Line of Business', ['class' => '']) }}
                                        {{
                                            Form::select('busn_psic_id', $bplo_busn, $value = '', ['id' => 'busn_psic_id', 'class' => 'form-control select3', 'data-placeholder' => 'Please select'])
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-form__group required">
                                        {{ Form::label('buspx_charge_id', 'Measure and Pax', ['class' => '']) }}
                                        {{
                                            Form::select('buspx_charge_id', $measure_pax, $value = '', ['id' => 'buspx_charge_id', 'class' => 'form-control select3', 'data-placeholder' => 'Please select'])
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="fv-row row">
                                <div class="col-sm-6">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('buspx_no_units', 'Number of Units', ['class' => 'fs-6 fw-bold']) }}
                                        {{ 
                                            Form::text($name = 'buspx_no_units', $value = '0', 
                                            $attributes = array(
                                                'id' => 'buspx_no_units',
                                                'class' => 'form-control form-control-solid numeric-only'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('buspx_capacity', 'Capacity', ['class' => 'fs-6 fw-bold']) }}
                                        {{ 
                                            Form::text($name = 'buspx_capacity', $value = '0.00', 
                                            $attributes = array(
                                                'id' => 'buspx_capacity',
                                                'class' => 'form-control form-control-solid numeric-only'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn submit-btn-add-measure btn-primary">Save</button>
                {{ Form::close() }}
            </div>   
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="measurePaxTable" class="display dataTable table w-100 table-striped" aria-describedby="groupMenuInfo">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{ __('No. of Unit') }}</th>
                                                                    <th>{{ __('Capacity') }}</th>
                                                                    <th>{{ __('Measure and Pax') }}</th>
                                                                    <th>{{ __('Line of Business') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="odd">
                                                                    <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div> 
                                                                     
        </div>
    </div>                                    
    <div class="item-layer">
        <h4 class="text-header">Documentary Requirements</h4>
        
        <div id="datatable-3" class="dataTables_wrapper">
            <!-- <div class="row">
                <div class="col-md-12">
                    <div class="float-end">
                        <a href="#" id="add-doc" class="btn btn-sm btn-primary action-item retire-btn" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti-plus" style="margin-right: 5px;"></i> Add Document
                        </a>
                    </div>
                </div>
            </div> -->
            <div class="row hide mt-4" id="doc-form">
                {{ Form::open(array('url' => 'business-online-application','enctype'=>'multipart/form-data','class'=>'formDtls', 'name' => 'addReqDoc')) }}
                    @csrf
                            <div class="fv-row row">
                                <div class="col-sm-6">
                                    <div class="form-group m-form__group required">
                                        {{ Form::label('busn_psic_id', 'Line of Business', ['class' => '']) }}
                                        {{
                                            Form::select('busn_psic_id', $bplo_busn, $value = '', ['id' => 'busn_psic_id_req', 'class' => 'form-control select3', 'data-placeholder' => 'Please select'])
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-form__group required">
                                        {{ Form::label('req_rel_id', 'Requirment Name', ['class' => '']) }}
                                        {{
                                            Form::select('req_rel_id', $reqName, $value = '', ['id' => 'req_rel_id', 'class' => 'form-control select3', 'data-placeholder' => 'Please select'])
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="fv-row row">
                                <div class="col-sm-12">
                                    <div class="form-group m-form__group required">
                                    {{ Form::label('attachment', 'Select Document', ['class' => 'fs-6 fw-bold']) }}
                                        
                                        {{ Form::input('file','attachment','',array('class'=>'form-control','id' => 'attachment'))}}  

                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn submit-btn-add-doc btn-primary">Save</button>
                {{ Form::close() }}
            </div>   
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="reqDocTable" class="display dataTable table w-100 table-striped" aria-describedby="groupMenuInfo">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{ __('Line of Business') }}</th>
                                                                    <th>{{ __('Document Title') }}</th>
                                                                    <th>{{ __('Attachment') }}</th>
                                                                    <th>{{ __('Action') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="odd">
                                                                    <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
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

</div>
