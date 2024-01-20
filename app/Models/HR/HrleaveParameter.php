<?php

namespace App\Models\HR;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HrleaveParameter extends Model
{
    public $table = 'hr_leave_parameter';

    public function updateActiveInactive($id,$columns){
     return DB::table('hr_leave_parameter')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('hr_leave_parameter')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('hr_leave_parameter')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('hr_leave_parameter')->where('id',$id)->first();
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
          1 =>"hrlp_description",
          2 =>"hrlp_is_active"
           
        );

        $sql = DB::table('hr_leave_parameter')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(hrlp_description)'),'like',"%".strtolower($q)."%")
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

    public function addParams($request){
      if ($request->input('params')) {
        foreach ($request->input('params') as $key => $value) {
              if (!isset($value['hrlpc_is_active'])) {
                $value['hrlpc_is_active']=1;
              }
              $add = HrleaveParameterDetail::updateOrCreate(
              [
                  'hrlp_id' => $request->id,//assistance id
                  'id' => $key,//file id
              ],
              $value
          );
        }
      }
    }
    public function parameters() 
    { 
        return $this->hasMany(HrleaveParameterDetail::class, 'hrlp_id', 'id'); 
    } 
}
