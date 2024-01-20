{{Form::open(array('url'=>'bfpapplicationform/assesnow','method'=>'post'))}}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
 <style>
    .modal-xl {
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
    .pt10{
        padding-top:10px;
    }

    .fee-details td:nth-child(5), .fee-details td:nth-child(7) {
        background: #80808052;
    }
    .fee-details td:nth-child(4), .fee-details td:nth-child(6), .fee-details td:nth-child(8) {
        background: #20b7cc42;
    }
    .fee-details tr:last-child{
        background: #80808052;
    }
    .sky-blue{
        background: #20B7CC !important;
        color:#fff;
        font-weight: bold;
    }
    .red{
        background: red !important;
        color:#fff;
        font-weight: bold;
    }
    .closeModel{cursor:pointer;}
    .align-center{text-align: center;}
    .inputpicker-div{
        height: unset !important;
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
    .field-requirement-details-status label{margin-top: 7px;}
 </style>

<div class="modal-body">
   
    <div class="row pt10" >
        
    

        <div class="col-lg-12 col-md-12 col-sm-12">
            <p style="text-align: center;"><b><u>ORDER OF PAYMENT</u></b></p>
          
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12" >
            <div class="row">
             <div class="col-sm-9">
                <p>NAME OF STABLEISHMENT/PROJECT : <b>{{$data->p_complete_name_v1}}</b></p>
                <p>LOCATION : <b>{{$data->ba_address_house_lot_no}} {{$data->ba_address_street_name}}</b></p>
                <p>OWNER/NAME OF REPRESENTATIVE :{{$data->p_first_name}} {{$data->p_middle_name}} {{$data->p_family_name}}</p>
             </div>
             <div class="col-sm-3">
                  <p>OPS No.<u style="width: 20px;"></u></p>
                  <p>Date:<u> {{$data->ba_date_started}}</u>
             </div>
           </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <p>FIRE SAFETY CLEARANCE APPLYING FOR :</p>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <p>Fire Safety Evaluation Clearance(FSIC)</p>
         </div>
         <div class="col-lg-4 col-md-4 col-sm-4">
            <p>Fire Safety Inspection Certificate(FSIC)</p>
         </div>
         <div class="col-lg-4 col-md-4 col-sm-4">
            <p>Others:<u>(Pis indicate)</u></p>
         </div>
    </div>
     </div>
        
    <!--------------- Fees Details End Here------------------>
    <div class="row fee-details">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="">
                            <div class="row field-requirement-details-status">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                           {{__('APPLICABLE FEES (FILL-up).')}}
                                      </div>
                                     <div class="col-lg-4 col-md-4 col-sm-4">
                                      </div>
                                      <div class="col-lg-2 col-md-2 col-sm-2">
                                        <input type="button" id="btn_addmore_feedetails" class="btn btn-success" value="Add More" style="padding: 0.4rem 0.76rem !important;">
                                     </div>
                            </div>
                           <span class="checkboxesdata activity-details" id="feedetails">
                            
                           </span>        
                    </div>
                </div>
            </div>
        </div>
    </div>

     <div class="row fee-details">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="">
                            <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                           {{__('Total Amount(in words).')}}
                                      </div>
                                     <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group"> {{ Form::label('', __(''),['class'=>'form-label','id'=>'amountinword']) }}</div>
                                      </div>
                                       <div class="col-lg-2 col-md-2 col-sm-2">
                                         {{ Form::label('showpaidamount', __('Paid Amount'),['class'=>'form-label','id'=>'']) }}
                                     </div>
                                      <div class="col-lg-2 col-md-2 col-sm-2">
                                         <div class="form-group">{{ Form::text('showpaidamount','', array('class' => 'form-control naofbussi showpaidamount','required'=>'required','id'=>'showpaidamount')) }}</div>
                                     </div>
                            </div>
                           <span class="checkboxesdata activity-details" id="feedetails">
                            
                           </span>        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--------------- Fees Details End Here------------------>

    <!--------------- Other Details End Here------------------>
   
        
    <!--------------- Other Details End Here------------------>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" name="submit" value="{{('Create')}}" class="btn  btn-primary">
</div>
{{Form::close()}}

<div id="hidenCheckboxHtml" class="hide">
     <div class="row removefeemasterdata" style="padding: 5px 0px;">
         <div class="col-lg-8 col-md-8 col-sm-8">
                    <div class="form-group">
                        <div class="form-icon-user">{{ Form::select('arrayoffee',$feemasterarray,'',array('class' => 'form-control arrayoffee','required'=>'required','id'=>'arrayoffee0')) }}</div>
                    </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">{{ Form::text('basicofcomputation[]','', array('class' => 'form-control naofbussi disabled-field','required'=>'required','id'=>'basicofcomputation')) }}</div>
        </div>
         <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="form-group">{{ Form::text('paidamount[]','', array('class' => 'form-control naofbussi paidamount','required'=>'required','id'=>'paidamount')) }}</div>
        </div>
         <div class="col-lg-1 col-md-1 col-sm-1"><div class="form-group"><input type="button" name="btn_cancel" class="btn btn-success btn_cancel_feemaster" cid="" value="Delete" style="padding: 0.4rem 1rem !important;"></div></div>
         <span class="checkboxarea row"></span>
    </div>
</div>
<script src="{{ asset('js/assesNow.js') }}"></script>