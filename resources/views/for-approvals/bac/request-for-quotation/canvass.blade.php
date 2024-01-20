<div class="modal form-inner fade" id="canvass-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="canvassLabel" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            {{ Form::open(array('url' => 'general-services/bac/request-for-quotations/canvass', 'class' => '', 'name' => 'canvassForm')) }}
            @csrf
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="canvassLabel">Item Canvass</h5>
            </div>
            <div class="modal-body p-0">
                <div class="row" style="margin-top: -1px">
                    <div class="col-sm-12">
                        <div id="datatable-result" class="table-responsive">
                            <table id="available-canvass-table" class="table table-striped m-0">
                                <thead>
                                    <tr>
                                        <th>Item Code</th>
                                        <th class="sliced">Item Description</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">UOM</th>
                                        <th class="text-center">Brand Model</th>
                                        <th class="text-center">Unit Cost</th>
                                        <th class="text-center">Total Cost</th>
                                        <th class="text-center">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-strong text-end">TOTAL AMOUNT</th>
                                        <th colspan="1" class="text-strong text-center text-danger">0</th>
                                        <th colspan="1" class="text-strong text-center"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('canvass_by', 'Canvass By', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'canvass_by', $value = '', 
                                    $attributes = array(
                                        'id' => 'canvass_by',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('canvass_date', 'Canvass Date', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'canvass_date', $value = '', 
                                    $attributes = array(
                                        'id' => 'canvass_date',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('contact_person', 'Contact Person', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'contact_person', $value = '', 
                                    $attributes = array(
                                        'id' => 'contact_person',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('contact_number', 'Contact No', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'contact_number', $value = '', 
                                    $attributes = array(
                                        'id' => 'contact_number',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('email_address', 'Contact Email', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'email_address', $value = '', 
                                    $attributes = array(
                                        'id' => 'email_address',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('delivery_period', 'Delivery Period', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'delivery_period', $value = '', 
                                    $attributes = array(
                                        'id' => 'delivery_period',
                                        'class' => 'form-control form-control-solid numeric-only',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-end">
                <button type="button" class="btn print-btn bg-print mw-200 hidden"><span>Print</span></button>
                <button type="button" class="btn submit-btn btn-info mw-200"><span>Submit</span></button>
                <button type="button" class="btn btn-secondary mw-200" data-bs-dismiss="modal"><span>Close</span></button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>