<div class="tab-pane fade show active" id="pr-details" role="tabpanel" aria-labelledby="bidding-details-tab">
    {{ Form::open(array('url' => 'general-services/purchase-requests', 'name' => 'prForm')) }}
    @csrf
    <h4 class="text-header">Purchase Request Information</h4>
    <div class="image-layer text-center">
        <img src="{{ url('/assets/images/illustrations/presentation.png') }}" />
        <button type="button" class="btn generate-btn btn-primary">UNLOCK PR</button>
    </div>
    <div class="detail-layer hidden">
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
                            'rows' => 3
                        )) 
                    }}
                    <span class="m-form__help text-danger"></span>
                </div>
            </div>
        </div>
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="modalToast" data-bs-autohide="true" class="toast hide bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body">
                Hello, world! This is a toast message.
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>