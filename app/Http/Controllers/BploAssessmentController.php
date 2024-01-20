<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\BploAssessment; 
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
class BploAssessmentController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrprofile = array(""=>"Select Owner");
    public $yeararr = array(""=>"Select Year");
    public $arrBarangay = array(""=>"Please Select");
    public $accountnos = array(""=>"Select Account Number");
    public $nofbusscode = array(""=>"Select Code");
    public $taxtypes = array(""=>"Select Code");
    public $classification =array(""=>"Select Code");
    public $activity =array(""=>"Select Code");
    public function __construct(){
		$this->_bploAssessment = new BploAssessment();
        $this->_commonmodel = new CommonModelmaster(); 
        $this->data = array('id'=>'','application_id'=>'','bas_code'=>'','profile_id'=>'','ba_code'=>'','ba_building_is_owned'=>'','kindofowner'=>'','ba_business_account_no'=>'','app_type'=>'','ba_tax_filing_mode'=>'','brgy_code'=>'','ba_municipality'=>'','p_code'=>'','ba_capital_investment'=>'','bas_gross_sales_amount'=>'','ba_building_total_area_occupied'=>'','ba_taxable_owned_truck_wheeler_10above'=>'','ba_taxable_owned_truck_wheeler_6above'=>'','ba_taxable_owned_truck_wheeler_4above'=>'','bas_applicable_quarter_from'=>'','	bas_applicable_quarter_to'=>'','lessor'=>'','lessoraddress'=>'','administrator'=>'','rentalstart'=>'','presentrate'=>'','ba_building_property_index_number'=>'','engneeringfee_description'=>'','engneering_amount'=>'','engneering_code'=>'','engneering_feeid'=>'','no_of_personnel'=>'','big'=>'','small'=>'');
         $this->postdata = array('id'=>'','application_id'=>'','profile_id'=>'','ba_taxable_owned_truck_wheeler_10above'=>'','ba_building_total_area_occupied'=>'','ba_taxable_owned_truck_wheeler_6above'=>'','ba_taxable_owned_truck_wheeler_4above'=>'','bas_applicable_quarter_from'=>'','bas_applicable_quarter_to'=>'','lessor'=>'','lessoraddress'=>'','administrator'=>'','rentalstart'=>'','presentrate'=>'','engneeringfee_description'=>'','engneering_amount'=>'','engneering_code'=>'','engneering_feeid'=>'','no_of_personnel'=>'','big'=>'','small'=>'');
        foreach ($this->_bploAssessment->getBarangay() as $val) {
            $this->arrBarangay[$val->id]=$val->brgy_code;
        } 
        // foreach ($this->_bploAssessment->getaccountnumbers() as $val) {
        //     $this->accountnos[$val->id]=$val->ba_business_account_no;
        // } 
    }
    public function index(Request $request)
    {   
        $yeararr= $this->yeararr;
        $year ='2020';
        for($i=0;$i<=10;$i++){
            $yeararr[$year] =$year; 
            $year = $year +1;
        }
        return view('bploassessment.index',compact('yeararr'));
        
    }
    public function getList(Request $request){
        $data=$this->_bploAssessment->getList($request);
    	$arr=array();
		$i="0";    
		foreach ($data['data'] as $row){	
            $arr[$i]['ba_business_account_no']=$row->ba_business_account_no;
            $arr[$i]['ba_business_name']=$row->ba_business_name;
            $arr[$i]['business_address']=$row->ba_address_house_lot_no.','.$row->ba_address_street_name;
            $arr[$i]['p_complete_name_v1']=$row->p_complete_name_v1;
            $arr[$i]['created_at']=date("M d, Y",strtotime($row->ba_date_started));
            $arr[$i]['status']=$row->app_type;
            
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bploassessment/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Update Application">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bploassessment/asses?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Assess Now"  data-title="Assess Now">
                    <i class="ti ti-currency-dollar text-white"></i>
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
   
   
    public function assessNow(Request $request){
        $id = $request->input('id');
        $data = $this->_bploAssessment->getUserBusinessDetails($id);
        if(count($data)>0){
            $data = $data[0];
        }
        $arrAccountNum = array(""=>"Select Account Number");
        foreach ($this->_bploAssessment->getAssesAccountNumbers() as $val) {
            $arrAccountNum[$val->application_id]=$val->ba_business_account_no;
        } 
        $arrApplAss = $this->_bploAssessment->getApplicationAssessments($id);
        $month = date("m",strtotime($data->ba_date_started));
        $qutr = ceil($month/3);
        
        $allfees = $this->_bploAssessment->getAllFeeMaster();  $arrFeesDetails =array();
        foreach ($allfees as $keyf => $valf) {
            $arrFeesDetails[$valf->id] = $valf->fee_name;
        }
       // $arrFeesDetails = array('1'=>'Mayors Permit Fee','2'=>'Sanitary Fee','3'=>'Garbage Fee','4'=>'Fire Inspection Fee');
        $i=0;
        $arrFees=array();
        $arrtaxFees=array();
        foreach($arrFeesDetails as $key=>$val){
            $fee = 0;
            foreach($arrApplAss as $f_key=>$f_val){
                if($key==2){
                    $fee +=$f_val->permit_amount;
                }elseif($key==3){
                    $fee +=$f_val->sanitary_amount;
                }elseif($key==4){
                    $fee +=$f_val->garbage_amount;
                }
            }
            $arrFees[$i]['cover_year']=$data->ba_cover_year;
            $arrFees[$i]['tax_type_fee']=$val;
            $arrFees[$i]['top_code']='0201';
            $arrFees[$i]['1_qutr_fee']='0.00';
            $arrFees[$i]['2_qutr_fee']='0.00';
            $arrFees[$i]['3_qutr_fee']='0.00';
            $arrFees[$i]['4_qutr_fee']='0.00';
            $arrFees[$i][$qutr.'_qutr_fee']=$fee;
            $arrFees[$i]['total_fee']=$fee;

            $arrtaxFees[$i]['cover_year']=$data->ba_cover_year;
            $arrtaxFees[$i]['tax_type_fee']=$val;
            $arrtaxFees[$i]['top_code']='0201';
            $arrtaxFees[$i]['tax_amount']=$fee;
            $arrtaxFees[$i]['excess_tax']='0.00';
            $arrtaxFees[$i]['rate']='0.00';
            $arrtaxFees[$i]['sircharge']='0.00';
            $arrtaxFees[$i]['interest']='0.00';
            $arrtaxFees[$i]['totalTax']=$fee;
            $i++;
        }
        $arrEngnneringfee = $this->_bploAssessment->getEngneeringFeebyid($data->engneering_feeid);
        foreach ($arrEngnneringfee as $e_key => $e_val) {
                if($e_val->id =='4'){ $fee = $e_val->amount * $data->no_of_personnel; $i='5'; }
                if($e_val->id =='1'){$i='1';}if($e_val->id =='2'){$i='11';} if($e_val->id =='5'){$i='12';}
                if($e_val->id !='4'){ $fee = $e_val->amount; }
                $arrFees[$i]['cover_year']=$data->ba_cover_year;
                $arrFees[$i]['tax_type_fee']=$e_val->description;
                $arrFees[$i]['top_code']=$e_val->top;
                $arrFees[$i]['1_qutr_fee']='0.00';
                $arrFees[$i]['2_qutr_fee']='0.00';
                $arrFees[$i]['3_qutr_fee']='0.00';
                $arrFees[$i]['4_qutr_fee']='0.00';
                $arrFees[$i][$qutr.'_qutr_fee']=$fee;
                $arrFees[$i]['total_fee']=$fee;

                $arrtaxFees[$i]['cover_year']=$data->ba_cover_year;
                $arrtaxFees[$i]['tax_type_fee']=$e_val->description;
                $arrtaxFees[$i]['top_code']=$e_val->top;
                $arrtaxFees[$i]['tax_amount']=$fee;
                $arrtaxFees[$i]['excess_tax']='0.00';
                $arrtaxFees[$i]['rate']='0.00';
                $arrtaxFees[$i]['sircharge']='0.00';
                $arrtaxFees[$i]['interest']='0.00';
                $arrtaxFees[$i]['totalTax']=$fee;
        }
        //echo "<pre>"; print_r($arrtaxFees); exit;
        return view('bploassessment.asses',compact('arrFees','arrAccountNum','arrtaxFees','data'));
	}


    public function store(Request $request){
        $data = (object)$this->data;
        $arrBarangay = $this->arrBarangay;
        $arrbDetails=array();
      
        if($request->input('id')>0 && $request->input('submit')==""){
            $data= $this->_bploAssessment->getProgiledataForedit($request->input('id'));
            $arrbDetails = $this->getBussinessDetals($request->input('id'));

            foreach ($this->_bploAssessment->getaccountnumbersedit($data->application_id) as $val) {
            $this->accountnos[$val->id]=$val->ba_business_account_no;
	        } 
        }else{
        	 foreach ($this->_bploAssessment->getaccountnumbers() as $val) {
            $this->accountnos[$val->id]=$val->ba_business_account_no;
	        } 
        }

        foreach ($this->_bploAssessment->getnatureofBussinessCodes() as $val) {
            $this->nofbusscode[$val->id]=$val->subclass_code;
        } 
        foreach ($this->_bploAssessment->getTaxTyeps() as $val) {
            $this->taxtypes[$val->id]=$val->tax_class_type_code;
        } 
       
        $accountnos = $this->accountnos;
        $nofbusscode = $this->nofbusscode;
        $taxtypes = $this->taxtypes;
        $classification = $this->classification;
        $activity = $this->activity;
		if($request->input('submit')!=""){
            foreach((array)$this->postdata as $key=>$val){
                $this->postdata[$key] = $request->input($key);
            }
            $this->postdata['updated_by']=\Auth::user()->creatorId();
            $this->postdata['updated_at'] = date('Y-m-d H:i:s');
            //echo "<pre>"; echo $request->input('id'); exit;
            if($request->input('id')>0){
                $this->_bploAssessment->updateData($request->input('id'),$this->postdata);
                $success_msg = 'Pblo Assessment  updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Pblo Assessment ".$this->data['id'];
                $lastinsertid = $request->input('id');
            }else{
                $this->postdata['created_by']=\Auth::user()->creatorId();
                $this->postdata['created_at'] = date('Y-m-d H:i:s');
                $this->postdata['ba_business_account_no'] = $_POST['accountnumber'];
                $this->postdata['app_type'] = $_POST['app_type'];
                $lastinsertid = $this->_bploAssessment->addData($this->postdata);
                $success_msg = 'Pblo Assessment added successfully.';
                $content = "User ".\Auth::user()->name." Added Pblo Assessment ".$lastinsertid;
            }
            // echo "<pre>"; print_r($_POST); exit;
             if(!empty($_POST['bussiness_application_code'])){
                 $loop = count($_POST['bussiness_application_code']);
                 $assementdetail = array();
              
                for($i=0; $i<$loop;$i++){
                    $assementdetail['bplo_assessment_id'] = $lastinsertid;
                    $assementdetail['bussiness_application_code'] = $_POST['bussiness_application_code'][$i];
                    $assementdetail['bussiness_application_id'] = $_POST['bussiness_application_id'][$i];
                    $assementdetail['bussiness_application_desc'] = $_POST['bussiness_application_desc'][$i];
                    $assementdetail['tax_type_code'] = $_POST['tax_type_code'][$i];
                    $assementdetail['tax_type_id'] = $_POST['tax_type_id'][$i];
                    $assementdetail['tax_type_desc'] = $_POST['tax_type_desc'][$i];
                    $assementdetail['classification_code'] = $_POST['classification_code'][$i];
                    $assementdetail['classification_id'] = $_POST['classification_id'][$i];
                    $assementdetail['classification_desc'] = $_POST['classification_desc'][$i];
                    $assementdetail['activity_code'] = $_POST['activity_code'][$i];
                    $assementdetail['activity_id'] = $_POST['activity_id'][$i];
                    $assementdetail['activity_desc'] = $_POST['activity_desc'][$i];
                    if(!empty($_POST['essential_commodities'][$i])){
                      $assementdetail['essential_commodities'] = $_POST['essential_commodities'][$i];  
                    }else {$assementdetail['essential_commodities'] = '0'; }
                    $assementdetail['no_of_perdays'] = $_POST['no_of_perdays'][$i];
                    $assementdetail['mayrol_permit_description'] = $_POST['mayrol_permit_description'][$i];
                    $assementdetail['permit_amount'] = $_POST['permit_amount'][$i];
                    $assementdetail['mayrol_permit_code'] = $_POST['mayrol_permit_code'][$i];
                    $assementdetail['garbage_description'] = $_POST['garbage_description'][$i];
                    $assementdetail['garbage_amount'] = $_POST['garbage_amount'][$i];
                    $assementdetail['garbage_code'] = $_POST['garbage_code'][$i];
                    $assementdetail['sanitary_description'] = $_POST['sanitary_description'][$i];
                    $assementdetail['sanitary_amount'] = $_POST['sanitary_amount'][$i];
                    $assementdetail['sanitary_code'] = $_POST['sanitary_code'][$i];
                    $assementdetail['capitalization'] = $_POST['capitalization'][$i];
                    $assementdetail['gross_sale'] = $_POST['gross_sale'][$i];
                    if(!empty($_POST['cto_assessmentid'][$i])){
                        $this->_bploAssessment->updateAssesmentDetail($_POST['cto_assessmentid'][$i],$assementdetail);
                    }else{
                        $this->_bploAssessment->addAssesmentDetail($assementdetail);
                    }
                }
             }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);   
            return redirect()->route('bp.bploassessment.index')->with('success', __($success_msg));
    	}
        return view('bploassessment.create',compact('data','accountnos','arrBarangay','nofbusscode','taxtypes','classification','activity','arrbDetails'));
        
	}
	public function getBussinessDetals($id){
        $arrbDetails = array();
        $i=0;
        foreach ($this->_bploAssessment->getBussinessDetals($id) as $val) {
            $arrbDetails[$i]['id']=$val->id;
            $arrbDetails[$i]['bplo_assessment_id']=$val->bplo_assessment_id;
            $arrbDetails[$i]['bussiness_application_code']=$val->bussiness_application_code;
            $arrbDetails[$i]['bussiness_application_id']=$val->bussiness_application_id;
            $arrbDetails[$i]['bussiness_application_desc']=$val->bussiness_application_desc;
            $arrbDetails[$i]['tax_type_code']=$val->tax_type_code;
            $arrbDetails[$i]['tax_type_id']=$val->tax_type_id;
            $arrbDetails[$i]['tax_type_desc']=$val->tax_type_desc;
            $arrbDetails[$i]['classification_code']=$val->classification_code;
            $arrbDetails[$i]['classification_id']=$val->classification_id;
            $arrbDetails[$i]['classification_desc']=$val->classification_desc; 
            $arrbDetails[$i]['activity_code']=$val->activity_code;
            $arrbDetails[$i]['activity_id']=$val->activity_id;
            $arrbDetails[$i]['activity_desc']=$val->activity_desc;
            $arrbDetails[$i]['essential_commodities']=$val->essential_commodities;
            $arrbDetails[$i]['no_of_perdays']=$val->no_of_perdays;
            $arrbDetails[$i]['mayrol_permit_description']=$val->mayrol_permit_description;
            $arrbDetails[$i]['permit_amount']=$val->permit_amount;
            $arrbDetails[$i]['mayrol_permit_code']=$val->mayrol_permit_code;
            $arrbDetails[$i]['garbage_description']=$val->garbage_description;
            $arrbDetails[$i]['garbage_amount']=$val->garbage_amount;
            $arrbDetails[$i]['garbage_code']=$val->garbage_code;
            $arrbDetails[$i]['sanitary_description']=$val->sanitary_description;
            $arrbDetails[$i]['sanitary_amount']=$val->sanitary_amount;
            $arrbDetails[$i]['sanitary_code']=$val->sanitary_code;
            $arrbDetails[$i]['capitalization']=$val->capitalization;
            $arrbDetails[$i]['gross_sale']=$val->gross_sale;
            $arrbDetails[$i]['app_qurtr']='2nd - 2nd';
            $arrbDetails[$i]['nature_of_bussiness']=$val->bussiness_application_desc;
            $arrbDetails[$i]['activity']=$val->activity_desc;
            $arrbDetails[$i]['capitalization']=$val->capitalization;
            $arrbDetails[$i]['gross_sale']=$val->gross_sale;
            $i++;
        }
        return $arrbDetails;
    }
    public function addNatureOfBusiness($request){
        $psic_subclass_id = $request->input('psic_subclass_id');
        $arr = array();
        $i=0;
        foreach ($psic_subclass_id as $key => $value) {
            if(!empty($request->input('psic_subclass_id')[$key])){
                $arr[$i]['psic_subclass_id']=$request->input('psic_subclass_id')[$key];
                $arr[$i]['taxable_item_name']=$request->input('taxable_item_name')[$key];
                $arr[$i]['taxable_item_qty']=$request->input('taxable_item_qty')[$key];
                $arr[$i]['capital_investment']=$request->input('capital_investment')[$key];
                $arr[$i]['date_started']=$request->input('date_started')[$key];
                $i++;
            }
        }
        if(count($arr)>0){
            $json = json_encode($arr);
            $arrData=array("nature_of_bussiness_json"=>$json);
            $this->_bploAssessment->updateData($request->id,$arrData);
        }
    }
   
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                // 'class_code'=>'required|unique:psic_classes,class_code,'.$request->input('id'),
                // 'section_id'=>'required',
                // 'division_id'=>'required', 
                // 'group_id'=>'required', 
                // 'class_description'=>'required'
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

    public function destroy($id)
    {
        $BploApplication = BploApplication::find($id);
        if($BploApplication->generated_by == \Auth::user()->creatorId()){
            $BploApplication->delete();
            return redirect()->route('bploassessment.index')->with('success', __('PSIC class successfully deleted.'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
      public function getprofilesasses(Request $request){
         $id= $request->input('pid');
         $data = $this->_bploAssessment->getprogiledata($id);
         echo json_encode($data);
    }
  
    public function getBussinessData(Request $request){
        $id= $request->input('id');
        $data = $this->_bploAssessment->getBussinessData($id);
        echo json_encode($data);
    }
    public function getTasktypesData(Request $request){
        $id= $request->input('id');
        $data = $this->_bploAssessment->getTasktypesData($id);
        echo json_encode($data);
    }
    public function getClasificationDesc(Request $request){
        $id= $request->input('id');
        $data = $this->_bploAssessment->getClasificationDesc($id);
        echo json_encode($data);
    }
    public function getActivityDesc(Request $request){
        $id= $request->input('id');
        $data = $this->_bploAssessment->getActivityDesc($id);
        echo json_encode($data);
    }
    public function getClassificationByType(Request $request){
        $tax_type_id = $request->input('tax_type_id');
        $preclassificationid = $request->input('pre_classification_id');
        $arrClassification = $this->_bploAssessment->getClassifications($tax_type_id);
        $htmloption ="<option value=''>Please Select</option>"; $selected ="";
        foreach ($arrClassification as $key => $value) {
        	if($value->id == $preclassificationid){ $selected ="selected"; }
            $htmloption .='<option value="'.$value->id.'" '.$selected.'>'.$value->bbc_classification_code.'</option>';
        }
        echo $htmloption;
    }
    public function getActivitybyClass(Request $request){
    	$class_id = $request->input('class_id');
    	$pre_activityid = $request->input('pre_activityid');
    	$arrActivity = $this->_bploAssessment->getActivitybyClass($class_id);
        $htmloption ="<option value=''>Please Select</option>";  $selected ="";
        foreach ($arrActivity as $key => $value) {
        	if($value->id == $pre_activityid){ $selected ="selected"; }
            $htmloption .='<option value="'.$value->id.'" '.$selected.'>'.$value->bba_code.'</option>';
        }
        echo $htmloption;
    }
     public function getAllFeeDetails(Request $request){
    	$taxtypeid = $request->input('tasktypeid');
    	$classificationid = $request->input('classificationid');
    	$activityid = $request->input('activityid');
        $areaused = $request->input('areaused');
        $noofworker = $request->input('noofworker');
        $capitaliztion = $request->input('capitaliztion');
    	$arrPermits = $this->_bploAssessment->getPermitfees($taxtypeid,$classificationid,$activityid,$noofworker,$capitaliztion);
    	foreach($arrPermits as &$val){
            $val->description=$val->id.' - '.$val->description.' - '.$val->bpt_permit_fee_amount;
        }
        $arrPermits1 = $this->_bploAssessment->getPermitfees2($taxtypeid,$classificationid,$activityid,$noofworker,$capitaliztion);
        foreach($arrPermits1 as &$val){
            $val->description=$val->id.' - '.$val->description.' - '.$val->bpt_permit_fee_amount;
        }

        $arrGarbage = $this->_bploAssessment->getGarbageDrodown($taxtypeid,$classificationid,$activityid,$areaused);
        foreach($arrGarbage as &$val){
            $val->description=$val->id.' - '.$val->description;
        }
        $arrSanitary = $this->_bploAssessment->getSanitaryDrodown($taxtypeid,$classificationid,$activityid,$areaused);
        foreach($arrSanitary as &$val){
            $val->description=$val->id.' - '.$val->description.' amount - '.$val->amount;
        }
        if((count($arrPermits) > 0 ) && (count($arrPermits1) > 0)){
            if($arrPermits[0]->bpt_permit_fee_amount > $arrPermits1[0]->bpt_permit_fee_amount){
                $arrPermits = $arrPermits;
            } else{ $arrPermits = $arrPermits1;}
        }
        //echo $arrPermits[0]->bpt_permit_fee_amount; echo $arrPermits1[0]->bpt_permit_fee_amount; exit;
        $arrJson = array();
        $arrJson['ESTATUS']=1;
        if(count($arrPermits)>0 || count($arrGarbage)>0 || count($arrSanitary)>0){
            $arrJson['ESTATUS']=0;
            $arrJson['arrPermits']=$arrPermits;
            $arrJson['arrGarbage']=$arrGarbage;
            $arrJson['arrSanitary']=$arrSanitary;
         }
        echo json_encode($arrJson);
    }

    public function getEngneeringFeeDetails(){
        $arrEngnneringfee = $this->_bploAssessment->getEngneeringFee();
         foreach($arrEngnneringfee as &$val){
            $val->description=$val->id.' - '.$val->description.' amount - '.$val->amount;
            $val->amount = number_format($val->amount,2);
        }
        $arrJson = array();
        $arrJson['ESTATUS']=1;
        if(count($arrEngnneringfee)>0 ){
            $arrJson['ESTATUS']=0;
            $arrJson['arrEngnneringfee']=$arrEngnneringfee;
         }
        echo json_encode($arrJson);
       
    }
  
}
