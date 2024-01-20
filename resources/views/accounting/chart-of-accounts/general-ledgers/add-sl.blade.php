<div class="modal form-inner fade" id="subsidiary-ledger-account-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="canvassLabel" tabindex="-1">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'accounting/chart-of-accounts/general-ledgers/sl', 'class'=>'formDtls', 'name' => 'slAccountForm')) }}
            @csrf
                <div class="modal-header bg-accent pt-3 pb-3">
                    <h5 class="modal-title full-width c-white" id="subisidiaryLedgerModal">
                        Manage Subsidiary Ledger Account
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body" style="padding-bottom: 1.5rem !important;">
                    <div class="fv-row row hidden">
                        <div class="col-sm-12">
                            {{ Form::label('id', 'ID', ['class' => 'required fs-6 fw-bold mb-2']) }}
                            {{ 
                                Form::text($name = 'id', $value = '', 
                                $attributes = array(
                                    'id' => 'id',
                                    'class' => 'form-control form-control-solid',
                                    'disabled' => 'disabled'
                                )) 
                            }}
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('prefix', 'Prefix No.', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'prefix', $value = '', 
                                    $attributes = array(
                                        'id' => 'prefix',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('code', 'Code', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'code', $value = '', 
                                    $attributes = array(
                                        'id' => 'code',
                                        'class' => 'form-control form-control-solid',
                                        'disabled' => 'disabled'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required">
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
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group">
                                {{ Form::label('bank_id', 'Bank', ['class' => '']) }}
                                {{
                                    Form::select('bank_id', $banks, $value = '', ['id' => 'bank_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a bank'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-3">
                            <div class="form-group m-form__group mb-0">
                                {{ Form::label('is_parent', 'Is Parent?', ['class' => 'fs-6 fw-bold']) }}
                                <br/>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" name="is_parent" type="radio" value="1">
                                    {{ Form::label('is_parent', 'Yes', ['class' => 'form-check-label']) }}
                                </div>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" name="is_parent" type="radio" value="0" checked="checked">
                                    {{ Form::label('is_parent', 'No', ['class' => 'form-check-label']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group mb-0">
                                {{ Form::label('is_rpt_tax_cy', 'RPT: Current Year', ['class' => 'fs-6 fw-bold']) }}
                                <br/>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" name="is_rpt_tax_cy" type="radio" value="1">
                                    {{ Form::label('is_rpt_tax_cy', 'Yes', ['class' => 'form-check-label']) }}
                                </div>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" name="is_rpt_tax_cy" type="radio" value="0">
                                    {{ Form::label('is_rpt_tax_cy', 'No', ['class' => 'form-check-label']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group mb-0">
                                {{ Form::label('sl_parent_id', 'Parent', ['class' => '']) }}
                                {{
                                    Form::select('sl_parent_id', $parents, $value = '', ['id' => 'sl_parent_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a parent'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 current-row hidden">
                        <div class="col-md-12">
                            <h4 class="text-header mb-3">Receivable/Contra Information</h4>
                            <div id="datatable-2" class="dataTables_wrapper">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="currentTable" class="display dataTable table w-100 table-striped" aria-describedby="currentInfo">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('FUND CODE') }}</th>
                                                        <th>{{ __('GL ACCOUNT') }}</th>
                                                        <th>{{ __('SL ACCOUNT') }}</th>
                                                        <th>{{ __('IS DEBIT') }}</th>
                                                        <th>{{ __('LAST MODIFIED') }}</th>
                                                        <th>{{ __('STATUS') }}</th>
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
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn submit-btn btn-primary">Save Changes</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>