<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;


class RptBuildingKind extends Model
{

    protected $table = 'rpt_building_kinds';

    protected $appends = ['building_kind_standard_name'];

    public function getbuildingKindStandardNameAttribute($value=''){
      return $this->bk_building_kind_code.'-'.$this->bk_building_kind_desc;
    }

    public function updateActiveInactive($id,$columns){
      return DB::table('rpt_building_kinds')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('rpt_building_kinds')->where('id',$id)->update($columns);
    }
    public function addData($postdata){

        return DB::table('rpt_building_kinds')->insert($postdata);
    }

    public function allBuildKinds($vars = '')
    {
        $kinds = self::where('bk_is_active', 1)->orderBy('id', 'asc')->get();
    
        $deps = array();
        foreach ($kinds as $kind) {
            $deps[] = array(
                $kind->id => $kind->bk_building_kind_desc
            );
        }

        $kinds = array();
        foreach($deps as $dep) {
            foreach($dep as $key => $val) {
                $kinds[$key] = $val;
            }
        }

        return $kinds;
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
          1 =>"bk_building_kind_code",
          2 =>"bk_building_kind_desc",  
          3 =>"bk_is_active",
          // 4 =>"active|inactive"

         );
         $sql = DB::table('rpt_building_kinds AS bgf')->select('*');
       
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(bk_building_kind_code)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(bk_building_kind_desc)'),'like',"%".strtolower($q)."%");
            });
        }

        
      
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else

          $sql->orderBy('bgf.id','ASC');

          $sql->orderBy('id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
