@extends('layouts.admin')
@section('page-title')
    {{__('Business Types')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Business Types')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" data-size="lg" data-url="{{ url('/typeofbussiness/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create Business Type')}}" class="btn btn-sm btn-primary">
            <i class="ti-plus"></i>
        </a>
    </div>
@endsection
<?php if(!empty($data)){ print_r($data); exit;}?>
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Business  Code')}}</th>
                                <th>{{__('Business Type')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($Typeofbussiness as $val)
                                <tr class="font-style">
                                    <td>{{ $val->bussiness_code}}</td>
                                    <td>{{ $val->bussiness_type }}</td>
                                    <td>
                                        @if($val->is_active==1)
                                                <span class="btn btn-success">Active</span>
                                            @else
                                                 <span class="btn btn-warning">InActive</span>
                                            @endif
                                    </td>
                                    
                                    <td class="Action">
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" data-size="lg" data-url="{{ url('/typeofbussiness/store?id='.$val->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Edit Business Type')}}" class="btn btn-sm align-items-center">
                                            <i class="ti-pencil text-white"></i>
                                            </a>
                                        </div>
                                       <!--  <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['psicsection.destroy', $val->id],'id'=>'delete-form-'.$val->id]) !!}
                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" ><i class="ti-trash text-white"></i></a>
                                            {!! Form::close() !!}
                                        </div> -->
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
