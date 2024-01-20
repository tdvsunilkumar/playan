<?php
namespace App\Models\SocialWelfare;
use Illuminate\Database\Eloquent\Model;
use App\Models\SocialWelfare\AssistanceType;
use App\Models\SocialWelfare\AssistanceRequirements;
use DB;
class AssistanceTypeRequirement extends Model
{
    public $table = 'welfare_swa_assistance_type_requirements';
    public function updateActiveInactive($id,$columns){
     return DB::table('welfare_swa_assistance_type_requirements')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('welfare_swa_assistance_type_requirements')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('welfare_swa_assistance_type_requirements')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('welfare_swa_assistance_type_requirements')->where('id',$id)->first();
    }

    public function requirement() 
    { 
        return $this->hasOne(AssistanceRequirements::class, 'id', 'wsr_id'); 
    } 
    public function assistType() 
    { 
        return $this->hasOne(AssistanceType::class, 'id', 'wsat_id'); 
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
          0 =>"id",
          1 =>"wsat_description",
		      2 =>"wsr_description",
          3 =>"wsatr_is_active"
        );

        $sql = $this->select('welfare_swa_assistance_type_requirements.id','wsr_description','wsat_description','wsatr_is_active')->join('welfare_swa_requirements', 'welfare_swa_requirements.id', '=', 'welfare_swa_assistance_type_requirements.wsr_id')->join('welfare_swa_assistance_type', 'welfare_swa_assistance_type.id', '=', 'welfare_swa_assistance_type_requirements.wsat_id');
        if(!empty($q) && isset($q)){
            $sql = $sql->where(function ($query) use($q) {
                        $query->where(DB::raw('LOWER(wsr_description)'),'like',"%".strtolower($q)."%");
                        $query->orWhere(DB::raw('LOWER(wsat_description)'),'like',"%".strtolower($q)."%");
                    });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql = $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql = $sql->orderBy('welfare_swa_assistance_type_requirements.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
