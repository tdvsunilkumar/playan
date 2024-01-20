<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\ReassessmentPaymentModel;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ReassessmentPaymentController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_ReassessmentPaymentModel = new ReassessmentPaymentModel(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','remark'=>'','pmode_policy'=>'');  
        $this->slugs = 'business-type';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
		$data = $this->_ReassessmentPaymentModel->getEditDetailssss();
		$listpaymentmode=config('constants.reAssessPayMode');
		
        return view('Bplo.ReassessmentPaymentMode.index',compact('data','listpaymentmode'));
    }

    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_ReassessmentPaymentModel->getEditDetails($request->input('id'));
        }
       $listpaymentmode=config('constants.reAssessPayMode');
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_ReassessmentPaymentModel->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated TFOC Basis '".$this->data['remark']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $request->id = $this->_ReassessmentPaymentModel->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added TFOC Basis '".$this->data['remark']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('bplo-reassessment-payment-mode.index')->with('success', __($success_msg));
        }
        return view('Bplo.ReassessmentPaymentMode.index',compact('data','listpaymentmode'));
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'pmode_policy'=>'required|unique:cto_bplo_re_assessment_payment_mode,pmode_policy,'.(int)$request->input('id'),
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
