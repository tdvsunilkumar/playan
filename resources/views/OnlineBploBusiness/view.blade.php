{{ Form::open(array('url' => 'Endrosement','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('busn_id',$busn_id, array('id' => 'busn_id')) }}
    {{ Form::hidden('payment_mode',$data->pm_id, array('id' => 'payment_mode')) }}
    {{ Form::hidden('year',$data->busn_tax_year, array('id' => 'year')) }}
     {{ Form::hidden('app_code',$data->app_code, array('id' => 'appcode')) }} 
     {{ Form::hidden('subclasshidden','', array('id' => 'subclasshidden')) }}  
     {{ Form::hidden('br_code','', array('id' => 'br_code')) }}  
    @php
        $dclass = ($data->busn_app_status==1 || $data->busn_app_status==0)?'':'disabled-status';
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
    </style>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="modal-footer">
                       <!--  <button type="button" class="btn  btn-primary">Link Business Application </button> -->
                        <button type="button" class="btn  btn-primary {{$dclass}}" data-busn_tax_year="" data-busn_id="" id="jqVerifyApplication">Verify Application</button>
                        @if($data->busn_app_status==7)
                         <button type="button" class="btn  btn-primary" id="activateApplication" value="{{$data->id}}">Activate Application</button>
                        @else
                         <button type="button" class="btn  btn-warning {{$dclass}}" value="{{$data->id}}" id="declineApplication" >Declined Application</button>
                        @endif
                    <!-- <input type="button" value="{{__('Close')}}" class="btn  btn-light" data-bs-dismiss="modal"> -->
                </div>
            </div>

            <div class="col-md-5">
                <!--- Start Status--->
                <div class="row" >
                   <div class=" border-right  space-right" style="border: 1px solid gray;margin: 0;float:left;">
                        <h4 class="text-header">Application Requirements</h4>
                        <p style="text-align:left; font-weight: bold;">Generate Barangay Clearance:<a style="color:red;">NO</a></p>
                        <div class="row">
                             <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('psicclass', __('Nature Of Business'),['class'=>'form-label']) }}
                                    <span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                        {{ Form::select('psicclass',$psicclassess,'', array('class' => 'form-control select3_view $dclass','id'=>'psicclass','required'=>'required')) }}
                                   </div>
                                   <span class="validate-err" id="err_psicclass"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('requirement_id', __('Requirements'),['class'=>'form-label']) }}
                                    <span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                      {{ Form::select('requirement',array(),'', array('class' => 'form-control select3_view $dclass','id'=>'requirement','required'=>'required')) }}
                                   </div>
                                </div>
                                <span class="validate-err" id="err_end_requirement_id"></span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
                                    <div class="form-icon-user">
                                        {{ Form::input('file','documentname','',array('class'=>'form-control','id'=>'documentname'))}}  
                                    </div>
                                    <span class="validate-err" id="err_documentname"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6"></div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <button type="button" style="float: right;" class="btn btn-primary {{$dclass}}" id="uploadAttachmentbtn">Upload File</button>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Document Title</th>
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

    



</div>


<script src="{{ asset('js/Bplo/view_application.js') }}"></script>

  