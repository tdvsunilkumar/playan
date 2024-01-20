<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class BusinessGovPsaLists extends Model
{
    public function updateData($id,$columns){
        return DB::table('cto_cashier')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_cashier')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }
    public function getTypeofOwnership(){
      return DB::table('bplo_business_type')->select('id','btype_desc')->get();
    }
    public function getdetails($id){
    		return DB::table('cto_cashier AS cc')
   		  ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id')
          ->select('cc.or_no','c.rpo_first_name','c.rpo_custom_last_name','c.rpo_middle_name','cc.tcm_id','cc.tax_credit_gl_id','cc.tax_credit_sl_id','cc.tax_credit_amount')->where('cc.id',$id)->first();
    }
    public function getCharges($field=''){
        $sql = DB::table('cto_charge_descriptions')->select('id','charge_desc')->where('is_active',1);
        if(!empty($field)){
            $sql->where($field,1);
        }
        return $sql->orderBy('charge_desc', 'ASC')->get()->toArray();
    }

    public function GetSubclassesArray(){
      return DB::table('psic_subclasses')->select('id','subclass_description')->get();
    }

    public function gettfocsids(){
      $idarray = array(1,2);
       return DB::table('report_column_headers')->select('tfoc_id')->whereIN('id',$idarray)->get();
    }

    public function getBusinesstax($ids,$busnid){
        return DB::table('cto_bplo_assessment')->select('id',DB::raw('SUM(tfoc_amount) AS amount'))->whereIN('tfoc_id',$ids)->where('busn_id',$busnid)->first();
    }

     public function getBusinessPSIC($id){
        return DB::table('bplo_business_psic AS psic')
                ->join('psic_subclasses AS sub', 'sub.id', '=', 'psic.subclass_id')
                ->select('sub.id','sub.subclass_code','sub.subclass_description','psic.busp_capital_investment','psic.busp_total_gross')->where('psic.busn_id',(int)$id)->get()->toArray();
    }

    public function getList($request){
    	$params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;  
        $search =$request->input('q');
        $tfocid ="";
        $from_date=$request->input('from_date');
        $to_date=$request->input('to_date');
         Session::put('blistfromdate',$from_date);
         Session::put('blisttodate',$to_date);
         Session::put('blistsearch',$search);
         Session::put('start',$params['start']);
         Session::put('length',$params['length']);
       if(!isset($params['start']) && !isset($params['length'])){
	      $start="0";
	      $length=$totalRecords;
	    }else{
	        $start = $params['start'];
	        $length = $params['length'];
	    }
        
        $columns = array( 
          1 =>"bb.busn_name",
          2 =>"bb.rpo_first_name",  
          3 =>"bb.busn_name",
          4 =>"cl.rpo_first_name",
          5 =>"bb.busn_tin_no",
          9 =>"bb.created_at",
         );
    	   $orderBy ='bb.id ASC';
		    if(isset($params['order'][0]['column'])){
		      $orderBy =$columns[$params['order'][0]['column']].' '.$params['order'][0]['dir'];
		    }
		    
		    $out="p_total_count";
		    $sql="CALL REPORT_BPLO_PSA_LIST('$from_date','$to_date','$start','$length','$search','$orderBy',@$out)";

		    $arr = DB::select($sql);
            Session::put('arrSql',$sql);
		    Session::put('REPORT_BPLO_PSA_LIST',$sql);
		    $data_cnt = DB::select("SELECT @$out as $out")[0]->p_total_count;
            Session::put('totalRows',$data_cnt);
		    return array("data_cnt"=>$data_cnt,"data"=>$arr);
    }

     public function getListold($request){

        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $from_date=$request->input('from_date');
        $to_date=$request->input('to_date');
        Session::put('psalidtfromdate',$from_date); Session::put('psalisttodate',$to_date); Session::put('psalistsearch',$q);

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="8";
        }
        
        $columns = array( 
          1 =>"bb.busn_name",
          2 =>"bb.rpo_first_name",  
          3 =>"bb.busn_name",
          4 =>"cl.rpo_first_name",
          5 =>"bb.busn_tin_no",
          9 =>"bb.created_at",
         );

        $sql = DB::table('bplo_business_history AS bb')
			   ->join('clients AS cl','cl.id','=','bb.client_id')
			   ->join('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
			   ->join('bplo_business_permit_issuance AS bbpi', 'bbpi.busn_id', '=', 'bb.busn_id')
               ->select('bb.busn_name','bb.busn_tin_no','bb.busn_employee_total_no','bb.busn_id','bb.app_code','cl.rpo_first_name As rpo_first_name','cl.rpo_middle_name As rpo_middle_name','cl.rpo_custom_last_name As rpo_custom_last_name','cl.suffix As suffix','cl.gender As gender',DB::raw("CONCAT(rpo_address_house_lot_no,', ',rpo_address_street_name,', ',rpo_address_subdivision) as ownar_address"),
			   'bbpi.bpi_remarks As bpi_remarks','bbpi.bpi_permit_no As bpi_permit_no','bbpi.bpi_issued_date As bpi_issued_date');
		    if(!empty($from_date) && isset($from_date)){
                $sql->whereDate('bb.application_date','>=',$from_date);
        }
        if(!empty($to_date) && isset($to_date)){
                    $sql->whereDate('bb.application_date','<=',$to_date);
            }
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                {
                    $sql->where(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busns_id_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(cl.rpo_first_name, ' ',cl.rpo_middle_name,' ',cl.rpo_custom_last_name,', ',cl.suffix)"), 'LIKE', "%".strtolower($q)."%")
                   ;
                    
                }
                
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else
        {
            $sql->orderBy('bb.id','ASC');
        }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        $sql->groupBy('bbpi.bpi_year','bbpi.busn_id');
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
	}

  public function getDataExport(){
      $params = $columns=array();
        $params = $_REQUEST;  
        // $search =$request->input('q');
        // $from_date=$request->input('from_date');
        // $to_date=$request->input('to_date');
        
        $totalRecords = Session::get('totalRows');$from_date = Session::get('blistfromdate');  $to_date = Session::get('blisttodate');  $search = Session::get('blistsearch');
            if(!isset($params['start']) && !isset($params['length'])){
          $start="0";
          $length=$totalRecords;
        }else{
            $start = $params['start'];
            $length = $params['length'];
        }
        
        $columns = array( 
          1 =>"bb.busn_name",
          2 =>"bb.rpo_first_name",  
          3 =>"bb.busn_name",
          4 =>"cl.rpo_first_name",
          5 =>"bb.busn_tin_no",
          9 =>"bb.created_at",
         );
           $orderBy ='bb.id ASC';
            if(isset($params['order'][0]['column'])){
              $orderBy =$columns[$params['order'][0]['column']].' '.$params['order'][0]['dir'];
            }
            
            $out="p_total_count";
            $sql="CALL REPORT_BPLO_PSA_LIST('$from_date','$to_date','$start','$length','$search','$orderBy',@$out)";

            $data = DB::select($sql);
            Session::put('REPORT_BPLO_PSA_LIST',$sql);
            $data_cnt = DB::select("SELECT @$out as $out")[0]->p_total_count;
            // return array("data_cnt"=>$data_cnt,"data"=>$arr);
        return $data;
  }
}
