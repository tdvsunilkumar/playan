<div class="modal form fade" id="replenish-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="replenishModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'for-approvals/petty-cash/replenishment', 'class'=>'formDtls needs-validation', 'name' => 'replenishForm')) }}
            @csrf
                <div class="modal-header bg-accent pt-4 pb-4">
                    <h5 class="modal-title full-width c-white" id="replenishModal">
                        Manage Replenishment
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group mb-0 required">
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
                    <div class="row">
                        <div class="col-md-12">
                            <label for="total-amount" class="text-secondary w-100 text-end fs-5 fw-bold">TOTAL AMOUNT: &nbsp; <span class="text-danger">0.00</span></label>
                            <h4 class="text-header mt-2 mb-4">Replenishment Details</h4>
                            <div class="table-responsive table-button">
                                <table id="replenishLineTable" class="display dataTable table w-100 table-striped" aria-describedby="replenishLineInfo" style="margin-top: 1.5rem !important; margin-bottom: 1.5rem !important">
                                    <thead>
                                        <tr>
                                            <th>{{ __('ID') }}</th>
                                            <th>{{ __('PETTY CASH NO') }}</th>
                                            <th>{{ __('AMOUNT') }}</th>
                                            <th>{{ __('LAST MODIFIED') }}</th>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>