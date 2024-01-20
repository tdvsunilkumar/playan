<div class="modal form fade" id="fixed-asset-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="fixedAssetModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'accounting/fixed-assets', 'class'=>'formDtls needs-validation', 'name' => 'fixedAssetForm')) }}
            @csrf
                <div class="modal-header bg-accent pt-4 pb-4">
                    <h5 class="modal-title full-width c-white" id="fixedAssetModal">
                        Manage Fixed Asset
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body p-0 p-4">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('received_by', 'Received By', ['class' => '']) }}
                                {{
                                    Form::select('received_by', $employees, $value = '', ['id' => 'received_by', 'class' => 'form-control select3 fs-3 require', 'data-placeholder' => 'select a receiver'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('issued_by', 'Issued By', ['class' => '']) }}
                                {{
                                    Form::select('issued_by', $employees, $value = '', ['id' => 'issued_by', 'class' => 'form-control select3 fs-3 require', 'data-placeholder' => 'select an issuer'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('fixed_asset_no', 'FA No', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'fixed_asset_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'fixed_asset_no',
                                        'class' => 'form-control form-control-solid strong disable',
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
                                {{ Form::label('property_category_id', 'Category', ['class' => '']) }}
                                {{
                                    Form::select('property_category_id', $categories, $value = '', ['id' => 'property_category_id', 'class' => 'form-control select3 fs-3 require', 'data-placeholder' => 'select a property category'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('gl_account_id', 'GL Account', ['class' => '']) }}
                                {{
                                    Form::select('gl_account_id', $gl_accounts, $value = '', ['id' => 'gl_account_id', 'class' => 'form-control select3 fs-3 require', 'data-placeholder' => 'select a gl account'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('item_id', 'Item Description', ['class' => '']) }}
                                {{
                                    Form::select('item_id', $items, $value = '', ['id' => 'item_id', 'class' => 'form-control select3 fs-3 require', 'data-placeholder' => 'select an item'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('property_type_id', 'Type', ['class' => '']) }}
                                {{
                                    Form::select('property_type_id', $property_types, $value = '', ['id' => 'property_type_id', 'class' => 'form-control select3 fs-3', 'data-placeholder' => 'select a property type'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('model', 'Model', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'model', $value = '', 
                                    $attributes = array(
                                        'id' => 'model',
                                        'class' => 'form-control form-control-solid'
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
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('mv_file_no', 'MV File No.', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'mv_file_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'mv_file_no',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('chasis_no', 'Chasis No.', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'chasis_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'chasis_no',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('plate_no', 'Plate No.', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'plate_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'plate_no',
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
                                {{ Form::label('received_date', 'Acquisition Date', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'received_date', $value = '', 
                                    $attributes = array(
                                        'id' => 'received_date',
                                        'class' => 'form-control form-control-solid strong require'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('unit_cost', 'Acquisition Cost', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'unit_cost', $value = '', 
                                    $attributes = array(
                                        'id' => 'unit_cost',
                                        'class' => 'form-control form-control-solid text-danger strong require',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('effectivity_date', 'Effectivity Date', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'effectivity_date', $value = '', 
                                    $attributes = array(
                                        'id' => 'effectivity_date',
                                        'class' => 'form-control form-control-solid strong require'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('estimated_life_span', 'Estimated Life Span', ['class' => '']) }}
                                {{
                                    Form::select('estimated_life_span', $life_spans, $value = '', ['id' => 'estimated_life_span', 'class' => 'form-control select3 fs-3 require', 'data-placeholder' => 'select an estimated life span'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group mb-0">
                                <label for="remarks" class="fs-6 fw-bold w-100">
                                    Particulars
                                    <div class="form-check form-switch float-end">
                                        <input class="form-check-input" type="checkbox" name="is_depreciative" id="is_depreciative">
                                        <label class="fs-6 form-check-label" for="is_depreciative">is depreciative?</label>
                                    </div>
                                </label>
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
                    <h4 class="text-header accordion-button mt-1" data-bs-toggle="collapse" data-bs-target="#collapseDepreciation" aria-expanded="true" aria-controls="collapseDepreciation">Depreciation Details</h4>
                    <div class="collapse show mt-3" id="collapseDepreciation">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group m-form__group">
                                    {{ Form::label('depreciation_type_id', 'Depreciation Type', ['class' => '']) }}
                                    {{
                                        Form::select('depreciation_type_id', $depreciation_types, $value = '', ['id' => 'depreciation_type_id', 'class' => 'form-control select3 fs-3 depreciation disable', 'data-placeholder' => 'select a depreciation type', 'disabled' => 'disabled'])
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group m-form__group">
                                    {{ Form::label('salvage_value', 'Salvage Value', ['class' => '']) }}
                                    {{
                                        Form::select('salvage_value', $salvage_values, $value = '', ['id' => 'salvage_value', 'class' => 'form-control select3 fs-3 depreciation disable', 'data-placeholder' => 'select a salvage value', 'disabled' => 'disabled'])
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group m-form__group">
                                    {{ Form::label('monthly_depreciation', 'Monthly Depreciation Value', ['class' => 'required fs-6 fw-bold']) }}
                                    {{ 
                                        Form::text($name = 'monthly_depreciation', $value = '', 
                                        $attributes = array(
                                            'id' => 'monthly_depreciation',
                                            'class' => 'form-control form-control-solid disable',
                                            'disabled' => 'disabled'
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-header accordion-button mt-1" data-bs-toggle="collapse" data-bs-target="#collapseHistory" aria-expanded="true" aria-controls="collapseHistory">History Details</h4>
                    <div class="collapse show mt-3" id="collapseHistory">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="datatable-3">
                                    <div class="table-responsive">
                                        <table id="fixedAssetHistoryTable" class="display dataTable table w-100 table-striped" aria-describedby="fixedAssetHistoryInfo">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('ID') }}</th>
                                                    <th>{{ __('ACQUIRED DATE') }}</th>
                                                    <th>{{ __('ACQUIRED BY') }}</th>
                                                    <th>{{ __('ISSUED BY') }}</th>
                                                    <th>{{ __('RETURNED DATE') }}</th>
                                                    <th>{{ __('RETURNED BY') }}</th>
                                                    <th>{{ __('RECEIVED BY') }}</th>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn submit-btn btn-primary"><i class="la la-save align-middle"></i> Save Changes</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>