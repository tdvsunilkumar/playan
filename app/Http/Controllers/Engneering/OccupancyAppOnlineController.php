<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\Engneering\OccupancyAppOnline;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use File;
use PDF;
use DB;
use Carbon\Carbon;

class OccupancyAppOnlineController extends Controller
{
     public $data = [];
     public $postdata = [];
     public $arrgetBrgyCode = array(""=>"Please Select");
     public $arrOwners = array(""=>"Please Select");
     public $arrPermitno = array(" "=>"Please Select");
     public $arrTypeofOccupancy = array();
     public $arrRequirements = array();  
     public $getServices = array();
     public $requirements = array();
       public function __construct(){
		$this->_engoccupancyapp= new OccupancyAppOnline(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','ebpa_id'=>'','eoa_application_type'=>'','dateissued'=>'','eoa_date_paid'=>'','tfoc_id'=>'','client_id'=>'','p_mobile_no'=>'','rpo_address_house_lot_no'=>'','rpo_address_street_name'=>'','rpo_address_subdivision'=>'','brgy_code'=>'','eoa_building_structure'=>'','nameofproject'=>'','ebpa_location'=>'','ebot_id'=>'','ebfd_no_of_storey'=>'','no_of_units'=>'','ebfd_floor_area'=>'','eoa_date_of_completion'=>'','eoa_floor_area'=>'','eoa_firstfloorarea'=>'','eoa_secondfloorarea'=>'','eoa_lotarea'=>'','eoa_perimeter'=>'','eoa_projectcost'=>'','eoa_surcharge_fee'=>'','eoa_total_net_amount'=>'','eoa_total_fees'=>'');  
        $this->slugs ='engoccupancyapp';
        $this->transaction_id = 10;
        // foreach ($this->_engoccupancyapp->getBarangay() as $val) {
        //     $this->arrgetBrgyCode[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
        // }
         foreach ($this->_engoccupancyapp->getOwners() as $val) {
             $this->arrOwners[$val->id]=$val->rpo_first_name." ".$val->rpo_middle_name." ".$val->rpo_custom_last_name;
         }
        foreach ($this->_engoccupancyapp->getRptOwners() as $val) {
             $this->arrlotOwner[$val->id]=$val->rpo_first_name." ".$val->rpo_middle_name." ".$val->rpo_custom_last_name;
         }
         foreach ($this->_engoccupancyapp->GetTypeofOccupancy() as $val) {
             $this->arrTypeofOccupancy[$val->id]=$val->ebot_description;
         }
         foreach ($this->_engoccupancyapp->GetBuildingpermits() as $val) {
             $this->arrPermitno[$val->ebpa_permit_no]=$val->ebpa_permit_no;
         }
         foreach ($this->_engoccupancyapp->getSercviceRequirements() as $val) {
             $this->requirements[$val->id]=$val->req_code_abbreviation."-".$val->req_description;
         }
        foreach ($this->_engoccupancyapp->getServices() as $val) {
             $this->getServices[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
         }           
     }
     
    public function index(Request $request)
    {
        $barangay=array(""=>"Please select");
        $getmincipalityid = $this->_engoccupancyapp->getOccumunciapality(); $munid ="";
            if(!empty($getmincipalityid)){ $munid = $getmincipalityid->mun_no;}
        foreach ($this->_engoccupancyapp->getBarangaybymunno($munid) as $val) {
             $barangay[$val->id]=$val->brgy_name;
         }
        // print_r($barangay); exit;
        $to_date=Carbon::now()->format('Y-m-d');
        $from_date=Carbon::now()->format('Y-m-d');
        $this->is_permitted($this->slugs, 'read');
            return view('Engneering.engoccupancyonline.index',compact('barangay','to_date','from_date'));
           
    }

   public function Declineapplication(Request $request){
        $id=$request->input('appid');
        $updatearray = array("is_approved"=>"2");
        $this->_engoccupancyapp->updateData($id,$updatearray);
    }

    public function approve(Request $request)
    {  $id=$request->input('appid');
        $data=$this->_engoccupancyapp->approve($id);
        return response()->json([
            'data' =>$data
        ]);
    }

    public function syncapptoremote(Request $request){
        $id=$request->input('id');
        $data=$this->_engoccupancyapp->syncapptoremote($id);
        if(isset($data)){
             Session::forget('REMOTE_SYNC_APPFORMID');
        }
        return response()->json([
            'data' =>$data
        ]);
    }

    public function getbuidingdata(Request $request){
        $permitid = $request->input('permitid');
        $data = $this->_engoccupancyapp->getbuidingpermitdata($permitid);
        echo json_encode($data);
    }
     

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_engoccupancyapp->getList($request);
        $arrPermitno = $this->arrPermitno;
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['ebpa_id']=$row->ebpa_id;
            $arr[$i]['ownername']=$row->rpo_first_name." ".$row->rpo_middle_name." ".$row->rpo_custom_last_name;
            //$addressnew = wordwrap($this->_commonmodel->getTaxPayerAddress($row->client_id), 40, "<br />\n");
            $barngayAddress = $this->_commonmodel->getBarangayname($row->location_brgy_id); 
            $barngayName = "";
            if(!empty($barngayAddress)){
               $barngayName = $barngayAddress->brgy_name;
            }
            $arr[$i]['barangay']=$barngayName;
            $arr[$i]['eoa_application_type']="Occupancy Service";
            $appsereviceNo = date('Y').'-'.str_pad($row->id, 4, '0', STR_PAD_LEFT);
            $arr[$i]['appno']=$appsereviceNo;
             if($row->is_approved == '0'){
                $status = '<span class="btn btn-info" style="padding: 0.1rem 0.5rem !important;">Pending</span>';
            }
            if($row->is_approved == '2'){
                $status = '<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Declined</span>';
            }
            if($row->is_approved == '1'){
                $status = '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Approved</span>';
            } 
            $arr[$i]['is_active']=$status;
            $startCarbon = Carbon::parse($row->created_at);
            $endCarbon = date('Y-m-d');
            $diff = $startCarbon->diff($endCarbon);
            if ($diff->days == 0) {
                $duration = "0 Day";
            } 
            elseif($diff->days == 1) {
                $duration = $diff->days . " Day";
            } else {
                $duration = $diff->days . " Days";
            }
            $arr[$i]['duration']=$duration;  
            $arr[$i]['date']=$row->created_at;                
            $arr[$i]['method']='Online';
            $arr[$i]['action']='
                <div class="action-btn bg-success ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engoccupancyapponline/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Occupancy Permit: Online Application">
                        <i class="ti-eye text-white"></i>
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

     public function store(Request $request){
        $data = (object)$this->data;
        $arrgetBrgyCode = $this->arrgetBrgyCode;
        $arrOwners = $this->arrOwners;
        $arrRequirements = $this->arrRequirements;
        $requirements = $this->requirements;
        $arrlocgetBrgyCode ="";
        $arrTypeofOccupancy = $this->arrTypeofOccupancy;
        $getServices = $this->getServices;
        $arrPermitno = $this->arrPermitno;
        $defaultFeesarr = $this->_engoccupancyapp->GetDefaultfees();
        $extrafeearr = array();
        $arrRequirements = $this->_engoccupancyapp->getJobRequirementsdefault(30);
          if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_engoccupancyapp->getdataforedit($request->input('id'));
             $arrRequirements = $this->_engoccupancyapp->getJobRequirementsData($data->frgn_eoa_id);
            $defaultFeesarr = $this->_engoccupancyapp->GetRequestfees($request->input('id')); 
            $arrgetBrgyCode = array();
            foreach ($this->_engoccupancyapp->getBarangay($data->brgy_code) as $val) {
              $arrgetBrgyCode[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
             }
             foreach ($this->_commonmodel->getBarangay($data->location_brgy_id)['data'] as $val) {
                $arrlocgetBrgyCode=$val->brgy_name;
            }
          }
          $getextrafees = $this->_engoccupancyapp->getextrafees();
          foreach ($getextrafees as $key => $value) {
              $extrafeearr[$value->description."#".$value->id] = $value->description;
          }
          $userroleid = "";
            $getroleofuserdata = $this->_engoccupancyapp->getUserrole(\Auth::user()->id);
            if(count($getroleofuserdata) > 0){
               $userroleid = $getroleofuserdata[0]->id; 
            }
          //print_r($getextrafees); exit;
		   if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $cashdata = $this->_engoccupancyapp->getCasheringIds($this->data['tfoc_id']);
            $this->data['agl_account_id'] = $cashdata->gl_account_id;
            $this->data['sl_id'] = $cashdata->sl_id;
            $this->data['surcharge_gl_id'] = $cashdata->tfoc_surcharge_gl_id;
            $this->data['surcharge_sl_id'] = $cashdata->tfoc_surcharge_sl_id;
            $this->data['is_active'] = '1';
            $this->data['eoa_projectcost'] = str_replace(",", "", $this->data['eoa_projectcost']);
            $this->data['eoa_surcharge_fee'] = str_replace(",", "", $this->data['eoa_surcharge_fee']);
            $this->data['eoa_total_net_amount'] = str_replace(",", "", $this->data['eoa_total_net_amount']);
            $this->data['eoa_total_fees'] = str_replace(",", "", $this->data['eoa_total_fees']);
             if($request->input('id')>0){
                $this->data['updated_by']=\Auth::user()->id;
                $this->data['updated_at'] = date('Y-m-d H:i:s');
                $this->_engoccupancyapp->updateData($request->input('id'),$this->data);
                $success_msg = 'Engineering Occupancy App updated successfully.';
                $eoa_application_no = $_POST['eoa_application_no'];
                $appid = $request->input('id');
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');

                $appid = $this->_engoccupancyapp->addData($this->data);
                $appsereviceNo = date('Y').'-'.str_pad($appid, 4, '0', STR_PAD_LEFT);
                $updateData2= array('eoa_application_no'=>$appsereviceNo);
                $this->_engoccupancyapp->updateData($appid,$updateData2);
                $success_msg = 'Engineering Occupancy App added successfully.';
                $eoa_application_no = $appid;
            }
           
            $netamount = 0;
            if(!empty($_POST['feesdesc'])){
            foreach ($_POST['feesdesc'] as $key => $value) {
                    $jobfeesdetails =array();
                     $jobfeesdetails['eoa_id'] =$appid;
                     if($_POST['istfocid'][$key] == 0){
                       $jobfeesdetails['tfoc_id'] = $_POST['tfoc_id']; 
                       $jobfeesdetails['is_default'] = '0';
                       $jobfeesdetails['fees_description'] = $value; 
                     }else{
                        $jobfeesdetails['is_default'] = '1';
                        $feedata = explode('#',$value);
                        if(count($feedata) > 1){
                           $jobfeesdetails['fees_description'] = $feedata[0];
                           $jobfeesdetails['tfoc_id'] = $feedata[1];   
                        }else{
                            $jobfeesdetails['fees_description'] = $feedata[0];
                        }
                     }
                     $jobfeesdetails['agl_account_id'] = $cashdata->gl_account_id;
                     $jobfeesdetails['sl_id'] = $cashdata->sl_id;
                     //$jobfeesdetails['tax_amount'] = $_POST['amountfee'][$key];
                     $jobfeesdetails['tax_amount'] = str_replace(",", "", $_POST['amountfee'][$key]);
                    // $netamount = $netamount + $_POST['amountfee'][$key];
                     $jobfeesdetails['updated_by']=\Auth::user()->id;
                     $jobfeesdetails['updated_at'] = date('Y-m-d H:i:s');
                     $checkexist = $this->_engoccupancyapp->checkoccupancyFeesDetail($appid,$value);
                     if(count($checkexist) > 0){
                            $this->_engoccupancyapp->updateoccupancyFeesDetailData($checkexist[0]->id,$jobfeesdetails);
                     }else{
                        $jobfeesdetails['created_by']=\Auth::user()->id;
                        $jobfeesdetails['created_at'] = date('Y-m-d H:i:s');
                        $this->_engoccupancyapp->addoccupancyFeesDetailData($jobfeesdetails);
                     }
                 }
             }
            
            //echo "<pre>"; print_r($_POST);  exit;//print_r($_FILES); exit;
             if(!empty($_POST['reqid'])){  
                foreach ($_POST['reqid'] as $key => $value) {
                    $jobreqarr = array();
                    $jobreqarr['eoa_id'] = $appid;
                    $jobreqarr['req_id'] = $_POST['reqid'][$key];
                    $jobreqarr['created_by']=\Auth::user()->id;
                    $jobreqarr['created_at'] = date('Y-m-d H:i:s');
                    $checkexistreq = $this->_engoccupancyapp->checkOccupancyRequirementsexist($request->input('id'),$_POST['reqid'][$key]);
                    if(count($checkexistreq) > 0){
                        $lastinsertid = $checkexistreq[0]->id;
                    }else{ $lastinsertid = $this->_engoccupancyapp->addOccupancyRequirementsData($jobreqarr); }

                   if(isset($request->file('reqfile')[$key])){  
                         if($image = $request->file('reqfile')[$key]){
                           $reqid= $_POST['reqid'][$key];
                         $destinationPath =  public_path().'/uploads/engineering/occupancy/'.$eoa_application_no.'/'.$reqid;
                            if(!File::exists($destinationPath)){ 
                                File::makeDirectory($destinationPath, 0755, true, true);
                            }
                         $filename =  $eoa_application_no.'requirement'.$lastinsertid;  
                         $filename = str_replace(" ", "", $filename);   
                         $requirementpdf = $filename. "." . $image->extension();
                         $extension =$image->extension();
                         $image->move($destinationPath, $requirementpdf);
                         
                        // print_r($image); exit;
                         $filearray = array();
                         $filearray['eoar_id'] = $lastinsertid;
                         $filearray['eoa_id'] =  $eoa_application_no;
                         $filearray['fe_name'] = $requirementpdf;
                         $filearray['fe_type'] = $extension;
                        // $filearray['fe_size'] = $_FILES['reqfile'.$reqid]['size'];
                         $filearray['fe_path'] = 'engineering/occupancy//'.$eoa_application_no.'/'.$reqid;
                         $filearray['created_by']=\Auth::user()->id;
                         $filearray['created_at'] = date('Y-m-d H:i:s');
                         $checkimageexits = $this->_engoccupancyapp->checkRequirementfileexist($reqid);
                          if(!empty($_POST['foid'][$key])){
                            $this->_engoccupancyapp->UpdateengFilesData($_POST['foid'][$key],$filearray);
                         }else{ $this->_engoccupancyapp->AddengFilesData($filearray); }
                         
                         // echo $profileImage;
                         }
                     }
                }
             }
            return redirect()->route('engoccupancyapp.index')->with('success', __($success_msg));
    	   }
    	
        return view('Engneering.engoccupancyonline.create',compact('data','arrgetBrgyCode','arrOwners','arrTypeofOccupancy','arrRequirements','requirements','defaultFeesarr','getServices','arrPermitno','extrafeearr','userroleid','arrlocgetBrgyCode'));
	}

	 public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'client_id'=>'required',
                'rpo_address_street_name'=>'required',
                'rpo_address_subdivision'=>'required',
                'ebpa_id'=>'required',
                'ebpa_location'=>'required',
            ]
            ,
            [
                'client_id.required' => 'Client is required',
                'rpo_address_subdivision.required'=>'Required',
                'ebpa_id.required'=>'Required',
                'ebpa_location.required'=>'Required',
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
