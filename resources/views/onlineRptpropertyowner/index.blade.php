@extends('layouts.admin')
@section('page-title')
    {{__('Taxpayer Online Registration')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Taxpayer Online Registration')}}</li>
@endsection
@section('action-btn')
{{ Form::hidden('isopen',$isopen, array('id' => 'isopen')) }}
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i></a>

<!-- <a href="#" data-size="lg" id="taxpayers" data-url="{{ url('/rptpropertyowner/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Property Owner')}}" class="btn btn-sm btn-primary">
            <i class="ti-plus"></i>
        </a> -->
    </div>
@endsection

@section('content')


    <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('flt_Status', 'Status', ['class' => 'fs-6 fw-bold']) }}
                                {{
                                    Form::select('flt_Status', ['0' => 'Pending','2' => 'Declined'], $value = '', ['id' => 'flt_Status', 'class' => 'form-control select3'])
                                }}                                 
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('q aa', 'Search Here...', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2" style="padding-top: 20px;">
                                <a href="#" class="btn btn-sm btn-primary" id="btn_search">
                                    <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
                                    <span class="btn-inner--icon"><i class="ti-trash"></i></span>
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
                                 <th>{{__('Taxpayer')}}</th>
                                 <th>{{__('Address')}}</th>
                                 <th>{{__('Gender')}}</th>
                                 <th>{{__('Email')}}</th>
                                 <th>{{__('Mobile No')}}</th>
                                 <th>{{__('Duration')}}</th>
                                 <th>{{__('Status')}}</th>
                                 <th>{{__('action')}}</th>
                                
                            </tr>
                            </thead>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <script src="{{ asset('js/onlineRptProperty.js') }}?rand={{ rand(000,999) }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
        $("#flt_Status").select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
        });
    </script>
@endsection

