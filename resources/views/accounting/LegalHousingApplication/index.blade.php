@extends('layouts.admin')
@section('page-title')
    {{__('Housing Application')}}
@endsection
@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/dropzone/dropzone.css?v='.filemtime(getcwd().'/assets/vendors/dropzone/dropzone.css').'') }}"/>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Housing Application')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
         <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
             <a href="#" data-size="xl" data-url="{{ url('/legal-housing-application/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Legal Application')}}" class="btn btn-sm btn-primary modal-dialog-scrollable">
            <i class="ti-plus"></i>
        </a>
    </div>
@endsection

@section('content')
   <div class="row hide" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="#" accept-charset="UTF-8" id="product_service">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2">
                                <a href="#" class="btn btn-sm btn-primary" id="btn_search">
                                    <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
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
                        <table class="table" id="Jq_datatablelist">
                            <thead>
                            <tr>
                                <th>{{__('SR No')}}</th>
                                <th>{{__('TRANSACTION NO')}}</th>
                                <th>{{__('Application Date')}}</th>
                                <th>{{__('TOP NO')}}</th>
                                <th>{{__('Payor')}}</th>
                                <th>{{__('type of transaction')}}</th>
                                <th>{{__('amount')}}</th>
                                <th>{{__('penalty')}}</th>
                                <th>{{__('balance')}}</th>
                                <th>{{__('or no')}}</th>
                                <th>{{__('or date')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                          
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('SocialWelfare.includes.secondModal')
    <script src="{{ asset('js/accounting/LegalHousingApplication.js?v='.filemtime(getcwd().'/js/accounting/LegalHousingApplication.js').'') }}"></script>
@endsection

@push('scripts')
@endpush
