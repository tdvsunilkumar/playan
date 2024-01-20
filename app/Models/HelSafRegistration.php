<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

use App\Traits\ModelUpdateCreate;

class HelSafRegistration extends Model
{
  use ModelUpdateCreate;

  public $table = 'ho_registration';
  public $timestamps = false;
  protected $fillable = ['is_opd','is_lab','is_family_planning','is_sanitary','cit_id','reg_date','reg_year','reg_remarks','reg_status'];
    
    public function updateActiveInactive($id,$columns){
     return $this->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return $this->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        $this->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return $this->where('id',$id)->first();
    }
	
	public function getcitizens(){
        return DB::table('citizens')->select('*')->get();
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
          1 =>"cit_id",
          2 =>"is_opd",
		  3 =>"is_lab",
          4 =>"is_family_planning",
          5 =>"is_sanitary",
		  6 =>"reg_date",
		  7 =>"reg_remarks",

        );
	if($request->input('reg_date_form') && $request->input('reg_date_to')){
		
		$sql = $this
		   ->join('citizens', 'citizens.id', '=', $this->table.'.cit_id')
		   ->select($this->table.'.*','citizens.cit_first_name','citizens.cit_middle_name','citizens.cit_last_name')
       ->whereBetween('reg_date',[$request->input('reg_date_form'),$request->input('reg_date_to')]);
	}else{

		$sql = $this
		   ->join('citizens', 'citizens.id', '=', $this->table.'.cit_id')
		   ->select($this->table.'.*','citizens.cit_first_name','citizens.cit_middle_name','citizens.cit_last_name');
	}       
	   if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(reg_remarks)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(reg_date)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cit_first_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cit_middle_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cit_last_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(reg_year)'),'like',"%".strtolower($q)."%"); 
                
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

      public function register($citizen,$date,$place,$remarks = '')
      {
          $update = [$place => 1, 'reg_remarks' => $remarks];
          self::updateOrCreate(
          [
            'cit_id' => $citizen,
            'reg_date' => $date,
            'reg_year' => Carbon::parse($date)->year
          ],$update
        );
      }
}
