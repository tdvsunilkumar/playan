@inject('controller', 'App\Http\Controllers\GsoPPMPController')
@extends('layouts.admin')

@section('page-title')
    {{__('Project Procurement Management Plan (Edit)')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('General Services') }}</li>
    <li class="breadcrumb-item">{{ __('PPMP') }}</li>
    <li class="breadcrumb-item">{{ __('EDIT') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="{{ url('general-services/project-procurement-management-plan') }}" data-size="lg" data-bs-toggle="tooltip" data-bs-placement="left" title="{{__('Back to Manage PPMP')}}" class="btn btn-sm btn-primary add-btn">
            <i class="ti-angle-double-left"></i> Back
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div id="ppmp-card" class="card table-card">
                <div class="card-header">
                    <h5>PPMP Details</h5>
                </div>
                <div class="card-body">
                    {{ Form::open(array('url' => 'general-services/project-procurement-management-plan', 'class'=>'formDtls needs-validation', 'name' => 'ppmpForm')) }}
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('fund_code_id', 'FUND CODE', ['class' => '']) }}
                                {{
                                    Form::select('fund_code_id', $fund_codes, $ppmp->fund_code_id, ['id' => 'fund_code_id', 'class' => 'form-control select3 fs-3', 'data-placeholder' => 'select a department', 'disabled' => 'disabled'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-form__group">
                                {{ Form::label('control_no', 'CONTROL NO.', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'control_no', $ppmp->control_no, 
                                    $attributes = array(
                                        'id' => 'control_no',
                                        'class' => 'form-control form-control-solid form-control-lg fs-3 fw-bold',
                                        'disabled' => 'disabled'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('department_id', 'DEPARTMENT', ['class' => '']) }}
                                {{
                                    Form::select('department_id', $departments, $ppmp->department_id, ['id' => 'department_id', 'class' => 'form-control select3 fs-3', 'data-placeholder' => 'select a department', 'disabled' => 'disabled'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('division_id', 'DIVISION', ['class' => '']) }}
                                {{
                                    Form::select('division_id', $divisions, request()->get('division'), ['id' => 'division_id', 'class' => 'form-control select3 fs-3', 'data-placeholder' => 'select a division', 'disabled' => 'disabled'])
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-form__group">
                                {{ Form::label('budget_year', 'YEAR', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::text($name = 'budget_year', $ppmp->budget_year, 
                                    $attributes = array(
                                        'id' => 'budget_year',
                                        'class' => 'form-control form-control-solid form-control-lg fs-3',
                                        'placeholder' => 'select a year',
                                        'disabled' => 'disabled'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group m-form__group required mb-2">
                                {{ Form::label('remarks', 'REMARKS', ['class' => 'required fs-6 fw-bold']) }}
                                {{ 
                                    Form::textarea($name = 'remarks', $ppmp->remarks, 
                                    $attributes = array(
                                        'id' => 'remarks',
                                        'class' => 'form-control form-control-solid fs-4',
                                        'rows' => 4,
                                        'disabled' => 'disabled'
                                    )) 
                                }}
                                <span class="m-form__help text-danger"></span>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    @php $locked = $controller->check_if_division_locked($ppmp->id, request()->get('division')); @endphp
    @if (count($budgets) > 0)
    <div class="row">
        <div class="col-xl-12">
            <div id="ppmp-details-card" class="card table-card">     
                <div class="card-body" style="position: relative">
                    <div class="form-group m-form__group mb-0" style="position:absolute; top: 25px; right: 25px; max-width: 400px; margin-top: -5px">
                        {{
                            Form::select('budget_gl_account_id', $budgetx, $budgets[0]->gl_account->code, ['id' => 'budget_gl_account_id', 'class' => 'form-control select3', 'data-placeholder' => 'select a budget'])
                        }}
                        <span class="m-form__help text-danger"></span>
                    </div>
                    <div class="tab-content" id="nav-tabContent">
                        @php $i = 0; @endphp
                        @foreach ($budgets as $budget)
                            <div class="tab-pane fade {{ ($i == 0) ? 'show active' : '' }}" id="tab-{{ $budget->gl_account->code }}" role="tabpanel" aria-labelledby="nav-{{ $budget->gl_account->code }}">
                                <h5 class="d-flex">
                                    <div class="me-auto total-gl-budget" data-row-total-gl-budget="{{ ($budget->final_budget ? number_format(floor(($budget->final_budget*100))/100, 2, '.', '') : number_format(floor(($budget->annual_budget*100))/100, 2, '.', '') ) }}">
                                        [ <span class="text-primary">{{ $budget->gl_account->code }}</span> ] <span class="text-primary">{{ $budget->gl_account->description }}</span> - <span class="fs-4 text-danger">{{ ($budget->final_budget ? number_format(floor(($budget->final_budget*100))/100, 2) : number_format(floor(($budget->annual_budget*100))/100, 2) ) }}</span>
                                    </div>
                                </h5>
                                <!-- START -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="ppmp-table" class="table table-{{ $budget->gl_account->id }} table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" colspan="1" class="text-center">CODE</th>
                                                        <th rowspan="2" colspan="1" class="text-center">GENERAL DESCRIPTION</th>
                                                        <th rowspan="2" colspan="1" class="text-center">QUANTITY SIZE</th>
                                                        <th rowspan="2" colspan="1" class="text-center">UNIT</th>
                                                        <th rowspan="2" colspan="1" class="text-center">ESTIMATED BUDGET</th>
                                                        <th rowspan="1" colspan="12" class="text-center">SCHEDULE/MILESTONE OF ACTIVITIES</th>
                                                    </tr>
                                                    <tr>
                                                        <th rowspan="1" colspan="1" class="text-center">JAN</th>
                                                        <th rowspan="1" colspan="1" class="text-center">FEB</th>
                                                        <th rowspan="1" colspan="1" class="text-center">MAR</th>
                                                        <th rowspan="1" colspan="1" class="text-center">APR</th>
                                                        <th rowspan="1" colspan="1" class="text-center">MAY</th>
                                                        <th rowspan="1" colspan="1" class="text-center">JUN</th>
                                                        <th rowspan="1" colspan="1" class="text-center">JUL</th>
                                                        <th rowspan="1" colspan="1" class="text-center">AUG</th>
                                                        <th rowspan="1" colspan="1" class="text-center">SEP</th>
                                                        <th rowspan="1" colspan="1" class="text-center">OCT</th>
                                                        <th rowspan="1" colspan="1" class="text-center">NOV</th>
                                                        <th rowspan="1" colspan="1" class="text-center">DEC</th>
                                                    </tr>
                                                </thead>
                                                <tbody data-row-gl="{{ $budget->gl_account->id }}" data-row-gl-code="{{ $budget->gl_account->code }}">
                                                    @php
                                                        $rows = 10; $totalAmt = 0; $iteration = 1;
                                                        $lines = $controller->find_lines($ppmp->id, $budget->gl_account->id, request()->get('division'));
                                                    @endphp
                                                    
                                                    @if (!empty($lines))
                                                        @foreach ($lines as $line)
                                                            <tr class="{{ ($locked > 0) ? 'active' : '' }}" data-row-id="{{ $line->id }}" data-row-gl-code="{{ $line->gl_account ? $line->gl_account->code : '' }}" data-row-division="{{ $line->division->id }}" data-row-division-code="{{ $line->division->code }}" data-row-cost="{{ $line->amount }}" data-row-total="{{ $line->total_amount }}" data-row-item="{{ $line->item_id }}" data-row-uom="{{ $line->uom_id }}" data-row-quantity="{{ $line->quantity }}" data-row-gl="{{ $line->gl_account_id }}">
                                                                <td rowspan="1" colspan="1" class="text-center fw-bold">{{ $line->gl_account ? $line->gl_account->code : '' }}</td>
                                                                <td rowspan="1" colspan="1" class="text-left" width="30%">
                                                                    <div class="form-group m-form__group required m-0">
                                                                        @if ($locked > 0)
                                                                        {{
                                                                            Form::select('item_id[]', $items[$budget->gl_account->id], $value = $line->item_id, ['id' => 'item_'.$budget->gl_account->code.'_'.$iteration, 'class' => 'form-control select3', 'data-placeholder' => '', 'disabled' => 'disabled'])
                                                                        }}
                                                                        @else
                                                                        {{
                                                                            Form::select('item_id[]', $items[$budget->gl_account->id], $value = $line->item_id, ['id' => 'item_'.$budget->gl_account->code.'_'.$iteration, 'class' => 'form-control select3', 'data-placeholder' => ''])
                                                                        }}
                                                                        @endif
                                                                        <span class="m-form__help text-danger"></span>
                                                                    </div>
                                                                </td>
                                                                <td rowspan="1" colspan="1" class="text-center" width="10%">
                                                                    @if ($locked > 0)
                                                                        <input type="text" class="text-center numeric-double" name="quantity[]" value="{{ $line->quantity }}" disabled="disabled">
                                                                    @else
                                                                        <input type="text" class="text-center numeric-double" name="quantity[]" value="{{ $line->quantity }}">
                                                                    @endif
                                                                </td>
                                                                <td rowspan="1" colspan="1" class="text-center fw-bold">{{ $line->uom ? $line->uom->code : '' }}</td>
                                                                <td rowspan="1" colspan="1" class="text-center fw-bold">{{ number_format($line->total_amount, 2) }}</td>
                                                                <td rowspan="1" colspan="1" class="text-center">{{ $controller->validate_item_request('01', $line->item_id, $ppmp->fund_code_id, $ppmp->department_id, request()->get('division'), $ppmp->budget_year) }}</td>
                                                                <td rowspan="1" colspan="1" class="text-center">{{ $controller->validate_item_request('02', $line->item_id, $ppmp->fund_code_id, $ppmp->department_id, request()->get('division'), $ppmp->budget_year) }}</td>
                                                                <td rowspan="1" colspan="1" class="text-center">{{ $controller->validate_item_request('03', $line->item_id, $ppmp->fund_code_id, $ppmp->department_id, request()->get('division'), $ppmp->budget_year) }}</td>
                                                                <td rowspan="1" colspan="1" class="text-center">{{ $controller->validate_item_request('04', $line->item_id, $ppmp->fund_code_id, $ppmp->department_id, request()->get('division'), $ppmp->budget_year) }}</td>
                                                                <td rowspan="1" colspan="1" class="text-center">{{ $controller->validate_item_request('05', $line->item_id, $ppmp->fund_code_id, $ppmp->department_id, request()->get('division'), $ppmp->budget_year) }}</td>
                                                                <td rowspan="1" colspan="1" class="text-center">{{ $controller->validate_item_request('06', $line->item_id, $ppmp->fund_code_id, $ppmp->department_id, request()->get('division'), $ppmp->budget_year) }}</td>
                                                                <td rowspan="1" colspan="1" class="text-center">{{ $controller->validate_item_request('07', $line->item_id, $ppmp->fund_code_id, $ppmp->department_id, request()->get('division'), $ppmp->budget_year) }}</td>
                                                                <td rowspan="1" colspan="1" class="text-center">{{ $controller->validate_item_request('08', $line->item_id, $ppmp->fund_code_id, $ppmp->department_id, request()->get('division'), $ppmp->budget_year) }}</td>
                                                                <td rowspan="1" colspan="1" class="text-center">{{ $controller->validate_item_request('09', $line->item_id, $ppmp->fund_code_id, $ppmp->department_id, request()->get('division'), $ppmp->budget_year) }}</td>
                                                                <td rowspan="1" colspan="1" class="text-center">{{ $controller->validate_item_request('10', $line->item_id, $ppmp->fund_code_id, $ppmp->department_id, request()->get('division'), $ppmp->budget_year) }}</td>
                                                                <td rowspan="1" colspan="1" class="text-center">{{ $controller->validate_item_request('11', $line->item_id, $ppmp->fund_code_id, $ppmp->department_id, request()->get('division'), $ppmp->budget_year) }}</td>
                                                                <td rowspan="1" colspan="1" class="text-center">{{ $controller->validate_item_request('12', $line->item_id, $ppmp->fund_code_id, $ppmp->department_id, request()->get('division'), $ppmp->budget_year) }}</td>
                                                            </tr>
                                                            @php $rows--; $totalAmt += floatval($line->total_amount); $iteration++; @endphp
                                                        @endforeach
                                                    @endif                                        
                                                    @while($rows > 0)
                                                        <tr data-row-id="0" data-row-cost="0" data-row-total="0" data-row-item="0" data-row-uom="" data-row-quantity="0" data-row-gl="">
                                                            <td rowspan="1" colspan="1" class="text-center fw-bold"></td>
                                                            <td rowspan="1" colspan="1" class="text-left" width="30%">
                                                                <div class="form-group m-form__group required m-0">
                                                                    @if ($locked > 0)
                                                                    {{
                                                                        Form::select('item_id[]', $items[$budget->gl_account->id], $value = '', ['id' => 'item_'.$budget->gl_account->code.'_'.$iteration, 'class' => 'form-control select3', 'data-placeholder' => '', 'disabled' => 'disabled'])
                                                                    }}
                                                                    @else
                                                                    {{
                                                                        Form::select('item_id[]', $items[$budget->gl_account->id], $value = '', ['id' => 'item_'.$budget->gl_account->code.'_'.$iteration, 'class' => 'form-control select3', 'data-placeholder' => ''])
                                                                    }}
                                                                    @endif
                                                                    <span class="m-form__help text-danger"></span>
                                                                </div>
                                                            </td>
                                                            <td rowspan="1" colspan="1" class="text-center" width="10%">
                                                                @if ($locked > 0)
                                                                    <input type="text" class="text-center numeric-double" name="quantity[]" value="" disabled="disabled">
                                                                @else
                                                                    <input type="text" class="text-center numeric-double" name="quantity[]" value="">
                                                                @endif
                                                            </td>
                                                            <td rowspan="1" colspan="1" class="text-center fw-bold"></td>
                                                            <td rowspan="1" colspan="1" class="text-center fw-bold"></td>
                                                            <td rowspan="1" colspan="1" class="text-center"></td>
                                                            <td rowspan="1" colspan="1" class="text-center"></td>
                                                            <td rowspan="1" colspan="1" class="text-center"></td>
                                                            <td rowspan="1" colspan="1" class="text-center"></td>
                                                            <td rowspan="1" colspan="1" class="text-center"></td>
                                                            <td rowspan="1" colspan="1" class="text-center"></td>
                                                            <td rowspan="1" colspan="1" class="text-center"></td>
                                                            <td rowspan="1" colspan="1" class="text-center"></td>
                                                            <td rowspan="1" colspan="1" class="text-center"></td>
                                                            <td rowspan="1" colspan="1" class="text-center"></td>
                                                            <td rowspan="1" colspan="1" class="text-center"></td>
                                                            <td rowspan="1" colspan="1" class="text-center"></td>
                                                        </tr>
                                                        @php $rows--; $iteration++; @endphp
                                                    @endwhile
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4" class="text-end fw-bold fs-5">TOTAL BUDGET</td>
                                                        <td colspan="1" data-row-total-budget="{{ number_format(floor(($totalAmt*100))/100, 2, '.', '') }}" class="total-budget text-center fw-bold fs-5 text-danger">{{ number_format(floor(($totalAmt*100))/100, 2) }}</td>
                                                        <td rowspan="1" colspan="1" class="text-center fw-bold fs-5"></td>
                                                        <td rowspan="1" colspan="1" class="text-center fw-bold fs-5"></td>
                                                        <td rowspan="1" colspan="1" class="text-center fw-bold fs-5"></td>
                                                        <td rowspan="1" colspan="1" class="text-center fw-bold fs-5"></td>
                                                        <td rowspan="1" colspan="1" class="text-center fw-bold fs-5"></td>
                                                        <td rowspan="1" colspan="1" class="text-center fw-bold fs-5"></td>
                                                        <td rowspan="1" colspan="1" class="text-center fw-bold fs-5"></td>
                                                        <td rowspan="1" colspan="1" class="text-center fw-bold fs-5"></td>
                                                        <td rowspan="1" colspan="1" class="text-center fw-bold fs-5"></td>
                                                        <td rowspan="1" colspan="1" class="text-center fw-bold fs-5"></td>
                                                        <td rowspan="1" colspan="1" class="text-center fw-bold fs-5"></td>
                                                        <td rowspan="1" colspan="1" class="text-center fw-bold fs-5"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- END -->
                            </div>
                        @php $i++; @endphp
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if (!($locked > 0) && (count($budgets) > 0))
    <button id="ppmp-lock-btn" class="btn btn-lg bg-secondary align-items-center btn-circle me-2 active" data-bs-toggle="tooltip" data-bs-placement="top" title="lock this">
        <i class="ti-lock text-white"></i>
    </button>
    @endif
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datepicker/bootstrap-datepicker.css?v='.filemtime(getcwd().'/assets/vendors/datepicker/bootstrap-datepicker.css').'') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css?v='.filemtime(getcwd().'/assets/vendors/datatables/css/datatables.min.css').'') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('assets/vendors/datepicker/bootstrap-datepicker.js?v='.filemtime(getcwd().'/assets/vendors/datepicker/bootstrap-datepicker.js').'') }}"></script>
<script src="{{ asset('assets/vendors/datatables/js/datatables.min.js?v='.filemtime(getcwd().'/assets/vendors/datatables/js/datatables.min.js').'') }}"></script>
<script src="{{ asset('js/datatables/gso-ppmp.js?v='.filemtime(getcwd().'/js/datatables/gso-ppmp.js').'') }}"></script>
<script src="{{ asset('js/forms/gso-ppmp.js?v='.filemtime(getcwd().'/js/forms/gso-ppmp.js').'') }}"></script>
@endpush