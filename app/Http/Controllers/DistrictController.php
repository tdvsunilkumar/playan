<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
     public $data = [];
     public $postdata = [];
     // public $arrClassCode = array(""=>"Please Select");
     public $arrMunCode = array(""=>"Please Select"); 
     public function __construct(){
		$this->_district = new District(); 
        $this->_commonmodel = new CommonModelmaster();
        

        $this->data = array('id'=>'','loc_local_code'=>'','dist_code'=>'','dist_name'=>'');  
    
        foreach ($this->_district->getMunId() as $val) {
               $this->arrMunCode[$val->id]=$val->mun_no.'-'.$val->mun_desc;
          }
    }
    
    public function index(Request $request)
    {
            // $District = $this->_district->getDistrict();
            // return view('districtselection.index', compact('District'));

            
                return view('districtselection.index');
            
    }
    public function districtActiveInactive(Request $request){
        $id = $request->input('id');
        $bt_is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $bt_is_activeinactive);
        $this->_district->updateActiveInactive($id,$data);
    }


    public function getList(Request $request){
        $data=$this->_district->getList($request);
        $arr=array();
        $i="0";
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            $arr[$i]['loc_local_code']=$j;
            $arr[$i]['loc_local_name']=$row->mun_no.'-'.$row->mun_desc;
            $arr[$i]['dist_code']=$row->dist_code;
            $arr[$i]['dist_name']=$row->dist_name;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            
           
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/district/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage District">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>

             </div>
                    '.$status.'
                </div>
              ';
              // <div class="action-btn bg-danger ms-2">
              //       <a href="#" class="mx-3 btn btn-sm deleterow ti-trash text-white text-white" id='.$row->id.'>
              //       </a>
              //   </div>
           
           
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

        public function getLocalIdDetails(Request $request){
            $id= $request->input('id');
            $data = $this->_district->getLocalIdDetails($id);
            echo json_encode($data);
        }
       
    public function store(Request $request){

        $data = (object)$this->data;
        $arrMunCode = $this->arrMunCode;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_district->editDistrict($request->input('id'));
           
        }
       
		if($request->input('submit')!=""){
            //dd($request->all());
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
                     

            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_district->updateData($request->input('id'),$this->data);
                $success_msg = 'District updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] =1;
                $this->_district->addData($this->data);
                $success_msg = 'District added successfully.';
            }


            return redirect()->route('district.index')->with('success', __($success_msg));
    	}
        return view('districtselection.create',compact('data','arrMunCode'));
	}

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'loc_local_code'=>'required|unique:rpt_district,loc_local_code,'.(int)$request->input('id').',id,dist_code,'.$request->input('dist_code').',dist_name,'.$request->input('dist_name'),
                'dist_code'=>'required|unique:rpt_district,dist_code,'.(int)$request->input('id'),
                'dist_name'=>'required|unique:rpt_district,dist_name,'.(int)$request->input('id')

            ],[
                'loc_local_code.required' => 'Required Field',
                'loc_local_code.unique' => 'Already Exists',
                'dist_code.required' => 'Required Field',
                'dist_code.unique' => 'Already Exists',
                'dist_name.required' => 'Required Field',
                'dist_name.unique' => 'Already Exists',
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
            $District = District::find($id);
            if($District->created_by == \Auth::user()->creatorId()){
                $District->delete();
            }
    }

}
