<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommonModelmaster;
use App\Models\RptCtoBillingDetail;
use App\Models\RptCtoBillingDetailsPenalty;
use App\Models\RptCtoBillingDetailsDiscount;
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
use App\Models\RptCtoBilling;
use App\Models\RptPropertyOwner;
use App\Models\HrEmployee;
use App\Models\HrDesignation;
use App\Models\RptLocality;
use App\Models\RptCtoTaxRevenue;
use App\Models\User;
use App\Helpers\Helper;
use App\Http\Controllers\RptPropertyController;
use DB;
use Carbon\Carbon;
use \Mpdf\Mpdf as PDF;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\SmsTemplate;
use App\Repositories\ComponentSMSNotificationRepository;

class BillingFormController extends Controller
{
	 public $arrRevisionYears = [];
     public $activeRevisionYear = "";
     public $arrBarangay = [];
     public $owners = [];
     private $slugs;
     private $slugs2;

	public function __construct(){
		$this->_rptproperty = new RptProperty();
        $this->_revisionyear = new RevisionYear;
        $this->_muncipality = new ProfileMunicipality;
        $this->_ctobilling = new RptCtoBilling;
        $this->_barangay   = new Barangay;
        $allOwners = RptPropertyOwner::where('is_active',1)->orderBy('created_at', 'DESC')->get();
		foreach ($this->_rptproperty->getRevisionYears() as $val) {
            $this->arrRevisionYears[$val->id]=$val->rvy_revision_year.'-'.$val->rvy_revision_code;
        }foreach ($this->_muncipality->getRptActiveMuncipalityBarngyCodes() as $val) {
            $this->arrBarangay[$val->id]=$val->brgy_code.'-'.$val->brgy_name;
        }foreach ($allOwners as $val) {
            $this->owners[$val->id]=$val->standard_name;
        }

        $this->activeRevisionYear    = $this->_revisionyear->getActiveRevisionYear();
        $this->slugs = 'treasurer/property-billing/single-property-billing';
        $this->slugs2 = 'treasurer/property-billing/multiple-property-billing';
	}

    public function index(Request $request)
    { 
        $this->is_permitted($this->slugs, 'read');
    	session()->forget('billingSelectedRevsionYear');
        session()->forget('billingTempData');
    	$revisionYears = $this->arrRevisionYears;
    	$activeRevisionYear = ($this->activeRevisionYear != null)?$this->activeRevisionYear->id:'';
        $arrBarangay = $this->arrBarangay;
        return view('billingform.index',compact('revisionYears','activeRevisionYear','arrBarangay'));
       
    }

    public function multiplePropertyIndex (Request $request){
        $this->is_permitted($this->slugs2, 'read');
        session()->forget('multipleBillingSelectedRevsionYear');
        session()->forget('billingTempData');
        $fromdate = date('Y-m-d'); $enddate = date('Y-m-d'); 
        $revisionYears = $this->arrRevisionYears;
        $activeRevisionYear = ($this->activeRevisionYear != null)?$this->activeRevisionYear->id:'';
        $arrBarangay = $this->arrBarangay;
        return view('billingform.multiple.index',compact('revisionYears','activeRevisionYear','arrBarangay','fromdate','enddate'));
    }

    public function storeMultiple (Request $request){
        $owners      = array('' => "Select Bill To");
        $controlid = ($request->has('id'))?$request->id:'';
        $controlDetails = $this->_ctobilling->where('rpt_cto_billings.cb_control_no',$controlid)->select('rpt_cto_billings.*','clients.full_name')->join('clients','clients.id','=','rpt_cto_billings.rpo_code')->first();
        /*if($controlDetails != null && $controlDetails->rpo_code != null)
        {
            foreach ($this->_commonmodel->getBploTaxpayersAutoSearchList($controlDetails->rpo_code)['data'] as $val) {
                $owners[$val->id]=$val->full_name;
            }
        }*/
        $billingMode = $request->has('cb_billing_mode')?$request->cb_billing_mode:0;
        $revisionYear = (session()->has('multipleBillingSelectedRevsionYear'))?session()->get('multipleBillingSelectedRevsionYear'):$this->activeRevisionYear->id;
        $barangyCode = (session()->has('multipleBillingSelectedBrgy'))?session()->get('multipleBillingSelectedBrgy'):'';
        $brngyDetails = $this->_barangay->getActiveBarangayCode($barangyCode);
        $revisionYearDetails =  $this->_revisionyear->find($revisionYear);
        $tdsData = $this->_rptproperty->where('pk_is_active',1)
                                  ->where('rvy_revision_year_id',$revisionYear)
                                  ->where('brgy_code_id',session()->get('billingSelectedBrgy'))
                                  ->get();
        return view('billingform.multiple.store',compact('revisionYearDetails','brngyDetails','billingMode','owners','controlDetails'));
    }

    public function store(Request $request)
    {
        $rpoCode     = $request->has('rpo_code')?$request->rpo_code:'';
        $controlNumber     = $request->has('cb_control_no')?$request->cb_control_no:'';
    	$billingMode = $request->has('cb_billing_mode')?$request->cb_billing_mode:0;
        if($billingMode == 0){
            $revisionYear = (session()->has('billingSelectedRevsionYear'))?session()->get('billingSelectedRevsionYear'):$this->activeRevisionYear->id;
            $barangyCode = (session()->has('billingSelectedBrgy'))?session()->get('billingSelectedBrgy'):'';
        }if($billingMode == 1){
            $revisionYear = (session()->has('multipleBillingSelectedRevsionYear'))?session()->get('multipleBillingSelectedRevsionYear'):$this->activeRevisionYear->id;
            $barangyCode = (session()->has('multipleBillingSelectedBrgy'))?session()->get('multipleBillingSelectedBrgy'):'';
        }
    	
        $controlNumberDetails = DB::table('rpt_cto_billings')->where('cb_control_no',$controlNumber)->first();
        $brngyDetails = $this->_barangay->getActiveBarangayCode($barangyCode);
        $tdnoarray = array(""=>"Select Tax Declaration No");
        foreach ($this->_ctobilling->getActivetdinbarangay($brngyDetails->id) as $key => $val) {
           $tdnoarray[$val->rp_td_no] = $val->rp_tax_declaration_no;
        }
        if($billingMode == 1){
            foreach ($this->_ctobilling->getActivetdinbarangayForMultiple() as $key => $val) {
             $tdnoarray[$val->rp_td_no] = $val->rp_tax_declaration_no;
           }
        }
    	$revisionYearDetails =  $this->_revisionyear->find($revisionYear);
        if($billingMode == 1){
    	$tdsData = $this->_rptproperty->where('pk_is_active',1)
    	                          ->where('rvy_revision_year_id',$revisionYear)
    	                          ->get();
        }else{
           $tdsData = $this->_rptproperty->where('pk_is_active',1)
                                  ->where('rvy_revision_year_id',$revisionYear)
                                  ->where('brgy_code_id',session()->get('billingSelectedBrgy'))
                                  ->get(); 
        }

    	                        
        //dd($brngyDetails);
    	return view('billingform.store',compact('revisionYearDetails','brngyDetails','billingMode','rpoCode','controlNumber','controlNumberDetails','tdnoarray'));
    }

