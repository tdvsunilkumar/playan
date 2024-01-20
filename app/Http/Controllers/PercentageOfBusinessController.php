<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\PercentageOfBusiness;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use DB;
use Response;
class PercentageOfBusinessController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
	 public $arrYears = array(""=>"Select year");
     public function __construct(){
        $this->_PercentageOfBusiness = new PercentageOfBusiness(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array();  
        $this->slugs = 'reports-masterlists-percentage-ofbusiness-owned-bysex';
		$arrYrs = $this->_PercentageOfBusiness->getYearDetails();
        foreach($arrYrs AS $key=>$val){
            $this->arrYears[$val->bpi_year] =$val->bpi_year;
        }
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
		$arrYears = $this->arrYears;
		$year =  $request->input('year');
		
	if(!empty($year)){
		//new first
			
		   // $FemaleNew1stQuarter = DB::table('bplo_business_history  as his')
		// 	                    ->leftjoin('bplo_business_permit_issuance as bbpi','bbpi.busn_id', '=', 'his.busn_id')
		// 						->leftjoin('clients as cl','cl.id', '=', 'his.client_id')
		// 						->where('his.busn_tax_year','=',$year)
		// 						->where('bbpi.bpi_issued_status','=','1')
		// 					    ->whereMonth('his.created_at','>','9')->whereMonth('his.created_at','<=','12')
		// 						->where('his.busn_app_status','=','1')
		// 						->where('his.app_code','=','1')
		// 						->where('cl.gender','=','0')
		// 						->where('his.is_individual','=','1')
		// 						->count();
	        
			
			// $MaleNew1stQuarter = DB::table('bplo_business_permit_issuance As bbpi')
			// 							->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 							->join('clients as cl','cl.id', '=', 'bp.client_id')
			// 							->where('bbpi.bpi_issued_status','=','1')
			// 							->where('bp.is_active','=','1')
			// 							->where('bbpi.bpi_year','=',$year)
			// 							->where('bbpi.bpi_month','<=','3')
			// 							->where('bbpi.app_type_id','=','1')
			// 							->where('cl.gender','=','1')
			// 							->where('bp.is_individual','=','1')
			// 							->count();
		    $FemaleNew1stQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_1stquarter'))
                                    ->first();
                                    $FemaleNew1stQuarter = $FemaleNew1stQuarterResult->total_women_1stquarter;
            $MaleNew1stQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_1stquarter'))
                                    ->first();
                                    $MaleNew1stQuarter = $MaleNew1stQuarterResult->total_men_1stquarter;
			$assoctive1stQuarterResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_new_1stquarter'))
                                    ->first();
                                    $assoctive1stQuarter = $assoctive1stQuarterResult->total_new_1stquarter;					
			// $assoctive1stQuarter = DB::table('bplo_business_permit_issuance As bbpi')
			// 						->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 						->where('bbpi.bpi_issued_status','=','1')
			// 						->where('bp.is_active','=','1')
			// 						->where('bbpi.bpi_year','=',$year)
			// 						->where('bbpi.bpi_month','<=','3')
			// 						->where('bbpi.app_type_id','=','1')
			// 						->where('bp.is_individual','=','0')->count();
              

								
			$total1stQuarterNew =  $MaleNew1stQuarter + $FemaleNew1stQuarter +$assoctive1stQuarter;
			if($total1stQuarterNew == 0){
				$total1stQuarterNew =1;
			}
			
			$FemalePercentageNew1st = $FemaleNew1stQuarter*100/$total1stQuarterNew;
			$MalePercentageNew1st = $MaleNew1stQuarter*100/$total1stQuarterNew;
			$AssociativePercentageNew1st = $assoctive1stQuarter*100/$total1stQuarterNew;
			
			
			//Renewal first
			// $FemaleRenewal1stquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','<=','3')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
								
			// $MaleRenewal1stquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','<=','3')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
			
			// $assoctive1stQuarterRe = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','<=','3')->where('bbpi.app_type_id','=','2')->where('bp.is_individual','=','0')->count();
			$FemaleRenewal1stquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_1stquarter'))
                                    ->first();
                                    $FemaleRenewal1stquarter = $FemaleRenewal1stquarterResult->total_women_1stquarter;
            $MaleRenewal1stquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_1stquarter'))
                                    ->first();
                                    $MaleRenewal1stquarter = $MaleRenewal1stquarterResult->total_men_1stquarter;
			$assoctive1stQuarterReResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_re_1stquarter'))
                                    ->first();
                                    $assoctive1stQuarterRe = $assoctive1stQuarterReResult->total_re_1stquarter;
								
			
			$total1stQuarterRen = $FemaleRenewal1stquarter + $MaleRenewal1stquarter +$assoctive1stQuarterRe;
			if($total1stQuarterRen == 0){
				$total1stQuarterRen = 1;
			}
			
			$FemalePercentageRe1st = $FemaleRenewal1stquarter*100/$total1stQuarterRen;
			$MalePercentageRe1st = $MaleRenewal1stquarter*100/$total1stQuarterRen;
			$AssociativePercentageRe1st = $assoctive1stQuarterRe*100/$total1stQuarterRen;
			
			
			//total first Quarter
			$totalFemale1stPercentage 	= $FemaleNew1stQuarter + $FemaleRenewal1stquarter;
			$totalMale1stPercentage 	= $MaleNew1stQuarter + $MaleRenewal1stquarter;
			$totalAssociativePercentage = $assoctive1stQuarterRe + $assoctive1stQuarter;
			
			$totalFirst= $totalFemale1stPercentage + $totalMale1stPercentage + $totalAssociativePercentage;
			if($totalFirst == 0){
				$totalFirst = 1;
			}
			$totalfemalePercentage1st = $totalFemale1stPercentage*100/$totalFirst;
			$totalMalePercentage1st = $totalMale1stPercentage*100/$totalFirst;
			$totalAssociativePercentage1st = $totalAssociativePercentage*100/$totalFirst; 

			
//Second Quarter
			//new Quarter		
			// $FemaleNew2ndQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bbpi.app_type_id','=','1')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
			
			// $MaleNew2ndQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bbpi.app_type_id','=','1')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
								
			// $assoctive2ndQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 						->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')->where('bbpi.bpi_year','=',$year)
			// 						->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bbpi.app_type_id','=','1')->where('bp.is_individual','=','0')->count();
			$FemaleNew2ndQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_2ndquarter'))
                                    ->first();
                                    $FemaleNew2ndQuarter = $FemaleNew2ndQuarterResult->total_women_2ndquarter;
            $MaleNew2ndQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_2ndquarter'))
                                    ->first();
                                    $MaleNew2ndQuarter = $MaleNew2ndQuarterResult->total_men_2ndquarter;
			$assoctive2ndQuarterResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_new_2ndquarter'))
                                    ->first();
                                    $assoctive2ndQuarter = $assoctive2ndQuarterResult->total_new_2ndquarter;
								
			$total2ndQuarterNew =  $FemaleNew2ndQuarter + $MaleNew2ndQuarter +$assoctive2ndQuarter;
			if($total2ndQuarterNew == 0){
				$total2ndQuarterNew =1;
			}
			
			$FemalePercentageNew2nd = $FemaleNew2ndQuarter*100/$total2ndQuarterNew;
			$MalePercentageNew2nd = $MaleNew2ndQuarter*100/$total2ndQuarterNew;
			$AssociativePercentageNew2nd = $assoctive2ndQuarter*100/$total2ndQuarterNew;
			
			
			//Renewal Quarter
			// $FemaleRenewal2ndquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
								
			// $MaleRenewal2ndquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
			
			// $assoctive2ndQuarterRe = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bbpi.app_type_id','=','2')->where('bp.is_individual','=','0')->count();
								
			$FemaleRenewal2ndquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_2ndquarter'))
                                    ->first();
                                    $FemaleRenewal2ndquarter = $FemaleRenewal2ndquarterResult->total_women_2ndquarter;
            $MaleRenewal2ndquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_2ndquarter'))
                                    ->first();
                                    $MaleRenewal2ndquarter = $MaleRenewal2ndquarterResult->total_men_2ndquarter;
			$assoctive2ndQuarterReResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_re_2ndquarter'))
                                    ->first();
                                    $assoctive2ndQuarterRe = $assoctive2ndQuarterReResult->total_re_2ndquarter;
			$total2ndQuarterRen = $FemaleRenewal2ndquarter + $MaleRenewal2ndquarter +$assoctive2ndQuarterRe;
			if($total2ndQuarterRen == 0){
				$total2ndQuarterRen = 1;
			}
			
			$FemalePercentageRe2nd = $FemaleRenewal2ndquarter*100/$total2ndQuarterRen;
			$MalePercentageRe2nd = $MaleRenewal2ndquarter*100/$total2ndQuarterRen;
			$AssociativePercentageRe2nd = $assoctive2ndQuarterRe*100/$total2ndQuarterRen;
			
			
			//total Second Quarter
			$totalFemale2ndPercentage 	= $FemaleNew2ndQuarter + $FemaleRenewal2ndquarter;
			$totalMale2ndPercentage 	= $MaleNew2ndQuarter + $MaleRenewal2ndquarter;
			$total2ndAssociativePercentage = $assoctive2ndQuarter + $assoctive2ndQuarterRe;
			
			$totalSecond= $totalFemale2ndPercentage + $totalMale2ndPercentage + $total2ndAssociativePercentage;
			if($totalSecond == 0){
				$totalSecond = 1;
			}
			$totalfemalePercentage2nd = $totalFemale2ndPercentage*100/$totalSecond;
			$totalMalePercentage2nd = $totalMale2ndPercentage*100/$totalSecond;
			$totalAssociativePercentage2nd = $total2ndAssociativePercentage*100/$totalSecond; 
			
//third Quarter
			//new Quarter		
			// $FemaleNew3rdQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bbpi.app_type_id','=','1')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
			
			// $MaleNew3rdQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bbpi.app_type_id','=','1')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
								
			// $assoctive3rdQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 						->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 						->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bbpi.app_type_id','=','1')->where('bp.is_individual','=','0')->count();
			$FemaleNew3rdQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_3rdquarter'))
                                    ->first();
                                    $FemaleNew3rdQuarter = $FemaleNew3rdQuarterResult->total_women_3rdquarter;
            $MaleNew3rdQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_3rdquarter'))
                                    ->first();
                                    $MaleNew3rdQuarter = $MaleNew3rdQuarterResult->total_men_3rdquarter;
			$assoctive3rdQuarterResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_new_3rdquarter'))
                                    ->first();
                                    $assoctive3rdQuarter = $assoctive3rdQuarterResult->total_new_3rdquarter;
			$total3rdQuarterNew =  $FemaleNew3rdQuarter + $MaleNew3rdQuarter +$assoctive3rdQuarter;
			if($total3rdQuarterNew == 0){
				$total3rdQuarterNew =1;
			}
			
			$FemalePercentageNew3rd = $FemaleNew3rdQuarter*100/$total3rdQuarterNew;
			$MalePercentageNew3rd = $MaleNew3rdQuarter*100/$total3rdQuarterNew;
			$AssociativePercentageNew3rd = $assoctive3rdQuarter*100/$total3rdQuarterNew;
			
			
			//Renewal Quarter
			// $FemaleRenewal3rdquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
								
			// $MaleRenewal3rdquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
			
			// $assoctive3rdQuarterRe = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bbpi.app_type_id','=','2')->where('bp.is_individual','=','0')->count();
			$FemaleRenewal3rdquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_3rdquarter'))
                                    ->first();
                                    $FemaleRenewal3rdquarter = $FemaleRenewal3rdquarterResult->total_women_3rdquarter;
            $MaleRenewal3rdquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_3rdquarter'))
                                    ->first();
                                    $MaleRenewal3rdquarter = $MaleRenewal3rdquarterResult->total_men_3rdquarter;
			$assoctive3rdQuarterReResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_re_3rdquarter'))
                                    ->first();
                                    $assoctive3rdQuarterRe = $assoctive3rdQuarterReResult->total_re_3rdquarter;
								
			
			$total3rdQuarterRen = $FemaleRenewal3rdquarter + $MaleRenewal3rdquarter +$assoctive3rdQuarterRe;
			if($total3rdQuarterRen == 0){
				$total3rdQuarterRen = 1;
			}
			
			$FemalePercentageRe3rd = $FemaleRenewal3rdquarter*100/$total3rdQuarterRen;
			$MalePercentageRe3rd = $MaleRenewal3rdquarter*100/$total3rdQuarterRen;
			$AssociativePercentageRe3rd = $assoctive3rdQuarterRe*100/$total3rdQuarterRen;			
			
			//total third Quarter
			$totalFemale3rdPercentage 	= $FemaleNew3rdQuarter + $FemaleRenewal3rdquarter;
			$totalMale3rdPercentage 	= $MaleNew3rdQuarter + $MaleRenewal3rdquarter;
			$total3rdAssociativePercentage = $assoctive3rdQuarter + $assoctive3rdQuarterRe;
			
			$totalthird= $totalFemale3rdPercentage + $totalMale3rdPercentage + $total3rdAssociativePercentage;
			if($totalthird == 0){
				$totalthird = 1;
			}
			$totalfemalePercentage3rd = $totalFemale3rdPercentage*100/$totalthird;
			$totalMalePercentage3rd = $totalMale3rdPercentage*100/$totalthird;
			$totalAssociativePercentage3rd = $total3rdAssociativePercentage*100/$totalthird;			
//forth Quarter
			//new Quarter		
			// $FemaleNew4thQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bbpi.app_type_id','=','1')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
			
			// $MaleNew4thQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bbpi.app_type_id','=','1')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
								
			// $assoctive4thQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 						->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 						->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bbpi.app_type_id','=','1')->where('bp.is_individual','=','0')->count();
			$FemaleNew4thQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_4thquarter'))
                                    ->first();
                                    $FemaleNew4thQuarter = $FemaleNew4thQuarterResult->total_women_4thquarter;
            $MaleNew4thQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_4thquarter'))
                                    ->first();
                                    $MaleNew4thQuarter = $MaleNew4thQuarterResult->total_men_4thquarter;
			$assoctive4thQuarterResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_new_4thquarter'))
                                    ->first();
                                    $assoctive4thQuarter = $assoctive4thQuarterResult->total_new_4thquarter;

								
			$total4thQuarterNew =  $FemaleNew4thQuarter + $MaleNew4thQuarter +$assoctive4thQuarter;
			if($total4thQuarterNew == 0){
				$total4thQuarterNew =1;
			}
			
			$FemalePercentageNew4th = $FemaleNew4thQuarter*100/$total4thQuarterNew;
			$MalePercentageNew4th = $MaleNew4thQuarter*100/$total4thQuarterNew;
			$AssociativePercentageNew4th = $assoctive4thQuarter*100/$total4thQuarterNew;
			
			
			//Renewal Quarter
			// $FemaleRenewal4thquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
								
			// $MaleRenewal4thquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
			
			// $assoctive4thQuarterRe = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bbpi.app_type_id','=','2')->where('bp.is_individual','=','0')->count();
			$FemaleRenewal4thquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_4thquarter'))
                                    ->first();
                                    $FemaleRenewal4thquarter = $FemaleRenewal4thquarterResult->total_women_4thquarter;
            $MaleRenewal4thquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_4thquarter'))
                                    ->first();
                                    $MaleRenewal4thquarter = $MaleRenewal4thquarterResult->total_men_4thquarter;
			$assoctive4thQuarterReResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_re_4thquarter'))
                                    ->first();
                                    $assoctive4thQuarterRe = $assoctive4thQuarterReResult->total_re_4thquarter;
								
			
			$total4thQuarterRen = $FemaleRenewal4thquarter + $MaleRenewal4thquarter +$assoctive4thQuarterRe;
			if($total4thQuarterRen == 0){
				$total4thQuarterRen = 1;
			}
			
			$FemalePercentageRe4th = $FemaleRenewal4thquarter*100/$total4thQuarterRen;
			$MalePercentageRe4th = $MaleRenewal4thquarter*100/$total4thQuarterRen;
			$AssociativePercentageRe4th = $assoctive4thQuarterRe*100/$total4thQuarterRen;			
			
			
			
			//total third Quarter
			$totalFemale4thPercentage 	= $FemaleNew4thQuarter + $FemaleRenewal4thquarter;
			$totalMale4thPercentage 	= $MaleNew4thQuarter + $MaleRenewal4thquarter;
			$total4thAssociativePercentage = $assoctive4thQuarter + $assoctive4thQuarterRe;
			
			$totalforth= $totalFemale4thPercentage + $totalMale4thPercentage + $total4thAssociativePercentage;
			if($totalforth == 0){
				$totalforth = 1;
			}
			$totalfemalePercentage4th = $totalFemale4thPercentage*100/$totalforth;
			$totalMalePercentage4th = $totalMale4thPercentage*100/$totalforth;
			$totalAssociativePercentage4th = $total4thAssociativePercentage*100/$totalforth;
			
			
			

	}else{
			//new first
					
			// $FemaleNew1stQuarter = DB::table('bplo_business_history  as his')
			//                     ->leftjoin('bplo_business_permit_issuance as bbpi','bbpi.busn_id', '=', 'his.busn_id')
			// 					->leftjoin('clients as cl','cl.id', '=', 'his.client_id')
			// 					->where('his.busn_tax_year','=',date('Y'))
			// 					// ->where('bbpi.bpi_issued_status','=','1')
			// 					// ->where('his.busn_app_status','=','1')
			// 					->where('his.app_code','=','1')
			// 					->where('cl.gender','=','0')
			// 					->where('his.is_individual','=','1')
			// 					->count();
			
			// $MaleNew1stQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','<=','3')->where('bbpi.app_type_id','=','1')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
								
			// $assoctive1stQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 						->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 						->where('bbpi.bpi_month','<=','3')->where('bbpi.app_type_id','=','1')->where('bp.is_individual','=','0')->count();
		    $FemaleNew1stQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year',date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_1stquarter'))
                                    ->first();
                                    $FemaleNew1stQuarter = $FemaleNew1stQuarterResult->total_women_1stquarter;
            $MaleNew1stQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year',date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_1stquarter'))
                                    ->first();
                                    $MaleNew1stQuarter = $MaleNew1stQuarterResult->total_men_1stquarter;
			$assoctive1stQuarterResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year',date('Y'))
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_new_1stquarter'))
                                    ->first();
                                    $assoctive1stQuarter = $assoctive1stQuarterResult->total_new_1stquarter;
								
			$total1stQuarterNew =  $MaleNew1stQuarter + $FemaleNew1stQuarter +$assoctive1stQuarter;
			if($total1stQuarterNew == 0){
				$total1stQuarterNew =1;
			}
			
			$FemalePercentageNew1st = $FemaleNew1stQuarter*100/$total1stQuarterNew;
			$MalePercentageNew1st = $MaleNew1stQuarter*100/$total1stQuarterNew;
			$AssociativePercentageNew1st = $assoctive1stQuarter*100/$total1stQuarterNew;
			
			
			//Renewal first
			// $FemaleRenewal1stquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','<=','3')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
								
			// $MaleRenewal1stquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','<=','3')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
			
			// $assoctive1stQuarterRe = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','<=','3')->where('bbpi.app_type_id','=','2')->where('bp.is_individual','=','0')->count();
			$FemaleRenewal1stquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_1stquarter'))
                                    ->first();
                                    $FemaleRenewal1stquarter = $FemaleRenewal1stquarterResult->total_women_1stquarter;
            $MaleRenewal1stquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_1stquarter'))
                                    ->first();
                                    $MaleRenewal1stquarter = $MaleRenewal1stquarterResult->total_men_1stquarter;
			$assoctive1stQuarterReResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_re_1stquarter'))
                                    ->first();
                                    $assoctive1stQuarterRe = $assoctive1stQuarterReResult->total_re_1stquarter;
								
			
			$total1stQuarterRen = $FemaleRenewal1stquarter + $MaleRenewal1stquarter +$assoctive1stQuarterRe;
			if($total1stQuarterRen == 0){
				$total1stQuarterRen = 1;
			}
			
			$FemalePercentageRe1st = $FemaleRenewal1stquarter*100/$total1stQuarterRen;
			$MalePercentageRe1st = $MaleRenewal1stquarter*100/$total1stQuarterRen;
			$AssociativePercentageRe1st = $assoctive1stQuarterRe*100/$total1stQuarterRen;
			
			
			//total first Quarter
			$totalFemale1stPercentage 	= $FemaleNew1stQuarter + $FemaleRenewal1stquarter;
			$totalMale1stPercentage 	= $MaleNew1stQuarter + $MaleRenewal1stquarter;
			$totalAssociativePercentage = $assoctive1stQuarterRe + $assoctive1stQuarter;
			
			$totalFirst= $totalFemale1stPercentage + $totalMale1stPercentage + $totalAssociativePercentage;
			if($totalFirst == 0){
				$totalFirst = 1;
			}
			$totalfemalePercentage1st = $totalFemale1stPercentage*100/$totalFirst;
			$totalMalePercentage1st = $totalMale1stPercentage*100/$totalFirst;
			$totalAssociativePercentage1st = $totalAssociativePercentage*100/$totalFirst; 

			
