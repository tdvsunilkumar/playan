{{ Form::open(array('url' => 'bplo-section-requirements','class'=>'formDtls')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}

<style type="text/css">
    .check-cash-heading {
        background: #20B7CC;
        padding-top: 5px;
        margin-bottom: 15px;
        color: #ffffff;
        font-weight: bold;
    }
    .btn-primary, .btn-danger {cursor: pointer;}
    .delete-btn-dtls{padding-top: 10px;}
    #section_id{background: unset;}
</style>
<div class="modal-body">
    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                {{ Form::label('section_id', __('Section'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <span class="validate-err">{{ $errors->first('section_id') }}</span>
                <div class="form-icon-user">
                    {{ Form::select('section_id',$arrSection,$data->section_id, array('class' => 'form-control disabled-field','id'=>'section_id','required'=>'required')) }}
                </div>
                <span class="validate-err" id="err_section_id"></span>
            </div>
        </div>
        <div class="form-group col-md-4">
            <div class="form-group">
                {{ Form::label('apptype_id', __('App Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <span class="validate-err">{{ $errors->first('apptype_id') }}</span>
                <div class="form-icon-user">
                    {{ Form::select('apptype_id',$apptypes,$data->apptype_id, array('class' => 'form-control select3','id'=>'apptype_id','required'=>'required')) }}
                </div>
                <span class="validate-err" id="err_apptype_id"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="box-border check-cash-dtls">
            <div class="row check-cash-heading">
                <div class="col-md-5">
                    {{ Form::label('requirement_id', __('Requirements'),['class'=>'form-label']) }}
                </div>
                <div class="col-md-2">
                    {{ Form::label('is_active', __('Status'),['class'=>'form-label']) }}
                </div>
                <div class="col-md-2">
                    {{ Form::label('remark', __('Remarks'),['class'=>'form-label']) }}
                </div>
                <div class="col-md-2">
                    {{ Form::label('remark', __('Required'),['class'=>'form-label']) }}
                </div>
                <div class="col-md-1">
                    <span class="btn-sm btn-primary" id="btn_addmore">
                        <i class="ti-plus"></i>
                    </span>
                </div>
            </div>
        </div>
        <span class="reqDetails requirement-details" id="reqDetails">
            @php $i=0; @endphp
            @foreach($arrDetails as $key=>$val)
            <div class="row removedata pt10">
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <div class="form-group">
                        <div class="form-icon-user">
                            {{ Form::select('requirement_id[]',$arrrequirement,$val['requirement_id'], array('class' => 'form-control select3 requirement_id','id'=>'requirement_id'.$i,'required'=>'required')) }}
                        </div>
                        <span class="validate-err" id="err_requirement"></span>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <div class="form-group">
                        <div class="form-icon-user">
                            {{ Form::select('is_active[]',array('1'=>'Active','0' =>'InActive'), $val['is_active'], array('class' => 'form-control spp_type','id'=>'is_active')) }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <div class="form-group">
                        <div class="form-icon-user">
                            {!! Form::textarea('remark[]', $val['remark'], ['class'=>'form-control','rows'=>'1']) !!}
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <div class="form-group">
                        <div class="form-icon-user">
                            {{ Form::checkbox('is_required_'.$val['requirement_id'],'1', ($val['is_required'])?true:false, ['id' => 'is_required'.$i, 'class' => 'form-check-input checkboxis_required']) }}

                        </div>
                    </div>
                </div>
                <div class="col-md-1 delete-btn-dtls">
                    <span class="btnCancel btn-sm btn-danger" id="{{$val['requirement_id']}}">
                        <i class="ti-trash"></i>
                    </span>
                </div>
               
                <script type="text/javascript">
                    $(document).ready(function(){
                        $("#requirement_id<?=$i?>").select3({dropdownAutoWidth : false,dropdownParent: $("#reqDetails")});
                    });
                </script>
                @php $i++; @endphp
            </div>
            @endforeach 
        </span>
    </div>
    <div class="modal-footer" style="padding-top:140px;">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
            <i class="fa fa-save icon"></i>
            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
        </div>
    </div>
</div>
{{Form::close()}}
<div id="hidenrequirementHtml" class="hide">
    <div class="removedata row pt10">
        <div class="col-lg-5 col-md-5 col-sm-5">
            <div class="form-group">
                <div class="form-icon-user">
                    @php 
                    $i=(count($arrDetails)>0)?count($arrDetails):0;
                    @endphp
                    {{ Form::select('requirement_id[]',$arrrequirement,'', array('class' => 'form-control requirement_id','id'=>'requirement_id'.$i,'required'=>'required')) }}
                </div>
                <span class="validate-err" id="err_requirement"></span>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                    {{ Form::select('is_active[]',array('1'=>'Active','0' =>'InActive'), '', array('class' => 'form-control spp_type','id'=>'is_active'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                    {!! Form::textarea('remark[]', '', ['class'=>'form-control','rows'=>'1']) !!}
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="form-group">
                <div class="form-icon-user">
                    {{ Form::checkbox('','1', ('')?true:false, array('id'=>'is_required'.$i,'class'=>'form-check-input checkboxis_required')) }}
                 
                </div>
            </div>
        </div>
        <div class="col-md-1 delete-btn-dtls">
            <span class="btnCancel btn-sm btn-danger" cid="">
                <i class="ti-trash"></i>
            </span>
        </div>
    </div>
</div>    

<script src="{{ asset('js/ajax_validation.js') }}"></script>  
<script src="{{ asset('js/Bplo/add_SectionRequirement.js') }}?rand={{ rand(000,999) }}"></script>  

