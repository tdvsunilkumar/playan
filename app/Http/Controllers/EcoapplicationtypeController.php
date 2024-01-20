<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Ecoapplicationtype;
use App\Models\EcoServiceTypedetails;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class EcoapplicationtypeController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
	 public $arrbarangay = array("" => "Please Select");
     public function __construct(){
        $this->_ecoapplicationtype = new Ecoapplicationtype();
		$this->_ecoservicetypedetails = new EcoServiceTypedetails();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','barangay_id'=>'','est_service_type'=>'','est_addtional_info'=>'','est_year_month'=>'');  
        $this->slugs = 'eco-application-type';
		foreach ($this->_ecoapplicationtype->getBarangay() as $val) {
            $this->arrbarangay[$val->id]=$val->brgy_name ." / ".$val->reception_name;
        }
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('ecoapplicationtype.index');
    }


    public function getList(Request $request){
		$location =$this->arrbarangay;
         $this->is_permitted($this->slugs, 'read');
        $data=$this->_ecoapplicationtype->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/ecoapplicationtype/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Reception Details">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->est_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['est_service_type']=(isset($location[$row->barangay_id]))? $location[$row->barangay_id]:'';
            $arr[$i]['est_status']=($row->est_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
        $data=array('est_status' => $is_activeinactive);
        $this->_ecoapplicationtype->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Reception Details ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
		$location =$this->arrbarangay;
        $data = (object)$this->data;
		$typedetails = $this->_ecoservicetypedetails->getEditDetails($request->input('id'));
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_ecoapplicationtype->getEditDetails($request->input('id'));
			
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_ecoapplicationtype->updateData($request->input('id'),$this->data);
						if(isset($typedetails->id) > 0){
							 EcoServiceTypedetails:: where('id',$typedetails->id)->update([
								'est_id' => $request->input('id'),
								'eat_additional_info'=>$request->input('est_addtional_info'),
								'eatd_discount'=>$request->input('eatd_discount'),
								'eatd_process_type'=>$request->input('eatd_process_type'),
								'eatd_amount_type'=>$request->input('eatd_amount_type'),
								'multiplier'=>$request->input('multiplier'),
								'excess'=>$request->input('excess'),
								'excess_amount'=>$request->input('excess_amount'),
								]);
						 
						 }else{
							 $EcoServicedetails = new EcoServiceTypedetails();
							 $EcoServicedetails->est_id = $request->input('id');
							 $EcoServicedetails->eat_additional_info =$request->input('est_addtional_info');
							 $EcoServicedetails->eatd_discount = $request->input('eatd_discount');
							 $EcoServicedetails->eatd_process_type = $request->input('eatd_process_type');
							 $EcoServicedetails->eatd_amount_type =$request->input('eatd_amount_type');
							 $EcoServicedetails->multiplier = $request->input('multiplier');
							 $EcoServicedetails->excess = $request->input('excess');
							 $EcoServicedetails->excess_amount =$request->input('excess_amount');
							 $EcoServicedetails->eatd_status =1;
							 $EcoServicedetails->created_by = \Auth::user()->id;
							 $EcoServicedetails->created_at = date('Y-m-d H:i:s');
							 $EcoServicedetails->save(); 
						 }
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."'Updated Reception Details'"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
				$this->data['est_service_type'] = 'nothing';
                $this->data['est_status'] = 1;
                $request->id = $this->_ecoapplicationtype->addData($this->data);
				if($request->input('est_addtional_info') > 0){
					 $EcoServicedetails = new EcoServiceTypedetails();
					 $EcoServicedetails->est_id = $request->id;
					 $EcoServicedetails->eat_additional_info =$request->input('est_addtional_info');
					 $EcoServicedetails->eatd_discount = $request->input('eatd_discount');
					 $EcoServicedetails->eatd_process_type = $request->input('eatd_process_type');
					 $EcoServicedetails->eatd_amount_type =$request->input('eatd_amount_type');
					 $EcoServicedetails->multiplier = $request->input('multiplier');
					 $EcoServicedetails->excess = $request->input('excess');
					 $EcoServicedetails->excess_amount =$request->input('excess_amount');
					 $EcoServicedetails->eatd_status =1;
					 $EcoServicedetails->created_by = \Auth::user()->id;
					 $EcoServicedetails->created_at = date('Y-m-d H:i:s');
					 $EcoServicedetails->save();
				}
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."'Added Reception Details'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('ecoapplicationtype.index')->with('success', __($success_msg));
        }
        return view('ecoapplicationtype.create',compact('data','location','typedetails'));
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(),[
                'barangay_id'=>'required',
            ],[
				'barangay_id.required'=>'The Location / Reception field is required.',
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
