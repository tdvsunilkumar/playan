<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\CommunityTax;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Session;
use File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
class CommunityTaxController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $clientsarr = array(""=>"Select Client");
    public $feesaray = array(""=>"Select fees");
    public $arrgetCountries = array(""=>"Please Select");
    public $arrcancelreason = array(""=>"Please Select");
    public $ortype_id ="";
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
		$this->_communitytax = new CommunityTax();
        $this->_commonmodel = new CommonModelmaster();
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon; 
        $this->data = array('id'=>'','cashier_year'=>'','cashier_issue_no'=>'','cashier_batch_no'=>'','client_citizen_id'=>'','tfoc_is_applicable'=>'','payee_type'=>'1','or_no'=>'','total_amount'=>'','total_paid_amount'=>'','payment_terms'=>'1','total_amount_change'=>'','total_paid_surcharge'=>'','cashier_particulars'=>'Community Tax Certificate','ctc_place_of_issuance'=>'','cashier_remarks'=>'');
        $this->slugs = 'cashier/community-tax'; 

        foreach ($this->_communitytax->getRptOwners() as $val){
            $this->clientsarr[$val->id]=$val->full_name;
        }
         foreach ($this->_communitytax->Gettaxfees() as $val){
            $this->feesaray[$val->id]=$val->accdesc;
        }
        foreach ($this->_communitytax->getCountries() as $val){
            $this->arrgetCountries[$val->id]=$val->nationality;
        }
        foreach ($this->_communitytax->getCancelReason() as $val){
            $this->arrcancelreason[$val->id]=$val->ocr_reason;
        } 
        $getortype = $this->_communitytax->GetOrtypeid('7');
                $this->ortype_id =  $getortype->ortype_id;   
    }
    public function index(Request $request)
    {   $this->is_permitted($this->slugs, 'read');
        $startdate = date('Y-m-d');   $enddate = date('Y-m-d');
        return view('communitytax.index',compact('startdate','enddate'));
        
    }

    public function cancelOrPayment(Request $request){
         $pswVeriStatus = (session()->has('casheringVerifyPsw'))?((session()->get('casheringVerifyPsw') == true)?true:false):false;
       // dd($pswVeriStatus);
        if(!$pswVeriStatus){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
        session()->forget('casheringVerifyPsw');
        $id= $request->input('cashier_id');
        $ocr_id= $request->input('ocr_id');
        $remark= $request->input('remark');
        $updataarray = array('ocr_id'=>$ocr_id,'cancellation_reason'=>$remark,'status'=>'0');
        $this->_communitytax->updateData($id,$updataarray);
        $this->_commonmodel->deletecashierincome($id);
        $data=array('status'=>'success',"message"=>"");
        echo json_encode($data);
    }

    public function getClientsbussiness(Request $request){
    	$id= $request->input('cid'); $businesshtml ="";
    	$businessdata = $this->_communitytax->getBusinessDetails($id);
    	foreach ($businessdata as $key => $value) {
    		$businesshtml .= '<option value='.$value->id.'>'.$value->busn_name.'</option>';
    	}
    	echo $businesshtml;
    }

    public function getIndianCurrency(float $number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
    }

    public function printCommunitytax(Request $request){
        $id = $request->input('id');
    	$ctcdata = $data = $this->_communitytax->getCertificateDetails($id);;
    	//echo "<pre>"; print_r($ctcdata); exit;
    	$arrgetCountries =$this->arrgetCountries;
    	$mpdf = new \Mpdf\Mpdf();
    	$mpdf->SetDisplayMode('fullpage');
        $mpdf->debug = true;
        $mpdf->showImageErrors = true;
        $mpdf->text_input_as_HTML = true;

        $html = file_get_contents(resource_path('views/layouts/templates/communitytaxcertificate.html'));
        $arrCpdoOverland = config('constants.arrCpdoOverland'); 
        $logo = url('/assets/images/logo.png');
        $logo2 = url('/assets/images/logo2.jpg');  
        $bgimage = url('/assets/images/clearancebackground.jpg');
        $unchecked = url('/assets/images/unchecked-checkbox.png');
        $checked = url('/assets/images/checked-checkbox.png');
        $html = str_replace('{{LOGO}}',$logo, $html);
        $html = str_replace('{{ornumrer}}',$ctcdata->or_no, $html);
        $html = str_replace('{{Year}}',$ctcdata->cashier_year, $html);
        $html = str_replace('{{city}}','Palayan City', $html);
        $html = str_replace('{{dateissued}}',date('Y-m-d',strtotime($ctcdata->created_at)), $html);
        $html = str_replace('{{lastname}}',$ctcdata->rpo_custom_last_name, $html);
        $html = str_replace('{{First}}',$ctcdata->rpo_first_name, $html);
        $html = str_replace('{{Middle}}',$ctcdata->rpo_middle_name, $html);
        $html = str_replace('{{tinno}}',$ctcdata->p_tin_no, $html);
        $html = str_replace('{{Address}}',$ctcdata->rpo_address_house_lot_no.",".$ctcdata->rpo_address_street_name.",".$ctcdata->rpo_address_subdivision, $html);
        if($ctcdata->gender =='1'){ $gender ='Male'; } else{ $gender ='Female'; }
        $html = str_replace('{{sex}}',$gender, $html);
        $html = str_replace('{{Citizenship}}',$arrgetCountries[$ctcdata->country], $html);
        $html = str_replace('{{IcrNo}}',$ctcdata->icr_no, $html);
        $html = str_replace('{{PlaceBirth}}',$ctcdata->birth_place, $html);
        $html = str_replace('{{Height}}',$ctcdata->height, $html);
        $html = str_replace('{{Weight}}',$ctcdata->weight, $html);
        $html = str_replace('{{dob}}',$ctcdata->dateofbirth, $html);
        $html = str_replace('{{civilstatus}}',$ctcdata->dateofbirth, $html);
        $html = str_replace('{{total}}',$ctcdata->total_amount, $html);
        $html = str_replace('{{interest}}',$ctcdata->total_paid_surcharge, $html);
        $html = str_replace('{{totalpaid}}',$ctcdata->total_paid_amount, $html);
        $inwords = $this->_commonmodel->numberToWord($ctcdata->total_amount);
        $html = str_replace('{{inword}}',$inwords, $html);  
        $arrFeeDetails = $this->_communitytax->GetFeedetails($request->input('id'));
        $secongtaxable ="";  $seconddue =""; $thirdtaxable =""; $thirddue =""; $fourthtaxable =""; $fourthdue="";
        foreach ($arrFeeDetails as $key => $value){
        	if($key==0){
        		 $html = str_replace('{{basictaxable}}',$value->ctc_taxable_amount, $html);
        			 $html = str_replace('{{basicdue}}',$value->tfc_amount, $html);
        	}
        	if($key==1){
        		$secongtaxable =$value->ctc_taxable_amount;
        		$seconddue = $value->tfc_amount;
        	}
        	if($key==2){
        		$thirdtaxable =$value->ctc_taxable_amount;
        		$thirddue = $value->tfc_amount;
        	}if($key==3){
        		 $fourthtaxable =$value->ctc_taxable_amount;
        		 $fourthdue = $value->tfc_amount;
        	}
        }
         $html = str_replace('{{secongtaxable}}',$secongtaxable, $html);
        	 $html = str_replace('{{seconddue}}',$seconddue, $html);
        	 $html = str_replace('{{thirdtaxable}}',$thirdtaxable, $html);
        	 $html = str_replace('{{thirddue}}',$thirddue, $html);
        	 $html = str_replace('{{fourthtaxable}}',$fourthtaxable, $html);
        	 $html = str_replace('{{fourthdue}}',$fourthdue, $html);
        $htmldynaapp='';
        $html = str_replace('{{cashier_name}}',Auth::user()->hr_employee->fullname, $html);
        // $natureofapp = config('constants.arrCpdoNatureApp');

        // foreach ($natureofapp as $key => $value) {
        //      	if( $key == $appdata->cna_id){
        //      		$htmldynaapp .='<td style="border:0px solid black; padding-right:30px;"> <p style="font-size: 15px;font-weight: 400;"><img src="'.$checked.'" style="max-width:20px;">'.$value.'</p></td>';
        //      	}else{
        //      		$htmldynaapp .='<td style="border:0px solid black; padding-right:30px;"><p style="font-size: 15px;font-weight: 400;"><img src="'.$unchecked.'" style="max-width:20px;">'.$value.'</p></td>';
        //      	}
        //      } 

        // $html = str_replace('{{Amount}}',$certificatedata->caf_amount, $html);
        // $html = str_replace('{{ornumber}}',$certificatedata->transaction_no, $html);
        // $html = str_replace('{{dateissued}}',$certificatedata->cc_date, $html);
        // $html = str_replace('{{total}}',$orderdata->caf_amount, $html);
        $filename="";
        //$html = $html;
        //echo $html; exit;
        $mpdf->WriteHTML($html);
        $filename = $id.$filename."ctccertificate.pdf";

        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        
        $arrSign= $this->_commonmodel->isSignApply('cashier_community_tax_collecting_officer');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
       
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        $signature = $this->_commonmodel->getuserSignature(Auth::user()->id);
        $path =  public_path().'/uploads/e-signature/'.$signature;
        if($isSignVeified==1 && $signType==2){
            $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
            if(!empty($signature) && File::exists($path)){
                // Apply Digital Signature
                $mpdf->Output($folder.$filename,'F');
                $arrData['signaturePath'] = $signature;
                $arrData['filename'] = $filename;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        if($isSignVeified==1 && $signType==1){
            // Apply E-Signature
            if(!empty($signature) && File::exists($path)){
                $mpdf->Image($path,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }

        $mpdf->Output($folder.$filename,"I");
    }

    public function getProfileDetails(Request $request){
        $id= $request->input('pid');   $ctype = $request->input('ctype');
        $arrgetCountries =$this->arrgetCountries;
        if($ctype =='1'){
        	$clientdata = array();
        	$data = $this->_communitytax->getProfileDetails($id);
	        $clientdata['address'] = $this->_commonmodel->getTaxPayerAddress($id);
	        $clientdata['tinno'] = $data->p_tin_no;  if($data->gender =='1'){ $gender ='Male'; } else{ $gender ='Female'; }
            if($data->civil_status ==""){$data->civil_status = 0;}
	        $clientdata['gender'] = $gender;  
	        $clientdata['dateofbirth'] = $data->dateofbirth; 
	        $clientdata['icr_no'] = $data->icr_no; 
	        $clientdata['height'] = $data->height; 
	        $clientdata['weight'] = $data->weight;
	        $clientdata['occupation'] = $data->occupation; 
	        $clientdata['civil_status'] = $data->civil_status; 
	        $clientdata['birth_place'] = $data->birth_place;
	        $clientdata['nationality'] = $arrgetCountries[$data->country];
        } else{
        	$data = $this->_communitytax->getCitizenDetails($id);
	        $clientdata['address'] = $this->_commonmodel->getCitizenAddress($id);
	        $clientdata['tinno'] = $data->cit_tin_no;  if($data->cit_gender =='1'){ $gender ='Male'; } else{ $gender ='Female'; }
            if($data->civil_status ==""){$data->civil_status = 0;}
	        $clientdata['gender'] = $gender;  
	        $clientdata['dateofbirth'] = $data->cit_date_of_birth; 
	        $clientdata['icr_no'] = $data->icr_no; 
	        $clientdata['height'] = $data->cit_height; 
	        $clientdata['weight'] = $data->cit_weight;
	        $clientdata['occupation'] = $data->occupation; 
	        $clientdata['civil_status'] = $data->civil_status; 
	        $clientdata['birth_place'] = $data->birth_place;
	        $clientdata['nationality'] = $arrgetCountries[$data->country_id];
        }  
        //print_r($data); exit;
        echo json_encode($clientdata);
    }

    public function getfeeamount(Request $request){
    	$id = $request->input('tfocid');
    	$feeamount = $this->_communitytax->GetFeeamount($id);
    	echo $feeamount->tfoc_amount;
    }

    public function gettaxpayerssearch(Request $request){
        $search = $request->input('search');
        $payeetype = $request->input('payee_type');
        if($payeetype == 1){
          $arrRes = $this->_commonmodel->getAllTaxpayersAutoSearchList($search);
        }else{
          $arrRes = $this->_commonmodel->getAllCitizenAutoSearchList($search);
        }
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->full_name;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }

    public function getClientsDropdown(Request $request){
    	 echo $id = $request->input('id');  $htmloption ='<option value="">Please Select</option>';
    	 if($id =='1'){
    	 		$data = $this->_communitytax->getRptOwners();
    	 		foreach ($data as $val) {
                   $htmloption .='<option value="'.$val->id.'>'.$val->full_name.'</option>';
              }
    	 }else{
    	 		$data = $this->_communitytax->getCitizens();
    	 		foreach ($data as $val) {
                   $htmloption .='<option value="'.$val->id.'>'.$val->cit_fullname.'</option>';
              }
    	 }
    	 echo $htmloption;
    }

    public function getOrnumber(Request $request){
    	$checkflag = $request->input('orflag');
        $orNumber =1;
    	if($checkflag == '1'){
    		$getorno = $this->_communitytax->GetcpdolatestOrNumber();
            if(!empty($getorno->or_no)){
                 $orNumber = $getorno->or_no +1;
            }
    	}else{
             $getorrange = $this->_commonmodel->getGetOrrange($this->ortype_id,\Auth::user()->id);
            if(empty($getorrange->latestusedor)){
                 if(!empty($getorrange->ora_from)){
                    $orNumber = $getorrange->ora_from;
                }
            }else{
                $orNumber = $getorrange->latestusedor + 1;
            }
    	}
        $orNumber = str_pad($orNumber, 7, '0', STR_PAD_LEFT);
        echo $orNumber;
    }
    
    public function getList(Request $request){
    	$this->is_permitted($this->slugs, 'read');
        $data=$this->_communitytax->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0; 
        $arrStatus=array();
        $arrStatus[0] ='<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>';
        $arrStatus[1] ='<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>'; 
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;  
            $arr[$i]['cashier_year']=$row->cashier_year;  
            $arr[$i]['taxpayername']=$row->full_name;
            $addressnew = wordwrap($this->_commonmodel->getTaxPayerAddress($row->client_citizen_id), 40, "<br />\n");
            $arr[$i]['completeaddress']="<div class='showLess'>".$addressnew."</div>";
            $arr[$i]['or_no']=$row->or_no;
            $arr[$i]['payment_terms'] = ($row->payment_terms > 0) ? '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">' . config('constants.paymentTerms')[(int)$row->payment_terms] . '</span>' : '';
            $arr[$i]['total_paid_amount']=number_format($row->total_paid_amount, 2, '.', ',');
            //if($row->status == '1'){ $status = "active"; } else{ $status = "Cancelled"; }
            $arr[$i]['status']=$arrStatus[$row->status];
            $arr[$i]['Date']=date("M d, Y",strtotime($row->created_at));
            $arr[$i]['cashier']=$row->fullname;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/community-tax/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Community Tax Certificate Payment">
                        <i class="ti-eye text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                    <a href="'.url('/cashier/community-tax/printReceipt?id='.$row->id).'" target="_blank" title="Print Community Tax"  data-title="Print Community Tax"  class="mx-3 btn btn-sm print text-white digital-sign-btn">
                        <i class="ti-printer text-white"></i>
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
     	$this->is_permitted($this->slugs, 'update');
	        $data = (object)$this->data;
	        $clientsarr = array();
	        $feesaray = $this->feesaray;
            $arrcancelreason = $this->arrcancelreason;
	        $arrFeeDetails = array();
            $data->createdat = Date('Y-m-d');
	        //$data->createdat = Date('Y-m-d', strtotime('-1 days'));
	        if($request->input('id')>0 && $request->input('submit')==""){
	            $data = $this->_communitytax->Geteditrecord($request->input('id'));

                if($data->payee_type==1){
                    foreach ($this->_communitytax->getTaxpayers($data->client_citizen_id) as $val) {
                        $clientsarr[$val->id]=$val->full_name;
                    }
                }else{
                    foreach ($this->_communitytax->getCitizensforedit($data->client_citizen_id) as $val) {
                        $clientsarr[$val->id]=$val->cit_fullname;
                    }
                }
	            //echo "<pre>"; print_r($healthcertreq); exit;
	            $data->createdat = date('Y-m-d',strtotime($data->created_at));
	            $arrFeeDetails = $this->_communitytax->GetFeedetails($request->input('id'));
	        }
            if($request->input('submit')!=""){
	            foreach((array)$this->data as $key=>$val){
	                $this->data[$key] = $request->input($key);
	            }

	            $cashierdetails = array();
	            $cashierdetails['cashier_year'] = date('Y');
	            $cashierdetails['cashier_month'] = date('m');
	            $cashierdetails['tfoc_is_applicable'] ='7';
	            $cashierdetails['payee_type'] = $this->data['payee_type'];
	            $cashierdetails['client_citizen_id'] =$this->data['client_citizen_id'];
                $clientdata = $this->_commonmodel->getClientName($this->data['client_citizen_id']);
                $taxpayername = $clientdata->full_name;
	            $this->data['taxpayers_name'] = $taxpayername;
	            //echo "<pre>"; print_r($this->data); exit;
	            $this->data['cashier_year'] = date('Y');
	            $this->data['cashier_month'] = date('m');
	            $this->data['tfoc_is_applicable'] = '7'; 
	            $this->data['ortype_id'] = '3'; 
	            unset($this->data['cashier_batch_no']); 
	            $this->data['updated_by']=\Auth::user()->id;
	            $this->data['updated_at'] = date('Y-m-d H:i:s');
	            if($request->input('id')>0){
	                $this->_communitytax->updateData($request->input('id'),$this->data);
	                $success_msg = 'Community Tax Certificate updated successfully.';
	                $lastinsertid = $request->input('id');
	            }else{
	                $this->data['created_by']=\Auth::user()->id;
	                $this->data['created_at'] = date('Y-m-d H:i:s');
	                $this->data['status'] = '1';
                    $this->data['cashier_or_date'] = $_POST['applicationdate'];
                    $this->data['net_tax_due_amount'] = $this->data['total_amount'];
                    $issueNumber = $this->getPrevIssueNumber();
                    $cashier_issue_no = str_pad($issueNumber, 5, '0', STR_PAD_LEFT);
                    $cashier_batch_no = date('Y')."-".$cashier_issue_no;

                    $getorRegister = $this->_commonmodel->Getorregisterid($this->ortype_id,$this->data['or_no']);
                    if($getorRegister != Null){
                      $uptassignmentrarr = array('or_count'=>$getorRegister->or_count -1,'latestusedor' => $this->data['or_no']);
                        $this->_communitytax->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);  
                      $this->data['or_assignment_id'] =  $getorRegister->assignid; 
                      $coaddata =$this->_commonmodel->getCoanoRegister($getorRegister->id);   
                      $this->data['or_register_id'] =  $getorRegister->id; 
                      $this->data['coa_no'] =  $coaddata->coa_no; 
                      if($getorRegister->or_count == 1){
                        $uptregisterarr = array('cpor_status'=>'2');
                        $this->_communitytax->updateOrRegisterData($getorRegister->id,$uptregisterarr);
                        $uptassignmentrarr = array('ora_is_completed'=>'1');
                        $this->_communitytax->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                      }   
                    }

                    $this->data['cashier_issue_no'] = $issueNumber; 
                    $this->data['cashier_batch_no'] = $cashier_batch_no; 
	                $lastinsertid = $this->_communitytax->addData($this->data);
                    Session::put('COMMUNITY_PRINT_CASHIER_ID',$lastinsertid);

	                $success_msg = 'Community Tax Certificate added successfully.';
	                $uptdata = array('latestusedor' => $this->data['or_no']);
	                $this->_communitytax->UpdateOrused('3',$uptdata);
                    $cashierdetails['cashier_batch_no'] =$cashier_batch_no;
	            }
	            $cashierdetails['cashier_id'] = $lastinsertid;
	            $Cashierid = $lastinsertid;
	            if(count($_POST['taxfees']) >0){
	            	foreach ($_POST['taxfees'] as $key => $value) {
	            		$cashdata = $this->_communitytax->getCasheringIds($value);

	            		$cashierdetails['tfoc_id'] = $value;
	            		$cashierdetails['ctc_taxable_amount'] =$_POST['taxableamount'][$key];
	            		$cashierdetails['tfc_amount'] = $_POST['amount'][$key];
                        $cashierdetails['all_total_amount'] = $_POST['amount'][$key];
	            		$cashierdetails['or_no'] = $this->data['or_no'];
	            		$cashierdetails['ortype_id'] =  $this->data['ortype_id'];
	            		$cashierdetails['cashier_remarks'] = $this->data['cashier_remarks'];
	            		$cashierdetails['agl_account_id'] = $cashdata->gl_account_id;
            			$cashierdetails['sl_id'] = $cashdata->sl_id;

	            		$checkdetailexist =  $this->_communitytax->checkrecordisexist($value,$Cashierid);
	            		if(count($checkdetailexist) > 0){
	            				$this->_communitytax->updateCashierDetailsData($checkdetailexist[0]->id,$cashierdetails);
	            		} else{
                            $cashierdetails['cashier_issue_no'] =$cashier_issue_no;
	            			$cashierdetailid = $this->_communitytax->addCashierDetailsData($cashierdetails);
                            $this->_commonmodel->insertCashReceipt($value, $_POST['amount'][$key], $request->or_no,$request->cashier_particulars);

                            $addincomedata = array();
                            $addincomedata['cashier_id'] = $lastinsertid;
                            $addincomedata['cashier_details_id'] = $cashierdetailid;
                            $addincomedata['tfoc_is_applicable'] = '7';
                            $addincomedata['taxpayer_name'] = $taxpayername;
                            $addincomedata['barangay_id'] = $clientdata->p_barangay_id_no;
                            $addincomedata['amount'] = $_POST['amount'][$key];
                            $addincomedata['tfoc_id'] = $value;
                            $addincomedata['fund_id'] = $cashdata->fund_id;
                            $addincomedata['gl_account_id'] = $cashdata->gl_account_id;
                            $addincomedata['sl_account_id'] = $cashdata->sl_id;
                            $addincomedata['cashier_or_date'] = $_POST['applicationdate'];
                            $addincomedata['or_no'] = $this->data['or_no'];
                            $addincomedata['form_code'] = $coaddata->cpor_series;
                            $addincomedata['or_register_id'] =  $getorRegister->id;
                            $addincomedata['or_from'] =  $getorRegister->ora_from;
                            $addincomedata['or_to'] =  $getorRegister->ora_to;
                            $addincomedata['coa_no'] =  $coaddata->coa_no;
                            $addincomedata['is_collected'] =  0;
                            $addincomedata['created_by'] =  \Auth::user()->id;
                            $addincomedata['created_at'] =  date('Y-m-d H:i:s');
                            $this->_commonmodel->addcashierIncomeData($addincomedata);
	            		}
	            	}
	            }
                $smsTemplate=SmsTemplate::where('id',67)->where('is_active',1)->first();
                if($data->payee_type==1){
                  $arrData = $this->_communitytax->getappdatataxpayer($this->data['client_citizen_id']);
                }else{
                   $arrData = $this->_communitytax->getappdatacitizen($this->data['client_citizen_id']); 
                }
                if(!empty($smsTemplate) && $arrData->p_mobile_no != null)
                {
                        $receipient=$arrData->p_mobile_no;
                        $msg=$smsTemplate->template;
                        $msg = str_replace('<NAME>', $arrData->full_name,$msg);
                        //$msg = str_replace('<TOP_NO>',$transactionno,$msg);
                        $msg = str_replace('<AMOUNT>',$this->data['total_amount'],$msg);
                        $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                       $this->send($msg, $receipient);
                }
	            return redirect()->route('community-tax.index')->with('success', __($success_msg));
	        }
	        return view('communitytax.create',compact('data','clientsarr','arrFeeDetails','feesaray','arrcancelreason'));
        
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
    public function getPrevIssueNumber(){
        $number=1;
        $arrPrev = $this->_communitytax->getPreviousIssueNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->cashier_issue_no+1;
        }
        return $number;
    }

     public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'or_no'=>'required|unique:cto_cashier,or_no,'.(int)$request->input('id'),
                'client_citizen_id'=>'required', 
                'total_amount'=>'required', 
                'total_paid_amount'=>'required|gte:total_amount',
                'total_amount_change'=>'required'
            ],
			[
				'or_no.unique' => 'This O.R. No. has already been taken',
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

    public function getbploappdetails(Request $request){
    	$id= $request->input('id');
        $data = $this->_communitytax->getBploApplictaiondetails($id);
        echo json_encode($data);
    }

}
