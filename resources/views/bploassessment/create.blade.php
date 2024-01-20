<style>
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

</style>
<link href="{{ asset('css/jquery.inputpicker.css') }}" rel="stylesheet" type="text/css">

{{Form::open(array('url'=>'bploassessment','method'=>'post'))}}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('application_id',$data->id, array('id' => 'application_id')) }}
    {{ Form::hidden('profile_id',$data->id, array('id' => 'profile_id')) }}
    {{ Form::hidden('accountnumber',$data->id, array('id' => 'accountnumber')) }}
    {{ Form::hidden('app_type','', array('id' => 'app_type')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-3 col-md-2 col-sm-2">
                <div class="form-group">
                    {{Form::label('ba_business_account_no',__('Business Account No.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('ba_business_account_no',$accountnos,$data->application_id, array('class' => 'form-control select3','id'=>'ba_business_account_no')) }}
                    </div>
                </div>
            </div>

             <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    {{Form::label('applicationdate',__('Application  Date'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                         {{Form::date('applicationdate','',array('class'=>'form-control','required'=>'required'))}}
                    </div>
                    <span class="validate-err" id="err_ba_cover_year"></span>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    {{Form::label('ba_cover_year',__('Covered Year'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{Form::text('ba_cover_year','',array('class'=>'form-control','required'=>'required','readonly'=>'readonly'))}}
                    </div>
                    <span class="validate-err" id="err_ba_cover_year"></span>
                </div>
            </div>
        </div>
        <div class="row pt10" >
            <!--------------- Owners Information Start Here---------------->
            <div class="col-lg-6 col-md-6 col-sm-6"  id="accordionFlushExample">  
                <div  class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingone">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                <h6 class="sub-title accordiantitle">{{__("Taxplayers Information")}}</h6>
                            </button>
                        </h6>
                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                            <div class="basicinfodiv">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('ba_p_last_name',__('Last Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::text('ba_p_last_name','',array('class'=>'form-control','required'=>'required'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_last_name"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('ba_p_first_name',__(' First Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::text('ba_p_first_name','',array('class'=>'form-control','required'=>'required'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_first_name"></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('ba_p_middle_name',__('Middle Name'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('ba_p_middle_name','',array('class'=>'form-control'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_middle_name"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <div class="form-group">
                                            {{Form::label('ba_p_address',__('Address'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::textarea('ba_p_address','',array('class'=>'form-control','rows'=>1))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_p_address"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('ba_telephone_no',__('Tel. No.'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('ba_telephone_no','',array('class'=>'form-control numeric'))}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="d-flex radio-check" style="padding-top: 10px;"><br>
                                            <div class="form-check form-check-inline form-group">
                                                {{ Form::radio('ba_building_is_owned', '1', ($data->ba_building_is_owned)?true:false, array('id'=>'Owned','class'=>'form-check-input code')) }}
                                                {{ Form::label('Owned', __('Owned'),['class'=>'form-label']) }}
                                            </div>
                                            <div class="form-check form-check-inline form-group">
                                                {{ Form::radio('ba_building_is_owned', '0', (!$data->ba_building_is_owned)?true:false, array('id'=>'Rented','class'=>'form-check-input code')) }}
                                                {{ Form::label('Rented', __('Rented/Leased'),['class'=>'form-label']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('ba_building_total_area_occupied',__('Area Used in Business(sq. m.)'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::number('ba_building_total_area_occupied','',array('class'=>'form-control','required'=>'required'))}}
                                            </div>
                                            <span class="validate-err" id="err_ba_building_total_area_occupied"></span>
                                        </div>
                                    </div>
                                 <!--    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="d-flex radio-check" style="padding-top: 30px;"><br>
                                            <div class="form-check form-check-inline form-group">
                                                {{ Form::checkbox('storecombustible', '1','', array('id'=>'storecombustible','class'=>'form-check-input code')) }}
                                                {{ Form::label('storecombustible', __('Store/Cell Combustible Substance'),['class'=>'form-label']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="d-flex radio-check" style="padding-top: 30px;"><br>
                                            <div class="form-check form-check-inline form-group">
                                                {{ Form::checkbox('withsignbillboard', '1','', array('id'=>'withsignbillboard','class'=>'form-check-input code')) }}
                                                {{ Form::label('withsignbillboard', __('With Signboard/Billboard'),['class'=>'form-label']) }}
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="d-flex radio-check" style="padding-top: 30px;"><br>
                                            <div class="form-check form-check-inline form-group">
                                                {{ Form::checkbox('bussinessplateissue', '1','', array('id'=>'bussinessplateissue','class'=>'form-check-input code')) }}
                                                {{ Form::label('bussinessplateissue', __('Business Plate Issued'),['class'=>'form-label']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            {{Form::label('big',__('Big'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('big',$data->big,array('class'=>'form-control'))}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            {{Form::label('small',__('Small'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{Form::text('small',$data->small,array('class'=>'form-control'))}}
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

            <!--------------- Business Information Start Here---------------->
            <div class="col-lg-6 col-md-6 col-sm-6" id="accordionFlushExample2">
                <div class="accordion accordion-flush">
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingtwo">
                            <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                            <h6 class="sub-title accordiantitle">{{__('Business  Information')}}</h6>
                            </button>
                        </h6>
                        
                        <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                            <div class="row"  id="otheinfodiv">
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        {{Form::label('ba_business_name',__('Trade Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{Form::text('ba_business_name','',array('class'=>'form-control','required'=>'required'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_business_name"></span>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        {{Form::label('ba_address_house_lot_no',__('Bldg/Hse #'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{Form::text('ba_address_house_lot_no','',array('class'=>'form-control','required'=>'required'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_address_house_lot_no"></span>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        {{Form::label('ba_address_street_name',__('Street'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_address_street_name','',array('class'=>'form-control'))}}
                                        </div>
                                        <span class="validate-err" id="err_ba_address_street_name"></span>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        {{Form::label('barangay_id',__('Barangay Code'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                        {{ Form::select('barangay_id',$arrBarangay,'', array('class' => 'form-control','id'=>'barangay_id','required'=>'required')) }}
                                        </div>
                                        <span class="validate-err" id="err_barangay_id"></span>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        {{Form::label('brgy_name',__('Barangay Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        <div class="form-icon-user">
                                            {{Form::text('brgy_name','',array('class'=>'form-control','required'=>'required','id'=>'brgy_name'))}}
                                        </div>
                                        <span class="validate-err" id="err_brgy_name"></span>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        {{Form::label('ba_telephone_no',__('Tel. No.'),['class'=>'form-label'])}}
                                        <div class="form-icon-user">
                                            {{Form::text('ba_telephone_no','',array('class'=>'form-control numeric','id'=>'ba_telephone_no2'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                        <div class="d-flex radio-check" style="padding-top: 10px;"><br>
                                            <div class="form-check form-check-inline form-group">
                                                {{ Form::radio('kindofowner', '1','', array('id'=>'Single','class'=>'form-check-input code')) }}
                                                {{ Form::label('Single', __('Single Proprietorship'),['class'=>'form-label']) }}
                                            </div>
                                            <div class="form-check form-check-inline form-group">
                                                {{ Form::radio('kindofowner', '2', '', array('id'=>'Rented','class'=>'form-check-input code')) }}
                                                {{ Form::label('Partnership', __('Partnership'),['class'=>'form-label']) }}
                                            </div>
                                            <div class="form-check form-check-inline form-group">
                                                {{ Form::radio('kindofowner', '3', '', array('id'=>'Rented','class'=>'form-check-input code')) }}
                                                {{ Form::label('Corporation', __('Corporation'),['class'=>'form-label']) }}
                                            </div>
                                        </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('p_tin_no',__('TIN'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::text('p_tin_no','',array('class'=>'form-control','required'=>'required'))}}
                                            </div>
                                            <span class="validate-err" id="err_p_tin_no"></span>
                                        </div>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                        <div class="form-group">
                                            {{Form::label('ba_taxable_owned_truck_wheeler_10above',__('Truck/Van Owned- 10 Wheeler And Above'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::text('ba_taxable_owned_truck_wheeler_10above',$data->ba_taxable_owned_truck_wheeler_10above,array('class'=>'form-control','required'=>'required'))}}
                                            </div>
                                        </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('ba_taxable_owned_truck_wheeler_6above',__('6 Wheelers'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::text('ba_taxable_owned_truck_wheeler_6above',$data->ba_taxable_owned_truck_wheeler_6above,array('class'=>'form-control','required'=>'required'))}}
                                            </div>
                                        </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            {{Form::label('ba_taxable_owned_truck_wheeler_4above',__('4 Wheelers'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::text('ba_taxable_owned_truck_wheeler_4above',$data->ba_taxable_owned_truck_wheeler_4above,array('class'=>'form-control','required'=>'required'))}}
                                            </div>
                                        </div>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                        <div class="form-group">
                                            {{Form::label('no_of_personnel',__('No Of Personnel (Occupational Tax)'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::text('no_of_personnel',$data->no_of_personnel,array('class'=>'form-control','required'=>'required'))}}
                                            </div>
                                        </div>
                                </div>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--------------- Business Information End Here------------------>
        </div>
        <div class="row pt10" >
        <div class="col-sm-12">
            <div class="row field-requirement-details-status">
                <div class="col-lg-12 col-md-12 col-sm-12">
                {{Form::label('',__('Building/Lessor Detail'),['class'=>'form-label'])}}
                </div>
            </div>
            <div class="row">
             <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    {{Form::label('lessor',__('Lessor'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{Form::text('lessor',$data->lessor,array('class'=>'form-control','required'=>'required'))}}
                    </div>
                    <span class="validate-err" id="err_ba_building_permit_no"></span>
                </div>
            </div>
             <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    {{Form::label('lessoraddress',__('Address'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{Form::text('lessoraddress',$data->lessoraddress,array('class'=>'form-control','required'=>'required'))}}
                    </div>
                    <span class="validate-err" id="err_ba_building_permit_no"></span>
                </div>
            </div>
             <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    {{Form::label('administrator',__('Administrator'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{Form::text('administrator',$data->administrator,array('class'=>'form-control','required'=>'required'))}}
                    </div>
                    <span class="validate-err" id="err_ba_building_permit_no"></span>
                </div>
            </div>
             <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group currency">
                    {{Form::label('rentalstart',__('Rental Start'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{Form::text('rentalstart',$data->rentalstart,array('class'=>'form-control numeric','required'=>'required'))}}
                        <div class="currency-sign"><span>Php</span></div>
                    </div>
                    <span class="validate-err" id="err_ba_building_permit_no"></span>
                </div>
            </div>
             <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group currency">
                    {{Form::label('presentrate',__('Present Rate'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{Form::text('presentrate',$data->presentrate,array('class'=>'form-control numeric','required'=>'required'))}}
                         <div class="currency-sign"><span>Php</span></div>
                    </div>
                    <span class="validate-err" id="err_ba_building_permit_no"></span>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    {{Form::label('ba_building_property_index_number',__('P. I. N.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{Form::text('ba_building_property_index_number',$data->ba_building_property_index_number,array('class'=>'form-control','required'=>'required'))}}
                    </div>
                    <span class="validate-err" id="err_ba_building_permit_no"></span>
                </div>
            </div>
           </div>
        </div>
        </div>

         <div class="row pt10" >
         <div class="col-sm-12">
            <div class="row field-requirement-details-status">
                <div class="col-lg-12 col-md-12 col-sm-12">
                {{Form::label('',__('Business Registration Details'),['class'=>'form-label'])}}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="form-group">
                        {{Form::label('ba_registration_ctc_no',__('Community Tax Cert. No.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{Form::text('ba_registration_ctc_no','',array('class'=>'form-control','required'=>'required'))}}
                        </div>
                        <span class="validate-err" id="err_ba_registration_ctc_no"></span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="form-group">
                        {{Form::label('ba_registration_ctc_issued_date',__('Issue Date'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{Form::date('ba_registration_ctc_issued_date','',array('class'=>'form-control','required'=>'required'))}}
                        </div>
                        <span class="validate-err" id="err_ba_registration_ctc_issued_date"></span>
                    </div>
                </div>
                 <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="form-group">
                        {{Form::label('ba_registration_ctc_place_of_issuance',__('Place of Issue'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{Form::text('ba_registration_ctc_place_of_issuance','',array('class'=>'form-control','required'=>'required'))}}
                        </div>
                        <span class="validate-err" id="err_ba_registration_ctc_place_of_issuance"></span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="form-group currency">
                        {{Form::label('ba_registration_ctc_amount_paid',__('Amount Paid'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{Form::number('ba_registration_ctc_amount_paid','',array('class'=>'form-control','required'=>'required','placeholder'=>'0.00','step'=>'0.001'))}}
                            <div class="currency-sign"><span>Php</span></div>
                        </div>
                        <span class="validate-err" id="err_ba_registration_ctc_amount_paid"></span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="form-group">
                        {{Form::label('ba_locational_clearance_no',__('Locational Clearance'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{Form::text('ba_locational_clearance_no','',array('class'=>'form-control','required'=>'required'))}}
                        </div>
                        <span class="validate-err" id="err_ba_locational_clearance_no"></span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="form-group">
                        {{Form::label('ba_locational_clearance_date_issued',__('Locational Date Issued'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{Form::date('ba_locational_clearance_date_issued','',array('class'=>'form-control','required'=>'required'))}}
                        </div>
                        <span class="validate-err" id="err_ba_locational_clearance_date_issued"></span>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="form-group">
                        {{Form::label('ba_bureau_domestic_trade_no',__('Bureau Of Domestic Trade'),['class'=>'form-label'])}}
                        <div class="form-icon-user">
                            {{Form::text('ba_bureau_domestic_trade_no','',array('class'=>'form-control'))}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="form-group">
                        {{Form::label('ba_bureau_domestic_trade_date_issued',__('Date Issued'),['class'=>'form-label'])}}
                        <div class="form-icon-user">
                            {{Form::date('ba_bureau_domestic_trade_date_issued','',array('class'=>'form-control'))}}
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="form-group">
                        {{Form::label('ba_sec_registration_no',__('SEC Registration No'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{Form::text('ba_sec_registration_no','',array('class'=>'form-control','required'=>'required'))}}
                        </div>
                        <span class="validate-err" id="err_ba_sec_registration_no"></span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="form-group">
                        {{Form::label('ba_sec_registration_date_issued',__('Date Issued'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{Form::date('ba_sec_registration_date_issued','',array('class'=>'form-control','required'=>'required'))}}
                        </div>
                        <span class="validate-err" id="err_ba_sec_registration_date_issued"></span>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="form-group">
                        {{Form::label('ba_dti_no',__('DTI No'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{Form::text('ba_dti_no','',array('class'=>'form-control','required'=>'required'))}}
                        </div>
                        <span class="validate-err" id="err_ba_dti_no"></span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="form-group">
                        {{Form::label('ba_dti_date_issued',__('Date Issued'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{Form::date('ba_dti_date_issued','',array('class'=>'form-control','required'=>'required'))}}
                        </div>
                        <span class="validate-err" id="err_ba_dti_date_issued"></span>
                    </div>
                </div>
           </div>
         </div>
        </div>

        <div class="row" style="padding-top: 10px;">
            <div class="col-sm-12">
            <a data-toggle="modal" href="javascript:void(0)" class="btn btn-primary btnPopupOpen" type="add">Add Business Details</a>
            </div>
        </div>

        <!--------------- Business Details Popup Information Start Here---------------->
        <div id="popupDetails">
            @php $i=0; @endphp
            @foreach($arrbDetails as $key=>$val)
                <div class="modal bussiness-model" id="myModal{{$i}}" data-backdrop="static">
                    <div class="modal-dialog modal-lg modalDiv" style="margin-top: -102px !important;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Business Details</h4>
                                <a class="close closeModel" mid="{{$i}}" type="edit" data-dismiss="modal" aria-hidden="true">X</a>
                            </div>
                            <div class="container"></div>
                            <div class="modal-body">
                                <div class="row">
                                    <h6 class="sub-title">{{__('Nature of Business Specification')}}</h6>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::label('bussiness_application_code',__('Business Code'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                    {{ Form::hidden('cto_assessmentid[]',$val['id'], array('class' => 'cto_assessmentid')) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{ Form::select('bussiness_application_code[]',$nofbusscode,$val['bussiness_application_code'], array('class' => 'form-control select3 bussiness_application_code','id'=>'bussiness_application_code')) }}
                                                    {{ Form::hidden('bussiness_application_id[]',$val['bussiness_application_id'], array('class' => 'bussiness_application_id')) }}
                                                </div>
                                                <span class="validate-err" id="err_bussiness_application_code"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-8 col-sm-8">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::text('bussiness_application_desc[]',$val['bussiness_application_desc'],array('class'=>'form-control select3bussiness_application_desc','placeholder'=>''))}}
                                                </div>
                                                <span class="validate-err" id="err_bussiness_application_desc"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                        <h6 class="sub-title">{{__('Tax Base')}}</h6>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-2 col-sm-2">
                                                <div class="form-group" style="padding-top: 30px;">
                                                    <div class="form-icon-user">
                                                        {{Form::label('capitalization',__('Capitalization:'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-2 col-sm-2">
                                                <div class="form-group" style="padding-top: 20px;">
                                                    <div class="form-icon-user">
                                                        {{Form::text('capitalization[]',$val['capitalization'],array('class'=>'form-control capitalization','placeholder'=>''))}}
                                                    </div>
                                                    <span class="validate-err" id="err_capitalization"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-2 col-sm-2">
                                                <div class="form-group" style="padding-top: 30px;">
                                                    <div class="form-icon-user">
                                                        {{Form::label('gross_sale',__('Gross Sale:'),['class'=>'form-label'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-2 col-sm-2">
                                                <div class="form-group" style="padding-top: 20px;">
                                                    <div class="form-icon-user">
                                                        {{Form::text('gross_sale[]',$val['gross_sale'],array('class'=>'form-control gross_sale','placeholder'=>''))}}
                                                    </div>
                                                    <span class="validate-err" id="err_gross_sale"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div class="row">
                                    <h6 class="sub-title">{{__('Line Of Business As Per Revenue Code')}}</h6>
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{Form::label('tax_type_code',__('Tax Type'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-2 col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{ Form::select('tax_type_code[]',$taxtypes, $val['tax_type_code'], array('class' => 'form-control select3 tax_type_code','id'=>'tax_type_code')) }}
                                                        {{ Form::hidden('tax_type_id[]',$val['tax_type_id'], array('class' => 'tax_type_id')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_tax_type_code"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-8 col-sm-8">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{Form::text('tax_type_desc[]',$val['tax_type_desc'],array('class'=>'form-control tax_type_desc','placeholder'=>''))}}
                                                    </div>
                                                    <span class="validate-err" id="err_tax_type_desc"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{Form::label('classification_code',__('Classification'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-2 col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{ Form::select('classification_code[]',$classification,$val['classification_code'], array('class' => 'form-control select3 selectdyna2 classification_code','id'=>'classification_code')) }}
                                                        {{ Form::hidden('classification_id[]',$val['classification_id'], array('class' => 'classification_id')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_classification_code"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-8 col-sm-8">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{Form::text('classification_desc[]',$val['classification_desc'],array('class'=>'form-control classification_desc','placeholder'=>''))}}
                                                    </div>
                                                    <span class="validate-err" id="err_classification_desc"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{Form::label('activity_code',__('Activity'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-2 col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{ Form::select('activity_code[]',$activity,$val['activity_code'], array('class' => 'form-control  select3 activity_code','id'=>'activity_code')) }}
                                                        {{ Form::hidden('activity_id[]',$val['activity_id'], array('class' => 'activity_id')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_activity_code"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-8 col-sm-8">
                                                <div class="form-group">
                                                    <div class="form-icon-user">
                                                        {{Form::text('activity_desc[]',$val['activity_desc'],array('class'=>'form-control activity_desc','placeholder'=>''))}}
                                                    </div>
                                                    <span class="validate-err" id="err_activity_desc"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2"></div>
                                            <div class="col-lg-4 col-md-2 col-sm-2">
                                                <div class="d-flex radio-check" style="padding-top: 30px;">
                                                    <div class="form-check form-check-inline form-group">
                                                        {{ Form::checkbox('essential_commodities[]', '1',($val['essential_commodities'])? true:false, array('class'=>'form-check-input code essential_commodities')) }}
                                                        {{ Form::label('essential_commodities', __('Essential Commodities'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                    </div>
                                                    <span class="validate-err" id="err_essential_commodities"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-2 col-sm-2">
                                                <div class="form-group" style="padding-top: 30px;">
                                                    <div class="form-icon-user">
                                                        {{Form::label('no_of_perdays',__('No of Days(if per Day):'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-2 col-sm-2">
                                                <div class="form-group" style="padding-top: 20px;">
                                                    <div class="form-icon-user">
                                                        {{Form::text('no_of_perdays[]',$val['no_of_perdays'],array('class'=>'form-control no_of_perdays','placeholder'=>''))}}
                                                    </div>
                                                    <span class="validate-err" id="err_no_of_perdays"></span>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="row">
                                    <h6 class="sub-title">{{__('Regulatory Fee Business Categories')}}</h6>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::label('mayrol_permit_description',__("Mayor's Permit"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::text('mayrol_permit_description[]',$val['mayrol_permit_description'],array('class'=>'form-control mayrol_permit_description','placeholder'=>''))}}
                                                    {{ Form::hidden('permit_amount[]',$val['permit_amount'], array('class' => 'permit_amount')) }}
                                                </div>
                                                <span class="validate-err" id="err_mayrol_permit_description"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::text('mayrol_permit_code[]',$val['mayrol_permit_code'],array('class'=>'form-control mayrol_permit_code','placeholder'=>''))}}
                                                </div>
                                                <span class="validate-err" id="err_mayrol_permit_code"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"><br>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::label('garbage_description',__('Garbage'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::text('garbage_description[]',$val['garbage_description'],array('class'=>'form-control garbage_description','placeholder'=>''))}}
                                                    {{ Form::hidden('garbage_amount[]',$val['garbage_amount'], array('class' => 'garbage_amount')) }}
                                                </div>
                                                <span class="validate-err" id="err_garbage_description"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::text('garbage_code[]',$val['garbage_code'],array('class'=>'form-control garbage_code','placeholder'=>''))}}
                                                </div>
                                                <span class="validate-err" id="err_garbage_code"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"><br>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::label('sanitory',__('Sanitary'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::text('sanitary_description[]',$val['sanitary_description'],array('class'=>'form-control sanitary_description','placeholder'=>''))}}
                                                    {{ Form::hidden('sanitary_amount[]',$val['sanitary_amount'], array('class' => 'sanitary_amount')) }}
                                                </div>
                                                <span class="validate-err" id="err_sanitary_description"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <div class="form-icon-user">
                                                    {{Form::text('sanitary_code[]',$val['sanitary_code'],array('class'=>'form-control sanitary_code','placeholder'=>''))}}
                                                </div>
                                                <span class="validate-err" id="err_sanitary_code"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                            <div class="modal-footer">
                                <a href="#" data-dismiss="modal" class="btn closeModel" mid="{{$i}}"  type="edit">Close</a>
                                <a href="#" class="btn btn-primary savebusinessDetails">Save changes</a>
                            </div>
                        </div>
                    </div>
                </div>
                @php $i++; @endphp
            @endforeach
        </div>
        <!--------------- Business Details Popup Information Start Here---------------->

              <!--------------- Business Details Listing Start Here------------------>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{__('Application Quarter')}}</th>
                                        <th>{{__("Nature Of Business / Fixex Items")}}</th>
                                        <th>{{__('Line of Business/Activities')}}</th>
                                        <th>{{__('Capitalization')}}</th>
                                        <th>{{__('Gross Sale/Reciepts')}}</th>
                                        <th>{{__('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=0; @endphp
                                    @foreach($arrbDetails as $key=>$val)
                                        <tr class="font-style" id="trId{{$i}}">
                                            <td class="app_qurtr">{{ $val['app_qurtr'] }}</td>
                                            <td class="nature_of_bussiness"><div class='showLess'>@php echo substr($val['nature_of_bussiness'],0,100) @endphp </div></td>
                                            <td class="activity">{{ $val['activity'] }}</td>
                                            <td class="capitalization">{{ $val['capitalization'] }}</td>
                                            <td class="gross_sale">{{ $val['gross_sale'] }}</td>
                                            <td class="action"><a href="javascript:void(0)" mid="{{$i}}" type="edit" class="btnPopupOpen">Edit</a></td>
                                            @php $i++; @endphp
                                        </tr>
                                    @endforeach
                                    <tr class="font-style last-option">
                                        <td></td><td></td><td></td><td></td><td></td><td></td>
                                    </tr>
                                    <tr class="font-style">
                                        <td></td><td></td><td></td><td></td><td></td><td></td>
                                    </tr>
                                    <tr class="font-style">
                                        <td></td><td></td><td></td><td></td><td></td><td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!--------------- Business Details Listing End Here------------------>

            <div class="row pt10" >
        <div class="col-sm-12">
            <div class="row field-requirement-details-status">
                <div class="col-lg-12 col-md-12 col-sm-12">
                {{Form::label('',__('Engineering Inspection Fee Details'),['class'=>'form-label'])}}
                </div>
            </div>
            <div class="row">
             <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    <div class="form-icon-user">
                        {{Form::label('engneeringfee',__('Engineering Fee'),['class'=>'form-label'])}}
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8">
                <div class="form-group">
                    <div class="form-icon-user">
                        {{Form::text('engneeringfee_description',$data->engneeringfee_description,array('class'=>'form-control engneeringfee_description','placeholder'=>'','id'=>'engneeringfee_description'))}}
                        {{ Form::hidden('engneering_amount',$data->engneering_amount, array('class' => 'engneering_amount','id'=>'engneering_amount')) }}
                        {{ Form::hidden('engneering_feeid',$data->engneering_feeid, array('class' => 'engneering_feeid','id'=>'engneering_feeid')) }}
                    </div>
                    <span class="validate-err" id="err_sanitary_description"></span>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <div class="form-group">
                    <div class="form-icon-user">
                        {{Form::text('engneering_code',$data->engneering_code,array('class'=>'form-control engneering_code','placeholder'=>'','id'=>'engneering_code'))}}
                    </div>
                    <span class="validate-err" id="err_sanitary_code"></span>
                </div>
            </div>
           </div>
        </div>
        </div>

    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Save')}}" class="btn  btn-primary"> -->
    </div>

{{Form::close()}}

<div id="hidenPopupHtml" class="hide">
    <div class="modal bussiness-model" id="" data-backdrop="static">
        <div class="modal-dialog modal-lg modalDiv modal-dialog-scrollable" style="margin-top: -102px !important;">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Business Details</h4>
                <a class="close closeModel" data-dismiss="modal" aria-hidden="true" type="add" mid="">X</a>
                </div><div class="container"></div>
                <div class="modal-body">
                    <div class="row">
                        <h6 class="sub-title">{{__('Nature of Business Specification')}}</h6>
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('bussiness_application_code',__('Business Code'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{ Form::select('bussiness_application_code[]',$nofbusscode, '', array('class' => 'form-control bussiness_application_code','id'=>'bussiness_application_code')) }}
                                        {{ Form::hidden('bussiness_application_id[]','', array('class' => 'bussiness_application_id')) }}
                                    </div>
                                    <span class="validate-err" id="err_bussiness_application_code"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('bussiness_application_desc[]','',array('class'=>'form-control bussiness_application_desc','placeholder'=>''))}}
                                    </div>
                                    <span class="validate-err" id="err_bussiness_application_desc"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h6 class="sub-title">{{__('Tax Base')}}</h6>
                        <div class="row">
                            <div class="col-lg-3 col-md-2 col-sm-2">
                                <div class="form-group" style="padding-top: 30px;">
                                    <div class="form-icon-user">
                                        {{Form::label('capitalization',__('Capitalization:'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-2 col-sm-2">
                                <div class="form-group" style="padding-top: 20px;">
                                    <div class="form-icon-user">
                                        {{Form::text('capitalization[]','',array('class'=>'form-control capitalization','placeholder'=>''))}}
                                    </div>
                                    <span class="validate-err" id="err_capitalization"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-2 col-sm-2">
                                <div class="form-group" style="padding-top: 30px;">
                                    <div class="form-icon-user">
                                        {{Form::label('grosssale',__('Gross Sale:'),['class'=>'form-label'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-2 col-sm-2">
                                <div class="form-group" style="padding-top: 20px;">
                                    <div class="form-icon-user">
                                        {{Form::text('gross_sale[]','',array('class'=>'form-control gross_sale','placeholder'=>''))}}
                                    </div>
                                    <span class="validate-err" id="err_gross_sale"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h6 class="sub-title">{{__('Line Of Business As Per Revenue Code')}}</h6>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                            {{Form::label('tax_type_code',__('Tax Type'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                            {{ Form::select('tax_type_code[]',$taxtypes, '', array('class' => 'form-control  tax_type_code')) }}
                                            {{ Form::hidden('tax_type_id[]','', array('class' => 'tax_type_id')) }}
                                        </div>
                                        <span class="validate-err" id="err_tax_type_code"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                            {{Form::text('tax_type_desc[]','',array('class'=>'form-control tax_type_desc','placeholder'=>''))}}
                                        </div>
                                        <span class="validate-err" id="err_tax_type_desc"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                            {{Form::label('classification_code',__('Classification'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                            {{ Form::select('classification_code[]',$classification, '', array('class' => 'form-control classification_code')) }}
                                            {{ Form::hidden('classification_id[]','', array('class' => 'classification_id')) }}
                                        </div>
                                        <span class="validate-err" id="err_classification_code"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                            {{Form::text('classification_desc[]','',array('class'=>'form-control classification_desc','placeholder'=>''))}}
                                        </div>
                                        <span class="validate-err" id="err_classification_desc"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                            {{Form::label('activity_code',__('Activity'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                            {{ Form::select('activity_code[]',$activity, '', array('class' => 'form-control activity_code')) }}
                                            {{ Form::hidden('activity_id[]','', array('class' => 'activity_id')) }}
                                        </div>
                                        <span class="validate-err" id="err_activity_code"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                            {{Form::text('activity_desc[]','',array('class'=>'form-control activity_desc','placeholder'=>''))}}
                                        </div>
                                        <span class="validate-err" id="err_activity_desc"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2"></div>
                                <div class="col-lg-4 col-md-2 col-sm-2">
                                    <div class="d-flex radio-check" style="padding-top: 30px;">
                                        <div class="form-check form-check-inline form-group">
                                            {{ Form::checkbox('essential_commodities[]', '1','', array('class'=>'form-check-input code essential_commodities')) }}
                                            {{ Form::label('essential_commodities', __('Essential Commodities'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-2 col-sm-2">
                                    <div class="form-group" style="padding-top: 30px;">
                                        <div class="form-icon-user">
                                            {{Form::label('no_of_perdays',__('No of Days(if per Day):'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-2 col-sm-2">
                                    <div class="form-group" style="padding-top: 20px;">
                                        <div class="form-icon-user">
                                            {{Form::text('no_of_perdays[]','',array('class'=>'form-control no_of_perdays','placeholder'=>''))}}
                                        </div>
                                        <span class="validate-err" id="err_no_of_perdays"></span>
                                    </div>
                                </div>
                            </div>
                    </div>
                 
                    <div class="row">
                        <h6 class="sub-title">{{__('Regulatory Fee Business Categories')}}</h6>
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('mayrol_permit_description',__("Mayor's Permit"),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('mayrol_permit_description[]','',array('class'=>'form-control mayrol_permit_description','placeholder'=>''))}}
                                        {{ Form::hidden('permit_amount[]','', array('class' => 'permit_amount')) }}
                                    </div>
                                    <span class="validate-err" id="err_mayrol_permit_description"></span>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('mayrol_permit_code[]','',array('class'=>'form-control mayrol_permit_code','placeholder'=>''))}}
                                    </div>
                                    <span class="validate-err" id="err_mayrol_permit_code"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row"><br>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('garbage_description',__('Garbage'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('garbage_description[]','',array('class'=>'form-control garbage_description','placeholder'=>''))}}
                                        {{ Form::hidden('garbage_amount[]','', array('class' => 'garbage_amount')) }}
                                    </div>
                                    <span class="validate-err" id="err_garbage_description"></span>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('garbage_code[]','',array('class'=>'form-control garbage_code','placeholder'=>''))}}
                                    </div>
                                    <span class="validate-err" id="err_garbage_code"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row"><br>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::label('sanitory',__('Sanitary'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('sanitary_description[]','',array('class'=>'form-control sanitary_description','placeholder'=>''))}}
                                        {{ Form::hidden('sanitary_amount[]','', array('class' => 'sanitary_amount')) }}
                                    </div>
                                    <span class="validate-err" id="err_sanitary_description"></span>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <div class="form-icon-user">
                                        {{Form::text('sanitary_code[]','',array('class'=>'form-control sanitary_code','placeholder'=>''))}}
                                    </div>
                                    <span class="validate-err" id="err_sanitary_code"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>

                <div class="modal-footer">
                    <a href="#" data-dismiss="modal" class="btn closeModel" mid="" type="add">Close</a>
                    <a href="javascript:void(0)" class="btn btn-primary savebusinessDetails">Save changes</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="hidenPopupListHtml" class="hide">
    <table>
        <tr class="font-style">
            <td class="app_qurtr"></td>
            <td class="nature_of_bussiness"></td>
            <td class="activity"></td>
            <td class="capitalization"></td>
            <td class="gross_sale"></td>
            <td class="action"><a href="javascript:void(0)" mid="" type="edit" class="btnPopupOpen">Edit</a></td>
        </tr>
    </table>
</div>

<script src="{{ asset('js/jquery.inputpicker.js') }}"></script>
<script src="{{ asset('js/addAssessment.js') }}"></script>
<script src="{{ asset('js/ajax_validation.js') }}"></script>



