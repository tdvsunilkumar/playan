<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Barangay;
class HeSaFamilyPlanningModel extends Model
{
	public function __construct() 
    {   
        date_default_timezone_set('Asia/Manila');
        $this->_Barangay = new Barangay();
    }
    public $table = 'ho_fam_plan';
    
    public function updateActiveInactive($id,$columns){
     return DB::table('ho_fam_plan')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        unset($columns['id']);
        // dd($id);
        return DB::table('ho_fam_plan')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('ho_fam_plan')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('ho_fam_plan')->where('fam_ref_id',$id)->first();
    }

    public function getPartnersDetails($id){
        return DB::table('ho_fam_plan')->leftJoin('citizens AS cit', 'cit.id', '=', 'ho_fam_plan.cit_id')->select('ho_fam_plan.id as partner_id', 'cit_id')->where('fam_ref_id',$id)->get();
    }
	
	 public function getPhysician(){
        return DB::table('ho_fam_plan')->select('*')->get();
    }
	public function getcitizensRefresh(){
        return DB::table('citizens')->select('id','cit_first_name','cit_middle_name','cit_last_name')->get();
    }
	public function getCitizens(){
        return DB::table('citizens')->select('*')->get();
    }
	
	public function getCitizensname($id){
      return DB::table('citizens as ci')
	  ->where('ci.id',$id)
	   ->join('barangays AS bar', 'bar.id', '=', 'ci.brgy_id')
	  ->select('ci.cit_age As cit_age','ci.cit_gender As cit_gender','ci.brgy_id','bar.brgy_name As brgy_name','ci.cit_house_lot_no','ci.cit_street_name','ci.cit_subdivision')
	  ->first();
    }
	public function getBarangay(){
        //return DB::table('barangays')->select('id','brgy_code','brgy_name')->get();
        return DB::table('barangays AS bgf')
              ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
              ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','brgy_code','bgf.is_active')->where('bgf.is_active',1)->get();
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
          0 =>"id",
		  1 =>"fam_ref_id",
		  2 =>"cit_id", 
	      3 =>"age",
	      4 =>"fam_is_active"
        );
       // $getFullName = 'CONCAT(cit.cit_first_name,' ',cit.cit_middle_name,' ',cit.cit_last_name)';

        $sql = DB::table('ho_fam_plan as hofp')
              ->join('citizens AS cit', 'cit.id', '=', 'hofp.cit_id')
              //->leftjoin('barangays', 'barangays.id', '=', 'hofp.brgy_id')
              ->select(
                'hofp.*',
                'cit.cit_first_name',
                'cit.cit_middle_name',
                'cit.cit_last_name',
                'cit.cit_gender',
                'cit.cit_age',
                //'barangays.brgy_name',
                DB::raw('GROUP_CONCAT(CONCAT(cit.cit_first_name," ",cit.cit_middle_name," ",cit.cit_last_name) SEPARATOR "<br />") as allNames'),
                DB::raw('GROUP_CONCAT(cit.cit_gender SEPARATOR " ") as allGender'),
                DB::raw('GROUP_CONCAT(cit.cit_age SEPARATOR "<br />") as allAge')
                
                
                
            );
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(fam_ref_id)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(pt_date)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cit.cit_first_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cit.cit_middle_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cit.cit_last_name)'),'like',"%".strtolower($q)."%")
                ; 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('hofp.fam_ref_id','DESC')
        ;

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->distinct('fam_ref_id')->count('fam_ref_id');
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->groupby('hofp.fam_ref_id')->get();
        
        return array("data_cnt"=>$data_cnt,"data"=>$data);
		
      }
	  
	  public function getTaxPayerAddress($id=0){
		
        $fullAddress ="";
        $arr =  DB::table('ho_fam_plan')->select('house_lot_no','street_name','subdivision','brgy_id')->where('id',$id)->first();
        if(isset($arr)){
			$address =(!empty($arr->house_lot_no) ? $arr->house_lot_no. ',' : '') . (!empty($arr->street_name) ? $arr->street_name. ',' : '') . (!empty($arr->subdivision) ? $arr->subdivision . ',' : '');
            $barngayAddress = $this->_Barangay->findDetails($arr->brgy_id);
            $fullAddress = $address.', '.$barngayAddress;
            $fullAddress = preg_replace('/,+/', ', ', $fullAddress);
            $fullAddress = trim($fullAddress,", ");
            $fullAddress = str_replace(', ', ', ', $fullAddress);
        }
        return $fullAddress;
    }
	  
}
