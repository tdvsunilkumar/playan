<?php
namespace App\Models\SocialWelfare;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Requirements;
use App\Traits\ModelUpdateCreate;

class AssistanceType extends Model
{
    use ModelUpdateCreate;
    
    public $table = 'welfare_swa_assistance_type';
    public function scopeActive($query)
    {
        $query->where('wsat_is_active', 1);
    }
    public function updateActiveInactive($id,$columns){
     return DB::table('welfare_swa_assistance_type')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('welfare_swa_assistance_type')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('welfare_swa_assistance_type')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('welfare_swa_assistance_type')->where('id',$id)->first();
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
          2 =>"wsat_is_active"
           
        );

        $sql = DB::table('welfare_swa_assistance_type')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(wsat_description)'),'like',"%".strtolower($q)."%")
                ; 
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
	  
	public function allAppType(){
	
      $wsat_type = DB::table('welfare_swa_assistance_type')
      ->where('wsat_is_active',1)
      ->orderBy('id')
	    ->select('id','wsat_description')->get();
      return $wsat_type;
	}
	
	public function oneapptype($id){
        return DB::table('welfare_swa_assistance_type')->where('id',$id)->select('id','wsat_description')->first();
    }

    public function requirements() 
    { 
        return $this->hasMany(AssistanceTypeRequirement::class, 'wsat_id', 'id'); 
    } 
    public function getRequirements()
    {
      $main = $this;
      $data = AssistanceTypeRequirement::where('wsat_id',$main->id)
                ->join('welfare_swa_requirements','welfare_swa_requirements.id','=','wsr_id')
                ->where('wsatr_is_active', 1)
                ->where('wsr_is_active', 1)
                ->get();
      return $data;
    }
    public function selectRequirement($search="")
      {
            // $data = [];
            // $q=$request->input('search');
            // $brgy = Requirements::where('is_active', 1)->take(5)->get();
            // if(!empty($q) && isset($q)){
            //       $brgy = Requirements::where(DB::raw('LOWER(req_description)'),'like',"%".strtolower($q)."%")
            //       ->where('is_active', 1)
            //       ->take(5)
            //       ->get();
            // }
            // foreach ($brgy as $key => $value) {
            //       $data += [$value->id => $value->req_description];
            // }
            // return $data;

            $page=1;
            if(isset($_REQUEST['page'])){
            $page = (int)$_REQUEST['page'];
            }
            $length = 20;
            $offset = ($page - 1) * $length;
            $sql = Requirements::where('is_active',1);
            if(!empty($search)){
                  $sql->where(function ($sql) use($search) {
                        if(is_numeric($search)){
                              $sql->Where('id',$search);
                        }else{
                              $sql->where(DB::raw('LOWER(req_description)'),'like',"%".strtolower($search)."%");
                        }
                  });
            }
            $sql->orderBy('req_description','ASC');
            $data_cnt=$sql->count();
            $sql->offset((int)$offset)->limit((int)$length);
            
            $data=$sql->get();
            return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
      
}
