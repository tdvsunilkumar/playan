<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommonModelmaster;
use App\Models\BploApplication;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\RptProperty;
use App\Models\Barangay;
use App\Models\RptPropertyAppraisal;
use App\Models\RptPlantTreesAppraisal;
use App\Models\ProfileMunicipality;
use App\Models\RptPropertyApproval;
use App\Models\RevisionYear;
use App\Models\RptPropertyHistory;
use App\Models\RptPropertySworn;
use App\Models\RptPropertyStatus;
use App\Models\RptPropertyAnnotation;
use App\Models\RptLandUnitValue;
use App\Models\RptPlantTressUnitValue;
use App\Models\RptBuildingUnitValue;
use App\Models\RptAssessmentLevel;
use App\Models\RptBuildingFloorValue;
use App\Models\RptPropertyMachineAppraisal;
use App\Models\RptPropertyTaxCert;
use App\Models\RptPropertyTaxCertDetail;
use App\Models\RptPropertyOwner;
use App\Models\User;
use App\Helpers\Helper;
use App\Http\Controllers\RptPropertyController;
use DB;
use Carbon\Carbon;
use \Mpdf\Mpdf as PDF;
use Illuminate\Support\Facades\Storage;

class TaxClearanceController extends Controller
{
     public $arrRevisionYears = [];
     public $activeRevisionYear = "";
     public $arrBarangay = [];
     public $owners = [];
     public $taxDNo = [];
     public $employee = [];
     public $data = [];
     private $slugs;
    public function __construct(){
        $this->_rptproperty = new RptProperty();
        $this->_revisionyear = new RevisionYear;
        $this->_muncipality = new ProfileMunicipality;
        $this->_rptpropertytaxcert = new RptPropertyTaxCert;
        $this->_barangay   = new Barangay;
        $allOwners = RptPropertyOwner::where('is_active',1)->get();
        foreach ($this->_rptproperty->getRevisionYears() as $val) {
            $this->arrRevisionYears[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        }foreach ($this->_muncipality->getRptActiveMuncipalityBarngyCodes() as $val) {
            $this->arrBarangay[$val->id]=$val->brgy_code.'-'.$val->brgy_name;
        }foreach ($allOwners as $val) {
            $this->owners[$val->id]=$val->standard_name;
        }
        foreach ($this->_rptpropertytaxcert->getTaxDeclaresionNODetailsAll() as $val) {
            $this->taxDNo[$val->id]=$val->rp_tax_declaration_no;
        }
        

        $this->activeRevisionYear    = $this->_revisionyear->getActiveRevisionYear();
        $this->data = [
            'id' => '',
            'rptc_year' => '',
            'rptc_control_no' => '',
            'rptc_owner_code' => '',
            'rptc_owner_tin_no' => '',
            'rptc_requestor_code' => '',
            'rptc_including_year' => '',
            'rptc_purpose' => '',
            'rptc_date' => '',
            'rptc_checked_by' => '',
            'rptc_checked_by_name' => '',
            'rptc_checked_position' => '',
            'rptc_prepared_by' => '',
            'rptc_prepared_by_name' => '',
            'rptc_prepared_position' => '',
            'rptc_or_no' => '',
            'rptc_or_date' => '',
            'rptc_or_amount' => '',
            'rptc_registered_by' => '',
            'rptc_modified_by' => '',
            'cashier_detail_id' => '',
            'cashier_id' => '',
            'created_at' => '',
            'updated_at' => ''
        ];
     $this->slugs = 'treasurer/tax-clearance';
    }

    public function getClientTaxClearance(Request $request){
         $id= $request->input('pid');
         $data = $this->_rptpropertytaxcert->clientData($id);
         echo json_encode($data);
    }
    public function getPropertyClientName(Request $request){
         $id= $request->input('id');
         $data = $this->_rptpropertytaxcert->getPropertyClientName($id);
         echo json_encode($data);
    }
    

    public function print($id=''){
        $taxes = RptPropertyTaxCert::with([
            'taxCertDetails.realProperty'
        ])->where('rptc_control_no',$id)
        ->first();
        //dd($taxes->id);
        $rpCOdesList  = DB::table('rpt_property_tax_cert_details')->where('rptc_code',$taxes->id)->pluck('rp_code','rp_code')->toArray();
        //dd($rpCOdesList);
        $taxesDetails = $this->_rptpropertytaxcert->paymentDetailsStandardQuery()->whereIn('ccrp.rp_code',$rpCOdesList)->get();
        // dd($taxes->rptc_checked_by);
        $CheckedBy= $this->_rptpropertytaxcert->getAppraisersPositionDetails($taxes->rptc_checked_by);
        // dd($CheckedBy);
        $PreparedBy= $this->_rptpropertytaxcert->getAppraisersPositionDetails($taxes->rptc_prepared_by);
        //dd($taxesDetails);
       // return view('taxclearance.print',compact('taxes','taxesDetails'));
        $documentFileName = "Tax-Clearance.pdf";
        $document = new \Mpdf\Mpdf(['format' => 'A5']);
        $document->watermarkImgBehind = true;
        $document->showWatermarkImage = true;
        // Set some header informations for output
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
        ];  
        $html = view('taxclearance.print',compact('taxes','taxesDetails','CheckedBy','PreparedBy'))->render();
        $document->WriteHTML($html);
        // Save PDF on your public storage 
        Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "S"));
        // Get file back from storage with the give header informations
        return Storage::disk('public')->download($documentFileName, 'Request', $header); //
    }
    public function getEmployeeTaxApproved(Request $request){
        $id= $request->input('id');
        $data = $this->_rptpropertytaxcert->getAppraisersPositionDetails($id);
        echo json_encode($data);
    }
    public function getEmployeeTaxRecommendin(Request $request){
        $id= $request->input('id');
        $data = $this->_rptpropertytaxcert->getAppraisersPositionDetails($id);
        echo json_encode($data);
    }
    public function getPaymentList(Request $request){
        $data=$this->_rptpropertytaxcert->getPaymentList($request);
        //dd($data);
        $arr=array();
        $i="0";    
        $count = 1;
        foreach ($data['data'] as $row){
            $arr[$i]['no']=$count;
            $arr[$i]['tax_payer']=$row->customername;
            $arr[$i]['td_no']=$row->rp_tax_declaration_no;
            if($row->startQtr != 14){
                $period = $row->startYear.' '.Helper::billing_quarters()[$row->startQtr].' - '.$row->endYear.' '.Helper::billing_quarters()[$row->endQtr];
            }else{
                $period = $row->startYear.' 1st Qtr - '.$row->endYear.' 4th Qtr';
            }
            $arr[$i]['period_covered']=$period;
            $arr[$i]['assessed_value']=Helper::decimal_format($row->assessedValue);
            $arr[$i]['or_no'] = $row->or_no;
            $arr[$i]['or_amount']=Helper::decimal_format($row->net_tax_due_amount);
            $arr[$i]['or_date']=date("d/m/Y",strtotime($row->cashier_or_date));
            $arr[$i]['status']=($row->status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>');
            $i++;
            $count++;
        }
        
        $totalRecords = $data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }

    public function getList(Request $request){
        session()->forget('tdsSelectedForTaxClearance');
        $data=$this->_rptpropertytaxcert->getList($request);
        //dd($data);
        $arr=array();
         $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0; 
        foreach ($data['data'] as $row){
            //dd($row->rptProperty->revision_year_code);
			$sr_no=$sr_no+1;
            $arr[$i]['no']=$sr_no;
            $arr[$i]['control_no']=$row->rptc_control_no;
            $arr[$i]['rptc_owner_tin_no']=$row->rptc_owner_tin_no;
            $arr[$i]['rptc_purpose']=$row->rptc_purpose;
            $arr[$i]['rptc_or_no']=$row->rptc_or_no;
            $arr[$i]['rptc_or_amount'] = Helper::money_format($row->rptc_or_amount);
            $arr[$i]['rptc_date']=date("d/m/Y",strtotime($row->rptc_or_date));
            $arr[$i]['owner']=$row->customername;
            $arr[$i]['requester']=$row->requesterName;
            $arr[$i]['rptc_or_no']=$row->rptc_or_no;
            $arr[$i]['td_count']=$row->rp_code;
            $arr[$i]['action']='
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center addNewProperty2" title="Update Tax Clearance" data-id="'.$row->id.'" data-url="'.url('taxclearance/store?id='.$row->id).'"  data-title="Update Tax Clearance">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div><div class="action-btn bg-primary ms-2">
                    <a href="'.url('taxclearance/print',$row->rptc_control_no).'" class="mx-3 btn btn-sm  align-items-center"  target="_blank"  title="Print Tax Clearance" data-id="'.$row->id.'" data-url="'.url('taxclearance/print',$row->id).'"  data-title="Print Tax Clearance">
                        <i class="ti-printer text-white"></i>
                    </a>
                </div>';
            $i++;
        }
        
        $totalRecords = $data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }
    
    public function loadSelectedTds(Request $request){
        $id = $request->has('id')?$request->id:0;
        //$tdsDetails = RptProperty::with(['landAppraisals','floorValues','machineAppraisals','barangay','propertyKindDetails'])
                                //->where('pk_is_active',1);
        $tdsDetails = $this->_rptpropertytaxcert->paymentDetailsStandardQuery()->where('rp.pk_is_active',1);
        if($id != ''){
            $selectedTds = DB::table('rpt_property_tax_cert_details')->where('rptc_code',$id)->pluck('rp_code','rp_code');
            //dd($selectedTds);
            $tdsDetails->whereIn('rp.id',$selectedTds->toArray());
        }else{
            $selectedTds = (session()->has('tdsSelectedForTaxClearance'))?session()->get('tdsSelectedForTaxClearance'):[];
            $tdsDetails->whereIn('rp.id',$selectedTds);
        }
        
                               
        $tdsDetails = $tdsDetails->get();
       // dd($tdsDetails);  
        return view('taxclearance.ajax.loadselectedtds',compact('tdsDetails','id'));                         
    }


    public function deleteSelectedTd(Request $request){
        
            $id = $request->input('id');
            $parentId = $request->input('parent_id');
            if($request->has('parent_id') && $request->parent_id == ''){
                //dd($request->session()->get('tdsSelectedForTaxClearance'));
               $request->session()->forget('tdsSelectedForTaxClearance.'.$request->id);
               return response()->json(['status' => __('success'), 'msg' => 'Selected TD delete successfully!']);
            }else{
                $propDetails = RptProperty::where('id',$request->id)->first();
                try {
                    $rptPlantTreeAppraisal = RptPropertyTaxCertDetail::where('rptc_code',$request->parent_id)
                                                                 ->where('rp_code',$propDetails->id)
                                                                 ->delete();
                    return response()->json(['status' => __('success'), 'msg' => 'Property Appraisal delete successfully!']);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', __($e->getMessage()));
                }
            

           
            }
    }
    
    public function storeData(Request $request)
    {
       $validator = \Validator::make(
            $request->all(), [
                'rptc_date'  => 'required',
                'rptc_owner_code'=>'required',
                'rptc_including_year' => 'required|digits:4|integer|min:1900',
                'rptc_purpose' => 'required',
                'rptc_requestor_code' => 'required',
                'rptc_checked_by' => 'required',
                'rptc_prepared_by' => 'required',
                'rptc_or_no' => 'required',
                'rptc_or_date' => 'required',
                'rptc_or_amount' =>'required',
                'rptc_owner_tin_no' =>'required',
            ],
            [
                'rptc_date.required'  => 'Required Field',
                'rptc_owner_code.required'=>'Required Field',
                'rptc_requestor_code.required' => 'Required Field',
                'rptc_including_year.required' => 'Required Field',
                'rptc_including_year.digits' => 'Invalid Value',
                'rptc_including_year.integer' => 'Invalid Value',
                'rptc_including_year.min' => 'Invalid Value',
                'rptc_purpose.required' => 'Required Field',
                'rptc_checked_by.required' => 'Required Field',
                'rptc_prepared_by.required' => 'Required Field',
                'rptc_or_no.required' => 'Required Field',
                'rptc_or_date.required' => 'Required Field',
                'rptc_or_amount.required' =>'Required Field',
                'rptc_owner_tin_no.required' =>'Required Field',
            ]
        );
        $validator->after(function ($validator) use($request) {
            $data = $validator->getData();
            if($data['id'] == ''){
                $sessionData = collect(session()->get('tdsSelectedForTaxClearance')); 
            }else{
                $selectedTds = DB::table('rpt_property_tax_cert_details')->where('rptc_code',$data['id'])->pluck('rp_code','rp_code');
                $lastSavedSession = DB::table('rpt_properties')->whereIn('id',$selectedTds->toArray())->pluck('rp_td_no','rp_td_no');
                $sessionData = $lastSavedSession;
            }                  
                if($sessionData->isEmpty()){
                    $validator->errors()->add('rp_td_no', 'No Td Selected yet!');
                }
              
            
    });
         $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            return response()->json($arr);
        }

        foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
               }
               unset($this->data['rptc_checked_by_name']);
               unset($this->data['rptc_prepared_by_name']);
               $this->data['rptc_modified_by'] = \Auth::user()->id;
               $this->data['updated_at'] = date("Y-m-d H:i:s");
        if($request->has('id') && $request->id != null){
            $this->_rptpropertytaxcert->updateData($request->id,$this->data);
            $msg = 'Tax clearance updated successfully!';
        }else{
            $this->data['rptc_registered_by'] = \Auth::user()->id;
            $this->data['created_at'] = date("Y-m-d H:i:s");
            //dd($this->data);
            $lastInsertedId = $this->_rptpropertytaxcert->addData($this->data);
            $this->generateControlNo($lastInsertedId);
            $sessionData = session()->get('tdsSelectedForTaxClearance');
            foreach ($sessionData as $key => $data) {
                $propDetails = RptProperty::where('id',$data)->first();
                $dataToSaveInDetails = [
                    'rptc_code' => $lastInsertedId,
                    'rp_code'  => $propDetails->id,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ];
                $this->_rptpropertytaxcert->addCertDetailsData($dataToSaveInDetails);
            } 
            session()->forget('tdsSelectedForTaxClearance'); 
            $msg = 'Tax clearance created successfully!';
        }
        return response()->json([
            'status' => 'success',
            'msg' => $msg
        ]);       
    }

     public function generateControlNo($id = ''){
        $billing = $this->_rptpropertytaxcert->find($id);
        $noOfBillingsForYear = $this->_rptpropertytaxcert->where('rptc_year',date("Y"))->count();
        $controlNo = date("Y")."-".str_pad($noOfBillingsForYear, 5, '0', STR_PAD_LEFT);;
        $dataToUpdate = [
            'rptc_control_no' => $controlNo
        ];
        $this->_rptpropertytaxcert->updateData($billing->id,$dataToUpdate);
        
        
    }

    public function store(Request $request)
    {
        $barangyCode = (session()->has('taxClearanceSelectedBrgy'))?session()->get('taxClearanceSelectedBrgy'):'';
        $brngyDetails = $this->_barangay->getActiveBarangayCode($barangyCode);
        $userDetails = User::find(\Auth::user()->creatorId());
        if($request->has('id') && $request->id != ''){
            $data = $this->_rptpropertytaxcert
                     ->leftJoin('cto_cashier as cc','cc.id','=','rpt_property_tax_certs.cashier_id')
                     ->where('rpt_property_tax_certs.id',$request->id)
                     ->select('rpt_property_tax_certs.*','cc.or_no','cc.total_paid_amount','cc.cashier_or_date')
                     ->first();
            //dd($data);
            $processedBy = User::find($data->rptc_checked_by);
            $data->rptc_checked_by_name = $processedBy->name;
            $data->rptc_prepared_by_name = $processedBy->name;
        }else{
            $data = (object)$this->data;
        }
        return view('taxclearance.store',compact('brngyDetails','userDetails','data'));
    }

    public function index(Request $request)
    { 
        $this->is_permitted($this->slugs, 'read');
        session()->forget('tdsSelectedForTaxClearance');
        $revisionYears = $this->arrRevisionYears;
        $activeRevisionYear = ($this->activeRevisionYear != null)?$this->activeRevisionYear->id:'';
        $arrBarangay = $this->arrBarangay;
         return view('taxclearance.index',compact('revisionYears','activeRevisionYear','arrBarangay'));
        
    }
    public function getTDNoTaxClearance(Request $request){
        $id= $request->input('id');
        $getgroups = $this->_rptpropertytaxcert->getTaxDeclaresionNOBuildingDetails($id);
       $htmloption ="<option value=''>Select TD No.</option>";
      foreach ($getgroups as $key => $value) {
         
            $htmloption .='<option value="'.$value->id.'">'.$value->rp_tax_declaration_no.'</option>';
        
      }
      echo $htmloption;
    }
    public function getTDNoTaxClearanceAll(Request $request){
       $getgroups = $this->_rptpropertytaxcert->getTaxDeclaresionNODetailsAll();
       $htmloption ="<option value=''>Select TD No.</option>";
      foreach ($getgroups as $key => $value) {
         $htmloption .='<option value="'.$value->id.'">'.$value->rp_tax_declaration_no.'</option>';
        
      }
      echo $htmloption;
    }

    public function getOrNoForOwner(Request $request){
            
         $data = $this->_rptpropertytaxcert->getOrNoForOwnerRemoteSelectList($request);
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

    public function gethremployees(Request $request){
        $data = $this->_rptpropertytaxcert->getEmployee($request);
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
    public function searchByTdNo(Request $request){
        //dd($request->all());
        $brngy = $request->brgy_no;
        $revisionYear = $request->rvy_revision_code;
        $rpTdNo       = $request->rp_td_no;
        $propertyDetails = [];
        if($request->has('parent_id') && $request->parent_id != ''){
            $selectedTds = DB::table('rpt_property_tax_cert_details')
                           ->where('rptc_code',$request->parent_id)
                           ->pluck('rp_code','rp_code');
            $lastSavedSession = DB::table('rpt_properties')->whereIn('id',$selectedTds->toArray())->pluck('rp_td_no','rp_td_no');
            $lastSavedSession = $lastSavedSession->toArray();
        }else{
            $lastSavedSession = (session()->has('tdsSelectedForTaxClearance'))?session()->get('tdsSelectedForTaxClearance'):[];
        }
        //dd($lastSavedSession);
        $validator = \Validator::make(
            $request->all(), [
                // 'rp_td_no'  => 'required',
                'brgy_no'=>'required',
            ],
            [
                // 'rp_td_no.required'=>'Required Field',
                'brgy_no.required'=>'Required Field',
            ]
        );
        $validator->after(function ($validator) use($brngy,$rpTdNo, &$propertyDetails,$lastSavedSession) {
            $data = $validator->getData();
                $oldPropertyData = $this->_rptpropertytaxcert->searchByTdNo($rpTdNo,$brngy);
                $propertyDetails = $oldPropertyData;                                
                if($oldPropertyData == null){
                    $validator->errors()->add('rp_td_no', 'No Td found!');
                }
                if(in_array($rpTdNo,$lastSavedSession)){
                    $validator->errors()->add('rp_td_no', 'Already selected!');
                }
            $checkPaymentRecord = $this->_rptpropertytaxcert->paymentDetailsStandardQuery()
                                                            ->where('ccrp.rp_code',$data['rp_td_no'])
                                                            ->first();
            if($checkPaymentRecord == null){
                $validator->errors()->add('rp_td_no', 'No payment record found!');
            }
              
            
    });
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            return response()->json($arr);
        }
        if($request->has('parent_id') && $request->parent_id != ''){
            //dd($lastSavedSession);
                $propDetails = RptProperty::where('id',$request->rp_td_no)->first();
                $dataToSaveInDetails = [
                    'rptc_code' => $request->parent_id,
                    'rp_code'  => $propDetails->id,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ];
                $this->_rptpropertytaxcert->addCertDetailsData($dataToSaveInDetails);
        }else{
            $lastSavedSession[$rpTdNo] = $rpTdNo;
            session()->put('tdsSelectedForTaxClearance',$lastSavedSession);
        }
        
        //dd($dataToReturn);
        return response()->json(['status' => 'success','data'=>$lastSavedSession]);
    }

    public function getTdsForAjaxSelectList(Request $request){
        $data = $this->_rptpropertytaxcert->getTdsForAjaxSelectList($request);
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
