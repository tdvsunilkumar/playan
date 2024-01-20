<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ReportAssessedFees extends Model
{
	public $table = 'bplo_business';
	
	public function getureofname($id){
        return DB::table('psic_subclasses')->where('class_id',$id)->select('subclass_description')->get();
    }
	
	public function NatureOfBusiness($id){
        return DB::table('bplo_business_psic')->where('busn_id',$id)->get();
    }
	public function getBusinessPSIC($id){
        return DB::table('bplo_business_psic AS psic')
                ->join('psic_subclasses AS sub', 'sub.id', '=', 'psic.subclass_id')
                ->select('sub.id','sub.subclass_code','sub.subclass_description','psic.busp_capital_investment','psic.busp_total_gross')->where('psic.busn_id',(int)$id)->get()->toArray();
    }
    public function gettfocsids($id){
       return DB::table('report_column_headers')->select('tfoc_id')->where('id',$id)->first();
    }

    public function getBusinesstax($ids,$busnid){
        return DB::table('cto_bplo_assessment')->select('id',DB::raw('SUM(tfoc_amount) AS amount'))->whereIN('tfoc_id',$ids)->where('busn_id',$busnid)->first();
    }

    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $from_date=$request->input('from_date');
        $to_date=$request->input('to_date');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="8";
        }
        
        $columns = array( 
		  1 =>"bpi_permit_no",
          2 =>"busns_id_no",  
          3 =>"cc.full_name",
		  4 =>"cc.rpo_custom_last_name",
		  5 =>"cc.rpo_first_name",
          6 =>"busn_name",
		  7 =>"bb.busn_office_add_lot_no",
		  8 =>"bat.app_type",
          10 =>"cc.p_telephone_no",
          11 =>"bbt.btype_desc",
		  12 =>"bb.busn_employee_no_male",
		  13 =>"bbp.busp_capital_investment",
		  14 =>"bbp.busp_total_gross",
		  15 =>"bbp.busp_non_essential",
         );
         $sql = DB::table('bplo_business AS bb')
            ->leftjoin('clients AS cc', 'cc.id', '=', 'bb.client_id')
            ->leftjoin('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
			->leftjoin('bplo_business_psic AS bbp', 'bbp.busn_id', '=', 'bb.id')
            ->leftjoin('cto_payment_mode AS cpm', 'cpm.id', '=', 'bb.pm_id')
            ->leftjoin('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
            ->leftjoin('barangays AS officeBrgy', 'officeBrgy.id', '=', 'bb.busn_office_barangay_id')
            ->leftjoin('profile_regions AS pr', 'pr.id', '=', 'officeBrgy.reg_no')
            ->leftjoin('profile_provinces AS pp', 'pp.id', '=', 'officeBrgy.prov_no')
            ->leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'officeBrgy.mun_no')
			->leftjoin('bplo_business_permit_issuance AS bbpi', 'bbpi.busn_id', '=', 'bb.id')
            ->select('bb.*','bbt.btype_desc','officeBrgy.brgy_name as office_brgy_name','pm.mun_desc as office_mun_desc','pp.prov_desc as office_prov_desc','pr.reg_region as office_reg_region'
            ,'cc.full_name','cc.rpo_custom_last_name','cc.rpo_first_name','cc.rpo_middle_name','cc.p_telephone_no','cc.p_mobile_no','bat.app_type','cpm.pm_desc',
			 'bbpi.bpi_permit_no As bpi_permit_no','bbp.busn_id As busn_id','bbp.subclass_id As subclass_id','bbp.busp_capital_investment As capital_investment','bbp.busp_total_gross As busp_total_gross','bbp.busp_non_essential As busp_non_essential','bbp.busp_essential As busp_essential');
        if(!empty($from_date) && isset($from_date)){
                $sql->whereDate('bb.application_date','>=',$from_date);
        }
        if(!empty($to_date) && isset($to_date)){
              $sql->whereDate('bb.application_date','<=',$to_date);
        }
        if(!empty($q) && isset($q)){
            switch (strtolower($q)) {
                case 'not completed':
                    $que = 0;
                    break;
                case 'completed/for verification':
                    $que = 1;
                    break;
                case 'for endorsement':
                    $que = 2;
                    break;
                case 'for assessment':
                    $que = 3;
                    break;
                case 'for payment':
                    $que = 4;
                    break;
                case 'for issuance':
                    $que = 5;
                    break;
                case 'license issued':
                    $que = 6;
                    break;
                case 'declined':
                    $que = 7;
                    break;
                case 'cancelled permit':
                    $que = 8;
                    break;
                default:
                    $que = null;
                    break;
            }
            $sql->where(function ($sql) use($q,$que){
                if(isset($que))
                {
                   $sql->where(DB::raw('busn_app_status'),$que); 
                }else{
                    $sql->where(DB::raw('LOWER(bb.busns_id_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbpi.bpi_permit_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
					 ->orWhere(DB::raw('LOWER(cc.full_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(cc.rpo_first_name, ' ',cc.rpo_middle_name,' ',cc.rpo_custom_last_name)"), 'LIKE', "%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_office_add_lot_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_office_add_street_name)'),'like',"%".strtolower($q)."%")
				    ->orWhere(DB::raw('LOWER(bb.busn_office_add_subdivision)'),'like',"%".strtolower($q)."%")
				    ->orWhere(DB::raw('LOWER(bb.office_brgy_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.office_mun_desc)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.office_prov_desc)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.office_reg_region)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bat.app_type)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cc.p_telephone_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cc.p_mobile_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbt.btype_desc)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_employee_no_male)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbp.busp_capital_investment)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbp.busp_essential)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbp.busp_non_essential)'),'like',"%".strtolower($q)."%")
					;
                }
                
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else
        {
            $sql->orderBy('bb.id','ASC');
        }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }


	public function getListexport($request){
		$params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $from_date=$request->input('from_date');
        $to_date=$request->input('to_date');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="8";
        }

		$sql = DB::table('bplo_business AS bb')
            ->leftjoin('clients AS cc', 'cc.id', '=', 'bb.client_id')
            ->leftjoin('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
			->leftjoin('bplo_business_psic AS bbp', 'bbp.busn_id', '=', 'bb.id')
            ->leftjoin('cto_payment_mode AS cpm', 'cpm.id', '=', 'bb.pm_id')
            ->leftjoin('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
            ->leftjoin('barangays AS officeBrgy', 'officeBrgy.id', '=', 'bb.busn_office_barangay_id')
            ->leftjoin('profile_regions AS pr', 'pr.id', '=', 'officeBrgy.reg_no')
            ->leftjoin('profile_provinces AS pp', 'pp.id', '=', 'officeBrgy.prov_no')
            ->leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'officeBrgy.mun_no')
			->leftjoin('bplo_business_permit_issuance AS bbpi', 'bbpi.busn_id', '=', 'bb.id')
            ->select('bb.*','bbt.btype_desc','officeBrgy.brgy_name as office_brgy_name','pm.mun_desc as office_mun_desc','pp.prov_desc as office_prov_desc','pr.reg_region as office_reg_region'
            ,'cc.full_name','cc.rpo_custom_last_name','cc.rpo_first_name','cc.rpo_middle_name','cc.p_telephone_no','cc.p_mobile_no','bat.app_type','cpm.pm_desc',
			 'bbpi.bpi_permit_no As bpi_permit_no','bbp.busn_id As busn_id','bbp.subclass_id As subclass_id','bbp.busp_capital_investment As capital_investment','bbp.busp_total_gross As busp_total_gross','bbp.busp_non_essential As busp_non_essential','bbp.busp_essential As busp_essential');   
		
		if(!empty($from_date) && isset($from_date)){
                $sql->whereDate('bb.application_date','>=',$from_date);
        }
        if(!empty($to_date) && isset($to_date)){
              $sql->whereDate('bb.application_date','<=',$to_date);
        } 
		
		if(!empty($q) && isset($q)){
            switch (strtolower($q)) {
                case 'not completed':
                    $que = 0;
                    break;
                case 'completed/for verification':
                    $que = 1;
                    break;
                case 'for endorsement':
                    $que = 2;
                    break;
                case 'for assessment':
                    $que = 3;
                    break;
                case 'for payment':
                    $que = 4;
                    break;
                case 'for issuance':
                    $que = 5;
                    break;
                case 'license issued':
                    $que = 6;
                    break;
                case 'declined':
                    $que = 7;
                    break;
                case 'cancelled permit':
                    $que = 8;
                    break;
                default:
                    $que = null;
                    break;
            }
            $sql->where(function ($sql) use($q,$que){
                if(isset($que))
                {
                   $sql->where(DB::raw('busn_app_status'),$que); 
                }else{
                    $sql->where(DB::raw('LOWER(bb.busns_id_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(cc.rpo_first_name, ' ',cc.rpo_middle_name,' ',cc.rpo_custom_last_name)"), 'LIKE', "%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bat.app_type)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cpm.pm_desc)'),'like',"%".strtolower($q)."%");
                }
                
            });
        }
	   
		/*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else
        {
            $sql->orderBy('bb.id','ASC');
        }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
	 }
}