//Second Quarter
			//new Quarter		
			// $FemaleNew2ndQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bp.app_code','=','1')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
			
			// $MaleNew2ndQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bp.app_code','=','1')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
								
			// $assoctive2ndQuarter = DB::table('bplo_business_permit_issuance As bbpi')
			//                        ->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 						->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 						->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bp.app_code','=','1')->where('bp.is_individual','=','0')->count();
			$FemaleNew2ndQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_2ndquarter'))
                                    ->first();
                                    $FemaleNew2ndQuarter = $FemaleNew2ndQuarterResult->total_women_2ndquarter;
            $MaleNew2ndQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_2ndquarter'))
                                    ->first();
                                    $MaleNew2ndQuarter = $MaleNew2ndQuarterResult->total_men_2ndquarter;
			$assoctive2ndQuarterResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_new_2ndquarter'))
                                    ->first();
                                    $assoctive2ndQuarter = $assoctive2ndQuarterResult->total_new_2ndquarter;
								
			$total2ndQuarterNew =  $FemaleNew2ndQuarter + $MaleNew2ndQuarter +$assoctive2ndQuarter;
			if($total2ndQuarterNew == 0){
				$total2ndQuarterNew =1;
			}
			
			$FemalePercentageNew2nd = $FemaleNew2ndQuarter*100/$total2ndQuarterNew;
			$MalePercentageNew2nd = $MaleNew2ndQuarter*100/$total2ndQuarterNew;
			$AssociativePercentageNew2nd = $assoctive2ndQuarter*100/$total2ndQuarterNew;
			
			
			//Renewal Quarter
			// $FemaleRenewal2ndquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bp.app_code','=','2')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
								
			// $MaleRenewal2ndquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bp.app_code','=','2')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
			
			// $assoctive2ndQuarterRe = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bp.app_code','=','2')->where('bp.is_individual','=','0')->count();
			$FemaleRenewal2ndquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_2ndquarter'))
                                    ->first();
                                    $FemaleRenewal2ndquarter = $FemaleRenewal2ndquarterResult->total_women_2ndquarter;
            $MaleRenewal2ndquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_2ndquarter'))
                                    ->first();
                                    $MaleRenewal2ndquarter = $MaleRenewal2ndquarterResult->total_men_2ndquarter;
			$assoctive2ndQuarterReResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_re_2ndquarter'))
                                    ->first();
                                    $assoctive2ndQuarterRe = $assoctive2ndQuarterReResult->total_re_2ndquarter;
								
			
			$total2ndQuarterRen = $FemaleRenewal2ndquarter + $MaleRenewal2ndquarter +$assoctive2ndQuarterRe;
			if($total2ndQuarterRen == 0){
				$total2ndQuarterRen = 1;
			}
			
			$FemalePercentageRe2nd = $FemaleRenewal2ndquarter*100/$total2ndQuarterRen;
			$MalePercentageRe2nd = $MaleRenewal2ndquarter*100/$total2ndQuarterRen;
			$AssociativePercentageRe2nd = $assoctive2ndQuarterRe*100/$total2ndQuarterRen;
			
			
			//total Second Quarter
			$totalFemale2ndPercentage 	= $FemaleNew2ndQuarter + $FemaleRenewal2ndquarter;
			$totalMale2ndPercentage 	= $MaleNew2ndQuarter + $MaleRenewal2ndquarter;
			$total2ndAssociativePercentage = $assoctive2ndQuarter + $assoctive2ndQuarterRe;
			
			$totalSecond= $totalFemale2ndPercentage + $totalMale2ndPercentage + $total2ndAssociativePercentage;
			if($totalSecond == 0){
				$totalSecond = 1;
			}
			$totalfemalePercentage2nd = $totalFemale2ndPercentage*100/$totalSecond;
			$totalMalePercentage2nd = $totalMale2ndPercentage*100/$totalSecond;
			$totalAssociativePercentage2nd = $total2ndAssociativePercentage*100/$totalSecond; 
			
