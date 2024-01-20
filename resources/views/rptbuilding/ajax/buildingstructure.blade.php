
<style type="text/css">
    .row{padding-top: 10px;}
</style>
{{Form::open(array('name'=>'forms','url'=>'rptbuilding/storebuildingstructure','method'=>'post','id'=>'storeBuildingStructural'))}}
 {{ Form::hidden('property_id',(isset($propertyCode->id))?$propertyCode->id:NULL, array('id' => 'property_id')) }}
  {{ Form::hidden('session_id',(isset($sessionId))?$sessionId:'', array('id' => 'session_id')) }}
<div class="modal-header">
                                <h4 class="modal-title">Building Structural Characteristics</h4>
                                <a class="close addStructuralCharacterModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true">X</a>
                            </div>
                            <div class="container"></div>
                            <div class="modal-body">
                                <div class="basicinfodiv">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::label('rbf_building_roof_desc1',__("Roof 1"),['class'=>'form-label'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::select('rbf_building_roof_desc1',$arrbuildingroof,(isset($propertyCode->rbf_building_roof_desc1))?$propertyCode->rbf_building_roof_desc1:'',array('class'=>'form-control rbf_building_roof_desc1'))}}
                                                    
                                                </div>
                                                <span class="validate-err" id="err_rbf_building_roof_desc1"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">    
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::select('rbf_building_roof_desc2',$arrbuildingroof,(isset($propertyCode->rbf_building_roof_desc2))?$propertyCode->rbf_building_roof_desc2:'',array('class'=>'form-control rbf_building_roof_desc2'))}}
                                                    
                                                </div>
                                                <span class="validate-err" id="err_rbf_building_roof_desc2"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">    
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    <!-- {{Form::label('rbf_building_roof_desc3',__("Roof 3"),['class'=>'form-label'])}}<span class="text-danger">*</span> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::select('rbf_building_roof_desc3',$arrbuildingroof,(isset($propertyCode->rbf_building_roof_desc3))?$propertyCode->rbf_building_roof_desc3:'',array('class'=>'form-control rbf_building_roof_desc3'))}}
                                                    
                                                </div>
                                                <span class="validate-err" id="err_rbf_building_roof_desc3"></span>
                                            </div>
                                        </div>
                                    </div><br /> 
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::label('rbf_building_floor_desc1',__("Floor 1"),['class'=>'form-label'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::select('rbf_building_floor_desc1',$arrbuildingfloor,(isset($propertyCode->rbf_building_floor_desc1))?$propertyCode->rbf_building_floor_desc1:'',array('class'=>'form-control rbf_building_floor_desc1'))}}
                                                    
                                                </div>
                                                <span class="validate-err" id="err_rbf_building_floor_desc1"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">    
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                   <!--  {{Form::label('rbf_building_floor_desc2',__("Floor 2"),['class'=>'form-label'])}}<span class="text-danger">*</span> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::select('rbf_building_floor_desc2',$arrbuildingfloor,(isset($propertyCode->rbf_building_floor_desc2))?$propertyCode->rbf_building_floor_desc2:'',array('class'=>'form-control rbf_building_floor_desc2'))}}
                                                    
                                                </div>
                                                <span class="validate-err" id="err_rbf_building_floor_desc2"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">    
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                   <!--  {{Form::label('rbf_building_floor_desc3',__("Floor 3"),['class'=>'form-label'])}}<span class="text-danger">*</span> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::select('rbf_building_floor_desc3',$arrbuildingfloor,(isset($propertyCode->rbf_building_floor_desc3))?$propertyCode->rbf_building_floor_desc3:'',array('class'=>'form-control rbf_building_floor_desc3'))}}
                                                    
                                                </div>
                                                <span class="validate-err" id="err_rbf_building_floor_desc3"></span>
                                            </div>
                                        </div>
                                    </div><br /> 
                                      <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::label('rbf_building_wall_desc1',__("Wall 1"),['class'=>'form-label'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::select('rbf_building_wall_desc1',$arrbuildingfwall,(isset($propertyCode->rbf_building_wall_desc1))?$propertyCode->rbf_building_wall_desc1:'',array('class'=>'form-control rbf_building_wall_desc1'))}}
                                                    
                                                </div>
                                                <span class="validate-err" id="err_rbf_building_wall_desc1"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">    
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                   <!--  {{Form::label('rbf_building_wall_desc2',__("Wall 2"),['class'=>'form-label'])}}<span class="text-danger">*</span> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::select('rbf_building_wall_desc2',$arrbuildingfwall,(isset($propertyCode->rbf_building_wall_desc2))?$propertyCode->rbf_building_wall_desc2:'',array('class'=>'form-control rbf_building_wall_desc2'))}}
                                                    
                                                </div>
                                                <span class="validate-err" id="err_rbf_building_wall_desc2"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">    
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    <!-- {{Form::label('rbf_building_wall_desc3',__("Wall 3"),['class'=>'form-label'])}}<span class="text-danger">*</span> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::select('rbf_building_wall_desc3',$arrbuildingfwall,(isset($propertyCode->rbf_building_wall_desc3))?$propertyCode->rbf_building_wall_desc3:'',array('class'=>'form-control rbf_building_wall_desc3'))}}
                                                    
                                                </div>
                                                <span class="validate-err" id="err_rbf_building_wall_desc3"></span>
                                            </div>
                                        </div>
                                    </div><br />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- <a href="#" data-dismiss="modal" class="btn closeModel" mid=""  type="edit">Close</a> -->
                                <a href="#" data-dismiss="modal" class="btn addStructuralCharacterModal" mid=""  type="edit">Close</a>
                                <button class="btn btn-primary" id="structuralcharacterbutton">Save Changes</button>
                                <!-- <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a> -->
                            </div>
                            {{Form::close()}}