<?php

namespace App\Http\Controllers;

use App\Models\RptPropertyCert;
use App\Models\RptPropertyCertDetails;
use App\Models\RptProperty;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Carbon\Carbon;
use File;
use App\Helpers\Helper;
class CertificateOfPropertyHoldingsController extends Controller
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
     public $arrOrnumber = array(""=>"Please Select");
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
        foreach ($this->_rptpropertycert->getHrEmplyees() as $val) {
            $this->arrHrEmplyees[$val->id]=$val->fullname;
        }
        foreach ($this->_rptpropertycert->getHrEmplyeesAppraisers() as $val) {
                $this->arrHrEmplyeesApproved[$val->id]=$val->fullname;
        }
        foreach ($this->_rptpropertycert->getHrEmplyeesAppraisers() as $val) {
                $this->arrHrEmplyeesAppraisers[$val->id]=$val->fullname;
        }
        foreach ($this->_rptpropertycert->getRevision() as $val) {
            $this->arrRevisionCode[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        }
        
        $this->slugs = 'certificate-of-property-holding';
    }
    public function getRevisionall(Request $request){
       $getgroups = $this->_rptpropertycert->getRevisionall($request->input('rvy_revision_year_id'));
       $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->rvy_revision_year.'-'.$value->rvy_revision_code.'</option>';
      }
      echo $htmloption;
    }  
    public function getClientLastName(Request $request){
       
       $getgroups = $this->_rptpropertycert->getClientsFirst($request->input('firstName'));
       $htmloption ="<option value=''>Please Select Owner Name</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->full_name.'</option>';
      }
      echo $htmloption;
    } 

    public function getOrNumberspropertyholdingAjax(Request $request){
        $search = $request->input('search');
           $id = $request->input('id');
           $arrRes = $this->_rptpropertycert->getctoCashierIdPropertyholdingAjax($search,$id);
            $arr = array();
            foreach ($arrRes['data'] as $key=>$val) {
                $arr['data'][$key]['id']=$val->or_no;
                $arr['data'][$key]['text']=$val->or_no;
            }
            $arr['data_cnt']=$arrRes['data_cnt'];
            echo json_encode($arr);
    } 

    public function getCertPositionDetails(Request $request){
        $id= $request->input('id');
        $data = $this->_rptpropertycert->getCertPositionDetails($id);
        echo json_encode($data);
    }
    public function getClientCodeAddressPropertyLand(Request $request){
       $lastNamePropertyLand = $request->input('lastName');
       $result = $this->_rptpropertycert->getClientCodeAddressPropertyLand($lastNamePropertyLand);
       // dd($result);exit;
       $formattedData = [];
       foreach ($result as $row) {
            if (isset($row->id, $row->full_name, $row->standard_address, $row->p_mobile_no, $row->p_email_address)) {
                 $address = wordwrap($row->standard_address, 100, "<br />\n");
                $standard_address="<div class='showLess'>".$address."</div>";
                $record = [
                    'selectdata' => '<input type="radio" name="rpc_owner_code" value="'.$row->id.'" class="clientdata">',
                    // 'selectdata' => '<input type="checkbox" name="rpc_owner_code" value="'.$row->id.'" class="clientdata">',
                    'full_name' => $row->full_name,
                    'address' => $standard_address,
                    'phone' => $row->p_mobile_no,
                    'email' => $row->p_email_address,
                ];
                $formattedData[] = $record;
            }
        }
        return response()->json(['data' => $formattedData]);
    }
    public function getClientsAppraisals(Request $request) {
		$id = $request->input('id');
		$rpc_code = $request->input('rpc_code');
		$data = $this->_rptpropertycert->getClientsAppraisals($id);
		$formattedData = [];
		$sr_no = 0;

		foreach ($data as $row) {
			$sr_no = $sr_no + 1;
			$record = [
				'srno' => '',
				'full_name' => $row->full_name,
				'rp_tax_declaration_no' => $row->rp_tax_declaration_no,
				'pk_code' => $row->pk_code,
				'pc_class_code' => $row->rp_class,
				'rp_cadastral_lot_no' => $row->rp_lot_cct_unit_desc,
				'brgy_name' => $row->brgy_name,
				'rpa_base_market_value' => number_format($row->rp_market_value, 2, '.', ','),
				'rpa_assessed_value' => ($row->pk_id == 1) ? number_format($row->rpb_assessed_value, 2, '.', ',') : number_format($row->rp_assessed_value, 2, '.', ','),
				/* 'action' => '<div class="action-btn bg-danger ms-2">
								<a href="#" class="mx-2 btn btn-sm deleterow ti-trash text-white text-white" id="'.$row->propertyId.'">
								   </a>
							 </div>', */
			];
			$arrAppHoldings = $this->_rptpropertycert->getClientsAppraisalsExit($rpc_code);
			$isChecked = false;
			$arrAppHoldingId = null;
			foreach ($arrAppHoldings as $arrAppHolding) {
				if ($arrAppHolding->id == $row->propertyId) {
					$isChecked = true;
					$arrAppHoldingId = $arrAppHolding->rptcertDId; 
					break;
				}
			}
			$record['srno'] = '<input type="checkbox" name="rp_code[]" class="myCheckbox" value="' . $row->propertyId . '" id="'.$arrAppHoldingId.'"';
			
			if ($isChecked) {
				$record['srno'] .= ' checked';
				// $record['action'] = '<button type="button" class="mx-2 btn btn-sm deleterow ti-trash text-white" style="background: #ff3a6e;" id="'.$arrAppHoldingId.'" onclick="handleButtonClick(this)">
				// </button>';

			}
			$record['srno'] .= '><input type="hidden" name="rvy_revision_year_id" id="rvy_revision_year_id" value="' . $row->rvy_revision_year . '">';

			$formattedData[] = $record;
		}

		return response()->json(['data' => $formattedData]);
}


    
    // public function getClientsAppraisalsBuilding(Request $request){
       
       
    //     $id = $request->input('id');
        
    //     $result = $this->_rptpropertycert->getClientsAppraisalsBuilding($id);
    //     $arrClientsAppraisalsBuilding='';
    //     // if(!count($result)){
    //     //          return
    //     //               '<tr class="ClientsAppraisalsDataBuilding">
    //     //                  <td colspan="9">Data not found</td>' .
                        
    //     //               '</tr>';
    //     // }

    //    foreach($result as $row)
    //         {
    //                $arrClientsAppraisalsBuilding .=
    //                   '<tr class="ClientsAppraisalsDataBuilding" id="mach">
    //                      <td style="border: 1px solid #ccc;"><input type="checkbox"  name="rpab_code[]" class="myCheckbox" value="'.$row->id.'">
    //                       <input type="hidden" name="rp_code[]"  value="'.$row->propertyId.'">
    //                        <input type="hidden" name="rvy_revision_year_id" id="rvy_revision_year_id" value="'.$row->rvy_revision_year.'"></td>' .
    //                       '<td style="border: 1px solid #ccc;text-align:left;">' . $row->rpo_first_name .' '. $row->rpo_middle_name .' '. $row->rpo_custom_last_name . '</td>' .
    //                      '<td style="border: 1px solid #ccc;text-align:left;">' . $row->rp_tax_declaration_no . '</td>' .
    //                      '<td style="border: 1px solid #ccc;text-align:left;">' . $row->pk_code . '</td>' .
    //                      '<td style="border: 1px solid #ccc;text-align:left;">' . $row->pc_class_code . '</td>' .
                         
    //                      '<td style="border: 1px solid #ccc;text-align:left;">' . $row->lotNo . '</td>' .
    //                      '<td style="border: 1px solid #ccc;text-align:left;">' . $row->brgy_name . '</td>' .
    //                      '<td style="border: 1px solid #ccc;text-align:left;">' . $row->rpbfv_floor_base_market_value . '</td>' .
    //                      '<td style="border: 1px solid #ccc;text-align:left;">' . $row->rpb_assessed_value . '</td>' .
    //                   '</tr>';

                     
    //         }
       

    //         return $arrClientsAppraisalsBuilding;
    // }
    
    //  public function getClientsAppraisalsMech(Request $request){
       
       
    //     $id = $request->input('id');
        
    //     $result = $this->_rptpropertycert->getClientsAppraisalsMech($id);
    //     $arrClientsAppraisalsMech='';

    //     // if(!count($result)){
    //     //          return
    //     //               '<tr class="ClientsAppraisalsDataMech">
    //     //                  <td colspan="9">Data not found</td>' .
                        
    //     //               '</tr>';
    //     // }


    //    foreach($result as $row)
    //         {
    //                $arrClientsAppraisalsMech .=
    //                   '<tr class="ClientsAppraisalsDataMech" >
    //                      <td style="border: 1px solid #ccc;"><input type="checkbox"  name="rpam_code[]" class="myCheckbox" value="'.$row->id.'">
    //                       <input type="hidden" name="rp_code[]"  value="'.$row->propertyId.'">
    //                        <input type="hidden" name="rvy_revision_year_id" id="rvy_revision_year_id" value="'.$row->rvy_revision_year.'"></td>' .
    //                       '<td style="border: 1px solid #ccc;text-align:left;">' . $row->rpo_first_name .' '. $row->rpo_middle_name .' '. $row->rpo_custom_last_name . '</td>' .
    //                      '<td style="border: 1px solid #ccc;text-align:left;">' . $row->rp_tax_declaration_no . '</td>' .
    //                      '<td style="border: 1px solid #ccc;text-align:left;">' . $row->pk_code . '</td>' .
    //                      '<td style="border: 1px solid #ccc;text-align:left;">' . $row->pc_class_code . '</td>' .
                         
    //                      '<td style="border: 1px solid #ccc;text-align:left;">' . $row->lotNo . '</td>' .
    //                      '<td style="border: 1px solid #ccc;text-align:left;">' . $row->brgy_name . '</td>' .
    //                      '<td style="border: 1px solid #ccc;text-align:left;">' . $row->rpma_base_market_value . '</td>' .
    //                      '<td style="border: 1px solid #ccc;text-align:left;">' . $row->rpm_assessed_value . '</td>' .
    //                   '</tr>';

                     
    //         }
       

    //         return $arrClientsAppraisalsMech;
    // }
     
    public function addRptCertRelation($request, $rptCertId) {
    $rp_code = $request->input('rp_code');
    
    if ($rp_code) {
        $i = 0;
        $deletedIds = []; // Store the IDs of records to be deleted

        foreach ($rp_code as $key => $value) {
            $arr[$i]['rpc_code'] = $rptCertId;
            $arr[$i]['rp_code'] = $request->input('rp_code')[$key];
            $arr[$i]['rpa_code'] = 0;
            $arr[$i]['rpam_code'] = 0;
            $arr[$i]['rpab_code'] = 0;
            $arr[$i]['updated_by'] = \Auth::user()->creatorId();
            $arr[$i]['updated_at'] = date('Y-m-d H:i:s');

            $check = $this->_rptpropertycert->checkAddAppraisalsExit($arr[$i]);

            if (count($check) > 0) {
                $this->_rptpropertycert->updatePropertyCertDetails($check[0]->id, $arr[$i]);
            } else {
                $arr[$i]['created_by'] = \Auth::user()->creatorId();
                $arr[$i]['created_at'] = date('Y-m-d H:i:s');
                $this->_rptpropertycert->addRptCertRelation($arr[$i]);
            }

            $deletedIds[] = $arr[$i]['rp_code']; // Store the IDs of records to be deleted
            $i++;
        }

        // Delete records that are not in the $deletedIds array
        RptPropertyCertDetails::where('rpc_code', $rptCertId)
            ->whereNotIn('rp_code', $deletedIds)
            ->delete();
    }
}

     public function addRptCertRelationMech($request,$rptCertId){
         $rp_code = $request->input('rp_code');

         // $rpam_code = $request->input('rpam_code');
         if($rp_code){
        $arr = array();
        $i=0;
         // print_r($rp_code);exit;
        foreach ($rp_code as $key => $value) {
                $arr[$i]['rpc_code']=$rptCertId;
                $arr[$i]['rp_code']=$request->input('rp_code')[$key];
                $arr[$i]['rpa_code']=0;
                $arr[$i]['rpab_code']=0;
                $arr[$i]['rpam_code']=0;
                $arr[$i]['updated_by'] =  \Auth::user()->creatorId();
                $arr[$i]['updated_at'] = date('Y-m-d H:i:s');
                // print_r($arr);exit;
                
                    $arr[$i]['created_by'] = \Auth::user()->creatorId();
                    $arr[$i]['created_at'] = date('Y-m-d H:i:s');
                    $this->_rptpropertycert->addRptCertRelation($arr[$i]);
                // }
                $i++;
          }
        
     }
 }
 public function addRptCertRelationBuilding($request,$rptCertId){
         $rp_code = $request->input('rp_code');
         // $rpab_code = $request->input('rpab_code');
         if($rp_code){
         
        $arr = array();
        $i=0;
         // print_r($rp_code);exit;
        foreach ($rp_code as $key => $value) {
                $arr[$i]['rpc_code']=$rptCertId;
                $arr[$i]['rp_code']=$request->input('rp_code')[$key];
                $arr[$i]['rpa_code']=0;
                $arr[$i]['rpam_code']=0;
                $arr[$i]['rpab_code']=0;
                $arr[$i]['updated_by'] =  \Auth::user()->creatorId();
                $arr[$i]['updated_at'] = date('Y-m-d H:i:s');
                // print_r($arr);exit;
                
                    $arr[$i]['created_by'] = \Auth::user()->creatorId();
                    $arr[$i]['created_at'] = date('Y-m-d H:i:s');
                    $this->_rptpropertycert->addRptCertRelation($arr[$i]);
                // }
                $i++;
          }
        
     }
 }
    
    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $revisionyear = $request->input('revisionyear');
        return view('rptpropertycertofpropertyholding.index',compact('revisionyear'));
    }
    
    public function getList(Request $request){
        $data=$this->_rptpropertycert->getList($request);
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
            $arr[$i]['ownername']=$row->full_name;
            $arr[$i]['rpc_date']=$row->rpc_date;
            $arr[$i]['creatby']=$row->creatby1;
            $arr[$i]['rp_tax_declaration_no']=$row->rpc_control_no;
            $arr[$i]['or_no']=$row->rpc_or_no;
            $arr[$i]['requestor']=$row->requestorfull;
            $arr[$i]['assessor']=$row->assessor1;
            $arr[$i]['certby']=$row->certifiedby1;
            $rpc_remarks = wordwrap($row->rpc_remarks, 40, "<br />\n");
            $arr[$i]['remarks']="<div class='showLess'>".$rpc_remarks."</div>";
            $arr[$i]['action']='
                    <div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptpropertycertofpropertyholding/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Update Certificate of Property Holdings">
                            <i class="ti-pencil text-white"></i>
                        </a>
                    </div>
                    <div class="action-btn bg-info ms-2">
                        <a href="#" title="Print Certificate of Property Holdings"  data-title="Print Certificate of Property Holdings" class="mx-3 btn print btn-sm" id="'.$row->id.'">
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
    public function getClientsAppMech($id=''){
            
            $arrAppHoldingMech = $this->_rptpropertycert->getClientsAppraisalsDetialsMech($id);
            
        return $arrAppHoldingMech;
    }
    public function getClientsAppBuilding($id=''){
            
            $arrAppHoldingBuilding = $this->_rptpropertycert->getClientsAppraisalsDetialsBuilding($id);
            
        return $arrAppHoldingBuilding;
    }
    public function getctoCashierDetailsPropertyHolding(Request $request){
       $id=$request->input('id');
       $getgroups = $this->_rptpropertycert->getctoCashierDetailsPropertyHolding($id);
       $htmloption ="";
       $htmloption .="<option value=''>Please Select O.R.NO.</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->or_no.'">'.$value->or_no.'</option>';
        }
        echo $htmloption;
    }
    public function store(Request $request){
        
        $data = (object)$this->data;
        $arrRequestor = $this->arrRequestor;
        $arrRequestorTaxpayer = $this->arrRequestorTaxpayer;
        $arrHrEmplyees = $this->arrHrEmplyees;
        $arrHrEmplyeesAppraisers = $this->arrHrEmplyeesAppraisers;
        $arrHrEmplyeesApproved = $this->arrHrEmplyeesApproved;
        $arrRevisionCode = $this->arrRevisionCode;
        $arrOrnumber = $this->arrOrnumber;
        $arrNoHolding = $this->getClientNolandHolding();
        $arrAppHolding = $this->getClientsApp();
        $arrAppHoldingMech = $this->getClientsAppMech();
        $arrAppHoldingBuilding = $this->getClientsAppBuilding();
        // $arrClientsAppraisals = $this->getClientsAppraisals($lastName,$firstName);
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptPropertyCert::find($request->input('id'));
            $arrNoHolding = $this->getClientNolandHolding($request->input('id'));
            $arrAppHolding = $this->getClientsApp($request->input('id'));
            $arrAppHoldingMech = $this->getClientsAppMech($request->input('id'));
            $arrAppHoldingBuilding = $this->getClientsAppBuilding($request->input('id'));
            
            foreach ($this->_rptpropertycert->getRevisionall($data->rvy_revision_year) as $val) {
                $arrRevisionCode[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
            }
            foreach ($this->_rptpropertycert->getctoCashierDetailsPropertyHolding($data->rpc_owner_code) as $val) {
                $arrOrnumber[$val->or_no]=$val->or_no;
            } 
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
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }

            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['rpc_owner_code'] = $request->input('rpc_owner_code');
            $this->data['rpc_cert_type'] = 1;
            if($request->input('id')>0){
                $this->_rptpropertycert->updateData($request->input('id'),$this->data);
                $rptCertId=$request->input('id');
                // $this->addRptCertRelation($request,$rptCertId);
                $success_msg = 'Certificate of Property Holdings updated successfully.';
            }else{
                
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['rpc_owner_code'] = $request->input('rpc_owner_code');
                $this->data['rpc_cert_type'] = 1;
                $rptCertId= $this->_rptpropertycert->addData($this->data);
                $accNo = str_pad($rptCertId, 6, '0', STR_PAD_LEFT);
                $arr['rpc_control_no']="09".$accNo;
                
                $this->_rptpropertycert->updateData($rptCertId,$arr);
                
                // echo $rptCertId;
                // exit();
                $success_msg = 'Certificate of Property Holdings added successfully.';
            }
            if($rptCertId>0){
               $this->addRptCertRelation($request,$rptCertId);
               // $this->addRptCertRelationMech($request,$rptCertId);
               // $this->addRptCertRelationBuilding($request,$rptCertId);
            }
            

            
            return redirect('certificate-of-property-holding')->with('success', __($success_msg));
        }
        return view('rptpropertycertofpropertyholding.create',compact('data','arrRequestor','arrRevisionCode','arrHrEmplyees','arrNoHolding','arrAppHolding','arrAppHoldingMech','arrAppHoldingBuilding','arrOrnumber','arrHrEmplyeesAppraisers','arrRequestorTaxpayer','arrHrEmplyeesApproved'));
        
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
                return redirect()->route('rptpropertycertofpropertyholding.store')->with('success', __($success_msg));
            }
            
        }
        
        return view('rptpropertycertofpropertyholding.client',compact('arrgetBrgyCode','arrgetCountries'));
        
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


    public function CertPholdingPrint(Request $request){
            $id= $request->input('id');
            $data = $this->_rptpropertycert->CertPholdingPrint($id);
             
            if(count($data)>0){
                $data = $data[0];

            }
            $arrDynamicTbl = "";
            $arrDynamicTblMech = "";
            $arrDynamicTblBuild = "";
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
            $html = file_get_contents(resource_path('views/layouts/templates/CertificateOfLandHoldings.html'));
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
            $rpa_base_market_value = $data->rp_market_value;
            if($data->pk_id == 1){
                $rpa_assessed_value = $data->rpb_assessed_value;
            }
            else{
                $rpa_assessed_value = $data->rp_assessed_value;
            }
            $rpc_or_date2 = $data->rpc_or_date;
            $carbonDate = Carbon::parse($rpc_or_date2);
            $rpc_or_date = $carbonDate->format('m/d/Y');
            $rpc_or_no = $data->rpc_or_no;
            $rpc_or_amount = $data->rpc_or_amount;
            $id= $data->rptcertId;
            $result = $this->_rptpropertycert->getClientsAppraisalsDetials($id);
            $arrDynamicTbl = '';
            //dd($result);
            foreach ($result as $row) {
                if ($row->pk_id == 1) {
                    $buildingAssesstvalue = $row->rpb_assessed_value;
                    $formattedValue = number_format($buildingAssesstvalue, 3, '.', ',');
                } else {
                    $buildingAssesstvalue = $row->rp_assessed_value;
                    $formattedValue = number_format($buildingAssesstvalue, 3, '.', ',');
                }
                $marketValue = $row->rp_market_value_adjustment;
                $rp_market_value = number_format($marketValue, 3, '.', ',');

                // Create a new table row for each row of data
                if($row->pk_code == 'B'){
                    $totalArea = number_format($row->area,3, '.', ',').' Sq.m.';
                }else if($row->pk_code == 'L'){
                    $totalArea = number_format($row->area,4, '.', ',').' Hectare';
                }else{
                     $totalArea = '';
                }
                $arrDynamicTbl .=
                    '<tr class="ClientsAppraisalsData" id="land">
                        <td style="border: 1px solid #000;text-align:center;">' . $row->rp_tax_declaration_no . '</td>' .
                        '<td style="border: 1px solid #000;text-align:center;">' . $row->full_name . '</td>' .
                        '<td style="border: 1px solid #000;text-align:center;">' . $row->brgy_name . '</td>' .
                        '<td style="border: 1px solid #000;text-align:center;">' . $totalArea .'</td>' .
                        '<td style="border: 1px solid #000;text-align:center;">' . $rp_market_value . '</td>' .
                        '<td style="border: 1px solid #000;text-align:center;">' . $formattedValue . '</td>' .
                    '</tr>';
            }
            // $resultmech = $this->_rptpropertycert->getClientsAppraisalsDetialsMech($id);
            // foreach($resultmech as $row)
            // {
            //        $arrDynamicTblMech .=
            //           '<tr class="ClientsAppraisalsDataMech" id="mach">
            //              <td style="border: 1px solid #000;text-align:center;">'.$row->rp_tax_declaration_no.'</td>' .
            //               '<td style="border: 1px solid #000;text-align:center;">' . $row->rpo_first_name .' '. $row->rpo_middle_name .' '. $row->rpo_custom_last_name . '</td>' .
            //              '<td style="border: 1px solid #000;text-align:center;">' . $row->brgy_name . '</td>' .
            //              '<td style="border: 1px solid #000;text-align:center;">' . $row->rp_total_land_area . '</td>' .
            //              '<td style="border: 1px solid #000;text-align:center;">' . $row->rpma_base_market_value . '</td>' .
            //              '<td style="border: 1px solid #000;text-align:center;">' . $row->rpm_assessed_value . '</td>' .
                        
            //           '</tr>';
            // }
            // $resultBuild = $this->_rptpropertycert->getClientsAppraisalsDetialsBuilding($id);
            // foreach($resultBuild as $row)
            // {
            //        $arrDynamicTblBuild .=
            //           '<tr class="ClientsAppraisalsDataBuilding" >
            //              <td style="border: 1px solid #000;text-align:center;">'.$row->rp_tax_declaration_no.'</td>' .
            //               '<td style="border: 1px solid #000;text-align:center;">' . $row->rpo_first_name .' '. $row->rpo_middle_name .' '. $row->rpo_custom_last_name . '</td>' .
            //              '<td style="border: 1px solid #000;text-align:center;">' . $row->brgy_name . '</td>' .
            //              '<td style="border: 1px solid #000;text-align:center;">' . $row->rp_total_land_area . '</td>' .
            //              '<td style="border: 1px solid #ccc;text-align:center;">' . $row->rpbfv_floor_base_market_value . '</td>' .
            //              '<td style="border: 1px solid #000;text-align:center;">' . $row->rpb_assessed_value . '</td>' .
                        
            //           '</tr>';
            // }
            $rpc_year = $data->rpc_date;
            $rpc_or_dateformatted_date = date("jS", strtotime($rpc_year));
            $formatted_dateMont = date("F, Y", strtotime($rpc_year));
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
            $html = str_replace('{{DynamicTblMech}}',$arrDynamicTblMech, $html);
            $html = str_replace('{{DynamicTblBuild}}',$arrDynamicTblBuild, $html);
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
            $RptPropertyCertDetails = RptPropertyCertDetails::find($id);
            if($RptPropertyCertDetails->created_by == \Auth::user()->creatorId()){
                $RptPropertyCertDetails->delete();
            }
    }
}
