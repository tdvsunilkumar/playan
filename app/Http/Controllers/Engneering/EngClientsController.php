<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\Engneering\EngClients;
use App\Models\CommonModelmaster;
use App\Models\Bplo\BploClients;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use File;
use Carbon\Carbon;
class EngClientsController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrgetBrgyCode = array(""=>"Please Select");
    public $arrgetCountries = array();
    public $arrgetClients = array(""=>"Please Select");
    public function __construct(){
        $this->_engClients = new EngClients();
        $this->_commonmodel = new CommonModelmaster();
        $this->_bploClients = new BploClients();
        $this->data = array('id'=>'','rpo_custom_last_name'=>'','rpo_first_name'=>'','rpo_middle_name'=>'','suffix'=>'','p_code'=>'','rpo_address_house_lot_no'=>'','rpo_address_street_name'=>'','rpo_address_subdivision'=>'','brgy_code'=>'','p_barangay_id_no'=>'','p_telephone_no'=>'','p_mobile_no'=>'','p_fax_no'=>'','p_tin_no'=>'','p_email_address'=>'','country'=>'','gender'=>'','dateofbirth'=>'','icr_no'=>'','height'=>'','weight'=>'','civil_status'=>'','birth_place'=>'');
       
        foreach ($this->_engClients->getCountries() as $val) {
            $this->arrgetCountries[$val->id]=$val->nationality;
        }
        foreach ($this->_engClients->getClients() as $val) {
            $this->arrgetClients[$val->id]=$val->rpo_first_name." ".$val->rpo_middle_name." ".$val->rpo_custom_last_name." ".$val->suffix;
        }
        
    }

    public function index(Request $request)
    {
            return view('Engneering.engClients.index');
    }
  
  
    public function getList(Request $request){
        $data=$this->_engClients->getList($request);
        $arr=array();
        $i="0";
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $arr[$i]['no']= $j; $status ="";
            $arr[$i]['account_no']=$row->account_no;
            $checkclientisused = $this->_engClients->checkClienthasjobrequest($row->id);
            if(count($checkclientisused)<=0){
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a></div>';
            }
            $arr[$i]['rpt_owner']=$row->full_name;
            /* if($row->rpo_address_house_lot_no){
              $Address =$row->rpo_address_house_lot_no.", ".$row->rpo_address_street_name.", ".$row->rpo_address_subdivision.", ".$row->brgy_name.", ".$row->mun_desc.", ".$row->prov_desc.", ".$row->reg_region;  
              }else{
                $Address =$row->rpo_address_street_name.", ".$row->rpo_address_subdivision.", ".$row->brgy_name.", ".$row->mun_desc.", ".$row->prov_desc.",".$row->reg_region; 
              }
              if($row->rpo_address_street_name){
              $Address =$row->rpo_address_house_lot_no.", ".$row->rpo_address_street_name.", ".$row->rpo_address_subdivision.", ".$row->brgy_name.",".$row->mun_desc.",".$row->prov_desc.", ".$row->reg_region;  
              }else{
                $Address =$row->rpo_address_subdivision.", ".$row->brgy_name.", ".$row->mun_desc.", ".$row->prov_desc.", ".$row->reg_region; 
              }
              if($row->rpo_address_subdivision){
              $Address =$row->rpo_address_house_lot_no.", ".$row->rpo_address_street_name.", ".$row->rpo_address_subdivision.", ".$row->brgy_name.",".$row->mun_desc.", ".$row->prov_desc.", ".$row->reg_region;  
              }else{
                $Address =$row->brgy_name.", ".$row->mun_desc.", ".$row->prov_desc.", ".$row->reg_region; 
              } 
			  $addressnew = wordwrap($Address, 40, "<br />\n");
			  */
            
			$addressnew = wordwrap($this->_commonmodel->getTaxPayerAddress($row->id), 50, "<br />\n");
            $arr[$i]['rpo_address_house_lot_no']="<div class='showLess'>".$addressnew."</div>";
            $arr[$i]['p_mobile_no']=$row->p_mobile_no;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
           
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">

                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engclients/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Taxpayers">

                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                </div>'.$status.'';
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
        $data=array('is_engg' => $is_activeinactive);
        $checkclientisused = $this->_engClients->checkClienthasjobrequest($id);
        if(count($checkclientisused)<=0){
          $this->_engClients->updateActiveInactive($id,$data);  
        }
        
    }
    public function getProfileDetails(Request $request){
        $id= $request->input('pid');
        $data = $this->_engClients->getProfileDetails($id);
        foreach ($this->_commonmodel->getBarangay($data->p_barangay_id_no)['data'] as $val) {
            $data->p_barangay_id_no = "<option value='".$val->id."' selected>".$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region."</option>";
        }
        echo json_encode($data);
    }
    public function store(Request $request){
        //dd($request->isMethod('post'));
        $data = (object)$this->data;
        $arrgetBrgyCode = $this->arrgetBrgyCode;
        $arrgetCountries =$this->arrgetCountries;
        $arrgetClients = $this->arrgetClients;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = EngClients::find($request->input('id'));
            foreach ($this->_commonmodel->getBarangay($data->p_barangay_id_no)['data'] as $val) {
                $arrgetBrgyCode[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
            }
        }
       
        if($request->isMethod('post')){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }

            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if(($request->input('clientsregistered')>0))
            {    $updatearay = array();
                 unset($this->data['id']);
                 $this->data['is_engg'] = '1';
            	 $this->_engClients->updateData($request->input('clientsregistered'),$this->data);
                 $client_id = $request->input('clientsregistered');
            	 $success_msg = 'Taxpayers Added successfully.';
            }else {
	            if($request->input('id')>0){  //echo "<pre>"; print_r($this->data);  echo $request->input('id'); exit;
	                $this->_engClients->updateData($request->input('id'),$this->data);
                    $client_id = $request->input('id');
	                $success_msg = 'Taxpayers updated successfully.';
	            }else{
	                $this->data['created_by']=\Auth::user()->id;
	                $this->data['created_at'] = date('Y-m-d H:i:s');
	                $this->data['is_engg'] = '1';
                    $currentYear = date('Y');
                    $previousClientNo = $this->_bploClients->getMaxClientNoForYear($currentYear);
                    $clientNo = $previousClientNo + 1;
                    $this->data['client_year'] = $currentYear;
                    $this->data['client_no'] = $clientNo;
	                $client_id = $this->_engClients->addData($this->data);
	                $success_msg = 'Taxpayers added successfully.';
	            }
	        }
            $this->_commonmodel->updateTaxPayerFullAddress($client_id);
            if($request->ajax()){
                return response()->json(['status'=>'success','msg'=>$success_msg]);
            }else{
                return redirect()->route('engclients.index')->with('success', __($success_msg));
            }
            
        }
		if(!empty($data->doc_json)){
			$arrdocDtls = $this->generateDocumentList($data->doc_json,$data->id);
			if(isset($arrdocDtls)){
				$data->arrDocumentDetailsHtml = $arrdocDtls;
			}
		}else{
			$data->arrDocumentDetailsHtml ="";
		}
        return view('Engneering.engClients.create',compact('data','arrgetBrgyCode','arrgetCountries','arrgetClients'));
        
    }
    //'p_email_address' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix'
    //'p_email_address' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix' 

    public function formValidation(Request $request){
       if($request->input('clientsregistered') > 0){
            $validator = \Validator::make(
                $request->all(), [
                 'rpo_custom_last_name' => 'required|unique:clients,rpo_custom_last_name,' .$request->input('id'). ',id,rpo_first_name,' .$request->input('rpo_first_name').',rpo_middle_name,' .$request->input('rpo_middle_name').',suffix,'.$request->input('suffix'), 
				 "p_mobile_no" => "required|min:10|regex:/^[+]?[0-9\s-]*$/ ",
				 'p_email_address'=>'required|regex:/(.+)@(.+)\.(.+)/i',
                 ],[
				"rpo_custom_last_name.unique" => "The First Name, Middle Name, Last Name and Suffix has already been taken",
				"p_mobile_no.required" => "Mobile Number is required",
				"p_email_address.required" => "Email Address is required",
                "p_mobile_no.min" => "Invalid mobile number, Please try another",
				"p_mobile_no.regex" => "Invalid mobile number, Please try another",
				"p_email_address.regex" => "Invalid Email Address, Please try another",
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
        }else{
            $validator = \Validator::make(
            $request->all(),[
             'rpo_custom_last_name' => 'required|unique:clients,rpo_custom_last_name,' .$request->input('id'). ',id,rpo_first_name,' .$request->input('rpo_first_name').',rpo_middle_name,' .$request->input('rpo_middle_name').',suffix,'.$request->input('suffix'), 
			 'p_email_address'=>'required|regex:/(.+)@(.+)\.(.+)/i',
			 "p_mobile_no" => "required|min:10|regex:/^[+]?[0-9\s-]*$/ ",
             ],[
				"rpo_custom_last_name.unique" => "The First Name, Middle Name, Last Name and Suffix has already been taken",
				"p_mobile_no.required" => "Mobile Number is required",
				"p_email_address.required" => "Email Address is required",
                "p_mobile_no.min" => "Invalid mobile number, Please try another",
				"p_mobile_no.regex" => "Invalid mobile number, Please try another",
				"p_email_address.regex" => "Invalid Email Address",
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
    
    public function Delete(Request $request){
        $id = $request->input('id');
            $RptPropertyOwner = RptPropertyOwner::find($id);
            if($RptPropertyOwner->created_by == \Auth::user()->id){
                $RptPropertyOwner->delete();
            }
    }
	public function uploadDocument(Request $request){
        
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = EngClients::find($healthCertId);
        
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->doc_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/engclients/';
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
                $this->_engClients->updateData($healthCertId,$data);
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
                            <div class='action-btn ms-2'>
                                <a class='btn' href='".asset('uploads/engclients').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
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
        $arrEndrosment = EngClients::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->doc_json,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/engclients/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['doc_json'] = json_encode($arrJson);
                    $this->_engClients->updateData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }
}
