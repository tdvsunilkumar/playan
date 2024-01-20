<div class="modal form fade" id="inventory-modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="departmentalRequisitionLabel" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header pt-4 pb-4">
                <h5 class="modal-title" id="departmentalRequisitionLabel">Manage Departmental Request</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- LEFT COLUMN DETAILS START -->
                    <div class="col-md-5 border-right space-right">
                        <div class="tab-content" id="pills-tabContent">
                                <h4 class="text-header">Item Information</h4>
                                <div class="fv-row row hidden">
                                    <div class="col-sm-12">
                                        {{ Form::label('id', 'ID', ['class' => 'required fs-6 fw-bold mb-2']) }}
                                        {{ 
                                            Form::text($name = 'id', $value = '', 
                                            $attributes = array(
                                                'id' => 'id',
                                                'class' => 'form-control form-control-solid',
                                                'disabled' => 'disabled'
                                            )) 
                                        }}
                                    </div>
                                </div>
                                <div class="fv-row row">
                                    <div class="col-sm-6">
                                        <div class="form-group m-form__group">
                                            {{ Form::label('item_category_id', 'Category Code', ['class' => 'fs-6 fw-bold']) }}
                                            {{ 
                                                Form::text($name = 'item_category_id', $value = '', 
                                                $attributes = array(
                                                    'id' => 'item_category_id',
                                                    'class' => 'form-control form-control-solid',
                                                    'readonly' => 'true'
                                                )) 
                                            }}
                                            <span class="m-form__help text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group m-form__group">
                                            {{ Form::label('gl_account_id', 'Account Code', ['class' => 'fs-6 fw-bold']) }}
                                            {{ 
                                                Form::text($name = 'gl_account_id', $value = '', 
                                                $attributes = array(
                                                    'id' => 'gl_account_id',
                                                    'class' => 'form-control form-control-solid',
                                                    'readonly' => 'true'
                                                )) 
                                            }}
                                        </div>
                                    </div>
                                </div>
                                <div class="fv-row row">
                                    <div class="col-sm-6">
                                        <div class="form-group m-form__group">
                                            {{ Form::label('item_code', 'Item Code', ['class' => 'fs-6 fw-bold']) }}
                                            {{ 
                                                Form::text($name = 'item_code', $value = '', 
                                                $attributes = array(
                                                    'id' => 'item_code',
                                                    'class' => 'form-control form-control-solid',
                                                    'readonly' => 'true'
                                                )) 
                                            }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group m-form__group">
                                            {{ Form::label('name', 'Item Name', ['class' => 'fs-6 fw-bold']) }}
                                            {{ 
                                                Form::text($name = 'name', $value = '', 
                                                $attributes = array(
                                                    'id' => 'name',
                                                    'class' => 'form-control form-control-solid',
                                                    'readonly' => 'true'
                                                )) 
                                            }}
                                        </div>
                                    </div>
                                </div>
                                <div class="fv-row row">
                                    <div class="col-sm-6">
                                        <div class="form-group m-form__group">
                                            {{ Form::label('description', 'Item Description', ['class' => 'fs-6 fw-bold']) }}
                                            {{ 
                                                Form::text($name = 'description', $value = '', 
                                                $attributes = array(
                                                    'id' => 'description',
                                                    'class' => 'form-control form-control-solid',
                                                    'readonly' => 'true'
                                                )) 
                                            }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group m-form__group">
                                            {{ Form::label('remarks', 'Remarks', ['class' => 'fs-6 fw-bold']) }}
                                            {{ 
                                                Form::text($name = 'remarks', $value = '', 
                                                $attributes = array(
                                                    'id' => 'remarks',
                                                    'class' => 'form-control form-control-solid',
                                                    'readonly' => 'true'
                                                )) 
                                            }}
                                        </div>
                                    </div>
                                </div>
                                <div class="fv-row row">
                                    <div class="col-sm-4">
                                        <div class="form-group m-form__group">
                                            {{ Form::label('quantity_inventory', 'On hand Qty', ['class' => 'fs-6 fw-bold']) }}
                                            {{ 
                                                Form::text($name = 'quantity_inventory', $value = '', 
                                                $attributes = array(
                                                    'id' => 'quantity_inventory',
                                                    'class' => 'form-control form-control-solid',
                                                    'readonly' => 'true'
                                                )) 
                                            }}
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group m-form__group">
                                            {{ Form::label('uom_id', 'Unit of Measure', ['class' => 'fs-6 fw-bold']) }}
                                            {{ 
                                                Form::text($name = 'uom_id', $value = '', 
                                                $attributes = array(
                                                    'id' => 'uom_id',
                                                    'class' => 'form-control form-control-solid',
                                                    'readonly' => 'true'
                                                )) 
                                            }}
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group m-form__group">
                                            {{ Form::label('quantity_reserved', 'Reserved Qty', ['class' => 'fs-6 fw-bold']) }}
                                            {{ 
                                                Form::text($name = 'quantity_reserved', $value = '', 
                                                $attributes = array(
                                                    'id' => 'quantity_reserved',
                                                    'class' => 'form-control form-control-solid',
                                                    'readonly' => 'true'
                                                )) 
                                            }}
                                        </div>
                                    </div>
                                </div>
                                <div class="fv-row row">
                                    <div class="col-sm-4">
                                        <div class="form-group m-form__group">
                                            {{ Form::label('latest_cost', 'Unit Cost', ['class' => 'fs-6 fw-bold']) }}
                                            {{ 
                                                Form::text($name = 'latest_cost', $value = '', 
                                                $attributes = array(
                                                    'id' => 'latest_cost',
                                                    'class' => 'form-control form-control-solid',
                                                    'readonly' => 'true'
                                                )) 
                                            }}
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group m-form__group">
                                            {{ Form::label('total_cost', 'Total Cost', ['class' => 'fs-6 fw-bold']) }}
                                            {{ 
                                                Form::text($name = 'total_cost', $value = '', 
                                                $attributes = array(
                                                    'id' => 'total_cost',
                                                    'class' => 'form-control form-control-solid',
                                                    'readonly' => 'true'
                                                )) 
                                            }}
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group m-form__group">
                                            {{ Form::label('latest_cost', 'Weighted Cost', ['class' => 'fs-6 fw-bold']) }}
                                            {{ 
                                                Form::text($name = 'latest_cost', $value = '', 
                                                $attributes = array(
                                                    'id' => 'latest_cost',
                                                    'class' => 'form-control form-control-solid',
                                                    'readonly' => 'true'
                                                )) 
                                            }}
                                        </div>
                                    </div>
                                </div>   


                        </div>
                    </div>
                    <!-- LEFT COLUMN DETAILS END -->
                    <!-- RIGHT COLUMN DETAILS START -->
                    <div class="col-md-7 space-left">
                        <!-- ITEM DETAILS START -->
                        <div id="datatable-3" class="dataTables_wrapper">
                                        <div class="row">
                                        <h4 class="text-header">Item History</h4>
                                            <div class="col-md-4">
                                            </div>
                                            <div class="col-md-4">
                                            {{ Form::label('filter_type', 'Filter By:', ['class' => 'fs-6 fw-bold']) }}
                                            {{ Form::select('filter_type',array('1' =>'1-Current Month','2' =>'2-Last 3 Month','3' =>'3-Last 6 Month','4' =>'4-Current Year'), $filter_type, array('class' => 'form-control select','id'=>'filter_type')) }}
                                            </div>
                                            <div class="col-md-4">
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="itemTable" class="display dataTable table w-100 table-striped" aria-describedby="groupMenuInfo">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Transaction') }}</th>
                                                                <th>{{ __('Transaction Date') }}</th>
                                                                <th>{{ __('Transact By') }}</th>
                                                                <th class="sliced">{{ __('Received By') }}</th>
                                                                <th>{{ __('Based Qty') }}</th>
                                                                <th class="sliced">{{ __('Posted Qty') }}</th>
                                                                <th class="sliced">{{ __('Balanced Qty') }}</th>
                                                                <th class="sliced">{{ __('Reserved Qty') }}</th>
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
                        <!-- ITEM DETAILS END -->
                    </div>
                    <!-- RIGHT COLUMN DETAILS END -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>