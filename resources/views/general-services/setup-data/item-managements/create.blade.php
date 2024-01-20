<div class="modal form fade" id="item-modal" tabindex="-1" role="dialog" aria-labelledby="itemModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">            
            <div class="modal-header bg-accent pt-4 pb-4">
                <h5 class="modal-title full-width c-white" id="itemModal">
                    Manage Item
                    <span class="variables"></span>
                </h5>
            </div>
            <div class="modal-body p-4">
                {{ Form::open(array('url' => 'general-services/setup-data/item-managements', 'class'=>'formDtls needs-validation', 'name' => 'itemForm')) }}
                @csrf
                <div class="fv-row row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('item_category_id', 'Item Category', ['class' => '']) }}
                            {{
                                Form::select('item_category_id', $item_categories, $value = '', ['id' => 'item_category_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a category'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('gl_account_id', 'GL Account', ['class' => '']) }}
                            {{
                                Form::select('gl_account_id', $gl_accounts, $value = '', ['id' => 'gl_account_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a gl account', 'disabled' => 'disabled'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="fv-row row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('item_type_id', 'Item Type', ['class' => '']) }}
                            {{
                                Form::select('item_type_id', $item_types, $value = '', ['id' => 'item_type_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a type'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('purchase_type_id', 'Purchase Type', ['class' => '']) }}
                            {{
                                Form::select('purchase_type_id', $pur_types, $value = '', ['id' => 'purchase_type_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a purchase type'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="fv-row row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group">
                            {{ Form::label('code', 'Code', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'code', $value = '', 
                                $attributes = array(
                                    'id' => 'code',
                                    'class' => 'form-control form-control-solid text-uppercase',
                                    'disabled' => 'disabled'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('uom_id', 'Unit of Measurement', ['class' => '']) }}
                            {{
                                Form::select('uom_id', $unit_of_measurements, $value = '', ['id' => 'uom_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a uom'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="fv-row row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('name', 'Name', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'name', $value = '', 
                                $attributes = array(
                                    'id' => 'name',
                                    'class' => 'form-control form-control-solid'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group">
                            {{ Form::label('description', 'Description', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'description', $value = '', 
                                $attributes = array(
                                    'id' => 'description',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="fv-row row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('weighted_cost', 'Init. Weighted Cost', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'weighted_cost', $value = '', 
                                $attributes = array(
                                    'id' => 'weighted_cost',
                                    'class' => 'form-control form-control-solid numeric-double'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('latest_cost', 'Init. Latest Cost', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'latest_cost', $value = '', 
                                $attributes = array(
                                    'id' => 'latest_cost',
                                    'class' => 'form-control form-control-solid numeric-double',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="fv-row row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group">
                            {{ Form::label('minimum_order_quantity', 'Minimum Order Quantity', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'minimum_order_quantity', $value = '', 
                                $attributes = array(
                                    'id' => 'minimum_order_quantity',
                                    'class' => 'form-control form-control-solid numeric-double'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group">
                            {{ Form::label('life_span', 'Life Span(No. of Months)', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'life_span', $value = '', 
                                $attributes = array(
                                    'id' => 'life_span',
                                    'class' => 'form-control form-control-solid numeric-only',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="fv-row row">
                    <div class="col-sm-12">
                        <div class="form-group m-form__group">
                            <label for="remarks" class="fs-6 fw-bold w-100">
                                Remarks
                                <div class="form-check form-switch float-end">
                                    <input class="form-check-input" type="checkbox" name="is_expirable" id="is_expirable" disabled="disabled">
                                    <label class="fs-6 form-check-label" for="is_expirable">is expirable?</label>
                                </div>
                            </label>
                            {{ 
                                Form::textarea($name = 'remarks', $value = '', 
                                $attributes = array(
                                    'id' => 'remarks',
                                    'class' => 'form-control form-control-solid',
                                    'rows' => 2
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
                <div class="fv-row row hidden upload-row">
                    <div class="col-sm-12">
                        <h4 class="text-header mb-3">Item Conversion Information</h4>
                    </div>
                    <div class="col-sm-12">
                        <div id="datatable-2" class="dataTables_wrapper">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="itemConversionTable" class="display dataTable table w-100 table-striped" aria-describedby="itemConversionInfo">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('ID') }}</th>
                                                    <th>{{ __('BASED QTY') }}</th>
                                                    <th>{{ __('BASED UOM') }}</th>
                                                    <th>{{ __('CONVERSION QTY') }}</th>
                                                    <th>{{ __('CONVERSION UOM') }}</th>
                                                    <th>{{ __('LAST MODIFIED') }}</th>
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
                    <div class="col-sm-12">
                        <h4 class="text-header mt-3 mb-3">Upload Information</h4>
                    </div>
                    <div class="col-sm-12">
                        {{ Form::open(array('url' => 'general-services/setup-data/item-managements?category=items', 'class'=>'dropzone dz-clickable', 'name' => 'itemUploadForm', 'id' => 'import-item-dropzone')) }}
                            <div class="dz-default dz-message"><span>Drop files here to upload</span></div>
                        {{ Form::close() }}
                    </div>
                    <div class="col-sm-12">
                        <div id="datatable-2" class="dataTables_wrapper mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="itemUploadTable" class="display dataTable table w-100 table-striped" aria-describedby="itemUploadInfo">
                                            <thead>
                                                <tr>
                                                    <th class="sliced">{{ __('FILENAME') }}</th>
                                                    <th>{{ __('TYPE') }}</th>
                                                    <th>{{ __('SIZE') }}</th>
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
                <button type="button" class="btn submit-btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>