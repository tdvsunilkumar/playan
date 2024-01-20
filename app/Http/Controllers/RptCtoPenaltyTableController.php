<?php

namespace App\Http\Controllers;

use App\Models\RptCtoPenaltyTable;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
use App\Helpers\Helper;

class RptCtoPenaltyTableController extends Controller
{
    
     public $data = [];
     private $slugs;

    public function __construct(){
        $this->_rptctopenaltytable = new RptCtoPenaltyTable();
        
  $this->data = array('id'=>'','cpt_current_year'=>'','cpt_effective_year'=>'','cpt_month_1'=>'','cpt_month_2'=>'','cpt_month_3'=>'','cpt_month_4'=>'','cpt_month_5'=>'','cpt_month_6'=>'','cpt_month_7'=>'','cpt_month_8'=>'','cpt_month_9'=>'','cpt_month_10'=>'','cpt_month_11'=>'','cpt_month_12'=>'');
         $this->slugs = 'real-property/rpt-cto-penalty-table';     
    }
    

    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $currentYearDataFromTable = DB::table('rpt_cto_penalty_tables')->groupby('cpt_current_year')->orderby('cpt_current_year','DESC')->pluck('cpt_current_year','cpt_current_year')->toArray();
        $effectiveYearDataFromTable = DB::table('rpt_cto_penalty_tables')->groupby('cpt_effective_year')->orderby('cpt_effective_year','DESC')->pluck('cpt_effective_year','cpt_effective_year')->toArray();
            $newArray = [
                date('Y')   => date('Y'),
                date('Y')+1 => date('Y')+1,
            ];
            $currentYearDataFromTable += $newArray;
            $collectionCurrentYears = collect($currentYearDataFromTable)->sortDesc()->toArray();
            $effectiveYearDataFromTable += $newArray;
            $collectionEffectYears = collect($effectiveYearDataFromTable)->sortDesc()->toArray();
            return view('rptctopenaltytable.index',compact('collectionCurrentYears','collectionEffectYears'));
        
    }
    public function getList(Request $request){
        $data=$this->_rptctopenaltytable->getList($request);
        $arr=array();
        $i="0";    
        $count = $request->start+1;
        foreach ($data['data'] as $row){
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>'; 
            $arr[$i]['sr_no']=$count;
        	$arr[$i]['cpt_current_year']=$row->cpt_current_year;
            $arr[$i]['cpt_effective_year']=$row->cpt_effective_year;
            $arr[$i]['cpt_month_1']=Helper::decimal_format($row->cpt_month_1);
            $arr[$i]['cpt_month_2']=Helper::decimal_format($row->cpt_month_2);
            $arr[$i]['cpt_month_3']=Helper::decimal_format($row->cpt_month_3);
            $arr[$i]['cpt_month_4']=Helper::decimal_format($row->cpt_month_4);
            $arr[$i]['cpt_month_5']=Helper::decimal_format($row->cpt_month_5);
            $arr[$i]['cpt_month_6']=Helper::decimal_format($row->cpt_month_6);
            $arr[$i]['cpt_month_7']=Helper::decimal_format($row->cpt_month_7);
            $arr[$i]['cpt_month_8']=Helper::decimal_format($row->cpt_month_8);
            $arr[$i]['cpt_month_9']=Helper::decimal_format($row->cpt_month_9);
            $arr[$i]['cpt_month_10']=Helper::decimal_format($row->cpt_month_10);
            $arr[$i]['cpt_month_11']=Helper::decimal_format($row->cpt_month_11);
            $arr[$i]['cpt_month_12']=Helper::decimal_format($row->cpt_month_12);
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptctopenaltytable/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Penalty Rate Scheduler">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                </div>
                '.$status.'
                </div>' ;
            
            $i++;
            $count++;
        }
        
        $totalRecords=$data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }

    public function activeInactive(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_rptctopenaltytable->updateData($id,$data);
    }

    public function checkArrayIsConsecutive($array=''){
        $a = $array;
        $res = [];
$stage = [];

foreach($a as $i) {
    if(count($stage) > 0 && $i != $stage[count($stage)-1]+1) {
        if(count($stage) > 1) {
            $res[] = $stage;
        }
        $stage = [];
    }
    $stage[] = $i;

}
dd($res);
    }



    public function store(Request $request){
        $data = (object)$this->data;
       

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptCtoPenaltyTable::find($request->input('id'));
            
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_rptctopenaltytable->updateData($request->input('id'),$this->data);
                $success_msg = 'Rpt Cto Penalty Table updated successfully.';
            }else{
                $this->data['is_active'] = 1;
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
               
                $this->_rptctopenaltytable->addData($this->data);
                $success_msg = 'Rpt Cto Penalty Table added successfully.';
            }
            return redirect()->route('rptctopenaltytable.index')->with('success', __($success_msg));
        }
        return view('rptctopenaltytable.create',compact('data'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'cpt_current_year'=>'required',
                'cpt_effective_year' => 'required|lte:cpt_current_year|unique:rpt_cto_penalty_tables,cpt_effective_year,'.$request->id.',id,cpt_current_year,'.$request->cpt_current_year,
                'cpt_month_1' => 'required|numeric|between:0,99.99',
                'cpt_month_2' => 'required|numeric|between:0,99.99',
                'cpt_month_3' => 'required|numeric|between:0,99.99',
                'cpt_month_4' => 'required|numeric|between:0,99.99',
                'cpt_month_5' => 'required|numeric|between:0,99.99',
                'cpt_month_6' => 'required|numeric|between:0,99.99',
                'cpt_month_7' => 'required|numeric|between:0,99.99',
                'cpt_month_8' => 'required|numeric|between:0,99.99',
                'cpt_month_9' => 'required|numeric|between:0,99.99',
                'cpt_month_10' => 'required|numeric|between:0,99.99',
                'cpt_month_11' => 'required|numeric|between:0,99.99',
                'cpt_month_12' => 'required|numeric|between:0,99.99',
            ],[
                'cpt_current_year.required'=>'Required Field',
                'cpt_effective_year.lte' => 'Should be equal or less than Current Year',
                'cpt_effective_year.unique' => 'Already Exists',
                'cpt_effective_year.required' => 'Required Field',
                'cpt_month_1.required' => 'Required Field',
                'cpt_month_1.numeric' => 'Invalid Value',
                'cpt_month_1.between' => 'Invalid Value',
                'cpt_month_2.required' => 'Required Field',
                'cpt_month_2.numeric' => 'Invalid Value',
                'cpt_month_2.between' => 'Invalid Value',
                'cpt_month_3.required' => 'Required Field',
                'cpt_month_3.numeric' => 'Invalid Value',
                'cpt_month_3.between' => 'Invalid Value',
                'cpt_month_4.required' => 'Required Field',
                'cpt_month_4.numeric' => 'Invalid Value',
                'cpt_month_4.between' => 'Invalid Value',
                'cpt_month_5.required' => 'Required Field',
                'cpt_month_5.numeric' => 'Invalid Value',
                'cpt_month_5.between' => 'Invalid Value',
                'cpt_month_6.required' => 'Required Field',
                'cpt_month_6.numeric' => 'Invalid Value',
                'cpt_month_6.between' => 'Invalid Value',
                'cpt_month_7.required' => 'Required Field',
                'cpt_month_7.numeric' => 'Invalid Value',
                'cpt_month_7.between' => 'Invalid Value',
                'cpt_month_8.required' => 'Required Field',
                'cpt_month_8.numeric' => 'Invalid Value',
                'cpt_month_8.between' => 'Invalid Value',
                'cpt_month_9.required' => 'Required Field',
                'cpt_month_9.numeric' => 'Invalid Value',
                'cpt_month_9.between' => 'Invalid Value',
                'cpt_month_10.required' => 'Required Field',
                'cpt_month_10.numeric' => 'Invalid Value',
                'cpt_month_10.between' => 'Invalid Value',
                'cpt_month_11.required' => 'Required Field',
                'cpt_month_11.numeric' => 'Invalid Value',
                'cpt_month_11.between' => 'Invalid Value',
                'cpt_month_12.required' => 'Required Field',
                'cpt_month_12.numeric' => 'Invalid Value',
                'cpt_month_12.between' => 'Invalid Value',
            ]
        );
        $validator->after(function ($validator) {
            $data = $validator->getData();
            $getLastEntryForCurrentYeat = DB::table('rpt_cto_penalty_tables')->where('cpt_current_year',$data['cpt_current_year'])->latest()->first();
            $getLastEntry = DB::table('rpt_cto_penalty_tables')->latest()->first();
            if($getLastEntryForCurrentYeat != null && $data['id'] == ''){
                if($data['cpt_effective_year'] != $getLastEntryForCurrentYeat->cpt_effective_year+1){
                    $validator->errors()->add('cpt_effective_year', 'There is gap between last saved effective year');
                }
            }
            if($getLastEntry != null && $data['id'] == ''){
            if($data['cpt_current_year'] !=$getLastEntry->cpt_current_year && $getLastEntry->cpt_current_year != $getLastEntry->cpt_effective_year){
                    $validator->errors()->add('cpt_current_year', 'Your last year data is Pending');
            }
        }
    });
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        //dd();
        echo json_encode($arr);exit;
    }

   
    public function Delete(Request $request){
        $id = $request->input('id');
            $RptCtoPenaltyTable = RptCtoPenaltyTable::find($id);
            if($RptCtoPenaltyTable->created_by == \Auth::user()->creatorId()){
                $RptCtoPenaltyTable->delete();
            }
    }
}
