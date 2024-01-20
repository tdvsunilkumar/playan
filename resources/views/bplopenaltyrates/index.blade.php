@extends('layouts.admin')
<style type="text/css">
    .datefield{padding-top: 26px;}
    .accordion-button::after{background-image: url() !important;}
   
</style>
@section('page-title')
    {{__('BPLO Penalty Rates')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('BPLO Penalty Rates')}}</li>
@endsection

@section('content')
  <div class="row">
        <div class="col-xl-12">
             {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
            <div class="card">
                 <div class="modal-body">
                        <div class="row pt10" >
                            <!--------------- Account No. Date & Period Start Here---------------->
                            <div class="col-lg-5 col-md-5 col-sm-5">  
                                <div  class="accordion accordion-flush">
                                    <div class="accordion-item">
                                        <h6 class="accordion-header" id="flush-headingone">
                                            <button class="accordion-button collapsed btn-primary" type="button" style="">
                                                <h6 class="sub-title accordiantitle">{{__("Penalty Rates")}}</h6>
                                            </button>
                                        </h6>
                                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
                                            <div class="row">
                                                {{ Form::hidden('oldsurcahrge',number_format($data->prate_surcharge_percent,2), array('id' => 'oldsurcahrge')) }}
                                                {{ Form::hidden('oldannualinterest',number_format($data->prate_annual_interest_percentage,2), array('id' => 'oldannualinterest')) }}
                                                {{ Form::hidden('oldmaxpenalty',number_format($data->prate_max_penalty_years,2), array('id' => 'oldmaxpenalty')) }}
                                                {{ Form::hidden('olddiscountrate',number_format($data->prate_discount_rate,2), array('id' => 'olddiscountrate')) }}
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('surchargepercent', __('Surcharge Percentage:'),['class'=>'form-label']) }}
                                                    </div>
                                                </div>
                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::text('prate_surcharge_percent', number_format($data->prate_surcharge_percent,2), array('class' => 'form-control','required'=>'required','id'=>'prate_surcharge_percent')) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('prate_annual_interest_percentage', __('Annual Interest Percentage:'),['class'=>'form-label']) }}
                                                    </div>
                                                </div>
                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('prate_max_penalty_years', __('Maximum Penalty Rate:'),['class'=>'form-label']) }}
                                                    </div>
                                                </div>
                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::text('prate_max_penalty_years',number_format($data->prate_max_penalty_years,2) , array('class' => 'form-control','required'=>'required','id'=>'prate_max_penalty_years')) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('prate_discount_rate', __('Discount Rate:'),['class'=>'form-label']) }}
                                                    </div>
                                                </div>
                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::text('prate_discount_rate', number_format($data->prate_discount_rate,2), array('class' => 'form-control','required'=>'required','id'=>'prate_discount_rate')) }}
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



