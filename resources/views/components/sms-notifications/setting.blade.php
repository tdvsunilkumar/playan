@extends('layouts.admin')

@section('page-title')
    {{__('SMS Notifications (Settings)')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('SMS Notifications') }}</li>
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection
@section('action-btn')
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-9">
            <div id="voucher-card" class="card table-card">
                <div class="card-header">
                    <h5 class="d-flex">
                        <div class="me-auto">SMS API Settings</div>
                        @if ($permissions->create > 0)
                        <div style="margin-top: -10px; margin-bottom: -10px">
                            <button id="add-btn" class="btn btn-info">
                                <span class="d-flex justify-content-center align-items-center">
                                    <i class="ti-plus align-middle me-2"></i> ADD NEW
                                </span>
                            </button>
                        </div>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="settingTable" class="display dataTable table w-100 table-striped" aria-describedby="settingInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('TYPE') }}</th>
                                                <th>{{ __('MASKING') }}</th>
                                                <th>{{ __('APP NAME') }}</th>
                                                <th>{{ __('LAST MODIFIED') }}</th>
                                                <th>{{ __('STATUS') }}</th>
                                                <th>{{ __('ACTIONS') }}</th>
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
        <div class="col-xl-3">
            <div id="voucher-card" class="card table-card">
                <div class="card-header">
                    <h5 class="w-100">SMS Settings</h5>
                </div>
                <div class="card-body">
                    {{ Form::open(array('url' => 'components/sms-notifications/settings/update', 'class'=>'formDtls needs-validation', 'name' => 'smsServerSettingForm', 'method' => 'POST')) }}
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required">
                                {{ Form::label('dcs', 'Enable SMS Notification', ['class' => 'mb-1']) }}
                                <div class="form-check form-switch">
                                    @if ($apps->is_enabled > 0)
                                        <input class="form-check-input" type="checkbox" name="is_enabled" checked="checked">
                                    @else
                                        <input class="form-check-input" type="checkbox" name="is_enabled">
                                    @endif
                                    <label class="fs-6 form-check-label" for="is_enabled"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group">
                                {{ Form::label('dcs', 'Masking Code', ['class' => 'mb-1']) }}
                                @foreach ($masks as $mask)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="masking_id" value="{{ $mask->id }}" {{ ($mask->id  == $apps->masking_id ) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $mask->code }}">
                                            {{ $mask->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    @include('components.sms-notifications.new-setting')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/sms-setting.js?v='.filemtime(getcwd().'/js/datatables/sms-setting.js').'') }}"></script>
<script src="{{ asset('js/forms/sms-setting.js?v='.filemtime(getcwd().'/js/forms/sms-setting.js').'') }}"></script>
@endpush