<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RptUpdateCode;

class RptUpdateCodeController extends Controller
{
    public $data = [];
    private $slugs;

    public function __construct(){
        $this->_rptupdatecode = new RptUpdateCode();
        
  $this->data = array('id'=>'','uc_code'=>'','uc_description'=>'','uc_usage_land'=>false,'uc_usage_building'=>false,'uc_usage_machine'=>false,'uc_change_property_of_ownership'=>'','uc_cancel_existing_faas'=>'','uc_consolidate_existing_faas'=>'','uc_subdivide_existing_faas'=>'','uc_cancel_only_one_existing_faas'=>'','uc_cease_tax_declaration'=>'','uc_revised_tax_declaration'=>'','uc_registered_by'=>'','uc_modified_by'=>'','uc_new_fresh'=>'','direct_cancellation'=>'');
      $this->slugs = 'real-property/update-code';       
    }
    
    
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('rptupdatecode.index');
    }
  
    public function PrintOptionUpdateCode(Request $request){
        $id = $request->input('id');

        $is_activeinactive = $request->input('is_print');
        $data=array('uc_new_fresh' => $is_activeinactive);

        $this->_rptupdatecode->updateActiveInactive($id,$data);
        $this->_rptupdatecode->updatePrint($id,$data);
    }
    public function getList(Request $request){
        $data=$this->_rptupdatecode->getList($request);
        $arr=array();
        $i=0;    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->uc_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$j;
            $arr[$i]['uc_code']=$row->uc_code;
            $arr[$i]['uc_description']=$row->uc_description;
            $arr[$i]['uc_usage_land']=($row->uc_usage_land==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Applicable</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Not Applicable</span>');
            $arr[$i]['stp_print']=($row->uc_new_fresh==1?'<input type="checkbox" id='.$row->id.'  class="form-check-input printupdate" name="stp_print" value="1" checked> ':'<input type="checkbox" class="form-check-input printupdate" id='.$row->id.' name="stp_print" value="0" >');
            $arr[$i]['uc_usage_building']=($row->uc_usage_building==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Applicable</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Not Applicable</span>');
            $arr[$i]['uc_usage_machine']=($row->uc_usage_machine==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Applicable</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Not Applicable</span>');

            /*$arr[$i]['uc_change_property_of_ownership']=($row->uc_change_property_of_ownership==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['uc_cancel_existing_faas']=($row->uc_cancel_existing_faas==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['uc_consolidate_existing_faas']=($row->uc_consolidate_existing_faas==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['uc_subdivide_existing_faas']=($row->uc_subdivide_existing_faas==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['uc_cancel_only_one_existing_faas']=($row->uc_cancel_only_one_existing_faas==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['uc_cease_tax_declaration']=($row->uc_cease_tax_declaration==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['uc_revised_tax_declaration']=($row->uc_revised_tax_declaration==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');*/

            $arr[$i]['is_active']=($row->uc_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $actionView = '';
            
                $actionView .= '
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptupdatecode/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Update Code">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>'  
                ;

          
            
                
                /*$actionView .= '<div class="action-btn bg-danger ms-2">
                <form method="post" action="'.url('rptupdatecode/delete').'" id="delete-form-'.$row->id.'" style="display:contents">
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
        $data=array('uc_is_active' => $is_activeinactive);
        $this->_rptupdatecode->updateActiveInactive($id,$data);
    }
    
    public function store(Request $request){
        $data = (object)$this->data;
       
        
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_rptupdatecode->find($request->input('id'));
            
        }
       //dd($data);
        if($request->input('submit')!=""){
            
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = ($request->has($key))?$request->input($key):0;
            }
            $this->data['uc_modified_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $updatedId =$this->_rptupdatecode->updateData($request->input('id'),$this->data);
                $this->_rptupdatecode->updatePrint($updatedId);
                $success_msg = 'Tax Declaration updated successfully.';
            }else{
                $this->data['uc_registered_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['uc_is_active'] = 1;
               
                $insertedId = $this->_rptupdatecode->addData($this->data);
                $this->_rptupdatecode->updatePrint($insertedId);
                $success_msg = 'Tax Declaration added successfully.';
            }
            return redirect()->route('rptupdatecode.index')->with('success', __($success_msg));
        }
        
        return view('rptupdatecode.create',compact('data'));
        
    }


    public function formValidation(Request $request){
       
        $validator = \Validator::make(
            $request->all(), [
                'uc_code' => 'required|unique:rpt_update_codes,uc_code,' .$request->input('id'). ',id,uc_description,' .$request->input('uc_description'),
                'uc_description' => 'required'
                
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
            $rptUpDateCade = RptUpdateCode::find($id);
            if($rptUpDateCade != null){
                if($rptUpDateCade->uc_registered_by == \Auth::user()->creatorId()){
                try {
                    $rptUpDateCade->delete();
                    return redirect()->back()->with('success', __('Tax declaration deleted successfully!'));
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
