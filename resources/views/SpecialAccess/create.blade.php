{{ Form::open(array('url' => 'special-access-for-apps','class'=>'formDtls','id'=>'specialaccessforapps')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
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
			<div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('module_id', __('Groups | Module '),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('module_id') }}</span>
                    <div class="form-icon-user">
                         {{ Form::select('module_id',$arrMenuGroup,$data->module_id, array('id'=>'module_id','class' => 'form-control select3','required'=>'required')) }}
						 {{ Form::hidden('group_id',$data->group_id, array('id' => 'group_id')) }}
                    </div>
                    <span class="validate-err" id="err_module_id"></span>
                </div>
            </div> 
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('sub_module_id', __('Sub Module'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('sub_module_id') }}</span>
                    <div class="form-icon-user">
						{{
							Form::select('sub_module_id', $submodulelist, $data->sub_module_id, ['id' => 'sub_module_id', 'class' => 'form-control select3', 'data-placeholder' => 'select'])
						}}
                    </div>
                    <span class="validate-err" id="err_sub_module_id"></span>
                </div>
            </div> 
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('application', __('Application'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('icon') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('application', $data->application, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <span class="validate-err" id="err_application"></span>
                </div>
            </div> 
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('remarks', __('Remarks'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('slug') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('remarks', $data->remarks, array('class' => 'form-control','maxlength'=>'200')) }}
                    </div>
                    <span class="validate-err" id="err_slug"></span>
                </div>
            </div>       
        </div> 
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" name="submit" id="savechanges" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
        </div>
    </div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation_confir_mass.js') }}"></script>
<script>
$(document).ready(function () {
    $('#module_id').on('change', function () {
         var selectedMenuModule = $(this).val();
        //Use AJAX to fetch menu_sub_id options based on the selected module_id.
        $.ajax({
            url: DIR+'get_sub_module_list', // Replace with your server-side endpoint
            method: 'GET',
            data: {
                "_token": $("#_csrf_token").val(),
                "menu_module_id" : selectedMenuModule,
              },
            success: function (data) {
				if(data.length > 0 ){
					// Clear existing options and populate the sub_module_id dropdown with new options.
					var menuSubIdDropdown = $('#sub_module_id');
					menuSubIdDropdown.empty();
					menuSubIdDropdown.append('<option value="" data-placeholder="select">select</option>');

					for (var i = 0; i < data.length; i++) {
						menuSubIdDropdown.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
					}
					$('#sub_module_id').prop("required", true);
					$('#err_sub_module_id').append('The sub module id field is required.');
				}else{
					var menuSubIdDropdown = $('#sub_module_id');
					menuSubIdDropdown.empty();
					$('#sub_module_id').prop("required", false);
				}
            }
        });
    });
	$('#module_id').on('change', function () {
        var selectedMenuModule = $(this).val();
        $.ajax({
            url: DIR+'getmoduleid', 
            method: 'GET',
            data: {
                "_token": $("#_csrf_token").val(),
                "module_id" : selectedMenuModule,
              },
			 success: function (data) {
              $("#group_id").val(data)  
            }
        });
    });
	var shouldSubmitForm = false;
	$('#savechanges').click(function (e) {
		if (!shouldSubmitForm) {
			var form = $('#specialaccessforapps');
			Swal.fire({
				title: "Are you sure?",
				html: '<span>Some Details may not be editable after saving</span>',
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
					$('#savechanges').click();
				} else {
					console.log("Form submission canceled");
				}
			});

			e.preventDefault();
		}
	});
    
});
</script>