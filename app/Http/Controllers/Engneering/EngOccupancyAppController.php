<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\Engneering\EngOccupancyApp;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage;
use File;
use PDF;
use DB;
use Carbon\Carbon;
use Session;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;

class EngOccupancyAppController extends Controller
{
     public $data = [];
     public $postdata = [];
     public $arrgetBrgyCode = array(""=>"Please Select");
     public $arrOwners = array(""=>"Please Select");
     public $arrPermitno = array(" "=>"Please Select");
     public $arrTypeofOccupancy = array();
     public $arrRequirements = array();  
     public $getServices = array();
     public $requirements = array(" "=>"Please Select");
     private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
     private $carbon;
       public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
		$this->_engoccupancyapp= new EngOccupancyApp(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->data = array('id'=>'','ebpa_id'=>'','eoa_application_type'=>'','dateissued'=>'','eoa_date_paid'=>'','tfoc_id'=>'','client_id'=>'','p_mobile_no'=>'','rpo_address_house_lot_no'=>'','rpo_address_street_name'=>'','rpo_address_subdivision'=>'','brgy_code'=>'','eoa_building_structure'=>'','nameofproject'=>'','location_brgy_id'=>'','ebot_id'=>'','ebfd_no_of_storey'=>'','no_of_units'=>'','ebfd_floor_area'=>'','eoa_date_of_completion'=>'','eoa_floor_area'=>'','eoa_firstfloorarea'=>'','eoa_secondfloorarea'=>'','eoa_lotarea'=>'','eoa_perimeter'=>'','eoa_projectcost'=>'','eoa_surcharge_fee'=>'','eoa_total_net_amount'=>'','eoa_total_fees'=>'');  
        $this->slugs ='engoccupancyapp';
        $this->transaction_id = 10;
        // foreach ($this->_engoccupancyapp->getBarangay() as $val) {
        //     $this->arrgetBrgyCode[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
        // }
        
         foreach ($this->_engoccupancyapp->getOwners() as $val) {
             $this->arrOwners[$val->id]=$val->full_name;
         }
        foreach ($this->_engoccupancyapp->getRptOwners() as $val) {
             $this->arrlotOwner[$val->id]=$val->full_name;
         }
         foreach ($this->_engoccupancyapp->GetTypeofOccupancy() as $val) {
             $this->arrTypeofOccupancy[$val->id]=$val->ebot_description;
         }
         // foreach ($this->_engoccupancyapp->GetBuildingpermits() as $val) {
         //     $this->arrPermitno[$val->ebpa_permit_no]=$val->ebpa_permit_no;
         // }
         foreach ($this->_engoccupancyapp->getSercviceRequirements() as $val) {
             $this->requirements[$val->id]=$val->req_code_abbreviation."-".$val->req_description;
         }

        foreach ($this->_engoccupancyapp->getServices() as $val) {
             $this->getServices[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
         }           
     }
     public function GetBuildingpermitsAjax(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_engoccupancyapp->GetBuildingpermitsAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            if($val->ebpa_permit_no > 0){
            $arr['data'][$key]['id']=$val->ebpa_permit_no;
            $arr['data'][$key]['text']=$val->ebpa_permit_no;
           }
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function getSercviceRequirementsAjax(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_engoccupancyapp->getSercviceRequirementsAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->req_code_abbreviation." - ".$val->req_description;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
         $barangay=array(""=>"Please select"); 
         $getmincipalityid = $this->_engoccupancyapp->getOccumunciapality(); $munid ="";
            if(!empty($getmincipalityid)){ $munid = $getmincipalityid->mun_no;}
            foreach ($this->_engoccupancyapp->getBarangaybymunno($munid) as $val) {
                 $barangay[$val->id]=$val->brgy_name;
             }
            $methods = array(''=>'Select Method','1' => 'Online','0' => 'Walkin');
            $to_date=Carbon::now()->format('Y-m-d');
            $from=Carbon::now();
            $oneMonthBefore = $from->subMonth();
            $from_date = $oneMonthBefore->format('Y-m-d');
            return view('Engneering.engoccupancy.index',compact('barangay','to_date','from_date','methods'));
           
    }

    public function MakeapprovePermit(Request $request){
        $id= $request->input('id');
        $jobreqarray =array('eoa_opd_approved_by'=>\Auth::user()->id,'is_approve'=>'1');
        $this->_engoccupancyapp->updateData($request->input('id'),$jobreqarray);
        $gettopdata = $this->_engoccupancyapp->checkTransexist($id,'10'); 
        if(count($gettopdata) > 0){
                $arr[$i]['topno']=$gettopdata[0]->transaction_no;
                $this->_engoccupancyapp->updateremotedata($id,$updateremotedata); 
        }
        $smsTemplate=SmsTemplate::where('id',48)->where('is_active',1)->first();
        $arrData = $this->_engoccupancyapp->GetOccupancyAppData($request->input('id'));
        if(!empty($smsTemplate) && $arrData->p_mobile_no != null)
            {
                $receipient=$arrData->p_mobile_no;
                $msg=$smsTemplate->template;
                $msg = str_replace('<NAME>', $arrData->full_name,$msg);
                $msg = str_replace('<REFERENCE_NO>', $arrData->ebpa_id,$msg);
                $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
               $this->send($msg, $receipient);
            }
        $array =array();
        $array['status'] = "success";
        $array['permitid'] = "";
        echo json_encode($array);
    }

    public function send($message, $receipient)
    {   
        $validate = $this->componentSMSNotificationRepository->validate();
        if ($validate > 0) {
            $setting = $this->componentSMSNotificationRepository->fetch_setting();
            $details = array(
                'message_type_id' => 1,
                'masking_code' => $setting->mask->code,
                'messages' => $message,
                'created_at' => $this->carbon::now(),
                'created_by' => \Auth::user()->id
            );
            $message = $this->componentSMSNotificationRepository->create($details);
           
                //$this->sendSms($receipient, $message);
                $this->componentSMSNotificationRepository->send($receipient, $message);

            return true;
        } else {
            return false;
        }
    }

     public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $eid = $request->input('eid');
        $arrRequirements = $this->_engoccupancyapp->getRequirementsbyid($rid);
        if(count($arrRequirements) > 0){
            if($arrRequirements[0]->fe_name){
                $path =  public_path().'/uploads/'.$arrRequirements[0]->fe_path."/".$arrRequirements[0]->fe_name;
                if(File::exists($path)) { 
                    unlink($path);

                }
                if(!empty($eid)){
                   $this->_engoccupancyapp->deleteimagerowbyid($eid); 
                }
               
            }
            $this->_engoccupancyapp->deleteRequirementsbyid($rid);
             echo "deleted";
        }
    }

    public function getbuidingdata(Request $request){
        $permitid = $request->input('permitid');
        $data = $this->_engoccupancyapp->getbuidingpermitdata($permitid);
        $barangaydata = $this->_commonmodel->getBarangayname($data->location_brgy_id);
        $data->locationofconstr = ""; $data->barangaytext ="";
        if(!empty($barangaydata)){
           $data->locationofconstr =$barangaydata->brgy_name;  
        }
         foreach ($this->_engoccupancyapp->getBarangay($data->p_barangay_id_no) as $val) {
              $data->barangaytext=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
        }
        echo json_encode($data);
    }

    public function getProfileDetails(Request $request){
        $id= $request->input('pid');
        $data = $this->_engoccupancyapp->getProfileDetails($id);
        echo json_encode($data);
    }

    public function deleteFeedetails(Request $request){
        $id =$request->input('id');
        $this->_engoccupancyapp->deleteFeedetailsrow($id);
    } 

    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_engoccupancyapp->updateData($id,$data);
    }

