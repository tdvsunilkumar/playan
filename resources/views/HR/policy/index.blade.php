@extends('layouts.admin')
@section('page-title')
    {{__('Policy - '.$data->title)}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Policy - '.$data->title)}}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table ">
                            <thead>
                            <tr>
                                <th>{{__('Description')}}</th>
                                <th>{{__('Value')}}</th>
                                <th>{{__('Note')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($data->rows as $row)
                                    <tr>
                                        <td>{{$row->hrsp_description}}</td>
                                        <td>{{$row->hrsp_value}}</td>
                                        <td>{{$row->hrsp_note}}</td>
                                        <td>
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{url('/hr/policy/edit/'.$row->id)}}" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit {{$data->title}}">
                                                    <i class="ti-pencil text-white"></i>
                                                </a>
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
    <script src="{{ asset('js/partials/datatable.js?v='.filemtime(getcwd().'/js/partials/datatable.js').'') }}"></script>
@endsection


