<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\allaplicant;
use Auth;
use App\Models\User;
use App\Models\Plan;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class allaplicantController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrBarangay = array();
    public $arrMunCode = array();
    public $arrprofile = array(""=>"Select Owner ");
    public $arrSubclasses = array(""=>"Please Select");
    private $slugs;
	 public function __construct()
    {
       $this->_applicant = new allaplicant();
       $this->data = array('id'=>'','profile_id'=>'','isnew'=>'','modeofpayment'=>'','applicationdate'=>'','tinno'=>'','registartionno'=>'','registrationdate'=>'','typeofbussiness'=>'','amendmentfrom'=>'','amendmentto'=>'','enjoyingtax'=>'','fname'=>'','mname'=>'','lname'=>'','bussinessname'=>'','lotno'=>'','street'=>'','subdivision'=>'','barangay'=>'','municipality'=>'','tradename'=>'','bussinessaddress'=>'','billing_postalcode'=>'3132','billing_email'=>'','billing_telephone'=>'','billing_mobile'=>'','owneraddress'=>'','owner_postalcode'=>'','owner_email'=>'','owner_telephone'=>'','owner_mobile'=>'','contactname'=>'','conactmobileno'=>'','contactemail'=>'','bussinessarea'=>'','noofempestablish'=>'','noofempewithlgu'=>'','lessor_fullname'=>'','lessorlotno'=>'','lessorstreetname'=>'','lessorsubdivision'=>'','lessorbarangay'=>'','lessor_address'=>'','lessor_mobile'=>'','lessor_email'=>'','monthlyrental'=>'','lineofbussiness'=>'','noofunits'=>'','capitalization'=>'','essential'=>'','non_essential'=>'');  
        foreach ($this->_applicant->getBarangay() as $val) {
            $this->arrBarangay[$val->id]=$val->brgy_code.' - '.$val->brgy_name;
        } 
         foreach ($this->_applicant->getSubClasses() as $val) {
            $this->arrSubclasses[$val->id]=$val->subclass_description;
        } 
        $this->slugs = 'business-permit/application';
    }
     public function index()
    {
        $this->is_permitted($this->slugs, 'read'); 
        //$allaplicants = DB::table('allaplicants')->get();
        return view('allaplicant.index');
    }
    public function getList(Request $request){
        $data=$this->_applicant->getList($request);
        $arr=array();
        $i="0";    
        foreach ($data['data'] as $row){    
            $arr[$i]['applicantname']=$row->p_complete_name_v2;
            $arr[$i]['bussinessname']=$row->bussinessname;
            $arr[$i]['tradename']=$row->tradename;
            $arr[$i]['isnew']=$row->app_type;
            $arr[$i]['modeofpayment']=$row->modeofpayment;
            $arr[$i]['monthlyrental']=$row->monthlyrental;
            $arr[$i]['bussinessaddress']=$row->owneraddress;
            $arr[$i]['sl_no']=$i + 1;

            $arr[$i]['action']='<div class="action-btn bg-warning ms-2">
                    <a href="#" data-size="xll" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/business-permit/application/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Applicant">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>&nbsp;&nbsp;';
                if($row->is_approve==0){ 
                    //$arr[$i]['action'].='<span class="btn btn-success approve" id="'.$row->id.'">Approve</span>';
                    $arr[$i]['status']='<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Unapprove</span>';    
                }
                else{ 
                    $arr[$i]['status']='<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Approve</span>';
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

    public function updateapprove(){
    	$sid = array('id' => $_POST['applicantid']);
        $applidata = array('is_approve' => '1');
        $this->_applicant->updateapplicantData($sid, $applidata);
        //return redirect()->route('allaplicant.index')->with('success', __('Customer Approved Successfully.'));
    }

     public function store(Request $request)
    {
        $arrSubclasses = $this->arrSubclasses;
        $arrBarangay = $this->arrBarangay;
        $arrtypes = array();   $bussinesstype =array(); $arrNature = array();
        $arrtypes[''] ="Select Type";
        foreach ($this->_applicant->apptypes() as $val) {
           $arrtypes[$val->id]=$val->app_type;
        }
        foreach ($this->_applicant->getprofiles() as $val) {
            $this->arrprofile[$val->id]=$val->p_first_name.' '.$val->p_middle_name.' '.$val->p_family_name;
        } 
        $bussinesstype[''] ="Select Business";
        foreach ($this->_applicant->bussinesstypes() as $val) {
           $bussinesstype[$val->id]=$val->bussiness_type;
        }
        foreach ($this->_applicant->getprofileProvcodeId() as $val) {
               $this->arrMunCode[$val->id]=$val->mun_no."-".$val->mun_desc;
        }
        $arrMunCode = $this->arrMunCode;
        $profile = $this->arrprofile;
        $lenssorbarangay = array();
         foreach ($this->_applicant->getBarangayapp() as $val) {
            $lenssorbarangay[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region.  " [".$val->reg_no.$val->prov_no.$val->mun_no.$val->brgy_code."]";
        } 
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
           $data = allaplicant::find($request->input('id'));
           $arrNature = $this->_applicant->getActivitydetialData($data->id);
                
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $data = allaplicant::find($request->input('id'));
                $this->data['updated_by']=\Auth::user()->creatorId();
                $this->data['updated_at'] = date('Y-m-d H:i:s');
                $this->_applicant->updateData($request->input('id'),$this->data);
                $checkprofileexist = $this->_applicant->checkprofile($this->data['owner_email'],$this->data['owner_mobile']);
                $success_msg = 'Applicant updated successfully.';
                $lastinsertid = $request->input('id');

            }else{
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['created_by']=\Auth::user()->creatorId();
                $lastinsertid = $this->_applicant->addData($this->data);
                //echo $this->data['owner_email'] ="marutidevkate@gmail.com"; echo $this->data['owner_mobile'];
                $checkprofileexist = $this->_applicant->checkprofile($this->data['owner_email'],$this->data['owner_mobile']);
                $dataprofile['p_first_name'] =$this->data['fname'];
                $dataprofile['p_middle_name'] = $this->data['mname'];
                $dataprofile['p_family_name'] = $this->data['lname'];
                $dataprofile['p_complete_name_v1'] = $this->data['lname']." ".$this->data['mname']." ".$this->data['fname'];
                $dataprofile['p_complete_name_v2'] = $this->data['fname']." ".$this->data['mname']." ".$this->data['lname'];
                $dataprofile['p_address_house_lot_no'] = "";
                $dataprofile['p_address_street_name'] = $this->data['bussinessaddress'];
                $dataprofile['p_telephone_no'] = $this->data['owner_telephone'];
                $dataprofile['p_mobile_no'] = $this->data['owner_mobile'];
                $dataprofile['p_tin_no'] = $this->data['tinno'];
                $dataprofile['p_email_address'] = $this->data['owner_email'];
                $dataprofile['c_code'] = 'Filipino';
                $dataprofile['p_registered_date'] = date('Y-m-d H:i:s');
                $dataprofile['p_registered_by']=\Auth::user()->creatorId();
                $dataprofile['p_modified_by']=\Auth::user()->creatorId();
                $dataprofile['ba_business_name'] = $this->data['bussinessname'];
                $dataprofile['updated_at'] = date('Y-m-d H:i:s');
                $dataprofile['created_at'] = date('Y-m-d H:i:s');
                
                if(count($checkprofileexist) > 0){   
                    if(!empty($checkprofileexist[0]->applicantids)){ $appids = $checkprofileexist[0]->applicantids.",".$lastinsertid;}
                    else{ $appids =$lastinsertid; }
                    $dataprofile['applicantids'] =$lastinsertid;
                    $this->_applicant->updateProfileData($checkprofileexist[0]->id,$dataprofile);
                }else{
                    $dataprofile['applicantids'] =$lastinsertid;
                   $this->_applicant->addProfileData($dataprofile); 
                }
                $success_msg = 'Applicant added successfully.';
            }
             //echo "<pre>"; print_r($_POST); exit;
                if(!empty($_POST['natureofbussiness'])){
                    $loop = count($_POST['natureofbussiness']);
                     $activitydetail = array();
                  
                    for($i=0; $i<$loop;$i++){
                        $activitydetail['applicantid'] = $lastinsertid;
                        $activitydetail['natureofbussiness'] = $_POST['natureofbussiness'][$i];
                        $activitydetail['noofunits'] = $_POST['noofunits'][$i];
                        $activitydetail['capitalization'] = $_POST['capitalization'][$i];
                        $activitydetail['essestial'] = $_POST['essestial'][$i];
                        $activitydetail['nonessestial'] = $_POST['nonessestial'][$i];
                        if(!empty($_POST['activityid'][$i])){
                            $this->_applicant->updateApplicationActivity($_POST['activityid'][$i],$activitydetail);
                        }else{
                            $this->_applicant->addApplicationActivity($activitydetail);
                        }
                    }
                 }
            return redirect()->route('bp.application.index')->with('success', __($success_msg));
        }
        return view('allaplicant.create',compact('data','arrtypes','bussinesstype','arrBarangay','arrMunCode','arrNature','arrSubclasses','profile','lenssorbarangay'));
       
    }

    public function storenew(){
         $allvalues = $request->all();
          //print_r($allvalues); exit;

            $rules = [
                'isnew' => 'required',
                'billing_telephone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                'billing_email' => 'required|email',
            ];


            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->route('allaplicant.index')->with('error', $messages->first());
            }
            $applicant                  = new allaplicant();
            $applicant->isnew           = $request->isnew;
            $applicant->modeofpayment  = $request->modeofpayment;
            $applicant->applicationdate = $request->applicationdate;
            $applicant->tinno         = $request->tinno;
            $applicant->registartionno    = $request->registartionno;
            $applicant->registrationdate    = $request->registrationdate;
            $applicant->typeofbussiness    = $request->typeofbussiness;
            $applicant->amendmentto    = $request->amendmentto;
            $applicant->amendmentfrom    = $request->amendmentfrom;
            $applicant->enjoyingtax    = $request->enjoyingtax;
            $applicant->lname    = $request->lname;
            $applicant->fname    = $request->fname;
            $applicant->mname    = $request->mname;
            $applicant->bussinessname    = $request->bussinessname;
            $applicant->tradename    = $request->tradename;
            $applicant->bussinessaddress    = $request->bussinessaddress;
            $applicant->billing_postalcode    = $request->billing_postalcode;
            $applicant->billing_email    = $request->billing_email;
            $applicant->billing_telephone    = $request->billing_telephone;
            $applicant->billing_mobile    = $request->billing_mobile;
            $applicant->owneraddress    = $request->owneraddress;
            $applicant->owner_postalcode    = $request->owner_postalcode;
            $applicant->owner_email    = $request->owner_email;
            $applicant->owner_telephone    = $request->owner_telephone;
            $applicant->owner_mobile    = $request->owner_mobile;
            $applicant->contactname    = $request->contactname;
            $applicant->conactmobileno    = $request->conactmobileno;
            $applicant->contactemail    = $request->contactemail;
            $applicant->bussinessarea    = $request->bussinessarea;
            $applicant->noofempestablish    = $request->noofempestablish;
            $applicant->noofempewithlgu    = $request->noofempewithlgu;
            $applicant->lessor_fullname    = $request->lessor_fullname;
            $applicant->lessor_address    = $request->lessor_address;
            $applicant->lessor_mobile    = $request->lessor_mobile;
            $applicant->lessor_email    = $request->lessor_email;
            $applicant->monthlyrental    = $request->monthlyrental;
            $applicant->lineofbussiness    = $request->lineofbussiness;
            $applicant->noofunits    = $request->noofunits;
            $applicant->capitalization    = $request->capitalization;
            $applicant->essential    = $request->essential;
            $applicant->non_essential    = $request->non_essential;
            $applicant->save();
               
            
            return redirect()->route('allaplicant.index')->with('success', __('Applicant successfully created.'));
    }

     public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'billing_email'=>'required|unique:allaplicants,billing_email,'.$request->input('id'),
                'tradename' =>'required|unique:allaplicants,tradename,'.$request->input('id'),
                'modeofpayment'=>'required',
                'applicationdate'=>'required',
                'tinno'=>'required',
                'registartionno'=>'required',
                'typeofbussiness'=>'required',
                'bussinessname'=>'required'
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

    public function getprofiles(Request $request){
         $id= $request->input('pid');  $apptype = '1';
         $data = $this->_applicant->getProfiledata($id);
         echo json_encode($data);
    }

     public function printapplication(){
     		$aid = $_POST['applicantid'];
     		$applicant = allaplicant::find($aid);
     		// echo $applicant['modeofpayment'];
     		// echo "<pre>"; print_r($applicant); exit;
     	    $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            // $mpdf->debug = true;
            // $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $filename="";
            $html = file_get_contents(resource_path('views/layouts/templates/customerinv.html'));
            $html = str_replace('{DATEOFAPPLICATION}', $applicant['applicationdate'], $html); $isnewchecked=""; $renewalchecked=""; $taxenjoy ="";
            if($applicant['isnew'] == '1'){ $isnew ="<span>New:Yes</span>";} else{ $isnew ="<span>Renewal:Yes</span>";}
            $html = str_replace('{ISNEW}', $isnew, $html);
            $html = str_replace('{MODEOFPAYMENT}', $applicant['modeofpayment'], $html);
            $html = str_replace('{TINNO}', $applicant['tinno'], $html);
            $html = str_replace('{REGISTARATIONNO}', $applicant['registartionno'], $html);
            $html = str_replace('{REGISTARATIODATE}', $applicant['applicationdate'], $html);
            $html = str_replace('{TYPEBUSSINESS}',$applicant['typeofbussiness'], $html);
            $html = str_replace('{FROM}',$applicant['amendmentfrom'], $html);
            $html = str_replace('{TO}', $applicant['amendmentto'], $html);
            if($applicant['enjoyingtax'] == '1'){ $taxenjoy ="Yes";}else{ $taxenjoy ="No";}
            $html = str_replace('{TAXENJOY}', $taxenjoy, $html);
            $html = str_replace('{LNAME}', $applicant['lname'], $html);
            $html = str_replace('{FNAME}', $applicant['fname'], $html);
            $html = str_replace('{MNAME}', $applicant['mname'], $html);
            $html = str_replace('{BUSSINESSNAME}', $applicant['bussinessname'], $html);
            $html = str_replace('{TRADENAME}', $applicant['tradename'], $html);
            $html = str_replace('{BUSSINESSADDRESS}', $applicant['bussinessaddress'], $html);
            $html = str_replace('{POSTALCODE}', $applicant['billing_postalcode'], $html);
            $html = str_replace('{EMAILADDRESS}', $applicant['billing_email'], $html);
            $html = str_replace('{TELEPHONE}', $applicant['billing_telephone'], $html);
            $html = str_replace('{MOBILE}', $applicant['billing_mobile'], $html);
            $html = str_replace('{OWNERADDRESS}', $applicant['owneraddress'], $html);
            $html = str_replace('{POSTALCODEOW}', $applicant['owner_postalcode'], $html);
            $html = str_replace('{EMAILADDRESSOW}', $applicant['owner_email'], $html);
            $html = str_replace('{TELEPHONEOW}', $applicant['owner_telephone'], $html);
            $html = str_replace('{MOBILEOW}', $applicant['owner_mobile'], $html);
            $html = str_replace('{CONATCTPERSON}', $applicant['contactname'], $html);
            $html = str_replace('{CONTACTPMOBILE}', $applicant['conactmobileno'], $html);
            $html = str_replace('{CONATCTEAMIL}', $applicant['contactemail'], $html);
            $html = str_replace('{BAREA}', $applicant['bussinessarea'], $html);
            $html = str_replace('{EMPESTA}', $applicant['noofempestablish'], $html);
            $html = str_replace('{EMPLGU}', $applicant['noofempewithlgu'], $html);
            $html = str_replace('{LFULLNAME}', $applicant['lessor_fullname'], $html);
            $html = str_replace('{LFULLADDRESS}', $applicant['lessor_address'], $html);
            $html = str_replace('{LTELENO}', $applicant['lessor_mobile'], $html);
            $html = str_replace('{LEMAILADDRESS}', $applicant['lessor_email'], $html);
            $html = str_replace('{MONTHLYRENTAL}', $applicant['monthlyrental'], $html);
            $html = str_replace('{LINEOFBUSSINESS}', $applicant['lineofbussiness'], $html);
            $html = str_replace('{NOOFUNITS}', $applicant['noofunits'], $html);
            $html = str_replace('{CAPITALIZATION}', $applicant['capitalization'], $html);
            $html = str_replace('{ESSENTIAL}', $applicant['essential'], $html);
            $html = str_replace('{NONESSESTIAL}', $applicant['non_essential'], $html);
            $mpdf->WriteHTML($html);
            $applicantname = $applicant['lname'].$applicant['fname'].".pdf";
            $folder =  public_path().'/uploads/application/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            $filename = public_path() . "/uploads/application/" . $applicantname;
            $mpdf->Output($filename, "F");
            @chmod($filename, 0777);
            echo url('/uploads/application/' . $applicantname);
    }

     public function show($ids)
    {
        $id       = \Crypt::decrypt($ids);
        $customer = Customer::find($id);

        return view('customer.show', compact('customer'));
    }
}
