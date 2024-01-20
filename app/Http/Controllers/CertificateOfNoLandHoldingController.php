<?php

namespace App\Http\Controllers;

use App\Models\RptPropertyCert;
use App\Models\RptPropertyOwner;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Carbon\Carbon;
use File;
class CertificateOfNoLandHoldingController extends Controller
{
    public $data = [];
     public $postdata = [];
     public $arrRequestor = array(""=>"Please Select");
     public $arrRequestorTaxpayer = array(""=>"Please Select");
     public $arrHrEmplyees = array(""=>"Please Select");
     public $arrHrEmplyeesApproved = array(""=>"Please Select");
     public $arrHrEmplyeesAppraisers = array(""=>"Please Select");
     public $arrRevisionCode = array();
     public $arrgetBrgyCode = array(""=>"Please Select");
     public $arrgetCountries = array(""=>"Please Select");
     public $arrOrnumber = array(""=>"Please Select O.R.NO.");
     private $slugs;

    public function __construct(){
    $this->_rptpropertycert = new RptPropertyCert();

        
        
  $this->data = array('id'=>'','rvy_revision_year'=>'','rpc_requestor_code'=>'','rpc_year'=>'','rpc_control_no'=>'','rpc_city_assessor_code'=>'','rpc_certified_by_code'=>'','rpc_or_no'=>'','rpc_or_date'=>'','rpc_or_amount'=>'','rpc_certified_by_position'=>'','rpc_cert_type'=>'','rpc_remarks'=>'','rpc_owner_code'=>'','cashierd_id'=>'','cashier_id'=>'','rpc_date'=>'');
  $this->dataCleint = array('id'=>'','rpo_custom_last_name'=>'','rpo_first_name'=>'','rpo_middle_name'=>'','suffix'=>'','p_code'=>'','rpo_address_house_lot_no'=>'','rpo_address_street_name'=>'','rpo_address_subdivision'=>'','brgy_code'=>'','p_barangay_id_no'=>'','p_telephone_no'=>'','p_mobile_no'=>'','p_fax_no'=>'','p_tin_no'=>'','p_email_address'=>'','country'=>'','gender'=>'','dateofbirth'=>'');

        foreach ($this->_rptpropertycert->getBarangay() as $val) {
            $this->arrgetBrgyCode[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
        }

        foreach ($this->_rptpropertycert->getCountries() as $val) {
            $this->arrgetCountries[$val->id]=$val->country_name;
        }
        
        foreach ($this->_rptpropertycert->getClientsName() as $val) {
            $this->arrRequestor[$val->id]=$val->full_name;
        }
        foreach ($this->_rptpropertycert->getClientsName() as $val) {
            $this->arrRequestorTaxpayer[$val->id]=$val->full_name;
        }
        foreach ($this->_rptpropertycert->getHrEmplyees() as $val) {
                $this->arrHrEmplyees[$val->id]=$val->fullname;
        }
        foreach ($this->_rptpropertycert->getHrEmplyeesAppraisers() as $val) {
                $this->arrHrEmplyeesApproved[$val->id]=$val->fullname;
        }
        foreach ($this->_rptpropertycert->getHrEmplyeesAppraisers() as $val) {
                $this->arrHrEmplyeesAppraisers[$val->id]=$val->fullname;
        }
        foreach ($this->_rptpropertycert->getRevisionDefault() as $val) {
            $this->arrRevisionCode[$val->rvy_revision_year]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        }
        foreach ($this->_rptpropertycert->getRevision() as $val) {
            $this->arrRevisionCode[$val->rvy_revision_year]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        }
        // foreach ($this->_rptpropertycert->getctoCashier() as $val) {
        //     $this->arrOrnumber[$val->or_no]=$val->or_no;
        // }
        // $this->slugs ='real-property/property-data/machinery';
        $this->slugs = 'certificate-of-no-land-holding';
    }
    
    public function getClientLastName(Request $request){
       
       $getgroups = $this->_rptpropertycert->getClientsFirst($request->input('firstName'));
       $htmloption ="<option value=''>Please Select Owner Name</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->full_name.'</option>';
      }
      echo $htmloption;
    }  

