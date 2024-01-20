<div class="tab-pane fade" id="alob-details" role="tabpanel" aria-labelledby="request-details-tab">
    {{ Form::open(array('url' => 'finance/budget-allocations', 'class' => 'formDtls', 'name' => 'alobForm')) }}
    @csrf
    <h4 class="text-header">Allotment & Obligation Slip Information</h4>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('allob_requested_date', 'Request Date', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::date($name = 'allob_requested_date', $value = '', 
                    $attributes = array(
                        'class' => 'form-control form-control-solid',
                        'disabled' => 'disabled'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('budget_year', 'Budget Year', ['class' => '']) }}
                {{
                    Form::select('budget_year', $years, $value = '', ['id' => 'budget_year', 'class' => 'form-control select3', 'data-placeholder' => 'select a year', 'disabled' => 'disabled'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('control_no', 'Control No', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::text($name = 'control_no', $value = '', 
                    $attributes = array(
                        'class' => 'form-control form-control-solid strong',
                        'disabled' => 'disabled'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('budget_no', 'Budget No', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::text($name = 'budget_no', $value = '', 
                    $attributes = array(
                        'class' => 'form-control form-control-solid strong',
                        'disabled' => 'disabled'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('allob_department_id', 'Department', ['class' => '']) }}
                {{
                    Form::select('allob_department_id', $departments, $value = '', ['id' => 'allob_department_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a department', 'disabled' => 'disabled'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group">
                {{ Form::label('allob_division_id', 'Division', ['class' => '']) }}
                {{
                    Form::select('allob_division_id', $allob_divisions, $value = '', ['id' => 'allob_division_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a division', 'disabled' => 'disabled'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('payee_id', 'Payee', ['class' => '']) }}
                {{
                    Form::select('payee_id', $payees, $value = '', ['id' => 'payee_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a payee', 'disabled' => 'disabled'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group m-form__group required">
                {{ Form::label('fund_code_id', 'Fund Code', ['class' => '']) }}
                {{
                    Form::select('fund_code_id', $fund_codes, $value = '', ['id' => 'fund_code_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a fund code', 'disabled' => 'disabled'])
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group m-form__group">
                {{ Form::label('address', 'Address', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::textarea($name = 'address', $value = '', 
                    $attributes = array(
                        'id' => 'address',
                        'class' => 'form-control form-control-solid',
                        'rows' => 1,
                        'disabled' => 'disabled'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group m-form__group required">
                {{ Form::label('particulars', 'Particulars', ['class' => 'fs-6 fw-bold']) }}
                {{ 
                    Form::textarea($name = 'particulars', $value = '', 
                    $attributes = array(
                        'id' => 'particulars',
                        'class' => 'form-control form-control-solid',
                        'rows' => 3,
                        'disabled' => 'disabled'
                    )) 
                }}
                <span class="m-form__help text-danger"></span>
            </div>
        </div>
    </div>
    {{ Form::close() }}

    <!-- ITEM DETAILS START -->
    <h4 class="text-header mt-1 mb-3">Budget Allotment Information</h4>
    <div id="datatable-3" class="dataTables_wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="allotmentBreakdownTable" class="display dataTable table w-100 table-striped mt-4" aria-describedby="allotmentBreakdown_info">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('GL ACCOUNT CODE') }}</th>
                                <th class="sliced">{{ __('GL ACCOUNT DETAILS') }}</th>
                                <th>{{ __('AMOUNT') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd">
                                <td valign="top" colspan="7" class="dataTables_empty">Loading...</td>
                            </tr>
                        </tbody>
                        <tfooter>
                            <tr>
                                <th colspan="3" class="text-end fs-5">{{ __('TOTAL AMOUNT') }}</th>
                                <th colspan="1" class="text-end text-danger fs-5">{{ __('0.00') }}</th>
                            </tr>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- ITEM DETAILS END -->
</div>