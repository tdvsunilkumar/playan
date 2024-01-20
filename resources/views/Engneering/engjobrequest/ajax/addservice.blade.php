{{Form::open(array('name'=>'forms','url'=>'engjobrequest/storeserviceform','method'=>'post','id'=>'storeJobService'))}}
 {{ Form::hidden('id',(isset($jobservice->id))?$jobservice->id:'', array('id' => 'id')) }}
 {{ Form::hidden('jobrequest_id',(isset($JobRequest->id))?$JobRequest->id:NULL, array('id' => 'jobrequest_id')) }}
 {{ Form::hidden('session_id',(isset($sessionId))?$sessionId:'', array('id' => 'session_id')) }}
<div class="modal-header">
                                <h4 class="modal-title">Job Request Service Update</h4>
                                <a class="close closeServiceModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true" style="cursor:pointer;">X</a>
                            </div>
                            <div class="container"></div>
                            <div class="modal-body">
                                <div class="basicinfodiv">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                         <div class="form-group">
                                         <div class="form-icon-user">
                                        {{Form::label('serviceid',__("Service"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::select('serviceid',$arrGetservices,'',array('class'=>'form-control select3 serviceid'))}}
                                    </div>
                                    <span class="validate-err" id="err_pc_class_code"></span>
                                </div>
                            </div>
                           
                        </div><br /> 
                            </div>
                            </div>
                            <div class="modal-footer">
                                <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                                <a href="#" data-dismiss="modal" class="btn closeServiceModal" mid=""  type="edit">Close</a>
                                <button class="btn btn-primary" id="saveServiceDetails">Save Changes</button>
                                <!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
                            </div>
                 {{Form::close()}}