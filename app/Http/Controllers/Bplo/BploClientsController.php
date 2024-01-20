<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\BploClients;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use File;
use Carbon\Carbon;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\taxpayerImport;
use DB;

class BploClientsController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrgetBrgyCode = array(""=>"Please Select");
    public $arrgetCountries = array();
    public $arrgetClients = array(""=>"Please Select");
    private $slugs;
    public $errorImport=array();
    public function __construct(){
        $this->_bploClients = new BploClients();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','rpo_custom_last_name'=>'','rpo_first_name'=>'','rpo_middle_name'=>'','suffix'=>'','p_code'=>'','rpo_address_house_lot_no'=>'','rpo_address_street_name'=>'','rpo_address_subdivision'=>'','brgy_code'=>'','p_barangay_id_no'=>'','p_telephone_no'=>'','p_mobile_no'=>'','p_fax_no'=>'','p_tin_no'=>'','p_email_address'=>'','country'=>'','gender'=>'','dateofbirth'=>'');
        foreach ($this->_bploClients->getCountries() as $val) {
            $this->arrgetCountries[$val->id]=$val->nationality;
        }
        foreach ($this->_bploClients->getClients() as $val) {
            $this->arrgetClients[$val->id]=$val->rpo_first_name." ".$val->rpo_middle_name." ".$val->rpo_custom_last_name;
        }
        $this->slugs = 'bploclients';
    }

    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
            return view('Bplo.clients.index');
    }
    public function bulkUpload(Request $request)
    {
        $this->is_permitted($this->slugs, 'upload');
        return view('Bplo.clients.bulkUpload');
    }
     
    public function uploadBulkTaxpayers(Request $request){
        $type = $request->input('type');
        if($request->hasFile('file')){
            $this->errorImport=array();
            /*$rules = [
                'file' => 'required|mimes:csv',
            ];
            $validator = \Validator::make($request->all(), $rules);
            if($validator->fails()){
                $response = ['status' => false, 'message' => $validator->getMessageBag()->first()];
                return json_encode($response);
            }*/
           $arrColumn =array('id','rpo_custom_last_name','rpo_first_name','rpo_middle_name','suffix','full_name','rpo_address_house_lot_no','rpo_address_street_name','rpo_address_subdivision','p_barangay_id_no','p_telephone_no','p_mobile_no','p_fax_no','p_tin_no','p_email_address','country','gender','dateofbirth','civil_status','is_active','is_bplo','is_rpt','is_engg','is_fire_safety','is_online','created_by','updated_by','created_at','updated_at');
            $excelData = (new taxpayerImport())->toArray(request()->file('file'))[0];
            if($type=='validateRecords'){
                $isMissing = $this->checkCoulmnMissing($arrColumn,$excelData[0]);
                if(!$isMissing){
                    $totelRecords = count($excelData) - 1;
                    $error='';
                    for($i = 1; $i <= count($excelData) - 1; $i++){
                        $arrData = $this->assignData($excelData[$i],$arrColumn);
                        $this->checkValidation($arrData,$i);
                    }
                }else{
                    $response = ['status' => false, 'message' => $isMissing];
                    return json_encode($response);
                }
                if(count($this->errorImport)>0){
                    $messages = implode("<br>",$this->errorImport);
                    $response = ['status' => false, 'message' => $messages];
                    return json_encode($response);
                }
                $response = ['status' => true, 'message' => 'Validate successfully.'];
                return json_encode($response);
            }else{
                $totelRecords = count($excelData) - 1;
                for($i = 1; $i <= count($excelData) - 1; $i++){
                    $arrData = $this->assignData($excelData[$i],$arrColumn);

                    unset($arrData['id']);
                    unset($arrData['full_name']);
                    $arrData['is_active']=1;
                    $arrData['is_bplo']=1;
                    $arrData['is_rpt']=1;
                    $arrData['is_engg']=1;
                    $arrData['is_fire_safety']=0;
                    $arrData['is_online']=0;
                    $arrData['updated_by']=\Auth::user()->id;
                    $arrData['updated_at'] = date('Y-m-d H:i:s');
                    $arrData['created_by']=\Auth::user()->id;
                    $arrData['created_at'] = date('Y-m-d H:i:s');
                    $arrData = array_filter($arrData, function ($value) {
                         return !is_null($value) && $value !== '' && $value !== 'NULL';
                    });
                    $this->_bploClients->addData($arrData);
                }
                $response = ['status' => true, 'message' => 'Uploaded successfully.'];
                return json_encode($response);
            }
            
        }
    }
    public function checkValidation($arrData,$cnt){
        //dd($arrData);
        $arrData['id']=0;
        $rules = [
            'rpo_custom_last_name' => 'required|unique:clients,rpo_custom_last_name,' .$arrData['id']. ',id,rpo_first_name,' .$arrData['rpo_first_name'].',rpo_middle_name,' .$arrData['rpo_middle_name'].',suffix,'.$arrData['suffix'], // Example custom rule
            'p_barangay_id_no' => 'required',
            //'p_mobile_no'=>'required|unique:clients,p_mobile_no',
            'p_email_address' => 'required|email|unique:clients,p_email_address',
        ];
        $customMessages = [
            'rpo_custom_last_name.required' => 'Last name required.',
            'rpo_custom_last_name.unique' => 'This user already exist, please try another.',
            'p_barangay_id_no.required' => 'Barangay required.',
            'p_email_address.required' => 'Email required.',
            'p_email_address.email' => 'Please provide a valid email address.',
            'p_email_address.unique' => 'This email already exist, please try another.',
            'p_mobile_no.required' => 'Mobile number required',
            'p_mobile_no.unique' => 'This mobile number already exist, please try another.',
        ];

        $validator = \Validator::make($arrData, $rules, $customMessages);
        if($validator->fails()){
            $messages = $validator->getMessageBag()->toArray();
            $i=0;
            foreach ($messages as $key => $val) {
                $fieldName = $key;
                $mesageName = $val[0];
                if($fieldName=='rpo_custom_last_name' && $mesageName!='Last name required.'){
                    $this->errorImport[] = "'".$arrData['full_name']."' This user already exist, please try another. at line number #".$cnt;
                }elseif($fieldName=='p_email_address' && $mesageName!='Email required.' && $mesageName!='Please provide a valid email address.'){
                    $this->errorImport[] = "'".$arrData['p_email_address']."' This email address already exist, please try another. at line number #".$cnt;
                }elseif($fieldName=='p_mobile_no' && $mesageName!='Mobile number required'){
                    $this->errorImport[] = "'".$arrData['p_mobile_no']."' This mobile already exist, please try another. at line number #".$cnt;
                }else{
                    $this->errorImport[] = $mesageName.' at line number #'.$cnt;
                }
                $i++;
            }
        }
    }
    public function assignData($excelData,$arrColumn){
        $arr = array();
        foreach($arrColumn AS $key=>$val){
            $arr[$val] = $excelData[$key];
        }
        return $arr;
    }

    public function checkCoulmnMissing($expectedElements,$actualElements){
        $missingElements = array_diff($expectedElements, $actualElements);

        $elementsWithSpellingMistakes = [];
        foreach ($expectedElements as $expectedElement) {
            $matched = false;
            foreach ($actualElements as $actualElement) {
                if (strcasecmp($expectedElement, $actualElement) === 0) {
                    $matched = true;
                    break;
                }
            }
            if (!$matched) {
                $elementsWithSpellingMistakes[] = $expectedElement;
            }
        }
        // Display the results
        if (!empty($missingElements)) {
            return 'Missing Columns: ' . implode(', ', $missingElements) . "\n";
        }
        if (!empty($elementsWithSpellingMistakes)) {
            return 'Columns with spelling mistakes: ' . implode(', ', $elementsWithSpellingMistakes) . "\n";
        }
        return false;
    }
    
    public function getList(Request $request){
        $data=$this->_bploClients->getList($request);
        $arr=array();
        $i="0";
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $arr[$i]['account_no']=$row->account_no;
            $arr[$i]['no']= $j;  $status ="";
            $checkclientisused = $this->_bploClients->checkClienthasapplication($row->id);
            if(count($checkclientisused)<=0){
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a></div>';
             }
            $arr[$i]['rpt_owner']=$row->rpo_first_name.' ' .$row->rpo_middle_name .' '.$row->rpo_custom_last_name;
            if(!empty($row->suffix)){
              $arr[$i]['rpt_owner'] .=", ".$row->suffix;   
            }
           // $arr[$i]['barangay']=$row->brgy_code.'-' .$row->brgy_name;
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
 */
            
            $addressnew = wordwrap($this->_commonmodel->getTaxPayerAddress($row->id), 45, "<br />\n");
            $arr[$i]['rpo_address_house_lot_no']="<div class='showLess'>".$addressnew."</div>";
            $arr[$i]['p_mobile_no']=$row->p_mobile_no;

            $arr[$i]['online_access']='<a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bploclients/online_access?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Online Access"  data-title="Online Access" style="background: #1aaad9;color: #fff;">
                        view
                    </a>
                </div>';

            $arr[$i]['view']='<a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bploclients/view?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="View"  data-title="Business Establishment View" style="background: #1aaad9;color: #fff;">
                        view
                    </a>
                </div>';

            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
           
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">

                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bploclients/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Taxpayers">

                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                 </div>'.$status.'
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
        $data=array('is_bplo' => $is_activeinactive);
        $checkclientisused = $this->_bploClients->checkClienthasapplication($id);
        if(count($checkclientisused)<=0){
        $this->_bploClients->updateActiveInactive($id,$data);
        }
    }
    public function getProfileDetails(Request $request){
        $id= $request->input('pid');
        $data = $this->_bploClients->getProfileDetails($id);
        foreach ($this->_commonmodel->getBarangay($data->p_barangay_id_no)['data'] as $val) {
            $data->p_barangay_id_no = "<option value='".$val->id."' selected>".$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region."</option>";
        }

        echo json_encode($data);
    }
    
    public function getbusinssForOnlineAccess(Request $request){
        $is_checked=$request->input('is_checked');
        $client_id=$request->input('client_id');
        $search = $request->input('search');
        $arrRes = $this->_bploClients->getBploBusinessList($search,$is_checked,$client_id);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']='['.$val->busns_id_no."]=>[".$val->busn_name."]";
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function AddBusnOnlineAccess(Request $request){
        $user_id= \Auth::user()->id;
        $client_id=$request->input('client_id');
        $busn_id=$request->input('busn_id');
        $propData = DB::table('bplo_business')->select('busns_id_no','busn_tin_no','client_id')->where('id',$busn_id)->first();
      
        $data = array();
        $data['client_id'] = $client_id;
        $data['busn_id'] = $busn_id;
        $data['taxpayer_id'] = $propData->client_id;
        $data['is_active'] = 1;
        $data['created_by']=\Auth::user()->id;
        $data['created_at'] = date('Y-m-d H:i:s');
        DB::table('bplo_online_accesss')->insert($data);
        $lastId= DB::getPdo()->lastInsertId();
        session()->flash('success', 'Submit successfully');
        $data['ESTATUS']=1;
        $data['lastId']=$lastId;
        echo json_encode($data);exit;
    }
    public function updateOnlineAccessRemoteServer(Request $request){
        if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
            $data['ESTATUS']=1;
            $id=$request->input('id');
            $type = $request->input('type');
            $remortServer = DB::connection('remort_server');
            if($type=='save'){
                $pre_data = DB::table('bplo_online_accesss')->where('id',$id)->first();
                $data=(array)$pre_data;
                $data['is_synced'] = 1;
                $remortServer->table('bplo_online_accesss')->insert($data);
                DB::table('bplo_online_accesss')->where('id',$id)->update(array('is_synced'=>1));
                $data['message']="Remote data inserted successfully";
            }elseif($type=='delete'){
                $remortServer->table('bplo_online_accesss')->where('id',$id)->delete();
                $data['message']="Remote data deleted successfully";
            }
            echo json_encode($data);exit;
        }
    }

    public function getBusinessDtls(Request $request){
        $data = $this->_bploClients->getBusinessDtls($request->id);
        $arrOtherDtls = $this->_bploClients->checkAllTopPaidTransaction($request->id);
        $payment_status = "Pending";
        if(isset($arrOtherDtls)){
            if($arrOtherDtls->payment_status==1){
                $payment_status='Paid';
            }
        }
        $dataToReturn = [
            'busn_name' => (isset($data->busn_name))?$data->busn_name:'',
            'busn_trade_name' => (isset($data->busn_trade_name))?$data->busn_trade_name:'',
            'busn_tin_no' => (isset($data->busn_tin_no))?$data->busn_tin_no:'',
            'busn_registration_no' => (isset($data->busn_registration_no))?$data->busn_registration_no:'',
            'busns_id_no' => (isset($data->busns_id_no))?$data->busns_id_no:'',
            'brgy_name' => (isset($data->brgy_name))?$data->brgy_name:'',
            'full_name' => (isset($data->full_name))?$data->full_name:'',
            'btype_desc' => (isset($data->btype_desc))?$data->btype_desc:'',
            'payment_status' => $payment_status
        ];
        return response()->json($dataToReturn);
    }

    public function getBusniessOnlineAccess(Request $request){
        $client_id = $request->input('client_id');
        $data = $this->_bploClients->getBusniessOnlineAccess($client_id);
        $formattedData = [];
        $sr_no =0;
        foreach ($data as $row) {
            $sr_no = $sr_no + 1;
            $record = [
                'srno' => $sr_no,
                'busn_tin_no' => $row->busn_tin_no,
                'full_name' => $row->full_name,
                'busns_id_no' => $row->busns_id_no,
                'busn_name' => $row->busn_name,
                'busn_trade_name' => $row->busn_trade_name,
                'brgy_name' => $row->brgy_name,
                'action' => '<button style="background: red;" class="mx-2 btn btn-sm deleterow ti-trash text-red text-white" id='.$row->id.'></button>',
            ];
            if ($row->is_active == 0) {
                $record['class'] = 'red-row';
            } else {
                $record['class'] = '';
            }

            $formattedData[] = $record;
        }

        return response()->json(['data' => $formattedData]);
    }
    public function checkBusinessExist(Request $request){
        $busn_id = $request->input('busn_id');
        $client_id = $request->input('client_id');
        $check = DB::table('bplo_online_accesss')->where('busn_id', $busn_id)->where('client_id', $client_id)->first();
        if ($check) {
            return response()->json(['Exist' =>true], 200);
        } else {
            return response()->json(['Exist' => false], 200);
        }
    }
    public function deleteOnlineAccess(Request $request){
        $id = $request->input('id');
        $data = DB::table('bplo_online_accesss')->where('id',$id)->delete();
    }
    public function onlineAccess(Request $request){
        $data = (object)$this->data;
        $arrBussiness=array("Please Select");
        
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BploClients::where('id', $request->input('id'))->first();
        }
        $ownername = $data->full_name;
        $address = $this->_commonmodel->getTaxPayerAddress($request->input('id'));
        if($data->is_online == 1){
            $status = 'Online';
        }else{
              $status = 'Offline';
        }
        
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                unset($this->data['status']);
                $this->_bploClients->updateData($request->input('id'),$this->data);
                $success_msg = 'Cemetery updated successfully.';

            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['status'] = 1;
               
                $this->_bploClients->addData($this->data);
                $success_msg = 'Cemetery added successfully.';
            }
            return redirect('rpt-online-taxpayers')->with('success', __($success_msg));
        }
        return view('Bplo.clients.online_access',compact('data','ownername','status','arrBussiness','address'));
        
    }

    public function store(Request $request){
        //dd($request->isMethod('post'));
        $data = (object)$this->data;
        $arrgetBrgyCode = $this->arrgetBrgyCode;
        $arrgetCountries =$this->arrgetCountries;
        $arrgetClients = $this->arrgetClients;
        
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BploClients::find($request->input('id'));
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
                 $updatearay['is_bplo'] = '1';
            	 $this->_bploClients->updateData($request->input('clientsregistered'),$updatearay);
            	 $success_msg = 'Bplo Taxpayer Updated successfully.';
                 Session::put('remort_serv_session_det', ['table' => "clients",'action' =>"update",'id'=>$request->input('clientsregistered')]);
                 $client_id = $request->input('clientsregistered');
            }else {
	            if($request->input('id')>0){
	                $this->_bploClients->updateData($request->input('id'),$this->data);
                    $client_id = $request->input('id');
	                $success_msg = 'Bplo Taxpayer updated successfully.';
	            }else{
	                $this->data['created_by']=\Auth::user()->id;
	                $this->data['created_at'] = date('Y-m-d H:i:s');
	                $this->data['is_bplo'] = '1';
                    $this->data['is_rpt'] = '0';
                    $this->data['is_engg'] = '0';
                    $currentYear = date('Y');
                    $previousClientNo = $this->_bploClients->getMaxClientNoForYear($currentYear);
                    $clientNo = $previousClientNo + 1;
                    $this->data['client_year'] = $currentYear;
                    $this->data['client_no'] = $clientNo;
	                $client_id = $this->_bploClients->addData($this->data);
	                $success_msg = 'Bplo Taxpayer added successfully.';
                    Session::put('remort_serv_session_det', ['table' => "clients",'action' =>"store",'id'=>$request->input('clientsregistered')]);
	            }
	        }
            $this->_commonmodel->updateTaxPayerFullAddress($client_id);

            if($request->ajax()){
                return response()->json(['status'=>'success','msg'=>$success_msg]);
            }else{
                return redirect()->route('bploclients.index')->with('success', __($success_msg));
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
        return view('Bplo.clients.create',compact('data','arrgetBrgyCode','arrgetCountries','arrgetClients'));
        
    }
    
    public function formValidation(Request $request){
		/* if($request->input('id') > 0){
			$validator = \Validator::make(
				$request->all(), [
					'p_email_address' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix' 
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
			 'p_mobile_no'=>'required|unique:clients,p_mobile_no,'.$request->input('id'),
             'p_email_address' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix'			 
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
			
		} */

        $validator = \Validator::make(
            $request->all(),[
			 'rpo_custom_last_name' => 'required|unique:clients,rpo_custom_last_name,' .$request->input('id'). ',id,rpo_first_name,' .$request->input('rpo_first_name').',rpo_middle_name,' .$request->input('rpo_middle_name').',suffix,'.$request->input('suffix'), 
			 'p_email_address'=>'required|regex:/(.+)@(.+)\.(.+)/i',
			 "p_mobile_no" => "required|min:10|regex:/^[+]?[0-9\s-]*$/ ",
             ],[
				"rpo_custom_last_name.unique" => "The First Name, Middle Name, Last Name and Suffix has already been taken",
				"p_mobile_no.required" => "Mobile Number is required",
				"p_email_address.required" => "Email Address is required",
				"p_email_address.regex" => "Invalid Email Address",
                "p_mobile_no.min" => "Invalid mobile number, Please try another",
				"p_mobile_no.regex" => "Invalid mobile number, Please try another",
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
        if($RptPropertyOwner->created_by == \Auth::user()->id){
            $RptPropertyOwner->delete();
        }
    }
    public function uploadDocument(Request $request){
        
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = BploClients::find($healthCertId);
        
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->doc_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/bploclients/';
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
                $this->_bploClients->updateData($healthCertId,$data);
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
                                <a class='btn' href='".asset('uploads/bploclients').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
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
        $arrEndrosment = BploClients::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->doc_json,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/bploclients/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['doc_json'] = json_encode($arrJson);
                    $this->_bploClients->updateData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }
    public function view(Request $request){
        $client_id=$request->input('id');
        return view('Bplo.clients.view',compact('client_id'));
    }
    public function getViewList(Request $request){
        $data = $this->_bploClients->getEstabilshment($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;

        foreach ($data['data'] as $row){ 
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['busn_name']=$row->busn_name;
            $or_no='';
            $or_date='';
            $arrOr = $this->_bploClients->getLastORDetails($row->busn_id);
            if(isset($arrOr)){
                $or_no=$arrOr->or_no;
                $or_date=date("d M, Y",strtotime($arrOr->cashier_or_date));
            }
            $arr[$i]['last_or_no']=$or_no;
            $arr[$i]['last_or_date']=$or_date;
            $arr[$i]['payment_status']='<span style="color:red;">Pending</span>';
            $arrStatus = $this->_bploClients->getPaymentStatus($row->busn_id);
            if(isset($arrStatus)){
                if($arrStatus->payment_status==1){
                    $arr[$i]['payment_status']='<span style="color:green;">Paid</span>';
                }
            }
            if($row->app_code=='3'){
                $arr[$i]['srno']='<strike style="color:red;">'.$arr[$i]['srno'].' </strike>';
                $arr[$i]['busn_name']='<strike style="color:red;">'.$arr[$i]['busn_name'].' </strike>';
                $arr[$i]['last_or_no']='<strike style="color:red;">'.$arr[$i]['last_or_no'].' </strike>';
                $arr[$i]['last_or_date']='<strike style="color:red;">'.$arr[$i]['last_or_date'].' </strike>';
                $arr[$i]['payment_status']='<strike style="color:red;">'.$arr[$i]['payment_status'].' </strike>';
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

}
