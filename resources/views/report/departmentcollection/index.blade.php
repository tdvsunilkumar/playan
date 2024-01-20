@extends('layouts.admin')
@section('page-title')
    {{__('Departmental Collection')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Departmental Collection')}}</li>
    
    
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
         <a class="btn btn-sm btn-primary" id="btn_download_spreadsheet">
                                     <i class="ti-file"></i>
                             </a>
    </div>
@endsection
@section('content')
<style type="text/css">
    .modal.show .modal-dialog {
        transform: none;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 1650px;
        pointer-events: auto;
        background-color: #ffffff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 15px;
        outline: 0;
    }
</style>
    <div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                              <div class="form-group">
                                {{ Form::label('department', __('Department'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <span class="validate-err">{{ $errors->first('department') }}</span>
                                <div class="form-icon-user">
                                    {{ Form::select('department',$arrDepaertments,'', array('class' => 'form-control select3 ','id'=>'department','required'=>'required')) }}
                                </div>
                                <span class="validate-err" id="err_agl_code"></span>
                              </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                              <div class="form-group">
                                {{Form::label('fromdate',__('From date'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::date('fromdate', $startdate, array('class' => 'form-control','placeholder'=>'From','id'=>'fromdate')) }}
                                </div>
                              </div>
                           </div>
                           <div class="col-lg-2 col-md-2 col-sm-2">
                              <div class="form-group">
                                {{Form::label('todate',__('To date'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::date('todate', $enddate, array('class' => 'form-control','placeholder'=>'To date','id'=>'todate')) }}
                                </div>
                              </div>
                           </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                              <div class="form-group">
                                {{Form::label('search',__('Search'),array('class'=>'form-label')) }}
                                <div class="form-icon-user">
                                      {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
                                </div>
                              </div>
                           </div>
                           <div class="col-lg-1 col-md-1 col-sm-1">
                                <div class="col-auto float-end ms-2" style="padding-top: 32px;">
                                    <a href="#" class="btn btn-sm btn-primary" id="btn_search">
                                        <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
                                        <span class="btn-inner--icon"><i class="ti-trash "></i></span>
                                    </a>
                                </div>
                           </div>
                          <!--  <div class="d-flex align-items-center justify-content-end mt-3">
                            <a class="btn btn-sm btn-primary" id="btn_download_spreadsheet">
                                    <span class="btn-inner--icon">Download Spreadsheet</span>
                             </a>
                        </div>
                        </div> -->
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
                                    <th>{{__('Taxpayer')}}</th>
                                    <th >{{__('Business Name')}}</th>
                                    <th >{{__('Tax Declaration No.')}}</th>
                                    <th>{{__('Particulars')}}</th>
                                    <th>{{__('O.R. Type')}}</th>
                                    <th>{{__('Top No.')}}</th>
                                    <th>{{__('O.R. Number')}}</th>
                                    <th>{{__('Date')}}</th>
                                    <th>{{__('Amount')}}</th>
                                    <th>{{__('Details')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Cashier')}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
      <div class="modal" id="viewdetails" class="hide" data-backdrop="static" style="z-index:9999999;">
        <div class="modal-dialog modal-lg modalDiv" style="max-width: 1200px;">
            <div class="modal-content" id="serviceform">
                <div class="modal-header">
					<h4 class="modal-title">Reference O.R. No.:&nbsp;&nbsp;<span id="orno"></span> [Item No.: <span id="itemNo"></span>]</h4>
					<a class="close closeReqModal" mid="" type="edit" data-dismiss="modal" aria-hidden="true" style="cursor:pointer;">X</a>
				</div>
				<div class="modal-body">
					<p>Taxpayer Name:&nbsp;&nbsp;<span id="taxpayername"></span> </p>
					<div class="card">
						<div class="card-body table-border-style">
							<div class="table-responsive">
								<table class="table table-responsive" id="dynamicdetails" style="margin:0px">
								
								</table>
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
	</div>
<script src="{{ asset('js/report/departmentalcollection.js') }}?rand={{ rand(000,999) }}"></script>
@endsection

  

