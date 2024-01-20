{{Form::open(array('url'=>'business-permit/application/store','method'=>'post'))}}
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
    .field-requirement-details-status label{margin-top: 7px;}

 </style>
<div class="modal-body">
   <div class="row">  
       <!--------------- Basic Info Start Here----------------> 
       <div class="col-lg-6 col-md-6 col-sm-6" id="accordionFlushExample">
          <div class="accordion accordion-flush" >
           <div class="accordion-item">
             <h6 class="accordion-header" id="flush-headingone">
              <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone" aria-expanded="false" aria-controls="flush-headingone">
                <h6 class="sub-title accordiantitle">{{__('Basic Info')}}</h6>
            </button>
        </h6>
        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1" data-bs-parent="#accordionFlushExample">
            <div class="basicinfodiv">
                <div class="row">
                                <div class="row">
                                       <div class="col-lg-7 col-md-7 col-sm-7">
                                        <div class="form-group">
                                            {{Form::label('owner_address',__('Owners Name'),array('class'=>'form-label')) }} 
                                             {{ Form::select('profile_id',$profile,$data->profile_id, array('class' => 'form-control select3','id'=>'profile')) }}
                                        </div>
                                      </div>
                                       <div class="col-lg-2 col-md-2 col-sm-2" style="padding-top: 20px;">
                                        <div class="form-group" >
                                            <a href="#"  data-size="xl" data-url="{{ url('/profileuser/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('New Profile User')}}" class="btn btn-sm btn-primary" style="margin-top: 8px;">
                                            <i class="ti-plus"></i></a>
                                        </div>
                                      </div>
                                 </div><br> 
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            {{Form::label('owner_address',__('Owners Address'),array('class'=>'form-label')) }} <span class="text-danger">*</span>
                                            <div class="input-group">
                                                {{Form::textarea('owneraddress',$data->owneraddress,array('class'=>'form-control','rows'=>2,'required'=>'required','placeholder'=>'Owner Address','id'=>'owneraddress'))}}
                                            </div>
                                    </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('PostalCode',__('Postal Code'),array('class'=>'form-label')) }} <span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::text('owner_postalcode',$data->owner_postalcode,array('class'=>'form-control numeric','required'=>'required','readonly'=>'readonly','placeholder'=>'Postal Code','id'=>'owner_postalcode'))}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('emailaaddress',__('Email Address'),array('class'=>'form-label')) }} <span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::text('owner_email',$data->owner_email,array('class'=>'form-control','required'=>'required','placeholder'=>'Email Address','id'=>'owner_email'))}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('telephoneno',__('Telephone No.'),array('class'=>'form-label')) }} <span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::text('owner_telephone',$data->owner_telephone,array('class'=>'form-control numeric','minlength'=>'10','required'=>'required','id'=>'owner_telephone'))}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('mobileno',__('Mobile No.'),array('class'=>'form-label')) }} <span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::text('owner_mobile',$data->owner_mobile,array('class'=>'form-control numeric','required'=>'required','id'=>'owner_mobile'))}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                    {{Form::label('application',__('Application Type'),array('class'=>'form-label')) }}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                     {{ Form::select('isnew',$arrtypes,$data->isnew, array('class' => 'form-control spp_type','id'=>'isnew','required'=>'required')) }}
                                     </div>

                                       <!--  <div id="tblFruits">
                                                 {{ Form::checkbox('isnew', '1', ($data->isnew ==1)?true:false, array('id'=>'asap','class'=>'product-list','required'=>'required')) }}
                                                 {{ Form::label('new', __('New'),['class'=>'form-label']) }}
                                           
                                               
                                                 {{ Form::checkbox('isnew', '2', ($data->isnew ==2)?true:false, array('id'=>'asap1','class'=>'product-list','required'=>'required')) }}
                                                 {{ Form::label('new', __('Renewal'),['class'=>'form-label']) }}
                                         
                                              
                                                 {{ Form::checkbox('isnew', '3', ($data->isnew ==3)?true:false, array('id'=>'asap2','class'=>'product-list','required'=>'required')) }}
                                                 {{ Form::label('new', __('Retire'),['class'=>'form-label']) }}
                                            
                                             </div> -->
                                             <span class="validate-err" id="err_isnew"></span>
                                         </div>
                                     </div>
                                     <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('modeofpayment',__('Mode of Payment'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('modeofpayment',array('' =>'Select type','annually' =>'Annually','semiannually' =>'Semi Annually','quaterly' =>'Quaterly'), $data->modeofpayment, array('class' => 'form-control spp_type','id'=>'modeofpayment','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_modeofpayment"></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('appdate',__('Application Date'),array('class'=>'form-label')) }}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::date('applicationdate',$data->applicationdate,array('class'=>'form-control','id'=>'applicationdate','required'=>'required'))}}
                                            </div>
                                            <span class="validate-err" id="err_applicationdate"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('tinno',__('Tin No'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::text('tinno',$data->tinno,array('class'=>'form-control','required'=>'required'))}}
                                            </div>
                                            <span class="validate-err" id="err_tinno"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('registration',__('Registration No'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::text('registartionno',$data->registartionno,array('class'=>'form-control','required'=>'required'))}}
                                            </div>
                                            <span class="validate-err" id="err_registartionno"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('registerdate',__('Registration Date'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{Form::date('registrationdate',$data->registrationdate,array('class'=>'form-control','required'=>'required'))}}
                                            </div>
                                            <span class="validate-err" id="err_registrationdate"></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('typeofbussiness',__('Select Business'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('typeofbussiness',$bussinesstype, $data->typeofbussiness, array('class' => 'form-control spp_type','id'=>'typeofbussiness','required'=>'required')) }}
                                            </div>
                                            <span class="validate-err" id="err_typeofbussiness"></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('amendentfrom',__('Amendent From'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{ Form::select('amendmentfrom',array('Select Amendent From' =>'','single' =>'Single','partenership' =>'Partnership','corporation' =>'Corporation'), $data->amendmentfrom, array('class' => 'form-control spp_type','id'=>'amendmentfrom')) }}
                                            </div>
                                            <span class="validate-err" id="err_amendmentfrom"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('amendentto',__('Amendent To'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{ Form::select('amendmentto',array('Select Amendent To' =>'','single' =>'Single','partenership' =>'Partnership','corporation' =>'Corporation'), $data->amendmentto, array('class' => 'form-control spp_type','id'=>'amendmentto')) }}
                                            </div>
                                            <span class="validate-err" id="err_amendmentto"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            {{Form::label('goveentity',__('Government Entity'),['class'=>'form-label'])}}
                                            <div class="form-icon-user">
                                                {{ Form::select('enjoyingtax',array('0' =>'No','1' =>'Yes'),$data->enjoyingtax, array('class' => 'form-control spp_type','id'=>'enjoyingtax')) }}
                                            </div>
                                            <span class="validate-err" id="err_enjoyingtax"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--------------- Basic Info Close Here----------------> 

            <!--------------- Address  Information Start Here----------------> 

            <div class="col-lg-6 col-md-6 col-sm-6"  id="accordionFlushExample2">
                <div class="accordion accordion-flush" >
                    <div class="accordion-item">
                        <h6 class="accordion-header" id="flush-headingtwo">
                          <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsetwo" aria-expanded="false" aria-controls="flush-headingtwo">
                           <h6 class="sub-title accordiantitle">{{__('Address  Information')}}</h6>
                       </button>
                   </h6>

                   <div id="flush-collapsetwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample2">
                    <div class="row"  id="otheinfodiv">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{Form::label('bussinessname',__('Business Name'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                <div class="form-icon-user">
                                    {{Form::text('bussinessname',$data->bussinessname,array('class'=>'form-control','placeholder'=>'Business Name','required'=>'required'))}}
                                </div>
                                <span class="validate-err" id="err_bussinessname"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {{Form::label('tradename',__('Trade Name'),['class'=>'form-label'])}} 
                                <div class="form-icon-user">
                                    {{Form::text('tradename',$data->tradename,array('class'=>'form-control','placeholder'=>'Trade Name'))}}
                                </div>
                                <span class="validate-err" id="err_tradename"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{Form::label('lotno',__('Lot No.'),array('class'=>'form-label')) }} <span class="text-danger">*</span>
                                <div class="input-group">
                                    {{Form::text('lotno',$data->lotno,array('class'=>'form-control','required'=>'required'))}}
                                </div>
                                <span class="validate-err" id="err_bussinessaddress"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{Form::label('street',__('Street'),array('class'=>'form-label')) }} <span class="text-danger">*</span>
                                <div class="input-group">
                                    {{Form::text('street',$data->street,array('class'=>'form-control','required'=>'required'))}}
                                </div>
                                <span class="validate-err" id="err_bussinessaddress"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{Form::label('subdivision',__('Subdivision'),array('class'=>'form-label')) }} <span class="text-danger">*</span>
                                <div class="input-group">
                                    {{Form::text('subdivision',$data->subdivision,array('class'=>'form-control','required'=>'required'))}}
                                </div>
                                <span class="validate-err" id="err_bussinessaddress"></span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    {{Form::label('barangay',__('Barangay'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                    {{ Form::select('barangay',$arrBarangay, $data->barangay, array('class' => 'form-control select3','id'=>'barangay','required'=>'required')) }}
                                    </div>
                                    <span class="validate-err" id="err_barangay"></span>
                                </div>
                        </div>
                         <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    {{Form::label('municipality',__('Muncipality'),['class'=>'form-label'])}}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                    {{ Form::select('municipality',$arrMunCode, $data->municipality, array('class' => 'form-control select3','id'=>'municipality','required'=>'required')) }}
                                    </div>
                                    <span class="validate-err" id="err_municipality"></span>
                                </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                {{Form::label('PostalCode',__('Postal Code'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                    {{Form::text('billing_postalcode',$data->billing_postalcode,array('class'=>'form-control'))}}
                                </div>
                                <span class="validate-err" id="err_billing_postalcode"></span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                {{Form::label('emailaaddress',__('Email Address'),array('class'=>'form-label')) }} <span class="text-danger">*</span>
                                <div class="form-icon-user">
                                    {{Form::text('billing_email',$data->billing_email,array('class'=>'form-control','required'=>'required'))}}
                                </div>
                                <span class="validate-err" id="err_billing_email"></span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                {{Form::label('telephoneno',__('Telephone No.'),array('class'=>'form-label')) }} <span class="text-danger">*</span>
                                <div class="form-icon-user">
                                    {{Form::text('billing_telephone',$data->billing_telephone,array('class'=>'form-control numeric','required'=>'required'))}}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                {{Form::label('mobileno',__('Mobile No.'),array('class'=>'form-label')) }} <span class="text-danger">*</span>
                                <div class="form-icon-user">
                                    {{Form::text('billing_mobile',$data->billing_mobile,array('class'=>'form-control numeric','required'=>'required'))}}
                                </div>
                            </div>
                        </div>
                       
                        
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                {{Form::label('conatctname',__('Contact Person Name'),array('class'=>'form-label')) }}
                                <div class="input-group">
                                    {{Form::text('contactname',$data->contactname,array('class'=>'form-control','rows'=>3))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                {{Form::label('mobileno',__('Contact Mobile No.'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                    {{Form::text('conactmobileno',$data->conactmobileno,array('class'=>'form-control'))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                {{Form::label('mobileno',__('Contact Email Address'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                    {{Form::text('contactemail',$data->contactemail,array('class'=>'form-control'))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                {{Form::label('Business area',__('Business Area'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                    {{Form::text('bussinessarea',$data->bussinessarea,array('class'=>'form-control'))}}
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                {{Form::label('noempestablishment',__('No of Emp. Establishment'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                    {{Form::number('noofempestablish',$data->noofempestablish,array('class'=>'form-control'))}}
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                {{Form::label('emplgu',__('No of Emp. Residing LGU'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                    {{Form::number('noofempewithlgu',$data->noofempewithlgu,array('class'=>'form-control'))}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
      </div>
       <!--------------- Address  Information End Here----------------> 
    </div>

      <!--------------- Other Activity Start Here----------------> 
        <div class="accordion accordion-flush" id="accordionFlushExample3">
            <div class="clearfix"></div>
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingthree">
                      <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsethree" aria-expanded="false" aria-controls="flush-headingthree">
                         <h6 class="sub-title accordiantitle">{{__('Rental(Lessor) Details')}}</h6>
                     </button>
                 </h6>

                 <div id="flush-collapsethree" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample3">
                    <h6 class="sub-title">{{__('NOTE: Fill Up Only If Business Place is Rented')}}</h6>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="form-group">
                                {{Form::label('lessor_fullname',__('Full Name'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                    {{Form::text('lessor_fullname',$data->lessor_fullname,array('class'=>'form-control'))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{Form::label('lessorlotno',__('House/Lot no'),array('class'=>'form-label')) }}
                                <div class="input-group">
                                    {{Form::text('lessorlotno',$data->lessorlotno,array('class'=>'form-control'))}}
                                </div>
                                <span class="validate-err" id="err_bussinessaddress"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{Form::label('lessorstreetname',__('Street'),array('class'=>'form-label')) }}
                                <div class="input-group">
                                    {{Form::text('lessorstreetname',$data->lessorstreetname,array('class'=>'form-control'))}}
                                </div>
                                <span class="validate-err" id="err_bussinessaddress"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{Form::label('lessorsubdivision',__('Subdivision'),array('class'=>'form-label')) }}
                                <div class="input-group">
                                    {{Form::text('lessorsubdivision',$data->lessorsubdivision,array('class'=>'form-control'))}}
                                </div>
                                <span class="validate-err" id="err_bussinessaddress"></span>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="form-group">
                                {{Form::label('lessorbarangay',__('Barangay,Muncipility,Province,Region'),['class'=>'form-label'])}}
                                <div class="form-icon-user">
                                {{ Form::select('lessorbarangay',$lenssorbarangay, $data->lessorbarangay, array('class' => 'form-control','id'=>'lessorbarangay')) }}
                                </div>
                                <span class="validate-err" id="err_barangay"></span>
                                </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                {{Form::label('lessormobileno',__('Mobile No.'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                    {{Form::text('lessor_mobile',$data->lessor_mobile,array('class'=>'form-control'))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                {{Form::label('lessoremail',__('Email Address'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                    {{Form::text('lessor_email',$data->lessor_email,array('class'=>'form-control'))}}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                {{Form::label('monthlyrental',__('Monthly Rental'),array('class'=>'form-label')) }}
                                 <div class="form-icon-user currency">
                                    {{Form::text('monthlyrental',$data->monthlyrental,array('class'=>'form-control numeric','placeholder'=>'0.00'))}}
                                    <div class="currency-sign"><span>Php</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        


         <!--------------- Business Activity start Here----------------> 
         <div class="accordion accordion-flush" id="accordionFlushExample4">
            <div class="accordion-item">
                <h6 class="accordion-header" id="flush-headingfour">
                  <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefour" aria-expanded="false" aria-controls="flush-headingfour">
                     <h6 class="sub-title accordiantitle">{{__('Business Activity')}}</h6>
                 </button>
             </h6>

                <div class="row" style="width: 100%; margin: 0px;padding-bottom: 20px;">
                    <div class="row field-requirement-details-status">
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            {{Form::label('subclass_id',__('Line Of Bussiness'),['class'=>'form-label'])}}
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            {{Form::label('taxable_item_name',__('No of Units'),['class'=>'form-label'])}}
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            {{Form::label('taxable_item_qty',__('Capitalization'),['class'=>'form-label numeric'])}}
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            {{Form::label('capital_investment',__('Essential'),['class'=>'form-label'])}}
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            {{Form::label('date_started',__('Non Essential'),['class'=>'form-label'])}}
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1">
                            <input type="button" id="btn_addmore_activity" class="btn btn-success" value="Add More" style="padding: 0.4rem 0.76rem !important;">
                        </div>
                    </div>
                    <span class="busiactivityDetails activity-details" id="busiactivityDetails">
                        <span class="validate-err" id="err_activitydetailserror"></span>
                        @php $i=0; @endphp
                        @foreach($arrNature as $key=>$val)
                            <div class="row removeactivitydata pt10">
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                         {{ Form::hidden('activityid',$val->id, array('id' => 'activityid')) }}    
                                        {{ Form::select('natureofbussiness[]',$arrSubclasses, $val->natureofbussiness, array('class' => 'form-control naofbussi natureofbussiness','id'=>'psic_subclass_id'.$i)) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                           {{Form::text('noofunits[]',$val->noofunits,array('class'=>'form-control','required'=>'required'))}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                           {{Form::text('capitalization[]',$val->capitalization,array('class'=>'form-control numeric','required'=>'required'))}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user" >
                                          {{Form::text('essestial[]',$val->essestial,array('class'=>'form-control','required'=>'required'))}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <div class="form-group">
                                        <div class="form-icon-user">
                                            {{Form::text('nonessestial[]',$val->nonessestial,array('class'=>'form-control','required'=>'required'))}}
                                        </div>
                                    </div>
                                </div>
                                @if($i>0)
                                    <div class="col-sm-1">
                                        <input type="button" name="btn_cancel_activity" class="btn btn-success btn_cancel_activity" value="Delete" style="padding: 0.4rem 1rem !important;">
                                    </div>
                                @endif
                                @php $i++; @endphp
                            </div>
                            
                        @endforeach
                    </span>
                </div>
          </div>
       </div>
      <!--------------- Business Activity End Here---------------->   
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
        <i class="fa fa-save icon"></i>
        <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
    </div>
    <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
</div>
{{Form::close()}}
<div id="hidenactivityHtml" class="hide">
    <div class="removeactivitydata row pt10">
        <div class="col-lg-3 col-md-3 col-sm-3">
            <div class="form-group">
                <div class="form-icon-user">
                {{ Form::select('natureofbussiness[]',$arrSubclasses, '', array('class' => 'form-control naofbussi natureofbussiness','required'=>'required','id'=>'natureofbussiness')) }}
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                    {{Form::text('noofunits[]','',array('class'=>'form-control','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                    {{Form::text('capitalization[]','',array('class'=>'form-control numeric','required'=>'required'))}}
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                    {{Form::text('essestial[]','',array('class'=>'form-control','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                   {{Form::text('nonessestial[]','',array('class'=>'form-control','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-sm-1">
            <input type="button" name="btn_cancel" class="btn btn-success btn_cancel_activity" cid="" value="Delete" style="padding: 0.4rem 1rem !important;">
        </div>
    </div>
</div>
<script src="{{ asset('js/add_applicant.js') }}"></script>
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script type="text/javascript">
   $(document).ready(function(){
    $('.numeric').numeric();
});
</script>
