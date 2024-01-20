<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RptCtoBasicSefTaxrate extends Model
{
  public function updateActiveInactive($id,$columns){
    return DB::table('rpt_cto_taxrates')->where('id',$id)->update($columns);
  }  
    public function updateData($id,$columns){
        return DB::table('rpt_cto_taxrates')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
		return DB::table('rpt_cto_taxrates')->insert($postdata);
	}
    public function getBasicSefTaxrate(){
       return DB::table('rpt_cto_taxrates')->select('*')->get();
    }
    public function editBasicSefTaxrate($id){
        return DB::table('rpt_cto_taxrates')->where('id',$id)->first();
    }

    

    public function getClassCode(){
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
          1 =>"pc_class_code",
          2 =>"bsst_basic_rate",
          3 =>"bsst_sef_rate",
          4 =>"bsst_sh_rate",
          5 =>"rcbst.is_active",
                     
        );

        $sql = DB::table('rpt_cto_taxrates AS rcbst')
              ->join('rpt_property_classes AS p', 'p.id', '=', 'rcbst.pc_class_code')
              ->select('rcbst.id','rcbst.bsst_basic_rate','rcbst.bsst_sef_rate','rcbst.bsst_sh_rate','rcbst.is_active','p.pc_class_code','p.pc_class_description'
             );

       
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->orWhere(DB::raw('LOWER(p.pc_class_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p.pc_class_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rcbst.bsst_basic_rate)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rcbst.bsst_sef_rate)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rcbst.bsst_sh_rate)'),'like',"%".strtolower($q)."%");               
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column'])){
          //dd($columns[$params['order'][0]['column']]);
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }else{
          $sql->orderBy('rcbst.id','ASC');
        }
          
        

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
