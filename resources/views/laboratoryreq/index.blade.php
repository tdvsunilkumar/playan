@extends('layouts.admin')
@section('page-title')
    
    {{__('Laboratory Request')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Laboratory Request')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        <a href="#" data-size="lg" data-url="{{ url('/laboratory-request/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Laboratory Request')}}" class="btn btn-sm btn-primary">
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
    
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="Jq_datatablelist">
                            <thead>
                            <tr>
                                <th>{{__('ID.')}}</th>
                                <th>{{__('Control No.')}}</th>
                                <th>{{__('Full Name')}}</th>
                                <th>{{__('Barangay')}}</th>
                                <th>{{__('Age')}}</th>
                                <th>{{__('Gender')}}</th>
                                <th>{{__('Diagnosis')}}</th>
                                <th>{{__('Top No.')}}</th>
                                <th>{{__('O.R. No.')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Payment Status')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Posting Status')}}</th>
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
<!-- modals lab request form -->
<div class="modal form-inner" id="addLabRequestFormModal" data-backdrop="static" style="z-index:1056;">
    <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
        <div class="modal-body">
            <div class="modal-content" id="LabRequest" style="overflow-y:scroll">
                <div class="modal-header">
                    <h5 class="modal-title" id="LabRequestTitle"></h5>
                    <input type="hidden" id="labrequest-form-url" >
                    <button type="button" class="btn-close close-labrequest-modal" id="closeLabRequestmodal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="body">
                    
                </div>
            </div>
        </div>
    </div>
</div>

    @include('common.secondModal')

<script src="{{ asset('js/laboratoryRequest.js') }}"></script>
@endsection


