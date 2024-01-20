<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use App\Models\Report\BploTaxFeeOtherCharges;
use App\Models\CommonModelmaster;
use App\Exports\FinancialTfocList;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Session;
use File;
use Response;

class BploTaxFeeOtherChargesController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public $typeofowner = array(""=>"Please Select");
     public function __construct(){
        $this->_bblotaxfeesothercharges = new BploTaxFeeOtherCharges(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->slugs = 'reportfanancetaxfeecharges';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $startdate = date('Y-m-d');   $enddate = date('Y-m-d');
        $startdate=Date('Y-m-d', strtotime('-15 days'));  
        $startdate = ""; $enddate="";  
        return view('report.fanancialtfoclist.index',compact('startdate','enddate'));
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_bblotaxfeesothercharges->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
        	$arr[$i]['sr_no']=$sr_no;
            $arr[$i]['description']=$row->accdesc;
            $arr[$i]['totalpaid']=number_format($row->totalpaidamount, 2, '.', ',');
            $arr[$i]['totalpenalty']=number_format($row->totalpenalty, 2, '.', ',');
            $arr[$i]['credit']=number_format(0, 2, '.', ',');
            $total = $row->totalpaidamount + $row->totalpenalty;
            $arr[$i]['total']=number_format($total, 2, '.', ',');
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

	public function exportreportstfoclists (Request $request){
		return Excel::download(new FinancialTfocList($request->get('keywords')), 'FinancialTfoc_sheet'.time().'.xlsx');
	}
}
