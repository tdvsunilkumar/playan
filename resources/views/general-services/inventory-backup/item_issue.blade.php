<div class="modal form fade" id="issuanceRequestModal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="issuanceRequestModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'general-services/inventory', 'class'=>'formDtls needs-validation', 'name' => 'issuanceRequestForm')) }}
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
                                        'required' => "true"
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
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('issue_month', 'Issuance Month', ['class' => '']) }}
                                {{
                                    Form::select('issue_month', $issue_month, $value = '', ['id' => 'issue_month', 'class' => 'form-control select3', 'data-placeholder' => 'select month'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('issue_year', 'Issuance Year', ['class' => '']) }}
                                {{
                                    Form::select('issue_year', $issue_year, $value = '', ['id' => 'issue_year', 'class' => 'form-control select3', 'data-placeholder' => 'select year'])
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
                                {{ Form::select('issue_type',array('' =>'Select Issuance Type','1' =>'1-RIS[Requisition & Issue Slip]','2' =>'2-ICS[Inventory Custodian Slip]','3' =>'3-PAR[Property Acknowledgement Receipt]'), $issue_type, array('class' => 'form-control select','id'=>'issue_type','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_bsf_tax_schedule"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('department_id', 'Department', ['class' => '']) }}
                                {{
                                    Form::select('department_id', $departments, $value = '', ['id' => 'department_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a department'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('division_id', 'Division', ['class' => '']) }}
                                {{
                                    Form::select('division_id', $divisions, $value = '', ['id' => 'division_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a division'])
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
                                    )) 
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="fv-row row">
                        <div class="col-xl-12">
                            <div class="card table-card">
                                <div class="card-body">
                                    <div id="datatable-2" class="dataTables_wrapper">
                                        <div class="row">
                                        <h4 class="text-header">Item Details</h4>
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="itemIssueTable" class="display dataTable table w-100 table-striped" aria-describedby="groupMenuInfo">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>{{ __('Item ID') }}</th>
                                                                <th>{{ __('Account Code') }}</th>
                                                                <th>{{ __('Item Type') }}</th>
                                                                <th>{{ __('Item Code') }}</th>
                                                                <th>{{ __('Item Name') }}</th>
                                                                <th class="sliced">{{ __('Item Desc') }}</th>
                                                                <th>{{ __('Qty') }}</th>
                                                                <th>{{ __('UOM') }}</th>
                                                                <th>{{ __('Estimated Life Span') }}</th>
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
                    <button type="button" class="btn submit-btn btn-primary">Request</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>