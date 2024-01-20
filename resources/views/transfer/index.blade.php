@extends('layouts.admin')
@section('page-title')
    {{__('Manage Transfer')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Transfer')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
		<a href="#" data-size="lg" data-url="{{ route('transfer.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New Transfer')}}" class="btn btn-sm btn-primary">
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
                                <th>{{__('Branch')}}</th>
                                <th>{{__('Department')}}</th>
                                <th>{{__('Transfer Date')}}</th>
                                <th>{{__('Description')}}</th>
                                 <th width="200px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($transfers as $transfer)
                                <tr>
                                    <td>{{ !empty($transfer->employee())?$transfer->employee()->name:'' }}</td>
                                    <td>{{ !empty($transfer->branch())?$transfer->branch()->name:'' }}</td>
                                    <td>{{ !empty($transfer->department())?$transfer->department()->name:'' }}</td>
                                    <td>{{  \Auth::user()->dateFormat($transfer->transfer_date) }}</td>
                                    <td>{{ $transfer->description }}</td>
									<td>
									<div class="action-btn bg-primary ms-2">
										<a href="#" data-url="{{ URL::to('transfer/'.$transfer->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Transfer')}}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
											<i class="ti-pencil text-white"></i>
										</a>
									</div>
									<div class="action-btn bg-danger ms-2">
										{!! Form::open(['method' => 'DELETE', 'route' => ['transfer.destroy', $transfer->id],'id'=>'delete-form-'.$transfer->id]) !!}

										<a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" data-original-title="{{__('Delete')}}" title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$transfer->id}}').submit();">
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
