@extends('layouts.admin')
@section('page-title')
    {{__('Tax Type')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Tax Type')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i></a>
        <a href="#" data-size="lg" data-url="{{ url('/taxtype/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create Tax Type')}}" class="btn btn-sm btn-primary">
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
                                    <span class="btn-inner--icon"><i class="ti-trash"></i></span>
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
                        <table class="table datatable" id="Jq_datatablelist">
                            <thead>
                            <tr>
                                <th>{{__('No.')}}</th>
                                <th>{{__('Type Code')}}</th>
                                <th>{{__('Tax Class')}}</th>
                                <th>{{__('Tax Type')}}</th>
                                <th>{{__('Short Name')}}</th>
                                <th>{{__('Reference')}}</th>
                                <th>{{__('Tax Category')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            

                            
                            <tbody>
                          <!--   @foreach ($data as $val)
                                <tr class="font-style">
                                    <td>{{ $val->tax_class_code}}{{ $val->type_code}}</td>
                                    <td>{{ $val->tax_class_code}}-{{ $val->tax_class_desc}}</td>
                                    <td>{{ $val->type_code}}-{{ $val->tax_type_description}}</td>
                                    <td>{{ $val->tax_type_short_name}}</td>
                                    <td>{{ $val->tia_account_code }}</td>
                                    <td>{{ $val->tax_type_description}}</td>
                                    <td>
                                        @if($val->is_active==1)
                                            <span class="btn btn-success">Active</span>
                                        @else
                                                <span class="btn btn-warning">InActive</span>
                                        @endif
                                    </td>
                                    <td class="Action">
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ url('/taxtype/store?id='.$val->id) }}" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="{{__('Edit')}}"  data-title="{{__('Type Edit')}}">
                                                <i class="ti-pencil text-white"></i>
                                            </a>


                                        </div>
                                    </td>
                                </tr>
                            @endforeach -->
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
     <script src="{{ asset('js/taxtype.js') }}">
    </script>
@endsection
