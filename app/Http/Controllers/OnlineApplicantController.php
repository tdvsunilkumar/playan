<?php

namespace App\Http\Controllers;
use App\Models\OnlineBploBusiness;
use App\Models\CommonModelmaster;
use App\Models\PsicSubclass;
use App\Models\BploBusinessPsic;
use App\Models\BploBusinessMeasurePax;
use App\Models\BploApplicationType;
use App\Models\Bplo\CtoPaymentMode;
use App\Models\BploBusinessPsicReq;
use App\Models\CashierRealProperty;
use App\Models\HoApplicationSanitary;
use App\Models\Barangay;
use App\Models\RptProperty;
use App\Models\Bplo\BploBusinessType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use File;
use \Mpdf\Mpdf as PDF;
use \NumberFormatter;
use Illuminate\Support\Facades\Storage;

class OnlineApplicantController extends Controller
{
    private $carbon;
    private $slugs;
    public $status = [
        '0' => 'Not Completed',
        '1' => 'Completed/For Verification',
        '2' => 'For Endorsement',
        '3' => 'For Assessment',
        '4' => 'For Payment',
        '5' => 'For Issuance',
        '6' => 'License Issued',
        '7' => 'Declined',
        '8' => 'Cancelled Permit'
    ];
    public $approve_status = [
        '0' => 'Pending',
        '1' => 'Accepted',
        '2' => 'Declined',
    ];
    public function __construct(Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->_hoappsanitary = new HoApplicationSanitary();
        $this->_barangay = new Barangay(); 
        $this->_BploBusiness = new OnlineBploBusiness(); 
        $this->_PsicSubclass = new PsicSubclass(); 
        $this->_BploBusinessMeasurePax = new BploBusinessMeasurePax(); 
        $this->_BploBusinessPsic = new BploBusinessPsic(); 
        $this->_BploApplicationType = new BploApplicationType(); 
        $this->_BploBusinessPsicReq = new BploBusinessPsicReq(); 
        $this->_CtoPaymentMode = new CtoPaymentMode(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_RptProperty = new RptProperty();
        $this->_CashierRealProperty = new CashierRealProperty();
        $this->carbon = $carbon;
        $this->slugs = 'business-online-application';
    }    

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $bsn_type=array(""=>"Please select");
	    $bsn_activity=array(""=>"Please select");
        $client=array(""=>"Please select");

        //$barangay=$this->_BploBusiness->getBarangay();
        $barangay=array(""=>"Please select");
        $psicSubclass=array(""=>"Please select");
        $floor_val=array();
        $app_type=array(""=>"Please select");
        $pay_mode=array(""=>"Please select");
        $locality=$this->_BploBusiness->allLocality();
        // $rpt_property=$this->_RptProperty->allRptProperty();
        $bplo_busn = ['' => 'Select a Business Plan'];
        $measure_pax = ['' => 'Select Measure or Pax'];
        $reqName = ['' => 'Select Requirment'];
        $serch_status =config('constants.arrBusinessApplicationStatus');
        $busn_tax_year=2023;
        $pm_id=1;
        $app_code=1;
        $to_date=Carbon::now()->format('Y-m-d');
        $from_date=Carbon::now()->format('Y-m-d');
        return view('OnlineBploBusiness.index')->with(compact('bsn_type','floor_val','to_date','from_date','locality','busn_tax_year','pm_id','app_code','app_type','pay_mode','reqName','bplo_busn','measure_pax','bsn_activity','client','barangay','psicSubclass','serch_status'));
    }

    public function getList(Request $request){
       $this->is_permitted($this->slugs, 'read');
       $data=$this->_BploBusiness->getList($request);
       $arr=array();
       $i="0";    
       $sr_no=(int)$request->input('start')-1; 
       $sr_no=$sr_no>0? $sr_no+1:0;
       foreach ($data['data'] as $row){
           $sr_no=$sr_no+1;
           $actions = '';
           $actions .= '<div class="action-btn bg-success ms-2">
                            <a href="javascript:;" class="mx-3 btn btn-sm  align-items-center edit-btn" data-id='.$row->frgn_busn_id.' title="Accept">
                                    <i class="ti-eye text-white"></i>
                                </a>
                        </div>';    
           $serial_no = $sr_no;
           $owner = $row->client_id != NULL ? $row->rpo_first_name  ." ". $row->rpo_middle_name ." ". $row->rpo_custom_last_name : "";
           $application_date = Carbon::parse($row->application_date)->format('d-M-Y');
           $last_pay_date = !empty($row->last_pay_date) ? Carbon::parse($row->last_pay_date)->format('d-M-Y') : "";
           $barangay=$row->office_brgy_name != NULL ? $row->office_brgy_name . ', ' . $row->office_mun_desc . ', ' . $row->office_prov_desc . ', ' . $row->office_reg_region : "";
           $barangay_new = wordwrap($barangay, 40, "<br />\n");
           $app_status = $this->approve_status[$row->is_approved];
              
           $startCarbon = Carbon::parse($row->application_date);
           $endCarbon = date('Y-m-d');
           $diff = $startCarbon->diff($endCarbon);
           if ($diff->days == 0) {
               $duration = "";
           } 
           elseif($diff->days == 1) {
               $duration = $diff->days . " Day";
           } else {
               $duration = $diff->days . " Days";
           }
           if($row->app_type=='Retire'){
                $serial_no='<strike style="color:red;">'.$serial_no.' </strike>';
                $row->busns_id_no = '<strike style="color:red;">'.$row->busns_id_no.' </strike>';
                $owner = '<strike style="color:red;">'.$owner.' </strike>';
                $row->busn_name = '<strike style="color:red;">'.$row->busn_name.' </strike>';
                $row->app_type = '<strike style="color:red;">'.$row->app_type.' </strike>';
                $application_date = '<strike style="color:red;">'.$application_date.' </strike>';
                $last_pay_date = '<strike style="color:red;">'.$last_pay_date.' </strike>';
                $app_status = '<strike style="color:red;">'.$app_status.' </strike>';
                $row->office_brgy_name = '<strike style="color:red;">'.$row->office_brgy_name.' </strike>';
                $barangay_new = '<strike style="color:red;">'.$barangay_new.' </strike>';
                $row->busn_app_method= '<strike style="color:red;">'.$row->busn_app_method.' </strike>';
                $duration='<strike style="color:red;">'.$duration.' </strike>';
           }
           $arr[$i]['srno']=$serial_no;
           $arr[$i]['busn_id_no']=$row->busns_id_no;
           $arr[$i]['owner']=utf8_encode($owner);
           $arr[$i]['busn_name']=$row->busn_name;
           $arr[$i]['app_type']=$row->app_type;
           $arr[$i]['app_date']= $application_date;
           $arr[$i]['last_pay_date']= $last_pay_date;
           $arr[$i]['busn_app_status']= $app_status;
           $arr[$i]['app_method']=$row->busn_app_method;
           $arr[$i]['barangay']=$row->office_brgy_name;
           $arr[$i]['duration']=$duration;
           $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
           $arr[$i]['action']=$actions;
          
           $i++;
       }
       
       $totalRecords=$data['data_cnt'];
       $json_data = array(
           "recordsTotal"    => intval($totalRecords),  
           "recordsFiltered" => intval($totalRecords),
           "data"            => $arr   // total data array
       );
       echo json_encode($json_data);
   }

