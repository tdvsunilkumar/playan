<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class ReportsMasterlists extends Model
{
    public $table = 'bplo_business';
	
	public function getureofname($id){
		
        return DB::table('bplo_business_psic')
               ->join('psic_subclasses','psic_subclasses.id','=','bplo_business_psic.subclass_id')
               ->where('bplo_business_psic.busn_id',$id)
               ->select('psic_subclasses.subclass_description','psic_subclasses.subclass_code','bplo_business_psic.busp_capital_investment','bplo_business_psic.busp_total_gross')->get();
    }
    public function calCapInvest($id){
        return DB::table('bplo_business_psic')
               ->join('psic_subclasses','psic_subclasses.id','=','bplo_business_psic.subclass_id')
               ->where('bplo_business_psic.busn_id',$id)
               ->sum('bplo_business_psic.busp_capital_investment');
    }
    public function calTotalGross($id){
        return DB::table('bplo_business_psic')
               ->join('psic_subclasses','psic_subclasses.id','=','bplo_business_psic.subclass_id')
               ->where('bplo_business_psic.busn_id',$id)
               ->sum('bplo_business_psic.busp_total_gross');
    }
	public function getBusinessPSIC($id){
        return DB::table('bplo_business_psic AS psic')
                ->join('psic_subclasses AS sub', 'sub.id', '=', 'psic.subclass_id')
                ->select('sub.id','sub.subclass_code','sub.subclass_description','sub.subclass_code','psic.busp_capital_investment','psic.busp_total_gross')->where('psic.busn_id',(int)$id)->get()->toArray();
    }
	public function NatureOfBusiness($id){
        return DB::table('bplo_business_psic')->where('busn_id',$id)->get();
    }
    public function sizeByCap($id,$b_typ_id){
        if($b_typ_id == 2){
            return DB::table('bplo_business_psic')
            ->join('psic_subclasses','psic_subclasses.id','=','bplo_business_psic.subclass_id')
            ->where('bplo_business_psic.busn_id',$id)
            ->sum('bplo_business_psic.busp_total_gross');
        }
        else{
            return DB::table('bplo_business_psic')
            ->join('psic_subclasses','psic_subclasses.id','=','bplo_business_psic.subclass_id')
            ->where('bplo_business_psic.busn_id',$id)
            ->sum('bplo_business_psic.busp_capital_investment');
        }
        
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
          1 =>"bb.busns_id_no",  
          2 =>"bbpi.bpi_permit_no",
          3 =>"bb.busn_name",  
          4 =>"cl.rpo_custom_last_name",
          5 =>"cl.rpo_first_name",
          6 =>"cl.rpo_middle_name",
          7 =>"cl.suffix",

          8 =>"cl.gender",
          11 =>"bb.created_at",
          12 =>"bat.app_type",
          15 =>"apt.name",
          16 =>"bbt.btype_desc",
          17 =>"ctc.total_paid_surcharge",
          18 =>"ctc.total_paid_interest",
          19 =>"ctc.total_paid_amount",
          20 =>"ctc.or_no",
          21 =>"ctc.cashier_or_date",

          22 =>"bb.busn_tin_no",
          23 =>"bb.busn_registration_no",
          24 =>"bb.busn_employee_no_male",
          25 =>"bb.busn_employee_no_female",
          26 =>"bb.busn_employee_total_no",
          27 =>"cl.p_mobile_no",
          28 =>"cl.p_email_address",  

          30 =>"bbpi.bpi_remarks",
          31 =>"bb.busn_plate_number",
          32 =>"bb.busn_app_method",
          33 =>"bbpi.bpi_issued_date",
          34 =>"bb.busn_bldg_area",
          35 =>"bb.busn_bldg_total_floor_area",    
         );

        $sql = DB::table('bplo_business AS bb')
			   ->join('clients AS cl','cl.id','=','bb.client_id')
			   ->join('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
			   ->join('acctg_payment_types AS apt', 'apt.id', '=', 'bb.pm_id')
			   ->join('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
			   ->join('cto_cashier AS ctc', 'ctc.client_citizen_id', '=', 'bb.client_id')
			   ->join('bplo_business_permit_issuance AS bbpi', 'bbpi.busn_id', '=', 'bb.id')
               ->where('bbt.id','!=',3)
               ->select('bb.*','cl.rpo_first_name As rpo_first_name','cl.rpo_middle_name As rpo_middle_name','cl.rpo_custom_last_name As rpo_custom_last_name','cl.suffix As suffix','cl.gender As gender','cl.p_mobile_no As p_mobile_no','cl.p_email_address As p_email_address',DB::raw("CONCAT(rpo_address_house_lot_no,', ',rpo_address_street_name,', ',rpo_address_subdivision) as ownar_address"),'bat.app_type',
			   'apt.name As payment_type','bbt.btype_desc As btype_desc','bbt.id As b_typ_id','ctc.total_paid_surcharge As total_paid_surcharge','ctc.total_paid_interest As total_paid_interest','ctc.total_paid_amount As total_paid_amount','ctc.or_no As or_no','ctc.cashier_or_date As cashier_or_date',
			   'bbpi.bpi_remarks As bpi_remarks','bbpi.bpi_permit_no As bpi_permit_no','bbpi.bpi_issued_date As bpi_issued_date')->groupBy('bbpi.bpi_permit_no');
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
            $sql->where(function ($sql) use($q,$que) {
                if(isset($que))
                {
                    $sql->where(DB::raw('busn_app_status'),$que); 
                }
                else{
                    $sql->where(DB::raw('LOWER(cl.rpo_first_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busns_id_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bbpi.bpi_permit_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.rpo_first_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.suffix)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.gender)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bat.app_type)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(apt.name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bbt.btype_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ctc.total_paid_surcharge)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ctc.total_paid_interest)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ctc.total_paid_amount)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ctc.or_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ctc.cashier_or_date)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_tin_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_registration_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_employee_no_male)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_employee_no_female)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_employee_total_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.p_mobile_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.p_email_address)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bbpi.bpi_remarks)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_plate_number)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_app_method)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_bldg_area)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_bldg_total_floor_area)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(cl.rpo_first_name, ' ',cl.rpo_middle_name,' ',cl.rpo_custom_last_name,', ',cl.suffix)"), 'LIKE', "%".strtolower($q)."%")
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
        $data_cnt=$sql->get()->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
	public function exportreportsmaster($request){

        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $from_date=$request->input('from_date');
        $to_date=$request->input('to_date');

	    $params['start']="0";
	    $params['length']=$request->input('length_limit');
        
        $columns = array( 
          1 =>"bb.busns_id_no",  
          2 =>"bbpi.bpi_permit_no",
          3 =>"bb.busn_name",  
          4 =>"cl.rpo_custom_last_name",
          5 =>"cl.rpo_first_name",
          6 =>"cl.rpo_middle_name",
          7 =>"cl.suffix",

          8 =>"cl.gender",
          11 =>"bb.created_at",
          12 =>"bat.app_type",
          15 =>"apt.name",
          16 =>"bbt.btype_desc",
          17 =>"ctc.total_paid_surcharge",
          18 =>"ctc.total_paid_interest",
          19 =>"ctc.total_paid_amount",
          20 =>"ctc.or_no",
          21 =>"ctc.cashier_or_date",

          22 =>"bb.busn_tin_no",
          23 =>"bb.busn_registration_no",
          24 =>"bb.busn_employee_no_male",
          25 =>"bb.busn_employee_no_female",
          26 =>"bb.busn_employee_total_no",
          27 =>"cl.p_mobile_no",
          28 =>"cl.p_email_address",  

          30 =>"bbpi.bpi_remarks",
          31 =>"bb.busn_plate_number",
          32 =>"bb.busn_app_method",
          33 =>"bbpi.bpi_issued_date",
          34 =>"bb.busn_bldg_area",
          35 =>"bb.busn_bldg_total_floor_area",    
         );

        $sql = DB::table('bplo_business AS bb')
			   ->join('clients AS cl','cl.id','=','bb.client_id')
			   ->join('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
			   ->join('acctg_payment_types AS apt', 'apt.id', '=', 'bb.pm_id')
			   ->join('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
			   ->join('cto_cashier AS ctc', 'ctc.client_citizen_id', '=', 'bb.client_id')
			   ->join('bplo_business_permit_issuance AS bbpi', 'bbpi.busn_id', '=', 'bb.id')
               ->where('bbt.id','!=',3)
               ->select('bb.*','cl.rpo_first_name As rpo_first_name','cl.rpo_middle_name As rpo_middle_name','cl.rpo_custom_last_name As rpo_custom_last_name','cl.suffix As suffix','cl.gender As gender','cl.p_mobile_no As p_mobile_no','cl.p_email_address As p_email_address',DB::raw("CONCAT(rpo_address_house_lot_no,', ',rpo_address_street_name,', ',rpo_address_subdivision) as ownar_address"),'bat.app_type',
			   'apt.name As payment_type','bbt.btype_desc As btype_desc','bbt.id As b_typ_id','ctc.total_paid_surcharge As total_paid_surcharge','ctc.total_paid_interest As total_paid_interest','ctc.total_paid_amount As total_paid_amount','ctc.or_no As or_no','ctc.cashier_or_date As cashier_or_date',
			   'bbpi.bpi_remarks As bpi_remarks','bbpi.bpi_permit_no As bpi_permit_no','bbpi.bpi_issued_date As bpi_issued_date')->groupBy('bbpi.bpi_permit_no');
		if(!empty($from_date) && isset($from_date)){
                $sql->whereDate('bb.application_date','>=',$from_date);
        }
        if(!empty($to_date) && isset($to_date)){
                    $sql->whereDate('bb.application_date','<=',$to_date);
            }
        if(!empty($q) && isset($q)){
               $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(cl.rpo_first_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busns_id_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bbpi.bpi_permit_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.rpo_first_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.suffix)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.gender)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bat.app_type)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(apt.name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bbt.btype_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ctc.total_paid_surcharge)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ctc.total_paid_interest)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ctc.total_paid_amount)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ctc.or_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ctc.cashier_or_date)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_tin_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_registration_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_employee_no_male)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_employee_no_female)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_employee_total_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.p_mobile_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.p_email_address)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bbpi.bpi_remarks)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_plate_number)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_app_method)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_bldg_area)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_bldg_total_floor_area)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(cl.rpo_first_name, ' ',cl.rpo_middle_name,' ',cl.rpo_custom_last_name,', ',cl.suffix)"), 'LIKE', "%".strtolower($q)."%")
                   ;
                    
                
                
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
    public function getListEstLineOfbusiness($request){

        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
		
		$q=$request->input('q');
        $from_date=$request->input('from_date');
        $to_date=$request->input('to_date');
		
		$columns = array( 
		
		   1 =>"bb.busns_id_no",  
          2 =>"bb.busn_name", 
          4 =>"cl.full_name",
          6 =>"cl.p_mobile_no",
          7 =>"bb.busn_tax_year",
          10 =>"bb.busn_plate_number",
          11 =>"bbpi.bpi_issued_date",
          12 =>"bb.created_at",
          13 =>"bbt.btype_desc",
          14 =>"bb.busn_app_method",
         );
		 
        $sql = DB::table('bplo_business AS bb')
			   ->join('clients AS cl','cl.id','=','bb.client_id')
			   ->join('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
			   ->join('acctg_payment_types AS apt', 'apt.id', '=', 'bb.pm_id')
			   ->join('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
			   ->join('cto_cashier AS ctc', 'ctc.client_citizen_id', '=', 'bb.client_id')
			   ->join('bplo_business_permit_issuance AS bbpi', 'bbpi.busn_id', '=', 'bb.id')
               ->where('bb.btype_id','!=',3)
               ->select('bb.*','cl.full_name','cl.rpo_first_name As rpo_first_name','cl.rpo_middle_name As rpo_middle_name','cl.rpo_custom_last_name As rpo_custom_last_name','cl.suffix As suffix','cl.gender As gender','cl.p_mobile_no As p_mobile_no','cl.p_email_address As p_email_address',DB::raw("CONCAT(rpo_address_house_lot_no,', ',rpo_address_street_name,', ',rpo_address_subdivision) as ownar_address"),'bat.app_type',
			   'apt.name As payment_type','bbt.btype_desc As btype_desc','bbt.id As b_typ_id','ctc.total_paid_surcharge As total_paid_surcharge','ctc.total_paid_interest As total_paid_interest','ctc.total_paid_amount As total_paid_amount','ctc.or_no As or_no','ctc.cashier_or_date As cashier_or_date',
			   'bbpi.bpi_remarks As bpi_remarks','bbpi.busn_id As busn_id','bbpi.bpi_permit_no As bpi_permit_no','bbpi.bpi_issued_date As bpi_issued_date');

	   if(!empty($from_date) && isset($from_date)){
              $sql->whereDate('bb.application_date','>=',$from_date);
        }
        if(!empty($to_date) && isset($to_date)){
              $sql->whereDate('bb.application_date','<=',$to_date);
        }
		if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q){
			   $sql->where(DB::raw('LOWER(cl.rpo_first_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busns_id_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cl.p_mobile_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_tax_year)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_plate_number)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbt.btype_desc)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_app_method)'),'like',"%".strtolower($q)."%")->orWhere(DB::raw('LOWER(cl.full_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw("CONCAT(cl.rpo_first_name, ' ',cl.rpo_middle_name,' ',cl.rpo_custom_last_name,', ',cl.suffix)"), 'LIKE', "%".strtolower($q)."%")
					; 
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
		$sql->groupBy('bb.busns_id_no','bb.busns_id');
		
        /*  #######  Get count without limit  ###### */   
        $data_cnt=$sql->get()->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
	
	public function getexportlistOfbusiness($request){
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
			   ->join('clients AS cl','cl.id','=','bb.client_id')
			   ->join('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
			   ->join('acctg_payment_types AS apt', 'apt.id', '=', 'bb.pm_id')
			   ->join('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
			   ->join('cto_cashier AS ctc', 'ctc.client_citizen_id', '=', 'bb.client_id')
			   ->join('bplo_business_permit_issuance AS bbpi', 'bbpi.busn_id', '=', 'bb.id')
               ->where('bb.btype_id','!=',3)
               ->select('bb.*','cl.full_name','cl.rpo_first_name As rpo_first_name','cl.rpo_middle_name As rpo_middle_name','cl.rpo_custom_last_name As rpo_custom_last_name','cl.suffix As suffix','cl.gender As gender','cl.p_mobile_no As p_mobile_no','cl.p_email_address As p_email_address',DB::raw("CONCAT(rpo_address_house_lot_no,', ',rpo_address_street_name,', ',rpo_address_subdivision) as ownar_address"),'bat.app_type',
			   'apt.name As payment_type','bbt.btype_desc As btype_desc','bbt.id As b_typ_id','ctc.total_paid_surcharge As total_paid_surcharge','ctc.total_paid_interest As total_paid_interest','ctc.total_paid_amount As total_paid_amount','ctc.or_no As or_no','ctc.cashier_or_date As cashier_or_date',
			   'bbpi.bpi_remarks As bpi_remarks','bbpi.busn_id As busn_id','bbpi.bpi_permit_no As bpi_permit_no','bbpi.bpi_issued_date As bpi_issued_date');
		
		if(!empty($from_date) && isset($from_date)){
                $sql->whereDate('bb.application_date','>=',$from_date);
        }
        if(!empty($to_date) && isset($to_date)){
              $sql->whereDate('bb.application_date','<=',$to_date);
        } 
		
		if(!empty($q) && isset($q)){
		   $sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(cl.rpo_first_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(bb.busns_id_no)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cl.p_mobile_no)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(bb.busn_tax_year)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(bb.busn_plate_number)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(bbt.btype_desc)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(bb.busn_app_method)'),'like',"%".strtolower($q)."%")->orWhere(DB::raw('LOWER(cl.full_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw("CONCAT(cl.rpo_first_name, ' ',cl.rpo_middle_name,' ',cl.rpo_custom_last_name,', ',cl.suffix)"), 'LIKE', "%".strtolower($q)."%")
			   ;
			});
		}
		$sql->groupBy('bb.busns_id_no','bb.busns_id');
        $data=$sql->get();
        return $data;
	 }
    public function getDataExportListBusnReg(){
      $from_date = Session::get('from_date'); 
      $to_date = Session::get('to_date');
      $q = Session::get('searchList');
      $sql = DB::table('bplo_business_history AS bb')
               ->leftjoin('clients AS cl','cl.id','=','bb.client_id')
               ->leftjoin('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
               ->leftjoin('acctg_payment_types AS apt', 'apt.id', '=', 'bb.pm_id')
               ->leftjoin('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
               ->leftjoin('bplo_business_permit_issuance AS bbpi', 'bbpi.busn_id', '=', 'bb.busn_id')
               ->select('bb.*','cl.full_name As full_name','cl.rpo_first_name As rpo_first_name','cl.rpo_middle_name As rpo_middle_name','cl.rpo_custom_last_name As rpo_custom_last_name','cl.suffix As suffix','cl.gender As gender','cl.p_mobile_no As p_mobile_no','cl.p_email_address As p_email_address',DB::raw("CONCAT(rpo_address_house_lot_no,', ',rpo_address_street_name,', ',rpo_address_subdivision) as ownar_address"),'bat.app_type',
               'apt.name As payment_type','bbt.btype_desc As btype_desc','bbt.id As b_typ_id',
               'bbpi.bpi_remarks As bpi_remarks','bbpi.bpi_permit_no As bpi_permit_no','bbpi.bpi_issued_date As bpi_issued_date');
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
            $sql->where(function ($sql) use($q,$que) {
                if(isset($que))
                {
                    $sql->where(DB::raw('bb.busn_app_status'),$que); 
                }
                else{
                    $sql->where(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bbt.btype_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_registration_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bbpi.bpi_permit_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bat.app_type)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.p_mobile_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.p_email_address)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.full_name)'),'like',"%".strtolower($q)."%");
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
        return $data;
  }
    public function getListBusnReg($request){

        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $from_date=$request->input('from_date');
        $to_date=$request->input('to_date');
        Session::put('from_date',$from_date); Session::put('to_date',$to_date);Session::put('searchList',$q);
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="8";
        }
        
        $columns = array( 
          1 =>"busn_name",  
          2 =>"bbt.btype_desc",
          3 => "busn_registration_no",
          4 =>"busn_name",
          5 =>"bbpi.bpi_permit_no",
          6 =>"bat.app_type",
          7 =>"bb.created_at",
          8 =>"bbpi.bpi_issued_date",
          9 =>"cl.rpo_first_name",
          14 =>"cl.p_mobile_no",
          15 =>"cl.p_email_address",  
         );

        $sql = DB::table('bplo_business_history AS bb')
			   ->leftjoin('clients AS cl','cl.id','=','bb.client_id')
			   ->leftjoin('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
			   ->leftjoin('acctg_payment_types AS apt', 'apt.id', '=', 'bb.pm_id')
			   ->leftjoin('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
			   ->leftjoin('bplo_business_permit_issuance AS bbpi', 'bbpi.busn_id', '=', 'bb.busn_id')
               ->select('bb.*','cl.full_name As full_name','cl.rpo_first_name As rpo_first_name','cl.rpo_middle_name As rpo_middle_name','cl.rpo_custom_last_name As rpo_custom_last_name','cl.suffix As suffix','cl.gender As gender','cl.p_mobile_no As p_mobile_no','cl.p_email_address As p_email_address',DB::raw("CONCAT(rpo_address_house_lot_no,', ',rpo_address_street_name,', ',rpo_address_subdivision) as ownar_address"),'bat.app_type',
			   'apt.name As payment_type','bbt.btype_desc As btype_desc','bbt.id As b_typ_id',
			   'bbpi.bpi_remarks As bpi_remarks','bbpi.bpi_permit_no As bpi_permit_no','bbpi.bpi_issued_date As bpi_issued_date');
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
            $sql->where(function ($sql) use($q,$que) {
                if(isset($que))
                {
                    $sql->where(DB::raw('bb.busn_app_status'),$que); 
                }
                else{
                    $sql->where(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bbt.btype_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_registration_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bbpi.bpi_permit_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bat.app_type)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.p_mobile_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.p_email_address)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.full_name)'),'like',"%".strtolower($q)."%");
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

    public function getDeclinedBusnList($request){

        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $from_date=$request->input('from_date');
        $to_date=$request->input('to_date');
        Session::put('dtilidtfromdate',$from_date); Session::put('dtilisttodate',$to_date); Session::put('dtilistsearch',$q);
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="8";
        }
        
        $columns = array( 
          1 =>"cl.rpo_first_name",  
          2 =>"busn_name",
          4 =>"bat.app_type",
          6 =>"cl.p_mobile_no",
		  7 =>"bb.busn_app_method",
          8 =>"cl.p_email_address",  
         );

        $sql = DB::table('bplo_business AS bb')
			   ->leftjoin('clients AS cl','cl.id','=','bb.client_id')
			   ->leftjoin('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
			   ->leftjoin('acctg_payment_types AS apt', 'apt.id', '=', 'bb.pm_id')
			   ->leftjoin('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
               ->where('bb.busn_app_status',7)
               ->select('bb.*','cl.rpo_first_name As rpo_first_name','cl.rpo_middle_name As rpo_middle_name','cl.rpo_custom_last_name As rpo_custom_last_name','cl.suffix As suffix','cl.gender As gender','cl.p_mobile_no As p_mobile_no','cl.p_email_address As p_email_address',DB::raw("CONCAT(rpo_address_house_lot_no,', ',rpo_address_street_name,', ',rpo_address_subdivision) as ownar_address"),'bat.app_type',
			   'apt.name As payment_type','bbt.btype_desc As btype_desc','bbt.id As b_typ_id');
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
            $sql->where(function ($sql) use($q,$que) {
                if(isset($que))
                {
                    $sql->where(DB::raw('busn_app_status'),$que); 
                }
                else{
                    $sql->where(DB::raw('LOWER(cl.rpo_first_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bat.app_type)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.p_mobile_no)'),'like',"%".strtolower($q)."%")
					 ->orWhere(DB::raw('LOWER(cl.p_email_address)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_app_method)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(cl.rpo_first_name, ' ',cl.rpo_middle_name,' ',cl.rpo_custom_last_name,', ',cl.suffix)"), 'LIKE', "%".strtolower($q)."%")
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
    public function getDeclinedBusnListexport(){
        $from_date = Session::get('dtilidtfromdate');  $to_date = Session::get('dtilisttodate');  $q = Session::get('dtilistsearch'); 
        $sql = DB::table('bplo_business AS bb')
               ->leftjoin('clients AS cl','cl.id','=','bb.client_id')
               ->leftjoin('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
               ->leftjoin('acctg_payment_types AS apt', 'apt.id', '=', 'bb.pm_id')
               ->leftjoin('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
               ->where('bb.busn_app_status',7)
               ->select('bb.*','cl.rpo_first_name As rpo_first_name','cl.rpo_middle_name As rpo_middle_name','cl.rpo_custom_last_name As rpo_custom_last_name','cl.suffix As suffix','cl.gender As gender','cl.p_mobile_no As p_mobile_no','cl.p_email_address As p_email_address',DB::raw("CONCAT(rpo_address_house_lot_no,', ',rpo_address_street_name,', ',rpo_address_subdivision) as ownar_address"),'bat.app_type',
               'apt.name As payment_type','bbt.btype_desc As btype_desc','bbt.id As b_typ_id');
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
            $sql->where(function ($sql) use($q,$que) {
                if(isset($que))
                {
                    $sql->where(DB::raw('busn_app_status'),$que); 
                }
                else{
                    $sql->where(DB::raw('LOWER(cl.rpo_first_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bat.app_type)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.p_mobile_no)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(cl.p_email_address)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_app_method)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(cl.rpo_first_name, ' ',cl.rpo_middle_name,' ',cl.rpo_custom_last_name,', ',cl.suffix)"), 'LIKE', "%".strtolower($q)."%")
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
        $data=$sql->get();
        return $data;
  }
}
