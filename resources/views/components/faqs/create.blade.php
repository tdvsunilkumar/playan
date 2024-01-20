<div class="modal form fade" id="faq-modal" tabindex="-1" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="groupMenuModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => 'components/faqs', 'class'=>'formDtls needs-validation', 'name' => 'faqForm')) }}
            @csrf
                <div class="modal-header bg-accent pb-4 pt-4">
                    <h5 class="modal-title full-width c-white" id="faqModal">
                        Manage FAQ
                        <span class="variables"></span>
                    </h5>
                </div>
                <div class="modal-body p-0 p-4">
                    <div class="row">
                        <div class="col-sm-9">
                            <div class="form-group m-form__group required">
                                {{ Form::label('name', 'Topic', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'title', $value = '', 
                                    $attributes = array(
                                        'id' => 'title',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-form__group required">
                                {{ Form::label('icon', 'Icon', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'icon', $value = '', 
                                    $attributes = array(
                                        'id' => 'icon',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required">
                                {{ Form::label('group_id', 'Group Menu', ['class' => '']) }}
                                {{
                                    Form::select('group_id', $groups, $value = '', ['id' => 'group_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a group menu'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required">
                                {{ Form::label('description', 'Description', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::textarea($name = 'description', $value = '', 
                                    $attributes = array(
                                        'id' => 'description',
                                        'class' => 'form-control form-control-solid',
                                        'rows' => 3
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="layers">
                        <div class="element">
                            <hr/>
                            {{ 
                                Form::text($name = 'id[]', $value = '', 
                                $attributes = array(
                                    'id' => 'header_id',
                                    'class' => 'form-control form-control-solid hidden'
                                )) 
                            }}
                            <h5 class="numbering">#1</h5>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group m-form__group required">
                                        {{ Form::label('header', 'Header', ['class' => 'required fs-6 fw-bold']) }}
                                        {{ 
                                            Form::textarea($name = 'header[]', $value = '', 
                                            $attributes = array(
                                                'id' => 'header',
                                                'class' => 'form-control form-control-solid',
                                                'rows' => 2
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group m-form__group mb-0">
                                        {{ Form::label('content', 'Content', ['class' => 'required fs-6 fw-bold']) }}
                                        {{ 
                                            Form::textarea($name = 'content[]', $value = '', 
                                            $attributes = array(
                                                'id' => 'content',
                                                'class' => 'form-control form-control-solid',
                                                'rows' => 3
                                            )) 
                                        }}
                                        <span class="m-form__help text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group m-form__group">
                                        <label for="exampleInputEmail1">
                                            File Attachment
                                        </label>
                                        <div></div>
                                        <div class="custom-file">
                                            <input type="text" name="file[]" class="hidden"/>
                                            <input type="file" class="custom-file-input" id="customFile" name="attachment[]" accept="image/*">
                                            <label class="custom-file-label" for="customFile">
                                                Choose file
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn toggle-btn remove-btn me-1 btn-danger">
                                <span class="d-flex justify-content-center align-items-center">
                                    <i class="ti-minus align-middle m-1"></i>&nbsp; REMOVE
                                </span>
                            </button>
                            <button type="button" class="btn toggle-btn add-btn btn-blue">
                                <span class="d-flex justify-content-center align-items-center">
                                    <i class="ti-plus align-middle m-1"></i>&nbsp; ADD
                                </span>
                            </button>
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