<div class="modal form fade" id="budget-proposal-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="bugetLabel" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            {{ Form::open(array('url' => 'finance/budget-proposal', 'class' => '', 'name' => 'budgetForm')) }}
            @csrf
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="bacRFQLabel">Manage Budget</h5>
            </div>
            <div class="modal-body">
                <h4 class="text-header mb-3">Budget Information</h4>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('fund_code_id', 'Fund Code', ['class' => '']) }}
                            {{
                                Form::select('fund_code_id', $fund_codes, $value = '', ['id' => 'fund_code_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a fund code'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('budget_year', 'Year', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'budget_year', $value = '', 
                                $attributes = array(
                                    'id' => 'budget_year',
                                    'class' => 'form-control form-control-solid strong'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('department_id', 'Department', ['class' => '']) }}
                            {{
                                Form::select('department_id', $departments, $value = '', ['id' => 'department_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a department'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('division_id', 'Division', ['class' => '']) }}
                            {{
                                Form::select('division_id', $divisions, $value = '', ['id' => 'division_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a division'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group m-form__group">
                            {{ Form::label('remarks', 'Remarks', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::textarea($name = 'remarks', $value = '', 
                                $attributes = array(
                                    'id' => 'remarks',
                                    'class' => 'form-control form-control-solid strong',
                                    'rows' => 3
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <h4 class="text-header mb-3">Breakdown Information</h4>
                        <div id="datatable-2" class="dataTables_wrapper">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="breakdownTable" class="display dataTable table w-100 table-striped" aria-describedby="prInfo">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('#') }}</th>
                                                    <th>{{ __('GL Description') }}</th>
                                                    <th>{{ __('Quarterly Budget') }}</th>
                                                    <th>{{ __('Annual Budget') }}</th>
                                                    <th>{{ __('Status') }}</th>
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
                                                    <th colspan="3" class="text-end fs-5">{{ __('TOTAL AMOUNT') }}</th>
                                                    <th colspan="1" class="text-end text-danger fs-5">{{ __('0.00') }}</th>
                                                    <th colspan="2"></th>
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
            {{ Form::close() }}
        </div>
    </div>
</div>