<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Barangay;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;
class BarangayController extends Controller
{
     public $data = [];
     public $nofbusscode = array(""=>"Select Code");
     public $arrbbaCode = array(""=>"Please Select");
     public $arrMunCode = array(""=>"Please Select");
     public $arrDistrictCodes = array(""=>"Select District Code");
    public $postdata = [];
    public function __construct(){
		$this->_barangay = new Barangay(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','brgy_code'=>'','reg_no'=>'','prov_no'=>'','mun_no'=>'','brgy_name'=>'','brgy_area_code'=>'','brgy_office'=>'','dist_code'=>'','brgy_display_for_bplo'=>'','brgy_display_for_rpt'=>'','brgy_display_for_rpt_locgroup'=>'','uacs_code'=>"");  
    }
    
    
    public function index(Request $request)
    {
        $barangay=array(""=>"Please select");
        return view('barangay.index',compact('barangay'));
    }
    public function getBarngayMunProvRegionList(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_barangay->getBarngayMunProvRegionList($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->mun_desc;
            $arr['data'][$key]['text']=$val->mun_desc.", ".$val->prov_desc.", ".$val->reg_region;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function getprofileProvcodeId(Request $request){
    $getgroups = $this->_barangay->getprofileProvcodeId($request->input('id'));
       $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->mun_no.'-'.$value->mun_desc.'</option>';
      }
      echo $htmloption;
    }
    public function getDistrictCodes(Request $request){
    $getgroups = $this->_barangay->getDistrictCodes($request->input('id'));
       $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->dist_code.'-'.$value->dist_name.'</option>';
      }
      echo $htmloption;
    }
    
    public function getList(Request $request){
        $isDisplayBrgyId = $this->_commonmodel->getSettingData('IS_DISPLAY_BRGY_ID');
        $data=$this->_barangay->getList($request);
        $arr=array();
        $i="0"; 
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        $copyHtml='';
        foreach ($data['data'] as $row){
            $j=$j+1;

            if($isDisplayBrgyId){
                $copyHtml = "<span class='btn btn-success copybrgyId' style='padding: 0.1rem 0.1rem !important;font-size:9px;' bid='".$row->id."'>Copy</a>";
            }
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$j;
            $arr[$i]['reg_region']=$row->reg_region;
            $arr[$i]['prov_desc']=$row->prov_desc;
            $arr[$i]['mun_desc']=$row->mun_desc;
            $arr[$i]['brgy_code']=$row->brgy_code;
            $arr[$i]['brgy_name']=$row->brgy_name.' '.$copyHtml;
            $arr[$i]['brgy_office']=$row->brgy_office;
            $arr[$i]['brgy_display_for_bplo']=($row->brgy_display_for_bplo==1?'<span class="btn btn-success" style="padding: 5px;">Yes</span>':'<span class="btn btn-warning" style="padding: 5px;">No</span>');

            $arr[$i]['brgy_display_for_rpt']=($row->brgy_display_for_rpt==1?'<span class="btn btn-success" style="padding: 5px;">Yes</span>':'<span class="btn btn-warning" style="padding: 5px;">No</span>');

            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/barangay/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Barangay">
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
        $data=array('is_active' => $is_activeinactive);
        $this->_barangay->updateActiveInactive($id,$data);
        Session::put('remort_serv_session_det', ['table' => "barangays",'action' =>"update",'id'=>$request->input('id')]);
    }
    

    public function store(Request $request){
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = Barangay::find($request->input('id'));
        }
        foreach ($this->_barangay->getProfileProvince() as $val) {
            $this->nofbusscode[$val->id]=$val->reg_region;
        }
        foreach ($this->_barangay->getprofileRegioncodebyid($data->reg_no) as $val) {
               $this->arrbbaCode[$val->id]=$val->prov_desc;
        }
        foreach ($this->_barangay->getprofileProvcodeId($data->prov_no) as $val) {
               $this->arrMunCode[$val->id]=$val->mun_no.'-'.$val->mun_desc;
        }
        foreach ($this->_barangay->getDistrictCodes($data->mun_no) as $val) {
               $this->arrDistrictCodes[$val->id]=$val->dist_code.'-'.$val->dist_name;
        }

        

         $nofbusscode = $this->nofbusscode;
         $arrbbaCode = $this->arrbbaCode;
         $arrMunCode = $this->arrMunCode;
         $districtCodes = $this->arrDistrictCodes;
         foreach((array)$this->data as $key=>$val){
            $this->data[$key] = $request->input($key);
        }
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['created_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['is_active'] =1;
            if($request->input('id')>0){
                $updatedId = $this->_barangay->updateData($request->input('id'),$this->data);
                /*$this->_barangay->setRptActiveForOnlyOne($updatedId);*/
                $success_msg = 'Barangay updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Barangay ".$this->data['brgy_name'];
                Session::put('remort_serv_session_det', ['table' => "barangays",'action' =>"update",'id'=>$request->input('id')]);
            }else{
                $insertedId = $this->_barangay->addData($this->data);
                /*$this->_barangay->setRptActiveForOnlyOne($insertedId);*/
                $success_msg = 'Barangay added successfully.';
                $content = "User ".\Auth::user()->name." Added Barangay ".$this->data['brgy_name'];
                Session::put('remort_serv_session_det', ['table' => "barangays",'action' =>"store",'id'=>$insertedId]);
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('barangay.index')->with('success', __($success_msg));
    	}
        return view('barangay.create',compact('data','nofbusscode','arrbbaCode','arrMunCode','districtCodes'));
	}
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                // 'brgy_code'=>'required|unique:barangays,brgy_code,'.(int)$request->input('id'),
                // 'brgy_name'=>'required|unique:barangays,brgy_code,'.(int)$request->input('id'),
                'brgy_code'=>'required',
                'reg_no'=>'required',
                'prov_no'=>'required',
                'mun_no'=>'required',
                'brgy_name'=>'required',
                'brgy_display_for_bplo'=>'required',
                'brgy_display_for_rpt'=>'required',
                'uacs_code'=>'numeric|nullable'

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
