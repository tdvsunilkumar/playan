<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use App\Models\Report\ListofBusinessperBar;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Barangay;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use DB;
use Response;
use Session;
class ListofBusinessperBarangay extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
	 public $arrYears = array(""=>"Select year");
     public function __construct(){
        $this->_ListofBusinessperBar = new ListofBusinessperBar(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_Barangay = new Barangay();
        $this->data = array();  
        $this->slugs = 'reports-masterlists-listof-business-per-barangay';
		$arrYrs = $this->_ListofBusinessperBar->getYearDetails();
        foreach($arrYrs AS $key=>$val){
            $this->arrYears[$val->bpi_year] =$val->bpi_year;
        }
    }
	
	
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
		$arrYears = $this->arrYears;
		$year =  date('Y');
		$to_date= date('Y-m-d');
        $from_date= date('Y-m-d');
		$from_date=Date('Y-m-d', strtotime('-30 days'));
		return view('report.ListofBusiness.index')->with(compact('year','arrYears','to_date','from_date'));
	}
	
    public function getList(Request $request){
		
       $this->is_permitted($this->slugs, 'read');
       $data=$this->_ListofBusinessperBar->getList($request);
	   
       $arr=array();
       $i="0";    
       $sr_no=(int)$request->input('start')-1; 
       $sr_no=$sr_no>0? $sr_no+1:0;
	   //echo "<pre>"; print_r($data); exit;
       foreach ($data['data'] as $row){
           $sr_no=$sr_no+1;
		  
           $complete_address=$this->_commonmodel->getbussinesAddress($row->busn_id);
		   $arr[$i]['srno']=$sr_no;
           $arr[$i]['busns_id_no']=$row->busns_id_no;
           $arr[$i]['taxpayer_name']=$row->rpo_first_name." ".$row->rpo_middle_name." ".$row->rpo_custom_last_name." ".$row->suffix;
           $arr[$i]['busn_name']=$row->busn_name;
		   $arr[$i]['location_address']="<div class='showLess'>".wordwrap($complete_address, 40, "\n<br>")."</div>";
		   if($row->app_code==1){
			$arr[$i]['app_type_id']='New';
		   }elseif($row->app_code==2){
			$arr[$i]['app_type_id']='Renewal';
		   }else{
			$arr[$i]['app_type_id']='Retire';  
		   }
		   
           $arr[$i]['bpi_issued_date']=date("Y-m-d ",strtotime($row->bpi_issued_date));
		   if($row->pm_id==1){
			   $arr[$i]['mode_of_payment']=config('constants.payMode')[$row->pm_id];
		   }elseif($row->pm_id==2){
			   $arr[$i]['mode_of_payment']=config('constants.payMode')[$row->pm_id];
		   }elseif($row->pm_id==3){
			   $arr[$i]['mode_of_payment']=config('constants.payMode')[$row->pm_id];
		   }else{
			   $arr[$i]['mode_of_payment']='';
		   }
		   $arr[$i]['total_assessment']=$row->totalassessment;
		   $arr[$i]['amount_paid']=$row->totalpaidamount;
		   $arr[$i]['remarks']=$row->bpi_remarks;
		   
           $i++;
       }
       
       $totalRecords=$data['data_cnt'];
       $json_data = array(
           "recordsTotal"    => intval($totalRecords),  
           "recordsFiltered" => intval($totalRecords),
           "data"            => $arr   // total data array
       );
       echo json_encode($json_data);
   }
   
   public function exportlistofbusiness(Request $request){
       
        $data=$this->_ListofBusinessperBar->exportdata($request);
		$from_date=$request->input('from_date');
		// echo $from_date;exit;
        $headers = array(
          'Content-Type' => 'text/csv'
        );
        
        if (!File::exists(public_path()."/files")) {
            File::makeDirectory(public_path() . "/files");
        }
        
        $filename =  public_path("files/listofbusinessperbarangay.csv");
        $handle = fopen($filename, 'w');
        
        fputcsv($handle, [ 
            'No.',
			'Business Id-No.',
			'Name Of Proprietor',
			'Business Name',
			'Location of Business',
			'Status (New/Renewal)',
			'Date Issued',
			'Mode Of Payment',
			'Total Assessment',
			'Amount Paid',
			'Remarks'
           ]);
           $i=1;
           foreach($data['data'] as $row){
					$taxpayer_name = (!empty($row->rpo_first_name) ? $row->rpo_first_name . ' ' : '') . (!empty($row->rpo_middle_name) ? $row->rpo_middle_name . ' ' : '') . (!empty($row->rpo_custom_last_name) ? $row->rpo_custom_last_name . ', ' : '') . (!empty($row->suffix) ? $row->suffix . ' ' : '');
			   	   $complete_address=$this->_commonmodel->getbussinesAddress($row->busn_id);
					if($row->app_code==1){
						$app_type_id='New';
				    }elseif($row->app_code==2){
						$app_type_id='Renewal';
				    }else{
						$app_type_id='Retire';  
				    }
				    if($row->pm_id==1){
					   $payMode=config('constants.payMode')[$row->pm_id];
				    }elseif($row->pm_id==2){
					   $payMode=config('constants.payMode')[$row->pm_id];
				    }elseif($row->pm_id==3){
					   $payMode=config('constants.payMode')[$row->pm_id];
				    }else{
					   $payMode='';
				    }
				   fputcsv($handle, [ 
					$i,
					$row->busns_id_no,
					$taxpayer_name,
					$row->busn_name,
					$complete_address,
					$app_type_id,
					date("Y-m-d ",strtotime($row->bpi_issued_date)),
					$payMode,
					$row->totalassessment,
					$row->totalpaidamount,
                    $row->bpi_remarks
				   ]);
				   
			$i++;
           }
          fclose($handle);
          return Response::download($filename, "listofbusinessperbarangay.csv", $headers);
      }
}