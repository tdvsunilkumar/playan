<?php

namespace App\Http\Controllers;

use App\Models\RptPropertyCert;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use File;
class CertificateOfRecordController extends Controller
{
    
    
     public $data = [];
     public $postdata = [];
     
     public $arrRequestor = array(""=>"Please Select");
     public $arrHrEmplyees = array(""=>"Please Select");
     public $arrRevisionCode = array();
    
     private $slugs;

    public function __construct(){
        $this->_rptpropertycert = new RptPropertyCert();
        
        
  $this->data = array('id'=>'','rvy_revision_year'=>'','rpc_requestor_code'=>'','rpc_year'=>'','rpc_control_no'=>'','rpc_city_assessor_code'=>'','rpc_certified_by_code'=>'','rpc_or_no'=>'','rpc_or_date'=>'','rpc_or_amount'=>'','rpc_certified_by_position'=>'','rpc_cert_type'=>'','rpc_remarks'=>'');
        
        
        foreach ($this->_rptpropertycert->getClientsName() as $val) {
            $this->arrRequestor[$val->id]=$val->full_name;
        }
        foreach ($this->_rptpropertycert->getHrEmplyees() as $val) {
            $this->arrHrEmplyees[$val->id]=$val->fullname;
        }
        foreach ($this->_rptpropertycert->getRevision() as $val) {
            $this->arrRevisionCode[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        }
        
        $this->slugs = 'certificate-record-listing';
    }
    
    public function getClientLastNameImprovement(Request $request){
       
       $getgroups = $this->_rptpropertycert->getClientsFirst($request->input('firstName'));
       $htmloption ="<option value=''>Please Select Owner Name</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->full_name.'</option>';
      }
      echo $htmloption;
    }  

    public function getCertPositionDetailsImprovement(Request $request){
        $id= $request->input('id');
        $data = $this->_rptpropertycert->getCertPositionDetails($id);
        echo json_encode($data);
    } 



     public function getClientCodeAddressImprovement(Request $request){
        // $lastName="Pagare"; 
        // $firstName="Pravin";
        $lastName = $request->input('lastName');
        $firstName = $request->input('firstName');
        $result = $this->_rptpropertycert->getClientCodeAddress($lastName,$firstName);
        $arrClientsDetails='';
       if(!count($result)){
                 return
                      '<tr class="ownerdetailsData">
                         <td colspan="4">Data not found</td>' .
                        
                      '</tr>';
        }
       foreach($result as $row)
            {
               $arrClientsDetails .=
                      '<tr class="ownerdetailsData" >
                      <td> <input type="radio" name="rpc_owner_code" value="'.$row->id.'" class="clientdata"></td>' .
                        '<td>' . $row->id . '</td>' .
                         '<td>' . $row->rpo_first_name .' '. $row->rpo_middle_name .' '. $row->rpo_custom_last_name . '</td>' .
                         '<td>' . $row->rpo_address_house_lot_no .' '. $row->rpo_address_street_name .' '. $row->rpo_address_subdivision . '</td>' .
                      '</tr>';

                   
                     
            }
            return $arrClientsDetails;
    }
    
    
    
    
     public function getClientsAppraisalsImprovement(Request $request){
       $id = $request->input('id');
        
        $result = $this->_rptpropertycert->getClientsAppraisalsImprovement($id);
        $arrClientsAppraisals='';
        if(!count($result)){
                 return
                      '<tr class="ClientsAppraisalsData">
                         <td colspan="9">Data not found</td>' .
                        
                      '</tr>';
        }

       foreach($result as $row)
            {
                   $arrClientsAppraisals .=
                      '<tr class="ClientsAppraisalsData" >
                         <td style="border: 1px solid #ccc;"><input type="checkbox"  name="rpa_code[]" class="myCheckbox" value="'.$row->id.'">
                          <input type="hidden" name="rp_code[]"  value="'.$row->propertyId.'"><input type="hidden" name="rvy_revision_year_id" id="rvy_revision_year_id" value="'.$row->rvy_revision_year.'"></td>' .
                          '<td style="border: 1px solid #ccc;">' . $row->rpo_first_name .' '. $row->rpo_middle_name .' '. $row->rpo_custom_last_name . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->rp_tax_declaration_no . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->pc_class_code . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->pk_code . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->rp_cadastral_lot_no . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->brgy_name . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->rpa_base_market_value . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->rpa_assessed_value . '</td>' .
                      '</tr>';

                     
            }
       

            return $arrClientsAppraisals;
    }

