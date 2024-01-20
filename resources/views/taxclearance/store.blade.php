{{Form::open(array('name'=>'forms','url'=>route('taxclearance.store'),'method'=>'post','id'=>'taxClearanceForm'))}}
<input type="hidden" name="id" id="id" value="{{$data->id}}">
<input type="hidden" name="rptc_year" id="rptc_year" value="{{($data->rptc_year != '')?$data->rptc_year:date('Y')}}">
<input type="hidden" name="rptc_control_no" id="rptc_control_no" value="{{($data->rptc_control_no != '')?$data->rptc_control_no:''}}">
 <style>
    .modal-xll {
        max-width: 1500px !important;
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
    .choices__inner {
        min-height: 35px;
        padding:5px ;
        padding-left:5px;
    }
    .field-requirement-details-status label{margin-top: 7px;}
    #flush-collapsetwo{
/*        padding-bottom: 80px;*/
    }
   .select3-container {
    box-sizing: border-box;
    display: block;
   
    margin: 0;
     position: inherit; 
    vertical-align: middle;
}


 </style>

<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <div class="form-group">
                        {{Form::label('rptc_owner_code',__("Declared Owner"),['class'=>'form-label'])}} <span class="text-danger">*</span>
                    </div>
                </div>
                
                 <div class="col-lg-6 col-md-6 col-sm-6" style="margin-left:-100px;margin-right: 0px;padding-right: 0px;width: 780px;">
                    <div class="form-group">
                            {{Form::select('rptc_owner_code',[],(isset($data->rptc_owner_code))?$data->rptc_owner_code:'',array('class'=>'form-control','id'=>'rptc_owner_code','placeholder'=>'Select Declared Owner','style'=>'width:800px;'))}}
                             <span class="validate-err" id="err_rptc_owner_code"></span>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1" style="text-align:;padding-left: 14px;margin-left: 7px;">
                    <div class="form-group">
                        <!-- <button id="searchTdNo" value="Add" class="btn btn-primary" ></button> -->
                        <a href="{{ url('/rptpropertyowner?isopenAddform=1') }}" title="Manage Taxpayer" data-title="Manage Taxpayers" class="btn btn-sm btn-primary" target="_blank" style="font-size: 16px;padding: 4px 8px;margin-top: 2px;">
                                                            <i class="ti-plus" style="font-size: 22px;padding: 5px;"></i>
                        </a>
                    </div>

                </div>
                <div class="col-lg-1 col-md-1 col-sm-1" style="margin-left: -46px;">
                    <div class="form-group">
                        {{Form::label('rptc_date',__('Date'),['class'=>'form-label'])}} <span class="text-danger">*</span>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <div class="form-group">
                           {{Form::text('rptc_date',($data->rptc_date != '')?$data->rptc_date:date("Y-m-d"),['class'=>'form-control rptc_date','id'=>'rptc_date','readonly'=>'readonly','style'=>'width: 286px;']);}} 
                           <span class="validate-err" id="err_rptc_date"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <div class="form-group">
                        {{Form::label('address',__("Address"),['class'=>'form-label'])}} <span class="text-danger">*</span>
                    </div>
                </div>
                 <div class="col-lg-7 col-md-7 col-sm-7" style="margin-left:-100px;">
                    <div class="form-group">
                            {{Form::text('address','',['class'=>'form-control rptc_date','id'=>'address','readonly'=>'readonly']);}} 
                             <span class="validate-err" id="err_rptc_owner_code"></span>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1">
                    <div class="form-group">
                        {{Form::label('rptc_date',__('Control No.'),['class'=>'form-label'])}} 
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <div class="form-group">
                           {{Form::text('rptc_control_no',($data->rptc_control_no != '')?$data->rptc_control_no:'',['class'=>'form-control rptc_date','id'=>'rptc_date','readonly'=>'readonly','style'=>'width: 286px;']);}} 
                           <span class="validate-err" id="err_rptc_date"></span>
                    </div>
                </div>
            </div>
        </div>
       
        
    </div>

    <div class="row pt10" >
        <!--------------- Owners Information Start Here---------------->
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone1" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle">{{__("Tax Declaration Reference")}}</h6>
                        </button>
                    </h6>
                    <div id="flush-collapseone1" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                        <div class="basicinfodiv">
                           <div class="row">
                            
                            <div class="col-lg-1 col-md-1 col-sm-1">
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2"style="text-align: ;padding-top: 2px;">
                                         <div class="select-group">
                                            <div class="form-icon-user">
                                             {{ Form::checkbox('checkowner','1', ('')?true:false, array('id'=>'checkowner','class'=>'form-check-input myCheckbox','style'=>'margin-top:9px')) }} {{Form::label('',__('Taxpayer Reference'),['class'=>'form-label','style'=>'padding-top: 7px;'])}}
                                            </div>
                                         </div>
                                    </div>

                            <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">
                                <div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group">
                                            {{Form::label('rp_code',__("TD No"),['class'=>'form-label'])}}
                                            <input type="hidden" name="rp_code">
                                        </div>
                                    </div>
                                    <!-- div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                               {{Form::text('brgy_no',(isset($brngyDetails->brgy_code))?$brngyDetails->brgy_code:'',array('class'=>'form-control brgy_no','id'=>'brgy_no','readonly'=>true))}} -->
                                               <input type="hidden" name="brgy_code" value="{{(isset($brngyDetails->id))?$brngyDetails->id:''}}">
                                          <!-- <span class="validate-err" id="err_brgy_no"></span>
                                        </div>
                                    </div> -->
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                             {{Form::select('rp_td_no',[],'',array('class'=>'form-control rp_td_no','id'=>'rp_td_no','placeholder'=>'Select TD No.'))}}
                                               <!-- {{Form::text('rp_td_no','',array('class'=>'form-control rp_td_no','id'=>'rp_td_no','placeholder' => 'Input T.D. No.'))}} -->
                                          <span class="validate-err" id="err_rp_td_no"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group">
                                            <button id="searchTdNo" value="Add" class="btn btn-primary" style="padding:0px;margin: 0px;padding-bottom: 5px;padding-top: 5px;"><i class="ti-save" style="font-size: 23px;margin: 0px;padding: 14px;"></i></button>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group">
                                            {{Form::label('rptc_owner_code',__("Taxpayer Name"),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5" style="margin-left:-1px;">
                                        <div class="form-group">
                                               {{Form::text('clientName','',array('class'=>'form-control owner','id'=>'clientName','readonly'))}}
                                                 <span class="validate-err" id="err_rptc_owner_code"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                     <div class="col-xl-12">
                <div class="card" style="">
                    <div class="card-body table-border-style" style="">
                        <div class="table-responsive" id="loadSelectedTdsForTaxClearance" style="">
                            
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
       
         <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone5" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle" id="paymentRecordTitle">{{ __("Payment Record", ['id' => '']) }}  </h6>
                        </button>
                    </h6>
                    <div id="flush-collapseone5" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                        <div class="basicinfodiv">
                           <div class="row">
                     <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive" >
                            <table class="table" id="loadSelectedTdsPayment">
                                <thead>
                                    <tr>
                                        <th>{{__('NO.')}}</th>
                                        <th>{{__('Taxpayer')}}</th>
                                        <th>{{__('T.D. No.')}}</th>
                                        <th>{{__('Period Covered')}}</th>
                                        <th>{{__('Assessed Value')}}</th>
                                        <th>{{__('OR Number')}}</th>
                                        <th>{{__('OR Amount')}}</th>
                                        <th>{{__('OR Date')}}</th>
                                        <th>{{__('status')}}</th>
                                    </tr>
                                </thead>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--------------- Land Apraisal Listing End Here------------------><br />
                          
                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Owners Information Start Here---------------->

        
        <!--------------- Business Information End Here------------------>
    </div>

    <div class="row pt10" >
        <!--------------- Owners Information Start Here---------------->
        <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
            <div  class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingone">
                        <!-- <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle">{{--__("Owner's Information")--}}</h6>
                        </button> -->
                    </h6>
                    <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                        <div class="basicinfodiv">
                           <div class="row">
           <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row">
                <!-- <div class="col-lg-2 col-md-2 col-sm-2"></div> -->
                <div class="col-lg-3 col-md-3 col-sm-3">
                                            <div class="form-group">

                                    {{Form::label('rptc_including_year',__('Tax(es) paid upto and including the year :'),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                       {{Form::text('rptc_including_year',($data->rptc_including_year)? $data->rptc_including_year: date('Y'),['class'=>'form-control rptc_including_year','id'=>'rptc_including_year']);}} 
                                       <span class="validate-err" id="err_rptc_including_year"></span>

                                   
                                </div>
                            </div>
                            
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                            <div class="form-group">

                                    {{Form::label('rptc_purpose',__("Purpose"),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                    
                                </div>
                            </div>
                             <div class="col-lg-5 col-md-5 col-sm-5">
                                <div class="form-group">
                                        {{Form::text('rptc_purpose',$data->rptc_purpose,['class'=>'form-control rptc_purpose','id'=>'rptc_purpose']);}}
                                      
                                  <span class="validate-err" id="err_rptc_purpose"></span>
                                   
                                </div>
                            </div>
                
                
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">
            <div class="row">
                <div class="col-lg-1 col-md-1 col-sm-1">
                                            <div class="form-group">

                                    {{Form::label('rptc_requestor_code',__('Requested By'),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4" >
                                <div class="form-group" style="width: 535px;">
                                        {{Form::select('rptc_requestor_code',[],(isset($data->rptc_requestor_code))?$data->rptc_requestor_code:'',array('class'=>'form-control rptc_requestor_code','id'=>'rptc_requestor_code','placeholder'=>'Requested By'))}} 
                                       <span class="validate-err" id="err_rptc_requestor_code"></span>

                                   
                                </div>
                            </div>
                             <div class="col-lg-1 col-md-1 col-sm-1" >
                                <div class="form-group">
                                    <!-- <button id="searchTdNo" value="Add" class="btn btn-primary" ><i class="ti-plus" style="font-size: 13px;padding: 0px; margin: 0px;"></i></button> -->
                                    <a href="{{ url('/rptpropertyowner?isopenAddform=1') }}" style="padding: 9px; float: right;" title="Manage Taxpayer" data-title="Manage Taxpayers" class="btn btn-sm btn-primary" target="_blank" >
                                    <i class="ti-plus" ></i></a>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1" >
                                            <div class="form-group">

                                    {{Form::label('rptc_checked_by',__("Checked By"),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                    
                                </div>
                            </div>
                             <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    {{Form::select('rptc_checked_by',[],(isset($data->rptc_checked_by))?$data->rptc_checked_by:$userDetails->name,array('class'=>'form-control rptc_checked_by select3','id'=>'rptc_checked_by','placeholder'=>'Select Name'))}}
                                        <!-- {{Form::text('rptc_checked_by_name',($data->rptc_checked_by_name != '')?$data->rptc_checked_by_name:$userDetails->name,['class'=>'form-control rptc_checked_by_name','id'=>'rptc_checked_by_name']);}} -->
                                        <!-- <input type="hidden" name="rptc_checked_by" value="{{ ($data->rptc_checked_by != '')?$data->rptc_checked_by:$userDetails->id}}"> -->
                                      
                                  <span class="validate-err" id="err_rptc_checked_by"></span>
                                   
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                            <div class="form-group">

                                    {{Form::label('rptc_checked_position',__("Position"),['class'=>'form-label'])}}
                                    
                                </div>
                            </div>
                             <div class="col-lg-2 col-md-2 col-sm-2" >
                                <div class="form-group">
                                        {{Form::text('rptc_checked_position',$data->rptc_checked_position,['class'=>'form-control rptc_checked_position','id'=>'rptc_checked_position']);}}
                                      
                                  <span class="validate-err" id="err_rptc_checked_position"></span>
                                   
                                </div>
                            </div>
                
                
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">
            <div class="row">
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                            <div class="form-group">

                                    {{Form::label('rptc_or_no',__('O.R. No.'),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <div class="form-group">
                                       
                                       {{Form::select('rptc_or_no',[],$data->rptc_or_no,array('class'=>'form-control rptc_or_no','id'=>'rptc_or_no','placeholder'=>'Select O.R. No.'))}}
                                       <span class="validate-err" id="err_rptc_or_no"></span>

                                   
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                            <div class="form-group">

                                    {{Form::label('rptc_prepared_by',__("Prepared By"),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                    
                                </div>
                            </div>
                             <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    {{Form::select('rptc_prepared_by',[],(isset($data->rptc_prepared_by))?$data->rptc_prepared_by:$userDetails->name,array('class'=>'form-control rptc_prepared_by select3','id'=>'rptc_prepared_by','placeholder'=>'Select Name'))}}


                                        <!-- {{Form::text('rptc_prepared_by_name',($data->rptc_prepared_by_name != '')?$data->rptc_prepared_by_name:$userDetails->name,['class'=>'form-control rptc_prepared_by_name','id'=>'rptc_prepared_by_name']);}} -->
                                      <!-- <input type="hidden" name="rptc_prepared_by" value="{{ ($data->rptc_prepared_by != '')?$data->rptc_prepared_by:$userDetails->id}}"> -->
                                  <span class="validate-err" id="err_rptc_prepared_by"></span>
                                   
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                            <div class="form-group">

                                    {{Form::label('rptc_prepared_position',__("Position"),['class'=>'form-label'])}}
                                    
                                </div>
                            </div>
                             <div class="col-lg-2 col-md-2 col-sm-2" >
                                <div class="form-group">
                                        {{Form::text('rptc_prepared_position',$data->rptc_prepared_position,['class'=>'form-control rptc_prepared_position','id'=>'rptc_prepared_position']);}}
                                      
                                  <span class="validate-err" id="err_rptc_prepared_position"></span>
                                   
                                </div>
                            </div>
                
                
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">
            <div class="row">
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                            <div class="form-group">

                                    {{Form::label('rptc_or_amount',__("Amt. Paid"),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                    
                                </div>
                            </div>
                             <div class="col-lg-5 col-md-5 col-sm-5">
                                <div class="form-group">
                                        {{Form::text('rptc_or_amount',$data->rptc_or_amount,['class'=>'form-control rptc_or_amount decimalvalue','id'=>'rptc_or_amount','readonly'=>true]);}}
                                      
                                  <span class="validate-err" id="err_rptc_or_amount"></span>
                                   
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                            <div class="form-group">

                                    {{Form::label('rpo_code',__("Dated"),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                    
                                </div>
                            </div>
                             <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                        {{Form::date('rptc_or_date',$data->rptc_or_date,['class'=>'form-control rptc_or_date','id'=>'rptc_or_date','readonly'=>true]);}}
                                        <input type="hidden" name="cashier_id" value="{{$data->cashier_id}}">
                                        <input type="hidden" name="cashier_detail_id" value="{{$data->cashier_detail_id}}">
                                  <span class="validate-err" id="err_rptc_or_date"></span>
                                   
                                </div>
                            </div>
                            
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                            <div class="form-group">

                                    {{Form::label('rptc_owner_tin_no',__("T.I.N"),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                    
                                </div>
                            </div>
                             <div class="col-lg-2 col-md-2 col-sm-2" >
                                <div class="form-group">
                                        {{Form::text('rptc_owner_tin_no',$data->rptc_owner_tin_no,['class'=>'form-control rptc_owner_tin_no','id'=>'rptc_owner_tin_no','readonly'=>true]);}}
                                      
                                  <span class="validate-err" id="err_rptc_owner_tin_no"></span>
                                   
                                </div>
                            </div>
                
                
            </div>
        </div>
        </div>
        <!--------------- Land Apraisal Listing End Here------------------><br />
                          
                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   

</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="" value="Save Changes" class="btn btn-primary" >
</div>
{{ Form::close() }}

<input type="hidden" name="dynamicid" value="3" id="dynamicid">
<script src="{{ asset('js/taxclearance/addTaxClearance.js') }}?rand={{rand(0,999)}}"></script>
<script type="text/javascript">
    setTimeout(function() { 
        var id = "{{($data->rptc_owner_code != '')?$data->rptc_owner_code:''}}";
      if(id > 0){
      var text = "{{(isset($data->owner->full_name) && $data->owner != '')?$data->owner->full_name:'Select Property Owner'}}";
               $("#rptc_owner_code").select3("trigger", "select", {
    data: { id: id ,text:text}
});
            }

      var requesturid = "{{($data->rptc_requestor_code != '')?$data->rptc_requestor_code:''}}";
      if(requesturid > 0){
      var requesturtext = "{{(isset($data->requester->full_name) && $data->owner != '')?$data->requester->full_name:'Select Property Owner'}}";
               $("#rptc_requestor_code").select3("trigger", "select", {
    data: { id: requesturid ,text:requesturtext}
});
            }
            var cashid = "{{(isset($data->cashier_detail_id) && $data->cashier_detail_id != '')?$data->cashier_detail_id:''}}";
            var amount = "{{(isset($data->total_paid_amount) && $data->total_paid_amount != '')?$data->total_paid_amount:''}}";
            var ordate = "{{(isset($data->cashier_or_date) && $data->cashier_or_date != '')?$data->cashier_or_date:''}}";
            var cash_id = "{{(isset($data->cashier_id) && $data->cashier_id != '')?$data->cashier_id:''}}";
      if(cashid > 0){
      var cashtext = "{{(isset($data->or_no) && $data->owner != '')?$data->or_no:'Select OR No.'}}";
               $("#rptc_or_no").select3("trigger", "select", {
    data: { id: cashtext ,text:cashtext,total_paid_amount:amount,cashier_or_date:ordate,cashier_id:cash_id,ccdid:cashid}
});
            }

            var reqid = "{{($data->rptc_checked_by != '')?$data->rptc_checked_by:''}}";
            var checkPos = "{{($data->rptc_checked_position != '')?$data->rptc_checked_position:''}}";
      if(reqid > 0){
      var reqtext = "{{(isset($data->checkedBy->fullname) && $data->owner != '')?$data->checkedBy->fullname:'Select Checked By'}}";
               $("#rptc_checked_by").select3("trigger", "select", {
    data: { id: reqid ,text:reqtext,description:checkPos}
});
            }

            var prepareid = "{{($data->rptc_prepared_by != '')?$data->rptc_prepared_by:''}}";
            var preparePos = "{{($data->rptc_prepared_position != '')?$data->rptc_prepared_position:''}}";
      if(prepareid > 0){
      var preparetext = "{{(isset($data->prepareBy->fullname) && $data->owner != '')?$data->prepareBy->fullname:'Select Preapared By'}}";
               $("#rptc_prepared_by").select3("trigger", "select", {
    data: { id: prepareid ,text:preparetext,description:preparePos}
});
            }
    }, 500);
    
</script>