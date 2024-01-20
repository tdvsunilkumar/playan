@extends('layouts.admin')
<title>Palayan-<?php echo $pageHeading2 = wordwrap($headingName, 100, "<br />\n");?></title>
@section('page-title')
    <?php 
    
    
        $sid= '';
        $subclass_id='';
        if(isset($_GET['sid'])){
            $sid = '?sid='.$_GET['sid'];
            $subclass_id=$_GET['sid'];
        }
        $pageHeading = 'BPLO Requirements';
        if(isset($headingName)){
           $pageHeading2 = wordwrap($headingName, 100, "<br />\n");
           $pageHeading="<div class='showLess' width=>".$pageHeading2."</div>";
        }
        echo $pageHeading;
    ?>
@endsection
@if(session()->has('remort_serv_add_req_rltn'))
    @php
    $remortSession = Session::get('remort_serv_add_req_rltn', []);
    $remortReqRltnTable = $remortSession['remort_req_rltn_table'] ?? null;
    $remortReqRltnAction = $remortSession['remort_req_rltn_action'] ?? null;
    $remortReqRltnIds = $remortSession['remort_req_rltn_ids'] ?? [];
    @endphp

    {{ Form::hidden('method_req_rltn', $remortReqRltnTable, ['id' => 'method_req_rltn']) }}
    {{ Form::hidden('action_req_rltn', $remortReqRltnAction, ['id' => 'action_req_rltn']) }}
    {{ Form::hidden('method_req_rltn_ids', json_encode($remortReqRltnIds), ['id' => 'method_req_rltn_ids']) }}
@endif
@php  Session::forget('remort_serv_add_req_rltn'); @endphp
{{ Form::hidden('sid',$subclass_id, array('id' => 'sid')) }}
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('BPLO Requirements')}}</li><?php
    if($subclass_id>0){ ?>
        <li class="breadcrumb-item"><a href="{{ url('/administrative/psic-libraries/sub-class')}}">{{__('Back To PSIC Sub-Class')}}</li></a><?php
    } ?>

@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        <a href="#" data-size="lg" data-url="{{ url('/bplorequirements/store'.$sid) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage '.$pageHeading)}}" class="btn btn-sm btn-primary">
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
    
    
    <script src="{{ asset('js/bploRequirement.js') }}"></script>
@endsection


