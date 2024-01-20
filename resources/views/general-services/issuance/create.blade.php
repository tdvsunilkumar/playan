<div class="modal form fade" id="issuance-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="issuanceLabel" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            {{ Form::open(array('url' => 'general-services/issuances', 'class' => '', 'name' => 'issuanceForm')) }}
            @csrf
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="bacRFQLabel">Manage Issuance</h5>
            </div>
            <div class="modal-body">
                <h4 class="text-header mb-3">Issuance Information</h4>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group m-form__group">
                            {{ Form::label('control_no', 'Control No.', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'control_no', $value = '', 
                                $attributes = array(
                                    'id' => 'control_no',
                                    'class' => 'form-control form-control-solid strong',
                                    'disabled' => 'disabled'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('issued_by', 'Issued By', ['class' => '']) }}
                            {{
                                Form::select('issued_by', $requestor, $value = '', ['id' => 'issued_by', 'class' => 'form-control select3', 'data-placeholder' => 'select a issuer'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('issued_date', 'Issuance Date', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::date($name = 'issued_date', $value = '', 
                                $attributes = array(
                                    'id' => 'issued_date',
                                    'class' => 'form-control form-control-solid'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('received_by', 'Received By', ['class' => '']) }}
                            {{
                                Form::select('received_by', $requestor, $value = '', ['id' => 'received_by', 'class' => 'form-control select3', 'data-placeholder' => 'select a receiver'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('received_date', 'Received Date', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::date($name = 'received_date', $value = '', 
                                $attributes = array(
                                    'id' => 'received_date',
                                    'class' => 'form-control form-control-solid'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('requested_by', 'Requested By', ['class' => '']) }}
                            {{
                                Form::select('requested_by', $requestor, $value = '', ['id' => 'requested_by', 'class' => 'form-control select3', 'data-placeholder' => 'select a requestor'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('requested_date', 'Requested Date', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::date($name = 'requested_date', $value = '', 
                                $attributes = array(
                                    'id' => 'requested_date',
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
                            {{ Form::label('department', 'Department', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'department', $value = '', 
                                $attributes = array(
                                    'id' => 'department',
                                    'class' => 'form-control form-control-solid',
                                    'disabled' => 'disabled'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group">
                            {{ Form::label('designation', 'Designation', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'designation', $value = '', 
                                $attributes = array(
                                    'id' => 'designation',
                                    'class' => 'form-control form-control-solid',
                                    'disabled' => 'disabled'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group m-form__group">
                            {{ Form::label('purchase_order_id', 'PO No. / PR No.', ['class' => '']) }}
                            {{
                                Form::select('purchase_order_id[]', $pr_po, $value = '', 
                                    [
                                        'id' => 'purchase_order_id', 
                                        'class' => 'form-control select3', 
                                        'multiple' => 'multiple', 
                                        'data-placeholder' => 'select'
                                    ]
                                )
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group m-form__group required">
                            {{ Form::label('remarks', 'Remarks', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::textarea($name = 'remarks', $value = '', 
                                $attributes = array(
                                    'id' => 'remarks',
                                    'class' => 'form-control form-control-solid',
                                    'rows' => 3
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>  
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8 mt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" name="slip[]" type="checkbox" value="1" checked="checked">
                            <label class="form-check-label" for="inlineCheckbox1">Requisition & Issue Slip</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" name="slip[]" type="checkbox" value="2" checked="checked">
                            <label class="form-check-label" for="inlineCheckbox2">Inventory Custodian Slip</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" name="slip[]" type="checkbox" value="3" checked="checked">
                            <label class="form-check-label" for="inlineCheckbox2">Property Acknowledgement Receipt</label>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-2 text-end">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" name="inventory" type="checkbox" value="1">
                            <label class="form-check-label" for="inlineCheckbox1">Is Inventory Issuance?</label>
                        </div>
                        <button type="button" class="btn add-item-btn btn-primary">Add Item</button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <h4 class="text-header mb-3">Item Information</h4>
                        <div id="datatable-2" class="dataTables_wrapper">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="itemTable" class="display dataTable table w-100 table-striped" aria-describedby="prInfo">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('#') }}</th>
                                                    <th>{{ __('Category') }}</th>
                                                    <th>{{ __('Type') }}</th>
                                                    <th>{{ __('Item Description') }}</th>
                                                    <th>{{ __('Quantity') }}</th>
                                                    <th>{{ __('UOM') }}</th>
                                                    <th>{{ __('Unit Cost') }}</th>
                                                    <th>{{ __('Total Cost') }}</th>
                                                    <th>{{ __('Actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="odd">
                                                    <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="7" class="text-end fs-5">{{ __('TOTAL AMOUNT') }}</th>
                                                    <th colspan="1" class="text-end text-danger fs-5">{{ __('0.00') }}</th>
                                                    <th colspan="1"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary aling-middle d-flex justify-content-center" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn print-btn bg-print align-middle d-flex justify-content-center hidden">Print</button>
                <button type="button" class="btn send-btn bg-info align-middle d-flex justify-content-center text-white">Send Request</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>