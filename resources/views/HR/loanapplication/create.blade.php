{{ Form::open(array('url' => 'loan-application','class'=>'formDtls','enctype'=>'multipart/form-data')) }}
{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
{{ Form::hidden('submit_type',0,array('id' => 'submit_type')) }}
<style>
      .field-requirement-details-status {
        border-bottom: 1px solid #f1f1f1;
        font-size: 13px;
        color: #fff;
        background: #20B7CC;
        text-transform: uppercase;
        margin: 20px 0px 6px 0px;
        margin-top: 20px;
    }
    .field-requirement-details-status label{padding-top:10px;}
    .permitclick{  cursor:pointer;color:skyblue; }
    .orpayment > .row{ padding:10px; }
    .btn{padding: 0.575rem 0.5rem;}
    .accordion-button::after {
    background-image: url();
  }
 </style>
<div class="modal-body">
	<div class="row">
		 <div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrla_employeesid', __('Employee Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrla_employeesid') }}</span>
				<div class="form-icon-user">
					{{ Form::select('hrla_employeesid',$arrEmployee,$data->hrla_employeesid, array('class' => 'form-control select3','id'=>'hrla_employeesid','required' => 'required',$data->hrla_approved_by ? 'readonly':'')) }}
				</div>
				<span class="validate-err" id="err_hrla_employeesid"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrla_department_id', __('Department'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrla_department_id') }}</span>
				<div class="form-icon-user" style="pointer-events:none;">
					{{ Form::select('hrla_department_id',$arrDepartment,$data->hrla_department_id, array('class' => 'form-control','id'=>'hrla_department_id','readonly')) }}
				</div>
				<span class="validate-err" id="err_hrla_department_id"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group" style="pointer-events:none;">
				{{ Form::label('hrla_division_id', __('Division'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrla_division_id') }}</span>
				<div class="form-icon-user">
					{{ Form::select('hrla_division_id',$arrDivision,$data->hrla_division_id, array('class' => 'form-control','id'=>'hrla_division_id','readonly')) }}
				</div>
				<span class="validate-err" id="err_hrla_division_id"></span>
			</div>
		</div>
	</div>
	<div class="row">
		 <div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrla_application_no', __('Loan Application NO'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrla_application_no') }}</span>
				<div class="form-icon-user">
					{{ Form::text('hrla_application_no',$data->hrla_application_no, array('class' => 'form-control','id'=>'hrla_application_no','readonly')) }}
				</div>
				<span class="validate-err" id="err_hrla_application_no"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group" id="parrent_hrla_loan_status">
				{{ Form::label('hrla_loan_status', __('Loan Status'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrla_loan_status') }}</span>
				<div class="form-icon-user">
					{{ Form::select('hrla_loan_status',$arrhrloanstatus,$data->hrla_loan_status, array('class' => 'form-control ','id'=>'hrla_loan_status','required' => 'required',$data->hrla_approved_by ? 'readonly':'')) }}
				</div>
				<span class="validate-err" id="err_hrla_loan_status"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrla_loan_date', __('Loan Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrla_loan_date') }}</span>
				<div class="form-icon-user">
					{{ Form::date('hrla_loan_date',$data->hrla_loan_date, array('class' => 'form-control ','id'=>'hrla_loan_date','required' => 'required',$data->hrla_approved_by ? 'readonly':'')) }}
				</div>
				<span class="validate-err" id="err_hrla_loan_date"></span>
			</div>
		</div>
	</div>
	<div class="row">
		 <div class="col-md-12">
			<div class="form-group">
				{{ Form::label('hrla_application_no', __('Loan Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrla_application_no') }}</span>
				<div class="form-icon-user">
					{{ Form::text('hrla_loan_description',$data->hrla_loan_description, array('class' => 'form-control','id'=>'hrla_application_no','required' => 'required',$data->hrla_approved_by ? 'readonly':'')) }}
				</div>
				<span class="validate-err" id="err_hrla_application_no"></span>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group" id="parrent_hrla_id">
				{{ Form::label('hrla_id', __('Loan Type'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrla_id') }}</span>
				<div class="form-icon-user">
					{{ Form::select('hrla_id',$arrHrloantype,$data->hrla_id, array('class' => 'form-control ','id'=>'hrla_id','required' => 'required',$data->hrla_approved_by ? 'readonly':'')) }}
				</div>
				<span class="validate-err" id="err_hrla_id"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrla_loan_amount', __('Loan Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrla_loan_amount') }}</span>
				<div class="form-icon-user">
					{{ Form::number('hrla_loan_amount',$data->hrla_loan_amount, array('class' => 'form-control onchangekeyup','id'=>'hrla_loan_amount','required' => 'required',$data->hrla_approved_by ? 'readonly':'')) }}
				</div>
				<span class="validate-err" id="err_hrla_loan_amount"></span>
			</div>
		</div>
	</div>
	<div class="row">
		 <div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrla_interest_percentage', __('Interest Percentage'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrla_interest_percentage') }}</span>
				<div class="form-icon-user">
					{{ Form::text('hrla_interest_percentage',$data->hrla_interest_percentage, array('class' => 'form-control onchangekeyup','id'=>'hrla_interest_percentage','required' => 'required',$data->hrla_approved_by ? 'readonly':'')) }}
				</div>
				<span class="validate-err" id="err_hrla_interest_percentage"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrla_interest_amount', __('Interest Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrla_interest_amount') }}</span>
				<div class="form-icon-user">
					{{ Form::number('hrla_interest_amount',$data->hrla_interest_amount, array('class' => 'form-control disable','id'=>'hrla_interest_amount','readonly')) }}
				</div>
				<span class="validate-err" id="err_hrla_interest_amount"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group" id="parrent_hrlc_id">
				{{ Form::label('hrlc_id', __('Cycle'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrlc_id') }}</span>
				<div class="form-icon-user">
					{{ Form::select('hrlc_id',$arrHrloanCycle,$data->hrlc_id, array('class' => 'form-control ','id'=>'hrlc_id','required' => 'required',$data->hrla_approved_by ? 'readonly':'')) }}
				</div>
				<span class="validate-err" id="err_hrla_loan_date"></span>
			</div>
		</div>
	</div>
	<div class="row">
		 <div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrla_amount_disbursed', __('Amount Disbursed'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrla_amount_disbursed') }}</span>
				<div class="form-icon-user">
					{{ Form::number('hrla_amount_disbursed',$data->hrla_amount_disbursed, array('class' => 'form-control','id'=>'hrla_amount_disbursed')) }}
				</div>
				<span class="validate-err" id="err_hrla_amount_disbursed"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrla_installment_amount', __('Installment Amount'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrla_installment_amount') }}</span>
				<div class="form-icon-user">
					{{ Form::number('hrla_installment_amount',$data->hrla_installment_amount, array('class' => 'form-control ','id'=>'hrla_installment_amount','readonly','required' => 'required')) }}
				</div>
				<span class="validate-err" id="err_hrla_loan_status"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				{{ Form::label('hrla_effectivity_date', __('Effectivity Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
				<span class="validate-err">{{ $errors->first('hrla_effectivity_date') }}</span>
				<div class="form-icon-user">
					{{ Form::date('hrla_effectivity_date',$data->hrla_effectivity_date, array('class' => 'form-control ','id'=>'hrla_effectivity_date','required' => 'required',$data->hrla_approved_by ? 'readonly':'')) }}
				</div>
				<span class="validate-err" id="err_hrla_effectivity_date"></span>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group" id="parrent_hrla_approved_by">
				{{ Form::label('hrla_approved_by', __('Approved By'),['class'=>'form-label']) }}
				<span class="validate-err">{{ $errors->first('hrla_approved_by') }}</span>
				<div class="form-icon-user">
					{{ Form::select('hrla_approved_by',$arrEmployee,$data->hrla_approved_by, array('class' => 'form-control disabled','id'=>'hrla_approved_by','disabled')) }}
				</div>
				<span class="validate-err" id="err_hrla_approved_by"></span>
			</div>
		</div>
	</div>
	 <div class="row hide" style="width: 100%; margin: 0px;padding-bottom: 20px;" id="checkdetaildiv">
			<div class="row field-requirement-details-status">
				<div class="col-lg-1 col-md-1 col-sm-1">
					{{Form::label('cycle',__('Cycle'),['class'=>'form-label'])}}
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1">
					{{Form::label('balance',__('Balance'),['class'=>'form-label'])}}
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2">
					{{Form::label('payment_date',__('Payment Date'),['class'=>'form-label numeric'])}}
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2">
					{{Form::label('installment_amount',__('Installment Amount'),['class'=>'form-label numeric'])}}
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2">
					{{Form::label('paid_amount',__('Paid Amount'),['class'=>'form-label'])}}
				</div>
				 <div class="col-lg-2 col-md-2 col-sm-2">
					{{Form::label('paid_date',__('Paid Date'),['class'=>'form-label'])}}
				</div>
				 <div class="col-lg-2 col-md-2 col-sm-2">
					{{Form::label('payroll_ref',__('Payroll Ref#'),['class'=>'form-label'])}}
				</div>
			</div>
			<span class="checkDetails activity-details tablestripped" id="checkDetails">
				@php $i=1; @endphp
				@foreach($arrPaymentDetails as $key=>$val)
				<div class="row removecheckdata" style="padding: 5px 0px;">
					 <div class="col-lg-1 col-md-1 col-sm-1">
						{{ Form::hidden('ledger_id[]',$val->id, array('id' => 'id')) }} 
						{{$i}}
					</div>
					<div class="col-lg-1 col-md-1 col-sm-1">
						{{ Form::text('hrll_balance[]',$val->hrll_balance,array('readonly','class' => 'form-control','required'=>'required','id'=>'hrll_balance')) }}
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
						{{ Form::date('hrll_payment_date[]',$val->hrll_payment_date,array('readonly','class' => 'form-control','required'=>'required','id'=>'hrll_payment_date')) }}
					</div>
					 <div class="col-lg-2 col-md-2 col-sm-2">
						{{Form::text('hrll_installment_amount[]',$val->hrll_installment_amount,array('readonly','class'=>'form-control checktype','id'=>'checktype'))}}
					</div>
					 <div class="col-lg-2 col-md-2 col-sm-2">
						{{Form::text('hrll_paid_amount[]',$val->hrll_paid_amount,array('readonly','class'=>'form-control','id'=>'hrll_paid_amount'))}}
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
						{{Form::date('hrll_paid_date[]',$val->hrll_paid_date,array('readonly','class'=>'form-control checkdate','id'=>'hrll_paid_date'))}}
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
						{{Form::text('hrll_payroll_ref_no[]',$val->hrll_payroll_ref_no,array('readonly','class'=>'form-control','id'=>'hrll_payroll_ref_no'))}}
					</div>
					 @php $i++; @endphp
				</div>
				 @endforeach
		</span>
	</div>
	<div class="modal-footer">
		<input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
		@if($data->id)
		<div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
			<input type="submit" name="submit" value="Approve" class="btn btn-primary approve" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;" {{$data->hrla_approved_by ? 'disabled':''}}>
		</div>
		@endif
		<div class="button" style="background: #20b7cc;padding-left: 8px;color: #fff;border-radius: 5px;">
			<i class="fa fa-save icon"></i>
			<input type="submit" name="submit" value="{{ ($data->id)>0?__('Save Changes'):__('Save Changes')}}" class="btn btn-primary add" style="background: #20b7cc;padding-left: 4px;border:1px solid #20b7cc;color: #fff;padding: 9px;border-radius: 5px;">
		</div>
	</div>
 </div>    
{{Form::close()}}  
<script src="{{ asset('js/ajax_validation.js') }}"></script>
 <script src="{{ asset('js/HR/add_hrloanapplication.js') }}"></script>  
 <script>
		@if(is_null($data->hrla_approved_by))
		computeInterest()
		@endif

 </script>