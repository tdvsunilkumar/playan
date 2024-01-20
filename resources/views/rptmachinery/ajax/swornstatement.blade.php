{{Form::open(array('name'=>'forms','url'=>'rptmachinery/swornstatment','method'=>'post','id'=>'saveSwornStatement'))}}
{{ Form::hidden('id',(isset($propertyStatus->id))?$propertyStatus->id:'', array('id' => 'id')) }}
{{ Form::hidden('property_id',(isset($propertyId))?$propertyId:'', array('id' => 'property_id')) }}
  
 <style>
    .modal-xll {
        max-width: 1350px !important;
    }
    .accordion-button{
        margin-bottom: 12px;
    }
    .form-group{
        margin-bottom: unset;
    }
    .form-group label {
        font-weight: 600;
        font-size: 12px;
    }
    .form-control, .custom-select{
        padding-left: 5px;
        font-size: 12px;
    }
    .textright{text-align:right;}
    .pt10{
        padding-top:10px;
    }
    .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #8080802e;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .row{padding-top:10px;}
    .choices__inner {
        min-height: 35px;
        padding:5px ;
        padding-left:5px;
    }
    .field-requirement-details-status label{margin-top: 7px;}
    #flush-collapsetwo{
/*        padding-bottom: 80px;*/
    }
    
 </style>
<div class="modal-header">
                <h4 class="modal-title">Sworn Statement Of Owner (Machinery)</h4>
                <a class="close closeSwornStatement" data-dismiss="modal" aria-hidden="true" type="add" mid="" style="cursor: default;">X</a>
                </div><div class="container"></div>
