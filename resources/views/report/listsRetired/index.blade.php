@extends('layouts.admin')
<!-- <style type="text/css">
    .datefield{padding-top: 26px;}
    .datepicker {
    width: 220;
    padding: 10px;
   }
</style> -->
@section('page-title')
    {{__('Retired Business')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Retired Business')}}</li>
    
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
                                      {{ Form::date('from_date', $startdate, array('class' => 'form-control','placeholder'=>'From','id'=>'from_date')) }}
                                </div>
                              </div>
                           </div>
                           <div class="col-lg-2 col-md-2 col-sm-2">
                              <div class="form-group">
                                {{Form::label('todate',__('To date'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::date('to_date', $enddate, array('class' => 'form-control','placeholder'=>'To date','id'=>'to_date')) }}
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
                                    <a class="btn btn-sm btn-primary" id="btn_download_spreadsheet" title="Download Spreadsheet">
                                        <span class="btn-inner-icon"><i class="ti-files"> </i></span>
                                     </a>
                                </div>
                           </div>
                           <!-- <div class="d-flex align-items-center justify-content-end mt-3">
                             <a class="btn btn-sm btn-primary" id="btn_download_spreadsheet">
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
                                    <th>{{__('No.')}}</th>
                                    <th>{{__('Business Name')}}</th>
                                    <th>{{__('Business Address')}}</th>
                                    <th>{{__('Retirement For? (Entire Business/Per line of Business)')}}</th>
                                    <th>{{__('Business Line')}}</th>
									<th>{{__('BIN')}}</th>
                                    <th>{{__('Reason for Retirement')}}</th>
                                    <th>{{__('Retirement Date')}}</th>
									<th>{{__('Date Established')}}</th>
									<th>{{__('Date Closed')}}</th>
									<th>{{__('Name of Owner')}}</th>
									<th>{{__('Contact No. of Owner')}}</th>
									<th>{{__('Application Method')}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="{{ asset('js/report/listsRetiredreport.js') }}"></script>
@endsection

  
