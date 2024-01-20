<?php

namespace App\Http\Controllers;
use App\Models\Country;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class CountryController extends Controller
{
    
     public $data = [];
  

    public function __construct(){
        $this->_country = new Country();
		$this->slugs = 'administrative/country';
        $this->data = array('id'=>'','country_name'=>'','is_active'=>'1','nationality'=>'');
    }
    
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
		return view('country.index');
        
    }

    public function getList(Request $request){
		$this->is_permitted($this->slugs, 'read');
        $data=$this->_country->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;        
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['country_name']=$row->country_name;
            $arr[$i]['nationality']=$row->nationality;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
             $arr[$i]['isdefault']=($row->is_default==1?'<input type="checkbox" id='.$row->id.'  class="form-check-input defaultupdate" name="stp_print" value="1" checked> ':'<input type="checkbox" class="form-check-input defaultupdate" id='.$row->id.' name="stp_print" value="0" >');
           
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/country/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Country">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>'  ;
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

    public function defaultUpdateCode(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_print');
        $data=array('is_default' => $is_activeinactive);
        $dataupt = array('is_default' => '0');
        $this->_country->updateDefaultall($dataupt);
        $this->_country->updateData($id,$data);


    }
    public function ActiveInactive(Request $request){
		$this->is_permitted($this->slugs, 'update');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_country->updateActiveInactive($id,$data);
}
    public function store(Request $request){
		$this->is_permitted($this->slugs, 'update');
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = Country::find($request->input('id'));
            
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                unset($this->data['is_active']);
                $this->_country->updateData($request->input('id'),$this->data);
                $success_msg = 'Country updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
               
                $this->_country->addData($this->data);
                $success_msg = 'Country added successfully.';
            }
            return redirect()->route('country.index')->with('success', __($success_msg));
        }
        return view('country.create',compact('data'));
        
    }


    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'country_name'=>'required|unique:countries,country_name,'.(int)$request->input('id'),
                'nationality'=>'required',
                
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
            $Country = Country::find($id);
            if($Country->created_by == \Auth::user()->creatorId()){
                $Country->delete();
            }
    }
}
