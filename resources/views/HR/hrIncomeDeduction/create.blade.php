{{ Form::open(array('url' => 'hr-income-deduction','class'=>'formDtls')) }}
    {{ Form::hidden('id',$id, array('id' => 'id')) }}
    <style type="text/css">
    .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: black;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{padding-top:5px;}
</style> 
<div class="modal-body">
	<div class="row">
		<div class="col-md-4">
            <div class="form-group" id="hridt_id_div">
                {{ Form::label('hridt_id', __('Application Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <span class="validate-err">{{ $errors->first('hridt_id') }}</span>
                <div class="form-icon-user">
                    {{ Form::select('hridt_id',$app_type,$data->hridt_id, array('class' => 'form-control select3','id'=>'hridt_id','required'=>'required')) }}
                </div>
                <span class="validate-err" id="err_hridt_id"></span>
            </div>
        </div>
		<div class="col-md-4">
            <div class="form-group">
                {{ Form::label('hriad_ref_no', __('Reference No'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <span class="validate-err">{{ $errors->first('hriad_ref_no') }}</span>
                <div class="form-icon-user">
                    {{ Form::text('hriad_ref_no',$hriad_ref_no, array('class' => 'form-control ','id'=>'hriad_ref_no','readonly'=>'readonly')) }}
                </div>
                <span class="validate-err" id="err_hriad_ref_no"></span>
            </div>
        </div>
		<div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hriad_description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hriad_description') }}</span>
				<div class="form-icon-user">
					{{ Form::text('hriad_description',$data->hriad_description, array('class' => 'form-control','id'=>'hriad_description','required'=>'required')) }}
				</div>
				<span class="validate-err" id="err_hriad_description"></span>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
            <div class="form-group">
                {{ Form::label('hriad_amount', __('Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <span class="validate-err">{{ $errors->first('hriad_amount') }}</span>
                <div class="form-icon-user">
                    {{ Form::text('hriad_amount',currency_format($data->hriad_amount), array('class' => 'form-control ','id'=>'hriad_amount', ($data->hridt_id > 2 )? 'readonly':'')) }}
                </div>
                <span class="validate-err" id="err_hriad_amount"></span>
            </div>
        </div>
		<div class="col-md-6">
            <div class="form-group" id="hrlc_id_div">
                {{ Form::label('hrlc_id', __('Cycle'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <span class="validate-err">{{ $errors->first('hrlc_id') }}</span>
                <div class="form-icon-user">
                    {{ Form::select('hrlc_id',$cycle,$data->hrlc_id, array('class' => 'form-control select3','id'=>'hrlc_id', ($data->hridt_id > 2 || $data->hridt_id === 1)? 'disabled':'')) }}
                </div>
                <span class="validate-err" id="err_hrlc_id"></span>
            </div>
        </div>
	</div>
	<div class="row">
		<div class="col-xl-12">
			<div class="float-end">
				<a href="#" id="btnAddEmployee" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="ti-plus"></i>
				</a>
			</div>
		</div>	
	</div>	
	<div class="row">
		<div class="col-xl-12">
			<div class="card">
				<div class="card-body table-border-style">
					<div class="table-responsive">
						<table class="table" id="selected_emp_list">
							<thead>
								<tr>
									<th>{{__('SR No')}}</th>
									<th>{{__('Employee Name')}}</th>
									<th>{{__('Department')}}</th>
									<th>{{__('Designation')}}</th>
									<th>{{__('Efectivity Date')}}</th>
									<th>{{__('Deducation Amount')}}</th>
									<th>{{__('Balance')}}</th>
									<th>{{__('Action')}}</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
		<div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
			<i class="fa fa-save icon"></i>
			<input type="submit" name="submit" value="{{ ($id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
		</div>
	</div>
</div>    

<div class="modal fade" id="orderofpaymentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
           <div class="modal-header">
                <h4 class="modal-title">Add Employee</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="container">
                <div class="modal-body">
					<div class="row" id="this_is_filter">
						<div class="col-sm-12">
							<div class=" mt-2 " id="multiCollapseExample1">
								<div class="card">
									<div class="card-body">
										<form method="GET" action="#" accept-charset="UTF-8" id="product_service">
											<div class="d-flex align-items-center justify-content-end">
												<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
													<div class="btn-box">
														{{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q_emp')) }}
													</div>
												</div>
												<div class="col-auto float-end ms-2">
													<a href="#" class="btn btn-sm btn-primary" id="btn_search_emp">
														<span class="btn-inner--icon"><i class="ti-search"></i></span>
													</a>
													<a href="#" class="btn btn-sm btn-danger" id="btn_emp_clear">
														<span class="btn-inner--icon"><i class="ti-trash"></i></span>
													</a>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-xl-12">
							<div class="card">
								<div class="card-body table-border-style">
									<div class="table-responsive">
										<table class="table" id="Jq_datatableEmplist">
											<thead>
												<tr>
													<th><input type='checkbox' class='selected_emp_id' id="select-all"></th>
													<th>{{__('Employee Name')}}</th>
													<th>{{__('Department')}}</th>
													<th>{{__('Designation')}}</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8 offset-md-4"><!-- Move col-md-4 to the right -->
							<div class="form-group" id="hridt_id_div">
								{{ Form::label('eft_date', __('Effective Date'), ['class' => 'form-label']) }}
								<span class="text-danger">*</span>
								<span class="validate-err">{{ $errors->first('eft_date') }}</span>
								<div class="form-icon-user">
									<!-- Set default current date -->
									{{ Form::date('eft_date', now()->toDateString(), ['class' => 'form-control', 'id' => 'eft_date', 'required' => 'required']) }}
								</div>
								<span class="validate-err" id="err_eft_date"></span>
							</div>
						</div>
					</div>
					{{--<input type="hidden" id="hridt_type">
					<div class="hidden" id="selected_emp_checkbox">

					</div>--}}
                </div>
                <div class="modal-footer"> 
                    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light closeOrderModal" data-bs-dismiss="modal">
					<input type="button" value="{{__('Save')}}" id="saveSelEmp" class="btn btn-primary">
                </div>
                </div>
            </div>
        </div>
    </div>
</div>  

<script>
    var selValues = @json($selectedEmp);
</script>
    {{Form::close()}}
 <script src="{{ asset('js/ajax_validation.js') }}"></script>  
 <script src="{{ asset('js/HR/add_hrIncomeDeduction.js') }}"></script> 

  
 
           