    public function addRptCertRelation($request,$rptCertId){
        $rp_code = $request->input('rp_code');
        $rpa_code = $request->input('rpa_code');

        $arr = array();
        $i=0;
         // print_r($rp_code);exit;
        foreach ($rp_code as $key => $value) {
                $arr[$i]['rpc_code']=$rptCertId;
                $arr[$i]['rp_code']=$request->input('rp_code')[$key];
                $arr[$i]['rpa_code']=$request->input('rpa_code')[$key];
                // $arr[$i]['updated_by'] =  \Auth::user()->creatorId();
                // $arr[$i]['updated_at'] = date('Y-m-d H:i:s');
                // // print_r($arr);exit;
                // $check= $this->_rptassessmentlevel->checkAssRequietExit($request->input('relationId')[$key]);
                 
                // if(count($check)>0){
                   
                //    $this->_rptassessmentlevel->updateAssRelationData($check[0]->id,$arr[$i]);
                // }
                // else{
                    $arr[$i]['created_by'] = \Auth::user()->creatorId();
                    $arr[$i]['created_at'] = date('Y-m-d H:i:s');
                    $this->_rptpropertycert->addRptCertRelation($arr[$i]);
                // }
                $i++;
          
        }
     }
    public function getActualdata(Request $request){
       $getgroups = $this->_rptpropertycert->getActualdata($request->input('id'));
       $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->pau_actual_use_code.' - '.$value->pau_actual_use_desc.'</option>';
      }
      echo $htmloption;
    }  
    
    
    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('rptpropertycertofrecord.index');
        
    }
    public function getList(Request $request){
        $data=$this->_rptpropertycert->getReacord($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $arr[$i]['srno']=$j;
            $arr[$i]['year']=$row->rpc_year;
            $arr[$i]['date']=$row->rpc_date;
            $arr[$i]['rp_tax_declaration_no']=$row->rpc_control_no;
            $arr[$i]['ownername']=$row->full_name;
            $arr[$i]['assessor']=$row->assessor1;
            $arr[$i]['or_no']=$row->rpc_or_no;
            $arr[$i]['kind']=$row->certifiedby1;
            $arr[$i]['requestor']=$row->requestorfull;
            $arr[$i]['rpa_base_market_value']=$row->requestorfull;
            $arr[$i]['remarks']=$row->rpc_remarks;
            if($row->rpc_cert_type==1){
                $arr[$i]['type']='Property Holding';
            }
            elseif($row->rpc_cert_type==2){
                $arr[$i]['type']='No Landholding';
            }
            elseif($row->rpc_cert_type==3){
               $arr[$i]['type']='No Improvement-Portion';
            }
            if($row->rpc_cert_type==1){
                $arr[$i]['action']='
                        <div class="action-btn bg-warning ms-2">
                            <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptpropertycertofpropertyholding/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Update Certificate of Property Holdings">
                                <i class="ti-pencil text-white"></i>
                            </a>
                        </div>
                        <div class="action-btn bg-info ms-2">
                            <a href="#" title="Print Certificate Of No Improvement"  data-title="Print Certificate of Property Holdings" class="mx-3 btn print btn-sm" id="'.$row->id.'">
                                <i class="ti-printer text-white"></i>
                            </a>
                        </div>';
            }
            elseif($row->rpc_cert_type==2){
                $arr[$i]['action']='
                        <div class="action-btn bg-warning ms-2">
                            <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptcertificateofnolandholding/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Update Certificate Of No Land Holding">
                                <i class="ti-pencil text-white"></i>
                            </a>
                        </div>
                        <div class="action-btn bg-info ms-2">
                            <a href="#" title="Print Certificate Of No Improvement"  data-title="Print Certificate Of No Land Holding" class="mx-3 btn printNoLand btn-sm" id="'.$row->id.'">
                                <i class="ti-printer text-white"></i>
                            </a>
                        </div>';
            }
            
            elseif($row->rpc_cert_type==3){
                $arr[$i]['action']='
                        <div class="action-btn bg-warning ms-2">
                            <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptcertificateofnoImprovement/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Update Certificate Of No Improvement">
                                <i class="ti-pencil text-white"></i>
                            </a>
                        </div>
                        <div class="action-btn bg-info ms-2">
                            <a href="#" title="Print Certificate Of No Improvement"  data-title="Print Certificate Of No Improvement" class="mx-3 btn printImprovemnt btn-sm" id="'.$row->id.'">
                                <i class="ti-printer text-white"></i>
                            </a>
                        </div>';
            }

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
        $data=array('lav_strip_is_active' => $is_activeinactive);
        $this->_rptpropertycert->updateActiveInactive($id,$data);
    }


    public function ApproveUnapprove(Request $request){
        $id = $request->input('id');
        $is_approve = $request->input('is_approve');
        $data=array('is_approve' => $is_approve);
        $this->_rptpropertycert->updateActiveInactive($id,$data);
    }
    public function getClientNolandHolding($id=''){
            
            $arrNoHolding = $this->_rptpropertycert->getClientNolandHolding($id);
            
        return $arrNoHolding;
    } 
    public function getClientsApp($id=''){
            
            $arrAppHolding = $this->_rptpropertycert->getClientsAppraisalsDetials($id);
            
        return $arrAppHolding;
    } 
    public function store(Request $request){
        
        $data = (object)$this->data;
        $arrRequestor = $this->arrRequestor;
        $arrHrEmplyees = $this->arrHrEmplyees;
        $arrRevisionCode = $this->arrRevisionCode;
        $arrNoHolding = $this->getClientNolandHolding();
        $arrAppHolding = $this->getClientsApp();
        // $arrClientsAppraisals = $this->getClientsAppraisals($lastName,$firstName);
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptPropertyCert::find($request->input('id'));
            $arrNoHolding = $this->getClientNolandHolding($request->input('id'));
            $arrAppHolding = $this->getClientsApp($request->input('id'));
            
            foreach ($this->_rptpropertycert->getRevisionall($data->rvy_revision_year) as $val) {
            $arrRevisionCode[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        } 
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['rpc_cert_type'] = 3;
            if($request->input('id')>0){
                $this->_rptpropertycert->updateData($request->input('id'),$this->data);
                $success_msg = 'Certificate Of No Improvement updated successfully.';
            }else{
                
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['rpc_owner_code'] = $request->input('rpc_owner_code');
                $this->data['rpc_cert_type'] = 3;
                $rptCertId= $this->_rptpropertycert->addData($this->data);
                $accNo = str_pad($rptCertId, 6, '0', STR_PAD_LEFT);
                $arr['rpc_control_no']="09".$accNo;
                
                $this->_rptpropertycert->updateData($rptCertId,$arr);
                $this->addRptCertRelation($request,$rptCertId);
                // echo $rptCertId;
                // exit();
                $success_msg = 'Certificate Of No Improvement added successfully.';
            }
            

            
            return redirect()->route('rptpropertycertofrecord.index')->with('success', __($success_msg));
        }
        return view('rptpropertycertofrecord.create',compact('data','arrRequestor','arrRevisionCode','arrHrEmplyees','arrNoHolding','arrAppHolding'));
        
    }
    

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
               // 'rvy_revision_year' => 'required|unique:rpt_land_unit_values,rvy_revision_year,' .$request->input('id'). ',id,loc_group_brgy_no,' .$request->input('loc_group_brgy_no').',pc_class_code,' .$request->input('pc_class_code').',ps_subclass_code,' .$request->input('ps_subclass_code').',pau_actual_use_code,' .$request->input('pau_actual_use_code'),
                
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


    public function CertPholdingPrintImprovement(Request $request){
            $id= $request->input('id');
            $data = $this->_rptpropertycert->CertPholdingPrint($id);
             
            if(count($data)>0){
                $data = $data[0];

            }
            $arrDynamicTbl = "";
            $ownerName="";

            $bff_date="";
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $filename="";
            $html = file_get_contents(resource_path('views/layouts/templates/certofnoimprovement.html'));
            $logo = url('/assets/images/logo.png');
            $signeture = url('/assets/images/signeture2.png');
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{SIGNETURE}}',$signeture, $html);
            $ownerName = $data->rpo_first_name.' '.$data->rpo_middle_name.' '.$data->rpo_custom_last_name;
            $requestor = $data->requestor1.' '.$data->requestor2.' '.$data->requestor3;
            $rp_cadastral_lot_no=$data->rp_cadastral_lot_no;
            $assessor = $data->assessor1.' '.$data->assessor2.' '.$data->assessor3;
            $assessordescription = $data->assessordescription;
            $certifiedby = $data->certifiedby1.' '.$data->certifiedby2.' '.$data->certifiedby3;
            $certifiedbydescription = $data->certifiedbydescription;
            $rp_tax_declaration_no = $data->rp_tax_declaration_no;
            $brgy_name = $data->brgy_name;
            $rp_total_land_area = $data->rp_total_land_area;
            $rpa_base_market_value = $data->rpa_base_market_value;
            $rpa_assessed_value = $data->rpa_assessed_value;
            $rpc_or_date = $data->rpc_or_date;
            $rpc_or_no = $data->rpc_or_no;
            $rpc_or_amount = $data->rpc_or_amount;
            $id= $data->rptcertId;
            
       
            $rpc_or_dateformatted_date = date("jS", strtotime($rpc_or_date));
            $formatted_dateMont = date("F, Y", strtotime($rpc_or_date));
            $html = str_replace('{{rp_cadastral_lot_no}}',$rp_cadastral_lot_no, $html);
            $html = str_replace('{{ownerName}}',strtoupper($ownerName), $html);
            $html = str_replace('{{requestor}}',strtoupper($requestor), $html);
            $html = str_replace('{{assessor}}',strtoupper($assessor), $html);
            $html = str_replace('{{assessordescription}}',$assessordescription, $html);
            $html = str_replace('{{certifiedby}}',strtoupper($certifiedby), $html);
            $html = str_replace('{{certifiedbydescription}}',$certifiedbydescription, $html);
            $html = str_replace('{{rp_tax_declaration_no}}',$rp_tax_declaration_no, $html);
            $html = str_replace('{{brgy_name}}',$brgy_name, $html);
            $html = str_replace('{{rp_total_land_area}}',$rp_total_land_area, $html);
            $html = str_replace('{{rpa_base_market_value}}',$brgy_name, $html);
            $html = str_replace('{{rpa_assessed_value}}',$rp_total_land_area, $html);
            $html = str_replace('{{rpc_or_dateformatted_date}}',$rpc_or_dateformatted_date, $html);
            $html = str_replace('{{formatted_dateMont}}',$formatted_dateMont, $html);
            $html = str_replace('{{rpc_or_no}}',$rpc_or_no, $html);
            $html = str_replace('{{rpc_or_date}}',$rpc_or_date, $html);
            $html = str_replace('{{rpc_or_amount}}',number_format($rpc_or_amount, 2, '.', ','), $html);

            
            $html = str_replace('{{DynamicTbl}}',$arrDynamicTbl, $html);
            
            $mpdf->WriteHTML($html);
            $applicantname = "certoflandholdingprint.pdf";
            $folder =  public_path().'/uploads/certoflandholdingprint/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0777, true, true);
            }
            $filename = public_path() . "/uploads/certoflandholdingprint/" . $applicantname;
            $mpdf->Output($filename, "F");
             @chmod($filename,  0777);
            echo url('/uploads/certoflandholdingprint/' . $applicantname);
    }
   
    public function Delete(Request $request){
        $id = $request->input('id');
            $RptPropertyCert = RptPropertyCert::find($id);
            if($RptPropertyCert->created_by == \Auth::user()->creatorId()){
                $RptPropertyCert->delete();
            }
    }
}
