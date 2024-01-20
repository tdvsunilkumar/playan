<div class="modal form fade" id="item-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="itemModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">            
            <div class="modal-header bg-accent p-0 p-4">
                <h5 class="modal-title full-width c-white" id="itemModal">
                    Manage Item Inventory
                    <span class="variables"></span>
                </h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="text-header mb-3">Item Details</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('item_category_id', 'Item Category', ['class' => 'w-100 fs-5']) }}
                            {{ Form::label('value', 'Item Category here', ['class' => 'w-100 fs-6 text-secondary']) }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('item_type_id', 'Item Type', ['class' => 'w-100 fs-5']) }}
                            {{ Form::label('value', 'Item Type here', ['class' => 'w-100 fs-6 text-secondary']) }}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('gl_account_id', 'GL Account', ['class' => 'w-100 fs-5']) }}
                            {{ Form::label('value', 'GL Account here', ['class' => 'w-100 fs-6 text-secondary']) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('item_code', 'Code', ['class' => 'w-100 fs-5']) }}
                            {{ Form::label('value', 'Item Code here', ['class' => 'w-100 fs-6 text-primary']) }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('item_name', 'Name', ['class' => 'w-100 fs-5']) }}
                            {{ Form::label('value', 'Item Name here', ['class' => 'w-100 fs-6 text-secondary']) }}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('item_desc', 'Description', ['class' => 'w-100 fs-5']) }}
                            {{ Form::label('value', 'Item Description here', ['class' => 'w-100 fs-6 text-secondary']) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('uom', 'Unit Of Measurement', ['class' => 'w-100 fs-5']) }}
                            {{ Form::label('value', 'Unit Of Measurement here', ['class' => 'w-100 fs-6 text-secondary']) }}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('unit_cost', 'Unit Cost', ['class' => 'w-100 fs-5']) }}
                            {{ Form::label('value', 'Weighted Cost: 25.00 / Latest Cost: 30.00', ['class' => 'w-100 fs-6 text-secondary']) }}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('quantity', 'Quantity On-Hand', ['class' => 'w-100 fs-5']) }}
                            {{ Form::label('value', 'Inventory Qty: 25 / Reserved Qty: 30', ['class' => 'w-100 fs-6 text-secondary']) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="text-header mb-3">Item History</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div id="filters-scroll" class="position-relative" style="width: 100%;">
                            <div id="datatable-2" class="dataTables_wrapper">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="itemHistoryTable" class="display dataTable table w-100 table-striped" aria-describedby="itemHistoryInfo">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('TRANS') }}</th>
                                                        <th>{{ __('DATE/TIME') }}</th>
                                                        <th class="sliced">{{ __('ISSUED BY') }}</th>
                                                        <th class="sliced">{{ __('RECEIVED BY') }}</th>
                                                        <th>{{ __('BASED FROM') }}</th>
                                                        <th>{{ __('BASED QTY') }}</th>
                                                        <th>{{ __('POSTED QTY') }}</th>
                                                        <th>{{ __('BALANCED QTY') }}</th>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>