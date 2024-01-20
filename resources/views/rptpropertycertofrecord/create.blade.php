
{{ Form::open(array('url' => 'rptpropertycertofpropertyholding')) }}
            {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  <style type="">
      .select3-container--default .select3-selection--single .select3-selection__rendered {
    color: #444;
    line-height: 28px;
    background: #fff;
}
    
.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width:950px;
    pointer-events: auto;
    background-color: #ffffff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 15px;
    outline: 0;
}
/*
 * bootstrap-tagsinput v0.8.0
 * 
 */
.badge {
    display: inline-block;
    padding: 0.35em 0.5em;
    font-size: 0.75em;
    font-weight: 500;
    line-height: 1;
    color: #0a0909; 
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 2px;
}
.bootstrap-tagsinput .badge {
  margin: 2px 0;
  padding:5px 8px;
}
.bootstrap-tagsinput .badge [data-role="remove"] {
  margin-left: 8px;
  cursor: pointer;
}
.bootstrap-tagsinput .badge [data-role="remove"]:after {
  content: "×";
  padding: 0px 4px;
  background-color:rgba(0, 0, 0, 0.1);
  border-radius:50%;
  font-size:13px
}
.bootstrap-tagsinput .badge [data-role="remove"]:hover:after {

  background-color:rgba(0, 0, 0, 0.62);}