    public function getCertPositionDetails(Request $request){
        $id= $request->input('id');
        $data = $this->_rptpropertycert->getCertPositionDetails($id);
        echo json_encode($data);
    } 
      public function getClientNolandHolding($id=''){
            
            $arrNoHolding = $this->_rptpropertycert->getClientNolandHolding($id);
            
        return $arrNoHolding;
    } 
     public function getClientCodeAddressnolandHolding(Request $request) {
        $lastName = $request->input('lastName');
        $firstName = $request->input('firstName');
        $data = $this->_rptpropertycert->getClientCodeAddressnolandHolding($lastName, $firstName);
        // dd($data);
        $formattedData = [];

        foreach ($data as $row) {
            // Check if the object has the expected properties
            if (isset($row->id, $row->full_name, $row->standard_address, $row->p_mobile_no, $row->p_email_address)) {
                 $address = wordwrap($row->standard_address, 30, "<br />\n");
                $standard_address="<div class='showLess'>".$address."</div>";
                $record = [
                    'selectdata' => '<input type="radio" name="rpc_owner_code" value="'.$row->id.'" class="clientdata">',
                    'full_name' => $row->full_name,
                    'address' => $standard_address,
                    'phone' => $row->p_mobile_no,
                    'email' => $row->p_email_address,
                ];

                $formattedData[] = $record; // Add the record to the formatted data array
            }
        }

        return response()->json(['data' => $formattedData]);
    }
    
    public function getClientsAppraisalsNoLand(Request $request){
        $id = $request->input('id');
        $data = $this->_rptpropertycert->getClientsAppraisalsNoLand($id);
        // dd($data);exit();
        $formattedData = [];
        $sr_no=0;
        foreach ($data as $row) {
            $sr_no=$sr_no+1;
            if($row->pk_id == 1){
                $buildingAssesstvalue = $row->rpb_assessed_value;
                $formattedValue = number_format($buildingAssesstvalue, 3, '.', ',');
            }else{
                $buildingAssesstvalue = $row->rp_assessed_value;
                $formattedValue = number_format($buildingAssesstvalue, 3, '.', ',');
            }
            $marketValue = $row->rp_market_value;
            $rp_market_value = number_format($marketValue, 3, '.', ',');
            $record = [
            'srno' =>$sr_no. '<input type="hidden" name="rp_code[]"  value="'.$row->propertyId.'"><input type="hidden" name="rvy_revision_year_id" id="rvy_revision_year_id" value="'.$row->rvy_revision_year.'">',
            'full_name' => $row->full_name,
            'rp_tax_declaration_no' => $row->rp_tax_declaration_no,
            'pk_code' => $row->pk_code,
            'pc_class_code' => $row->rp_class,
            'rp_cadastral_lot_no' => $row->rp_lot_cct_unit_desc,
            'brgy_name' => $row->brgy_name,
            'rpa_base_market_value' => $row->rp_market_value,
            'rpa_assessed_value' => $formattedValue,
             ];
            $formattedData[] = $record;
        }
        return response()->json(['data' => $formattedData]);
    }

