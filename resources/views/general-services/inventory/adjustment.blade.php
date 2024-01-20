<div class="modal form-inner fade" id="item-adjustment-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="itemAdjustmentModal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">            
            <div class="modal-header bg-accent p-0 p-4">
                <h5 class="modal-title full-width c-white" id="itemAdjustmentModal">
                    Add Item Adjustment
                    <span class="variables"></span>
                </h5>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => 'general-services/inventory/send', 'class'=>'formDtls', 'name' => 'adjustmentForm')) }}
                @csrf
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group m-form__group required">
                            {{ Form::label('adjustment_type_id', 'Adjustment Type', ['class' => '']) }}
                            {{
                                Form::select('adjustment_type_id', $adjustments, $value = '', ['id' => 'adjustment_type_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a type'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group m-form__group required">
                            {{ Form::label('quantity', 'Quantity Adjustment', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'quantity', $value = '', 
                                $attributes = array(
                                    'id' => 'quantity',
                                    'class' => 'form-control form-control-solid numeric-double'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group m-form__group required mb-0">
                            {{ Form::label('remarks', 'Remarks', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::textarea($name = 'remarks', $value = '', 
                                $attributes = array(
                                    'id' => 'remarks',
                                    'class' => 'form-control form-control-solid',
                                    'rows' => 3
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn send-btn bg-print align-middle d-flex justify-content-center text-white">Send Adjustment</button>
            </div>
        </div>
    </div>
</div>