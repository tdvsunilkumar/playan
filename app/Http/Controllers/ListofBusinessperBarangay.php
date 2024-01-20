<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\ListofBusinessperBar;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use DB;
use Response;
class ListofBusinessperBarangay extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
	 public $arrYears = array(""=>"Select year");
     public function __construct(){
        $this->_ListofBusinessperBar = new ListofBusinessperBar(); 
        $this->_commonmodel = new CommonModelmaster();
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
		return view('ListofBusiness.index')->with(compact('year','arrYears'));
	}
	
    public function getList(Request $request){
		
       $this->is_permitted($this->slugs, 'read');
       $data=$this->_ListofBusinessperBar->getList($request);

       $arr=array();
       $i="0";    
       $sr_no=(int)$request->input('start')-1; 
       $sr_no=$sr_no>0? $sr_no+1:0;
	   
       foreach ($data['data'] as $row){
           $sr_no=$sr_no+1;
		   
           $arr[$i]['srno']=$sr_no;
           $arr[$i]['barangay']=$row->brgy_name;
           $arr[$i]['new_application']=$row->new_application;
           $arr[$i]['renewal']=$row->renewal_application;
           $arr[$i]['total']=$row->new_application + $row->renewal_application;
		   
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
        $data=$this->_ListofBusinessperBar->getDataPerBarngay();
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
			'Barangay',
			'New Application',
			'Renewal',
			'Total',
        ]);
        $i=1;
        foreach($data as $row){
		    fputcsv($handle, [ 
				$i,
				$row->brgy_name,
				$row->new_application,
				$row->renewal_application,
				$row->new_application + $row->renewal_application,
		    ]);
		    $i++;
        }
        fclose($handle);
        return Response::download($filename, "Total Of Business Per Barangay.csv", $headers);
      }
}