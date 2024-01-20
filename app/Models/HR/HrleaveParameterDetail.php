<?php

namespace App\Models\HR;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Traits\ModelUpdateCreate;

class HrleaveParameterDetail extends Model
{

    use ModelUpdateCreate;
    public $table = 'hr_leave_parameter_detail';
    protected $guarded = ['id'];
    public $timestamps = false;
    
    public function updateActiveInactive($id,$columns){
     return DB::table('hr_leave_parameter_detail')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('hr_leave_parameter_detail')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('hr_leave_parameter_detail')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('hr_leave_parameter_detail')->where('id',$id)->first();
    }
	public function HrleaveType(){
        return DB::table('hr_leavetypes')->select('*')->get();
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
          1 =>"hrlp_id",
		  2 =>"hrlt_id",
		  3 =>"hrlpc_days",
		  4 =>"hrat_id",
		  5 =>"hrlpc_credits",
          2 =>"hrlpc_is_active"
           
        );

        $sql = DB::table('hr_leave_parameter_detail')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(hrlp_id)'),'like',"%".strtolower($q)."%")
                ; 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
