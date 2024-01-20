{{Form::open(array('name'=>'forms','url'=>'jobrequest/saveelectricalcalculation','method'=>'post','id'=>'electricalrevision'))}}
 {{ Form::hidden('jobrequestid',(isset($jobserviceid))?$jobserviceid:'', array('id' => 'jobrequestid')) }}
 {{ Form::hidden('session_id',(isset($sessionId))?$sessionId:'', array('id' => 'session_id')) }}
<div class="modal-header">
                        <h4 class="modal-title">Electrical Fees Assessment</h4>
                                <a class="close closeelectricalrevisionModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true" style="cursor:pointer;">X</a>
                            </div>
                            <div class="container"></div>
                            <div class="modal-body">
                                <div class="basicinfodiv">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                         <div class="form-group">
                                             <div class="form-icon-user">
                                            {{Form::label('totalload',__("Total Connected Load"),['class'=>'form-label'])}}
                                             </div>
                                         </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{ Form::number('eef_total_load_kva',$electicfeedata->eef_total_load_kva, array('class' => 'form-control eef_total_load_kva','id'=>'eef_total_load_kva','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_eef_total_load_kva"></span>
                                           </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                         <div class="form-group">
                                             <div class="form-icon-user">
                                            {{Form::label('kva',__("KVA"),['class'=>'form-label'])}}
                                             </div>
                                         </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{ Form::text('eef_total_load_total_fees',$electicfeedata->eef_total_load_total_fees, array('class' => 'form-control disabled-field','id'=>'eef_total_load_total_fees','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_totalloadfee"></span>
                                           </div>
                                        </div>
                                     </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                         <div class="form-group">
                                             <div class="form-icon-user">
                                            {{Form::label('totaltransformer',__("Total Transformer/ Uninterrupted Power Supply(UPS) / Generator Capacity"),['class'=>'form-label'])}}
                                             </div>
                                         </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{ Form::number('eef_total_ups',$electicfeedata->eef_total_ups, array('class' => 'form-control eef_total_ups','id'=>'eef_total_ups','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_eef_total_ups"></span>
                                           </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                         <div class="form-group">
                                             <div class="form-icon-user">
                                            {{Form::label('kva',__("KVA"),['class'=>'form-label'])}}
                                             </div>
                                         </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{ Form::text('eef_total_ups_total_fees',$electicfeedata->eef_total_ups_total_fees, array('class' => 'form-control disabled-field','id'=>'eef_total_ups_total_fees','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_totalloadfee"></span>
                                           </div>
                                        </div>
                                     </div>
                                     <div class="row">
                                         <div class="col-lg-12 col-md-12 col-sm-12">
                                         <div class="form-group">
                                             <div class="form-icon-user">
                                            {{Form::label('pole',__("Pole/Attachment Location Plan Permit"),['class'=>'form-label'])}}
                                             </div>
                                         </div>
                                        </div>
                                     </div>
                                     <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                         <div class="form-group">
                                             <div class="form-icon-user">
                                            {{Form::label('polelocation',__("Power Supply Pole location"),['class'=>'form-label'])}}
                                             </div>
                                         </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{ Form::number('eef_pole_location_qty',$electicfeedata->eef_pole_location_qty, array('class' => 'form-control eef_pole_location_qty','id'=>'eef_pole_location_qty','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_eef_pole_location_qty"></span>
                                           </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                         <div class="form-group">
                                         </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{ Form::text('eef_pole_location_total_fees',$electicfeedata->eef_pole_location_total_fees, array('class' => 'form-control disabled-field','id'=>'eef_pole_location_total_fees','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_eef_pole_location_total_fees"></span>
                                           </div>
                                        </div>
                                     </div>
                                      <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                         <div class="form-group">
                                             <div class="form-icon-user">
                                            {{Form::label('polelocation',__("Power Supply Pole location"),['class'=>'form-label'])}}
                                             </div>
                                         </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{ Form::number('eef_guying_attachment_qty',$electicfeedata->eef_guying_attachment_qty, array('class' => 'form-control eef_guying_attachment_qty','id'=>'eef_guying_attachment_qty','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_eef_guying_attachment_qty"></span>
                                           </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                         <div class="form-group">
                                         </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{ Form::text('eef_guying_attachment_fees',$electicfeedata->eef_guying_attachment_fees, array('class' => 'form-control disabled-field','id'=>'eef_guying_attachment_fees','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_eef_pole_location_total_fees"></span>
                                           </div>
                                        </div>
                                     </div>
                                     <div class="row">
                                         <div class="col-lg-12 col-md-12 col-sm-12">
                                         <div class="form-group">
                                             <div class="form-icon-user">
                                            {{Form::label('pole',__("Miscellaneous Fees"),['class'=>'form-label'])}}
                                             </div>
                                         </div>
                                        </div>
                                     </div>
                                     <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                         <div class="form-group">
                                             <div class="form-icon-user">
                                            
                                             </div>
                                         </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{Form::label('polelocation',__("Electric Meter"),['class'=>'form-label'])}}
                                            </div>
                                            <span class="validate-err" id="err_pc_class_code"></span>
                                           </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                         <div class="form-group">
                                             {{Form::label('polelocation',__("Wiring Permit Issurance"),['class'=>'form-label'])}}
                                         </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                               
                                            </div>
                                            <span class="validate-err" id="err_eef_pole_location_total_fees"></span>
                                           </div>
                                        </div>
                                     </div>
                                     <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                         <div class="form-group">
                                             <div class="form-icon-user">
                                              {{ Form::select('eefm_id',$miscellaneousfees,$electicfeedata->eefm_id, array('class' => 'form-control eefm_id','id'=>'eefm_id','required'=>'required')) }}
                                             </div>
                                              <span class="validate-err" id="err_eefm_id"></span>
                                         </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                                {{ Form::text('eef_electric_meter_fees',$electicfeedata->eef_electric_meter_fees, array('class' => 'form-control eef_electric_meter_fees','id'=>'eef_electric_meter_fees','required'=>'required')) }}
                                            </div>
                                           
                                           </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                         <div class="form-group">
                                            {{ Form::text('eef_wiring_permit_fees',$electicfeedata->eef_wiring_permit_fees, array('class' => 'form-control eef_wiring_permit_fees','id'=>'eef_wiring_permit_fees','required'=>'required')) }}
                                         </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                               {{ Form::text('eef_miscellaneous_tota_fees',$electicfeedata->eef_miscellaneous_tota_fees, array('class' => 'form-control disabled-field','id'=>'eef_miscellaneous_tota_fees','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_eef_pole_location_total_fees"></span>
                                           </div>
                                        </div>
                                     </div>
                                     <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                        
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                        
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                         <div class="form-group">
                                            {{Form::label('polelocation',__("Total Fees"),['class'=>'form-label'])}}
                                         </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <div class="form-icon-user">
                                               {{ Form::text('eef_total_fees',$electicfeedata->eef_total_fees, array('class' => 'form-control disabled-field','id'=>'eef_total_fees','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_eef_total_fees"></span>
                                           </div>
                                        </div>
                                     </div>
                            </div>
                            </div>
                            <div class="modal-footer">
                                <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                                <a href="#" data-dismiss="modal" class="btn closeelectricalrevisionModal" mid=""  type="edit">Close</a>
                                <button class="btn btn-primary" id="saveServiceDetails">Save Changes</button>
                                <!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
                            </div>
                 {{Form::close()}}