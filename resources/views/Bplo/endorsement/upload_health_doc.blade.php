    {{ Form::hidden('id',$id, array('id' => 'id')) }}
    <style type="text/css">
        .accordion-button::after{background-image: url();}
         .field-requirement-details-status {
            border-bottom: 1px solid #f1f1f1;
            font-size: 13px;
            color: black;
            background: #8080802e;
            text-transform: uppercase;
            margin: 20px 0px 6px 0px;
            margin-top: 20px;
        }
        .field-requirement-details-status label{padding-top:5px;}
        .modal-lg, .modal-xl {
          max-width: 975px !important;
        }
        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 550px;
            pointer-events: auto;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            outline: 0;
			margin-left: 35%;
			margin-top: 10%;
        }
    </style>
    <div class="modal-body">
        <div class="row">
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
                                                    {{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('file','document_name','',array('class'=>'form-control'))}}  
                                                    </div>
                                                    <span class="validate-err" id="err_document"></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6"></div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <button type="button" style="float: right;" class="btn btn-primary" id="uploadAttachment">Upload File</button>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
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

        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        </div>
    </div>
</div> 
<script src="{{ asset('js/add_hoapphealthcert.js') }}"></script>



