<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CemeteryStyle extends Model
{ 
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'eco_cemeteries_style';
    
    public $timestamps = false;
    
    public function updateActiveInactive($id,$columns){
     return DB::table('eco_cemeteries_style')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('eco_cemeteries_style')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('eco_cemeteries_style')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('eco_cemeteries_style')->where('id',$id)->first();
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
          1 =>"eco_cemetery_style",
          2 =>"ecs_status"
           
        );

        $sql = DB::table('eco_cemeteries_style')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(eco_cemetery_style)'),'like',"%".strtolower($q)."%")
                ; 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function allCemeteryStyles()
    {
        $styles = self::where('ecs_status', 1)->orderBy('id', 'asc')->get();
    
        $styz = array();
        $styz[] = array('' => 'select a cemetery style');
        foreach ($styles as $style) {
            $styz[] = array(
                $style->id => $style->eco_cemetery_style
            );
        }

        $styles = array();
        foreach($styz as $sty) {
            foreach($sty as $key => $val) {
                $styles[$key] = $val;
            }
        }

        return $styles;
    }
}
