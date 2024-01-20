<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Locality extends Model
{
  public $table = 'rpt_locality';

  public function mun(){
        return $this->belongsTo(ProfileMunicipality::class,'mun_no');
    }

  public function tresurer(){
        return $this->belongsTo(HrEmployee::class,'loc_treasurer_id');
    } 
  public function assessor(){
        return $this->belongsTo(HrEmployee::class,'loc_assessor_id');
    }   

  public function updateActiveInactive($id,$columns){
    return DB::table('rpt_locality')->where('id',$id)->update($columns);
  }  
  public function updateData($id,$columns){
        return DB::table('rpt_locality')->where('id',$id)->update($columns);
  }
  public function addData($postdata){
		 DB::table('rpt_locality')->insert($postdata);
    return DB::getPdo()->lastInsertId();

	}
    public function getLocality(){
       return DB::table('rpt_locality')->select('*')->get();
    }
    public function editLocality($id){
        return DB::table('rpt_locality')->where('id',$id)->first();
     }
    public function getHrEmployeeCode(){
        return DB::table('hr_employees')->select('id','firstname','middlename','lastname')->get();
    }
    public function getMunId(){
        return DB::table('profile_municipalities')->select('id','mun_no','mun_desc')->where('is_active',1)->where('mun_display_for_rpt',1)->get();
    }

     public function bploLocality($vars = '')
    {
      return self::where('department', 2)->first();
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
          0 =>"firstname",  
          1 =>"middlename",
          2 =>"lastname",
          3 =>"p1firstname",
          4 =>"p1middlename",
          5 =>"p1lastname",
          6 =>"p2firstname",
          7 =>"p2middlename",
          8 =>"p2lastname",
          9 =>"p3firstname",
          10 =>"p3middlename",
          11 =>"p3lastname",
          12 =>"p4firstname",
          13 =>"p4middlename",
          14 =>"p4lastname",
          15 =>"p5firstname",
          16 =>"p5middlename",
          17 =>"p5lastname",
          18 =>"p6firstname",
          19 =>"p6middlename",
          20 =>"p6lastname",
          21 =>"loc_local_name",
          22 =>"loc_address",
          23 =>"loc_telephone_no",
          24 =>"loc_fax_no",
          25 =>"loc_budget_officer_position",
          26 =>"loc_treasurer_position",
          27 =>"loc_assessor_position",
          28 =>"loc_chief_land_tax_position",
          29=>"loc_assessor_assistant_position",
          31=>"loc_local_code",
          32=>"mun_no",
          33=>"mun_desc",
          // 30 =>"is_active"
           
        );
        
        
        
        // $sql = DB::table('rpt_locality')
        $sql = DB::table('rpt_locality AS rpt')
              ->join('hr_employees AS p', 'p.id', '=', 'rpt.loc_mayor_id')
              ->join('hr_employees AS p1', 'p1.id', '=', 'rpt.loc_administrator_id')
              ->join('hr_employees AS p2', 'p2.id', '=', 'rpt.loc_budget_officer_id')
              ->join('hr_employees AS p3', 'p3.id', '=', 'rpt.loc_treasurer_id')
              ->join('hr_employees AS p4', 'p4.id', '=', 'rpt.loc_chief_land_id')
              ->join('hr_employees AS p5', 'p5.id', '=', 'rpt.loc_assessor_id')
              ->join('hr_employees AS p6', 'p6.id', '=', 'rpt.loc_assessor_assistant_id')
              ->join('profile_municipalities AS mu', 'mu.id', '=', 'rpt.mun_no')
              ->select('rpt.id','p.firstname','p.middlename','p.lastname','p1.firstname as p1firstname','p1.middlename as p1middlename','p1.lastname as p1lastname','p2.firstname as p2firstname','p2.middlename as p2middlename','p2.lastname as p2lastname','p3.firstname as p3firstname','p3.middlename as p3middlename','p3.lastname as p3lastname','p4.firstname as p4firstname','p4.middlename as p4middlename','p4.lastname as p4lastname','p5.firstname as p5firstname','p5.middlename as p5middlename','p5.lastname as p5lastname','p6.firstname as p6firstname','p6.middlename as p6middlename','p6.lastname as p6lastname','rpt.loc_local_code','rpt.loc_local_name','rpt.loc_address','rpt.loc_telephone_no','rpt.loc_fax_no','rpt.loc_budget_officer_position','rpt.loc_treasurer_position','rpt.loc_chief_land_tax_position','rpt.loc_assessor_position','rpt.loc_assessor_assistant_position','rpt.is_active','mu.mun_no','mu.mun_desc','rpt.asment_id');
              



        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(p.firstname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(mu.mun_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(mu.mun_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p.middlename)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p.lastname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p1.firstname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p1.middlename)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p1.lastname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p2.firstname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p2.middlename)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p2.lastname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p3.firstname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p3.middlename)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p3.lastname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p4.firstname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p4.middlename)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p4.lastname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p5.firstname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p5.middlename)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p5.lastname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p6.firstname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p6.middlename)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p6.lastname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.loc_local_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.loc_local_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.loc_address)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.loc_telephone_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.loc_fax_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.loc_budget_officer_position)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.loc_treasurer_position)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.loc_chief_land_tax_position)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.loc_assessor_position)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.loc_assessor_assistant_position)'),'like',"%".strtolower($q)."%");  
                    
                                
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

    

