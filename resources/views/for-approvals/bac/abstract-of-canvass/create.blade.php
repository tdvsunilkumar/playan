<div class="modal form fade" id="rfq-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="bacRFQLabel" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            {{ Form::open(array('url' => 'general-services/bac/abstract-of-canvas', 'class' => '', 'name' => 'rfqForm')) }}
            @csrf
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="bacRFQLabel">Manage Abstract Of Canvass</h5>
            </div>
            <div class="modal-body">
                <h4 class="text-header mb-3">Purchase Request's Information</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-form__group">
                            {{ Form::label('project_name', 'Project Name', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::textarea($name = 'project_name', $value = '', 
                                $attributes = array(
                                    'id' => 'project_name',
                                    'class' => 'form-control form-control-solid',
                                    'rows' => 3,
                                    'disabled' => 'disabled'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            {{ Form::label('control_no', 'Control No: ', ['class' => 'required fs-5 fw-bold']) }}
                            {{ Form::label('control_no', '&nbsp; _ _ _ _ _ _ _ _ _ _ _ _ _ _', ['class' => 'required fs-5 fw-bold text-danger']) }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            {{ Form::label('total_budget', 'Approved Budget: ', ['class' => 'required fs-5 fw-bold']) }}
                            {{ Form::label('total_budget', '&nbsp; _ _ _ _ _ _ _ _ _ _ _ _ _ _', ['class' => 'required fs-5 fw-bold text-danger']) }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="accordion" id="supplier-content">                            
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h4 class="text-header mb-3">Committees</h4>
                        <div id="datatable-2" class="dataTables_wrapper">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="committeeTable" class="display dataTable table w-100 table-striped" aria-describedby="committeeInfo">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Name') }}</th>
                                                    <th>{{ __('Department') }}</th>
                                                    <th>{{ __('Division') }}</th>
                                                    <th>{{ __('Designations') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="odd">
                                                    <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h4 class="text-header mb-3">Other Information</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group m-form__group">
                                    {{ Form::label('remarks', 'Remarks', ['class' => 'required fs-6 fw-bold']) }}
                                    {{ 
                                        Form::textarea($name = 'remarks', $value = '', 
                                        $attributes = array(
                                            'id' => 'remarks',
                                            'class' => 'form-control form-control-solid',
                                            'rows' => 3,
                                            'disabled'
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-form__group">
                                    {{ Form::label('recommendations', 'Recommendations', ['class' => 'required fs-6 fw-bold']) }}
                                    {{ 
                                        Form::textarea($name = 'recommendations', $value = '', 
                                        $attributes = array(
                                            'id' => 'recommendations',
                                            'class' => 'form-control form-control-solid',
                                            'rows' => 3,
                                            'disabled'
                                        )) 
                                    }}
                                    <span class="m-form__help text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary aling-middle d-flex justify-content-center" data-bs-dismiss="modal">Close</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>