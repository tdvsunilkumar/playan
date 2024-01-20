{{ Form::open(array('url' => 'ApplicationPaymentServices')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
 {{ Form::hidden('fpayment_module_name',$data->fpayment_module_name, array('class' => 'form-control','id'=>'fpayment_module_name')) }}
    <style type="text/css">
        .accordion-button::after{background-image: url();}
		.action-btn.bg-info.ms-4 {height: 44px; width: 79px;}
		.modal-content {
			float: left;
		   margin-left: 50%;
		   margin-top: 50%;
		   transform: translate(-50%, -50%);
   }
    </style>
	<div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group" id="fund_idparrent">
                    {{ Form::label('fpayment_app_name', __('Application Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <div class="form-icon-user">
                     {{ Form::text('fpayment_app_name', $data->fpayment_app_name, array('class' => 'form-control','id'=>'fpayment_app_name','required','readonly')) }}
                    </div>
                    <span class="validate-err" id="err_busn_name"></span>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('department_cashering', __('Applicable Department[Cashiering]'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                    <span class="validate-err">{{ $errors->first('department_cashering') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('department_cashering','', array('class' => 'form-control','id'=>'department_cashering','required','readonly')) }}
                    </div>
                </div>
            </div>
         </div>
		<div class="row">
		   <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('tfoc_id', __('Payment Description [ Chart of Account ]'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('tfoc_id') }}</span>
                    <div class="form-icon-user">
                        {{ Form::select('tfoc_id',$arrctotfocs,$data->tfoc_id, array('class' =>'form-control select3','id'=>'tfoc_id')) }}
                    </div>
                    <span class="validate-err" id="err_tfoc_id"></span>
                </div>
            </div>
		 </div>
		 <div class="row">
			<div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('fpayment_remarks', __('Remarks'),['class'=>'form-label']) }}
                    <span class="validate-err">{{ $errors->first('fpayment_remarks') }}</span>
                    <div class="form-icon-user">
                        {{ Form::text('fpayment_remarks', $data->fpayment_remarks, array('class' => 'form-control','id'=>'fpayment_remarks')) }}
                    </div>
                    <span class="validate-err" id="err_fpayment_remarks"></span>
                </div>
            </div>
		 </div>
		 <div class="row">
			<div class="col-md-12">
				<h4 style="color:red;">NOTE: This form is Applicable to a system application that needs to be linked on a certain payment service.</h4>
            </div>
		 </div>
	</div>
	<div class="modal-footer">
		<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
            <i class="fa fa-save icon"></i>
            <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
        </div>
		<!-- <input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn  btn-primary"> -->
	</div>
</div>
{{Form::close()}}
<script src="{{ asset('js/ajax_validation_confir_mass.js') }}?rand={{rand(0,99)}}"></script>
<script type="text/javascript">
$(document).ready(function (){
	$('#tfoc_id').on('change',function(){
		var idget =$(this).val();
		getAccoutdescription(idget);
	 });
	 if($("#tfoc_id").val() > 0) { 
       var idget = $("#tfoc_id option:selected").val();
        getAccoutdescription(idget);
     }
	 
  function  getAccoutdescription(idget){
	   var tfoc_id =idget;
       $.ajax({
            url :DIR+'ApplicationPaymentServices-getmodulename', // json datasource
            type: "POST", 
            data: {"id": tfoc_id,"_token": $("#_csrf_token").val()},
            success: function(html){
			  $("#department_cashering").empty();
              $("#department_cashering").val(html);
			  
            }
        })
   }
});
</script>
  