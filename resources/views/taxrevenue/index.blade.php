@extends('layouts.admin')
<style type="text/css">
    .datefield{padding-top: 26px;}
    .swal2-container{
        z-index:9999999 !important;
    }
    .select3-container{
        z-index: 9999999 !important;
    }

</style>

@section('page-title')
    {{__('Tax Revenue')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Treasurer')}}</li>
    <li class="breadcrumb-item">{{__('Property Setup(Data)')}}</li>
    <li class="breadcrumb-item">{{__('Tax Revenue')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        
    </div>
@endsection

@section('content')
<div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        

                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20">
                                    <div class="btn-box">
                                        {{ Form::label('Search', 'Property Kind', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::select('active_status_filter',$propKinds,'', array('class' => 'form-control select3','id'=>'rptPropertySearchByPkCode','placeholder'=>'All')) }}
                                    </div>
                            </div>
                            
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('Search', 'Search Here...', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'rptPropertySearchByText')) }}
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
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    
     {{ Form::open(array('url' => 'taxrevenue/store','id'=>"taxRevenueForm",'class'=>'taxRevenueForm')) }}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="Jq_datatablelist">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="text-align:center;border: 1px solid;">{{__('No.')}}</th>
                                    <th rowspan="2" style="text-align:center;border: 1px solid;">{{__('Property')}}</th>
                                    <th rowspan="2" style="text-align:center;border: 1px solid;">{{__('Tax Revenue Name')}}</th>
                                    <th rowspan="2" style="text-align:center;border: 1px solid;">{{__('Tax Revenue Description')}}</th>
                                    <th rowspan="2" style="text-align:center;border: 1px solid;">{{__('Revenue Year')}}</th>
                                    <th colspan="3" style="text-align:center;border: 1px solid;">{{__('Basic Tax')}}</th>
                                    <th colspan="3" style="text-align:center;border: 1px solid;">{{__("SEF[Special Education Fund]")}}</th>
                                    <th colspan="3" style="text-align:center;border: 1px solid;">{{__('SHT[Socialize Housing Tax]')}}</th>
                                    <th rowspan="2" style="text-align:center;border: 1px solid;">{{__("Action")}}</th>
                                </tr>
                                                        <tr>
                                    <!-- For Basic Tax -->
                                    <th style="border: 1px solid;text-align:center;">{{__('Basic')}}</th>
                                    <th style="border: 1px solid;text-align:center;">{{__("Discount")}}</th>
                                    <th style="border: 1px solid;text-align:center;">{{__("Penalty")}}</th>

                                    <!-- Special Education Fund -->
                                    <th style="border: 1px solid;text-align:center;">{{__('Sef')}}</th>
                                    <th style="border: 1px solid;text-align:center;">{{__("Discount")}}</th>
                                    <th style="border: 1px solid;text-align:center;">{{__("Penalty")}}</th>

                                    <!-- For Socialize Housing Tax -->
                                    <th style="border: 1px solid;text-align:center;">{{__('SHT')}}</th>
                                    <th style="border: 1px solid;text-align:center;">{{__("Discount")}}</th>
                                    <th style="border: 1px solid;text-align:center;">{{__("Penalty")}}</th>
                                </tr>
                               
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    {{Form::close()}}
    <script src="{{ asset('js/taxrevenue/index.js') }}?rand={{ rand(000,999) }}"></script>
@endsection

