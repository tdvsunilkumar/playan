{{ Form::open(array('url' => 'barangay','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
            <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('brgy_code', __('Barangay No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('brgy_code') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('brgy_code', $data->brgy_code, array('class' => 'form-control','maxlength'=>'5','required'=>'required','maxlength'=>'20')) }}
                                </div>
                                <span class="validate-err" id="err_brgy_code"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                                {{ Form::label('reg_no', __('Region'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    
                                <div class="form-icon-user">
                                    {{ Form::select('reg_no',$nofbusscode,$data->reg_no, array('class' => 'form-control select3','id'=>'reg_no','required'=>'required')) }}
                                    
                                </div>
                                <span class="validate-err" id="err_reg_no"></span>
                            </div>
                        </div> 
                        <div class="col-md-4">
                           <div class="form-group">
                                {{ Form::label('prov_no', __('Province'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    
                                <div class="form-icon-user">
                                    {{ Form::select('prov_no',$arrbbaCode,$data->prov_no, array('class' => 'form-control select3','id'=>'prov_no','required'=>'required')) }}
                                    
                                </div>
                                <span class="validate-err" id="err_prov_no"></span>
                            </div>
                        </div> 
                        <div class="col-md-4">
                           <div class="form-group">
                                {{ Form::label('mun_no', __('Municipality'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    
                                <div class="form-icon-user">
                                    {{ Form::select('mun_no',$arrMunCode,$data->mun_no, array('class' => 'form-control select3','id'=>'mun_no','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_mun_no"></span>
                            </div>
                        </div>        
                        <div class="col-md-8">
                            <div class="form-group">
                                {{ Form::label('brgy_name', __('Barangay Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('brgy_name') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::text('brgy_name', $data->brgy_name, array('class' => 'form-control','required'=>'required')) }}
                                </div>
                                 <span class="validate-err" id="err_brgy_name"></span>
                            </div>
                        </div>
                    
                     
                       <!--  <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('brgy_area_code', __('Area Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('brgy_area_code') }}</span>
                                <div class="form-icon-user">
                                     {{ Form::text('brgy_area_code', $data->brgy_area_code, array('class' => 'form-control','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_brgy_area_code"></span>
                            </div>
                        </div> -->

                        
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('brgy_office', __('Office Name'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('brgy_office') }}</span>
                                <div class="form-icon-user">
                                   {{ Form::text('brgy_office', $data->brgy_office, array('class' => 'form-control')) }}
                                </div>
                                 <span class="validate-err" id="err_brgy_office"></span>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                           <div class="form-group">
                                {{ Form::label('dist_code', __('District'),['class'=>'form-label']) }}
                                <div class="form-icon-user">
                                    {{ Form::select('dist_code',$districtCodes,$data->dist_code, array('class' => 'form-control select3','id'=>'dist_code')) }}
                                    
                                </div>
                                <span class="validate-err" id="err_dist_code"></span>
                            </div>
                        </div> 
                        <!-- <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('dist_code', __('Dist Code'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('brgy_office') }}</span>
                                <div class="form-icon-user">
                                   {{ Form::text('dist_code', $data->dist_code, array('class' => 'form-control')) }}
                                </div>
                                 <span class="validate-err" id="err_brgy_office"></span>
                            </div>
                        </div> -->
                        
                        <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('brgy_display_for_bplo', __('BPLO System'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('brgy_display_for_bplo',array('0' =>'No','1' =>'Yes'), $data->brgy_display_for_bplo, array('class' => 'form-control spp_type','id'=>'brgy_display_for_bplo','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_brgy_display_for_bplo"></span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('brgy_display_for_rpt', __('RPT System'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('brgy_display_for_rpt',array('0' =>'No','1' =>'Yes'), $data->    brgy_display_for_rpt, array('class' => 'form-control spp_type','id'=>'  brgy_display_for_rpt','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_brgy_display_for_rpt"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('uacs_code', __('UACS Code'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('uacs_code', $data->uacs_code, array('class' => 'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_uacs_code"></span>
                </div>
            </div>
            <!-- <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('brgy_display_for_rpt', __('Real Property Tax'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('brgy_display_for_rpt',array('' =>'Please Select','1' =>'Yes','0' =>'No'), $data->brgy_display_for_rpt, array('class' => 'form-control spp_type','id'=>'brgy_display_for_rpt','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bsf_tax_schedule"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('brgy_display_for_rpt_locgroup', __('Profile Municipality'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('brgy_display_for_rpt_locgroup',array('' =>'Please Select','1' =>'Yes','0' =>'No'), $data->brgy_display_for_rpt_locgroup, array('class' => 'form-control spp_type','id'=>'brgy_display_for_rpt_locgroup','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bsf_tax_schedule"></span>
                </div>
            </div> -->
                    </div>
                    <div class="modal-footer">
                        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                            <i class="fa fa-save icon"></i>
                            <input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
                        </div>
                        <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
                    </div>
            </div>
    {{Form::close()}}
    
   <script src="{{ asset('js/ajax_validation.js') }}"></script> 
   <script src="{{ asset('js/addBarangay.js') }}"></script>
   <script type="text/javascript">
    $(document).ready(function () {
    $('#savechanges').click(function (e) {
        e.preventDefault();
        $(".validate-err").html('');
        var data = $("form").serialize();
        var obj={};
        for(var key in data){
            obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :DIR+'barangay/formValidation', // json datasource
            type: "POST", 
            data: $('#submitLandUnitValueForm').serialize(),
            dataType: 'json',
            success: function(html){
                if(html.ESTATUS){
                  
                    $("#err_"+html.field_name).html(html.error);
                    $("."+html.field_name).focus();
                }else{
                    Swal.fire({
                    title: "Are you sure?",
                    html: '<span style="color: red;">This will save the current changes.</span>',
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
                    $('#submitLandUnitValueForm').submit();
                    form.submit();
                    location.reload();
                    } else {
                        console.log("Form submission canceled");
                    }
                });
                }
            }
        })
     
   });
});


</script>  