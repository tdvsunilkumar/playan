<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RptPropertyKind extends Model
{
    use HasFactory;

    protected $fillable = [
        'pk_code',
        'pk_description',
        'pk_is_active',
        'pk_registered_by',
        'pk_modified_by'
    ];

    public function updateActiveInactive($id,$columns){
      return DB::table('rpt_property_kinds')->where('id',$id)->update($columns);
    }  

    public function updateData($id,$columns){
        return DB::table('rpt_property_kinds')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('rpt_property_kinds')->insert($postdata);
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
          1 => "pk_code",  
          2 => "pk_description",
          3 => "pk_is_active",
         );
         $sql = DB::table('rpt_property_kinds AS bgf')->select('id','pk_code','pk_description','pk_is_active');
        // return DB::table('bplo_system_parameters')->select('locality','name')->get();
        //$sql->where('bgf.created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(pk_code)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(pk_description)'),'like',"%".strtolower($q)."%");    
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
