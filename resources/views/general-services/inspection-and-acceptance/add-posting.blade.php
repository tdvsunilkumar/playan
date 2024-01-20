<div class="modal form-inner fade" id="add-posting-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="addPurchaseRequestLabel">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            {{ Form::open(array('url' => 'general-services/inspection-and-acceptance', 'class' => '', 'name' => 'postingForm')) }}
            @csrf
            <div class="modal-header p-0">
                <div class="input-group search-group">
                    <input class="form-control border-end-0 border" type="search" id="keyword1" placeholder="search for keywords">
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="row" style="margin-top: -1px">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="available-posting-table" class="table table-striped m-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>ITEM CODE</th>
                                        <th>ITEM DESCRIPTION</th>
                                        <th class="text-center">UOM</th>
                                        <th class="text-center">RECEIVABLE QUANTITY</th>
                                        <th class="text-center">POSTING QUANTITY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="p-3 pt-4 mt-1" style="border-top: 1px solid #f1f1f1;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('inspected_by', 'Inspected By', ['class' => '']) }}
                                {{
                                    Form::select('inspected_by', $users, $value = '', ['id' => 'inspected_by', 'class' => 'form-control select3', 'data-placeholder' => 'select an inspector'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('inspected_date', 'Inspected Date', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'inspected_date', $value = '', 
                                    $attributes = array(
                                        'id' => 'inspected_date',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('received_by', 'Received By', ['class' => '']) }}
                                {{
                                    Form::select('received_by', $users, $value = '', ['id' => 'received_by', 'class' => 'form-control select3', 'data-placeholder' => 'select a receiver'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('received_date', 'Received Date', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'received_date', $value = '', 
                                    $attributes = array(
                                        'id' => 'received_date',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('reference_no', 'Reference No.', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'reference_no', $value = '', 
                                    $attributes = array(
                                        'id' => 'reference_no',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('reference_date', 'Reference Date', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'reference_date', $value = '', 
                                    $attributes = array(
                                        'id' => 'reference_date',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group m-form__group required">
                                {{ Form::label('remarks', 'Remarks', ['class' => 'required fs-6 fw-bold mb-1']) }}
                                <div class="form-check form-check-inline pull-right m-0 mb-1">
                                    <input class="form-check-input" name="inventory_posting" type="checkbox" value="1">
                                    <span class="form-check-label" for="inlineCheckbox1">Is Inventory Posting</span>
                                </div>
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary aling-middle justify-content-center" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn post-btn btn-primary aling-middle justify-content-center">Post Now</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>