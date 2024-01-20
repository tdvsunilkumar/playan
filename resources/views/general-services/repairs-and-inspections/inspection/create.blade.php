<div class="modal form fade" id="repair-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="repairModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'general-services/repairs-and-inspections/inspection', 'class'=>'formDtls needs-validation', 'name' => 'repairForm')) }}
            @csrf
                <div class="modal-header bg-accent pt-4 pb-4">
                    <h5 class="modal-title full-width c-white" id="repairModal">
                        Manage Pre-Repair Inspection Request
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body p-4">
                    <div class="wrapper">
                        <h4 class="text-header accordion-button mt-0 mb-0" data-bs-toggle="collapse" data-bs-target="#collapseDetails" aria-expanded="true" aria-controls="collapseDetails">Pre-Repair Inspection Request Details</h4>
                        <div class="collapse show mt-3" id="collapseDetails">
                            <div class="row pad-start pad-end">
                                <div class="col-sm-4">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('property_id', 'Fixed Asset No.', ['class' => '']) }}
                                        {{
                                            Form::select('property_id', $fixed_assets, $value = '', ['id' => 'property_id', 'class' => 'form-control select3 form-control-solid', 'data-placeholder' => 'select a fixed asset', 'disabled' => 'disabled'])
                                        }}  
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('requested_by', 'Requested By', ['class' => '']) }}
                                        {{
                                            Form::select('requested_by', $employees, $value = '', ['id' => 'requested_by', 'class' => 'form-control select3 form-control-solid', 'data-placeholder' => 'select a requestor', 'disabled' => 'disabled'])
                                        }}  
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('requested_date', 'Date Requested', ['class' => 'required fs-6 fw-bold']) }}
                                        {{ 
                                            Form::date($name = 'requested_date', $value = '', 
                                            $attributes = array(
                                                'id' => 'requested_date',
                                                'class' => 'form-control form-control-solid',
                                                'disabled' => 'disabled'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row pad-start pad-end">
                                <div class="col-sm-4">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('type', 'Type', ['class' => 'required fs-6 fw-bold']) }}
                                        {{ 
                                            Form::text($name = 'type', $value = '', 
                                            $attributes = array(
                                                'id' => 'type',
                                                'class' => 'form-control form-control-solid disable',
                                                'disabled' => 'disabled'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('model', 'Brand/Model', ['class' => 'required fs-6 fw-bold']) }}
                                        {{ 
                                            Form::text($name = 'model', $value = '', 
                                            $attributes = array(
                                                'id' => 'model',
                                                'class' => 'form-control form-control-solid disable',
                                                'disabled' => 'disabled'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('engine_no', 'Engine No.', ['class' => 'required fs-6 fw-bold']) }}
                                        {{ 
                                            Form::text($name = 'engine_no', $value = '', 
                                            $attributes = array(
                                                'id' => 'engine_no',
                                                'class' => 'form-control form-control-solid disable',
                                                'disabled' => 'disabled'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row pad-start pad-end">
                                <div class="col-sm-4">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('plate_no', 'Plate No.', ['class' => 'required fs-6 fw-bold']) }}
                                        {{ 
                                            Form::text($name = 'plate_no', $value = '', 
                                            $attributes = array(
                                                'id' => 'plate_no',
                                                'class' => 'form-control form-control-solid disable',
                                                'disabled' => 'disabled'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('received_date', 'Acquisition Date', ['class' => 'required fs-6 fw-bold']) }}
                                        {{ 
                                            Form::date($name = 'received_date', $value = '', 
                                            $attributes = array(
                                                'id' => 'received_date',
                                                'class' => 'form-control form-control-solid disable',
                                                'disabled' => 'disabled'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('unit_cost', 'Acquisition Cost', ['class' => 'required fs-6 fw-bold']) }}
                                        {{ 
                                            Form::text($name = 'unit_cost', $value = '', 
                                            $attributes = array(
                                                'id' => 'unit_cost',
                                                'class' => 'form-control form-control-solid strong text-danger disable',
                                                'disabled' => 'disabled'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row pad-start pad-end">
                                <div class="col-sm-12">
                                    <div class="form-group m-form__group">
                                        {{ Form::label('issues', 'Issues / Concerns', ['class' => 'fs-6 fw-bold']) }}
                                        {{ 
                                            Form::textarea($name = 'issues', $value = '', 
                                            $attributes = array(
                                                'id' => 'issues',
                                                'class' => 'form-control form-control-solid',
                                                'rows' => 3,
                                                'disabled' => 'disabled'
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 wrapper">
                        <h4 class="text-header accordion-button mt-0 mb-0" data-bs-toggle="collapse" data-bs-target="#collapseHistory" aria-expanded="true" aria-controls="collapseHistory">Repair History Details</h4>
                        <div class="collapse show mt-3" id="collapseHistory">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="datatable-3">
                                        <div class="table-responsive">
                                            <table id="repairHistoryTable" class="display dataTable table w-100 table-striped" aria-describedby="repairHistoryInfo">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('ID') }}</th>
                                                        <th>{{ __('DATE REQUESTED') }}</th>
                                                        <th class="sliced">{{ __('CONCERNS') }}</th>
                                                        <th class="sliced">{{ __('REMARKS') }}</th>
                                                        <th>{{ __('DATE ACCOMPLISHED') }}</th>
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
                    </div>
                    <div class="mt-3 wrapper">
                        <h4 class="text-header accordion-button mt-0 mb-0" data-bs-toggle="collapse" data-bs-target="#collapseInspection" aria-expanded="true" aria-controls="collapseHistory">Inspection Details</h4>
                        <div class="collapse show mt-3" id="collapseInspection">
                            <div class="row pad-start pad-end">
                                <div class="col-sm-12">
                                    <div class="form-group m-form__group required">
                                        {{ Form::label('inspected_remarks', 'Conclusion / Remarks', ['class' => 'fs-6 fw-bold']) }}
                                        {{ 
                                            Form::textarea($name = 'inspected_remarks', $value = '', 
                                            $attributes = array(
                                                'id' => 'inspected_remarks',
                                                'class' => 'form-control form-control-solid',
                                                'rows' => 3
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <h4 class="text-header mb-3">PARTS TO BE APPLIED OR REPLACED</h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="datatable-3">
                                        <div class="table-responsive">
                                            <table id="repairItemsTable" class="display dataTable table w-100 table-striped" aria-describedby="repairItemsInfo">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('ID') }}</th>
                                                        <th class="sliced">{{ __('ITEM DESCRIPTION') }}</th>
                                                        <th class="sliced">{{ __('REMARKS') }}</th>
                                                        <th>{{ __('QUANTITY') }}</th>
                                                        <th>{{ __('UOM') }}</th>
                                                        <th>{{ __('AMOUNT') }}</th>
                                                        <th>{{ __('TOTAL') }}</th>
                                                        <th>{{ __('ACTIONS') }}</th>
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn send-btn btn-primary">Send Request</button>
                </div>
            {{ Form::close() }}
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div id="modalToast" data-bs-autohide="true" class="toast hide bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-body text-white">
                    Hello, world! This is a toast message.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>