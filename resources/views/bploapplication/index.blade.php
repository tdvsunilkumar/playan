@extends('layouts.admin')
<style type="text/css">
    .datefield{padding-top: 26px;}
</style>
@section('page-title')
    {{__('BPLO Applications')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('BPLO Applications')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        <a href="#" data-size="xll" data-url="{{ url('/bploapplication/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('New Business License')}}" class="btn btn-sm btn-primary">
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
                             <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    {{Form::label('',__('Year'),['class'=>'form-label'])}}
                                     <div class="form-icon-user">
                                    {{ Form::select('year',$yeararr,'', array('class' => 'form-control','id'=>'yeardate')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                             <div class="form-group">
                                    {{Form::label('aprovedlabel',__('Approved'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{ Form::select('aproved',array('' =>'Please Select','1' =>'Yes','0' =>'No'), '', array('class' => 'form-control spp_type','id'=>'aproved')) }}
                                    </div>
                                     <span class="validate-err" id="err_amendmentfrom"></span>
                                </div>
                            </div>
                             <div class="col-lg-2 col-md-2 col-sm-2">
                             <div class="form-group">
                                   {{Form::label('date',__('Date'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{ Form::date('datecreated', '', array('class' => 'form-control','placeholder'=>'date','id'=>'datecreated1')) }}
                                    </div>
                                     <span class="validate-err" id="err_amendmentfrom"></span>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="form-group">
                                {{Form::label('barangay',__('Barangays'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                     {{ Form::select('barangay',$getbarangays,'', array('class' => 'form-control spp_type','id'=>'barangay')) }}
                                </div>
                            </div>
                           </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
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
                                    <th>{{__('Business Acc. No.')}}</th>
                                    <th>{{__("TaxPayer's Name")}}</th>
                                    <th>{{__('Business Trade Name')}}</th>
                                    <th>{{__('Business Address')}}</th>
                                    <th>{{__('Application Date')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/bploapplication.js') }}"></script>
@endsection

