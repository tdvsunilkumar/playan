@extends('layouts.admin')

@section('page-title')
    {{__('Payee [Creditors]')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Finance') }}</li>
    <li class="breadcrumb-item">{{ __('Payee [Creditors]') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" title="{{__('Create Payee')}}" class="btn btn-sm btn-primary add-btn">
            <i class="ti-plus"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <p class="text-left m-0">
                                    <span class="pull-left">Show</span>
                                    <select id="perPage" class="form-select pull-left">
                                        <option value="5" selected="selected">5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span class="pull-left">Entries</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <span class="pull-right me-2">Search</span>
                            <div class="input-group search-group">
                                <input class="form-control border-end-0 border" type="search" id="keywords" placeholder="search for keywords">
                                <span class="input-group-append search-group">
                                    <button class="search-btn btn btn-outline-secondary bg-white border-start-0 border ms-n5" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 pe-3">
                            <div id="datatable-result"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('finance.cbopayee.create')
@endsection
@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
    <script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
    <script src="{{ asset('js/datatables/cbopayee.js?v='.filemtime(getcwd().'/js/datatables/cbopayee.js').'') }}" type="text/javascript"></script>
    <script src="{{ asset('js/forms/cbopayee.js?v='.filemtime(getcwd().'/js/forms/cbopayee.js').'') }}" type="text/javascript"></script>
@endpush