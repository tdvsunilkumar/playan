<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use App\Models\Report\ListNotOperational;
use App\Exports\ListofBusinessNotOperationalExport;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use File;
use Response;

class ListofBusinessNotOperational extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_ListNotOperational = new ListNotOperational(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'');  
        $this->slugs = 'reports-masterlists-listof-business-not-operational';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $startdate = date('Y-m-d');   $currentmonth = date('Y/m');           
        return view('report.notoperational.index',compact('startdate'));
    
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_ListNotOperational->getList($request);

        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
		
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
			$owner_name = $row->full_name;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['owner_name']= $owner_name;
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['closed']=$row->bri_issued_date;
            $arr[$i]['closed_date_form']=$row->retire_date_closed;
            $arr[$i]['closed_date_to']=(isset($row->application_dates))?$row->application_dates:date("Y-m-d");
            $i++; 
        }
        
        $totalRecords=$data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }
    public function exportreportsListofBusinessNotOperational (Request $request){
        return Excel::download(new ListofBusinessNotOperationalExport($request->get('keywords')), 'ListofBusinessNotOperational_sheet'.time().'.xlsx');
    }

}
