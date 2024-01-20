<div class="modal form-inner fade" id="item-conversion-modal" data-bs-keyboard="false" data-bs-backdrop="static" role="dialog" aria-labelledby="itemModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">            
            <div class="modal-header bg-accent pt-4 pb-4">
                <h5 class="modal-title full-width c-white" id="itemModal">
                    Manage Item
                    <span class="variables"></span>
                </h5>
            </div>
            <div class="modal-body p-4">
                {{ Form::open(array('url' => 'general-services/setup-data/item-managements/store-conversion', 'class'=>'formDtls needs-validation', 'name' => 'itemConversionForm')) }}
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('based_quantity', 'Based Quantity', ['class' => 'fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'based_quantity', $value = '', 
                                $attributes = array(
                                    'id' => 'based_quantity',
                                    'class' => 'form-control form-control-solid numeric-double',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group">
                            {{ Form::label('based_uom', 'Based UOM', ['class' => '']) }}
                            {{
                                Form::select('based_uom', $unit_of_measurements, $value = '', ['id' => 'based_uom', 'class' => 'form-control select3', 'data-placeholder' => 'select a uom', 'disabled' => 'disabled'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('conversion_quantity', 'Conversion Quantity', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'conversion_quantity', $value = '', 
                                $attributes = array(
                                    'id' => 'conversion_quantity',
                                    'class' => 'form-control form-control-solid numeric-double'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('conversion_uom', 'Conversion UOM', ['class' => '']) }}
                            {{
                                Form::select('conversion_uom', $unit_of_measurements, $value = '', ['id' => 'conversion_uom', 'class' => 'form-control select3', 'data-placeholder' => 'select a uom'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn submit-btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>