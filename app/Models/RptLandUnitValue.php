<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class RptLandUnitValue extends Model
{
    public function updateActiveInactive($id,$columns){
        return DB::table('rpt_land_unit_values')->where('id',$id)->update($columns);
    }
    public function ApproveUnapprove($id,$columns){
        return DB::table('rpt_land_unit_values')->where('id',$id)->update($columns);
    }   
 

 
    public function updateData($id,$columns){
        return DB::table('rpt_land_unit_values')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('rpt_land_unit_values')->insert($postdata);
    }
    
    

    public function getLocal(){
        return DB::table('profile_municipalities')->select('id','mun_no','mun_desc')->where('mun_display_for_rpt',1)->where('is_active',1)->get();
    }
    public function getBrgy(){
        return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('brgy_display_for_rpt',1)->where('is_active',1)->get();
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
  
    public function getActual(){
        return DB::table('rpt_property_actual_uses')->select('id','pau_actual_use_code','pau_actual_use_desc')->where('pau_is_active',1)->get();
    }
    public function getRevisionDefult(){
        return DB::table('rpt_revision_year')->select('id','rvy_revision_year','rvy_revision_code')->where('is_active',1)->where('is_default_value',1)->get();
    }
    
    public function getRevision(){
        return DB::table('rpt_revision_year')->select('id','rvy_revision_year','rvy_revision_code')->get();
    }
    public function getRevisionall($id){
        return DB::table('rpt_revision_year')->select('id','rvy_revision_year','rvy_revision_code')->where('id',$id)->get();
    }
     public function getRevisionyears(){
        return DB::table('rpt_revision_year')->get()->toArray();
    }
    public function getRptClass(){
        return DB::table('rpt_property_classes')->select('id','pc_class_description','pc_class_code')->where('pc_is_active',1)->get();
    }

    public function classAjaxRequest($request){
      $term=$request->input('term');
        $query = DB::table('rpt_property_classes')->select('id',DB::raw('CONCAT(pc_class_code,"-",pc_class_description) as text'))->where('pc_is_active',1);
                           
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(pc_class_code)'),'like',"%".strtolower($term)."%");
                $sql->orWhere(DB::raw('LOWER(pc_class_description)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;
    }
    public function getsubclass($request){
        $term=$request->input('term');
        $id = $request->id;
        $query = DB::table('rpt_property_subclassifications')->select('id','ps_subclass_code','ps_subclass_desc',DB::raw('CONCAT(ps_subclass_code,"-",ps_subclass_desc) as text'))->where('ps_is_active',1)->where('pc_class_code','=',$id);
                           
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(ps_subclass_code)'),'like',"%".strtolower($term)."%");
                $sql->orWhere(DB::raw('LOWER(ps_subclass_desc)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;
    }
    public function getActualdata($request){
        $term=$request->input('term');
        $id = $request->id;
        $query =DB::table('rpt_property_actual_uses')->select('id',DB::raw('CONCAT(pau_actual_use_code,"-",pau_actual_use_desc) as text'))->where('pau_is_active',1)->where('pc_class_code','=',$id);
                           
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(pau_actual_use_code)'),'like',"%".strtolower($term)."%");
                $sql->orWhere(DB::raw('LOWER(pau_actual_use_desc)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;
    }
    public function getRptSubClass(){
        return DB::table('rpt_property_subclassifications')->select('id','ps_subclass_code','ps_subclass_desc')->where('ps_is_active',1)->get();
    }
    public function getAssmentDetails($munId, $brgyId, $pkcode, $classId, $actulId,$yearId)
    {
        return DB::table('rpt_assessment_levels')
            ->select('id')
            ->where('mun_no', $munId)
            ->where('loc_group_brgy_no', $brgyId)
            ->where('pk_code', $pkcode)
            ->where('pc_class_code', $classId)
            ->where('pau_actual_use_code', $actulId)
            ->where('rvy_revision_year', $yearId)
            ->pluck('id');
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
          1 =>"loc.mun_no", 
          2 =>"b.brgy_code",  
          3 =>"rvy_revision_year", 
          4 =>"pc_class_code",
          5 =>"lav_unit_value",
          6 => "ut.lav_unit_measure",
		  7 => "ut.is_approve",
          8 => "ut.is_approve",
          9 => "ut.lav_strip_is_active"
          
        //   5 =>"lav_strip_is_active"
         );
        if(!empty($revisionyear)){
          $sql = DB::table('rpt_land_unit_values AS ut')
               ->join('profile_municipalities AS loc', 'loc.id', '=', 'ut.loc_local_code')
               ->join('barangays AS b', 'b.id', '=', 'ut.loc_group_brgy_no')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'ut.pc_class_code')
               ->join('rpt_property_subclassifications AS sub', 'sub.id', '=', 'ut.ps_subclass_code')
               ->join('rpt_property_actual_uses AS act', 'act.id', '=', 'ut.pau_actual_use_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'ut.rvy_revision_year')
               ->select('ut.id','class.pc_class_code','class.pc_class_description','sub.ps_subclass_code','sub.ps_subclass_desc','loc.mun_no','loc.mun_desc','b.brgy_code','b.brgy_name','act.pau_actual_use_code','act.pau_actual_use_desc','year.rvy_revision_year','year.rvy_revision_code','ut.lav_unit_value','ut.lav_unit_measure','ut.lav_strip_is_active','ut.is_approve','loc.id as munId','b.id as brgyId','class.id as classId','act.id as actulId','year.id as yearId')->where('ut.rvy_revision_year',$revisionyear);
             } else{
               $sql = DB::table('rpt_land_unit_values AS ut')
               ->join('profile_municipalities AS loc', 'loc.id', '=', 'ut.loc_local_code')
               ->join('barangays AS b', 'b.id', '=', 'ut.loc_group_brgy_no')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'ut.pc_class_code')
               ->join('rpt_property_subclassifications AS sub', 'sub.id', '=', 'ut.ps_subclass_code')
               ->join('rpt_property_actual_uses AS act', 'act.id', '=', 'ut.pau_actual_use_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'ut.rvy_revision_year')
               ->select('ut.id','class.pc_class_code','class.pc_class_description','sub.ps_subclass_code','sub.ps_subclass_desc','loc.mun_no','loc.mun_desc','b.brgy_code','b.brgy_name','act.pau_actual_use_code','act.pau_actual_use_desc','year.rvy_revision_year','year.rvy_revision_code','ut.lav_unit_value','ut.lav_unit_measure','ut.lav_strip_is_active','ut.is_approve','loc.id as munId','b.id as brgyId','class.id as classId','act.id as actulId','year.id as yearId')->where('year.is_active',1)->where('year.is_default_value',1);
             }

        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(class.pc_class_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(class.pc_class_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(sub.ps_subclass_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(sub.ps_subclass_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(loc.mun_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(loc.mun_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(b.brgy_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(act.pau_actual_use_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(act.pau_actual_use_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(year.rvy_revision_year)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(year.rvy_revision_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ut.lav_unit_value)'),'like',"%".strtolower($q)."%")
					->orWhere(function ($sql) use ($q) {
							  if ($q === 'Square Meter' || $q === 'Square Meter') {
								  $sql->where('ut.lav_unit_measure', '=', 1); // Condition for Taxable (option 1)
							  } elseif ($q === 'Hectare' || $q === 'hectare') {
								  $sql->where('ut.lav_unit_measure', '=', 2); // Condition for Exempt (option 2)
							  }
						})
					;
                    
            });
        }



        /*  #######  Set Order By  ###### */
        //dd($columns[$params['order'][0]['column']]);
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('ut.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}



