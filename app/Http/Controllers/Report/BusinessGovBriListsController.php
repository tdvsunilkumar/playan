<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use App\Models\Report\BusinessGovBriLists;
use App\Models\CommonModelmaster;
use App\Exports\BusinessGovBriList;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Barangay;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Session;
use File;
use Response;

class BusinessGovBriListsController extends Controller
{
    public $data = [];
     public $postdata = [];
     private $slugs;
     public $typeofowner = array(""=>"Please Select");
     public function __construct(){
        $this->_businessgovbrilist = new BusinessGovBriLists(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_Barangay = new Barangay();
        $this->data = array('id'=>'','busloc_desc'=>'');  
        $this->slugs = 'reportsnationalgovbir-lists';
        foreach ($this->_businessgovbrilist->getTypeofOwnership() as $val){
             $this->typeofowner[$val->id]=$val->btype_desc;
         } 
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $startdate = date('Y-m-d');   $enddate = date('Y-m-d');
        $startdate=Date('Y-m-d', strtotime('-30 days'));  
        Session::forget('birlidtfromdate'); Session::forget('birlisttodate'); Session::forget('birlistsearch');
        $arrDepaertments = array('0'=>'Select Subclass');
         foreach ($this->_businessgovbrilist->GetSubclassesArray() as $val) {
             $arrDepaertments[$val->id]=$val->subclass_description;
         }
         $typeofowner = $this->typeofowner;
        
        
        return view('report.businessbirlist.index',compact('startdate','enddate','arrDepaertments','typeofowner'));
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_businessgovbrilist->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
        	$arr[$i]['sr_no']=$sr_no;
            $arr[$i]['busns_id_no']=$row->busns_id_no;
            $arr[$i]['bpi_permit_no']=$row->bpi_permit_no;
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['ownername']=$row->full_name;
            $arr[$i]['busn_tin_no']=$row->busn_tin_no;
            $complete_address=$this->_commonmodel->getbussinesAddress($row->busn_id);
            $arr[$i]['businessaddress']="<div class='showLess'>".wordwrap($complete_address, 40, "<br />\n")."</div>";
            $lineofbdata=$this->_businessgovbrilist->getBusinessPSIC($row->busn_id); $lineofbusiness = "";
            $capitalinvestment = 0; $grosssale = 0;
            foreach ($lineofbdata as $key => $value) { $srno = $key +1;
            	$lineofbusiness .=$srno.'.'.wordwrap($value->subclass_description, 40, "<br />\n")."<br>";
            	$capitalinvestment = $capitalinvestment + $value->busp_capital_investment;
            	$grosssale = $grosssale + $value->busp_total_gross;
            }
           $arr[$i]['natureofbusiness']="<div class='showLess'>".wordwrap($lineofbusiness, 40, "<br />\n")."</div>";
           $arr[$i]['grosssale']=number_format($grosssale, 2, '.', ',');
           $arr[$i]['app_status']=config('constants.arrBusinessApplicationType')[$row->app_code]; 
           $arr[$i]['registartiondate']=date('Y-m-d',strtotime($row->created_at));
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

    public function getSizeByCap($val){
		if($val <= 150000){
			return "Micro";
		}elseif ($val >= 150001 && $val <= 5000000) {
			return "Small";
		}elseif ($val >= 5000001 && $val <= 20000000) {
			return "Medium";
		}
		elseif ($val >= 20000001) {
			return "Large";
		}
	}

    public function multi_array_key_exists($key, array $array): bool
	{
	    if (array_key_exists($key, $array)) {
	        return true;
	    } else {
	        foreach ($array as $nested) {
	            if (is_array($nested) && multi_array_key_exists($key, $nested))
	                return true;
	        }
	    }
	    return false;
	}

	public function exportreportsbrilists (Request $request){
		return Excel::download(new BusinessGovBriList($request->get('keywords')), 'NationalBirlist_sheet'.time().'.xlsx');
	}
}