//third Quarter
			//new Quarter		
			// $FemaleNew3rdQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bp.app_code','=','1')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
			
			// $MaleNew3rdQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bp.app_code','=','1')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
								
			// $assoctive3rdQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 						->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 						->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bp.app_code','=','1')->where('bp.is_individual','=','0')->count();
			$FemaleNew3rdQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_3rdquarter'))
                                    ->first();
                                    $FemaleNew3rdQuarter = $FemaleNew3rdQuarterResult->total_women_3rdquarter;
            $MaleNew3rdQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_3rdquarter'))
                                    ->first();
                                    $MaleNew3rdQuarter = $MaleNew3rdQuarterResult->total_men_3rdquarter;
			$assoctive3rdQuarterResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_new_3rdquarter'))
                                    ->first();
                                    $assoctive3rdQuarter = $assoctive3rdQuarterResult->total_new_3rdquarter;
								
			$total3rdQuarterNew =  $FemaleNew3rdQuarter + $MaleNew3rdQuarter +$assoctive3rdQuarter;
			if($total3rdQuarterNew == 0){
				$total3rdQuarterNew =1;
			}
			
			$FemalePercentageNew3rd = $FemaleNew3rdQuarter*100/$total3rdQuarterNew;
			$MalePercentageNew3rd = $MaleNew3rdQuarter*100/$total3rdQuarterNew;
			$AssociativePercentageNew3rd = $assoctive3rdQuarter*100/$total3rdQuarterNew;
			
			
			//Renewal Quarter
			// $FemaleRenewal3rdquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bp.app_code','=','2')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
								
			// $MaleRenewal3rdquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bp.app_code','=','2')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
			
			// $assoctive3rdQuarterRe = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bp.app_code','=','2')->where('bp.is_individual','=','0')->count();
			$FemaleRenewal3rdquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_3rdquarter'))
                                    ->first();
                                    $FemaleRenewal3rdquarter = $FemaleRenewal3rdquarterResult->total_women_3rdquarter;
            $MaleRenewal3rdquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_3rdquarter'))
                                    ->first();
                                    $MaleRenewal3rdquarter = $MaleRenewal3rdquarterResult->total_men_3rdquarter;
			$assoctive3rdQuarterReResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_re_3rdquarter'))
                                    ->first();
                                    $assoctive3rdQuarterRe = $assoctive3rdQuarterReResult->total_re_3rdquarter;
								
			
			$total3rdQuarterRen = $FemaleRenewal3rdquarter + $MaleRenewal3rdquarter +$assoctive3rdQuarterRe;
			if($total3rdQuarterRen == 0){
				$total3rdQuarterRen = 1;
			}
			
			$FemalePercentageRe3rd = $FemaleRenewal3rdquarter*100/$total3rdQuarterRen;
			$MalePercentageRe3rd = $MaleRenewal3rdquarter*100/$total3rdQuarterRen;
			$AssociativePercentageRe3rd = $assoctive3rdQuarterRe*100/$total3rdQuarterRen;			
			
			//total third Quarter
			$totalFemale3rdPercentage 	= $FemaleNew3rdQuarter + $FemaleRenewal3rdquarter;
			$totalMale3rdPercentage 	= $MaleNew3rdQuarter + $MaleRenewal3rdquarter;
			$total3rdAssociativePercentage = $assoctive3rdQuarter + $assoctive3rdQuarterRe;
			
			$totalthird= $totalFemale3rdPercentage + $totalMale3rdPercentage + $total3rdAssociativePercentage;
			if($totalthird == 0){
				$totalthird = 1;
			}
			$totalfemalePercentage3rd = $totalFemale3rdPercentage*100/$totalthird;
			$totalMalePercentage3rd = $totalMale3rdPercentage*100/$totalthird;
			$totalAssociativePercentage3rd = $total3rdAssociativePercentage*100/$totalthird;			
