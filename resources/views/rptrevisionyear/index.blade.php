@extends('layouts.admin')
@section('page-title')
    {{__('Revision Year Setup')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Revision Year Setup')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i></a>
            <a href="#" data-size="lg" data-url="{{ url('/revisionyear/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Revision Year')}}" class="btn btn-sm btn-primary">
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
    <style type="text/css">
        .align-center {
          text-align: center;
        }
    </style>
    
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="Jq_datatablelist">
                            <thead>
                            <tr>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('No.')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('Year')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('Code')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('Description')}}</th>
                                <th colspan="3" style="border:1px solid #fff;text-align: center;">{{__('Applicable Tax scenario')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('Assessor')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('Assistant Assessor')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('default')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('Status')}}</th>
                                <th rowspan="2" style="border:1px solid #fff;text-align: center;">{{__('Action')}}</th>
                            </tr>
                            <tr>
                                <th style="border:1px solid #fff;text-align: center;width: 8%;">{{__('Basic Tax')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 8%;"> {{__('SEF')}}</th>
                                <th style="border:1px solid #fff;text-align: center;width: 8%;">{{__('SHT')}}</th>
                                
                            </tr>
                            </thead>
                           </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<script src="{{ asset('js/revisionyear.js') }}?rand={{rand(0,999)}}"></script>
@endsection
