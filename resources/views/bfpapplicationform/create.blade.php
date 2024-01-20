<style>
    
    {
   height: 10%;
 }
.struct {
    position: absolute;
    display: none;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: #8b00002b;
}
.prompt {
    
    position: absolute;
    width: 50%;
    height: 10%;
    top: 25%;
    color: #fff;
    font-size: 26px;
    padding-top: 44px;
    left: 25%;
    border-radius: 1%;
    text-align: center;
    background: #601212bd;

} 

    .modal-xl {
        max-width: 1350px !important;
    }
    .accordion-button{
        margin-bottom: 12px;
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
        
    }
    .choices__inner {
        min-height: 35px;
        padding:5px ;
        padding-left:5px;
    }
    .field-requirement-details-status label{margin-top: 7px;}
    #flush-collapsetwo{
        padding-bottom: 20px;
    }
    .modal:nth-of-type(even) {
        z-index: 1052 !important;
    }
    .modal-backdrop.show:nth-of-type(even) {
        z-index: 1051 !important;
    }
    .bussiness-model{
        padding-top: 8%;
    }
    .sub-title{ padding-top: 7px; }
    .form-group {
    /* margin-bottom: 1.3rem; */
    margin: 0px;
}
.modal-footer {
            display: flex;
            flex-wrap: wrap;
            flex-shrink: 0;
            align-items: center;
            justify-content: flex-end;
            padding-right: 41px;
            /* padding: 1rem; */
            border-top: 1px solid #f1f1f1;
            border-bottom-right-radius: 14px;
            border-bottom-left-radius: 14px;
        }

</style>




@php 
    $busid=$_GET['busn_id'];
    $bend_id=$_GET['bend_id'];
    $year=$_GET['year'];
    @endphp

