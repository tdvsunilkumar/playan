{{ Form::open(array('url' => 'business-permit/business-owners/store','class'=>'formDtls')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<div class="modal-body">
                    <div class="row">
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_first_name', __('First Name'),['class'=>'form-label']) }}<!-- <span class="text-danger">*</span> -->
                                <span class="validate-err">{{ $errors->first('p_first_name') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('p_first_name', $data->p_first_name, array('class' => 'form-control')) }}
                                </div>
                                <span class="validate-err" id="err_p_first_name"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_middle_name', __('Middle Name'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('p_middle_name') }}</span>
                                <div class="form-icon-user">
                                   {{ Form::text('p_middle_name', $data->p_middle_name, array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_family_name', __('Last Name'),['class'=>'form-label']) }}<!-- <span class="text-danger">*</span> -->
                                <span class="validate-err">{{ $errors->first('p_family_name') }}</span>
                                <div class="form-icon-user">
                                   {{ Form::text('p_family_name', $data->p_family_name, array('class' => 'form-control')) }}
                                </div>
                                <span class="validate-err" id="err_p_family_name"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                         <!-- <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_code', __('Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('p_code') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('p_code', $data->p_code, array('class' => 'form-control','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_p_code"></span>
                            </div>
                        </div> -->
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_address_house_lot_no', __('Lot No'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('p_address_house_lot_no') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('p_address_house_lot_no', $data->p_address_house_lot_no, array('class' => 'form-control')) }}
                                </div>
                                <span class="validate-err" id="err_p_address_house_lot_no"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_address_street_name', __('Street Name'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('p_address_street_name') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('p_address_street_name', $data->p_address_street_name, array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>

                        
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_address_subdivision', __('Subdivision'),['class'=>'form-label']) }}<!-- <span class="text-danger">*</span> -->
                                <span class="validate-err">{{ $errors->first('p_address_subdivision') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('p_address_subdivision', $data->p_address_subdivision, array('class' => 'form-control')) }}
                                </div>
                                <span class="validate-err" id="err_p_address_subdivision"></span>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="row">
                         
                         <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('brgy_code', __('Barangay,Municipality,Province,Region'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('brgy_code') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('brgy_code',$arrBarangay,$data->brgy_code, array('class' => 'form-control','id'=>'barangay_id','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_brgy_code"></span>
                            </div>
                        </div>
                         <!-- <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('brgy_name', __('Barangay Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('brgy_name') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('brgy_name', $data->brgy_name, array('class' => 'form-control','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_brgy_name"></span>
                            </div>
                        </div>
                    </div> -->
                   <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_telephone_no', __('Telephone No.'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('p_telephone_no') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::number('p_telephone_no', $data->p_telephone_no, array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_mobile_no', __('Mobile No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('p_mobile_no') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::number('p_mobile_no', $data->p_mobile_no, array('class' => 'form-control','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_p_mobile_no"></span>
                            </div>
                        </div>
                        
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_fax_no', __('Fax no'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('p_fax_no') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::number('p_fax_no', $data->p_fax_no, array('class' => 'form-control')) }}
                                </div>
                                <span class="validate-err" id="err_p_fax_no"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_tin_no', __('TIN No'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('p_tin_no') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('p_tin_no', $data->p_tin_no, array('class' => 'form-control','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_p_tin_no"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_email_address', __('Email Id'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('p_email_address') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::email('p_email_address', $data->p_email_address, array('class' => 'form-control')) }}
                                </div>
                                <span class="validate-err" id="err_p_email_address"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('c_code', __('Country'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                    <div class="form-icon-user">
                                       {{ Form::select('c_code',$arrCountryCode,$data->c_code, array('class' => 'form-control select3','id'=>'c_code','required'=>'required')) }}
                                    </div>
                                    <span class="validate-err" id="err_bsf_tax_schedule"></span>
                                </div>
                            </div>
                         <!-- <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_job_position', __('Job Position'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('p_job_position') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('p_job_position', $data->p_job_position, array('class' => 'form-control','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_p_job_position"></span>
                            </div>
                        </div>-->
                    </div> 
                     <div class="row">
                           
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_gender', __('Gender'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('section_id') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::select('p_gender',array('' =>'Select Gender','male' =>'Male','female' =>'Female'), $data->p_gender, array('class' => 'form-control','id'=>'p_gender','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_p_gender"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_date_of_birth', __('Date Of Birth'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('section_id') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::date('p_date_of_birth', $data->p_date_of_birth, array('class' => 'form-control','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_p_date_of_birth"></span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ba_code', __('BA Code'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('ba_code') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('ba_code', $data->ba_code, array('class' => 'form-control')) }}
                                </div>
                                <span class="validate-err" id="err_ba_code"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('ba_business_name', __('Business Name'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('ba_business_name') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('ba_business_name', $data->ba_business_name, array('class' => 'form-control')) }}
                                </div>
                                <span class="validate-err" id="err_ba_business_name"></span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('p_place_of_work', __('Place Of Work'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('section_id') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('p_place_of_work', $data->p_place_of_work, array('class' => 'form-control','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_p_place_of_work"></span>
                            </div>
                        </div>
                        
                         <div class="col-md-2">
                                <div class="form-group">
                                    {{ Form::label('p_is_employee', __('Is Employee'),['class'=>'form-label']) }} {{ Form::checkbox('p_is_employee','1', ($data->p_is_employee)?true:false, array('id'=>'id','class'=>'form-check-input code')) }}
                                    <div class="form-icon-user">
                                       
                                    </div>
                                    
                                    <span class="validate-err" id="err_bbef_code"></span>
                                </div>
                        </div>
                    </div> -->

                    

                    @if(($data->id)>0)
                      <table class="table datatable">
                            <thead>
                            <tr>
                                 <th>{{__('Business Name')}}</th>
                                <th>{{__('Date Registered')}}</th>
                                <th>{{__('Status')}}</th>
                               
                            </tr>
                            
                            </thead>
                            <tbody>
                            @if(!$arrNature)
                             <tr class="font-style"> 
                                    <td colspan="3"><center>No data</center></td>
                                </tr> 
                            @else
                            @foreach($arrNature as $val)
                             
                              
                                  
                                
                                <tr class="font-style"> 
                                    <td>{{$val['ba_business_name']}}</td>
                                    <td>{{$val['created_at']}}</td>
                                    <td> @if($val['app_type_id']==1)
                                               New
                                            @elseif($val['app_type_id']==2)
                                                Renew
                                             @elseif($val['app_type_id']==3)
                                                Retire    
                                        @endif</td>
                                </tr>
                               
                             @endforeach
                               @endif  
                            </tbody>
                        </table>

                    @endif

                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary">
                    </div>
        </div>    
    {{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script> 
<script src="{{ asset('js/addProfile.js') }}"></script>  
           