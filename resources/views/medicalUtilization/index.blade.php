@extends('layouts.admin')

@section('page-title')
    {{__('Inventory Report')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Health and Safety') }}</li>
    <li class="breadcrumb-item">{{ __('Reports') }}</li>
    <li class="breadcrumb-item">{{ __('Inventory-Utilization') }}</li>
@endsection

@section('content')
<div class="row">
        <div class="col-xl-12">
            <div id="report-ledger-card" class="card noflow" style="min-height: 400px">
                <div class="card-header">
                    <h5 class="w-100">Inventory Utilization</h5>
                </div>
                <div class="card-body">
                    {{ Form::open(array('url' => 'reports-inventory-utilization/store','class'=>'formDtls needs-validation', 'name' => 'medicalUtilizationForm', 'method' => 'POST')) }}
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                            {{ Form::label('util_rep_type', __('Receive Type'),['class'=>'form-label']) }}
                            {!! Form::select('type',
                                                ['1' => 'Internal'], 
                                                2, ['class' => 'form-control rec_type select3',  'id' => 'rec_type', 'readonly']) !!}
                                <span class="validate-err" id="err_util_rep_type"></span>
                            </div>
                        </div>
                        <!-- <div class="col-sm-6">
                            <div class="form-group m-form__group">
                            {{ Form::label('supplier', __('Supplier'),['class'=>'form-label']) }}
                                        <span class="validate-err">{{ $errors->first('supplier') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::select('supplier_id',
                                                $select_suppliers, 
                                                null, ['class' => 'form-control supplier', 'id' => 'supplier']) !!}
                                        </div>
                                        <span class="validate-err" id="err_supplier_id"></span>
                            </div>
                        </div> -->
                        <!-- <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                            {{ Form::label('date_range', __('Date Range'),['class'=>'form-label']) }}
                                        <span class="validate-err">{{ $errors->first('date_range') }}</span>
                                        <div class="form-icon-user">
                                            {!! Form::select('util_rep_range',
                                                $select_date_ranges, 
                                                null, ['class' => 'form-control date_range', 'id' => 'date_range']) !!}
                                        </div>
                                        <span class="validate-err" id="err_util_rep_range"></span>
                            </div>
                        </div> -->
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('year', __('Year'),['class'=>'form-label']) }}
                                <span style="color: red">*</span>
                                <span class="validate-err">{{ $errors->first('year') }}</span>
                                <div class="form-icon-user">
                                    {!! Form::select('year',
                                        ['' => 'Select Year', date('Y') => date('Y'), (date('Y') - 1) => (date('Y') - 1), (date('Y') - 2) => (date('Y') - 2)], 
                                        null, ['class' => 'form-control year select3', 'id' => 'year','required']) !!}
                                </div>
                                <span class="validate-err" id="err_util_rep_year"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('util_rep_remarks', __('Category'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('remarks') }}</span>
                                <div class="form-icon-user">
                                {!! Form::select('category',
                                        $arrcategory, 
                                        null, ['class' => 'form-control util_rep_remarks select3', 'id' => 'util_rep_remarks']) !!}
                                    </div>
                                <span class="validate-err" id="err_util_rep_remarks"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex justify-content-end mt-2 mb-2">
                            <button type="submit" class="btn submit-btn print-btn bg-print align-middle justify-content-center"><i class="la la-download"></i> Export</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="{{ asset('js/ajax_validation.js') }}"></script>
<script src="{{ asset('js/inventory_utilization_create.js') }}"></script>
