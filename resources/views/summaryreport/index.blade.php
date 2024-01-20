@extends('layouts.admin')
<style type="text/css">
    .datefield{padding-top: 26px;}
    .accordion-button::after{background-image: url() !important;}
   
</style>
@section('page-title')
    {{__('Summary Report')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Summary Report')}}</li>
@endsection

@section('content')
  <div class="row">
        <div class="col-xl-12">
             {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
            <div class="card">
                 <div class="modal-body">
                        <div class="row pt10" >
                            <!--------------- Account No. Date & Period Start Here---------------->
                            <div class="col-lg-6 col-md-6 col-sm-6">  
                                <div  class="accordion accordion-flush">
                                    <div class="accordion-item">
                                        <h6 class="accordion-header" id="flush-headingone">
                                            <button class="accordion-button collapsed btn-primary" type="button" style="">
                                                <h6 class="sub-title accordiantitle">{{__("Summary Report")}}</h6>
                                            </button>
                                        </h6>
                                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
                                            <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        {{ Form::label('surchargepercent', __('Period Covered:'),['class'=>'form-label']) }}
                                                    </div>
                                                </div>
                                                 <div class="col-md-4">
                                                    <div class="form-group">
                                                        {{ Form::date('prate_surcharge_percent', number_format($data->prate_surcharge_percent,2), array('class' => 'form-control','required'=>'required','id'=>'prate_surcharge_percent')) }}
                                                    </div>
                                                </div>
                                            
                                               <div class="col-md-1">
                                                        <div class="form-group">
                                                            {{ Form::label('prate_annual_interest_percentage', __('To'),['class'=>'form-label']) }}
                                                        </div>
                                                    </div>
                                                     <div class="col-md-4">
                                                        <div class="form-group">
                                                            {{ Form::date('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}
                                                        </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                       
                                                    </div>
                                                </div>
                                                 <div class="col-md-9">
                                                    <div class="form-group">
                                                         {{ Form::radio('prate_max_penalty_years', '1', ($data->prate_max_penalty_years)?true:false, array('id'=>'active','class'=>'form-check-input code')) }}
                                                        {{ Form::label('active', __('Barangay Share'),['class'=>'form-label']) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                       
                                                    </div>
                                                </div>
                                                 <div class="col-md-9">
                                                    <div class="form-group">
                                                         {{ Form::radio('prate_max_penalty_years', '1', ($data->prate_max_penalty_years)?true:false, array('id'=>'active','class'=>'form-check-input code')) }}
                                                        {{ Form::label('active', __('Summary by Account'),['class'=>'form-label']) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                       
                                                    </div>
                                                </div>
                                                 <div class="col-md-9">
                                                    <div class="form-group">
                                                         {{ Form::radio('prate_max_penalty_years', '1', ($data->prate_max_penalty_years)?true:false, array('id'=>'active','class'=>'form-check-input code')) }}
                                                        {{ Form::label('active', __('Daily Collection Report by OR'),['class'=>'form-label']) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                       
                                                    </div>
                                                </div>
                                                 <div class="col-md-9">
                                                    <div class="form-group">
                                                         {{ Form::radio('prate_max_penalty_years', '1', ($data->prate_max_penalty_years)?true:false, array('id'=>'active','class'=>'form-check-input code')) }}
                                                        {{ Form::label('active', __('Monthly Summary by Account'),['class'=>'form-label']) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                       
                                                    </div>
                                                </div>
                                                 <div class="col-md-9">
                                                    <div class="form-group">
                                                         {{ Form::radio('prate_max_penalty_years', '1', ($data->prate_max_penalty_years)?true:false, array('id'=>'active','class'=>'form-check-input code')) }}
                                                        {{ Form::label('active', __('Statement of Checks'),['class'=>'form-label']) }}
                                                    </div>
                                                </div>
                                                
                                                
                                            </div>
                                            
                                             <div class="modal-footer">
                                                <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                                               <input type="submit" name="submit" value="{{('Update')}}" class="btn submit btn-primary">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       </div>        
                    </div>          
            </div>
        </div>
    </div>
    <script src="{{ asset('js/PenaltyRates.js') }}"></script>
@endsection



