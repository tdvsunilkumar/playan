<?php

namespace App\Http\Controllers;

use App\Models\HoReportingRange;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
class MorbidityCaseController extends Controller
{
    
    public $data = [];
    public $arrRange = array(""=>"Select Range","summary"=>"All");

    public function __construct(){
        $this->_reportingrange = new HoReportingRange();
        $this->data = array('id'=>'','rep_range'=>'','rep_remarks'=>'','rep_status'=>'');
		$this->slugs = 'healthy-and-safety/reports/morbid-cases';
        foreach ($this->_reportingrange->getRange() as $id => $val) {
            $this->arrRange[$id]=$val['name'];
        } 
    }
    
    
    public function index(Request $request)
    {
        $arrRange = $this->arrRange;
		return view('morbiditycase.index',compact('arrRange'));
       
    }

    public function store(Request $request){
        $range = (object)$this->arrRange;
        $year = [];
        $yearRange = Carbon::parse('2022-04-21')->toPeriod(Carbon::today(), 1, 'years');
        foreach ($yearRange as $value) {
            $year[$value->year] = $value->year;
        }

        if ($request->isMethod('post')!="") {
            if ($request->input('range') === 'summary') {
                return redirect()->route('morbidity-report',['year'=>$request->input('range_year')]);
            }
            $range = $this->_reportingrange->getRangeDetails($request->input('range'));
            return redirect()->route('morbidity-report-specific',['range'=>$request->input('range'),'year'=>$request->input('range_year')]);
        }
        return view('morbiditycase.create',compact('range','year'));
        
    }
    
    
    public function getList(Request $request){
        $data=$this->_reportingrange->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
             $j=$j+1;
            $arr[$i]['no']=$j;
            $arr[$i]['diag_name']=$row->diag_name;
            $arr[$i]['icd10_details']=$row->icd10_details;
            $arr[$i]['totalM']=$row->totalM;
            $arr[$i]['totalF']=$row->totalF;
            $arr[$i]['grandtotal']=$row->grandtotal;
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
public function formValidation(Request $request){
    $validator = \Validator::make(
        $request->all(), [
            'id,
            rep_range,' .$request->input('rep_range'),
            // 'ra_appraiser_id'=>'required|unique:rpt_appraisers,ra_appraiser_id,'.$request->input('id'),
            // 'ra_appraiser_position'=>'required'
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
