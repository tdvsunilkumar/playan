<div class="modal form fade" id="rfq-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="bacRFQLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            {{ Form::open(array('url' => 'general-services/bac/request-for-quotations', 'class' => '', 'name' => 'rfqForm')) }}
            @csrf
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="bacRFQLabel">Manage Request For Quotation</h5>
            </div>
            <div class="modal-body">
                <h4 class="text-header mb-3">Request For Quotation Information</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('fund_code_id', 'Fund Code', ['class' => 'fs-6 fw-bold']) }}
                            {{
                                Form::select('fund_code_id', $fund_codes, $value = '', ['id' => 'fund_code_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a fund code'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('purchase_type_id', 'Purchase Type', ['class' => 'fs-6 fw-bold']) }}
                            {{
                                Form::select('purchase_type_id', $purchase_types, $value = '', ['id' => 'purchase_type_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a purchase type'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-form__group required">
                            {{ Form::label('project_name', 'Project Name', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::textarea($name = 'project_name', $value = '', 
                                $attributes = array(
                                    'id' => 'project_name',
                                    'class' => 'form-control form-control-solid',
                                    'rows' => 3
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            {{ Form::label('control_no', 'Control No.: ', ['class' => 'required fs-5 fw-bold']) }}
                            {{ Form::label('control_no', '&nbsp; _ _ _ _ _ _ _ _ _ _ _ _ _ _', ['class' => 'required fs-5 fw-bold text-danger']) }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            {{ Form::label('total_budget', 'Approved Budget: ', ['class' => 'required fs-5 fw-bold']) }}
                            {{ Form::label('total_budget', '&nbsp; _ _ _ _ _ _ _ _ _ _ _ _ _ _', ['class' => 'required fs-5 fw-bold text-danger']) }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                    <h4 class="text-header mb-3">Purchase Requests Information</h4>
                        <div id="datatable-3" class="dataTables_wrapper">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="prTable" class="display dataTable table w-100 table-striped mt-4" aria-describedby="pr_info">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('PR No.') }}</th>
                                                    <th class="sliced">{{ __('DEPARTMENT / DIVISION') }}</th>
                                                    <th>{{ __('RFQ No.') }}</th>
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
                    <div class="col-md-6">
                        <h4 class="text-header mb-3">Suppliers Information</h4>
                        <div id="datatable-3" class="dataTables_wrapper">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="supplierTable" class="display dataTable table w-100 table-striped mt-4" aria-describedby="supplier_info">
                                            <thead>
                                                <tr>
                                                    <th class="sliced">{{ __('SUPPLIER') }}</th>
                                                    <th>{{ __('TOTAL') }}</th>
                                                    <th>{{ __('STATUS') }}</th>
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
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h4 class="text-header mb-3">Item Information</h4>
                        <div id="datatable-3" class="dataTables_wrapper">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="itemTable" class="display dataTable table w-100 table-striped mt-4" aria-describedby="item_info">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('ITEM ID') }}</th>
                                                    <th>{{ __('ITEM CODE') }}</th>
                                                    <th class="sliced">{{ __('DESCRIPTION') }}</th>
                                                    <th>{{ __('QUANTITY') }}</th>
                                                    <th>{{ __('UOM') }}</th>
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
                
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h4 class="text-header mb-3">Other Information</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group m-form__group required">
                                    {{ Form::label('deadline_date', 'Submission Deadline', ['class' => 'required fs-6 fw-bold']) }}
                                    {{ 
                                        Form::date($name = 'deadline_date', $value = '', 
                                        $attributes = array(
                                            'id' => 'deadline_date',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group m-form__group required">
                                    {{ Form::label('quotation_date', 'Quotation Begins', ['class' => 'required fs-6 fw-bold']) }}
                                    {{ 
                                        Form::date($name = 'quotation_date', $value = '', 
                                        $attributes = array(
                                            'id' => 'quotation_date',
                                            'class' => 'form-control form-control-solid',
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
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
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group m-form__group required">
                                    {{ Form::label('warranty_exp_id', 'Warranty - Expendable Supplies', ['class' => 'fs-6 fw-bold']) }}
                                    {{
                                        Form::select('warranty_exp_id', $warranty, $value = '', ['id' => 'warranty_exp_id', 'class' => 'form-control select3', 'data-placeholder' => 'select an expendable warranty'])
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group m-form__group required">
                                    {{ Form::label('warranty_non_exp_id', 'Warranty - Non Expendable Supplies', ['class' => 'fs-6 fw-bold']) }}
                                    {{
                                        Form::select('warranty_non_exp_id', $non_warranty, $value = '', ['id' => 'warranty_non_exp_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a non expendable warranty'])
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group m-form__group required">
                                    {{ Form::label('price_validaty_id', 'Price Validity', ['class' => 'fs-6 fw-bold']) }}
                                    {{
                                        Form::select('price_validaty_id', $price_validity, $value = '', ['id' => 'price_validaty_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a price validity'])
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary aling-middle d-flex justify-content-center" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn send-btn bg-info align-middle d-flex justify-content-center text-white">Send Request</button>
                <button type="button" class="btn print-btn bg-print align-middle d-flex justify-content-center hidden">Print</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>