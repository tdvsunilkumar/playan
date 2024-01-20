<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Traits\ModelUpdateCreate;
class Policy extends Model
{
    public $table = 'welfare_policy_settings';
    protected $guarded = ['id'];
    public $timestamps = false;
    use ModelUpdateCreate;

    public function addData($postdata){
        // dd($postdata);
        self::create($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getEditDetails($id){
        return self::where('id',$id)->first();
    }
    public function updateData($id,$columns){
        return self::whereId($id)->update($columns);
    }
    public function getList($request)
    {
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        if(!isset($params['start']) && !isset($params['length'])){
            $params['start']="0";
            $params['length']="10";
        }
        $columns = array(
            1 =>"wps_key",
            2 =>"wps_value",
        );
        $sql = $this;
        if(!empty($q) && isset($q)){
            $sql =  $sql->where(function ($query) use($q) {
                        $query->where(DB::raw('LOWER(wps_key)'),'like',"%".strtolower($q)."%");
                        $query->orWhere(DB::raw('LOWER(wps_value)'),'like',"%".strtolower($q)."%");
                    });                
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
            $sql->orderBy('welfare_social_welfare_assistance.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
