@extends('layouts.admin')
@section('page-title')
    
    {{__('Building Unit Value')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')



    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Building Unit Value')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        <a href="#" data-size="lg" data-url="{{ url('/rptbuildingunitvalue/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Building Unit Value')}}" class="btn btn-sm btn-primary">
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
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
                                <div class="btn-box">
                                {{ Form::label('Search', 'Revision Year', ['class' => 'fs-6 fw-bold']) }}
                               {{ Form::select('revisionyear',$revisionyear,$selected_revision->id, array('class' => 'form-control select3','id'=>'revisionyear')) }}
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
                                <th>{{__('Kind')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Revision')}}</th>
                                <th>{{__('Min-Unit Value')}}</th>
                                <th>{{__('Max-Unit Value')}}</th>
                                <th>{{__('Approve')}}</th>
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
<script src="{{ asset('js/rptBuildingUnitValue.js') }}?rand={{ rand(000,999) }}"></script>
@endsection
<script src="{{ asset('js/select2.min.js') }}"></script>
