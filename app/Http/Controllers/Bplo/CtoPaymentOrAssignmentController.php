<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\CtoPaymentOrAssignment;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;

class CtoPaymentOrAssignmentController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;  
     public $getparam ="";
     public function __construct(Request $request){
        $this->_CtoPaymentOrAssignment = new CtoPaymentOrAssignment(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->getparam = $request->input('iscashier'); 
        $this->data = array('id'=>'','ora_remarks'=>'','ortype_id'=>'','ora_from'=>'','ora_to'=>'','ora_is_completed'=>'0','ora_completed_date'=>'','ora_date_returned'=>'','ora_remarks'=>'','or_count'=>'','cpor_id'=>'');  
        $this->slugs = 'bplo-or-asssignment';
    }
    public function index(Request $request)
    {   $isparam = $this->getparam;
        if(empty($isparam)){
           $isparam = Session::get('iscashier'); 
        }
        if($this->getparam > 0){
           Session::put('iscashier',$this->getparam); 
        }
        if(empty($isparam)){
           $this->is_permitted($this->slugs, 'read'); 
           Session::forget('iscashier');
        }
        return view('Bplo.or_assignment.index',compact('isparam'));
    }

    public function getOrDescoption(Request $request){

      $id= $request->input('id'); $html= '<option value="">Please Select</option>';
      $ids = $this->_CtoPaymentOrAssignment->getRegisteredata($id); 
      $existids = array();
      foreach ($ids as $key => $value) {
          array_push($existids, $value->cpor_id);
      }
      //print_r($existids);
      $getdata = $this->_CtoPaymentOrAssignment->getOrregister($id,$existids);
        foreach ($getdata as $key => $value) {
            $html .='<option value="'.$value->id.'">'.$value->cpor_series.'=>['.$value->ora_from.'-'.$value->ora_to.']</option>';
        }
        echo $html;
    }

    public function getOrDetails(Request $request){
           $id= $request->input('id');
            $getdata = $this->_CtoPaymentOrAssignment->getOrregisterbyid($id);
            echo json_encode($getdata);
    }

    public function getList(Request $request){
        $data=$this->_CtoPaymentOrAssignment->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/CtoPaymentOrAssignment/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage O.R. Assignment">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                 $idexist = $this->_CtoPaymentOrAssignment->checkidExist($row->id);
                 $actions .=(count($idexist) ==0) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : '';
            }
            $arr[$i]['srno']=$sr_no;
			$arr[$i]['type']=$row->ortype_name;
            $arr[$i]['coa_no']=$row->or_short_name.",#".$row->coa_no;
            $arr[$i]['ora_from']=$row->ora_from;
            $arr[$i]['ora_to']=$row->ora_to;
            $arr[$i]['or_count']=$row->or_count;
            $arr[$i]['ora_is_completed']=($row->ora_is_completed == 1) ? '<span class="badge badge-yes-no bg-info p-2">Yes</span>' : '<span class="badge badge-yes-no bg-secondary p-2">No</span>';
            $arr[$i]['ora_date_issued']=date('Y-m-d', strtotime($row->created_at));
            $arr[$i]['ora_completed_date']=$row->ora_completed_date;
            $arr[$i]['ora_date_returned']=$row->ora_date_returned;
            $arr[$i]['ora_cashier']=$row->fullname;
            $arr[$i]['ora_remarks']=$row->ora_remarks;
            $arr[$i]['ora_is_active']=($row->ora_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
        $data=array('ora_is_active' => $is_activeinactive);
        $this->_CtoPaymentOrAssignment->deleteFromAssignment($id);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Bplo OR Assignment ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        // if(empty($this->getparam)){ 
        // if($request->input('id')>0){
        //     $this->is_permitted($this->slugs, 'update');
        // }else{
        //     $this->is_permitted($this->slugs, 'create');     
        // } }

        $data = (object)$this->data;
        $arrType = array(""=>"Please Select");
        $arrOrdetail = array(""=>"Please Select");
        $arrCompleted = array("1"=>"Yes","0"=>"No");
        foreach ($this->_CtoPaymentOrAssignment->getORType() as $val) {
            $arrType[$val->id]=$val->id.'-'.$val->ortype_name;
        }
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_CtoPaymentOrAssignment->getEditDetails($request->input('id'));
        }
        foreach ($this->_CtoPaymentOrAssignment->getOrregisterdefualt() as $key => $value) {
            $arrOrdetail[$value->id] = $value->cpor_series.'=>['.$value->ora_from.'-'.$value->ora_to.']';
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['ora_is_completed']=(int)$this->data['ora_is_completed'];
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_CtoPaymentOrAssignment->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Bplo O.R. Assignment '".$this->data['ora_remarks']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ora_is_active'] = 1;
                $request->id = $this->_CtoPaymentOrAssignment->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Bplo O.R. Assignment '".$this->data['ora_remarks']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            if($request->input('iscashier') == 1){
                return redirect('bplo-or-asssignment?iscashier=1')->with('success', __($success_msg));;
            }else{
               return redirect()->route('ORAssignment.index')->with('success', __($success_msg)); 
           }
        }
        return view('Bplo.or_assignment.create',compact('data','arrType','arrCompleted','arrOrdetail'));
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'ortype_id' => 'required',
                'cpor_id'  => 'required',
                'ora_to' => 'required|gt:ora_from',
            ]
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        if($arr['ESTATUS']==0){
            $rangeExist = $this->_CtoPaymentOrAssignment->checkRangeExist($request->input('ora_from'),$request->input('ora_to'),$request->input('ortype_id'),$request->input('id'));
            if($rangeExist[0]->total_cnt > 0){
                $arr['field_name'] = 'rangeExistError';
                $arr['error'] = "This range is already exist in table.";
                $arr['ESTATUS'] = 1;
            }
            $checkisopenortype = $this->_CtoPaymentOrAssignment->checkOropenExist($request->input('ortype_id'),\Auth::user()->id,$request->input('id'));
            if(count($checkisopenortype) > 0){
                $arr['field_name'] = 'rangeExistError';
                $arr['error'] = "This form type has already open orno.";
                $arr['ESTATUS'] = 1;
             }
        }
        echo json_encode($arr);exit;
    }
   
}

