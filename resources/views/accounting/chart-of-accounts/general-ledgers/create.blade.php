<div class="modal form fade" id="general-ledger-account-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="generalAccountLedgerModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'accounting/chart-of-accounts/general-ledgers', 'class'=>'formDtls', 'name' => 'glAccountForm')) }}
            @csrf
                <div class="modal-header bg-accent pt-3 pb-3">
                    <h5 class="modal-title full-width c-white" id="generalAccountLedgerModal">
                        Manage General Ledger Account
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('acctg_account_group_id', 'Account Group', ['class' => '']) }}
                                {{
                                    Form::select('acctg_account_group_id', $account_groups, $value = '', ['id' => 'acctg_account_group_id', 'class' => 'form-control select3', 'data-placeholder' => 'select an account group'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('acctg_account_group_major_id', 'Major Group', ['class' => '']) }}
                                {{
                                    Form::select('acctg_account_group_major_id', $major_account_groups, $value = '', ['id' => 'acctg_account_group_major_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a major group'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('acctg_account_group_submajor_id', 'Sub-Major Group', ['class' => '']) }}
                                {{
                                    Form::select('acctg_account_group_submajor_id', $submajor_account_groups, $value = '', ['id' => 'acctg_account_group_submajor_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a sub-major group'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('acctg_fund_code_id', 'Fund Code', ['class' => '']) }}
                                {{
                                    Form::select('acctg_fund_code_id', $fund_codes, $value = '', ['id' => 'acctg_fund_code_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a fund code'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
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
                                {{ Form::label('normal_balance', 'Normal Balance', ['class' => '']) }}
                                {{
                                    Form::select('normal_balance', $normal_balance, $value = '', ['id' => 'normal_balance', 'class' => 'form-control select3', 'data-placeholder' => 'select a normal balance'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-6">
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
                        <div class="col-sm-6">
                            <div class="form-group m-form__group mb-0">
                                {{ Form::label('mother_code', 'Mother Code', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'mother_code', $value = '', 
                                    $attributes = array(
                                        'id' => 'mother_code',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group mb-0">
                                {{ Form::label('is_with_sl', 'Is with S/L Code?', ['class' => 'fs-6 fw-bold']) }}
                                <br/>
                                <div class="form-check form-check-inline mt-2">
                                    <input id="is_with_sl_yes" class="form-check-input" name="is_with_sl" type="radio" value="Yes">
                                    {{ Form::label('is_with_sl', 'Yes', ['class' => 'form-check-label']) }}
                                </div>
                                <div class="form-check form-check-inline mt-2">
                                    <input id="is_with_sl_no" class="form-check-input" name="is_with_sl" type="radio" value="No" checked="checked">
                                    {{ Form::label('is_with_sl', 'No', ['class' => 'form-check-label']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <h4 class="text-header mb-3">SUBSIDIARY Information</h4>
                            <div id="datatable-2" class="dataTables_wrapper">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="subsidiaryTable" class="display dataTable table w-100 table-striped" aria-describedby="subsidiaryInfo">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('PREFIX') }}</th>
                                                        <th>{{ __('CODE') }}</th>
                                                        <th>{{ __('DESCRIPTION') }}</th>
                                                        <th>{{ __('IS PARENT') }}</th>
                                                        <th>{{ __('VISIBILITY') }}</th>
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
                    <button type="button" class="btn submit-btn btn-primary"><i class="la la-save"></i> Save Changes</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>