    public function busn_psic_lists(Request $request,$busn_id)//used
    {  
        $result = $this->_BploBusiness->bploBusnPsicList($request,$busn_id);
        $result1=(object)  $result;
       

        $res = $result1->data->map(function($subClass)  {
            $wrap_desc = wordwrap($subClass->subclass_description, 100, "<br />\n");
            $desc="<div class='showLess'>".$wrap_desc."</div>";
            return [
                'id' => $subClass->ID,
                'code' => $subClass->subclass_code,
                'desc' =>  $desc,
                'busp_no_units' =>   $subClass->busp_no_units,
                'busp_capital_investment' =>  $subClass->busp_capital_investment,
                'busp_essential' =>  $subClass->busp_essential,
                'busp_non_essential' => $subClass->busp_non_essential,
                'action'     => ''
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result1->data_cnt),  
			"recordsFiltered" => intval($result1->data_cnt),
            'data' => $res,
        ]);
    }

    public function requirment_doc_list(Request $request,$busn_id)//used
    {  
        $result = $this->_BploBusiness->bploRequirmentDocList($request,$busn_id);
        $result1=(object)  $result;
        $res = $result1->data->map(function($docList) {
            $remotePath = 'bplo_business_req_doc/'.$docList->attachment;
            // $remotePath = 'public/uploads/billing/bplo/' . $arrTran->attachment;
            // Retrieve the file contents from the remote disk
            $fileContents = config('filesystems.disks.remote')['asset_url'].$remotePath;
            $action =  '<a href="'.$fileContents.'" class="action-btn bg-warning btn ms-05 btn-sm align-items-center" target="_blank" title="Download">
                            <i class="ti-download"></i>
                        </a>';      
            $wrap_desc = wordwrap($docList->subclass_description, 100, "<br />\n");
            $desc="<div class='showLess'>".$wrap_desc."</div>";
            return [
                'id' => $docList->ID,
                'line_business' => $docList->subclass_description,
                'code' => $docList->req_description,
                'desc' =>  $docList->req_description,
                'attachment' => $docList->attachment,
                'action'     => $action
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result1->data_cnt),  
			"recordsFiltered" => intval($result1->data_cnt),
            'data' => $res,
        ]);
    }
    public function download_doc(Request $request,$id)
    {
        $doc=$this->_BploBusinessPsicReq->find($id);
        $filePath =  public_path().'/uploads/bplo_business_req_doc/'.$doc->attachment;
        return Storage::disk('public')->download($filePath); //
        //return Storage::download($filePath);
    }

