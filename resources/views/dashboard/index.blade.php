@extends('layouts.admin')

@section('page-title')
    {{__('Dashboard')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
@endsection

@section('action-btn')
<div class="float-end w-15 pt-4">
    <div class="menu-box">
        <div class="form-group m-form__group required" id="parent_gender">
            {{
                Form::select('dashboard_menu', $dashboard_menu, $value = '', ['id' => 'dashboard_menu', 'class' => 'form-control select3', 'data-placeholder' => 'select'])
            }}
            <span class="m-form__help text-danger"></span>
        </div>
    </div>
</div>
@endsection

@section('content')
    <div class="row">
        @foreach($widgets as $widget)
            @include('dashboard.widgets.widgets-'.$widget)
        @endforeach
    </div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
<style>
    .widgets {
        visibility: hidden;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/forms/dashboard.js?v='.filemtime(getcwd().'/js/forms/dashboard.js').'') }}"></script>
@endpush