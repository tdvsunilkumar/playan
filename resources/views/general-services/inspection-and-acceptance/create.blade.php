<div class="modal form fade" id="purchase-order-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="bacRFQLabel" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            {{ Form::open(array('url' => 'general-services/purchase-orders', 'class' => '', 'name' => 'purchaseOrderForm')) }}
            @csrf
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="bacRFQLabel">Manage Resolution</h5>
            </div>
            <div class="modal-body">
                <h4 class="text-header mb-3">Purchase Orders Information</h4>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('rfq_id', 'Control No.', ['class' => '']) }}
                            {{
                                Form::select('rfq_id', $rfqs, $value = '', ['id' => 'rfq_id', 'class' => 'form-control select3', 'data-placeholder' => 'select control no', 'disabled' => 'disabled'])
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
                        <div class="form-group m-form__group">
                            {{ Form::label('purchase_order_type_id', 'Purchase Order Type', ['class' => '']) }}
                            {{
                                Form::select('purchase_order_type_id', $po_types, $value = '', ['id' => 'purchase_order_type_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a po type', 'disabled' => 'disabled'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('purchase_order_date', 'PO Date', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::date($name = 'purchase_order_date', $value = '', 
                                $attributes = array(
                                    'id' => 'purchase_order_date',
                                    'class' => 'form-control form-control-solid',
                                    'disabled' => 'disabled'
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
                        <div class="form-group m-form__group">
                            {{ Form::label('procurement_mode_id', 'Procurement Mode', ['class' => '']) }}
                            {{
                                Form::select('procurement_mode_id', $modes, $value = '', ['id' => 'procurement_mode_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a procurement mode', 'disabled' => 'disabled'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('payment_term_id', 'Payment Term', ['class' => '']) }}
                            {{
                                Form::select('payment_term_id', $payment_terms, $value = '', ['id' => 'payment_term_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a payment term', 'disabled' => 'disabled'])
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
                        <div class="form-group m-form__group">
                            {{ Form::label('delivery_place', 'Delivery Place', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'delivery_place', $value = '', 
                                $attributes = array(
                                    'id' => 'delivery_place',
                                    'class' => 'form-control form-control-solid',
                                    'disabled' => 'disabled'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('committed_date', 'Commited Date', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::date($name = 'committed_date', $value = '', 
                                $attributes = array(
                                    'id' => 'committed_date',
                                    'class' => 'form-control form-control-solid',
                                    'disabled' => 'disabled'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('delivery_term_id', 'Delivery Term', ['class' => '']) }}
                            {{
                                Form::select('delivery_term_id', $delivery_terms, $value = '', ['id' => 'delivery_term_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a delivery term', 'disabled' => 'disabled'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-form__group">
                            {{ Form::label('remarks', 'Remarks', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::textarea($name = 'remarks', $value = '', 
                                $attributes = array(
                                    'id' => 'remarks',
                                    'class' => 'form-control form-control-solid',
                                    'rows' => 3,
                                    'disabled' => 'disabled'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
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
                                                    <th>{{ __('Item Code') }}</th>
                                                    <th>{{ __('Item Description') }}</th>
                                                    <th>{{ __('Quantity Purchased') }}</th>
                                                    <th>{{ __('Quantity Posted') }}</th>
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
                                                    <th colspan="6" class="text-end fs-5">{{ __('TOTAL AMOUNT') }}</th>
                                                    <th colspan="1" class="text-start text-danger fs-5">{{ __('0.00') }}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h4 class="text-header mb-3">Posting Information</h4>
                        <div id="datatable-2" class="dataTables_wrapper">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="postingTable" class="display dataTable table w-100 table-striped" aria-describedby="postingTableInfo">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('IAR NO.') }}</th>
                                                    <th>{{ __('Reference') }}</th>
                                                    <th>{{ __('Inspected By') }}</th>
                                                    <th>{{ __('Received By') }}</th>
                                                    <th>{{ __('Item Posted') }}</th>
                                                    <th>{{ __('Actions') }}</th>
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
                <button type="button" class="btn btn-secondary aling-middle d-flex justify-content-center" data-bs-dismiss="modal">Close</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>