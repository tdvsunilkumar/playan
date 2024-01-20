@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar/'));
@endphp
@push('script-page')
    <script>
        $(document).on('click', '#billing_data', function () {
            $("[name='shipping_name']").val($("[name='billing_name']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_phone']").val($("[name='billing_phone']").val());
            $("[name='shipping_zip']").val($("[name='billing_zip']").val());
            $("[name='shipping_address']").val($("[name='billing_address']").val());
        })

    </script>
    <script>

    //Branch Wise Deapartment Get
    $(document).ready(function (){
         
        
    });


   
</script>
@endpush
@section('page-title')
    {{__('Manage Applicants')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Applicants')}}</li>
@endsection

@section('action-btn')
    <div class="float-end">
         <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
        <a href="#" data-size="xll" data-url="{{ url('/business-permit/application/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Manage Applicant')}}" class="btn btn-sm btn-primary">
            <i class="ti-plus"></i>
        </a>
    </div>
@endsection

@section('content')
 <div class="row hide" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="#" accept-charset="UTF-8" id="product_service">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::text('q', '', array('class' => 'form-control','placeholder'=>'Search...','id'=>'q')) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2">
                                <a href="#" class="btn btn-sm btn-primary" id="btn_search">
                                    <span class="btn-inner--icon"><i class="ti-search"></i></span>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger" id="btn_clear">
                                    <span class="btn-inner--icon"><i class="ti-trash"></i></span>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="Jq_datatablelist">
                            <thead>
                            <tr>
                                <th> {{__('ID No.')}}</th>
                                <th> {{__('Applicant Name')}}</th>
                                <th> {{__('Business Name')}}</th>
                                <th> {{__('Trade Name')}}</th>
                                <th> {{__('Status')}}</th>
                                <th> {{__('Mode of Payment')}}</th>
                                <th> {{__('Monthly rent')}}</th>
                                <th> {{__('Business Address')}}</th>
                                <th> {{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody> 
                           <?php /* @foreach ($allaplicants as $k=>$allaplicant) 
                                <tr class="cust_tr" id="cust_detail">
                                    <td class="Id">{{$allaplicant->id}}
                                    </td>
                                    <td class="font-style">{{$allaplicant->fname}} {{$allaplicant->mname}} {{$allaplicant->lname}}</td>
                                    <td>{{$allaplicant->bussinessname}}</td>
                                    <td>{{$allaplicant->tradename}}</td>
                                    <td>@if($allaplicant->isnew==1)
                                                <span class="btn btn-success">yes</span>
                                            @else
                                                 <span class="btn btn-warning">no</span>
                                            @endif</td>
                                    <td>{{$allaplicant->modeofpayment}}</td>
                                    <td>{{$allaplicant->monthlyrental}}</td>
                                    <td>{{$allaplicant->bussinessaddress}}</td>
                                    <td class="Action">
                                        <span>
                                            <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ url('/allaplicant/store?id='.$allaplicant->id) }}" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="{{__('Edit')}}"  data-title="{{__('Class Appliaction')}}">
                                                <i class="ti-pencil text-white"></i>
                                            </a>
                                        </div>
                                        @if($allaplicant->is_approve==0)
                                                <span class="btn btn-success approve" id="{{$allaplicant->id}}">Approve</span>
                                            @else
                                                 <span class="btn btn-warning">Pay</span>
                                            @endif
                                            <span class="btn btn-success print" id="{{$allaplicant->id}}">print</span>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach */?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
  </div>
<script src="{{ asset('js/allapplicant.js') }}"></script>
@endsection


