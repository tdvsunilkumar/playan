<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\HealthSafetySetupDataService;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class HealthSafetySetupDataServiceController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
	 public $getServices = array(""=>"Please Select");
	 public $arrtransactiontype = array(""=>"Please Select");
	 public $arrdepartment = array(""=>"Please Select");
	 public $arrserviceform = array(""=>"Please Select");
     public function __construct(){
        $this->_HealthSafetySetup = new HealthSafetySetupDataService(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','tfoc_id'=>'','ho_service_name'=>'','ho_service_description'=>'','top_transaction_type_id'=>'','ho_service_department'=>'','ho_service_amount'=>'','ho_service_form'=>'');  
        $this->slugs = 'health-safety-setup-data-service';
		 foreach ($this->_HealthSafetySetup->getServices() as $val) {
             $this->getServices[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
         }
		 foreach ($this->_HealthSafetySetup->getTransactiontype() as $val) {
             $this->arrtransactiontype[$val->id]=$val->id." - ".$val->ttt_desc;
         }
		foreach(config('constants.rehoservicedepartment') as $key => $val) {
			 $this->arrdepartment[$key]=$val;
		}
		foreach(config('constants.rehoserviceform') as $key => $val) {
			 $this->arrserviceform[$key]=$val;
		}
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('HoServices.index');
    }
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
       
		$servicedepartment =$this->arrdepartment;
		$serviceform =$this->arrserviceform;
		$getServices = $this->getServices;
		$arrtransactiontype = $this->arrtransactiontype;
		
	    $data=$this->_HealthSafetySetup->getList($request);
		
		$arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if($this->is_permitted($this->slugs, 'update', 1) > 0){
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/health-safety-setup-data-service/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Services">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if($this->is_permitted($this->slugs, 'delete', 1) > 0){
                $actions .=($row->ho_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $arr[$i]['srno'] = $sr_no;
			$arr[$i]['tfoc_id'] = $row->tfocID ? '['.$row->gl_code.' - '.$row->gl_description.']=>['.$row->sl_prefix.' - '.$row->sl_description.']' : '';
			$arr[$i]['ho_service_name']=$row->ho_service_name;
			$arr[$i]['ho_service_description']=$row->ho_service_description;
			$arr[$i]['ho_service_department']=($row->ho_service_department > 0)?$servicedepartment[$row->ho_service_department]: '';
            $arr[$i]['top_transaction_type_id']=($row->top_transaction_type_id > 0)?$arrtransactiontype[$row->top_transaction_type_id]: '';
			$arr[$i]['ho_service_form']=($row->ho_service_form > 0)?$serviceform[$row->ho_service_form]: '';
            $arr[$i]['ho_is_active']=($row->ho_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
        $data=array('ho_is_active' => $is_activeinactive);
        $this->_HealthSafetySetup->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Service ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
		$getServices = $this->getServices;
		$arrtransactiontype = $this->arrtransactiontype;
		$servicedepartment =$this->arrdepartment;
		$serviceform =$this->arrserviceform;
        $data = (object)$this->data;

		
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_HealthSafetySetup->getEditDetails($request->input('id'));
			 
        }
      
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['created_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_HealthSafetySetup->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Service '".$this->data['ho_service_description']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ho_is_active'] = 1;
                $request->id = $this->_HealthSafetySetup->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Service '".$this->data['ho_service_description']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect('health-safety-setup-data-service')->with('success', __($success_msg));
        }
        return view('HoServices.create',compact('data','getServices','arrtransactiontype','serviceform','servicedepartment'));
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                // 'ho_service_name'=>'required|:ho_services,ho_service_name,'.(int)$request->input('id'),
                'ho_service_name' => 'required|unique:ho_services,ho_service_name,' .$request->input('id'),
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
   
   public function getServicefeename(Request $request){
          $id = $request->input('id');
          $name = $this->_HealthSafetySetup->GetServicename($id);
          if(count($name)>0){
            echo $name[0]->chartofaccount;
          }else{
            echo "";
          }
         //echo "<pre>"; print_r($name); exit;
    }

    public function getServices(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_HealthSafetySetup->getService($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->ho_service_name;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function getService(Request $request)
    {
        $id = $request->input('id');
        $data = HealthSafetySetupDataService::find($id);
        $data->ho_service_amount = number_format($data->ho_service_amount,2);
        $data->service_name = $data->service_name;
        echo json_encode($data);

    }
	
	public function getServicesPermit(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_HealthSafetySetup->getServicePermit($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->ho_service_name;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function getServicePermit(Request $request)
    {
        $id = $request->input('id');
        $data = HealthSafetySetupDataService::find($id);
        $data->ho_service_amount = number_format($data->ho_service_amount,2);
        $data->service_name = $data->service_name;
        echo json_encode($data);

    }
}
