@extends('layouts.admin')
<style type="text/css">
    .datefield{padding-top: 26px;}
    .accordion-button::after{background-image: url() !important;}
   

</style>
@section('page-title')
    {{__('System Setup')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('System Setup')}}</li>
@endsection

@section('content')
  <div class="row">
        <div class="col-xl-12">
             {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
            <div class="card">
                 <div class="modal-body">
                        <div class="row pt8" >
                            <!--------------- Account No. Date & Period Start Here---------------->
                            <div class="col-lg-3 col-md-3 col-sm-3">  
                                <div  class="accordion accordion-flush">
                                    <div class="accordion-item">
                                        <h6 class="accordion-header" id="flush-headingone">
                                            <button class="accordion-button collapsed btn-primary" type="button" style="">
                                                <h6 class="sub-title accordiantitle">{{__("System Setup")}}</h6>
                                            </button>
                                        </h6>
                                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
                                            <div class="row">
                                                {{ Form::hidden('oldconfiguration_value',number_format($data->configuration_value), array('id' => 'oldconfiguration_value')) }}
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('Window No', __('Window No:'),['class'=>'form-label']) }}
                                                    </div>
                                                </div>
                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::text('configuration_value', number_format($data->configuration_value), array('class' => 'form-control','required'=>'required','id'=>'configuration_value')) }}
                                                    </div>
                                                </div>
                                            </div>
                                           
                                             <div class="modal-footer">
                                                <!-- <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal"> -->
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
    <script src="{{ asset('js/configuration.js') }}"></script>
@endsection



