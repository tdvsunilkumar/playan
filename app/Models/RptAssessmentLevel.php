<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RptAssessmentLevel extends Model
{

  protected $table = 'rpt_assessment_levels';

  public function assessementRelations(){
        return $this->hasMany(RptAssessmentLevelsRelation::class,'assessment_id');
    }

  public function updateActiveInactive($id,$columns){
    return DB::table('rpt_assessment_levels')->where('id',$id)->update($columns);
  }  
      public function updateData($id,$columns){
        return DB::table('rpt_assessment_levels')->where('id',$id)->update($columns);
    }
      public function addData($postdata){
		           DB::table('rpt_assessment_levels')->insert($postdata);
               return DB::getPdo()->lastInsertId();
	  }
      public function getpkCode(){
        return DB::table('rpt_property_kinds')->select('id','pk_code','pk_description')->where('pk_is_active',1)->get();
    }
    public function getRptClass($request){
        $term=$request->input('term');
        $query = DB::table('rpt_property_classes')->select('id','pc_class_code','pc_class_description',DB::raw('CONCAT(pc_class_code,"-",pc_class_description) as text'))->where('pc_is_active',1);
                           
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(pc_class_description)'),'like',"%".strtolower($term)."%");
                $sql->orWhere(DB::raw('LOWER(pc_class_code)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;
    }
    public function getRptSubClass($request){
        $term=$request->input('term');
        $query = DB::table('rpt_property_actual_uses AS rpsc')
                  ->join('rpt_property_classes AS rpc', 'rpc.id', '=', 'rpsc.pc_class_code')
                  ->select('rpsc.id',DB::raw('CONCAT("[",rpc.pc_class_code,"-",rpc.pc_class_description,"=>",rpsc.pau_actual_use_code,"-",rpsc.pau_actual_use_desc,"]") as text'))->where('rpsc.pau_is_active',1);
                           
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(rpc.pc_class_code)'),'like',"%".strtolower($term)."%");
                $sql->orWhere(DB::raw('LOWER(rpc.pc_class_description)'),'like',"%".strtolower($term)."%");
                $sql->orWhere(DB::raw('LOWER(rpsc.pau_actual_use_desc)'),'like',"%".strtolower($term)."%");
                $sql->orWhere(DB::raw('LOWER(rpsc.pau_actual_use_code)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;          
    }
    
    public function getRevisionDefult(){
        return DB::table('rpt_revision_year')->select('id','rvy_revision_year','rvy_revision_code')->where('is_active',1)->where('is_default_value',1)->get();
    }
    
    public function getRevisionyears(){
        return DB::table('rpt_plant_tress_unit_values AS ut')->join('rpt_revision_year AS year', 'year.id', '=', 'ut.rvy_revision_year')->select('year.id','year.rvy_revision_year','year.rvy_revision_code')->groupBy('ut.rvy_revision_year')->get();
    }
    public function getClassDetailss($id){
        return DB::table('rpt_property_classes')->select('id','pc_class_code','pc_class_description',)->where('pc_is_active',1)->where('id',(int)$id)->first();
    }
    public function getSubClassDetailss($id){
       return DB::table('rpt_property_actual_uses AS rpsc')
                  ->join('rpt_property_classes AS rpc', 'rpc.id', '=', 'rpsc.pc_class_code')
                  ->select('rpsc.id','rpc.pc_class_code','rpc.pc_class_description','rpsc.pau_actual_use_code','rpsc.pau_actual_use_desc','rpc.id AS pc_class_id')->where('rpsc.pau_is_active',1)->where('rpsc.id',(int)$id)->first();
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
    public function getActualCode(){
        return DB::table('rpt_property_actual_uses')->select('id','pau_actual_use_code','pau_actual_use_desc')->where('pau_is_active',1)->get();
    }
    
     public function getLocal(){
        return DB::table('profile_municipalities')->select('id','mun_no','mun_desc')->where('mun_display_for_rpt',1)->where('is_active',1)->get();
    }
    public function getBrgy(){
        return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('brgy_display_for_rpt',1)->where('is_active',1)->get();
    }

    public function updateAssRelationData($id,$columns){
        return DB::table('rpt_assessment_levels_relations')->where('id',$id)->update($columns);
    }
    public function getAssRelation($id){
        return DB::table('rpt_assessment_levels_relations')->where('assessment_id',$id)->get()->toArray();
    }
    
    public function checkAssRequietExit($id){
        return DB::table('rpt_assessment_levels_relations')->select('id')->where('id',(int)$id)->get()->toArray();
    }

    
    public function UpdateAssRequietExit($id){
        return DB::table('rpt_assessment_levels_relations')->select('*')->where('assessment_id',$id)->get()->toArray();
    }
    public function getAssmentDetails($id){
       return DB::table('rpt_assessment_levels AS r')
       ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'r.pk_code')
       ->join('rpt_property_classes AS c', 'c.id', '=', 'r.pc_class_code')
       ->join('rpt_revision_year AS ry', 'ry.id', '=', 'r.rvy_revision_year')
       ->leftjoin('rpt_property_actual_uses AS ac', 'ac.id', '=', 'r.pau_actual_use_code')
       ->select('ry.rvy_revision_code','ry.rvy_revision_year','ac.pau_actual_use_code','ac.pau_actual_use_desc','pk.pk_code','pk.pk_description','c.pc_class_code','c.pc_class_description','r.pk_code as pkcode')->where('r.id',$id)->get()->toArray();
    }
    public function getAssRequiet($id){
       return DB::table('rpt_assessment_levels_relations AS brr')
       ->join('rpt_assessment_levels AS r', 'r.id', '=', 'brr.assessment_id')
       ->select('*','brr.id AS relationId')->where('assessment_id',$id)->get()->toArray();
       // return DB::table('bplo_requirement_relations AS bgf')->select('*')->where('bplo_requirement_id',$id)->get();
    }
    
    public function addAssRelationData($postdata){
         return DB::table('rpt_assessment_levels_relations')->insert($postdata);
        // return DB::getPdo()->lastInsertId();
    }
    public function getRevision(){
        return DB::table('rpt_revision_year')->select('id','rvy_revision_year','rvy_revision_code')->get();
    }


 public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $revisionyear = $request->input('revisionyear');
        $kind = $request->input('kind');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"id",
          1 =>"pk_code",
          2 =>"pc_class_code",
          3 =>"pau_actual_use_code",  
          4 =>"rvy_revision_year",
          5 => "is_default_value",
          6 => "is_approve",
          7 => "is_active"
         
          // 11 =>"is_active"
        );
        if(!empty($revisionyear)){
          $sql = DB::table('rpt_assessment_levels AS ral')
          ->join('rpt_property_kinds AS rpk', 'rpk.id', '=', 'ral.pk_code')
          ->join('rpt_property_classes AS rpc', 'rpc.id', '=', 'ral.pc_class_code')
          ->leftjoin('rpt_property_actual_uses AS rpau', 'rpau.id', '=', 'ral.pau_actual_use_code')
          ->join('rpt_revision_year AS rry', 'rry.id', '=', 'ral.rvy_revision_year')              
          ->select('ral.id','ral.is_approve','rpk.pk_code','rpk.pk_description','rpc.pc_class_code','rpc.pc_class_description','rpau.pau_actual_use_code','rpau.pau_actual_use_desc','rry.rvy_revision_year','rry.rvy_revision_code','ral.al_minimum_unit_value','ral.al_maximum_unit_value','ral.al_assessment_level','ral.is_active','rpk.id as kindId')->where('ral.rvy_revision_year',$revisionyear);
        } else{
          $sql = DB::table('rpt_assessment_levels AS ral')
          ->join('rpt_property_kinds AS rpk', 'rpk.id', '=', 'ral.pk_code')
          ->join('rpt_property_classes AS rpc', 'rpc.id', '=', 'ral.pc_class_code')
          ->leftjoin('rpt_property_actual_uses AS rpau', 'rpau.id', '=', 'ral.pau_actual_use_code')
          ->join('rpt_revision_year AS rry', 'rry.id', '=', 'ral.rvy_revision_year')              
          ->select('ral.id','ral.is_approve','rpk.pk_code','rpk.pk_description','rpc.pc_class_code','rpc.pc_class_description','rpau.pau_actual_use_code','rpau.pau_actual_use_desc','rry.rvy_revision_year','rry.rvy_revision_code','ral.al_minimum_unit_value','ral.al_maximum_unit_value','ral.al_assessment_level','ral.is_active','rpk.id as kindId')->where('rry.is_active',1)->where('rry.is_default_value',1);
        }
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(rpk.pk_code)'),'like',"%".strtolower($q)."%")
                ->orwhere(DB::raw('LOWER(rpk.pk_description)'),'like',"%".strtolower($q)."%")
                ->orwhere(DB::raw('LOWER(rpau.pau_actual_use_code)'),'like',"%".strtolower($q)."%")
                ->orwhere(DB::raw('LOWER(rpau.pau_actual_use_desc)'),'like',"%".strtolower($q)."%")
                ->orwhere(DB::raw('LOWER(rry.rvy_revision_year)'),'like',"%".strtolower($q)."%")
                ->orwhere(DB::raw('LOWER(rpc.pc_class_code)'),'like',"%".strtolower($q)."%")
                ->orwhere(DB::raw('LOWER(rpc.pc_class_description)'),'like',"%".strtolower($q)."%");
                  
            });
        }
        if(!empty($kind) && isset($kind)){
          $sql->where('ral.pk_code',$kind);
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('ral.id','ASC');
         

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

}
