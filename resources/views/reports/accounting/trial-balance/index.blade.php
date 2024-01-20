@extends('layouts.admin')

@section('page-title')
    {{__('Trial Balance')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reports') }}</li>
    <li class="breadcrumb-item">{{ __('Accounting') }}</li>
    <li class="breadcrumb-item">{{ __('Trial Balance') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div id="report-ledger-card" class="card noflow" style="min-height: 400px">
                <div class="card-header">
                    <h5 class="w-100">FILTER TRIAL BALANCE REPORT</h5>
                </div>
                <div class="card-body">
                    {{ Form::open(array('url' => 'reports/accounting/trial-balance', 'class'=>'formDtls needs-validation', 'name' => 'reportsAcctgTrialBalanceForm', 'method' => 'GET')) }}
                    @csrf
                    <div class="row">
                        <!-- <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('code', 'Account Code', ['class' => '']) }}
                                {{
                                    Form::select('code', $codes, $value = '', ['id' => 'code', 'class' => 'form-control select3', 'data-placeholder' => 'select an account code'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div> -->
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required">
                                {{ Form::label('fund_code_id', 'Fund Code', ['class' => '']) }}
                                {{
                                    Form::select('fund_code_id', $fund_codes, $value = '', ['id' => 'fund_code_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a fund code'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('category', 'Category', ['class' => '']) }}
                                {{
                                    Form::select('category', $categories, $value = '', ['id' => 'category', 'class' => 'form-control select3', 'data-placeholder' => 'select a category'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('name', 'Description', ['class' => '']) }}
                                {{
                                    Form::select('name', $name, $value = '', ['id' => 'name', 'class' => 'form-control select3', 'data-placeholder' => 'select a name'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('date_from', 'Date From', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'date_from', $value = '', 
                                    $attributes = array(
                                        'id' => 'date_from',
                                        'class' => 'form-control form-control-solid',
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('date_to', 'Date To', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::date($name = 'date_to', $value = '', 
                                    $attributes = array(
                                        'id' => 'date_to',
                                        'class' => 'form-control form-control-solid'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('export_as', 'Export As', ['class' => '']) }}
                                {{
                                    Form::select('export_as', $export_as, $value = 'pageview', ['id' => 'export_as', 'class' => 'form-control select3', 'data-placeholder' => 'select export as'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('order_by', 'Order By', ['class' => '']) }}
                                {{
                                    Form::select('order_by', $orders, $value = 'ASC', ['id' => 'order_by', 'class' => 'form-control select3', 'data-placeholder' => 'select order by'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex justify-content-end mt-2 mb-2">
                            <button type="button" class="btn submit-btn print-btn bg-print align-middle justify-content-center"><i class="la la-download"></i> Export</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/forms/report-acctg-trial-balance.js?v='.filemtime(getcwd().'/js/forms/report-acctg-trial-balance.js').'') }}"></script>
@endpush