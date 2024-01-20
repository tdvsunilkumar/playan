{{ Form::open(array('url' => 'CtoPaymentOrAssignment','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
@if(session()->has('iscashier'))
  {{ Form::hidden('iscashier',Session::get('iscashier'), array('id' => 'iscashier')) }}
@endif

<style>
.modal-content {
    position: absolute;
   float: left;
   margin-left: 50%;
   margin-top: 50%;
  transform: translate(-50%, -50%);
}
</style>
    <div class="modal-body">
        <div class="row">
            <span class="validate-err" id="err_rangeExistError"></span>
             <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('ortype_id', __('O.R. Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('ortype_id',$arrType,$data->ortype_id, array('class' => 'form-control select3','id'=>'ortype_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_ortype_id"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('shortname', __('Short Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('shortname') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('shortname','', array('class' => 'form-control disabled-field','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_cpot_id"></span>
                </div>
            </div> 
            <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('cpor_id', __('O.R. Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('cpor_id',$arrOrdetail,$data->cpor_id, array('class' => 'form-control select3','id'=>'cpor_id','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_cpor_id"></span>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('tagno', __('Tag No.'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('tagno') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('tagno','', array('class' => 'form-control disabled-field','required'=>'required','id'=>'tagno')) }}
                    </div>
                    <span class="validate-err" id="err_tagno"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ora_from', __('O.R. Series From'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ora_from') }}</span>
                    <div class="form-icon-user">
                         {{ Form::number('ora_from', $data->ora_from, array('class' => 'form-control disabled-field','maxlength'=>'150','required'=>'required','id'=>'ora_from')) }}
                    </div>
                    <span class="validate-err" id="err_ora_from"></span>
                </div>
            </div>  
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ora_to', __('O.R. Series To'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ora_to') }}</span>
                    <div class="form-icon-user">
                         {{ Form::number('ora_to', $data->ora_to, array('class' => 'form-control disabled-field','maxlength'=>'150','required'=>'required','id'=>'ora_to')) }}
                    </div>
                    <span class="validate-err" id="err_ora_to"></span>
                </div>
            </div>  
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('or_count', __('O.R. Count'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('or_count') }}</span>
                    <div class="form-icon-user">
                         {{ Form::number('or_count', $data->or_count, array('class' => 'form-control disabled-field','maxlength'=>'150','required'=>'required','id'=>'or_count')) }}
                    </div>
                    <span class="validate-err" id="err_or_count"></span>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $disabled = ($data->id>0)?false:true;
                ?>
                <div class="form-group">
                    {{ Form::label('ora_is_completed', __('Completed'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                        {{ Form::select('ora_is_completed',$arrCompleted,$data->ora_is_completed, array('class' => 'form-control select3','id'=>'ora_is_completed','required'=>'required','disabled'=>$disabled)) }}
                    </div>
                    <span class="validate-err" id="err_ora_is_completed"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ora_completed_date', __('Date Completed'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                         {{ Form::text('ora_completed_date', $data->ora_completed_date, array('class' => 'form-control','maxlength'=>'150','readonly'=>'readonly')) }}
                    </div>
                    <span class="validate-err" id="err_ora_to"></span>
                </div>
            </div> 
             <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('ora_date_returned', __('Date Return'),['class'=>'form-label']) }}
                    <div class="form-icon-user">
                         {{ Form::date('ora_date_returned', $data->ora_date_returned, array('class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_ora_date_returned"></span>
                </div>
            </div>
             <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('ora_remarks', __('Remark'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('ora_remarks') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ora_remarks', $data->ora_remarks, array('class' => 'form-control','maxlength'=>'150')) }}
                    </div>
                    <span class="validate-err" id="err_ora_remarks"></span>
                </div>
            </div>     
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
<script src="{{ asset('js/Bplo/add_or_assignment.js') }}?rand={{ rand(000,999) }}"></script>
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
            url :DIR+'CtoPaymentOrAssignment/formValidation', // json datasource
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