    public function getAppraisersPositionDetails(Request $request){
        $id= $request->input('id');
        $data = $this->_rptpropertycert->getAppraisersPositionDetails($id);
        echo json_encode($data);
    } 
    public function getClientsAppraisalsMechNoLand(Request $request){
        $id = $request->input('id');
        $result = $this->_rptpropertycert->getClientsAppraisalsMechNoLand($id);
        $arrClientsAppraisalsMech='';
        $i = 1; 
       foreach($result as $row)
            {
                   $arrClientsAppraisalsMech .=
                      '<tr class="ClientsAppraisalsDataMech" id="mach">
                         <td style="border: 1px solid #ccc;">'.$i.'
                          <input type="hidden" name="rp_code[]"  value="'.$row->propertyId.'">
                           <input type="hidden" name="rvy_revision_year_id" id="rvy_revision_year_id" value="'.$row->rvy_revision_year.'"></td>' .
                          '<td style="border: 1px solid #ccc;">' . $row->rpo_first_name .' '. $row->rpo_middle_name .' '. $row->rpo_custom_last_name . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->rp_tax_declaration_no . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->pk_code . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->pc_class_code . '</td>' .
                         
                         '<td style="border: 1px solid #ccc;">' . $row->lotNo . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->brgy_name . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->rpma_base_market_value . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->rpm_assessed_value . '</td>' .
                      '</tr>';

               $i++;      
            }
           return $arrClientsAppraisalsMech;
    }
     public function getClientsAppraisalsBuildingNoLand(Request $request){
        $id = $request->input('id');
        $result = $this->_rptpropertycert->getClientsAppraisalsBuildingNoLand($id);
        $arrClientsAppraisalsBuilding='';
        $i = 1;
       foreach($result as $row)
            {
                   $arrClientsAppraisalsBuilding .=
                      '<tr class="ClientsAppraisalsDataBuilding" >
                         <td style="border: 1px solid #ccc;">'.$i.'
                          <input type="hidden" name="rp_code[]"  value="'.$row->propertyId.'">
                           <input type="hidden" name="rvy_revision_year_id" id="rvy_revision_year_id" value="'.$row->rvy_revision_year.'"></td>' .
                          '<td style="border: 1px solid #ccc;">' . $row->rpo_first_name .' '. $row->rpo_middle_name .' '. $row->rpo_custom_last_name . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->rp_tax_declaration_no . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->pk_code . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->pc_class_code . '</td>' .
                         
                         '<td style="border: 1px solid #ccc;">' . $row->lotNo . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->brgy_name . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->rpbfv_floor_base_market_value . '</td>' .
                         '<td style="border: 1px solid #ccc;">' . $row->rpb_assessed_value . '</td>' .
                      '</tr>';

                   $i++;  
            }
            return $arrClientsAppraisalsBuilding;
    }

    public function addRptCertRelation($request,$rptCertId){
        $rp_code = $request->input('rp_code');
        $arr = array();
        $i=0;
         // print_r($rp_code);exit;
        foreach ($rp_code as $key => $value) {
                $arr[$i]['rpc_code']=$rptCertId;
                $arr[$i]['rp_code']=$request->input('rp_code')[$key];
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

     
    public function getClientsNameDetailsIdAllRequest(Request $request){
       $getgroups = $this->_rptpropertycert->getClientsName();
       $htmloption ="";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->rpo_first_name.'  '.$value->rpo_middle_name.' '.$value->rpo_custom_last_name.'</option>';
      }
      echo $htmloption;
    }  
    public function getClientsNameDetailsIdAll(Request $request){
        
       $getgroups = $this->_rptpropertycert->getClientsName();
       $htmloption ="";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->rpo_first_name.'  '.$value->rpo_middle_name.' '.$value->rpo_custom_last_name.'</option>';
      }
      echo $htmloption;
    }  
    public function getClientsNameDetailsId(Request $request){
       $getgroups = $this->_rptpropertycert->getClientsNameDetails($request->input('id'));
       $htmloption ="";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->full_name.'</option>';
      }
      
