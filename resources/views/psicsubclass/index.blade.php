@extends('layouts.admin')
@section('page-title')
    {{__('PSIC Subclass')}}
@endsection
@push('script-page')
@endpush
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
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('PSIC Subclass')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
         <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
             <a href="#" data-size="lg" data-url="{{ url('/psicsubclass/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage PSIC Subclass')}}" class="btn btn-sm btn-primary">
            <i class="ti-plus"></i>
        </a>
    </div>
@endsection

@section('content')
   <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card" >
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-end" >
                           
                           <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                                <div class="btn-box" >
                                {{ Form::label('Section', 'Section', ['class' => 'fs-6 fw-bold']) }}
                                {{
                                    Form::select('Section', $arrsection, $value = '', ['id' => 'Section', 'class' => 'form-control ','data-placeholder' => 'Please select'])
                                }}                                 
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                                <div class="btn-box" >
                                {{ Form::label('Division', 'Division', ['class' => 'fs-6 fw-bold']) }}
                                {{
                                    Form::select('Division', $arrdivision, $value = '', ['id' => 'Division', 'class' => 'form-control','data-placeholder' => 'Please select'])
                                }}                                 
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                                <div class="btn-box" >
                                {{ Form::label('Group', 'Group', ['class' => 'fs-6 fw-bold']) }}
                                {{
                                    Form::select('Group', $arrgroup, $value = '', ['id' => 'Group', 'class' => 'form-control','data-placeholder' => 'Please select'])
                                }}                                 
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 pdr-20">
                                <div class="btn-box" >
                                {{ Form::label('Class', 'Class', ['class' => 'fs-6 fw-bold']) }}
                                {{
                                    Form::select('ClassId', $arrclass, $value = '', ['id' => 'ClassId', 'class' => 'form-control','data-placeholder' => 'Please select'])
                                }}                                 
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 pdr-20">
                                <div class="btn-box">
                                    {{ Form::label('Search', 'Search Here...', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
                                </div>
                            </div>
                             <div class="col-auto float-end ms-1" style="padding-top: 19px;">
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
                                <th>{{__('Subclass Code')}}</th>
                                <th>{{__('Section')}}</th>
                                <th>{{__('Division ')}}</th>
                                <th>{{__('Group Desc')}}</th>
                                <th>{{__('Class Desc')}}</th>
                                <th>{{__('Subclass Desc')}}</th>
                                <th>{{__('Establishment')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            
                            <tbody>
                            <?php /*@foreach ($Psicsubclass as $val)
                                <tr class="font-style"> 
                                    <td>{{ $val->subclass_code }}</td>
                                    <td>{{ $val->section_code}}</td>
                                    <td>{{ $val->division_code }}</td>
                                    <td>{{ $val->group_code }}</td>
                                    <td>{{ $val->class_code }}</td>
                                    
                                    <td>{{ $val->subclass_description}}</td>
                                    <td>
                                        @if($val->is_active==1)
                                                <span class="btn btn-success">Active</span>
                                            @else
                                                 <span class="btn btn-warning">InActive</span>
                                            @endif
                                    </td>
                                   <td class="Action">
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ url('/psicsubclass/store?id='.$val->id) }}" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="{{__('Edit')}}"  data-title="{{__('Edit SubClass')}}">  
                                                <i class="ti-pencil text-white"></i>
                                            </a>
                                        </div>
                                       <!--  <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['psicsubclass.destroy', $val->id],'id'=>'delete-form-'.$val->id]) !!}
                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" ><i class="ti-trash text-white"></i></a>
                                            {!! Form::close() !!}
                                        </div> -->
                                    </td>
                                </tr>
                            @endforeach*/?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <script src="{{ asset('js/psicsubclass.js') }}?rand={{ rand(000,999) }}"></script>
@endsection
