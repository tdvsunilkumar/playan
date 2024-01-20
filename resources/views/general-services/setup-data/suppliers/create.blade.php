<div class="modal form fade" id="supplier-modal" role="dialog" aria-labelledby="supplierModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">        
            <div class="modal-header bg-accent">
                <h5 class="modal-title full-width c-white pt-3 pb-3" id="supplierModal">
                    Manage Supplier
                    <span class="variables"></span>
                </h5>
            </div>
            <div class="modal-body p-0 p-4">
                {{ Form::open(array('url' => 'general-services/setup-data/suppliers', 'class'=>'formDtls needs-validation', 'name' => 'supplierForm')) }}
                @csrf
                <h4 class="text-header">Supplier Details</h4>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('code', 'Code', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'code', $value = '', 
                                $attributes = array(
                                    'id' => 'code',
                                    'class' => 'form-control form-control-solid text-uppercase fw-bold',
                                    'readOnly' => TRUE                                   
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group">
                            {{ Form::label('product_line_id', 'Product Line', ['class' => 'fs-6 fw-bold']) }}        
                            {{
                                Form::select('product_line_id[]', $product_lines, $value = '', ['id' => 'product_line_id', 'class' => 'form-control selectpicker', 'multiple' => 'multiple', 'data-live-search' => 'true', 'data-actions-box' => 'true', 'data-size' => '7'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('branch_name', 'Branch Name', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'branch_name', $value = '', 
                                $attributes = array(
                                    'id' => 'branch_name',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-form__group required">
                            {{ Form::label('business_name', 'Business Name', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'business_name', $value = '', 
                                $attributes = array(
                                    'id' => 'business_name',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group m-form__group required">
                            {{ Form::label('vat_type', 'Vat Type', ['class' => 'fs-6 fw-bold']) }}
                            {{
                                Form::select('vat_type', $vat, $value = '', ['id' => 'vat_type', 'class' => 'form-control select3', 'data-placeholder' => 'select a vat type'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group required">
                            {{ Form::label('ewt_id', 'EWT', ['class' => 'fs-6 fw-bold']) }}
                            {{
                                Form::select('ewt_id', $ewt, $value = '', ['id' => 'ewt_id', 'class' => 'form-control select3', 'data-placeholder' => 'select an ewt'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('evat_id', 'EVAT', ['class' => 'fs-6 fw-bold']) }}
                            {{
                                Form::select('evat_id', $evat, $value = '', ['id' => 'evat_id', 'class' => 'form-control select3', 'data-placeholder' => 'select an evat', 'disabled' => 'disabled'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('house_lot_no', 'Blk / Lot No.', ['class' => 'fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'house_lot_no', $value = '', 
                                $attributes = array(
                                    'id' => 'house_lot_no',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('street_name', 'Street Name', ['class' => 'fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'street_name', $value = '', 
                                $attributes = array(
                                    'id' => 'street_name',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('subdivision', 'Subdivision / Village Name', ['class' => 'fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'subdivision', $value = '', 
                                $attributes = array(
                                    'id' => 'subdivision',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row" id="divBarngay">
                    <div class="col-sm-8">
                        <div class="form-group m-form__group required">
                            {{ Form::label('barangay_id', 'Barangay, Municipality, Province, Region', ['class' => 'fs-6 fw-bold']) }}
                            {{
                                Form::select('barangay_id', $barangays, $value = '', ['id' => 'barangay_id', 'class' => 'form-control', 'data-placeholder' => 'select a barangay...'])
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-form__group">
                            {{ Form::label('email_address', 'Email Address', ['class' => 'fs-6 fw-bold']) }}
                            {{ 
                                Form::email($name = 'email_address', $value = '', 
                                $attributes = array(
                                    'id' => 'email_address',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group m-form__group">
                            {{ Form::label('telephone_no', 'Telephone No.', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'telephone_no', $value = '', 
                                $attributes = array(
                                    'id' => 'telephone_no',
                                    'class' => 'form-control form-control-solid text-uppercase'
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-form__group required">
                            {{ Form::label('mobile_no', 'Mobile No.', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'mobile_no', $value = '', 
                                $attributes = array(
                                    'id' => 'mobile_no',
                                    'class' => 'form-control form-control-solid numeric-only',
                                    'maxlength' => 11,
                                    'minlengh' => 11
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-form__group">
                            {{ Form::label('fax_no', 'Fax No.', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'fax_no', $value = '', 
                                $attributes = array(
                                    'id' => 'fax_no',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-form__group">
                            {{ Form::label('tin_no', 'Tin No.', ['class' => 'required fs-6 fw-bold']) }}
                            {{ 
                                Form::text($name = 'tin_no', $value = '', 
                                $attributes = array(
                                    'id' => 'tin_no',
                                    'class' => 'form-control form-control-solid',
                                )) 
                            }}
                            <span class="m-form__help text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group m-form__group">
                            {{ Form::label('remarks', 'Remarks', ['class' => 'required fs-6 fw-bold']) }}
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
                {{ Form::close() }}
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-header mb-3">Contact Details</h4>
                        <div id="datatable-2" class="dataTables_wrapper">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="supplierContactTable" class="display dataTable table w-100 table-striped" aria-describedby="supplierContactInfo">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('CONTACT PERSON') }}</th>
                                                    <th>{{ __('TEL NO.') }}</th>
                                                    <th>{{ __('MOBILE NO.') }}</th>
                                                    <th>{{ __('EMAIL') }}</th>
                                                    <th>{{ __('LAST MODIFIED') }}</th>
                                                    <th>{{ __('STATUS') }}</th>
                                                    <th>{{ __('ACTIONS') }}</th>
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
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="text-header mt-3 mb-3">Upload Details</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        {{ Form::open(array('url' => 'general-services/setup-data/suppliers?category=suppliers', 'class'=>'dropzone dz-clickable', 'name' => 'supplierUploadForm', 'id' => 'import-supplier-dropzone')) }}
                            <div class="dz-default dz-message"><span>Drop files here to upload</span></div>
                        {{ Form::close() }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div id="datatable-2" class="dataTables_wrapper mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="supplierUploadTable" class="display dataTable table w-100 table-striped" aria-describedby="supplierUploadInfo">
                                            <thead>
                                                <tr>
                                                    <th class="sliced">{{ __('FILENAME') }}</th>
                                                    <th>{{ __('TYPE') }}</th>
                                                    <th>{{ __('SIZE') }}</th>
                                                    <th>{{ __('ACTIONS') }}</th>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn submit-btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>
