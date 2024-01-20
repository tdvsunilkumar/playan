<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class RptPlantTressUnitValue extends Model
{
    public function updateActiveInactive($id,$columns){
        return DB::table('rpt_plant_tress_unit_values')->where('id',$id)->update($columns);
      }  
    public function updateData($id,$columns){
        return DB::table('rpt_plant_tress_unit_values')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('rpt_plant_tress_unit_values')->insert($postdata);
    }
    public function getLocal(){
        return DB::table('profile_municipalities')->select('id','mun_no','mun_desc')->where('mun_display_for_rpt',1)->where('is_active',1)->get();
    }
    public function getBrgy(){
        return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('brgy_display_for_rpt',1)->where('is_active',1)->get();
    }
    public function plantTreeAjaxRequest($request){
        $term=$request->input('term');
        $query = DB::table('rpt_plant_tress')->select('id','pt_ptrees_code','pt_ptrees_description',DB::raw('CONCAT(pt_ptrees_code,"-",pt_ptrees_description) as text'))->where('is_active',1);
                           
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(pt_ptrees_description)'),'like',"%".strtolower($term)."%");
                $sql->orWhere(DB::raw('LOWER(pt_ptrees_code)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;
    }

    public function classSubAjaxRequest($request){
        $term=$request->input('term');
        $query = DB::table('rpt_property_subclassifications AS rpsc')
        ->join('rpt_property_classes AS rpc', 'rpc.id', '=', 'rpsc.pc_class_code')
        ->select('rpsc.id',DB::raw('CONCAT("[",rpc.pc_class_code,"-",rpc.pc_class_description,"=>",rpsc.ps_subclass_code,"-",rpsc.ps_subclass_desc,"]") as text'))->where('rpsc.ps_is_active',1);
                           
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(rpc.pc_class_code)'),'like',"%".strtolower($term)."%");
                $sql->orWhere(DB::raw('LOWER(rpc.pc_class_description)'),'like',"%".strtolower($term)."%");
                $sql->orWhere(DB::raw('LOWER(rpsc.ps_subclass_code)'),'like',"%".strtolower($term)."%");
                $sql->orWhere(DB::raw('LOWER(rpsc.ps_subclass_desc)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;
    }
    public function getBrgyId(){
        $municipaliti=DB::table('profile_municipalities')->select('id')->where('mun_display_for_rpt',1)->where('is_active',1)->first();
        if(!empty($municipaliti))
        {
          $rpt_locality=DB::table('rpt_locality')->select('loc_group_default_barangay_id')->where('mun_no',$municipaliti->id)->first();
          if($rpt_locality->loc_group_default_barangay_id != null)
          {
            $brgy_id=$rpt_locality->loc_group_default_barangay_id;
          }
          else{
            $brgy_id=null;
          }
        }
        else{
          $brgy_id=null;
        }
        return $brgy_id;
    }
    public function getPlantTressCode(){
        return DB::table('rpt_plant_tress')->select('id','pt_ptrees_code','pt_ptrees_description')->where('is_active',1)->get();

    }
    public function getRevisionDefult(){
        return DB::table('rpt_revision_year')->select('id','rvy_revision_year','rvy_revision_code')->where('is_active',1)->where('is_default_value',1)->get();
    }
    
    public function getYear(){
        return DB::table('rpt_revision_year')->select('id','rvy_revision_year','rvy_revision_code')->get();

    }
    
     public function getRevisionyears(){
        return DB::table('rpt_plant_tress_unit_values AS ut')->join('rpt_revision_year AS year', 'year.id', '=', 'ut.rvy_revision_year')->select('year.id','year.rvy_revision_year','year.rvy_revision_code')->groupBy('ut.rvy_revision_year')->get();
    }
    
    public function getRptClass(){
        return DB::table('rpt_property_classes')->select('id','pc_class_code','pc_class_description')->where('pc_is_active',1)->get();
    }
    public function getsubclass($id){
        return DB::table('rpt_property_subclassifications')
        ->select('id','ps_subclass_code','ps_subclass_desc')->where('ps_is_active',1)->where('pc_class_code','=',$id)->get();
    }
    public function getRptSubClass(){
         return DB::table('rpt_property_subclassifications AS rpsc')
        ->join('rpt_property_classes AS rpc', 'rpc.id', '=', 'rpsc.pc_class_code')
        ->select('rpsc.id','rpsc.ps_subclass_code','rpsc.ps_subclass_desc','rpc.pc_class_code','rpc.pc_class_description')->where('rpsc.ps_is_active',1)->get();
    }
    public function getSubClassDetailss($id){
        return DB::table('rpt_property_subclassifications AS rpsc')
        ->join('rpt_property_classes AS rpc', 'rpc.id', '=', 'rpsc.pc_class_code')
        ->select('rpsc.id','rpsc.ps_subclass_code','rpsc.ps_subclass_desc','rpc.pc_class_code','rpsc.pc_class_code AS pc_class_id','rpc.pc_class_description')->where('rpsc.ps_is_active',1)->where('rpsc.id',(int)$id)->first();
    }
    
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $revisionyear = $request->input('revisionyear');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }



        $columns = array( 
          0 =>"id",
          1 =>"pt_ptrees_code", 
          2 =>"pc_class_code", 
          3 =>"ps_subclass_code",  
          4 =>"rvy_revision_year", 
          5 =>"ptuv_unit_value",
          6 =>"is_approve",
          7 =>"ptuv_is_active",
        );
         if(!empty($revisionyear)){
            $sql = DB::table('rpt_plant_tress_unit_values AS ptuv')
               ->join('rpt_plant_tress AS rpt', 'rpt.id', '=', 'ptuv.pt_ptrees_code')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'ptuv.pc_class_code')
               ->join('rpt_property_subclassifications AS sub', 'sub.id', '=', 'ptuv.ps_subclass_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'ptuv.rvy_revision_year')
               ->select('ptuv.id','rpt.pt_ptrees_code','rpt.pt_ptrees_description','class.pc_class_code','class.pc_class_description','sub.ps_subclass_code','sub.ps_subclass_desc','year.rvy_revision_year','year.rvy_revision_code','ptuv.ptuv_unit_value','ptuv.ptuv_is_active','ptuv.is_approve')->where('ptuv.rvy_revision_year',$revisionyear);
        } else{
            $sql = DB::table('rpt_plant_tress_unit_values AS ptuv')
               ->join('rpt_plant_tress AS rpt', 'rpt.id', '=', 'ptuv.pt_ptrees_code')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'ptuv.pc_class_code')
               ->join('rpt_property_subclassifications AS sub', 'sub.id', '=', 'ptuv.ps_subclass_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'ptuv.rvy_revision_year')
               ->select('ptuv.id','rpt.pt_ptrees_code','rpt.pt_ptrees_description','class.pc_class_code','class.pc_class_description','sub.ps_subclass_code','sub.ps_subclass_desc','year.rvy_revision_year','year.rvy_revision_code','ptuv.ptuv_unit_value','ptuv.ptuv_is_active','ptuv.is_approve')->where('year.is_active',1)->where('year.is_default_value',1);

        }
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(class.pc_class_code)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw("CONCAT(class.pc_class_code,'-',class.pc_class_description)"), 'LIKE', "%{$q}%")
                ->orWhere(DB::raw("CONCAT(rpt.pt_ptrees_code,'-',rpt.pt_ptrees_description)"), 'LIKE', "%{$q}%")
                ->orWhere(DB::raw("CONCAT(sub.ps_subclass_code,'-',sub.ps_subclass_desc)"), 'LIKE', "%{$q}%")
                ->orWhere(DB::raw("CONCAT(year.rvy_revision_year,'-',year.rvy_revision_code)"), 'LIKE', "%{$q}%")
                ->orWhere(DB::raw('LOWER(rpt.pt_ptrees_code)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(rpt.pt_ptrees_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(class.pc_class_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(sub.ps_subclass_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(sub.ps_subclass_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(year.rvy_revision_year)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(year.rvy_revision_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ptuv.ptuv_unit_value)'),'like',"%".strtolower($q)."%");
                    
            });
        }



        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('ptuv.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}



