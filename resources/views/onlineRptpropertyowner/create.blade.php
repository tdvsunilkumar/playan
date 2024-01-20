{{ Form::open(array('url' => 'taxpayer-online-registration','id'=>'storePropertyOwnerForm')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }} 
{{ Form::hidden('ref_client_id',$ref_client_id, array('id' => 'ref_client_id')) }}   

{{Form::hidden('p_mobile_no_old','',array('class'=>'form-control p_mobile_no_old','id'=>'p_mobile_no_old','placeholder' => ''))}}
{{Form::hidden('p_email_address_old','',array('class'=>'form-control p_email_address_old','id'=>'p_email_address_old','placeholder' => ''))}}

<style>
    .modal-body {
        position: relative;
        flex: 1 1 auto;
        padding: 1.25rem;
        /* padding-bottom: 5%; */
    }
    .modal-xll {
        max-width: 1330px !important;
    }
   
    .modal.show .modal-dialog {
        transform: none;
        display: flex;
        position: fixed;
        /* justify-content: center; */
        /* width: 1550px; */
    }
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1055;
        display: none;
        width: 100%;
        height: 100%;
        overflow-x: hidden;
        overflow-y: auto;
        outline: 0;
        margin-top: -28px;
    }
     .modal-content {
        height: 100%;
        position: fixed;
        display: flex;
        flex-direction: column;
        /* width: 1550px; */
        pointer-events: auto;
        background-color: #ffffff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 15px;
        outline: 0;
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
    /*.select3-container{
        z-index: 9999999 !important;
    }*/

 </style>
    <div class="modal-body">
                <div class="row pt10" >
                    <div class="col-lg-5 col-md-5 col-sm-5">
                        <!--------------- Owners Information Start Here---------------->
                        <div class="row"  id="accordionFlushExample">  
                            <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingone">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingtwo">
                                            <h6 class="sub-title accordiantitle">{{__("Online Registration")}}</h6>
                                        </button>
                                    </h6>
                                    <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
                                        <div class="basicinfodiv">
                                            <div class="row">      
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{Form::label('rpo_custom_last_name',__('Last Name / Corporation/Organization Name/Couples Name/Other Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                        <div class="form-icon-user">
                                                            {{Form::textarea('rpo_custom_last_name',$data->rpo_custom_last_name,array('class'=>'form-control','rows'=>'2','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_rpo_custom_last_name"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('rpo_first_name',__('First Name'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::text('rpo_first_name',$data->rpo_first_name,array('class'=>'form-control','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_rpo_first_name"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('rpo_middle_name',__('Middle Name'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::text('rpo_middle_name',$data->rpo_middle_name,array('class'=>'form-control','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_rpo_middle_name"></span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('suffix',__('Suffix(Jr, Sr, II, III)'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::text('suffix',$data->suffix,array('class'=>'form-control','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_suffix"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('rpo_address_house_lot_no',__('House/Lot No.'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::textarea('rpo_address_house_lot_no',$data->rpo_address_house_lot_no,array('class'=>'form-control','rows'=>1,'readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_rpo_address_house_lot_no"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('rpo_address_street_name',__('Street Name'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::textarea('rpo_address_street_name',$data->rpo_address_street_name,array('class'=>'form-control','rows'=>1,'readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_rpo_address_street_name"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('rpo_address_subdivision',__('Subdivision'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::textarea('rpo_address_subdivision',$data->rpo_address_subdivision,array('class'=>'form-control','rows'=>1,'readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_rpo_address_subdivision"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12" id="p_barangay_id_no_div">
                                                    <div class="form-group" id="p_barangay_id_group">
                                                        {{Form::label('p_barangay_id_no',__('Barangay, Municipality, Province, Region'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::select('p_barangay_id_no',$arrgetBrgyCode,$data->p_barangay_id_no,array('class'=>'form-control','id'=>'p_barangay_id','disabled'=>'disabled')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_p_barangay_id_no"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('p_telephone_no',__('Telephone No.'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::text('p_telephone_no',$data->p_telephone_no,array('class'=>'form-control phonenumber','id'=>'p_telephone_no','placeholder' => '','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_p_telephone_no"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('p_mobile_no',__('Mobile No.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                        <div class="form-icon-user">
                                                            {{Form::text('p_mobile_no',$data->p_mobile_no,array('class'=>'form-control phonenumber','id'=>'p_mobile_no','placeholder' => '','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_p_mobile_no"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('p_fax_no',__('Fax No.'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::text('p_fax_no',$data->p_fax_no,array('class'=>'form-control','id'=>'p_fax_no','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_p_fax_no"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('p_email_address',__('Email Address'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                        <div class="form-icon-user">
                                                            {{Form::email('p_email_address',$data->p_email_address,array('class'=>'form-control','id'=>'p_email_address','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_p_email_address"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('p_tin_no',__('Tin No.'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::text('p_tin_no',$data->p_tin_no,array('class'=>'form-control','id'=>'p_tin_no','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_p_tin_no"></span>
                                                    </div>
                                                </div> 
                                                <div class="col-md-4" id="country_div">
                                                    <div class="form-group">
                                                            {{ Form::label('country', __('Nationality'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                
                                                            <div class="form-icon-user">
                                                                {{ Form::select('country',$arrgetCountries,$data->country, array('class' => 'form-control','id'=>'country','readonly'=>"true",'disabled'=>'disabled')) }}
                                                                
                                                            </div>
                                                            <span class="validate-err" id="err_country"></span>
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('gender',__('Gender'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{ Form::select('gender',array('1' =>'Male','0' =>'Female'), $data->gender, array('class' => 'form-control spp_type','id'=>'gender','disabled'=>'disabled')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_gender"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('dateofbirth',__('Date Of Birth'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::date('dateofbirth',$data->dateofbirth,array('class'=>'form-control','id'=>'dateofbirth','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_dateofbirth"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('remarks',__('Remarks'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                        {{Form::text('remarks',$data->remarks,array('class'=>'form-control','id'=>'remarks'))}}
                                                        </div>
                                                        <span class="validate-err" id="err_dateofbirth"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row"  id="accordionFlushExample2">  
                            <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingone2">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone2" aria-expanded="false" aria-controls="flush-headingtwo2">
                                            <h6 class="sub-title accordiantitle">{{__("Proof of identity")}}</h6>
                                        </button>
                                    </h6>
                                    <div id="flush-collapseone2" class="accordion-collapse collapse" aria-labelledby="flush-headingone2" data-bs-parent="#accordionFlushExample2">
                                        <div class="basicinfodiv">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body table-border-style">
                                                            <div class="table-responsive">
                                                                <table class="table" style="width: 100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>{{__('Document title')}}</th>
                                                                            <th>{{__('Action')}}</th>
                                                                        </tr>
                                                                        @if($data->government_id_attachment != null)
                                                                            @php
                                                                                $remotePath = 'client_documents/'.$data->government_id_attachment;
                                                                                $fileContents = config('filesystems.disks.remote')['asset_url'].$remotePath;
                                                                            @endphp
                                                                        <tr>
                                                                            <td>Government Id</td>
                                                                            <td><a href="{{ $fileContents }}" class="action-btn bg-warning btn ms-05 btn-sm align-items-center" target="_blank" title="Download">
                                                                                <i class="ti-download"></i>
                                                                            </a></td>
                                                                        </tr>
                                                                        <input type="hidden" name="government_id_attachment" value="1" id="government_id_attachment">
                                                                        @else
                                                                            <input type="hidden" name="government_id_attachment" value="0" id="government_id_attachment">
                                                                        @endif
                                                                        @if($data->business_permit_attachment != null)
                                                                            @php
                                                                                $remotePath = 'client_documents/'.$data->business_permit_attachment;
                                                                                $fileContents = config('filesystems.disks.remote')['asset_url'].$remotePath;
                                                                            @endphp
                                                                        <tr>
                                                                            <td>Business Permit</td>
                                                                            <td><a href="{{ $fileContents }}" class="action-btn bg-warning btn ms-05 btn-sm align-items-center" target="_blank" title="Download">
                                                                                <i class="ti-download"></i>
                                                                            </a></td>
                                                                        </tr>
                                                                        <input type="hidden" name="business_permit_attachment" value="1" id="business_permit_attachment">
                                                                        @else
                                                                            <input type="hidden" name="business_permit_attachment" value="0" id="business_permit_attachment">
                                                                        @endif
                                                                        @if($data->property_tax_attachment != null)
                                                                            @php
                                                                                $remotePath = 'client_documents/'.$data->property_tax_attachment;
                                                                                $fileContents = config('filesystems.disks.remote')['asset_url'].$remotePath;
                                                                            @endphp
                                                                        <tr>
                                                                            <td>Property Tax</td>
                                                                            <td><a href="{{ $fileContents }}" class="action-btn bg-warning btn ms-05 btn-sm align-items-center" target="_blank" title="Download">
                                                                                <i class="ti-download"></i>
                                                                            </a></td>
                                                                        </tr>
                                                                        <input type="hidden" name="property_tax_attachment" value="1" id="property_tax_attachment">
                                                                        @else
                                                                            <input type="hidden" name="property_tax_attachment" value="0" id="property_tax_attachment">
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
                            </div>
                        </div> 
                        <div class="row"  id="accordionFlushExample1">  
                            <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingone1">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone1" aria-expanded="false" aria-controls="flush-headingtwo1">
                                            <h6 class="sub-title accordiantitle">{{__("Existing Taxpayers Information")}}</h6>
                                        </button>
                                    </h6>
                                    <div id="flush-collapseone1" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample1">
                                        <div class="basicinfodiv">
                                            @if($data->p_email_address != null)
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group m-form__group">
                                                        {{ Form::checkbox($name = 'c_email', $value = '0', $checked = false, $attributes = array(
                                                            'id' => 'c_email',
                                                            'class' => 'form-check-input c_email'
                                                        )) }}
                                                        {{ Form::label('c_email', 'Copy [Email Address] from Taxpayer Portal to Palayan System', ['class' => 'form-check-label c_email' ]) }}
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if($data->p_mobile_no != null)
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group m-form__group">
                                                        {{ Form::checkbox($name = 'c_mobile', $value = '0', $checked = false, $attributes = array(
                                                            'id' => 'c_mobile',
                                                            'class' => 'form-check-input c_mobile',
                                                        )) }}
                                                        {{ Form::label('c_mobile', 'Copy [Mobile] from Taxpayer Portal to Palayan System', ['class' => 'form-check-label c_mobile' ]) }}
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="row">
                                            
                                                <div class="col-lg-10 col-md-10 col-sm-10" style="width: 88%;">
                                                    <div class="form-group" id="p_search">
                                                            {{ Form::label('search', __('Search'),['class'=>'form-label']) }}
                                                
                                                            <div class="form-icon-user">
                                                                {{ Form::select('search',$client,$ref_client_id, array('class' => 'form-control','id'=>'search')) }}
                                                            </div>
                                                            <span class="validate-err" id="err_country"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2" style="text-align: end;
                                                    height: 35px;
                                                    margin-top: 29px;
                                                    width: 13%;
                                                    margin-left: -16px;
                                                    display: flex;"><br>
                                                    <a href="#" class="btn btn-sm btn-danger" id="clear_data" style="">
                                                        <span class="btn-inner--icon"><i class="ti-trash "></i></span>
                                                    </a>
                                                </div>
                                                
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        {{Form::label('rpo_custom_last_name',__('Last Name / Corporation/Organization Name/Couples Name/Other Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                        <div class="form-icon-user">
                                                            {{Form::textarea('rpo_custom_last_name_ext','',array('class'=>'form-control','id'=>'rpo_custom_last_name_ext','rows'=>'2','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_rpo_custom_last_name"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('rpo_first_name',__('First Name'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::text('rpo_first_name_ext','',array('class'=>'form-control','id'=>'rpo_first_name_ext','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_rpo_first_name"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('rpo_middle_name',__('Middle Name'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::text('rpo_middle_name_ext','',array('class'=>'form-control','id'=>'rpo_middle_name_ext','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_rpo_middle_name"></span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('suffix',__('Suffix(Jr, Sr, II, III)'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::text('suffix_ext','',array('class'=>'form-control','id'=>'suffix_ext','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_suffix"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('rpo_address_house_lot_no',__('House/Lot No.'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::textarea('rpo_address_house_lot_no_ext','',array('class'=>'form-control','id'=>'rpo_address_house_lot_no_ext','rows'=>1,'readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_rpo_address_house_lot_no"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('rpo_address_street_name',__('Street Name'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::textarea('rpo_address_street_name_ext','',array('class'=>'form-control','id'=>'rpo_address_street_name_ext','rows'=>1,'readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_rpo_address_street_name"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('rpo_address_subdivision',__('Subdivision'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::textarea('rpo_address_subdivision_ext','',array('class'=>'form-control','rows'=>1,'id'=>'rpo_address_subdivision_ext','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_rpo_address_subdivision"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12" id="p_barangay_id_no_div_ext">
                                                    <div class="form-group" id="p_barangay_id_group_ext">
                                                        {{Form::label('p_barangay_id_no',__('Barangay, Municipality, Province, Region'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                        <div class="form-icon-user">
                                                            {{Form::textarea('p_barangay_id_no_ext','',array('class'=>'form-control','rows'=>1,'id'=>'p_barangay_id_no_ext','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_p_barangay_id_no"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('p_telephone_no',__('Telephone No.'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::text('p_telephone_no_ext','',array('class'=>'form-control phonenumber','id'=>'p_telephone_no_ext','placeholder' => '','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_p_telephone_no"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('p_mobile_no',__('Mobile No.'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                        <div class="form-icon-user">
                                                            {{Form::text('p_mobile_no_ext','',array('class'=>'form-control phonenumber','id'=>'p_mobile_no_ext','placeholder' => '','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_p_mobile_no"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('p_fax_no',__('Fax No.'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::text('p_fax_no_ext','',array('class'=>'form-control','id'=>'p_fax_no_ext','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_p_fax_no"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('p_email_address',__('Email Address'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                                        <div class="form-icon-user">
                                                            {{Form::email('p_email_address_ext','',array('class'=>'form-control','id'=>'p_email_address_ext','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_p_email_address"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('p_tin_no',__('Tin No.'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::text('p_tin_no_ext','',array('class'=>'form-control','id'=>'p_tin_no_ext','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_p_tin_no"></span>
                                                    </div>
                                                </div> 
                                                <div class="col-md-4" id="country_div_ext">
                                                    <div class="form-group">
                                                            {{ Form::label('country', __('Nationality'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                
                                                            <div class="form-icon-user">
                                                                {{ Form::select('country_ext',$arrgetCountries,'', array('class' => 'form-control','id'=>'country_ext','disabled'=>'disabled')) }}
                                                                
                                                            </div>
                                                            <span class="validate-err" id="err_country"></span>
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('gender',__('Gender'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{ Form::select('gender_ext',array('' =>'Select','1' =>'Male','0' =>'Female'), '', array('class' => 'form-control spp_type','id'=>'gender_ext','disabled'=>'disabled')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_gender"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::label('dateofbirth',__('Date Of Birth'),['class'=>'form-label'])}}
                                                        <div class="form-icon-user">
                                                            {{Form::date('dateofbirth_ext','',array('class'=>'form-control','id'=>'dateofbirth_ext','readonly'=>"true"))}}
                                                        </div>
                                                        <span class="validate-err" id="err_dateofbirth"></span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-4 col-md-4 col-sm-4" id="online" style="margin-top: 35px;display: none;">
                                                    <div class="form-group">
                                                        <style>
                                                            .dot {
                                                              height: 20px;
                                                              width: 20px;
                                                              background-color: #bbb;
                                                              border-radius: 50%;
                                                              display: inline-block;
                                                            }
                                                        </style>
                                                        <div class="form-icon-user">
                                                            <table>
                                                                <tr><td style="padding-top: 4px;"><span class="dot" style="background:green;"></span></td><td style="padding-left: 5px;"><b style="color: green;">Online</b></td></tr>
                                                            </table>
                                                           
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                              
                                                <div class="col-lg-4 col-md-4 col-sm-4" id="offline" style="margin-top: 35px;display: none;">
                                                    <div class="form-group">
                                                        <style>
                                                            .dot {
                                                              height: 20px;
                                                              width: 20px;
                                                              background-color: #bbb;
                                                              border-radius: 50%;
                                                              display: inline-block;
                                                            }
                                                        </style>
                                                        <div class="form-icon-user">
                                                            <table>
                                                                <tr><td style="padding-top: 4px;"><span class="dot" style="background:red;"></span></td><td style="padding-left: 5px;"><b style="color: red;">Offline</b></td></tr>
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
                    <div class="col-lg-7 col-md-7 col-sm-7">
                        <div class="row"  id="accordionFlushExample5">  
                            <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingone5">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone5" aria-expanded="false" aria-controls="flush-headingtwo5">
                                            <h6 class="sub-title accordiantitle">{{__("Business Permit Reference")}}</h6>
                                        </button>
                                    </h6>
                                    <div id="flush-collapseone5" class="accordion-collapse collapse" aria-labelledby="flush-headingone5" data-bs-parent="#accordionFlushExample5">
                                        <div class="basicinfodiv">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body table-border-style">
                                                            <div class="table-responsive">
                                                                <table class="table" id="bplo_datatable" style="width: 100%;">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>{{__('No.')}}</th>
                                                                        <th>{{__('BUSINESS ID-No.')}}</th>
                                                                        <th>{{__('Business Name')}}</th>
                                                                        <th>{{__('Barangay')}}</th>
                                                                        <th>{{__('Type')}}</th>
                                                                        <th>{{__('Date')}}</th>
                                                                        <th>{{__('Last payment')}}</th>
                                                                    </tr>
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
                        <div class="row"  id="accordionFlushExample6">  
                            <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingone6">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone6" aria-expanded="false" aria-controls="flush-headingtwo6">
                                            <h6 class="sub-title accordiantitle">{{__("Planning & Development Reference")}}</h6>
                                        </button>
                                    </h6>
                                    <div id="flush-collapseone6" class="accordion-collapse collapse" aria-labelledby="flush-headingone6" data-bs-parent="#accordionFlushExample6">
                                        <div class="basicinfodiv">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body table-border-style">
                                                            <div class="table-responsive">
                                                                <table class="table" id="planning_datatable" style="width: 100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>{{__('No')}}</th>
                                                                            <th>{{__('Control No')}}</th>
                                                                            <th>{{__('Applicant Name')}}</th>
                                                                            <th>{{__('Project name')}}</th>
                                                                            <th>{{__('Address')}}</th>
                                                                            <th>{{__('App Status')}}</th>
                                                                            <th>{{__('O.R.NO')}}</th>
                                                                            <th>{{__('O.R.Date')}}</th>
                                                                        </tr>
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
                        <div class="row"  id="accordionFlushExample3">  
                            <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingone3">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone3" aria-expanded="false" aria-controls="flush-headingtwo3">
                                            <h6 class="sub-title accordiantitle">{{__("Engineering Reference")}}</h6>
                                        </button>
                                    </h6>
                                    <div id="flush-collapseone3" class="accordion-collapse collapse" aria-labelledby="flush-headingone3" data-bs-parent="#accordionFlushExample3">
                                        <div class="basicinfodiv">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body table-border-style">
                                                            <div class="table-responsive">
                                                                <table class="table" id="eng_datatable" style="width: 100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>{{__('SR No.')}}</th>
                                                                            <th>{{__('Job Req no.')}}</th>
                                                                            <th>{{__('Applicant Name')}}</th>
                                                                            <th>{{__('Services')}}</th>
                                                                            
                                                                            <th>{{__('Permit No.')}}</th>
                                                                            
                                                                            <th>{{__('O.R No')}}</th>
                                                                            <th>{{__('O.R date')}}</th>
                                                                        </tr>
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
                        <div class="row"  id="accordionFlushExample7">  
                            <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingone7">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone7" aria-expanded="false" aria-controls="flush-headingtwo7">
                                            <h6 class="sub-title accordiantitle">{{__("Occupancy Reference")}}</h6>
                                        </button>
                                    </h6>
                                    <div id="flush-collapseone7" class="accordion-collapse collapse" aria-labelledby="flush-headingone7" data-bs-parent="#accordionFlushExample7">
                                        <div class="basicinfodiv">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body table-border-style">
                                                            <div class="table-responsive">
                                                                <table class="table" id="occupation_datatable" style="width: 100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>{{__('SR No.')}}</th>
                                                                            <th>{{__('Permit No.')}}</th>
                                                                            <th>{{__('Applicant Name')}}</th>
                                                                            <th>{{__('Services')}}</th>
                                                                            <th>{{__('App No.')}}</th>
                                                                            <th>{{__('TOP No.')}}</th>

                                                                            
                                                                            <th>{{__('O.R. No.')}}</th>
                                                                            <th>{{__('O.R. Date')}}</th>
                                                                            
                                                                        </tr>
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
                        <div class="row"  id="accordionFlushExample8">  
                            <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingone8">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone8" aria-expanded="false" aria-controls="flush-headingtwo8">
                                            <h6 class="sub-title accordiantitle">{{__("Real Property Reference")}}</h6>
                                        </button>
                                    </h6>
                                    <div id="flush-collapseone8" class="accordion-collapse collapse" aria-labelledby="flush-headingone8" data-bs-parent="#accordionFlushExample8">
                                        <div class="basicinfodiv">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body table-border-style">
                                                            <div class="table-responsive">
                                                                <table class="table" id="realproperty_datatable" style="width: 100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>{{__('SR No.')}}</th>
                                                                            <th>{{__('TD No.')}}</th>
                                                                            <th>{{__('Taxpayer Name')}}</th>
                                                                            <th>{{__('Barangay')}}</th>
                                                                            <th>{{__('PIN')}}</th>
                                                                            <th>{{__('Lot|CCT|Unit No.|Description')}}</th>
                                                                            <th>{{__('Class')}}</th>
                                                                            <th>{{__('Assessed Value')}}</th>
                                                                        </tr>
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
                        <div class="row"  id="accordionFlushExample4">  
                            <div  class="accordion accordion-flush">
                                <div class="accordion-item">
                                    <h6 class="accordion-header" id="flush-headingone4">
                                        <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone4" aria-expanded="false" aria-controls="flush-headingtwo4">
                                            <h6 class="sub-title accordiantitle">{{__("Departmental Access")}}</h6>
                                        </button>
                                    </h6>
                                    <div id="flush-collapseone4" class="accordion-collapse collapse show" aria-labelledby="flush-headingone3" data-bs-parent="#accordionFlushExample4">
                                        <div class="basicinfodiv">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group m-form__group">
                                                        {{ Form::checkbox($name = 'is_bplo', $value = '1', $checked = 1 ?"true" : "", $attributes = array(
                                                            'id' => 'is_bplo',
                                                            'class' => 'form-check-input is_bplo',
                                                            'disabled'=>'disabled'
                                                        )) }}
                                                        {{ Form::label('business_permit', 'Business Permit', ['class' => 'form-check-label business_permit' ]) }}
                                                    </div>
                                                </div>
                                            
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group m-form__group">
                                                        {{ Form::checkbox($name = 'is_fire_safety', $value = '1', $checked = 1 ?"true" : "", $attributes = array(
                                                            'id' => 'is_fire_safety',
                                                            'class' => 'form-check-input is_fire_safety',
                                                            'disabled'=>'disabled'
                                                        )) }}
                                                        {{ Form::label('planning_dev', 'Planning & Development', ['class' => 'form-check-label fire_safety' ]) }}
                                                    </div>
                                                </div>
                                            
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group m-form__group">
                                                        {{ Form::checkbox($name = 'is_engg', $value = '1', $checked = 1 ?"true" : "", $attributes = array(
                                                            'id' => 'is_engg',
                                                            'class' => 'form-check-input is_engg',
                                                            'disabled'=>'disabled'
                                                        )) }}
                                                        {{ Form::label('engineering', 'Engineering', ['class' => 'form-check-label engineering' ]) }}
                                                    </div>
                                                </div>
                                            
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group m-form__group">
                                                        {{ Form::checkbox($name = 'is_engg', $value = '1', $checked = 1 ?"true" : "", $attributes = array(
                                                            'id' => 'is_engg',
                                                            'class' => 'form-check-input is_engg',
                                                            'disabled'=>'disabled'
                                                        )) }}
                                                        {{ Form::label('occupancy', 'Occupancy', ['class' => 'form-check-label engineering' ]) }}
                                                    </div>
                                                </div>
                                            
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group m-form__group">
                                                        {{ Form::checkbox($name = 'is_rpt', $value = '1', $checked = 1 ?"true" : "", $attributes = array(
                                                            'id' => 'is_rpt',
                                                            'class' => 'form-check-input is_rpt',
                                                            'disabled'=>'disabled'
                                                        )) }}
                                                        {{ Form::label('real_property', 'Real Property', ['class' => 'form-check-label real_property' ]) }}
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
           
           <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #ff3a6e;padding-left: 8px;color: #fff;border-radius: 5px;">
                <input type="button" id="decline" value="{{ ($data->id)>0?__('Decline'):__('Decline')}}" class="btn btn-primary" style="background: #ff3a6e;padding-left: 4px;color: #fff;padding: 9px;border-radius: 5px;border: none;">
            </div>
            <div class="button" style="background: #6fd943;padding-left: 8px;color: #fff;border-radius: 5px;">
                <input type="button" id="approve" value="{{ ($data->id)>0?__('Accept'):__('Accept')}}" class="btn btn-primary" style="background: #6fd943;padding-left: 4px;color: #fff;padding: 9px;border-radius: 5px;border: none;">
            </div>
            <!-- <input type="button" id="submitOwnerForm"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
            <br><br><br>
        </div> 
         </div>
        </div>
        <!--------------- Business Details Listing End Here------------------>

            </div>

        </div>
    </div>
</div>
 {{Form::close()}}

@push('styles')
<style>
    .dataTables_length {
        display: none !important;
    }
</style>
@endpush
<!-- <script src="{{ asset('js/ajax_validation.js') }}"></script> -->
<script src="{{ asset('js/online_add_rptProperty.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
	
    $('#planning_datatable').DataTable({
                "paging": true,       // Enable paging
                "lengthMenu": [3],  // Set page length to 10
                "ordering": true,    // Enable sorting
                // Add your custom options or callbacks here, if needed
    });
    $('#eng_datatable').DataTable({
                "paging": true,       // Enable paging
                "lengthMenu": [10],  // Set page length to 10
                "ordering": true,    // Enable sorting
                // Add your custom options or callbacks here, if needed
    });
    $('#occupation_datatable').DataTable({
                "paging": true,       // Enable paging
                "lengthMenu": [10],  // Set page length to 10
                "ordering": true,    // Enable sorting
                // Add your custom options or callbacks here, if needed
    });
    $('#realproperty_datatable').DataTable({
                "paging": true,       // Enable paging
                "lengthMenu": [10],  // Set page length to 10
                "ordering": true,    // Enable sorting
                // Add your custom options or callbacks here, if needed
    });
	$("#uploadAttachmentonly").click(function(){
		uploadAttachmentonly();
	});
	$(".deleteAttachment").click(function(){
		deleteAttachment($(this));
	})
	
});
function uploadAttachmentonly(){
	$(".validate-err").html("");
	if (typeof $('#ora_document')[0].files[0]== "undefined") {
		$("#err_documents").html("Please upload Document");
		return false;
	}
	var formData = new FormData();
	formData.append('file', $('#ora_document')[0].files[0]);
	formData.append('healthCertId', $("#id").val());
	showLoader();
	$.ajax({
	   url : DIR+'rptpropertyowner-uploadDocument',
	   type : 'POST',
	   data : formData,
	   processData: false,  // tell jQuery not to process the data
	   contentType: false,  // tell jQuery not to set contentType
	   success : function(data) {
			hideLoader();
			var data = JSON.parse(data);
			if(data.ESTATUS==1){
				$("#err_end_requirement_id").html(data.message);
			}else{
				$("#end_requirement_id").val(0);
				$("#ora_document").val(null);
				if(data!=""){
					$("#DocumentDtlsss").html(data.documentList);
				}
				Swal.fire({
					 position: 'center',
					 icon: 'success',
					 title: 'Document uploaded successfully.',
					 showConfirmButton: false,
					 timer: 1500
				})
				$(".deleteEndrosment").unbind("click");
				$(".deleteEndrosment").click(function(){
					deleteEndrosment($(this));
				})
			}
	   }
	});
}
 function deleteAttachment(thisval){
		var healthCertid = thisval.attr('healthCertid');
		var doc_id = thisval.attr('doc_id');
		const swalWithBootstrapButtons = Swal.mixin({
		   customClass: {
			   confirmButton: 'btn btn-success',
			   cancelButton: 'btn btn-danger'
		   },
		   buttonsStyling: false
	   })
	   swalWithBootstrapButtons.fire({
		   text: "Are you sure?",
		   icon: 'warning',
		   showCancelButton: true,
		   confirmButtonText: 'Yes',
		   cancelButtonText: 'No',
		   reverseButtons: true
	   }).then((result) => {
			if(result.isConfirmed){
				showLoader();
				$.ajax({
				   url :DIR+'rptpropertyowner-deleteAttachment', // json datasource
				   type: "POST", 
				   data: {
						"healthCertid": healthCertid,
						"doc_id": doc_id,  
				   },
				   success: function(html){
					hideLoader();
					thisval.closest("tr").remove();
					   Swal.fire({
						 position: 'center',
						 icon: 'success',
						 title: 'Update Successfully.',
						 showConfirmButton: false,
						 timer: 1500
					   })
				   }
			   })
		   }
	   })
	}
</script>