    public function searchByTdNo(Request $request){
        session()->forget('billingTempData');
    	$brngy = $request->brgy_no;
    	$revisionYear = $request->rvy_revision_code;
    	$rpTdNo       = $request->rp_td_no;
        $billingmode  = $request->billingmode;

        $propertyDetails = [];
        $validator = \Validator::make(
            $request->all(), [
            	'rp_td_no'  => 'required',
                'brgy_no'=>'required',
                'rvy_revision_code' => 'required'
            ],
            [
            	'rp_td_no.required'=>'Required Field',
                'brgy_no.required'=>'Required Field',
                'rvy_revision_code.required'=>'Required Field',
            ]
        );
        $validator->after(function ($validator) use($brngy,$revisionYear,$rpTdNo,$billingmode, &$propertyDetails) {
            $data = $validator->getData();
                if($billingmode =='1'){
                     $oldPropertyData = $this->_rptproperty->searchByTdNoforMultiple($rpTdNo,$brngy,$revisionYear);
                }else{
                   $oldPropertyData = $this->_rptproperty->searchByTdNo($rpTdNo,$brngy,$revisionYear); 
                }
                
                $propertyDetails = $oldPropertyData;                                
                if($oldPropertyData == null){
                    $validator->errors()->add('rp_td_no', 'No Td found!');
                }
              
            
    });
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            return response()->json($arr);
        }
        //dd($propertyDetails->machineAppraisals[0]->pc_class_description);
        if( $propertyDetails->propertyKindDetails->pk_code == 'L'){
        	$pcClassCode = $propertyDetails->landAppraisals[0]->pc_class_code;
        	$class =     $propertyDetails->landAppraisals[0]->pc_class_description;   
        	$assessedValue = $propertyDetails->landAppraisals->sum('rpa_assessed_value');
        }if( $propertyDetails->propertyKindDetails->pk_code == 'B'){
        	$pcClassCode = $propertyDetails->pc_class_code;
        	$class =     $propertyDetails->pc_class_description;   
        	$assessedValue = $propertyDetails->rpb_assessed_value;
        }if( $propertyDetails->propertyKindDetails->pk_code == 'M'){
        	$pcClassCode = $propertyDetails->machineAppraisals[0]->pc_class_code;
        	$class =     $propertyDetails->machineAppraisals[0]->pc_class_description;   
        	$assessedValue = $propertyDetails->machineAppraisals->sum('rpm_assessed_value');
        }
        $startYear = $this->checkPendingBills($propertyDetails->id);
        $dataToReturn = [
        	'rp_code' => $propertyDetails->id,
            'rp_pindcno'=>$propertyDetails->rp_pin_declaration_no,
            'rp_location_number_n_street' => (isset($propertyDetails->barangay->brgy_name))?$propertyDetails->barangay->brgy_name:'',
            'rpo_code' => $propertyDetails->rpo_code,
            'assessed_value' =>$assessedValue,
            'land_owner' => (isset($propertyDetails->propertyOwner))?$propertyDetails->propertyOwner->standard_name:'',
            'land_location' => (isset($propertyDetails->propertyOwner->standard_address))?$propertyDetails->propertyOwner->standard_address:'',
            'kind' => $propertyDetails->propertyKindDetails->pk_description,
            'pk_code' => $propertyDetails->propertyKindDetails->pk_code,
            'class' => $class,
            'pc_class_code' => $pcClassCode,
            'note' => ''
        ];
        if(isset($startYear['year']) && $startYear['year'] == date("Y")+1){
            $dataToReturn['note'] = '<div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;"><div class="row"><div class="col-lg-2 col-md-2 col-sm-2"><div class="form-group"><label class="form-label">Note:</label></div></div><div class="col-lg-10 col-md-10 col-sm-10"><div class="form-group"> <input type="text" readonly="readonly"  class="form-control" value="New Tax Declaration created last '.date("m/d/Y",strtotime($propertyDetails->created_at)).'" style="color:red; font-weight:bold;"/></div></div></div></div>';
        }
        //dd($dataToReturn);
        return response()->json(['status' => 'success','data'=>$dataToReturn]);
    }

    public function getbarangaybyid(Request $request){
        $rpTdNo       = $request->rp_td_no;
        $PropertyData = $this->_rptproperty->getBarangayByid($rpTdNo);
        $dataToReturn = [
            'barangay' => (isset($PropertyData->barangay->brgy_name))?strtoupper($PropertyData->barangay->brgy_name):''
        ];
        session()->forget('billingTempData');
        return response()->json(['status' => 'success','data'=>$dataToReturn]);
    }

    public function generateControlNo($id = ''){
        $billing = $this->_ctobilling->find($id);
        $noOfBillingsForYear = $this->_ctobilling->where('cb_control_year',date("Y"))->where('cb_control_no','!=','')->orderBy('id','desc')->first();
        if($noOfBillingsForYear == null){
            $cNo = 1;
        }else{
            $cNo = str_replace('-','',strstr($noOfBillingsForYear->cb_control_no, '-'))+1;
        }
        $controlNo = date("Y")."-".str_pad($cNo, 5, '0', STR_PAD_LEFT);
        $dataToUpdate = [
            'cb_control_no' => $controlNo
        ];
        $this->_ctobilling->updateData($billing->id,$dataToUpdate);
        
        
    }

    public function checkForPreviousOwner($prop = '',$year){
        $previousOwners = DB::table('rpt_properties as rp')
                          ->join('rpt_property_approvals as rpa',function($j)use($prop){
                            $j->on('rpa.rp_property_code','=','rp.rp_property_code')->where('rpa.rp_property_code',$prop->rp_property_code);
                          })
                          ->where('rp.rp_property_code',$prop->rp_property_code)
                          ->where('rp.pk_is_active',9)
                          ->orWhere('rpa.rp_app_cancel_type',config('constants.update_codes_land.GR'))
                          ->pluck('rp.rp_app_effective_year');
        return $previousOwners;
    }

    

    public function checkPendingBills($propId='', $year = '', $qtr = ''){
        $propDetails = RptProperty::find($propId);
        $allEffectiveYears = $this->_ctobilling->getGrAndPreviousOwnerData($propDetails->rp_property_code,$propDetails->id)->pluck('rp_app_effective_year');
        $endYear = date("Y")+1;
        $paidYears = DB::table('rpt_cto_billing_details')
                      ->where('rp_property_code',$propDetails->rp_property_code)
                      //->where('sd_mode',14)
                      ->pluck('cbd_covered_year')
                      ->toArray();
        if(!$allEffectiveYears->isEmpty()){
            $startyear        = $allEffectiveYears->min();
        }else{
            $startyear = $propDetails->rp_app_effective_year;
        }

        $allPossibleYear = range($startyear,$endYear);
        $diif            = array_values(array_diff($allPossibleYear, $paidYears));
        $yearOfBilling   = (isset($diif[0]))?$diif[0]:$startyear;
        $lastPayment     = DB::table('rpt_cto_billing_details')->where('rp_property_code',$propDetails->rp_property_code)->where('cbd_covered_year',$startyear)->orderBy('cbd_covered_year','DESC')->first();
        if($lastPayment ==  null){
            $qtrOfBilling = 11;
        }else{
            //dd($lastPayment);
            if($lastPayment->sd_mode == '14' || $lastPayment->sd_mode == '44'){
                $qtrOfBilling           = 11;
            }else{
                $yearOfBilling     = $lastPayment->cbd_covered_year;
                $indesx        = array_search($lastPayment->sd_mode,array_keys(Helper::billing_quarters()));
                $qtrOfBilling           = array_keys(Helper::billing_quarters())[$indesx+1];
            }
        }
        if(empty($diif)){
            return [
                'year'   => date("Y")+1,
                'status' =>  true,
                'msg'    => 'Your all dues are cleared!',
            ];
        }
        if($year != $yearOfBilling || $qtr != $qtrOfBilling){
            return [
                'status' =>  true,
                'msg'    => 'Your year '.$yearOfBilling.' dues are still pending!',
                'year'   => $yearOfBilling,
                'qtr'    => $qtrOfBilling,
            ];
        }
    }

    public function checkBillingExistance($propId = '',$data=''){
        foreach ($data as $key => $value) {
            $newData = $data[0];
            $year    = $newData['year'];
            $sdMode  = $newData['sd_modes'][0];
            $result = DB::table('rpt_cto_billing_details')->where('rp_code',$propId)
                                                      ->where('cbd_covered_year',$year)
                                                      ->where('sd_mode',$sdMode)
                                                      ->orWhere('sd_mode',14)->count();
            if($result == 0){
            return ['status' => false,'msg' => 'no record exists'];
        }else{
            if($sdMode == '14'){
                $msg = "BIlling already exists for year ".$year;
            }else{
                $msg = "BIlling already exists for year ".$year."'s ".Helper::billing_quarters()[$sdMode];
            }
            return ['status' => true,'msg' => $msg];
        }                                          
        }
    }

    public function findSdMode($month = ''){
        if(in_array($month,[1,2,3])){
            return 11;
        }if(in_array($month,[4,5,6])){
            return 22;
        }if(in_array($month,[7,8,9])){
            return 33;
        }if(in_array($month,[10,11,12])){
            return 44;
        }
    }

    

    public function storeData(Request $request)
    {
        //dd($request->all());
        $validator = \Validator::make(
            $request->all(), [
                'rp_td_no'  => 'required',
                'brgy_no'=>'required',
                'rvy_revision_code' => 'required',
                'cb_covered_from_year' => 'required|digits:4|integer|min:1900',
                'cb_covered_to_year' => 'required|digits:4|integer|min:1900|gte:cb_covered_from_year|max:'.(date("Y")+1),
                'sd_mode' => 'required_if:cb_all_quarter_paid,=,0',
                'sd_mode_to' => 'required_if:cb_all_quarter_paid,=,0|gte:sd_mode',
                'rpo_code_desc' => 'required',
                'owner_address' =>'required',
                'pk_code_desc' =>'required',
                'prop_class' => 'required',
                'cb_assessed_value' => 'required'
            ],
            [
                'rp_td_no.required'  => 'Required Field',
                'brgy_no.required'=>'Required Field',
                'rvy_revision_code.required' => 'Required Field',
                'cb_covered_from_year.required' => 'Required Field',
                'cb_covered_from_year.digits' => 'Invalid Value',
                'cb_covered_from_year.integer' => 'Invalid Value',
                'cb_covered_from_year.min' => 'Invalid Value',
                'cb_covered_from_year.max' => 'Invalid Value',
                'cb_covered_to_year.required' => 'Required Field',
                'cb_covered_to_year.digits' => 'Invalid Value',
                'cb_covered_to_year.integer' => 'Invalid Value',
                'cb_covered_to_year.min' => 'Invalid Value',
                'cb_covered_to_year.max' => 'Invalid Value',
                'cb_covered_to_year.gte' => 'Invalid Value',
                'sd_mode.required' => 'Required Field',
                'sd_mode_to.required' => 'Required Field',
                'sd_mode_to.gte' => 'Invalid Value',
                'rpo_code_desc.required' => 'Required Field',
                'owner_address.required' =>'Required Field',
                'pk_code_desc.required' =>'Required Field',
                'prop_class.required' => 'Required Field',
                'cb_assessed_value.required' => 'Required Field'
            ]
        );
        $validator->after(function ($validator) use($request) {
            $data = $validator->getData();
               if(isset($data['cb_billing_mode']) && $data['cb_billing_mode'] =='1'){
                     $oldPropertyData = $this->_rptproperty->searchByTdNoforMultiple($request->rp_td_no,$request->brgy_code,$request->rvy_revision_year);
                }else{
                    $oldPropertyData = $this->_rptproperty->searchByTdNo($request->rp_td_no,$request->brgy_code,$request->rvy_revision_year);
                }
                                               
                if($oldPropertyData == null){
                    $validator->errors()->add('rp_td_no', 'No Td found!');
                }
                if(isset($data['cb_all_quarter_paid']) && $data['cb_all_quarter_paid'] != 1){

                if($data['cb_covered_from_year'] < date("Y") && $data['sd_mode_to'] != 44){
                    if($data['cb_covered_to_year'] < date("Y")){
                        $validator->errors()->add('sd_mode_to', "You cannot pay quartely for previous or prior years!");
                    }
                    
                }
                 $allQtrs = Helper::billing_quarters();
                 //dd($allQtrs);
                 $currentQtr = $this->findSdMode(date("n"));
                if($data['cb_covered_to_year'] <= date("Y") && $data['sd_mode_to'] < $currentQtr){
                    $validator->errors()->add('sd_mode_to', "You can only pay till or next to ".$allQtrs[$currentQtr]);
                }
                
            }
               // dd($data);
        });
         $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            return response()->json($arr);
        }

        if($request->has('compute_for_discount') && $request->compute_for_discount == ""){
            return response()->json([
                'status' => 'for_discount',
                'msg'    => 'Do you want to compute for discount?'
            ]);
        }
         
         $getBasicRates = $this->_ctobilling->getBasicRates($request->pc_class_code);
         $propDetails   = RptProperty::with(['propertyKindDetails','barangay'])->where('id',$request->rp_code)->first();
         //dd($propDetails);
         if($getBasicRates == null){
            return response()->json([
                'status' => 'error',
                'msg'    => 'No tax rate set for this class!'
            ]);
         }
         if($request->has('cb_all_quarter_paid') && $request->cb_all_quarter_paid == 1){
            $request->request->add(['sd_mode' => 11,'sd_mode_to' => 44]);
         }

         //dd($request->all());
         $annualData = [];
         $quarterData = [];
         $advanceYearData = [];
         $noOfYears   = [];
         for ($i=$request->cb_covered_from_year; $i <= $request->cb_covered_to_year ; $i++) { 
             if($request->has('sd_mode') && $request->sd_mode != '' && $request->has('sd_mode_to') && $request->sd_mode_to != ''){
            $sdMode = $request->sd_mode;
            $sdModeTo = $request->sd_mode_to;
            if($sdMode == 11 && $sdModeTo == 44){
                $request->cb_all_quarter_paid = 1;
                $sdModes      = [14];
                $noOfYears[$i] = $sdModes;
                if($i <= date("Y")){
                    $annualData[] = ['year'=>$i,'sd_modes'=>$sdModes];
                }
                if($i > date("Y")){
                    $advanceYearData[] = ['year'=>$i,'sd_modes'=>$sdModes];
                }
                
            }else{
                $request->cb_all_quarter_paid = 0;
                $allModes = Helper::billing_quarters();
                $fromIndex = array_search($request->sd_mode,array_keys($allModes));
                $toIndex   = array_search($request->sd_mode_to,array_keys($allModes));
                if(($request->cb_covered_to_year-$request->cb_covered_from_year) != 0){
                    if($i != $request->cb_covered_from_year && $i != $request->cb_covered_to_year){
                        //$noOfYears[$i][] = 14;
                        $annualData[] = ['year'=>$i,'sd_modes'=>[14]];
                    }else{
                        if($i == $request->cb_covered_from_year){
                            if($request->sd_mode == 11){
                                $sdModes      = [14];
                                $noOfYears[$i] = $sdModes;
                                if($i <= date("Y")){
                                $annualData[] = ['year'=>$i,'sd_modes'=>$sdModes];
                            }
                            if($i > date("Y")){
                                 $advanceYearData[] = ['year'=>$i,'sd_modes'=>$sdModes];
                               }
                            }else{
                                $sdModes = array_slice(array_keys($allModes),$fromIndex);
                                $noOfYears[$i] = $sdModes;
                                $quarterData[] = ['year'=>$i,'sd_modes'=>$sdModes];
                            }
                            
                        }else{
                            if($request->sd_mode_to == 44){
                                $sdModes      = [14];
                                $noOfYears[$i] = $sdModes;
                                if($i <= date("Y")){
                                $annualData[] = ['year'=>$i,'sd_modes'=>$sdModes];
                            }
                            if($i > date("Y")){
                                 $advanceYearData[] = ['year'=>$i,'sd_modes'=>$sdModes];
                               }
                            }else{
                                $sdModes = array_slice(array_keys($allModes),0,$toIndex+1);
                                $noOfYears[$i] = $sdModes;
                                $quarterData[] = ['year'=>$i,'sd_modes'=>$sdModes];
                            }
                            
                        }

                    }
                    
                }else{
                    $sdModes       = array_slice(array_keys($allModes),$fromIndex,($fromIndex == 0)?$toIndex+1:$toIndex);
                    $noOfYears[$i] = $sdModes;
                    $quarterData[] = ['year'=>$i,'sd_modes'=>$sdModes];
                }
               
                
            }
         }
         }
         //dd($annualData);
         $quarterData[] = $annualData;
         $quarterData[] = $advanceYearData;
         $allModesData = DB::table('schedule_descriptions')->select('id','sd_mode')->where('is_active',1)->get();
         $allData = [];
         $pendingBillingResult = $this->checkPendingBills($propDetails->id,$request->cb_covered_from_year,$request->sd_mode);
            if(isset($pendingBillingResult['status']) && $pendingBillingResult['status']){
                return response()->json([
                    'status' => 'pending',
                    'msg'    => $pendingBillingResult['msg'],
                    'year'   => (isset($pendingBillingResult['year']))?$pendingBillingResult['year']:'',
                    'qtr'    => (isset($pendingBillingResult['qtr']))?$pendingBillingResult['qtr']:'',
                ]);
            }
         /*$exisResult = $this->checkBillingExistance($propDetails->id,$noOfYears);
            if($exisResult['status']){
                //break;
                return response()->json([
                    'status' => 'error',
                    'msg'    => $exisResult['msg']
                ]);
            }*/
            $stopouterLoops = 0;
         foreach ($quarterData as $data) {
            if(!empty($data) && $stopouterLoops == 0){
            if(isset($data['year'])){
                $allQuarterPaid = 0;
                $data = [$data];
            }else{
                $allQuarterPaid = 1;
            }
            //dd($data[0]['year']);
            $fromYear = $data[0]['year'];
            $toYear   = end($data)['year'];
             $billingData = [
            'rp_property_code' => $propDetails->rp_property_code,
            'rp_code' => $propDetails->id,
            'pk_code' => $propDetails->propertyKindDetails->pk_code,
            'pk_id'   => $propDetails->propertyKindDetails->id,
            'rvy_revision_year' => $propDetails->rvy_revision_year_id,
            'rvy_revision_code' => $propDetails->rvy_revision_code,
            'brgy_code' => $propDetails->brgy_code_id,
            'brgy_no' => $propDetails->barangay->brgy_code,
            'rp_td_no' => $propDetails->rp_td_no,
            'rp_suffix' => $propDetails->rp_suffix, 
            'rp_tax_declaration_no' => $propDetails->rp_tax_declaration_no,
            'cb_billing_mode' => $request->cb_billing_mode,
            'cb_control_year' => date("Y"), 
            'rpo_code' => ($request->cb_billing_mode == 1)?$request->rpo_code_for_multiple:$request->rpo_code,
            'cb_control_no' => ($request->has('control_number_for_multiple'))?$request->control_number_for_multiple:'',
            'transaction_id' => ($request->has('txn_id_for_multiple'))?$request->txn_id_for_multiple:'',
            'transaction_no' => ($request->has('txn_no_for_multiple'))?$request->txn_no_for_multiple:'',
            'cb_covered_from_year' => $fromYear,
            'cb_covered_to_year' => $toYear,
            'cb_assessed_value' => $request->cb_assessed_value,
            'cb_billing_date' => date("Y-m-d"),
            'cb_is_paid' => 0,
            'cb_all_quarter_paid' => $allQuarterPaid,
            'cb_billing_notes' => '',
            'cb_certified_by' => \Auth::user()->id,
            'cb_certified_by_position' => '',
            'created_at' => date("Y-m-h H:i:s"),
            'updated_at' => date("Y-m-h H:i:s")
         ];
         /* Check TD is eligible for SEF and SHT */
         $sefEligibility = (isset($propDetails->revisionYearDetails->has_tax_sef))?$propDetails->revisionYearDetails->has_tax_sef:0;
         $shEligibility = (isset($propDetails->revisionYearDetails->has_tax_sh))?$propDetails->revisionYearDetails->has_tax_sh:0;
         /* Check TD is eligible for SEF and SHT */
         $billingDetailsData = [];

         foreach ($data as $key=>$year) {
            if($stopouterLoops == 0){
            foreach ($year['sd_modes'] as $key => $mode) {
         $penalityEligibility = 0;
         $penalityRate        = 0;
         $discountEligibility = 0;
         $discountRate        = 0;

         $basicAmount         = 0;
         $basicInterst        = 0;
         $basicDiscount       = 0;
         $sefAmount           = 0;
         $sefInterst          = 0;
         $sefDisc             = 0;
         $shAmount            = 0;
         $shInterst           = 0;
         $shDisc              = 0;
            //dd($key);
         $sdModeId = $allModesData->where('sd_mode',$mode)->first();
             /* Calculate Basic, SH, and SEF Amount */
             //dd($propDetails);
         $newPropObj = $this->_ctobilling->getGrAndPreviousOwnerData($propDetails->rp_property_code,$propDetails->id)->where('rp_app_effective_year','<=',$year['year'])->last();
         $assessedValue = (isset($newPropObj->rp_assessed_value))?$newPropObj->rp_assessed_value:$request->cb_assessed_value;
         if($getBasicRates != null){
            $basicAmount = $assessedValue*($getBasicRates->bsst_basic_rate/100);
            $sefAmount   = ($sefEligibility)?$assessedValue*($getBasicRates->bsst_sef_rate/100):0;
            $shtMax      = (isset($getBasicRates->assessed_value_max_amount) && $getBasicRates->assessed_value_max_amount > 0)?$getBasicRates->assessed_value_max_amount:0;
            $shAmount    = ($assessedValue > $shtMax)?(($shEligibility)?$assessedValue*($getBasicRates->bsst_sh_rate/100):0):0;

         }
         if(isset($sdModeId->sd_mode) && $sdModeId->sd_mode != 14){
            $basicAmount = $basicAmount/4;
            $sefAmount = $sefAmount/4;
            $shAmount = $shAmount/4;
         }
         /* Calculate Basic, SH and SEF Amount */

         /* Get penality and discount due date and rates */
         if($sdModeId != null){
            if($year['year'] > date("Y")){
                $penelityDueDateData = $this->_ctobilling->getPaymentScheduledData($year['year'],$sdModeId->id);
            }else{
                $penelityDueDateData = $this->_ctobilling->getPaymentScheduledData($year['year'],$sdModeId->id);
            }
            
            $getPenalityRateDate = $this->_ctobilling->getPenalityRateData($year['year'],$sdModeId->id);
             if(isset($getPenalityRateDate->cps_penalty_limitation) && $getPenalityRateDate->cps_penalty_limitation != 1){
                if($year['year'] >= date("Y")){
             if(isset($penelityDueDateData->rcpsched_penalty_due_date) && $penelityDueDateData->rcpsched_penalty_due_date != ''  && $request->compute_for_penalty == 1){
                 if(Carbon::today()->gt($penelityDueDateData->rcpsched_penalty_due_date)){
                    
                    if($getPenalityRateDate != null){
                        $penalityRate        = $getPenalityRateDate->cps_penalty_rate;
                        $penalityEligibility = 1;
                     }
                    
                 }
                
             }
         }else{
            if($getPenalityRateDate != null){
                        $penalityRate        = $getPenalityRateDate->cps_penalty_rate;
                        $penalityEligibility = 1;
                     }
         }
         }else{
            if($getPenalityRateDate != null){
                        $penalityRate        = $getPenalityRateDate->cps_maximum_penalty;
                        $penalityEligibility = 1;
                     }
         }
             /*if($year['year'] == '2023'){
                dd($getPenalityRateDate);
             }*/
             if(isset($penelityDueDateData->rcpsched_discount_due_date) && $penelityDueDateData->rcpsched_discount_due_date != '' && $request->compute_for_discount == 1){
                if(Carbon::today()->lte($penelityDueDateData->rcpsched_discount_due_date)){
                    $discountRate  = $penelityDueDateData->rcpsched_discount_rate;
                    $discountEligibility = 1;
                }
             }
         }
          /* Get penality and discount due date and rates */
          $exeData = $this->_ctobilling->checkExceptionForCurrentYear($propDetails->rp_property_code);
          if(isset($exeData['status']) && $exeData['status'] == true){
             if($year['year'] == $exeData['lastYear']){
                $penalityRate = (isset($exeData['penaltyRate']))?$exeData['penaltyRate']:$penalityRate;
             }
            
          }
        
         /* Calculate Basic interst/penality */
         if($penalityEligibility == 1){
            $basicInterst = $basicAmount*($penalityRate/100);
            $sefInterst   = $sefAmount*($penalityRate/100);
            $shInterst   = $shAmount*($penalityRate/100);
         }
         /* Calculate Basic interst/penality */

         /* Calculate Basic Discount */
         if($discountEligibility == 1 && $key == 0){
            $basicDiscount = $basicAmount*($discountRate/100);
            $sefDisc = $sefAmount*($discountRate/100);
            $shDisc   = $shAmount*($discountRate/100);
         }
         /* Calculate Basic Discount */

         /* Calculate Total Amount Due */
         $totalAmountDue = ($basicAmount+$basicInterst)-$basicDiscount+($sefAmount+$sefInterst)-$sefDisc+($shAmount+$shInterst)-$shDisc;
         /* Calculate Total Amount Due */
         $currentQtr  = $this->findSdMode(date('n'));
         $allPossQtrs = ['11' => '1','22' => '2', '33' => '3', '44' => '4'];
         //dd($currentQtr);
         //$savedBillingDetails = $this->_ctobilling->find($lastInsertedId);
          $dataTOsaveInBillingDetails = [
            'rp_property_code' => $propDetails->rp_property_code,
            'rp_code' => $propDetails->id,
            'cb_control_year' => $billingData['cb_control_year'],
            'cbd_covered_year' => $year['year'],
            'sd_mode' => $mode,
            'rpo_code' => $request->rpo_code,
            'cbd_assessed_value' => $assessedValue,
            'cbd_basic_amount' => $basicAmount,
            'cbd_basic_penalty' => $basicInterst,
            'cbd_basic_discount' => $basicDiscount,
            'cbd_sef_amount' => $sefAmount,
            'cbd_sef_penalty' => $sefInterst,
            'cbd_sef_discount' => $sefDisc,
            'cbd_sh_amount' => $shAmount,
            'cbd_sh_penalty' => $shInterst,
            'cbd_sh_discount' => $shDisc,
            'cbd_amount_due'  => $totalAmountDue,
            'cbd_is_paid' => 0,
            'basic_discount_rate'  => $discountRate,
            'basic_penalty_rate'    => $penalityRate,
            'cbd_registered_by' => \Auth::user()->creatorId(),
            'created_at' => date("Y-m-d H:i:s"),
            'isPenalty' => $penalityEligibility,
            'isDiscount' => $discountEligibility
          ];
        $checkBillExis = DB::table('rpt_cto_billing_details')
                              ->where('rp_property_code',$propDetails->rp_property_code)
                              ->where('cbd_covered_year',$year['year'])
                              ->where('sd_mode',$mode)
                              ->first();
        if($checkBillExis != null){
            $stopouterLoops = 1;
            break;
        }
            $billingDetailsData[] = (object)$dataTOsaveInBillingDetails;
      }
  }
      
         }
         $billingData['billingDetails'] = $billingDetailsData;
         $allData[] = (object)$billingData;
         }
     }
            $request->session()->put('billingTempData', $allData);  
            //dd(session()->get('billingTempData'));                  
        return response()->json([
                'status' => 'success',
                'msg'    => 'Bill generated successfully!'
            ]);

    }


    public function collectDataForBasicDetails($detail,$taxTrevId,$taxRevenueYear){
        $propDetails = DB::table('rpt_properties')->select('pk_id')->where('id',$detail->rp_code)->first();
        $revenueCodeDetails = $this->_ctobilling->getRevenueCodeDetails($taxTrevId,$taxRevenueYear,$propDetails->pk_id);          /* Save basic Details */
                    $basicDetails = [
                        'trevs_id' => $taxTrevId,
                        'tax_revenue_year' => $taxRevenueYear,
                        'basic_tfoc_id' => (isset($revenueCodeDetails->basic_tfoc_id))?$revenueCodeDetails->basic_tfoc_id:0,
                        'basic_gl_id' => (isset($revenueCodeDetails->basic_gl_id))?$revenueCodeDetails->basic_gl_id:0,
                        'basic_sl_id' => (isset($revenueCodeDetails->basic_sl_id))?$revenueCodeDetails->basic_sl_id:0,
                        'basic_amount' => $detail->cbd_basic_amount,
                        'sef_amount'  => $detail->cbd_sef_amount,
                        'sef_tfoc_id' => (isset($revenueCodeDetails->sef_tfoc_id))?$revenueCodeDetails->sef_tfoc_id:0,
                        'sef_gl_id' => (isset($revenueCodeDetails->sef_gl_id))?$revenueCodeDetails->sef_gl_id:0,
                        'sef_sl_id' => (isset($revenueCodeDetails->sef_sl_id))?$revenueCodeDetails->sef_sl_id:0,
                        'sh_amount' => $detail->cbd_sh_amount,
                        'sh_tfoc_id' => (isset($revenueCodeDetails->sh_tfoc_id))?$revenueCodeDetails->sh_tfoc_id:0,
                        'sh_gl_id' => (isset($revenueCodeDetails->sh_gl_id))?$revenueCodeDetails->sh_gl_id:0,
                        'sh_sl_id' => (isset($revenueCodeDetails->sh_sl_id))?$revenueCodeDetails->sh_sl_id:0,
                        'basic_total_due' => $detail->cbd_basic_amount+$detail->cbd_sef_amount+$detail->cbd_sh_amount,
                    ];
                    return $basicDetails;
    }

    public function collectDataForDiscountDetails($detail,$taxTrevId,$taxRevenueYear){
        $propDetails = DB::table('rpt_properties')->select('pk_id')->where('id',$detail->rp_code)->first();
        $revenueCodeDetails = $this->_ctobilling->getRevenueCodeDetails($taxTrevId,$taxRevenueYear,$propDetails->pk_id);
                    /* Save basic Details */
                    $basicDetails = [
                        'trevs_id' => $taxTrevId,
                        'tax_revenue_year' => $taxRevenueYear,
                        'basic_discount_tfoc_id' => (isset($revenueCodeDetails->basic_discount_tfoc_id))?$revenueCodeDetails->basic_discount_tfoc_id:0,
                        'basic_discount_gl_id' => (isset($revenueCodeDetails->basic_d_gl_id))?$revenueCodeDetails->basic_d_gl_id:0,
                        'basic_discount_sl_id' => (isset($revenueCodeDetails->basic_d_sl_id))?$revenueCodeDetails->basic_d_sl_id:0,
                        'basic_discount_rate' => $detail->basic_discount_rate,
                        'basic_discount_amount' => $detail->cbd_basic_discount,
                        'sef_discount_tfoc_id' => (isset($revenueCodeDetails->sef_discount_tfoc_id))?$revenueCodeDetails->sef_discount_tfoc_id:0,
                        'sef_discount_gl_id' => (isset($revenueCodeDetails->sef_d_gl_id))?$revenueCodeDetails->sef_d_gl_id:0,
                        'sef_discount_sl_id' => (isset($revenueCodeDetails->sef_d_sl_id))?$revenueCodeDetails->sef_d_sl_id:0,
                        'sef_discount_amount'  => $detail->cbd_sef_discount,
                        'sef_discount_rate' => $detail->basic_discount_rate,
                        'sh_discount_tfoc_id' => (isset($revenueCodeDetails->sh_discount_tfoc_id))?$revenueCodeDetails->sh_discount_tfoc_id:0,
                        'sh_discount_gl_id' => (isset($revenueCodeDetails->sh_d_gl_id))?$revenueCodeDetails->sh_d_gl_id:0,
                        'sh_discount_sl_id' => (isset($revenueCodeDetails->sh_d_sl_id))?$revenueCodeDetails->sh_d_sl_id:0,
                        'sh_discount_amount'  => $detail->cbd_sh_discount,
                        'sh_discount_rate' => $detail->basic_discount_rate,
                        'dicount_total_due' => $detail->cbd_basic_discount+$detail->cbd_sef_discount+$detail->cbd_sh_discount,
                    ];
                    return $basicDetails;
    }

    public function collectDataForPenaltyDetails($detail,$taxTrevId,$taxRevenueYear){
        $propDetails = DB::table('rpt_properties')->select('pk_id')->where('id',$detail->rp_code)->first();
        $revenueCodeDetails = $this->_ctobilling->getRevenueCodeDetails($taxTrevId,$taxRevenueYear,$propDetails->pk_id);
                    /* Save basic Details */
                    $basicDetails = [
                        'trevs_id' => $taxTrevId,
                        'tax_revenue_year' => $taxRevenueYear,
                        'basic_penalty_tfoc_id' => (isset($revenueCodeDetails->basic_penalty_tfoc_id))?$revenueCodeDetails->basic_penalty_tfoc_id:0,
                        'basic_penalty_gl_id' => (isset($revenueCodeDetails->basic_p_gl_id))?$revenueCodeDetails->basic_p_gl_id:0,
                        'basic_penalty_sl_id' => (isset($revenueCodeDetails->basic_p_sl_id))?$revenueCodeDetails->basic_p_sl_id:0,
                        'basic_penalty_rate' => $detail->basic_penalty_rate,
                        'basic_penalty_amount' => $detail->cbd_basic_penalty,
                        'sef_penalty_tfoc_id' => (isset($revenueCodeDetails->sef_penalty_tfoc_id))?$revenueCodeDetails->sef_penalty_tfoc_id:0,
                        'sef_penalty_gl_id' => (isset($revenueCodeDetails->sef_p_gl_id))?$revenueCodeDetails->sef_p_gl_id:0,
                        'sef_penalty_sl_id' => (isset($revenueCodeDetails->sef_p_sl_id))?$revenueCodeDetails->sef_p_sl_id:0,
                        'sef_penalty_amount'  => $detail->cbd_sef_penalty,
                        'sef_penalty_rate' => $detail->basic_penalty_rate,
                        'sh_penalty_tfoc_id' => (isset($revenueCodeDetails->sh_penalty_tfoc_id))?$revenueCodeDetails->sh_penalty_tfoc_id:0,
                        'sh_penalty_gl_id' => (isset($revenueCodeDetails->sh_p_gl_id))?$revenueCodeDetails->sh_p_gl_id:0,
                        'sh_penalty_sl_id' => (isset($revenueCodeDetails->sh_p_sl_id))?$revenueCodeDetails->sh_p_sl_id:0,
                        'sh_penalty_amount'  => $detail->cbd_sh_penalty,
                        'sh_penalty_rate' => $detail->basic_penalty_rate,
                        'penalty_total_due' => $detail->cbd_basic_penalty+$detail->cbd_sef_penalty+$detail->cbd_sh_penalty,
                    ];
                    return $basicDetails;
    }

    public function deleteRow(Request $request){
        $id = $request->input('id');
        $getcontrolno = $this->_ctobilling->getcontrolno($id);
        $this->_ctobilling->deleteBillingsrow($id);
        $this->_ctobilling->deleteBillingDetailsrow($id);
        $this->_ctobilling->deleteBillingDiscountrow($id);
        $this->_ctobilling->deleteBillingPenaltyrow($id);
        $checkrecordcontrolno = $this->_ctobilling->getcountofcontrolno($getcontrolno->cb_control_no);
        if(count($checkrecordcontrolno) == 0){
            $this->_ctobilling->deleteTopTransactionrow($getcontrolno->transaction_id);
        }
    }

    public function genrateBill($value=''){
        $sessionData = collect(session()->get('billingTempData'));
        if($sessionData->isempty()){
             return response()->json([
                'status' => 'error',
                'msg'    => 'No data found to generate bill, Please create bill then try again!'
            ]);
        }
        $controlNoForMultiple = '';
        $txnIdForMultiple     = '';
        $txnNoForMultiple     = '';
        $totalAmountToPay     = 0;
        $topNosForSms         = [];
         foreach ($sessionData as $data) {
             $dataToSaveinBilling = (array)$data;
             $dataToSaveInDetails = (array)$dataToSaveinBilling['billingDetails'];
             unset($dataToSaveinBilling['billingDetails']);
             if($dataToSaveinBilling['cb_billing_mode'] == 1 && $dataToSaveinBilling['cb_control_no'] == null){
                $dataToSaveinBilling['cb_control_no'] = $controlNoForMultiple;
             }
             $lastinsertid = $this->_ctobilling->addData($dataToSaveinBilling);
             if($dataToSaveinBilling['cb_control_no'] == null ){
                $this->generateControlNo($lastinsertid);
                $collectionOfDetails = collect($dataToSaveInDetails);
             /* Save data in cto_top_transactions */
             $topTxnData = [
                'top_transaction_type_id' => 3,
                'transaction_ref_no' => $lastinsertid,
                'tfoc_is_applicable' => 2,
                'amount' => $collectionOfDetails->sum('cbd_amount_due'),
                'is_paid' => 0,
                'created_by' => \Auth::user()->id,
                'created_at' => date("Y-m-d H:i:s")

             ];
             $totalAmountToPay +=  $collectionOfDetails->sum('cbd_amount_due');
             //dd($topTxnData);
             $trans_id = $this->_ctobilling->addTopTransactions($topTxnData);
             $transaction_no = str_pad($trans_id, 6, '0', STR_PAD_LEFT);
             $topNosForSms[] = $transaction_no;
             $this->_ctobilling->updateTopTransactions($trans_id,array("transaction_no"=>$transaction_no));
             $this->_ctobilling->updateData($lastinsertid,['transaction_id'=>$trans_id,'transaction_no'=>$transaction_no]);
             /* Save data in cto_top_transactions */
             }
             $savedBillingDetails = $this->_ctobilling->find($lastinsertid);
             $controlNoForMultiple = $savedBillingDetails->cb_control_no;
             foreach ($dataToSaveInDetails as $detail) {
                 
                 $commonDetails = [
                    'cb_code' => $savedBillingDetails->id,
                    'cb_control_year' => $savedBillingDetails->cb_control_year,
                    'rp_property_code' => $detail->rp_property_code,
                    'rp_code' => $detail->rp_code,
                    'cbd_covered_year' => $detail->cbd_covered_year,
                    'sd_mode' => $detail->sd_mode,
                    'rpo_code' => $detail->rpo_code,
                    'cbd_assessed_value' => $detail->cbd_assessed_value,
                    'cbd_is_paid' => $detail->cbd_is_paid,
                    'created_at' => $detail->created_at
                 ];
                 if($detail->cbd_covered_year > date("Y")){ //Advance Year
                    //dd($detail);
                    $taxRevenueYear = 1;
                    $taxTrevId =  1;
                    if($detail->isDiscount == 1){
                        $discountDetails = $this->collectDataForDiscountDetails($detail,$taxTrevId,$taxRevenueYear);
                        $mergedDiscountDetails = array_merge($commonDetails,$discountDetails);
                        $this->_ctobilling->addBillingDetailsDiscountData($mergedDiscountDetails);
                    }
                    $basicDetails = $this->collectDataForBasicDetails($detail,$taxTrevId,$taxRevenueYear);
                    $mergedBasicDetails = array_merge($commonDetails,$basicDetails);
                    $this->_ctobilling->addBillingDetailsData($mergedBasicDetails);
                    /* Save basic Details */
                 }else if($detail->cbd_covered_year < date("Y")-1){ //Prior Years
                    $taxRevenueYear = 4;
                    $taxTrevId      = 4;
                    if($detail->isPenalty == 1){
                        $penaltyDetails = $this->collectDataForPenaltyDetails($detail,$taxTrevId,$taxRevenueYear);
                        $mergedPenaltyDetails = array_merge($commonDetails,$penaltyDetails);
                        $this->_ctobilling->addBillingDetailsPenaltyData($mergedPenaltyDetails);
                    }
                    $basicDetails = $this->collectDataForBasicDetails($detail,$taxTrevId,$taxRevenueYear);
                    $mergedBasicDetails = array_merge($commonDetails,$basicDetails);
                    $this->_ctobilling->addBillingDetailsData($mergedBasicDetails);
                    /* Save basic Details */
                 }else if($detail->cbd_covered_year == date("Y")-1){ //Previous Years
                    $taxRevenueYear = 3;
                    $taxTrevId =  3;
                    if($detail->isPenalty == 1){
                        //$taxTrevId = 7;
                        $penaltyDetails = $this->collectDataForPenaltyDetails($detail,$taxTrevId,$taxRevenueYear);
                        $mergedPenaltyDetails = array_merge($commonDetails,$penaltyDetails);
                        $this->_ctobilling->addBillingDetailsPenaltyData($mergedPenaltyDetails);

                    }
                    $basicDetails = $this->collectDataForBasicDetails($detail,$taxTrevId,$taxRevenueYear);
                    $mergedBasicDetails = array_merge($commonDetails,$basicDetails);
                    $this->_ctobilling->addBillingDetailsData($mergedBasicDetails);
                    /* Save basic Details */
                 }else{     //Current Year
                    $taxRevenueYear = 2;
                    $taxTrevId =  2;
                    $basicDetails = $this->collectDataForBasicDetails($detail,$taxTrevId,$taxRevenueYear);
                    $mergedBasicDetails = array_merge($commonDetails,$basicDetails);
                    $this->_ctobilling->addBillingDetailsData($mergedBasicDetails);
                    /* Save basic Details */
                    if($detail->isDiscount == 1){
                        //$taxTrevId = 4;
                        $discountDetails = $this->collectDataForDiscountDetails($detail,$taxTrevId,$taxRevenueYear);
                        $mergedDiscountDetails = array_merge($commonDetails,$discountDetails);
                        $this->_ctobilling->addBillingDetailsDiscountData($mergedDiscountDetails);
                    }
                    if($detail->isPenalty == 1){
                        //$taxTrevId = 5;
                        $penaltyDetails = $this->collectDataForPenaltyDetails($detail,$taxTrevId,$taxRevenueYear);
                        $mergedPenaltyDetails = array_merge($commonDetails,$penaltyDetails);
                        $this->_ctobilling->addBillingDetailsPenaltyData($mergedPenaltyDetails);

                    }
                    
                 }
             }
             $this->uploadPdf($savedBillingDetails->id);
         }
       
         $this->sendSmsBilling($savedBillingDetails->id);           
         //dd($savedBillingDetails);
         session()->forget('billingTempData');
         return response()->json([
                'status' => 'success',
                'msg'    => 'Bill generated successfully!',
                'cno'    => $controlNoForMultiple,
                'txnNo'  =>  $savedBillingDetails->transaction_id
            ]);
    }

    public function sendSmsBilling($id=''){
       $billingData = RptCtoBilling::with(['rptProperty','billingDetails.billingPenaltyDetails','billingDetails.billingDiscountDetails'])
        ->addSelect(
                    [
                        'totalBasicDue' => RptCtoBillingDetail::select(DB::raw("SUM(basic_total_due) AS totalBasicDue"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id'),
                        'totalPenaltyDue' => RptCtoBillingDetailsPenalty::select(DB::raw("SUM(penalty_total_due)"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id') ,
                        'totalDiscountDue' => RptCtoBillingDetailsDiscount::select(DB::raw("SUM(dicount_total_due)"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')            
                ]
            )
        ->where('rpt_cto_billings.id',$id)
        ->get();
        $totalTaXDue = $billingData->sum('totalBasicDue');
        $totalPenaltyDue = $billingData->sum('totalPenaltyDue');
        $totalDiscountDue = $billingData->sum('totalDiscountDue');
        $subTotal = $totalTaXDue+$totalPenaltyDue;
        $netTaxDue = $subTotal-$totalDiscountDue;
        $rpCode = (isset($billingData[0]->rp_code))?$billingData[0]->rp_code:0;
        $rpoCode = (isset($billingData[0]->rpo_code))?$billingData[0]->rpo_code:0;
        $topNo = (isset($billingData[0]->transaction_no))?$billingData[0]->transaction_no:0;
        $clientDetails = DB::table('rpt_properties as rp')
                       ->select('rp.pk_id','c.full_name','c.p_mobile_no','rpk.pk_description','rp.rp_tax_declaration_no')
                       ->join('clients as c','c.id','=','rp.rpo_code')
                       ->join('rpt_property_kinds as rpk','rpk.id','=','rp.pk_id')
                       ->where('rp.id',$rpCode)
                       ->first();
        $clientNew = DB::table('clients as c')
                       ->select('c.full_name','c.p_mobile_no')
                       ->where('c.id',$rpoCode)
                       ->first();             // dd($clientDetails);
                       //dd($topNosForSms);
        $smsTemplate=SmsTemplate::searchBySlug($this->slugs)->first();
         
        if(!empty($smsTemplate) && $clientNew->p_mobile_no != null)
        {
            $receipient = $clientNew->p_mobile_no;
            $msg=$smsTemplate->template;
            $msg = str_replace('<NAME>', $clientNew->full_name,$msg);
            $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
            $msg = str_replace('<PROPERTY_KIND>', $clientDetails->pk_description,$msg);
            $msg = str_replace('<TAX_DECLARATION_NO>', $clientDetails->rp_tax_declaration_no,$msg);
            $msg = str_replace('<TOP_NO>',$topNo,$msg);
            $msg = str_replace('<BILLING_AMOUNT>',Helper::decimal_format($netTaxDue),$msg);
            $msg = preg_replace("/[\n\r]/","\\n", $msg);;
            try {
                $this->send($msg, $receipient);
            } catch (\Exception $e) {
                dd($e);
                 session()->forget('billingTempData');
                 return response()->json([
                        'status' => 'success',
                        'msg'    => 'Bill generated successfully!',
                        'cno'    => $controlNoForMultiple,
                        'txnNo'  =>  $savedBillingDetails->transaction_id
                    ]);
            }
        }
    }

    public function send($message, $receipient)
    {   
        $interface = new ComponentSMSNotificationRepository;
        $validate = $interface->validate();
        if ($validate > 0) {
            $setting = $interface->fetch_setting();
            $details = array(
                'type_id' => 1,
                'action_id' => NULL, // put action id please refer to sms_actions, mark as null if action applicable
                'masking_code' => $setting->mask->code,
                'messages' => $message,
                'created_at' => Carbon::now(),
                'created_by' => \Auth::user()->id
            );
            $message = $interface->create($details);
           
                //$this->sendSms($receipient, $message);
                $interface->send($receipient, $message);

            return true;
        } else {
            return false;
        }
    }

    public function getPropDue($txnNo = '229'){
        $assessedValue = "CASE 
                                        WHEN pk.pk_code = 'L' THEN (SELECT SUM(COALESCE(rpt_property_appraisals.rpa_assessed_value,0)) FROM rpt_property_appraisals WHERE rpt_property_appraisals.rp_code = rp.id)
                                        WHEN pk.pk_code = 'B' THEN rp.rpb_assessed_value
                                        WHEN pk.pk_code = 'M' THEN (SELECT SUM(COALESCE(rpt_property_machine_appraisals.rpm_assessed_value,0)) FROM rpt_property_machine_appraisals WHERE rpt_property_appraisals.rp_code = rp.id) END";
        $marketValue = "CASE 
                                        WHEN pk.pk_code = 'L' THEN (SELECT SUM(COALESCE(rpt_property_appraisals.rpa_adjusted_market_value,0)) FROM rpt_property_appraisals WHERE rpt_property_appraisals.rp_code = rp.id)
                                        WHEN pk.pk_code = 'B' THEN rp.rpb_accum_deprec_market_value
                                        WHEN pk.pk_code = 'M' THEN (SELECT SUM(COALESCE(rpt_property_machine_appraisals.rpma_market_value,0)) FROM rpt_property_machine_appraisals WHERE rpt_property_appraisals.rp_code = rp.id) END";                                
        $billingDetails = DB::table('rpt_cto_billings as cb')
                             ->join('rpt_properties as rp','rp.id','=','cb.rp_code')
                             ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
                             ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rp.id')
                             ->leftJoin('rpt_property_machine_appraisals','rpt_property_machine_appraisals.rp_code','=','rp.id')
                             ->join('rpt_cto_billing_details as cbd','cbd.cb_code','=','cb.id')
                             ->join('cto_top_transactions as ctt','ctt.id','=','cb.transaction_id')
                             ->leftJoin('rpt_cto_billing_details_discounts as cbdd',function($j){
                                 $j->on('cbdd.cb_code','=','cbd.cb_code')
                                  ->on('cbdd.cbd_covered_year','=','cbd.cbd_covered_year')
                                  ->on('cbdd.sd_mode','=','cbd.sd_mode');
                             })
                             ->leftJoin('rpt_cto_billing_details_penalties as cbdp',function($j){
                                 $j->on('cbdp.cb_code','=','cbd.cb_code')
                                  ->on('cbdp.cbd_covered_year','=','cbd.cbd_covered_year')
                                  ->on('cbdp.sd_mode','=','cbd.sd_mode');
                             })
                             ->select(
                                'cbd.cbd_covered_year','cbd.sd_mode','cbd.cbd_assessed_value','cb.rp_code','cb.rp_property_code','cb.rpo_code',
                                'ctt.transaction_no','ctt.attachment','ctt.id','rp.rp_tax_declaration_no','rp.rp_tax_declaration_no','rp.rp_pin_declaration_no','rp.rp_assessed_value as assessedValue','rp.rp_market_value_adjustment as marketValue',
                                /*DB::raw($assessedValue.' as assessedValue'),
                                DB::raw($marketValue.' as marketValue'),*/
                                DB::raw('MAX(cbd.sd_mode) as endQtr'),
                                DB::raw('MIN(cbd.sd_mode) as startQtr'),
                                DB::raw('((SUM(COALESCE(cbd.basic_amount,0))+SUM(COALESCE(cbd.sef_amount,0))+SUM(COALESCE(cbd.sh_amount,0)))+(SUM(COALESCE(cbdp.basic_penalty_amount,0))+SUM(COALESCE(cbdp.sef_penalty_amount,0))+SUM(COALESCE(cbdp.sh_penalty_amount,0)))-(SUM(COALESCE(cbdd.basic_discount_amount,0))+SUM(COALESCE(cbdd.sef_discount_amount,0))+SUM(COALESCE(cbdd.sh_discount_amount,0)))) as totalDue')
                               )
                             ->where('cb.transaction_id',(isset($txnNo))?$txnNo:0)
                             ->groupBy('cb.rp_code')
                             ->get();
        return $billingDetails;
    }

    public function loadMultipleProps(Request $request){
        $paidButon = 1;
        $cno = $request->cb_control_no;
        $multipleBilling  = $this->_ctobilling->with(['rptProperty','billingDetails.billingPenaltyDetails','billingDetails.billingDiscountDetails'])->where('cb_control_no',$cno)->get();
        $multipleBillingDetails = $this->_ctobilling->getBillingDetailsByControlNo($cno);
        //dd($multipleBillingDetails[0]->rptProperty->class_for_kind->pc_class_code);
         /* Check either paid or not*/
        $checkPidOrNot = RptCtoBilling::where('cb_control_no',$cno)->get();
        if(!$checkPidOrNot->isEmpty()){
            $notPaid = $checkPidOrNot->where('cb_is_paid',0);
            if($notPaid->isEmpty()){
                $paidButon = 0;
            }
        }
        $view = view('billingform.ajax.multiplebillinglist',compact('multipleBilling','multipleBillingDetails','paidButon'))->render();
        return response()->json(['view'=>$view,'cno' => $cno]);
    }

    public function computebillingdata(Request $request){
        $billing = collect(session()->get('billingTempData'))->sortBy('cb_covered_from_year');
        //dd($billing);
    	return view('billingform.ajax.compteddata',compact('billing'));
    }

    public function show(Request $request){
        $id = $request->id;
        $billingData = RptCtoBilling::where('rpt_cto_billings.id',$id)->with(['rptProperty','billingDetails.billingPenaltyDetails','billingDetails.billingDiscountDetails'])
        ->join('rpt_cto_billing_details as cbd',function($j){
            $j->on('cbd.cb_code','=','rpt_cto_billings.id')
               ->leftJoin('rpt_cto_billing_details_discounts as cbdd',function($j){
                $j->on('cbdd.cb_code','=','cbd.cb_code')
                  ->on('cbdd.cbd_covered_year','=','cbd.cbd_covered_year')
                  ->on('cbdd.sd_mode','=','cbd.sd_mode');
               });
        })
        ->leftJoin('cto_top_transactions', function($join){
            $join->on('cto_top_transactions.transaction_ref_no','=','rpt_cto_billings.id')
                 ->where('cto_top_transactions.tfoc_is_applicable',2);
        })
        ->select(
            'rpt_cto_billings.*',
            DB::raw('(COALESCE(cbdd.basic_discount_amount,0)+COALESCE(cbdd.sef_discount_amount,0)+COALESCE(cbdd.sh_discount_amount,0)) as discount'),
            'cto_top_transactions.transaction_no as new_transaction_no')
        ->first();
        $billingDetails = DB::table('rpt_cto_billing_details as cbd')
                             ->leftJoin('rpt_cto_billing_details_discounts as cbdd',function($j){
                                 $j->on('cbdd.cb_code','=','cbd.cb_code')
                                  ->on('cbdd.cbd_covered_year','=','cbd.cbd_covered_year')
                                  ->on('cbdd.sd_mode','=','cbd.sd_mode');
                             })
                             ->leftJoin('rpt_cto_billing_details_penalties as cbdp',function($j){
                                 $j->on('cbdp.cb_code','=','cbd.cb_code')
                                  ->on('cbdp.cbd_covered_year','=','cbd.cbd_covered_year')
                                  ->on('cbdp.sd_mode','=','cbd.sd_mode');
                             })
                             ->select(
                                'cbd.cbd_covered_year','cbd.sd_mode','cbd.cbd_assessed_value',
                                DB::raw('COALESCE(cbd.basic_amount,0) as basicAmount'),
                                DB::raw('COALESCE(cbdd.basic_discount_amount,0) as basicDiscount'),
                                DB::raw('COALESCE(cbdp.basic_penalty_amount,0) as basicPenalty'),

                                DB::raw('COALESCE(cbd.sef_amount,0) as sefAmount'),
                                DB::raw('COALESCE(cbdd.sef_discount_amount,0) as sefDiscount'),
                                DB::raw('COALESCE(cbdp.sef_penalty_amount,0) as sefPenalty'),

                                DB::raw('COALESCE(cbd.sh_amount,0) as shAmount'),
                                DB::raw('COALESCE(cbdd.sh_discount_amount,0) as shDiscount'),
                                DB::raw('COALESCE(cbdp.sh_penalty_amount,0) as shPenalty'),

                                DB::raw('((COALESCE(cbd.basic_amount,0)+COALESCE(cbd.sef_amount,0)+COALESCE(cbd.sh_amount,0))+(COALESCE(cbdp.basic_penalty_amount,0)+COALESCE(cbdp.sef_penalty_amount,0)+COALESCE(cbdp.sh_penalty_amount,0))-(COALESCE(cbdd.basic_discount_amount,0)+COALESCE(cbdd.sef_discount_amount,0)+COALESCE(cbdd.sh_discount_amount,0))) as totalDue')
                               )
                             ->where('cbd.cb_code',(isset($billingData->id))?$billingData->id:0)
                             ->get();
        
        return view('billingform.ajax.show',compact('billingData','billingDetails'));
    } 

    public function printBill($id = '',$callFrom = 'user'){
        $billDetails = RptCtoBilling::where('id',$id)->with(['rptProperty','billingDetails.billingPenaltyDetails','billingDetails.billingDiscountDetails'])->first();
		/* echo'<pre>';
		print_r($billDetails);die; */
        $billDetailsForPrint = $this->_ctobilling->getBillingDetails($id);
         //dd($billDetailsForPrint);
        if($billDetails->rptProperty->propertyKindDetails->pk_code == "L"){
                    $classId = $billDetails->rptProperty->landAppraisals[0]->class->id;
                    $class = $billDetails->rptProperty->landAppraisals[0]->class->pc_class_code;
                    $actualUse = $billDetails->rptProperty->landAppraisals[0]->actualUses->pau_actual_use_code;
                }if($billDetails->rptProperty->propertyKindDetails->pk_code == "B"){
                    $classId = $billDetails->rptProperty->propertyClass->id;
                    $class = $billDetails->rptProperty->propertyClass->pc_class_code;
                    $actualUse = $billDetails->rptProperty->floorValues[0]->actualUses->pau_actual_use_code;
                }if($billDetails->rptProperty->propertyKindDetails->pk_code == "M"){
                    $classId = $billDetails->rptProperty->machineAppraisals[0]->class->id;
                    $class = $billDetails->rptProperty->machineAppraisals[0]->class->pc_class_code;
                    $actualUse = $class;
                }
        $processedBy = User::find($billDetails->cb_certified_by);
        // dd($processedBy);
        $topNo=RptCtoBilling::where('cb_control_no',$billDetails->cb_control_no)->first();
       // dd($topNo);
       $Barangay ="";
       $owner ="";
       $RptLocality = "";
       $verified_by ="";
       $processedBy ="";

       if (isset($topNo)) {
           $owner = RptPropertyOwner::where('id',$topNo->rpo_code)->first();
           $processedBy = HrEmployee::where('user_id',$topNo->cb_certified_by)->first();
           $processedPosition = HrDesignation::where('id',$processedBy->hr_designation_id)->first();
           // dd($processedPosition->description);
           $Barangay = Barangay::where('id',$topNo->brgy_code)->first();
            if (isset($Barangay)) {
             $RptLocality = RptLocality::where('mun_no',$Barangay->mun_no)->where('department',1)->first();
             $verified_by = HrEmployee::where('id',$RptLocality->loc_treasurer_id)->first();
            }
        }
        
        $lastPayment =  $this->_ctobilling->getLastPaymentDetails($billDetails->rptProperty->id); 
        //dd($lastPayment);   
        $rates = $this->_ctobilling->getBasicRates($classId);
        $data = [
                    'billDetailsForPrint' => $billDetailsForPrint,
                    'billDetails' => $billDetails, 
                    'class' => $class,
                    'actualUse' => $actualUse, 
                    'rates' => $rates, 
                    'processedBy' => $processedBy,
                    'topNo'=>$topNo,
                    'owner'=>$owner,
                    'processedBy'=>$processedBy,
                    'verified_by'=>$verified_by,
                    'RptLocality'=>$RptLocality,
                    'processedPosition'=>$processedPosition, 
                    'lastPayment' => $lastPayment
                ];
        //return view('billingform.printbill', $data);
        $documentFileName = "Single-property-billing.pdf";
        $document = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [216, 297]]);
            $document->watermarkImgBehind = true;
            $document->showWatermarkImage = true;
            $document->SetDisplayMode(90);
            $document->AddPage('p','','','','',10,10,10,2,10,0);
        
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
        ];  
        $html = view('billingform.printbill', $data)->render();
        //return view('billingform.printbill',compact('billDetails','class','actualUse','rates','processedBy'));
        // $html_back = view('inquiries.by_apr_no.printFAASBack', $data)->render();

        $document->WriteHTML($html);
        // $document->WriteHTML($html_back);
        if($callFrom == 'dev'){
            $data['document'] = $document;
            return $data;
        }else{
            // Save PDF on your public storage 
            Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "S"));
             
            // Get file back from storage with the give header informations
            return Storage::disk('public')->download($documentFileName, 'Request', $header); //
        }
         
        
        
    }

    public function uploadPdf($id = '32'){
        $billingData = RptCtoBilling::find($id);
        if(isset($billingData->cb_billing_mode) && $billingData->cb_billing_mode == 1){
            $doc = $this->printBillForMultiple($billingData->cb_control_no,'dev');
        }else{
            $doc = $this->printBill($id,'dev');
        }
        //dd($doc);
        $topNo = (isset($billingData->transaction_no))?$billingData->transaction_no:'';
        $filename = $topNo.'.pdf';
        try {
            $destinationPath =  public_path().'/uploads/billing/real-property/';
            if(File::exists($destinationPath.$filename)) { 
                unlink($destinationPath.$filename);
            }
            if(!File::exists($destinationPath)) { 
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $mpdf = $doc['document'];
            $mpdf->Output($destinationPath.$filename,'F'); 
            @chmod($destinationPath.$filename, 0777);
            $columns['attachment'] = $filename;
        } catch (\Exception $e) {
            dd($e);
            
        }
        DB::table('cto_top_transactions')->where('transaction_no',$topNo)->update($columns);
    }
    
    public function printBillForMultiple($id = '',$callFrom = 'user'){
        $partspageNo = explode('=', $id);
        if($callFrom == 'dev'){
            $desiredData = $id;
        }else{
            $pageNo = $partspageNo[1];
        
            $partsId = explode('&', $id);
            $desiredData = $partsId[0];
        }
        
         //dd($desiredData);
        $billDetails = RptCtoBilling::where('cb_control_no',$desiredData)->with(['rptProperty','billingDetails'])
                                                                ->orderby('cb_covered_from_year','DESC')
                                                                ->get();
        // dd($billDetails);
        if(!$billDetails->isEmpty()){
            $processedBy = User::find($billDetails[0]->cb_certified_by);
        }else{
            $processedBy = [];
        }
       $topNo=RptCtoBilling::where('cb_control_no',$desiredData)->first();
        
       $Barangay ="";
       $owner ="";
       $RptLocality = "";
       $verified_by ="";
       $processedBy ="";

       if (isset($topNo)) {
           $owner = RptPropertyOwner::where('id',$topNo->rpo_code)->first();
           $processedBy = HrEmployee::where('user_id',$topNo->cb_certified_by)->first();
           $processedPosition = HrDesignation::where('id',$processedBy->hr_designation_id)->first();
           // dd($processedPosition->description);
           $Barangay = Barangay::where('id',$topNo->brgy_code)->first();
            if (isset($Barangay)) {
             $RptLocality = RptLocality::where('mun_no',$Barangay->mun_no)->where('department',1)->first();
             $verified_by = HrEmployee::where('id',$RptLocality->loc_treasurer_id)->first();
            }
        }
       // dd($RptLocality);
       // dd($verified_by->standard_name);
        //return view('billingform.multiple.printbill',compact('billDetails','processedBy'));
        $data = [
                    'multipleBillingDetails' => $this->_ctobilling->getBillingDetailsByControlNo($desiredData),
                    'billDetails' => $billDetails, 
                    'processedBy' => $processedBy,
                    'topNo'=>$topNo,
                    'owner'=>$owner,
                    'processedBy'=>$processedBy,
                    'verified_by'=>$verified_by,
                    'RptLocality'=>$RptLocality,
                    'pageNo'=>(isset($pageNo))?$pageNo:'',
                    'processedPosition'=>(isset($processedPosition))?$processedPosition:'',
                ];
                //dd($data['multipleBillingDetails']);
        //return view('billingform.multiple.printbill', $data);
        $documentFileName = "multiple-property-billing.pdf";
           
        $document = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [216, 297]]);
            $document->watermarkImgBehind = true;
            $document->showWatermarkImage = true;
            $document->SetDisplayMode(90);
            $document->AddPage('p','','','','',10,10,30,2,10,50);
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
        ];  
        $html = view('billingform.multiple.printbill', $data)->render();
        //return view('billingform.printbill',compact('billDetails','class','actualUse','rates','processedBy'));
        // $html_back = view('inquiries.by_apr_no.printFAASBack', $data)->render();
        $document->WriteHTML($html);
        // $document->WriteHTML($html_back);
        if($callFrom == 'dev'){
                $data['document'] = $document;
                return $data;
            }else{
                // Save PDF on your public storage 
                Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "S"));
                 
                // Get file back from storage with the give header informations
                return Storage::disk('public')->download($documentFileName, 'Request', $header); //
            }
    }



    public function getMultipleList (Request $request){
        $data=$this->_ctobilling->getMultipleList($request);
        $arr=array();
        //echo "<pre>"; print_r($data); exit;
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        $params = $_REQUEST;
        $start = isset($params['start']) ? intval($params['start']) : 0;
        $length = isset($params['length']) ? intval($params['length']) : 10;
        $current_page = floor($start / $length) + 1;
        foreach ($data['data'] as $row){
			$sr_no=$sr_no+1;
            $arr[$i]['no']=$sr_no;
            $arr[$i]['con_no']=$row->cb_control_no;
            $arr[$i]['bill_to']=$row->full_name;
            $arr[$i]['barangay'] = (isset($row->rptProperty->barangay_details->brgy_name))?$row->rptProperty->barangay_details->brgy_name:''; 
            $arr[$i]['date']=date("d-m-Y",strtotime($row->cb_billing_date));
            $arr[$i]['topno']=$row->transaction_no;
            $arr[$i]['amount_due']=Helper::money_format($row->totalDueNew);
            $arr[$i]['is_paid'] = ($row->cb_is_paid == 1 ? '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Paid</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Pending</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center showMultipleBillingDetails" title="View Billing" data-id="'.$row->cb_control_no.'" data-url="'.url('billingform/storemultiple').'?id='.$row->cb_control_no.'"  data-title="Update Application">
                        <i class="ti-eye text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-primary ms-2">
                        <a href="'.url('billingform/multiplepropertiesprintbill/'.$row->cb_control_no).'&pageNo='.$current_page.'" class="mx-3 btn btn-sm  align-items-center realpropertyaction" target="_blank" data-actionname="print" data-propertyid="'.$row->id.'"  data-size="xxll"  title="Print Tax Declaration"  data-title="Print Tax Declaration">

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

    public function updateOwner(Request $request){
        $controlNumber = $request->cb_control_no;
        $rpoCode       = $request->id;
        try {
            RptCtoBilling::where('cb_control_no',$controlNumber)->update(['rpo_code'=>$rpoCode]);
            $response = [
                'status' => 'success',
                'msg'    => 'updated successfully'
            ];
        } catch (\Exception $e) {
             $response = [
                'status' => 'error',
                'msg'    => $e->getMessage()
            ];
        }
        return response()->json($response);
    }

    public function ActiveInactive(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('cb_is_paid' => '2');
        $this->_ctobilling->updateData($id,$data);
    }
    public function getList(Request $request){
        session()->forget('billingTempData');
        $data=$this->_ctobilling->getList($request);
        //dd($data);
        $classes = array(""=>"");
        
        foreach ($this->_ctobilling->getPropertyClasscode() as $keyc => $valc) {
            $classes[$valc->id] = $valc->pc_class_code;
        }
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0";    
        $count = $request->start+1;
        foreach ($data['data'] as $row){
            $arr[$i]['no']=$count;
            //$arr[$i]['arp_no']=($row->rptProperty != null)?$row->rptProperty->rp_tax_declaration_no->rvy_revision_code.'-'.$row->rptProperty->barangay_details->brgy_code.'-'.$row->rp_td_no:'';
            $arr[$i]['arp_no']=($row->rptProperty != null)?$row->rptProperty->rp_tax_declaration_no:'';
            $arr[$i]['taxpayer_name']=($row->full_name != null)?$row->full_name:''; 
            $arr[$i]['barangay'] = (isset($row->rptProperty->barangay_details->brgy_name))?$row->rptProperty->barangay_details->brgy_name:''; 
            $arr[$i]['type'] = ($row->rptProperty != null)?$row->rptProperty->propertyKindDetails->pk_code.'-'.$row->rptProperty->class_for_kind->pc_class_code:'';
            $arr[$i]['billing_date']=$row->cb_billing_date;
            $arr[$i]['paeriod_covered']=$row->period_covered;
            $arr[$i]['assessed_value']=Helper::money_format($row->cb_assessed_value);
            $totalBasic = ($row->amount_due != null)?$row->amount_due:0;
            $totalPenalty = ($row->penalty_due != null)?$row->penalty_due:0;
            $totalDiscount = ($row->discount != null)?$row->discount:0;
            $arr[$i]['controlno']=$row->cb_control_no;
            $arr[$i]['topno']=$row->transaction_no;
             $arr[$i]['orno']=$row->cb_or_no;
            $arr[$i]['amount_due']=Helper::money_format($totalBasic+$totalPenalty-$totalDiscount);
            $arr[$i]['is_paid'] = ($row->cb_is_paid == 1 ? '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Paid</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Pending</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center showBillingDetails" title="View Billing" data-sr="'.$count.'" data-id="'.$row->id.'" data-url="'.url('billingform/show').'"  data-title="Update Application">
                        <i class="ti-eye text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-primary ms-2">
                        <a href="'.url('billingform/printbill/'.$row->id).'" class="mx-3 btn btn-sm  align-items-center realpropertyaction" target="_blank" data-actionname="print" data-propertyid="'.$row->id.'"  data-size="xxll"  title="Print Tax Declaration"  data-title="Print Tax Declaration">

                            <i class="ti-printer text-white"></i>
                        </a>
                    </div>';
                if($row->cb_is_paid == 0 ){
                   $arr[$i]['action'].='<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>
                    </div>'; 
                }
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

    public function storRemoteRptBillReceipt(Request $request){
            $qtrs = ['1' => '11', '2' => '22', '3' => '33', '4' => '44'];
           if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
            $transaction_no = $request->input('transaction_no');
            $arrTrans = $this->getPropDue($transaction_no);
            //dd($arrTrans);
            if(isset($arrTrans) && !empty($arrTrans)){
                foreach ($arrTrans as $key => $arrTran) {
                $allModesData = DB::table('schedule_descriptions')->select('id','sd_mode')->where('is_active',1)->get();
                $sdModeId = $allModesData->where('sd_mode',$arrTran->endQtr)->first();
                $dueDateData = $this->_ctobilling->getPaymentScheduledData(date("Y"),$sdModeId->id);
                $arrData['rp_code'] = $arrTran->rp_code;
                $arrData['rp_property_code'] = $arrTran->rp_property_code;
                $arrData['client_id'] = $arrTran->rpo_code;
                $arrData['bill_year'] = date("Y");
                $arrData['bill_month'] = date("m");
                $arrData['bill_due_date'] = (isset($dueDateData->rcpsched_penalty_due_date))?$dueDateData->rcpsched_penalty_due_date:'';
                $arrData['pm_id'] = ($arrTran->startQtr == 14)?1:3;
                $arrData['pap_id'] = ($arrTran->endQtr == 14)?1:((in_array($arrTran->endQtr,array_flip($qtrs)))?array_flip($qtrs)[$arrTran->endQtr]:0);
                $arrData['total_amount'] = $arrTran->totalDue;
                $arrData['transaction_no'] = $arrTran->transaction_no;
                $arrData['attachement'] = $arrTran->attachment;
                $arrData['updated_by'] = \Auth::user()->id;
                $arrData['updated_at'] = date('Y-m-d H:i:s');
                $arrData['is_synced'] = 0;
                //This is for Main Server
                $arrBill = DB::table('rpt_bill_summary')
                                ->select('id')
                                ->where('rp_code',$arrTran->rp_code)
                                ->where('transaction_no',$arrTran->transaction_no)
                                ->first();
                /*/* old Code */
                if(isset($arrBill)){
                    DB::table('rpt_bill_summary')->where('id',$arrBill->id)->update($arrData);
                }else{
                    $arrData['created_by'] =  \Auth::user()->id;
                    $arrData['created_at'] =  date('Y-m-d H:i:s');
                    DB::table('rpt_bill_summary')->insert($arrData);
                }
                /* old Code */
                /* Updated Code */
                //DB::table('rpt_bill_summary')->insert($arrData);
                /* Updated Code */

                // This is for Remote Server
                $destinationPath =  public_path().'/uploads/billing/real-property/'.$arrTran->attachment;
                if(file_exists($destinationPath)){
                    $fileContents = file_get_contents($destinationPath);
                    $remotePath = 'public/uploads/billing/real-property/'.$arrTran->attachment;
                    Storage::disk('remote')->put($remotePath, $fileContents);
                }
                
                $remortServer = DB::connection('remort_server');
                $arrBill = $remortServer->table('rpt_bill_summary')->select('id')->where('rp_code',$arrTran->rp_code)->where('transaction_no',$arrTran->transaction_no)->first();
                /* old Code */
                try {
                    if(isset($arrBill)){
                        $remortServer->table('rpt_bill_summary')->where('id',$arrBill->id)->update($arrData);
                    }else{
                        $arrData['created_by'] =  \Auth::user()->id;
                        $arrData['created_at'] =  date('Y-m-d H:i:s');
                        $remortServer->table('rpt_bill_summary')->insert($arrData);
                    }
                    DB::table('rpt_bill_summary')->where('rp_code',$arrTran->rp_code)->where('transaction_no',$arrTran->transaction_no)->update(array('is_synced'=>1));
                    if(file_exists($destinationPath)){
                    unlink($destinationPath);
                }
                }catch (\Throwable $error) {
                    return $error;
                }
                /* old Code */
                /* Updated Code */
                /*try {
                    $remortServer->table('rpt_bill_summary')->insert($arrData);
                    DB::table('rpt_bill_summary')->where('rp_code',$arrTran->rp_code)->where('transaction_no',$arrTran->transaction_no)->update(array('is_synced'=>1));
                    unlink($destinationPath);
                }catch (\Throwable $error) {
                    return $error;
                }*/
                /* Updated Code */
                }
                
                return "Done";
            }
        }
    }

    public function storRemoteRptOnlineAccess(Request $request){
            if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
            $transaction_no = $request->input('transaction_no');
            $arrTrans = $this->getPropDue($transaction_no);
           // dd($arrTrans);
            if(isset($arrTrans) && !empty($arrTrans)){
                foreach ($arrTrans as $key => $arrTran) {
                $allModesData = DB::table('schedule_descriptions')->select('id','sd_mode')->where('is_active',1)->get();
                $sdModeId = $allModesData->where('sd_mode',$arrTran->endQtr)->first();
                $dueDateData = $this->_ctobilling->getPaymentScheduledData(date("Y"),$sdModeId->id);
                //dd($dueDateData);
                $arrData['taxpayer_id'] = $arrTran->rpo_code;
                $arrData['tax_declaration_no'] = $arrTran->rp_tax_declaration_no;
                $arrData['property_index_no'] = $arrTran->rp_pin_declaration_no;
                $arrData['market_value'] = $arrTran->marketValue;
                $arrData['assessed_value'] = $arrTran->assessedValue;
                $arrData['amount_due'] = $arrTran->totalDue;
                $arrData['payment_status'] = 0;
                $arrData['is_active'] = 1;
                $arrData['updated_by'] = \Auth::user()->id;
                $arrData['updated_at'] = date('Y-m-d H:i:s');
                $arrData['is_synced'] = 0;
                //This is for Main Server
                $arrBillUat = DB::table('rpt_property_online_accesses')
                                //->select('id','rp_code')
                                ->where('rp_code',$arrTran->rp_code)
                                ->first();
                                //dd($arrBill);
                if($arrBillUat != null){
                    DB::table('rpt_property_online_accesses')->where('id',$arrBillUat->id)->update($arrData);
                }

                // This is for Remote Server
                $remortServer = DB::connection('remort_server');
                $arrBill = $remortServer->table('rpt_property_online_accesses')->select('id')->where('rp_code',$arrTran->rp_code)->first();

                try {
                    if($arrBill != null){
                        $remortServer->table('rpt_property_online_accesses')->where('id',$arrBill->id)->update($arrData);
                        DB::table('rpt_property_online_accesses')->where('rp_code',$arrTran->rp_code)->update(array('is_synced'=>1));
                    }else{
                        if($arrBillUat != null){
                            $dataToInsert = (array)$arrBillUat;
                            unset($dataToInsert['id']);
                            $remortServer->table('rpt_property_online_accesses')->insert($dataToInsert);
                            DB::table('rpt_property_online_accesses')->where('rp_code',$arrTran->rp_code)->update(array('is_synced'=>1));
                      }

                    }
                }catch (\Throwable $error) {
                    return $error;
                }
                }
                
                return "Done";
            }
        }
    }

    public function collectDataForPenaltyDetailsNew($detail,$taxTrevId,$taxRevenueYear){
        $propDetails = DB::table('rpt_properties')->select('pk_id')->where('id',$detail->rp_code)->first();
        $revenueCodeDetails = $this->_ctobilling->getRevenueCodeDetails($taxTrevId,$taxRevenueYear,$propDetails->pk_id);
                    /* Save basic Details */
                    $basicDetails = [
                        'trevs_id' => $taxTrevId,
                        'tax_revenue_year' => $taxRevenueYear,
                        'basic_penalty_tfoc_id' => (isset($revenueCodeDetails->basic_penalty_tfoc_id))?$revenueCodeDetails->basic_penalty_tfoc_id:0,
                        'basic_penalty_gl_id' => (isset($revenueCodeDetails->basic_p_gl_id))?$revenueCodeDetails->basic_p_gl_id:0,
                        'basic_penalty_sl_id' => (isset($revenueCodeDetails->basic_p_sl_id))?$revenueCodeDetails->basic_p_sl_id:0,
                        'basic_penalty_rate' => $detail->basic_penalty_rate,
                        'basic_penalty_amount' => $detail->cbd_basic_penalty,
                        'sef_penalty_tfoc_id' => (isset($revenueCodeDetails->sef_penalty_tfoc_id))?$revenueCodeDetails->sef_penalty_tfoc_id:0,
                        'sef_penalty_gl_id' => (isset($revenueCodeDetails->sef_p_gl_id))?$revenueCodeDetails->sef_p_gl_id:0,
                        'sef_penalty_sl_id' => (isset($revenueCodeDetails->sef_p_sl_id))?$revenueCodeDetails->sef_p_sl_id:0,
                        'sef_penalty_amount'  => $detail->cbd_sef_penalty,
                        'sef_penalty_rate' => $detail->basic_penalty_rate,
                        'sh_penalty_tfoc_id' => (isset($revenueCodeDetails->sh_penalty_tfoc_id))?$revenueCodeDetails->sh_penalty_tfoc_id:0,
                        'sh_penalty_gl_id' => (isset($revenueCodeDetails->sh_p_gl_id))?$revenueCodeDetails->sh_p_gl_id:0,
                        'sh_penalty_sl_id' => (isset($revenueCodeDetails->sh_p_sl_id))?$revenueCodeDetails->sh_p_sl_id:0,
                        'sh_penalty_amount'  => $detail->cbd_sh_penalty,
                        'sh_penalty_rate' => $detail->basic_penalty_rate,
                        'penalty_total_due' => $detail->cbd_basic_penalty+$detail->cbd_sef_penalty+$detail->cbd_sh_penalty,
                    ];
                    return $basicDetails;
    }

    public function deleteRowNew(Request $request){
        $id = $request->input('id');
        $getcontrolno = $this->_ctobilling->getcontrolno($id);
        $this->_ctobilling->deleteBillingsrow($id);
        $this->_ctobilling->deleteBillingDetailsrow($id);
        $this->_ctobilling->deleteBillingDiscountrow($id);
        $this->_ctobilling->deleteBillingPenaltyrow($id);
        $checkrecordcontrolno = $this->_ctobilling->getcountofcontrolno($getcontrolno->cb_control_no);
        if(count($checkrecordcontrolno) == 0){
            $this->_ctobilling->deleteTopTransactionrow($getcontrolno->transaction_id);
        }
    }
}
