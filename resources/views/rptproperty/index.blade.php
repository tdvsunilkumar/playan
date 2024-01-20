@extends('layouts.admin')
<style type="text/css">
    .datefield{padding-top: 26px;}
    .swal2-container{
        z-index:9999999 !important;
    }
    /*.select3-container{
        //z-index: 9999999 !important;
    }*/

</style>

@section('page-title')
    {{__('Land')}}
@endsection
@push('script-page')
@endpush
<div>
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Land')}}</li>
@endsection
</div>
@section('action-btn')
    <div class="float-end">
        <a href="#" class="btn btn-sm btn-primary uploadNewProperties" data-size="lg" data-url="{{ url('/rptproperty/bulkUpload') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Bulk Upload')}}" {{ (session()->get('landSelectedBrgy') == '')?'hidden':''}}>
            <span class="btn-inner--icon"><i class="ti-import"></i>&nbsp;Bulk Upload</span>
        </a>

        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        
        <a href="#"  data-url="{{ url('/rptproperty/store') }}" title="{{__('New Property data')}}" data-title="Real Property - Land | Plant & Trees" class="btn btn-sm btn-primary addNewProperty" {{ (session()->get('landSelectedBrgy') == '')?'hidden':''}}>
            <i class="ti-plus"></i>
        </a>
        
    </div>
@endsection

@section('content')
<div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                         {{ Form::open(array('url' => '','id'=>"rptPropertySerachFilter")) }}
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" >
                                <div class="btn-box">
                                    {{ Form::label('Search', 'Revision Year', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::select('revision_year',$revisionYears,$activeRevisionYear, array('class' => 'form-control','id'=>'rptPropertySearchByRevisionYear')) }}
                                </div>
                            </div>
                            
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20" >
                                <div class="btn-box">
                                    {{ Form::label('Search', 'Barangay', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::select('barangy_filter',$arrBarangay,session()->get('landSelectedBrgy'), array('class' => 'form-control','id'=>'rptPropertySearchByBarangy')) }}
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 pdr-20">
                                    <div class="btn-box">
                                        {{ Form::label('Search', 'Status', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::select('active_status_filter',['1'=>'Active','0'=>'Cancelled'],'', array('class' => 'form-control','id'=>'rptPropertySearchByStatus')) }}
                                    </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('Search', 'Search Here...', ['class' => 'fs-6 fw-bold']) }}
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'rptPropertySearchByText')) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2"><br>
                                <a href="#" class="btn btn-sm btn-primary" id="btn_search">
                                    <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
                                    <span class="btn-inner--icon"><i class="ti-trash "></i></span>
                                </a>
                            </div>

                        </div>
                        {{Form::close()}}
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
                                    <th>{{__('TD. No.')}}</th>
                                    <th>{{__('Taxpayers Name')}}</th>
                                    <th>{{__('Barangay')}}</th>
                                    <th>{{__('PIN')}}</th>
                                    <th>{{__("Survey No.")}}</th>
                                    <th>{{__('Market Value')}}</th>
                                    <th>{{__("Assessed Value")}}</th>
                                    <th>{{__("Update Code")}}</th>
                                    <th>{{__("Effectivity")}}</th>
                                    <th>{{__("Updated By")}}</th>
                                    <th>{{__("Date")}}</th>
                                    <th>{{__('Status')}}</th>
                                    
                                    <th>{{__('Action')}}</th>
                                    <!-- <th>{{__('Other')}}</th> -->
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="selectUpdateCode" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="margin-top:45%;">
            <div class="modal-header">
                <h5 class="modal-title">Update Code Selection</h5>
                <button type="button" class="close closeUpdateCodeNodal" data-dismiss="modal" aria-label="Close" style="background: none;
    border: none;
    padding: 0px;
    margin: 0px;">
                <span aria-hidden="true" style="    padding: 0;
    margin: 0;
    font-size: 24px;">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8">
                            <div class="form-group">
                        {{ Form::label('Search', 'Tax Declaration No.', ['class' => 'fs-6 fw-bold']) }}
                        <div class="form-icon-user">
                            {{ Form::text('taxdeclaretion','', array('class' => 'form-control taxdeclaretion','id' => 'taxdeclaretion','readonly'=>'true')) }}
                           
                        </div>
                        <span class="validate-err" id="err_selected_update_code"></span>
                        
                    </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="form-group">
                        {{ Form::label('Search', 'Item No.', ['class' => 'fs-6 fw-bold']) }}
                        <div class="form-icon-user">
                            {{ Form::text('count','', array('class' => 'form-control count','id' => 'count','readonly'=>'true')) }}
                        </div>
                        <span class="validate-err" id="err_selected_update_code"></span>
                    </div>
                   </div>
					<div class="col-lg-12 col-md-12 col-sm-12"  style="margin-bottom:150px;">
						 <div class="form-group">
						  {{ Form::label('Search', 'Update Code', ['class' => 'fs-6 fw-bold']) }}
							<div class="form-icon-user">
								{!! $updateCodes !!}
								{{ Form::hidden('selected_property_id','', array('id' => 'pk_id','class'=>'selected_property_id')) }}
							</div>
							<span class="validate-err" id="err_selected_update_code"></span>
						</div>
					</div>
				</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="updateCodeSekected">Next</button>
                <button type="button" class="btn btn-secondary closeUpdateCodeNodal" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal" id="selectBillingType" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">Select Billing Type</h5>
                <button type="button" class="close closeUpdateCodeNodal" data-dismiss="modal" aria-label="Close" >
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                        <div class="form-icon-user">
                            {{ Form::select('billing_type',['Billing for Single Property','Billing for Multiple Properties'],'',['class'=>'form-control selected_property_id_for_billing','placeholder' => 'Select Billing Type']) }}
                            {{ Form::hidden('selected_property_id_for_billing','', array('id' => 'pk_id','class'=>'selected_property_id_for_billing')) }}
                        </div>
                        <span class="validate-err" id="err_selected_property_id_for_billing"></span>
                        
                    </div>
                </div>
            
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="billingTypeSekected">Next</button>
                <button type="button" class="btn btn-secondary closeUpdateCodeNodal" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal" id="commonUpDateCodeIntermediateModal1" data-backdrop="static" >
        <div class="modal-dialog " >
            <div class="modal-content" >
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
            </div>
        </div>
    </div> 
    <div class="modal" id="commonUpDateCodeIntermediateModal2" data-backdrop="static" style="z-index:9999999 !important;">
        <div class="modal-dialog " >
            <div class="modal-content" >
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
            </div>
        </div>
    </div>
    <div class="modal" id="addPreviousOwnerForLandModal"  style="z-index:99999999 !important;">
   <div class="modal-dialog " >
      <div class="modal-content" >
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <button type="button" class="btn-close eventOnCloseModal" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="body">
         </div>
      </div>
   </div>
</div>


    <script src="{{ asset('js/rptPropertyNew.js') }}?rand={{rand(0,99)}}"></script>
@endsection

