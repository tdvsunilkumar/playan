{{ Form::open(array('url' => 'CtoPaymentOrSetup','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    {{ Form::hidden('user_id',Auth::user()->id, array('id' => 'id')) }}
    <style type="text/css">
        accordion-button:not(.collapsed)::after, .accordion-button::after {
             background-image: unset !important;
        }
        .accordiantitle{
                color: #000 !important;
        }
        .text-blue{
            color: #20B7CC;
            font-size: 20px;
            cursor: pointer;
        }
        .accordion-button {
            background-color: #f0f4f3 !important;
            display: block;
            padding: 8px;
            height: 40px !important;

        }
        .sub-title{
            float: left;
        }
        .savedit-btn{
            float: right;
        }
        .save-btn{
            font-weight: 500;
            line-height: 1.5;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            font-size: 0.875rem;
            border-radius: 6px;
        }
    </style>
    <div class="modal-body">
        <div class="row">
            <div class="@if($data->id != null)col-md-5 @else col-md-12 @endif">
                <div class="row">
                    @php
                    if($data->id != null){
                        $readonly = true;
                    }else{
                        $readonly = false;
                    }
                    @endphp
                    <div class="col-md-6">
                        <div class="form-group or_field_group">
                            {{ Form::label('or_field_form', __('System Default [OR Type]'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                            <div class="form-icon-user">
                                {{ Form::select('or_field_form',$system_arr_type,$data->or_field_form, array('class' => 'form-control','id'=>'or_field_form', 'readonly' => $readonly)) }}
                            </div>
                            <span class="validate-err" id="err_or_field_form"></span>
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group ortype_group">
                            {{ Form::label('ortype_id', __('User [OR Type]'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                            <div class="form-icon-user">
                                {{ Form::select('ortype_id',$user_arr_type,$data->ortype_id, array('class' => 'form-control','id'=>'ortype_id','required'=>'required', 'readonly' => $readonly)) }}
                            </div>
                            <span class="validate-err" id="err_ortype_id"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('user_id', __('Current User'),['class'=>'form-label']) }}
                            <div class="form-icon-user">
                                 {{ Form::text('user',Auth::user()->name, array('readonly'=>true,'class' => 'form-control','maxlength'=>'150')) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('ors_remarks', __('Remark'),['class'=>'form-label']) }}
                            <div class="form-icon-user">
                                 {{ Form::text('ors_remarks', $data->ors_remarks, array('class' => 'form-control','maxlength'=>'150')) }}
                            </div>
                        </div>
                    </div>
                    @if($data->id > 0)
                    <div class="col-md-10" style="padding-right: 0px;">
                        <div class="form-group copy_user_id_group">
                            {{ Form::label('copy_user_id', __('Copy Setup From User'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                            <div class="form-icon-user">
                                {{ Form::select('copy_user_id',$copy_user_arr,null, array('class' => 'form-control','id'=>'copy_user_id')) }}
                            </div>
                            <span class="validate-err" id="err_copy_user_id"></span>
                        </div>
                    </div>
                    <div class="col-md-2" style="text-align: end;margin-top:@if($data->id > 0) 26px @endif;">
                        <button class="btn btn-primary" type="button" onclick="copyDetails('{{$data->or_field_form}}', {{$data->id}})" style="padding: 9px;">Copy Details</button>
                    </div>
                    @endif
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::checkbox('is_portrait', '1',$data->is_portrait ? true : false, array('id'=>'is_portrait')) }}
                            {{ Form::label('Portrait', __('Portrait'),['class'=>'form-label']) }}
                        </div>
                    </div>
                </div> 
                <div class="row"><?php
                    if($data->id>0){
                        foreach ($setupData as $h_id => $val) { 
                            $name = isset($data->setup_details->$h_id->{$h_id.'_name'})?$data->setup_details->$h_id->{$h_id.'_name'}:'';
        
                            $font_size = isset($data->setup_details->$h_id->{$h_id.'_font_size'})?$data->setup_details->$h_id->{$h_id.'_font_size'}:'';
        
                            $position_top = isset($data->setup_details->$h_id->{$h_id.'_position_top'})?$data->setup_details->$h_id->{$h_id.'_position_top'}:'';
        
                            $position_bottom = isset($data->setup_details->$h_id->{$h_id.'_position_bottom'})?$data->setup_details->$h_id->{$h_id.'_position_bottom'}:'';
        
                            $position_right = isset($data->setup_details->$h_id->{$h_id.'_position_right'})?$data->setup_details->$h_id->{$h_id.'_position_right'}:'';
        
                            $position_left = isset($data->setup_details->$h_id->{$h_id.'_position_left'})?$data->setup_details->$h_id->{$h_id.'_position_left'}:'';
        
                            $font_is_bold = isset($data->setup_details->$h_id->{$h_id.'_font_is_bold'})?$data->setup_details->$h_id->{$h_id.'_font_is_bold'}:'';
        
                            $is_visible = isset($data->setup_details->$h_id->{$h_id.'_is_visible'})?$data->setup_details->$h_id->{$h_id.'_is_visible'}:'';
                            $right_justify = isset($data->setup_details->$h_id->{$h_id.'_right_justify'})?$data->setup_details->$h_id->{$h_id.'_right_justify'}:'';
                            $is_show_border = isset($data->setup_details->$h_id->{$h_id.'_is_show_border'})?$data->setup_details->$h_id->{$h_id.'_is_show_border'}:'';
        
                            
                            
                            ?>
                            <div class="col-lg-12 col-md-6 col-sm-6" style="margin-top: -20px;" id="accordionFlushExample_<?$h_id?>">
                                <br>
                                <div class="accordion accordion-flush">
                                    <div class="accordion-item">
                                        <div class="accordion-header" id="flush-headingtwo">
                                            <div class="accordion-button collapsed">
                                                <h6 class="sub-title">{{__($val)}}</h6>
                                                <h6 class="savedit-btn">
                                                    <i class="ti-pencil text-blue editDtls" h_id="{{$h_id}}"></i>
                                                </h6>
                                            </div>
                                        </div>
                                        <div id="formDtls_{{$h_id}}" class="accordion-collapse hide">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label($h_id.'_name', __('Sample Data'),['class'=>'form-label']) }}
                                                        <div class="form-icon-user">
                                                            {{ Form::text($h_id.'_name',$name , array('class' => 'form-control','maxlength'=>'100')) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label($h_id.'_font_size', __('Font Size'),['class'=>'form-label']) }}
                                                        <div class="form-icon-user">
                                                            {{ Form::text($h_id.'_font_size', $font_size, array('class' => 'form-control numeric','maxlength'=>'4')) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label($h_id.'_position_left', __('Position (X)'),['class'=>'form-label']) }}
                                                        <div class="form-icon-user">
                                                            {{ Form::text($h_id.'_position_left',$position_left, array('class' => 'form-control numeric','maxlength'=>'4')) }}
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="col-md-6 hide">
                                                    <div class="form-group">
                                                        {{ Form::label($h_id.'_position_bottom', __('Position Right'),['class'=>'form-label']) }}
                                                        <div class="form-icon-user">
                                                            {{ Form::text($h_id.'_position_bottom',$position_bottom, array('class' => 'form-control numeric','maxlength'=>'4')) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label($h_id.'_position_top', __('Position (Y)'),['class'=>'form-label']) }}
                                                        <div class="form-icon-user">
                                                            {{ Form::text($h_id.'_position_top',$position_top, array('class' => 'form-control numeric','maxlength'=>'4')) }}
                                                        </div>
                                                    </div>
                                                </div> 
                                                
                                                <div class="col-md-6 hide">
                                                    <div class="form-group">
                                                        {{ Form::label($h_id.'_position_right', __('Position Bottom'),['class'=>'form-label']) }}
                                                        <div class="form-icon-user">
                                                            {{ Form::text($h_id.'_position_right', $position_right, array('class' => 'form-control numeric','maxlength'=>'4')) }}
                                                        </div>
                                                    </div>
                                                </div> 
                                                 <div class="col-md-3">
                                                   <div class="d-flex radio-check"><br>
                                                        <div class="form-check form-check-inline form-group">
                                                            {{ Form::checkbox($h_id.'_right_justify', '1', ($right_justify)?true:false, array('id'=>$h_id.'right_justify','class'=>'form-check-input')) }}
                                                            {{ Form::label($h_id.'right_justify', __('Rght Justify'),['class'=>'form-label']) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                   <div class="d-flex radio-check"><br>
                                                        <div class="form-check form-check-inline form-group">
                                                            {{ Form::checkbox($h_id.'_font_is_bold', '1',($font_is_bold)?true:false, array('id'=>$h_id.'_font_is_bold','class'=>'form-check-input')) }}
                                                            {{ Form::label($h_id.'_font_is_bold', __('Font Bold'),['class'=>'form-label']) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                   <div class="d-flex radio-check"><br>
                                                        <div class="form-check form-check-inline form-group">
                                                            {{ Form::checkbox($h_id.'_is_visible', '1', ($is_visible)?true:false, array('id'=>$h_id.'_is_visible','class'=>'form-check-input')) }}
                                                            {{ Form::label($h_id.'_is_visible', __('Visible'),['class'=>'form-label']) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                   <div class="d-flex radio-check"><br>
                                                        <div class="form-check form-check-inline form-group">
                                                            {{ Form::checkbox($h_id.'_is_show_border', '1', ($is_show_border)?true:false, array('id'=>$h_id.'_is_show_border','class'=>'form-check-input')) }}
                                                            {{ Form::label($h_id.'_is_show_border', __('Show Cell Border'),['class'=>'form-label']) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="button" class="save-btn btn-primary saveDetails" value="Save" h_id="{{$h_id}}" style="float: right;">
                                                </div>
                                            </div> 
                                             
                                        </div>
                                    </div>
                                </div>
                            </div><?php 
                        }
                    } ?>
                </div>
            </div>
            @if($data->id != null)
            <div class="col-md-7 pdf-details">
                <div class="form-group" id="sample-frame">
                  <iframe id="pdf-iframe" src="{{ url('bplo-or-setup-sample') }}/{{ $data->id }}" width="90%" height="1200px"></iframe>
                </div>
            </div> 
            @endif
        </div>
       
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/Bplo/addSetup.js') }}"></script>
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
            url :DIR+'CtoPaymentOrSetup/formValidation', // json datasource
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
                    // location.reload();
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