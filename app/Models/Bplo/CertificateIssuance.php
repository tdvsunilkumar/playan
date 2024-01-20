<?php

namespace App\Models\Bplo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CertificateIssuance extends Model
{
    public function updateData($id,$columns){
        return DB::table('bplo_business_retirement_issuance')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('bplo_business_retirement_issuance')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }
    public function updateusersavedataData($id,$columns){
        return DB::table('user_last_save_data')->where('id',$id)->update($columns);
    }
    public function addusersaveData($data){
        DB::table('user_last_save_data')->insert($data);
        return DB::getPdo()->lastInsertId();
    }
    public function CheckFormdataExist($formid,$userid){
        return DB::table('user_last_save_data')->where('form_id',$formid)->where('user_id',$userid)->get();
    }
    public function updateBploBusinessData($id,$columns){
        return DB::table('bplo_business')->where('id',(int)$id)->update($columns);
    }
    public function getLineOfBusiness($retireid=0){
        return DB::table('bplo_business_retirement_psic AS bbrp')
            ->leftjoin('psic_subclasses AS ps', 'ps.id', '=', 'bbrp.subclass_id')
            ->select('subclass_description','bbrp.subclass_id','subclass_code')->where('bbrp.busnret_id',$retireid)->orderBy('subclass_description','ASC')->get()->toArray();
    }

    public function getEditDetails($id){
       return DB::table('bplo_business_retirement_issuance AS bbri')->leftjoin('bplo_business_retirement as bbr','bbri.retire_id','=','bbr.id')->leftjoin('bplo_business as bb','bbri.busn_id','=','bb.id')->leftjoin('clients AS c', 'c.id', '=', 'bb.client_id')->select('bbr.id as retireid','bb.busns_id_no','bbri.id','bbri.busn_id','bbr.retire_documentary_json','bbri.bri_remarks','bbr.retire_date_start','bbr.retire_date_closed','bb.busn_name','bbri.bri_issued_date','bbri.status','bbri.bri_issued_by','bbri.bri_issued_position','bbri.bri_upload_documents_json','bbri.bri_retirement_certificate_name','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','c.rpo_address_house_lot_no','bbr.busn_id','bbr.retire_year','bbri.status')->where('bbri.id',$id)->first();
    }

    public function updateIssuanceData($id,$columns){
        return DB::table('bplo_business_retirement_issuance')->where('id',$id)->update($columns);
    }

    public function getEmployeeDetails($id){
       return DB::table('hr_employees')->select('fullname','suffix','emp_ptr_no','emp_issue_date','emp_issue_at','emp_prc_validity','tin_no','c_house_lot_no','c_street_name','c_subdivision','c_brgy_code','c_region')->where('id',$id)->get()->first();
    }

    public function getRetirementDetails($busn_id,$year){
        return   DB::table('bplo_business_retirement as bbr')->leftjoin('bplo_business_retirement_issuance AS bbri','bbri.retire_id','=','bbr.id')
          ->leftJoin('bplo_business as bb','bbr.busn_id','=','bb.id')->Join('clients AS c', 'c.id', '=', 'bb.client_id')
          ->select('bbr.id','bb.busn_name','bbr.retire_date_closed','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','bri_issued_by','bbri.bri_retirement_certificate_name','bri_issued_position','bri_issued_date','bbri.id as certificateid','bb.busn_office_main_barangay_id')->where('bbr.busn_id',$busn_id)->where('retire_year',(int)$year)->first();
    }

    public function getDetailofEngDefault($id){
         return DB::table('cto_cashier_details_eng_occupancy')
          ->select('fees_description','tfc_amount')->where('cashier_id',$id)->get();
    }

    public function getEmployee(){
         return DB::table('hr_employees')->select('id','fullname','suffix')->get();
    }

    public function getAccountGeneralLeaderbyid($id,$glid){
        return DB::table('acctg_account_subsidiary_ledgers  AS aasl')->join('acctg_account_general_ledgers as aagl', 'aasl.gl_account_id', '=', 'aagl.id')->select('aasl.id','aagl.code','aagl.description as gldescription','aasl.prefix','aasl.description')->where('aagl.is_active',1)->where('aasl.is_parent',0)->where('aasl.is_hidden',0)->where('aasl.is_active',1)->where('aasl.id',$id)->where('aasl.gl_account_id',$glid)->first();
    }

    public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $startdate =$request->input('fromdate');
    $enddate = $request->input('todate');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"cc.id",
      1 =>"bb.busns_id_no",
      2 =>"c.full_name",
      3 =>"bb.busn_name",
      4 =>"bbri.bri_remarks",	
      5 =>"bbr.retire_date_start",
      6=>'bbr.retire_date_closed',
      7=>'bbri.bri_issued_date',
      9=>'bbri.status',
     
    );

    $sql = DB::table('bplo_business_retirement_issuance AS bbri')
		  ->leftjoin('bplo_business_retirement as bbr','bbri.retire_id','=','bbr.id')
   		  ->leftjoin('bplo_business as bb','bbri.busn_id','=','bb.id')
		  ->leftjoin('clients AS c', 'c.id', '=', 'bb.client_id')
          ->select('bb.busns_id_no','bbri.id','bbri.bri_remarks','bbr.retire_date_start','bbr.retire_date_closed','bb.busn_name','bbri.bri_issued_date','bbri.status','c.full_name','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','c.rpo_address_house_lot_no');
    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
	 if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(bb.busns_id_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw("CONCAT(c.rpo_first_name, ' ', COALESCE(c.rpo_middle_name, ''), ' ', COALESCE(c.rpo_custom_last_name))"), 'LIKE', "%{$q}%")
					->orWhere(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbri.bri_remarks)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbr.retire_date_start)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbr.retire_date_closed)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbri.bri_issued_date)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbri.status)'),'like',"%".strtolower($q)."%")
					;
			});
		}
	
 		if(!empty($startdate) && isset($startdate)){
            $sdate = explode('-', $startdate);
            $startdate = $sdate[2]."-".$sdate[1]."-".$sdate[0]; 
            $startdate = date('Y-m-d',strtotime($startdate)); 
            $sql->whereDate('cc.created_at','>=',trim($startdate));  
        }
        if(!empty($enddate) && isset($enddate)){
            $edate = explode('-', $enddate);
            $enddate = $edate[2]."-".$edate[1]."-".$edate[0]; 
            $enddate = date('Y-m-d',strtotime($enddate)); 
            $sql->whereDate('cc.created_at','<=',trim($enddate));  
        }
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('bbri.id','DESC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
}