.bootstrap-tagsinput .badge [data-role="remove"]:hover:active {
  box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
}
input, button, select, optgroup, textarea {
    margin: 0;
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
    border: none;
}
.bootstrap-tagsinput {
    padding: 5px 10px;
    line-height: 28px;
    background: #f8f9fd;
    border: 1px solid #938181;
    border-radius: 10px;
    width: 100%;
}
</style>          
    <div class="modal-body">
         <div class="row">
            <!-- {{ Form::open(array('url' => '')) }} -->
            <div class="col-md-8">
              <div class="form-group">
                    {{ Form::label('lastName', __('Search Details'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       <!-- <input type="text" data-role="tagsinput"  > -->
  

                       {{ Form::text('lastName','', array('class' => 'form-control  
                        ','id'=>'lastName','data-role'=>'tagsinput')) }}
                        <!-- ,'placeholder'=>'Like last name, first name, full name, mobile, telephone, email' -->
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
            <div class="col-md-2" style="padding-top: 32px;">
                <div class="form-group">
                    <div class="form-icon-user">
                    
                    <a href="#" class="btn btn-sm btn-primary" id="search">
                                    <span class="btn-inner--icon"><i class="ti-search"></i> <b>search</b></span>
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
            <table style="border: 1px solid #ccc;padding: 9px;text-align: center;">
                <tr style="border: 1px solid #ccc;">
                        <th colspan="6"><h2 style="background: #6988f8;color:#fff;padding: 7px;font-size: 16px;">Owner Details</h2></th>
                    
                </tr>
                <tr id="ownerdetails"  class="clientdata" style="border: 1px solid #ccc;">
                    <th>Select</th>
                    <th>Code</th>
                    <th>Owner Name</th>
                    <th>Owner Address</th>
                    
                </tr>
                @if(($data->id)>0)
                 @foreach ($arrNoHolding as $val)
                <tr id="ownerdetails"  class="clientdata" style="border: 1px solid #ccc;">
                   
                    <td><input type="checkbox" name="rpc_owner_code" value="{{$val->clientsId}}" class="clientdata" checked></td>
                    <td>{{$val->clientsId}}</td>
                    <td>{{$val->rpo_first_name}} {{$val->rpo_middle_name}} {{$val->rpo_custom_last_name}}</td>
                    <td>{{$val->rpo_address_house_lot_no}} {{$val->rpo_address_street_name}} {{$val->rpo_address_subdivision}}</td>
                </tr>
                @endforeach
               @endif
               
            </table>
   
   
           <div id="partialData" style=" width: 100%;">
           <style>
            table tr:hover {
              background-color: #ffff99;
            }
            </style>
           <table style="border: 1px solid #ccc;padding: 9px;text-align: center;margin-top: 25px;    margin-left: -11px;width: 103%; ">
            <tr style="border: 1px solid #ccc;">
                    <th colspan="10"><h2 style="background: #6988f8;color:#fff;padding: 7px;font-size: 16px;">Select Property Holding</h2></th>
                    
                </tr>
                
                
                <tr id="partial"  style="border: 1px solid #ccc;">
                    <th>Select</th>
                    <th>Declared Owner</th>
                    <th>T.D. No.</th>
                    <th>Kind</th>
                    <th>Class</th>
                    <th>Lot No.</th>
                    <th>Location</th>
                    <th>Market Value</th>
                    <th>Assessed Value</th>
                    <th>Delete</th>
                </tr>
                
                @if(($data->id)>0)
                @foreach ($arrAppHolding as $val)
                <tr id="ownerdetails"  class="clientdata" style="border: 1px solid #ccc;">
                   
                    <td><input type="checkbox" name="rpa_code[]" value="{{$data->id}}"  checked>
                        <input type="hidden" name="rp_code[]"  value="{{$val->propertyId}}">
                    </td>
                    <td>{{$val->rpo_first_name}} {{$val->rpo_middle_name}} {{$val->rpo_custom_last_name}}</td>
                    <td>{{$val->rp_tax_declaration_no}}</td>
                    <td>{{$val->pc_class_code}}</td>
                    <td>{{$val->pk_code}}</td>
                    <td>{{$val->rp_cadastral_lot_no}}</td>
                    <td>{{$val->brgy_name}}</td>
                    <td>{{$val->rpa_base_market_value}}</td>
                    <td>{{$val->rpa_assessed_value}}</td>
                    <td><div class="action-btn bg-danger ms-2">
                <a href="#" class="mx-2 btn btn-sm deleterow ti-trash text-white text-white" id='{{$val->rptcertDId}}'>
                   </a>
             </div></td>
                </tr>
                @endforeach
               @endif
                 
               <tbody>
               </tbody>
           </table>
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


        <div id="myDiv" style="display: none">
        @endif
        <div class="row" style="margin-top: 50px;background: #e6d4d4;">
             <div class="col-md-2">
              <div class="form-group">
                    {{ Form::label('rvy_revision_year', __('Series Of'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('rvy_revision_year',$data->rvy_revision_year, array('class' => 'form-control','id'=>'rvy_revision_year')) }}
                      <!--  {{ Form::select('rvy_revision_year',$arrRevisionCode,$data->rvy_revision_year, array('class' => 'form-control select3 
                        ','id'=>'rvy_revision_year','required'=>'required')) }} -->
                    </div>
                    <span class="validate-err" id="err_loc_local_code"></span>
                </div>
            </div> 
            <div class="col-md-6">
              <div class="form-group">
                    {{ Form::label('rpc_requestor_code', __('Issue upon the Request of'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('rpc_requestor_code',$arrRequestor,$data->rpc_requestor_code, array('class' => 'form-control select3 
                        ','id'=>'rpc_requestor_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_loc_local_code"></span>
                </div>
            </div> 
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('rpc_year', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        
                        {{ Form::date('rpc_year',$data->rpc_year, array('class' => 'form-control  
                        ','id'=>'rpc_year','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_rvy_revision_year"></span>
                </div>
            </div>
            
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('rpc_city_assessor_code', __('City Assosser'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('rpc_city_assessor_code',$arrHrEmplyees,$data->rpc_city_assessor_code, array('class' => 'form-control select3 
                        ','id'=>'rpc_city_assessor_code','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_rvy_revision_year"></span>
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('rpc_certified_by_code', __('Certified By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('rpc_certified_by_code',$arrHrEmplyees,$data->rpc_certified_by_code, array('class' => 'form-control select3 
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
              <div class="form-group">
                    {{ Form::label('rpc_or_no', __('O.R.No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::text('rpc_or_no',$data->rpc_or_no, array('class' => 'form-control  
                        ','id'=>'rpc_or_no','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_loc_local_code"></span>
                </div>
            </div> 
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('rpc_or_date', __('O.R.Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::date('rpc_or_date',$data->rpc_or_date, array('class' => 'form-control  
                        ','id'=>'rpc_or_date','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_rvy_revision_year"></span>
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('rpc_or_amount', __('O.R. Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::number('rpc_or_amount',$data->rpc_or_amount, array('class' => 'form-control  
                        ','id'=>'rpc_or_amount','required'=>'required','step'=>'0.01')) }}
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
<script src="{{ asset('js/addCertificateOfHolding.js') }}?rand={{ rand(000,999) }}"></script>
<script src="{{ asset('js/tagsinput.js') }}?rand={{ rand(000,999) }}"></script>




