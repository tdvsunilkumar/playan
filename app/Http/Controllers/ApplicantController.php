<?php

namespace App\Http\Controllers;
use App\Models\BploBusiness;
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
use App\Models\RptPropertyOwner;
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
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Import;
use Maatwebsite\Excel\Concerns\FromCollection;


class ApplicantController extends Controller
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
    public function __construct(Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->_hoappsanitary = new HoApplicationSanitary();
        $this->_barangay = new Barangay(); 
        $this->_BploBusiness = new BploBusiness(); 
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
        $this->slugs = 'business-permit/application';
    }    

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $bsn_type=$this->_BploBusiness->getBsnType();
	    $bsn_activity=$this->_BploBusiness->getBsnActivity();
        // $client=$this->_BploBusiness->getOwnerName();

        //$barangay=$this->_BploBusiness->getBarangay();
        $barangay=array(""=>"Please select");
        $client=array(""=>"Please select");
        $psicSubclass=array(""=>"Please select");
        $floor_val=array();
        $app_type=$this->_BploApplicationType->allAppType();
        $pay_mode=$this->_CtoPaymentMode->allPayMode();
        $locality=$this->_BploBusiness->allLocality();
        // $rpt_property=$this->_RptProperty->allRptProperty();
        $rpt_property=array(""=>"Please select");
        $bplo_busn = ['' => 'Select a Business Plan'];
        $measure_pax = ['' => 'Select Measure or Pax'];
        $reqName = ['' => 'Select Requirment'];
        $serch_status =config('constants.arrBusinessApplicationStatus');
        $busn_tax_year=date('Y');
        $pm_id=1;
        $app_code=1;
        $to_date=Carbon::now()->format('Y-m-d');
        $from_date=Carbon::now()->format('Y-m-d');
        $status= [
            "9"=>"All",
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
        return view('BploBusiness.index')->with(compact('bsn_type','status','floor_val','to_date','from_date','rpt_property','locality','busn_tax_year','pm_id','app_code','app_type','pay_mode','reqName','bplo_busn','measure_pax','bsn_activity','client','barangay','psicSubclass','serch_status'));
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
           $isRetire = $this->_BploBusiness->checkRetireExist($row->id,$row->app_code);
           $disableBtn = ($row->app_type!='Retire' && $isRetire)?'disabled-status':'';

           if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                if($row->app_type=='Retire'){
                    $actions .= '<div class="action-btn bg-danger ms-2 '.$disableBtn.'">
                       <a href="javascript:;" class="mx-3 btn btn-sm  align-items-center jqUnlock" data-id='.$row->id.' title="Edit">
                            <i class="ti-unlock text-white"></i>
                        </a>
                   </div>';
                }
               $actions .= '<div class="action-btn bg-warning ms-2 '.$disableBtn.'">
                   <a href="javascript:;" class="mx-3 btn btn-sm  align-items-center edit-btn" data-id='.$row->id.' title="Edit">
                        <i class="ti-pencil text-white"></i>
                    </a>
               </div>';
           }
           if ($this->is_permitted($this->slugs, 'update', 1) > 0 && $row->busn_app_status >= 1) {

                $actions .= '<div class="action-btn bg-info ms-2 '.$disableBtn.'">
                   <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/business-permit/application/viewapp?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Verify Application"  data-title="Manage Applications" >
                       <i class="ti-eye text-white"></i>
                   </a>
               </div>';
            }

            //  if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            //     $actions .= '<div class="action-btn bg-info ms-2">
            //                 <a data-size="lg" data-bs-toggle="tooltip" title="status" class="mx-3 btn btn-sm  align-items-center"  id="change-status-btn"  data-id='.$row->id.' title="Edit"  data-title="Manage Formula">
            //                     <i class="ti-eye text-white"></i>
            //                 </a>
            //             </div>';
            // }
            if ( $row->busn_tax_year < date('Y') && $row->busns_id_no != null ) {
                $actions .=($row->app_code != 3) ? '<div class="action-btn bg-secondary ms-2 '.$disableBtn.'"><a href="#" class="mx-3 btn btn-sm newRenew ti-share-alt text-white" name="stp_print" value="2" title="Renew" id='.$row->id.'></a></div>' : 
                                                   '';  
            };
           if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
               $actions .=($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2 '.$disableBtn.'"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" title="Remove" id='.$row->id.'></a>' : 
                   '<div class="action-btn bg-info ms-2 '.$disableBtn.'"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" title="Restore" id='.$row->id.'></a>';  
           };
     
           $serial_no = $sr_no;
           $owner = $row->full_name;
           $application_date = Carbon::parse($row->application_date)->format('d-M-Y');
           $last_pay_date = !empty($row->last_pay_date) ? Carbon::parse($row->last_pay_date)->format('d-M-Y') : "";
           $barangay=$row->office_brgy_name != NULL ? $row->office_brgy_name . ', ' . $row->office_mun_desc . ', ' . $row->office_prov_desc . ', ' . $row->office_reg_region : "";
           $barangay_new = wordwrap($barangay, 40, "<br />\n");
           $app_status = $this->status[$row->busn_app_status];
           $latest_history=$this->_BploBusiness->BploBusnLatestHistoryByBusnId($row->id,$row->busn_tax_year);
            if(isset($latest_history)){
                $startCarbon = Carbon::parse($latest_history->application_date);
                $bplo_issurance=$this->_BploBusiness->checkBploIsuuance($row->id,$row->app_code,$row->busn_tax_year);
                if(isset($bplo_issurance)){
                            $endCarbon = Carbon::parse($bplo_issurance->bpi_issued_date);
                            $diff = $startCarbon->diff($endCarbon);
                            if ($diff->days == 0) {
                                $duration = "";
                            } 
                            elseif($diff->days == 1) {
                                $duration = $diff->days . " Day";
                            } else {
                                $duration = $diff->days . " Days";
                            }
                    }
                    else{
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
                    }
            }else{
                $duration="";
            }

           
            // dd($duration);
           if($row->app_type=='Retire'){
                $serial_no='<strike style="color:red;">'.$serial_no.' </strike>';
                $row->busns_id_no = '<strike style="color:red;">'.$row->busns_id_no.' </strike>';
                $owner = '<strike style="color:red;">'.$owner.' </strike>';
                $row->busn_name = '<strike style="color:red;">'.$row->busn_name.' </strike>';
                $row->app_type = '<strike style="color:red;">'.$row->app_type.' </strike>';
                $application_date = '<strike style="color:red;">'.$application_date.' </strike>';
                $last_pay_date = '<strike style="color:red;">'.$last_pay_date.' </strike>';
                $app_status = '<strike style="color:red;">'.$app_status.' </strike>';
                $row->pm_desc = '<strike style="color:red;">'.$row->pm_desc.' </strike>';
                $row->office_brgy_name = '<strike style="color:red;">'.$row->office_brgy_name.' </strike>';
                $barangay_new = '<strike style="color:red;">'.$barangay_new.' </strike>';
                $row->busn_app_method= '<strike style="color:red;">'.$row->busn_app_method.' </strike>';
                $duration='<strike style="color:red;">'.$duration.' </strike>';
           }
           $arr[$i]['srno']=$serial_no;
           $arr[$i]['busn_id_no']=$row->busns_id_no;
           $arr[$i]['owner']=$owner;
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

    public function busn_psic_lists(Request $request,$busn_id)
    {  
        $result = $this->_BploBusinessPsic->getList($request,$busn_id);
        $result1=(object)  $result;
        if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
            $action =  '<a href="javascript:;" class="action-btn delete-btn-busn-plan bg-danger btn ms-05 btn-sm align-items-center " title="Delete">
                                <i class="ti-trash"></i>
                        </a>
                        <a href="#" id="edit_busn_plan" class="btn btn-sm btn-warning action-item edit_busn_plan " role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti-pencil"></i>
                        </a>';
        }

        $res = $result1->data->map(function($subClass) use ($action) {
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

    public function requirment_doc_list(Request $request,$busn_id)
    {  
        $result = $this->_BploBusinessPsicReq->getList($request,$busn_id);
        $result1=(object)  $result;
        $res = $result1->data->map(function($docList) {
            if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
                $action =  '<a href="javascript:;" class="action-btn delete-btn-req-doc bg-danger btn ms-05 btn-sm align-items-center " title="Delete">
                                    <i class="ti-trash"></i>
                            </a>
                            <a href="'.asset("uploads/bplo_business_req_doc/" . $docList->attachment).'" class="action-btn bg-warning btn ms-05 btn-sm align-items-center" target="_blank" title="Download">
                                    <i class="ti-download"></i>
                            </a>';                  
            }
            $wrap_desc = wordwrap($docList->subclass_description, 100, "<br />\n");
            $desc="<div class='showLess'>".$wrap_desc."</div>";
            return [
                'id' => $docList->ID,
                'line_business' => $desc,
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

    public function busn_measure_lists(Request $request,$busn_id)
    {  
        $result = $this->_BploBusinessMeasurePax->getList($request,$busn_id);
        $result1=(object)  $result;
        $res = $result1->data->map(function($measurePax) {
            $wrap_desc = wordwrap($measurePax->subclass_description, 100, "<br />\n");
            $desc="<div class='showLess'>".$wrap_desc."</div>";
            $wrap_charge_desc = wordwrap($measurePax->charge_desc, 100, "<br />\n");
            $charge_desc="<div class='showLess'>".$wrap_charge_desc."</div>";
            if ($this->is_permitted($this->slugs, 'disapprove', 1) > 0) {
                $action =  '<a href="javascript:;" class="action-btn delete-btn-measure bg-danger btn ms-05 btn-sm align-items-center " title="Delete">
                                    <i class="ti-trash"></i>
                            </a>
                            <a href="#" id="edit_measure" class="btn btn-sm btn-warning action-item edit_measure " role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti-pencil"></i>
                        </a>';
            }
            return [
                'id' => $measurePax->ID,
                'subclass_description' => $desc,
                'buspx_no_units' =>  $measurePax->buspx_no_units,
                'buspx_capacity' =>   $measurePax->buspx_capacity,
                'charge_desc' =>  $charge_desc,
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

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        $isTest=$this->_commonmodel->getConfiguration('PREV_APP_DATE');
        $data = $this->_BploBusiness->find($id);
        if(!empty($data->busn_office_main_barangay_id))
        {
            foreach ($this->_commonmodel->getBarangay($data->busn_office_main_barangay_id)['data'] as $val) {
                $data->busn_office_main_barangay_id = "<option value='".$val->id."' selected>".$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region."</option>";
            }
        }
        if(!empty($data->busn_office_barangay_id))
        {
            foreach ($this->_commonmodel->getBarangay($data->busn_office_barangay_id)['data'] as $val) {
                $data->busn_office_barangay_id = "<option value='".$val->id."' selected>".$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region."</option>";
            }
        }    
        if(!empty($data->client_id))
        {
            foreach ($this->_commonmodel->getBploTaxpayersAutoSearchList($data->client_id)['data'] as $val) {
                $data->client_id = "<option value='".$val->id."' selected>".$val->full_name."</option>";
            }
        }   
        if(!empty($data->rp_code))
        {
            
            foreach ($this->_commonmodel->getBploRpt($data->rp_code)['data'] as $val) {
                $data->rp_code = "<option value='".$val->id."' selected>".$val->rp_tax_declaration_no."</option>";
            }
        } 
        return response()->json([
            'data' => $data,
            'floor_val' => json_decode($data->floor_val_id),
            'isTest' => (int)$isTest
        ]);
    }

    public function store(Request $request)
    {
        $this->is_permitted($this->slugs, 'create');
        if($request->get('busn_status') == 1)
        {
                return response()->json([
                    'data' => 'error',
                    'title' => 'Error!',
                    'text' => 'At least one business plan is needed.',
                    'type' => 'error',
                    'class' => 'btn-brand'
                ]);
        }
        if($request->get('busn_tax_year') != null)
        {
            $currentYear =  $request->get('busn_tax_year');
            $currentMonth = Carbon::now()->month;
            $this->is_permitted($this->slugs, 'create');
            $lastRecord = BploBusiness::latest()->first();
            $lastValue = $lastRecord ? $lastRecord->id : null;
            $lastNumber = intval(substr($lastValue, -6));
            $lastMonth = intval(substr($lastValue, 5, 2));
            $lastYear = intval(substr($lastValue, 0, 4));
            $newNumber = $lastNumber + 1;
            $busn_series_no=sprintf('%06d', $newNumber);
            $newValue = sprintf('%04d-%02d-%06d', $currentYear, $currentMonth, $newNumber);
        }
        else{
            $busn_series_no=NULL;
            $newValue=NULL;
        }
        $timestamp = $this->carbon::now();
        $floors=$request->get('floor_val_id');
        if(!empty($floors)){
            $floors_json=json_encode($floors);
        }else{
            $floors_json="";
        }
        $details = array(
            'busn_tax_month' => $this->carbon::now()->month,
            'busn_series_no' => $busn_series_no,
            'busn_tracking_no' => $newValue,
            'locality_id' => $request->get('locality_id'),
            'loc_local_id' => $request->get('loc_local_id'),
            'app_code' => $request->get('app_code'),
            'pm_id' => $request->get('pm_id'),
            'busn_tax_year' => $request->get('busn_tax_year'),
            'busn_name' => $request->get('busn_name'),
            'btype_id' => $request->get('btype_id'),
            'busn_trade_name' => $request->get('busn_trade_name'),
            'busn_registration_no' => $request->get('busn_registration_no'),
            'busn_registration_date' => $request->get('busn_registration_date'),
            'busn_tin_no' => $request->get('busn_tin_no'),
            'busn_office_main_barangay_id' => $request->get('busn_office_main_barangay_id'),
            'busn_office_main_building_no' => $request->get('busn_office_main_building_no'),
            'busn_office_main_building_name' => $request->get('busn_office_main_building_name'),
            'busn_office_main_add_block_no' => $request->get('busn_office_main_add_block_no'),
            'busn_office_main_add_lot_no' => $request->get('busn_office_main_add_lot_no'),
            'busn_office_main_add_street_name' => $request->get('busn_office_main_add_street_name'),
            'busn_office_main_add_subdivision' => $request->get('busn_office_main_add_subdivision'),
            'client_id' => $request->get('client_id'),

            'busloc_id' => $request->get('busloc_id'),
            'busn_bldg_area' => $request->get('busn_bldg_area'),
            'busn_bldg_total_floor_area' => $request->get('busn_bldg_total_floor_area'),
            'busn_employee_no_female' => $request->get('busn_employee_no_female'),
            'busn_employee_no_male' => $request->get('busn_employee_no_male'),
            'busn_employee_total_no' => $request->get('busn_employee_no_female') + $request->get('busn_employee_no_male'),
            'busn_employee_no_lgu' => $request->get('busn_employee_no_lgu'),
            'busn_vehicle_no_van_truck' => $request->get('busn_vehicle_no_van_truck'),
            'busn_vehicle_no_motorcycle' => $request->get('busn_vehicle_no_motorcycle'),
            'busn_bldg_is_owned' => $request->input('radio'),
            'busn_bldg_tax_declaration_no' => $request->get('busn_bldg_tax_declaration_no'),
            'rp_property_code' => $request->get('rp_property_code'),
            'rp_code' => $request->get('rp_code'),

            'busn_bldg_property_index_no' => $request->get('busn_bldg_property_index_no'),
            'floor_val_id' => $floors_json,
            'busn_tax_incentive_enjoy' => $request->input('en_radio'),
            'busn_office_is_same_as_main' => $request->get('is_check'),
            // 'busn_office_is_same_as_main' => $request->get('busn_office_is_same_as_main'),
            'busn_office_building_no' => $request->get('busn_office_building_no'),
            'busn_office_building_name' => $request->get('busn_office_building_name'),
            'busn_office_add_block_no' => $request->get('busn_office_add_block_no'),
            'busn_office_add_lot_no' => $request->get('busn_office_add_lot_no'),
            'busn_office_add_street_name' => $request->get('busn_office_add_street_name'),
            'busn_office_add_subdivision' => $request->get('busn_office_add_subdivision'),
            'busn_office_barangay_id' => $request->get('busn_office_barangay_id'),
            'busn_app_status' => 0,
            'application_date' => $timestamp,
            'is_synced' => 0,
            'created_at' => $timestamp,
            'created_by' => Auth::user()->id
        );
        if($request->get('btype_id') != null)
        {
            $busn_type_det=BploBusinessType::getEditDetails($request->get('btype_id'));
            $details['is_individual'] = $busn_type_det->is_individual;
        }
        $bplo_business = $this->_BploBusiness->create($details);

        return response()->json(
            [
                'data' => $bplo_business,
                'title' => 'Well done!',
                'text' => 'Data has been successfully added.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function update(Request $request, $id)
    {
        //dd($request->input());
        $this->is_permitted($this->slugs, 'update');
        $timestamp = $this->carbon::now();
        $floors=$request->get('floor_val_id');
        if(!empty($floors)){
            $floors_json=json_encode($floors);
        }else{
            $floors_json="";
        }
        $bplo_busn = $this->_BploBusiness->find($id);
        if($bplo_busn->busn_app_status > 1){
            $status=$bplo_busn->busn_app_status;
        }else{
            $status=$request->get('busn_status');
        }
        if($request->get('busn_status') == 1)
        {
            $msz="Data has been successfully Submitted.";
            $busn_plan=DB::table('bplo_business_psic')
                        ->where('busn_id',$id)
                        ->first();
            if($busn_plan == NULL){
                return response()->json([
                    'data' => $bplo_busn,
                    'title' => 'Error!',
                    'text' => 'At least one business plan is needed.',
                    'type' => 'error',
                    'class' => 'btn-brand'
                ]);
            }
            $reqDoc = $this->_BploBusiness->checkAllDocument($id);
            // dd($bplo_busn);
            // $bplo_business_psic_req=DB::table('bplo_business_psic_req')
            //             ->where('busn_id',$id)
            //             ->first();
                if($reqDoc != NULL){
                    $convertedString = implode(', ', $reqDoc);
                    $alertMessage = "The following Line of Business has lacking documents: ". $convertedString;         
                    return response()->json([
                        'data' => $bplo_busn,
                        'title' => 'Error!',
                        'text' => $alertMessage,
                        'type' => 'error',
                        'class' => 'btn-brand'
                    ]);
                }
        }
        else{
            $msz="Data has been successfully Updated.";
        }
        $details = array(
            'pm_id' => $request->get('pm_id'),
            'busn_tax_year' => $request->get('busn_tax_year'),
            'busn_app_status' => $status,
            'updated_at' => $timestamp,
            'updated_by' => Auth::user()->id,
            'is_synced' => 0,
        );
        
        if ($request->has('app_code') && $request->get('app_code') !== null) {
            $details['app_code'] = $request->get('app_code');
            $app_code=$details['app_code'];
        }else{
            $app_code=$bplo_busn->app_code;
        }
         if($request->get('busn_name') != NULL)
         {
            if($request->get('btype_id') != null)
            {
                $busn_type_det=BploBusinessType::getEditDetails($request->get('btype_id'));
                $is_individual = $busn_type_det->is_individual;
            }
            $newDetails = array(
                'busn_name' => $request->get('busn_name'),
                'btype_id' => $request->get('btype_id'),
                'is_individual' => $is_individual,
                'busn_trade_name' => $request->get('busn_trade_name'),
                'busn_registration_no' => $request->get('busn_registration_no'),
                'busn_registration_date' => $request->get('busn_registration_date'),
                'busn_tin_no' => $request->get('busn_tin_no'),
                'busn_office_main_barangay_id' => $request->get('busn_office_main_barangay_id'),
                'busn_office_main_building_no' => $request->get('busn_office_main_building_no'),
                'busn_office_main_building_name' => $request->get('busn_office_main_building_name'),
                'busn_office_main_add_block_no' => $request->get('busn_office_main_add_block_no'),
                'busn_office_main_add_lot_no' => $request->get('busn_office_main_add_lot_no'),
                'busn_office_main_add_street_name' => $request->get('busn_office_main_add_street_name'),
                'busn_office_main_add_subdivision' => $request->get('busn_office_main_add_subdivision'),
                'client_id' => $request->get('client_id'),
            );
            $details = array_merge($details, $newDetails);
         }  
         if($request->get('busloc_id') != NULL) 
         {
            $newDetails2=array(
            'busloc_id' => $request->get('busloc_id'),
            'busn_bldg_area' => $request->get('busn_bldg_area'),
            'busn_bldg_total_floor_area' => $request->get('busn_bldg_total_floor_area'),
            'busn_employee_no_female' => $request->get('busn_employee_no_female'),
            'busn_employee_no_male' => $request->get('busn_employee_no_male'),
            'busn_employee_total_no' => $request->get('busn_employee_no_female') + $request->get('busn_employee_no_male'),
            'busn_employee_no_lgu' => $request->get('busn_employee_no_lgu'),
            'busn_vehicle_no_van_truck' => $request->get('busn_vehicle_no_van_truck'),
            'busn_vehicle_no_motorcycle' => $request->get('busn_vehicle_no_motorcycle'),
            'busn_bldg_is_owned' => $request->input('radio'),
            'busn_bldg_tax_declaration_no' => $request->get('busn_bldg_tax_declaration_no'),
            'rp_property_code' => $request->get('rp_property_code'),
            'rp_code' => $request->get('rp_code'),
            'busn_bldg_property_index_no' => $request->get('busn_bldg_property_index_no'),
            'floor_val_id' => $floors_json,
            'busn_tax_incentive_enjoy' => $request->get('en_radio'),
            // 'busn_office_is_same_as_main' => $request->get('busn_office_is_same_as_main'),
            'busn_office_is_same_as_main' => $request->get('is_check'),
            'busn_office_building_no' => $request->get('busn_office_building_no'),
            'busn_office_building_name' => $request->get('busn_office_building_name'),
            'busn_office_add_block_no' => $request->get('busn_office_add_block_no'),
            'busn_office_add_lot_no' => $request->get('busn_office_add_lot_no'),
            'busn_office_add_street_name' => $request->get('busn_office_add_street_name'),
            'busn_office_add_subdivision' => $request->get('busn_office_add_subdivision'),
            'busn_office_barangay_id' => $request->get('busn_office_barangay_id'),
            );
            $details = array_merge($details, $newDetails2);
         }
       
        $u_bplo_busn= $this->_BploBusiness->updateData($id, $details);
        $bplo_busn_history = $this->_BploBusiness->BploBusinessHistoryByBusnId($id,$request->get('busn_tax_year'));   
        if($request->get('busn_status') == 1){
            $l_bplo_business = $this->_BploBusiness->findForUpdateHistory($id);
            foreach($l_bplo_business as $key=>$val){
                $historyData[$key] = $l_bplo_business->$key;
            }
            if(count($bplo_busn_history) == 0){
                $newHistory=array(
                    'busn_id' => $id,
                );
                $historyData = array_merge($historyData, $newHistory);                
                $this->_BploBusiness->addBploBusinessHistory($historyData);
            }  
            else{
                $bplo_business = $this->_BploBusiness->updateBploHistoryData($bplo_busn_history[0]->id, $historyData);               
            }  

            $bplo_business_psic = $this->_BploBusiness->reload_busn_psic_by_Busn_id($id);   
            $ext_busn_psic_id=array();
                foreach($bplo_business_psic as $key=>$item)
                {
                    $psicRowAttributes = get_object_vars($item);
                    unset($psicRowAttributes['id']);
                    $psicRowAttributes['busn_psic_id'] = $item->id;
                    $psicRowAttributes['busp_tax_year']=$request->get('busn_tax_year');
                    $psicRowAttributes['app_code']=$app_code;
                    $bplo_busn_psic_history = $this->_BploBusiness->BploBusnPsicHistoryByPsic($item->id,$request->get('busn_tax_year'));   
                    if(empty($bplo_busn_psic_history)){
                        DB::table('bplo_business_psic_history')->insert($psicRowAttributes);
                        $l_busn_psic_id=DB::getPdo()->lastInsertId();
                    }else{
                        DB::table('bplo_business_psic_history')->where('id',$bplo_busn_psic_history->id)->update($psicRowAttributes);
                        $l_busn_psic_id=$bplo_busn_psic_history->id;
                    }  
                $ext_busn_psic_id[$key]=$l_busn_psic_id;
                }
            DB::table('bplo_business_psic_history')->where('busn_id',$id)->where('app_code',$app_code)->where('busp_tax_year',$request->get('busn_tax_year'))->whereNotIn('id',$ext_busn_psic_id)->delete();    
            $bplo_business_psic_req = $this->_BploBusiness->reload_busn_req_doc($id);   
            $ext_busn_psic_req_id=array();
                    foreach($bplo_business_psic_req as $key=>$item)
                    {
                        $psicReqRowAttributes = get_object_vars($item);
                        unset($psicReqRowAttributes['id']);
                        unset($psicReqRowAttributes['app_type_id']);
                        $psicReqRowAttributes['busn_psic_req_id'] = $item->id;
                        $psicReqRowAttributes['busreq_year']=$request->get('busn_tax_year');
                        $psicReqRowAttributes['app_type_id']=$app_code;
                        $bplo_busn_psic_req_history = $this->_BploBusiness->BploBusnPsicReqHistoryByPsicReq($item->id,$request->get('busn_tax_year'));   
                        if(empty($bplo_busn_psic_req_history)){
                            DB::table('bplo_business_psic_req_history')->insert($psicReqRowAttributes);
                            $l_busn_psic_req_id=DB::getPdo()->lastInsertId();
                        }else{
                            DB::table('bplo_business_psic_req_history')->where('id',$bplo_busn_psic_req_history->id)->update($psicReqRowAttributes);
                            $l_busn_psic_req_id=$bplo_busn_psic_req_history->id;
                        }  
                    $ext_busn_psic_req_id[$key]=$l_busn_psic_req_id;
                    }       
            DB::table('bplo_business_psic_req_history')->where('busn_id',$id)->where('app_type_id',$app_code)->where('busreq_year',$request->get('busn_tax_year'))->whereNotIn('id',$ext_busn_psic_req_id)->delete();
            
            $bplo_business_measure_pax = $this->_BploBusiness->reload_busn_measure_pax($id);   
            $ext_busn_measure_pax_id=array();
                    foreach($bplo_business_measure_pax as $key=>$item)
                    {
                        $measurePaxRowAttributes = get_object_vars($item);
                        unset($measurePaxRowAttributes['id']);
                        $measurePaxRowAttributes['buspx_id'] = $item->id;
                        $measurePaxRowAttributes['buspx_year']=$request->get('busn_tax_year');
                        $measurePaxRowAttributes['app_code']=$app_code;
                        $bplo_busn_measure_pax_history = $this->_BploBusiness->BploBusnMeasurePaxHistoryByBuspx($item->id,$request->get('busn_tax_year'));   
                        if(empty($bplo_busn_measure_pax_history)){
                            DB::table('bplo_business_measure_pax_history')->insert($measurePaxRowAttributes);
                            $l_busn_measure_pax_id=DB::getPdo()->lastInsertId();
                        }else{
                            DB::table('bplo_business_measure_pax_history')->where('id',$bplo_busn_measure_pax_history->id)->update($measurePaxRowAttributes);
                            $l_busn_measure_pax_id=$bplo_busn_measure_pax_history->id;
                        }  
                    $ext_busn_measure_pax_id[$key]=$l_busn_measure_pax_id;
                    }       
            DB::table('bplo_business_measure_pax_history')->where('busn_id',$id)->where('app_code',$app_code)->where('buspx_year',$request->get('busn_tax_year'))->whereNotIn('id',$ext_busn_measure_pax_id)->delete();
        }

        if($bplo_busn->busn_app_status == 5 || $bplo_busn->busn_app_status == 6 && $bplo_busn->app_code != $request->get('app_code'))
        {
            if($request->get('app_code') == 2){
                $this->updateNew($id);
            }
        }



        return response()->json([
            'data' => $u_bplo_busn,
            'title' => 'Well done!',
            'text' => $msz,
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }
    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_BploBusiness->updateActiveInactive($id,$data);
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Bplo Formula ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
    public function updateNew($id){
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentDate = Carbon::now()->format('Y-m-d');
        DB::beginTransaction();
        try {
                // Update the current row
                DB::table('bplo_business')
                    ->where('id',$id)
                    ->update([
                        'busn_tax_year' => $currentYear,
                        'busn_tax_month' => $currentMonth,
                        'busn_app_status' => 0,
                        'busn_dept_involved' => 0,
                        'busn_dept_completed' => 0,
                        'is_final_assessment' => 0,
                        'app_code' => 2,
                        'application_date' => $currentDate
                    ]);
            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Handle the exception
        }
        // Log Details Start
    }
    public function NewRenew(Request $request){
        $this->is_permitted($this->slugs, 'update');
        $id = $request->input('id');
        $is_newRenew = $request->input('is_newRenew');
        $this->updateNew($id);
        $action = $is_newRenew==1?'Renew':'New';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' updated Bplo appllication type ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
    
    public function add_business_plan(Request $request)
    {
        $timestamp = $this->carbon::now();
        $verify=$this->_BploBusinessPsic->verifyUnique($request->get('subclass_id'),$request->get('busn_id'));
        if($request->get('plan_id') != 0)
        {
            $pre_bplo_business = $this->_BploBusinessPsic->find($request->get('plan_id'));
            if($pre_bplo_business->subclass_id != $request->get('subclass_id') && $verify > 0)
            {
                return response()->json(
                    [
                        'title' => 'Warning!',
                        'text' => "The line of business already exists.",
                        'type' => 'error',
                        'class' => 'btn-brand'
                    ],
                    Response::HTTP_CREATED
                );
            }
                $details = array(
                    'subclass_id' => $request->get('subclass_id'),
                    'busn_id' =>  $request->get('busn_id'),
                    'busp_no_units' => $request->get('busp_no_units'),
                    'busp_capital_investment' => $request->get('busp_capital_investment'),
                    'busp_essential' => $request->get('busp_essential'),
                    'busp_non_essential' => $request->get('busp_non_essential'),
                    'busp_total_gross' => $request->get('busp_non_essential') + $request->get('busp_essential'),
                    'updated_at' => $timestamp,
                    'is_synced' => 0,
                    'updated_by' => Auth::user()->id
                );
            $bplo_business = $this->_BploBusiness->update_bsn_plan($request->get('plan_id'),$details);
            return response()->json([
                'data' => $request->get('plan_id'),
                'title' => 'Well done!',
                'text' => 'Data has been successfully Updated.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        }
        else{
            if($verify > 0)
            {
                return response()->json(
                    [
                        'title' => 'Warning!',
                        'text' => "The line of business already exists.",
                        'type' => 'error',
                        'class' => 'btn-brand'
                    ],
                    Response::HTTP_CREATED
                );
            }
                $details = array(
                    'subclass_id' => $request->get('subclass_id'),
                    'busn_id' =>  $request->get('busn_id'),
                    'busp_no_units' => $request->get('busp_no_units'),
                    'busp_capital_investment' => $request->get('busp_capital_investment'),
                    'busp_essential' => $request->get('busp_essential'),
                    'busp_non_essential' => $request->get('busp_non_essential'),
                    'busp_total_gross' => $request->get('busp_non_essential') + $request->get('busp_essential'),
                    'created_at' => $timestamp,
                    'is_synced' => 0,
                    'created_by' => Auth::user()->id
                );
            $bplo_business = $this->_BploBusiness->add_bsn_plan($details);
            return response()->json([
                'data' => $bplo_business,
                'title' => 'Well done!',
                'text' => 'Data has been successfully added.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        }
            
    }

    public function add_measure_pax(Request $request)
    {
        $timestamp = $this->carbon::now();
        $verify=$this->_BploBusinessMeasurePax->verifyUnique($request->get('busn_id'),$request->get('busn_psic_id'),$request->get('buspx_charge_id'));
        if($request->get('id') != 0)
        {
            $measure_pax=$this->_BploBusinessMeasurePax->find_measure_pax($request->get('id'));
            if($measure_pax->buspx_charge_id != $request->get('buspx_charge_id'))
            {
                if($verify > 0)
                {
                    return response()->json(
                        [
                            'title' => 'Warning!',
                            'text' => "This Measure and Pax already exist.",
                            'type' => 'error',
                            'class' => 'btn-brand'
                        ],
                        Response::HTTP_CREATED
                    );
                } 
            }
            $id =  $request->get('id');
            $details = array(
                'buspx_capacity' => $request->get('buspx_capacity'),
                'buspx_no_units' => $request->get('buspx_no_units'),
                'updated_at' => $timestamp,
                'is_synced' => 0,
                'updated_by' => Auth::user()->id
            );
            $bploBusinessPsic = $this->_BploBusinessPsic->find($request->get('busn_psic_id'));
            $details = array(
                'busn_id' => $request->get('busn_id'),
                'busn_psic_id' =>  $request->get('busn_psic_id'),
                'subclass_id' => $bploBusinessPsic->subclass_id,
                'tfoc_id' => $request->get('tfoc_id'),
                'buspx_charge_id' => $request->get('buspx_charge_id'),
                'buspx_capacity' => $request->get('buspx_capacity'),
                'buspx_no_units' => $request->get('buspx_no_units'),
                'updated_at' => $timestamp,
                'is_synced' => 0,
                'updated_by' => Auth::user()->id
            );
            $bplo_business = $this->_BploBusiness->update_measure_pax($id,$details);
            return response()->json([
                'data' => $bplo_business,
                'title' => 'Well done!',
                'text' => 'Data has been successfully updated.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        }
        else{
            if($verify > 0)
            {
                return response()->json(
                    [
                        'title' => 'Warning!',
                        'text' => "This Measure and Pax already exist.",
                        'type' => 'error',
                        'class' => 'btn-brand'
                    ],
                    Response::HTTP_CREATED
                );
            }
            $bploBusinessPsic = $this->_BploBusinessPsic->find($request->get('busn_psic_id'));
                $details = array(
                    'busn_id' => $request->get('busn_id'),
                    'busn_psic_id' =>  $request->get('busn_psic_id'),
                    'subclass_id' => $bploBusinessPsic->subclass_id,
                    'tfoc_id' => $request->get('tfoc_id'),
                    'buspx_charge_id' => $request->get('buspx_charge_id'),
                    'buspx_capacity' => $request->get('buspx_capacity'),
                    'buspx_no_units' => $request->get('buspx_no_units'),
                    'created_at' => $timestamp,
                    'is_synced' => 0,
                    'created_by' => Auth::user()->id
                );
            $bplo_business = $this->_BploBusiness->add_measure_pax($details);
            return response()->json([
                'data' => $bplo_business,
                'title' => 'Well done!',
                'text' => 'Data has been successfully added.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        }
            
    }

    public function update_measure_pax(Request $request)
    {
        $timestamp = $this->carbon::now();
        $id =  $request->get('id');
            $details = array(
                'buspx_capacity' => 30,
                'buspx_no_units' => 30,
                'updated_at' => $timestamp,
                'is_synced' => 0,
                'updated_by' => Auth::user()->id
            );
        $bplo_business = $this->_BploBusiness->update_measure_pax($id,$details);
        
    }
    
    public function edit_measure_pax(Request $request,$id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->_BploBusinessMeasurePax->find_measure_pax($id)
        ]);
    }

    public function edit_busn_plan(Request $request,$id)
    {
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->_BploBusinessPsic->find($id)
        ]);
    }

    public function add_requirment_doc(Request $request)
    {
        $timestamp = $this->carbon::now();
        $arrcheckReqexist = $this->_BploBusiness->checkExistpbsireqdoc($request->get('req_code'),$request->get('busn_id'),$request->get('busn_psic_id'));
        if(count($arrcheckReqexist) > 0 ){
            return response()->json([
                'data' => $arrcheckReqexist,
                'title' => 'Error!',
                'text' => 'This requirement is already exist.',
                'type' => 'error',
                'class' => 'btn-brand'
            ]);
        }
        $bploBusinessPsic = $this->_BploBusinessPsic->find($request->get('busn_psic_id'));
                // Start Uploading Attachement
            if($image = $request->file('attachment')) {
                $destinationPath =  public_path().'/uploads/bplo_business_req_doc/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
               
            }
            // End Uploading Attachement
           
        $details = array(
                'busn_id' => $request->get('busn_id'),
                'busn_psic_id' =>  $request->get('busn_psic_id'),
                'subclass_id' => $bploBusinessPsic->subclass_id,
                'attachment' => $filename,
                'busreq_year'  => $bploBusinessPsic->busn_tax_year,
                'app_type_id'  => $bploBusinessPsic->app_code,
                'br_code' => $request->get('br_code'),
                'req_code' => $request->get('req_code'),
                'busreq_status' => 1,
                'created_at' => $timestamp,
                'is_synced' => 0,
                'created_by' => Auth::user()->id
            );
        $bplo_business = $this->_BploBusiness->add_req_doc($details);
        return response()->json([
            'data' => $bplo_business,
            'title' => 'Well done!',
            'text' => 'Data has been successfully added.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove_busn_plan(Request $request, $id)
    {
        $this->is_permitted($this->slugs,'delete');
        $findByBusnPlanId=$this->_BploBusinessMeasurePax->findByBusnPlanId($id);
        $psic_req=$this->_BploBusinessPsicReq->findByBusnPlanId($id);
        if($findByBusnPlanId != null || $psic_req != null )
        {
            return response()->json(
                [
                    'title' => 'Warning!',
                    'text' => "The line of business can not be remove,It's already assigned to a Ducument Requirment OR Measure or Pax ",
                    'type' => 'error',
                    'class' => 'btn-brand'
                ],
                Response::HTTP_CREATED
            );
        }
        $this->_BploBusinessPsic->remove_busn_plan($id);
        return response()->json(
            [
                'id' => $id,
                'title' => 'Well done!',
                'text' => 'The line of business has been successfully removed.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }
    public function remove_measure(Request $request, $id)
    {
        $this->is_permitted($this->slugs,'delete');
        $pre_data=$this->_BploBusinessMeasurePax->find_measure_pax($id);
        $this->_BploBusinessMeasurePax->remove_measure($id);
        return response()->json(
            [
                'data' => $pre_data,
                'title' => 'Well done!',
                'text' => 'The measure has been successfully removed.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function remove_req_doc(Request $request, $id)
    {
        $this->is_permitted($this->slugs,'delete');
        $pre_data=$this->_BploBusinessPsicReq->find($id);
        $destinationPath = public_path().'/uploads/bplo_business_req_doc/';
        $fileToDelete = $destinationPath . $pre_data->attachment;
        if (File::exists($fileToDelete)) {
            File::delete($fileToDelete);
        }
        $this->_BploBusinessPsicReq->remove_doc($id);
        return response()->json(
            [
                'data' => $pre_data,
                'title' => 'Well done!',
                'text' => 'The Requirement Document has been successfully removed.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }


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
    public function reload_client_det(Request $request, $id) 
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
        $rpt=$this->_RptProperty->find($id);
        if ($rpt_details->or_no == null) {
            $data->pay_status = "Unpaid"; // Use object property syntax here
        } else {
            $data->pay_status = "Paid"; // Use object property syntax here
        }
        $data->building_name=$rpt->rp_building_name;
        $data->building_own_name=$data->propertyOwner->full_name;
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
        $bplo_business_plan=$this->_BploBusinessPsic->reload_busn_plan($busn_id);
        $total_capitalisation=$this->_BploBusinessPsic->busn_plan_sum($busn_id);

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
        $html = view('BploBusiness.print_new', $data)->render();
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
    public function sendEmail(Request $request){
        $busn_id=$request->input('id');
        $BploBusiness ="";
        $BploBusiness = BploBusiness::where('id',$busn_id)->first(); 
        if($BploBusiness){
           $propertyOwner = RptPropertyOwner::where('id',$BploBusiness->client_id)->first(); 
        // dd($propertyOwner->p_email_address);
        if(isset($propertyOwner)){
            if(!empty($propertyOwner->p_email_address)){
                $data=array();
                $description = 'UNIFIED APPLICATION FORM FOR RENEWAL OF BUSINESS PERMIT.';
                $html = $this->generatePaymentPdf($busn_id);
                $html = str_replace("{DESCRIPTION}",$description, $html);
                $html = str_replace("{USER_EMAIL}",$propertyOwner->p_email_address, $html);

                $data['message'] = $html;
                $data['to_name']=$propertyOwner->rpo_first_name;
                $data['to_email']=$propertyOwner->p_email_address;
                // $data['to_email']='safikalam123@gmail.com';
                $data['subject']='PALAYAN CITY HALL: APPLICATION FOR RENEWAL OF BUSINESS PERMIT';
                try {
                     Mail::send([], ['data' =>$data], function ($m) use ($data) {
                    $m->to($data['to_email'], $data['to_name']);
                    $m->subject($data['subject']);
                    $m->setBody($data['message'], 'text/html');
                });
                 } catch (\Exception $e) {
                    dd($e);
                     
                 } 
            }
        } 
        }
        
    }
     public function generatePaymentPdf($busn_id = '')
    {
        $bplo_business=$this->_BploBusiness->reload_summary($busn_id);
        $bplo_business_plan=$this->_BploBusinessPsic->reload_busn_plan($busn_id);
        $total_capitalisation=$this->_BploBusinessPsic->busn_plan_sum($busn_id);

        $data = [
                    'bplo_business' => $bplo_business, 
                    'bplo_business_plan' => $bplo_business_plan,
                    'total_capitalisation' => $total_capitalisation
                ];
            return view('BploBusiness.print_new_mail', compact('data','bplo_business','bplo_business_plan','total_capitalisation'))->render();
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
    //function for giolocation by rp code
    
    public function getLocationsbypropid(Request $request){
        $html ='<input type="hidden" name="property_code" value="'.$request->input('property_code').'">';
            $rpt_properties=$this->_RptProperty->getRpCodebyid($request->input('property_code'));
            $arrLocations = $this->_RptProperty->getPropertydoclinkbyid($rpt_properties->rp_property_code);
           $i=1;
           $newI = 0;
           foreach ($arrLocations as $key => $val) {
               $html.='<div class="removedocumentsdata row pt10">
                               <div class="col-lg-1 col-md-1 col-sm-1">
                                 <div class="form-group"><div class="form-icon-user">
                                   <p class="serialnoclass" style="text-align:left;padding-left:13px;font-size: 23px;">'.$i.'</p>
                                   <input id="fileid" name="geoid[]" type="hidden" value="'.$val->id.'">
                                   </div>
                                 </div>
                                </div>
                               <div class="col-lg-7 col-md-7 col-sm-7">
                                   <div class="form-group">
                                      <input id="linkdesc" class="form-control linkdesc" name="linkdesc[]" type="text" value="'.$val->doc_link.'" readonly>
                                      <span class="validate-err linkdesc" id="linkdesc'.$newI.'"></span>
                                  </div>
                               </div>
                               <div class="col-lg-3 col-md-3 col-sm-3">
                                   <div class="form-group">
                                      <input id="remark" class="form-control" name="remark[]" type="text" value="'.$val->Remark.'" readonly>
                                  </div>
                               </div>
                                                                   <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group">
                                       <a class="btn btn-primary" href="'.$val->doc_link.'" target="_blank" style="height: 37px;"><i class="ti-world"></i></a>

                                      </div>
                                </div>
                               </div>';  $i++;$newI++;
           }
           
                                                                                          

           $arr =array('status'=>'success','msg'=>'data saved successfully','dynadata'=>$html);

           echo json_encode($arr);
   }
    public function bulkUpload(Request $request){
        $this->is_permitted($this->slugs, 'upload');
        $arrType = array("1"=>"Business Permit","2"=>"PSIC Subclass","3"=>"Measure & Pax");
        return view('BploBusiness.bulkUpload',compact('arrType'));
    }

    public function uploadBulkBusinessData(Request $request){
        $upload_type =  $request->input('upload_type');
        if($upload_type==1){
            return $this->uploadBploBusiness($request);
        }else if($upload_type==2){
            return $this->uploadPsicSublcasses($request);
        }else if($upload_type==3){
            return $this->uploadMeasurePax($request);
        }
    }
    public function uploadMeasurePax($request){
        $type = $request->input('type');
        $upload_type =  $request->input('upload_type');
        if($request->hasFile('file')){
            $this->errorImport=array();
            $arrColumn = array('client_name','busn_id','busn_name','subclass_id','subclass_name', 'tfoc_id', 'buspx_charge_id', 'buspx_capacity', 'buspx_no_units');
            $excelData = (new Import())->toArray(request()->file('file'))[0];
            if($type=='validateRecords'){
                $isMissing = $this->checkCoulmnMissing($arrColumn,$excelData[0]);
                if(!$isMissing){
                    $totelRecords = count($excelData) - 1;
                    $error='';
                    for($i = 1; $i <= count($excelData) - 1; $i++){
                        $arrData = $this->assignData($excelData[0],$excelData[$i],$arrColumn);
                        $this->checkValidation($arrData,$i,$upload_type);
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
                    $arrData = $this->assignData($excelData[0],$excelData[$i],$arrColumn);
                    unset($arrData['busn_name']);
                    unset($arrData['client_name']);
                    unset($arrData['subclass_name']);
                    $arrData['updated_by']=\Auth::user()->id;
                    $arrData['updated_at'] = date('Y-m-d H:i:s');
                    $arrData['created_by']=\Auth::user()->id;
                    $arrData['created_at'] = date('Y-m-d H:i:s');
                    $arrData = array_filter($arrData, function ($value) {
                        return !is_null($value) && $value !== '' && $value !== 'NULL';
                    });
                    $this->_BploBusiness->add_measure_pax($arrData);
                }
                $response = ['status' => true, 'message' => 'Uploaded successfully.'];
                return json_encode($response);
            }
        }
    }

    public function uploadPsicSublcasses($request){
        $type = $request->input('type');
        $upload_type =  $request->input('upload_type');
        if($request->hasFile('file')){
            $this->errorImport=array();
            $arrColumn = array('client_name','busn_id','busn_name','subclass_id', 'busp_no_units', 'busp_capital_investment', 'busp_essential', 'busp_non_essential');
            $excelData = (new Import())->toArray(request()->file('file'))[0];
            if($type=='validateRecords'){
                $isMissing = $this->checkCoulmnMissing($arrColumn,$excelData[0]);
                if(!$isMissing){
                    $totelRecords = count($excelData) - 1;
                    $error='';
                    for($i = 1; $i <= count($excelData) - 1; $i++){
                        $arrData = $this->assignData($excelData[0],$excelData[$i],$arrColumn);
                        $this->checkValidation($arrData,$i,$upload_type);
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
                    $arrData = $this->assignData($excelData[0],$excelData[$i],$arrColumn);
                    unset($arrData['busn_name']);
                    unset($arrData['client_name']);

                    $arrData['busp_total_gross']=$arrData['busp_non_essential'] + $arrData['busp_essential'];
                    $arrData['updated_by']=\Auth::user()->id;
                    $arrData['updated_at'] = date('Y-m-d H:i:s');
                    $arrData['created_by']=\Auth::user()->id;
                    $arrData['created_at'] = date('Y-m-d H:i:s');
                    $arrData = array_filter($arrData, function ($value) {
                        return !is_null($value) && $value !== '' && $value !== 'NULL';
                    });
                    $this->_BploBusiness->add_bsn_plan($arrData);
                }
                $response = ['status' => true, 'message' => 'Uploaded successfully.'];
                return json_encode($response);
            }
        }
    }
    public function uploadBploBusiness($request){
        $upload_type =  $request->input('upload_type');
        $type = $request->input('type');
        if($request->hasFile('file')){
            $this->errorImport=array();
            $arrColumn = array( 'client_id','client_name','busn_office_main_barangay_id','busn_office_main_add_street_name','busn_office_main_add_subdivision', 'busn_office_main_add_lot_no', 'busn_tax_year', 'busn_tax_month','application_date', 'app_code', 'pm_id', 'busn_name', 'busn_trade_name', 'btype_id', 'busn_registration_no', 'busn_tin_no','busn_office_main_building_no', 'busn_office_main_building_name', 'busn_office_main_add_block_no', 'busloc_id', 'busn_bldg_area', 'busn_bldg_total_floor_area', 'busn_employee_no_female', 'busn_employee_no_male', 'busn_employee_total_no', 'busn_employee_no_lgu', 'busn_vehicle_no_van_truck', 'busn_vehicle_no_motorcycle', 'busn_bldg_is_owned', 'busn_bldg_tax_declaration_no', 'busn_bldg_property_index_no', 'online_busn_bldg_property_index_no', 'busn_tax_incentive_enjoy', 'busn_office_building_no', 'busn_office_building_name', 'busn_office_add_block_no', 'busn_office_add_lot_no', 'busn_office_add_street_name', 'busn_office_add_subdivision', 'busn_office_barangay_id');
            $excelData = (new Import())->toArray(request()->file('file'))[0];

            if($type=='validateRecords'){
                $isMissing = $this->checkCoulmnMissing($arrColumn,$excelData[0]);
                if(!$isMissing){
                    $totelRecords = count($excelData) - 1;
                    $error='';
                    for($i = 1; $i <= count($excelData) - 1; $i++){
                        $arrData = $this->assignData($excelData[0],$excelData[$i],$arrColumn);
                        $this->checkValidation($arrData,$i,$upload_type);
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
                $locality=$this->_BploBusiness->allLocality();
                $totelRecords = count($excelData) - 1;
                for($i = 1; $i <= count($excelData) - 1; $i++){
                    $arrData = $this->assignData($excelData[0],$excelData[$i],$arrColumn);
                    unset($arrData['id']);
                    unset($arrData['client_name']);
                    $arrData['locality_id']=$locality->id;
                    $arrData['loc_local_id']=$locality->loc_local_code;
                    $currentYear =  $arrData['busn_tax_year'];
                    $currentMonth = $arrData['busn_tax_month'];
                    $lastRecord = BploBusiness::latest()->first();
                    $lastValue = $lastRecord ? $lastRecord->id : null;
                    $lastNumber = intval(substr($lastValue, -6));
                    $lastMonth = intval(substr($lastValue, 5, 2));
                    $lastYear = intval(substr($lastValue, 0, 4));
                    $newNumber = $lastNumber + 1;
                    $busn_series_no=sprintf('%06d', $newNumber);
                    $newValue = sprintf('%04d-%02d-%06d', $currentYear, $currentMonth, $newNumber);
                    $arrData['busn_series_no'] = $busn_series_no;
                    $arrData['busn_tracking_no'] = $newValue;

                    $arrData['updated_by']=\Auth::user()->id;
                    $arrData['updated_at'] = date('Y-m-d H:i:s');
                    $arrData['created_by']=\Auth::user()->id;
                    $arrData['created_at'] = date('Y-m-d H:i:s');
                    $arrData['application_date'] = date('Y-m-d',strtotime($arrData['application_date']));

                    $arrData['busn_app_status'] = 0;
                    if($arrData['btype_id'] != null && $arrData['btype_id']>0){
                        $busn_type_det=BploBusinessType::getEditDetails($arrData['btype_id']);
                        $arrData['is_individual'] = $busn_type_det->is_individual;
                    }
                    $arrData = array_filter($arrData, function ($value) {
                         return !is_null($value) && $value !== '' && $value !== 'NULL';
                    });
                    $bplo_business = $this->_BploBusiness->create($arrData);
                }
                $response = ['status' => true, 'message' => 'Uploaded successfully.'];
                return json_encode($response);
            }
        }
    }
    public function checkValidation($arrData,$cnt,$upload_type){
        $arrData['id']=0;
        $rules = [];
        $customMessages = [];
        if($upload_type==1){
            $rules = [
                 'client_id'=>'required|numeric','client_name'=>'required','busn_office_main_barangay_id'=>'required|numeric','busn_tax_year'=>'required', 'busn_tax_month'=>'required|numeric','app_code'=>'required', 'pm_id'=>'required','busn_name'=>'required|unique:bplo_business,busn_name,' .$arrData['id'].',id,client_id,' .$arrData['client_id'], 'busn_trade_name'=>'required', 'btype_id'=>'required|numeric', 'busn_registration_no'=>'required', 'busn_tin_no'=>'required', 'busloc_id'=>'required|numeric', 'busn_bldg_area'=>'required', 'busn_bldg_total_floor_area'=>'numeric', 'busn_employee_no_female'=>'numeric', 'busn_employee_no_male'=>'numeric', 'busn_employee_total_no'=>'numeric', 'busn_employee_no_lgu'=>'numeric', 'busn_vehicle_no_van_truck'=>'numeric', 'busn_vehicle_no_motorcycle'=>'numeric', 'busn_bldg_is_owned'=>'required', 'busn_bldg_tax_declaration_no'=>'required', 'busn_bldg_property_index_no'=>'required', 'online_busn_bldg_property_index_no'=>'required', 'busn_tax_incentive_enjoy'=>'required|numeric', 'busn_office_building_no'=>'required', 'busn_office_building_name'=>'required', 'busn_office_add_block_no'=>'required', 'busn_office_add_lot_no'=>'required', 'busn_office_add_street_name'=>'required', 'busn_office_add_subdivision'=>'required', 'busn_office_barangay_id'=>'required|numeric','application_date'=>'required'
            ];
        }elseif($upload_type==2){
            $rules = [
                'client_name'=>'required','busn_id'=>'required|numeric','busn_name'=>'required','subclass_id'=>'required|unique:bplo_business_psic,subclass_id,' .$arrData['id'].',id,busn_id,' .$arrData['busn_id'],
                'busp_no_units'=>'required|numeric','busp_capital_investment'=>'required|numeric','busp_essential'=>'required|numeric','busp_non_essential'=>'required|numeric'
            ];
        }elseif($upload_type==3){
            $rules = [
                'client_name'=>'required','busn_id'=>'required|numeric','busn_name'=>'required',
                'subclass_id'=>'required|numeric',
                'buspx_charge_id'=>'required|unique:bplo_business_measure_pax,buspx_charge_id,' .$arrData['id'].',id,busn_id,' .$arrData['busn_id'].',subclass_id,' .$arrData['subclass_id'],
                'tfoc_id'=>'required|numeric','buspx_capacity'=>'required|numeric','buspx_no_units'=>'required|numeric'
            ];
        }

        $validator = \Validator::make($arrData, $rules, $customMessages);
        if($validator->fails()){
            $messages = $validator->getMessageBag()->toArray();
            $i=0;
            foreach ($messages as $key => $val) {
                $fieldName = $key;
                $mesageName = $val[0];
                $this->errorImport[] = $mesageName.' at line number #'.$cnt;
                $i++;
            }
        }
    }
    public function assignData($heading, $excelData,$arrColumn){
        $arr = array();
        foreach($arrColumn AS $val){
            $key = array_search($val, $heading);
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
    public function downloadBusinessPermitTemplate(){
         // Define the data to export
        $arrHeading = array('client_id'=>'','client_name'=>'','busn_office_main_barangay_id'=>'','busn_office_main_add_street_name'=>'','busn_office_main_add_subdivision'=>'', 'busn_office_main_add_lot_no'=>'', 'busn_tax_year'=>date("Y"), 'busn_tax_month'=>date("m"),'application_date'=>date("Y-m-d"),'app_code'=>'1', 'pm_id'=>'1','busn_name'=>'', 'busn_trade_name'=>'', 'btype_id'=>'', 'busn_registration_no'=>'', 'busn_tin_no'=>'','busn_office_main_building_no'=>'', 'busn_office_main_building_name'=>'', 'busn_office_main_add_block_no'=>'', 'busloc_id'=>'', 'busn_bldg_area'=>'', 'busn_bldg_total_floor_area'=>'', 'busn_employee_no_female'=>'', 'busn_employee_no_male'=>'', 'busn_employee_total_no'=>'', 'busn_employee_no_lgu'=>'', 'busn_vehicle_no_van_truck'=>'', 'busn_vehicle_no_motorcycle'=>'', 'busn_bldg_is_owned'=>'', 'busn_bldg_tax_declaration_no'=>'', 'busn_bldg_property_index_no'=>'', 'online_busn_bldg_property_index_no'=>'', 'busn_tax_incentive_enjoy'=>'', 'busn_office_building_no'=>'', 'busn_office_building_name'=>'', 'busn_office_add_block_no'=>'', 'busn_office_add_lot_no'=>'', 'busn_office_add_street_name'=>'', 'busn_office_add_subdivision'=>'', 'busn_office_barangay_id'=>'');

        $arrClients=DB::table('clients')->select('id AS client_id','full_name AS client_name','p_barangay_id_no AS busn_office_main_barangay_id','rpo_address_street_name AS busn_office_main_add_street_name','rpo_address_subdivision AS busn_office_main_add_subdivision','rpo_address_house_lot_no AS busn_office_main_add_lot_no')->where('is_active',1)->get()->toArray();

        $arrHeadData=array();
        foreach($arrHeading AS $h_key => $h_val){
            $arrHeadData[] = $h_key;
        }
        $arrFields[0] = $arrHeadData;
        $cnt=1;
        foreach($arrClients As $key => $val){
            $data = array();
            foreach($arrHeading AS $h_key => $h_val){
                $val = (array)$val;
                if(array_key_exists($h_key,$val)){
                    $data[] = $val[$h_key];
                }else{
                    $data[]=$h_val;
                }
            }
            $arrFields[$cnt] = $data;
            $cnt++;
        }
        $data = collect($arrFields);
        // Define the export class inline
        $exportClass = new class($data) implements FromCollection {
            protected $data;
            public function __construct($data){
                $this->data = $data;
            }
            public function collection(){
                return $this->data;
            }
        };
        // Export the data to Excel
        return Excel::download($exportClass, 'business-permit.csv');
    }

    public function downloadPSICSubclassTemplate(){
        // Define the data to export
        $arrHeading = array('client_name'=>'','busn_id'=>'','busn_name'=>'','subclass_id'=>'', 'busp_no_units'=>'', 'busp_capital_investment'=>'', 'busp_essential'=>'', 'busp_non_essential'=>'');

        $arrBusn=DB::table('bplo_business AS bb')->join('clients AS cl','bb.client_id','=','cl.id')->select('bb.id AS busn_id','cl.full_name AS client_name','busn_name')->where('busn_app_status','<=',1)->get()->toArray();

        $arrHeadData=array();
        foreach($arrHeading AS $h_key => $h_val){
            $arrHeadData[] = $h_key;
        }
        $arrFields[0] = $arrHeadData;
        $cnt=1;
        foreach($arrBusn As $key => $val){
            $data = array();
            foreach($arrHeading AS $h_key => $h_val){
                $val = (array)$val;
                if(array_key_exists($h_key,$val)){
                    $data[] = $val[$h_key];
                }else{
                    $data[]=$h_val;
                }
            }
            $arrFields[$cnt] = $data;
            $cnt++;
        }
        $data = collect($arrFields);
        // Define the export class inline
        $exportClass = new class($data) implements FromCollection {
            protected $data;
            public function __construct($data){
                $this->data = $data;
            }
            public function collection(){
                return $this->data;
            }
        };
        // Export the data to Excel
        return Excel::download($exportClass, 'business-permit-psic-subclasses.csv');
    }
    public function downloadMeasurePaxTemplate(){
        // Define the data to export
        $arrHeading = array('client_name'=>'','busn_id'=>'','busn_name'=>'','subclass_id'=>'','subclass_name'=>'', 'tfoc_id'=>'', 'buspx_charge_id'=>'', 'buspx_capacity'=>'', 'buspx_no_units'=>'');

        $arrBusn=DB::table('bplo_business AS bb')
        ->join('clients AS cl','bb.client_id','=','cl.id')
        ->join('bplo_business_psic AS ps','ps.busn_id','=','bb.id')
        ->join('psic_subclasses as sub','ps.subclass_id', '=', 'sub.id')
        ->select('bb.id AS busn_id','cl.full_name AS client_name','busn_name','ps.subclass_id','sub.subclass_description AS subclass_name')->where('busn_app_status','<=',1)->get()->toArray();

        $arrHeadData=array();
        foreach($arrHeading AS $h_key => $h_val){
            $arrHeadData[] = $h_key;
        }
        $arrFields[0] = $arrHeadData;
        $cnt=1;
        foreach($arrBusn As $key => $val){
            $data = array();
            foreach($arrHeading AS $h_key => $h_val){
                $val = (array)$val;
                if(array_key_exists($h_key,$val)){
                    $data[] = $val[$h_key];
                }else{
                    $data[]=$h_val;
                }
            }
            $arrFields[$cnt] = $data;
            $cnt++;
        }
        $data = collect($arrFields);
        // Define the export class inline
        $exportClass = new class($data) implements FromCollection {
            protected $data;
            public function __construct($data){
                $this->data = $data;
            }
            public function collection(){
                return $this->data;
            }
        };
        // Export the data to Excel
        return Excel::download($exportClass, 'business-permit-measure-pax.csv');
    }


    
}
