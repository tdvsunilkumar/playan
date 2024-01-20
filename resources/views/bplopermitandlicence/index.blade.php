@extends('layouts.admin')
<style type="text/css">
    .datefield{padding-top: 26px;}
</style>
@section('page-title')
    {{__('BPLO Business(Permit and License)')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('BPLO Business(Permit and License)')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        <a href="#" data-size="xl" data-url="{{ url('/bplopermitandlicence/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Cashiering(Permit & License)')}}" class="btn btn-sm btn-primary">
            <i class="ti-plus"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="Jq_datatablelist">
                            <thead>
                                <tr>
                                    <th>{{__('Business Acc. No.')}}</th>
                                    <th>{{__("TaxPayer's Name")}}</th>
                                    <th>{{__('Order Number')}}</th>
                                    <th>{{__('Due Amount')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/bplopermitlicence.js') }}"></script>
@endsection

