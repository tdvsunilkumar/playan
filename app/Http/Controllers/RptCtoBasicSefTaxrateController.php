<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\RptCtoBasicSefTaxrate;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class RptCtoBasicSefTaxrateController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public $arrClassCode = array(""=>"Please Select"); 
     public function __construct(){
		$this->_ctobasicseftaxrate = new RptCtoBasicSefTaxrate(); 
        $this->_commonmodel = new CommonModelmaster();
        

        $this->data = array('id'=>'','pc_class_code'=>'','bsst_basic_rate'=>'','bsst_sef_rate'=>'','bsst_sh_rate'=>'','assessed_value_max_amount' => '');  
        $this->slugs = 'rptctobasicseftaxrate';
        foreach ($this->_ctobasicseftaxrate->getClassCode() as $val) {
            $this->arrClassCode[$val->id]=$val->pc_class_code.'-'.$val->pc_class_description;
        }  
    }
    
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('rptctobasicseftaxrate.index');
        
    }
    public function getList(Request $request){
        $data=$this->_ctobasicseftaxrate->getList($request);
        $arr=array();
        $i="0";
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$j;
            $arr[$i]['pc_class_code']=$row->pc_class_code.'-'.$row->pc_class_description;
           /* $arr[$i]['property_class_description']=$row->pc_class_description;*/
            $arr[$i]['bsst_basic_rate']=$row->bsst_basic_rate;
            $arr[$i]['bsst_sef_rate']=$row->bsst_sef_rate;
            $arr[$i]['bsst_sh_rate']=$row->bsst_sh_rate;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            
           
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptctobasicseftaxrate/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Fixed Taxes & Fees">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>';
              // <div class="action-btn bg-danger ms-2">
              //       <a href="#" class="mx-3 btn btn-sm deleterow ti-trash text-white text-white" id='.$row->id.'>
              //       </a>
              //   </div>
           
           
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

    public function ActiveInactive(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_ctobasicseftaxrate->updateActiveInactive($id,$data);
}   
    public function store(Request $request){
        $data = (object)$this->data;
        $arrClassCode = $this->arrClassCode;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_ctobasicseftaxrate->editBasicSefTaxrate($request->input('id'));
           
        }
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_ctobasicseftaxrate->updateData($request->input('id'),$this->data);
                $success_msg = 'Cto Basic Taxrate updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
               
                $this->_ctobasicseftaxrate->addData($this->data);
                $success_msg = 'Cto Basic Taxrate added successfully.';
            }
            return redirect()->route('rptctobasicseftaxrate.index')->with('success', __($success_msg));
    	}
        return view('rptctobasicseftaxrate.create',compact('data','arrClassCode'));
	}
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'pc_class_code'=>'required',
                'bsst_basic_rate'=>'required|numeric|between:0,99.99',
                'bsst_sef_rate'=>'required|numeric|between:0,99.99',
                'bsst_sh_rate' => 'required|numeric|between:0,99.99',

            ],[
                'pc_class_code.required'=>'Required Field',
                'bsst_basic_rate.required'=>'Required Field',
                'bsst_basic_rate.numeric'=>'Invalid Value',
                'bsst_basic_rate.between'=>'Invalid Value',
                'bsst_sef_rate.required'=>'Required Field',
                'bsst_sef_rate.numeric'=>'Invalid Value',
                'bsst_sef_rate.between'=>'Invalid Value',
                'bsst_sh_rate.required' => 'Required Field',
                'bsst_sh_rate.numeric'=>'Invalid Value',
                'bsst_sh_rate.between'=>'Invalid Value',
            ]
        );
        $validator->after(function ($validator) {
            $data = $validator->getData();
           // dd($data);
            if($data['bsst_sh_rate'] > 0 && ($data['assessed_value_max_amount'] == '' || $data['assessed_value_max_amount'] == 0)){
                $validator->errors()->add('assessed_value_max_amount', 'Required Field');
            }
    });
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }

    public function Delete(Request $request){
        $id = $request->input('id');
            $rptctobasicseftaxrate = RptCtoBasicSefTaxrate::find($id);
            if($rptctobasicseftaxrate->created_by == \Auth::user()->creatorId()){
                $rptctobasicseftaxrate->delete();
            }
    }
    }