//forth Quarter
			//new Quarter		
			// $FemaleNew4thQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))
			// 					->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bp.app_code','=','1')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
			
			// $MaleNew4thQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bbpi.app_type_id','=','1')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
								
			// $assoctive4thQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 						->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 						->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bp.app_code','=','1')->where('bp.is_individual','=','0')->count();
			$FemaleNew4thQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_4thquarter'))
                                    ->first();
                                    $FemaleNew4thQuarter = $FemaleNew4thQuarterResult->total_women_4thquarter;
            $MaleNew4thQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_4thquarter'))
                                    ->first();
                                    $MaleNew4thQuarter = $MaleNew4thQuarterResult->total_men_4thquarter;
			$assoctive4thQuarterResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_new_4thquarter'))
                                    ->first();
                                    $assoctive4thQuarter = $assoctive4thQuarterResult->total_new_4thquarter;
								
			$total4thQuarterNew =  $FemaleNew4thQuarter + $MaleNew4thQuarter +$assoctive4thQuarter;
			if($total4thQuarterNew == 0){
				$total4thQuarterNew =1;
			}
			
			$FemalePercentageNew4th = $FemaleNew4thQuarter*100/$total4thQuarterNew;
			$MalePercentageNew4th = $MaleNew4thQuarter*100/$total4thQuarterNew;
			$AssociativePercentageNew4th = $assoctive4thQuarter*100/$total4thQuarterNew;
			
			
			//Renewal Quarter
			// $FemaleRenewal4thquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bp.app_code','=','2')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
								
			// $MaleRenewal4thquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bp.app_code','=','2')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
			
			// $assoctive4thQuarterRe = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bp.app_code','=','2')->where('bp.is_individual','=','0')->count();
			$FemaleRenewal4thquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_4thquarter'))
                                    ->first();
                                    $FemaleRenewal4thquarter = $FemaleRenewal4thquarterResult->total_women_4thquarter;
            $MaleRenewal4thquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_4thquarter'))
                                    ->first();
                                    $MaleRenewal4thquarter = $MaleRenewal4thquarterResult->total_men_4thquarter;
			$assoctive4thQuarterReResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_re_4thquarter'))
                                    ->first();
                                    $assoctive4thQuarterRe = $assoctive4thQuarterReResult->total_re_4thquarter;
								
			
			$total4thQuarterRen = $FemaleRenewal4thquarter + $MaleRenewal4thquarter +$assoctive4thQuarterRe;
			if($total4thQuarterRen == 0){
				$total4thQuarterRen = 1;
			}
			
			$FemalePercentageRe4th = $FemaleRenewal4thquarter*100/$total4thQuarterRen;
			$MalePercentageRe4th = $MaleRenewal4thquarter*100/$total4thQuarterRen;
			$AssociativePercentageRe4th = $assoctive4thQuarterRe*100/$total4thQuarterRen;			
			
			
			
			//total third Quarter
			$totalFemale4thPercentage 	= $FemaleNew4thQuarter + $FemaleRenewal4thquarter;
			$totalMale4thPercentage 	= $MaleNew4thQuarter + $MaleRenewal4thquarter;
			$total4thAssociativePercentage = $assoctive4thQuarter + $assoctive4thQuarterRe;
			
			$totalforth= $totalFemale4thPercentage + $totalMale4thPercentage + $total4thAssociativePercentage;
			if($totalforth == 0){
				$totalforth = 1;
			}
			$totalfemalePercentage4th = $totalFemale4thPercentage*100/$totalforth;
			$totalMalePercentage4th = $totalMale4thPercentage*100/$totalforth;
			$totalAssociativePercentage4th = $total4thAssociativePercentage*100/$totalforth;			
			
		}


        return view('PercentageOf.index')->with(compact('arrYears','FemaleNew1stQuarter','MaleNew1stQuarter','assoctive1stQuarter','FemalePercentageNew1st','MalePercentageNew1st','AssociativePercentageNew1st','FemaleRenewal1stquarter','MaleRenewal1stquarter','assoctive1stQuarterRe','FemalePercentageRe1st','MalePercentageRe1st','AssociativePercentageRe1st','totalFemale1stPercentage','totalMale1stPercentage','totalAssociativePercentage','totalfemalePercentage1st','totalMalePercentage1st','totalAssociativePercentage1st',
		'FemaleNew2ndQuarter','MaleNew2ndQuarter','assoctive2ndQuarter','FemalePercentageNew2nd','MalePercentageNew2nd','AssociativePercentageNew2nd','FemaleRenewal2ndquarter','MaleRenewal2ndquarter','assoctive2ndQuarterRe','FemalePercentageRe2nd','MalePercentageRe2nd','AssociativePercentageRe2nd','totalFemale2ndPercentage','totalMale2ndPercentage','total2ndAssociativePercentage','totalfemalePercentage2nd','totalMalePercentage2nd','totalAssociativePercentage2nd',
		'FemaleNew3rdQuarter','MaleNew3rdQuarter','assoctive3rdQuarter','FemalePercentageNew3rd','MalePercentageNew3rd','AssociativePercentageNew3rd','FemaleRenewal3rdquarter','MaleRenewal3rdquarter','assoctive3rdQuarterRe','FemalePercentageRe3rd','MalePercentageRe3rd','AssociativePercentageRe3rd','totalFemale3rdPercentage','totalMale3rdPercentage','total3rdAssociativePercentage','totalfemalePercentage3rd','totalMalePercentage3rd','totalAssociativePercentage3rd',
		'FemaleNew4thQuarter','MaleNew4thQuarter','assoctive4thQuarter','FemalePercentageNew4th','MalePercentageNew4th','AssociativePercentageNew4th','assoctive4thQuarterRe','MaleRenewal4thquarter','FemaleRenewal4thquarter','FemalePercentageRe4th','MalePercentageRe4th','AssociativePercentageRe4th','totalfemalePercentage4th','totalMalePercentage4th','totalAssociativePercentage4th','totalFemale4thPercentage','totalMale4thPercentage','total4thAssociativePercentage'));
			
		
    }


   public function exportpercentage(Request $request){
	   $year =  $request->input('year');
	   if(!empty($year)){
		//new first
			$FemaleNew1stQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_1stquarter'))
                                    ->first();
                                    $FemaleNew1stQuarter = $FemaleNew1stQuarterResult->total_women_1stquarter;
            $MaleNew1stQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_1stquarter'))
                                    ->first();
                                    $MaleNew1stQuarter = $MaleNew1stQuarterResult->total_men_1stquarter;
			$assoctive1stQuarterResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_new_1stquarter'))
                                    ->first();
                                    $assoctive1stQuarter = $assoctive1stQuarterResult->total_new_1stquarter;					
			// $assoctive1stQuarter = DB::table('bplo_business_permit_issuance As bbpi')
			// 						->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 						->where('bbpi.bpi_issued_status','=','1')
			// 						->where('bp.is_active','=','1')
			// 						->where('bbpi.bpi_year','=',$year)
			// 						->where('bbpi.bpi_month','<=','3')
			// 						->where('bbpi.app_type_id','=','1')
			// 						->where('bp.is_individual','=','0')->count();
              

								
			$total1stQuarterNew =  $MaleNew1stQuarter + $FemaleNew1stQuarter +$assoctive1stQuarter;
			if($total1stQuarterNew == 0){
				$total1stQuarterNew =1;
			}
			
			$FemalePercentageNew1st = $FemaleNew1stQuarter*100/$total1stQuarterNew;
			$MalePercentageNew1st = $MaleNew1stQuarter*100/$total1stQuarterNew;
			$AssociativePercentageNew1st = $assoctive1stQuarter*100/$total1stQuarterNew;
			
			
			//Renewal first
			// $FemaleRenewal1stquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','<=','3')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
								
			// $MaleRenewal1stquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','<=','3')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
			
			// $assoctive1stQuarterRe = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','<=','3')->where('bbpi.app_type_id','=','2')->where('bp.is_individual','=','0')->count();
			$FemaleRenewal1stquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_1stquarter'))
                                    ->first();
                                    $FemaleRenewal1stquarter = $FemaleRenewal1stquarterResult->total_women_1stquarter;
            $MaleRenewal1stquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_1stquarter'))
                                    ->first();
                                    $MaleRenewal1stquarter = $MaleRenewal1stquarterResult->total_men_1stquarter;
			$assoctive1stQuarterReResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_re_1stquarter'))
                                    ->first();
                                    $assoctive1stQuarterRe = $assoctive1stQuarterReResult->total_re_1stquarter;
								
			
			$total1stQuarterRen = $FemaleRenewal1stquarter + $MaleRenewal1stquarter +$assoctive1stQuarterRe;
			if($total1stQuarterRen == 0){
				$total1stQuarterRen = 1;
			}
			
			$FemalePercentageRe1st = $FemaleRenewal1stquarter*100/$total1stQuarterRen;
			$MalePercentageRe1st = $MaleRenewal1stquarter*100/$total1stQuarterRen;
			$AssociativePercentageRe1st = $assoctive1stQuarterRe*100/$total1stQuarterRen;
			
			
			//total first Quarter
			$totalFemale1stPercentage 	= $FemaleNew1stQuarter + $FemaleRenewal1stquarter;
			$totalMale1stPercentage 	= $MaleNew1stQuarter + $MaleRenewal1stquarter;
			$totalAssociativePercentage = $assoctive1stQuarterRe + $assoctive1stQuarter;
			
			$totalFirst= $totalFemale1stPercentage + $totalMale1stPercentage + $totalAssociativePercentage;
			if($totalFirst == 0){
				$totalFirst = 1;
			}
			$totalfemalePercentage1st = $totalFemale1stPercentage*100/$totalFirst;
			$totalMalePercentage1st = $totalMale1stPercentage*100/$totalFirst;
			$totalAssociativePercentage1st = $totalAssociativePercentage*100/$totalFirst; 

			
//Second Quarter
			//new Quarter		
			// $FemaleNew2ndQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bbpi.app_type_id','=','1')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
			
			// $MaleNew2ndQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bbpi.app_type_id','=','1')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
								
			// $assoctive2ndQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 						->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')->where('bbpi.bpi_year','=',$year)
			// 						->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bbpi.app_type_id','=','1')->where('bp.is_individual','=','0')->count();
			$FemaleNew2ndQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_2ndquarter'))
                                    ->first();
                                    $FemaleNew2ndQuarter = $FemaleNew2ndQuarterResult->total_women_2ndquarter;
            $MaleNew2ndQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_2ndquarter'))
                                    ->first();
                                    $MaleNew2ndQuarter = $MaleNew2ndQuarterResult->total_men_2ndquarter;
			$assoctive2ndQuarterResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_new_2ndquarter'))
                                    ->first();
                                    $assoctive2ndQuarter = $assoctive2ndQuarterResult->total_new_2ndquarter;
								
			$total2ndQuarterNew =  $FemaleNew2ndQuarter + $MaleNew2ndQuarter +$assoctive2ndQuarter;
			if($total2ndQuarterNew == 0){
				$total2ndQuarterNew =1;
			}
			
			$FemalePercentageNew2nd = $FemaleNew2ndQuarter*100/$total2ndQuarterNew;
			$MalePercentageNew2nd = $MaleNew2ndQuarter*100/$total2ndQuarterNew;
			$AssociativePercentageNew2nd = $assoctive2ndQuarter*100/$total2ndQuarterNew;
			
			
			//Renewal Quarter
			// $FemaleRenewal2ndquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
								
			// $MaleRenewal2ndquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
			
			// $assoctive2ndQuarterRe = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bbpi.app_type_id','=','2')->where('bp.is_individual','=','0')->count();
								
			$FemaleRenewal2ndquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_2ndquarter'))
                                    ->first();
                                    $FemaleRenewal2ndquarter = $FemaleRenewal2ndquarterResult->total_women_2ndquarter;
            $MaleRenewal2ndquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_2ndquarter'))
                                    ->first();
                                    $MaleRenewal2ndquarter = $MaleRenewal2ndquarterResult->total_men_2ndquarter;
			$assoctive2ndQuarterReResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_re_2ndquarter'))
                                    ->first();
                                    $assoctive2ndQuarterRe = $assoctive2ndQuarterReResult->total_re_2ndquarter;
			$total2ndQuarterRen = $FemaleRenewal2ndquarter + $MaleRenewal2ndquarter +$assoctive2ndQuarterRe;
			if($total2ndQuarterRen == 0){
				$total2ndQuarterRen = 1;
			}
			
			$FemalePercentageRe2nd = $FemaleRenewal2ndquarter*100/$total2ndQuarterRen;
			$MalePercentageRe2nd = $MaleRenewal2ndquarter*100/$total2ndQuarterRen;
			$AssociativePercentageRe2nd = $assoctive2ndQuarterRe*100/$total2ndQuarterRen;
			
			
			//total Second Quarter
			$totalFemale2ndPercentage 	= $FemaleNew2ndQuarter + $FemaleRenewal2ndquarter;
			$totalMale2ndPercentage 	= $MaleNew2ndQuarter + $MaleRenewal2ndquarter;
			$total2ndAssociativePercentage = $assoctive2ndQuarter + $assoctive2ndQuarterRe;
			
			$totalSecond= $totalFemale2ndPercentage + $totalMale2ndPercentage + $total2ndAssociativePercentage;
			if($totalSecond == 0){
				$totalSecond = 1;
			}
			$totalfemalePercentage2nd = $totalFemale2ndPercentage*100/$totalSecond;
			$totalMalePercentage2nd = $totalMale2ndPercentage*100/$totalSecond;
			$totalAssociativePercentage2nd = $total2ndAssociativePercentage*100/$totalSecond; 
			
//third Quarter
			//new Quarter		
			// $FemaleNew3rdQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bbpi.app_type_id','=','1')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
			
			// $MaleNew3rdQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bbpi.app_type_id','=','1')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
								
			// $assoctive3rdQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 						->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 						->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bbpi.app_type_id','=','1')->where('bp.is_individual','=','0')->count();
			$FemaleNew3rdQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_3rdquarter'))
                                    ->first();
                                    $FemaleNew3rdQuarter = $FemaleNew3rdQuarterResult->total_women_3rdquarter;
            $MaleNew3rdQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_3rdquarter'))
                                    ->first();
                                    $MaleNew3rdQuarter = $MaleNew3rdQuarterResult->total_men_3rdquarter;
			$assoctive3rdQuarterResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_new_3rdquarter'))
                                    ->first();
                                    $assoctive3rdQuarter = $assoctive3rdQuarterResult->total_new_3rdquarter;
			$total3rdQuarterNew =  $FemaleNew3rdQuarter + $MaleNew3rdQuarter +$assoctive3rdQuarter;
			if($total3rdQuarterNew == 0){
				$total3rdQuarterNew =1;
			}
			
			$FemalePercentageNew3rd = $FemaleNew3rdQuarter*100/$total3rdQuarterNew;
			$MalePercentageNew3rd = $MaleNew3rdQuarter*100/$total3rdQuarterNew;
			$AssociativePercentageNew3rd = $assoctive3rdQuarter*100/$total3rdQuarterNew;
			
			
			//Renewal Quarter
			// $FemaleRenewal3rdquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
								
			// $MaleRenewal3rdquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
			
			// $assoctive3rdQuarterRe = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bbpi.app_type_id','=','2')->where('bp.is_individual','=','0')->count();
			$FemaleRenewal3rdquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_3rdquarter'))
                                    ->first();
                                    $FemaleRenewal3rdquarter = $FemaleRenewal3rdquarterResult->total_women_3rdquarter;
            $MaleRenewal3rdquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_3rdquarter'))
                                    ->first();
                                    $MaleRenewal3rdquarter = $MaleRenewal3rdquarterResult->total_men_3rdquarter;
			$assoctive3rdQuarterReResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_re_3rdquarter'))
                                    ->first();
                                    $assoctive3rdQuarterRe = $assoctive3rdQuarterReResult->total_re_3rdquarter;
								
			
			$total3rdQuarterRen = $FemaleRenewal3rdquarter + $MaleRenewal3rdquarter +$assoctive3rdQuarterRe;
			if($total3rdQuarterRen == 0){
				$total3rdQuarterRen = 1;
			}
			
			$FemalePercentageRe3rd = $FemaleRenewal3rdquarter*100/$total3rdQuarterRen;
			$MalePercentageRe3rd = $MaleRenewal3rdquarter*100/$total3rdQuarterRen;
			$AssociativePercentageRe3rd = $assoctive3rdQuarterRe*100/$total3rdQuarterRen;			
			
			//total third Quarter
			$totalFemale3rdPercentage 	= $FemaleNew3rdQuarter + $FemaleRenewal3rdquarter;
			$totalMale3rdPercentage 	= $MaleNew3rdQuarter + $MaleRenewal3rdquarter;
			$total3rdAssociativePercentage = $assoctive3rdQuarter + $assoctive3rdQuarterRe;
			
			$totalthird= $totalFemale3rdPercentage + $totalMale3rdPercentage + $total3rdAssociativePercentage;
			if($totalthird == 0){
				$totalthird = 1;
			}
			$totalfemalePercentage3rd = $totalFemale3rdPercentage*100/$totalthird;
			$totalMalePercentage3rd = $totalMale3rdPercentage*100/$totalthird;
			$totalAssociativePercentage3rd = $total3rdAssociativePercentage*100/$totalthird;			
//forth Quarter
			//new Quarter		
			// $FemaleNew4thQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bbpi.app_type_id','=','1')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
			
			// $MaleNew4thQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bbpi.app_type_id','=','1')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
								
			// $assoctive4thQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 						->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 						->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bbpi.app_type_id','=','1')->where('bp.is_individual','=','0')->count();
			$FemaleNew4thQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_4thquarter'))
                                    ->first();
                                    $FemaleNew4thQuarter = $FemaleNew4thQuarterResult->total_women_4thquarter;
            $MaleNew4thQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_4thquarter'))
                                    ->first();
                                    $MaleNew4thQuarter = $MaleNew4thQuarterResult->total_men_4thquarter;
			$assoctive4thQuarterResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_new_4thquarter'))
                                    ->first();
                                    $assoctive4thQuarter = $assoctive4thQuarterResult->total_new_4thquarter;

								
			$total4thQuarterNew =  $FemaleNew4thQuarter + $MaleNew4thQuarter +$assoctive4thQuarter;
			if($total4thQuarterNew == 0){
				$total4thQuarterNew =1;
			}
			
			$FemalePercentageNew4th = $FemaleNew4thQuarter*100/$total4thQuarterNew;
			$MalePercentageNew4th = $MaleNew4thQuarter*100/$total4thQuarterNew;
			$AssociativePercentageNew4th = $assoctive4thQuarter*100/$total4thQuarterNew;
			
			
			//Renewal Quarter
			// $FemaleRenewal4thquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
								
			// $MaleRenewal4thquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
			
			// $assoctive4thQuarterRe = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_year','=',$year)->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bbpi.app_type_id','=','2')->where('bp.is_individual','=','0')->count();
			$FemaleRenewal4thquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_4thquarter'))
                                    ->first();
                                    $FemaleRenewal4thquarter = $FemaleRenewal4thquarterResult->total_women_4thquarter;
            $MaleRenewal4thquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_4thquarter'))
                                    ->first();
                                    $MaleRenewal4thquarter = $MaleRenewal4thquarterResult->total_men_4thquarter;
			$assoctive4thQuarterReResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', $year)
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_re_4thquarter'))
                                    ->first();
                                    $assoctive4thQuarterRe = $assoctive4thQuarterReResult->total_re_4thquarter;
								
			
			$total4thQuarterRen = $FemaleRenewal4thquarter + $MaleRenewal4thquarter +$assoctive4thQuarterRe;
			if($total4thQuarterRen == 0){
				$total4thQuarterRen = 1;
			}
			
			$FemalePercentageRe4th = $FemaleRenewal4thquarter*100/$total4thQuarterRen;
			$MalePercentageRe4th = $MaleRenewal4thquarter*100/$total4thQuarterRen;
			$AssociativePercentageRe4th = $assoctive4thQuarterRe*100/$total4thQuarterRen;			
			
			
			
			//total third Quarter
			$totalFemale4thPercentage 	= $FemaleNew4thQuarter + $FemaleRenewal4thquarter;
			$totalMale4thPercentage 	= $MaleNew4thQuarter + $MaleRenewal4thquarter;
			$total4thAssociativePercentage = $assoctive4thQuarter + $assoctive4thQuarterRe;
			
			$totalforth= $totalFemale4thPercentage + $totalMale4thPercentage + $total4thAssociativePercentage;
			if($totalforth == 0){
				$totalforth = 1;
			}
			$totalfemalePercentage4th = $totalFemale4thPercentage*100/$totalforth;
			$totalMalePercentage4th = $totalMale4thPercentage*100/$totalforth;
			$totalAssociativePercentage4th = $total4thAssociativePercentage*100/$totalforth;
			
			

	}else{
			//new first
					
			$FemaleNew1stQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year',date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_1stquarter'))
                                    ->first();
                                    $FemaleNew1stQuarter = $FemaleNew1stQuarterResult->total_women_1stquarter;
            $MaleNew1stQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year',date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_1stquarter'))
                                    ->first();
                                    $MaleNew1stQuarter = $MaleNew1stQuarterResult->total_men_1stquarter;
			$assoctive1stQuarterResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year',date('Y'))
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_new_1stquarter'))
                                    ->first();
                                    $assoctive1stQuarter = $assoctive1stQuarterResult->total_new_1stquarter;
								
			$total1stQuarterNew =  $MaleNew1stQuarter + $FemaleNew1stQuarter +$assoctive1stQuarter;
			if($total1stQuarterNew == 0){
				$total1stQuarterNew =1;
			}
			
			$FemalePercentageNew1st = $FemaleNew1stQuarter*100/$total1stQuarterNew;
			$MalePercentageNew1st = $MaleNew1stQuarter*100/$total1stQuarterNew;
			$AssociativePercentageNew1st = $assoctive1stQuarter*100/$total1stQuarterNew;
			
			
			//Renewal first
			// $FemaleRenewal1stquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','<=','3')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
								
			// $MaleRenewal1stquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','<=','3')->where('bbpi.app_type_id','=','2')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
			
			// $assoctive1stQuarterRe = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','<=','3')->where('bbpi.app_type_id','=','2')->where('bp.is_individual','=','0')->count();
			$FemaleRenewal1stquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_1stquarter'))
                                    ->first();
                                    $FemaleRenewal1stquarter = $FemaleRenewal1stquarterResult->total_women_1stquarter;
            $MaleRenewal1stquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_1stquarter'))
                                    ->first();
                                    $MaleRenewal1stquarter = $MaleRenewal1stquarterResult->total_men_1stquarter;
			$assoctive1stQuarterReResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_re_1stquarter'))
                                    ->first();
                                    $assoctive1stQuarterRe = $assoctive1stQuarterReResult->total_re_1stquarter;
								
			
			$total1stQuarterRen = $FemaleRenewal1stquarter + $MaleRenewal1stquarter +$assoctive1stQuarterRe;
			if($total1stQuarterRen == 0){
				$total1stQuarterRen = 1;
			}
			
			$FemalePercentageRe1st = $FemaleRenewal1stquarter*100/$total1stQuarterRen;
			$MalePercentageRe1st = $MaleRenewal1stquarter*100/$total1stQuarterRen;
			$AssociativePercentageRe1st = $assoctive1stQuarterRe*100/$total1stQuarterRen;
			
			
			//total first Quarter
			$totalFemale1stPercentage 	= $FemaleNew1stQuarter + $FemaleRenewal1stquarter;
			$totalMale1stPercentage 	= $MaleNew1stQuarter + $MaleRenewal1stquarter;
			$totalAssociativePercentage = $assoctive1stQuarterRe + $assoctive1stQuarter;
			
			$totalFirst= $totalFemale1stPercentage + $totalMale1stPercentage + $totalAssociativePercentage;
			if($totalFirst == 0){
				$totalFirst = 1;
			}
			$totalfemalePercentage1st = $totalFemale1stPercentage*100/$totalFirst;
			$totalMalePercentage1st = $totalMale1stPercentage*100/$totalFirst;
			$totalAssociativePercentage1st = $totalAssociativePercentage*100/$totalFirst; 

			
