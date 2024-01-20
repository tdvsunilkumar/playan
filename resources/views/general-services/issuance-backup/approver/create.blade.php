<div class="modal form fade" id="group-menu-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="issuanceRequestModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'general-services/issuance/approver', 'class'=>'formDtls needs-validation', 'name' => 'issuanceRequestForm')) }}
            @csrf
                <div class="modal-header bg-accent">
                    <h5 class="modal-title full-width c-white" id="issuanceRequestModal">
                        Inssuance Request
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="fv-row row">
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('issue_control_no', 'Control No.', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'issue_control_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'issue_control_no',
                                        'class' => 'form-control form-control-solid',
                                        'readonly' => true
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('issue_date', 'Issuance Date', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'issue_date', $value = '', 
                                    $attributes = array(
                                        'id' => 'issue_date',
                                        'class' => 'form-control form-control-solid',
                                        'readonly' => true
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('issue_month', 'Issuance Month', ['class' => '']) }}
                                {{
                                    Form::select('issue_month', $issue_month, $value = '', ['id' => 'issue_month', 'class' => 'form-control select3', 'data-placeholder' => 'select month', 'disabled' => 'disabled'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('issue_year', 'Issuance Year', ['class' => '']) }}
                                {{
                                    Form::select('issue_year', $issue_year, $value = '', ['id' => 'issue_year', 'class' => 'form-control select3', 'data-placeholder' => 'select year', 'disabled' => 'disabled'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                       

                        <div class="col-md-3">
                            <div class="form-group required" >
                            Issuance Type <span class="text-danger">*</span>
                                <div class="form-icon-user">
                                {{ Form::select('issue_type',array('' =>'Select Issuance Type','1' =>'1-RIS[Requisition & Issue Slip]','2' =>'2-ICS[Inventory Custodian Slip]','3' =>'3-PAR[Property Acknowledgement Receipt]'), $issue_type, array('class' => 'form-control select','id'=>'issue_type','required'=>'required','readonly' => true)) }}
                                </div>
                                <span class="validate-err" id="err_bsf_tax_schedule"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('department_id', 'Department', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'department_id', $value = '', 
                                    $attributes = array(
                                        'id' => 'department_id',
                                        'class' => 'form-control form-control-solid',
                                        'readonly' => true
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('division_id', 'Division', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'division_id', $value = '', 
                                    $attributes = array(
                                        'id' => 'division_id',
                                        'class' => 'form-control form-control-solid',
                                        'readonly' => true
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('issue_fund_cluster', 'Fund Cluster', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'issue_fund_cluster', $value = '', 
                                    $attributes = array(
                                        'id' => 'issue_fund_cluster',
                                        'class' => 'form-control form-control-solid',
                                        'readonly' => true
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('issue_requestor', 'Request By', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'issue_requestor', $value = '', 
                                    $attributes = array(
                                        'id' => 'issue_requestor',
                                        'class' => 'form-control form-control-solid',
                                        'readonly' => true
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">

                                {{ Form::label('issue_requestor_position', 'Designation', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'issue_requestor_position', $value = '', 
                                    $attributes = array(
                                        'id' => 'issue_requestor_position',
                                        'class' => 'form-control form-control-solid',
                                        'readonly' => true
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('issue_date', 'Request Date', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'issue_date', $value = '', 
                                    $attributes = array(
                                        'id' => 'issue_date',
                                        'class' => 'form-control form-control-solid',
                                        'readonly' => true
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required">
                                {{ Form::label('issue_remarks', 'Remarks', ['class' => 'fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'issue_remarks', $value = '', 
                                    $attributes = array(
                                        'id' => 'issue_remarks',
                                        'class' => 'form-control form-control-solid',
                                        'readonly' => true
                                    )) 
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('employee_id', 'Approved By', ['class' => '']) }}
                                {{
                                    Form::select('employee_id', $employees, $value = '', ['id' => 'employee_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a requestor'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('designation_id', 'Designation', ['class' => '']) }}
                                {{
                                    Form::select('designation_id', $designations, $value = '', ['id' => 'designation_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a designation', 'disabled' => 'disabled'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group required">
                                {{ Form::label('issue_approver_date', 'Approved Date', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'issue_approver_date', $value = '', 
                                    $attributes = array(
                                        'id' => 'issue_approver_date',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-xl-12">
                            <div class="card table-card">
                                <div class="card-body">
                                    <div id="datatable-2" class="dataTables_wrapper">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="itemTable" class="display dataTable table w-100 table-striped" aria-describedby="groupMenuInfo">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Control No.') }}</th>
                                                                <th>{{ __('Issuance Date') }}</th>
                                                                <th>{{ __('Item Name') }}</th>
                                                                <th class="sliced">{{ __('Item Desc') }}</th>
                                                                <th>{{ __('UOM') }}</th>
                                                                <th class="sliced">{{ __('Qty') }}</th>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn submit-btn btn-primary">Approved</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>