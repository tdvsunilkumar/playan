@extends('layouts.admin')

@section('page-title')
    {{__('Manage Warning')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Warning')}}</li>
@endsection


@section('action-btn')
    <div class="float-end">
  
            <a href="#" data-url="{{ route('warning.create') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Create New Warning')}}" data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
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
                                <th>{{__('Warning By')}}</th>
                                <th>{{__('Warning To')}}</th>
                                <th>{{__('Subject')}}</th>
                                <th>{{__('Warning Date')}}</th>
                                <th>{{__('Description')}}</th>
                                
                                    <th width="200px">{{__('Action')}}</th>
                                
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($warnings as $warning)
                                <tr>
                                    <td>{{!empty( $warning->WarningBy($warning->warning_by))? $warning->WarningBy($warning->warning_by)->name:'' }}</td>
                                    <td>{{ !empty($warning->warningTo($warning->warning_to))?$warning->warningTo($warning->warning_to)->name:'' }}</td>
                                    <td>{{ $warning->subject }}</td>
                                    <td>{{  \Auth::user()->dateFormat($warning->warning_date) }}</td>
                                    <td>{{ $warning->description }}</td>
                                    
                                        <td>
											<div class="action-btn bg-primary ms-2">
												<a href="#" class="mx-3 btn btn-sm align-items-center" data-size="lg" data-url="{{ URL::to('warning/'.$warning->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Warning')}}" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
												<i class="ti-pencil text-white"></i>
											</a>
											</div>
									   
											<div class="action-btn bg-danger ms-2">
											{!! Form::open(['method' => 'DELETE', 'route' => ['warning.destroy', $warning->id],'id'=>'delete-form-'.$warning->id]) !!}
													<a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$warning->id}}').submit();">
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
