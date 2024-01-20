<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RptPropertyActualUse extends Model
{
  public $table = 'rpt_property_actual_uses';
    use HasFactory;

    protected $fillable = [
        'pc_class_code',
        'pau_actual_use_code',
        'pau_actual_use_desc',
        'pau_with_land_stripping',
        'pau_is_active',
        'pau_registered_by',
        'pau_modified_by'
    ];

    public function updateActiveInactive($id,$columns){
      return DB::table('rpt_property_actual_uses')->where('id',$id)->update($columns);
    }  
    
    public function updateData($id,$columns){
        return DB::table('rpt_property_actual_uses')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('rpt_property_actual_uses')->insert($postdata);
    }
    public function getRptActualDetails($id){
        return DB::table('rpt_property_actual_uses')->select('*')->where('pc_class_code',$id)->get()->toArray();
    }
    public function rptPropClass(){
      return DB::table('rpt_property_classes')->select('id','pc_class_code','pc_class_description')->get();
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
          0 => "id",
          1 => "pc_class_code",  
          2 => "pau_actual_use_code",
          3 => "pau_actual_use_desc",
          4 => "pau_with_land_stripping",
          5 => "pc_class_description",
          // 5 => "pau_is_active"
         );
          $sql = DB::table('rpt_property_actual_uses AS bgf')
                 ->join('rpt_property_classes AS rptc', 'rptc.id', '=', 'bgf.pc_class_code')
                 ->select('bgf.id','rptc.pc_class_description','bgf.pau_actual_use_code','bgf.pau_actual_use_desc','bgf.pau_with_land_stripping','bgf.pau_is_active', 'rptc.pc_class_code');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(bgf.pau_actual_use_code)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(bgf.pau_actual_use_desc)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(rptc.pc_class_description)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(rptc.pc_class_code)'),'like',"%".strtolower($q)."%"); 
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
