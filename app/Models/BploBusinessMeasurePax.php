<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BploBusinessMeasurePax extends Model
{
    protected $guarded = ['id'];

    public $table = 'bplo_business_measure_pax';
    
    public $timestamps = false;
    public function findByBusnPlanId($id){
        return self::where('busn_psic_id',$id)->first();
    }
    public function remove_measure($id){
        return self::where('id',$id)->delete();
    }
    public function verifyUnique($busn_id,$busn_psic_id,$buspx_charge_id){
        return self::where('busn_psic_id',$busn_psic_id)->where('busn_id',$busn_id)->where('buspx_charge_id',$buspx_charge_id)->count();
    }
    public function find_measure_pax($id)
    { 
        return self::where('id',$id)->first();
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
          0 =>"bbm.buspx_no_units",
          1 =>"bbm.buspx_capacity",
          2 =>"ccd.charge_desc",
          3 =>"psc.subclass_description", 
        );
    
        $sql = DB::table('bplo_business_measure_pax AS bbm')
              ->leftjoin('psic_subclasses AS psc', 'psc.id', '=', 'bbm.subclass_id')
              ->leftjoin('bplo_business AS bb', 'bb.id', '=', 'bbm.busn_id')
              ->leftjoin('bplo_business_psic AS bbp', 'bbp.id', '=', 'bbm.busn_psic_id')
              ->leftjoin('cto_charge_descriptions AS ccd', 'ccd.id', '=', 'bbm.buspx_charge_id')
              ->leftjoin('psic_sections AS ps', 'ps.id', '=', 'psc.section_id')
              ->leftjoin('psic_divisions AS pd', 'psc.division_id', '=', 'pd.id')
              ->leftjoin('psic_groups AS pg', 'psc.group_id', '=', 'pg.id')
              ->leftjoin('psic_classes AS pc', 'psc.group_id', '=', 'pc.id')
              ->select('bbm.id as ID','subclass_description','buspx_no_units','buspx_capacity','charge_desc')
              ->where('bbm.busn_id',$busn_id);
    
        //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
            if(!empty($q) && isset($q)){
                $sql->where(function ($sql) use($q) {
                    $sql->where(DB::raw('LOWER(bbm.buspx_no_units)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(bbm.buspx_capacity)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(ccd.charge_desc)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(psc.subclass_description)'),'like',"%".strtolower($q)."%");

                });
            }
            /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('id','ASC');
    
        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
