<?php

namespace App\Models\Bplo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class PdoBploEndosement extends Model
{
    public function updateData($id,$columns){
        return DB::table('pdo_bplo_endosements')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('pdo_bplo_endosements')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function findDataById($id){
      return DB::table('pdo_bplo_endosements')->where('id',$id)->first();
  }
    public function getbploOwners($id){
        return DB::table('clients as c')->select('c.id','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.suffix')->where('c.id','=',(int)$id)->get();
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

    public function getBusinesDetails($id){
    	return DB::table('bplo_business AS bb')->select('id','client_id','busn_name','busn_office_main_barangay_id')->where('id',$id)->first();
    }
    public function gethremployess(){ 
      return DB::table('hr_employees')->select('id','firstname','middlename','lastname','fullname','suffix','title')->where('is_active',1)->get();
    }
	
	  public function getUserhremployess($id){ 
      return DB::table('hr_employees')->select('user_id')->where('id',$id)->where('is_active',1)->first()->user_id;
    }
	
    public function checkcasheringexist($busnid,$bendid){
        return DB::table('bplo_business_endorsement as bbe')->join('cto_cashier_details as ccd','ccd.tfoc_id','=','bbe.tfoc_id')->select('ccd.or_no','ccd.id')->where('bbe.id','=',$bendid)->where('ccd.busn_id','=',$busnid)->get();
    }
	
    public function getORdetails($id){
      //echo "here"; exit;
       return DB::table('cto_cashier_details')->select('cashier_id','or_no','tfc_amount',DB::raw('DATE(created_at) AS created_at'))->where('id',$id)->get()->first();
    }

    public function getpdoendosementexist($bus_id,$bus_end_id,$year=""){
    	 return DB::table('pdo_bplo_endosements AS pbe')->where('busn_id',$bus_id)->where('bend_id',$bus_end_id)->where('pend_year',$year)->first();
    }

    public function getPrintdata($id){
     return DB::table('pdo_bplo_endosements as pbe')
     ->join('clients as c','c.id','=','pbe.client_id')
     ->leftjoin('bplo_business as bb','bb.id','=','pbe.busn_id')
     ->leftjoin('barangays as b','b.id','=','c.p_barangay_id_no')
     ->leftjoin('hr_employees as h','h.id','=','pbe.pend_approved_by')
     ->select('pbe.pend_date','pbe.pend_inspected_by','pbe.pend_inspected_officer_position','pbe.pend_approved_by','pbe.pend_officer_position','h.fullname','bb.busn_name','b.brgy_name','pbe.or_no','pbe.or_date','pbe.or_amount')
     ->where('pbe.id','=',$id)->first();
    }
    public function getPosition($id){
        $data= DB::table('hr_employees')
        ->where('hr_employees.id',$id)
        ->join('hr_designations', 'hr_designations.id', '=', 'hr_employees.hr_designation_id')
        ->select('hr_designations.description')->first();
		return $data;
      } 
	public function getBusinessEndorsementDetailsInspection($busn_id,$bbendo_id,$year=''){
        return DB::table('pdo_bplo_endosements')
		->select('pend_document')
		->where('bend_id',$bbendo_id)
		->where('pend_year',(int)$year)
		->where('busn_id',$busn_id)
		->first();
    }
	public function updateBusinessEndorsement($busn_id,$bbendo_id,$columns,$year=''){
		
        return DB::table('pdo_bplo_endosements')->where('bend_id',$bbendo_id)->where('busn_id',$busn_id)->where('pend_year',(int)$year)->update($columns);
    }
    public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"cs.id",
      1 =>"c.rpo_custom_last_name",
      2 =>"bb.busn_name",
      3 =>"is_active"	
    );

    $sql = DB::table('pdo_bplo_endosements AS lc')
   		  ->join('clients AS c', 'c.id', '=', 'lc.client_id') 
   		  ->leftjoin('bplo_business as bb','c.id','=','bb.client_id')
          ->select('lc.id','bb.busn_name','lc.pend_status','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name');

    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
              ->orWhere(DB::raw('LOWER(lc.busn_name)'),'like',"%".strtolower($q)."%")
              ->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
              ->orWhere(DB::raw('LOWER(c.rpo_middle_name)'),'like',"%".strtolower($q)."%");
			});
		}
		/*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
}
