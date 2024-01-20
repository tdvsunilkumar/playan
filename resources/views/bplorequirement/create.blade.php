{{ Form::open(array('url' => 'bplorequirements','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
<?php
    $select3='select3';
    $readonly = '';
    if(isset($_GET['sid'])){
        $select3='disabled-field';
        //$readonly='readonly=readonly';
    }
?>
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
</style>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('section_id', __('Section'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <span class="validate-err">{{ $errors->first('section_id') }}</span>
                <div class="form-icon-user">
                    {{ Form::select('section_id',$arrsection,$data->section_id, array('class' => 'form-control '.$select3,'id'=>'section_id','required'=>'required',$readonly)) }}
                </div>
                <span class="validate-err" id="err_section_id"></span>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('division_id', __('Division'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <span class="validate-err">{{ $errors->first('division_id') }}</span>
                <div class="form-icon-user">
                    {{ Form::select('division_id',$arrdivision,$data->division_id, array('class' => 'form-control '.$select3,'id'=>'division_id','required'=>'required',$readonly)) }}
                </div>
                <span class="validate-err" id="err_division_id"></span>
            </div>
        </div>

        
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('group_id', __('Group'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <span class="validate-err">{{ $errors->first('group_id') }}</span>
                <div class="form-icon-user">
                    {{ Form::select('group_id',$arrgroup,$data->group_id, array('class' => 'form-control '.$select3,'id'=>'group_id','required'=>'required',$readonly)) }}
                </div>
                <span class="validate-err" id="err_group_id"></span>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('class_id', __('Class'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <span class="validate-err">{{ $errors->first('class_id') }}</span>
                <div class="form-icon-user">
                    {{ Form::select('class_id',$arrclass,$data->class_id, array('class' => 'form-control '.$select3,'id'=>'class_id','required'=>'required',$readonly)) }}
                </div>
                <span class="validate-err" id="err_class_id"></span>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('subclass_id', __('Subclass'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <span class="validate-err">{{ $errors->first('subclass_id') }}</span>
                <div class="form-icon-user">
                    {{ Form::select('subclass_id',$arrsubclass,$data->subclass_id, array('class' => 'form-control '.$select3,'id'=>'subclass_id','required'=>'required',$readonly)) }}
                </div>
                <span class="validate-err" id="err_subclass_id"></span>
            </div>
        </div>




        <div class="form-group col-md-12">
            <div class="form-group">
                {{ Form::label('apptype_id', __('App Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <span class="validate-err">{{ $errors->first('apptype_id') }}</span>
                <div class="form-icon-user">
                    {{ Form::select('apptype_id',$apptypes,$data->apptype_id, array('class' => 'form-control ','id'=>'apptype_id','required'=>'required')) }}
                </div>
                <span class="validate-err" id="err_apptype_id"></span>
            </div>
        </div>
        <!-- <br><br>
         <div class="col-md-6">
                <div class="d-flex radio-check" style="padding-top: 35px;">
                    <div class="form-check form-check-inline form-group col-md-1" style="padding-right: 30px;">

                        {{ Form::radio('is_active2', '1', ($data->is_active2)?true:false, array('id'=>'active','class'=>'form-check-input code')) }}
                        {{ Form::label('active', __('Active'),['class'=>'form-label']) }}
                    </div>
                    <div class="form-check form-check-inline form-group col-md-1">
                        {{ Form::radio('is_active2', '0', (!$data->is_active2)?true:false, array('id'=>'InActive','class'=>'form-check-input code')) }}
                        {{ Form::label('InActive', __('InActive'),['class'=>'form-label']) }}
                    </div>
                </div>
            </div>     -->
            <!-- <div class="form-group col-md-6">
            {{ Form::label('req_code_abbreviation', __('Code Abbrevation'),['class'=>'form-label']) }}
            <span class="text-danger">*</span>
            <span class="validate-err">{{ $errors->first('req_code_abbreviation') }}</span>
            {!! Form::textarea('req_code_abbreviation', $data->req_code_abbreviation, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
            <span class="validate-err" id="err_req_code_abbreviation"></span>
            </div>
            <div class="form-group col-md-6">
            {{ Form::label('req_description', __('Request Desc'),['class'=>'form-label']) }}
            <span class="text-danger">*</span>
            <span class="validate-err">{{ $errors->first('req_description') }}</span>
            {!! Form::textarea('req_description', $data->req_description, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
            <span class="validate-err" id="err_req_description"></span>
            </div> -->


<div class="row">
    <div class="row field-requirement-details-status" style="background: #20b7cc;color: #fff;">
        <div class="col-lg-4 col-md-4 col-sm-4">
            {{Form::label('requirement_id',__('Requirment'),['class'=>'form-label'])}}
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            {{Form::label('is_active',__('Status'),['class'=>'form-label'])}}
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3">
            {{Form::label('remark',__('Remarks'),['class'=>'form-label numeric'])}}
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
           {{ Form::label('remark', __('Required'),['class'=>'form-label']) }}
        </div>

        <div class="col-lg-1 col-md-1 col-sm-1" style="padding-top: 8px; text-align: end;">
            <span class="btn-sm btn-primary" id="btn_addmore_nature" style="cursor: pointer;">
                        <i class="ti-plus"></i>
                    </span>
            
        </div>
    </div>

    <span class="natureDetails nature-details" id="natureDetails">
        @php $i=0; @endphp
        @foreach($arrNature as $key=>$val)
        <div class="row removenaturedata pt10">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="form-group">
                    <div class="form-icon-user">
                        {{ Form::select('requirement_id[]',$arrrequirement,$val['requirement_id'], array('class' => 'form-control select3 requirement_id','id'=>'requirement_id'.$i)) }}
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
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="form-group">
                    <div class="form-icon-user">
                        {!! Form::textarea('remark[]', $val['remark'], ['class'=>'form-control','rows'=>'1']) !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2" style="padding-top:7px">
                    <div class="form-group">
                        <div class="form-icon-user">
                            {{ Form::checkbox('is_required_'.$val['requirement_id'],'1', ($val['is_required'])?true:false, ['id' => 'is_required'.$i, 'class' => 'form-check-input checkboxis_required']) }}

                        </div>
                    </div>
                </div>
           
            <div class="col-sm-1" style="padding-top: 9px;">
                <!-- <input type="button" name="btn_cancel_nature" class="btn btn-success " id="{{$val['id']}}" value="Delete" style="padding: 0.4rem 1rem !important;"> -->
                <span class="btn-sm btn-danger btn_cancel_nature delete" id="{{$val['id']}}" style="cursor:pointer;">
                        <i class="ti-trash"></i>
                    </span>
            </div>
       
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#requirement_id<?=$i?>").select3({dropdownAutoWidth : false,dropdownParent: $("#natureDetails")});
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
                <input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Update'):__('Create')}}" class="btn  btn-primary"> -->
        </div>
</div>

<input type="hidden" name="dynamicid" value="3" id="dynamicid">
<div id="hidennatureHtml" class="hide">
    <div class="removenaturedata row pt10">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <div class="form-icon-user">
                   @php 
                   $i=(count($arrNature)>0)?count($arrNature):0;

                   @endphp
                   {{ Form::select('requirement_id[]',$arrrequirement,'', array('class' => 'form-control requirement_id','id'=>'requirement_id'.$i)) }}

               </div>
               <span class="validate-err" id="err_requirement"></span>
           </div>
       </div>

       
       <div class="col-lg-2 col-md-2 col-sm-2">
        <div class="form-group">
            <div class="form-icon-user">
                {{ Form::select('is_active[]',array('1'=>'Active','0' =>'InActive'), '', array('class' => 'form-control spp_type','id'=>'is_active')) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3">
        <div class="form-group">
            <div class="form-icon-user">
                {!! Form::textarea('remark[]', '', ['class'=>'form-control','rows'=>'1']) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2" style="padding-top:7px">
            <div class="form-group">
                <div class="form-icon-user">
                   {{ Form::checkbox('','1', ('')?true:false, array('id'=>'is_required'.$i,'class'=>'form-check-input checkboxis_required')) }}
                   
                </div>
            </div>
        </div>
    <div class="col-sm-1" style="padding-top: 9px;">
        <!-- <input type="button" name="btn_cancel"  class="btn btn-success btn_cancel_nature delete" cid="" value="Delete" style="padding: 0.4rem 1rem !important;"> -->
        <span class="btn-sm btn-danger btn_cancel_nature delete" value="Delete" style="cursor:pointer;">
                        <i class="ti-trash"></i>
                    </span>
    </div>
</div>
</div>    

{{Form::close()}}

<script src="{{ asset('js/ajax_validation.js') }}"></script>  
<script src="{{ asset('js/add_pblorequirements.js') }}?rand={{ rand(000,999) }}"></script>  
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
            url :DIR+'bplorequirements/formValidation', // json datasource
            type: "POST", 
            data: $('#submitLandUnitValueForm').serialize(),
            dataType: 'json',
            success: function(html){
                if(html.ESTATUS){
                  
                    $("#err_"+html.field_name).html(html.error);
                    $("."+html.field_name).focus();
                }else{
                    var areFieldsFilled = checkIfFieldsFilled();
                    if (areFieldsFilled) {
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
            }
        })
     
   });
   function checkIfFieldsFilled() {
            var form = $('#submitLandUnitValueForm');
            var requiredFields = form.find('[required="required"]');
            var isValid = true;

            requiredFields.each(function () {
                var field = $(this);
                var fieldValue = field.val();

                if (fieldValue === '') {
                    isValid = false;
                    return false; // Exit the loop early if any field is empty
                }
            });

            if (!isValid) {
                // Swal.fire({
                //     title: "All required fields must be filled",
                //     icon: 'error',
                //     customClass: {
                //         confirmButton: 'btn btn-danger',
                //     },
                //     buttonsStyling: false
                // });
            }

            return isValid;
        }
});


</script>     