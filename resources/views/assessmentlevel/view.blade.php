<style type="text/css">
    .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 800px;
        pointer-events: auto;
        background-color: #ffffff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 15px;
        outline: 0;
        float: left;
        margin-left: 4%;
        margin-top: 53%;
        transform: translate(0%, -50%);
    }
</style>
<div class="modal-body" style="padding-bottom: 0px;">
    @foreach ($DefaultAssData as $ass)
         <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                    {{ Form::label('mun_no', __('Item No.'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('al_assessment_level',$serial_no,array('class' => 'form-control','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_loc_local_code"></span>
                </div>
            </div>  
            <div class="col-md-4">
              <div class="form-group">
                    {{ Form::label('mun_no', __('Revision Year'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('al_assessment_level',$ass->rvy_revision_year.'-'.$ass->rvy_revision_code,array('class' => 'form-control','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_loc_local_code"></span>
                </div>
            </div>       
            <div class="col-md-6">
              <div class="form-group">
                    {{ Form::label('loc_group_brgy_no', __('Kind'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{ Form::text('al_assessment_level',$ass->pk_code.'-'.$ass->pk_description,array('class' => 'form-control','readonly')) }} 
                    </div>
                    <span class="validate-err" id="err_rvy_revision_year"></span>
                </div>
            </div>   
        </div>
        
        <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                    {{ Form::label('loc_group_brgy_no', __('Class'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                       {{ Form::text('al_assessment_level',$ass->pc_class_code.'-'.$ass->pc_class_description,array('class' => 'form-control','readonly')) }} 
                    </div>
                    <span class="validate-err" id="err_rvy_revision_year"></span>
                </div>
            </div>
            
            @if($ass->pkcode==3)
            <div class="col-md-6">
              <div class="form-group">
                    {{ Form::label('mun_no', __('Actual Use'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('al_assessment_level','',array('class' => 'form-control','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_loc_local_code"></span>
                </div>
            </div>   
        </div>
        @else
        <div class="col-md-6">
              <div class="form-group">
                    {{ Form::label('mun_no', __('Actual Use'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('al_assessment_level',$ass->pau_actual_use_code.'-'.$ass->pau_actual_use_desc,array('class' => 'form-control','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_loc_local_code"></span>
                </div>
            </div>   
        </div>
        @endif
        @endforeach
</div>  

<div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            
                            <thead>
                            <tr>
                                <th>{{__('Min Value')}}</th>
                                <th>{{__('Max Value')}}</th>
                                <th>{{__('Assessment Level(%)')}}</th>
                                <th>{{__('Status')}}</th>
                                
                                
                            </tr>
                            </thead>
                            <tbody>
                              @if(!$DefaultData)
                                <tr class="font-style">
                                    <td colspan="3" style="text-align:center;">No Data</td>
                                 </tr>
                              @else
                              
                              @foreach ($DefaultData as $val)
                              
                                @php 
                                $minimum_unit_value= $val->minimum_unit_value; 
                                $maximum_unit_value= $val->maximum_unit_value ;
                                $assessment_level= $val->assessment_level; 
                                @endphp
                                  <tr class="font-style">
                                    <td>@php echo '₱' . number_format($minimum_unit_value,3)@endphp</td>
                                    <td>@php echo '₱' . number_format($maximum_unit_value,3)@endphp</td>
                                     <td>@php echo  number_format($assessment_level,3)@endphp</td>
                                    <td>
                                        @if($val->re_is_active==1)
                                                <span class="btn btn-success">Active</span>
                                            @else
                                                 <span class="btn btn-warning">InActive</span>
                                            @endif
                                    </td>
                                    
                                </tr>
                               
                            @endforeach
                             @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

