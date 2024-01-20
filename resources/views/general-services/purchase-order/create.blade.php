<div class="modal form fade" id="purchase-order-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="bacRFQLabel" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            {{ Form::open(array('url' => 'general-services/purchase-orders', 'class' => '', 'name' => 'purchaseOrderForm')) }}
            @csrf
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="bacRFQLabel">Manage Purchase Order</h5>
            </div>
            <div class="modal-body">
                <h4 class="text-header mb-3">Purchase Orders Information</h4>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group m-form__group required">
                            {{ Form::label('rfq_id', 'Control No.', ['class' => '']) }}
                            {{
                                Form::select('rfq_id', $rfqs, $value = '', ['id' => 'rfq_id', 'class' => 'form-control select3', 'data-placeholder' => 'select control no'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('purchase_order_no', 'PO No.', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'purchase_order_no', $value = '', 
                                $attributes = array(
                                    'id' => 'purchase_order_no',
                                    'class' => 'form-control form-control-solid strong',
                                    'disabled' => 'disabled'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('supplier', 'Supplier', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'supplier', $value = '', 
                                $attributes = array(
                                    'id' => 'supplier',
                                    'class' => 'form-control form-control-solid',
                                    'disabled' => 'disabled'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group m-form__group required">
                            {{ Form::label('purchase_order_type_id', 'Purchase Order Type', ['class' => '']) }}
                            {{
                                Form::select('purchase_order_type_id', $po_types, $value = '', ['id' => 'purchase_order_type_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a po type'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group required">
                            {{ Form::label('purchase_order_date', 'PO Date', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::date($name = 'purchase_order_date', $value = '', 
                                $attributes = array(
                                    'id' => 'purchase_order_date',
                                    'class' => 'form-control form-control-solid'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('address', 'Address', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'address', $value = '', 
                                $attributes = array(
                                    'id' => 'address',
                                    'class' => 'form-control form-control-solid',
                                    'disabled' => 'disabled'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group m-form__group required">
                            {{ Form::label('procurement_mode_id', 'Procurement Mode', ['class' => '']) }}
                            {{
                                Form::select('procurement_mode_id', $modes, $value = '', ['id' => 'procurement_mode_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a procurement mode'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group required">
                            {{ Form::label('committed_date', 'Commited Date', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::date($name = 'committed_date', $value = '', 
                                $attributes = array(
                                    'id' => 'committed_date',
                                    'class' => 'form-control form-control-solid'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('project_name', 'Project Name', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'project_name', $value = '', 
                                $attributes = array(
                                    'id' => 'project_name',
                                    'class' => 'form-control form-control-solid',
                                    'disabled' => 'disabled'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group m-form__group required">
                            {{ Form::label('delivery_place', 'Delivery Place', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'delivery_place', $value = $localAddress, 
                                $attributes = array(
                                    'id' => 'delivery_place',
                                    'class' => 'form-control form-control-solid'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group required">
                            {{ Form::label('delivery_term_id', 'Delivery Term', ['class' => '']) }}
                            {{
                                Form::select('delivery_term_id', $delivery_terms, $value = '', ['id' => 'delivery_term_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a delivery term'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group required">
                            {{ Form::label('payment_term_id', 'Payment Term', ['class' => '']) }}
                            {{
                                Form::select('payment_term_id', $payment_terms, $value = '', ['id' => 'payment_term_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a payment term'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-form__group mb-0">
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
                <h4 class="text-header mt-2 mb-3">Other Information</h4>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('funding_by', 'Funding By', ['class' => '']) }}
                            {{
                                Form::select('funding_by', $funding_by, $value = '', ['id' => 'funding_by', 'class' => 'form-control select3', 'data-placeholder' => 'select a funding'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('approval_by', 'Approval By', ['class' => '']) }}
                            {{
                                Form::select('approval_by', $approval_by, $value = '', ['id' => 'approval_by', 'class' => 'form-control select3', 'data-placeholder' => 'select an approver'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <h4 class="text-header mb-3">Purchase Requests Information</h4>
                        <div id="datatable-2" class="dataTables_wrapper">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="prTable" class="display dataTable table w-100 table-striped" aria-describedby="prInfo">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('PR No.') }}</th>
                                                    <th>{{ __('Requesting Agency') }}</th>
                                                    <th>{{ __('ALOB No.') }}</th>
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
                <div class="row mt-4">
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
                                                    <th>{{ __('Item Code') }}</th>
                                                    <th>{{ __('Item Description') }}</th>
                                                    <th>{{ __('Quantity') }}</th>
                                                    <th>{{ __('Unit Cost') }}</th>
                                                    <th>{{ __('Total Cost') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="odd">
                                                    <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="5" class="text-end fs-5">{{ __('TOTAL AMOUNT') }}</th>
                                                    <th colspan="1" class="text-end text-danger fs-5">{{ __('0.00') }}</th>
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