    public function busn_measure_lists(Request $request,$busn_id)//used
    {  
        $result = $this->_BploBusiness->bploMeasurePaxList($request,$busn_id);
        $result1=(object)  $result;
        $res = $result1->data->map(function($measurePax) {
            $wrap_desc = wordwrap($measurePax->subclass_description, 100, "<br />\n");
            $desc="<div class='showLess'>".$wrap_desc."</div>";
            $wrap_charge_desc = wordwrap($measurePax->charge_desc, 100, "<br />\n");
            $charge_desc="<div class='showLess'>".$wrap_charge_desc."</div>";
           
            return [
                'id' => $measurePax->ID,
                'subclass_description' => $desc,
                'buspx_no_units' =>  $measurePax->buspx_no_units,
                'buspx_capacity' =>   $measurePax->buspx_capacity,
                'charge_desc' =>  $charge_desc,
                'action'     => ''
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result1->data_cnt),  
			"recordsFiltered" => intval($result1->data_cnt),
            'data' => $res,
        ]);
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        $isTest=$this->_commonmodel->getConfiguration('PREV_APP_DATE');
        $data = $this->_BploBusiness->find($id);
        if(!empty($data->busn_office_main_barangay_id))
        {
            foreach ($this->_BploBusiness->getBarangay($data->busn_office_main_barangay_id)['data'] as $val) {
                $data->busn_office_main_barangay_id = "<option value='".$val->id."' selected>".$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region."</option>";
            }
        }
        if(!empty($data->busn_office_barangay_id))
        {
            foreach ($this->_BploBusiness->getBarangay($data->busn_office_barangay_id)['data'] as $val) {
                $data->busn_office_barangay_id = "<option value='".$val->id."' selected>".$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region."</option>";
            }
        } 
        if(!empty($data->app_code))
        {
            foreach ($this->_BploBusiness->allAppType($data->app_code)['data'] as $val) {
                $data->app_code = "<option value='".$val->id."' selected>".$val->app_type."</option>";
            }
        }  
        if(!empty($data->pm_id))
        {
            foreach ($this->_BploBusiness->allPayMode($data->pm_id)['data'] as $val) {
                $data->pm_id = "<option value='".$val->id."' selected>".$val->pm_desc."</option>";
            }
        }  
        if(!empty($data->btype_id))
        {
            foreach ($this->_BploBusiness->getBsnType($data->btype_id)['data'] as $val) {
                $data->btype_id = "<option value='".$val->id."' selected>".$val->btype_desc."</option>";
            }
        } 
        if(!empty($data->busloc_id))
        {
            foreach ($this->_BploBusiness->getBsnActivity($data->busloc_id)['data'] as $val) {
                $data->busloc_id = "<option value='".$val->id."' selected>".$val->busloc_desc."</option>";
            }
        }  
        if(!empty($data->client_id))
        {
            foreach ($this->_BploBusiness->getOwnerName($data->client_id)['data'] as $val) {
                $data->client_id_html = "<option value='".$val->id."' selected>".$val->full_name."</option>";
            }
        }  
        return response()->json([
            'data' => $data,
            'isTest' => (int)$isTest
        ]);
    }

    public function approve(Request $request,$id)
    {
        $data=$this->_BploBusiness->approve($id);
        return response()->json([
            'data' =>$data
        ]);
    }
    public function decline(Request $request,$id)
    {
        $data=$this->_BploBusiness->decline($id);
        return response()->json([
            'data' =>$data
        ]);
    }
    
    // public function store(Request $request)
    // {
    //     $this->is_permitted($this->slugs, 'create');
    //     if($request->get('busn_status') == 1)
    //     {
    //             return response()->json([
    //                 'data' => 'error',
    //                 'title' => 'Error!',
    //                 'text' => 'At least one business plan is needed.',
    //                 'type' => 'error',
    //                 'class' => 'btn-brand'
    //             ]);
    //     }
    //     if($request->get('busn_tax_year') != null)
    //     {
    //         $currentYear =  $request->get('busn_tax_year');
    //         $currentMonth = Carbon::now()->month;
    //         $this->is_permitted($this->slugs, 'create');
    //         $lastRecord = BploBusiness::latest()->first();
    //         $lastValue = $lastRecord ? $lastRecord->id : null;
    //         $lastNumber = intval(substr($lastValue, -6));
    //         $lastMonth = intval(substr($lastValue, 5, 2));
    //         $lastYear = intval(substr($lastValue, 0, 4));
    //         $newNumber = $lastNumber + 1;
    //         $busn_series_no=sprintf('%06d', $newNumber);
    //         $newValue = sprintf('%04d-%02d-%06d', $currentYear, $currentMonth, $newNumber);
    //     }
    //     else{
    //         $busn_series_no=NULL;
    //         $newValue=NULL;
    //     }
    //     $timestamp = $this->carbon::now();
    //     $floors=$request->get('floor_val_id');
    //     if(!empty($floors)){
    //         $floors_json=json_encode($floors);
    //     }else{
    //         $floors_json="";
    //     }
    //     $details = array(
    //         'busn_tax_month' => $this->carbon::now()->month,
    //         'busn_series_no' => $busn_series_no,
    //         'busn_tracking_no' => $newValue,
    //         'locality_id' => $request->get('locality_id'),
    //         'loc_local_id' => $request->get('loc_local_id'),
    //         'app_code' => $request->get('app_code'),
    //         'pm_id' => $request->get('pm_id'),
    //         'busn_tax_year' => $request->get('busn_tax_year'),
    //         'busn_name' => $request->get('busn_name'),
    //         'btype_id' => $request->get('btype_id'),
    //         'busn_trade_name' => $request->get('busn_trade_name'),
    //         'busn_registration_no' => $request->get('busn_registration_no'),
    //         'busn_tin_no' => $request->get('busn_tin_no'),
    //         'busn_office_main_barangay_id' => $request->get('busn_office_main_barangay_id'),
    //         'busn_office_main_building_no' => $request->get('busn_office_main_building_no'),
    //         'busn_office_main_building_name' => $request->get('busn_office_main_building_name'),
    //         'busn_office_main_add_block_no' => $request->get('busn_office_main_add_block_no'),
    //         'busn_office_main_add_lot_no' => $request->get('busn_office_main_add_lot_no'),
    //         'busn_office_main_add_street_name' => $request->get('busn_office_main_add_street_name'),
    //         'busn_office_main_add_subdivision' => $request->get('busn_office_main_add_subdivision'),
    //         'client_id' => $request->get('client_id'),

