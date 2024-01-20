@extends('layouts.admin')
<title>Palayan-<?php echo $pageHeading2 = wordwrap($_GET['name'], 100, "<br />\n");?></title>
@section('page-title')

    <?php 
    $pageHeading = 'Tax Fee And Other Charges';
    if(isset($_GET['name'])){
        $pageHeading2 = wordwrap($_GET['name'], 100, "<br />\n");
        $pageHeading="<div class='showLess' width=>".$pageHeading2."</div>";
    }else{
        $pageHeading = 'Tax Fee And Other Charges';
    }
    echo $pageHeading;
    ?>
@endsection
@push('script-page')
@endpush
{{ Form::hidden('type',$_GET['type'], array('id' => 'type')) }}
{{ Form::hidden('sid',$_GET['sid'], array('id' => 'sid')) }}
{{ Form::hidden('pageHeading',$pageHeading, array('id' => 'pageHeading')) }}
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Tax Fee And Other Charges')}}</li><?php
    if($_GET['type']==1){ ?>
        <li class="breadcrumb-item"><a href="{{ url('/administrative/psic-libraries/section')}}">{{__('Back To PSIC Section')}}</li></a><?php
    }if($_GET['type']==2){ ?>
        <li class="breadcrumb-item"><a href="{{ url('/administrative/psic-libraries/sub-class')}}">{{__('Back To PSIC Sub-Class')}}</li></a><?php
    }  ?>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        <a href="#" data-size="xxll" data-url="{{ url('/PsicTfoc/store?type='.$_GET['type'].'&sid='.$_GET['sid'].'') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="Manage {{__($pageHeading)}}" class="btn btn-sm btn-primary" style="color:#fff;">
            <i class="ti-plus" style="color:#fff;"></i>
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
                                <a href="#" class="btn btn-sm btn-danger" id="clean">
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
                                <th>{{__('Description')}}</th>
                                <th>{{__('Transaction')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('STATUS')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                           </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('js/Bplo/PsicTfoc.js') }}"></script>
@endsection
