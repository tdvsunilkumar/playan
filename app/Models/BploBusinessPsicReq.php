<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BploBusinessPsicReq extends Model
{
    protected $guarded = ['id'];

    public $table = 'bplo_business_psic_req';
    
    public $timestamps = false;
    
    public function remove_doc($id){
        return self::where('id',$id)->delete();
    }
    public function find($id){
      return self::where('id',$id)->first();
  }
    public function findByBusnPlanId($id){
      return self::where('busn_psic_id',$id)->first();
  }
    public function getList($request,$busn_id)
    {
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
    
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
    
        $columns = array( 
          0 =>"ps.subclass_description",
          1=>"bbpr.attachment",
          2 =>"req.req_description" 
        );
    
        $sql = DB::table('bplo_business_psic_req AS bbpr')
              ->join('requirements AS req', 'req.id', '=', 'bbpr.req_code')
              ->leftjoin('bplo_business_psic AS bbp', 'bbp.id', '=', 'bbpr.busn_psic_id')
              ->leftjoin('psic_subclasses AS ps', 'ps.id', '=', 'bbp.subclass_id')
              ->select('bbpr.id as ID','attachment','req.req_description','ps.subclass_description')
              ->where('bbpr.busn_id',$busn_id);

            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(bbpr.attachment)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(req.req_description)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(ps.subclass_description)'),'like',"%".strtolower($q)."%");
                });
            }
            /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else
        {
          $sql->orderBy('id','ASC');
        }
    
        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
