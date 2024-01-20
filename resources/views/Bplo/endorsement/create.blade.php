{{ Form::open(array('url' => 'Endrosement','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('bend_year',$data->bend_year, array('id' => 'bend_year')) }}
    {{ Form::hidden('end_tfoc_id',$data->end_tfoc_id, array('id' => 'end_tfoc_id')) }}
    {{ Form::hidden('end_fee_name',$data->end_fee_name, array('id' => 'end_fee_name')) }}
    {{ Form::hidden('bbendo_id',$data->bbendo_id, array('id' => 'bbendo_id')) }}
    {{ Form::hidden('bend_status',$data->bend_status, array('id' => 'bend_status')) }}
    {{ Form::hidden('force_mark_complete',$data->force_mark_complete, array('id' => 'force_mark_complete')) }}
    {{ Form::hidden('app_code',$data->app_type_id, array('id' => 'app_code')) }}
    {{ Form::hidden('payment_mode',$data->payment_mode, array('id' => 'payment_mode')) }}
    
    @php
        $dclass = ($data->bend_status==2 || $data->bend_status==3)?'disabled-status':'';
        $readOnly = ($isNational && $data->bbendo_id==1)?'readonly':'';
        $readOnly = ($data->bend_status>=2)?'readonly':$readOnly;
        $data->enddept_fee = ($isNational && $data->bbendo_id==1)?'':$data->enddept_fee;
    @endphp
    
    <style type="text/css">
         .assesment-heading thead th {font-size: unset !important;}
        .table thead th{ padding-top: 4px;padding-bottom: 4px; }
        .btn{padding: 0.4rem 1.3rem !important;font-size: 12px !important;}
        #assessmentModal{
                padding-top: 5%;
        }
        .assesment-heading thead tr th{
            color: black;
            background: white;
        }
        .btn-success{
            background-color: #59ae36 !important;
            border-color: #59ae36 !important;
        }
        table td, .table th{
            padding: 0.4rem 0.75rem !important;
            border-right: 1px solid #f1f1f1 !important;
        }
        .assesment-heading{border: 1px solid #f1f1f1;}
        .modal.form-sub {
            background: rgba(0,0,0,0.3);
        }
		
    </style>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="modal-footer">
                    @if($getBploBusinessStatus==6)
                    <h5 style="color:red;">Business Permit Issued Dated {{$getBploBusinessissueDate}}</h5>
                    @if($data->force_mark_complete==1)
                    @else
                    <button type="button" class="btn  btn-primary saveData {{$dclass}}" value="1">Save As Draft</button>
                    @endif
                    @if($data->bend_status==2)
                        <button type="button" class="btn  btn-success saveData {{$dclass}}" value="incomplete">Unmark as Complete</button>
                    @else
                       <button type="button" class="btn  btn-success saveData {{$dclass}}" value="2">Mark as Complete</button>
                    @endif

                    @if($data->bend_status==3)
                        <button type="button" class="btn  btn-success saveData" value="restore">Restore</button>
                    @else
                       <button type="button" class="btn  btn-warning saveData {{$dclass}}" value="3">Decline</button>
                    @endif
                    @else
                    @if($data->force_mark_complete==1)
                    @else
                    <button type="button" class="btn  btn-primary saveData {{$dclass}}" value="1">Save As Draft</button>
                    @endif
                    @if($data->bend_status==2)
                        <button type="button" class="btn  btn-success saveData" value="incomplete">Unmark as Complete</button>
                    @else
                       <button type="button" class="btn  btn-success saveData {{$dclass}}" value="2">Mark as Complete</button>
                    @endif

                    @if($data->bend_status==3)
                        <button type="button" class="btn  btn-success saveData" value="restore">Restore</button>
                    @else
                       <button type="button" class="btn  btn-warning saveData {{$dclass}}" value="3">Decline</button>
                    @endif
                    <!-- <input type="button" value="{{__('Close')}}" class="btn  btn-light" data-bs-dismiss="modal"> -->
                    @endif
                </div>
            </div>

            <div class="col-md-5">
                <!--- Start Status--->
                <div class="row" >
                    <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-headingone">
                                    <button class="accordion-button collapsed btn-primary btn-endorsementStatus{{$data->bend_status}}" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Status")}}</span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <button type="button" class="btn btn-endorsementStatus{{$data->bend_status}}">{{config('constants.arrBusEndorsementStatus')[(int)$data->bend_status]}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample2">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading1">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse1" aria-expanded="false" aria-controls="flush-collapse1">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Fees")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapse1" class="accordion-collapse collapse show" aria-labelledby="flush-heading1" data-bs-parent="#accordionFlushExample2">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6"></div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <button type="button" style="float: right;" class="btn  btn-primary" id="btnAssessment">View Initial Assessment</button>
                                            </div>
                                        </div><br>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Fees</th>
                                                                <th>Amount</th>
                                                            </tr>
                                                            <tr>
                                                                <td><?=$data->end_fee_name?></td>
                                                                <td>{{ Form::text('enddept_fee',$data->enddept_fee, array('class' => 'form-control numeric $dclass','maxlength'=>'150','placeholder'=>'0.00','id'=>'enddept_fee',$data->end_fee_name == null ? 'readOnly' : $readOnly)) }}</td>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                                @if($data->bbendo_id==1)
                                                    <p class="note"><i>This fee only for local government</i></p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample4">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading4">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse4" aria-expanded="false" aria-controls="flush-collapse4">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Documentary Requirements From BPLO")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapse4" class="accordion-collapse collapse show" aria-labelledby="flush-heading4" data-bs-parent="#accordionFlushExample4">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Document Title</th>
                                                                <th>Attachment</th>
                                                            </tr>
                                                            @if(isset($arrDocumentDtls))
                                                                @foreach($arrDocumentDtls AS $key=>$val)
                                                                    <tr>
                                                                        <td>{{$val->req_description}}</td>
                                                                         <td><a class="btn" href="{{asset('uploads/bplo_business_req_doc') }}/{{$val->attachment}}" target='_blank'><i class='ti-download'></i></a></td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif

                                                            @if(count($arrDocumentDtls)==0)
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
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    {{ Form::label('requirement_id', __('Requirements'),['class'=>'form-label']) }}
                                                    <span class="text-danger">*</span>
                                                    <div class="form-icon-user">
                                                        {{ Form::select('end_requirement_id',$arrReq,'', array('class' => 'form-control $dclass','id'=>'end_requirement_id')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_end_requirement_id"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    {{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('file','document_name','',array('class'=>'form-control $dclass'))}}  
                                                    </div>
                                                    <span class="validate-err" id="err_document"></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6"></div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <button type="button" style="float: right;" class="btn btn-primary {{$dclass}}" id="uploadAttachment">Upload File</button>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Document Title</th>
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


                </div>
                <!--- End Status --->
            </div>    


            <!--- Start Print Details--->
            <div class="col-md-7 pdf-details">
                <div class="form-group" style="text-align: center;">
                  <!-- <button type="button" class="btn  btn-primary"> <i class="ti-printer text-white fs-12"></i> Print</button> -->
                  <iframe id="pdf-iframe" src="{{$sunmary_url}}" width="100%" height="1200px"></iframe> 

                </div>
            </div>    
            <!--- End Print Details--->
        </div> 
    </div>

    



<div class="modal fade form-sub" id="assessmentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
           <div class="modal-header">
                <h4 class="modal-title">Initial Assessment</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="container"></div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table assesment-heading">
                            <thead>
                                <tr>
                                    <th>Business Identification No.</th>
                                    <th><?=$data->busns_id_no?></th>
                                </tr>
                                <tr>
                                    <th>Business Name</th>
                                    <th><?=$data->busn_name?></th>
                                </tr>
                                <tr >
                                    <th>Fee Name</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <thead id="feeDetails">
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- <div class="modal-footer"> 
                   <button class="btn btn-primary"> <i class="ti-printer text-white fs-12"></i> Print</button>
                </div> -->
            </div>
        </div>
    </div>
</div>
{{Form::close()}}
<script type="text/javascript">
    $(document).ready(function(){
        $('.iframe-full-height').on('load', function(){
            this.style.height='750px';
        });
    })
</script>

<script src="{{ asset('js/Bplo/add_Endrosement.js') }}?rand={{ rand(000,999) }}"></script>
