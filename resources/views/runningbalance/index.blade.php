@extends('layouts.admin')
<style type="text/css">
    .datefield{padding-top: 26px;}
    .accordion-button::after{background-image: url() !important;}
   
</style>
@section('page-title')
    {{__('Running Balance')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Running Balance')}}</li>
@endsection

@section('content')
<style type="text/css">
.form-control, .custom-select {
    color: #fff;
    text-align: center;
    width: 147px;
    background: #584ed2;
    font-weight: bold;
    padding-left: 5px;
    font-size: 12px !important;
}
</style>
  <div class="row">
        <div class="col-xl-12">
             {{ Form::hidden('id',$data->id, array('id' => 'id')) }}
            <div class="card">

                 <div class="modal-body">
                        <div class="row pt10" >
                            <!--------------- Account No. Date & Period Start Here---------------->
                            <div class="col-lg-12 col-md-12 col-sm-12">  
                                <div  class="accordion accordion-flush">
                                    <div class="accordion-item">
                                        <h6 class="accordion-header" id="flush-headingone">
                                            <button class="accordion-button collapsed btn-primary" type="button" style="">
                                                <h6 class="sub-title accordiantitle" style="text-align:center;"><center>{{__("Running Balance")}}</center></h6>
                                            </button>
                                        </h6>
                                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
                                            <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>Collection Type</h4>  
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>Payment Count</h4>    
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                     <h4>Total Collection</h4>     
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                     <h4>O.R. Balance</h4>     
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::label('license', __('Business Permit & License'),['class'=>'form-label']) }}</h4>  
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>    
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                     <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>     
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                     <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>     
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::label('property', __('Real Property'),['class'=>'form-label']) }}</h4>  
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>    
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                     <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>     
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                     <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>     
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::label('corporate', __('Community Tax (Corporate)'),['class'=>'form-label']) }}</h4>  
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>    
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                     <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>     
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                     <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>     
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::label('invidual', __('Community Tax (Invidual)'),['class'=>'form-label']) }}</h4>  
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>    
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                     <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>     
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                     <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>     
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::label('miscellaneous', __('Miscellaneous'),['class'=>'form-label']) }}</h4>  
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>    
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                     <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>     
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                     <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>     
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                    </div>
                                </div>
                            </div>


                                <div class="col-lg-12 col-md-12 col-sm-12">  
                                <div  class="accordion accordion-flush">
                                    <div class="accordion-item">
                                        
                                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
                                           
                                            <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>Total OR Amount:</h4>  
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>    
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>Active Receipt:</h4>  
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>Total Checks:</h4>  
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>    
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>Void Receipt:</h4>  
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>Total Cash:</h4>  
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>    
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>Total No. of Used Receipts:</h4>  
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>Total Applied Tax Credit:</h4>  
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>    
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>Excess of Checks(Tax Credit):</h4>  
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <div class="form-group">
                                                      <h4>{{ Form::text('prate_annual_interest_percentage', number_format($data->prate_annual_interest_percentage,2), array('class' => 'form-control','required'=>'required','id'=>'prate_annual_interest_percentage')) }}</h4>    
                                                    </div>
                                                </div>
                                            </div>
                                             </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-12 col-md-12 col-sm-12">  
                                <div  class="accordion accordion-flush">
                                    <div class="accordion-item">
                                        <h6 class="accordion-header" id="flush-headingone">
                                            <button class="accordion-button collapsed btn-primary" type="button" style="">
                                                <h6 class="sub-title accordiantitle" style="text-align:center;"><center>{{__("Check Listing")}}</center></h6>
                                            </button>
                                        </h6>
                                        <div id="flush-collapseone" class="accordion-collapse collapse show" aria-labelledby="flush-headingone1">
                                            <div class="row">
                                                
                                                <table style="text-align: center;">
                                                    <tr>
                                                        <th>
                                                           Check No.
                                                        </th>
                                                        <th>
                                                           Drawee Name
                                                        </th>
                                                        <th>
                                                            Drawee Bank
                                                        </th>
                                                        <th>
                                                            Date
                                                        </th>
                                                        <th>
                                                            Check Type
                                                        </th>
                                                        <th>
                                                            Amount
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #ccc;border: 1px solid #fff;color:#000;font-weight: bold;">0</td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #ccc;border: 1px solid #fff;color:#000;font-weight: bold;">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #ccc;border: 1px solid #fff;color:#000;font-weight: bold;">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #ccc;border: 1px solid #fff;color:#000;font-weight: bold;">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #30addd;border: 1px solid #fff;color:#fff;font-weight: bold;">0</td>
                                                        <td style="background: #ccc;border: 1px solid #fff;color:#000;font-weight: bold;">0</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            </div>
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