    //         'busloc_id' => $request->get('busloc_id'),
    //         'busn_bldg_area' => $request->get('busn_bldg_area'),
    //         'busn_bldg_total_floor_area' => $request->get('busn_bldg_total_floor_area'),
    //         'busn_employee_no_female' => $request->get('busn_employee_no_female'),
    //         'busn_employee_no_male' => $request->get('busn_employee_no_male'),
    //         'busn_employee_total_no' => $request->get('busn_employee_no_female') + $request->get('busn_employee_no_male'),
    //         'busn_employee_no_lgu' => $request->get('busn_employee_no_lgu'),
    //         'busn_vehicle_no_van_truck' => $request->get('busn_vehicle_no_van_truck'),
    //         'busn_vehicle_no_motorcycle' => $request->get('busn_vehicle_no_motorcycle'),
    //         'busn_bldg_is_owned' => $request->input('radio'),
    //         'busn_bldg_tax_declaration_no' => $request->get('busn_bldg_tax_declaration_no'),
    //         'rp_property_code' => $request->get('rp_property_code'),
    //         'rp_code' => $request->get('rp_code'),

    //         'busn_bldg_property_index_no' => $request->get('busn_bldg_property_index_no'),
    //         'floor_val_id' => $floors_json,
    //         'busn_tax_incentive_enjoy' => $request->input('en_radio'),
    //         'busn_office_is_same_as_main' => $request->get('is_check'),
    //         // 'busn_office_is_same_as_main' => $request->get('busn_office_is_same_as_main'),
    //         'busn_office_building_no' => $request->get('busn_office_building_no'),
    //         'busn_office_building_name' => $request->get('busn_office_building_name'),
    //         'busn_office_add_block_no' => $request->get('busn_office_add_block_no'),
    //         'busn_office_add_lot_no' => $request->get('busn_office_add_lot_no'),
    //         'busn_office_add_street_name' => $request->get('busn_office_add_street_name'),
    //         'busn_office_add_subdivision' => $request->get('busn_office_add_subdivision'),
    //         'busn_office_barangay_id' => $request->get('busn_office_barangay_id'),
    //         'busn_app_status' => 0,
    //         'application_date' => $timestamp,
    //         'created_at' => $timestamp,
    //         'created_by' => Auth::user()->id
    //     );
    //     if($request->get('btype_id') != null)
    //     {
    //         $busn_type_det=BploBusinessType::getEditDetails($request->get('btype_id'));
    //         $details['is_individual'] = $busn_type_det->is_individual;
    //     }
    //     $bplo_business = $this->_BploBusiness->create($details);

    //     return response()->json(
    //         [
    //             'data' => $bplo_business,
    //             'title' => 'Well done!',
    //             'text' => 'Data has been successfully added.',
    //             'type' => 'success',
    //             'class' => 'btn-brand'
    //         ],
    //         Response::HTTP_CREATED
    //     );
    // }

