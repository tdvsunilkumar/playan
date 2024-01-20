<div class="modal form fade" id="disapprove-payment-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="accountPaymentLabel" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{ Form::open(array('url' => 'accounting/journal-entries/payables/payments', 'class' => '', 'name' => 'disapprovePaymentForm')) }}
            @csrf
            <div class="modal-header pt-4 pb-4 bg-danger">
                <h5 class="modal-title" id="accountPayableLabel">Payment Disapproval (<span class="code"></span>)</h5>
            </div>
            <div class="modal-body p-0 p-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-form__group required mb-0">
                            {{ Form::label('disapproved_payment_remarks', 'Why Disapprove?', ['class' => 'required fs-5 fw-bold mb-1']) }}
                            {{ 
                                Form::textarea($name = 'disapproved_payment_remarks', $value = '', 
                                $attributes = array(
                                    'id' => 'disapproved_payment_remarks',
                                    'class' => 'form-control form-control-solid fs-5 pe-2 ps-2',
                                    'rows' => 5
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary aling-middle d-flex justify-content-center" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn submit-disapprove-payment-btn bg-danger text-white align-middle d-flex justify-content-center">Submit</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>