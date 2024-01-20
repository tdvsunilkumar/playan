@extends('layouts.admin')

@section('page-title')
    {{__('Employees')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Human Resource') }}</li>
    <li class="breadcrumb-item">{{ __('Employees') }}</li>
@endsection
{{ Form::hidden('isopen',$isopen, array('id' => 'isopen')) }}
@section('action-btn')
    <div class="float-end">
        <!-- filter -->
        <div class="row">
            <div class="col-md-5">
                <div class="btn-box" id="parent_filter_department">
                    {{ Form::label('filter_department', 'Department', ['class' => 'fs-6 fw-bold ']) }}
                    {{ Form::select('filter_department',
                        $departments,
                        '', 
                        array('class' => 'form-control select3','id'=>'filter_department')
                    ) }}                                
                </div>
            </div>
            <div class="col-md-5">
                <div class="btn-box" id="parent_filter_status">
                    {{ Form::label('filter_status', 'Status', ['class' => 'fs-6 fw-bold']) }}
                    {{ Form::select('filter_status',['InActive','Active','All'],1, array('class' => 'form-control select3','id'=>'filter_status')) }}                                
                </div>
            </div>
            <div class="col-md-2 float-end"><br>
                <a href="#" data-size="xxl" data-url="{{ url('/human-resource/employees/store2') }}" data-ajax-popup="true" data-bs-toggle="tooltip" id="addEmployee" title="{{__('Add Employee')}}" class="btn btn-sm btn-primary" data-controls-modal="your_div_id" data-title="Add Employee" data-backdrop="static" data-keyboard="false">
                    <i class="ti-plus"></i>
                </a>
            </div>
        </div>
            
        <!-- filter end -->
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card table-card">
                <div class="card-body">
                    <div id="datatable-2" class="dataTables_wrapper">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="employeeTable" class="display dataTable table w-100 table-striped" aria-describedby="supplierInfo">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID No.') }}</th>
                                                <th>{{ __('TITLE') }}</th>
                                                <th class="sliced">{{ __('FULLNAME') }}</th>
                                                <th class="sliced">{{ __('ADDRESS') }}</th>
                                                <th>{{ __('MOBILE NO') }}</th>
                                                <th class="sliced">{{ __('POSITION') }}</th>
                                                <th class="sliced">{{ __('DEPARTMENT') }}</th>
                                                <th>{{ __('LAST MODIFIED') }}</th>
                                                <th>{{ __('STATUS') }}</th>
                                                <th>{{ __('ACTIONS') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td valign="top" colspan="12" class="dataTables_empty">Loading...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- for some unknown reason i cant remove this, department sort not working -->
    @include('human-resource.employees.create')
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/dropzone/dropzone.css?v='.filemtime(getcwd().'/assets/vendors/dropzone/dropzone.css').'') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/selectpicker/bootstrap-select.min.css?v='.filemtime(getcwd().'/assets/vendors/selectpicker/bootstrap-select.min.css').'') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/growl/jquery.growl.css?v='.filemtime(getcwd().'/assets/vendors/growl/jquery.growl.css').'') }}"/>
@endpush
@push('scripts')
    <script src="{{ asset('js/custom.js?v='.filemtime(getcwd().'/js/custom.js').'') }}"></script>
    <script src="{{ asset('js/common.js?v='.filemtime(getcwd().'/js/common.js').'') }}"></script>

<script src="{{ asset('assets/vendors/dropzone/dropzone.js?v='.filemtime(getcwd().'/assets/vendors/dropzone/dropzone.js').'') }}"></script>
<script src="{{ asset('assets/vendors/selectpicker/bootstrap-select.min.js?v='.filemtime(getcwd().'/assets/vendors/selectpicker/bootstrap-select.min.js').'') }}"></script>

<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>

<script src="{{ asset('js/datatables/hr-employee.js?v='.filemtime(getcwd().'/js/datatables/hr-employee.js').'') }}"></script>
@endpush