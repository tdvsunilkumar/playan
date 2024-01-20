<?php
/*This Controller using any other module permissions, When this functionality will complete please set appropriate permisiions and delete this comment.*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RptPropertyActualUse;
use App\Models\RptPropertyClass;

class RptPropertyActualUseController extends Controller
{
    public $data = [];
    public $proClasses = array();
    private $slugs;

    public function __construct(){
        $this->_rptpropertyactualuse = new RptPropertyActualUse();
        $this->_rptpropertyclass = new RptPropertyClass();
        $this->data = array('id'=>'','pc_class_code'=>'','pau_actual_use_code'=>'','pau_actual_use_desc'=>'','pau_with_land_stripping'=>'','pau_registered_by'=>'','pau_modified_by'=>'');
         foreach ($this->_rptpropertyactualuse->rptPropClass() as $val) {
            $this->proClasses[$val->id]=$val->pc_class_code .'-' . $val->pc_class_description;
        }
        $this->slugs = 'real-property/property/actual-use';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('rptpropertyactualuse.index');
    }
    public function getList(Request $request){
        $data=$this->_rptpropertyactualuse->getList($request);
        $arr=array();
        $i=0;    
       
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->pau_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$j;
            $arr[$i]['pc_class_codeDetails']=$row->pc_class_code.'-'.$row->pc_class_description;
            $arr[$i]['pau_actual_use_code']=$row->pau_actual_use_code;
            $arr[$i]['pau_actual_use_desc']=$row->pau_actual_use_desc;
            $arr[$i]['pau_with_land_stripping']=($row->pau_with_land_stripping==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['is_active']=($row->pau_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $actionView = '';
            
                $actionView .= '
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptpropertyactualuse/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Actual Use">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>';

            
                
                /*$actionView .= '<div class="action-btn bg-danger ms-2">
                <form method="post" action="'.url('rptpropertyactualuse/delete').'" id="delete-form-'.$row->id.'" style="display:contents">
                <input type="hidden" name="id" value="'.$row->id.'" />
                <input type="hidden" name="_token" value="'. csrf_token().'">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="'.__("Delete").'" ><i class="ti-trash text-white"></i></a>
                                                    </form>
                                                 </div>
                ';*/
            
            $arr[$i]['action'] = '<span>'.$actionView.'</span>';
                // <div class="action-btn bg-danger ms-2">
                //     <a href="#" class="mx-3 btn btn-sm deleterow ti-trash text-white text-white" id='.$row->id.'>
                //     </a>
                // </div>
            
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
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('pau_is_active' => $is_activeinactive);
        $this->_rptpropertyactualuse->updateActiveInactive($id,$data);
    }

    public function store(Request $request){
        $data = (object)$this->data;
        $proClassess = $this->proClasses;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_rptpropertyactualuse->find($request->input('id'));
            
        }
       //dd($data);
        if($request->input('submit')!=""){
            
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = ($request->has($key))?$request->input($key):0;
            }
            $this->data['pau_modified_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            //dd($this->data);
            if($request->input('id')>0){
                if(isset($this->data['pau_registered_by']) && $this->data['pau_registered_by'] == 0){
                    unset($this->data['pau_registered_by']);
                }
                $this->_rptpropertyactualuse->updateData($request->input('id'),$this->data);
                $success_msg = 'Property Actual Use updated successfully.';
            }else{
                $this->data['pau_registered_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['pau_is_active'] = 1;
               
                $this->_rptpropertyactualuse->addData($this->data);
                $success_msg = 'Property Actual Use added successfully.';
            }
            return redirect('real-property/property/actual-use')->with('success', __($success_msg));
        }
        
        return view('rptpropertyactualuse.create',compact('data','proClassess'));
        
    }

    public function formValidation(Request $request){
       
        $validator = \Validator::make(
            $request->all(), [
            	// 'pc_class_code'=>'required',
                'pau_actual_use_code'=>'required',
                'pau_actual_use_desc' => 'required',
                'pc_class_code' => 'required|unique:rpt_property_actual_uses,pc_class_code,' .$request->input('id'). ',id,pau_actual_use_code,' .$request->input('pau_actual_use_code').',pau_actual_use_desc,' .$request->input('pau_actual_use_desc'),
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

   
    public function Delete(Request $request){
            
           $id = $request->input('id');
            $rptPropertyKind = RptPropertyActualUse::find($id);
            if($rptPropertyKind != null){
                if($rptPropertyKind->pau_registered_by == \Auth::user()->creatorId()){
                try {
                    $rptPropertyKind->delete();
                    return redirect()->back()->with('success', __('Property Actual Use deleted successfully!'));
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', __($e->getMessage()));
                }
            }else{
                return redirect()->back()->with('error', __('You are not authorised to delete this record'));

            }

            }else{
                return redirect()->back()->with('error', __('You are not authorised to delete this record'));

            }
            
    }
}
