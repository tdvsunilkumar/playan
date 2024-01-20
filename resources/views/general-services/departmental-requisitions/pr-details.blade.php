<div class="tab-pane fade" id="pr-details" role="tabpanel" aria-labelledby="bidding-details-tab">
    {{ Form::open(array('url' => 'general-services/purchase-requests', 'name' => 'prForm')) }}
    @csrf
    <h4 class="text-header">Purchase Request Information</h4>
    <div class="detail-layer">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('purchase_request_no', 'PR No.', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'purchase_request_no', $value = '', 
                        $attributes = array(
                            'class' => 'form-control form-control-solid strong',
                            'disabled' => 'disabled'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('approved_date', 'Approved Date', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'approved_date', $value = '', 
                        $attributes = array(
                            'class' => 'form-control form-control-solid',
                            'disabled' => 'disabled'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('prepared_by', 'Prepared By', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'prepared_by', $value = '', 
                        $attributes = array(
                            'class' => 'form-control form-control-solid',
                            'disabled' => 'disabled'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div> 
            <div class="col-sm-6">
                <div class="form-group m-form__group">
                    {{ Form::label('prepared_date', 'Prepared Date', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::text($name = 'prepared_date', $value = '', 
                        $attributes = array(
                            'class' => 'form-control form-control-solid',
                            'disabled' => 'disabled'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div> 
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group m-form__group required">
                    {{ Form::label('pr_remarks', 'Purpose', ['class' => 'fs-6 fw-bold']) }}
                    {{ 
                        Form::textarea($name = 'pr_remarks', $value = '', 
                        $attributes = array(
                            'id' => 'pr_remarks',
                            'class' => 'form-control form-control-solid',
                            'rows' => 3,
                            'disabled' => 'disabled'
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>