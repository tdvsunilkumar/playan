@extends('layouts.admin')

@section('page-title')
    {{__('Outbox')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Components') }}</li>
    <li class="breadcrumb-item">{{ __('SMS Notifications') }}</li>
    <li class="breadcrumb-item">{{ __('Outbox') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card noflow table-card">
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="outboxTable" class="display dataTable table w-100 table-striped" aria-describedby="outboxInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th class="sliced">{{ __('TRANS ID') }}</th>
                                                <th class="sliced">{{ __('MESSAGES') }}</th>
                                                <th>{{ __('TYPE') }}</th>
                                                <th>{{ __('MSISDN') }}</th>
                                                <th>{{ __('TELCO') }}</th>
                                                <th>{{ __('STATUS') }}</th>
                                                <th>{{ __('LAST MODIFIED') }}</th>
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
<script src="{{ asset('js/datatables/sms-outbox.js?v='.filemtime(getcwd().'/js/datatables/sms-outbox.js').'') }}"></script>
@endpush