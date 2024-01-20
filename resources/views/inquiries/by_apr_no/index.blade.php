@extends('layouts.admin')

@section('page-title')
    {{__('Inquiries')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Inquiries') }}</li>
    <li class="breadcrumb-item" id="dyn_html">ARP No Inquiry</li>

@endsection

@section('content')
    <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12" style="margin-right: 5px;">
                                <div class="" id="filter_type_group">
                                    {{ Form::label('Search', 'Search', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::select('filter_type',array('1' =>'By ARP No.','2' =>'By TCT No.','3' =>'By CCT No.','4' =>'By Taxpayer Name','5' =>'By Survey No.','6' =>'By Building Kind'), $filter_type, array('class' => 'form-control','id'=>'filter_type')) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 hide" id="build">
                                <div class="btn-box">
                                    {{ Form::label('kind_id', 'Select Building Kind', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::select('kind_id',$build_kinds, $value = "", array('class' => 'form-control','id'=>'kind_id')) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12" id="search_q">
                                <div class="btn-box">
                                    {{ Form::label('search', 'Enter APR No.', ['class' => 'fs-6 fw-bold','id' => "search_name"]) }}
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Type data here...','id'=>'q')) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2">
                                <br>
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
    <div class="row" id="arp_no">
        <div class="col-xl-12">
            <div class="card table-card">
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                        <h4 class="text-header">ARP No. Inquiry</h4>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="inquiriesByAprTable" class="display dataTable table w-100 table-striped" aria-describedby="inquiriesByAprInfo">
                                        <thead>
                                            <tr>
                                                <th>{{__('No.')}}</th>
                                                <th>{{ __('T.D. No.') }}</th>
                                                <th>{{ __("Taxpayer's Name") }}</th>
                                                <th>{{ __('Property Index Number') }}</th>
                                                <th>{{ __('Kind') }}</th>
                                                <th>{{ __('Class') }}</th>
                                                <th>{{ __('Value') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row hide" id="tct_no">
        <div class="col-xl-12">
            <div class="card table-card">
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                        <h4 class="text-header">TCT No. Inquiry[For Land Only]</h4>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="inquiriesByTctTable" class="display dataTable table w-100 table-striped" aria-describedby="inquiriesByAprInfo">
                                        <thead>
                                            <tr>
                                                <th>{{__('No.')}}</th>
                                                <th>{{ __('T.D. No.') }}</th>
                                                <th>{{ __("Taxpayer's Name") }}</th>
                                                <th>{{ __('OCT/TCT No.') }}</th>
                                                <th>{{ __('Property Index Number') }}</th>
                                                <th>{{ __('Kind') }}</th>
                                                <th>{{ __('Class') }}</th>
                                                <th>{{ __('Value') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row hide" id="cct_no">
        <div class="col-xl-12">
            <div class="card table-card">
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                        <h4 class="text-header">CCT No. Inquiry[For Building Only]</h4>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="inquiriesByCctTable" class="display dataTable table w-100 table-striped" aria-describedby="inquiriesByAprInfo">
                                        <thead>
                                            <tr>
                                                <th>{{__('No.')}}</th>
                                                <th>{{ __('T.D. No.') }}</th>
                                                <th>{{ __("Taxpayer's Name") }}</th>
                                                <th>{{ __('CCT No.') }}</th>
                                                <th>{{ __('Unit No.') }}</th>
                                                <th>{{ __('Kind') }}</th>
                                                <th>{{ __('Class') }}</th>
                                                <th>{{ __('Value') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row hide" id="own">
        <div class="col-xl-12">
            <div class="card table-card">
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                            <h4 class="text-header">Taxpayer Inquiry[Taxpayer's Name]</h4>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="inquiriesByOwnTable" class="display dataTable table w-100 table-striped" aria-describedby="inquiriesByAprInfo">
                                        <thead>
                                            <tr>
                                                <th>{{__('No.')}}</th>
                                                <th>{{ __('T.D. No.') }}</th>
                                                <th>{{ __("Taxpayer's Name") }}</th>
                                                <th>{{ __('Property Index Number') }}</th>
                                                <th>{{ __('Kind') }}</th>
                                                <th>{{ __('Lot No.') }}</th>
                                                <th>{{ __('Class') }}</th>
                                                <th>{{ __('Value') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row hide" id="survey">
        <div class="col-xl-12">
            <div class="card table-card">
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                        <h4 class="text-header">Survey No Inquiry[For Land Title Only]</h4>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="inquiriesBySurveyTable" class="display dataTable table w-100 table-striped" aria-describedby="inquiriesByAprInfo">
                                        <thead>
                                            <tr>
                                                <th>{{__('No.')}}</th>
                                                <th>{{ __('T.D. No.') }}</th>
                                                <th>{{ __("Taxpayer's Name") }}</th>
                                                <th>{{ __('Survey No.') }}</th>
                                                <th>{{ __('Kind') }}</th>
                                                <th>{{ __('Class') }}</th>
                                                <th>{{ __('Value') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row hide" id="build_kind">
        <div class="col-xl-12">
            <div class="card table-card">
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                        <h4 class="text-header">Building Kind Inquiry</h4>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="inquiriesByBuildKindTable" class="display dataTable table w-100 table-striped" aria-describedby="inquiriesByAprInfo">
                                        <thead>
                                            <tr>
                                                <th>{{__('No.')}}</th>
                                                <th>{{ __('T.D. No.') }}</th>
                                                <th>{{ __("Taxpayer's Name") }}</th>
                                                <th>{{ __('CCT No.') }}</th>
                                                <th>{{ __('Unit No.') }}</th>
                                                <th>{{ __('Kind') }}</th>
                                                <th>{{ __('Class') }}</th>
                                                <th>{{ __('Value') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <tr class="odd">
                                                <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/inquiries-by-arp-no.js?v='.filemtime(getcwd().'/js/datatables/inquiries-by-arp-no.js').'') }}"></script>
<script src="{{ asset('js/forms/inquiries-by-arp-no.js?v='.filemtime(getcwd().'/js/forms/inquiries-by-arp-no.js').'') }}"></script>
@endpush
