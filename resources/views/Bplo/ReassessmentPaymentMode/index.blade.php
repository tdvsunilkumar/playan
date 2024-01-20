@extends('layouts.admin')
@section('page-title')
    {{__('Re-assessment Payment Mode')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Re-assessment Payment Mode')}}</li>
@endsection
@section('content')
<div class="row">
	<div class="col-xl-12">
		<div class="card">
        <div class="card-body">
			<div class="row">
			{{ Form::open(array('url' => 'bplo-reassessment-payment-mode/store','class'=>'formDtls', 'files' => true)) }}
					@csrf
				@if(isset($data->id) > 0)
					{{ Form::hidden('id',$data->id, array('id' => 'id')) }}
				
					@foreach($listpaymentmode as $key=>$val)
					<div class="col-md-12">
						<div class="form-group m-form__group">
							{{ Form::radio('pmode_policy', $key, ($data->pmode_policy == $key)?true:false, array('id'=>'pmode_policy','class'=>'form-check-input code','required'=>'required')) }}
							{{ Form::label('pmode_policy', $val, ['class' => 'fs-6 fw-bold']) }}
							<span class="m-form__help text-danger"></span>
						</div>
					</div>
					@endforeach
					 <div class="col-md-12">
						<div class="form-group">
							{{ Form::label('remark', __('Remark'),['class'=>'form-label']) }}
							<span class="validate-err">{{ $errors->first('remark') }}</span>
							<div class="form-icon-user">
								 {{ Form::textarea('remark',$data->remark, array('rows'=>'3','class' => 'form-control','maxlength'=>'150')) }}
							</div>
							<span class="validate-err" id="err_reg_remarks"></span>
						</div>
					</div>
				@else
					@foreach($listpaymentmode as $key=>$val)
					<div class="col-md-12">
						<div class="form-group m-form__group">
							{{ Form::radio('pmode_policy', $key, ('' == $key)?true:false, array('id'=>'pmode_policy','class'=>'form-check-input code','required'=>'required')) }}
							{{ Form::label('pmode_policy', $val, ['class' => 'fs-6 fw-bold']) }}
							<span class="m-form__help text-danger"></span>
						</div>
					</div>
					@endforeach
					 <div class="col-md-12">
						<div class="form-group">
							{{ Form::label('remark', __('Remark'),['class'=>'form-label']) }}
							<span class="validate-err">{{ $errors->first('remark') }}</span>
							<div class="form-icon-user">
								 {{ Form::textarea('remark','', array('rows'=>'3','class' => 'form-control','maxlength'=>'150')) }}
							</div>
							<span class="validate-err" id="err_reg_remarks"></span>
						</div>
					</div>
				@endif
				 <div class="modal-footer">
					<input type="submit" name="submit"  value="{{('Save')}}" class="btn  btn-primary">
				</div>
			{{ Form::close() }}
			</div>
		</div>
		</div>
	</div>
</div> 
<script src="{{ asset('js/Bplo/reassessmentpaymentmode.js') }}"></script>
@endsection
