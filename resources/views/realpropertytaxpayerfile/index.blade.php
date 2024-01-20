@extends('layouts.admin')
@section('page-title')
    {{__("Property Owner's File")}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__("Property Owner's File")}}</li>
@endsection
@section('action-btn')

    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i></a>

<!-- <a href="#" data-size="lg" data-url="{{ url('/rptpropertyowner/store') }}" class="btn btn-sm btn-primary addNewPropertyOwner">
            <i class="ti-plus"></i>
        </a> -->
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
                           <!--  <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" >
                               
                                    <div class="btn-box">
                                         {{ Form::label('Search', 'By Alphabet', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::select('alphabet',['a'=>'A','b'=>'B','c'=>'C','d'=>'D','e'=>'E','f'=>'F','g'=>'G','h'=>'H','i'=>'I','j'=>'J','k'=>'K','l'=>'L','m'=>'M','n'=>'N','o'=>'O','p'=>'P','q'=>'Q','r'=>'R','s'=>'S','t'=>'T','u'=>'U','v'=>'V','x'=>'X','y'=>'Y','z'=>'Z'],'', array('class' => 'form-control','id'=>'rptPropertySearchByAlphabet','placeholder' => 'Search By Alphabet')) }}
                                    </div>
                                
                            </div> -->
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('Search', 'Search Here...', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
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
                                 <th>{{__('No.')}}</th>
                                 <th>{{__("Owner's Name")}}</th>
                                 <th>{{__('Address')}}</th>
                                 <th>{{__('Mobile No.')}}</th>
                                 <th>{{__('Email Address')}}</th>
                                 <th>{{__('action')}}</th>
                                
                            </tr>
                            </thead>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="addPropertyOwnerModal" data-backdrop="static" >
        <div class="modal-dialog " >
            <div class="modal-content" >
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
            </div>
        </div>
    </div> 

    <div class="modal" id="taxDeclarationSummary" data-backdrop="static" >
        <div class="modal-dialog " >
            <div class="modal-content" >
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
            </div>
        </div>
    </div> 
    <script src="{{ asset('js/rptop/index.js') }}?rand={{0,999}}"></script>
@endsection

