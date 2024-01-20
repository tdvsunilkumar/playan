@extends('layouts.admin')
@section('page-title')
    {{__('Tax, Fee & Other Charges')}}
@endsection
@push('script-page')
@endpush
<div>
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Tax, Fee & Other Charges')}}</li>
@endsection
</div>
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
         <a href="#" data-size="xl" data-url="{{ url('/engtfoc/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Tax, Fee & Other Charges')}}" class="btn btn-sm btn-primary modal-dialog-scrollable">
            <i class="ti-plus"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="row" id="filterdiv">
                            <div class="col-lg-5 col-md-5 col-sm-5">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                              <div class="form-group">
                                {{ Form::label('departmentnew', __('Select Department'),['class'=>'form-label']) }}
                                <span class="validate-err">{{ $errors->first('departmentnew') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('departmentnew',$arrDepaertments,'', array('class' => 'form-control ','id'=>'departmentnew','required'=>'required')) }}
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
                           <div class="col-auto float-end ms-1" style="padding-top: 25px;">
                                <a href="#" class="btn btn-sm btn-primary" id="btn_search" style="padding: 10px;">
                                    <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger" id="btn_clear" style="padding: 10px;">
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
                                <th>{{__('No')}}</th>
                                <th>{{__('Fund Code')}}</th>
                                <th>{{__('Type Of Charges')}}</th>
                                <th>{{__('Chart Of Account')}}</th>
                                <th>{{__('Description')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Applicable To')}}</th>
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
     <script src="{{ asset('js/Engneering/ctotfoc.js') }}"></script>
@endsection
