{{ Form::open(array('url' => 'bploclients','id'=>'bulkUploadClients')) }}
    <div class="modal-body">
        <div class="row pt10" >
            <!--------------- Owners Information Start Here---------------->
            <div class="row">
                <span class="validate-err" id="commonError"></span>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="form-group">
                        {{Form::label('bulkUploadFile',__('Upload CSV File'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{Form::file('bulkUploadFile',array('class'=>'form-control','required'=>'required', 'id'=>'bulkUploadFile','accept'=>'.csv'))}}
                        </div>
                        <span class="validate-err" id="err_ba_p_first_name"></span>
                        <a href="{{asset('assets/storage/uploads/sample/sample-taxpayers.csv')}}" target="_blank">Sample Taxypayers CSV file</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal" id="canelBtn">

                    <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                        <input type="submit" name="submit" value="Validate" class="btn btn-primary setBtntype" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;border-radius: 5px;" btn_type="validateRecords">
                    </div>

                    <div class="button hide" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;" id="DivUpload">
                        <i class="ti-import"></i>
                        <input type="submit" name="submit" value="Upload" class="btn btn-primary setBtntype" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;border-radius: 5px;" id="uploadsFile"  btn_type="uploadsFile">
                    </div>
                </div>
            </div>
        </div>
    </div>
 {{Form::close()}}
<script src="{{ asset('js/Bplo/taxpayerBulkupload.js') }}"></script> 
