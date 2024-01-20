@extends('layouts.admin')
<style type="text/css">
    
    .datefield{padding-top: 26px;}
</style>
@section('page-title')
    {{__('Clearance Application')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Clearance Application')}}</li>
    
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        <a href="#" data-size="lg" data-url="{{ url('/bploappclearance/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Bplo App Clearance')}}" class="btn btn-sm btn-primary">
            <i class="ti-plus"></i>
        </a>
    </div>
@endsection


@section('content')

    <div class="row" id="this_is_filter" style="display:none;">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3">
                              <div class="form-group">
                                {{Form::label('search',__('Search'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
                                </div>
                              </div>
                           </div>
                           <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="col-auto float-end ms-2" style="padding-top: 40px;">
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
                                    <th>{{__('Owner')}}</th>
                                    <th>{{__("Business Account Number")}}</th>
                                    <th>{{__('Departmental Code')}}</th>
                                    <th>{{__('Year Applied')}}</th>
                                    <th>{{__('Application No')}}</th>
                                    <th>{{__('Transaction')}}</th>
                                    <th>{{__('Environmental Fee')}}</th>
                                    <th>{{__('Payment In CTO')}}</th>
                                    <th>{{__('Date Issued')}}</th>
                                    <th>{{__('Remarks')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/bploappclearance.js') }}"></script>
@endsection

