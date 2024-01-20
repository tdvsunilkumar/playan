<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ImperialSystem;
use Auth;
use DB;

class ImperialSystemController extends Controller
{
    private $slugs;
    public function __construct(){
        $this->_ImperialSystem = new ImperialSystem(); 
        $this->data = array(
            'cis_code'=>'',
            'cis_imperial_system'=>'',
            'cis_status'=>'',
            'is_active'=>'',
        );
        $this->slugs = 'medical-certificate';
    }
 
    public function index(){
        try{
            $this->is_permitted($this->slugs, 'read');
            return view('imperialSystem.index');
        }catch(Exception $e){
            return ($e->getMessage());
        }
    }

    public function GetList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_ImperialSystem->getList($request);
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
                        data-url="'.url('/imperial-system/store').'?id='. $row->id .'" 
                        data-ajax-popup="true"
                        data-bs-toggle="tooltip" 
                        title="Edit"  
                        data-title="Manage Imperial System">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->is_active == 1) ? '<div class="action-btn btn-sm btn-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';   
            }
            $arr[$i]['srno']= $sr_no;
            $arr[$i]['cis_code']= $row->cis_code;
            $arr[$i]['cis_imperial_system']= $row->cis_imperial_system;
            $arr[$i]['is_active']= ($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
            $this->_ImperialSystem->updateActiveInactive($id,$data);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function store(request $request){
        try {
            $selected = [
                'id' => null,
                'cis_code' => null,
                'cis_imperial_system' => null,
                'is_active' => 1,
            ];
            // This Section Is For edit
            if($request->input('id') > 0 && $request->input('submit') == ""){ 
                $imperial_system = $this->_ImperialSystem->getSingleData($request->input('id'));
                $selected['id'] = $request->input('id');
                $selected['cis_code'] = $imperial_system->cis_code;
                $selected['cis_imperial_system'] = $imperial_system->cis_imperial_system;
                $selected['is_active'] = $imperial_system->is_active;
            }
            // This Section Is For Add And Update
            if($request->isMethod('post')!=""){

                $this->data['cis_code'] = $request->cis_code;
                $this->data['cis_imperial_system'] = $request->cis_imperial_system;
                $this->data['cis_status'] = $request->status ? $request->status : 1;
                $this->data['is_active'] = $request->is_active;
                $this->data['created_by'] = Auth::user()->id;
                $this->data['updated_by'] = Auth::user()->id;
                $message = "";
                
                if($request->input('id')>0){
                    $this->_ImperialSystem->updateData($request->input('id'), $this->data);
                    $message = "Updated.";
                }else{
                    $this->data['is_active'] = 1;
                    $this->_ImperialSystem->addData($this->data);
                    $message = "Added.";
                }
                return redirect()->route('imperial-system')->with('success', __('Data Successfully ' .$message));
            }
            return view('imperialSystem.create', compact('selected'));
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), 
			[
                'cis_code'=>'required',
                'cis_imperial_system'=>'required',
			],[
				'cis_code.required' => 'Code Is Required',
				'cis_imperial_system.required' => 'Imperial System Is Required'
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
