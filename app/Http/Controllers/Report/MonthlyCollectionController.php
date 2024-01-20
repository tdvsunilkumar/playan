<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use App\Models\Report\MonthlyCollection;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use Response;

class MonthlyCollectionController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_monthlycollection = new MonthlyCollection(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','busloc_desc'=>'');  
        $this->slugs = 'reports-monthly-collection';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $startdate = date('Y-m-d');   $currentmonth = date('Y/m');
        $startdate=Date('Y-m-d', strtotime('-15 days'));
        $arrDepaertments = array('0'=>'All');
         foreach ($this->_monthlycollection->GetDepartmrntsArray() as $val) {
             $arrDepaertments[$val->id]=$val->pcs_name;
         }
        //$arrDepaertments = array('0'=>'All','1'=>'Business Permit', '2'=>'Real Property', '3'=>'Engineering', '4'=>'Occupancy', '5'=>'Planning and Development', '6'=>'Health & Safety','7'=>'Community Tax','8'=>'Burial Permit','9'=>'Miscellaneous');
        
        return view('report.monthlyreport.index',compact('startdate','currentmonth','arrDepaertments'));
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_monthlycollection->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['date']=$row->created_at;
            $arr[$i]['or_no']=$row->or_no;
            $arr[$i]['busns_id_no']=$row->busns_id_no;
            $arr[$i]['taxpayername']=$row->full_name;
            $arr[$i]['businessname']=$row->busn_name;
            $arr[$i]['total_amount']=number_format($row->total_amount, 2, '.', ',');
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

    public function exportmonthlycollection(Request $request){
        $data =$this->_monthlycollection->getListexport($request);
		
        $headers = array(
          'Content-Type' => 'text/csv'
        );
        
        if (!File::exists(public_path()."/files")) {
            File::makeDirectory(public_path() . "/files");
        }
        
        $filename =  public_path("files/monthlycollectionlists.csv");
        $handle = fopen($filename, 'w');
        
        fputcsv($handle, [ 
            'No.',
			'date.',
			'O.R. Number',
			'Business ID',
			'Taxpayer',
			'Business Name',
			'Amount'
           ]);
           $i=1;
           foreach($data['data'] as $row){
				$fullname = $row->rpo_first_name." ".$row->rpo_middle_name." ".$row->rpo_custom_last_name;
				$Date = date("M d, Y",strtotime($row->created_at));
				   fputcsv($handle, [ 
					$i,
					$row->created_at,
					$row->or_no,
					$row->busns_id_no,
					$fullname,
					$row->busn_name,
					number_format($row->total_amount, 2, '.', ',')
				   ]);
				   
			$i++;
           }
          fclose($handle);
          return Response::download($filename, "monthlycollectionlists.csv", $headers);
      }
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'busloc_desc'=>'required|unique:bplo_business_locations,busloc_desc,'.(int)$request->input('id'),
            ]
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }
}
