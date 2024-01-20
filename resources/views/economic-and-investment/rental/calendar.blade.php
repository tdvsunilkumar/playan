@extends('layouts.admin')

@section('page-title')
    {{__('Calendar')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Economic & Investment') }}</li>
    <li class="breadcrumb-item">{{ __('Calendar') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div id="voucher-card" class="card table-card">
                <div class="card-header">
                    <h5 class="w-100">Calendar (Event)</h5>
                </div>
                <div class="card-body">
                    <div id="m_calendar"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/fullcalendar/fullcalendar.bundle.css?v='.filemtime(getcwd().'/assets/vendors/fullcalendar/fullcalendar.bundle.css').'') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/fullcalendar/fullcalendar.override.css?v='.filemtime(getcwd().'/assets/vendors/fullcalendar/fullcalendar.override.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/fullcalendar/fullcalendar.bundle.js?v='.filemtime(getcwd().'/assets/vendors/fullcalendar/fullcalendar.bundle.js').'') }}"></script>
<script src="{{ asset('js/datatables/eco-calendar.js?v='.filemtime(getcwd().'/js/datatables/eco-calendar.js').'') }}"></script>
@endpush