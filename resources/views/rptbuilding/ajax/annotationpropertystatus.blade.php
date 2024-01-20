{{Form::open(array('name'=>'forms','url'=>'rptbuilding/anootationspeicalpropertystatus','method'=>'post','id'=>'saveAnnotationPropertyStatus'))}}
{{ Form::hidden('id',(isset($propertyStatus->id))?$propertyStatus->id:'', array('id' => 'id')) }}
{{ Form::hidden('property_id',(isset($propertyId))?$propertyId:'', array('id' => 'property_id')) }}
{{ Form::hidden('action','main_form', array()) }}
  
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
                <h4 class="modal-title">Annotation & Property Status (Building)</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
           
                                 <div class="col-lg-2 col-md-2 col-sm-2">
                                 	<div class="row">
                                		<div class="col-lg-12 col-md-12 col-sm-12">
                                 	<div class="form-group">
                                    {{ Form::checkbox('rpss_mciaa_property', '1', (isset($propertyStatus->rpss_mciaa_property))?$propertyStatus->rpss_mciaa_property:false, array('id'=>'rpss_mciaa_property','class'=>'form-check-input rpss_mciaa_property property_type')) }}&nbsp;
                                    {{Form::label('rpss_mciaa_property',__('MCIAA Property'),['class'=>'form-label'])}}
                                </div>
                            </div>
                                </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                	<div class="row">
                                		<div class="col-lg-12 col-md-12 col-sm-12">
                                	<div class="form-group">
                         
                                    {{ Form::checkbox('rpss_peza_property', '1', (isset($propertyStatus->rpss_peza_property))?$propertyStatus->rpss_peza_property:false, array('id'=>'rpss_peza_property','class'=>'form-check-input rpss_peza_property property_type')) }}&nbsp;
                                    {{Form::label('rpss_peza_property',__('PEZA Property'),['class'=>'form-label'])}}
                                </div>
                            </div>
                                </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                	<div class="row">
                                		<div class="col-lg-12 col-md-12 col-sm-12">
                                			<div class="form-group">
                                 	
                                    {{ Form::checkbox('rpss_beneficial_use', '1', (isset($propertyStatus->rpss_beneficial_use))?$propertyStatus->rpss_beneficial_use:false, array('id'=>'rpss_beneficial_use','class'=>'form-check-input rpss_beneficial_use property_type')) }}&nbsp;
                                    {{Form::label('rpss_beneficial_use',__('Beneficial Use'),['class'=>'form-label'])}}
                                </div>
                                		</div>
                                	</div>
                                	
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                	<div class="row">
                                		<div class="col-lg-2 col-md-2 col-sm-2">
                                			<div class="form-group">
                                			{{Form::label('rpss_peza_property',__('User:'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                		</div>
                                		</div>
                                		<div class="col-lg-10 col-md-10 col-sm-10">
                                			<div class="form-group">
                                				
                                		{{Form::select('rpss_beneficial_user_code',$profile,(isset($propertyStatus->rpss_beneficial_user_code))?$propertyStatus->rpss_beneficial_user_code:'',array('class'=>'form-control rpss_beneficial_user_code','placeholder'=>'Select Name'))}}
                                	</div>
                                	<span class="validate-err" id="err_rpss_beneficial_user_code"></span>
                                		</div>
                                		<div class="col-lg-6 col-md-6 col-sm-6">
                                			<div class="form-group">
                                			{{Form::hidden('rpss_beneficial_user_name',(isset($propertyStatus->rpss_beneficial_user_name))?$propertyStatus->rpss_beneficial_user_name:'',array('class'=>'form-control rpss_beneficial_user_name','placeholder'=>'User Name','readonly'=>true))}}
                                		</div>
                                		<span class="validate-err" id="err_rpss_beneficial_user_name"></span>
                                		</div>
                                	</div>
                                </div>
        </div>            

                        </div>
                    </div>
                </div>
            </div>
        </div>

        

        <div class="col-lg-4 col-md-4 col-sm-4"  id="accordionFlushExample5" style="padding-top: 10px;">  
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
                                 	<div class="form-group">
                                    {{ Form::checkbox('rpss_is_mortgaged', '1', (isset($propertyStatus->rpss_is_mortgaged))?$propertyStatus->rpss_is_mortgaged:false, array('id'=>'rpss_is_mortgaged','class'=>'form-check-input rpss_is_mortgaged property_type_new')) }}&nbsp;
                                    {{Form::label('rpss_is_mortgaged',__('Currently Mortgage'),['class'=>'form-label'])}}
                                </div>
                            </div>
                                </div>
           </div>  
           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
           	<div class="col-lg-2 col-md-2 col-sm-2">
                                 	<div class="form-group">

                                    {{Form::label('rpss_is_mortgaged_date',__('Dated:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
                                </div>
                            </div>
                                		<div class="col-lg-6 col-md-6 col-sm-6">
                                 	<div class="form-group">
                                 		{{Form::date('rpss_is_mortgaged_date',(isset($propertyStatus->rpss_is_mortgaged_date) && $propertyStatus->rpss_is_mortgaged_date != '')?$propertyStatus->rpss_is_mortgaged_date:date("Y-m-d"),array('class'=>'form-control rpss_is_mortgaged_date','rows'=>1))}}

                                   
                                </div>
                            </div>
                                </div>
           </div>  
           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
           	<div class="col-lg-6 col-md-6 col-sm-6">
                                 	<div class="form-group">
                                    {{ Form::checkbox('rpss_is_levy', '1', (isset($propertyStatus->rpss_is_levy))?$propertyStatus->rpss_is_levy:false, array('id'=>'rpss_is_levy','class'=>'form-check-input rpss_is_levy property_type_new')) }}&nbsp;
                                    {{Form::label('rpss_is_levy',__('On Leavy'),['class'=>'form-label'])}}
                                </div>
                            </div>
                                		<div class="col-lg-6 col-md-6 col-sm-6">
                                 	<div class="form-group">
                                    {{ Form::checkbox('rpss_is_auction', '1', (isset($propertyStatus->rpss_is_auction))?$propertyStatus->rpss_is_auction:false, array('id'=>'rpss_is_auction','class'=>'form-check-input rpss_is_auction property_type_new')) }}&nbsp;
                                    {{Form::label('rpss_is_auction',__('On Auction'),['class'=>'form-label'])}}
                                </div>
                            </div>
                                </div>
           </div>  
           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
                                		<div class="col-lg-12 col-md-12 col-sm-12">
                                 	<div class="form-group">
                                    {{ Form::checkbox('rpss_is_protest', '1', (isset($propertyStatus->rpss_is_protest))?$propertyStatus->rpss_is_protest:false, array('id'=>'rpss_is_protest','class'=>'form-check-input rpss_is_protest property_type_new')) }}&nbsp;
                                    {{Form::label('rpss_is_protest',__('Under Protest'),['class'=>'form-label'])}}
                                </div>
                            </div>
                                </div>
           </div>  
           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
           	<div class="col-lg-2 col-md-2 col-sm-2">
                                 	<div class="form-group">

                                    {{Form::label('rpss_is_protest_date',__('Dated:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
                                </div>
                            </div>
                                		<div class="col-lg-6 col-md-6 col-sm-6">
                                 	<div class="form-group">
                                 		{{Form::date('rpss_is_protest_date',(isset($propertyStatus->rpss_is_protest_date) && $propertyStatus->rpss_is_protest_date != '')?$propertyStatus->rpss_is_protest_date:date("Y-m-d"),array('class'=>'form-control rpss_is_protest_date','rows'=>1))}}

                                   
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

        <div class="col-lg-8 col-md-8 col-sm-8"  id="accordionFlushExample5" style="padding-top: 10px;">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                       
                    </h6>
                    <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                        <div class="basicinfodiv">
                            <!--------------- Land Apraisal Listing Start Here------------------>
        <div class="row mortgage_details_dection">
             <div class="col-lg-12 col-md-12 col-sm-12"> <a class="text-center"> <h6 style="color: #20B7CC;" class="sub-title accordiantitle">MORTGAGE DETAILS</h6></a></div>    
             <div class="col-lg-12 col-md-12 col-sm-12"> 
             	<div class="row">
                    
             		<div class="col-lg-3 col-md-3 col-sm-3">
             			{{Form::label('rpss_mortgage_amount',__('Mortgage Amount:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
             		</div>
             		<div class="col-lg-3 col-md-3 col-sm-3">
             			{{Form::text('rpss_mortgage_amount',(isset($propertyStatus->rpss_mortgage_amount))?$propertyStatus->rpss_mortgage_amount:'',array('class'=>'form-control rpss_mortgage_amount decimalvalue','rows'=>1,'placeholder'=>'Mortgage Amount'))}}
             			<span class="validate-err" id="err_rpss_mortgage_amount"></span>
             		</div>
             		<div class="col-lg-3 col-md-3 col-sm-3">
             			{{Form::label('rpss_mortgage_to_code',__('Mortgage To:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
             		</div>
             		<div class="col-lg-3 col-md-3 col-sm-3">
             			{{Form::select('rpss_mortgage_to_code',$profile,(isset($propertyStatus->rpss_mortgage_to_code))?$propertyStatus->rpss_mortgage_to_code:'',array('class'=>'form-control rpss_mortgage_to_code','rows'=>1,'placeholder'=>'Select Name'))}}
             			<span class="validate-err" id="err_rpss_mortgage_to_code"></span>
             		</div>
             	</div>
             </div>  

             <div class="col-lg-12 col-md-12 col-sm-12"> 
             	<div class="row">
             		<div class="col-lg-3 col-md-3 col-sm-3">
             			{{Form::label('rpss_mortgage_cancelled',__('Canceled/Discharged:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
             		</div>
             		<div class="col-lg-9 col-md-9 col-sm-9">
             			{{Form::text('rpss_mortgage_cancelled',(isset($propertyStatus->rpss_mortgage_cancelled))?$propertyStatus->rpss_mortgage_cancelled:'',array('class'=>'form-control rpss_mortgage_cancelled','rows'=>1,'placeholder'=>'Canceled/Discharged'))}}
             			<span class="validate-err" id="err_rpss_mortgage_cancelled"></span>
             		</div>
             		
             	</div>
             </div>  

              <div class="col-lg-12 col-md-12 col-sm-12"> 
             	<div class="row">
             		<div class="col-lg-3 col-md-3 col-sm-3">
             			{{Form::label('rpss_mortgage_exec_date',__('Executed On:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
             		</div>
             		<div class="col-lg-3 col-md-3 col-sm-3">
             			{{Form::date('rpss_mortgage_exec_date',(isset($propertyStatus->rpss_mortgage_exec_date))?$propertyStatus->rpss_mortgage_exec_date:date("Y-m-d"),array('class'=>'form-control rpss_mortgage_exec_date','rows'=>1,))}}
             			<span class="validate-err" id="err_rpss_mortgage_exec_date"></span>
             		</div>
             		<div class="col-lg-3 col-md-3 col-sm-3">
             			{{Form::label('rpss_mortgage_exec_by',__('Executed By:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
             		</div>
             		<div class="col-lg-3 col-md-3 col-sm-3">
             			{{Form::text('rpss_mortgage_exec_by',(isset($propertyStatus->rpss_mortgage_exec_by))?$propertyStatus->rpss_mortgage_exec_by:'',array('class'=>'form-control rpss_mortgage_exec_by','rows'=>1,'placeholder'=>'Executed By'))}}
             			<span class="validate-err" id="err_rpss_mortgage_exec_by"></span>
             		</div>
             	</div>
             </div> 

              <div class="col-lg-12 col-md-12 col-sm-12"> 
             	<div class="row">
             		<div class="col-lg-3 col-md-3 col-sm-3">
             			{{Form::label('rpss_mortgage_certified_before',__('Certified Before:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
             		</div>
             		<div class="col-lg-3 col-md-3 col-sm-3">
             			{{Form::text('rpss_mortgage_certified_before',(isset($propertyStatus->rpss_mortgage_certified_before))?$propertyStatus->rpss_mortgage_certified_before:'',array('class'=>'form-control rpss_mortgage_certified_before','rows'=>1,'placeholder'=>'Certified Before'))}}
             			<span class="validate-err" id="err_rpss_mortgage_certified_before"></span>
             		</div>
             		<div class="col-lg-3 col-md-3 col-sm-3">
             			{{Form::label('rpss_mortgage_notary_public_date',__('Notary Public On:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
             		</div>
             		<div class="col-lg-3 col-md-3 col-sm-3">
             			{{Form::date('rpss_mortgage_notary_public_date',(isset($propertyStatus->rpss_mortgage_notary_public_date))?$propertyStatus->rpss_mortgage_notary_public_date:date("Y-m-d"),array('class'=>'form-control rpss_mortgage_notary_public_date','rows'=>1,))}}
             			<span class="validate-err" id="err_rpss_mortgage_notary_public_date"></span>
             		</div>
             		
             	</div>
             </div>

        </div>            

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8 col-md-8 col-sm-8"  id="accordionFlushExample5" style="padding-top: 10px;">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                       
                    </h6>
                    <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                        <div class="basicinfodiv">
                            <!--------------- Land Apraisal Listing Start Here------------------>
        <div class="row">
        	<div class="col-lg-12 col-md-12 col-sm-12"> <a class="text-center"> <h6 style="color: #20B7CC;" class="sub-title accordiantitle">ANNOTATION</h6></a></div>    
             <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive" id="listAnnotationshere">
                            
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
        <div class="col-lg-4 col-md-4 col-sm-4"  id="accordionFlushExample5" style="padding-top: 10px;">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingfive">
                       
                    </h6>
                    <div id="flush-collapsefive" class="accordion-collapse collapse show" aria-labelledby="flush-headingfive" data-bs-parent="#accordionFlushExample5">
                        <div class="basicinfodiv">
                            <!--------------- Land Apraisal Listing Start Here------------------>
        <div class="row" id="anootationForm">
            
           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
           	<div class="col-lg-3 col-md-3 col-sm-3">
                                 	<div class="form-group">

                                    {{Form::label('rpa_annotation_date_time',__('Date Time:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
                                </div>
                            </div>
            <div class="col-lg-9 col-md-4 col-sm-4">
                                 	<div class="form-group">
                                 		
                                 		<input type="date"
       autocomplete="on"
       id="created_date"
       name="rpa_annotation_date_time"
       class="form-control"
       value="(isset($propertyStatus->rpa_annotation_date_time) && $propertyStatus->rpa_annotation_date_time != '')?$propertyStatus->rpa_annotation_date_time:''" 
        >
<span class="validate-err" id="err_rpa_annotation_date_time"></span>
                                   
                                </div>
                            </div>
                           
                                </div>
           </div>  
           
            
           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
           	<div class="col-lg-3 col-md-3 col-sm-3">
                                 	<div class="form-group">

                                    {{Form::label('rpa_annotation_by_code',__('By:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
                                </div>
                            </div>
            <div class="col-lg-9 col-md-9 col-sm-9">
                                 	<div class="form-group">
                                 		{{Form::select('rpa_annotation_by_code',$appraisers,(isset($propertyStatus->rpa_annotation_by_code) && $propertyStatus->rpa_annotation_by_code != '')?$propertyStatus->rpa_annotation_by_code:'',array('class'=>'form-control rpa_annotation_by_code','rows'=>1,'placeholder' => 'Select Name'))}}
<span class="validate-err" id="err_rpa_annotation_by_code"></span>
                                   
                                </div>
                            </div>
                           
                                </div>
           </div>  
           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
           	<div class="col-lg-3 col-md-3 col-sm-3">
                                 	<div class="form-group">

                                    {{Form::label('rpa_annotation_desc',__('Annotation:'),['class'=>'form-label','style'=>'margin-top:8px;'])}}
                                </div>
                            </div>
            <div class="col-lg-9 col-md-9 col-sm-9">
                                 	<div class="form-group">
                                 		{{Form::textarea('rpa_annotation_desc',(isset($propertyStatus->rpa_annotation_desc) && $propertyStatus->rpa_annotation_desc != '')?$propertyStatus->rpa_annotation_desc:'',array('class'=>'form-control rpa_annotation_desc','rows'=>5))}}
<span class="validate-err" id="err_rpa_annotation_desc"></span>
                                   
                                </div>
                            </div>
                           
                                </div>
           </div>  
           <div class="col-lg-12 col-md-12 col-sm-12">
           <div class="row">
           	<div class="col-lg-4 col-md-4 col-sm-4"></div>
           	<div class="col-lg-4 col-md-4 col-sm-4"></div>
           	<div class="col-lg-4 col-md-4 col-sm-4"><input style="float:right;" type="button" name="submit" id="saveAnnotationData" value="Save" class="btn  btn-primary"></div>
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
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">

     <input type="submit" name="submit" id="submit"  value="{{ (isset($approvedata->id) && $approvedata->id > 0)?__('Update'):__('Save')}}" class="btn  btn-primary">
  
</div>
{{Form::close()}}



