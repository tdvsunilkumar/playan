@extends('layouts.admin')
@section('page-title')
    {{__('Dashboard')}}
@endsection
@section('content')
<div class="row" id="this_is_filter" style="margin-bottom: 10px;margin-top: -73px;">
    <div class="col-sm-12">
        <div class="d-flex align-items-center justify-content-between">
            <ul class="breadcrumb" style="margin: 0;">
                <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li> -->
            </ul>
            <form method="GET" action="#" accept-charset="UTF-8" id="product_service">
                <div>
                    <!-- {{ Form::label('Search', 'Dashboard', ['class' => 'fs-6 fw-bold']) }} -->
                    {{ Form::select('status', $dash, '', ['class' => 'form-control', 'id' => 'status', 'style' => 'width: 150px;']) }}
                </div>
            </form>
        </div>
    </div>
</div>

    <div class="row">
        <div id="viewContainer">
            
        </div>
    </div>
    <script src="{{ asset('js/dashboard.js') }}?rand={{ rand(000,999) }}"></script>
@endsection