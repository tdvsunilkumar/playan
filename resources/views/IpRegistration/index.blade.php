@extends('layouts.admin')
@section('page-title')
    {{__('IP Security Management')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('IP Security Management')}}</a></li>
@endsection
@section('action-btn')
    <div class="float-end">
         <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
             <a href="#" data-size="xll" data-url="{{ url('/ip-security-manage/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage IP Security')}}" class="btn btn-sm btn-primary modal-dialog-scrollable">
            <i class="ti-plus"></i>
        </a>
    </div>
@endsection

@section('content')
   <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="#" accept-charset="UTF-8" id="product_service">
							<div class="d-flex align-items-center justify-content-end">
								<div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12">
									<div class="btn-box">
										{{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
									</div>
								</div>
								<div class="col-auto float-end ms-2">
									<a href="#" class="btn btn-sm btn-primary" id="btn_search">
										<span class="btn-inner--icon"><i class="ti-search"></i></span>
									</a>
									<a href="#" class="btn btn-sm btn-danger" id="btn_clear">
										<span class="btn-inner--icon"><i class="ti-trash"></i></span>
									</a>
								</div>
							</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-9">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="Jq_datatablelist">
                            <thead>
								<tr>
									<th>{{__('No')}}</th>
									<th>{{__('IP Address')}}</th>
                                    <th>{{__('Local Name')}}</th>
                                    <th>{{__('Remarks')}}</th>
                                    <th>{{__('Registered By')}}</th>
                                    <th>{{__('Generated')}}</th>
                                    <th>{{__('Modified')}}</th>
									<th>{{__('Status')}}</th>
									<th>{{__('Action')}}</th>
								</tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="voucher-card" class="card table-card">
                <div class="card-header">
                    <h5 class="w-100">Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group">
                                {{ Form::label('dcs', 'Enable IP Security', ['class' => 'mb-1']) }}
                                <div class="form-check form-switch">
                                    @if ($ip_settings_status > 0)
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked="checked">
                                    @else
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active">
                                    @endif
                                    <label class="fs-6 form-check-label" for="is_active"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/IpRegistration.js') }}"></script>
@endsection


