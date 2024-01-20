<style>
.modal-content {
     position: absolute;
   float: left;
   margin-left: 50%;
   margin-top: 50%;
  transform: translate(-50%, -50%);
}
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
{{ Form::open(array('url' => 'BploEndorsingDept','class'=>'formDtls','id'=>'CtoChargeDescription')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}

    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('edept_name', __('Department Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('edept_name') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('edept_name', $data->edept_name, array('class' => 'form-control','maxlength'=>'50','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_edept_name"></span>
                </div>
            </div> 
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('fees', __('Fees'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('fees') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('fees', $data->fees, array('class' => 'form-control','readonly' => 'true','id'=>"fees")) }}
                    </div>
                    <span class="validate-err" id="err_fees"></span>
                </div>
            </div> 
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('tfoc_id', __('Tax,Fee & Other Charges'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::select('tfoc_id',$arrAccount,$data->tfoc_id, array('class' => 'form-control select3','id'=>'tfoc_id')) }}
                    </div>
                    <span class="validate-err" id="err_tfoc_id"></span>
                </div>
            </div>
        </div>  

        
       <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                     {{ Form::checkbox('force_mark_complete','1', ($data->force_mark_complete)?true:false, array('id'=>'force_mark_complete','class'=>'form-check-input new')) }}
                    {{Form::label('force_mark_complete',__('Force [Mark As Complete]'),['class'=>'form-label'])}}
                    
                    <span class="validate-err" id="err_tfoc_id"></span>
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
                    {{ Form::label('remark', __('Required'),['class'=>'form-label']) }}
                </div>
                <div class="col-md-4">
                    {{ Form::label('remark', __('Remarks'),['class'=>'form-label']) }}
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
                <div class="col-lg-2 col-md-2 col-sm-2" style="padding-left: 27px;padding-top: 8px;">
                    <div class="form-group">
                        <div class="form-icon-user">
                            {{ Form::checkbox('is_required_'.$val['requirement_id'],'1', ($val['is_required'])?true:false, ['id' => 'is_required'.$i, 'class' => 'form-check-input checkboxis_required']) }}

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="form-group">
                        <div class="form-icon-user">
                            {!! Form::textarea('remark[]', $val['remark'], ['class'=>'form-control','rows'=>'1']) !!}
                        </div>
                    </div>
                </div>
                
                
                <div class="col-md-1 delete-btn-dtls">
                    <span class="btnCancel btn-sm btn-danger" id="{{$val['requirement_id']}}">
                        <i class="ti-trash"></i>
                    </span>
                </div>
               
               
                @php $i++; @endphp
            </div>
            @endforeach 
        </span>

    </div>
        <div class="modal-footer" style="padding-top:140px;">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
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
        <div class="col-lg-2 col-md-2 col-sm-2" style="padding-left: 27px;padding-top: 8px;">
            <div class="form-group">
                <div class="form-icon-user">
                   {{ Form::checkbox('','1', ('')?true:false, array('id'=>'is_required'.$i,'class'=>'form-check-input checkboxis_required')) }}
                 
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <div class="form-icon-user">
                    {!! Form::textarea('remark[]', '', ['class'=>'form-control','rows'=>'1']) !!}
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
<script src="{{ asset('js/Bplo/add_BploEndorsingDept.js') }}?rand={{ rand(000,999) }}"></script>
<script src="{{ asset('js/ajax_validation.js')}}?rand={{ rand(000,999) }}"></script>
<script type="text/javascript">
      $(document).ready(function () {
    var shouldSubmitForm = false;

        $('#submit').click(function (e) {
            var form = $('#CtoChargeDescription');
            var areFieldsFilled = checkIfFieldsFilled();

            if (areFieldsFilled) {
                e.preventDefault(); // Prevent the default form submission

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
                        shouldSubmitForm = true;
                        form.submit();
                    } else {
                        console.log("Form submission canceled");
                    }
                });
            }
        });

        function checkIfFieldsFilled() {
    var form = $('#CtoChargeDescription');
    var requiredFields = form.find('.requirement_id'); // Update the selector
    var isValid = true;

    requiredFields.each(function () {
        var field = $(this);
        var fieldValue = field.val();

        if (fieldValue === '' || fieldValue === null) {
            isValid = false;
            return false; // Exit the loop early if any field is empty
        }
    });

    if (!isValid) {
        // $('#err_requirement').html('<p>.</p>');
        console.log("All required fields must be filled");
    }

    return isValid;
}

});
  </script> 