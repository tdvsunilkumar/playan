<?php

namespace App\Http\Controllers\Engneering;
use App\Models\CommonModelmaster;
use App\Models\Engneering\EngBldgPermitApp;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;

class EngBldgPermitAppController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrMuncipality = array(""=>"Please Select");
    public $arrBarangay = array(""=>"Please Select");
    public $arrapptype = array(""=>"Please Select");
    public $arrengScope = array(""=>"Please Select");
    public $arrOcupancytype = array(""=>"Please Select");
    public $arrSubOcupancytype = array(""=>"Please Select");
    public function __construct(){
		$this->_engbidgPermitApp = new EngBldgPermitApp();
        $this->_commonmodel = new CommonModelmaster(); 
        $this->data = array('id'=>'','ebpa_mun_no'=>'','ebpa_application_no'=>'','ebpa_permit_no'=>'','eba_id'=>'','ebpa_application_date'=>'','ebpa_issued_date'=>'','ebpa_owner_last_name'=>'','ebpa_owner_first_name'=>'','ebpa_owner_mid_name'=>'','ebpa_owner_suffix_name'=>'','ebpa_tax_acct_no'=>'','ebpa_form_of_own'=>'','ebpa_economic_act'=>'','ebpa_address_house_lot_no'=>'','ebpa_address_street_name'=>'','ebpa_address_subdivision'=>'','brgy_code'=>'','ebpa_location'=>'','ebs_id'=>'','ebpa_scope_remarks'=>'','no_of_units'=>'','ebot_id'=>'','ebost_id'=>'','ebpa_occ_other_remarks'=>'','ebpa_bldg_official_name'=>'');

         foreach ($this->_engbidgPermitApp->getMuncipalities() as $val) {
             $this->arrMuncipality[$val->id]=$val->mun_desc;
         }
         foreach ($this->_engbidgPermitApp->getBarangay() as $val) {
             $this->arrBarangay[$val->id]=$val->brgy_name;
         }
         foreach ($this->_engbidgPermitApp->getBuildAppType() as $val) {
             $this->arrapptype[$val->id]=$val->eba_description;
         }
          foreach ($this->_engbidgPermitApp->getBuildScopes() as $val) {
             $this->arrengScope[$val->id]=$val->ebs_description;
         }
         foreach ($this->_engbidgPermitApp->getOccupancytype() as $val) {
             $this->arrOcupancytype[$val->id]=$val->ebot_description;
         }
         foreach ($this->_engbidgPermitApp->getOccupancySubtype() as $val) {
             $this->arrSubOcupancytype[$val->id]=$val->ebost_description;
         }
    }
    
    public function index(Request $request)
    {
        return view('Engneering.engbuildpermitapp.index');
    }


    public function getList(Request $request){
        $data=$this->_engbidgPermitApp->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;    
        foreach ($data['data'] as $row){   
            $sr_no=$sr_no+1; 
            $arr[$i]['municipal']=$row->mun_desc;
            $arr[$i]['appno']=$row->ebpa_application_no;
            $arr[$i]['permitno']=$row->ebpa_permit_no;
            $arr[$i]['appdate']=date('Y-m-d',strtotime($row->ebpa_application_date));
            $arr[$i]['ownername']=$row->ebpa_owner_last_name.' '.$row->ebpa_owner_first_name.' '.$row->ebpa_owner_mid_name;
            $arr[$i]['issueddate']=date('Y-m-d',strtotime($row->ebpa_issued_date));
            $arr[$i]['address']=$row->ebpa_address_street_name;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engbldgpermitapp/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Bldg Permit App">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div><div class="action-btn bg-danger ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engbldgpermitapp/destroy?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Delete"  data-title=""Business Permit Fee Delete">
                       <i class="ti-trash text-white text-white"></i>
                    </a>
                </div>';
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


    public function store(Request $request){
        $data = (object)$this->data;
        $muncipality = $this->arrMuncipality;
        $buildingscope = $this->arrengScope;
        $arrBarangay = $this->arrBarangay;
        $arrapptype =$this->arrapptype;
        $buildingOccupancytype =  $this->arrOcupancytype;
        $buildingOccupancysubtype =$this->arrSubOcupancytype;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = EngBldgPermitApp::find($request->input('id')); 
        }
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            // $this->data['updated_by']=\Auth::user()->id;
            // $this->data['updated_at'] = date('Y-m-d H:i:s');
            // $this->data['updated_date'] = date('Y-m-d H:i:s');
            //  $this->data['taxclass_taxtype_classification_code'] = $classcode->tax_class_code.$typecode->type_code.$classificationcode->bbc_classification_code.$this->data['bba_code'];
            if($request->input('id')>0){
                $this->_engbidgPermitApp->updateData($request->input('id'),$this->data);
                $success_msg = 'Activity updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Building Permit App ".$this->data['ebpa_mun_no'];
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->_engbidgPermitApp->addData($this->data);
                $success_msg = 'Activity added successfully.';
                $content = "User ".\Auth::user()->name." Added Building Permit App ".$this->data['ebpa_mun_no'];
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->id;
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('engbldgpermitapp.index')->with('success', __($success_msg));
    	}
        return view('Engneering.engbuildpermitapp.create',compact('data','muncipality','buildingscope','arrBarangay','arrapptype','buildingOccupancytype','buildingOccupancysubtype'));
        
	}

	 public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'ebpa_mun_no'=>'required',
                'ebpa_application_no'=>'required',
                'ebpa_permit_no'=>'required',
                'eba_id'=>'required',
                'ebpa_application_date'=>'required',
                'ebpa_issued_date'=>'required',
                'ebpa_owner_last_name'=>'required',
                'ebpa_owner_first_name'=>'required',
                'ebpa_owner_mid_name'=>'required',
                'brgy_code'=>'required',
                'ebpa_location'=>'required',
                'ebs_id'=>'required',
                'ebpa_scope_remarks'=>'required',
                'ebost_id'=>'required',
                'ebot_id'=>'required',
                'ebpa_occ_other_remarks'=>'required',
                'ebpa_bldg_official_name'=>'required',
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
