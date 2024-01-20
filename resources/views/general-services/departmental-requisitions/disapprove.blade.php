<div class="modal form-inner fade" id="disapprove-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="bacRFQLabel" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{ Form::open(array('url' => 'for-approvals/departmental-requisition', 'class' => '', 'name' => 'disapproveDepartmentalRequisitionForm')) }}
            @csrf
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="bacRFQLabel">Departmental Request Disapproval (<span class="code"></span>)</h5>
            </div>
            <div class="modal-body p-0 p-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-form__group required mb-0">
                            {{ Form::label('disapproved_remarks', 'Why Disapprove?', ['class' => 'required fs-5 fw-bold mb-1']) }}
                            {{ 
                                Form::textarea($name = 'disapproved_remarks', $value = '', 
                                $attributes = array(
                                    'id' => 'disapproved_remarks',
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
                <button type="button" class="btn submit-disapprove-btn btn-blue align-middle d-flex justify-content-center">Submit</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>