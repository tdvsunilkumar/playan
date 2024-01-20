<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\CtoPaymentDueDate;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
class CtoPaymentDueDateController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_CtoPaymentDueDate = new CtoPaymentDueDate(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','app_type_id'=>'','due_1st_payment'=>'','due_semi_annual_2nd_sem'=>'','due_quarterly_2nd'=>'','due_quarterly_3rd'=>'','due_quarterly_4th'=>'');  
        $this->slugs = 'payment-data-due-date';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('Bplo.duedate.index');
    }


    public function getList(Request $request){
         $this->is_permitted($this->slugs, 'read');
        $data=$this->_CtoPaymentDueDate->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/CtoPaymentDueDate/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Due Date">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->due_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['type']=$row->app_type;
            $arr[$i]['due_1st_payment']=$row->due_1st_payment;
            $arr[$i]['due_semi_annual_2nd_sem']=$row->due_semi_annual_2nd_sem;
            $arr[$i]['due_quarterly_2nd']=$row->due_quarterly_2nd;
            $arr[$i]['due_quarterly_3rd']=$row->due_quarterly_3rd;
            $arr[$i]['due_quarterly_4th']=$row->due_quarterly_4th;

            $arr[$i]['is_active']=($row->due_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
        $data=array('due_is_active' => $is_activeinactive);
        $this->_CtoPaymentDueDate->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Due Date ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;
        $arrType = array(""=>"Please Select");
        foreach ($this->_CtoPaymentDueDate->getApplicationType() as $val) {
            $arrType[$val->id]=$val->app_type;
        }
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_CtoPaymentDueDate->getEditDetails($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            $this->is_permitted($this->slugs, 'upload');
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');

            // Start Uploading Attachement
            if($image = $request->file('due_attached_docs')) {
                $destinationPath =  public_path().'/uploads/due_date/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                $this->data['due_attached_docs'] = $filename;
            }
            // End Uploading Attachement

            if($request->input('id')>0){
                $this->_CtoPaymentDueDate->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Due Date"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['due_is_active'] = 1;
                $request->id = $this->_CtoPaymentDueDate->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Due Date"; 
            }

            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('duedate.index')->with('success', __($success_msg));
        }
        return view('Bplo.duedate.create',compact('data','arrType'));
    }
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'app_type_id'=>'required|unique:cto_payment_due_dates,app_type_id,'.(int)$request->input('id'),
                'due_semi_annual_2nd_sem'=>'required',
                'due_quarterly_2nd'=>'required',
                'due_quarterly_3rd'=>'required',
                'due_quarterly_4th'=>'required'
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
    public function deleteAttchment(Request $request){
        $id=$request->input('id');
        $name=$request->input('name');
        $destinationPath = public_path().'/uploads/due_date/';
        File::delete($destinationPath.$name);
        $files = array();
        $files['due_attached_docs'] = "";
        $this->_CtoPaymentDueDate->updateData($id,$files); 
    }
}