    // public function update(Request $request, $id)
    // {
    //     $this->is_permitted($this->slugs, 'update');
    //     $timestamp = $this->carbon::now();
    //     $floors=$request->get('floor_val_id');
    //     if(!empty($floors)){
    //         $floors_json=json_encode($floors);
    //     }else{
    //         $floors_json="";
    //     }
    //     $bplo_busn = $this->_BploBusiness->find($id);
    //     if($bplo_busn->busn_app_status > 1){
    //         $status=$bplo_busn->busn_app_status;
    //     }else{
    //         $status=$request->get('busn_status');
    //     }
    //     if($request->get('busn_status') == 1)
    //     {
    //         $msz="Data has been successfully Submitted.";
    //         $busn_plan=DB::table('bplo_business_psic')
    //                     ->where('busn_id',$id)
    //                     ->first();
    //         if($busn_plan == NULL){
    //             return response()->json([
    //                 'data' => $bplo_busn,
    //                 'title' => 'Error!',
    //                 'text' => 'At least one business plan is needed.',
    //                 'type' => 'error',
    //                 'class' => 'btn-brand'
    //             ]);
    //         }
    //     }
    //     else{
    //         $msz="Data has been successfully Updated.";
    //     }
    //     $details = array(
    //                     'app_code' => $request->get('app_code'),
    //                     'pm_id' => $request->get('pm_id'),
    //                     'busn_tax_year' => $request->get('busn_tax_year'),
    //                     'busn_app_status' => $status,
    //                     'updated_at' => $timestamp,
    //                     'updated_by' => Auth::user()->id
    //                     );
    //      if($request->get('busn_name') != NULL)
    //      {
    //         if($request->get('btype_id') != null)
    //         {
    //             $busn_type_det=BploBusinessType::getEditDetails($request->get('btype_id'));
    //             $is_individual = $busn_type_det->is_individual;
    //         }
    //         $newDetails = array(
    //             'busn_name' => $request->get('busn_name'),
    //             'btype_id' => $request->get('btype_id'),
    //             'is_individual' => $is_individual,
    //             'busn_trade_name' => $request->get('busn_trade_name'),
    //             'busn_registration_no' => $request->get('busn_registration_no'),
    //             'busn_tin_no' => $request->get('busn_tin_no'),
    //             'busn_office_main_barangay_id' => $request->get('busn_office_main_barangay_id'),
    //             'busn_office_main_building_no' => $request->get('busn_office_main_building_no'),
    //             'busn_office_main_building_name' => $request->get('busn_office_main_building_name'),
    //             'busn_office_main_add_block_no' => $request->get('busn_office_main_add_block_no'),
    //             'busn_office_main_add_lot_no' => $request->get('busn_office_main_add_lot_no'),
    //             'busn_office_main_add_street_name' => $request->get('busn_office_main_add_street_name'),
    //             'busn_office_main_add_subdivision' => $request->get('busn_office_main_add_subdivision'),
    //             'client_id' => $request->get('client_id'),
    //         );
    //         $details = array_merge($details, $newDetails);
    //      }  
    //      if($request->get('busloc_id') != NULL) 
    //      {
    //         $newDetails2=array(
    //         'busloc_id' => $request->get('busloc_id'),
    //         'busn_bldg_area' => $request->get('busn_bldg_area'),
    //         'busn_bldg_total_floor_area' => $request->get('busn_bldg_total_floor_area'),
    //         'busn_employee_no_female' => $request->get('busn_employee_no_female'),
    //         'busn_employee_no_male' => $request->get('busn_employee_no_male'),
    //         'busn_employee_total_no' => $request->get('busn_employee_no_female') + $request->get('busn_employee_no_male'),
    //         'busn_employee_no_lgu' => $request->get('busn_employee_no_lgu'),
    //         'busn_vehicle_no_van_truck' => $request->get('busn_vehicle_no_van_truck'),
    //         'busn_vehicle_no_motorcycle' => $request->get('busn_vehicle_no_motorcycle'),
    //         'busn_bldg_is_owned' => $request->input('radio'),
    //         'busn_bldg_tax_declaration_no' => $request->get('busn_bldg_tax_declaration_no'),
    //         'rp_property_code' => $request->get('rp_property_code'),
    //         'rp_code' => $request->get('rp_code'),
    //         'busn_bldg_property_index_no' => $request->get('busn_bldg_property_index_no'),
    //         'floor_val_id' => $floors_json,
    //         'busn_tax_incentive_enjoy' => $request->get('en_radio'),
    //         // 'busn_office_is_same_as_main' => $request->get('busn_office_is_same_as_main'),
    //         'busn_office_is_same_as_main' => $request->get('is_check'),
    //         'busn_office_building_no' => $request->get('busn_office_building_no'),
    //         'busn_office_building_name' => $request->get('busn_office_building_name'),
    //         'busn_office_add_block_no' => $request->get('busn_office_add_block_no'),
    //         'busn_office_add_lot_no' => $request->get('busn_office_add_lot_no'),
    //         'busn_office_add_street_name' => $request->get('busn_office_add_street_name'),
    //         'busn_office_add_subdivision' => $request->get('busn_office_add_subdivision'),
    //         'busn_office_barangay_id' => $request->get('busn_office_barangay_id'),
    //         );
    //         $details = array_merge($details, $newDetails2);
    //      }
    //     $bplo_business = $this->_BploBusiness->updateData($id, $details);
    //     $bplo_busn_history = $this->_BploBusiness->BploBusinessHistoryByBusnId($id);   
    //     if(count($bplo_busn_history) == 0 && $request->get('busn_status') == 1){
    //         $l_bplo_business = $this->_BploBusiness->findForUpdateHistory($id);
    //         foreach($l_bplo_business as $key=>$val){
    //             $historyData[$key] = $l_bplo_business->$key;
    //         }
    //         $newHistory=array(
    //                             'busn_id' => $id,
    //                         );
    //         $historyData = array_merge($historyData, $newHistory);                
    //         $this->_BploBusiness->addBploBusinessHistory($historyData);
    //     }
    //     return response()->json([
    //         'data' => $bplo_business,
    //         'title' => 'Well done!',
    //         'text' => $msz,
    //         'type' => 'success',
    //         'class' => 'btn-brand'
    //     ]);
    // }
    // public function ActiveInactive(Request $request){
    //     $this->is_permitted($this->slugs, 'delete');
    //     $id = $request->input('id');
    //     $is_activeinactive = $request->input('is_activeinactive');
    //     $data=array('is_active' => $is_activeinactive);
    //     $this->_BploBusiness->updateActiveInactive($id,$data);
    //     // Log Details Start
    //     $action = $is_activeinactive==1?'Restored':'Soft Deleted';
    //     $logDetails['module_id'] =$id;
    //     $logDetails['log_content'] = "User '".\Auth::user()->name."' Bplo Formula ".$action; 
    //     $this->_commonmodel->updateLog($logDetails);
    //     // Log Details End
    // }
    // public function NewRenew(Request $request){
    //     $this->is_permitted($this->slugs, 'update');
    //     $id = $request->input('id');
    //     $is_newRenew = $request->input('is_newRenew');
    //     $data=array('app_code' => $is_newRenew);
    //     $this->_BploBusiness->updateActiveInactive($id,$data);
    //     // Log Details Start
    //     $action = $is_newRenew==1?'Renew':'New';
    //     $logDetails['module_id'] =$id;
    //     $logDetails['log_content'] = "User '".\Auth::user()->name."' updated Bplo appllication type ".$action; 
    //     $this->_commonmodel->updateLog($logDetails);
    //     // Log Details End
    // }
    