      echo $htmloption;
    }  
    // public function getClientsNameDetailsId(Request $request){
    //     $id= $request->input('id');
    //     $data = $this->_rptpropertycert->getClientsNameDetails($id);
    //     echo json_encode($data);
    // }
    public function hrEmployeesAjax(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_rptpropertycert->getHrEmplyeesAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->fullname;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function getClientsNameDetailsAjax(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_rptpropertycert->getClientsNameAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->full_name;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $revisionyear = $request->input('revisionyear');
        return view('rptcertificateofnolandholding.index',compact('revisionyear'));
        
    }
    public function getctoCashierIdPropertyNoLandHoldingAjax(Request $request){
        $search = $request->input('search');
        $id=$request->input('id');
        $arrRes = $this->_rptpropertycert->getctoCashierIdPropertyNoLandHoldingAjax($search,$id);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->or_no;
            $arr['data'][$key]['text']=$val->or_no;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);

    }
    public function getOrNumberDetailsold(Request $request){
       $taxpayerid=$request->input('taxpayerid');
       $id = $request->input('id');
       $getgroups = $this->_rptpropertycert->getctoCashierIdPropertyNoLandHoldingnew($id,$taxpayerid);
       $htmloption ="";
       $htmloption .="<option value=''>Please Select O.R.NO.</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->or_no.'">'.$value->or_no.'</option>';
        }
        echo $htmloption;
    }

    public function getOrNumberDetails(Request $request){
      $search = $request->input('search');
      $taxpayerid=$request->input('taxpayerid');
       $id = $request->input('id');
       $arrRes = $this->_rptpropertycert->getctoCashierIdPropertyNoLandHoldingAjax($search,$id,$taxpayerid);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->or_no;
            $arr['data'][$key]['text']=$val->or_no;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function getIssuanceDetails(Request $request){
        $id= $request->input('id');
        $data = $this->_rptpropertycert->getctoCashierIsseueanceDetails($id);
        echo json_encode($data);
    }
    public function ActiveInactive(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('status' => $is_activeinactive);
        $this->_rptpropertycert->updateActiveInactive($id,$data);
    }
    public function getList(Request $request){
        $data=$this->_rptpropertycert->getListNoHolding($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
             $status =($row->status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            $j=$j+1;
            $arr[$i]['srno']=$j;
            $arr[$i]['rpc_year']=$row->rpc_year;
            $arr[$i]['rpc_date']=$row->rpc_date;
            $arr[$i]['creatby']=$row->creatby1;
            $arr[$i]['rp_tax_declaration_no']=$row->rpc_control_no;
            $arr[$i]['ownername']=$row->full_name;
            $arr[$i]['or_no']=$row->rpc_or_no;
            $arr[$i]['requestor']=$row->requestorfull;
            $arr[$i]['assessor']=$row->assessor1;
            $arr[$i]['certby']=$row->certifiedby1;
            $rpc_remarks = wordwrap($row->rpc_remarks, 40, "<br />\n");
            $arr[$i]['remarks']="<div class='showLess'>".$rpc_remarks."</div>";
            $arr[$i]['action']='
                    <div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptcertificateofnolandholding/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Update Certificate Of No Land Holding">
                            <i class="ti-pencil text-white"></i>
                        </a>
                    </div>
                    <div class="action-btn bg-info ms-2">
                        <a href="#" title="Print Certificate Of No Land Holding"  data-title="Print Certificate Of No Land Holding" class="mx-3 btn print btn-sm" id="'.$row->id.'">
                            <i class="ti-printer text-white"></i>
                        </a>
                    </div>
                    '.$status.'
                    '
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
    public function ApproveUnapprove(Request $request){
        $id = $request->input('id');
        $is_approve = $request->input('is_approve');
        $data=array('is_approve' => $is_approve);
        $this->_rptpropertycert->updateActiveInactive($id,$data);
    }
    
    public function store(Request $request){
        
        $data = (object)$this->data;
        $arrRequestor = $this->arrRequestor;
        $arrRequestorTaxpayer = $this->arrRequestorTaxpayer;
        $arrHrEmplyees = $this->arrHrEmplyees;
        $arrHrEmplyeesAppraisers = $this->arrHrEmplyeesAppraisers;
        $arrHrEmplyeesApproved = $this->arrHrEmplyeesApproved;
        $arrRevisionCode = $this->arrRevisionCode;
        $arrNoHolding = $this->getClientNolandHolding();
        $arrOrnumber = $this->arrOrnumber;
        // $arrClientsAppraisals = $this->getClientsAppraisals($lastName,$firstName);
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptPropertyCert::find($request->input('id'));
            $arrNoHolding = $this->getClientNolandHolding($request->input('id'));
            // $this->_rptpropertycert->updateActiveInactive($id,$data);
            foreach ($this->_rptpropertycert->getClientsNameAjax($data->rpc_requestor_code)['data'] as $val) {
                $arrRequestor[$val->id]=$val->full_name;
            }
            foreach ($this->_rptpropertycert->getClientsNameAjax($data->rpc_owner_code)['data'] as $val) {
                $arrRequestorTaxpayer[$val->id]=$val->full_name;
            }
            foreach ($this->_rptpropertycert->getHrEmplyeesAjax($data->rpc_city_assessor_code)['data'] as $val) {
                $arrHrEmplyeesAppraisers[$val->id]=$val->fullname;
            }
            foreach ($this->_rptpropertycert->getHrEmplyeesAjax($data->rpc_certified_by_code)['data'] as $val) {
                $arrHrEmplyeesApproved[$val->id]=$val->fullname;
            }
            foreach ($this->_rptpropertycert->getRevisionall($data->rvy_revision_year) as $val) {
               $arrRevisionCode[$val->rvy_revision_year]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
            } 
            // foreach ($this->_rptpropertycert->getctoCashierIdPropertyNoLandHolding($data->rpc_owner_code)['data'] as $val) {
            //      $arrOrnumber[$val->or_no]=$val->or_no;
            // }
            foreach ($this->_rptpropertycert->getctoCashierIdPropertyNoLandHolding($data->rpc_owner_code) as $val) {
                $arrOrnumber[$val->or_no]=$val->or_no;
            }
        }
        
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['rpc_owner_code'] = $request->input('rpc_owner_code');
            $this->data['rpc_cert_type'] = 2;
            if($request->input('id')>0){
                $this->_rptpropertycert->updateData($request->input('id'),$this->data);
                $success_msg = 'Certificate Of No Land Holding updated successfully.';
            }else{
                
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['rpc_owner_code'] = $request->input('rpc_owner_code');
                $this->data['rpc_cert_type'] = 2;
                $rptCertId= $this->_rptpropertycert->addData($this->data);
                $accNo = str_pad($rptCertId, 6, '0', STR_PAD_LEFT);
                $arr['rpc_control_no']="09".$accNo;
                
                $this->_rptpropertycert->updateData($rptCertId,$arr);
                // $this->addRptCertRelation($request,$rptCertId);
                // echo $rptCertId;
                // exit();
                $success_msg = 'Certificate Of No Land Holding added successfully.';
            }
            

            
            return redirect('certificate-of-no-land-holding')->with('success', __($success_msg));
        }
        return view('rptcertificateofnolandholding.create',compact('data','arrOrnumber','arrRequestor','arrRevisionCode','arrHrEmplyees','arrNoHolding','arrHrEmplyeesAppraisers','arrRequestorTaxpayer','arrHrEmplyeesApproved'));
        
    }
    public function storeClient(Request $request){
        $dataCleint = (object)$this->dataCleint;
        $arrgetBrgyCode = $this->arrgetBrgyCode;
        $arrgetCountries =$this->arrgetCountries;
       
       
        if($request->input('submitClient')){
             $this->dataCleint = array('id'=>'','rpo_custom_last_name'=>'','rpo_first_name'=>'','rpo_middle_name'=>'','suffix'=>'','p_code'=>'','rpo_address_house_lot_no'=>'','rpo_address_street_name'=>'','rpo_address_subdivision'=>'','brgy_code'=>'','p_barangay_id_no'=>'','p_telephone_no'=>'','p_mobile_no'=>'','p_fax_no'=>'','p_tin_no'=>'','p_email_address'=>'','country'=>'','gender'=>'','dateofbirth'=>'');
                $this->dataCleint['rpo_custom_last_name']=$request->input('rpo_custom_last_name');
                $this->dataCleint['rpo_first_name']=$request->input('rpo_first_name');
                $this->dataCleint['rpo_middle_name']=$request->input('rpo_middle_name');
                $this->dataCleint['suffix']=$request->input('suffix');
                $this->dataCleint['p_code']=$request->input('p_code');
                $this->dataCleint['rpo_address_house_lot_no']=$request->input('rpo_address_house_lot_no');
                $this->dataCleint['rpo_address_street_name']=$request->input('rpo_address_street_name');
                $this->dataCleint['rpo_address_subdivision']=$request->input('rpo_address_subdivision');
                $this->dataCleint['brgy_code']=$request->input('brgy_code');
                $this->dataCleint['p_barangay_id_no']=$request->input('p_barangay_id_no');
                $this->dataCleint['p_telephone_no']=$request->input('p_telephone_no');
                $this->dataCleint['p_mobile_no']=$request->input('p_mobile_no');
                $this->dataCleint['p_fax_no']=$request->input('p_fax_no');
                $this->dataCleint['p_tin_no']=$request->input('p_tin_no');
                $this->dataCleint['p_email_address']=$request->input('p_email_address');
                $this->dataCleint['country']=$request->input('country');
                $this->dataCleint['gender']=$request->input('gender');
                $this->dataCleint['dateofbirth']=$request->input('dateofbirth');
                $this->dataCleint['created_by']=\Auth::user()->creatorId();
                $this->dataCleint['created_at'] = date('Y-m-d H:i:s');
                $this->dataCleint['is_rpt'] = '1';
                $this->_rptpropertycert->addDataClient($this->dataCleint);
                $success_msg = 'Property Owner added successfully.';
            
            if($request->ajax()){
                return response()->json(['status'=>'success','msg'=>$success_msg]);
            }else{
                return redirect()->route('rptcertificateofnolandholding.client')->with('success', __($success_msg));
            }
            
        }
        
        return view('rptcertificateofnolandholding.client',compact('arrgetBrgyCode','arrgetCountries'));
        
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
    

    public function CertPholdingPrintNoLand(Request $request){
            $id= $request->input('id');
            $data = $this->_rptpropertycert->CertPholdingPrintNoLanddata($id);
            // dd($data);exit;
            if(count($data)>0){
                $data = $data[0];
            }
            $arrDynamicTbl = "";
            $ownerName="";

            $bff_date="";
            $mpdf = new \Mpdf\Mpdf();
            $mpdf = new \Mpdf\Mpdf(['format' => 'A5']);
            $mpdf->watermarkImgBehind = true;
            $mpdf->showWatermarkImage = true;
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $filename="";
            $html = file_get_contents(resource_path('views/layouts/templates/CertificateOfNoLandHolding.html'));
            $logo = url('/assets/images/rptCertLog/assessor-logo.png');
            $backgroundlogo = url('/assets/images/rptCertLog/Logo.png');
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{backgroundlogo}}',$backgroundlogo, $html);
            if($data->suffix){
              $ownerName = $data->rpo_first_name.' '.$data->rpo_middle_name.' '.$data->rpo_custom_last_name.', '.$data->suffix;
            }else{
                $ownerName = $data->rpo_first_name.' '.$data->rpo_middle_name.' '.$data->rpo_custom_last_name;
            }
            if($data->requestor4){
              $requestor = $data->requestor1.' '.$data->requestor2.' '.$data->requestor3.', '.$data->requestor4;
            }else{
                $requestor = $data->requestor1.' '.$data->requestor2.' '.$data->requestor3;
            }
            if($data->assessor4){
                $assessor =$data->assessor5.' '.$data->assessor1.' '.$data->assessor2.' '.$data->assessor3.', '.$data->assessor4;
            }else{
                 $assessor =$data->assessor5.' '.$data->assessor1.' '.$data->assessor2.' '.$data->assessor3;
            }
            if($data->certifiedby4){
                $certifiedby =$data->certifiedby5.' '.$data->certifiedby1.' '.$data->certifiedby2.' '.$data->certifiedby3.', '.$data->certifiedby4;
            }else{
                 $certifiedby =$data->certifiedby5.' '.$data->certifiedby1.' '.$data->certifiedby2.' '.$data->certifiedby3;
            }
            $assessordescription = $data->assessordescription;
            $certifiedbydescription = $data->certifiedbydescription;
            $rp_tax_declaration_no = $data->rp_tax_declaration_no;
            $brgy_name = $data->brgy_name;
            $rp_total_land_area = $data->rp_total_land_area;
            $rpa_base_market_value = $data->rpa_base_market_value;
            $rpa_assessed_value = $data->rpa_assessed_value;
            $rpc_or_date2 = $data->rpc_or_date;
            $carbonDate = Carbon::parse($rpc_or_date2);
            $rpc_or_date = $carbonDate->format('m/d/Y');
            $rpc_year = $data->rpc_year;
            $rpc_date = $data->rpc_date;
            $rpc_or_no = $data->rpc_or_no;
            $rpc_or_amount = $data->rpc_or_amount;
            $id= $data->rptcertId;
            $rpc_or_dateformatted_date = date("jS", strtotime($rpc_date));
            $formatted_dateMont = date("F, Y", strtotime($rpc_date));
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
            $applicantname = "certofNolandholdingprint.pdf";
            $folder =  public_path().'/uploads/certofNolandholdingprint/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0777, true, true);
            }
            $filename = public_path() . "/uploads/certofNolandholdingprint/" . $applicantname;
            $mpdf->Output($filename, "F");
            @chmod($filename,  0777);
            echo url('/uploads/certofNolandholdingprint/' . $applicantname);
    }
   
    public function Delete(Request $request){
        $id = $request->input('id');
            $RptPropertyCert = RptPropertyCert::find($id);
            if($RptPropertyCert->created_by == \Auth::user()->creatorId()){
                $RptPropertyCert->delete();
            }
    }
}
