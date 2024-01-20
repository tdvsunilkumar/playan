<?php

namespace App\Http\Controllers;

use App\Models\BploAssessTaxRateEffectivit;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class BploAssessTaxRateEffectivitController extends Controller
{
    
     public $data = [];
   
    public function __construct(){
        $this->_bploassessetaxrateeffectivit = new BploAssessTaxRateEffectivit();
        
  $this->data = array('id'=>'','tre_code'=>'','tre_effectivity_year'=>'','tre_quarter'=>'','tre_ordinance_number'=>'','is_active'=>'1','tre_remarks'=>'');
        
        
    }
    public function index(Request $request)
    {
        return view('bploassessetaxrateeffectivit.index');
    }
    
    
    public function getList(Request $request){
        $data=$this->_bploassessetaxrateeffectivit->getList($request);
        $arr=array();
        $i="0";   

        foreach ($data['data'] as $row){
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$i+1;
            $arr[$i]['tre_code']=$row->id;
            $arr[$i]['tre_effectivity_year']=$row->tre_effectivity_year;
            $arr[$i]['tre_ordinance_number']=$row->tre_ordinance_number;
            $tre_quarter =""; if($row->tre_quarter =='5'){ $schedule ='1st';} elseif($row->tre_quarter =='2'){ $tre_quarter ='2nd'; }elseif($row->tre_quarter =='3'){ $tre_quarter ='3rd'; }elseif($row->tre_quarter =='2'){ $tre_quarter ='4th'; }
            $arr[$i]['tre_quarter']=$tre_quarter;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['tre_remarks']=$row->tre_remarks;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bploassessetaxrateeffectivit/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Tax Rate Effectivity Edit">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>' ;
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
        $data=array('is_active' => $is_activeinactive);
        $this->_bploassessetaxrateeffectivit->updateActiveInactive($id,$data);
}
    public function store(Request $request){
        $data = (object)$this->data;
       

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BploAssessTaxRateEffectivit::find($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_bploassessetaxrateeffectivit->updateData($request->input('id'),$this->data);
                $success_msg = 'Tax Rate Effectivity updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['tre_date'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $this->_bploassessetaxrateeffectivit->addData($this->data);
                $success_msg = 'Tax Rate Effectivity added successfully.';
            }
            return redirect()->route('bploassessetaxrateeffectivit.index')->with('success', __($success_msg));
        }
        return view('bploassessetaxrateeffectivit.create',compact('data'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                // 'tre_effectivity_year'=>'required',
                'tre_quarter'=>'required',
                'tre_ordinance_number'=>'required',
                
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
            $BploAssessTaxRateEffectivit = BploAssessTaxRateEffectivit::find($id);
            if($BploAssessTaxRateEffectivit->created_by == \Auth::user()->creatorId()){
                $BploAssessTaxRateEffectivit->delete();
            }
    }
}