//Second Quarter
			//new Quarter		
			// $FemaleNew2ndQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bp.app_code','=','1')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
			
			// $MaleNew2ndQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bp.app_code','=','1')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
								
			// $assoctive2ndQuarter = DB::table('bplo_business_permit_issuance As bbpi')
			//                        ->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 						->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 						->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bp.app_code','=','1')->where('bp.is_individual','=','0')->count();
			$FemaleNew2ndQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_2ndquarter'))
                                    ->first();
                                    $FemaleNew2ndQuarter = $FemaleNew2ndQuarterResult->total_women_2ndquarter;
            $MaleNew2ndQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_2ndquarter'))
                                    ->first();
                                    $MaleNew2ndQuarter = $MaleNew2ndQuarterResult->total_men_2ndquarter;
			$assoctive2ndQuarterResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_new_2ndquarter'))
                                    ->first();
                                    $assoctive2ndQuarter = $assoctive2ndQuarterResult->total_new_2ndquarter;
								
			$total2ndQuarterNew =  $FemaleNew2ndQuarter + $MaleNew2ndQuarter +$assoctive2ndQuarter;
			if($total2ndQuarterNew == 0){
				$total2ndQuarterNew =1;
			}
			
			$FemalePercentageNew2nd = $FemaleNew2ndQuarter*100/$total2ndQuarterNew;
			$MalePercentageNew2nd = $MaleNew2ndQuarter*100/$total2ndQuarterNew;
			$AssociativePercentageNew2nd = $assoctive2ndQuarter*100/$total2ndQuarterNew;
			
			
			//Renewal Quarter
			// $FemaleRenewal2ndquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bp.app_code','=','2')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
								
			// $MaleRenewal2ndquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bp.app_code','=','2')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
			
			// $assoctive2ndQuarterRe = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->where('bbpi.bpi_year','=',date('Y'))->where('bbpi.bpi_issued_status','=','1')->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','3')->where('bbpi.bpi_month','<=','6')->where('bp.app_code','=','2')->where('bp.is_individual','=','0')->count();
			$FemaleRenewal2ndquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_2ndquarter'))
                                    ->first();
                                    $FemaleRenewal2ndquarter = $FemaleRenewal2ndquarterResult->total_women_2ndquarter;
            $MaleRenewal2ndquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 2)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_2ndquarter'))
                                    ->first();
                                    $MaleRenewal2ndquarter = $MaleRenewal2ndquarterResult->total_men_2ndquarter;
			$assoctive2ndQuarterReResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 1)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_re_2ndquarter'))
                                    ->first();
                                    $assoctive2ndQuarterRe = $assoctive2ndQuarterReResult->total_re_2ndquarter;
								
			
			$total2ndQuarterRen = $FemaleRenewal2ndquarter + $MaleRenewal2ndquarter +$assoctive2ndQuarterRe;
			if($total2ndQuarterRen == 0){
				$total2ndQuarterRen = 1;
			}
			
			$FemalePercentageRe2nd = $FemaleRenewal2ndquarter*100/$total2ndQuarterRen;
			$MalePercentageRe2nd = $MaleRenewal2ndquarter*100/$total2ndQuarterRen;
			$AssociativePercentageRe2nd = $assoctive2ndQuarterRe*100/$total2ndQuarterRen;
			
			
			//total Second Quarter
			$totalFemale2ndPercentage 	= $FemaleNew2ndQuarter + $FemaleRenewal2ndquarter;
			$totalMale2ndPercentage 	= $MaleNew2ndQuarter + $MaleRenewal2ndquarter;
			$total2ndAssociativePercentage = $assoctive2ndQuarter + $assoctive2ndQuarterRe;
			
			$totalSecond= $totalFemale2ndPercentage + $totalMale2ndPercentage + $total2ndAssociativePercentage;
			if($totalSecond == 0){
				$totalSecond = 1;
			}
			$totalfemalePercentage2nd = $totalFemale2ndPercentage*100/$totalSecond;
			$totalMalePercentage2nd = $totalMale2ndPercentage*100/$totalSecond;
			$totalAssociativePercentage2nd = $total2ndAssociativePercentage*100/$totalSecond; 
			
