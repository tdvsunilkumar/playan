<div class="modal form-inner fade" id="item-modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">            
            <div class="modal-header bg-accent pb-4 pt-4">
                <h5 class="modal-title full-width c-white" id="itemModal">
                    Manage Pre-Repair Item
                    <span class="variables"></span>
                </h5>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => 'general-services/repairs-and-inspections/inspection', 'name' => 'itemForm', 'method' => 'POST')) }}
                @csrf
                <div class="fv-row row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('item_id', 'Item Description', ['class' => '']) }}
                            {{
                                Form::select('item_id', $items, $value = '', ['id' => 'item_id', 'class' => 'form-control select3', 'data-placeholder' => 'select an item'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group">
                            {{ Form::label('uom_id', 'Unit Of Measurement', ['class' => '']) }}
                            {{
                                Form::select('uom_id', $uoms, $value = '', ['id' => 'uom_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a uom', 'disabled' => 'disabled'])
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
                        <div class="form-group m-form__group">
                            {{ Form::label('unit_cost', 'Unit Cost', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'unit_cost', $value = '', 
                                $attributes = array(
                                    'id' => 'unit_cost',
                                    'class' => 'form-control form-control-solid numeric-double',
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
                        <div class="form-group m-form__group mb-0">
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