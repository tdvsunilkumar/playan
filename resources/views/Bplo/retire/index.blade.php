@extends('layouts.admin')
@section('page-title')
    {{__('Retirement of Business')}}
@endsection
@push('script-page')
@endpush
<div>
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
        <li class="breadcrumb-item">{{__('Business Permit')}}</li>
        <li class="breadcrumb-item">{{__('Retirement of Business')}}</li>
    @endsection
</div>
@section('action-btn')
    <div class="float-end">

            <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i></a>
            <a href="#" data-size="xxll" data-url="{{ url('/business-permit-retire/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Retirement of Business')}}" class="btn btn-sm btn-primary">
            <i class="ti-plus"></i>
        </a>
        
    </div>
@endsection
@section('content')
    <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-end" >
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('Search', 'Year', ['class' => 'fs-6 fw-bold']) }}
                                {{ Form::select('search_year',$arrYears,date("Y"), ['class' => 'form-control select3', 'id' => 'search_year']) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('Search', 'Search Here...', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2"><br>
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
                        <table class="table" id="Jq_datatablelist">
                            <thead>
                            <tr>
                                <th>{{__('No.')}}</th>
                                <th>{{__('Business Id-No.')}}</th>
                                <th>{{__('TaxPayer Name')}}</th>
                                <th>{{__('Business Name')}}</th>
                                <th>{{__('Application Type')}}</th>
                                <th>{{__('Reason')}}</th>
                                <th>{{__('Established')}}</th>
                                <th>{{__('Closed')}}</th>
                                <th>{{__('Duration')}}</th>
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
    
    <script src="{{ asset('js/Bplo/BusinessPermitRetire.js') }}"></script>
@endsection