     public function UpdatePermitIssued(Request $request){
        $releaseupdate =array('eoa_permit_released_by'=>\Auth::user()->id,'eoa_is_permit_released'=>'1','eoa_permit_released_date_time'=>date('Y-m-d H:i:s'));
        $this->_engoccupancyapp->updateData($request->input('id'),$releaseupdate);
        $smsTemplate=SmsTemplate::where('id',49)->where('is_active',1)->first();
        $arrData = $this->_engoccupancyapp->GetOccupancyAppData($request->input('id'));
        if(!empty($smsTemplate) && $arrData->p_mobile_no != null)
            {
                $receipient=$arrData->p_mobile_no;
                $msg=$smsTemplate->template;
                $msg = str_replace('<NAME>', $arrData->full_name,$msg);
                $msg = str_replace('<REFERENCE_NO>', $arrData->ebpa_id,$msg);
                $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
               $this->send($msg, $receipient);
            }
        $array =array();
        $array['status'] = "success";
        $array['permitid'] = "";
        echo json_encode($array);
    }

     public function getBarngayList(Request $request){
       $search = $request->input('search');
       $getmincipalityid = $this->_engoccupancyapp->getOccumunciapality(); $munid ="";
       if(!empty($getmincipalityid)){ $munid = $getmincipalityid->mun_no;
        }
        $arrRes = $this->_engoccupancyapp->getBarangayforajax($search,$munid);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            //$arr['data'][$key]['text']=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
            $arr['data'][$key]['text']=$val->brgy_name;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
   }


     public function saveorderofpayment(Request $request){
         $id= $request->input('appid');
         $tfocid= $request->input('tfocid');
         $amount= $request->input('amount');
         $data =array();
         $data['top_transaction_type_id'] = 10; 
         $data['tfoc_is_applicable'] = '4';
         $data['transaction_ref_no'] = $id;
         $data['tfoc_id'] = $tfocid;
         $amount = str_replace(",", "", $amount);
         $data['amount'] = $amount;
         //echo "here"; exit;

         $checkidexist = $this->_engoccupancyapp->checkTransactionexist($id,$data['top_transaction_type_id']);
         if(count($checkidexist)> 0){
            $array = ["status"=>"success","message" =>"Data Saved Successfully.",'transactionno'=>$checkidexist[0]->transaction_no,'transid'=>$checkidexist[0]->id];
            $appuptdata = array('top_transaction_type_id'=>$data['top_transaction_type_id'],'eoa_opd_created_by'=>\Auth::user()->id);
                $this->_engoccupancyapp->updateData($id,$appuptdata);
            $filename = $this->PrintorderFile($id);    
            $appuptdata = array('amount'=>$amount,'attachment'=>$filename);
            $this->_engoccupancyapp->TransactionupdateData($checkidexist[0]->id,$appuptdata);
         }else{
            $data['created_at'] = date('Y-m-d H:i:s');
            $lastinsert =$this->_engoccupancyapp->TransactionaddData($data);
            $transactionno = str_pad($lastinsert, 6, '0', STR_PAD_LEFT);
            $filename = $this->PrintorderFile($id);
            $updatedata = array('transaction_no'=>$transactionno,'attachment'=>$filename);
            $this->_engoccupancyapp->TransactionupdateData($lastinsert,$updatedata);
                $appuptdata = array('top_transaction_type_id'=>$data['top_transaction_type_id'],'eoa_opd_created_by'=>\Auth::user()->id);
                $this->_engoccupancyapp->updateData($id,$appuptdata);
                $array = ["status"=>"success","message" =>"Data Saved Successfully.",'transactionno'=>$transactionno,'transid'=>$lastinsert];
         }
        $updateremotedata = array();
        $updateremotedata['cashieramount'] = $amount;
        $this->_engoccupancyapp->updateremotedata($request->input('appid'),$updateremotedata);  
         echo json_encode($array);
    }

