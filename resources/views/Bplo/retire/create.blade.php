{{ Form::open(array('url' => 'business-permit-retire/store','class'=>'formDtls','id'=>'mainForm')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('retire_employee_no_lgu',$data->id, array('id' => 'retire_employee_no_lgu')) }}
{{ Form::hidden('retire_vehicle_no_van_truck',$data->retire_vehicle_no_van_truck, array('id' => 'retire_vehicle_no_van_truck')) }}
{{ Form::hidden('retire_vehicle_no_motorcycle',$data->retire_vehicle_no_motorcycle, array('id' => 'retire_vehicle_no_motorcycle')) }}

{{ Form::hidden('submitAction','', array('id' => 'submitAction')) }}
@php
    $readonly = ($data->id>0)?'readonly':'';
    $select3 = ($data->id>0)?'disabled-field':'select3';
    $disableBtn = ($data->retire_status>0)?'disabled':'';
@endphp
<link href="https://fonts.cdnfonts.com/css/digital-numbers" rel="stylesheet">
<style type="text/css">
    .lable-main-title{ color: #00000078; padding-bottom:10px }
    .from-to-dtls .row{margin-bottom: -14px !important;}
    .box-border{ border: 1px solid #80808059;margin: 6px -2px 6px -4px; }
    .basicinfodiv .row { margin: 6px 0px 6px 0px;}
    .wht-color{color: #ffffff;}
    .fee-details th{
        text-align: right; !important;
    }
    .fee-details th:nth-child(1), .fee-details th:nth-child(2), .fee-details th:nth-child(3){
        text-align: left; !important;
    }
    .fee-details td:nth-child(3), .fee-details td:nth-child(4), .fee-details td:nth-child(5), .fee-details td:nth-child(6) {
        background: #80808052;
        text-align: right;
    }
    .fee-details td:nth-child(2), .fee-details td:nth-child(3){
        background: #20b7cc42;
        text-align: left;
    }
    .fee-details tr:last-child{
        background: #80808052;
    }
    .red{
        background: red !important;
        color:#fff;
        font-weight: bold;
        text-align: right !important;
    }
    .label-value{color: gray;}
    .basicinfodiv input {height: 35px;}
    .radio-check input{height: 22px;width: 22px;}
    .radio-check label {padding: 7px;}
    .radio-check{padding-top: 8px;}
    .btn-primary, .btn-danger {cursor: pointer;}
    .delete-btn-dtls{padding-top: 9px;}
    .main-form{padding: 0px;padding-left: 5px;}
    #footer-details .modal-footer{border-top:unset !important;}
    #footer-details{border-top: 1px solid #f1f1f1;}
    .form-check-inline{margin-bottom: 0px;}
    .payment-dtls .table thead th{padding:unset;padding: 10px;font-size: 10px; text-transform: unset;}
    .payment-dtls .table td{padding:unset;padding: 10px;font-size: 10px; text-transform: unset;}
    #jqFeeDetails input{text-align: right;}
    .currency-sign{margin-top: -17px !important;height: 33px !important;}
    .currency-sign span{font-size: 12px;margin-top: 7px;}
</style>
    <div class="modal-body payment-dtls">
        <div class="row">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('busn_id', __('Search Business Details'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{ Form::select('busn_id',$arrBussNo,$data->busn_id, array('class' => 'form-control busn_id '.$select3,'id'=>'busn_id','required'=>'required')) }}
                        </div>
                        <span class="validate-err" id="err_busn_id" style="text-align: right;"></span>
                    </div>
                </div>
            </div>
             <div class="col-lg-4 col-md-4 col-sm-4 main-form" >  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-heading1">
                            <button class="accordion-button btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse1" aria-expanded="false" aria-controls="flush-collapse1">
                                <h6 class="sub-title accordiantitle">
                                    <i class="ti-menu-alt text-white fs-12"></i>
                                    <span class="accordiantitle-icon">{{__("Business Information")}}
                                    </span>
                                </h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
                            <div class="basicinfodiv">
                                <div class="box-border">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('busn_id_no', __('Business Identification No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <div class="form-icon-user">
                                                    {{Form::text('busn_id_no','',array('class'=>'form-control','required'=>'required','readonly'=>'readonly'))}}
                                                </div>
                                                <span class="validate-err" id="err_busn_id_no" style="text-align: right;"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('busn_name', __('Business Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                <div class="form-icon-user">
                                                    {{Form::text('busn_name','',array('class'=>'form-control','required'=>'required','readonly'=>'readonly'))}}
                                                </div>
                                                <span class="validate-err" id="err_busn_name" style="text-align: right;"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="from-to-dtls">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    {{ Form::label('retire_date_start', __('Date Established'),['class'=>'form-label']) }}
                                                    <span class="text-danger">*</span>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                       {{Form::date('retire_date_start',$data->retire_date_start,array('class'=>'form-control','required'=>'required'))}}
                                                    </div>
                                                </div>
                                            </div>
                                           
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    {{ Form::label('to', __('Date Closed'),['class'=>'form-label']) }}
                                                    <span class="text-danger">*</span>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                       {{Form::date('retire_date_closed',$data->retire_date_closed,array('class'=>'form-control','id'=>'retire_date_closed','required'=>'required'))}}
                                                    </div>
                                                    <span class="validate-err" id="err_retire_date_closed"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               <div class="box-border">
                                    <div class="from-to-dtls">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    {{ Form::label('retire_bldg_area', __('Business Area(in sq.m)'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{Form::text('retire_bldg_area',$data->retire_bldg_area,array('class'=>'form-control numeric'))}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    {{ Form::label('retire_bldg_total_floor_area', __('Total Floor Area'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{Form::text('retire_bldg_total_floor_area',$data->retire_bldg_total_floor_area,array('class'=>'form-control numeric'))}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    {{ Form::label('retire_employee_no_female', __('No. Of Female'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{Form::text('retire_employee_no_female',$data->retire_employee_no_female,array('class'=>'form-control numeric calculateEmp'))}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    {{ Form::label('retire_employee_no_male', __('No. Of Male'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{Form::text('retire_employee_no_male',$data->retire_employee_no_male,array('class'=>'form-control numeric calculateEmp'))}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    {{ Form::label('retire_employee_total_no', __('Total Employee'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{Form::text('retire_employee_total_no',$data->retire_employee_total_no,array('class'=>'form-control numeric','readonly'=>'readonly'))}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="box-border">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h7 class="lable-main-title">Apply Retirement For?<span class="text-danger">*</span></h7> 
                                            <div class="d-flex radio-check">
                                                <div class="form-check form-check-inline form-group">
                                                    {{ Form::radio('retire_application_type', '2', ($data->retire_application_type =='2')?true:false, array('id'=>'entier_business','class'=>'form-check-input', 'required'=>'required')) }}
                                                    {{ Form::label('entier_business', __('Entire Business'),['class'=>'form-label']) }}
                                                    <br>
                                                    {{ Form::radio('retire_application_type', '1',  ($data->retire_application_type =='1')?true:false, array('id'=>'per_line','class'=>'form-check-input', 'required'=>'required','disabled')) }}
                                                    {{ Form::label('per_line', __('Per Line of Business'),['class'=>'form-label']) }}
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="box-border">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h7 class="lable-main-title">Reasons For Retirement<span class="text-danger">*</span></h7> 
                                             <span class="validate-err" id="err_retire_reason_ids"></span>
                                            <div class="d-flex radio-check">
                                                <div class="form-check form-check-inline form-group">
                                                    @php $i=0; @endphp
                                                    @foreach($arrRetireReason as $key=>$val)
                                                        @php
                                                            $checked='';
                                                            if($data->id>0){
                                                                $arr = explode(",",$data->retire_reason_ids);
                                                                $checked = (in_array($key,$arr))?true:false;
                                                            }
                                                           
                                                        @endphp
                                                        {{ Form::checkbox('retire_reason_ids[]', $key, $checked, array('id'=>'reason_'.$key,'class'=>'form-check-input')) }}
                                                        {{ Form::label('reason_'.$key, __($val),['class'=>'form-label']) }}<br>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            {{Form::label('retire_reason_remarks',__('Reason Remarks'),['class'=>'form-label'])}}
                                            {{Form::textarea('retire_reason_remarks',$data->retire_reason_remarks,array('class'=>'form-control','id'=>'retire_reason_remarks','rows'=>'2'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="box-border">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {{Form::label('retire_remarks',__('Remarks'),['class'=>'form-label'])}}
                                            {{Form::textarea('retire_remarks',$data->retire_remarks,array('class'=>'form-control','id'=>'retire_remarks','rows'=>'2'))}}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-8 col-sm-8 main-form">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-heading2">
                            <button class="accordion-button btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse2" aria-expanded="false" aria-controls="flush-collapse2">
                                <h6 class="sub-title accordiantitle">
                                    <i class="ti-menu-alt text-white fs-12"></i>
                                    <span class="accordiantitle-icon">{{__("Line Of Business")}}
                                    </span>
                                </h6>
                            </button>
                        </h6>
                        <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-heading2">
                            <div class="basicinfodiv">
                                <div class="box-border">
                                    <div class="row ">
                                        <div class="col-xl-12 fee-details">
                                            <div class="card">
                                                <span class="validate-err" id="err_nature_of_buss"></span>
                                                <div class="card-body table-border-style">
                                                    <div class="table-responsive" id="jqLineOfBuss">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{ Form::checkbox('check_all',1,'true', array('style'=>'height:unset;','class'=>'check_all disabled-field')) }}</th>
                                                                    <th>Code</th>
                                                                    <th>Description</th>
                                                                    <th>Capital Investment</th>
                                                                    <th>Gross Income Essential</th>
                                                                    <th>Gross Income Non-Essential</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="jqFeeDetails"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($data->id>0)
                    <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample3">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading3">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Documentary Requirements")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapse3" class="accordion-collapse collapse show" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6" id="divSubclass">
                                                <div class="form-group">
                                                    {{ Form::label('sub_class_id', __('Line Of Business'),['class'=>'form-label']) }}
                                                    <span class="text-danger">*</span>
                                                    <div class="form-icon-user">
                                                        {{ Form::select('sub_class_id',$arrSubClass,'', array('class' => 'form-control','id'=>'sub_class_id')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_sub_class_id"></span>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4" id="divRequirement">
                                                <div class="form-group">
                                                    {{ Form::label('requirement_id', __('Requirements'),['class'=>'form-label']) }}
                                                    <span class="text-danger">*</span>
                                                    <div class="form-icon-user">
                                                        {{ Form::select('requirement_id',array(""=>"Please Select"),'', array('class' => 'form-control','id'=>'requirement_id')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_requirement_id"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('file','document_name','',array('class'=>'form-control'))}}  
                                                    </div>
                                                    <span class="validate-err" id="err_document"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <button type="button" style="margin-top: 25px;" class="btn btn-primary" id="uploadAttachment">Upload File</button>
                                            </div>
                                           
                                            <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Line Of Business</th>
                                                                <th>Requirement</th>
                                                                <th>Attachment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <thead id="DocumentDtls">
                                                            <?php echo $arrDocumentDetailsHtml?>
                                                            @if(empty($arrDocumentDetailsHtml))
                                                            <tr>
                                                                <td colspan="3"><i>No results found.</i></td>
                                                            </tr>
                                                            @endif 
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div id="footer-details">
            <div class="modal-footer" style="float:right;">
                <h6 class="note " id="commonNote"></h6>
                @if($data->id>0 && $data->retire_status==0)
                    <input type="button" name="deleteRetirement"  value="Delete Save As Draft" class="btn  btn-danger" id="jqdeleteRetirement">
                @endif
                <input type="submit" name="submit"  value="Save As Draft" class="btn btn-primary saveData" id="jqSaveDraft" {{$disableBtn}}>
                <input type="submit" name="submit"  value="Submit" class="btn btn-primary saveData" id="jqPaidAmount" {{$disableBtn}}>
                <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
            </div>
        </div>
    </div>
{{Form::close()}}

<script src="{{asset('js/Bplo/add_BusinessPermitRetire.js')}}"></script>

  