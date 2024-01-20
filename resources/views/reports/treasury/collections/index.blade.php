@extends('layouts.admin')

@section('page-title')
    {{__('Collections & Deposits')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reports') }}</li>
    <li class="breadcrumb-item">{{ __('Treasury') }}</li>
    <li class="breadcrumb-item">{{ __('Collections & Deposits') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div id="report-ledger-card" class="card noflow">
                <div class="card-header">
                    <h5 class="w-100">FILTER COLLECTIONS REPORT</h5>
                </div>
                <div class="card-body">
                    {{ Form::open(array('url' => 'reports/treasury/collections-and-deposits', 'class'=>'formDtls needs-validation', 'name' => 'reportsCollectionForm', 'method' => 'GET')) }}
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group required">
                                {{ Form::label('fund', 'Fund code', ['class' => '']) }}
                                {{
                                    Form::select('fund', $fund_codes, $value = '', ['id' => 'fund', 'class' => 'form-control select3', 'data-placeholder' => 'select a fund code'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('officer_id', 'Officer', ['class' => '']) }}
                                {{
                                    Form::select('officer_id', $officers, $value = '', ['id' => 'officer_id', 'class' => 'form-control select3', 'data-placeholder' => 'select an officer'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
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
                                    Form::select('export_as', $export_as, $value = 'pdf', ['id' => 'export_as', 'class' => 'form-control select3', 'data-placeholder' => 'select export as'])
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
<script src="{{ asset('js/forms/report-treasury-collection.js?v='.filemtime(getcwd().'/js/forms/report-treasury-collection.js').'') }}"></script>
@endpush