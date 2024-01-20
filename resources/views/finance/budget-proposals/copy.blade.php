<div class="modal form fade" id="copy-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="bugetLabel" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            {{ Form::open(array('url' => 'finance/budget-proposal/copy', 'class' => '', 'name' => 'copyBudgetForm')) }}
            @csrf
            <div class="modal-header pt-4 pb-4 btn-blue">
                <h5 class="modal-title" id="bacRFQLabel">Copy Budget Proposal</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group m-form__group required" style="margin-bottom: 0.5rem !important">
                            {{ Form::label('budget_year2', 'YEAR', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'budget_year2', $value = '', 
                                $attributes = array(
                                    'id' => 'budget_year2',
                                    'class' => 'form-control form-control-solid',
                                    'placeholder' => 'select a year',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary aling-middle d-flex justify-content-center" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn submit-btn btn-blue"><i class="la la-save align-middle"></i> Copy Changes</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>