//third Quarter
			//new Quarter		
			// $FemaleNew3rdQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bp.app_code','=','1')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
			
			// $MaleNew3rdQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bp.app_code','=','1')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
								
			// $assoctive3rdQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 						->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 						->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bp.app_code','=','1')->where('bp.is_individual','=','0')->count();
			$FemaleNew3rdQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_3rdquarter'))
                                    ->first();
                                    $FemaleNew3rdQuarter = $FemaleNew3rdQuarterResult->total_women_3rdquarter;
            $MaleNew3rdQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_3rdquarter'))
                                    ->first();
                                    $MaleNew3rdQuarter = $MaleNew3rdQuarterResult->total_men_3rdquarter;
			$assoctive3rdQuarterResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_new_3rdquarter'))
                                    ->first();
                                    $assoctive3rdQuarter = $assoctive3rdQuarterResult->total_new_3rdquarter;
								
			$total3rdQuarterNew =  $FemaleNew3rdQuarter + $MaleNew3rdQuarter +$assoctive3rdQuarter;
			if($total3rdQuarterNew == 0){
				$total3rdQuarterNew =1;
			}
			
			$FemalePercentageNew3rd = $FemaleNew3rdQuarter*100/$total3rdQuarterNew;
			$MalePercentageNew3rd = $MaleNew3rdQuarter*100/$total3rdQuarterNew;
			$AssociativePercentageNew3rd = $assoctive3rdQuarter*100/$total3rdQuarterNew;
			
			
			//Renewal Quarter
			// $FemaleRenewal3rdquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bp.app_code','=','2')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
								
			// $MaleRenewal3rdquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bp.app_code','=','2')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
			
			// $assoctive3rdQuarterRe = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','6')->where('bbpi.bpi_month','<=','9')->where('bp.app_code','=','2')->where('bp.is_individual','=','0')->count();
			$FemaleRenewal3rdquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_3rdquarter'))
                                    ->first();
                                    $FemaleRenewal3rdquarter = $FemaleRenewal3rdquarterResult->total_women_3rdquarter;
            $MaleRenewal3rdquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_3rdquarter'))
                                    ->first();
                                    $MaleRenewal3rdquarter = $MaleRenewal3rdquarterResult->total_men_3rdquarter;
			$assoctive3rdQuarterReResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 3)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_re_3rdquarter'))
                                    ->first();
                                    $assoctive3rdQuarterRe = $assoctive3rdQuarterReResult->total_re_3rdquarter;
								
			
			$total3rdQuarterRen = $FemaleRenewal3rdquarter + $MaleRenewal3rdquarter +$assoctive3rdQuarterRe;
			if($total3rdQuarterRen == 0){
				$total3rdQuarterRen = 1;
			}
			
			$FemalePercentageRe3rd = $FemaleRenewal3rdquarter*100/$total3rdQuarterRen;
			$MalePercentageRe3rd = $MaleRenewal3rdquarter*100/$total3rdQuarterRen;
			$AssociativePercentageRe3rd = $assoctive3rdQuarterRe*100/$total3rdQuarterRen;			
			
			//total third Quarter
			$totalFemale3rdPercentage 	= $FemaleNew3rdQuarter + $FemaleRenewal3rdquarter;
			$totalMale3rdPercentage 	= $MaleNew3rdQuarter + $MaleRenewal3rdquarter;
			$total3rdAssociativePercentage = $assoctive3rdQuarter + $assoctive3rdQuarterRe;
			
			$totalthird= $totalFemale3rdPercentage + $totalMale3rdPercentage + $total3rdAssociativePercentage;
			if($totalthird == 0){
				$totalthird = 1;
			}
			$totalfemalePercentage3rd = $totalFemale3rdPercentage*100/$totalthird;
			$totalMalePercentage3rd = $totalMale3rdPercentage*100/$totalthird;
			$totalAssociativePercentage3rd = $total3rdAssociativePercentage*100/$totalthird;			
