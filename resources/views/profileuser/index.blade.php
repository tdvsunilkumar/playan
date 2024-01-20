@extends('layouts.admin')
@section('page-title')
    {{__('Business Owners')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Business Owners')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
             <a href="#" data-size="lg" data-url="{{ url('/business-permit/business-owners/store') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create Business Owner')}}" class="btn btn-sm btn-primary">
            <i class="ti-plus"></i>
        </a>
    </div>
@endsection


@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                 <th>{{__('No.')}}</th>
                                <th>{{__('Owners Name')}}</th>
                                <th>{{__('House|Lot No., Street, Subdivision')}}</th>
                                <th>{{__('Brgy No')}}</th>
                                <th>{{__('Address')}}</th>
                                <th>{{__('Mobile No.')}}</th>
                                <th>{{__('Email')}}</th>
                                <!-- <th>{{__('Position')}}</th> -->
                                <!-- <th>{{__('Gender')}}</th> -->
                                <th>{{__('DOB')}}</th>
                                <th>{{__('Business')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            

                            
                            
                            </thead>
                            <tbody>
                            @php
                            $i=0;
                            
                            @endphp
                            @foreach ($Profileusers as $val)
                            @php
                           
                            $i=$i+1;
                            @endphp
                                <tr class="font-style"> 
                                    <td>{{ $i }}</td>
                                    <td>{{ $val->p_first_name}} {{$val->p_middle_name}} {{$val->p_family_name}}</td>
                                    <td>{{ $val->p_address_house_lot_no }},{{ $val->p_address_street_name }},{{ $val->p_address_subdivision }}</td>
                                    <td>{{ $val->brgy_code }}</td>
                                    <td>{{ $val->brgy_name }}, {{ $val->mun_desc }}, {{ $val->prov_desc }}, {{ $val->reg_region }} 
                                        <!-- [{{ $val->reg_no }}{{ $val->prov_no }}{{ $val->mun_no }}{{ $val->brgy_code }}] -->
                                    </td>
                                    <td>{{ $val->p_mobile_no}}</td>
                                    <td>{{ $val->p_email_address }}</td>
                                    <!-- <td>{{ $val->p_job_position }}</td> -->
                                    <!-- <td>{{ $val->p_gender }}</td> -->
                                    @if($val->p_date_of_birth!='0000-00-00')
                                       <td>{{$val->p_date_of_birth}}</td>
                                    @else
                                         <td></td>



                                    @endif
                                    <td>{{ $val->ba_business_name}}</td>
                                   <td class="Action">
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ url('/business-permit/business-owners/store?id='.$val->id) }}" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="{{__('Edit')}}"  data-title="{{__('Edit Business Owner')}}">  
                                                <i class="ti-pencil text-white"></i>
                                            </a>
                                        </div>
                                       <!--  <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['psicsubclass.destroy', $val->id],'id'=>'delete-form-'.$val->id]) !!}
                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" ><i class="ti-trash text-white"></i></a>
                                            {!! Form::close() !!}
                                        </div> -->
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
