@extends('layouts.admin')
@section('page-title')
    
    {{__('Penalty Rate Scheduler')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
<style type="text/css">
    .dropdown-menu{
        z-index: 99999 !important;}
</style>


    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Penalty Rate Scheduler')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        <a href="#" data-size="lg" data-url="{{ url('/rptctopenaltytable/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Penalty Rate Scheduler')}}" class="btn btn-sm btn-primary">
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
                         {{ Form::open(array('url' => '','id'=>"rptPropertySerachFilter")) }}
                        <div class="d-flex align-items-center justify-content-end">
                            
                             <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" >
                                
                                    <div class="btn-box">
                                        {{ Form::label('Search', 'Year', ['class' => 'fs-6 fw-bold']) }}
                                   {{ Form::select('revision_year',['Year Encoded','Effectivity'],'', array('class' => 'form-control','id'=>'setFilterType')) }}
                                    </div>
                                
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" >
                               
                                    <div class="btn-box">
                                         {{ Form::label('Search', 'Year Encoded', ['class' => 'fs-6 fw-bold','id'=>'rptPropertySearchByEffectiveYearLabel']) }}
                                    {{ Form::text('year_encoded','', array('class' => 'form-control','id'=>'rptPropertySearchByEffectiveYear','placeholder' => 'Year Encoded')) }}
                                    </div>
                                
                            </div>
                            
                            <div class="col-auto float-end ms-2"><br>
                                <a href="#" class="btn btn-sm btn-primary" id="btn_search">
                                    <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
                                    <span class="btn-inner--icon"><i class="ti-trash "></i></span>
                                </a>
                            </div>

                        </div>
                        {{Form::close()}}
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
                                <th>{{__('NO')}}</th>
                                <th>{{__('YEAR')}}</th>
                                <th>{{__('EFFECTIVITY')}}</th>
                                <th>{{__('JANUARY[%]')}}</th>
                                <th>{{__('FEBRUARY[%]')}}</th>
                                <th>{{__('MARCH[%]')}}</th>
                                <th>{{__('APRIL[%]')}}</th>
                                <th>{{__('MAY[%]')}}</th>
                                <th>{{__('JUNE[%]')}}</th>
                                <th>{{__('JULY[%]')}}</th>
                                <th>{{__('AUGUST[%]')}}</th>
                                <th>{{__('SEPTEMBER[%]')}}</th>
                                <th>{{__('OCTOBER[%]')}}</th>
                                <th>{{__('NOVEMBER[%]')}}</th>
                                <th>{{__('DECEMBER[%]')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="{{ asset('js/rptCtoPenaltyTable.js') }}"></script>
 <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datepicker/bootstrap-datepicker.css?v='.filemtime(getcwd().'/assets/vendors/datepicker/bootstrap-datepicker.css').'') }}"/>

<script src="{{ asset('assets/vendors/datepicker/bootstrap-datepicker.js?v='.filemtime(getcwd().'/assets/vendors/datepicker/bootstrap-datepicker.js').'') }}"></script>
<script src="{{ asset('js/forms/inquiries-by-arp-no.js?v='.filemtime(getcwd().'/js/forms/inquiries-by-arp-no.js').'') }}"></script>
<script type="text/javascript">
$('#rptPropertySearchByEffectiveYear').datepicker({
            minViewMode: 2,
            format: 'yyyy',
            autoclose: true,
            clearBtn: false
        });

</script>
@endsection

