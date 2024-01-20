<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\HeSaFamilyPlanningModel;
use App\Models\Barangay;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Models\HelSafRegistration;
use File;
class HeSaFamilyPlanning extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
	 public $arrgetBrgyCode = array(""=>"Please Select");
	 public $getcitizens = array(""=>"Please Select");
     public function __construct(){
        $this->_HeSaFamilyPlanning = new HeSaFamilyPlanningModel();
		$this->_Barangay = new Barangay();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','cit_id'=>'','fam_ref_id'=>'','age'=>'','house_lot_no'=>'','street_name'=>'','subdivision'=>'','brgy_id'=>'','fam_date'=>Carbon::now());  
        $this->slugs = 'health-safety-family-planning';
		foreach ($this->_HeSaFamilyPlanning->getCitizens() as $val) {
             $this->getcitizens[$val->id]=$val->cit_fullname;
        }
		
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('familyplanning.index');
    }
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_HeSaFamilyPlanning->getList($request);
		
		$getcitizens  = $this->getcitizens;
        $arr=array();
		
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
			
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/health-safety-family-planning/store?id='.$row->fam_ref_id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Family Planning">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->fam_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $arr[$i]['srno']= $sr_no;
			$arr[$i]['fam_ref_id']= $row->fam_ref_id;
			$arr[$i]['cit_id']= $row->allNames;
			$gend=[];
			$aa=explode(" ",$row->allGender);
			$t = 0;
			while($t < 2) {
				$gend[]=($aa[$t]=='1')?'Female':'Male';
				$t++;
			}
			$arr[$i]['gender']=$gend[0].'<br>'.$gend[1];
			$arr[$i]['age']= $row->allAge;
            $arr[$i]['address']= wordwrap($this->_HeSaFamilyPlanning->getTaxPayerAddress($row->id), 40, "<br />\n");
            $arr[$i]['date']= Carbon::parse($row->fam_date)->format('M d, Y');
            $arr[$i]['fam_is_active']= ($row->fam_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']= $actions;
           
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
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('fam_is_active' => $is_activeinactive);
        $this->_HeSaFamilyPlanning->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Pregnancy Test ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
		if(($request->input('id')>0 && $request->input('submit')=="")){
            $citid = $request->input('id');
        }
        if (isset($request->id)) {
			
			$citid = $request->id;
            
        }
		$barangays = array();
        if (isset($citid)) {
            $data = $this->_HeSaFamilyPlanning->getEditDetails($request->input('id'));
			if($request->input('brgy_id')){
				foreach ($this->_commonmodel->getBarangay($request->input('brgy_id'))['data'] as $val) {
					$barangays[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
				}
			}else{	
				foreach ($this->_commonmodel->getBarangay($data->brgy_id)['data'] as $val) {
					$barangays[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
				}	
			}
        }
		$getcitizens = $this->getcitizens;
		$current_years=date('Y');
        $data = (object)$this->data;
        $partners = [];
		$years = substr($current_years, -2);
        $incre_no_req_id=$this->generatelabreqidNumber("FP-".$years);
        $data->fam_ref_id= $incre_no_req_id;
		//print_r($data->fam_ref_id);die;
        if($request->input('id') != null && $request->input('submit')==""){
            $data = $this->_HeSaFamilyPlanning->getEditDetails($request->input('id'));
            $partners = $this->_HeSaFamilyPlanning->getPartnersDetails($request->input('id'));
        }

        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['fam_plan_modified_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
				$i=0;
                while($i < 2){
					$this->data['cit_id']=$_POST['cit_id'][$i];
					$this->data['age']=$_POST['age'][$i];
                    $famid = $_POST['fam_id'][$i];
                    $this->_HeSaFamilyPlanning->updateData($famid,$this->data);
                    $i++;
                }
                $success_msg = 'Updated successfully.';
            }else{
				$i=0;
				while($i < 2) {
                    $this->data['fam_ref_id']   =$incre_no_req_id;
					$this->data['house_lot_no'] =$request->input('house_lot_no');
					$this->data['street_name']  =$request->input('street_name');
					$this->data['subdivision']  =$request->input('subdivision');
					$this->data['brgy_id']      =$request->input('brgy_id');
					$this->data['age']          =$_POST['age'][$i];
					$this->data['cit_id']       =$_POST['cit_id'][$i];
					$this->data['fam_plan_created_by']=\Auth::user()->id;
					$this->data['created_at'] = date('Y-m-d H:i:s');
					$this->data['fam_is_active'] = 1;
                    $request->id = $this->_HeSaFamilyPlanning->addData($this->data);
                    HelSafRegistration::register($_POST['cit_id'][$i],$this->data['fam_date'],'is_family_planning');
					
				 $i++;
				}
				$success_msg = 'Added successfully.';				
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->back();
        }
		if(!empty($data->doc_json)){
        $arrdocDtls = $this->generateDocumentList($data->doc_json,$data->id);
            if(isset($arrdocDtls)){
                $data->arrDocumentDetailsHtml = $arrdocDtls;
            }
        }else{
            $data->arrDocumentDetailsHtml ="";
        }
        return view('familyplanning.create',compact('data','getcitizens','partners','barangays'));
    }
   
    public function generatelabreqidNumber($company_code) {
        $prefix = $company_code;
        $last_bookingq=DB::table('ho_fam_plan')->orderBy('id','desc');
            if($last_bookingq->count() > 0){
                $last_booking=$last_bookingq->first()->fam_ref_id;
            } else {
              $last_booking='0000';
            }
            if($last_booking){
                $last_booking=$last_booking;
            } else {
                   $last_booking='0000';
            }
            
        $last_number = str_replace($prefix, "", $last_booking);
        $counter = intval(ltrim($last_number, "0")) + 1;
        $appliction_no =$prefix . str_pad($counter, 4, 0, STR_PAD_LEFT);
        return $appliction_no;
    }
	
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'fam_date'=>'required',
				'brgy_id'=>'required',
				'cit_id' => 'array',
				'cit_id.*' => 'required|distinct'
            ],[
			'fam_date.required'=>'Date is Required',
			'brgy_id.required'=>'Barangay is Required',
			'cit_id.0.required'=>'Citizen is Required',
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
	
    public function getCitizensname(Request $request){
		$id = $request->input('id');
		$data1 = $this->_HeSaFamilyPlanning->getCitizensname($id);
		foreach ($this->_commonmodel->getBarangay($data1->brgy_id)['data'] as $val) {
			$address=$barangays[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
		}
		$data['address'] =$address;
		$data['cit_house_lot_no'] =$data1->cit_house_lot_no;
		$data['cit_street_name'] =$data1->cit_street_name;
		$data['cit_subdivision'] =$data1->cit_subdivision;
		$data['brgy_id'] =$data1->brgy_id;
		$data['cit_age'] =$data1->cit_age;
		$data['cit_gender'] =$data1->cit_gender;
	  
	  return $data;
	}
	public function getRefreshHelSaf(Request $request){
       $getgroups = $this->_HeSaFamilyPlanning->getcitizensRefresh();
       $htmloption ="";
      foreach ($getgroups as $key => $value) {
            $htmloption .='<option value="'.$value->id.'">'.$value->cit_first_name.'  '.$value->cit_middle_name.' '.$value->cit_last_name.'</option>';
      }
      echo $htmloption;
    }
	
	public function uploadDocument(Request $request){
        
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = HeSaFamilyPlanningModel::find($healthCertId);
        
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->doc_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/familyplanning/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                $arrData = array();
                $arrData['doc_id'] = count($arrJson)+1;
                $arrData['filename'] = $filename;
                $finalJsone[] = $arrData;
                if(isset($arrEndrosment)){
                    $arrJson = json_decode($arrEndrosment->doc_json,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['doc_json'] = json_encode($finalJsone);
                $this->_HeSaFamilyPlanning->updateData($healthCertId,$data);
                $arrDocumentList = $this->generateDocumentList($data['doc_json'],$healthCertId);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
    public function generateDocumentList($arrJson,$healthCertid){
        $html = "";
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $html .= "<tr>
                        <td>".$val['filename']."</td>
                        <td>
                            <div class='action-btn bg-success ms-2'>
                                <a class='btn' href='".asset('uploads/familyplanning').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
                            </div>
                            <div class='action-btn bg-danger ms-2'>
                                <a href='#' class='mx-3 btn btn-sm deleteAttachment ti-trash text-white text-white' doc_id='".$val['doc_id']."' healthCertid='".$healthCertid."'></a>
                            </div>
                        </td>
                    </tr>";
                }
            }
        }
        return $html;
    }
    public function deleteAttachment(Request $request){
        $healthCertid = $request->input('healthCertid');
        $doc_id = $request->input('doc_id');
        $arrEndrosment = HeSaFamilyPlanningModel::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->doc_json,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/familyplanning/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['doc_json'] = json_encode($arrJson);
                    $this->_HeSaFamilyPlanning->updateData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }
}
