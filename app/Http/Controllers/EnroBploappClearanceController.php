<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\EnroBploAppClearance;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Illuminate\Foundation\Validation\ValidatesRequests;
class EnroBploappClearanceController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $yeararr = array(""=>"Select Year");
    public $arrbfpapplication = array(""=>"Select App");
    public $arrBfpAssess = array(""=>"Select Assessment");
    public $arrBfpInsOrder = array(""=>"Please Order");
    public function __construct(){
		$this->_bploappclearance = new EnroBploAppClearance();
        $this->_commonmodel = new CommonModelmaster(); 
        $this->data = array('id'=>'','p_code'=>'','brgy_code'=>'','ba_code'=>'','ba_business_account_no'=>'','ebac_app_code'=>'','ebac_app_year'=>'','ebac_app_no'=>'','ebac_transaction_no'=>'','ebac_environmental_fee'=>'','ebac_is_paid'=>'','ebac_issuance_date'=>'','ebac_remarks'=>'');

         foreach ($this->_bploappclearance->getbploApplications() as $val) {
            $this->arrbfpapplication[$val->id]=$val->ba_business_account_no;
        } 
    }
    public function index(Request $request)
    {   
        
       
        return view('bploappclearance.index');
        
    }
    
    public function getList(Request $request){
        $data=$this->_bploappclearance->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0";  $z="1";  
        foreach ($data['data'] as $row){
            $arr[$i]['srno']=$z;    
            $arr[$i]['p_complete_name_v1']=$row->p_complete_name_v1;
            $arr[$i]['ba_business_account_no']=$row->ba_business_account_no;
            $arr[$i]['ebac_app_code']=$row->ebac_app_code;
            $arr[$i]['ebac_app_year']=$row->ebac_app_year;
            $arr[$i]['ebac_app_no']=$row->ebac_app_no;
            $arr[$i]['ebac_transaction_no']=$row->ebac_transaction_no;
            $arr[$i]['ebac_environmental_fee']=$row->ebac_environmental_fee;
            $arr[$i]['ebac_is_paid']=$row->ebac_is_paid;
            $arr[$i]['ebac_issuance_date']=date("M d, Y",strtotime($row->ebac_issuance_date));
            $arr[$i]['ebac_remarks']=$row->ebac_remarks;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bfpapplicationform/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage BFP Application">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
               <div class="action-btn bg-info ms-2">
                        <a href="#" title="Print Env Clearance"  data-title="Print Env Clearance" class="mx-3 btn print btn-sm  align-items-center" id="'.$row->id.'">
                            <i class="ti ti-printer text-white"></i>
                        </a>
                 </div>';
              if($row->isreport=='1'){
                $arr[$i]['action'] .='<div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm printreport align-items-center" title="Print Report" data-title="Print Report" id="'.$row->id.'">
                        <i class="ti ti-printer text-white"></i>
                    </a>
                </div>';    
	            } else{
	               $arr[$i]['action'] .='<div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm report align-items-center"  data-title="Generate" id="'.$row->id.'">
                        <i class="ti-reload text-white"></i>
                    </a>
                </div>';    
	            }   
              
            $i++; $z++;
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
        $bfpapplications =$this->arrbfpapplication;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BfpOccupancyType::find($request->input('id')); 
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_bploappclearance->updateData($request->input('id'),$this->data);
                $success_msg = 'Bplo App Clearance updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
               
                $this->_bploappclearance->addData($this->data);
                $success_msg = 'Bplo App Clearance added successfully.';
            }
            return redirect()->route('bploappclearance.index')->with('success', __($success_msg));
        }
        return view('bploappclearance.create',compact('data','bfpapplications'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'brgy_code'=>'required',
                'ba_code'=>'required',
                'ebac_app_code'=>'required', 
                'ebac_app_year'=>'required', 
                'ebac_app_no'=>'required',
                'ebac_environmental_fee'=>'required',
                'ebac_issuance_date'=>'required'
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

    public function bploAppclearancePrint(Request $request){
    	    $id= $request->input('id');
            $data = $this->_bploappclearance->getAppClearance($id);
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $filename="";
            $html = file_get_contents(resource_path('views/layouts/templates/environmentclearance.html'));
            $logo = url('/assets/images/logo.png');
            $sign = url('/assets/images/signeture2.png');  
            $bgimage = url('/assets/images/clearancebackground.jpg');
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{OWNERNAME}}',$data->p_complete_name_v1, $html);
            $html = str_replace('{{BUSSINESS}}',$data->ba_business_name, $html);
            $html = str_replace('{{BARANGAYNAME}}',$data->brgy_name, $html);
            $issueddate = date("d, F Y",strtotime($data->ebac_issuance_date));
            $html = str_replace('{{DATE}}',$issueddate, $html);
            $html = str_replace('{{SIGN}}',$sign, $html);
            $html = str_replace('{{BGIMAGE}}',$bgimage, $html);
            $mpdf->WriteHTML($html);
            $filename = str_replace(' ','', $data->p_complete_name_v1);
            $applicantname = date('ymdhis').$filename."environmentclearance.pdf";
            $folder =  public_path().'/uploads/environmentclearance/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            $filename = public_path() . "/uploads/environmentclearance/" . $applicantname;
            $mpdf->Output($filename, "F");
            @chmod($filename, 0777);
            echo url('/uploads/environmentclearance/' . $applicantname);
    }
    public function EnroBploAppClearanceReport(Request $request){
    	   $id= $request->input('id'); 
    	    $clearance = $this->_bploappclearance->getAppClearancerow($id);
    	    $postadata = array();
    	    $postadata['ebac_code'] = $id; 
    	    $postadata['p_code'] = $clearance->p_code;
    	    $postadata['ba_business_account_no'] = $clearance->ba_business_account_no;
    	    $postadata['ba_code'] = $clearance->ba_code;
    	    $postadata['ebir_inspection_date'] = date('Y-m-d');
    	    $postadata['ebir_inspected_by'] = \Auth::user()->creatorId();
    	    $postadata['created_by'] =  \Auth::user()->creatorId();
    	    $postadata['updated_by'] =  \Auth::user()->creatorId();
    	    $postadata['created_at'] =  date('Y-m-d H:i:s');
    	    $postadata['updated_at'] =  date('Y-m-d H:i:s');
    	    $lastinsert = $this->_bploappclearance->addenroInspectionReportData($postadata);

    	    $updatedata =array();
    	    $updatedata['isreport'] ='1';
    	    $this->_bploappclearance->updateData($request->input('id'),$updatedata);
    	    //echo "<pre>"; print_r($clearance); exit;
            
    }
     public function bploInspectionReportPrint(Request $request){
    	    $id= $request->input('id');
            $data = $this->_bploappclearance->getAppClearance($id);
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $filename="";
            $html = file_get_contents(resource_path('views/layouts/templates/enroinspectionreport.html'));
            $logo = url('/assets/images/logo.png');
            $sign = url('/assets/images/signeture2.png');  
            $html = str_replace('{{LOGO}}',$logo, $html);
            // $html = str_replace('{{OWNERNAME}}',$data->p_complete_name_v1, $html);
            // $html = str_replace('{{BUSSINESS}}',$data->ba_business_name, $html);
            // $html = str_replace('{{BARANGAYNAME}}',$data->brgy_name, $html);
            // $issueddate = date("d, F Y",strtotime($data->ebac_issuance_date));
            // $html = str_replace('{{DATE}}',$issueddate, $html);
            // $html = str_replace('{{SIGN}}',$sign, $html);
            $mpdf->WriteHTML($html);
            $applicantname = "enroinspectionreport.pdf";
            $folder =  public_path().'/uploads/enroinspectionreport/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            $filename = public_path() . "/uploads/enroinspectionreport/" . $applicantname;
            $mpdf->Output($filename, "F");
            @chmod($filename, 0777);
            echo url('/uploads/enroinspectionreport/' . $applicantname);
    }
}
