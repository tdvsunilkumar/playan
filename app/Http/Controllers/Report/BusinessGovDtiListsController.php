<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use App\Models\Report\BusinessGovDtiLists;
use App\Models\CommonModelmaster;
use App\Exports\BusinessGovDtiList;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Barangay;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Session;
use File;
use Response;

class BusinessGovDtiListsController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_businessgovdtilist = new BusinessGovDtiLists(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_Barangay = new Barangay();
        $this->data = array('id'=>'','busloc_desc'=>'');  
        $this->slugs = 'reportsnationalgovdti-lists';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $startdate = date('Y-m-d');
		$enddate = date('Y-m-d');
        $startdate=Date('Y-m-d', strtotime('-30 days'));
        
		Session::forget('dtilidtfromdate'); Session::forget('dtilisttodate'); Session::forget('dtilistsearch');
        $arrDepaertments = array('0'=>'Select Subclass');
         foreach ($this->_businessgovdtilist->GetSubclassesArray() as $val) {
             $arrDepaertments[$val->id]=$val->subclass_description;
         }
        return view('report.businessdtilist.index',compact('startdate','enddate','arrDepaertments'));
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_businessgovdtilist->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
            $arr[$i]['sr_no']=$sr_no;
            $arr[$i]['businessname']=$row->busn_name;
            $arr[$i]['app_type']=$row->btype_desc;
            $arr[$i]['busn_registration_no']=$row->busn_registration_no;
            $arr[$i]['bpi_issued_date']=date('Y-m-d',strtotime($row->bpi_issued_date));
            $arr[$i]['bpi_permit_no']=$row->bpi_permit_no;
            $arr[$i]['busn_app_status']=$row->app_type;
            $arr[$i]['application_date']=$row->application_date;
            $arr[$i]['ownername']=$row->full_name;
            $complete_address=$this->_commonmodel->getbussinesAddress($row->busn_id);
            $arr[$i]['businessaddress']="<div class='showLess'>".wordwrap($complete_address, 40, "<br />\n")."</div>";
            $lineofbdata=$this->_businessgovdtilist->getBusinessPSIC($row->busn_id); $lineofbusiness = "";
            $capitalinvestment = 0; $grosssale = 0;
            foreach ($lineofbdata as $key => $value) { $srno = $key +1;
            	$lineofbusiness .=$srno.'.'.wordwrap($value->subclass_description, 40, "<br />\n")."<br>";
            	$capitalinvestment = $capitalinvestment + $value->busp_capital_investment;
            	$grosssale = $grosssale + $value->busp_total_gross;
            }
            //echo $lineofbusiness; print_r($lineofbdata); exit;
            $arr[$i]['lineofbusiness']="<div class='showLess'>".wordwrap($lineofbusiness, 40, "<br />\n")."</div>";
            $arr[$i]['capitalinvestment']=number_format($capitalinvestment, 2, '.', ',');
            $arr[$i]['grosssale']=number_format($grosssale, 2, '.', ',');
         	$arr[$i]['sizeofbusiness']=$this->getSizeByCap($capitalinvestment);
         	$arr[$i]['ornumber']=$row->ornumber;
         	$arr[$i]['contactno']=$row->p_mobile_no;
         	$arr[$i]['emailaddress']=$row->p_email_address;
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

	public function exportreportsmasterlists(Request $request){
		return Excel::download(new BusinessGovDtiList($request->get('keywords')), 'NationalDtilist_sheet'.time().'.xlsx');
	}
    
}
