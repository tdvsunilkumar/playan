@extends('layouts.admin')
@section('page-title')
    {{__('Interest/Surcharges')}}
@endsection
@push('script-page')
@endpush

<style type="text/css">
    table{
        width: 100px;
    }
    #otheinfodiv{
        margin-left: 0px;
        margin-right: 0px;
    }
    tr{
        padding-top: 10px;
    }
    td{
        padding-bottom: 10px;
        border-right: 5px solid red;
    }
    .first{
        width: 30%;
        border-right: 1px solid #808080f0 !important;
        text-align:right;
        padding-right: 20px;
        font-weight: bold;
        font-size: 14px;
    }
    .second{
        padding-left: 20px;
    }
    accordion-button:not(.collapsed)::after, .accordion-button::after {
         background-image: unset !important;
    }
</style>
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Interest/Surcharges')}}</li>
@endsection

@section('content')
    <div class="row">
        
       
        <div class="col-lg-5 col-md-5 col-sm-5" id="accordionFlushExample2">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <button class="accordion-button collapsed btn-primary" type="button">
                        <h6 class="sub-title accordiantitle">{{__('Surcharge')}}</h6>
                        </button>
                    </h6>
                    
                    <div id="flush-collapsetwo" class="accordion-collapse collapse show" >
                        <div class="row"  id="otheinfodiv">
                            <table>
                                <tr>
                                    <td class="first">Surcharge</td>
                                    <td class="second"><?=($data->tis_surcharge_rate_type==2)?'₱':''?><?=$data->tis_surcharge_amount?><?=($data->tis_surcharge_rate_type==1)?'%':''?></td>
                                </tr>
                                <tr>
                                    <td class="first">Schedule</td>
                                    <td class="second"><?=$data->tis_surcharge_schedule?></td>
                                </tr>
                                <tr>
                                    <td class="first">No. of Months</td>
                                    <td class="second">Not Set</td>
                                </tr>
                                <tr>
                                    <td class="first">Formula</td>
                                    <td class="second"><?=$data->tis_surcharge_formula?></td>
                                </tr>
                              
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="col-lg-5 col-md-5 col-sm-5" id="accordionFlushExample2">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h6 class="accordion-header" id="flush-headingtwo">
                        <button class="accordion-button collapsed btn-primary" type="button" >
                        <h6 class="sub-title accordiantitle">{{__('Interest')}}</h6>
                        </button>
                    </h6>
                    
                    <div id="" class="accordion-collapse" >
                        <div class="row"  id="otheinfodiv">
                            <table>
                                <tr>
                                    <td class="first">Interest</td>
                                    <td class="second"><?=($data->tis_interest_rate_type==2)?'₱':''?><?=$data->tis_interest_amount?><?=($data->tis_interest_rate_type==1)?'%':''?></td>
                                </tr>
                                <tr>
                                    <td class="first">Schedule</td>
                                    <td class="second"><?=$data->tis_interest_schedule?></td>
                                </tr>
                                <tr>
                                    <td class="first">No. of Months</td>
                                    <td class="second"><?=$data->tis_interest_max_month?></td>
                                </tr>
                                <tr>
                                    <td class="first">Formula</td>
                                    <td class="second"><?=$data->tis_interest_formula?></td>
                                </tr>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="float-end">
            <a href="#" data-size="lg" data-url="{{ url('/CtoTaxInterestSurcharge/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Manage Interest/Surcharges')}}" class="btn btn-sm btn-primary">
            Update
        </a>
    </div>

    </div>
@endsection
