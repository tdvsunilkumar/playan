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
                                        {{Form::email('p_email_address',$status,array('class'=>'form-control','id'=>'p_email_address','readonly'))}}
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
                                                        <tr id="ownerdetailsView">
                                                            <th>{{__('No.')}}</th>
                                                            <th>{{__("Tax Declaration")}}</th>
                                                            <th>{{__('Taxpayer Name')}}</th>
                                                            <th>{{__('PIN')}}</th>
                                                            <th>{{__('CLASS')}}</th>
                                                            <th>{{__('BARANGAY')}}</th>
                                                            <th>{{__('LOT|CCT|UNIT NO.|DESCRIPTION.')}}</th>
                                                            <th>{{__('ASSESSED VALUE')}}</th>
                                                           
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    
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

<script src="{{ asset('js/rptonlineposting/view.js') }}?rand={{ rand(0,999)}}"></script>
