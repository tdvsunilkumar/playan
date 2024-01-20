@extends('layouts.admin')

@section('page-title')
    {{__('Citizens')}}
@endsection

@push('script-page')
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    @switch(Route::current()->getName())
        @case('citizen.index')
        <li class="breadcrumb-item">{{ __('Health And Safety') }}</li>
        @break
        @case('social.citizen.index')
        <li class="breadcrumb-item">{{ __('Social Welfare') }}</li>
        @break
        @case('eco.citizens')
        <li class="breadcrumb-item">{{ __('Economic & Investment') }}</li>
        @break
        @default
        @break
    @endswitch
    <li class="breadcrumb-item">{{ __('Citizens') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
            <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i></a>
            <a href="#" data-size="lg" data-url="{{ url('/citizens/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" id="addCitizen" title="{{__('Manage Citizen Details')}}" class="btn btn-sm btn-primary" data-controls-modal="your_div_id" data-backdrop="static" data-keyboard="false">
                <i class="ti-plus"></i>
            </a>
        
    </div>
@endsection
    {{ Form::hidden('isopen',$isopen, array('id' => 'isopen')) }}

@section('content')
<div class="row hide create-form" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
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
                                    <span class="btn-inner--icon"><i class="ti-trash "></i></span>
                                </a>
                            </div>

                        </div>
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
                        <table class="table main-citizen-table" id="citizen-datatable">
                            <thead>
                            <tr>
                                <th>{{__('No')}}</th>
                                <th>{{__('Full Name')}}</th>
                                <th>{{__('Barangay')}}</th>
                                <th>{{__('Birthdate')}}</th>
                                <th>{{__('Gender')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                           </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('scripts')
<!-- for eco -->
<script src="{{ asset('assets/js/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
<!-- end for eco -->
    
    <script src="{{ asset('js/SocialWelfare/social-welfare-index.js?v='.filemtime(getcwd().'/js/SocialWelfare/social-welfare-index.js').'') }}"></script>
    <script src="{{ asset('js/SocialWelfare/Citizen.js?v='.filemtime(getcwd().'/js/SocialWelfare/Citizen.js').'') }}"></script>
@endpush