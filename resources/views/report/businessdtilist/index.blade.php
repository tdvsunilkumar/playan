@extends('layouts.admin')
<!-- <style type="text/css">
    .datefield{padding-top: 26px;}
</style> -->
@section('page-title')
    {{__('DTI LIST')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('DTI LIST')}}</li>
    
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
    </div>
@endsection
@section('content')
    <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                              
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                              <div class="form-group">
                                {{Form::label('fromdate',__('From date'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::date('from_date', $startdate, array('class' => 'form-control','id'=>'from_date')) }}
                                </div>
                              </div>
                           </div>
                           <div class="col-lg-2 col-md-2 col-sm-2">
                              <div class="form-group">
                                {{Form::label('todate',__('To date'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::date('to_date', $enddate, array('class' => 'form-control','id'=>'to_date')) }}
                                </div>
                              </div>
                           </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                              <div class="form-group">
                                {{Form::label('search',__('Search'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
                                </div>
                              </div>
                           </div>
                           <div class="col-lg-1 col-md-1 col-sm-1">
                                <div class="col-auto float-end ms-2" style="padding-top: 30px;">
                                    <a href="#" class="btn btn-sm btn-primary" id="btn_search">
                                        <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
                                        <span class="btn-inner--icon"><i class="ti-trash "></i></span>
                                    </a>
                                    <a href="{{ url('export-nationalgovdti-lists') }}" class="btn btn-sm btn-primary" id="btn_download_spreadsheet" title="Download Spreadsheet">
                                        <span class="btn-inner-icon"><i class="ti-files"> </i></span>
                             </a>
                                </div>
                           </div>
                          <!--  <div class="d-flex align-items-center justify-content-end mt-3">
                            <a href="{{ url('export-nationalgovdti-lists') }}" class="btn btn-sm btn-primary" id="btn_download_spreadsheet">
                                    <span class="btn-inner--icon">Download Spreadsheet</span>
                             </a>
                        </div> -->
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
                                    <th>{{_('NO.')}}</th>
                                    <th>{{__('Business Name.')}}</th>
                                    <th>{{__('Type of Business')}}</th>
                                    <th>{{__('Registration No.')}}</th>
                                    <th>{{__('Date issued')}}</th>
                                    <th>{{__('Permit No.')}}</th>
                                    <th>{{__('Status Registration')}}</th>
                                    <th>{{__('Date Applied ')}}</th>
                                    <th>{{__('Owner Name')}}</th>
                                    <th>{{__('Business Address')}}</th>
                                    <th>{{__('Line of Business')}}</th>
                                    <th>{{__('Capital Investment')}}</th>
                                    <th>{{__('Gross Sale')}}</th>
                                    <th>{{__('Size of Business')}}</th>
                                    <th>{{__('O.R. Number')}}</th>
                                    <th>{{__('Contact No.')}}</th>
                                    <th>{{__('Email Address')}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/report/businessdtilist.js') }}"></script>
@endsection

  

