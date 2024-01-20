<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class RptPropertySubclassification extends Model
{
  public $table = 'rpt_property_subclassifications';
    public function updateActiveInactive($id,$columns){
      return DB::table('rpt_property_subclassifications')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('rpt_property_subclassifications')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('rpt_property_subclassifications')->insert($postdata);
    }
    public function getRptClass(){
        return DB::table('rpt_property_classes')->select('id','pc_class_description','pc_class_code')->where('pc_is_active',1)->get();
    }
    public function getRptSubclassDetails($id){
        return DB::table('rpt_property_subclassifications')->select('*')->where('pc_class_code',$id)->get()->toArray();
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
          1 =>"pc_class_code", 
          2 =>"ps_subclass_code", 
          3 =>"ps_subclass_desc",  
          4 =>"ps_is_for_plant_trees", 
          5 =>"ps_is_active", 
         );
         $sql = DB::table('rpt_property_subclassifications AS sub')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'sub.pc_class_code')
               ->select('sub.id','class.pc_class_code','class.pc_class_description','sub.ps_subclass_code','sub.ps_subclass_desc','sub.ps_is_for_plant_trees','sub.ps_is_active');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(class.pc_class_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(class.pc_class_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ps_subclass_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ps_subclass_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ps_is_for_plant_trees)'),'like',"%".strtolower($q)."%");
                    
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('sub.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}


