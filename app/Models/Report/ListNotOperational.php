<?php

namespace App\Models\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Session;
class ListNotOperational extends Model
{
    public $table = 'bplo_business';
	
    public function getDataExport(){
        $q = Session::get('searchList');
        $sql = DB::table('bplo_business_retirement As bbr')
            ->join('bplo_business_retirement_issuance AS bbri','bbri.busn_id','=','bbr.busn_id')
            ->join('bplo_business AS bb','bb.id','=','bbr.busn_id')
            ->join('clients AS cl','cl.id','=','bb.client_id')
            ->select('bb.id','bb.busn_name','cl.full_name','cl.rpo_first_name','cl.rpo_middle_name','cl.rpo_custom_last_name','cl.suffix','bbri.bri_issued_date','bbr.retire_date_closed',DB::raw('(SELECT application_date FROM bplo_business_history AS bbh WHERE bbh.busn_id = bbr.busn_id AND bbh.app_code!=3 AND application_date>=bri_issued_date ORDER BY bbh.id DESC LIMIT 1) AS application_dates'));
            
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cl.full_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw("CONCAT(cl.rpo_first_name, ' ',cl.rpo_middle_name,' ',cl.rpo_custom_last_name)"), 'LIKE', "%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(bbri.bri_issued_date)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(bbr.retire_date_closed)'),'like',"%".strtolower($q)."%")
                ; 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('bb.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        
        $data=$sql->get();
        return $data;
    }
    public function getList($request){

        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        Session::put('searchList',$q);
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }


        $columns = array( 
          0 =>"id",
		  1 =>"cl.full_name",
          2 =>"busn_name",
		  3 =>"bbri.bri_issued_date",
		  4 =>"bbr.retire_date_closed",
           
        );
		
        $sql = DB::table('bplo_business_retirement As bbr')
			->join('bplo_business_retirement_issuance AS bbri','bbri.busn_id','=','bbr.busn_id')
			->join('bplo_business AS bb','bb.id','=','bbr.busn_id')
			->join('clients AS cl','cl.id','=','bb.client_id')
            ->select('bb.id','bb.busn_name','cl.full_name','cl.rpo_first_name','cl.rpo_middle_name','cl.rpo_custom_last_name','cl.suffix','bbri.bri_issued_date','bbr.retire_date_closed',DB::raw('(SELECT application_date FROM bplo_business_history AS bbh WHERE bbh.busn_id = bbr.busn_id AND bbh.app_code!=3 AND application_date>=bri_issued_date ORDER BY bbh.id DESC LIMIT 1) AS application_dates'));
			
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(cl.full_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw("CONCAT(cl.rpo_first_name, ' ',cl.rpo_middle_name,' ',cl.rpo_custom_last_name)"), 'LIKE', "%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(bbri.bri_issued_date)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(bbr.retire_date_closed)'),'like',"%".strtolower($q)."%")
                ; 
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('bb.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
