@extends('layouts.admin')
@section('page-title')
    {{__('Percentage of Business')}}
@endsection
@push('script-page')
@endpush
<div>
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Percentage of Business (Owned By Sex)')}}</li>
@endsection
</div>
@section('action-btn')
<link href="{{ asset('assets/js/yearpicker.css')}}" rel="stylesheet">
<style type="text/css">
    .yearpicker-container {
    position: fixed;
    color: var(--text-color);
    width: 280px;
    border: 1px solid var(--border-color);
    border-radius: 3px;
    font-size: 1rem;
    box-shadow: 1px 1px 8px 0px rgba(0, 0, 0, 0.2);
    background-color: var(--background-color);
    z-index: 10;
    margin-top: 0.2rem;
}

</style>
<style>
	 table, th, td {
		border: 0.5px solid gray;
		border-collapse: collapse;
        padding: 6px;
	 }
	 table {
     margin: 50px;
}
</style>
@endsection
@section('content')
	<div class="row" id="this_is_filter">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-end" style="float:right;padding-right: 32px;">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 pdr-20" style="float:right;margin-right:-73px;">
                                <div class="btn-box">
                                	 <?php
                                        $currentYear = date('Y');
                                    ?>
                                {{ Form::label('Search', 'Select Year', ['class' => 'fs-6 fw-bold']) }}
								@if(!empty($_GET['year']))
								{{ Form::text('year',$_GET['year'], array('class' => 'yearpicker form-control','id'=>'year_search')) }}	
								@else
								{{ Form::text('year', $currentYear, array('class' => 'yearpicker form-control','id'=>'year_search')) }}	
								@endif
                                </div>
                            </div> 
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12 pdr-20" style="text-align: end;    padding: 0px;">
								<a href="{{ url('export-percentage-ofbusiness') }}" class="btn btn-sm btn-primary  mt-3" id="btn_download_spreadsheet" title="Download Spreadsheet">
									 <span class="btn-inner-icon"><i class="ti-files" style="font-size: 25px;"> </i></span>
								</a>
							</div>
                        </div>
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
                    <table style="text-align:center;">
						<thead style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">
							<tr>
								<td rowspan="3" style="border: 1px solid #ffff;border-left:1px solid gray;">Quarter</td>
								<td colspan="6" style="border: 1px solid #ffff;">New Business Registration</td>
								<td colspan="6"style="border: 1px solid #ffff;">Renewal of Business</td>
								<td colspan="6" style="border: 1px solid #ffff;border-right:1px solid gray ;">Total Business</td>
								
							</tr>
							<tr>
								<td colspan="3" style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Percentage</td>
								<td colspan="3" style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Number</td>
								<td colspan="3" style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Percentage</td>
								<td colspan="3" style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Number</td>
								<td colspan="3" style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Percentage</td>
								<td colspan="3" style="background: #20b7cc;color: #fff;border: 1px solid #ffff;border-right:1px solid gray ;">Number</td>
							</tr>
							<tr>
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Women</td>
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Men</td>
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">No Sex(Partnership, Corporation,Association)</td>
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Women</td>
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Men</td>
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">No Sex(Partnership, Corporation,Association)</td>
								
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Women</td>
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Men</td>
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">No Sex(Partnership, Corporation,Association)</td>
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Women</td>
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Men</td>
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">No Sex(Partnership, Corporation,Association)</td>
								
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Women</td>
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Men</td>
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">No Sex(Partnership, Corporation,Association)</td>
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Women</td>
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;">Men</td>
								<td  style="background: #20b7cc;color: #fff;border: 1px solid #ffff;border-right:1px solid gray ;">No Sex(Partnership, Corporation,Association)</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1st</td>
								<td>{{number_format((float)$FemalePercentageNew1st, 2, '.', '')}}%</td>
								<td>{{number_format((float)$MalePercentageNew1st, 2, '.', '')}}%</td>
								<td>{{number_format((float)$AssociativePercentageNew1st, 2, '.', '')}}%</td>
								<td>{{$FemaleNew1stQuarter}}</td>
								<td>{{$MaleNew1stQuarter}}</td>
								<td>{{$assoctive1stQuarter}}</td>
								
								<td>{{number_format((float)$FemalePercentageRe1st, 2, '.', '')}}%</td>
								<td>{{number_format((float)$MalePercentageRe1st, 2, '.', '')}}%</td>
								<td>{{number_format((float)$AssociativePercentageRe1st, 2, '.', '')}}%</td>
								<td>{{$FemaleRenewal1stquarter}}</td>
								<td>{{$MaleRenewal1stquarter}}</td>
								<td>{{$assoctive1stQuarterRe}}</td>
								
								<td>{{number_format((float)$totalfemalePercentage1st, 2, '.', '')}}%</td>
								<td>{{number_format((float)$totalMalePercentage1st, 2, '.', '')}}%</td>
								<td>{{number_format((float)$totalAssociativePercentage1st, 2, '.', '')}}%</td>
								<td>{{$totalFemale1stPercentage}}</td>
								<td>{{$totalMale1stPercentage}}</td>
								<td>{{$totalAssociativePercentage}}</td>
							</tr>
							<tr>
								<td>2nd</td>
								<td>{{number_format((float)$FemalePercentageNew2nd, 2, '.', '')}}%</td>
								<td>{{number_format((float)$MalePercentageNew2nd, 2, '.', '')}}%</td>
								<td>{{number_format((float)$AssociativePercentageNew2nd, 2, '.', '')}}%</td>
								<td>{{$FemaleNew2ndQuarter}}</td>
								<td>{{$MaleNew2ndQuarter}}</td>
								<td>{{$assoctive2ndQuarter}}</td>
								
								<td>{{number_format((float)$FemalePercentageRe2nd, 2, '.', '')}}%</td>
								<td>{{number_format((float)$MalePercentageRe2nd, 2, '.', '')}}%</td>
								<td>{{number_format((float)$AssociativePercentageRe2nd, 2, '.', '')}}%</td>
								<td>{{$FemaleRenewal2ndquarter}}</td>
								<td>{{$MaleRenewal2ndquarter}}</td>
								<td>{{$assoctive2ndQuarterRe}}</td>
								
								<td>{{number_format((float)$totalfemalePercentage2nd, 2, '.', '')}}%</td>
								<td>{{number_format((float)$totalMalePercentage2nd, 2, '.', '')}}%</td>
								<td>{{number_format((float)$totalAssociativePercentage2nd, 2, '.', '')}}%</td>
								<td>{{$totalFemale2ndPercentage}}</td>
								<td>{{$totalMale2ndPercentage}}</td>
								<td>{{$total2ndAssociativePercentage}}</td>
							</tr>
							<tr>
								<td>3rd</td>
								<td>{{number_format((float)$FemalePercentageNew3rd, 2, '.', '')}}%</td>
								<td>{{number_format((float)$MalePercentageNew3rd, 2, '.', '')}}%</td>
								<td>{{number_format((float)$AssociativePercentageNew3rd, 2, '.', '')}}%</td>
								<td>{{$FemaleNew3rdQuarter}}</td>
								<td>{{$MaleNew3rdQuarter}}</td>
								<td>{{$assoctive3rdQuarter}}</td>
								
								<td>{{number_format((float)$FemalePercentageRe3rd, 2, '.', '')}}%</td>
								<td>{{number_format((float)$MalePercentageRe3rd, 2, '.', '')}}%</td>
								<td>{{number_format((float)$AssociativePercentageRe3rd, 2, '.', '')}}%</td>
								<td>{{$FemaleRenewal3rdquarter}}</td>
								<td>{{$MaleRenewal3rdquarter}}</td>
								<td>{{$assoctive3rdQuarterRe}}</td>
								
								<td>{{number_format((float)$totalfemalePercentage3rd, 2, '.', '')}}%</td>
								<td>{{number_format((float)$totalMalePercentage3rd, 2, '.', '')}}%</td>
								<td>{{number_format((float)$totalAssociativePercentage3rd, 2, '.', '')}}%</td>
								<td>{{$totalFemale3rdPercentage}}</td>
								<td>{{$totalMale3rdPercentage}}</td>
								<td>{{$total3rdAssociativePercentage}}</td>
							</tr>
							<tr>
								<td>4th</td>
								<td>{{number_format((float)$FemalePercentageNew4th, 2, '.', '')}}%</td>
								<td>{{number_format((float)$MalePercentageNew4th, 2, '.', '')}}%</td>
								<td>{{number_format((float)$AssociativePercentageNew4th, 2, '.', '')}}%</td>
								<td>{{$FemaleNew4thQuarter}}</td>
								<td>{{$MaleNew4thQuarter}}</td>
								<td>{{$assoctive4thQuarter}}</td>
								
								<td>{{number_format((float)$FemalePercentageRe4th, 2, '.', '')}}%</td>
								<td>{{number_format((float)$MalePercentageRe4th, 2, '.', '')}}%</td>
								<td>{{number_format((float)$AssociativePercentageRe4th, 2, '.', '')}}%</td>
								<td>{{$FemaleRenewal4thquarter}}</td>
								<td>{{$MaleRenewal4thquarter}}</td>
								<td>{{$assoctive4thQuarterRe}}</td>
								
								<td>{{number_format((float)$totalfemalePercentage4th, 2, '.', '')}}%</td>
								<td>{{number_format((float)$totalMalePercentage4th, 2, '.', '')}}%</td>
								<td>{{number_format((float)$totalAssociativePercentage4th, 2, '.', '')}}%</td>
								<td>{{$totalFemale4thPercentage}}</td>
								<td>{{$totalMale4thPercentage}}</td>
								<td>{{$total4thAssociativePercentage}}</td>
							</tr>
						</tbody>
					</table>
					</div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/yearpicker.js') }}"></script>
    <script src="{{ asset('js/PercentageOf.js') }}?rand={{ rand(000,999) }}"></script>
@endsection
