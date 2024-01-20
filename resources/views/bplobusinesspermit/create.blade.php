{{ Form::open(array('url' => 'bplobusinesspermit','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$bplo_business->id, array('id' => 'id')) }}
    {{ Form::hidden('app_type_id',$data->app_type_id, array('id' => 'app_type_id')) }}
    {{ Form::hidden('issuance_id',$data->issuance_id, array('id' => 'issuance_id')) }}
    {{ Form::hidden('bpi_year',$data->bpi_year, array('id' => 'bpi_year')) }}
    {{ Form::hidden('bpi_issued_date',$data->bpi_issued_date, array('id' => 'bpi_issued_date')) }}
    <style type="text/css">
         .assesment-heading thead th {font-size: unset !important;}
/*        .table thead th{ padding-top: 4px;padding-bottom: 4px; }*/
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
       /* table td, .table th{
            padding: 0.4rem 1.3rem !important;
/*            border-right: 1px solid #ffff !important;*/
        }*/
        .assesment-heading{border: 1px solid #ccc;}
       
    </style>
     @php
        $showButton = false;
        $targetIds = ["1", "2", "3", "4"];
    @endphp

    @foreach($endorsment as $endorsments)
        @if(in_array($endorsments->id, $targetIds) && $endorsments->bend_status == "2")
            @php
                $targetIds = array_diff($targetIds, [$endorsments->id]);
            @endphp
        @endif
    @endforeach

    @if(empty($targetIds))
        @php
            $showButton = true;
        @endphp
    @endif
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="modal-footer">
                    <button type="button" id="Approved" class="btn  btn-primary saveData " value="1" style="display:none;" {{ $showButton ? '' : 'disabled' }}>Issue Business Permit</button>
                     <button type="button" id="cancel" class="btn  btn-danger saveData " value="2" style="display:none;">Cancel Issued Permit</button>
                   
                   <!-- <input type="button" value="{{__('Close')}}" class="btn  btn-dark" data-bs-dismiss="modal"> -->
                 </div>
            </div>

            <div class="col-md-5">
                <!--- Start Status--->
                <div class="row" >
                    <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
                        <div  class="accordion accordion-flush" >
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-headingone" >
                                    <button class="accordion-button collapsed btn-primary btn-endorsementStatus" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Business Permit Details")}}</span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample" >
                                    <div class="basicinfodiv">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    {{ Form::label('requirement_id', __('Business Identification No.'),['class'=>'form-label']) }}
                                                    <span class="text-danger">*</span>
                                                    <div class="form-icon-user">
                                                     {{ Form::text('document_name',$bplo_business->busns_id_no,array('class'=>'form-control','readonly'))}}     
                                                    </div>
                                                    <span class="validate-err" id=""></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    {{ Form::label('permit_no', __('Business Permit No.'),['class'=>'form-label']) }}
                                                    <span class="text-danger">*</span>
                                                    <div class="form-icon-user">
                                                     {{ Form::text('permit_no','',array('class'=>'form-control','readonly','id'=>'permit_no'))}}     
                                                    </div>
                                                    <span class="validate-err" id=""></span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    {{ Form::label('bpi_remarks', __('Remarks'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::textarea('bpi_remarks','',array('class'=>'form-control','rows'=>'2','id'=>'bpi_remarks'))}}  
                                                    </div>
                                                    <span class="validate-err" id=""></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    {{ Form::label('business_plate_no', __('Business Plate No.'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::text('business_plate_no','',array('class'=>'form-control','id'=>'business_plate_no'))}}  
                                                    </div>
                                                    <span class="validate-err" id=""></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6"></div>
                                               

                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <button type="button" style="float: right;" class="btn btn-primary" id="saveData" {{ $showButton ? '' : 'disabled' }}>Save Changes</button>
                                                </div>



                                            </div>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExampl3">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading3" >
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3" >
                                        <h6 class="sub-title accordiantitle" >
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon" >{{__("Endorsement")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapse3" class="accordion-collapse collapse show" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                           <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th style='border:1px solid #ccc;'>Department</th>
                                                                <th style='border:1px solid #ccc;'>Status</th>
                                                                <th style='border:1px solid #ccc;'>Documents</th>
                                                            </tr>
                                                        </thead>
                                                           @foreach($endorsment AS $endorsments)
                                                            <tr>
                                                                <td style='border:1px solid #ccc;'>{{$endorsments->edept_name}}</td>
                                                                <td  style='border:1px solid #ccc;'>
                                                                   @if($endorsments->bend_status==0)
                                                                   Not Started
                                                                   @elseif($endorsments->bend_status==1)
                                                                   In-Progress
                                                                   @elseif($endorsments->bend_status==2)
                                                                   Completed
                                                                   @elseif($endorsments->bend_status==3)
                                                                   Decline
                                                                   @endif
                                                                </td>
                                                                
                                                                <td style='border:1px solid #ccc;'>
                                                                    @if($endorsments->id==1)
                                                                   <div class="action-btn bg-info ms-2">
                                                                        <button type="button" style="float: right;" class="btn  btn-primary" id="btnOrderofPayment"><i class="ti-eye text-white"></i></button>

                                                                            
                                                                        </a>
                                                                    </div>
                                                                   @elseif($endorsments->id==2)
                                                                   <div class="action-btn bg-info ms-2">
                                                                        <button type="button" style="float: right;" class="btn  btn-primary" id="btnOrderofPayment1"><i class="ti-eye text-white"></i></button>

                                                                            
                                                                        </a>
                                                                    </div>
                                                                   @elseif($endorsments->id==3)
                                                                   <div class="action-btn bg-info ms-2">
                                                                        <button type="button" style="float: right;" class="btn  btn-primary" id="btnOrderofPayment2"><i class="ti-eye text-white"></i></button>

                                                                            
                                                                        </a>
                                                                    </div>
                                                                   @elseif($endorsments->id==4)
                                                                   <div class="action-btn bg-info ms-2">
                                                                        <button type="button" style="float: right;" class="btn  btn-primary" id="btnOrderofPayment3"><i class="ti-eye text-white"></i></button>

                                                                            
                                                                        </a>
                                                                    </div>
                                                                   @endif
                                                                    
                                                                </td>
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
              <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExampl10">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading10" >
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse10" aria-expanded="false" aria-controls="flush-collapse10" >
                                        <h6 class="sub-title accordiantitle" >
                                            <i class="fa fa-suitcase text-white fs-12"></i>
                                            <span class="accordiantitle-icon" >{{__("Signed Business Permit")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapse10" class="accordion-collapse collapse show" aria-labelledby="flush-heading10" data-bs-parent="#accordionFlushExample10">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                           <div class="row">
                                            <div class="col-lg-10 col-md-10 col-sm-10">
                                                <div class="form-group">
                                                    {{ Form::label('document_name', __('Upload copy of the signed and issued Business Permit'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('file','document_name','',array('class'=>'form-control '))}}  
                                                    </div>
                                                    <span class="validate-err" id="err_document"></span>
                                                </div>
                                            </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2" style="padding-top: 27px;">
                                                    <button type="button" style="float: right;" class="btn btn-primary " id="uploadAttachment">Upload File</button>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th style='border:1px solid #ccc;'>Document Title</th>
                                                                <th style='border:1px solid #ccc;'>Attachment</th>
                                                                <th style='border:1px solid #ccc;'>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <thead id="DocumentDtls">

                                                          
                                                            @if(empty($data->document_detailsInspection))
                                                            <tr>
                                                                <td colspan="3" ><i>No results found.</i></td>
                                                            </tr>
                                                            @else
                                                             <?php echo $data->document_detailsInspection?>
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

    



<div class="modal fade" id="assessmentModal">
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
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>Business Name</th>
                                    <th></th>
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
                <div class="modal-footer"> 
                   <button class="btn btn-primary"> <i class="ti-printer text-white fs-12"></i> Print</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="orderofpaymentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="
        position: relative;
            display: flex;
            flex-direction: column;
            width: 80%;
            pointer-events: auto;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            outline: 0;
           float: left;
           margin-left: 50%;
           margin-top: 50%;
           transform: translate(-50%, -50%);">
           <div class="modal-header">

                <h4 class="modal-title">Fire Protection Uploads</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="container">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExampl3">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading3" >
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3" >
                                        <h6 class="sub-title accordiantitle" >
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon" >{{__("Document Details")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapse3" class="accordion-collapse collapse show" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                           <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th style='border:1px solid #ccc;'>Form | Module Name</th>
                                                                <th style='border:1px solid #ccc;'>Document Title</th>
                                                                <th style='border:1px solid #ccc;'>Action</th>
                                                            </tr>
                                                            @if($ApplicationFireProtection)
                                                            @foreach($ApplicationFireProtection as $document)
                                                            <tr>
                                                                <td>Fire Protection</td>
                                                                <td>{{$document->filename}}</td>
                                                                <td><a class="btn" href="../uploads/document_requirement/{{$document->filename}}" target='_blank'><i class='ti-download'></i></a></td>
                                                            </tr>
                                                            @endforeach
                                                            @else
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
                     
                       
                </div>
                </div>
                </div>
        </div>
    </div>
                <div class="modal fade" id="orderofpaymentModal2">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="
        position: relative;
            display: flex;
            flex-direction: column;
            width: 80%;
            pointer-events: auto;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            outline: 0;
           float: left;
           margin-left: 50%;
           margin-top: 50%;
           transform: translate(-50%, -50%);">
           <div class="modal-header">

                <h4 class="modal-title">Planning & Development Uploads</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="container">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExampl3">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading3" >
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3" >
                                        <h6 class="sub-title accordiantitle" >
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon" >{{__("Document Details")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapse3" class="accordion-collapse collapse show" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                           <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th style='border:1px solid #ccc;'>Form | Module Name</th>
                                                                <th style='border:1px solid #ccc;'>Document Title</th>
                                                                <th style='border:1px solid #ccc;'>Action</th>
                                                            </tr>
                                                           @if($Planing)
                                                            @foreach($Planing as $document)
                                                            <tr>
                                                                <td>Planning & Development Uploads</td>
                                                                <td>{{$document->filename}}</td>
                                                                <td><a class="btn" href="../uploads/document_requirement/{{$document->filename}}" target='_blank'><i class='ti-download'></i></a></td>
                                                            </tr>
                                                            @endforeach
                                                            @else
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
                     
                       
                </div>
                </div>
                </div>
        </div>
    </div>
    <div class="modal fade" id="orderofpaymentModal3">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="
        position: relative;
            display: flex;
            flex-direction: column;
            width: 80%;
            pointer-events: auto;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            outline: 0;
           float: left;
           margin-left: 50%;
           margin-top: 50%;
           transform: translate(-50%, -50%);">
           <div class="modal-header">

                <h4 class="modal-title">Health & Safety Uploads</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="container">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExampl3">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading3" >
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3" >
                                        <h6 class="sub-title accordiantitle" >
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon" >{{__("Document Details")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>

                                <div id="flush-collapse3" class="accordion-collapse collapse show" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                           <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th style='border:1px solid #ccc;'>Form | Module Name</th>
                                                                <th style='border:1px solid #ccc;'>Document Title</th>
                                                                <th style='border:1px solid #ccc;'>Action</th>
                                                            </tr>
                                                            
                                                            @if($healthArray)
                                                            @foreach($healthArray as $document)
                                                            <tr>
                                                                <td>Health & Safety Uploads</td>
                                                                <td>{{$document->filename}}</td>
                                                                <td><a class="btn" href="../uploads/document_requirement/{{$document->filename}}" target='_blank'><i class='ti-download'></i></a></td>
                                                            </tr>
                                                            @endforeach
                                                            @else
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
                     
                       
                </div>
                </div>
                </div>
        </div>
    </div>
    <div class="modal fade" id="orderofpaymentModal4">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="
        position: relative;
            display: flex;
            flex-direction: column;
            width: 80%;
            pointer-events: auto;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            outline: 0;
           float: left;
           margin-left: 50%;
           margin-top: 50%;
           transform: translate(-50%, -50%);">
           <div class="modal-header">

                <h4 class="modal-title">Environmental Uploads</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="container">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExampl3">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading3" >
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3" >
                                        <h6 class="sub-title accordiantitle" >
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon" >{{__("Document Details")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                <div id="flush-collapse3" class="accordion-collapse collapse show" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                                    <div class="basicinfodiv">
                                        <div class="row">
                                           <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th style='border:1px solid #ccc;'>Form | Module Name</th>
                                                                <th style='border:1px solid #ccc;'>Document Title</th>
                                                                <th style='border:1px solid #ccc;'>Action</th>
                                                            </tr>
                                                           @if($EnvReport)
                                                            @foreach($EnvReport as $document)
                                                            <tr>
                                                                <td>Environmental Uploads</td>
                                                                <td>{{$document->filename}}</td>
                                                                <td><a class="btn" href="../uploads/document_requirement/{{$document->filename}}" target='_blank'><i class='ti-download'></i></a></td>
                                                            </tr>
                                                            
                                                            @endforeach
                                                            @else
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
                     
                       
                </div>
                </div>
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

<script src="{{ asset('js/add_bplobusinesspermit.js') }}?rand={{ rand(000,999) }}"></script>
  