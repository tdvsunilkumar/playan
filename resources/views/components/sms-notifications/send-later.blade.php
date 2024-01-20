<div class="modal form fade" id="send-later-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="sendLaterModal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'components/sms-notifications/send-later', 'class'=>'formDtls needs-validation', 'name' => 'scheduleForm')) }}
            @csrf
                <div class="modal-header bg-accent pb-4 pt-4">
                    <h5 class="modal-title full-width c-white" id="sendLaterModal">
                        Send Schedule SMS
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body p-0 p-4">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required">
                                {{ Form::label('schedule', 'Schedule', ['class' => 'required fs-6 fw-bold']) }}
                                <input name="schedule" class="form-control m-input" type="datetime-local" value="" id="schedule">
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn submit-btn btn-primary">Save Changes</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>