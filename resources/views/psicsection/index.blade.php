@extends('layouts.admin')
@section('page-title')
    {{__('PSIC Section')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('PSIC Sections')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" data-size="lg" data-url="{{ url('/psicsection/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create PSIC Section')}}" class="btn btn-sm btn-primary">
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
                                <th>{{__('No.')}}</th>
                                <th>{{__('Section Code')}}</th>
                                <th>{{__('Section Desc')}}</th>
                                <th>{{__('Section Status')}}</th>
                                <th>{{__('Section Date')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                 @php $i=0; @endphp
                            @foreach ($PsicSections as $val)
                                <?php $i=$i+1; ?>
                                <tr class="font-style">
                                    <td>{{$i}}</td>
                                    <td>{{ $val->section_code}}</td>
                                    <td>{{ $val->section_description }}</td>
                                    <td>
                                        @if($val->section_status==1)
                                                <span class="btn btn-success">Active</span>
                                            @else
                                                 <span class="btn btn-warning">InActive</span>
                                            @endif
                                    </td>
                                    
                                    
                                    <td>{{ $val->section_generated_date}}</td>
                                    <td class="Action">
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" data-size="lg" data-url="{{ url('/psicsection/store?id='.$val->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Edit PSIC Section')}}" class="btn btn-sm align-items-center">
                                            <i class="ti-pencil text-white"></i>
                                            </a>
                                        </div>
                                        <div class="action-btn bg-info ms-2">
                                            <a href="{{ url('/PsicTfoc?type=1&name='.$val->section_description.'&sid='.$val->id) }}" title="{{__('Set Charges')}}" class="btn btn-sm align-items-center">
                                            <i class="ti ti-currency-dollar text-white"></i>
                                            </a>
                                        </div>
                                        <div class="action-btn bg-info ms-2">
                                            <a href="{{ url('/bplo-section-requirements?sid='.$val->id) }}" title="Set Requirement" class="btn btn-sm align-items-center">
                                                <i class="ti-write text-white"></i>
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
