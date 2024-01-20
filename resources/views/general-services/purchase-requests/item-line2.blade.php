<div class="modal form-inner fade" id="item-line2-modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">            
            <div class="modal-header bg-accent pb-4 pt-4">
                <h5 class="modal-title full-width c-white" id="itemModal">
                    Manage Item
                    <span class="variables"></span>
                </h5>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => 'general-services/purchase-requests', 'class'=>'formDtls needs-validation', 'name' => 'itemForm')) }}
                @csrf
                <div class="fv-row row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('item_description', 'Item Description', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'item_description', $value = '', 
                                $attributes = array(
                                    'id' => 'item_description',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('uom_id', 'Unit Of Measurement', ['class' => '']) }}
                            {{
                                Form::select('uom_id', $unit_of_measurements, $value = '', ['id' => 'uom_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a uom'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="fv-row row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('quantity', 'Quantity', ['class' => 'required fs-6 fw-bold']) }}
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
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('unit_cost', 'Unit Cost', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'unit_cost', $value = '', 
                                $attributes = array(
                                    'id' => 'unit_cost',
                                    'class' => 'form-control form-control-solid numeric-double'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="fv-row row">
                    <div class="col-sm-12">
                        <div class="form-group m-form__group">
                            {{ Form::label('total_cost', 'Total Cost', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'total_cost', $value = '', 
                                $attributes = array(
                                    'id' => 'total_cost',
                                    'class' => 'form-control form-control-solid',
                                    'disabled' => 'disabled'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="fv-row row">
                    <div class="col-sm-12">
                        <div class="form-group m-form__group">
                            {{ Form::label('remarks', 'Remarks', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::textarea($name = 'remarks', $value = '', 
                                $attributes = array(
                                    'id' => 'remarks',
                                    'class' => 'form-control form-control-solid',
                                    'rows' => 2
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
                <button type="button" class="btn submit-btn btn-primary"><i class="la la-save align-middle"></i> Save Changes</button>
            </div>
        </div>
    </div>
</div>