    // public function add_business_plan(Request $request)
    // {
    //     $timestamp = $this->carbon::now();
    //     $verify=$this->_BploBusinessPsic->verifyUnique($request->get('subclass_id'),$request->get('busn_id'));
    //     if($request->get('plan_id') != 0)
    //     {
    //         $pre_bplo_business = $this->_BploBusinessPsic->find($request->get('plan_id'));
    //         if($pre_bplo_business->subclass_id != $request->get('subclass_id') && $verify > 0)
    //         {
    //             return response()->json(
    //                 [
    //                     'title' => 'Warning!',
    //                     'text' => "The line of business already exists.",
    //                     'type' => 'error',
    //                     'class' => 'btn-brand'
    //                 ],
    //                 Response::HTTP_CREATED
    //             );
    //         }
    //             $details = array(
    //                 'subclass_id' => $request->get('subclass_id'),
    //                 'busn_id' =>  $request->get('busn_id'),
    //                 'busp_no_units' => $request->get('busp_no_units'),
    //                 'busp_capital_investment' => $request->get('busp_capital_investment'),
    //                 'busp_essential' => $request->get('busp_essential'),
    //                 'busp_non_essential' => $request->get('busp_non_essential'),
    //                 'busp_total_gross' => $request->get('busp_non_essential') + $request->get('busp_essential'),
    //                 'updated_at' => $timestamp,
    //                 'updated_by' => Auth::user()->id
    //             );
    //         $bplo_business = $this->_BploBusiness->update_bsn_plan($request->get('plan_id'),$details);
    //         return response()->json([
    //             'data' => $bplo_business,
    //             'title' => 'Well done!',
    //             'text' => 'Data has been successfully Updated.',
    //             'type' => 'success',
    //             'class' => 'btn-brand'
    //         ]);
    //     }
    //     else{
    //         if($verify > 0)
    //         {
    //             return response()->json(
    //                 [
    //                     'title' => 'Warning!',
    //                     'text' => "The line of business already exists.",
    //                     'type' => 'error',
    //                     'class' => 'btn-brand'
    //                 ],
    //                 Response::HTTP_CREATED
    //             );
    //         }
    //             $details = array(
    //                 'subclass_id' => $request->get('subclass_id'),
    //                 'busn_id' =>  $request->get('busn_id'),
    //                 'busp_no_units' => $request->get('busp_no_units'),
    //                 'busp_capital_investment' => $request->get('busp_capital_investment'),
    //                 'busp_essential' => $request->get('busp_essential'),
    //                 'busp_non_essential' => $request->get('busp_non_essential'),
    //                 'busp_total_gross' => $request->get('busp_non_essential') + $request->get('busp_essential'),
    //                 'created_at' => $timestamp,
    //                 'created_by' => Auth::user()->id
    //             );
    //         $bplo_business = $this->_BploBusiness->add_bsn_plan($details);
    //         return response()->json([
    //             'data' => $bplo_business,
    //             'title' => 'Well done!',
    //             'text' => 'Data has been successfully added.',
    //             'type' => 'success',
    //             'class' => 'btn-brand'
    //         ]);
    //     }
            
    // }

    // public function add_measure_pax(Request $request)
    // {
    //     $timestamp = $this->carbon::now();
    //     $verify=$this->_BploBusinessMeasurePax->verifyUnique($request->get('busn_id'),$request->get('busn_psic_id'),$request->get('buspx_charge_id'));
    //     if($request->get('id') != 0)
    //     {
    //         $measure_pax=$this->_BploBusinessMeasurePax->find_measure_pax($request->get('id'));
    //         if($measure_pax->buspx_charge_id != $request->get('buspx_charge_id'))
    //         {
    //             if($verify > 0)
    //             {
    //                 return response()->json(
    //                     [
    //                         'title' => 'Warning!',
    //                         'text' => "The measure or pax already exists.",
    //                         'type' => 'error',
    //                         'class' => 'btn-brand'
    //                     ],
    //                     Response::HTTP_CREATED
    //                 );
    //             } 
    //         }
    //         $id =  $request->get('id');
    //         $details = array(
    //             'buspx_capacity' => $request->get('buspx_capacity'),
    //             'buspx_no_units' => $request->get('buspx_no_units'),
    //             'updated_at' => $timestamp,
    //             'updated_by' => Auth::user()->id
    //         );
    //         $bploBusinessPsic = $this->_BploBusinessPsic->find($request->get('busn_psic_id'));
    //         $details = array(
    //             'busn_id' => $request->get('busn_id'),
    //             'busn_psic_id' =>  $request->get('busn_psic_id'),
    //             'subclass_id' => $bploBusinessPsic->subclass_id,
    //             'tfoc_id' => $request->get('tfoc_id'),
    //             'buspx_charge_id' => $request->get('buspx_charge_id'),
    //             'buspx_capacity' => $request->get('buspx_capacity'),
    //             'buspx_no_units' => $request->get('buspx_no_units'),
    //             'updated_at' => $timestamp,
    //             'updated_by' => Auth::user()->id
    //         );
    //         $bplo_business = $this->_BploBusiness->update_measure_pax($id,$details);
    //         return response()->json([
    //             'data' => $bplo_business,
    //             'title' => 'Well done!',
    //             'text' => 'Data has been successfully updated.',
    //             'type' => 'success',
    //             'class' => 'btn-brand'
    //         ]);
    //     }
    //     else{
    //         if($verify > 0)
    //         {
    //             return response()->json(
    //                 [
    //                     'title' => 'Warning!',
    //                     'text' => "The measure or pax already exists.",
    //                     'type' => 'error',
    //                     'class' => 'btn-brand'
    //                 ],
    //                 Response::HTTP_CREATED
    //             );
    //         }
    //         $bploBusinessPsic = $this->_BploBusinessPsic->find($request->get('busn_psic_id'));
    //             $details = array(
    //                 'busn_id' => $request->get('busn_id'),
    //                 'busn_psic_id' =>  $request->get('busn_psic_id'),
    //                 'subclass_id' => $bploBusinessPsic->subclass_id,
    //                 'tfoc_id' => $request->get('tfoc_id'),
    //                 'buspx_charge_id' => $request->get('buspx_charge_id'),
    //                 'buspx_capacity' => $request->get('buspx_capacity'),
    //                 'buspx_no_units' => $request->get('buspx_no_units'),
    //                 'created_at' => $timestamp,
    //                 'created_by' => Auth::user()->id
    //             );
    //         $bplo_business = $this->_BploBusiness->add_measure_pax($details);
    //         return response()->json([
    //             'data' => $bplo_business,
    //             'title' => 'Well done!',
    //             'text' => 'Data has been successfully added.',
    //             'type' => 'success',
    //             'class' => 'btn-brand'
    //         ]);
    //     }
            