    public function storeOccubillSummary(Request $request){
       if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
          $id = $request->input('appid'); 
          $transactionno =  $request->input('transactionno');
          $arrTran = $this->_engoccupancyapp->getBillDetails($transactionno,$id);
          //print_r($arrTran); exit;
          if(isset($arrTran)){ 
             $billsaummary = array();
             $billsaummary['occupancy_id'] = $id; 
             $billsaummary['client_id'] = $arrTran->client_id;
             $billsaummary['bill_year'] = date('Y');
             $billsaummary['bill_month'] = date('m');
             $billsaummary['total_amount'] = $arrTran->amount;
             $billsaummary['pm_id'] = 1;
             $billsaummary['attachement'] = $arrTran->attachment;
             $billsaummary['transaction_no'] = $arrTran->transaction_no;

          //This is for Main Server
            $arrBill = DB::table('occupancy_bill_summary')->select('id')->where('occupancy_id',$id)->where('transaction_no',$arrTran->transaction_no)->first();
            if(isset($arrBill)){
                DB::table('occupancy_bill_summary')->where('id',$arrBill->id)->update($billsaummary);
            }else{
                 $billsaummary['created_by'] = \Auth::user()->id;
                 $billsaummary['created_at'] = date('Y-m-d H:i:s');
                $this->_engoccupancyapp->insertbillsummary($billsaummary);
            }

            // This is for Remote Server
                $destinationPath =  public_path().'/uploads/billing/occupancy/'.$arrTran->attachment;
                $fileContents = file_get_contents($destinationPath);
                $remotePath = 'public/uploads/billing/occupancy/'.$arrTran->attachment;
                Storage::disk('remote')->put($remotePath, $fileContents);
                $remortServer = DB::connection('remort_server');
                $arrBill = $remortServer->table('occupancy_bill_summary')->select('id')->where('occupancy_id',$id)->where('transaction_no',$arrTran->transaction_no)->first();

                try {
                    if(isset($arrBill)){
                        $remortServer->table('occupancy_bill_summary')->where('id',$arrBill->id)->update($billsaummary);
                    }else{
                        $billsaummary['created_by'] =  \Auth::user()->id;
                        $billsaummary['created_at'] =  date('Y-m-d H:i:s');
                       $this->_engoccupancyapp->insertbillsummaryremote($billsaummary);
                    }
                    DB::table('occupancy_bill_summary')->where('occupancy_id',$id)->where('transaction_no',$arrTran->transaction_no)->update(array('is_synced'=>1));
                    unlink($destinationPath);
                }catch (\Throwable $error) {
                    return $error;
                }  
                echo "Done";
            } 
        }
           
    }

    public function PrintorderFile($appid){
        $data = $this->_engoccupancyapp->find($appid);
        $checkidexist = $this->_engoccupancyapp->checkTransactionexist($appid,'10');
        $transactionno ="";
        if(count($checkidexist)>0){
            $transactionno = $checkidexist[0]->transaction_no;
        }
        PDF::SetTitle('Order OF Payment:'.$data->ebpa_id);    
        PDF::SetMargins(20, 15, 20,true);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('P', 'in', array(8.5, 13), true, 'UTF-8', false);
        PDF::SetFont('Helvetica', '', 9);

        // Header
        $top = 10;
        $border = 0;
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<h3>CITY ENGINEER'S OFFICE</h3>", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Palayan City", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);
        PDF::MultiCell(0, 0, '<h3>ORDER OF PAYMENT</h3>', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<h3>OCCUPANCY PERMIT</h3>', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);
        PDF::MultiCell(100, 0, 'Annual Inspection Applicalion No.: <b></b>', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Date Prepared/ Computed: <b>'.Carbon::parse($data->dateissued)->format('M. d, Y').'</b>', $border, 'R', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Date Applied: <b>'.Carbon::parse($data->dateissued)->format('F Y').'</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        PDF::MultiCell(0, 0, 'TOP No: <b>'.$transactionno.'</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        PDF::MultiCell(0, 0, 'Applicant: <b>'.$data->client->fullname.'</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Contact No / Person: <b>'.$data->p_mobile_no.'</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Building/Structure: <b>'.$data->nameofproject.'</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Location: <b>'.$data->ebpa_location.'</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        PDF::MultiCell(88, 0, 'CLASSIFICATION Of USE/CHARACTER OF OCCUPANCY', $border, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'AGRIGULTURAL (Division 1.1)', $border, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        
        $floor = '
        <table>
            <tr>
                <td colspan="2">A. i. Floor Area</td>
                <td style="border-bottom-width:1px;text-align:center"><b>'.$data->eoa_firstfloorarea.'</b></td>
                <td colspan="2">1st Floor</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td style="border-bottom-width:1px;text-align:center"><b>'.$data->eoa_secondfloorarea.'</b></td>
                <td colspan="2">2nd Floor</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td style="border-bottom-width:1px;text-align:center"><b>'.$data->ebfd_floor_area.'</b></td>
                <td colspan="2"> Total Area</td>
            </tr>
            <tr>
                <td colspan="2">ii. Lot Area</td>
                <td style="border-bottom-width:1px;text-align:center"><b>'.$data->eoa_lotarea.'</b></td>
                <td><b>'.$data->eoa_lotarea.'</b> sq. mtr.</td>
            </tr>
            <tr>
                <td colspan="2">iii. Perimeter</td>
                <td style="border-bottom-width:1px;text-align:center"><b>'.$data->eoa_perimeter.'</b></td>
                <td colspan="2"> L.mtr.</td>
            </tr>
            <tr>
                <td colspan="2">B. Project Cost:</td>
                <td style="border-bottom-width:1px;text-align:center;">'.number_format($data->eoa_projectcost,2).'</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2">C. O.R. No.:</td>
                <td colspan="2" style="border-bottom-width:1px"></td>
            </tr>
            <tr>
                <td colspan="2">Date Paid:</td>
                <td colspan="2" style="border-bottom-width:1px;text-align:right">'.$data->eoa_date_paid.'</td>
            </tr>
        </table>
        ';
        $defaultFeesarr = $this->_engoccupancyapp->GetDefaultfees()->toArray();
        $default = array_column($defaultFeesarr, 'fees_description');
        PDF::MultiCell(88, 0, $floor, $border, 'L', 0, 0, '', '', true, 0, true);
        $floor = '<table style="padding:0 10px">';
        $key = 0;
        foreach ($default as $key => $value) {
            $key = $key + 1;
            $fees = DB::table('eng_occupancy_fees_details')->select('*')->where([['fees_description',$value],['eoa_id',$data->id]])->first();
            if ($fees->tax_amount != '0.0') {
                if ($value === 'Others') {
                    $otherfees = DB::table('eng_occupancy_fees_details')->select('*')->where([['eoa_id',$data->id]])->whereNotIn('fees_description',$default)->get();
                    $floor .= '<tr>
                                    <td>'.$key.'. '.str_replace('Fee','', $value).' (Specify):</td>
                                    <td style="border-bottom-width:1px;text-align:right">'.number_format($fees->tax_amount,2).'</td>
                                </tr>';
                    foreach ($otherfees as $id => $val) {
                        $floor .= '<tr>
                                        <td> &nbsp; &nbsp; &nbsp;'.$val->fees_description.'-</td>
                                        <td style="border-bottom-width:1px;text-align:right">'.number_format($val->tax_amount,2).'</td>
                                    </tr>';
                    }
                } else {
                    $floor .= '<tr>
                        <td width="150px">'.$key.'. '.str_replace('Fee','', $value).'-</td>
                        <td style="border-bottom-width:1px;text-align:right">'.number_format($fees->tax_amount,2).'</td>
                    </tr>';
                }
            }
        }
        $floor .= '
        <tr><td></td></tr>
        <tr>
                <td>Penalty/Surcharge-</td>
                <td style="border-bottom-width:1px;text-align:right">'.number_format($data->eoa_surcharge_fee, 2).'</td>
            </tr>
        <tr style="text-align:right">
                <td>TOTAL FEE-</td>
                <td style="border-bottom-width:2px"><b>'.number_format($data->eoa_total_fees, 2).'</b></td>
            </tr>
        </table>
        ';
        PDF::setCellPaddings(0,10,15,0);
        PDF::MultiCell(0, 0, $floor, $border, 'L', 0, 1, '', '', true, 0, true);

        PDF::ln(10);
        PDF::MultiCell(88, 0, '<br><br><br><br><br><br><br><br>Prepared By: <br><br><br><br>', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(88, 0, '<br><br><br><br><br><br><br><br>Approved By: <br><br><br><br>', $border, 'L', 0, 1, '', '', true, 0, true);

        PDF::setCellPaddings(0,0,0,0);
        $preparer = ($data->preparer)?$data->preparer->hr_employee->fullname:'';
          // echo $data->preparer;exit;
        $approver = ($data->approver)?$data->approver->hr_employee->fullname:'';
        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, strtoupper($preparer), 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(20, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, strtoupper($approver), 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 0, 'C', 0, 1, '', '', true, 0, true);

        $preparer = ($data->preparer)?$data->preparer->hr_employee->department->shortname.' '.$data->preparer->hr_employee->designation->description:'';
        // echo $preparer;exit;
        $approver = ($data->approver)?$data->approver->hr_employee->department->shortname.' '.$data->approver->hr_employee->designation->description:'';
        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, strtoupper($preparer), 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(20, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, strtoupper($approver), 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 0, 'C', 0, 1, '', '', true, 0, true);

        PDF::ln(5);
        PDF::MultiCell(70, 0, 'Noted By: <br><br><br>', $border, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, strtoupper(user_mayor()->fullname), 'B', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, 'CITY MAYOR', $border, 'C', 0, 1, '', '', true, 0, true);

        //PDF::Output('OrderOFPayment:'.$data->ebpa_id.'.pdf');

        $filename = 'OrderOFPayment'.$data->ebpa_id.'.pdf';
        $folder =  public_path().'/uploads/billing/occupancy/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        PDF::Output($folder.$filename,'F');
        @chmod($folder.$filename, 0777);
        return $filename;
        
    }


     public function Printorder(Request $request){
        $id = $request->input('id');   
        $data = $this->_engoccupancyapp->find($request->input('id'));
        $checkidexist = $this->_engoccupancyapp->checkTransactionexist($id,'10');
        $transactionno ="";
        if(count($checkidexist)>0){
            $transactionno = $checkidexist[0]->transaction_no;
        }
        PDF::SetTitle('Order OF Payment:'.$data->ebpa_id);    
        PDF::SetMargins(20, 15, 20,true);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('P', 'in', array(8.5, 13), true, 'UTF-8', false);
        PDF::SetFont('Helvetica', '', 9);

        // Header
        $top = 10;
        $border = 0;
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<h3>CITY ENGINEER'S OFFICE</h3>", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Palayan City", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);
        PDF::MultiCell(0, 0, '<h3>ORDER OF PAYMENT</h3>', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<h3>OCCUPANCY PERMIT</h3>', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);
        PDF::MultiCell(100, 0, 'Annual Inspection Applicalion No.: <b></b>', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Date Prepared/ Computed: <b>'.Carbon::parse($data->dateissued)->format('M. d, Y').'</b>', $border, 'R', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Date Applied: <b>'.Carbon::parse($data->dateissued)->format('F Y').'</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        PDF::MultiCell(0, 0, 'TOP No: <b>'.$transactionno.'</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        PDF::MultiCell(0, 0, 'Applicant: <b>'.$data->client->fullname.'</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Contact No / Person: <b>'.$data->p_mobile_no.'</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Building/Structure: <b>'.$data->nameofproject.'</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Location: <b>'.$data->ebpa_location.'</b>', $border, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        PDF::MultiCell(88, 0, 'CLASSIFICATION Of USE/CHARACTER OF OCCUPANCY', $border, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'AGRIGULTURAL (Division 1.1)', $border, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        
        $floor = '
        <table>
            <tr>
                <td colspan="2">A. i. Floor Area</td>
                <td style="border-bottom-width:1px;text-align:center"><b>'.$data->eoa_firstfloorarea.'</b></td>
                <td colspan="2">1st Floor</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td style="border-bottom-width:1px;text-align:center"><b>'.$data->eoa_secondfloorarea.'</b></td>
                <td colspan="2">2nd Floor</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td style="border-bottom-width:1px;text-align:center"><b>'.$data->ebfd_floor_area.'</b></td>
                <td colspan="2"> Total Area</td>
            </tr>
            <tr>
                <td colspan="2">ii. Lot Area</td>
                <td style="border-bottom-width:1px;text-align:center"><b>'.$data->eoa_lotarea.'</b></td>
                <td><b>'.$data->eoa_lotarea.'</b> sq. mtr.</td>
            </tr>
            <tr>
                <td colspan="2">iii. Perimeter</td>
                <td style="border-bottom-width:1px;text-align:center"><b>'.$data->eoa_perimeter.'</b></td>
                <td colspan="2"> L.mtr.</td>
            </tr>
            <tr>
                <td colspan="2">B. Project Cost:</td>
                <td style="border-bottom-width:1px;text-align:center;">'.number_format($data->eoa_projectcost,2).'</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2">C. O.R. No.:</td>
                <td colspan="2" style="border-bottom-width:1px"></td>
            </tr>
            <tr>
                <td colspan="2">Date Paid:</td>
                <td colspan="2" style="border-bottom-width:1px;text-align:right">'.$data->eoa_date_paid.'</td>
            </tr>
        </table>
        ';
        $defaultFeesarr = $this->_engoccupancyapp->GetDefaultfees()->toArray();
        $default = array_column($defaultFeesarr, 'fees_description');
        PDF::MultiCell(88, 0, $floor, $border, 'L', 0, 0, '', '', true, 0, true);
        $floor = '<table style="padding:0 10px">';
        $key = 0;
        foreach ($default as $key => $value) {
            $key = $key + 1;
            $fees = DB::table('eng_occupancy_fees_details')->select('*')->where([['fees_description',$value],['eoa_id',$data->id]])->first();
            if ($fees->tax_amount != '0.0') {
                if ($value === 'Others') {
                    $otherfees = DB::table('eng_occupancy_fees_details')->select('*')->where([['eoa_id',$data->id]])->whereNotIn('fees_description',$default)->get();
                    $floor .= '<tr>
                                    <td>'.$key.'. '.str_replace('Fee','', $value).' (Specify):</td>
                                    <td style="border-bottom-width:1px;text-align:right">'.number_format($fees->tax_amount,2).'</td>
                                </tr>';
                    foreach ($otherfees as $id => $val) {
                        $floor .= '<tr>
                                        <td> &nbsp; &nbsp; &nbsp;'.$val->fees_description.'-</td>
                                        <td style="border-bottom-width:1px;text-align:right">'.number_format($val->tax_amount,2).'</td>
                                    </tr>';
                    }
                } else {
                    $floor .= '<tr>
                        <td width="150px">'.$key.'. '.str_replace('Fee','', $value).'-</td>
                        <td style="border-bottom-width:1px;text-align:right">'.number_format($fees->tax_amount,2).'</td>
                    </tr>';
                }
            }
        }
        $floor .= '
        <tr><td></td></tr>
        <tr>
                <td>Penalty/Surcharge-</td>
                <td style="border-bottom-width:1px;text-align:right">'.number_format($data->eoa_surcharge_fee, 2).'</td>
            </tr>
        <tr style="text-align:right">
                <td>TOTAL FEE-</td>
                <td style="border-bottom-width:2px"><b>'.number_format($data->eoa_total_fees, 2).'</b></td>
            </tr>
        </table>
        ';
        PDF::setCellPaddings(0,10,15,0);
        PDF::MultiCell(0, 0, $floor, $border, 'L', 0, 1, '', '', true, 0, true);

        PDF::ln(10);
        PDF::MultiCell(88, 0, '<br><br><br><br><br><br><br><br>Prepared By: <br><br><br><br>', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(88, 0, '<br><br><br><br><br><br><br><br>Approved By: <br><br><br><br>', $border, 'L', 0, 1, '', '', true, 0, true);

        PDF::setCellPaddings(0,0,0,0);
        $preparer = ($data->preparer)?$data->preparer->hr_employee->fullname:'';
          // echo $data->preparer;exit;
        $approver = ($data->approver)?$data->approver->hr_employee->fullname:'';
        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, strtoupper($preparer), 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(20, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, strtoupper($approver), 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 0, 'C', 0, 1, '', '', true, 0, true);

        $preparer = ($data->preparer)?$data->preparer->hr_employee->department->shortname.' '.$data->preparer->hr_employee->designation->description:'';
        // echo $preparer;exit;
        $approver = ($data->approver)?$data->approver->hr_employee->department->shortname.' '.$data->approver->hr_employee->designation->description:'';
        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, strtoupper($preparer), 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(20, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, strtoupper($approver), 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 0, 'C', 0, 1, '', '', true, 0, true);

        PDF::ln(5);
        PDF::MultiCell(70, 0, 'Noted By: <br><br><br>', $border, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 0, strtoupper(user_mayor()->fullname), 'B', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(60, 0, 'CITY MAYOR', $border, 'C', 0, 1, '', '', true, 0, true);

        // PDF::Output('Order OF Payment: '.$data->ebpa_id.'.pdf');

        $filename = 'Order OF Payment: '.$data->ebpa_id.'.pdf';
        $arrSign= $this->_commonmodel->isSignApply('occupancy_permit_submitted_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('occupancy_permit_approved_by');
        $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        if($signType==2){
            PDF::Output($folder.$filename,'F');
            @chmod($folder.$filename, 0777);
        }
        $arrData['filename'] = $filename;
        $arrData['isMultipleSign'] = 1;
        $arrData['isDisplayPdf'] = 0;
        $arrData['isSavePdf'] = 0;
        $preparerUserId = ($data->preparer)?$data->preparer->hr_employee->user_id:'';
        $varifiedSignature = $this->_commonmodel->getuserSignature($preparerUserId);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

        if($isSignVeified==1 && $signType==2){
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                $arrData['isSavePdf'] = 1;
                $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
                $arrData['signaturePath'] = $varifiedSignature;
                if($isSignCertified==0 && $signType==2){
                    $arrData['isDisplayPdf'] = 1;
                    return $this->_commonmodel->applyDigitalSignature($arrData);
                }else{
                    $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }
        }

        $certifiedSignature = $this->_commonmodel->getuserSignature(strtoupper($data->approver->hr_employee->user_id));
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;

        if($isSignCertified==1 && $signType==2){
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                $arrData['isDisplayPdf'] = 1;
                $arrData['signaturePath'] = $certifiedSignature;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }

        if($isSignCertified==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                PDF::Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, $arrCertified->esign_resolution);
            }
        }
        if($isSignVeified==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                PDF::Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)) { 
                File::delete($folder.$filename);
            }
        }
        PDF::Output($filename,"I");
    }     
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_engoccupancyapp->getList($request);
       // $arrPermitno = $this->arrPermitno;
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a></div>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a></div>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['ebpa_id']=$row->ebpa_id;
            $arr[$i]['ownername']=$row->full_name;
            $barngayName = "";
            $barngayAddress = $this->_commonmodel->getBarangayname($row->location_brgy_id); 
            if(!empty($barngayAddress)){
               $barngayName = $barngayAddress->brgy_name;
            }
            $arr[$i]['locbarangay']= $barngayName;
            $arr[$i]['eoa_application_type']="Occupancy Service";
            $arr[$i]['appno']=$row->eoa_application_no;
            $arr[$i]['generated']=$row->created_at;
             $arr[$i]['topno'] ="";  $orno =""; $ordate="";
            if($row->top_transaction_type_id > 0){
               $gettopdata = $this->_engoccupancyapp->checkTransexist($row->id,$row->top_transaction_type_id); 
               if(count($gettopdata) > 0){
                $arr[$i]['topno']=$gettopdata[0]->transaction_no;
                //print_r($gettopdata); exit;
                 $ordata = $this->_engoccupancyapp->getORandORdate($gettopdata[0]->id);
                 if(count($ordata) > 0){
                  $orno = $ordata[0]->or_no; $ordate = $ordata[0]->created_at; 
                 }
               }
            }
            $arr[$i]['amount']=number_format($row->eoa_total_fees,2);
            $arr[$i]['ornumber']=$orno;
            $arr[$i]['ordate']=$ordate;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['is_online']=($row->is_online == 0)? 'Walkin':'Online'; 
             if($row->is_approve ==1){
                     $status.= '<div class="action-btn bg-info ms-2">
                        <a href="'.route('engoccupancyapp.certificateoccupancyprint',['id'=>$row->id]).'" title="Print Occupancy" target="_blank" data-title="Print Occupancy" class="mx-3 btn btn-sm print align-items-center digital-sign-btn" id="'.$row->id.'">
                            <i class="ti-printer text-white"></i>
                        </a></div>';
                    }                   
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engoccupancyapp/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Occupancy Permit Application">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status;
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

     public function store(Request $request){
        $data = (object)$this->data;
        $arrgetBrgyCode = $this->arrgetBrgyCode;
        $arrOwners = $this->arrOwners; $issurcharge = 0;
        $arrRequirements = $this->arrRequirements;
        $requirements = $this->requirements;
        $arrlocgetBrgyCode = array();
        $arrTypeofOccupancy = $this->arrTypeofOccupancy;
        $getServices = $this->getServices;
        $arrPermitno = $this->arrPermitno;
        $defaultFeesarr = $this->_engoccupancyapp->GetDefaultfees();
        $extrafeearr = array(); $fulladdress = "";
        $arrRequirements = $this->_engoccupancyapp->getJobRequirementsdefault(30);
        //print_r($arrRequirements); exit;
        $getsurchargesl = $this->_engoccupancyapp->getCasheringIds(30);
        $issurcharge = $getsurchargesl->tfoc_surcharge_sl_id;
          if($request->input('id')>0 && $request->input('submit')==""){
            $data = EngOccupancyApp::find($request->input('id'));
             $arrRequirements = $this->_engoccupancyapp->getJobRequirementsData($request->input('id'));
            $defaultFeesarr = $this->_engoccupancyapp->GetRequestfees($request->input('id')); 
            $arrPermitno = array();
            foreach ($this->_engoccupancyapp->getPermitno($data->ebpa_id) as $keyp => $valuep) {
                $arrPermitno[$valuep->ebpa_permit_no] =$valuep->ebpa_permit_no;
            }
            
            $arrgetBrgyCode = array();
            foreach ($this->_engoccupancyapp->getBarangay($data->brgy_code) as $val) {
              $fulladdress=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
             }
          }
           foreach ($this->_commonmodel->getBarangay($data->location_brgy_id)['data'] as $val) {
                $arrlocgetBrgyCode=$val->brgy_name;
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
                         $destinationPath =  public_path().'/uploads/engineering/occupancy';
                            if(!File::exists($destinationPath)){ 
                                File::makeDirectory($destinationPath, 0755, true, true);
                            }
                         $filename =  $reqid.'requirement'.$lastinsertid;  
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
                         $filearray['fe_path'] = 'engineering/occupancy';
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
              Session::put('REMOTE_SYNC_APPFORMIDOCCUPANCY',$appid); 
            return redirect()->route('engoccupancyapp.index')->with('success', __($success_msg));
    	   }
    	
        return view('Engneering.engoccupancy.create',compact('data','arrgetBrgyCode','arrOwners','arrTypeofOccupancy','arrRequirements','requirements','defaultFeesarr','getServices','arrPermitno','extrafeearr','userroleid','issurcharge','arrlocgetBrgyCode','fulladdress'));
	}

	 public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'client_id'=>'required',
                'rpo_address_street_name'=>'required',
                'rpo_address_subdivision'=>'required',
                'ebpa_id'=>'required',
                'location_brgy_id'=>'required',
            ]
            ,
            [
                'client_id.required' => 'Client is required',
                'rpo_address_subdivision.required'=>'Required',
                'ebpa_id.required'=>'Required',
                'location_brgy_id.required'=>'Required',
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

    public function PrintCertificateOfOccupancy($id){
        $data = $this->_engoccupancyapp->find($id);
        PDF::SetTitle('Order OF Payment: '.$data->ebpa_id);    
        PDF::SetMargins(20, 15, 20,true);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('P', 'in', array(8.5, 13), true, 'UTF-8', false);
        PDF::SetFont('Helvetica', '', 9);
        // Header
        $top = 10;
        $border = 0;
        // PDF::Image(public_path('assets\images\logo.png'),25, $top, 25, 25);
        // PDF::Image(public_path("assets\images\department_logos\Enginee's Office.jpg"),160, $top, 25, 25);
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<b>CITY ENGINEER'S OFFICE</b>", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Palayan City", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);
        PDF::MultiCell(0, 0, '<h3>TANGGAPAN NG PINUNONG PANGGUSALI</h3>', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '(OFFICE OF THE BUILDING OFFICIAL)', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);
        PDF::SetFont('Helvetica', '', 11);
        PDF::MultiCell(0, 0, '<h3>CERTIFICATE OF OCCUPANCY</h3>', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(1);
        
        PDF::MultiCell(70, 0, '<b>No.</b>', 0, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35, 0, 'OP'.$data->ebpa_id, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 0, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(70, 0, '<b>Fee Paid</b>', 0, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35, 0, 'Php '.currency_format($data->eoa_total_fees), 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 0, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(70, 0, '<b>O.R. No.</b>', 0, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35, 0, $data->orno, 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 0, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(70, 0, '<b>Date Paid</b>', 0, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35, 0, ($data->ordate) ? date('F j, Y', strtotime($data->ordate)) : '', 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 0, 'C', 0, 1, '', '', true, 0, true);

        PDF::SetFont('Helvetica', '', 10);
         PDF::ln(5);

        PDF::MultiCell(100, 0, '', $border, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35, 0, '', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<b>'.date('F j, Y', strtotime($data->dateissued)).'</b>', 'B', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(100, 0, '', $border, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35, 0, '', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Date', $border, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);

        PDF::MultiCell(0, 0, '<p style="text-indent:80px">This CERTIFICATE OF OCCUPANCY is issued/granted pursuant to Section 309 of the National Building Code (PD 1096).</p>', $border, 'L', 0, 1, '', '', true, 0, true);
        
        PDF::ln(5);
        PDF::MultiCell(70, 0, 'Name of Owner: ', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $data->client->full_name, 'B', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(70, 0, 'Name of Project: ', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $data->nameofproject, 'B', 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(70, 0, 'Use or Character of Occupancy:', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $this->arrTypeofOccupancy[$data->ebot_id], 'B', 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(70, 0, 'Group: ', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'B', 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(70, 0, 'Location at/along: ', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $data->ebpa_location, 'B', 'L', 0, 1, '', '', true, 0, true);

        $permit = $this->_engoccupancyapp->getbuidingpermitdata($data->ebpa_id);
        PDF::MultiCell(50, 0, 'Under Building Permit No.: ', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, $data->ebpa_id, 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, 'Date Issued: ', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, ($permit->ebpa_issued_date) ? date('F j, Y', strtotime($permit->ebpa_issued_date)) : '', 'B', 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(50, 0, 'With Official Reciept No.: ', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, $permit->ejr_or, 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, 'Date Paid: ', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, ($permit->ejr_ordate) ? date('F j, Y', strtotime($permit->ejr_ordate)) : '', 'B', 'L', 0, 1, '', '', true, 0, true);
        
        PDF::ln(5);

        PDF::MultiCell(0, 0, '<p style="text-indent:80px">The owner shall properly maintain the building/structure, enhance its Architectural well-being, Structural stability, Electrical, Mechanical, Sanitation, Plumbing, Electronics, Interior Design, and Fire-Protective properties and shall not be occupied or used for purposes other than its intended use as stated above.</p>', $border, 'J', 0, 1, '', '', true, 0, true);
        PDF::ln(2);
        PDF::MultiCell(0, 0, '<p style="text-indent:80px">The Architect or Engineer who drew up the plans and specifications for the building/structure is aware that under Article 1723 of the Civil Code of the Philippines he is responsible for damages if within fifteen (15) years from the completion of the building/structure, the same should collapse due to defects in the plans or specifications or defects in the ground. He is therefore enjoined to conduct Annual Inspection of the structure to ensure that the conditions under which the structure was designed are not being violated or abused.</p>', $border, 'J', 0, 1, '', '', true, 0, true);
        PDF::ln(2);
        PDF::MultiCell(0, 0, '<p style="text-indent:80px">The building/structure shall be subject to Annual Inspection and issuance of a Certificate of Occupancy for a period of one (1) year from the date of issuance of certificate and yearly thereafter.</p>', $border, 'J', 0, 1, '', '', true, 0, true);
        PDF::ln(2);
        PDF::MultiCell(0, 0, '<p style="text-indent:80px">A Certified copy hereof shall be posted within the premises of the building and shall not be removed without authority from the Building Official.</p>', $border, 'J', 0, 1, '', '', true, 0, true);
        PDF::ln(5);

        $approver = ($data->approver) ? $data->approver->hr_employee->fullname : '';
        $approver_designation = ($data->approver) ? $data->approver->hr_employee->designation->description : '';
        PDF::MultiCell(120, 0, '', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<br><br><b>'.$approver .'</b>', 'B', 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(120, 0, '', $border, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<b>'.$approver_designation.'</b><br>Pinunong Panggusali<br>(Building Official)', $border, 'C', 0, 1, '', '', true, 0, true);
       // echo $data->approver->hr_employee->user_id; exit;
        

        // PDF::Output('Certificate of Occupancy: '.$data->ebpa_id.'.pdf');
        $filename = 'Certificate of Occupancy: '.$data->ebpa_id.'.pdf';
        $arrSign= $this->_commonmodel->isSignApply('occupancy_permit_approved_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        $arrSign= $this->_commonmodel->isSignApply('occupancy_permit_approved_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        $user_id = 0;
        if(isset($data->approver->hr_employee->user_id)){
            $user_id = $data->approver->hr_employee->user_id;
        }
        $signature = $this->_commonmodel->getuserSignature($user_id);
        $path =  public_path().'/uploads/e-signature/'.$signature;
        if($isSignVeified==1 && $signType==2){
            $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
            // echo $signature;exit;
            if(!empty($signature) && File::exists($path)){
                // Apply Digital Signature
                PDF::Output($folder.$filename,'F');
                $arrData['signaturePath'] = $signature;
                $arrData['filename'] = $filename;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        if($isSignVeified==1 && $signType==1){
            // Apply E-Signature
            if(!empty($signature) && File::exists($path)){
                PDF::Image($path,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        
        PDF::Output($folder.$filename,"I");

    }
}
