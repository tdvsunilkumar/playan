@extends('layouts.admin')
@section('page-title')
    {{__('No. of Business')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('No. of Business  (Permits Issued)') }}</li>
@endsection
@section('action-btn')
    <!--<div class="float-end">
        <a href="#" id="filter_box" class="btn btn-sm btn-primary action-item" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti-filter"></i>
        </a>
    </div> -->
@endsection
<style>
h6.headersetting {
    background: #20B7CC;
    height: 34px;
    color: white;
    padding: 6px;
}
</style>
@section('content')
    <!--<div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
						<div class="d-flex align-items-center justify-content-end" id="nearsearch">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                {{ Form::label('Search', 'Business Year', ['class' => 'fs-6 fw-bold']) }}
								@if(!empty($_GET['year']))
                                {{ Form::select('year',$arrYears,$_GET['year'], array('class' => 'form-control','id'=>'btn_search')) }}
								@else
								{{ Form::select('year',$arrYears,date('Y'), array('class' => 'form-control','id'=>'btn_search')) }}	
								@endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6" id="accordionFlushExample2">
								<div class="accordion accordion-flush">
									<div class="accordion-item">
										<h6 class="headersetting"; style="padding-left:15px;">
											{{__('1st Quarter')}}
										</h6>
										<div id="flush-collapsetwo" class="accordion-collapse collapse show" >
											<div class="row">
												<div class="col-lg-12">
													<div class="first_1">
														<span style="float:left;">New Business Registration</span>
														<span style="float:center; margin-left:100px;">{{$NewFirstQuarter}}</span>
													</div>
												</div>
												<div class="col-lg-12">
													<div class="second_2">
														<span style="float:left;">Renewal of Business</span>
														<span style="float:center; margin-left:140px;">{{$RenewalFirstquarter}}</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							 <div class="col-lg-6 col-md-6 col-sm-6" id="accordionFlushExample2">
								<div class="accordion accordion-flush">
									<div class="accordion-item">
										<h6 class="headersetting"; style="padding-left:15px;">
											{{__('2nd Quarter')}}
										</h6>
										<div id="flush-collapsetwo" class="accordion-collapse collapse show" >
											<div class="row">
												<div class="col-lg-12">
													<div class="first_1">
														<span style="float:left;">New Business Registration</span>
														<span style="float:center; margin-left:100px;">{{$SecondNewQuarter}}</span>
													</div>
												</div>
												<div class="col-lg-12">
													<div class="second_2">
														<span style="float:left;">Renewal of Business</span>
														<span style="float:center; margin-left:140px;">{{$SecondRenewalQuarter}}</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6" id="accordionFlushExample2">
								<div class="accordion accordion-flush">
									<div class="accordion-item">
										<h6 class="headersetting"; style="padding-left:15px;">
											{{__('3rd Quarter')}}
										</h6>
										<div id="flush-collapsetwo" class="accordion-collapse collapse show" >
											<div class="row">
												<div class="col-lg-12">
													<div class="first_1">
														<span style="float:left;">New Business Registration</span>
														<span style="float:center; margin-left:100px;">{{$New3thQuarter}}</span>
													</div>
												</div>
												<div class="col-lg-12">
													<div class="second_2">
														<span style="float:left;">Renewal of Business</span>
														<span style="float:center; margin-left:140px;">{{$Renewal3thQuarter}}</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							 <div class="col-lg-6 col-md-6 col-sm-6" id="accordionFlushExample2">
								<div class="accordion accordion-flush">
									<div class="accordion-item">
										<h6 class="headersetting"; style="padding-left:15px;">
											{{__('4th Quarter')}}
										</h6>
										<div id="flush-collapsetwo" class="accordion-collapse collapse show" >
											<div class="row">
												<div class="col-lg-12">
													<div class="first_1">
														<span style="float:left;">New Business Registration</span>
														<span style="float:center; margin-left:100px;">{{$New4thQuarter}}</span>
													</div>
												</div>
												<div class="col-lg-12">
													<div class="second_2">
														<span style="float:left;">Renewal of Business</span>
														<span style="float:center; margin-left:140px;">{{$Renewal4thQuarter}}</span>
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
        </div>
    </div>
@endsection
@push('scripts')
<script src="{{ asset('js/businesspermitissued.js') }}"></script>
@endpush