    // }

    // public function update_measure_pax(Request $request)
    // {
    //     $timestamp = $this->carbon::now();
    //     $id =  $request->get('id');
    //         $details = array(
    //             'buspx_capacity' => 30,
    //             'buspx_no_units' => 30,
    //             'updated_at' => $timestamp,
    //             'updated_by' => Auth::user()->id
    //         );
    //     $bplo_business = $this->_BploBusiness->update_measure_pax($id,$details);
        
    // }
    
    // public function edit_measure_pax(Request $request,$id)
    // {
    //     $this->is_permitted($this->slugs, 'read'); 
    //     return response()->json([
    //         'data' => $this->_BploBusinessMeasurePax->find_measure_pax($id)
    //     ]);
    // }

    // public function edit_busn_plan(Request $request,$id)
    // {
    //     $this->is_permitted($this->slugs, 'read'); 
    //     return response()->json([
    //         'data' => $this->_BploBusinessPsic->find($id)
    //     ]);
    // }

    // public function add_requirment_doc(Request $request)
    // {
    //     $timestamp = $this->carbon::now();
    //     $arrcheckReqexist = $this->_BploBusiness->checkExistpbsireqdoc($request->get('req_code'),$request->get('busn_id'),$request->get('busn_psic_id'));
    //     if(count($arrcheckReqexist) > 0 ){
    //         return response()->json([
    //             'data' => $arrcheckReqexist,
    //             'title' => 'Error!',
    //             'text' => 'This requirement is already exist.',
    //             'type' => 'error',
    //             'class' => 'btn-brand'
    //         ]);
    //     }
    //     $bploBusinessPsic = $this->_BploBusinessPsic->find($request->get('busn_psic_id'));
    //             // Start Uploading Attachement
    //         if($image = $request->file('attachment')) {
    //             $destinationPath =  public_path().'/uploads/bplo_business_req_doc/';
    //             if(!File::exists($destinationPath)) { 
    //                 File::makeDirectory($destinationPath, 0755, true, true);
    //             }
    //             $filename = "attachment_".time().'.'.$image->extension();
    //             $image->move($destinationPath, $filename);
               
    //         }
    //         // End Uploading Attachement
           
    //     $details = array(
    //             'busn_id' => $request->get('busn_id'),
    //             'busn_psic_id' =>  $request->get('busn_psic_id'),
    //             'subclass_id' => $bploBusinessPsic->subclass_id,
    //             'attachment' => $filename,
    //             'br_code' => $request->get('br_code'),
    //             'req_code' => $request->get('req_code'),
    //             'busreq_status' => 1,
    //             'created_at' => $timestamp,
    //             'created_by' => Auth::user()->id
    //         );
    //     $bplo_business = $this->_BploBusiness->add_req_doc($details);
    //     return response()->json([
    //         'data' => $bplo_business,
    //         'title' => 'Well done!',
    //         'text' => 'Data has been successfully added.',
    //         'type' => 'success',
    //         'class' => 'btn-brand'
    //     ]);
    // }

    // public function remove_busn_plan(Request $request, $id)
    // {
    //     $this->is_permitted($this->slugs,'delete');
    //     $findByBusnPlanId=$this->_BploBusinessMeasurePax->findByBusnPlanId($id);
    //     $psic_req=$this->_BploBusinessPsicReq->findByBusnPlanId($id);
    //     if($findByBusnPlanId != null || $psic_req != null )
    //     {
    //         return response()->json(
    //             [
    //                 'title' => 'Warning!',
    //                 'text' => "The line of business can not be remove,It's already assigned to a Ducument Requirment OR Measure or Pax ",
    //                 'type' => 'error',
    //                 'class' => 'btn-brand'
    //             ],
    //             Response::HTTP_CREATED
    //         );
    //     }
    //     $this->_BploBusinessPsic->remove_busn_plan($id);
    //     return response()->json(
    //         [
    //             'title' => 'Well done!',
    //             'text' => 'The line of business has been successfully removed.',
    //             'type' => 'success',
    //             'class' => 'btn-brand'
    //         ],
    //         Response::HTTP_CREATED
    //     );
    // }
    // public function remove_measure(Request $request, $id)
    // {
    //     $this->is_permitted($this->slugs,'delete');
    //     $this->_BploBusinessMeasurePax->remove_measure($id);
    //     return response()->json(
    //         [
    //             'title' => 'Well done!',
    //             'text' => 'The measure has been successfully removed.',
    //             'type' => 'success',
    //             'class' => 'btn-brand'
    //         ],
    //         Response::HTTP_CREATED
    //     );
    // }

