<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RptDelinquent;
use App\Models\RptShortCollection;
use App\Models\RptProperty;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\Barangay;
use App\Models\ProfileMunicipality;
use File;
use App\Http\Controllers\Bplo\TreasurerAssessmentController;
use Illuminate\Support\Facades\Mail;
use DB;

class RptShortCollectionController extends Controller
{
    public $data = [];
    public $arrBarangay = array(""=>"Select Barangay");
    private $slugs;
    public function __construct(){
        $this->shortcollection = new RptShortCollection(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_muncipality = new ProfileMunicipality;
        $this->_barangay    = new Barangay;
        $this->data = array('id'=>'','busn_id'=>'','last_paid_date'=>'','busns_id_no'=>'','busn_name'=>'','ownar_name'=>'','application_date'=>'','application_date'=>'','pm_desc'=>'','p_email_address'=>'');
        foreach ($this->_muncipality->getRptActiveMuncipalityBarngyCodes() as $val) {
            $this->arrBarangay[$val->id]=$val->brgy_code.'-'.$val->brgy_name;
        }
        $this->slugs = 'rpt-short-collection';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $arrBarangay = $this->arrBarangay;
        return view('rptshortcollection.index',compact('arrBarangay'));
    }

    public function getAllTds(Request $request){
         $data = $this->shortcollection->getAllTds($request);
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
    public function getList(Request $request){
        $data=$this->shortcollection->getList($request);
       // dd($data);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  

        foreach ($data['data'] as $row){

            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;  
            $arr[$i]['taxDeclarationNo']=$row->taxDeclarationNo;
            $ownar_name=$row->rpo_first_name.' '.$row->rpo_middle_name.' '.$row->rpo_custom_last_name;
            if(!empty($row->suffix)){
                $ownar_name .=", ".$row->suffix;
            }
            $arr[$i]['ownar_name']=$ownar_name;
            $arr[$i]['email']=$row->p_email_address;
            $arr[$i]['brgy_name']=$row->brgy_name;
            $arr[$i]['prop_type']=$row->pk_code.'-'.$row->propertyClass;
            $unitMeaure = ($row->unitMeasure != '')?config('constants.lav_unit_measure.'.$row->unitMeasure):'';
            $arr[$i]['area']=Helper::decimal_format($row->area).' '.$unitMeaure;
            $arr[$i]['assessedValue']=Helper::money_format($row->assessedValue);
            $arr[$i]['top_no']=$row->txnId;
            //$last_paid_date = (!empty($row->last_paid_date))?date("M d, Y",strtotime($row->last_paid_date)):'';
            //$arr[$i]['last_paid_date']=$last_paid_date;
            $arr[$i]['last_or_no']=$row->lastOrNo;
            $arr[$i]['last_or_amount']=Helper::money_format($row->lastOrAmount);
            $arr[$i]['last_or_date']=$row->lastOrDate;
            $arr[$i]['short_amount']=Helper::money_format($row->newAmount-$row->paidAmount);
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rpt-short-collection/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="View"  data-title="Manage Real Proprty Tax: Payment File">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center sendEmail" title="Send Email" d_id="'.$row->id.'" email="'.$row->p_email_address.'">
                        <i class="ti-email text-white"></i>
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
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data       = RptProperty::where('id',$request->input('id'))->first();
            $details = $this->shortcollection->getShortColleDetails($request->id);
            //dd($details);
        }
        return view('rptshortcollection.create',compact('data','details'));
    }

    public function generateViewForDeliquency($data = '',$displayType = 'email'){
        return view('mails.realPropertyTaxDeliquency',compact('data','displayType'))->render();
    }
    public function sendEmail(Request $request){
        $arrDtls = $this->shortcollection->with([
            'rptProperty'=>function($q){
            $q->select('id','rpo_code','pk_id','rvy_revision_year_id','brgy_code_id','rp_td_no');

        }
    ])
        ->join('rpt_delinquents AS bd2', 'bd2.rp_code', '=', 'rpt_delinquents.rp_code')
        ->select(
            'rpt_delinquents.*',
            DB::raw('MIN(bd2.year) as fromYear'),
            DB::raw('MAX(bd2.year) as toYear'),
            DB::raw('SUM(bd2.total_amount) as totalDueAmount'),
            DB::raw('SUM(bd2.basic_amount) as totalBasicAmount'),
            DB::raw('SUM(bd2.sef_amount) as totalSefAmount'),
            DB::raw('SUM(bd2.sh_amount) as totalShAmount'),
            DB::raw('SUM(bd2.basic_penalty) as totalBasicPenalty'),
            DB::raw('SUM(bd2.sef_penalty) as totalSefPenalty'),
            DB::raw('SUM(bd2.sh_penalty) as totalShPenalty'),
        )
        ->where('rpt_delinquents.id',$request->id)
        ->first();
        //dd($arrDtls->rptProperty);
        $id=$request->input('id');
        //dd($arrDtls->rptProperty->propertyOwner->p_email_address);
        if(isset($arrDtls)){
            if(!empty($arrDtls->rptProperty->propertyOwner->p_email_address)){
                //$this->_TreasurerAssessment = new TreasurerAssessmentController(); 
                $data=array();
                $encrypt = $this->_commonmodel->encryptData($id);
                $approve_url = url('/approveDelinquency/'.$encrypt);
                $description = 'Your payment still pending, Please pay as soon as possible.';
                $html = $this->generateHtmlOrPdf($arrDtls,2020,'delinquencyEmail');
                $html = str_replace("{APPROVE_URL}",$approve_url, $html);
                $html = str_replace("{DESCRIPTION}",$description, $html);
                $html = str_replace("{USER_EMAIL}",$arrDtls->rptProperty->propertyOwner->p_email_address, $html);

                $data['message'] = $html;
                $data['to_name']=$arrDtls->rptProperty->propertyOwner->rpo_first_name;
                $data['to_email']=$arrDtls->rptProperty->propertyOwner->p_email_address;
                $data['subject']='Delinquency Notice';

                Mail::send([], ['data' =>$data], function ($m) use ($data) {
                    $m->to($data['to_email'], $data['to_name']);
                    $m->subject($data['subject']);
                    $m->setBody($data['message'], 'text/html');
                }); 
            }
        }
    }

    public function generateHtmlOrPdf($data,$year,$displayType){
        if($displayType=="pdf"){
            $this->generatePdfFile($bus_id,$html,$data,$displayType);
        }elseif($displayType=='delinquencyEmail'){
            return $this->generateViewForDeliquency($data);
        }elseif($displayType=='assessmentEmail'){
            $data['isShowBtn']=0;
            $data['username']=$arrBussDtls->rpo_custom_last_name;
            $Finalhtml = view('mails.taxOrderPaymentEmail',compact('html','data','displayType'));
            return $Finalhtml;
        }
        // ************* End Display Content Details ************************
    }
    public function approveDelinquency(Request $request,$encrypt){
        $id = $this->_commonmodel->decryptData($encrypt);
        if($id>0){
            $arrData['is_approved']=1;
            $arrData['acknowledged_date']=date('Y-m-d H:i:s');
            $this->shortcollection->updateData($id,$arrData);
            return view('errors.DelinquencyThankyou');
        }
    }

    public function getDeliquentsTds($value=''){
        $tdsNotPaidYet = RptProperty::with([
            'revisionYearDetails'=>function($q){
                $q->select('id','rvy_revision_year');
            }
        ])
        ->select('rpt_properties.id','rpt_properties.rp_property_code','rpt_properties.pk_is_active','rpt_properties.rvy_revision_year_id','rpt_properties.rp_td_no','rpt_properties.rp_app_effective_year','billd.cbd_covered_year',DB::raw('MAX(billd.sd_mode) as lastPaidMode'))
        ->where('pk_is_active',1)

        ->leftJoin('rpt_cto_billings as bill',function($j){
                                 $j->on('bill.rp_code','=','rpt_properties.id');
                                 $j->where('bill.cb_is_paid',1);
                                 $j->join('rpt_cto_billing_details as billd',function($jagain){
                                    $jagain->on('billd.rp_code','=','bill.rp_code')
                                           ->orderBy('billd.cbd_covered_year','DESC');
                                 });
                                 //$j->whereRaw('billd.cbd_covered_year = rpt_delinquents.year');
                               })
        ->where('rp_app_effective_year','<',date("Y"))
        ->whereNotIn('rpt_properties.id',function($query){
                      $query->from('rpt_cto_billings as bill')
                      ->leftJoin('rpt_cto_billing_details as billd',function($join){
                          $join->on('billd.rp_code','=','billd.rp_code');
                     });
                      $query->select('billd.rp_code')
                      ->whereIn('billd.sd_mode',[44,14])
                      ->where('bill.cb_is_paid',1);
            })
        ->groupBy('rpt_properties.id')->get()
          ->take(50);
          //dd($tdsNotPaidYet);
          foreach ($tdsNotPaidYet as $value) {
              $startingYear = (isset($value->revisionYearDetails->rvy_revision_year))?$value->revisionYearDetails->rvy_revision_year:2023;
              //dd($startingYear);
              $dataToSaveInDelq = [
                'rp_code' => $value->id,
                'rp_property_code' => $value->rp_property_code,
                'created_at'       => date("Y-m-d H:i:s")
              ];
              $rptPropObj = new RptProperty;
              for ($i=$startingYear; $i <= date("Y")-1 ; $i++) { 
                $modes     = [14];
              if($value->lastPaidMode != null && $value->cbd_covered_year == $i && !in_array($value->lastPaidMode,[14,44])){
                $indesx = array_search($value->lastPaidMode,array_keys(Helper::billing_quarters()));
                $qtrOfBilling = array_keys(Helper::billing_quarters())[$indesx+1];
                $modes  = array_slice(array_keys(Helper::billing_quarters()),$indesx+1);
                  }
                  
                   foreach($modes as $mode){
                   $response = $rptPropObj->calculatePenaltyFee($value->id,$i,$mode);
                   $dataToSaveInDelq['year'] = $i;
                   $dataToSaveInDelq['sd_mode'] = $mode;
                   $dataToSaveInDelq['basic_amount'] = $response['basicAmount'];
                   $dataToSaveInDelq['sef_amount'] = $response['basicSefAmount'];
                   $dataToSaveInDelq['sh_amount'] = $response['basicShAMount'];
                   $dataToSaveInDelq['basic_penalty'] = $response['basicPenalty'];
                   $dataToSaveInDelq['sef_penalty'] = $response['sefPenalty'];
                   $dataToSaveInDelq['sh_penalty'] = $response['shPenalty'];
                   $dataToSaveInDelq['total_amount'] = ($response['basicAmount']+$response['basicPenalty'])+($response['basicSefAmount']+$response['sefPenalty'])+($response['basicShAMount']+$response['shPenalty']);
                   $checkAlreadyExist = $this->shortcollection
                                             ->where('rp_code',$value->id)
                                             ->where('year',$i)
                                             ->get();
                    if($checkAlreadyExist->isEmpty()){
                        $this->shortcollection->adData($dataToSaveInDelq);
                    }
                   }
                                            
              }
          }
        //dd($tdsNotPaidYet);
    }
}
