<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BploRequirements extends Model
{
    protected $guarded = ['id'];

    public $table = 'bplo_requirements';
    
    public $timestamps = false;
     public function updateData($id,$columns){
        return DB::table('bplo_requirements')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
		 DB::table('bplo_requirements')->insert($postdata);
        return DB::getPdo()->lastInsertId();
	  }
    public function updateRequiredmentRelationData($id,$columns){
        return DB::table('bplo_requirement_relations')->where('id',$id)->update($columns);
    }
    public function getrequirementRelation($id){
        return DB::table('bplo_requirement_relations')->where('bplo_requirement_id',$id)->get()->toArray();
    }
    public function checkRequirdmentRequietExit($columns){
        return DB::table('bplo_requirement_relations')->select('id')->where('bplo_requirement_id',$columns['bplo_requirement_id'])->where('requirement_id',$columns['requirement_id'])->where('subclass_id',$columns['subclass_id'])->get()->toArray();
    }
    public function UpdateRequirdmentRequietExit($id){
        return DB::table('bplo_requirement_relations')->select('*')->where('bplo_requirement_id',$id)->get()->toArray();
    }
    public function addRequiredmentRelationData($postdata){
         DB::table('bplo_requirement_relations')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getRequirdmentRequiet($id){
       return DB::table('bplo_requirement_relations AS brr')
       ->join('requirements AS r', 'r.id', '=', 'brr.requirement_id')
       ->select('brr.id','r.req_code_abbreviation','r.req_description','brr.is_active','brr.remark')->where('bplo_requirement_id',$id)->get()->toArray();
       // return DB::table('bplo_requirement_relations AS bgf')->select('*')->where('bplo_requirement_id',$id)->get();
    }
    
    
	public function sectioncode()
	{
		return DB::table('psic_sections')->select('id','section_code','section_description')->where('section_status','1')->get();
	}
	public function divisioncode($id)
	{
		return DB::table('psic_divisions')->select('id','division_code','division_description')->where('division_status','1')->where('section_id','=',$id)->get();
	}
	public function groupcode($division_id=0,$section_id=0)
	{
		return DB::table('psic_groups')->select('id','group_code','group_description')->where('is_active','1')->where('division_id','=',$division_id)->where('section_id','=',$section_id)->get();
	}
	public function classcode($group_id=0,$division_id=0,$section_id=0)
	{
		return DB::table('psic_classes')->select('id','class_code','class_description')->where('is_active','1')->where('group_id','=',$group_id)->where('division_id','=',$division_id)->where('section_id','=',$section_id)->get();
	}
	public function subclasscode($class_id=0,$group_id=0,$division_id=0,$section_id=0)
	{
		return DB::table('psic_subclasses')->select('id','subclass_code','subclass_description')->where('is_active','1')->where('class_id','=',$class_id)->where('group_id','=',$group_id)->where('division_id','=',$division_id)->where('section_id','=',$section_id)->get();
	}
	public function requirementcode()
	{
		return DB::table('requirements')->select('id','req_code_abbreviation','req_description')->where('req_dept_bplo','1')->get();
	}
	 public function apptypes()
    {
        return DB::table('pbloapplicationtypes')->select('id','app_type')->where('is_active','1')->get();
    }
	public function getdivisionsbkp()
    {
        return $this->hasMany('App\Models\PsicSection', 'id', 'section_id');
    }
    public function requirementRelations($id)
    {
        return DB::table('bplo_requirement_relations')->select('is_active')->where('is_active','1')->where('bplo_requirement_id','=',$id)->count();
    }


    
    public function getbplorequirements($request)
    {
    	$params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $sid=$request->input('sid');
        $arrSub = array();
        if($sid>0){
            $arrSub = DB::table('psic_subclasses')->select('id','group_id','class_id','division_id','section_id')->where('id','=',$sid)->get()->toArray();

        }

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

          $columns = array( 
          0 =>"id",
          1 =>"app_type",
          2 =>"br_remarks",
          3 =>"is_active2",
          4 =>"section_description",
         );
          

          
         $sql =DB::table('bplo_requirements')
               //->join('psic_sections', 'bplo_requirements.section_id', '=', 'psic_sections.id')
               //->join('psic_divisions', 'bplo_requirements.division_id', '=', 'psic_divisions.id')
               //->join('psic_groups', 'bplo_requirements.group_id', '=', 'psic_groups.id')
               //->join('psic_classes', 'bplo_requirements.class_id', '=', 'psic_classes.id')
               //->join('psic_subclasses', 'bplo_requirements.subclass_id', '=', 'psic_subclasses.id')
               ->join('pbloapplicationtypes', 'bplo_requirements.apptype_id', '=', 'pbloapplicationtypes.id')
               // ->join('bplo_requirement_relations', 'bplo_requirements.id', '=', 'bplo_requirement_relations.bplo_requirement_id')
               ->select('bplo_requirements.id','pbloapplicationtypes.app_type','br_remarks','bplo_requirements.is_active2');
            // $sql =DB::table('bplo_requirement_relations')
            //      ->join('bplo_requirement_relations', 'bplo_requirements.id', '=', 'bplo_requirement_relations.bplo_requirement_id')
            //        ->select('is_active');
    	if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(app_type)'),'like',"%".strtolower($q)."%");
                    //->orWhere(DB::raw('LOWER(section_description)'),'like',"%".strtolower($q)."%")
                    //->orWhere(DB::raw('LOWER(division_code)'),'like',"%".strtolower($q)."%")
                    //->orWhere(DB::raw('LOWER(division_description)'),'like',"%".strtolower($q)."%")
                    //->orWhere(DB::raw('LOWER(group_code)'),'like',"%".strtolower($q)."%")
                    //->orWhere(DB::raw('LOWER(group_description)'),'like',"%".strtolower($q)."%")
                    //->orWhere(DB::raw('LOWER(class_code)'),'like',"%".strtolower($q)."%")
                   // ->orWhere(DB::raw('LOWER(class_description)'),'like',"%".strtolower($q)."%")
                    //->orWhere(DB::raw('LOWER(subclass_code)'),'like',"%".strtolower($q)."%")
                    //->orWhere(DB::raw('LOWER(subclass_description)'),'like',"%".strtolower($q)."%")
                    //->orWhere(DB::raw('LOWER(app_type)'),'like',"%".strtolower($q)."%")
                    //->orWhere(DB::raw('LOWER(req_code_abbreviation)'),'like',"%".strtolower($q)."%");
                   
                    
            });
        }
        if($sid>0){
            if(isset($arrSub)){
                $sql->where('bplo_requirements.group_id',(int)$arrSub[0]->group_id);
                $sql->where('bplo_requirements.class_id',(int)$arrSub[0]->class_id);
                $sql->where('bplo_requirements.division_id',(int)$arrSub[0]->division_id);
                $sql->where('bplo_requirements.section_id',(int)$arrSub[0]->section_id);
                $sql->where('bplo_requirements.subclass_id',(int)$arrSub[0]->id);
            }
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
