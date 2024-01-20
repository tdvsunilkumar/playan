<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\BploBusiness;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class Applicant2Controller extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_BploBusiness = new BploBusiness(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'',
                            'locality_id'=>'',
                            'busn_tax_year'=>'',
                            'busn_tax_month'=>'',
                            'busn_series_no'=>'',
                            'busn_tracking_no'=>'',
                            'app_code'=>'',
                            'pm_id'=>'',
                            'busn_id_initial'=>'',
                            'loc_local_id'=>'',
                            'busn_id'=>'',
                            'busn_id_no'=>'',
                            'busn_name'=>'',
                            'busn_trade_name'=>'',
                            'btype_id'=>'',
                            'busn_registration_no'=>'',
                            'busn_tin_no'=>'',
                            'client_id'=>'',
                            'busn_office_main_building_no'=>'',
                            'busn_office_main_building_name'=>'',
                            'busn_office_main_add_block_no'=>'',
                            'busn_office_main_add_lot_no'=>'',
                            'busn_office_main_add_street_name'=>'',
                            'busn_office_main_add_subdivision'=>'',
                            'busn_office_main_barangay_id'=>'',
                            'busloc_id'=>'',
                            'busn_bldg_area'=>'',
                            'busn_bldg_total_floor_area'=>'',
                            'busn_employee_no_female'=>'',
                            'busn_employee_no_male'=>'',
                            'busn_employee_total_no'=>'',
                            'busn_employee_no_lgu'=>'',
                            'busn_vehicle_no_van_truck'=>'',
                            'busn_vehicle_no_motorcycle'=>'',
                            'busn_bldg_is_owned'=>'',
                            'busn_bldg_tax_declaration_no'=>'',
                            'busn_bldg_property_index_no'=>'',
                            'busn_tax_incentive_enjoy'=>'',
                            'busn_office_is_same_as_main'=>'',
                            'busn_office_building_no'=>'',
                            'busn_office_building_name'=>'',
                            'busn_office_add_block_no'=>'',
                            'busn_office_add_lot_no'=>'',
                            'busn_office_add_street_name'=>'',
                            'busn_office_add_subdivision'=>'',
                            'busn_office_barangay_id'=>'',
                            'busn_app_status'=>'',
                            'busn_dept_involved'=>'',
                            'busn_dept_completed'=>'',
                        );  
        $this->slugs = 'business-permit/application';
    }
    public function index(Request $request)
    {
       
        $this->is_permitted($this->slugs, 'read');
        $bsn_type=$this->_BploBusiness->getBsnType();
        $bsn_activity=$this->_BploBusiness->getBsnType();
        $client=$this->_BploBusiness->getOwnerName();
        $barangay=$this->_BploBusiness->getBarangay();
        return view('BploBusiness.index2')->with(compact('bsn_type','bsn_activity','client','barangay'));
    }

    public function getList(Request $request){
		
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_BploBusiness->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/BploBusiness/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Formula">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
			if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-info ms-2">
							 <a href="javascript:;" data-size="lg" data-bs-toggle="tooltip" title="status" class="btn btn-sm btn-primary" id="change-status-btn">
								<i class="ti-eye text-white"></i>
							</a>
						</div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->btype_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
			
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['busn_id_no']=$row->busn_id_no;
            $arr[$i]['owner']=$row->client->rpo_custom_last_name ."". $row->client->rpo_first_name ."". $row->client->rpo_middle_name;
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['app_type']=$row->app_code;
            $arr[$i]['app_date']=$row->created_at;
            $arr[$i]['busn_app_status']=$row->busn_app_status;
            $arr[$i]['app_method']=$row->app_code;
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
	
	public function ChnageStatus(Request $request,$id){
		die('ddddd');
		
	}
    
    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('btype_status' => $is_activeinactive);
        $this->_BploBusiness->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Bplo Formula ".$action; 
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
            $data = $this->_BploBusiness->getEditDetails($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_BploBusiness->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated TFOC Basis '".$this->data['btype_desc']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['btype_status'] = 1;
                $request->id = $this->_BploBusiness->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added TFOC Basis '".$this->data['btype_desc']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('bploBusinessType.index')->with('success', __($success_msg));
        }
        return view('BploBusiness.create',compact('data'));
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'btype_desc'=>'required|unique:bplo_business_type,btype_desc,'.(int)$request->input('id'),
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
