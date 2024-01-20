<?php

namespace App\Http\Controllers\Accounting;
use App\Http\Controllers\Controller;
use App\Models\Accounting\LegalResidentialLocDetails;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class LegalResidentialLocDetailsController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_LegalResidentialLocDetails = new LegalResidentialLocDetails(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','residential_location_id'=>'','lot_number'=>'');  
        $this->slugs = 'residential-housing-list';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('cemeterylistdetails.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_LegalResidentialLocDetails->getList($request);
		
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            // if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            //     $actions .= '<div class="action-btn bg-warning ms-2">
            //         <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cemeterieslistdetails/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Cemetery list">
            //             <i class="ti-pencil text-white"></i>
            //         </a>
            //     </div>';
            // }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $arr[$i]['srno']=$sr_no;
			$arr[$i]['block']=$row->block;
			$arr[$i]['lot_number']=$row->lot_number;
			$arr[$i]['lot_status']=($row->lot_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Available</span>':'<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">Occupied</span>');
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
        $data=array('is_active' => $is_activeinactive);
        $this->_LegalResidentialLocDetails->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' list details ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
	
	public function ActiveInactives(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('ecl_status' => $is_activeinactive);
        $this->_LegalResidentialLocDetails->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' list details ".$action; 
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

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_LegalResidentialLocDetails->getEditDetails($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_LegalResidentialLocDetails->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated list details '"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ecl_status'] = 1;
				 $this->data['status'] = 1;
                $request->id = $this->_LegalResidentialLocDetails->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added list details '"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('cemeterystyle.index')->with('success', __($success_msg));
        }
        return view('cemeterylistdetails.create',compact('data'));
    }
    
   public function saveornumberoflot(Request $request){
    	 $this->data['ecl_id']= $request->input('ecl_id');
    	 $this->data['ecl_block']= $request->input('ecl_block');
    	 $this->data['ecl_lot']= $request->input('ecl_lot');
    	 $data =array();
			if($request->input('id')>0){
				die('ss');
                $this->_LegalResidentialLocDetails->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $array = ["status"=>"success","message" =>"Data Saved Successfully."];
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ecl_status'] = 1;
				$this->data['status'] = 1;
                $request->id = $this->_LegalResidentialLocDetails->addData($this->data);
                $success_msg = 'Added successfully.';
                $array = ["status"=>"success","message" =>"Data Saved Successfully."];
            }
    	  
         echo json_encode($array);
    }
	
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                //'eco_cemetery_style'=>'required|unique:eco_cemeteries_style,eco_cemetery_style,'.(int)$request->input('id'),
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
