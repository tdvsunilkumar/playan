<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Locality;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;

class LocalityController extends Controller
{
     public $data = [];
     public $postdata = [];
     public $arrMunCode = array(""=>"Please Select");
     public $arrHrEmpCode = array(""=>"Please Select");
     public function __construct(){
		$this->_locality = new Locality(); 
        $this->_commonmodel = new CommonModelmaster();
        

        $this->data = array('id'=>'','loc_local_code'=>'','loc_local_name'=>'','loc_address'=>'','loc_telephone_no'=>'','loc_fax_no'=>'','loc_mayor_id'=>'','loc_administrator_id'=>'','loc_budget_officer_id'=>'','loc_budget_officer_position'=>'','loc_treasurer_id'=>'','loc_treasurer_position'=>'','loc_chief_land_id'=>'','loc_chief_land_tax_position'=>'','loc_assessor_id'=>'','loc_assessor_position'=>'','loc_assessor_assistant_id'=>'','loc_assessor_assistant_position'=>'','mun_no'=>'1','asment_id'=>'');  
          foreach ($this->_locality->getMunId() as $val) {
               $this->arrMunCode[$val->id]=$val->mun_no.'-'.$val->mun_desc;
          }
           foreach ($this->_locality->getHrEmployeeCode() as $val) {
            $this->arrHrEmpCode[$val->id]=$val->firstname.' '.$val->middlename.' '.$val->lastname;
        }      
    }
    
    public function index(Request $request)
    {
            // $Locality = $this->_locality->getLocality();
            // return view('locality.index', compact('Locality'));

            
                return view('locality.index');
            
    }
    public function getList(Request $request){


        $data=$this->_locality->getList($request);
        $arr=array();
        $i="0";    
        foreach ($data['data'] as $row){
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$i+1;
            $arr[$i]['loc_local_code']=$row->mun_no.'-'.$row->mun_desc;
            $arr[$i]['loc_address']=$row->loc_address;
            $arr[$i]['loc_telephone_no']=$row->loc_telephone_no;
            $arr[$i]['loc_fax_no']=$row->loc_fax_no;
            $arr[$i]['loc_mayor']=$row->firstname.' '.$row->middlename.' '.$row->lastname;
            $arr[$i]['loc_administrator_name']=$row->p1firstname.' '.$row->p1middlename.' '.$row->p1lastname;
            $arr[$i]['loc_budget_officer_name']=$row->p2firstname.' '.$row->p2middlename.' '.$row->p2lastname;
            $arr[$i]['loc_budget_officer_position']=$row->loc_budget_officer_position;
            $arr[$i]['loc_treasurer_name']=$row->p3firstname.' '.$row->p3middlename.' '.$row->p3lastname;
            $arr[$i]['loc_treasurer_position']=$row->loc_treasurer_position;
            $arr[$i]['loc_chief_land_tax']=$row->p4firstname.' '.$row->p4middlename.' '.$row->p4lastname;
            $arr[$i]['loc_chief_land_tax_position']=$row->loc_chief_land_tax_position;
            $arr[$i]['loc_assessor_name']=$row->p5firstname.' '.$row->p5middlename.' '.$row->p5lastname;
            $arr[$i]['loc_assessor_position']=$row->loc_assessor_position;
            $arr[$i]['loc_assessor_assistant_name']=$row->p6firstname.' '.$row->p6middlename.' '.$row->p6lastname;
            $arr[$i]['loc_assessor_assistant_position']=$row->loc_assessor_assistant_position;
            $arr[$i]['asment_id']=($row->asment_id==1?'Local Government':'National Government');
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            
                                 
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/locality/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Locality ">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>'  ;
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
    
    public function ActiveInactive(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_locality->updateActiveInactive($id,$data);
        Session::put('remort_serv_session_det', ['table' => "rpt_locality",'action' =>"update",'id'=>$request->input('id')]);
}  
    public function store(Request $request){
        
        $data = (object)$this->data;
        $arrHrEmpCode = $this->arrHrEmpCode;
        $arrMunCode = $this->arrMunCode;
         if($request->input('id')>0 && $request->input('submit')==""){
            $data = Locality::find($request->input('id'));
            
        }
       

       if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_locality->updateData($request->input('id'),$this->data);
                $success_msg = 'Locality updated successfully.';
                Session::put('remort_serv_session_det', ['table' => "rpt_locality",'action' =>"update",'id'=>$request->input('id')]);
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
               
                $last_insert_id=$this->_locality->addData($this->data);
                $success_msg = 'Locality added successfully.';
                Session::put('remort_serv_session_det', ['table' => "rpt_locality",'action' =>"store",'id'=>$last_insert_id]);
            }
            return redirect()->route('locality.index')->with('success', __($success_msg));
        }


        
        return view('locality.create',compact('data','arrHrEmpCode','arrMunCode'));
	}
    // public function formValidation(Request $request){
    //     $validator = \Validator::make(
    //         $request->all(), [
    //             // 'brgy_code'=>'required|unique:barangays,brgy_code,'.(int)$request->input('id'),
    //             // 'brgy_name'=>'required|unique:barangays,brgy_code,'.(int)$request->input('id'),
    //             // 'brgy_office'=>'required'

    //         ]
    //     );
    //     $arr=array('ESTATUS'=>0);
    //     if($validator->fails()){
    //         $messages = $validator->getMessageBag();
    //         $arr['field_name'] = $messages->keys()[0];
    //         $arr['error'] = $messages->all()[0];
    //         $arr['ESTATUS'] = 1;
    //     }
    //     echo json_encode($arr);exit;
    // }

    public function Delete(Request $request){
        $id = $request->input('id');
            $locality = Locality::find($id);
            if($locality->created_by == \Auth::user()->creatorId()){
                $locality->delete();
            }
    }
   

}
