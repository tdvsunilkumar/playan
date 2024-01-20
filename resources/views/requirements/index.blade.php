@extends('layouts.admin')
@section('page-title')
    {{__('Requirements')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Requirements')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" data-size="lg" data-url="{{ url('/requirements/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create Requirements')}}" class="btn btn-sm btn-primary">
            <i class="ti-plus"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('No.')}}</th>
                                <th>{{__('Code')}}</th>
                                <th>{{__('Description')}}</th>
                                <th>{{__('REQ-BPLO')}}</th>
                                <th>{{__('REQ-BFP')}}</th>
                                <th>{{__('REQ-HEALTH OFFICE')}}</th>
                                <th>{{__('REQ-ENGINEERING')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            
                            </thead>
                            <tbody>
                                <?php $i=0;?>
                            @foreach ($data as $val)
                            <?php $i=$i+1;?>
                                <tr class="font-style">
                                    <td>{{ $i}}</td>
                                    <td>{{ $val->req_code_abbreviation}}</td>
                                    <td>{{ $val->req_description }}</td>
                                    <td> @if($val->req_dept_bplo==1)
                                                <span class="btn btn-success" style="background: #09720b;">Yes</span>
                                            @else
                                                 <span class="btn btn-danger" style="padding: 5px;">No</span>
                                        @endif</td>
                                    <td>@if($val->req_dept_bfp==1)
                                                <span class="btn btn-success" style="background: #09720b;">Yes</span>
                                            @else
                                                 <span class="btn btn-danger" style="padding: 5px;">No</span>
                                        @endif</td>
                                    <td>@if($val->req_dept_health_office==1)
                                                 <span class="btn btn-success" style="background: #09720b;">Yes</span>
                                            @else
                                                 <span class="btn btn-danger" style="padding: 5px;">No</span>
                                        @endif</td>
                                      <td>@if($val->req_dept_eng==1)
                                                 <span class="btn btn-success" style="background: #09720b;">Yes</span>
                                            @else
                                                 <span class="btn btn-danger" style="padding: 5px;">No</span>
                                        @endif</td>
                                    <td>   
                                        @if($val->is_active==1)
                                                <span class="btn btn-success">Active</span>
                                            @else
                                                 <span class="btn btn-warning">InActive</span>
                                            @endif
                                    </td>
                                    <td class="Action">
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ url('/requirements/store?id='.$val->id) }}" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="{{__('Edit')}}"  data-title="{{__('Requirements Edit')}}">
                                                <i class="ti-pencil text-white"></i>
                                            </a>
                                        </div>
                                        <!-- <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['requirements.destroy', $val->id],'id'=>'delete-form-'.$val->id]) !!}
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
