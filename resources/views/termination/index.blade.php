@extends('layouts.admin')
@section('page-title')
    {{__('Manage Termination')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Termination')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        
            <a href="#" data-url="{{ route('termination.create') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Create New Termination')}}" data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
                <i class="ti-plus"></i>
            </a>
        
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Employee Name')}}</th>
                                <th>{{__('Termination Type')}}</th>
                                <th>{{__('Notice Date')}}</th>
                                <th>{{__('Termination Date')}}</th>
                                <th>{{__('Description')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($terminations as $termination)
                                <tr>
                                    <td>{{ !empty($termination->employee())?$termination->employee()->name:'' }}</td>
                                    <td>{{ !empty($termination->terminationType())?$termination->terminationType()->name:'' }}</td>
                                    <td>{{  \Auth::user()->dateFormat($termination->notice_date) }}</td>
                                    <td>{{  \Auth::user()->dateFormat($termination->termination_date) }}</td>
                                    {{--                                    <td>{{ $termination->description }}</td>--}}
                                    <td>
                                        <a href="#" class="action-item" data-url="{{ route('termination.description',$termination->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Desciption')}}" data-title="{{__('Desciption')}}"><i class="fa fa-comment"></i></a>
                                    </td>
									<td>
										<div class="action-btn bg-primary ms-2">
											<a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ URL::to('termination/'.$termination->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Termination')}}" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
												<i class="ti-pencil text-white"></i>
											</a>
										</div>
										<div class="action-btn bg-danger ms-2">
											{!! Form::open(['method' => 'DELETE', 'route' => ['termination.destroy', $termination->id],'id'=>'delete-form-'.$termination->id]) !!}
											<a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$termination->id}}').submit();">
												<i class="ti-trash text-white"></i>
											</a>
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