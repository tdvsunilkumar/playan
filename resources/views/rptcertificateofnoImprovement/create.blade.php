{{ Form::open(array('url' => 'rptcertificateofnoImprovement')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('cashier_id',(isset($data->cashier_id))?$data->cashier_id:'', array('id' => 'cashier_id')) }}
{{ Form::hidden('cashierd_id',(isset($data->cashierd_id))?$data->cashierd_id:'', array('id' => 'cashierd_id')) }}
  <style type="">
.modal.show .modal-dialog {
        transform: none;
        display: grid;
        align-items: center;
        justify-content: center;
    }
     .select3-container--default .select3-selection--single {
        font-size: 12px !important;
        border: 1px solid #ffffff;
        background: #fff;
        border-radius: 6px;
    }
   .select3-container--default .select3-selection--single .select3-selection__rendered {
        color: #444;
        line-height: 33px;
        background: #fff;
        border-radius: 5px;
    }
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
/*        margin-left: -220px;*/
    }

</style>          
    <div class="modal-body">
         <div class="row">
            <!-- {{ Form::open(array('url' => '')) }} -->
            <div class="col-md-11" style="width: 91.66667%;">
              <div class="form-group">
                    {{ Form::label('lastName', __('Search Details'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::text('lastName','', array('class' => 'form-control  
                        ','id'=>'lastName','placeholder'=>'Like last name, first name, full name, mobile, telephone, email')) }}
                    </div>
                    <span class="validate-err" id="err_loc_local_code"></span>
                </div>
            </div> 
           <!--  <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('firstName', __('First Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('firstName','', array('class' => 'form-control  
                        ','id'=>'firstName')) }}
                    </div>
                    <span class="validate-err" id="err_rvy_revision_year"></span>
                </div>
            </div> -->
            <div class="col-md-1" style="padding-top: 28px;">
                <div class="form-group">
                    <div class="form-icon-user">
                    
                    <a href="#" class="btn btn-sm btn-primary" id="search" style="padding-top: 7px;padding-bottom: 7px;">
                                    <span class="btn-inner--icon"><i class="ti-search"></i> <b>Search</b></span>
                                </a>

                    </div>
                </div>
            </div>
            
            <!-- {{Form::close()}} -->
            <style>
            table tr:hover {
              background-color: #ffff99;
            }
            </style>
            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample3">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingone3">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone3" aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">{{__("TAXPAYERS DETAILS")}}</h6>
                            </button>
                        </h6>
                        
                        <div id="flush-collapseone3" class="accordion-collapse collapse show" aria-labelledby="flush-headingone3" data-bs-parent="#accordionFlushExample">
                            <div class="basicinfodiv">
                                <div class="row">
                                   <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body table-border-style">
                                            <div class="table-responsive" style="width: 1278px;">
                                                <table class="table" id="new_taxpayer_details" width="100%">
                                                    <thead>
                <tr id="ownerdetails"  class="clientdata" style="border: 1px solid #ccc;">
                    <th style="width: 10px;background:#3ec9d6;color:#fff;">Select</th>
                    <th style="width: 100px;text-align: left;background:#3ec9d6;color:#fff;">Owner Name</th>
                    <th style="width: 500px;text-align: left;background:#3ec9d6;color:#fff;">Owner Address</th>
                    <th style="width: 20px;background:#3ec9d6;color:#fff;">Phone</th>
                    <th style="width: 20px;background:#3ec9d6;color:#fff;">Email</th>
                </tr>
                @if(($data->id)>0)
                 @foreach ($arrNoHolding as $val)
                <tr id="ownerdetails"  class="clientdata" style="border: 1px solid #ccc;">
                    
                   <!-- {{ Form::text('clientId',(isset($val->clientsId))?$val->clientsId:'', array('id' => 'clientId')) }} -->
                   <td style="width: 20px;"><input type="radio" name="rpc_owner_code" value="{{$val->clientsId}}" class="clientdata" checked></td>
                   <td >{{$val->full_name}}</td>
                    <td >{{$val->rpo_address_house_lot_no}} {{$val->rpo_address_street_name}} {{$val->rpo_address_subdivision}}</td>
                    <td >{{$val->p_mobile_no}}</td>
                    <td >{{$val->p_email_address}}</td>
                </tr>
                @endforeach
               @endif
            </table>
            
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
</div>
   
           <div id="partialData" style=" width: 100%;">
           <style>
            table tr:hover {
              background-color: #ffff99;
            }
            </style>
           <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample1">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingone1">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone1" aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">{{__("PROPERTY DETAILS")}}</h6>
                            </button>
                        </h6>
                        
                        <div id="flush-collapseone1" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                            <div class="basicinfodiv">
                                <div class="row">
                                   <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body table-border-style">
                                            <div class="table-responsive" style="width: 1278px;">
                                                <table class="table" id="new_Property_details" width="100%">
                                                    <thead>
                
                
                <tr id=""  style="border: 1px solid #ccc;background:#3ec9d6;color:#fff;">
                    <th>Select</th>
                    <th>Declared Owner</th>
                    <th>T.D. No.</th>
                    <th>Kind</th>
                    <th>Class</th>
                    <th>LOT|CCT|UNIT NO.|DESCRIPTION</th>
                    <th>Location</th>
                    <th>Market Value</th>
                    <th>Assessed Value</th>
                </tr>
                
                 
               <tbody>
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
        </div>
</div>

    <!--   <script type="text/javascript">
    function ShowHideDiv(chkPassport) {
        var partialData = document.getElementById("partialData");
        partialData.style.display = chkPassport.checked ? "block" : "none";
    }
     </script> -->
     @if(($data->id)>0)
     <div id="myDiv" >
        @else


        <div id="myDiv">
        @endif
        <div class="row" style="margin-top: 10px;
    margin-left: 13px;
    padding: 0px;">
            <div class="modal" id="addStructuralCharacterModal" data-backdrop="static" style="z-index:9999999 !important;">
                <div class="modal-dialog modal-lg modalDiv" >
                    <div class="modal-content" id="structuralCharacterForm">

                    </div>
                </div>
            </div>  
             <div class="row" style="margin-top: 10px;padding-top: 10px;background: #e6d4d4;">
             
            <div class="col-md-7" style="width: 62.84333%;">
              <div class="form-group" id="rpc_requestor_code_group">
                    {{ Form::label('rpc_requestor_code', __('Issue upon the Request of'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('rpc_requestor_code',$arrRequestor,$data->rpc_requestor_code, array('class' => 'form-control select3 
                        ','id'=>'rpc_requestor_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_loc_local_code"></span>
                </div>
            </div>
            
            <div class="col-md-1" style="display: contents;">
              <div class="form-group">
                    {{ Form::label('', __(''),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                         <!-- <a href="#" class="btn btn-sm btn-primary" id="refreshRequestor" style="font-size: 16px;padding: 4px 8px;margin-top: 9px;">
                                    <span class="btn-inner--icon"><i class="ti-reload"></i> </span>
                                </a> -->
                       <!-- <a href="rptcertificateofnolandholding/client" class="btn btn-sm btn-primary" target="_blank" style="font-size: 16px;padding: 4px 8px;margin-top: 9px;">
                            <i class="ti-plus"></i>
                        </a> -->
                        <a href="{{ url('/rptpropertyowner?isopenAddform=1') }}" title="Manage Taxpayer" data-title="Manage Taxpayers" class="btn btn-sm btn-primary" target="_blank" style="font-size: 16px;padding: 4px 8px;margin-top: 9px;">
                                                            <i class="ti-plus"></i>
                        </a>
                     </div>
                        <span class="validate-err" id="err_loc_local_code"></span>
             </div>
            </div>  
            <div class="col-md-2" style="padding-left: 27px;width: 17.10%;">
              <div class="form-group">
                    {{ Form::label('rvy_revision_year', __('Series Of'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('rvy_revision_year',$data->rvy_revision_year, array('class' => 'form-control','id'=>'rvy_revision_year','readonly')) }}
                      <!--  {{ Form::select('rvy_revision_year',$arrRevisionCode,$data->rvy_revision_year, array('class' => 'form-control select3 
                        ','id'=>'rvy_revision_year','required'=>'required')) }} -->
                    </div>
                    <span class="validate-err" id="err_loc_local_code"></span>
                </div>
            </div> 
             <div class="col-md-2" style="width: 16.66667%;">
              <div class="form-group">
                    {{ Form::label('rpc_year', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        
                        <?php
                           $currentYear = date('Y');
                        ?>
                        @if($data->rpc_year)
                        {{ Form::hidden('rpc_year',$data->rpc_year, array('class' => 'form-control','id'=>'rpc_year')) }}
                        @else
                        {{ Form::hidden('rpc_year',$currentYear, array('class' => 'form-control','id'=>'rpc_year')) }}
                        @endif
                        @if($data->rpc_date)
                        {{ Form::date('rpc_date',$data->rpc_date, array('class' => 'form-control  
                        ','id'=>'rpc_date','required'=>'required')) }}
                        @else
                        {{ Form::date('rpc_date',now()->format('Y-m-d'), array('class' => 'form-control  
                        ','id'=>'rpc_date','required'=>'required')) }}
                        @endif
                    </div>
                    <span class="validate-err" id="err_rvy_revision_year"></span>
                </div>
            </div>
           <div class="col-md-4">
              <div class="form-group" id="rpc_city_assessor_code_group">
                    {{ Form::label('rpc_city_assessor_code', __('City Assessor'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('rpc_city_assessor_code',$arrHrEmplyeesAppraisers,$data->rpc_city_assessor_code, array('class' => 'form-control  
                        ','id'=>'rpc_city_assessor_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_rvy_revision_year"></span>
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group" id="rpc_certified_by_code_group">
                    {{ Form::label('rpc_certified_by_code', __('Certified By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('rpc_certified_by_code',$arrHrEmplyeesApproved,$data->rpc_certified_by_code, array('class' => 'form-control  
                        ','id'=>'rpc_certified_by_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_rvy_revision_year"></span>
                </div>
            </div>
             <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('rpc_certified_by_position', __('Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                      {{ Form::text('rpc_certified_by_position',$data->rpc_certified_by_position, array('class' => 'form-control','id'=>'rpc_certified_by_position')) }}
                    </div>
                    <span class="validate-err" id="err_rvy_revision_year"></span>
                </div>
            </div>
            <!-- <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('rpc_cert_type', __('Taxation Procedure'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('rpc_cert_type',array('' =>'Please Select','1'=>'Property Holding','2' =>'No Landholding','3'=>'No Improvement-Portion','4'=>'No Improvement-Whole','5'=>'With Improvement, Tru Mechine-Copy','0'=>'All Certification'), $data->rpc_cert_type, array('class' => 'form-control spp_type','id'=>'rpc_cert_type')) }}
                    </div>
                    <span class="validate-err" id="err_bgf_fee_option"></span>
                </div>
            </div> -->
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('rpc_control_no', __('Control No.'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{ Form::text('rpc_control_no',$data->rpc_control_no, array('class' => 'form-control  
                        ','id'=>'rpc_control_no','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_loc_local_code"></span>
                </div>
            </div> 
            <div class="col-md-4">
              <div class="form-group" id="rpc_or_no_group">
                    {{ Form::label('rpc_or_no', __('O.R.No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('rpc_or_no',$arrOrnumber,$data->rpc_or_no, array('class' => 'form-control select3 
                        ','id'=>'rpc_or_no','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_loc_local_code"></span>
                </div>
            </div> 
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('rpc_or_date', __('O.R.Date'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::date('rpc_or_date',$data->rpc_or_date, array('class' => 'form-control  
                        ','id'=>'rpc_or_date','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_rvy_revision_year"></span>
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('rpc_or_amount', __('O.R. Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::number('rpc_or_amount',$data->rpc_or_amount, array('class' => 'form-control  
                        ','id'=>'rpc_or_amount','step'=>'0.01','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_rvy_revision_year"></span>
                </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                    {{ Form::label('rpc_remarks', __('Remarks'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('rpc_remarks',$data->rpc_remarks, array('class' => 'form-control  
                        ','id'=>'rpc_remarks')) }}
                    </div>
                    <span class="validate-err" id="err_rvy_revision_year"></span>
                </div>
            </div>
        </div>
       
        </div>
     </div>  

</div>
       
       
       


        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}?rand={{ rand(000,999) }}"></script>
<script src="{{ asset('js/addCertificateOfImprovment.js') }}?rand={{ rand(000,999) }}"></script>
<!-- <script src="{{ asset('js/addCertificateOfNoHolding.js') }}?rand={{ rand(000,999) }}"></script> -->




