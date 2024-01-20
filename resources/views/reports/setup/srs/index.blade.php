
@extends('layouts.admin')

@section('page-title')
    {{__('SRS Order')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reports') }}</li>
    <li class="breadcrumb-item">{{ __('Setup') }}</li>
    <li class="breadcrumb-item">{{ __('SRS Order') }}</li>
@endsection
@section('content')
<div class="container">
  <div class="nested">
    <div class="item">
      Item 1
      <div class="nested">
        <div class="item">
          Item 4
          <div class="nested">
            <div class="item">
              Item 7
              <div class="nested"></div>
            </div>
            <div class="item">
              Item 8
              <div class="nested"></div>
            </div>
            <div class="item">
              Item 9
              <div class="nested"></div>
            </div>
          </div>
        </div>
        <div class="item">
          Item 5
          <div class="nested"></div>
        </div>
        <div class="item">
          Item 6
          <div class="nested"></div>
        </div>
      </div>
    </div>
    <div class="item">
      Item 2
      <div class="nested"></div>
    </div>
    <div class="item">
      Item 3
      <div class="nested"></div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://rawgit.com/bevacqua/dragula/master/dist/dragula.js"></script>
<script src="{{ asset('/js/partials/reorder.js?v='.filemtime(getcwd().'/js/partials/reorder.js').'') }}"></script>
@endpush
@push('styles')
<style>
  .nested{
    border: 1px solid #000;
    padding: 10px;
  }
</style>
@endpush
