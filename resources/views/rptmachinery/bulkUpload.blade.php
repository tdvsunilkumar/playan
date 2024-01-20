{{ Form::open(array('url' => 'business-permit/application','id'=>'bulkUploadClients')) }}
    <div class="modal-body">
        <div class="row pt10" >
            <!--------------- Owners Information Start Here---------------->
            <div class="row">
                <span class="validate-err" id="commonError"></span>
                 <div class="col-lg-10 col-md-10 col-sm-10">
                    <div class="form-group m-form__group required">
                        {{ Form::label('upload_type', 'Upload Type', ['class' => '']) }}<span class="text-danger">*</span> 
                        {{
                            Form::select('upload_type', $arrType, $value = '', ['id' => 'upload_type', 'class' => 'form-control select3','required'=>'required'])
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 col-md-10 col-sm-10" id="DivBusiness">
                    <div class="form-group">
                        {{Form::label('bulkUploadFile1',__('Upload Machine Tax Declaration[XLSX File]-1st'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{Form::file('bulkUploadFile1',array('class'=>'form-control','id'=>'bulkUploadFile1','accept'=>'.csv'))}}
                        </div>
                        <a href="{{url('rptmachinery/downloadmachineTDTemplate')}}?status=1" target="_blank">Download Machine Tax Declaration Template[XLSX File]</a>
                    </div>
                </div>

                

                <div class="col-lg-10 col-md-10 col-sm-10 hide" id="DivPsic">
                    <div class="form-group">
                        {{Form::label('bulkUploadFile2',__('Upload Machine Previous Owner Tax Declaration[XLSX File]-2nd'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{Form::file('bulkUploadFile2',array('class'=>'form-control', 'id'=>'bulkUploadFile2','accept'=>'.csv'))}}
                        </div>
                        <a href="{{url('rptmachinery/downloadmachineTDTemplate')}}?status=9" target="_blank">Download Machine Previous Owner Tax Declaration Template[XLSX File]</a>
                    </div>
                </div>

                <div class="col-lg-10 col-md-10 col-sm-10 hide" id="DivMeasure">
                    <div class="form-group">
                        {{Form::label('bulkUploadFile3',__('Upload Machine Appraisals'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{Form::file('bulkUploadFile3',array('class'=>'form-control', 'id'=>'bulkUploadFile3','accept'=>'.csv'))}}
                        </div>
                        <a href="{{url('rptmachinery/downloadMachineAppraisalTemplate')}}" target="_blank">Download Real Property: Machine Appraisal Template[XLSX File]</a>
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
<script src="{{ asset('js/rptMachineBulkUpload.js') }}"></script> 
