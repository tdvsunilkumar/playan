{{ Form::open(array('url' => 'development-permit-computation/store','class'=>'formDtls','id'=>'cpdomodule')) }}
{!! Form::hidden('id', $selected['id'], array('id' => 'id')) !!}
{!! Form::hidden('is_active', $selected['is_active'], array('id' => 'is_active')) !!}

<div class="modal-body">
	<div class="row pt10">
		<div class="col-lg-12 col-md-16 col-sm-12" id="accordionFlushExample">  
			<div class="accordion accordion-flush">
				<div class="accordion-item">
					<h6 class="accordion-header" id="flush-headingone">
						<button class="accordion-button  btn-primary" type="button">
							Service Computation Type Development Permit
						</button>
					</h6>
					<div id="flush-collapseone" class="accordion-collapse collapse show">
						<div class="basicinfodiv">
							<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="cm_id_group">
                                        {{ Form::label('cm_id', __('Service Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                        <span class="validate-err">{{ $errors->first('cm_id') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::select('cm_id', 
                                                $cpdo_modules, 
                                                $selected['cm_id'], 
                                                ['class' => 'form-control', 'id' => 'cm_id','required'=>'required']) !!}
                                        </div>
                                        <span class="validate-err" id="err_cm_id"></span>
                                    </div>
                                </div>
                            </div>
						</div>

                        <div class="basicinfodiv">
							<div class="row">
                                <div class="col-md-12">
                                    <div class="table">
                                        <div class="float-end" style="margin-bottom: 5px;">
                                            <a href="#"
                                                class="btn btn-sm btn-primary add-more">
                                                <i class="ti-plus"></i>
                                            </a>
                                        </div>
                                        <table class="table-responsive" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="10%">No.</th>
                                                    <th width="30%">Description</th>
                                                    <th width="30%">Amount</th>
                                                    <th width="20%">Type</th>
                                                    <th width="10%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-body">
                                                @if (count($cpdo_development_lines) > 0)
                                                    @foreach ($cpdo_development_lines as $cpdo_k => $cpdo_v)
                                                        <tr>
                                                            <td>{{ ($cpdo_k + 1) }}</td>
                                                            <td>
                                                                <input type="text" name="data[{{ $cpdo_k + 1 }}][cdpcl_description]" class="form-control" placeholder="description" value="{{ $cpdo_v->cdpcl_description }}" />
                                                                <input type="hidden" name="data[{{ $cpdo_k + 1 }}][cdpcl_id]" class="form-control" placeholder="description" value="{{ $cpdo_v->id }}" />
                                                            </td>
                                                            <td>
                                                                <input type="text" name="data[{{ $cpdo_k + 1 }}][cdpcl_amount]" class="form-control input-amount" placeholder="Amount" value="{{ $cpdo_v->cdpcl_amount }}" />
                                                            </td>
                                                            <td class="imperial-code-group-{{ ($cpdo_k + 1) }}">
                                                                <select name="data[{{ ($cpdo_k + 1) }}][cis_id]" class="form-control imperial-code-{{ ($cpdo_k + 1) }}">
                                                                    @foreach ($cpdo_imperials as $k => $v)
                                                                    <option {{ $cpdo_v->cis_id == $k ? 'selected' : '' }} value="{{ $k }}">{{ $v }}</option>  
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <a href="javascript::void(0)"
                                                                    class="btn btn-sm btn-danger remove">
                                                                    <i class="ti-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td>1</td>
                                                        <td>
                                                            <input type="text" name="data[1][cdpcl_description]" class="form-control" placeholder="description" />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="data[1][cdpcl_amount]" class="form-control input-amount" placeholder="Amount" />
                                                        </td>
                                                        <td class="imperial-code-group-1">
                                                            <select name="data[1][cis_id]" class="form-control imperial-code-1">
                                                                @foreach ($cpdo_imperials as $k => $v)
                                                                <option value="{{ $k }}">{{ $v }}</option>  
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <a href="javascript::void(0)"
                                                                class="btn btn-sm btn-danger remove">
                                                                <i class="ti-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div> 
	<div class="modal-footer">
		<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="submit" value="Save Changes" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
		<!-- <input type="submit" id="submit" name="submit" value="Save Changes" class="btn  btn-primary"> -->
	</div>
</div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/development-permit-add.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
    $('#submit').click(function (e) {
        e.preventDefault();
        $(".validate-err").html('');
        var data = $("form").serialize();
        var obj={};
        for(var key in data){
            obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :DIR+'development-permit-computation/store/formValidation', // json datasource
            type: "POST", 
            data: $('#cpdomodule').serialize(),
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
                    $('#cpdomodule').submit();
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