{{Form::open(array('url'=>'bfpapplicationform','method'=>'post','enctype'=>'multipart/form-data','id'=>'bfpapplicationformForm'))}}
    {{ Form::hidden('id',$dataApplication->id, array('id' => 'id')) }}
    {{Form::hidden('bend_id',$bend_id,array('id' => 'bend_id'))}}
    {{ Form::hidden('busn_id',$busid, array('id' => 'busn_id')) }}
    {{ Form::hidden('year',$year, array('id' => 'year')) }}
    {{ Form::hidden('client_id',$dataApplication->client_id, array('id' => 'client_id')) }}
    {{ Form::hidden('barangay_id','', array('id' => 'barangay_id')) }}
    {{Form::hidden('bot_id',$dataApplication->bot_id,array('id'=>'bot_id'))}}

    
    <div class="modal-body">
        <div class="row">
            <font id="checkederror">please check option</font>
            <!-- <div class="col-lg-3 col-md-2 col-sm-2">
                <div class="form-group">
                    {{Form::label('ba_business_account_no',__('Business Account No.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('ba_business_account_no',$accountnos,'',array('class' => 'form-control select3','id'=>'ba_business_account_no')) }}
                    </div>
                    <span class="validate-err" id="err_ba_business_account_no"></span>
                </div>
            </div> -->

            <!--  <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    {{Form::label('bff_date',__('Application  Date'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{Form::date('bff_date',$dataApplication->bff_date,array('class'=>'form-control','required'=>'required'))}}
                    </div>
                    <span class="validate-err" id="err_ba_cover_year"></span>
                </div>
            </div>
            

            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    {{Form::label('bff_year',__('Covered Year'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{Form::number('bff_year',$dataApplication->bff_year,array('class'=>'form-control','required'=>'required'))}}
                    </div>
                    <span class="validate-err" id="err_ba_cover_year"></span>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                {{Form::label('bff_application_no',__('Application No.'),['class'=>'form-label'])}}
                <div class="form-icon-user">
                    {{Form::text('bff_application_no',$dataApplication->bff_application_no,array('class'=>'form-control','readonly'=>'readonly'))}}
                </div>
            </div>
        </div> -->
        </div>
        <div class="row pt10" >
            <!--------------- Owners Information Start Here---------------->
            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingone">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">{{__("Owner Information")}}</h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                            <div class="basicinfodiv">
                                <div class="row">
                                   <!--  <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('ba_p_last_name',__('Name Of Owner'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::text('ba_p_last_name','',array('class'=>'form-control','required'=>'required'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_last_name"></span>
                                        </div>
                                    </div> -->
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <div class="form-group">
                                            {{Form::label('ba_p_first_name',__('Name of Owner'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::text('ba_p_first_name','',array('class'=>'form-control','required'=>'required','readonly'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_first_name"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('busns_id_no',__('Business ID No.'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('busns_id_no','',array('class'=>'form-control','readonly'))}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <div class="form-group">
                                            {{Form::label('businessName',__('Building | Locality | Structure | Business | Establishment Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::textarea('businessName','',array('class'=>'form-control','rows'=>1,'readonly'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_address"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            {{Form::label('bff_application_no',__('Application No.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::text('bff_application_no',$dataApplication->bff_application_no,array('class'=>'form-control','required'=>'required'))}}
                                            </div>
                                            <span id="message" style="color: red;"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                                {{Form::label('bff_year',__('Covered Year'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                <div class="form-icon-user">
                                                    {{Form::number('bff_year',$year,array('class'=>'form-control','required'=>'required','readonly'=>'readonly'))}}
                                                </div>
                                                <span class="validate-err" id="err_ba_cover_year"></span>
                                            </div>
                                        </div>
                                    
                                    
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <div class="form-group">
                                            {{Form::label('ba_p_address',__('Address'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::textarea('ba_p_address','',array('class'=>'form-control','rows'=>1,'readonly'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_address"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            {{Form::label('bff_date',__('Application  Date'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                @php 
                                                 $year=date("Y");
                                                 $date = date('Y-m-d');
                                                @endphp
                                                @if($dataApplication->bff_date)
                                                 {{Form::date('bff_date',$dataApplication->bff_date,array('class'=>'form-control','required'=>'required'))}}
                                                 @else
                                                  {{Form::date('bff_date',$date,array('class'=>'form-control','required'=>'required'))}}
                                                  @endif
                                            </div>
                                            <span class="validate-err" id="err_ba_cover_year"></span>
                                        </div>
                                    </div>
                                    @if($dataApplication->bff_representative_id)
                                    <div class="col-lg-11 col-md-11 col-sm-11">
                                        <div class="form-group" id="representative">
                                            {{Form::label('bff_representative_id',__('Authorized Representative'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('bff_representative_id',$citizen,$dataApplication->bff_representative_id, array('class' => 'form-control','id'=>'bff_representative_id2','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_address"></span>
                                        </div>
                                    </div>
                                    
                                    @else
                                    <div class="col-lg-11 col-md-11 col-sm-11" style="    width: 96.66667%;">
                                        <div class="form-group" id="representative">
                                            {{Form::label('bff_representative_id',__('Authorized Representative'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('bff_representative_id',$citizen,$dataApplication->bff_representative_id, array('class' => 'form-control ','id'=>'bff_representative_id','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_address"></span>
                                        </div>
                                    </div>
                                    @endif
                             <div class="col-md-1" style="text-align: right;width: 2.33333%;">
                                  <div class="form-group">
                                        {{ Form::label('', __(''),['class'=>'form-label']) }}
                                        <div class="form-icon-user">
                                          <!--  <a href="#" class="btn btn-sm btn-primary" id="refreshCitizen" style="font-size: 16px;padding: 6px 8px;margin-top: 6px;">
                                            <span class="btn-inner--icon"><i class="ti-reload"></i> </span>
                                           </a> -->
                                           <a href="{{ url('/fireclients?isopenAddform=1') }}" title="Manage Taxpayer" data-title="Manage Taxpayers" class="btn btn-sm btn-primary" target="_blank" style="font-size: 16px;padding: 6px 8px;margin-top: 6px;">
                                                <i class="ti-plus"></i>
                                            </a>
                                         </div>
                                            <span class="validate-err" id="err_loc_local_code"></span>
                                 </div>
                            </div> 
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group" id="occupancy">
                                        {{Form::label('bot_occupancy_type',__('Occupancy Type'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                        {{ Form::select('bot_occupancy_type',$arrOcupancy,$dataApplication->bot_occupancy_type, array('class' => 'form-control select3','id'=>'bot_occupancy_type','required'=>'required')) }}

                                        </div>
                                        <span class="validate-err" id="err_barangay_id"></span>
                                    </div>
                            </div>
                              
                            <!-- <div class="col-lg-4 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                      
                                    </div>

                                     {{Form::label('subclass_code',__('Business Code'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                        {{ Form::select('subclass_code',$nofbusscode,$dataApplication->subclass_code, array('class' => 'form-control','id'=>'subclass_code','required'=>'required')) }}
                                        </div>
                                    <span class="validate-err" id="err_bussiness_application_code"></span>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                     {{Form::label('bussiness_application_desc',__('Business Desc'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                        
                                         {{Form::text('bussiness_application_desc','',array('class'=>'form-control','required'=>'required','id'=>'bussiness_application_desc'))}}
                                    </div>
                                    <span class="validate-err" id="err_bussiness_application_desc"></span>
                                </div>
                            </div> -->
                                    <!-- <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('bot_occupancy_type',__('Occupancy Type'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                        {{ Form::select('bot_occupancy_type',$arrOcupancy,$dataApplication->bot_occupancy_type, array('class' => 'form-control','id'=>'bot_occupancy_type','required'=>'required')) }}
                                        </div>
                                        <span class="validate-err" id="err_barangay_id"></span>
                                    </div>
                                </div>
                                 -->

                                   <!--    <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('brgy_code',__('Barangay Code'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                       
                                         <div class="form-icon-user">
                                            {{Form::text('brgy_code','',array('class'=>'form-control','required'=>'required','id'=>'brgy_code'))}}
                                        </div>
                                        <span class="validate-err" id="err_barangay_id"></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        {{Form::label('brgy_name',__('Barangay Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{Form::text('brgy_name','',array('class'=>'form-control','required'=>'required','id'=>'brgy_name'))}}
                                        </div>
                                        <span class="validate-err" id="err_brgy_name"></span>
                                    </div>
                                </div>
                                 -->
                              <!--   <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            {{Form::label('busn_bldg_area',__('Area Used In Bussiness(sq. m.)'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::number('busn_bldg_area',$dataApplication->busn_bldg_area,array('class'=>'form-control','required'=>'required'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_building_total_area_occupied"></span>
                                        </div>
                                    </div> -->
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            {{Form::label('busn_bldg_total_floor_area',__('Total Floor Area Used In (sq. m.)'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">

                                                {{Form::number('busn_bldg_total_floor_area',$dataApplication->busn_bldg_total_floor_area,array('class'=>'form-control','readonly'))}}

                                                {{Form::hidden('busn_bldg_area',$dataApplication->busn_bldg_area,array('class'=>'form-control'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_building_total_area_occupied"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            {{Form::label(' bff_no_of_storey',__('No. of Storey'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::number('bff_no_of_storey',$dataApplication->bff_no_of_storey,array('class'=>'form-control','id'=>'bff_no_of_storey','readonly'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_building_total_area_occupied"></span>
                                        </div>
                                    </div>
                                   <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            {{Form::label('bff_mobile_no',__('Contact Number'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('bff_mobile_no','',array('class'=>'form-control','readonly'))}}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            {{Form::label('bff_mobile_no',__('Phone Number'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('bff_mobile_no','',array('class'=>'form-control numeric'))}}
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            {{Form::label('bff_email_addrress',__('Email'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('bff_email_addrress','',array('class'=>'form-control ','readonly'))}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            {{Form::label('bff_remarks',__('Remarks | Comments'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('bff_remarks',$dataApplication->bff_remarks,array('class'=>'form-control numeric'))}}
                                            </div>
                                        </div>
                                    </div>
                                
                                    
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--------------- Owners Information Start Here---------------->

            <!--------------- ATTACHED DOCUMENTARY REQUIREMENTS Start Here---------------->
         
            
        </div>


        <div class="row pt10" >
            <!--------------- Owners Information Start Here---------------->
            <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingone">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">{{__("Line Of Business")}}</h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                            <div class="basicinfodiv">
                                <div class="row">
                                   <table>
                                       <tr>
                                           <th style="border: 1px solid #000;text-align: center;">No.</th>
                                           <th style="border: 1px solid #000;text-align: center;">Code</th>
                                           <th style="border: 1px solid #000;text-align: center;">Description</th>
                                       </tr>
                                       <tr>
                                        @php($count=0)
                                        @foreach($dataPSIC as $row)
                                        @php($count++)
                                           <td style="border: 1px solid #000;text-align: center;">{{$count}}</td>
                                           <td style="border: 1px solid #000;text-align: center;">{{$row->subclass_code}}</td>
                                           <td style="border: 1px solid #000;text-align: center;">{{$row->subclass_description}}</td>
                                       </tr>
                                       @endforeach
                                   </table>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{ Form::label('bff_application_type', __('Application Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                               {{ Form::select('bff_application_type',$arrApplicationType, $dataApplication->bff_application_type, array('class' => 'form-control spp_type','id'=>'bff_application_type','required'=>'required','onmousedown'=>'return false;','readonly')) }}
                                            </div>
                                            <span class="validate-err" id="err_bsf_tax_schedule"></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{ Form::label('purpase', __('Purpose'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                               {{ Form::select('purpase',$arrPurpose, $dataApplication->bff_application_type, array('class' => 'form-control spp_type','id'=>'purpase','required'=>'required','onmousedown'=>'return false;','readonly')) }}
                                            </div>
                                            <span class="validate-err" id="err_bsf_tax_schedule"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{ Form::label('bff_category', __('Category'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                               {{ Form::select('bff_category',$arrCategory,$dataApplication->bff_category, array('class' => 'form-control spp_type','id'=>'bff_category','required'=>'required','onmousedown'=>'return false;','readonly')) }}

                                            </div>
                                            <span class="validate-err" id="err_bsf_tax_schedule"></span>
                                        </div>
                                    </div>
                                    
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--------------- Owners Information Start Here---------------->

            <!--------------- ATTACHED DOCUMENTARY REQUIREMENTS Start Here---------------->
         
            
        </div>
    
        <!--------------- ATTACHED DOCUMENTARY REQUIREMENTS End Here------------------>
        <!--------------- FOR NEW BUSINESS REQUIREMENTS Start Here------------------>
            <div class="row pt10" >
                <div class="col-lg-12 col-md-12 col-sm-12" style="background: #20b7cc;text-align: center;
                 color: #fff;">
                {{Form::label('',__('FSIC FOR BUSINESS PERMIT'),['class'=>'form-label','style'=>'padding-top: 10px;'])}}
                </div>
                
                
            <div class="col-sm-6">
            <div class="row field-requirement-details-status">
               
                <div class="col-lg-12 col-md-12 col-sm-12"style="text-align: center;">
                    <div class="select-group">
               
                    {{ Form::checkbox('bff_req_new_business','1', ($dataApplication->bff_req_new_business)?true:false, array('id'=>'bff_req_new_business','class'=>'form-check-input new','style'=>'margin-top:9px')) }} {{Form::label('',__('FOR NEW BUSINESS REQUIREMENTS'),['class'=>'form-label'])}}
               
                    <script type="text/javascript">
                    var bffnewbusinessDivFile = document.getElementById("bffnewbusinessDivFile");
                    bffnewbusinessDivFile.style.display = "block";
                    </script>
                  </div>
                  <p id="message2" style="color:red;font-size:15px;"></p>
                </div>
            </div>
            <div id="bffnewbusinessDivFile" style="display: none">
            <div class="row"style="padding-top:15px;padding-left: 150px;">
            
            
            
             @foreach($dataCategoryRequirment as $key => $rownew)
             @if($rownew->req_id)
             <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="form-group">
                    {{ Form::checkbox('req_id['.$key.']',$rownew->id, ($rownew->req_description)?true:false, array('id' => 'req_id_' . $rownew->id,'class'=>'form-check-input new', 'onclick' => 'toggleUpload(this)')) }}
                    {{Form::label('req_id',__($rownew->req_description),['class'=>'form-label'])}}
                    <script type="text/javascript">
                    var bff_req6Div = document.getElementById("bff_req6Div");
                    bff_req6Div.style.display = "block";
                    function toggleUpload(checkbox) {
                        var fileInput = document.getElementById('bfr_document_file_' + id);
                        fileInput.readOnly = !document.getElementById('req_id_' + id).checked;
                        // var fileInput = checkbox.closest('.form-group').querySelector('input[type="file"]');
                        // fileInput.readOnly = !checkbox.checked;
                        if (fileInput.readOnly) {
                            fileInput.onclick = function(event) {
                                event.preventDefault();
                            };
                        } else {
                            fileInput.onclick = null;
                        }
                    }
                    </script>
                     {{ Form::input('file', 'bfr_document_file['.$key.']', '',array('id' => 'bfr_document_file_' . $rownew->id,'class'=>'form-control new','style'=>'margin:5px;width:58%;', 'readonly' => 'readonly','onclick'=>'return false;'))}}   
                    
                    
                    
                    <a href="../public/uploads/bfp_applicaton/{{$rownew->bfr_document_file}}" target="_blank">Document Download</a>
                   
                    <span class="validate-err" id="err_ba_building_permit_no"></span>
                    </div>

                    
                    
            </div>
            @else
            <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="form-group">
                    {{ Form::checkbox('req_id['.$key.']',$rownew->id, ($rownew->req_description)?false:true, array('id' => 'req_id_' . $rownew->id,'class'=>'form-check-input new', 'onclick' => 'toggleUpload(this)')) }}
                    {{Form::label('req_id',__($rownew->req_description),['class'=>'form-label'])}}
                    <script type="text/javascript">
                    var bff_req6Div = document.getElementById("bff_req6Div");
                    bff_req6Div.style.display = "block";
                    function toggleUpload(checkbox) {
                        var fileInput = document.getElementById('bfr_document_file_' + id);
                        fileInput.readOnly = !document.getElementById('req_id_' + id).checked;
                        // var fileInput = checkbox.closest('.form-group').querySelector('input[type="file"]');
                        // fileInput.readOnly = !checkbox.checked;
                        if (fileInput.readOnly) {
                            fileInput.onclick = function(event) {
                                event.preventDefault();
                            };
                        } else {
                            fileInput.onclick = null;
                        }
                    }
                    </script>
                     {{ Form::input('file', 'bfr_document_file['.$key.']', '',array('id' => 'bfr_document_file_' . $rownew->id,'class'=>'form-control new','style'=>'margin:5px;width:58%;', 'readonly' => 'readonly','onclick'=>'return false;'))}}   
                    <span class="validate-err" id="err_ba_building_permit_no"></span>
                    </div>
                    
            </div>
            @endif

             @endforeach
             
            
           
            </div>
        </div>
           
    </div>
   
        <!--------------- FOR NEW OF BUSINESS End Here------------------>
        <!--------------- FOR RENEWAL OF BUSINESS Start Here------------------>
        <div class="col-sm-6">
            <div class="row field-requirement-details-status">
                
                <div class="col-lg-12 col-md-12 col-sm-12"style="text-align: center;">
                    <div class="select-group">
                        {{ Form::checkbox('bff_req_renew_business','2', ($dataApplication->bff_req_renew_business)?true:false, array('id'=>'bff_req_renew_business','class'=>'form-check-input renew','style'=>'margin-top:9px'),['class'=>'form-label'])}}{{Form::label('',__('FOR RENEW BUSINESS REQUIREMENTS'),['class'=>'form-label'])}}
                    <script type="text/javascript">
                    var bffrenewbusinessDivFile = document.getElementById("bffrenewbusinessDivFile");
                    bffrenewbusinessDivFile.style.display = "block";
                    </script>
                  </div>
                  <p id="message3" style="color:red;font-size:15px;"></p>

                </div>
            </div>
           
            
         <br>
            
             <div class="row"style="padding-left: 200px;">
            @foreach($dataCategoryRequirmentRenew as $key => $rownew)
              @if($rownew->req_id)
              
             <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="form-group">
                    {{ Form::checkbox('req_id['.$key.']',$rownew->id, ($rownew->req_description)?true:false, array('id'=>'req_id[]','class'=>'form-check-input renew', 'onclick' => 'toggleUpload(this)')) }}
                    {{Form::label('req_id',__($rownew->req_description),['class'=>'form-label'])}}
                    <script type="text/javascript">
                    var bff_req6Div = document.getElementById("bff_req6Div");
                    bff_req6Div.style.display = "block";
                    function toggleUpload(checkbox) {
                        var fileInput = checkbox.closest('.form-group').querySelector('input[type="file"]');
                        fileInput.readOnly = !checkbox.checked;
                        if (fileInput.readOnly) {
                            fileInput.onclick = function(event) {
                                event.preventDefault();
                            };
                        } else {
                            fileInput.onclick = null;
                        }
                    }
                    </script>

                    
                     {{ Form::input('file', 'bfr_document_file['.$key.']', '',array('class'=>'form-control renew','style'=>'margin:5px;width:58%;', 'readonly' => 'readonly','onclick'=>'return false;'))}}   
                    
                     <a href="../public/uploads/bfp_applicaton/{{$rownew->bfr_document_file}}" target="_blank">Document Download</a>
                   
                    <span class="validate-err" id="err_ba_building_permit_no"></span>
                    </div>

            </div>
            @else
            <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="form-group">
                    {{ Form::checkbox('req_id['.$key.']',$rownew->id, ($rownew->req_description)?false:true, array('id'=>'req_id[]','class'=>'form-check-input renew', 'onclick' => 'toggleUpload(this)')) }}
                    {{Form::label('req_id',__($rownew->req_description),['class'=>'form-label'])}}
                    <script type="text/javascript">
                    var bff_req6Div = document.getElementById("bff_req6Div");
                    bff_req6Div.style.display = "block";
                    function toggleUpload(checkbox) {
                        var fileInput = checkbox.closest('.form-group').querySelector('input[type="file"]');
                        fileInput.readOnly = !checkbox.checked;
                        if (fileInput.readOnly) {
                            fileInput.onclick = function(event) {
                                event.preventDefault();
                            };
                        } else {
                            fileInput.onclick = null;
                        }
                    }
                    </script>
                    {{ Form::input('file', 'bfr_document_file['.$key.']', '',array('class'=>'form-control renew','style'=>'margin:5px;width:58%;', 'readonly' => 'readonly','onclick'=>'return false;'))}}   
                    <span class="validate-err" id="err_ba_building_permit_no"></span>
                    </div>
                     
            </div>
            @endif
            
             @endforeach
             
             </div>
           
            </div>
           
            
        </div>
        <!--------------- FOR RENEWAL OF BUSINESS End Here------------------>
 


         <div class="row pt10" >
         <div class="col-sm-12">
            <div class="row field-requirement-details-status">
                <div class="col-lg-12 col-md-12 col-sm-12" style="background: #20b7cc;text-align: center;
                 color: #fff;">
                {{Form::label('',__('Business Registration Details'),['class'=>'form-label'])}}
                </div>
            </div>
            <div class="row">
                
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <div class="form-group">
                        {{Form::label('clientName',__('Owner/Authorized Representative Over Printed Name'),['class'=>'form-label'])}}
                        <div class="form-icon-user">
                            {{ Form::text('clientName','', array('class' => 'form-control','id'=>'clientName','readonly')) }}
                        </div>
                        <span class="validate-err" id="err_ba_registration_ctc_issued_date"></span>
                    </div>
                </div>
                 
                 <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="form-group">
                        {{Form::label('bff_date',__('Date'),['class'=>'form-label'])}}
                        <div class="form-icon-user">

                            {{Form::date('bff_date2',$date,array('class'=>'form-control','readonly'))}}

                        </div>
                        <span class="validate-err" id="err_ba_registration_ctc_place_of_issuance"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-7" style="padding-right: 0px;width: 64.33333%;">
                    <div class="form-group" id="verified">
                        {{Form::label('bff_verified_by',__('VERIFIED BY BFP-CRO'),['class'=>'form-label'])}}
                        <div class="form-icon-user">
                            @if($dataApplication->bff_application_no)
                             @if($auth->id == $dataApplication->bff_verified_by || $auth->id == $dataApplication->bff_certified_by)
                                {{ Form::select('bff_verified_by', $employee, $dataApplication->bff_verified_by, array('class' => 'form-control spp_type','id'=>'bff_verified_by2','required'=>'required','onmousedown'=>'return false;','readonly')) }}
                            @elseif($dataApplication->bff_verified_status == "1")
                                   {{ Form::select('bff_verified_by', $employee, $dataApplication->bff_verified_by, array('class' => 'form-control spp_type','id'=>'bff_verified_by2','required'=>'required','onmousedown'=>'return false;','readonly')) }}
                           <!--  @elseif($auth->id == $dataApplication->bff_verified_by || $auth->id == $dataApplication->bff_certified_by || $dataApplication->bff_application_no == "")
                            {{ Form::select('bff_verified_by',$employee,$dataApplication->bff_verified_by, array('class' => 'form-control select3','id'=>'bff_verified_by')) }} -->
                            @else
                            {{ Form::select('bff_verified_by',$employee,$dataApplication->bff_verified_by, array('class' => 'form-control select3','id'=>'bff_verified_by')) }}
                            @endif
                            @else
                            {{ Form::select('bff_verified_by',$employee,$dataApplication->bff_verified_by, array('class' => 'form-control select3','id'=>'bff_verified_by')) }}
                            @endif
                        </div>
                        <span class="validate-err" id="err_ba_registration_ctc_amount_paid"></span>
                    </div>
                </div>
                <div class="col-md-1" style="width: 2.33333%;text-align: end;">
                    <div class="form-group">
                            {{ Form::label('', __(''),['class'=>'form-label']) }}
                            <div class="form-icon-user">
                                 <!-- <a href="#" class="btn btn-sm btn-primary" id="refreshEmployee" style="font-size: 16px;padding: 6px 8px;margin-top: 6px;">
                                            <span class="btn-inner--icon"><i class="ti-reload"></i> </span>
                                        </a> -->
                               <a href="{{ url('/human-resource/employees?isopenAddform=1') }}" title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary" target="_blank" style="font-size: 16px;padding: 6px 8px;margin-top: 6px;">
                                    <i class="ti-plus"></i>
                                </a>
                                   
                             </div>
                                <span class="validate-err" id="err_loc_local_code"></span>
                     </div>
                </div> 

                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="form-group">
                        {{Form::label('bff_veridifed_date',__('DateTime'),['class'=>'form-label'])}}
                        <div class="form-icon-user">
                            {{Form::input('datetime-local','bff_veridifed_date',$dataApplication->bff_veridifed_date,array('class'=>'form-control',($auth->id == $dataApplication->bff_verified_by || $auth->id == $dataApplication->bff_certified_by)? 'readonly':'readonly'))}}
                        </div>
                        <span class="validate-err" id="err_ba_locational_clearance_no"></span>
                    </div>
                </div>
               
               @if($dataApplication->bff_application_no)
                @if($dataApplication->bff_verified_status == "1")
                <div class="col-lg-12 col-md-12 col-sm-12"style="text-align:;">
                    <div class="form-group" style="color: #f10808;">
               
                    {{ Form::checkbox('bff_verified_status','1', ($dataApplication->bff_verified_status)?true:false, array('id'=>'bff_verified_status','class'=>'form-check-input ','style'=>'margin-top:9px','onclick'=>'return false;')) }} {{Form::label('',__('Approved: Confirmation'),['class'=>'form-label','style'=>'padding-top:6px;color:red;'])}}
                    </div>
                    
                </div>
                 @elseif($auth->id == $dataApplication->bff_verified_by)
                <div class="col-lg-12 col-md-12 col-sm-12"style="text-align:;">
                    <div class="form-group">
               
                    {{ Form::checkbox('bff_verified_status','1', ($dataApplication->bff_verified_status)?true:false, array('id'=>'bff_verified_status','class'=>'form-check-input ','style'=>'margin-top:9px')) }} {{Form::label('',__('Approved: Confirmation'),['class'=>'form-label','style'=>'padding-top:6px;color:red'])}}
               
                  </div>
                    
                </div>
                @endif
                @else
                @endif
               
           </div>
         </div>
        </div>

      <div class="row pt10" >
         <div class="col-sm-12">
            <div class="row field-requirement-details-status">
                <div class="col-lg-12 col-md-12 col-sm-12" style="background: #20b7cc;text-align: center;
                 color: #fff;">
                {{Form::label('',__('FSIC MONITORING (TO BE FILLED-UP BY BFP PERSONNEL ONLY)'),['class'=>'form-label'])}}
                </div>
            </div>
            <div class="row" style="padding-top: 10px;">
                
                

                  <div class="col-lg-3 col-md-3 col-sm-3">
                    <table style="border: 1px solid;text-align: center;width: 100%;">
                    
                        <tr style="border: 1px solid;">
                        <th colspan="2">CRO</th>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Date</td><td>{{Form::date('bff_cro_date',$dataApplication->bff_cro_date,array('class'=>'form-control'))}}</td>
                        </tr>
                        <tr style="border: 1px solid;">
                       <td>In</td><td>Out</td>
                       </tr>
                       <tr>
                       <td >{{Form::time('bff_cro_in',$dataApplication->bff_cro_in,array('class'=>'form-control'))}}</td><td>{{Form::time('bff_cro_out',$dataApplication->bff_cro_out,array('class'=>'form-control'))}}</td>
                       </tr>
                   
                    
                    </table>
                </div>
                      <div class="col-lg-3 col-md-3 col-sm-3">
                    <table style="border: 1px solid;text-align: center;width: 100%;">
                    
                        <tr style="border: 1px solid;">
                        <th colspan="2">FCA</th>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Date</td><td>{{Form::date('bff_fca_date',$dataApplication->bff_fca_date,array('class'=>'form-control'))}}</td>
                        </tr>
                        <tr style="border: 1px solid;">
                       <td>In</td><td>Out</td>
                       </tr>
                       <tr>
                       <td >{{Form::time('bff_fca_in',$dataApplication->bff_fca_in,array('class'=>'form-control'))}}</td><td>{{Form::time('bff_fca_out',$dataApplication->bff_fca_out,array('class'=>'form-control'))}}</td>
                       </tr>
                   
                    
                    </table>
                </div>

                      <div class="col-lg-3 col-md-3 col-sm-3">
                    <table style="border: 1px solid;text-align: center;width: 100%;">
                    
                        <tr style="border: 1px solid;">
                        <th colspan="2">FCCA</th>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Date</td><td>{{Form::date('bff_fcca_date',$dataApplication->bff_fcca_date,array('class'=>'form-control'))}}</td>
                        </tr>
                        <tr style="border: 1px solid;">
                       <td>In</td><td>Out</td>
                       </tr>
                       <tr>
                       <td >{{Form::time('bff_fcca_in',$dataApplication->bff_fcca_in,array('class'=>'form-control'))}}</td><td>{{Form::time('bff_fcca_out',$dataApplication->bff_fcca_out,array('class'=>'form-control'))}}</td>
                       </tr>
                   
                    
                    </table>
                </div>

                      <div class="col-lg-3 col-md-3 col-sm-3">
                    <table style="border: 1px solid;text-align: center;width: 100%;">
                    
                        <tr style="border: 1px solid;">
                        <th colspan="2">C,FSES</th>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Date</td><td>{{Form::date('bff_cfses1_date',$dataApplication->bff_cfses1_date,array('class'=>'form-control'))}}</td>
                        </tr>
                        <tr style="border: 1px solid;">
                       <td>In</td><td>Out</td>
                       </tr>
                       <tr>
                       <td >{{Form::time('bff_cfses1_in',$dataApplication->bff_cfses1_in,array('class'=>'form-control'))}}</td><td>{{Form::time('bff_cfses1_out',$dataApplication->bff_cfses1_out,array('class'=>'form-control'))}}</td>
                       </tr>
                   
                    
                    </table>
                </div>

                      <div class="col-lg-3 col-md-3 col-sm-3">
                    <table style="border: 1px solid;text-align: center;width: 100%;">
                    
                        <tr style="border: 1px solid;">
                        <th colspan="2">FSI</th>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Date</td><td>{{Form::date('bff_fsi_date',$dataApplication->bff_fsi_date,array('class'=>'form-control'))}}</td>
                        </tr>
                        <tr style="border: 1px solid;">
                       <td>In</td><td>Out</td>
                       </tr>
                       <tr>
                       <td >{{Form::time('bff_fsi_in',$dataApplication->bff_fsi_in,array('class'=>'form-control'))}}</td><td>{{Form::time('bff_fsi_out',$dataApplication->bff_fsi_out,array('class'=>'form-control'))}}</td>
                       </tr>
                   
                    
                    </table>
                </div>

                      <div class="col-lg-3 col-md-3 col-sm-3">
                    <table style="border: 1px solid;text-align: center;width: 100%;">
                    
                        <tr style="border: 1px solid;">
                        <th colspan="2">C,FSES</th>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Date</td><td>{{Form::date('bff_cfses2_date',$dataApplication->bff_cfses2_date,array('class'=>'form-control'))}}</td>
                        </tr>
                        <tr style="border: 1px solid;">
                       <td>In</td><td>Out</td>
                       </tr>
                       <tr>
                       <td >{{Form::time('bff_cfses2_in',$dataApplication->bff_cfses2_in,array('class'=>'form-control'))}}</td><td>{{Form::time('bff_cfses2_out',$dataApplication->bff_cfses2_out,array('class'=>'form-control'))}}</td>
                       </tr>
                   
                    
                    </table>
                </div>

                      <div class="col-lg-3 col-md-3 col-sm-3">
                    <table style="border: 1px solid;text-align: center;width: 100%;">
                    
                        <tr style="border: 1px solid;">
                        <th colspan="2">CFM/MFM</th>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Date</td><td>{{Form::date('bff_cfm_mfm_date',$dataApplication->bff_cfm_mfm_date,array('class'=>'form-control'))}}</td>
                        </tr>
                        <tr style="border: 1px solid;">
                       <td>In</td><td>Out</td>
                       </tr>
                       <tr>
                       <td >{{Form::time('bff_cfm_mfm_in',$dataApplication->bff_cfm_mfm_in,array('class'=>'form-control'))}}</td><td>{{Form::time('bff_cfm_mfm_out',$dataApplication->bff_cfm_mfm_out,array('class'=>'form-control'))}}</td>
                       </tr>
                   
                    
                    </table>
                </div>

                <div class="col-sm-12" style="padding-top: 10px;">
                    <div class="row field-requirement-details-status">
                        <div class="col-lg-12 col-md-12 col-sm-12" style="background: #20b7cc;text-align: center;
                 color: #fff;">
                        {{Form::label('',__('CLAIM STUB'),['class'=>'form-label'])}}
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-4 col-sm-4" style="    width: 39.33333%;">
                    <div class="form-group" id="certified">
                        {{Form::label('bff_certified_by',__('Certified By'),['class'=>'form-label'])}}
                        <div class="form-icon-user">
                            @if($dataApplication->bff_application_no)
                            @if($auth->id == $dataApplication->bff_verified_by || $auth->id == $dataApplication->bff_certified_by)
                                {{ Form::select('bff_certified_by', $employee, $dataApplication->bff_certified_by, array('class' => 'form-control ','id'=>'bff_certified_by2','required'=>'required','onmousedown'=>'return false;','readonly')) }}
                            @elseif($dataApplication->bff_certified_status == "1")
                                  {{ Form::select('bff_certified_by', $employee, $dataApplication->bff_certified_by, array('class' => 'form-control','id'=>'bff_certified_by2','required'=>'required','onmousedown'=>'return false;','readonly')) }}
                            @else 
                            {{ Form::select('bff_certified_by', $employee, $dataApplication->bff_certified_by, array('class' => 'form-control select3', 'id' => 'bff_certified_by')) }}     
                           
                            @endif
                            @else 
                            {{ Form::select('bff_certified_by', $employee, $dataApplication->bff_certified_by, array('class' => 'form-control select3', 'id' => 'bff_certified_by')) }}     
                           
                            @endif

                        </div>
                        <span class="validate-err" id="err_ba_registration_ctc_amount_paid"></span>
                    </div>
                </div>
                <div class="col-md-1" style="width: 2.33333%;text-align: center;padding: 0px;">
                    <div class="form-group">
                            {{ Form::label('', __(''),['class'=>'form-label']) }}
                            <div class="form-icon-user">
                                 <!-- <a href="#" class="btn btn-sm btn-primary" id="refreshEmployeeCert" style="font-size: 16px;padding: 6px 8px;margin-top: 6px;">
                                            <span class="btn-inner--icon"><i class="ti-reload"></i> </span>
                                        </a> -->
                               <a href="{{ url('/human-resource/employees?isopenAddform=1') }}" title="{{__('Add Citizen')}}" class="btn btn-sm btn-primary" target="_blank" style="font-size: 16px;padding: 6px 8px;margin-top: 6px;">
                                    <i class="ti-plus"></i>
                                </a>
                                   
                             </div>
                                <span class="validate-err" id="err_loc_local_code"></span>
                     </div>
                </div> 
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="form-group">
                        {{Form::label('bff_certified_position',__('Position'),['class'=>'form-label'])}}
                        <div class="form-icon-user">
                            <!-- @if($dataApplication->bff_certified_status == "1") -->
                            <!-- {{Form::text('bff_certified_position',$dataApplication->bff_certified_position,array('class'=>'form-control','id'=>'empPosition', 'readonly'))}} -->
                            <!-- @else -->
                            {{Form::text('bff_certified_position',$dataApplication->bff_certified_position,array('class'=>'form-control','id'=>'empPosition',($auth->id == $dataApplication->bff_verified_by || $auth->id == $dataApplication->bff_certified_by)? '':''))}}
                            <!-- @endif -->

                        </div>
                        <span class="validate-err" id="err_ba_locational_clearance_no"></span>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="form-group">
                        {{Form::label('bff_certified_date',__('Date'),['class'=>'form-label'])}}
                        <div class="form-icon-user">
                            @if($dataApplication->bff_certified_status == "1")
                            {{Form::date('bff_certified_date',$dataApplication->bff_certified_date,array('class'=>'form-control','readonly'))}}
                            @else
                            {{Form::date('bff_certified_date',$dataApplication->bff_certified_date,array('class'=>'form-control',($auth->id == $dataApplication->bff_verified_by || $auth->id == $dataApplication->bff_certified_by)? 'readonly':'readonly'))}}
                            @endif
                        </div>
                        <span class="validate-err" id="err_ba_locational_clearance_no"></span>
                    </div>
                </div>
                @if($dataApplication->bff_application_no)
                @if($dataApplication->bff_verified_status == "1")
                @if($dataApplication->bff_certified_status == "1")
                 <div class="col-lg-12 col-md-12 col-sm-12"style="text-align:;">
                    <div class="form-group" style="color: #f10808;">
               
                    {{ Form::checkbox('bff_certified_status','1', ($dataApplication->bff_certified_status)?true:false, array('id'=>'bff_certified_status','class'=>'form-check-input ','style'=>'margin-top:9px','onclick'=>'return false;')) }} {{Form::label('',__('Approved: Confirmation'),['class'=>'form-label','style'=>'padding-top:6px:color:red;'])}}
                  
                  </div>
                  
                </div>
                @elseif($auth->id == $dataApplication->bff_certified_by)
                <div class="col-lg-12 col-md-12 col-sm-12"style="text-align:;">
                    <div class="form-group">
               
                    {{ Form::checkbox('bff_certified_status','1', ($dataApplication->bff_certified_status)?true:false, array('id'=>'bff_certified_status','class'=>'form-check-input ','style'=>'margin-top:9px')) }} {{Form::label('',__('Approved: Confirmation'),['class'=>'form-label','style'=>'padding-top:6px;color:red;'])}}
               
                  </div>
                 
                </div>
                @endif
                @else
                @endif
                @endif
                 

               
           </div>
         </div>
        </div>
        @if($dataApplication->id>0)
        <div class="col-md-12">
                <!--- Start Status--->
                <div class="row" style="padding-top:10px;">
                    <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample3">  
                        <div  class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-heading3">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse3" aria-expanded="false" aria-controls="flush-collapse3">
                                        <h6 class="sub-title accordiantitle">
                                            <i class="ti-menu-alt text-white fs-12"></i>
                                            <span class="accordiantitle-icon">{{__("Uploads")}}
                                            </span>
                                        </h6>
                                    </button>
                                </h6>
                                
                                
                                <div id="flush-collapse3" class="accordion-collapse collapse" aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample3">
                                
                                    <div class="basicinfodiv">
                                        <div class="row">
                                           
                                            <div class="col-lg-11 col-md-11 col-sm-11">
                                                <div class="form-group">
                                                    {{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::input('file','document_name','',array('class'=>'form-control '))}}  
                                                    </div>
                                                    <span class="validate-err" id="err_document"></span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-1 col-md-1 col-sm-1" style="padding-top:26px;">
                                                <button type="button" style="float: right;" class="btn btn-primary " id="uploadAttachment">Upload File</button>
                                            </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12"><br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                           <tr>
                                                               <th>File Name</th>
                                                                <th>Attachment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <thead id="DocumentDtls">
                                                            <?php echo $arrdocDtls?>
                                                            @if(empty($arrdocDtls))
                                                            <tr>
                                                                <td colspan="3"><i>No results found.</i></td>
                                                            </tr>
                                                            @endif 
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
                    @endif

        <!--------------- Business Details Listing End Here------------------>
    <div class="row" class="col-md-12" style="text-align: left;">
        <div class="col-md-3" style="padding-top:17px;padding-left: 0px;padding-right: 0px;"> 
            @if($dataApplication->bff_verified_status == "1" && $dataApplication->bff_certified_status == "1")
            <a href="{{url('/BfpChequePrint?id='.$dataApplication->id)}}" title="Print Application Form" style="background: #20b7cc;padding: 10px;color: #fff;" data-title="Print Application Form" target="_blank" class="mx-3 btn btn-sm digital-sign-btn" id="{{$dataApplication->id}}" >
                            <i class="ti-printer text-white"></i> Print
                        </a>
            @endif
          
        </div>
        <div class="col-md-9" style="padding-left: 0px;padding-right: 0px;">    
            <div class="modal-footer" style="padding-right: 8px;">
                <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit"  id="submit"  name="submit" value="{{ ($dataApplication->id)>0?__('Save Changes'):__('Save Changes')}}" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                </div>
            
            </div>
        </div>   
    </div>
            </div>


        </div>
    </div>
</div>


 {{Form::close()}}

<script src="{{ asset('js/jquery.inputpicker.js') }}"></script>
<script src="{{ asset('js/Addbfpapplicationform.js') }}?rand={{ rand(000,999) }}"></script>
<!-- <script src="{{ asset('js/ajax_validation.js') }}"></script> -->

<script>
$(document).ready(function() {

    var shouldSubmitForm = false;
    $('#submit').click(function (e) {
        if (!shouldSubmitForm) {
            var form = $('#bfpapplicationformForm');
            Swal.fire({
                title: "Are you sure?",
                text: "It will not change details after the confirmation?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    shouldSubmitForm = true;
                    form.submit();
                    $('#submit').click();
                } else {
                    console.log("Form submission canceled");
                }
            });

            e.preventDefault();
        }
    });
    var inputField = $('#bff_application_no');
    var messageSpan = $('#message');

    inputField.on('input', function() {
        var value = inputField.val();

        if (value.length === 0) {
            messageSpan.text('');
        } else if (/^\d{1,8}$/.test(value)) {
            if (value.length === 8) {
                messageSpan.text('');
            } else {
                messageSpan.text('Valid: Max 8 digits');
            }
        } else {
            messageSpan.text('Invalid: Must be up to 8 digits');
        }
    });

    // Trigger the input event on page load
    inputField.trigger('input');

});

</script>



