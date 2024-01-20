@extends('layouts.admin')
@section('page-title')
    {{__('Engineering Building Permit App')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Engineering Building Permit App')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
         <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
             <a href="#" data-size="lg" data-url="{{ url('/engbldgpermitapp/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Engineering Bldg Permit App')}}" class="btn btn-sm btn-primary modal-dialog-scrollable">
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
                        <form method="GET" action="#" accept-charset="UTF-8" id="product_service">
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
                        </form>
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
                                <th>{{__('Municipal')}}</th>
                                <th>{{__('Applocation No')}}</th>
                                <th>{{__('Permit No')}}</th>
                                <th>{{__('Application Date')}}</th>
                                <th>{{__('Owner Name')}}</th>
                                <th>{{__('Issued Date')}}</th>
                                <th>{{__('Address')}}</th>
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
     <script src="{{ asset('js/engneering/engbldgpermitapp.js') }}"></script>
@endsection
