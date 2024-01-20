{{ Form::open(array('url' => 'HrleaveParameter','class'=>'formDtls','id'=>'submitLandUnitValueForm')) }}
    {{ Form::hidden('id',$data->id, array('id' => 'id')) }}

    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('hrlp_description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('hrlp_description') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('hrlp_description', $data->hrlp_description, array('class' => 'form-control','maxlength'=>'150','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_hrlp_description"></span>
                </div>
            </div>    
        </div> 
        <table class="table" id="leave-params">
            <thead>
                <tr>
                    <th>{{Form::label('id',__('Leave Type'),['class'=>'form-label'])}}</th>
                    <th>{{Form::label('id',__('# of Days'),['class'=>'form-label'])}}</th>
                    <th>{{Form::label('id',__('Accrual Type'),['class'=>'form-label'])}}</th>
                    <th>{{Form::label('id',__('Accrual Credits'),['class'=>'form-label'])}}</th>
                    <th>
                        <span class="btn_addmore_params btn btn-primary" style="color:white;">
                            <i class="ti-plus"></i>
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @if(isset($data->parameters))
                    @foreach($data->parameters as $param)
                    <tr id="contain_{{$param->id}}">
                        <td>
                            {{ Form::select('params['.$param->id.'][hrlt_id]',
                                $arrLeaveType,
                                $param->hrlt_id,
                                array(
                                    'class' => 'form-control hrlt_id select3',
                                    'required'=>'required',
                                    'id'=>'hrlt_id_'.$param->id.''
                                    )
                            ) }}
                        </td>
                        <td>
                            {{Form::text('params['.$param->id.'][hrlpc_days]',
                                $param->hrlpc_days,
                                array(
                                    'class'=>'form-control hrlpc_days ',
                                    'required'=>'required',
                                    'id'=>'hrlpc_days_'.$param->id.''
                                    )
                                )}}
                        </td>
                        <td>
                            {{ Form::select('params['.$param->id.'][hrat_id]',
                                $arrHrAccrualType,
                                $param->hrat_id,
                                array(
                                    'class' => 'form-control hrat_id select3',
                                    'required'=>'required',
                                    'id'=>'hrat_id_'.$param->id.''
                                    )
                            ) }}
                        </td>
                        <td>
                            {{Form::text('params['.$param->id.'][hrlpc_credits]',
                                $param->hrlpc_credits,
                                array(
                                    'class'=>'form-control hrlpc_credits',
                                    'id'=>'hrlpc_credits'
                                    )
                                )}}
                        </td>
                        <td>
                            {{Form::hidden('params['.$param->id.'][hrlpc_is_active]',
                                $param->hrlpc_is_active,
                                array(
                                    'class'=>'hrlpc_is_active'
                                    )
                                )}}
                            @if($param->hrlpc_is_active === 1)
                            <button type="button" class="btn btn-danger btn_remove_params" data-id="{{$param->id}}" data-status="0">
                                <i class="ti-trash"></i>
                            </button>
                            @else
                            <button type="button" class="btn btn-primary btn_remove_params" data-id="{{$param->id}}" data-status="1">
                                <i class="ti-reload"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="modal-footer">
            
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
        </div>
    </div>
{{Form::close()}}
<table>
    <tbody class="hidden" id="addLeaveParams">
        <tr id="contain_changeid">
            <td>
                {{ Form::select('params[changeid][hrlt_id]',
                    $arrLeaveType,
                    '',
                    array(
                        'class' => 'form-control hrlt_id select3',
                        'required'=>'required',
                        'id'=>'hrlt_id_changeid'
                        )
                ) }}
            </td>
            <td>
                {{Form::text('params[changeid][hrlpc_days]',
                    '',
                    array(
                        'class'=>'form-control hrlpc_days ',
                        'required'=>'required',
                        'id'=>'hrlpc_days_changeid'
                        )
                    )}}
            </td>
            <td>
                {{ Form::select('params[changeid][hrat_id]',
                    $arrHrAccrualType,
                    '',
                    array(
                        'class' => 'form-control hrat_id select3',
                        'required'=>'required',
                        'id'=>'hrat_id_changeid'
                        )
                ) }}
            </td>
            <td>
                {{Form::text('params[changeid][hrlpc_credits]',
                    '',
                    array(
                        'class'=>'form-control hrlpc_credits',
                        'id'=>'hrlpc_credits'
                        )
                    )}}
            </td>
            <td>
                <button type="button" class="btn btn-danger btn_remove_params"><i class="ti-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>


<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/HR/add_leave_params.js') }}"></script>
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
            url :DIR+'HrleaveParameter/formValidation', // json datasource
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