@extends('layouts.admin')
@section('page-title')
    {{__('Online Payment Acceptance')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Online')}}</a></li>
    <li class="breadcrumb-item">{{__('Online Payment Acceptance')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
         <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
             
    </div>
@endsection
{{-- Form::hidden('remote_cashier_id_for_rpt',376, array('id' => 'remote_cashier_id_for_rpt')) --}}
@if(session()->has('remote_cashier_id'))
    {{ Form::hidden('remote_cashier_id',Session::get('remote_cashier_id'), array('id' => 'remote_cashier_id')) }}
    @php Session::forget('remote_cashier_id'); @endphp
@endif
@if(session()->has('remote_cashier_id_for_rpt'))
    {{ Form::hidden('remote_cashier_id_for_rpt',Session::get('remote_cashier_id_for_rpt'), array('id' => 'remote_cashier_id_for_rpt')) }}
    @php Session::forget('remote_cashier_id_for_rpt'); @endphp
@endif
 {{ Form::hidden('department','', array('id' => 'department')) }}
@section('content')
   <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="#" accept-charset="UTF-8" id="product_service">
							<div class="d-flex align-items-center justify-content-end">
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
									<div class="btn-box">
                                        {{ Form::label('department_flt', 'Department', ['class' => 'fs-6 fw-bold']) }}
                                        {{
                                            Form::select('department_flt', $department, $value = '', ['id' => 'department_flt', 'class' => 'form-control'])
                                        }}   
									</div>
								</div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 pdr-20">
									<div class="btn-box">
                                        {{ Form::label('status', 'Transaction Status', ['class' => 'fs-6 fw-bold']) }}
                                        {{
                                            Form::select('flt_Status', ['0' => 'Pending','1' => 'Success','2' => 'Cancelled','3' => 'Failed'], $value = '1', ['id' => 'flt_Status', 'class' => 'form-control'])
                                        }}   
									</div>
								</div>
								<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
									<div class="btn-box">
                                        {{ Form::label('Search', 'Search Here...', ['class' => 'fs-6 fw-bold']) }}
										{{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
									</div>
								</div>
								<div class="col-auto float-end ms-2" style="padding-top: 20px;">
                                    <a href="#" class="btn btn-sm btn-primary" id="btn_search" style="padding: 10px;">
                                        <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger" id="btn_clear" style="padding: 10px;">
                                        <span class="btn-inner--icon"><i class="ti-trash "></i></span>
                                    </a>
                                </div>
							</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="Jq_datatablelist">
                            <thead>
								<tr>
									<th>{{__('No.')}}</th>
									<th>{{__('Department')}}</th>
                                    <th>{{__('Taxpayer Name')}}</th>
                                    <th>{{__('Bill Year')}}</th>
                                    <th>{{__('Bill Month')}}</th>
                                    <th>{{__('Total Amount')}}</th>
                                    <th>{{__('Total Paid Amount')}}</th>
                                    <th>{{__('transaction no.')}}</th>
                                    <th>{{__('payment date')}}</th>
									<th>{{__('payment Status')}}</th>
									<th>{{__('Action')}}</th>
								</tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <div class="modal" id="showrequiremets" class="hide" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-lg modalDiv" >
            <div class="modal-content" id="serviceform">
                <div class="modal-header">
                <h4 class="modal-title">O.R Details</h4>
                <a class="close closeReqModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true">X</a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12"> 
                            <div class="d-flex radio-check">
                             <div class="form-check form-check-inline form-group">
                                {{ Form::checkbox('isuserrange', '1', '', array('id'=>'isuserrange','class'=>'form-check-input')) }}
                               {{ Form::label('isuserrange', __('Manual OR-Series'),['class'=>'form-label']) }}
                              </div>
                            </div>
                       </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                             <div class="form-group">
                                {{Form::label('or_no',__('O.R. No.'),['class'=>'form-label'])}}
                                <div class="form-icon-user">
                                    {{Form::text('or_no','',array('class'=>'form-control disabled-field','id'=>'or_no'))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                                    {{Form::label('or_no',__('O.R. Date'),['class'=>'form-label'])}}
                                    <div class="form-icon-user">
                                        {{Form::text('applicationdate',date('Y-m-d'),array('class'=>'form-control','id'=>'applicationdate'))}}
                                    </div>
                                </div>
                        </div>
                        <span class="validate-err" id="err_or_no" style="text-align: right;"></span>
                    </div>
               </div>
                <div class="modal-footer"> 
                <input type="button" value="{{__('Cancel')}}" class="btn  btn-light closeOrderModal" data-bs-dismiss="modal">
               <button class="btn btn-primary approved" name ="approve" id ="approve" value=""> <i class="la la-save"></i>Approve</button>
              </div>
            </div>
        </div>
    </div>
    @if(session()->has('PRINT_CASHIER_ID'))
         <iframe id="openPrintDialog" src="@php echo url('/cashier/cashier-business-permit/printReceipt?id='.Session::get('PRINT_CASHIER_ID')) @endphp" class="hide" width="100%" height="800px"></iframe>
        @php  Session::forget('PRINT_CASHIER_ID'); @endphp
    @endif
    @if(session()->has('PRINT_CASHIER_ID_FOR_RPT'))
         <iframe id="openPrintDialog" src="@php echo url('/cashier-real-property/printReceipt?id='.Session::get('PRINT_CASHIER_ID_FOR_RPT')) @endphp" class="hide" width="100%" height="800px"></iframe>
        @php  Session::forget('PRINT_CASHIER_ID_FOR_RPT'); @endphp
    @endif
    <script type="text/javascript">
        $(document).ready(function () {
            $("#department_flt").select3({dropdownAutoWidth : false,dropdownParent: $("#multiCollapseExample1")});

        $("#flt_Status").select3({dropdownAutoWidth : false,dropdownParent: $("#this_is_filter")});
        });
    </script>
    <script src="{{ asset('js/OnlinePaymentHistory.js') }}?rand={{ rand(0000,9999) }}"></script>
@endsection


