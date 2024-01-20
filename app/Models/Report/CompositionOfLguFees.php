<?php

namespace App\Models\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class CompositionOfLguFees extends Model
{
    public $table = 'psic_subclasses';
	

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
          1 =>"subclass_description",
           
        );

        $sql = DB::table('psic_subclasses As ps')
			  ->join('psic_tfocs AS pt','pt.subclass_id','=','ps.id')
			  ->join('cto_charge_types AS cct','cct.id','=','pt.ctype_id')
			  ->join('bplo_application_type AS bat','bat.id','=','pt.app_code')
              ->select('ps.id','ps.subclass_description','cct.ctype_desc As ctype_desc','bat.app_type As app_type','pt.ptfoc_effectivity_date As ptfoc_effectivity_date')->where('pt.ptfoc_is_active',1);
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(subclass_description)'),'like',"%".strtolower($q)."%")
                ; 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('ps.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
