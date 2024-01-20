<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class PercentageOfBusiness extends Model
{
    public $table = 'bplo_business';
	
	public function getYearDetails(){
        return DB::table('bplo_business_permit_issuance')->select('bpi_year')->groupBy('bpi_year')->orderBy('bpi_year','DESC')->get()->toArray(); 
    }

	public function getList($request){
        $year =  $request->input('year');
        $sql = DB::table('bplo_business As a')
			->join('bplo_business_type AS b','b.id','=','a.btype_id')
			->join('clients AS c','c.id','=','a.client_id')
			->join('bplo_business_permit_issuance AS cl','d.busn_id','=','a.id')
			->where('a.is_active','=','1')
			->where('d.bpi_issued_status','=','1')
            ->select('*');
			
		if(isset($year)){
			$sql->where('d.bpi_year', $year); 
		}	
			
			
        $data_cnt=$sql->count();
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }


}
