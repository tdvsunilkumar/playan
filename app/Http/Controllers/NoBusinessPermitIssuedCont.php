<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\NoBusinessPermit;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use DB;
use Response;
class NoBusinessPermitIssuedCont extends Controller
{
     public $data = [];
     public $postdata = [];
	 public $arrYears = array(""=>"Select Year");
     private $slugs;
	 
     public function __construct(){
        $this->_NoBusinessPermit = new NoBusinessPermit();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array();
        $this->slugs = 'reports-masterlists-noof-business-permit-issued';
		$arrYrs = $this->_NoBusinessPermit->getYearDetails();
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
			
			$NewFirstQuarter = DB::table('bplo_business_permit_issuance')->where('bpi_year','=',$year)->where('bpi_month','<=','3')->where('app_type_id','1')->count();
			$RenewalFirstquarter = DB::table('bplo_business_permit_issuance')->where('bpi_year','=',$year)->where('bpi_month','<=','3')->where('app_type_id','=','2')->count();
			
			$SecondNewQuarter = DB::table('bplo_business_permit_issuance')->where('bpi_year','=',$year)->where('bpi_month','>','3')->where('bpi_month','<=','6')->where('app_type_id','=','1')->count();
			$SecondRenewalQuarter = DB::table('bplo_business_permit_issuance')->where('bpi_year','=',$year)->where('bpi_month','>','3')->where('bpi_month','<=','6')->where('app_type_id','=','2')->count();
			
			$New3thQuarter = DB::table('bplo_business_permit_issuance')->where('bpi_year','=',$year)->where('bpi_month','>','6')->where('bpi_month','<=','9')->where('app_type_id','=','1')->count();
			$Renewal3thQuarter = DB::table('bplo_business_permit_issuance')->where('bpi_year','=',$year)->where('bpi_month','>','6')->where('bpi_month','<=','9')->where('app_type_id','=','2')->count();
			
			$New4thQuarter = DB::table('bplo_business_permit_issuance')->where('bpi_year','=',$year)->where('bpi_month','>','9')->where('bpi_month','<=','12')->where('app_type_id','=','1')->count();
			$Renewal4thQuarter = DB::table('bplo_business_permit_issuance')->where('bpi_year','=',$year)->where('bpi_month','>','9')->where('bpi_month','<=','12')->where('app_type_id','=','2')->count();

		}else{
			
			$NewFirstQuarter = DB::table('bplo_business_permit_issuance')->where('bpi_year','=',date('Y'))->where('bpi_month','<=','3')->where('app_type_id','=','1')->count();
			$RenewalFirstquarter = DB::table('bplo_business_permit_issuance')->where('bpi_year','=',date('Y'))->where('bpi_month','<=','3')->where('app_type_id','=','2')->count();
			
			$SecondNewQuarter = DB::table('bplo_business_permit_issuance')->where('bpi_year','=',date('Y'))->where('bpi_month','>','3')->where('bpi_month','<=','6')->where('app_type_id','=','1')->count();
			$SecondRenewalQuarter = DB::table('bplo_business_permit_issuance')->where('bpi_year','=',date('Y'))->where('bpi_month','>','3')->where('bpi_month','<=','6')->where('app_type_id','=','2')->count();
			
			$New3thQuarter = DB::table('bplo_business_permit_issuance')->where('bpi_year','=',date('Y'))->where('bpi_month','>','6')->where('bpi_month','<=','9')->where('app_type_id','=','1')->count();
			$Renewal3thQuarter = DB::table('bplo_business_permit_issuance')->where('bpi_year','=',date('Y'))->where('bpi_month','>','6')->where('bpi_month','<=','9')->where('app_type_id','=','2')->count();
			
			$New4thQuarter = DB::table('bplo_business_permit_issuance')->where('bpi_year','=',date('Y'))->where('bpi_month','>','9')->where('bpi_month','<=','12')->where('app_type_id','=','1')->count();
			$Renewal4thQuarter = DB::table('bplo_business_permit_issuance')->where('bpi_year','=',date('Y'))->where('bpi_month','>','9')->where('bpi_month','<=','12')->where('app_type_id','=','2')->count();
		}
		
        return view('businesspermitissued.index')->with(compact(
				'arrYears',
				'NewFirstQuarter',
				'RenewalFirstquarter',
				'SecondNewQuarter',
				'SecondRenewalQuarter',
				'New3thQuarter',
				'Renewal3thQuarter',
				'New4thQuarter',
				'Renewal4thQuarter',
			));
    }

}
