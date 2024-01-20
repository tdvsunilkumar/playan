@extends('layouts.admin')
@section('page-title')
    
    {{__('Out Patient Record Card')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Out Patient Record Card')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        <a href="#" data-size="lg" data-url="{{ url('/recordcard/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Patient Record Card')}}" class="btn btn-sm btn-primary">
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
                                <th>{{__('No.')}}</th>
                                <th>{{__('Record No.')}}</th>
                                <th>{{__('Full Name')}}</th>
                                <th>{{__('Barangay')}}</th>
                                <th>{{__('Age')}}</th>
                                <th>{{__('Sex')}}</th>
                                <th>{{__('PhilHealth Member')}}</th>
                                <th>{{__('status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 2nd modal  -->
<div class="modal fade form-inner" id="addLabRequestmodal" data-backdrop="static" style="z-index:1056;">
    <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
        <div class="modal-body">
            <div class="modal-content" id="LabRequest" >
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
<!-- 3rd modal -->
<div class="modal fade form-inner" id="addLabRequestFormModal" data-backdrop="static" style="z-index:1057;">
    <div class="modal-dialog modal-xl modalDiv modal-dialog-scrollable">
        <div class="modal-body">
            <div class="modal-content" id="LabRequest" >
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

<!-- modal for issuance-->
<div class="modal fade form-inner" id="issuanceModal" data-backdrop="static" style="z-index:1058;">
    <div class="modal-dialog modal-xxl modalDiv modal-dialog-scrollable">
        <div class="modal-body">
            <div class="modal-content" id="IssueModal" >
                <div class="modal-header">
                    <h5 class="modal-title" id="IssueModalTitle"></h5>
                    <input type="hidden" id="IssueModal-form-url" >
                    <button type="button" class="btn-close close-IssueModal-modal" id="closeIssueModalmodal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="body">
                    
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="{{ asset('js/recordcard.js') }}"></script>
@endsection


