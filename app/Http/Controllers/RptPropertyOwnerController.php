<?php

namespace App\Http\Controllers;
use App\Models\RptPropertyOwner;
use App\Models\CommonModelmaster;
use App\Models\RptProperty;
use App\Models\RptPropertyHistory;
use App\Models\RevisionYear;
use App\Models\ProfileMunicipality;
use App\Models\RptLocality;
use App\Models\Bplo\BploClients;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use File;
use Carbon\Carbon;
use \Mpdf\Mpdf as PDF;
use Illuminate\Support\Facades\Storage;
use DB;

class RptPropertyOwnerController extends Controller
{
    
    public $data = [];
    public $postdata = [];
    public $arrgetBrgyCode = array(""=>"Please Select");
	public $arrgetClients = array(""=>"Please Select");
    public $arrgetCountries = array();
    private $slugs;
    private $slugs2;
    public $activeRevisionYear = [];
    public $activeMuncipalityCode = [];
    public function __construct(){
        $this->_rptpropertyowner = new RptPropertyOwner();
        $this->_commonmodel = new CommonModelmaster();
        $this->_propertyHistory = new RptPropertyHistory;
        $this->_revisionyear = new RevisionYear;
        $this->_muncipality = new ProfileMunicipality;
        $this->_bploClients = new BploClients();
        $this->data = array('id'=>'','rpo_custom_last_name'=>'','rpo_first_name'=>'','rpo_middle_name'=>'','suffix'=>'','p_code'=>'','rpo_address_house_lot_no'=>'','rpo_address_street_name'=>'','rpo_address_subdivision'=>'','brgy_code'=>'','p_barangay_id_no'=>'','p_telephone_no'=>'','p_mobile_no'=>'','p_fax_no'=>'','p_tin_no'=>'','p_email_address'=>'','country'=>'','gender'=>'','dateofbirth'=>'');

        foreach ($this->_rptpropertyowner->getCountries() as $val) {
            $this->arrgetCountries[$val->id]=$val->nationality;
        }

        /*foreach ($this->_rptpropertyowner->getClientslist() as $val) {
          
              $this->arrgetClients[$val->id]=$val->full_name;
          }*/
          $this->activeRevisionYear    = $this->_revisionyear->getActiveRevisionYear();
          $this->activeMuncipalityCode = $this->_muncipality->getActiveMuncipalityCode();
          $this->slugs = 'real-property-taxpayers-file';
          $this->slugs2 = 'real-property/property-owners';
    }

    public function index(Request $request)
    {
        $this->is_permitted($this->slugs2, 'read');
        $isopen=$request->input('isopenAddform');
        return view('rptpropertyowner.index',compact('isopen'));
        
    }
    
    public function getList(Request $request){
        $data=$this->_rptpropertyowner->getList($request);
        //dd($data);
        $arr=array();
        $i="0";
        $j=(int)$request->input('start')-1;
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $arr[$i]['no']= $j;  $status ="";
            $arr[$i]['account_no']=$row->account_no;
             $checkclientisused = $this->_rptpropertyowner->checkClientisused($row->id); 
             if(count($checkclientisused)<=0){
             $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a></div>';
            }

            $arr[$i]['rpt_owner'] = $row->full_name;
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

            $addressnew = wordwrap($this->_commonmodel->getTaxPayerAddress($row->id), 50, "<br/>\n");
            $arr[$i]['rpo_address_house_lot_no']="<div class='showLess'>".$addressnew."</div>";
            $arr[$i]['p_mobile_no']=$row->p_mobile_no;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
           
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">

                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptpropertyowner/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Property Owner">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                 </div>
                    '.$status.'
              ';
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
        $data=array('is_rpt' => $is_activeinactive);
        $this->_rptpropertyowner->updateActiveInactive($id,$data);
    }
    
