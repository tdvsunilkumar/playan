@extends('layouts.admin')
@section('page-title')
    {{__('GSIS')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Master Data')}}</a></li>
    <li class="breadcrumb-item">{{__('GSIS')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
         <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
             <a href="#" data-size="xll" data-url="{{ url('/hr-gsis-benefits/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage GSIS')}}" class="btn btn-sm btn-primary modal-dialog-scrollable">
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
                        <form method="GET" action="#" accept-charset="UTF-8" id="product_service">
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
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="Jq_datatablelist">
                            <thead>
								<tr>
									<th>{{__('SR No')}}</th>
									<th>{{__('Description')}}</th>
									<th>{{__('Amount From')}}</th>
									<th>{{__('Amount To')}}</th>
									<th>{{__('Personal Share')}}</th>
                                    <th>{{__('Goverment Share')}}</th>
									<th>{{__('Action')}}</th>
								</tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/HR/hrgsis.js?v='.filemtime(getcwd().'/js/HR/hrgsis.js')) }}"></script>
@endsection


