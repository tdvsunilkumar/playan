<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;


class PsicDivision extends Model
{
    public function updateData($id,$columns){
        return DB::table('psic_divisions')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
		DB::table('psic_divisions')->insert($postdata);
        return DB::getPdo()->lastInsertId();
	}
	public function sectioncode()
	{
		return DB::table('psic_sections')->select('*')->where('section_status','1')->get();
	}
    public function checkisexist($code)
    {
        return DB::table('psic_divisions')->select('id','division_description')->where('division_code',$code)->get();
    }
    public function groupcodebydivision($division_id=0,$section_id=0)
    {
        return DB::table('psic_groups')->select('id','group_code','group_description')->where('is_active','1')->where('division_id','=',$division_id)->where('section_id','=',$section_id)->get();
    }

	public function getdivisionsbkp()
    {
        return $this->hasMany('App\Models\PsicSection', 'id', 'section_id');
    }
    public function getdivisions()
    {
        return DB::table('psic_divisions')->join('psic_sections', 'psic_divisions.section_id', '=', 'psic_sections.id')->select('division_code','section_description','division_description','division_status','psic_divisions.id as id')->get();
    }
    public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $Section=$request->input('Section');
    $q=$request->input('q');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"subclass_code",
      1 =>"division_code",
      2 =>"section_code",
      3 =>"division_description",
      4 =>"division_status"   
    );

    $sql = DB::table('psic_divisions AS pd')
          ->join('psic_sections AS ps', 'ps.id', '=', 'pd.section_id')
          ->select('pd.id','ps.section_code','ps.section_description','pd.division_code','pd.division_description','pd.division_status');
     if(!empty($Section) && isset($Section)){
            $sql->where('pd.section_id','=',$Section);  
        }
    //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(section_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(section_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(division_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(division_code)'),'like',"%".strtolower($q)."%");
            });
        }
        /*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('pd.id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);

    }
    
}
