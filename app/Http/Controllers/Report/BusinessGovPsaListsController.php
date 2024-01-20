<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use App\Models\Report\BusinessGovPsaLists;
use App\Models\CommonModelmaster;
use App\Exports\BusinessGovPsaExportList;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Barangay;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Session;
use File;
use Response;

class BusinessGovPsaListsController extends Controller
{
    public $data = [];
     public $postdata = [];
     private $slugs;
     public $typeofowner = array(""=>"Please Select");
     public function __construct(){
        $this->_businessgovpsalist = new BusinessGovPsaLists(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_Barangay = new Barangay();
        $this->data = array('id'=>'','busloc_desc'=>'');  
        $this->slugs = 'reportsnationalgovpsa-lists';
        foreach ($this->_businessgovpsalist->getTypeofOwnership() as $val){
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
         foreach ($this->_businessgovpsalist->GetSubclassesArray() as $val) {
             $arrDepaertments[$val->id]=$val->subclass_description;
         }
         $typeofowner = $this->typeofowner;
        //$arrDepaertments = array('0'=>'All','1'=>'Business Permit', '2'=>'Real Property', '3'=>'Engineering', '4'=>'Occupancy', '5'=>'Planning and Development', '6'=>'Health & Safety','7'=>'Community Tax','8'=>'Burial Permit','9'=>'Miscellaneous');
        
        return view('report.businesspsalist.index',compact('startdate','enddate','arrDepaertments','typeofowner'));
    }

    public function getList(Request $request){
        $tfocids = $this->_businessgovpsalist->gettfocsids();
        $tfocidarray = array();
        foreach ($tfocids as $key => $value) {
            array_push($tfocidarray, $value->tfoc_id);
        }
        
        $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_businessgovpsalist->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
        	$arr[$i]['sr_no']=$sr_no;
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['ownername']=$row->rpo_first_name." ".$row->rpo_middle_name." ".$row->rpo_custom_last_name;
            $lineofbdata=$this->_businessgovpsalist->getBusinessPSIC($row->busn_id); $lineofbusiness = "";
            $capitalinvestment = 0; $grosssale = 0;
            foreach ($lineofbdata as $key => $value) { $srno = $key +1;
            	$lineofbusiness .=$srno.'.'.wordwrap($value->subclass_description, 40, "<br />\n")."<br>";
            	$capitalinvestment = $capitalinvestment + $value->busp_capital_investment;
            	$grosssale = $grosssale + $value->busp_total_gross;
            }
           $arr[$i]['lineofbusiness']=$lineofbusiness;
           if($row->app_code == 1){
            $arr[$i]['grosssale']=number_format($row->capitalinvestment, 2, '.', ',');
           }else{
            $arr[$i]['grosssale']=number_format($row->grosssale, 2, '.', ',');
           }
           $businesstaxdata = $this->_businessgovpsalist->getBusinesstax($tfocidarray,$row->busn_id);
     
           $arr[$i]['businesstax']=number_format($businesstaxdata->amount,2);
           $arr[$i]['app_status']=config('constants.arrBusinessApplicationType')[$row->app_code]; 
           $arr[$i]['totalnoofemp']=$row->busn_employee_total_no;
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
		return Excel::download(new BusinessGovPsaExportList($request->get('keywords')), 'BusinessGovPsaList_sheet'.time().'.xlsx');
	}
}
