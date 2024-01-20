<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class RptBuildingExtraItem extends Model
{
    protected $appends = ['standard_item_name'];
    protected $table = 'rpt_building_extra_items';
    public function updateActiveInactive($id,$columns){
      return DB::table('rpt_building_extra_items')->where('id',$id)->update($columns);
    } 

    public function getStandardItemNameAttribute($value=''){
       return $this->bei_extra_item_code.'-'.$this->bei_extra_item_desc;
     } 
    public function updateData($id,$columns){
        return DB::table('rpt_building_extra_items')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('rpt_building_extra_items')->insert($postdata);
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
          1 =>"bei_extra_item_code",
          2 =>"bei_extra_item_desc",  
          3 =>"bei_is_active"
         );
         $sql = DB::table('rpt_building_extra_items AS bgf')->select('*');
        // return DB::table('bplo_system_parameters')->select('locality','name')->get();
        //$sql->where('bgf.created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(bei_extra_item_desc)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(bei_extra_item_code)'),'like',"%".strtolower($q)."%");
                    
                   
                    
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('bgf.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
