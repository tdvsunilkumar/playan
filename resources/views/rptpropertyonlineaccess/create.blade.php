<!-- {{ Form::open(array('url' => 'rptpropertyowner','id'=>'storePropertyOwnerForm')) }} -->
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}  
<style>
    .modal-content {
        position: relative;
        /* display: flex; */
        flex-direction: column;
        width: 100%;
        width: 1350px;
        pointer-events: auto;
        background-color: #ffffff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 15px;
        outline: 0;
        margin-left: -220px;
    }
    tr.strikeout td:before {
      content: " ";
      position: absolute;
      top: 50%;
      left: 0;
      border-bottom: 1px solid #111;
      width: 100%;
    }

    tr.strikeout td:after {
      content: "\00B7";
      font-size: 1px;
    }
    .red-row {
        color: red;
        text-decoration: line-through;
    }

 </style>
    <div class="modal-body">
        <div class="row pt10" >
            <!--------------- Owners Information Start Here---------------->
            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample1">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingone1">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone1" aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">{{__("Owner Information")}}</h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone1" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample1">
                            <div class="basicinfodiv">
                                <div class="row">
                                   <div class="col-lg-9 col-md-9 col-sm-9">
                                        <div class="form-group">
                                            {{Form::label('owner name',__('Taxpayer Name'),['class'=>'form-label'])}}
                                        
                                        <div class="form-icon-user">
                                            {{Form::text('taxpayer',$ownername,array('class'=>'form-control phonenumber','id'=>'taxpayerName','placeholder' => '','readonly'))}}
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            {{Form::label('',__('Online Access'),['class'=>'form-label'])}}
                                        
                                        <div class="form-icon-user">
                                            {{Form::text('assess','',array('class'=>'form-control phonenumber','id'=>'taxpayer','placeholder' => 'Permission Granted','readonly'))}}
                                        </div>
                                        </div>
                                    </div>
                                   
                                     
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            {{Form::label('address',__('Complete Address'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::textarea('rpo_address_house_lot_no',$data->standard_address,array('class'=>'form-control','readonly','rows'=>1))}}
                                            </div>
                                            <span class="validate-err" id="err_rpo_address_house_lot_no"></span>
                                        </div>
                                    </div>
                                   
                                    
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    {{Form::label('p_telephone_no',__('Telephone No.'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('p_telephone_no',$data->p_telephone_no,array('class'=>'form-control phonenumber','id'=>'p_telephone_no','placeholder' => '','readonly'))}}
                                    </div>
                                    <span class="validate-err" id="err_p_telephone_no"></span>
                                </div>
                            </div>
                             <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    {{Form::label('p_mobile_no',__('Mobile No.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                        {{Form::text('p_mobile_no',$data->p_mobile_no,array('class'=>'form-control phonenumber','id'=>'p_mobile_no','placeholder' => '','readonly'))}}
                                    </div>
                                    <span class="validate-err" id="err_p_mobile_no"></span>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    {{Form::label('p_email_address',__('Email Address'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                        {{Form::email('p_email_address',$data->p_email_address,array('class'=>'form-control','id'=>'p_email_address','readonly'))}}
                                    </div>
                                    <span class="validate-err" id="err_p_email_address"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    {{Form::label('p_email_address',__('Status'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                        {{Form::text('p_email_address',$status,array('class'=>'form-control','id'=>'p_email_address','readonly'))}}
                                    </div>
                                    <span class="validate-err" id="err_p_email_address"></span>
                                </div>
                            </div>
                             </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample2">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingone0">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="000" data-bs-target="#flush-collapseone2" aria-expanded="false" aria-controls="flush-headingtwo22">
                               
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                     <h6 class="sub-title accordiantitle">{{__("Property Details")}}</h6> 
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3" style="text-align: end;margin-left: -53px;">
                                     {{ Form::checkbox('checkowner','1', ('')?true:false, array('id'=>'myCheckbox','class'=>'form-check-input myCheckbox','style'=>'margin-top:9px')) }} {{Form::label('',__('Taxpayer Reference'),['class'=>'form-label','style'=>'padding-top: 7px;'])}}
                                    </div>
                                
                               
                            </button>
                        </h6>
                        <div id="flush-collapseone2" class="accordion-collapse 00 show" aria-labelledby="flush-headingone20" data-bs-parent="#accordionFlushExample2">
                            <div class="basicinfodiv">
                                <div class="row">
                                   <div class="col-lg-10 col-md-10 col-sm-10">
                                        <div class="form-group" id="taxDeclaration_group">
                                            {{Form::label('owner name',__('Search Tax Declaration Details'),['class'=>'form-label'])}}
                                        
                                        <div class="form-icon-user" style="width: 1132px;" id="representative">
                                            {{ Form::select('taxDeclaration',$taxDeclaration,'', array('class' => 'form-control','id'=>'taxDeclaration','required'=>'required')) }}
                                            {{Form::hidden('rpo_code','',array('class'=>'form-control ','id'=>'rpo_code','placeholder' => ''))}}
                                            {{Form::hidden('rp_property_code','',array('class'=>'form-control ','id'=>'rp_property_code','placeholder' => ''))}}
                                        </div>
                                        <span class="validate-err" id="validate-err"></span>
                                         <span class="validate-err2" id="validate-err2"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2" style="text-align:end;">
                                        <div class="form-group">
                                            
                                        &nbsp;
                                        <div class="form-icon-user" style="padding-top: 7px;">
                                            <button class="btn btn-primary" style="padding-top: 7px;padding-bottom: 7px;" id="saveData"> Apply Changes</button>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <div class="form-group">
                                            {{Form::label('',__('Taxpayer Name'),['class'=>'form-label'])}}
                                        
                                        <div class="form-icon-user">
                                            {{Form::text('assess','',array('class'=>'form-control ','id'=>'taxpayer','placeholder' => ''))}}
                                        </div>
                                        </div>
                                    </div>
                                   
                                     
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            {{Form::label('location',__('Location'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                 {{Form::text('location','',array('class'=>'form-control','id'=>'taxpayer','placeholder' => ''))}}
                                            </div>
                                            <span class="validate-err" id="err_rpo_address_house_lot_no"></span>
                                        </div>
                                    </div>
                                   <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            {{Form::label('pin',__('PIN'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                 {{Form::text('pin','',array('class'=>'form-control','id'=>'taxpayer','placeholder' => ''))}}
                                            </div>
                                            <span class="validate-err" id="err_rpo_address_house_lot_no"></span>
                                        </div>
                                    </div>
                                    
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    {{Form::label('p_telephone_no',__('LOT|CCT|UNIT NO.|DESCRIPTION.'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('cct_unit_no','',array('class'=>'form-control phonenumber','id'=>'cct_unit_no','placeholder' => '','readonly'))}}
                                    </div>
                                    <span class="validate-err" id="err_p_telephone_no"></span>
                                </div>
                            </div>
                             <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    {{Form::label('class',__('Class'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                        {{Form::text('class','',array('class'=>'form-control','id'=>'p_mobile_no','placeholder' => '','readonly'))}}
                                    </div>
                                    <span class="validate-err" id="err_p_mobile_no"></span>
                                </div>
                            </div>
                            
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    {{Form::label('assessed_value',__('Assessed Value'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                        {{Form::email('assessed_value','',array('class'=>'form-control','id'=>'p_email_address','readonly'))}}
                                    </div>
                                    <span class="validate-err" id="err_p_email_address"></span>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    {{Form::label('last_or_no',__('Last OR NO.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                        {{Form::email('last_or_no','',array('class'=>'form-control','id'=>'p_email_address','readonly'))}}
                                    </div>
                                    <span class="validate-err" id="err_p_email_address"></span>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    {{Form::label('last_or_date',__('Last OR Date'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                        {{Form::email('last_or_date','',array('class'=>'form-control','id'=>'p_email_address','readonly'))}}
                                    </div>
                                    <span class="validate-err" id="err_p_email_address"></span>
                                </div>
                            </div>
                             </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample3">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingone3">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone3" aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">{{__("Online Access")}}</h6>
                            </button>
                        </h6>
                        
                        <div id="flush-collapseone3" class="accordion-collapse collapse show" aria-labelledby="flush-headingone3" data-bs-parent="#accordionFlushExample">
                            <div class="basicinfodiv">
                                <div class="row">
                                   <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body table-border-style">
                                            <div class="table-responsive">
                                                <table class="table" id="new_added_land_apraisal">
                                                    <thead>
                                                        <tr id="ownerdetails">
                                                            <th>{{__('No.')}}</th>
                                                            <th>{{__("Tax Declaration")}}</th>
                                                            <th>{{__('Taxpayer Name')}}</th>
                                                            <th>{{__('PIN')}}</th>
                                                            <th>{{__('CLASS')}}</th>
                                                            <th>{{__('BARANGAY')}}</th>
                                                            <th>{{__('LOT|CCT|UNIT NO.|DESCRIPTION.')}}</th>
                                                            <th>{{__('ASSESSED VALUE')}}</th>
                                                            <th>{{__('Action')}}</th>
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
            <!-- Initially, your table body is empty -->
        </tbody>
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
            <!--------------- ATTACHED DOCUMENTARY REQUIREMENTS Start Here---------------->
         
            
        </div>
           </div>
         </div>
        </div>
        <!--------------- Business Details Listing End Here------------------>
              <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
           
            <!-- <input type="button" id="submitOwnerForm"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
            </div>

        </div>
    </div>
</div>
 <!-- {{Form::close()}} -->

<script src="{{ asset('js/rptonlineposting/add.js') }}?rand={{ rand(0,999)}}"></script>
<!-- <script>
$(document).ready(function() {
    $('#new_added_land_apraisal').DataTable({
        "paging": true,        // Enable paging
        "pageLength": 10,      // Set the number of rows per page to 10
        "ordering": true       // Enable sorting
    });
});
</script> -->
