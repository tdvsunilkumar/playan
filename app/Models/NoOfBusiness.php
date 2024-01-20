<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;


class NoOfBusiness extends Model
{
    public function updateData($id,$columns){
        return DB::table('psic_divisions')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        return DB::table('psic_divisions')->insert($postdata);
    }
    public function sectioncode()
    {
        return DB::table('psic_sections')->select('id','section_description')->get()->toArray();
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
    public function getdivisions($section_id)
    {
        return DB::table('psic_divisions')->select('id','section_id','division_description')->where('section_id','=',$section_id)->get()->toArray();
    }
    public function getGroup($section_id,$division_id)
    {
        return DB::table('psic_groups')->select('id','section_id','division_id','group_description')->where('section_id','=',$section_id)->where('division_id','=',$division_id)->get()->toArray();
    }
    public function getClass($section_id,$division_id,$group_id)
    {
        return DB::table('psic_classes')->select('id','section_id','division_id','group_id','class_description')->where('section_id','=',$section_id)->where('division_id','=',$division_id)->where('group_id','=',$group_id)->get()->toArray();
    }
    public function getSubClasses($section_id,$division_id,$group_id,$class_id)
    {
        return DB::table('psic_subclasses')->select('id','section_id','division_id','group_id','class_id','subclass_description')->where('section_id','=',$section_id)->where('division_id','=',$division_id)->where('group_id','=',$group_id)->where('class_id','=',$class_id)->get()->toArray();
    }
    public function getAllSubClasses()
    {
        return DB::table('psic_subclasses AS ps')
             ->leftjoin('psic_classes AS pc', 'pc.id', '=', 'ps.class_id')
            ->leftjoin('psic_groups AS pg', 'pg.id', '=', 'pc.group_id')
            ->leftjoin('psic_divisions AS pd', 'pd.id', '=', 'pg.division_id')
            ->leftjoin('psic_sections AS psec', 'psec.id', '=', 'pd.section_id')
            ->select('ps.id','ps.subclass_description','pc.class_description','pg.group_description','pd.division_description','psec.section_description')
            ->orderBy('psec.id')
            ->get();
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
      0 =>"subclass_code",
      1 =>"section_description",
      2 =>"division_description",
      3 =>"division_description",
      4 =>"division_status"   
    );

    $sql = DB::table('psic_sections')
          
          ->select('id','section_description');

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
      $sql->orderBy('id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);

    }


}
