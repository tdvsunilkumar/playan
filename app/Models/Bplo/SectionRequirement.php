<?php

namespace App\Models\Bplo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class SectionRequirement extends Model
{
    protected $guarded = ['id'];

    public $table = 'psic_section_requirements';
    
    public $timestamps = false;
     public function updateData($id,$columns){
        return DB::table('psic_section_requirements')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('psic_section_requirements')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getSection($id)
    {
        return DB::table('psic_sections')->select('id','section_code','section_description')->where('id',(int)$id)->get()->toArray();
    }
    public function requirementcode()
    {
        return DB::table('requirements')->select('id','req_code_abbreviation','req_description')->where('req_dept_bplo','1')->get();
    }
    public function apptypes()
    {
        return DB::table('bplo_application_type')->select('id','app_type')->get();
    }
    public function getSectionRequirement($id){
        return DB::table('psic_section_requirements')->select('requirement_json')->where('id',$id)->first();
    }
    public function getList($request)
    {
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $sid=$request->input('sid');
        if(!isset($params['start']) && !isset($params['length'])){
            $params['start']="0";
            $params['length']="10";
        }
        $columns = array( 
            0 =>"id",
            1 =>"app_type",
        );
        $sql =DB::table('psic_section_requirements AS sr')
        ->join('bplo_application_type AS app', 'sr.apptype_id', '=', 'app.id')
        ->select('sr.id','app.app_type');
        $sql->where('section_id',(int)$sid);
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(app_type)'),'like',"%".strtolower($q)."%");
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
            $sql->orderBy('sr.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
