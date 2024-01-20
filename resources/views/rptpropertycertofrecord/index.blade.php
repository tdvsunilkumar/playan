@extends('layouts.admin')
@section('page-title')
    
    {{__('Certificate Record File')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')



    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Certificate Record File')}}</li>
@endsection
@section('action-btn')
   <!--  <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        <a href="#" data-size="lg" data-url="{{ url('/rptpropertycertofpropertyholding/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Certificate of Property Holdings')}}" class="btn btn-sm btn-primary">
            <i class="ti-plus"></i>
        </a>
    </div> -->
@endsection



@section('content')
<link href="{{ asset('assets/js/yearpicker.css')}}" rel="stylesheet">
<style type="text/css">
    .yearpicker-container {
    position: fixed;
    color: var(--text-color);
    width: 280px;
    border: 1px solid var(--border-color);
    border-radius: 3px;
    font-size: 1rem;
    box-shadow: 1px 1px 8px 0px rgba(0, 0, 0, 0.2);
    background-color: var(--background-color);
    z-index: 10;
    margin-top: 0.2rem;
}
</style>

    <!-- <div class="row hide" id="this_is_filter"> -->
        <div class="col-sm-12">
            <div class=" mt-10 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{-- {{ Form::open(array('url' => '')) }} --}}
                        <div class="d-flex align-items-center  justify-content-end">
                            <div class="col-xl-3 col-lg-1 col-md-6 col-sm-12 col-12">
                              <div class="form-group">  
                               
                                <div class="btn-box" style="padding-right: 20px;">
                                  <div class="btn-box">
                                     <b>Property Kind</b>{{ Form::select('allType',array('2'=>'Land','1' =>'Building','3' =>'Machineries'),'', array('class' => 'form-control spp_type','id'=>'allType')) }}
                                </div>
                                </div>
                              </div>
                            </div>
                            
                          <!--   <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12 col-12">
                              <div class="form-group">  
                               
                                <div class="btn-box" style="padding-right: 20px;">
                                   <input type="radio" name="allType" id="allType" value="2">  Buliding
                                </div>
                              </div>
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12 col-12">
                              <div class="form-group">  
                               
                                <div class="btn-box" style="padding-right: 20px;">
                                    <input type="radio" name="allType" id="allType" value="3"> All Kind
                                </div>
                              </div>
                            </div> -->
                            
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                              <div class="form-group">  
                                <div class="btn-box">
                                     <b>Certificate Type</b>{{ Form::select('rpc_cert_type',array('1' =>'Property Holding','2' =>'No Landholding','3'=>'No Improvement-Portion'),'', array('class' => 'form-control spp_type','id'=>'rpc_cert_type')) }}
                                </div>
                              </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12">
                              <div class="form-group">  
                               <div class="btn-box" style="padding-left: 20px;padding-right: 20px;">
                                    <?php
                                        $currentYear = date('Y');
                                    ?>
                                   <b> Year</b>{{ Form::text('year', $currentYear, array('class' => 'yearpicker form-control','id'=>'year')) }}
                                </div>
                              </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12">
                              <div class="form-group">  
                               
                                <div class="btn-box" style="padding-right: 20px;">
                                   <b> Details</b>{{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Details...','id'=>'q')) }}
                                </div>
                              </div>
                            </div>
                            <div class="col-auto float-end ms-2"style="margin-top:-5px;">
                                <a href="#" class="btn btn-sm btn-primary" id="btn_search">
                                    <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
                                    <span class="btn-inner--icon"><i class="ti-trash"></i></span>
                                </a>
                            </div>

                        </div>
                        {{-- {{Form::close()}} --}}
                    </div>
                </div>
            </div>
        </div>
    <!-- </div> -->
    <div class="row">
        
        
        
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="Jq_datatablelist">
                            <thead>
                            <tr>
                                <th>{{__('No.')}}</th>
                                <th>{{__('Year')}}</th>
                                <th>{{__('Controller No.')}}</th>
                                <th>{{__('Owner')}}</th>
                                <th>{{__('Requestor')}}</th>
                                <th>{{__('City Assessor')}}</th>
                                <th>{{__('Remarks')}}</th>
                                <th>{{__('Certificate Type')}}</th>
                                <th>{{__('O.R. NO.')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<script src="{{ asset('assets/js/yearpicker.js') }}"></script>
<script src="{{ asset('js/certificateOfRecord.js') }}?rand={{ rand(000,999) }}"></script>
@endsection

