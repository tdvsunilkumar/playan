<div class="modal form fade" id="general-journal-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="accountPayableModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'accounting/general-journals', 'class'=>'formDtls needs-validation', 'name' => 'generalJournalForm')) }}
            @csrf
                <div class="modal-header bg-accent p-0 p-4">
                    <h5 class="modal-title full-width c-white" id="generalJournaModal">
                        Manage General Journal
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('general_journal_no', 'Transaction No.', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'general_journal_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'general_journal_no',
                                        'class' => 'require form-control form-control-solid strong disable',
                                        'disabled' => 'disabled'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required disabled">
                                {{ Form::label('transaction_date', 'Transaction Date', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'transaction_date', $value = '', 
                                    $attributes = array(
                                        'id' => 'transaction_date',
                                        'class' => 'require form-control form-control-solid strong'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('fixed_asset_id', 'Fixed Asset No', ['class' => '']) }}
                                {{
                                    Form::select('fixed_asset_id', $fixed_assets, $value = '', ['id' => 'fixed_asset_id', 'class' => 'require form-control select3', 'data-placeholder' => 'select a fixed asset no'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('fund_code_id', 'Fund Code', ['class' => '']) }}
                                {{
                                    Form::select('fund_code_id', $fund_codes, $value = '', ['id' => 'fund_code_id', 'class' => 'require form-control select3', 'data-placeholder' => 'select a fund code'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('payee_id', 'Payee', ['class' => '']) }}
                                {{
                                    Form::select('payee_id', $payees, $value = '', ['id' => 'payee_id', 'class' => 'require form-control select3', 'data-placeholder' => 'select a payee'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('division_id', 'Responsibility Center', ['class' => '']) }}
                                {{
                                    Form::select('division_id', $divisions, $value = '', ['id' => 'division_id', 'class' => 'require form-control select3', 'data-placeholder' => 'select a responsibility center'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required mb-0">
                                {{ Form::label('particulars', 'Particulars', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::textarea($name = 'particulars', $value = '', 
                                    $attributes = array(
                                        'id' => 'particulars',
                                        'class' => 'form-control form-control-solid',
                                        'rows' => 3
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <h4 class="text-header mb-3">GENERAL JOURNAL ENTRIES</h4>
                            <div id="datatable-2" class="dataTables_wrapper">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="journalEntriesTable" class="display dataTable table w-100 table-striped" aria-describedby="journalEntriesInfo">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('GL DESCRIPTION') }}</th>
                                                        <th>{{ __('DEBIT AMOUNT') }}</th>
                                                        <th>{{ __('CREDIT AMOUNT') }}</th>
                                                        <th>{{ __('Actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="odd">
                                                        <td valign="top" colspan="6" class="dataTables_empty">Loading...</td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="1" class="text-start text-danger fs-5"></th>
                                                        <th colspan="1" class="text-start text-danger total-debit fs-5">{{ __('0.00') }}</th>
                                                        <th colspan="1" class="text-start text-danger total-credit fs-5">{{ __('0.00') }}</th>
                                                        <th colspan="1" class="text-start fs-5">{{ __('TOTAL AMOUNT') }}</th>
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
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn submit-btn btn-primary">Post Changes</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>