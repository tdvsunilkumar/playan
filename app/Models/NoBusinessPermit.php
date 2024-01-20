<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class NoBusinessPermit extends Model
{
	public $table = 'bplo_business_permit_issuance';
	
	public function getYearDetails(){
        return DB::table('bplo_business_permit_issuance')->select('bpi_year')->groupBy('bpi_year')->orderBy('bpi_year','DESC')->get()->toArray(); 
    }

    public function getList($request){
		
        $params = $columns = $totalRecords = $data = array();
		
        $params = $_REQUEST;
		
        $q=$request->input('q');
      
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="8";
        }
        
        $columns = array( 
          0 =>"bpi_issued_date",
         );
         $sql = DB::table('bplo_business_permit_issuance')
            ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(bpi_issued_date)'),'like',"%".strtolower($q)."%");
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
