<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\CemeteryList;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class CemeteryListController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
	 public $arrCemeteryStyle = array("" => "Please Select");
	 public $arrCemeteryName = array("" => "Please Select");
     public function __construct(){
        $this->_cemeterylist = new CemeteryList(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','brgy_id'=>'','ec_id'=>'','ecs_id'=>'','ecl_street'=>'','ecl_block'=>'','ecl_lot_no_from'=>'','ecl_lot_no_to'=>'','ecl_slot'=>'');  
        $this->slugs = 'cemetery-list';
		
		foreach($this->_cemeterylist->CemeteryStyle() as $val) {
            $this->arrCemeteryStyle[$val->id]=$val->eco_cemetery_style;
        }
		foreach ($this->_cemeterylist->getBarangay() as $val) {
            $this->arrCemeteryName[$val->id]=$val->brgy_name ." => ".$val->cem_name;
        }
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('cemeterylist.index');
    }


    public function getList(Request $request){
		$cemeterystyle =$this->arrCemeteryStyle;
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_cemeterylist->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cemeterylist/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Cemetery List">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['brgy_id']=$row->brgy_name;
			$arr[$i]['ec_id']=$row->cem_name;
			$arr[$i]['ecs_id']=$cemeterystyle[$row->ecs_id];
			$arr[$i]['ecl_street']=$row->ecl_street;
			$arr[$i]['ecl_block']=$row->ecl_block;
			$arr[$i]['ecl_lot_no_from']=$row->ecl_lot_no_from;
			$arr[$i]['ecl_lot_no_to']=$row->ecl_lot_no_to;
			$arr[$i]['ecl_slot']=$row->ecl_slot;
			$arr[$i]['count']='';
            $arr[$i]['status']=($row->status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']=$actions;
           
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
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('status' => $is_activeinactive);
        $this->_cemeterylist->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Cemetery List ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
		$CemeteryName = $this->arrCemeteryName;
		$cemeterystyle =$this->arrCemeteryStyle;
		
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_cemeterylist->getEditDetails($request->input('id'));
			
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_cemeterylist->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Cemetery List '"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['status'] = 1;
                $request->id = $this->_cemeterylist->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Cemetery List '"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('cemeterylist.index')->with('success', __($success_msg));
        }
        return view('cemeterylist.create',compact('data','cemeterystyle','CemeteryName'));
    }
    
    public function getbrgyid(Request $request){
        $id= $request->input('id');
        $data = $this->_cemeterylist->getbrgyid($id);
        return $data->brgy_id;

    }
	
	public function lotnolist(Request $request){
        $ecl_id= $request->input('id');
		$ifo= $request->input('ifo');
		$to= $request->input('to');
        $data = $this->_cemeterylist->lotnolist($ecl_id);
		$lotno=array();
		
		foreach($data as  $row){
			$lotno[]=$row->ecl_lot;
		}
		
		for ($i = $ifo; $i <= $to; $i++){
			$ilist[] = $i;
		}
		if(!empty($lotno)){
			$arrlistlot = array_diff($ilist,$lotno);
		}else{
			$arrlistlot =$ilist;
		}
		$totallist = count($arrlistlot);
		echo json_encode($arrlistlot);
    }
	
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
				'ec_id'=>'required',
				'ecs_id'=>'required',
                'ecl_block'=>'required|unique:eco_cemeteries_lists,ecl_block,'.(int)$request->input('id'),
				'ecl_lot_no_from'=>'required',
				'ecl_lot_no_to'=>'required',
				
            ],
			[
				'ec_id.required' => 'The Cemetery name field is required.',
				'ecs_id.required' => 'The Cemetery style field is required.',
				'ecl_block.required' => 'The block no field is required.',
				'ecl_block.unique' => 'The block has already been taken.',
				'ecl_lot_no_from.required' => 'The Lot. No. From field is required.',
				'ecl_lot_no_to.required' => 'The Lot. No. To field is required.',
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
