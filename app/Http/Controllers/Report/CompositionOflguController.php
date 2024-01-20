<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Report\CompositionOflgu;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use Response;

class CompositionOflguController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_compositionoflgu = new CompositionOflgu(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','busloc_desc'=>'');  
        $this->slugs = 'reports-composition-lgu-fees';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $startdate = date('Y-m-d');   $enddate = date('Y-m-d');
        $startdate=Date('Y-m-d', strtotime('-15 days'));
        $arrDepaertments = array('0'=>'Select Subclass');
         foreach ($this->_compositionoflgu->GetSubclassesArray() as $val) {
             $arrDepaertments[$val->id]=$val->subclass_description;
         }
        //$arrDepaertments = array('0'=>'All','1'=>'Business Permit', '2'=>'Real Property', '3'=>'Engineering', '4'=>'Occupancy', '5'=>'Planning and Development', '6'=>'Health & Safety','7'=>'Community Tax','8'=>'Burial Permit','9'=>'Miscellaneous');
        
        return view('report.compositionoflgu.index',compact('startdate','enddate','arrDepaertments'));
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_compositionoflgu->getList($request);
        $arrMeasureCharges = array();
         foreach ($this->_compositionoflgu->getCharges('req_measure_pax') as $val) {
            $arrMeasureCharges[$val->id]=$val->charge_desc;
        }
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['description']=$row->accdesc;
            $arr[$i]['transaction']=$row->ctype_desc;
            $arr[$i]['type']=$row->app_type;
            $arr[$i]['effectivitydate']=$row->ptfoc_effectivity_date;
            $arr[$i]['cctype_desc']=$row->cctype_desc;
            $amounthtml="";
            if(!empty($row->ptfoc_json)){
            	$arrayjson = json_decode($row->ptfoc_json);
            	if($row->cctype_id =='6'){
            		$amounthtml ='<table><tr><th style="margin-top:-10px">Bracket</th><th style="margin-top:-10px">Lower Limit</th><th style="margin-top:-10px">Upper Limit</th><th style="margin-top:-10px">Amount<th></tr><tbody>';
            		 $z =1;
            	     foreach ($arrayjson as $key => $val) {
					    	$amounthtml .='<tr><td>'.$z.'</td><td>'.$val->lower_amount.'</td><td>'.$val->upper_amount.'</td><td>'.$val->range_amount.'<td></tr>';
					    	$z++;
            	  }
            	  $amounthtml .='</tbody></table>'; 
              }else if($row->cctype_id =='2'){
              	$amounthtml ='<table><tr><th style="margin-top:-10px">Bracket</th><th style="margin-top:-10px">Formula</th></tr><tbody>';

            		 $z =1;
					$amounthtml .='<tr><td>'.$z.'</td><td>'.$arrayjson->formula.'</td></tr>';
            	    $amounthtml .='</tbody></table>';
              }else if($row->cctype_id =='3'){
              	$amounthtml ='<table><tr><th>Bracket</th><th>Measure Fax</th><th>Amount</th></tr><tbody>';
            		 $z =1;
            		 foreach ($arrayjson as $key => $val) {
					$amounthtml .='<tr><td>'.$z.'</td><td>'.$arrMeasureCharges[$val->charge_id].'</td><td>'.$val->measure_amount.'</td></tr>'; $z++;
				   }
            	    $amounthtml .='</tbody></table>';
              }
              else if($row->cctype_id =='4'){
              	$amounthtml ='<table><tr><th>Bracket</th><th>Month</th><th>Amount</th></tr><tbody>';
            		 $z =1;
            		 foreach ($arrayjson as $key => $val) {
					$amounthtml .='<tr><td>'.$z.'</td><td>'.$val->month_name.'</td><td>'.$val->amount.'</td></tr>'; $z++;
				   }
            	    $amounthtml .='</tbody></table>';
              }
              else if($row->cctype_id =='5'){
              	$amounthtml ='<table><tr><th>Bracket</th><th>name</th><th>Amount</th></tr><tbody>';
            		 $z =1;
            		 foreach ($arrayjson as $key => $val) {
					$amounthtml .='<tr><td>'.$z.'</td><td>'.$val->quarter_name.'</td><td>'.$val->amount.'</td></tr>'; $z++;
				   }
            	    $amounthtml .='</tbody></table>';
              }
			}else{
				$amounthtml ='<table><tr><th>Bracket</th><th>Constant</th></tr><tbody>';
            		 $z =1;
					$amounthtml .='<tr><td>'.$z.'</td><td>'.$row->ptfoc_constant_amount.'</td></tr>';
            	    $amounthtml .='</tbody></table>';
			}
            $arr[$i]['amount']=$amounthtml;
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
