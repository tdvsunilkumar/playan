<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RevisionYear extends Model
{
    public $table = 'rpt_revision_year';

    public function updateActiveInactive($id,$columns){
      return DB::table('rpt_revision_year')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        $data = tap(DB::table('rpt_revision_year')->where('id',$id))->update($columns);
        $respinseModel = $data->first();
        return $respinseModel->id;
    }
    
    
    public function addData($postdata){
		 DB::table('rpt_revision_year')->insert($postdata);
        return DB::getPdo()->lastInsertId();
	}

  public function getActiveRevisionYear($value=''){
      $sql = DB::table('rpt_revision_year');
            $sql->where(function ($sql) use($value) {
                if($value != ""){
                  $sql->where('id',$value);
                }else{
                  $sql->where('is_default_value',1);
                }
            });
        
              
              return $sql->first();
      }
    
   
    public function getRevisionyear(){
       return DB::table('rpt_revision_year')->select('*')->get();
    }
    public function editRevisionyear($id){
        return DB::table('rpt_revision_year as rry')
                  ->leftJoin('hr_employees as ass','ass.id','=','rry.rvy_city_assessor_code')
                  ->leftJoin('hr_employees as asass','asass.id','=','rry.rvy_city_assessor_assistant_code')
                  ->where('rry.id',$id)
                  ->select('rry.*','ass.fullname as assesser','asass.fullname as assassesser')
                  ->first();
    }

    public function getHrEmployeeCode(){
        return DB::table('hr_employees')->select('id','firstname','middlename','lastname')->get();
    }

    public function assesserAjaxRequest($request){
      $term=$request->input('term');
        $query = DB::table('hr_employees')->select('id','firstname','middlename','lastname','fullname as text');
                           
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(fullname)'),'like',"%".strtolower($term)."%");
                $sql->orWhere(DB::raw('LOWER(firstname)'),'like',"%".strtolower($term)."%");
                $sql->orWhere(DB::raw('LOWER(middlename)'),'like',"%".strtolower($term)."%");
                $sql->orWhere(DB::raw('LOWER(lastname)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;
    }
    
    
    public function updatePrint($id = null){
      $default_value = $this->find($id);

      if($default_value->is_default_value == 1){
        try {
         $this->where('id','!=',$default_value->id)->update(['is_default_value'=>0]);
        } catch (\Exception $e) {
          echo $e->getMessage();exit;
        }
      }
      
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
          1 =>"rvy_revision_year",  
          2 =>"rvy_revision_code",
          3 =>DB::raw("CONCAT(rpt.rvy_revision_year,'-',rpt.rvy_revision_code)"),
          4 =>"has_tax_basic",
          5 =>"has_tax_sef",
          6 =>"has_tax_sh",
          7 =>"p1.fullname",
          8 =>"p.fullname",
          9 =>"is_default_value",
          10 =>"is_active",
                     
        );
        
        $sql = DB::table('rpt_revision_year AS rpt')
              ->leftjoin('hr_employees AS p', 'p.id', '=', 'rpt.rvy_city_assessor_assistant_code')
              ->join('hr_employees AS p1', 'p1.id', '=', 'rpt.rvy_city_assessor_code')
              ->select('rpt.id','p.fullname','p.middlename','p.lastname','p1.fullname as p1firstname','p1.middlename as p1middlename','p1.lastname as p1lastname','rpt.rvy_revision_year','rpt.rvy_revision_code','rpt.display_for_bplo','rpt.display_for_rpt','rpt.is_active','rpt.is_default_value','rpt.has_tax_basic','rpt.has_tax_sef','rpt.has_tax_sh');

       
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(p.fullname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p.middlename)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p.lastname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p1.fullname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p1.middlename)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(p1.lastname)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rvy_revision_year)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rvy_revision_code)'),'like',"%".strtolower($q)."%");  
                    
                                
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('rpt.id','ASC');
        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}

    