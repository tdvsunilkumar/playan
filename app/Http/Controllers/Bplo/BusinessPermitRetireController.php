<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\BusinessPermitRetire;
use App\Models\CommonModelmaster;
use App\Models\BploAssessmentCalculationCommon;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use DB;
class BusinessPermitRetireController extends Controller
{
    public $data = [];
    private $slugs;
    public function __construct(){
        $this->_BusinessPermitRetire = new BusinessPermitRetire(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_assessmentCalculationCommon = new BploAssessmentCalculationCommon();
        $this->data = array('id'=>'','busn_id'=>'','retire_application_type'=>'2','retire_reason_ids'=>'','retire_reason_remarks'=>'','retire_date_start'=>'','retire_date_closed'=>'','retire_remarks'=>'','retire_status'=>'0','retire_bldg_area'=>'','retire_bldg_total_floor_area'=>'','retire_employee_no_female'=>'','retire_employee_no_male'=>'','retire_employee_total_no'=>'','retire_employee_no_lgu'=>'','retire_vehicle_no_van_truck'=>'','retire_vehicle_no_motorcycle'=>'');
        $this->slugs = 'business-permit-retire';
    }
    public function index(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $arrYears=array(""=>"Select Year");
        $arrYrs = $this->_BusinessPermitRetire->getYearDetails();
        foreach($arrYrs AS $key=>$val){
            $arrYears[$val->retire_year] =$val->retire_year;
        }
        return view('Bplo.retire.index',compact('arrYears'));
    }
    public function getList(Request $request){
        $data=$this->_BusinessPermitRetire->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  

        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;  
            $arr[$i]['busns_id_no']=$row->busns_id_no;
            $ownar_name=$row->rpo_first_name.' '.$row->rpo_middle_name.' '.$row->rpo_custom_last_name;
            if(!empty($row->suffix)){
                $ownar_name .=", ".$row->suffix;
            }
            
            $arr[$i]['ownar_name']=$ownar_name;
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['retire_app_type']=($row->retire_application_type==2)?'Entire Business':'Per Line of Business';
            $reason = wordwrap($row->retire_reason_remarks, 50, "<br>\n");
            $arr[$i]['reason']=$row->name;

             $arr[$i]['status']=($row->retire_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Submitted</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Draft</span>');


            $arr[$i]['retire_date_start']=date("M d, Y",strtotime($row->retire_date_start));
            $arr[$i]['retire_date_closed']=date("M d, Y",strtotime($row->retire_date_closed));
            $arr[$i]['duration']=$this->_commonmodel->calculateTotalYearMonth($row->retire_date_start,$row->retire_date_closed);
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/business-permit-retire/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Retirement of Business">
                        <i class="ti-pencil text-white"></i>
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
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
        $data = (object)$this->data;
        $arrDocumentDetailsHtml="";
        $arrSubClass=array(""=>"Please Select");
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_BusinessPermitRetire->getEditDetails($request->input('id'));
            $arrdocDtls = $this->generateDocumentList($data->retire_documentary_json,$data->id);
            if(isset($arrdocDtls)){
                $arrDocumentDetailsHtml = $arrdocDtls;
            }
            $arrClss=$this->_BusinessPermitRetire->getLineOfBusiness($data->busn_id);
            foreach($arrClss AS $key=>$val){
                $arrSubClass[$val->subclass_id]=$val->subclass_code.'=>'.$val->subclass_description;
            }
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            unset($this->data['retire_status']);
            // dd($request->input('submitAction'));
            if($request->input('submitAction')=='Submit'){
                $this->data['retire_status']=1;
            }else{
                $this->data['retire_status']=0;
            }   
            $this->data['retire_reason_ids']=implode(',',$this->data['retire_reason_ids']);
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_BusinessPermitRetire->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "'".$request->input('busn_name')."' Business Retired update by '".\Auth::user()->name."'"; 
            }else{
                $arrBusDtls = $this->_BusinessPermitRetire->getbussDtls($this->data['busn_id']);
                if(isset($arrBusDtls)){
                    $this->data['prev_app_code'] = $arrBusDtls->app_code;
                }
                $this->data['retire_year'] = date('Y');
                $this->data['retire_month'] = date('m');
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $request->id = $this->_BusinessPermitRetire->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] =  "'".$request->input('busn_name')."' Business Retir added by '".\Auth::user()->name."'"; 
            }
            $arrDetails = $request->input('subclass_id');
            if(count($arrDetails) >0){
                $dataDtls=array();
                $dataDtls['updated_by']=\Auth::user()->id;
                $dataDtls['updated_at'] = date('Y-m-d H:i:s');
                foreach ($arrDetails as $key => $value){
                    $dataDtls['busnret_id'] = $request->id;
                    $dataDtls['busn_id'] =$this->data['busn_id'];
                    $dataDtls['subclass_id'] =$value;
                    $dataDtls['busnret_no_units'] = $request->input('busnret_no_units_'.$value);
                    $dataDtls['busnret_capital_investment'] = $request->input('busnret_capital_investment_'.$value);
                    $dataDtls['busnret_essential'] = $request->input('busnret_essential_'.$value);
                    $dataDtls['busnret_non_essential'] = $request->input('busnret_non_essential_'.$value);
                    $dataDtls['busnret_total_gross'] = $dataDtls['busnret_essential'] + $dataDtls['busnret_non_essential'];
                    $checkdetailexist =  $this->_BusinessPermitRetire->checkRecordIsExist($value,$request->id);
                    if(count($checkdetailexist) > 0){
                        $this->_BusinessPermitRetire->updateRetirementDetails($checkdetailexist[0]->id,$dataDtls);
                    } else{
                        $dataDtls['created_by']=\Auth::user()->id;
                        $dataDtls['created_at'] = date('Y-m-d H:i:s');
                        $this->_BusinessPermitRetire->addRetirementDetails($dataDtls);
                    }
                }
                $this->_BusinessPermitRetire->deletePSICSubclass($this->data['busn_id'],$request->id,$request->input('subclass_id'));
            }
            if($request->input('submitAction')=='Submit'){
                //Create Retire Assessment Details
                $this->_assessmentCalculationCommon->data=array();
                $pm_id=1;
                $app_code=3; // Retire application
                $retire_id= $request->id;
                $this->_assessmentCalculationCommon->calculateAssessmentDetails($this->data['busn_id'],$app_code,$pm_id,'saveData',date("Y"),0,$retire_id);
                //$this->_assessmentCalculationCommon->updateDelinquencyDetails($this->data['busn_id'],$app_code,date("Y"),$retire_id);
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('BusinessPermitRetire.index')->with('success', __($success_msg));
        }
        $arrRetireReason = array();
        foreach ($this->_BusinessPermitRetire->getRetireReason($data->retire_reason_ids) as $val) {
           $arrRetireReason[$val->id]=$val->name;
        }
        $arrReq= array();
        foreach ($this->_BusinessPermitRetire->getRetireReason($data->retire_reason_ids) as $val) {
           $arrReq[$val->id]=$val->name;
        }
        $arrBussNo=array(""=>"Please Select");
        foreach ($this->_BusinessPermitRetire->getBussinessList($data->busn_id) as $val) {
           $arrBussNo[$val->id]=$val->busns_id_no.'=>'.$val->busn_name;
        }


        return view('Bplo.retire.create',compact('data','arrBussNo','arrRetireReason','arrDocumentDetailsHtml','arrReq','arrSubClass'));
    }
    public function getRequirementList(Request $request){
        $sub_class_id=$request->input('sub_class_id');
        $arr=$this->_BusinessPermitRetire->getRequirementList($sub_class_id);
        $html="<option value=''>Please Select</opion>";
        foreach($arr AS $key=>$val){
            $html.='<option value="'.$val->id.'">'.$val->req_code_abbreviation.'=>'.$val->req_description.'</opion>';
        }
        $arr['ESTATUS']=0;
        $arr['html']=$html;
        echo json_encode($arr);exit;
    }
    public function generateDocumentList($arrJson,$id, $status='0'){
        $html = "";
        $dclass = ($status>0)?'disabled-status':'';
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $sub_class_name = wordwrap($val['sub_class_name'], 50, "<br>\n");
                    $requirement_name = wordwrap($val['requirement_name'], 50, "<br>\n");
                    $html .= "<tr>
                        <td><span class='showLessDoc'>".$sub_class_name."</span></td>
                        <td><span class='showLessDoc'>".$requirement_name."</span></td>
                        <td><a class='btn' href='".asset('uploads/retire_documents').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a></td>
                        <td>
                            <div class='action-btn bg-danger ms-2'>
                                <a href='#' class='mx-3 btn btn-sm deleteDocument ti-trash text-white text-white ".$dclass."' rid='".$val['requirement_id']."' eid='".$id."'></a>
                            </div>
                        </td>
                    </tr>";
                }
            }
        }
        return $html;
    }
    public function formValidation(Request $request){
        // dd($request->input('submitAction'));
        $ids = $request->input('retire_reason_ids%5B%5D');
        $subclass_id= $request->input('subclass_id%5B%5D');
        $start_date =date("ymd",strtotime($request->input('retire_date_start')));
        $end_date =date("ymd",strtotime($request->input('retire_date_closed')));
        $arr=array('ESTATUS'=>0);
        if(empty($ids)){
            $arr['field_name'] = 'retire_reason_ids';
            $arr['error'] = 'Please select at least one Reason for retirement.';
            $arr['ESTATUS'] = 1;
        }elseif(empty($subclass_id)){
            $arr['field_name'] = 'nature_of_buss';
            $arr['error'] = 'Please select at least one Line of Business.';
            $arr['ESTATUS'] = 1;
        }elseif(!empty($request->input('retire_date_closed')) && $start_date>$end_date){
            $arr['field_name'] = 'retire_date_closed';
            $arr['error'] = 'Closed Date should be greater than Date Established.';
            $arr['ESTATUS'] = 1;
        }
        if($request->input('submitAction')=='Submit'){
            if($request->input('id') != null){
                $arrDtls = $this->_BusinessPermitRetire->getEditDetails($request->input('id'));
                $message='';
                $ESTATUS=0;
                $arrDocumentList='';
                if(isset($arrDtls)){
                    if($arrDtls->retire_documentary_json == null or $arrDtls->retire_documentary_json == "[]"){
                        $arr['field_name'] = 'err_sub_class_id';
                        $arr['error'] = 'You have to upload atleast one document.';
                        $arr['ESTATUS'] = 2;
                    }
                }
            }
            else{
                $arr['field_name'] = 'err_sub_class_id';
                $arr['error'] = 'You have to upload atleast one document.';
                $arr['ESTATUS'] = 2;  
            }
            
        }
        echo json_encode($arr);exit;
    }

    public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $id = $request->input('id');
        $arr = $this->_BusinessPermitRetire->getEditDetails($id);
        if(isset($arr)){
            $arrJson = json_decode($arr->retire_documentary_json,true);
            if(isset($arrJson)){
                $key  = array_search($rid, array_column($arrJson, 'requirement_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/retire_documents/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['retire_documentary_json'] = json_encode($arrJson);
                    $this->_BusinessPermitRetire->updateData($id,$data);
                    echo "deleted";
                }
            }
        }
    }
    public function uploadDocument(Request $request){
        $id =  $request->input('id');
        $sub_class_id =  $request->input('sub_class_id');
        $requirement_id =  $request->input('requirement_id');
        $requirement_name =  $request->input('requirement_name');
        $sub_class_name =  $request->input('sub_class_name');
        $arrDtls = $this->_BusinessPermitRetire->getEditDetails($id);
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        if(isset($arrDtls)){
            $arrJson = (array)json_decode($arrDtls->retire_documentary_json,true);
            $key  = array_search($requirement_id, array_column($arrJson, 'requirement_id'));
            if($key !== false){
                $message="This requirement is already exist";
                $ESTATUS=1;
            }
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/retire_documents/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                $arrData = array();
                $arrData['requirement_id'] = $requirement_id;
                $arrData['requirement_name'] = $requirement_name;
                $arrData['sub_class_id'] = $sub_class_id;
                $arrData['sub_class_name'] = $sub_class_name;
                $arrData['filename'] = $filename;
                $finalJsone[] = $arrData;
                if(isset($arrDtls)){
                    $arrJson = json_decode($arrDtls->retire_documentary_json,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['retire_documentary_json'] = json_encode($finalJsone);
                $this->_BusinessPermitRetire->updateData($id,$data);
                $arrDocumentList = $this->generateDocumentList($data['retire_documentary_json'],$id);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
    public function getBusinessDetails(Request $request){
        $busn_id=$request->input('busn_id');
        $id =$request->input('id');
        $type=$request->input('retire_application_type');
        $allchecked = ($type=='2')?'checked':'';
        $arrBusDtls = $this->_BusinessPermitRetire->getbussDtls($busn_id);
        if(isset($arrBusDtls)){
            $arr['busn_id_no']=$arrBusDtls->busns_id_no;
            $arr['busn_name']=$arrBusDtls->busn_name;
            if($id<=0){
                $arr['busn_bldg_area']=$arrBusDtls->busn_bldg_area;
                $arr['busn_bldg_total_floor_area']=$arrBusDtls->busn_bldg_total_floor_area;
                $arr['busn_employee_no_female']=$arrBusDtls->busn_employee_no_female;
                $arr['busn_employee_no_male']=$arrBusDtls->busn_employee_no_male;
                $arr['busn_employee_total_no']=$arrBusDtls->busn_employee_total_no;
                $arr['busn_employee_no_lgu']=$arrBusDtls->busn_employee_no_lgu;
                $arr['busn_vehicle_no_van_truck']=$arrBusDtls->busn_vehicle_no_van_truck;
                $arr['busn_vehicle_no_motorcycle']=$arrBusDtls->busn_vehicle_no_motorcycle;
            }
        }
        $arrBuss=$this->_BusinessPermitRetire->getLineOfBusiness($busn_id);
        $html="";
        foreach($arrBuss AS $key=>$val){
            $isExist = $this->_BusinessPermitRetire->prevPSICExist($id,$val->subclass_id);
            $checked='checked';
            if(isset($isExist)){
                $checked ='checked';
                $val->busp_capital_investment = $isExist->busnret_capital_investment;
                $val->busp_essential = $isExist->busnret_essential;
                $val->busp_non_essential = $isExist->busnret_non_essential;
            }
            $description = wordwrap($val->subclass_description, 40, "<br>\n");
            $html .='<tr>
                <td><input style="height:unset;" class="chk-box disabled-field" type="checkbox" name="subclass_id[]" value="'.$val->subclass_id.'" '.$allchecked.' '.$checked.'></td>
                <td>'.$val->subclass_code.'</td>
                <td><span class="showLess">'.$description.'</span></td>
                <td class="currency"><input class="form-control numeric" type="text" name="busnret_capital_investment_'.$val->subclass_id.'" value="'.$val->busp_capital_investment.'"><div class="currency-sign"><span>Php</span></div></td>
                <td class="currency"><input class="form-control numeric" type="text" name="busnret_essential_'.$val->subclass_id.'" value="'.$val->busp_essential.'"><div class="currency-sign"><span>Php</span></td>
                <td class="currency"><input class="form-control numeric"  type="text" name="busnret_non_essential_'.$val->subclass_id.'" value="'.$val->busp_non_essential.'"><div class="currency-sign"><span>Php</span></td>
            </tr>
            <input type="hidden" name="busnret_no_units_'.$val->subclass_id.'" value="'.$val->busp_no_units.'">';
        }
        $arrIssue = $this->_BusinessPermitRetire->checkPrevPermitIssuance($busn_id);
        $isPrevPermitIssuance=0;
        if(isset($arrIssue)){
            $isPrevPermitIssuance=1;
            $arr['issue_date']=date("M d, Y",strtotime($arrIssue->bpi_issued_date));
        }

        $arr['ESTATUS']=0;
        $arr['html']=$html;
        $arr['isPrevPermitIssuance']=$isPrevPermitIssuance;
        echo json_encode($arr);exit;
    }
    public function deleteRetirementApplication(Request $request){
        $retirement_id=$request->input('retirement_id');
        $this->_BusinessPermitRetire->deleteRetirementApplication($retirement_id);
        $arr['ESTATUS']=0;
        echo json_encode($arr);exit;
    }
    public function checkPreviousPendingAmt(Request $request){
        $busn_id=$request->input('busn_id');
        $arrPayment = DB::table('cto_bplo_final_assessment_details')->select('payment_date')->where('sub_amount','>',0)->where('payment_status',0)->where('busn_id',(int)$busn_id)->whereIn('app_code',[1,2])->orderBy('assess_year','DESC')->first();
        $arr['ESTATUS']=0;
        if(isset($arrPayment)){
            $arr['ESTATUS']=1;
            $arr['message']='Before retierement you must need to pay previous pending amount';
        }
        echo json_encode($arr);exit;
    }
}
