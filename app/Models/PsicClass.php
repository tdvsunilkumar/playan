<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class PsicClass extends Model
{
  public function updateData($id,$columns){
    return DB::table('psic_classes')->where('id',$id)->update($columns);
  }
  public function addData($postdata){
		 DB::table('psic_classes')->insert($postdata);
    return DB::getPdo()->lastInsertId();
	}
  public function sectioncode()
  {
     return DB::table('psic_divisions AS a')
            ->join('psic_sections AS b', 'b.id', '=', 'a.section_id')
            ->select('a.id','a.section_id','a.division_code','a.division_description','b.section_code','b.section_description')->where('a.division_status',1)->where('b.section_status','1')->get();
  }
  public function getSection(){
		return DB::table('psic_sections')->select('id','section_code','section_description')->where('section_status',1)->get();
	}
  public function getDivision($id){
		return DB::table('psic_divisions')->select('id','division_code','division_description')->where('division_status',1)->where('section_id','=',$id)->get();
	}
  public function getGroup($id){
		return DB::table('psic_groups')->select('id','group_code','group_description')->where('is_active',1)->where('division_id','=',$id)->get();
	}


  public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $Section=$request->input('Section');
    $Division=$request->input('Division');
    $Group=$request->input('Group');
    $q=$request->input('q');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      1 =>"class_code",
      2 =>"section_description",
      3 =>"division_description",
      4 =>"group_description",
      5 =>"class_description",	
      6 =>"pc.is_active"     
    );

    $sql = DB::table('psic_classes AS pc')
          ->join('psic_sections AS ps', 'ps.id', '=', 'pc.section_id')
          ->join('psic_divisions AS pd', 'pc.division_id', '=', 'pd.id')
          ->join('psic_groups AS pg', 'pc.group_id', '=', 'pg.id')
          ->select('pc.id','ps.section_code','section_description','division_code','group_code','group_description','division_description','pc.is_active','class_description','class_code');
     if(!empty($Section) && isset($Section)){
            $sql->where('pc.section_id','=',$Section);  
        }
         if(!empty($Division) && isset($Division)){
            $sql->where('pc.division_id','=',$Division);  
        }
        if(!empty($Group) && isset($Group)){
            $sql->where('pc.group_id','=',$Group);  
        }
    //$sql->where('pc.generated_by', '=', \Auth::user()->creatorId());
		if(!empty($q) && isset($q)){
			$sql->where(function ($sql) use($q) {
				$sql->where(DB::raw('LOWER(class_code)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(section_description)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(division_description)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(group_description)'),'like',"%".strtolower($q)."%")
          ->orWhere(DB::raw('LOWER(class_description)'),'like',"%".strtolower($q)."%");
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
