<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class BfpRequirement extends Model
{
 
    public function updateData($id,$columns){
        return DB::table('bfp_requirements')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('bfp_requirements')->insert($postdata);
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('bfp_requirements')->where('id',$id)->update($columns);
    }
    public function getApplicationType(){
        return DB::table('bfp_application_type')->select('id','btype_name','btype_description')->where('btype_status',1)->get();
    }
    public function getPurpose(){
        return DB::table('bfp_application_purpose')->select('id','bap_desc')->where('bap_status',1)->get();
    }
    public function getCategory(){
        return DB::table('bfp_application_category')->select('id','bac_desc')->where('bac_status',1)->get();
    }
    public function getRequirements(){
        return DB::table('requirements')->select('id','req_description')->where('req_dept_bfp',1)->where('is_active',1)->get();
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
          0 =>"", 
          1 =>"btype_description",
          2 =>"bap_desc",
          3 =>"bac_desc",
          4 =>"req_description",
          5 =>"bac_status"
         );
         $sql = DB::table('bfp_requirements AS r')
              ->join('bfp_application_type AS a', 'a.id', '=', 'r.btype_id')
              ->join('bfp_application_purpose AS p', 'p.id', '=', 'r.bap_id')
              ->join('bfp_application_category AS c', 'c.id', '=', 'r.bac_id')
              ->join('requirements AS req', 'req.id', '=', 'r.req_id')
              ->select('r.id','a.btype_description','p.bap_desc','c.bac_desc','req.req_description','r.bac_status');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(a.btype_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p.bap_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(c.bac_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(req.req_description)'),'like',"%".strtolower($q)."%");
                    
                   
          });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('r.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}

