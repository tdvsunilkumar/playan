<div class="modal form fade" id="setting-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="groupMenuModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'components/sms-notifications/settings', 'class'=>'formDtls needs-validation', 'name' => 'smsSettingForm')) }}
            @csrf
                <div class="modal-header bg-accent pb-4 pt-4">
                    <h5 class="modal-title full-width c-white" id="groupMenuModal">
                        Manage Setting
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body p-0 p-4">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('app_name', 'App Name', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'app_name', $value = $apps->app_name, 
                                    $attributes = array(
                                        'id' => 'app_name',
                                        'class' => 'require form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('app_key', 'App Key', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'app_key', $value = $apps->app_key, 
                                    $attributes = array(
                                        'id' => 'app_key',
                                        'class' => 'require form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('passphrase', 'Passphrase', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'passphrase', $value = $apps->passphrase, 
                                    $attributes = array(
                                        'id' => 'passphrase',
                                        'class' => 'require form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('app_secret', 'App Secret', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'app_secret', $value = $apps->app_secret, 
                                    $attributes = array(
                                        'id' => 'app_secret',
                                        'class' => 'require form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('payload_url', 'Payload URL', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'payload_url', $value = $apps->payload_url, 
                                    $attributes = array(
                                        'id' => 'payload_url',
                                        'class' => 'require form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('dlr_url', 'DLR URL', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'dlr_url', $value = $apps->dlr_url, 
                                    $attributes = array(
                                        'id' => 'dlr_url',
                                        'class' => 'require form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('dcs', 'Data Coding Scheme', ['class' => '']) }}
                                {{
                                    Form::select('dcs', $schemes, $value = '', ['id' => 'dcs', 'class' => 'require form-control select3', 'data-placeholder' => 'select a data coding scheme'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('shortcode_mask', 'Masking Code', ['class' => '']) }}
                                {{
                                    Form::select('shortcode_mask', $maskings, $value = '', ['id' => 'shortcode_mask', 'class' => 'require form-control select3', 'data-placeholder' => 'select a masking code'])
                                }}
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