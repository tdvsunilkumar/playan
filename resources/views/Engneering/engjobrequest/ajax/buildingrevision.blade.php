{{Form::open(array('name'=>'forms','url'=>'jobrequest/savebuildingculation','method'=>'post','id'=>'buildingrevision'))}}
 {{ Form::hidden('jobrequestid',(isset($jobserviceid))?$jobserviceid:'', array('id' => 'jobrequestid')) }}
 {{ Form::hidden('session_id',(isset($sessionId))?$sessionId:'', array('id' => 'session_id')) }}
  {{ Form::hidden('floorarea',(isset($floorarea))?$floorarea:'', array('id' => 'floorarea')) }}
  {{ Form::hidden('totalssessdfeeamt','', array('id' => 'totalssessdfeeamt')) }}
<div class="modal-header">
                        <h4 class="modal-title">Building Fees Assessment</h4>
                                <a class="close closebuildingrevisionModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true" style="cursor:pointer;">X</a>
                            </div>
                            <div class="container"></div>
                            <div class="modal-body">
                                <div class="basicinfodiv">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                         <div class="form-group">
                                            {{Form::label('polelocation',__("Total SQM"),['class'=>'form-label'])}}
                                         </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                               {{ Form::text('ebpf_total_sqm',$buildingfeedata->ebpf_total_sqm, array('class' => 'form-control','id'=>'ebpf_total_sqm','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ebpf_total_sqm"></span>
                                           </div>
                                        </div>
                                     </div>
                                     <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                         <div class="form-group">
                                            {{Form::label('buildingcategory',__("Building Category"),['class'=>'form-label'])}}
                                         </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                         <div class="form-group">
                                             <div class="form-icon-user">
                                              {{ Form::select('ebpfd_id',$buildingdivision,$buildingfeedata->ebpfd_id, array('class' => 'form-control ebpfd_id','id'=>'ebpfd_id','required'=>'required')) }}
                                             </div>
                                              <span class="validate-err" id="err_ebpfd_id"></span>
                                         </div>
                                        </div>
                                     </div>
                                     <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                         <div class="form-group">
                                            {{Form::label('polelocation',__("Total Fees"),['class'=>'form-label'])}}
                                         </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                               {{ Form::text('ebpf_total_fees',$buildingfeedata->ebpf_total_fees, array('class' => 'form-control disabled-field','id'=>'ebpf_total_fees','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_eef_total_fees"></span>
                                           </div>
                                        </div>
                                     </div>
                            </div>
                            </div>
                            <div class="modal-footer">
                                <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                                <a href="#" data-dismiss="modal" class="btn closebuildingrevisionModal" mid=""  type="edit">Close</a>
                                <button class="btn btn-primary" id="saveServiceDetails">Save Changes</button>
                                <!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
                            </div>
                 {{Form::close()}}