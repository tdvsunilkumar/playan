<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class PsicGroup extends Model
{
    public function updateData($id,$columns){
        return DB::table('psic_groups')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
		DB::table('psic_groups')->insert($postdata);
    return DB::getPdo()->lastInsertId();
	}
	public function sectioncode()
    {
     return DB::table('psic_divisions AS a')
            ->join('psic_sections AS b', 'b.id', '=', 'a.section_id')
            ->select('a.id','a.section_id','a.division_code','a.division_description','b.section_code','b.section_description')->where('a.division_status',1)->where('b.section_status','1')->get();
    }
	public function divisioncode($id)
	{
		return DB::table('psic_divisions')->select('id','division_description')->where('division_status','1')->where('section_id','=',$id)->get();
	}
	public function getdivisionsbkp()
    {
        return $this->hasMany('App\Models\PsicSection', 'id', 'section_id');
    }
    public function classcodebygroup($group_id=0,$division_id=0,$section_id=0)
    {
        return DB::table('psic_classes')->select('id','class_code','class_description')->where('is_active','1')->where('group_id','=',$group_id)->where('division_id','=',$division_id)->where('section_id','=',$section_id)->get();

    }
    
    public function getgroups()
    {
    	return DB::table('psic_groups')->join('psic_sections', 'psic_groups.section_id', '=', 'psic_sections.id')->join('psic_divisions', 'psic_groups.division_id', '=', 'psic_divisions.id')->select('psic_groups.id','section_description','division_description','group_code','group_description','is_active')->get();
	}
    
	public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $Section=$request->input('Section');
    $Division=$request->input('Division');
    $q=$request->input('q');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"subclass_code",
      1 =>"group_code",
      2 =>"section_code",
      3 =>"division_description",
      4 =>"group_description",
      5 =>"division_status"   
    );

    $sql = DB::table('psic_groups AS pg')
          ->join('psic_sections AS ps', 'ps.id', '=', 'pg.section_id')
          ->join('psic_divisions AS pd', 'pd.id', '=', 'pg.division_id')
          ->select('pg.id','ps.section_code','ps.section_description','pd.division_code','pd.division_description','pg.is_active','pg.group_code','pg.group_description');
     if(!empty($Section) && isset($Section)){
            $sql->where('pg.section_id','=',$Section);  
        }
         if(!empty($Division) && isset($Division)){
            $sql->where('pg.division_id','=',$Division);  
        }
    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(section_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(section_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(division_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(division_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(group_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(group_description)'),'like',"%".strtolower($q)."%");
            });
        }
        /*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('pg.id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);

    }
}
