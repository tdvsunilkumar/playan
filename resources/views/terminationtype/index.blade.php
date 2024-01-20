@extends('layouts.admin')
@section('page-title')
    {{__('Manage Termination Type')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Termination Type')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
		<a href="#" data-url="{{ route('terminationtype.create') }}" data-ajax-popup="true" data-title="{{__('Create New Termination Type')}}" data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
			<i class="ti-plus"></i>
		</a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-3">
            @include('layouts.hrm_setup')
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Termination Type')}}</th>
                                <th width="200px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($terminationtypes as $terminationtype)
                                <tr>
                                    <td>{{ $terminationtype->name }}</td>
                                    <td>
										<div class="action-btn bg-primary ms-2">
											<a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ URL::to('terminationtype/'.$terminationtype->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Document Type')}}" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
												<i class="ti-pencil text-white"></i>
											</a>
										</div>
									
										<div class="action-btn bg-danger ms-2">
											{!! Form::open(['method' => 'DELETE', 'route' => ['terminationtype.destroy', $terminationtype->id],'id'=>'delete-form-'.$terminationtype->id]) !!}
											<a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti-trash text-white text-white"></i></a>
											{!! Form::close() !!}
										</div>
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