//forth Quarter
			//new Quarter		
			// $FemaleNew4thQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))
			// 					->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bp.app_code','=','1')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
			
			// $MaleNew4thQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bbpi.app_type_id','=','1')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
								
			// $assoctive4thQuarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 						->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 						->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bp.app_code','=','1')->where('bp.is_individual','=','0')->count();
			$FemaleNew4thQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_4thquarter'))
                                    ->first();
                                    $FemaleNew4thQuarter = $FemaleNew4thQuarterResult->total_women_4thquarter;
            $MaleNew4thQuarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_4thquarter'))
                                    ->first();
                                    $MaleNew4thQuarter = $MaleNew4thQuarterResult->total_men_4thquarter;
			$assoctive4thQuarterResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 1)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_new_4thquarter'))
                                    ->first();
                                    $assoctive4thQuarter = $assoctive4thQuarterResult->total_new_4thquarter;
								
			$total4thQuarterNew =  $FemaleNew4thQuarter + $MaleNew4thQuarter +$assoctive4thQuarter;
			if($total4thQuarterNew == 0){
				$total4thQuarterNew =1;
			}
			
			$FemalePercentageNew4th = $FemaleNew4thQuarter*100/$total4thQuarterNew;
			$MalePercentageNew4th = $MaleNew4thQuarter*100/$total4thQuarterNew;
			$AssociativePercentageNew4th = $assoctive4thQuarter*100/$total4thQuarterNew;
			
			
			//Renewal Quarter
			// $FemaleRenewal4thquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bp.app_code','=','2')->where('cl.gender','=','0')->where('bp.is_individual','=','1')->count();
								
			// $MaleRenewal4thquarter = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->join('clients as cl','cl.id', '=', 'bp.client_id')->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bp.app_code','=','2')->where('cl.gender','=','1')->where('bp.is_individual','=','1')->count();
			
			// $assoctive4thQuarterRe = DB::table('bplo_business_permit_issuance As bbpi')->join('bplo_business as bp','bp.id', '=', 'bbpi.busn_id')
			// 					->where('bbpi.bpi_year','=',date('Y'))->where('bp.is_active','=','1')
			// 					->where('bbpi.bpi_month','>','9')->where('bbpi.bpi_month','<=','12')->where('bp.app_code','=','2')->where('bp.is_individual','=','0')->count();
			$FemaleRenewal4thquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_women_4thquarter'))
                                    ->first();
                                    $FemaleRenewal4thquarter = $FemaleRenewal4thquarterResult->total_women_4thquarter;
            $MaleRenewal4thquarterResult = DB::table('bplo_business_history as a')
								    ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 1)
								    ->where('b.gender', 1)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_men_4thquarter'))
                                    ->first();
                                    $MaleRenewal4thquarter = $MaleRenewal4thquarterResult->total_men_4thquarter;
			$assoctive4thQuarterReResult = DB::table('bplo_business_history as a')
								    // ->join('clients as b', 'a.client_id', '=', 'b.id')
								    ->join('bplo_business_permit_issuance as c', function ($join) {
								        $join->on('a.busn_id', '=', 'c.busn_id')
								            ->where('c.bpi_issued_status', '=', 1);
								    })
								    ->where('a.busn_tax_year', date('Y'))
								    ->where('a.is_individual', 0)
								    ->where('a.busn_app_status', '>', 1)
								    ->where('a.app_code', 2)
								    ->where(DB::raw('QUARTER(a.created_at)'), 4)
                                    ->select(DB::raw('count(DISTINCT a.busn_id) as total_re_4thquarter'))
                                    ->first();
                                    $assoctive4thQuarterRe = $assoctive4thQuarterReResult->total_re_4thquarter;
								
			
			$total4thQuarterRen = $FemaleRenewal4thquarter + $MaleRenewal4thquarter +$assoctive4thQuarterRe;
			if($total4thQuarterRen == 0){
				$total4thQuarterRen = 1;
			}
			
			$FemalePercentageRe4th = $FemaleRenewal4thquarter*100/$total4thQuarterRen;
			$MalePercentageRe4th = $MaleRenewal4thquarter*100/$total4thQuarterRen;
			$AssociativePercentageRe4th = $assoctive4thQuarterRe*100/$total4thQuarterRen;			
			
			
			
			//total third Quarter
			$totalFemale4thPercentage 	= $FemaleNew4thQuarter + $FemaleRenewal4thquarter;
			$totalMale4thPercentage 	= $MaleNew4thQuarter + $MaleRenewal4thquarter;
			$total4thAssociativePercentage = $assoctive4thQuarter + $assoctive4thQuarterRe;
			
			$totalforth= $totalFemale4thPercentage + $totalMale4thPercentage + $total4thAssociativePercentage;
			if($totalforth == 0){
				$totalforth = 1;
			}
			$totalfemalePercentage4th = $totalFemale4thPercentage*100/$totalforth;
			$totalMalePercentage4th = $totalMale4thPercentage*100/$totalforth;
			$totalAssociativePercentage4th = $total4thAssociativePercentage*100/$totalforth;			
			
		}
		
        $headers = array(
          'Content-Type' => 'text/csv'
        );
        
        if (!File::exists(public_path()."/files")) {
            File::makeDirectory(public_path() . "/files");
        }
        
        $filename =  public_path("files/percentageofbusiness.csv");
        $handle = fopen($filename, 'w');
		
		fputcsv($handle, ['','','','','New Business Registration','','','','','','Renewal of Business','','','','','Total Business','','']);
        fputcsv($handle, ['','','Percentage','','','Number','','','Percentage','','','Number','','','Percentage','','','Number','','']);
        fputcsv($handle, ['Quarter','Women','Men','No Sex(Partnership, Corporation,Association)','Women','Men','No Sex(Partnership, Corporation,Association)','Women','Men','No Sex(Partnership, Corporation,Association)','Women','Men','No Sex(Partnership, Corporation,Association)','Women','Men','No Sex(Partnership, Corporation,Association)','Women','Men','No Sex(Partnership, Corporation,Association)']);
		
		fputcsv($handle,['1st',number_format((float)$FemalePercentageNew1st, 2, '.', '').'%',number_format((float)$MalePercentageNew1st, 2, '.', '').'%',number_format((float)$AssociativePercentageNew1st, 2, '.', '').'%',$FemaleNew1stQuarter,$MaleNew1stQuarter,$assoctive1stQuarter,number_format((float)$FemalePercentageRe1st, 2, '.', '').'%',number_format((float)$MalePercentageRe1st, 2, '.', '').'%',number_format((float)$AssociativePercentageRe1st, 2, '.', '').'%',$FemaleRenewal1stquarter,$MaleRenewal1stquarter,$assoctive1stQuarterRe,number_format((float)$totalfemalePercentage1st, 2, '.', '').'%',number_format((float)$totalMalePercentage1st, 2, '.', '').'%',number_format((float)$totalAssociativePercentage1st, 2, '.', '').'%',$totalFemale1stPercentage,$totalMale1stPercentage ,$totalAssociativePercentage ]);
		fputcsv($handle,['2nd',number_format((float)$FemalePercentageNew2nd, 2, '.', '').'%',number_format((float)$MalePercentageNew2nd, 2, '.', '').'%',number_format((float)$AssociativePercentageNew2nd, 2, '.', '').'%',$FemaleNew2ndQuarter,$MaleNew2ndQuarter,$assoctive2ndQuarter,number_format((float)$FemalePercentageRe2nd, 2, '.', '').'%',number_format((float)$MalePercentageRe2nd, 2, '.', '').'%',number_format((float)$AssociativePercentageRe2nd, 2, '.', '').'%',$FemaleRenewal2ndquarter,$MaleRenewal2ndquarter,$assoctive2ndQuarterRe,number_format((float)$totalfemalePercentage2nd, 2, '.', '').'%',number_format((float)$totalMalePercentage2nd, 2, '.', '').'%',number_format((float)$totalAssociativePercentage2nd, 2, '.', '').'%',$totalFemale2ndPercentage,$totalMale2ndPercentage,$total2ndAssociativePercentage]);
		fputcsv($handle,['3rd',number_format((float)$FemalePercentageNew3rd, 2, '.', '').'%',number_format((float)$MalePercentageNew3rd, 2, '.', '').'%',number_format((float)$AssociativePercentageNew3rd, 2, '.', '').'%',$FemaleNew3rdQuarter,$MaleNew3rdQuarter,$assoctive3rdQuarter,number_format((float)$FemalePercentageRe3rd, 2, '.', '').'%',number_format((float)$MalePercentageRe3rd, 2, '.', '').'%',number_format((float)$AssociativePercentageRe3rd, 2, '.', '').'%',$FemaleRenewal3rdquarter,$MaleRenewal3rdquarter,$assoctive3rdQuarterRe,number_format((float)$totalfemalePercentage3rd, 2, '.', '').'%',number_format((float)$totalMalePercentage3rd, 2, '.', '').'%',number_format((float)$totalAssociativePercentage3rd, 2, '.', '').'%',$totalFemale3rdPercentage,$totalMale3rdPercentage,$total3rdAssociativePercentage]);
		fputcsv($handle,['4th',number_format((float)$FemalePercentageNew4th, 2, '.', '').'%',number_format((float)$MalePercentageNew4th, 2, '.', '').'%',number_format((float)$AssociativePercentageNew4th, 2, '.', '').'%',$FemaleNew4thQuarter,$MaleNew4thQuarter,$assoctive4thQuarter,number_format((float)$FemalePercentageRe4th, 2, '.', '').'%',number_format((float)$MalePercentageRe4th, 2, '.', '').'%',number_format((float)$AssociativePercentageRe4th, 2, '.', '').'%',$FemaleRenewal4thquarter,$MaleRenewal4thquarter,$assoctive4thQuarterRe,number_format((float)$totalfemalePercentage4th, 2, '.', '').'%',number_format((float)$totalMalePercentage4th, 2, '.', '').'%',number_format((float)$totalAssociativePercentage4th, 2, '.', '').'%',$totalFemale4thPercentage,$totalMale4thPercentage,$total4thAssociativePercentage]);
			
           
          fclose($handle);
          return Response::download($filename, "percentageofbusiness.csv", $headers);  
        
   }
}
