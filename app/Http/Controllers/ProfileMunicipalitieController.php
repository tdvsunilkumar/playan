<?php

namespace App\Http\Controllers;

use App\Models\ProfileMunicipality;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
use Session;
class ProfileMunicipalitieController extends Controller
{
    
     public $data = [];
     public $nofbusscode = array(""=>"Select Code");
     public $brgyCode = array(""=>"Please Select");
     public $arrbbaCode = array(""=>"Please Select");
     public $arrMunCode = array(""=>"Please Select");
     public $arrHrEmpCode = array(""=>"Please Select");

    public function __construct(){
        $this->_profilemunicipalitie = new ProfileMunicipality();
        $this->slugs = 'administrative/address/municipality';
  $this->data = array('id'=>'','mun_code'=>'','reg_no'=>'','prov_no'=>'','mun_no'=>'','mun_desc'=>'','mun_zip_code'=>'','mun_area_code'=>'','mun_display_for_bplo'=>'','mun_display_for_rpt'=>'0','mun_display_for_welfare'=>'0','uacs_code'=>'','mun_display_for_accounting'=>'0','mun_display_for_economic'=>'0','mun_display_for_cpdo'=>'0','mun_display_for_eng'=>'0','mun_display_for_occupancy'=>'0');
  $this->localitydata = array('loc_local_code'=>'','loc_local_name'=>'',/*'loc_address'=>'',*/'loc_telephone_no'=>'','loc_fax_no'=>'',
        'loc_mayor_id'=>'','loc_administrator_id'=>'','loc_budget_officer_id'=>'','loc_budget_officer_position'=>'','loc_treasurer_id'=>'','loc_treasurer_position'=>'',
        'loc_chief_land_id'=>'','loc_chief_land_tax_position'=>'','loc_group_default_barangay_id' => '','loc_assessor_id'=>'','loc_assessor_position'=>'','loc_assessor_assistant_id'=>'',
        'loc_assessor_assistant_position'=>'','is_active'=>'1','mun_no'=>'','loc_accountant_id'=>"","loc_accountant_position"=>"","loc_chief_bplo_id"=>"","loc_chief_bplo_position"=>"","loc_welfare_head_id"=>"","loc_welfare_head_position"=>"",'asment_id'=>'','bfp_inspection_order'=>'0'); 

         foreach ($this->_profilemunicipalitie->getMunId() as $val) {
               $this->arrMunCode[$val->id]=$val->mun_no.'-'.$val->mun_desc;
          }
           foreach ($this->_profilemunicipalitie->getHrEmployeeCode() as $val) {
            if($val->suffix){
              $this->arrHrEmpCode[$val->id]=$val->title.' '.$val->firstname.' '.$val->middlename.' '.$val->lastname.', '.$val->suffix;
            }
            else{
                $this->arrHrEmpCode[$val->id]=$val->title.' '.$val->firstname.' '.$val->middlename.' '.$val->lastname;
            }
        }
    }
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
		return view('profilemunicipalitie.index');
    }
    public function ProfileProvinceData(Request $request){
        $id= $request->input('id');
        $data = $this->_profilemunicipalitie->ProfileProvinceData($id);
        echo json_encode($data);
    }
    public function getprofileRegioncodeId(Request $request){
    $getgroups = $this->_profilemunicipalitie->getprofileRegioncodebyid($request->input('id'));
       $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->prov_desc.'</option>';
      }
      echo $htmloption;
    }
    public function getList(Request $request){
        $data=$this->_profilemunicipalitie->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$j;
            $arr[$i]['reg_region']=$row->reg_region.' - '.$row->reg_description;
            $arr[$i]['prov_desc']=$row->prov_desc;
            $arr[$i]['mun_no']=$row->mun_no;
            $arr[$i]['mun_desc']=$row->mun_desc;
            $arr[$i]['mun_zip_code']=$row->mun_zip_code;
            $arr[$i]['mun_area_code']=$row->mun_area_code;
            $arr[$i]['mun_display_for_bplo']=($row->mun_display_for_bplo==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['mun_display_for_rpt']=($row->mun_display_for_rpt==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['mun_display_for_welfare']=($row->mun_display_for_welfare==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['mun_display_for_accounting']=($row->mun_display_for_accounting==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');

            $arr[$i]['mun_display_for_economic']=($row->mun_display_for_economic==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['mun_display_for_cpdo']=($row->mun_display_for_cpdo==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['mun_display_for_eng']=($row->mun_display_for_eng==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['mun_display_for_occupancy']=($row->mun_display_for_occupancy==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');

            
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/profilemunicipalitie/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Municipality ">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>'  
                ;
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
    
   public function updateDataMenuPermission(Request $request){
        $id = 51;
        $is_active = $request->input('is_active');
        $data=array('is_active' => $is_active);
       $this->_profilemunicipalitie->updateDataMenuPermission($id,$data);
    }
    public function ActiveInactive(Request $request){
		$this->is_permitted($this->slugs, 'update');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_profilemunicipalitie->updateActiveInactive($id,$data);
        Session::put('remort_serv_session_det', ['table' => "profile_municipalities",'action' =>"update",'id'=>$request->input('id')]);
    }

    public function store(Request $request){
        $arrDept = array();
        $arrDept[1]=$this->localitydata+array("dept_name"=>"Business Permit");
        $arrDept[2]=$this->localitydata+array("dept_name"=>"Real Property");
        $arrDept[5]=$this->localitydata+array("dept_name"=>"Economic & Investment");
        $arrDept[3]=$this->localitydata+array("dept_name"=>"Social Welfare");
        $arrDept[4]=$this->localitydata+array("dept_name"=>"Accounting");
        $arrDept[6]=$this->localitydata+array("dept_name"=>"Planning & Investment");
        $arrDept[7]=$this->localitydata+array("dept_name"=>"Engineering");
        $arrDept[8]=$this->localitydata+array("dept_name"=>"Occupancy");

        $data = (object)$this->data;
        $localitydata = (object)$this->localitydata;
        
        $arrHrEmpCode = $this->arrHrEmpCode;
        //echo "<pre>"; print_r($arrHrEmpCode); exit;
        $arrMunCode = $this->arrMunCode;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = ProfileMunicipality::find($request->input('id'));
            $localitydata = (array)$this->_profilemunicipalitie->getlocalitydata();
            // print_r($localitydata);exit;
            foreach($localitydata as $department_id=>$details){
                $details = (array)$details;
                if($details['department']==2){
                    $arrDept[1]=$details;
                    $arrDept[1]['dept_name']='Business Permit';
                }
                if($details['department']==1){
                    $arrDept[2]=$details;
                    $arrDept[2]['dept_name']='Real Property';
                }
                if($details['department']==5){
                    $arrDept[5]=$details;
                    $arrDept[5]['dept_name']='Economic & Investment';
                }
                if($details['department']==3){
                    $arrDept[3]=$details;
                    $arrDept[3]['dept_name']='Social Welfare';
                }
                if($details['department']==4){
                    $arrDept[4]=$details;
                    $arrDept[4]['dept_name']='Accounting';
                }
                if($details['department']==6){
                    $arrDept[6]=$details;
                    $arrDept[6]['dept_name']='Planning & Development';
                }
                if($details['department']==7){
                    $arrDept[7]=$details;
                    $arrDept[7]['dept_name']='Engineering';
                }
                if($details['department']==8){
                    $arrDept[8]=$details;
                    $arrDept[8]['dept_name']='Occupancy';
                }
                
            }
           
        }

        foreach ($this->_profilemunicipalitie->getProfileProvince() as $val) {
            $this->nofbusscode[$val->id]=$val->reg_region.' - '.$val->reg_description;
        }   
        foreach ($this->_profilemunicipalitie->getprofileRegioncodebyid($data->reg_no) as $val) {
           $this->arrbbaCode[$val->id]=$val->prov_desc;
        }

        if($request->input('id')>0)
        {
            foreach ($this->_profilemunicipalitie->getProfileBarangay($request->input('id')) as $val) {
                $this->brgyCode[$val->id]=$val->brgy_code.' - '.$val->brgy_name;
            }
            $brgyCode= $this->brgyCode; 
        }
        else{
            $brgyCode= NULL;
        }

         $nofbusscode = $this->nofbusscode;
         $arrbbaCode = $this->arrbbaCode;
        
        foreach((array)$this->data as $key=>$val){
            $this->data[$key] = $request->input($key);
        }
        
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $updatedId = $this->_profilemunicipalitie->updateData($request->input('id'),$this->data);
                $this->_profilemunicipalitie->setRptActiveForOnlyOne($updatedId);
                $success_msg = 'Profile Municipality updated successfully.';
                $insertedId = $request->input('id');
                Session::put('remort_serv_session_det', ['table' => "profile_municipalities",'action' =>"update",'id'=>$request->input('id')]);
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;

                $insertedId =$this->_profilemunicipalitie->addData($this->data);
                $this->_profilemunicipalitie->setRptActiveForOnlyOne($insertedId);
                $success_msg = 'Profile Municipality added successfully.';
                Session::put('remort_serv_session_det', ['table' => "profile_municipalities",'action' =>"store",'id'=>$insertedId]);
            }
            foreach($arrDept as $department_id=>$locality){
                $flag=0;
                if($department_id==1 && $request->input('mun_display_for_bplo')==1){
                    $flag=1;
                }
                if($department_id==2 && $request->input('mun_display_for_rpt')==1){
                    $flag=1;
                }
                if($department_id==3 && $request->input('mun_display_for_welfare')==1){
                    $flag=1;
                }
                if($department_id==4 && $request->input('mun_display_for_accounting')==1){
                    $flag=1;
                }
                if($department_id==5 && $request->input('mun_display_for_economic')==1){
                    $flag=1;
                }
                if($department_id==6 && $request->input('mun_display_for_cpdo')==1){
                    $flag=1;
                }
                if($department_id==7 && $request->input('mun_display_for_eng')==1){
                    $flag=1;
                }
                if($department_id==8 && $request->input('mun_display_for_occupancy')==1){
                    $flag=1;
                }
                if($flag==1){
                    $postData=array();
                    switch ($department_id) {
                        case 1:
                            $postData['department']=2;
                            break;
                        case 2:
                            $postData['department']=1;
                            break;
                        default:
                            $postData['department']=$department_id;
                            break;
                    }
                    $checklocalityexist = $this->_profilemunicipalitie->checkLocalityExist($postData['department']);
                    foreach((array)$this->localitydata as $key=>$val){
                        $postData[$key] = $request->input($key.$department_id);
                    }
                    if($postData['department']==1){
                        $postData['loc_local_code'] = $request->input('mun_no');
                        $postData['loc_local_name'] = $request->input('mun_desc');
                    }
                    if(count($checklocalityexist) > 0){
                        $postData['mun_no'] =$insertedId;
                        $postData['updated_by']=\Auth::user()->id;
                        $postData['updated_at'] = date('Y-m-d H:i:s');
                        $this->_profilemunicipalitie->updatelocality($checklocalityexist[0]->id,$postData);
                        $this->_profilemunicipalitie->remoteupdatelocality($checklocalityexist[0]->id,$postData);
                    }else{
                        $postData['mun_no'] =$insertedId;
                        $postData['created_by']=\Auth::user()->id;
                        $postData['created_at'] = date('Y-m-d H:i:s');
                        $this->_profilemunicipalitie->addLocalityData($postData); 
                        $this->_profilemunicipalitie->remotraddLocalityData($postData); 
                    }
                }
            }

            return redirect()->route('profilemunicipalitie.index')->with('success', __($success_msg));
        }
        return view('profilemunicipalitie.create',compact('data','nofbusscode','brgyCode','arrbbaCode','arrHrEmpCode','arrMunCode','arrDept'));
        
    }
    


    public function formValidation(Request $request){
        $arrFields=array(
            'uacs_code' => 'numeric|nullable'
        );
        $arrMsg=array();
        if (empty($request->input('id'))) {
            $arrFields +=array(
            'mun_no' => 'required|unique:profile_municipalities,mun_no,' .$request->input('id'),
            'reg_no'=>'required',
            'prov_no'=>'required',
            'mun_desc' => 'required|unique:profile_municipalities,mun_desc,' .$request->input('id')

            );
            $arrMsg+=array(
                'mun_no.unique' => 'Already Exists',
                'mun_desc.unique' => 'Already Exists'
            );
        }
        if($request->input('mun_display_for_bplo')==1){
            $arrFields +=array(
                'loc_local_code1'=>"required",
                'loc_telephone_no1' => 'required',
                'loc_mayor_id1' => 'required',
                'loc_budget_officer_id1'=>'required',
                'loc_budget_officer_position1'=>'required',
                'loc_treasurer_id1'=>'required',
                'loc_treasurer_position1'=>'required'
            );
            $arrMsg +=array(
                'loc_local_code1.required' => 'Please enter Local Code',
                'loc_telephone_no1.required' => 'Please enter Telephone No.',
                'loc_mayor_id1.required' => 'Please select Mayor',
                'loc_budget_officer_id1.required'=>'Please select Officer Name',
                'loc_budget_officer_position1.required'=>'Please enter Possition',
                'loc_treasurer_id1.required'=>'Please select Treasurer',
                'loc_treasurer_position1.required'=>'Please enter Treasurer'
            );
        }
        if($request->input('mun_display_for_rpt')==1){
            $arrFields +=array(
                'loc_telephone_no2' => 'required',
                'loc_mayor_id2' => 'required',
                'loc_budget_officer_id2'=>'required',
                'loc_budget_officer_position2'=>'required',
                'loc_treasurer_id2'=>'required',
                'loc_treasurer_position2'=>'required'
            );
            $arrMsg +=array(
                'loc_telephone_no2.required' => 'Please enter Telephone No.',
                'loc_mayor_id2.required' => 'Please select Mayor',
                'loc_budget_officer_id2.required'=>'Please select Officer Name',
                'loc_budget_officer_position2.required'=>'Please enter Possition',
                'loc_treasurer_id2.required'=>'Please select Treasurer',
                'loc_treasurer_position2.required'=>'Please enter Treasurer'
            );
        }
        $validator = \Validator::make(
            $request->all(),$arrFields,$arrMsg
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
        $ProfileMunicipality = ProfileMunicipality::find($id);
        if($ProfileMunicipality->created_by == \Auth::user()->creatorId()){
            $ProfileMunicipality->delete();
        }
    }

    public function getOfficerposition(Request $request){
        $id= $request->input('id');
        $data = $this->_profilemunicipalitie->getOfficerPosition($id);
        echo json_encode($data);
    }

    public function getUACScode(Request $request)
    {
        $reg_id= $request->input('reg_id');
        $prov_id= $request->input('prov_id');
        $prov_code = $this->_profilemunicipalitie->province($prov_id)->uacs_code;
        $reg_code = $this->_profilemunicipalitie->region($reg_id)->uacs_code;
        
        return $reg_code.'-'.$prov_code;
    }
}
