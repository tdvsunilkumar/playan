<?php

namespace App\Models\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Session;
class ListofBusinessperBar extends Model
{
    public $table = 'bplo_business_permit_issuance';
	
    public function getYearDetails(){
        return DB::table('bplo_business_permit_issuance')->select('bpi_year')->groupBy('bpi_year')->orderBy('bpi_year','DESC')->get()->toArray(); 
    }
	
	public function exportdata($request){
		$params = $columns=array();
        $params = $_REQUEST;  
        $search =$request->input('q');
        $from_date=$request->input('from_date');
        $to_date=$request->input('to_date');
        $brayid = 0;
        
        $totalRecords = Session::get('totalRows');$from_date = Session::get('blistfromdate');  $to_date = Session::get('blisttodate');  $search = Session::get('blistsearch');
      
        if(!isset($params['start']) && !isset($params['length'])){
          $start="0";
          $length=$totalRecords;
        }else{
            $start = $params['start'];
            $length = $params['length'];
        }
    $columns = array( 
      1 =>"bb.busns_id_no",
      2 =>"cl.full_name",
      3 =>"bb.busn_name",
      4 =>"bb.busn_office_main_building_no",
      5 =>"bbpi.app_type_id",
      6 =>"bbpi.bpi_issued_date",
      7 =>"pm_id",
      8 =>"totalassessment",
      9 =>"totalpaidamount",
      10 =>"bbpi.app_type_id",
    );

    $orderBy ='busn_name ASC';
    if(isset($params['order'][0]['column'])){
      $orderBy =$columns[$params['order'][0]['column']].' '.$params['order'][0]['dir'];
    }
    $out="p_total_count";
    $sql="CALL REPORT_BPLOLIST_PER_BRGY('$brayid','$from_date','$to_date','$start',$length,'$search','$orderBy',@$out)";
    // $sql = Session::get('arrSql');
    $arr = DB::select($sql);

    Session::put('REPORT_BPLOLIST_PER_BRGY',$sql);
    return array("data"=>$arr);
			   
    }

    public function getList($request){
    $params = $columns=array();
    $params = $_REQUEST;  
    $search =$request->input('q');
    $brayid = 0;
    $from_date=$request->input('from_date');
    $to_date=$request->input('to_date');

	 Session::put('blistfromdate',$from_date);
	 Session::put('blisttodate',$to_date);
	 Session::put('blistsearch',$search);
     Session::put('start',$params['start']);
     Session::put('length',$params['length']);
    if(!isset($params['start']) && !isset($params['length'])){
      $start="0";
      $length="10";
    }else{
        $start = $params['start'];
        $length = $params['length'];
    }
    $columns = array( 
      1 =>"bb.busns_id_no",
      2 =>"cl.full_name",
      3 =>"bb.busn_name",
      4 =>"bb.busn_office_main_building_no",
	  5 =>"bbpi.app_type_id",
	  6 =>"bbpi.bpi_issued_date",
	  7 =>"pm_id",
	  8 =>"totalassessment",
	  9 =>"totalpaidamount",
	  10 =>"bbpi.app_type_id",
    );

    $orderBy ='busn_name ASC';
    if(isset($params['order'][0]['column'])){
      $orderBy =$columns[$params['order'][0]['column']].' '.$params['order'][0]['dir'];
    }
    // $from_date = ""; $to_date="";
    $out="p_total_count";
    $sql="CALL REPORT_BPLOLIST_PER_BRGY('$brayid','$from_date','$to_date','$start',$length,'$search','$orderBy',@$out)";
    $arr = DB::select($sql);
    Session::put('arrSql',$sql);
    Session::put('REPORT_BPLOLIST_PER_BRGY',$sql);
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

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        
        $columns = array( 
           1 =>"bb.busns_id_no",
		  2 =>"cl.full_name",
		  3 =>"bb.busn_name",
		  4 =>"bb.busn_office_main_building_no",
		  5 =>"bbpi.app_type_id",
		  6 =>"bbpi.bpi_issued_date",
		  7 =>"pm_id",
		  8 =>"totalassessment",
		  9 =>"totalpaidamount",
		  10 =>"bpi_remarks",
         );

        $sql = DB::table('bplo_business_permit_issuance AS bbpi')
			   ->join('bplo_business AS bb','bb.id','=','bbpi.busn_id')
			    ->join('clients AS cl','cl.id','=','bb.client_id')
			   ->where('bbpi.bpi_issued_status','=','1')
			   ->where('bb.is_active','=','1')
               ->select('bbpi.id','bb.busns_id_no','cl.rpo_first_name','cl.rpo_middle_name','cl.rpo_custom_last_name','cl.suffix','bb.busn_name','bb.busn_office_main_building_no','bb.busn_office_main_building_name','bb.busn_office_main_add_block_no','bb.busn_office_main_add_lot_no','bb.busn_office_main_add_street_name','bb.busn_office_main_add_subdivision',
			   'bbpi.app_type_id','bbpi.bpi_issued_date');
			   
       if(!empty($from_date) && isset($from_date)){
                $sql->whereDate('bb.application_date','>=',$from_date);
        }
        if(!empty($to_date) && isset($to_date)){
                    $sql->whereDate('bb.application_date','<=',$to_date);
            }
        if(!empty($q) && isset($q)){
            switch (strtolower($q)) {
                case 'not completed':
                    $que = 0;
                    break;
                case 'completed/for verification':
                    $que = 1;
                    break;
                case 'for endorsement':
                    $que = 2;
                    break;
                case 'for assessment':
                    $que = 3;
                    break;
                case 'for payment':
                    $que = 4;
                    break;
                case 'for issuance':
                    $que = 5;
                    break;
                case 'license issued':
                    $que = 6;
                    break;
                case 'declined':
                    $que = 7;
                    break;
                case 'cancelled permit':
                    $que = 8;
                    break;
                default:
                    $que = null;
                    break;
            }
            $sql->where(function ($sql) use($q,$que) {
                if(isset($que))
                {
                    $sql->where(DB::raw('busn_app_status'),$que); 
                }
                else{
                    $sql->where(DB::raw('LOWER(bb.busns_id_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cl.rpo_first_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cl.rpo_middle_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(cl.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_office_main_building_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_office_main_building_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_office_main_add_block_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_office_main_add_lot_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_office_main_add_street_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bb.busn_office_main_add_subdivision)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(bbpi.bpi_issued_date)'),'like',"%".strtolower($q)."%")
					->orWhere(function ($sql) use ($q) {
						  if ($q === 'New' || $q === 'new') {
							  $sql->where('bbpi.app_type_id', '=', 1);
						  }elseif ($q === 'Renewal' || $q === 'renewal') {
							  $sql->where('bbpi.app_type_id', '=', 2); 
						  }elseif ($q === 'Retire' || $q === 'retire') {
							  $sql->where('bbpi.app_type_id', '=', 3); 
						  }
					})
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
            $sql->orderBy('bbpi.id','ASC');
        }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
	}
}