<div class="modal-body">
    <div class="row pt10" >
        <!----  Approval Data Info ------------>
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample5" style="padding-top: 10px;">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                       
                    </h6>
                    <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                        <div class="basicinfodiv">
                            <!--------------- Land Apraisal Listing Start Here------------------>
        <div class="row">
           
                                 <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                    <span class="text-center" style="word-spacing: 10px;font-size: 20px;" >Under the provisions of Republic Act No. 7160, I HEREBY CERTIFY that the current and fair market value of the forgoing described property of which I am the Owner/Administrator, is to the best of my knowledge and belief.</span>
                            </div>
                                </div>
                                </div><br/>
                                 <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <div class="form-group">

                                    {{Form::label('land_market_value',__('Land Market Value:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                        {{Form::text('land_market_value',(isset($area))?$area:'',array('class'=>'form-control decimalvalue land_market_value','rows'=>1))}}
                                        <span class="validate-err" id="err_land_market_value"></span>

                                   
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                {{Form::label('rps_improvement_value',__('Improvement:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
                            </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                        {{Form::text('rps_improvement_value',(isset($propertyStatus->rps_improvement_value))?$propertyStatus->rps_improvement_value:0,array('class'=>'form-control decimalvalue rps_improvement_value','rows'=>1))}}
                                </div>
                            </div>
                                </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <div class="form-group" id="oath_id">

                                    {{Form::label('rps_person_taking_oath_id',__('Person Taking Oath:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                        {{Form::select('rps_person_taking_oath_id',$profile,(isset($propertyStatus->rps_person_taking_oath_id))?$propertyStatus->rps_person_taking_oath_id:'',array('class'=>'form-control rps_person_taking_oath_code','rows'=>1,'placeholder' => 'Select Name'))}}
                                        <input type="hidden" name="rps_person_taking_oath_custom" value="{{(isset($propertyStatus->rps_person_taking_oath_custom))?$propertyStatus->rps_person_taking_oath_custom:''}}">
                                </div>
                            </div>
                            <div class="col-md-1" style="display: contents;">
                                  <div class="form-group">
                                        <div class="form-icon-user">
                                           <a href="#" class="btn btn-sm btn-primary" id="refreshClient" style="font-size: 16px;padding: 9px 8px;">
                                            <span class="btn-inner--icon"><i class="ti-reload"></i> </span>
                                           </a>
                                           <a href="{{ url('/rptpropertyowner?isopenAddform=1') }}" title="Manage Taxpayer" data-title="Manage Taxpayers" class="btn btn-sm btn-primary" target="_blank" style="font-size: 16px;padding: 9px 8px;">
                                                <i class="ti-plus"></i>
                                            </a>
                                         </div>
                                            <span class="validate-err" id="err_loc_local_code"></span>
                                 </div>
                            </div> 
                                </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">

                                    {{Form::label('rps_date',__('Subscribed and sworn to before the administering official on'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                        {{Form::date('rps_date',(isset($propertyStatus->rps_date))?$propertyStatus->rps_date:date("Y-m-d"),array('class'=>'form-control rps_date','rows'=>1))}}
                                 </div>
                            </div>
                                </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group" id="cto_no">

                                    {{Form::label('rps_ctc_no',__('the person taking oath presenting Community Tax Certificate No:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    {{Form::select('rps_ctc_no',$orData,(isset($propertyStatus->rps_ctc_no))?$propertyStatus->rps_ctc_no:'',array('class'=>'form-control rps_ctc_no','rows'=>1,'placeholder' => 'Select O.R.No.'))}}
                                </div>
                            </div>
                                </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">

                                    {{Form::label('rps_ctc_issued_date',__('Issued On'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                        {{Form::date('rps_ctc_issued_date',(isset($propertyStatus->rps_ctc_issued_date))?$propertyStatus->rps_ctc_issued_date:date("Y-m-d"),array('class'=>'form-control rps_ctc_issued_date land_market_value','id'=>'rps_ctc_issued_date','readonly','style'=>'width: 324px;','rows'=>1))}}

                                   
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1" style="text-align: right;">
                                <div class="form-group">
                                {{Form::label('rps_ctc_issued_place',__('at:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
                            </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                        {{Form::text('rps_ctc_issued_place',(isset($propertyStatus->rps_ctc_issued_place))?$propertyStatus->rps_ctc_issued_place:'',array('class'=>'form-control rps_ctc_issued_place','readonly','id'=>'rps_ctc_issued_place','rows'=>1))}}
                                   
                                </div>
                            </div>
                                </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <div class="form-group">

                                            {{Form::label('rps_administer_official1',__('Administering Official'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                            {{Form::label('rps_administer_official_title1',__('Official Title'),['class'=>'form-label','style'=>'margin-top:8px;'])}}


                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <div class="form-group">
                                            {{Form::label('rps_administer_official_title1',__('Type'),['class'=>'form-label','style'=>'margin-top:8px;'])}}


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        
                                        <div class="col-lg-6 col-md-6 col-sm-6" id="div1" style="">
                                            <div class="form-group" id="administer1">
                                                {{Form::select('rps_administer_official1_id',$profile,(isset($propertyStatus->rps_administer_official1_id))?$propertyStatus->rps_administer_official1_id:'',array('class'=>'form-control rps_administer_official1','id'=>'rps_administer_official11','rows'=>1,'placeholder' => 'Select official'))}}
                                                 
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6" id="div2" style="display:none;">
                                            <div class="form-group" id="administer2">
                                                {{Form::select('rps_administer_official1_id',$employee,(isset($propertyStatus->rps_administer_official1_id))?$propertyStatus->rps_administer_official1_id:'',array('class'=>'form-control rps_administer_official1','id'=>'rps_administer_official12','rows'=>1,'placeholder' => 'Select official'))}}
                                                 
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6" id="div3" style="display:none;">
                                            <div class="form-group" id="administer3">
                                                {{Form::select('rps_administer_official1_id',$profile,(isset($propertyStatus->rps_administer_official1_id))?$propertyStatus->rps_administer_official1_id:'',array('class'=>'form-control rps_administer_official1','id'=>'rps_administer_official13','rows'=>1,'placeholder' => 'Select official'))}}
                                                 
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1" id="div4">
                                              <div class="form-group">
                                                   
                                                    <div class="form-icon-user">
                                                       <a href="#" class="btn btn-sm btn-primary" id="refreshClient2" style="font-size: 16px;padding: 9px 8px;">
                                                        <span class="btn-inner--icon"><i class="ti-reload"></i> </span>
                                                       </a>
                                                       <a href="{{ url('/rptpropertyowner?isopenAddform=1') }}" title="Manage Taxpayer" data-title="Manage Taxpayers" class="btn btn-sm btn-primary" target="_blank" style="font-size: 16px;padding: 9px 8px;">
                                                            <i class="ti-plus"></i>
                                                        </a>
                                                     </div>
                                                        <span class="validate-err" id="err_loc_local_code"></span>
                                             </div>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1" id="div5" style="display:none;">
                                              <div class="form-group">
                                                   
                                                    <div class="form-icon-user">
                                                       <a href="#" class="btn btn-sm btn-primary" id="refreshEmployee" style="font-size: 16px;padding: 9px 8px;">
                                                        <span class="btn-inner--icon"><i class="ti-reload"></i> </span>
                                                       </a>
                                                       <a href="{{ url('/human-resource/employees?isopenAddform=1') }}" title="Manage Taxpayer" data-title="Manage Employee" class="btn btn-sm btn-primary" target="_blank" style="font-size: 16px;padding: 9px 8px;">
                                                            <i class="ti-plus"></i>
                                                        </a>
                                                     </div>
                                                        <span class="validate-err" id="err_loc_local_code"></span>
                                             </div>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1" id="div6" style="display:none;">
                                              <div class="form-group">
                                                    
                                                    <div class="form-icon-user">
                                                       <a href="#" class="btn btn-sm btn-primary" id="refreshCon" style="font-size: 16px;padding: 9px 8px;">
                                                        <span class="btn-inner--icon"><i class="ti-reload"></i> </span>
                                                       </a>
                                                       <a href="{{ url('/con?isopenAddform=1') }}" title="Manage Taxpayer" data-title="Manage Taxpayers" class="btn btn-sm btn-primary" target="_blank" style="font-size: 16px;padding: 9px 8px;">
                                                            <i class="ti-plus"></i>
                                                        </a>
                                                     </div>
                                                        <span class="validate-err" id="err_loc_local_code"></span>
                                             </div>
                                        </div>
                                        
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                    {{Form::text('rps_administer_official_title1',(isset($propertyStatus->rps_administer_official_title1))?$propertyStatus->rps_administer_official_title1:'',array('class'=>'form-control rps_administer_official_title1','rows'=>1))}}

                                               
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-3 col-sm-3" >
                                            <div class="form-group" id="administer23">
                                                {{ Form::select('rps_administer_official1_type',array('1' =>'Taxpayer','2' =>'Employee','3'=>'Consultant'), (isset($propertyStatus->rps_administer_official1_type))?$propertyStatus->rps_administer_official1_type:'', array('class' => 'form-control spp_type','id'=>'rps_administer_official1_type','required'=>'required')) }}
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        
                                        <div class="col-lg-6 col-md-6 col-sm-6" id="div21" style="">
                                            <div class="form-group" id="administer21">
                                                {{Form::select('rps_administer_official2_id',$profile,(isset($propertyStatus->rps_administer_official2_id))?$propertyStatus->rps_administer_official2_id:'',array('class'=>'form-control rps_administer_official1','id'=>'rps_administer_official2_id1','rows'=>1,'placeholder' => 'Select official'))}}
                                                 
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6" id="div22" style="display:none;">
                                            <div class="form-group" id="administer22">
                                                {{Form::select('rps_administer_official2_id',$employee,(isset($propertyStatus->rps_administer_official2_id))?$propertyStatus->rps_administer_official2_id:'',array('class'=>'form-control rps_administer_official1','id'=>'rps_administer_official2_id2','rows'=>1,'placeholder' => 'Select official'))}}
                                                 
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6" id="div23" style="display:none;">
                                            <div class="form-group" id="administer23">
                                                {{Form::select('rps_administer_official2_id',$profile,(isset($propertyStatus->rps_administer_official2_id))?$propertyStatus->rps_administer_official2_id:'',array('class'=>'form-control rps_administer_official1','id'=>'rps_administer_official2_id3','rows'=>1,'placeholder' => 'Select official'))}}
                                            </div>
                                        </div>
                                        
                                       
                                        <div class="col-lg-1 col-md-1 col-sm-1" id="div24">
                                              <div class="form-group">
                                                    <div class="form-icon-user">
                                                       <a href="#" class="btn btn-sm btn-primary" id="refreshClient3" style="font-size: 16px;padding: 9px 8px;">
                                                        <span class="btn-inner--icon"><i class="ti-reload"></i> </span>
                                                       </a>
                                                       <a href="{{ url('/rptpropertyowner?isopenAddform=1') }}" title="Manage Taxpayer" data-title="Manage Taxpayers" class="btn btn-sm btn-primary" target="_blank" style="font-size: 16px;padding: 9px 8px;">
                                                            <i class="ti-plus"></i>
                                                        </a>
                                                     </div>
                                                        <span class="validate-err" id="err_loc_local_code"></span>
                                             </div>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1" id="div25" style="display:none;">
                                              <div class="form-group">
                                                    <div class="form-icon-user">
                                                       <a href="#" class="btn btn-sm btn-primary" id="refreshEmployee2" style="font-size: 16px;padding: 9px 8px;">
                                                        <span class="btn-inner--icon"><i class="ti-reload"></i> </span>
                                                       </a>
                                                       <a href="{{ url('/human-resource/employees?isopenAddform=1') }}" title="Manage Taxpayer" data-title="Manage Taxpayers" class="btn btn-sm btn-primary" target="_blank" style="font-size: 16px;padding: 9px 8px;">
                                                            <i class="ti-plus"></i>
                                                        </a>
                                                     </div>
                                                        <span class="validate-err" id="err_loc_local_code"></span>
                                             </div>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1" id="div26" style="display:none;">
                                              <div class="form-group">
                                                    <div class="form-icon-user">
                                                       <a href="#" class="btn btn-sm btn-primary" id="refreshCon" style="font-size: 16px;padding: 9px 8px;">
                                                        <span class="btn-inner--icon"><i class="ti-reload"></i> </span>
                                                       </a>
                                                       <a href="{{ url('/con?isopenAddform=1') }}" title="Manage Taxpayer" data-title="Manage Taxpayers" class="btn btn-sm btn-primary" target="_blank" style="font-size: 16px;padding: 9px 8px;">
                                                            <i class="ti-plus"></i>
                                                        </a>
                                                     </div>
                                                        <span class="validate-err" id="err_loc_local_code"></span>
                                             </div>
                                        </div>
                                      
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                    {{Form::text('rps_administer_official_title2',(isset($propertyStatus->rps_administer_official_title2))?$propertyStatus->rps_administer_official_title2:'',array('class'=>'form-control rps_administer_official_title1','rows'=>1))}}

                                               
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3" >
                                            <div class="form-group" id="administer23">
                                                {{ Form::select('rps_administer_official2_type',array('1' =>'Taxpayer','2' =>'Employee','3'=>'Consultant'), (isset($propertyStatus->rps_administer_official2_type))?$propertyStatus->rps_administer_official2_type:'', array('class' => 'form-control spp_type','id'=>'rps_administer_official2_type','required'=>'required')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
        </div>            

                        </div>
                    </div>
                </div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">

     <input type="submit" name="submit" id="submit"  value="{{ (isset($approvedata->id) && $approvedata->id > 0)?__('Update'):__('Save')}}" class="btn  btn-primary">
  
</div>
<script src="{{ asset('js/AddRptPropertySwornstatement.js') }}?rand={{ rand(000,999) }}"></script>
{{Form::close()}}



