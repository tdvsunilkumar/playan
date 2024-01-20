<?php $version = config('constants.version'); ?>
{{ Form::open(array('url' => 'profilemunicipalitie','id'=>'submitLandUnitValueForm')) }}
   
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('rptsystem',$data->mun_display_for_rpt, array('id' => 'rptsystem')) }}
  
   <style>
     .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 1000px;
        pointer-events: auto;
        background-color: #ffffff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 15px;
        outline: 0;
        float: left;
       
  }
        #flush-collapsetwo{
            padding-bottom: 20px;
        }
        .pt10{
            padding-top:10px;
        }
    }
    </style>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('mun_no', __('Number'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::text('mun_no', $data->mun_no, array('class' => 'form-control','required'=>'required','maxlength'=>'20')) }}
                    </div>
                    <span class="validate-err" id="err_mun_no"></span>
                </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('reg_no', __('Region'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('reg_no',$nofbusscode,$data->reg_no, array('class' => 'form-control select3 uacs_change','id'=>'reg_no','required'=>'required')) }}
                        
                    </div>
                    <span class="validate-err" id="err_reg_no"></span>
                </div>
            </div> 
            <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('prov_no', __('Province'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::select('prov_no',$arrbbaCode,$data->prov_no, array('class' => 'form-control select3 uacs_change','id'=>'prov_no','required'=>'required')) }}
                        
                    </div>
                    <span class="validate-err" id="err_prov_no"></span>
                </div>
            </div> 
            <div class="col-md-12">
               <div class="form-group">
                    {{ Form::label('mun_desc', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
        
                    <div class="form-icon-user">
                        {{ Form::text('mun_desc', $data->mun_desc, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_mun_desc"></span>
                </div>
            </div>
           
            <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('mun_zip_code', __('Zip Code'),['class'=>'form-label']) }}
        
                    <div class="form-icon-user">
                        {{ Form::text('mun_zip_code', $data->mun_zip_code, array('class' => 'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('mun_area_code', __('Area Code'),['class'=>'form-label']) }}
        
                    <div class="form-icon-user">
                        {{ Form::text('mun_area_code', $data->mun_area_code, array('class' => 'form-control')) }}
                    </div>
                    <span class="validate-err" id="err_bbef_code"></span>
                </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                    {{ Form::label('uacs_code', __('UACS Code'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('uacs_code', $data->uacs_code, array('class' => 'form-control uacs_change')) }}
                    </div>
                    <span class="validate-err" id="err_uacs_code"></span>
                </div>
            </div>
            
            
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('mun_display_for_bplo', __('BPLO System'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('mun_display_for_bplo',array('0' =>'No','1' =>'Yes'), $data->mun_display_for_bplo, array('class' => 'form-control spp_type','id'=>'mun_display_for_bplo','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bsf_tax_schedule"></span>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('mun_display_for_rpt', __('RPT System'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('mun_display_for_rpt',array('0' =>'No','1' =>'Yes'), $data->mun_display_for_rpt, array('class' => 'form-control spp_type','id'=>'mun_display_for_rpt','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_bsf_tax_schedule"></span>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('mun_display_for_welfare', __('Social Welfare'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('mun_display_for_welfare',array('0' =>'No','1' =>'Yes'), $data->mun_display_for_welfare, array('class' => 'form-control spp_type','id'=>'mun_display_for_welfare','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_mun_display_for_welfare"></span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('mun_display_for_accounting', __('Accounting'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('mun_display_for_accounting',array('0' =>'No','1' =>'Yes'), $data->mun_display_for_accounting, array('class' => 'form-control spp_type','id'=>'mun_display_for_accounting','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_mun_display_for_accountinge"></span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('mun_display_for_economic', __('Economic Investment '),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('mun_display_for_economic',array('0' =>'No','1' =>'Yes'), $data->mun_display_for_economic, array('class' => 'form-control spp_type','id'=>'mun_display_for_economic','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_mun_display_for_economic"></span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('mun_display_for_cpdo', __('Planning & Develop'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('mun_display_for_cpdo',array('0' =>'No','1' =>'Yes'), $data->mun_display_for_cpdo, array('class' => 'form-control spp_type','id'=>'mun_display_for_cpdo','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_mun_display_for_cpdo"></span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('mun_display_for_eng', __('Engineering'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('mun_display_for_eng',array('0' =>'No','1' =>'Yes'), $data->mun_display_for_eng, array('class' => 'form-control spp_type','id'=>'mun_display_for_eng','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_mun_display_for_eng"></span>
                </div>
            </div>
             <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('mun_display_for_occupancy', __('Occupancy'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                       {{ Form::select('mun_display_for_occupancy',array('0' =>'No','1' =>'Yes'), $data->mun_display_for_occupancy, array('class' => 'form-control spp_type','id'=>'mun_display_for_occupancy','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_mun_display_for_occupancy"></span>
                </div>
            </div>
        </div>
        <!--- Start Departiment wise locality --->
        <?php //echo "<pre>"; print_r($arrDept); exit;
        foreach ($arrDept as $key => $localitydata) { 
            $hideClass="hide";
            if($data->mun_display_for_bplo==1 && $key==1){
                $hideClass="";
            }
            if($data->mun_display_for_rpt==1 && $key==2){
                $hideClass="";
            }
            if($data->mun_display_for_economic==1 && $key==5){
                $hideClass="";
            }
            if($data->mun_display_for_welfare==1 && $key==3){
                $hideClass="";
            }
            if($data->mun_display_for_accounting==1 && $key==4){
                $hideClass="";
            }
            if($data->mun_display_for_cpdo==1 && $key==6){
                $hideClass="";
            }
            if($data->mun_display_for_eng==1 && $key==7){
                $hideClass="";
            }
            if($data->mun_display_for_occupancy==1 && $key==8){
                $hideClass="";
            }
            
            // if Social welfare
            if ($key==3) {
                ?>
                <div class="row pt10 <?=$hideClass?>" id="divDetaprtment_{{$key}}">  
                    <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample<?=$key?>">  
                        <div class="accordion accordion-flush">
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="flush-headingone<?=$key?>">
                                    <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone<?=$key?>" aria-expanded="false" aria-controls="flush-headingtwo">
                                        <h6 class="sub-title accordiantitle">{{$localitydata['dept_name']}}: {{__("Locality")}}</h6>
                                    </button>
                                </h6>
                                <div id="flush-collapseone<?=$key?>" class="accordion-collapse collapse" aria-labelledby="flush-headingone<?=$key?>" data-bs-parent="#accordionFlushExample<?=$key?>">
                                    <div class="basicinfodiv" id="locationdetails1">
                                        <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        {{ Form::label('loc_local_code'.$key, __('Local Code'),['class'=>'form-label']) }}<span class="text-danger starrequired">*</span>
                                                        <span class="validate-err">{{ $errors->first('loc_local_code') }}</span>
                                                        <div class="form-icon-user">
                                                            {{ Form::text('loc_local_code'.$key, $localitydata['loc_local_code'], array('class' => 'form-control mandatoryclass','readonly','maxlength'=>'20')) }}
                                                        </div>
                                                        <span class="validate-err" id="err_loc_local_code{{$key}}"></span>
                                                    </div>
                                                </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('loc_telephone_no'.$key, __('Telephone No(s)'),['class'=>'form-label']) }}<span class="text-danger starrequired">*</span>
                                                    <span class="validate-err">{{ $errors->first('loc_telephone_no') }}</span>
                                                    <div class="form-icon-user">
                                                        {{ Form::text('loc_telephone_no'.$key, $localitydata['loc_telephone_no'], array('class' => 'form-control mandatoryclass','maxlength'=>'20')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_loc_telephone_no{{$key}}"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('loc_fax_no'.$key, __('Fax no(s)'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('loc_fax_no') }}</span>
                                                    <div class="form-icon-user">
                                                        {{ Form::text('loc_fax_no'.$key, $localitydata['loc_fax_no'], array('class' => 'form-control','maxlength'=>'20')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_loc_fax_no{{$key}}"></span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-8 col-md-8 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('loc_welfare_head_id'.$key, __('Social Welfare Head'),['class'=>'form-label']) }}<span class="text-danger starrequired">*</span>
                                                    <span class="validate-err">{{ $errors->first('loc_welfare_head_id') }}</span>
                                                    <div class="form-icon-user">
                                                        {{ Form::select('loc_welfare_head_id'.$key,$arrHrEmpCode,$localitydata['loc_welfare_head_id'], array('class' => 'form-control forSelect3 mandatoryclass getPosition','id'=>'loc_welfare_head_id'.$key,'pname'=>'loc_welfare_head_position'.$key)) }}
                                                    </div>
                                                    <span class="validate-err" id="err_loc_welfare_head_id{{$key}}"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('loc_welfare_head_position'.$key, __('Social Welfare Head Position'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('loc_welfare_head_position') }}</span>
                                                    <div class="form-icon-user">
                                                        {{ Form::text('loc_welfare_head_position'.$key, $localitydata['loc_welfare_head_position'], array('class' => 'form-control','maxlength'=>'20')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_loc_welfare_head_position{{$key}}"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                // end social welfare
            } else {
            ?>
            <div class="row pt10 <?=$hideClass?>" id="divDetaprtment_{{$key}}">  
                <div class="col-lg-12 col-md-12 col-sm-12"  id="accordionFlushExample<?=$key?>">  
                    <div class="accordion accordion-flush">
                        <div class="accordion-item">
                            <h6 class="accordion-header" id="flush-headingone<?=$key?>">
                                <button class="accordion-button collapsed btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseone<?=$key?>" aria-expanded="false" aria-controls="flush-headingtwo">
                                    <h6 class="sub-title accordiantitle">{{$localitydata['dept_name']}}: {{__("Locality")}}</h6>
                                </button>
                            </h6>
                            <div id="flush-collapseone<?=$key?>" class="accordion-collapse collapse" aria-labelledby="flush-headingone<?=$key?>" data-bs-parent="#accordionFlushExample<?=$key?>">
                                <div class="basicinfodiv" id="locationdetails1">
                                    <div class="row"><?php
                                        if($key==1){ ?>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('loc_local_code'.$key, __('Local Code'),['class'=>'form-label']) }}<span class="text-danger starrequired">*</span>
                                                    <span class="validate-err">{{ $errors->first('loc_local_code') }}</span>
                                                    <div class="form-icon-user">
                                                         {{ Form::text('loc_local_code'.$key, $localitydata['loc_local_code'], array('class' => 'form-control mandatoryclass','maxlength'=>'20')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_loc_local_code{{$key}}"></span>
                                                </div>
                                            </div><?php  
                                        } ?>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                {{ Form::label('loc_telephone_no'.$key, __('Telephone No(s)'),['class'=>'form-label']) }}<span class="text-danger starrequired">*</span>
                                                <span class="validate-err">{{ $errors->first('loc_telephone_no') }}</span>
                                                <div class="form-icon-user">
                                                     {{ Form::text('loc_telephone_no'.$key, $localitydata['loc_telephone_no'], array('class' => 'form-control mandatoryclass','maxlength'=>'20')) }}
                                                </div>
                                                <span class="validate-err" id="err_loc_telephone_no{{$key}}"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                {{ Form::label('loc_fax_no'.$key, __('Fax no(s)'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('loc_fax_no') }}</span>
                                                <div class="form-icon-user">
                                                     {{ Form::text('loc_fax_no'.$key, $localitydata['loc_fax_no'], array('class' => 'form-control','maxlength'=>'20')) }}
                                                </div>
                                                <span class="validate-err" id="err_loc_fax_no{{$key}}"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                {{ Form::label('loc_mayor_id'.$key, __('Mayor'),['class'=>'form-label']) }}<span class="text-danger starrequired">*</span>
                                                <span class="validate-err">{{ $errors->first('loc_mayor_id') }}</span>
                                                <div class="form-icon-user">
                                                    <div class="form-icon-user">
                                                    {{ Form::select('loc_mayor_id'.$key,$arrHrEmpCode,$localitydata['loc_mayor_id'], array('class' => 'form-control forSelect3 mandatoryclass','id'=>'loc_mayor_id'.$key)) }}
                                                    
                                                    </div>
                                                </div>
                                                <span class="validate-err" id="err_loc_mayor_id{{$key}}"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                {{ Form::label('loc_administrator_id'.$key, __('Administrator'),['class'=>'form-label']) }}
                                                <span class="validate-err">{{ $errors->first('loc_administrator_id') }}</span>
                                                <div class="form-icon-user">
                                                      {{ Form::select('loc_administrator_id'.$key,$arrHrEmpCode,$localitydata['loc_administrator_id'], array('class' => 'form-control forSelect3','id'=>'loc_administrator_id'.$key)) }}
                                                </div>
                                                <span class="validate-err" id="err_loc_administrator_id{{$key}}"></span>
                                            </div>
                                        </div>
                                <?php if($key != 6 && $key!=7 && $key!=8){  ?>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                {{ Form::label('loc_budget_officer_id'.$key, __('Budget Officer Name'),['class'=>'form-label']) }}<span class="text-danger starrequired">*</span>
                                                <span class="validate-err">{{ $errors->first('loc_budget_officer_id') }}</span>
                                                <div class="form-icon-user">
                                                    {{ Form::select('loc_budget_officer_id'.$key,$arrHrEmpCode,$localitydata['loc_budget_officer_id'], array('class' => 'form-control forSelect3 mandatoryclass getPosition','id'=>'loc_budget_officer_id'.$key,'pname'=>'loc_budget_officer_position'.$key)) }}
                                                </div>
                                                <span class="validate-err" id="err_loc_budget_officer_id{{$key}}"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                {{ Form::label('loc_budget_officer_position'.$key, __('Budget Officer Position'),['class'=>'form-label']) }}<span class="text-danger starrequired">*</span>
                                                <span class="validate-err">{{ $errors->first('loc_budget_officer_position') }}</span>
                                                <div class="form-icon-user">
                                                     {{ Form::text('loc_budget_officer_position'.$key, $localitydata['loc_budget_officer_position'], array('class' => 'form-control mandatoryclass','maxlength'=>'75')) }}
                                                </div>
                                                <span class="validate-err" id="err_loc_budget_officer_position{{$key}}"></span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                {{ Form::label('loc_treasurer_id'.$key, __('Treasurer Name'),['class'=>'form-label']) }}<span class="text-danger starrequired">*</span>
                                                <span class="validate-err">{{ $errors->first('loc_treasurer_id') }}</span>
                                                <div class="form-icon-user">
                                                     {{ Form::select('loc_treasurer_id'.$key,$arrHrEmpCode,$localitydata['loc_treasurer_id'], array('class' => 'form-control forSelect3 mandatoryclass getPosition','id'=>'loc_treasurer_id'.$key,'pname'=>'loc_treasurer_position'.$key)) }}
                                                </div>
                                                <span class="validate-err" id="err_loc_treasurer_id{{$key}}"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                {{ Form::label('loc_treasurer_position'.$key, __('Treasurer Position'),['class'=>'form-label']) }}<span class="text-danger starrequired">*</span>
                                                <span class="validate-err">{{ $errors->first('loc_treasurer_position') }}</span>
                                                <div class="form-icon-user">
                                                     {{ Form::text('loc_treasurer_position'.$key, $localitydata['loc_treasurer_position'], array('class' => 'form-control mandatoryclass','maxlength'=>'75')) }}
                                                </div>
                                                <span class="validate-err" id="err_loc_treasurer_position{{$key}}"></span>
                                            </div>
                                        </div>
                                        <?php
                                        if($key==2){ ?>
                                             <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('loc_chief_land_id'.$key, __('Chief, Land Tax'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('loc_chief_land_id') }}</span>
                                                    <div class="form-icon-user">
                                                          {{ Form::select('loc_chief_land_id'.$key,$arrHrEmpCode,$localitydata['loc_chief_land_id'], array('class' => 'form-control forSelect3 getPosition','id'=>'loc_chief_land_id'.$key,'pname'=>'loc_chief_land_tax_position'.$key)) }}
                                                    </div>
                                                    <span class="validate-err" id="err_loc_chief_land_id{{$key}}"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('loc_chief_land_tax_position'.$key, __('Chief, Land Tax Position'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('loc_chief_land_tax_position') }}</span>
                                                    <div class="form-icon-user">
                                                         {{ Form::text('loc_chief_land_tax_position'.$key, $localitydata['loc_chief_land_tax_position'], array('class' => 'form-control','maxlength'=>'75')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_loc_chief_land_tax_position{{$key}}"></span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('loc_assessor_id'.$key, __('Assessor Name'),['class'=>'form-label']) }}<span class="text-danger starrequired">*</span>
                                                    <span class="validate-err">{{ $errors->first('loc_assessor_id') }}</span>
                                                    <div class="form-icon-user">
                                                         {{ Form::select('loc_assessor_id'.$key,$arrHrEmpCode,$localitydata['loc_assessor_id'], array('class' => 'form-control forSelect3 mandatoryclass getPosition','id'=>'loc_assessor_id'.$key,'pname'=>'loc_assessor_position'.$key)) }}
                                                    </div>
                                                    <span class="validate-err" id="err_loc_assessor_name{{$key}}"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('loc_assessor_position'.$key, __('Assessor Position'),['class'=>'form-label']) }}<span class="text-danger starrequired">*</span>
                                                    <span class="validate-err">{{ $errors->first('loc_assessor_position') }}</span>
                                                    <div class="form-icon-user">
                                                         {{ Form::text('loc_assessor_position'.$key, $localitydata['loc_assessor_position'], array('class' => 'form-control mandatoryclass','maxlength'=>'75')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_loc_assessor_position{{$key}}"></span>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('loc_assessor_assistant_id'.$key, __('Assessor Assistant Name'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('loc_assessor_assistant_id') }}</span>
                                                    <div class="form-icon-user">
                                                         {{ Form::select('loc_assessor_assistant_id'.$key,$arrHrEmpCode,$localitydata['loc_assessor_assistant_id'], array('class' => 'form-control forSelect3 getPosition','id'=>'loc_assessor_assistant_id'.$key,'pname'=>'loc_assessor_assistant_position'.$key)) }}
                                                    </div>
                                                    <span class="validate-err" id="err_loc_assessor_assistant_id{{$key}}"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('loc_assessor_assistant_position'.$key, __('Assessor Assistant Position'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('loc_assessor_assistant_position') }}</span>
                                                    <div class="form-icon-user">
                                                         {{ Form::text('loc_assessor_assistant_position'.$key, $localitydata['loc_assessor_assistant_position'], array('class' => 'form-control','maxlength'=>'75')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_loc_assessor_assistant_position{{$key}}"></span>
                                                </div>
                                            </div>
                                            @if($brgyCode != null)
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('loc_group_default_barangay_id'.$key, __('Set Default Location Group'),['class'=>'form-label']) }}<span class="text-danger starrequired">*</span>
                                                    <span class="validate-err">{{ $errors->first('loc_group_default_barangay_id') }}</span>
                                                    <div class="form-icon-user">
                                                        <div class="form-icon-user">
                                                        {{ Form::select('loc_group_default_barangay_id'.$key,$brgyCode,$localitydata['loc_group_default_barangay_id'], array('class' => 'form-control forSelect3 mandatoryclass','id'=>'loc_group_default_barangay_id'.$key)) }}
                                                        
                                                        </div>
                                                    </div>
                                                    <span class="validate-err" id="err_loc_group_default_barangay_id{{$key}}"></span>
                                                </div>
                                            </div>
                                            @endif
                                        <?php
                                         }
                                        if($key==1 || $key==4 ){ ?>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('loc_accountant_id'.$key, __('Accountant'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('loc_accountant_id') }}</span>
                                                    <div class="form-icon-user">
                                                          {{ Form::select('loc_accountant_id'.$key,$arrHrEmpCode,$localitydata['loc_accountant_id'], array('class' => 'form-control forSelect3 getPosition','id'=>'loc_accountant_id'.$key,'pname'=>'loc_accountant_position'.$key)) }}
                                                    </div>
                                                    <span class="validate-err" id="err_loc_accountant_id{{$key}}"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    {{ Form::label('loc_accountant_position'.$key, __('Accountant Position'),['class'=>'form-label']) }}
                                                    <span class="validate-err">{{ $errors->first('loc_accountant_position') }}</span>
                                                    <div class="form-icon-user">
                                                         {{ Form::text('loc_accountant_position'.$key, $localitydata['loc_accountant_position'], array('class' => 'form-control','maxlength'=>'75')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_loc_accountant_position{{$key}}"></span>
                                                </div>
                                            </div><?php
                                                if($key==1){ ?>
                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                        <div class="form-group">
                                                            {{ Form::label('loc_chief_bplo_id'.$key, __('Chieff BPLO'),['class'=>'form-label']) }}
                                                            <span class="validate-err">{{ $errors->first('loc_chief_bplo_id') }}</span>
                                                            <div class="form-icon-user">
                                                                  {{ Form::select('loc_chief_bplo_id'.$key,$arrHrEmpCode,$localitydata['loc_chief_bplo_id'], array('class' => 'form-control forSelect3 getPosition','id'=>'loc_chief_bplo_id'.$key,'pname'=>'loc_chief_bplo_position'.$key)) }}
                                                            </div>
                                                            <span class="validate-err" id="err_loc_chief_bplo_id{{$key}}"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                        <div class="form-group">
                                                            {{ Form::label('loc_chief_bplo_position'.$key, __('Chieff BPLO Position'),['class'=>'form-label']) }}
                                                            <span class="validate-err">{{ $errors->first('loc_chief_bplo_position') }}</span>
                                                            <div class="form-icon-user">
                                                                 {{ Form::text('loc_chief_bplo_position'.$key, $localitydata['loc_chief_bplo_position'], array('class' => 'form-control','maxlength'=>'75')) }}
                                                            </div>
                                                            <span class="validate-err" id="err_loc_chief_bplo_position{{$key}}"></span>
                                                        </div>
                                                    </div>
                                                    <?php 
                                                }  
                                           }
                                        } 
                                            
                                        if($key!=4 && $key!=5 && $key!=2 && $key!=6 && $key!=7 && $key!=8){?>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    {{ Form::label('asment_id', __('Fire Protection Assessment Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                                    <span class="validate-err">{{ $errors->first('asment_id') }}</span>
                                                    <div class="form-icon-user">
                                                         @php 
                                                            $arrAssessmentType=config('constants.arrAssessmentType');
                                                         @endphp
                                                         {{ Form::select('asment_id'.$key,$arrAssessmentType,$localitydata['asment_id'], array('class' => 'form-control forSelect3','id'=>'asment_id','required'=>'required')) }}
                                                    </div>
                                                    <span class="validate-err" id="err_loc_assessor_assistant_name"></span>
                                                </div>
                                            </div><?php
                                        } ?>
                                        <?php if($key==1){
                                            ?>
                                            <div class="col-md-4" style="padding-top: 25px;">
                                                <div class="form-group">
                                                   
                                                    <span class="validate-err">{{ $errors->first('bfp_inspection_order') }}</span>
                                                    <div class="form-icon-user">
                                                         {{ Form::checkbox('bfp_inspection_order'.$key,'1', ($localitydata['bfp_inspection_order'])?true:false, array('id'=>'bfp_inspection_order','class'=>'form-check-input new','style'=>'margin-top:9px')) }} {{Form::label('',__('Display Fire Protection Inspection Order'),['class'=>'form-label','style'=>'padding-top: 9px;'])}}
                                                    </div>
                                                    <span class="validate-err" id="err_bfp_inspection_order"></span>
                                                </div>
                                            </div>
                                       <?php  } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><?php 
            }
        } ?>
        <!--- End Departiment wise locality --->

    </div>

    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary">
    </div>
{{Form::close()}}
<!-- <script src="{{ asset('js/ajax_validationmuncipality.js?ver='.$version) }}"></script> -->
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/addProfileMunicipalitie.js') }}?rand={{rand(0,999)}}"></script>
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
            url :DIR+'profilemunicipalitie/formValidation', // json datasource
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


