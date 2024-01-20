{{ Form::open(array('url' => 'Endrosement','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('bri_issued_byold',$data->bri_issued_byold, array('id' => 'bri_issued_byold')) }}
    {{ Form::hidden('busn_id',$data->busn_id, array('id' => 'busn_id')) }}
    {{ Form::hidden('retire_year',$data->retire_year, array('id' => 'retire_year')) }}
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
                    @if($data->status =='0')
                    <button type="button" class="btn  btn-primary updatestatus" value="1">Issue Retirement Certificate</button>
                    @else
                       <button type="button" class="btn  btn-warning updatestatus" value="0">Cancel Certificate</button>
                    @endif
                    <!-- <input type="button" value="{{__('Close')}}" class="btn  btn-light" data-bs-dismiss="modal"> -->
                </div>
            </div>

            <div class="col-md-5">
                <!--- Start Status--->
                <div class="row" >
                    <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-headingone">
                                    <button class="accordion-button collapsed btn-primary btn-endorsementStatus" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Retirement Details")}}</span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    {{ Form::label('businessno', __('Business Identification No.'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('text','businessno',$data->busns_id_no,array('class'=>'form-control disabled-field'))}}  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    {{ Form::label('businessnm', __('Business Name'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('text','businessnm',$data->busn_name,array('class'=>'form-control disabled-field'))}}  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('establieshed', __('Established'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('text','establieshed',$data->retire_date_start,array('class'=>'form-control disabled-field'))}}  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('actualclosure', __('Actual Closure'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('text','actualclosure',$data->retire_date_closed,array('class'=>'form-control disabled-field'))}}  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('certdate', __('Closure Certificate Date'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('text','certdate',$data->bri_issued_date,array('class'=>'form-control disabled-field'))}}  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    {{ Form::label('remark', __('Remark'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('text','remark',$data->bri_remarks,array('class'=>'form-control','id'=>'remark'))}}  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8">
                                                <div class="form-group">
                                                    {{ Form::label('bri_issued_by', __('Business Permit Personnel'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::select('bri_issued_by',$employee,$data->bri_issued_by, array('class' => 'form-control','id'=>'bri_issued_by')) }}
                                                    </div>
                                                </div>
                                                <span class="validate-err" id="err_bri_issued_by"></span>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('position', __('Position'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('text','position',$data->bri_issued_position,array('class'=>'form-control','id'=>'position'))}}  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                            <button type="button" style="float: right;"  class="btn btn-primary" id="savedata">Save Changes</button>
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
                                            <span class="accordiantitle-icon">{{__("Line Of Business")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapse1" class="accordion-collapse collapse show" aria-labelledby="flush-heading1" data-bs-parent="#accordionFlushExample2">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Checkbox</th>
                                                                <th>Code</th>
                                                                <th>Line Of Business</th>
                                                            </tr>
                                                        </thead>
                                                         @foreach($arrClss as  $key => $val)
                                                         <tr>
                                                            <td>
                                                                {{ Form::checkbox('ebot_idshow', '', array('id'=>'ebot_idshow','class'=>'disabled-field','disabled'=>'true')) }}</td>
                                                            <td>{{$val->subclass_code}}</td>
                                                             <td>{{$val->subclass_description}}</td>
                                                         </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
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
                                                        </thead>
                                                         <thead id="DocumentDtlsstatis">
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
                      <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample3">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading3">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Uploads")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapse3" class="accordion-collapse collapse show" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-lg-9 col-md-9 col-sm-9" >
                                                <div class="form-group">
                                                    {{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('file','documentname','',array('class'=>'form-control','id'=>'documentname'))}}  
                                                    </div>
                                                    <span class="validate-err" id="err_document"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3" style="padding-top: 27px;">
                                                    <button type="button" style="height: 35px;" class="btn btn-primary" id="uploadAttachmentbtn">Upload File</button>
                                                </div>
                                            
                                            <div class="col-lg-12 col-md-12 col-sm-12">
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
                                                             <?php echo $data->document_details?>
                                                            @if(empty($data->document_details))
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
         <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-info" data-bs-dismiss="modal">
            </div>    
    </div>


</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('.iframe-full-height').on('load', function(){
            alert(this.contentDocument.body.scrollHeight)
            this.style.height='750px';
        });
    })
</script>

<script src="{{ asset('js/Bplo/add_certissuance.js') }}"></script>
  