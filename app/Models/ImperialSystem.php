<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ImperialSystem extends Model
{
    use HasFactory;
    protected $table = "cpdo_imperial_system";
    protected $guarded;

    public function addData($request){
       return Self::create($request);
    }

    public function updateData($id, $request){
        return Self::find($id)->update($request);
     }

    public function getList($request){
        try {
            $params = $columns = $totalRecords = $data = array();
            $params = $_REQUEST;
            $q=$request->input('q');

            if(!isset($params['start']) && !isset($params['length'])){
                $params['start']="0";
                $params['length']="10";
            }

            $columns = array( 
                0 =>"id",
                1 =>"cis_code",
                2 =>"cis_imperial_system",
                3 =>"is_active",
            );
            
            $sql = DB::table('cpdo_imperial_system');
            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(cis_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cis_imperial_system)'),'like',"%".strtolower($q)."%"); 
                });
            }
            /*  #######  Set Order By  ###### */
            if(isset($params['order'][0]['column']))
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
            else
            $sql->orderBy('created_at','DESC');

            /*  #######  Get count without limit  ###### */
            $data_cnt=$sql->count();
            /*  #######  Set Offset & Limit  ###### */
            $sql->offset((int)$params['start'])->limit((int)$params['length']);
            $data=$sql->get();
            return array("data_cnt"=>$data_cnt,"data"=>$data);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getSingleData($id){
        return Self::find($id);
    }

    public function updateActiveInactive($id,$columns){
        return DB::table('cpdo_imperial_system')->where('id',$id)->update($columns);
    }
}
