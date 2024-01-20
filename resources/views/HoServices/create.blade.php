{{ Form::open(array('url' => 'health-safety-setup-data-service','class'=>'formDtls','id'=>'service')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
    <div class="modal-body">
        <div class="row">
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('tfoc_id', __('Tax, Fees & Other Charges'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('tfoc_id') }}</span>
                    <div class="form-icon-user">
                         {{ Form::select('tfoc_id',$getServices,$data->tfoc_id, array('class' =>'form-control select3','id'=>'tfoc_id')) }}
                    </div>
                    <span class="validate-err" id="err_tfoc_id"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('service_fee', __('Service Fee'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('service_fee') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('service_fee','',array('class' => 'form-control','id'=>'service_fee','readonly')) }}
                    </div>
                </div>
            </div>		
        </div>
		<div class="row">
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('ho_service_name', __('Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('ho_service_name') }}</span>
                    <div class="form-icon-user">
                      {{ Form::text('ho_service_name',$data->ho_service_name,array('class' => 'form-control','id'=>'ho_service_name','required'=>'required')) }}   
                    </div>
                    <span class="validate-err" id="err_tfoc_id"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('ho_service_description', __('Service Description'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('services_name') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ho_service_description',$data->ho_service_description,array('class' => 'form-control','id'=>'ho_service_description')) }}
                    </div>
                </div>
            </div>
		</div>
		<div class="row">
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('ho_service_department', __('Department'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('ho_service_department') }}</span>
                    <div class="form-icon-user">
						{{
							Form::select('ho_service_department',$servicedepartment, $data->ho_service_department, ['id' => 'ho_service_department', 'class' => 'form-control select3'])
						}}					  
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('ho_service_form', __('Service Form'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('services_name') }}</span>
                    <div class="form-icon-user">
						{{
							Form::select('ho_service_form',$serviceform,$data->ho_service_form, ['id' => 'ho_service_form', 'class' => 'form-control select3'])
						}}
					</div>
                </div>
            </div>
		</div>
		<div class="row" id="showhidetaxfee" style="margin-bottom:200px;">
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('top_transaction_type_id', __('Tax Order Of Payment (Transaction Type)'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('top_transaction_type_id') }}</span>
                    <div class="form-icon-user">
						{{ Form::select('top_transaction_type_id',$arrtransactiontype,$data->top_transaction_type_id, array('class' =>'form-control select3','id'=>'top_transaction_type_id')) }}
                    </div>
                </div>
            </div>
			<div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('ho_service_amount', __('Amount'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('ho_service_amount') }}</span>
                    <div class="form-icon-user">
                         {{ Form::text('ho_service_amount',$data->ho_service_amount,array('class' => 'form-control','id'=>'ho_service_amount')) }}
                    </div>
                </div>
            </div>
		</div>
        <div class="modal-footer">
            <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
            <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
                <i class="fa fa-save icon"></i>
                <input type="submit" id="savechanges" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
            </div>
            <!-- <input type="submit" name="submit"  value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
        </div>
    </div>
{{Form::close()}}

 <script src="{{ asset('js/HealthSafetySetupDataService.js') }}"></script>
 <script src="{{ asset('js/ajax_validation.js') }}"></script>
 <script type="text/javascript">
    $(document).ready(function () {

    var shouldSubmitForm = false;
    $('#savechanges').click(function (e) {
            var form = $('#service');
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
            var form = $('#service');
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
                
            }

            return isValid;
        }
    $("#commonModal").find('.body').css({overflow: 'unset'})

	 $("#showhidetaxfee").hide();
     if($("#tfoc_id option:selected").val() > 0 ){
          var val = $("#tfoc_id option:selected").val();
          getserviceName(val);
		  $("#showhidetaxfee").show();
     }
    	$("#showhidetaxfee").hide();
         if($("#tfoc_id option:selected").val() > 0 ){
              var val = $("#tfoc_id option:selected").val();
              getserviceName(val);
    		  $("#showhidetaxfee").show();
         }

         $('#tfoc_id').on('change', function() {
            var id =$(this).val();
            getserviceName(id);
    		$("#showhidetaxfee").show();
    		if(id == 0){
    			$('#top_transaction_type_id').val([]);
    			$('#ho_service_amount').val('');
    			$("#showhidetaxfee").hide();
    		}else{
    			$('#top_transaction_type_id').val([]);
    			$('#ho_service_amount').val('');
    			$("#showhidetaxfee").show();
    		}
        });
	
    	function  getserviceName(aglcode){
         var id =aglcode;
           $.ajax({
                url :DIR+'health-safety-setup-data-service/getserviceName', // json datasource
                type: "POST", 
                data: {
                        "id": id, "_token": $("#_csrf_token").val(),
                    },
                success: function(html){
                   $("#service_fee").val(html);
                }
            })
        } 
});
</script>
  