    // public function remove_req_doc(Request $request, $id)
    // {
    //     $this->is_permitted($this->slugs,'delete');
    //     $pre_data=$this->_BploBusinessPsicReq->find($id);
    //     $destinationPath = public_path().'/uploads/bplo_business_req_doc/';
    //     $fileToDelete = $destinationPath . $pre_data->attachment;
    //     if (File::exists($fileToDelete)) {
    //         File::delete($fileToDelete);
    //     }
    //     $this->_BploBusinessPsicReq->remove_doc($id);
    //     return response()->json(
    //         [
    //             'title' => 'Well done!',
    //             'text' => 'The Requirment Document has been successfully removed.',
    //             'type' => 'success',
    //             'class' => 'btn-brand'
    //         ],
    //         Response::HTTP_CREATED
    //     );
    // }


    public function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public function reload_busn_plan(Request $request, $busn_id) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->_BploBusiness->reload_busn_plan($busn_id)
        ]);
    }
    public function reload_sub_class(Request $request) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' =>$this->_PsicSubclass->reload_sub_class()
        ]);
    }
    

    public function reload_summary(Request $request, $busn_id) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->_BploBusiness->reload_summary($busn_id)
        ]);
    }


    public function reload_measure_pax(Request $request, $busn_plan_id) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->_BploBusiness->reload_measure_pax($busn_plan_id)
        ]);
    }
    public function reload_client_det(Request $request, $id) //used
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->_BploBusiness->getOwnerNameById($id)
        ]);
    }
    public function reload_rpt_info(Request $request, $id,$year) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_BploBusiness->getRptInfoById($id);
        $rpt_details=$this->_CashierRealProperty->realPropertyPaidTaxesOrNot($id,$year);
        if ($rpt_details->or_no == null) {
            $data->pay_status = "Unpaid"; // Use object property syntax here
        } else {
            $data->pay_status = "Paid"; // Use object property syntax here
        }
        
        return response()->json([
            'data' =>  $data
        ]);
    }
    public function checkMuncByBrgy(Request $request, $id) 
    {   
        return response()->json([
            'data' => $this->_commonmodel->checkMuncByBrgy($id)
        ]);
    }
    
    public function reload_requirments(Request $request, $busn_plan_id) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->_BploBusiness->reload_requirments($busn_plan_id)
        ]);
    }
    public function load_floor_val(Request $request, $rp_code) ///
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->_BploBusiness->load_floor_val($rp_code)
        ]);
    }
    

    public function reload_address($id) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->_BploBusiness->reload_address($id)
        ]);
    }

    public function reload_barangay() 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->_BploBusiness->reload_barangay()
        ]);
    }
    public function refresh_client() 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->_BploBusiness->refresh_client()
        ]);
    }
    public function print_summary(Request $request,$busn_id)
    {
        $bplo_business=$this->_BploBusiness->reload_summary($busn_id);
        $bplo_business_plan=$this->_BploBusiness->reload_busn_plan($busn_id);
        $total_capitalisation=$this->_BploBusiness->busn_plan_sum($busn_id);

        $data = [
                    'bplo_business' => $bplo_business, 
                    'bplo_business_plan' => $bplo_business_plan,
                    'total_capitalisation' => $total_capitalisation
                ];


        // Setup a filename 
        $documentFileName = $busn_id."-summary.pdf";
 
        // Create the mPDF document
        $document = new PDF( [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => '3',
            'margin_top' => '8',
            'margin_bottom' => '8',
            'margin_footer' => '2',
        ]);     
 
        // Set some header informations for output
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
        ];	
        $html = view('OnlineBploBusiness.print_new', $data)->render();
        $document->WriteHTML($html);
         
        // Save PDF on your public storage 
        Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "S"));
        $folder =  public_path().'/uploads/summary/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder,  0777, true, true);
        }
        $filename = public_path() . "/uploads/summary/" . $documentFileName;
        $document->Output($filename, "F");
        // Get file back from storage with the give header informations
        return Storage::disk('public')->download($documentFileName, 'Request', $header); //
  

    }
    public function unclockBusiness(Request $request){
        $busn_id = $request->input('busn_id');
        $ESTATUS=0;
        $message='';
        if($busn_id>0){
            $app_code=1;// By default its should be New
            $isExist = $this->_BploBusiness->checkCurrentYearDetails($busn_id,$app_code,date("Y"));
            if(!$isExist){
                $arr['is_final_assessment']=0;
                $arr['busn_app_status']=0;
                $arr['busn_dept_involved']=0;
                $arr['busn_dept_completed']=0;
                $arr['app_code']=$app_code;
                $arr['pm_id']=1; // Anually;
                $this->_BploBusiness->updateData($busn_id, $arr);
            }else{
                $ESTATUS=1;
                $message='Application is not possible this time. Please come by Next Year.';
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        echo json_encode($arr);exit;

    }
    // This function for Testing purpose don't remove
    public function updateBusinessDateForTest(Request $request){
        $busn_id = $request->input('busn_id');
        $date = $request->input('test_tax_date');
        $test_pm_id= $request->input('test_pm_id');
        $test_app_code= $request->input('test_app_code');
        $arr = array();
        $arr['busn_tax_year'] = date("Y",strtotime($date));
        $arr['busn_tax_month'] = date("m",strtotime($date));
        if($test_pm_id>0){
            $arr['pm_id'] = $test_pm_id;
        }
        if($test_app_code>0){
            $arr['app_code'] = $test_app_code;
        }

        $arr['application_date'] = date("Y-m-d",strtotime($date));
        $arr['created_at'] = date("Y-m-d H:i:s",strtotime($date));
        $arr['updated_at'] = date("Y-m-d H:i:s",strtotime($date));
        
        $this->_BploBusiness->updateData($busn_id, $arr);
    }
}
