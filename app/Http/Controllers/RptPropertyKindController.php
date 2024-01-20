<?php
/*This Controller using any other module permissions, When this functionality will complete please set appropriate permisiions and delete this comment.*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RptPropertyKind;

class RptPropertyKindController extends Controller
{
    public $data = [];
    private $slugs;

    public function __construct(){
        $this->_rptpropertykind = new RptPropertyKind();
        $this->data = array('id'=>'','pk_code'=>'','pk_description'=>'','pk_registered_by'=>'','pk_modified_by'=>'');
        $this->slugs = 'real-property/property/kind';      
    }
    
    public function index(Request $request)
    {
		
        $this->is_permitted($this->slugs, 'read');
        return view('rptpropertykind.index');    
    }


    public function changeStatus(Request $request)
    {
        $user = RptPropertyKind::find($request->user_id);
        $user->pk_is_active = $request->status;
        $user->save();
  
        return response()->json(['success'=>'Status change successfully.']);
    }
    public function getList(Request $request){
        $data=$this->_rptpropertykind->getList($request);
        $arr=array();
        $i=0;   
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->pk_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$j;
            $arr[$i]['pk_code']=$row->pk_code;
            $arr[$i]['pk_description']=$row->pk_description;
            $arr[$i]['is_active']=($row->pk_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/real-property/property/kind/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Property Kind ">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                </div>
                '.$status.'
                </div>' ;
                //  <div class="action-btn bg-danger ms-2">
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
        $data=array('pk_is_active' => $is_activeinactive);
        $this->_rptpropertykind->updateActiveInactive($id,$data);
    }
    
    public function store(Request $request){
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_rptpropertykind->find($request->input('id'));
            
        }
       //dd($data);
        if($request->input('submit')!=""){
            
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = ($request->has($key))?$request->input($key):0;
            }
            $this->data['pk_modified_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                if(isset($this->data['pk_registered_by']) && $this->data['pk_registered_by'] == 0){
                    unset($this->data['pk_registered_by']);
                }
                $this->_rptpropertykind->updateData($request->input('id'),$this->data);
                $success_msg = 'Property Kind updated successfully.';
            }else{
                $this->data['pk_registered_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['pk_is_active'] = 1;
               
                $this->_rptpropertykind->addData($this->data);
                $success_msg = 'Property Kind added successfully.';
            }
            return redirect()->route('kind.index')->with('success', __($success_msg));
        }
        
        return view('rptpropertykind.create',compact('data'));
        
    }
    
    public function formValidation(Request $request){
       
        $validator = \Validator::make(
            $request->all(), [
                'pk_code' => 'required|unique:rpt_property_kinds,pk_code,' .$request->input('id'). ',id,pk_description,' .$request->input('pk_description'),
                // 'pk_code'=>"required",
                'pk_description' => 'required'
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
            $rptPropertyKind = RptPropertyKind::find($id);
            if($rptPropertyKind != null){
                if($rptPropertyKind->pk_registered_by == \Auth::user()->creatorId()){
                try {
                    $rptPropertyKind->delete();
                    return redirect()->back()->with('success', __('Property Kind deleted successfully!'));
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
