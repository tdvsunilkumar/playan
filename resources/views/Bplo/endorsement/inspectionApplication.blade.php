{{ Form::open(array('url' => 'Endrosement','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('bend_year',$data->bend_year, array('id' => 'bend_year')) }}
    {{ Form::hidden('bbendo_id',$data->bbendo_id, array('id' => 'bbendo_id')) }}
    {{ Form::hidden('bend_status',$data->bend_status, array('id' => 'bend_status')) }}
   
   
    <style type="text/css">
        .modal.show .modal-dialog {
            transform: none;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
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
            margin-top: 25%;
            transform: translate(-50%, -50%);
      }
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
                <!--- Start Status--->
                <div class="row" >
                   
                    


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
                                           
                                            <div class="col-lg-12 col-md-12 col-sm-12">
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
                                                    <button type="button" style="float: right;" class="btn btn-primary " id="uploadAttachmentInspection">Upload File</button>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                
                                                                <th>File Name</th>
                                                                <th>Attachment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <thead id="DocumentDtls">
                                                            <?php echo $data->document_detailsInspection?>
                                                            @if(empty($data->document_detailsInspection))
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
<script type="text/javascript">
    $(document).ready(function(){
        $('.iframe-full-height').on('load', function(){
            alert(this.contentDocument.body.scrollHeight)
            this.style.height='750px';
        });
    })
</script>

<script src="{{ asset('js/Bplo/add_Endrosement.js') }}?rand={{ rand(000,999) }}"></script>
  
