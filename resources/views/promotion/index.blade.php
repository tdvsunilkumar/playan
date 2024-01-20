@extends('layouts.admin')

@section('page-title')
    {{__('Manage Promotion')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Promotion')}}</li>
@endsection

@section('action-btn')
    <div class="float-end">
    
            <a href="#" data-url="{{ route('promotion.create') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Create New Promotion')}}" data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
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
                                
                                <th>{{__('Designation')}}</th>
                                <th>{{__('Promotion Title')}}</th>
                                <th>{{__('Promotion Date')}}</th>
                                <th>{{__('Description')}}</th>
                                <th width="200px">{{__('Action')}}</th>  
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($promotions as $promotion)
                                <tr>
                                    
                                    <td>{{ !empty($promotion->employee())?$promotion->employee()->name:'' }}</td>
                                    
                                    <td>{{ !empty($promotion->designation())?$promotion->designation()->name:'' }}</td>
                                    <td>{{ $promotion->promotion_title }}</td>
                                    <td>{{  \Auth::user()->dateFormat($promotion->promotion_date) }}</td>
                                    <td>{{ $promotion->description }}</td>
                                   
                                        <td>
                                         
                                           
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ URL::to('promotion/'.$promotion->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Promotion')}}" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                    <i class="ti-pencil text-white"></i>
                                                </a>
                                                </div>
                                           

                                        

                                            
                                                <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['promotion.destroy', $promotion->id],'id'=>'delete-form-'.$promotion->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$promotion->id}}').submit();">
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
