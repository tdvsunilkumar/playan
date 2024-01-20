<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DevelopmentPermitComputation;
use App\Models\ZoningClearance;
use Auth;
use DB;

class ZoningClearanceController extends Controller
{
    private $slugs;
    public function __construct(){
        $this->_DevelopmentPermit = new DevelopmentPermitComputation(); 
        $this->_ZoningClearance = new ZoningClearance();
        $this->cpdo_modules = array("" => "Select Services");
        $this->cpdo_imperials = array("" => "Select Types");
        $this->slugs = 'medical-certificate';
    }
 
    public function index(){
        try{
            $this->is_permitted($this->slugs, 'read');
            return view('zoningClearance.index');
        }catch(Exception $e){
            return ($e->getMessage());
        }
    }

    public function GetList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_ZoningClearance->getList($request);
        $arr=array();
        $i="0";
        $sr_no=(int)$request->input('start')-1;
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) { 
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#"
                        class="mx-3 btn btn-sm  align-items-center" 
                        data-url="'.url('/zoning-clearance-computation/store').'?id='. $row->id .'" 
                        data-ajax-popup="true"
                        data-bs-toggle="tooltip" 
                        title="Edit"
                        data-size="xxl"
                        data-title="Manage Zoning Clearance Computation">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->czcc_is_active == 1) ? '<div class="action-btn btn-sm btn-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';   
            }
            $arr[$i]['srno']= $sr_no;
            $arr[$i]['cm_id']= $row->cm_module_desc;
            $arr[$i]['is_active']= ($row->czcc_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
        try {
            $this->is_permitted($this->slugs, 'delete');
            $id = $request->input('id');
            $is_activeinactive = $request->input('is_activeinactive');
            $data=array('is_active' => $is_activeinactive);
            $this->_ZoningClearance->updateActiveInactive($id,$data);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    } 

    public function getImperials(){
        $cpdo_imperials = $this->_DevelopmentPermit->getImperials();
        return response()->json(['status' => 200, 'data' => $cpdo_imperials]);
    }

    public function store(request $request){
        try {
            $czccl = [];
            $cpdo_modules = $this->cpdo_modules;
            $services = $this->_DevelopmentPermit->getZoningServices();
            foreach ($services as $key => $service) {
                $cpdo_modules[$service->id] = $service->cm_module_desc;
            }

            $selected = [
                'id' => null,
                'cm_id' => null,
                'is_active' => 1,
            ];
            // This Section Is For edit
            if($request->input('id') > 0 && $request->input('submit') == ""){
                $cpdo_modules = [];
                $services = $this->_DevelopmentPermit->getServicesById($request->input('id'));
                foreach ($services as $key => $service) {
                    $cpdo_modules[$service->id] = $service->cm_module_desc;
                }
                $czcc = $this->_ZoningClearance->getSingleData($request->input('id'));
                $czccl = $this->_ZoningClearance->getCZCCLines($czcc->id);
                $selected['id'] = $request->input('id');
                $selected['cm_id'] = $czcc->cm_id;
            }
            // This Section Is For Add And Update
            if($request->isMethod('post')!=""){
                $message = "";
                $form_data = $request->all();
                if($request->input('id')>0){
                    $this->_ZoningClearance->updateData($request->input('id'), $form_data);
                    $message = "Updated.";
                }else{
                    $this->_ZoningClearance->addData($form_data);
                    $message = "Added.";
                }
                return redirect()->route('zoning-clearance-computation')->with('success', __('Data Successfully ' .$message));
            }
            return view('zoningClearance.create', compact('selected', 'cpdo_modules', 'czccl'));
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), 
			[
                'cm_id'=>'required'
			],[
				'cm_id.required' => 'Service Type Required',
			]
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);
    }
}
