<div class="modal form fade" id="add-date-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="addDateLabel" tabindex="-1">
    <div class="modal-dialog modal-md">
    <div class="modal-content">
            {{ Form::open(array('url' => 'accounting/journal-entries/payables/update-voucher-date', 'class'=>'formDtls needs-validation', 'name' => 'voucherDateForm')) }}
            @csrf
                <div class="modal-header bg-accent p-0 p-4">
                    <h5 class="modal-title full-width c-white" id="voucherDateModal">
                        <span class="text">Manage Voucher's Date</span>
                        <span class="variables hidden"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required">
                                {{ Form::label('voucher_date', 'Voucher Date', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'voucher_date', $value = '', 
                                    $attributes = array(
                                        'id' => 'voucher_date',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required">
                                {{ Form::label('approver', 'Approver', ['class' => '']) }}
                                {{
                                    Form::select('approver', $approvers, $value = '', ['id' => 'approver', 'class' => 'require form-control select3', 'data-placeholder' => 'select an approver'])
                                }}
                                <span class="m-form__help text-danger"></span>
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