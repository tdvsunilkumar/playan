<?php

namespace App\Http\Controllers;

use App\Models\RptBuildingKind;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class RptBuildingKindController extends Controller
{
    public $data = [];
    private $slugs;
    public function __construct(){
        $this->_rptbuildingkind = new RptBuildingKind();
        $this->data = array('id'=>'','bk_building_kind_code'=>'','bk_building_kind_desc'=>'');
        $this->slugs = 'real-property/building/kind-structure';      
    }
    
    public function index(Request $request)
    {
       $this->is_permitted($this->slugs, 'read');
       return view('rptbuildingkind.index');
        
    }
    
    public function getList(Request $request){
        $data=$this->_rptbuildingkind->getList($request);
        $arr=array();
        $i="0"; 
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->bk_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$j;
            $arr[$i]['bk_building_kind_code']=$row->bk_building_kind_code;
            $arr[$i]['bk_building_kind_desc']=$row->bk_building_kind_desc;
            
            $arr[$i]['is_active']=($row->bk_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptbuildingkind/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Building Kind">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                    '.$status.'
                </div>'  
                

                ;
               
            
            
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
        $data=array('bk_is_active' => $is_activeinactive);
        $this->_rptbuildingkind->updateActiveInactive($id,$data);
}
    public function store(Request $request){
        $data = (object)$this->data;
       

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptBuildingKind::find($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_rptbuildingkind->updateData($request->input('id'),$this->data);
                $success_msg = 'Rpt Building Kind updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['bk_is_active'] = 1;
               
                $this->_rptbuildingkind->addData($this->data);
                $success_msg = 'Rpt Building Kind added successfully.';
            }
             if($request->ajax()){
                return response()->json(['status'=>'success','msg'=>$success_msg]);
            }else{
                return redirect('real-property/building/kind-structure')->with('success', __($success_msg));
            }
        }
        return view('rptbuildingkind.create',compact('data'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'bk_building_kind_desc' => 'required|unique:rpt_building_kinds,bk_building_kind_desc,' .$request->input('id'). ',id,bk_building_kind_code,' .$request->input('bk_building_kind_code'),
 
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

   
// public function Delete(Request $request){
//     $id = $request->input('id');
//         $RptBuildingKind = RptBuildingKind::find($id);
//         if($RptBuildingKind->created_by == \Auth::user()->creatorId()){
//             $RptBuildingKind->delete();
//         }
// }

  

    
}
