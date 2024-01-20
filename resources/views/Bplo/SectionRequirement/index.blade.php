@extends('layouts.admin')
<title>Palayan-<?php echo $pageHeading2 = wordwrap($headingName, 100, "<br />\n");?></title>
@section('page-title')
    <?php 
    
    
        $sid= '';
        $section_id='';
        if(isset($_GET['sid'])){
            $sid = '?sid='.$_GET['sid'];
            $section_id=$_GET['sid'];
        }
        $pageHeading = 'BPLO Requirements';
        if(isset($headingName)){
           $pageHeading2 = wordwrap($headingName, 100, "<br />\n");
           $pageHeading="<div class='showLess' width=>".$pageHeading2."</div>";
        }
        echo $pageHeading;
    ?>
@endsection
{{ Form::hidden('sid',$section_id, array('id' => 'sid')) }}
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Section Requirements')}}</li><?php
    if($section_id>0){ ?>
        <li class="breadcrumb-item"><a href="{{ url('/administrative/psic-libraries/section')}}">{{__('Back To PSIC Section')}}</li></a><?php
    } ?>

@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        <a href="#" data-size="lg" data-url="{{ url('/bplo-section-requirements/store'.$sid) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage '.$headingName)}}" class="btn btn-sm btn-primary">
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
                                    <span class="btn-inner--icon"><i class="ti-trash "></i></span>
                                </a>
                            </div>

                        </div>
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
                                <th>{{__('App Type')}}</th>
                                <th>{{__('Requirements')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <script src="{{ asset('js/Bplo/SectionRequirement.js') }}"></script>
@endsection