    public function getProfileDetails(Request $request){
        $id= $request->input('id');
        $data = $this->_rptpropertyowner->getProfileDetails($id);
		print_r($data);die;
        foreach ($this->_commonmodel->getBarangay($data->p_barangay_id_no)['data'] as $val) {
            $data->p_barangay_id_no = "<option value='".$val->id."' selected>".$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region."</option>";
        }
        echo json_encode($data);
    }
	 public function getClientsDetails(Request $request){
        $id= $request->input('pid');
        $data = $this->_rptpropertyowner->getClientsDetails($id);
        foreach ($this->_commonmodel->getBarangay($data->p_barangay_id_no)['data'] as $val) {
            $data->p_barangay_id_no = "<option value='".$val->id."' selected>".$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region."</option>";
        }
        echo json_encode($data);
    }
    public function store(Request $request){
        //dd($request->isMethod('post'));
        $data = (object)$this->data;
        $arrgetBrgyCode = $this->arrgetBrgyCode;
        // If its in add
        $barangays = $this->_commonmodel->getBarangay();
        foreach ($barangays['data'] as $val) {
            $arrgetBrgyCode[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
        }
        $arrgetCountries =$this->arrgetCountries;
		$arrgetClients = [];
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptPropertyOwner::where('id', $request->input('id'))->first();
            foreach ($this->_commonmodel->getBarangay($data->p_barangay_id_no)['data'] as $val) {
                $arrgetBrgyCode[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
            }
            
        }
       
        if($request->isMethod('post')){
            $telNo = str_replace(array( '(', ')',' ' ), '', $request->p_telephone_no);
            $mobNo = str_replace(array( '(', ')',' ' ), '', $request->p_mobile_no);
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['p_telephone_no'] = $telNo;
            $this->data['p_mobile_no'] = $mobNo;
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if(($request->input('clientsregistered')>0))
            {    $updatearay = array();
                 unset($this->data['id']);
                 $this->data['is_rpt'] = '1';
               $this->_rptpropertyowner->updateData($request->input('clientsregistered'),$this->data);
               $client_id = $request->input('clientsregistered');
               $success_msg = 'RPT Clients Added successfully.';
            }else {
                  if($request->input('id')>0){
                      $this->_rptpropertyowner->updateData($request->input('id'),$this->data);
                      $client_id = $request->input('id');
                      $success_msg = 'Property Owner updated successfully.';
                  }else{
                      $this->data['is_rpt']=1;
                      $this->data['created_by']=\Auth::user()->creatorId();
                      $this->data['created_at'] = date('Y-m-d H:i:s');
                      $currentYear = date('Y');
                      $previousClientNo = $this->_bploClients->getMaxClientNoForYear($currentYear);
                      $clientNo = $previousClientNo + 1;
                      $this->data['client_year'] = $currentYear;
                      $this->data['client_no'] = $clientNo;
                      $client_id = $this->_rptpropertyowner->addData($this->data);
                      $success_msg = 'RPT Owner added successfully.';
                  }
            }
            $this->_commonmodel->updateTaxPayerFullAddress($client_id);

            if($request->ajax()){
                return response()->json(['status'=>'success','msg'=>$success_msg]);
            }else{
                // return redirect()->route('real-property/property-owners')->with('success', __($success_msg));
                return redirect('real-property/property-owners')->with('success', __($success_msg));
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
        return view('rptpropertyowner.create',compact('data','arrgetBrgyCode','arrgetCountries','arrgetClients'));
    }

    

    public function formValidation(Request $request){
        
       /*  $telNo = str_replace(array( '(', ')',' ' ), '', $request->p_telephone_no);
        $mobNo = str_replace(array( '(', ')',' ' ), '', $request->p_mobile_no);
        $request->merge([ 'p_telephone_no' => $telNo,'p_mobile_no' => $mobNo ]);
        $regex = '/^([+-]?(?=\\.\\d|\\d)(?:\\d+)?(?:\\.?\\d*))(?:[e]([+-]?\\d+))?$/i';
        $validator = \Validator::make(
            $request->all(), [
              'rpo_custom_last_name'=>'required',  
              'p_barangay_id_no' => 'required',
              'p_mobile_no'    => 'required',
              'p_email_address' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
              // 'country' => 'required',
              // 'gender' => 'required'
             ],[
              'rpo_custom_last_name.required'=>'Required Field',  
              'p_barangay_id_no.required' => 'Required Field',
              'p_telephone_no.required' => 'Required Field',
              'p_telephone_no.min' => 'Invalid Telephone',
              'p_mobile_no.required'    => 'Required Field',
              'p_mobile_no.min'    => 'Invalid Mobile',
              'p_email_address.required' => 'Required Field',
              'country.required' => 'Required Field',
              'gender.required' => 'Required Field'
             ]
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit; */
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
    
    public function Delete(Request $request){
        $id = $request->input('id');
            $RptPropertyOwner = RptPropertyOwner::find($id);
            if($RptPropertyOwner->created_by == \Auth::user()->creatorId()){
                $RptPropertyOwner->delete();
            }
    }

    /* Real Property Tax Owner File */

    public function taxpayersindex(Request $request){
        $this->is_permitted($this->slugs, 'read'); 
        return view('realpropertytaxpayerfile.index');
         
    }

    public function rptopGetList(Request $request){
        $data=$this->_rptpropertyowner->getRptopList($request);
        //dd($data);
        $arr=array();
        $i="0";
        $j=(int)$request->input('start')+1; 
        foreach ($data['data'] as $row){
            $arr[$i]['no']= $j;
            $arr[$i]['rpt_owner'] = $row->full_name;
            if($row->rpo_address_house_lot_no){
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
            $arr[$i]['rpo_address_house_lot_no']="<div class='showLess'>".$addressnew."</div>";
            $arr[$i]['p_mobile_no']=$row->p_mobile_no;
            $arr[$i]['email']=$row->p_email_address;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center addNewPropertyOwner" data-url="'.url('/rptpropertyowner/store?id='.$row->id).'" title="Edit"  data-title="Manage Property Owner">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center showTaxDeclarationsDetails" data-url="'.url('/rptpropertyowner/show?id='.$row->id).'" title="RPTO"  data-title="Manage Property Owner">
                        <i class="ti-eye text-white"></i>
                    </a>
                </div>';
           
           
            $i++;
            $j++;
        }
        
        $totalRecords=$data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }

    public function show(Request $request){
        //dd($this->activeRevisionYear);
        $propOwner = $request->id;
        $rptProperties = RptProperty::with(
            ['propertyKindDetails'=>function($query){
                                              $query->select(['id','pk_code','pk_description']);
                                         },
                                      
                                     ])
                                      ->select(['id','rp_tax_declaration_no','rp_pin_declaration_no','rp_property_code','rpo_code','rvy_revision_year_id','brgy_code_id','pk_id','rp_section_no','rp_pin_no','rp_td_no','pc_class_code','rpb_assessed_value','rpb_accum_deprec_market_value','rp_assessed_value'])
                                      ->where('rpo_code',$propOwner)
                                      ->where('is_deleted',0);
        $rptProperties = $rptProperties->get();
        $activeRevisionYear = $this->activeRevisionYear;
        $path = $request->path();
        if($path == 'rptpropertyowner/show'){
            return view('realpropertytaxpayerfile.show',compact('rptProperties','propOwner','activeRevisionYear'));
        }else{
            return $rptProperties;
        }
        
    }

    public function rptopLoadHistory(Request $request){
        $id = $request->id;
        $history = $this->_propertyHistory->with([
                'activeProp.revisionYearDetails',
                'cancelProp.revisionYearDetails',
                'activeProp.barangay',
                'cancelProp.barangay',
                'cancelProp.propertyOwner',
                'cancelProp.landAppraisals.actualUses'
            ])->where('rp_property_code',$id)->get();
        //dd($history);
        return view('realpropertytaxpayerfile.history',compact('history'));
    }



    public function printBill(Request $request){
        $this->_rptpropertyowner->eligibleToGenearteCoNumb($request);
        $rptProperties = $this->show($request);
        $controlNumber = DB::table('rpt_properties_assessment_notices')->where('rpo_code',$request->id)->orderBy('id','desc')->first();
        //dd($controlNumber);
        $propOwnerDetails = RptPropertyOwner::find($request->id);
        $activeRevisionYear = $this->activeRevisionYear;
        $RptLocality = RptLocality::where('mun_no',(isset($this->activeMuncipalityCode->id))?$this->activeMuncipalityCode->id:0)->where('department',1)->first();
        //dd($RptLocality);
        $data = [
                    'propOwnerDetails' => $propOwnerDetails, 
                    'rptProperties' => $rptProperties,
                    'controlNumber' => $controlNumber,
                    'activeRevisionYear' => $activeRevisionYear,
                    'RptLocality' => $RptLocality
                ];
                //dd($propOwnerDetails);
        $documentFileName = "assessment-list.pdf";
        $document = new PDF( [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => '3',
            'margin_top' => '20',
            'margin_bottom' => '20',
            'margin_footer' => '2',
        ]);   
        $document->shrink_tables_to_fit = 0;  
        $document->AddPage('L','','','','',10,10,4,4,10,10);
 
        // Set some header informations for output
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
        ];  
        $html = view('realpropertytaxpayerfile.billofassessement', $data)->render();

        $document->WriteHTML($html);
        // $document->WriteHTML($html_back);
         
        // Save PDF on your public storage 
        Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "S"));
         
        // Get file back from storage with the give header informations
        return Storage::disk('public')->download($documentFileName, 'Request', $header); //
    }
	
	public function uploadDocument(Request $request){
        
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = RptPropertyOwner::find($healthCertId);
        
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->doc_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/rptpropertyowner/';
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
                $this->_rptpropertyowner->updateData($healthCertId,$data);
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
                                <a class='btn' href='".asset('uploads/rptpropertyowner').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
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
        $arrEndrosment = RptPropertyOwner::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->doc_json,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/rptpropertyowner/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['doc_json'] = json_encode($arrJson);
                    $this->_rptpropertyowner->updateData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }

    public function getAllClients(Request $request){
         $data = $this->_rptpropertyowner->getAllClients($request);
         $morePages=true;
         $pagination_obj= json_encode($data);
           if (empty($data->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $data->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        return response()->json($results);
    }

    public function taxPayerAjaxRequest(Request $request){
         $data = $this->_rptpropertyowner->getClientslist($request);
         $morePages=true;
         $pagination_obj= json_encode($data);
           if (empty($data->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $data->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        return response()->json($results);
    }
   
}
