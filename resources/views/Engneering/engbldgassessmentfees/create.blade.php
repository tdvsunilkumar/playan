{{ Form::open(array('url' => 'engbldgassessmentfees')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
  
   
    <div class="modal-body">

         <div class="row">
            <div class="col-md-12">
                  <div class="form-group">
                        {{ Form::label('ebpa_id', __('Permit Owner'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                        <div class="form-icon-user">
                            {{ Form::select('ebpa_id',$arrClassCode,$data->ebpa_id, array('class' => 'form-control select3 
                            ','id'=>'ebpa_id','required'=>'required')) }}
                        </div>
                        <span class="validate-err" id="err_ebost_id"></span>
                    </div>
            </div>       
           
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <table style="border: 1px solid;text-align: center;">
                    <div class="form-group">
                        <tr style="border: 1px solid;">
                        <th colspan="2">Land Use / Zoning</th>
                        </tr>
                        <tr style="border: 1px solid;">
                            
                        <td>Amount Due</td><td>
                            <div class="form-icon-user currency">
                                {{Form::number('ebaf_zoning_amount',$data->ebaf_zoning_amount,array('class'=>'form-control','step'=>'0.01'))}}
                            <div class="currency-sign"><span>Php</span></div>
                            </div>
                            </td>
                        </tr>
                         <tr style="border: 1px solid;">
                        <td>Assessed By</td><td>{{Form::text('ebaf_zoning_assessed_by',$data->ebaf_zoning_assessed_by,array('class'=>'form-control'))}}</td>
                        </tr>
                       
                        <tr style="border: 1px solid;">
                        <td>O.R Number</td><td>{{Form::text('ebaf_zoning_date_paid',$data->ebaf_zoning_date_paid,array('class'=>'form-control'))}}</td>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Date Paid</td><td>{{Form::date('ebaf_zoning_date_paid',$data->ebaf_zoning_date_paid,array('class'=>'form-control'))}}</td>
                        </tr>
                       
                    </div>
                    
                </table>
            </div>
            

            <div class="col-lg-4 col-md-4 col-sm-4">
                <table style="border: 1px solid;text-align: center;">
                    <div class="form-group">
                        <tr style="border: 1px solid;">
                        <th colspan="2">Line and Grade</th>
                        </tr>
                        <tr style="border: 1px solid;">

                        <td>Amount Due</td><td>
                            <div class="form-icon-user currency">{{Form::number('ebaf_linegrade_amount',$data->ebaf_linegrade_amount,array('class'=>'form-control','step'=>'0.01'))}}
                            <div class="currency-sign"><span>Php</span></div>
                            </div>
                            </td>
                        </tr>
                         <tr style="border: 1px solid;">
                        <td>Assessed By</td><td>{{Form::text('ebaf_linegrade_assessed_by',$data->ebaf_linegrade_assessed_by,array('class'=>'form-control'))}}</td>
                        </tr>
                        
                        <tr style="border: 1px solid;">
                        <td>O.R Number</td><td>{{Form::text('ebaf_linegrade_or_no',$data->ebaf_linegrade_or_no,array('class'=>'form-control'))}}</td>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Date Paid</td><td>{{Form::date('ebaf_linegrade_date_paid',$data->ebaf_linegrade_date_paid,array('class'=>'form-control'))}}</td>
                        </tr>
                       
                    </div>
                    
                </table>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4">
                <table style="border: 1px solid;text-align: center;">
                    <div class="form-group">
                        <tr style="border: 1px solid;">
                        <th colspan="2">Building</th>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Amount Due</td><td>
                            <div class="form-icon-user currency">
                                {{Form::number('ebaf_bldg_amount',$data->ebaf_bldg_amount,array('class'=>'form-control','step'=>'0.01'))}}
                            <div class="currency-sign"><span>Php</span></div>
                            </div>
                            </td>
                        </tr>
                         <tr style="border: 1px solid;">
                        <td>Assessed By</td><td>{{Form::text('ebaf_bldg_assessed_by',$data->ebaf_bldg_assessed_by,array('class'=>'form-control'))}}</td>
                        </tr>
                       
                        <tr style="border: 1px solid;">
                        <td>O.R Number</td><td>{{Form::text('ebaf_bldg_or_no',$data->ebaf_bldg_or_no,array('class'=>'form-control'))}}</td>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Date Paid</td><td>{{Form::date('ebaf_bldg_date_paid',$data->ebaf_bldg_date_paid,array('class'=>'form-control'))}}</td>
                        </tr>
                       
                    </div>
                    
                </table>
            </div>
        </div>
       
       <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <table style="border: 1px solid;text-align: center;">
                    <div class="form-group">
                        <tr style="border: 1px solid;">
                        <th colspan="2">Plumbing</th>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Amount Due</td><td>
                            <div class="form-icon-user currency">

                            {{Form::number('ebaf_plum_amount',$data->ebaf_plum_amount,array('class'=>'form-control','step'=>'0.01'))}}
                            <div class="currency-sign"><span>Php</span></div>
                            </div>
                            </td>
                        </tr>
                         <tr style="border: 1px solid;">
                        <td>Assessed By</td><td>{{Form::text('ebaf_plum_assessed_by',$data->ebaf_plum_assessed_by,array('class'=>'form-control'))}}</td>
                        </tr>
                       
                        <tr style="border: 1px solid;">
                        <td>O.R Number</td><td>{{Form::text('ebaf_plum_or_no',$data->ebaf_plum_or_no,array('class'=>'form-control'))}}</td>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Date Paid</td><td>{{Form::date('ebaf_plum_date_paid',$data->ebaf_plum_date_paid,array('class'=>'form-control'))}}</td>
                        </tr>
                       
                    </div>
                    
                </table>
            </div>
            

            <div class="col-lg-4 col-md-4 col-sm-4">
                <table style="border: 1px solid;text-align: center;">
                    <div class="form-group">
                        <tr style="border: 1px solid;">
                        <th colspan="2">Electrical</th>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Amount Due</td><td>
                            <div class="form-icon-user currency">
                                {{Form::number('ebaf_elec_amount',$data->ebaf_elec_amount,array('class'=>'form-control','step'=>'0.01'))}}
                            <div class="currency-sign"><span>Php</span></div>
                            </div>
                            </td>
                        </tr>
                         <tr style="border: 1px solid;">
                        <td>Assessed By</td><td>{{Form::text('ebaf_elec_assessed_by',$data->ebaf_elec_assessed_by,array('class'=>'form-control'))}}</td>
                        </tr>
                       
                        <tr style="border: 1px solid;">
                        <td>O.R Number</td><td>{{Form::text('ebaf_elec_or_no',$data->ebaf_elec_or_no,array('class'=>'form-control'))}}</td>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Date Paid</td><td>{{Form::date('ebaf_elec_date_paid',$data->ebaf_elec_date_paid,array('class'=>'form-control'))}}</td>
                        </tr>
                       
                    </div>
                    
                </table>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4">
                <table style="border: 1px solid;text-align: center;">
                    <div class="form-group">
                        <tr style="border: 1px solid;">
                        <th colspan="2">Mechanical</th>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Amount Due</td><td>
                            <div class="form-icon-user currency">
                                {{Form::number('ebaf_mech_amount',$data->ebaf_mech_amount,array('class'=>'form-control','step'=>'0.01'))}}
                            <div class="currency-sign"><span>Php</span></div>
                            </div>
                            </td>
                        </tr>
                         <tr style="border: 1px solid;">
                        <td>Assessed By</td><td>{{Form::text('ebaf_mech_assessed_by',$data->ebaf_mech_assessed_by,array('class'=>'form-control'))}}</td>
                        </tr>
                        
                        <tr style="border: 1px solid;">
                        <td>O.R Number</td><td>{{Form::text('ebaf_mech_or_no',$data->ebaf_mech_or_no,array('class'=>'form-control'))}}</td>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Date Paid</td><td>{{Form::date('ebaf_mech_date_paid',$data->ebaf_mech_date_paid,array('class'=>'form-control'))}}</td>
                        </tr>
                       
                    </div>
                    
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <table style="border: 1px solid;text-align: center;">
                    <div class="form-group">
                        <tr style="border: 1px solid;">
                        <th colspan="2">Others</th>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Amount Due</td><td>
                            <div class="form-icon-user currency">
                                {{Form::number('ebaf_others_amount',$data->ebaf_others_amount,array('class'=>'form-control','step'=>'0.01'))}}
                            <div class="currency-sign"><span>Php</span></div>
                            </div>
                            </td>
                        </tr>
                         <tr style="border: 1px solid;">
                        <td>Assessed By</td><td>{{Form::text('ebaf_others_assessed_by',$data->ebaf_others_assessed_by,array('class'=>'form-control'))}}</td>
                        </tr>
                        
                        <tr style="border: 1px solid;">
                        <td>O.R Number</td><td>{{Form::text('ebaf_others_or_no',$data->ebaf_others_or_no,array('class'=>'form-control'))}}</td>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Date Paid</td><td>{{Form::date('ebaf_others_date_paid',$data->ebaf_others_date_paid,array('class'=>'form-control'))}}</td>
                        </tr>
                       
                    </div>
                    
                </table>
            </div>
            
            
            <div class="col-lg-4 col-md-4 col-sm-4">
                <table style="border: 1px solid;text-align: center;">
                    <div class="form-group">
                        <tr style="border: 1px solid;">
                        <th colspan="2">Total</th>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Amount Due<span class="text-danger">*</span></td><td>
                            <div class="form-icon-user currency">
                                {{Form::number('ebaf_total_amount',$data->ebaf_total_amount,array('class'=>'form-control','step'=>'0.01','required'=>'required'))}}
                                <div class="currency-sign"><span>Php</span></div>
                            </div>
                            </td>
                        </tr>
                         <tr style="border: 1px solid;">
                        <td>Assessed By<span class="text-danger">*</span></td><td>{{Form::text('ebaf_total_assessed_by',$data->ebaf_total_assessed_by,array('class'=>'form-control','required'=>'required'))}}</td>
                        </tr>
                        
                        <tr style="border: 1px solid;">
                        <td>O.R Number<span class="text-danger">*</span></td><td>{{Form::text('ebaf_total_or_no',$data->ebaf_total_or_no,array('class'=>'form-control','required'=>'required'))}}</td>
                        </tr>
                        <tr style="border: 1px solid;">
                        <td>Date Paid<span class="text-danger">*</span></td><td>{{Form::date('ebaf_total_date_paid',$data->ebaf_total_date_paid,array('class'=>'form-control','required'=>'required'))}}</td>
                        </tr>
                       
                    </div>
                    
                </table>
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
<script src="{{ asset('js/ajax_validation.js') }}"></script>




