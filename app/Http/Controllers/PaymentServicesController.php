<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\AppPaymentServices;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use DB;
class PaymentServicesController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
	 public $arrctotfocs = array(""=>"Please Select");
     public function __construct(){
		 
        $this->_AppPaymentServices = new AppPaymentServices(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','fpayment_app_name'=>'','fpayment_module_name'=>'','tfoc_id'=>'','fpayment_remarks'=>'');  
        $this->slugs = 'application-form-payment-services';
		
		foreach ($this->_AppPaymentServices->Ctotfocs() as $val) {
             $this->arrctotfocs[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
        }
		
		
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('ApplicationPaymentServices.index');
    }
	
	
    public function getList(Request $request){
		
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_AppPaymentServices->getList($request);
		$arrctotfocs = $this->arrctotfocs;
		
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/ApplicationPaymentServices/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Application Forms [& Payment Services]">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
			if($row->tfoc_id > 0){ 
				$cashing_id = $this->_AppPaymentServices->getAccountDesc($row->tfoc_id);
			}else{
				$cashing_id =0;
			}
			$arrApplicableDept =config('constants.arrApplicableDept');
			
			$fpayment_app_name = wordwrap($row->fpayment_app_name, 40, "<br />\n");
            $tfoc_id ="";
            if(!empty($arrctotfocs[$row->tfoc_id])){
              $tfoc_id = wordwrap(($row->tfoc_id > 0)?$arrctotfocs[$row->tfoc_id]:'', 40, "<br />\n");  
            }
			$fpayment_remarks = wordwrap($row->fpayment_remarks, 40, "<br />\n");
			
            $arr[$i]['srno']= $sr_no;
			$arr[$i]['fpayment_app_name']="<div class='showLess'>".$fpayment_app_name."</div>";
			$arr[$i]['tfoc_id']="<div class='showLess'>".$tfoc_id."</div>";
			$arr[$i]['ApplicableDept']=$arrApplicableDept[$cashing_id];
			$arr[$i]['fpayment_remarks']="<div class='showLess'>".$fpayment_remarks."</div>";
            $arr[$i]['action']= $actions;
           
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
        $data=array('fpayment_status' => $is_activeinactive);
        $this->_AppPaymentServices->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Application Forms ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
		$arrctotfocs = $this->arrctotfocs;
		$arrApplicableDept =config('constants.arrApplicableDept');
        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_AppPaymentServices->getEditDetails($request->input('id'));
        }
		
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
				
                $this->_AppPaymentServices->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
            }else{
				$this->data['created_by']=\Auth::user()->creatorId();
				$this->data['created_at'] = date('Y-m-d H:i:s');
				$request->id = $this->_AppPaymentServices->addData($this->data);
				$success_msg = 'Added successfully.';				
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->back();
        }
        return view('ApplicationPaymentServices.create',compact('data','arrctotfocs','arrApplicableDept'));
    }
	
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                //'fpayment_remarks'=>'required|:cto_forms_miscellaneous_payments,fpayment_remarks,'.(int)$request->input('id'),
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
	
	 public function getmodulename(Request $request){
       $data = $this->_AppPaymentServices->getAccountDesc($request->input('id'));
	   $arrApplicableDept =config('constants.arrApplicableDept');
	   return $arrApplicableDept[$data];
    }

}
