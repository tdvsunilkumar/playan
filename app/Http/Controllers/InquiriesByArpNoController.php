<?php

namespace App\Http\Controllers;
use App\Models\AcctgAccountGroupSubsubmajor;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee;
use App\Models\RptLocality;
use App\Models\RptPropertyAppraisal;
use App\Models\RptPlantTreesAppraisal;
use App\Models\RptPropertyApproval;
use App\Models\RptPropertyClass;
use App\Models\RptProperty;
use App\Models\RptBuildingFloorValue;
use App\Models\RptBuildingType;
use App\Models\RptPropertyHistory;
use App\Models\RptPropertyBuildingFloorAdItem;
use App\Models\RptPropertyMachineAppraisal;
use App\Models\GsoSupplierContactPerson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\InquiriesByArpNoInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use \Mpdf\Mpdf as PDF;
use File;
use \NumberFormatter;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Helper;
use DB;

class InquiriesByArpNoController extends Controller
{
    private InquiriesByArpNoInterface $InquiriesByArpNoRepository;
    private $carbon;
    private $params = [];
    private $slugs;
    public $additionalItems = [
        ['id' => 'FO', 'desc' => 'Fence', 'sr_no' => '1'],
        ['id' => 'BL', 'desc' => 'Balcony', 'sr_no' => '4'],
        ['id' => 'BM', 'desc' => 'Basement', 'sr_no' => '7'],
        ['id' => 'FW', 'desc' => 'Finishes', 'sr_no' => '10'],
        ['id' => 'EL', 'desc' => 'Electrical', 'sr_no' => '11'],
        ['id' => 'GS', 'desc' => 'Gate(s)', 'sr_no' => '2'],
        ['id' => 'T', 'desc' => 'Terrace', 'sr_no' => '5'],
        ['id' => 'MZ', 'desc' => 'Mezzanine', 'sr_no' => '8'],
        ['id' => '', 'desc' => 'Wall', 'sr_no' => ''],
        ['id' => 'PB', 'desc' => 'Plumbing', 'sr_no' => '12'],
        ['id' => 'GR', 'desc' => 'Garage', 'sr_no' => '3'],
        ['id' => 'R', 'desc' => 'Roof Deck', 'sr_no' => '6'],
        ['id' => 'CE', 'desc' => 'Ceiling', 'sr_no' => '9'],
        ['id' => '', 'desc' => 'Floor', 'sr_no' => ''],
        ['id' => 'other_items', 'desc' => 'Other Specify', 'sr_no' => ''],
            ];

    public function __construct(InquiriesByArpNoInterface $InquiriesByArpNoRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->InquiriesByArpNoRepository = $InquiriesByArpNoRepository;
        $this->carbon = $carbon;
        $this->slugs = 'real-property/inquiries';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $build_kinds = $this->InquiriesByArpNoRepository->allBuildKinds();
        $filter_type=1;
        return view('inquiries.by_apr_no.index')->with(compact('filter_type','build_kinds'));
    }
    
    public function lists(Request $request)
    {       
        $statusClass = [
            'draft' => 'draft-bg',
            'for approval' => 'for-approval-bg',
            'requested' => 'requested-bg',
            'for alob approval' => 'for-approval-bg',
            'allocated' => 'allocated-bg'
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'create', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn print-tax bg-primary btn m-1 btn-sm align-items-center" title="Print Tax Declaration">
                            <i class="ti-printer text-white"></i>
                        </a>
                        <a href="javascript:;" class="action-btn print-faas bg-warning btn m-1 btn-sm align-items-center" title="Print FAAS">
                            <i class="ti-printer text-white"></i>
                        </a>';
        }
        
        $canUpdate = $this->is_permitted($this->slugs, 'update', 1); 
        $canRead = $this->is_permitted($this->slugs, 'read', 1); 
        $result = $this->InquiriesByArpNoRepository->listItems($request);

        $increment = $request->start+1;
        $res = $result->data->map(function($rptProperty) use ($statusClass, $actions,&$increment) {
            $prop_index_no="106".($rptProperty->dist_code != null ? "-".$rptProperty->dist_code : "").($rptProperty->brgy_code != null ? "-".$rptProperty->brgy_code : "").($rptProperty->rp_section_no != null ? "-".$rptProperty->rp_section_no : "").($rptProperty->rp_pin_no != null ? "-".$rptProperty->rp_pin_no : "").($rptProperty->rp_suffix != null ? "-".$rptProperty->rp_suffix : "");
            $full_name = $rptProperty->full_name;
            $sl_no = $increment;
            $increment++;
            if($rptProperty->kind == "Building"){
                $buildingAssesstvalue = $rptProperty->value;
                $formattedValue = number_format($buildingAssesstvalue, 2, '.', ',');
            }else{
                $buildingAssesstvalue = $rptProperty->value;
                $formattedValue = number_format($buildingAssesstvalue, 2, '.', ',');
            }
           // echo "<pre>"; print_r($rptProperty); exit;
            return [
                'sl_no' => $sl_no,
                'id' => $rptProperty->ID,
                'own_name' => $full_name,
                'prop_index_no' => $rptProperty->rp_pin_declaration_no,
                'td_no' => $rptProperty->rp_tax_declaration_no,
                'kind' => $rptProperty->kind,
                'class' => $rptProperty->rp_class,
                'value' => $formattedValue,
                'action' => $actions
            ];
        });
        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function listByTct(Request $request)
    {       
        $statusClass = [
            'draft' => 'draft-bg',
            'for approval' => 'for-approval-bg',
            'requested' => 'requested-bg',
            'for alob approval' => 'for-approval-bg',
            'allocated' => 'allocated-bg'
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'create', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn print-tax bg-primary btn m-1 btn-sm align-items-center" title="Print Tax Declaration">
                            <i class="ti-printer text-white"></i>
                        </a>
                        <a href="javascript:;" class="action-btn print-faas bg-warning btn m-1 btn-sm align-items-center" title="Print FAAS">
                            <i class="ti-printer text-white"></i>
                        </a>';
        }
        $canUpdate = $this->is_permitted($this->slugs, 'update', 1); 
        $canRead = $this->is_permitted($this->slugs, 'read', 1); 
        $result = $this->InquiriesByArpNoRepository->listItemsByTct($request);
        $increment=$request->start+1;;
        $res = $result->data->map(function($rptProperty) use ($statusClass, $actions,&$increment) {
            $prop_index_no="106".$rptProperty->dist_code."-".$rptProperty->brgy_code."-".$rptProperty->rp_section_no."-".$rptProperty->rp_pin_no."-".$rptProperty->rp_suffix;
            $f_name = $rptProperty->full_name;
            $sl_no = $increment;
            $increment++;
            $value = $rptProperty->value;
            $formattedValue = number_format($value, 3, '.', ',');
            return [
                'id' => $rptProperty->ID,
                'sl_no' => $sl_no,
                'own_name' => $f_name,
                'tct_no' => $rptProperty->tct_no,
                'prop_index_no' => $rptProperty->rp_pin_declaration_no,
                'td_no' => $rptProperty->rp_tax_declaration_no,
                'kind' => $rptProperty->kind,
                'class' => $rptProperty->rp_class,
                'value' => $formattedValue,
                'action' => $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function listByCct(Request $request)
    {       
        $statusClass = [
            'draft' => 'draft-bg',
            'for approval' => 'for-approval-bg',
            'requested' => 'requested-bg',
            'for alob approval' => 'for-approval-bg',
            'allocated' => 'allocated-bg'
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'create', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn print-tax bg-primary btn m-1 btn-sm align-items-center" title="Print Tax Declaration">
                            <i class="ti-printer text-white"></i>
                        </a>
                        <a href="javascript:;" class="action-btn print-faas bg-warning btn m-1 btn-sm align-items-center" title="Print FAAS">
                            <i class="ti-printer text-white"></i>
                        </a>';
        }
        $canUpdate = $this->is_permitted($this->slugs, 'update', 1); 
        $canRead = $this->is_permitted($this->slugs, 'read', 1); 
        $result = $this->InquiriesByArpNoRepository->listItemsByCct($request);
        $increment=$request->start+1;;
        $res = $result->data->map(function($rptProperty) use ($statusClass, $actions,&$increment) {
            $prop_index_no="106".$rptProperty->dist_code."-".$rptProperty->brgy_code."-".$rptProperty->rp_section_no."-".$rptProperty->rp_pin_no."-".$rptProperty->rp_suffix;
            $f_name = $rptProperty->full_name;
            $sl_no = $increment;
            $increment++;
            $value = $rptProperty->value;
            $formattedValue = number_format($value, 3, '.', ',');
            return [
                'id' => $rptProperty->ID,
                'own_name' => $f_name,
                'sl_no' => $sl_no,
                'cct_no' => $rptProperty->cct_no,
                'unit_no' => $rptProperty->unit_no,
                'prop_index_no' => $rptProperty->rp_pin_declaration_no,
                'td_no' => $rptProperty->rp_tax_declaration_no,
                'kind' => $rptProperty->kind,
                'class' => $rptProperty->rp_class,
                'value' => $formattedValue,
                'action' => $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function listByOwn(Request $request)
    {       
        $statusClass = [
            'draft' => 'draft-bg',
            'for approval' => 'for-approval-bg',
            'requested' => 'requested-bg',
            'for alob approval' => 'for-approval-bg',
            'allocated' => 'allocated-bg'
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'create', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn print-tax bg-primary btn m-1 btn-sm align-items-center" title="Print Tax Declaration">
                            <i class="ti-printer text-white"></i>
                        </a>
                        <a href="javascript:;" class="action-btn print-faas bg-warning btn m-1 btn-sm align-items-center" title="Print FAAS">
                            <i class="ti-printer text-white"></i>
                        </a>';
        }
        $canUpdate = $this->is_permitted($this->slugs, 'update', 1); 
        $canRead = $this->is_permitted($this->slugs, 'read', 1); 
        $result = $this->InquiriesByArpNoRepository->listItemsByOwn($request);
        $increment=$request->start+1;;
        $res = $result->data->map(function($rptProperty) use ($statusClass, $actions,&$increment) {
            $prop_index_no="106".$rptProperty->dist_code."-".$rptProperty->brgy_code."-".$rptProperty->rp_section_no."-".$rptProperty->rp_pin_no."-".$rptProperty->rp_suffix;
            $f_name = $rptProperty->full_name;
            $sl_no = $increment;
            $increment++;
            if($rptProperty->kind == "Building"){
                $buildingAssesstvalue = $rptProperty->rpb_assessed_value;
                $formattedValue = number_format($buildingAssesstvalue, 3, '.', ',');
            }else{
                $buildingAssesstvalue = $rptProperty->rp_assessed_value;
                $formattedValue = number_format($buildingAssesstvalue, 3, '.', ',');
            }
            return [
                'id' => $rptProperty->ID,
                'sl_no' => $sl_no,
                'lot_no' => $rptProperty->lot_no,
                'own_name' => $f_name,
                'prop_index_no' => $rptProperty->rp_pin_declaration_no,
                'td_no' => $rptProperty->rp_tax_declaration_no,
                'kind' => $rptProperty->kind,
                'class' => $rptProperty->rp_class,
                'value' => $formattedValue,
                'action' => $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function listByBuildKind(Request $request)
    {       
        $statusClass = [
            'draft' => 'draft-bg',
            'for approval' => 'for-approval-bg',
            'requested' => 'requested-bg',
            'for alob approval' => 'for-approval-bg',
            'allocated' => 'allocated-bg'
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'create', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn print-tax bg-primary btn m-1 btn-sm align-items-center" title="Print Tax Declaration">
                            <i class="ti-printer text-white"></i>
                        </a>
                        <a href="javascript:;" class="action-btn print-faas bg-warning btn m-1 btn-sm align-items-center" title="Print FAAS">
                            <i class="ti-printer text-white"></i>
                        </a>';
        }
        $canUpdate = $this->is_permitted($this->slugs, 'update', 1); 
        $canRead = $this->is_permitted($this->slugs, 'read', 1); 
        $result = $this->InquiriesByArpNoRepository->listItemsByBuildKind($request);
        $increment=$request->start+1;;
        $res = $result->data->map(function($rptProperty) use ($statusClass, $actions,&$increment) {
            $prop_index_no="106".$rptProperty->dist_code."-".$rptProperty->brgy_code."-".$rptProperty->rp_section_no."-".$rptProperty->rp_pin_no."-".$rptProperty->rp_suffix;
            $f_name = $rptProperty->full_name;
            $sl_no = $increment;
            $increment++;
            $value = $rptProperty->value;
            $formattedValue = number_format($value, 3, '.', ',');
            return [
                'id' => $rptProperty->ID,
                'sl_no' => $sl_no,
                'own_name' => $f_name,
                'cct_no' => $rptProperty->cct_no,
                'unit_no' => $rptProperty->unit_no,
                'prop_index_no' => $rptProperty->rp_pin_declaration_no,
                'td_no' => $rptProperty->rp_tax_declaration_no,
                'kind' => $rptProperty->kind,
                'class' => $rptProperty->rp_class,
                'value' => $formattedValue,
                'action' => $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function listByServey(Request $request)
    {       
        $statusClass = [
            'draft' => 'draft-bg',
            'for approval' => 'for-approval-bg',
            'requested' => 'requested-bg',
            'for alob approval' => 'for-approval-bg',
            'allocated' => 'allocated-bg'
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'create', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn print-tax bg-primary btn m-1 btn-sm align-items-center" title="Print Tax Declaration">
                            <i class="ti-printer text-white"></i>
                        </a>
                        <a href="javascript:;" class="action-btn print-faas bg-warning btn m-1 btn-sm align-items-center" title="Print FAAS">
                            <i class="ti-printer text-white"></i>
                        </a>';
        }
        $canUpdate = $this->is_permitted($this->slugs, 'update', 1); 
        $canRead = $this->is_permitted($this->slugs, 'read', 1); 
        $result = $this->InquiriesByArpNoRepository->listItemsByServey($request);
        $increment=$request->start+1;;
        $res = $result->data->map(function($rptProperty) use ($statusClass, $actions,&$increment) {
            $prop_index_no="106".$rptProperty->dist_code."-".$rptProperty->brgy_code."-".$rptProperty->rp_section_no."-".$rptProperty->rp_pin_no."-".$rptProperty->rp_suffix;
            $f_name = $rptProperty->full_name;
            $sl_no = $increment;
            $increment++;
            $value = $rptProperty->value;
            $formattedValue = number_format($value, 3, '.', ',');
            return [
                'id' => $rptProperty->ID,
                'sl_no' => $sl_no,
                'own_name' => $f_name,
                'survey_no' => $rptProperty->survey_no,
                'td_no' => $rptProperty->rp_tax_declaration_no,
                'kind' => $rptProperty->kind,
                'class' => $rptProperty->rp_class,
                'value' => $formattedValue,
                'action' => $actions
            ];
        });
        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }
    public function getLineItems($startFrom, $perPage, $keywords, $sortBy, $orderBy) 
    {
        $res = $this->InquiriesByArpNoRepository->listItems($startFrom, $perPage, $keywords, $sortBy, $orderBy);
        return $res->map(function($payeeData) {
            return (object) [
                'id' => $payeeData->payeeId,
                'payeeName' => $payeeData->payeeName,
                'payeeType' => $payeeData->payeeType,
                'modified' => ($payeeData->payeeUpdatedAt !== NULL) ? date('d-M-Y', strtotime($payeeData->payeeUpdatedAt)).'<br/>'. date('h:i A', strtotime($payeeData->payeeUpdatedAt)) : date('d-M-Y', strtotime($payeeData->payeeCreatedAt)).'<br/>'. date('h:i A', strtotime($payeeData->payeeCreatedAt)),
                'status' => $payeeData->status
            ];
        });
    }

    public function printTaxDec(Request $request)
    {
        echo url('/real-property/inquiries/printTax/'.$request->get('id'));
    }

    public function printFAAS(Request $request)
    {
        echo url('/real-property/inquiries/pdfFaas/'.$request->get('id'));
    }
    public function printFAASBuilding(Request $request)
    {
        // echo url('/real-property/inquiries/pdfFaasBuilding/'.$request->get('id'));
        echo url('/real-property/inquiries/pdfFaas/'.$request->get('id'));
    }

    public function pdfFaasBuilding(Request $request,$id)
    {
        $rptProperty = $this->InquiriesByArpNoRepository->listItemsById($id);
        $adminBrgy = $this->InquiriesByArpNoRepository->listBarangayId($rptProperty->admin_barangay_id);
        $own_address = $this->InquiriesByArpNoRepository->listBarangayId($rptProperty->propertyOwner_barangay_id);
        $prop_index_no="106".($rptProperty->dist_code != null ? "-".$rptProperty->dist_code : "").($rptProperty->brgy_code != null ? "-".$rptProperty->brgy_code : "").($rptProperty->rp_section_no != null ? "-".$rptProperty->rp_section_no : "").($rptProperty->rp_pin_no != null ? "-".$rptProperty->rp_pin_no : "").($rptProperty->rp_suffix != null ? "-".$rptProperty->rp_suffix : "");
		$own_name = $rptProperty->full_name;
		
        $own_address=isset($own_address) ? $own_address->brgy_name.' ,'.$own_address->mun_desc.' ,'.$own_address->prov_desc.' ,'.$own_address->reg_region : '';
        $RptPropertyAppraisals=RptPropertyAppraisal::where('rp_code',$id)->get();
        $RptPlantTreesAppraisal=RptPlantTreesAppraisal::where('rp_code',$id)->get();
        $admin_address=isset($adminBrgy) ? $adminBrgy->brgy_name.' ,'.$adminBrgy->mun_desc.' ,'.$adminBrgy->prov_desc.' ,'.$adminBrgy->reg_region : '';
           $admin_name = $rptProperty->full_name;
        
        $registered_by = $this->InquiriesByArpNoRepository->CreatelistHrDescId($rptProperty->rp_registered_by);
        $RptPropertyApproval=RptPropertyApproval::where('rp_code',$id)->first();
        $apprisedPosition = $this->InquiriesByArpNoRepository->listHrDescId($RptPropertyApproval->rp_app_appraised_by);
        $recommendPosition = $this->InquiriesByArpNoRepository->listHrDescId($RptPropertyApproval->rp_app_recommend_by);
        $approvedPosition = $this->InquiriesByArpNoRepository->listHrDescId($RptPropertyApproval->rp_app_approved_by);
        $buildingKind=$this->InquiriesByArpNoRepository->listBuildingKind($rptProperty->bk_building_kind_code);
        // print_r($buildingKind->bk_building_kind_desc);exit;
        $landDetails=RptProperty::where('id',$rptProperty->rp_code_lref)->first();
        $BuildingUnitValue=RptBuildingFloorValue::where('rp_code',$id)->first();
        $BuildingType=RptBuildingType::where('id',$BuildingUnitValue->bt_building_type_code)->first();
        // print_r($BuildingType->bt_building_type_desc);exit;
        $rptPropertyOwner = $this->InquiriesByArpNoRepository->listItemsById($landDetails->id);
        $Land_own_name = $rptPropertyOwner->full_name;
        $RptPropertyBuildingFloorAdItem = RptPropertyBuildingFloorAdItem::where('rp_code', $id)
        ->get();
        $landReference    = RptProperty::where('id',$rptProperty->rp_code_lref)->first();
        $history          = RptPropertyHistory::with([
                        'activeProp.revisionYearDetails',
                        'cancelProp.revisionYearDetails',
                        'activeProp.barangay',
                        'cancelProp.barangay',
                        'cancelProp.propertyOwner',
                        'cancelProp.landAppraisals.actualUses'
                    ])->where('rp_property_code',$rptProperty->rp_property_code)
                    ->orderBy('id','DESC')
                    ->take(1)->get();
        $cacnceledPinArray = [];
        $cacnceledTdArray = [];
        $cacnceledTdOwnerArray = [];
        $cacnceledTdAssValue = [];
        $cacnceledArpArray = [];
        $cancelComTdArray = [];
        $cancelPropEffectivity = [];
        foreach ($history as $key => $value) {
            $cacnceledPinArray[] = (isset($value->cancelProp->rp_pin_declaration_no))?$value->cancelProp->rp_pin_declaration_no:'';
                        $cacnceledTdArray[] = (isset($value->cancelProp->rp_tax_declaration_aform))?$value->cancelProp->rp_tax_declaration_aform:'';
                        $cancelPropEffectivity[] = (isset($value->cancelProp->rp_app_effective_year))?$value->cancelProp->rp_app_effective_year:'';
                        $cancelComTdArray[] = (isset($value->cancelProp->rp_tax_declaration_no))?$value->cancelProp->rp_tax_declaration_no:'';
                        $cacnceledTdOwnerArray[] = (isset($value->cancelProp->propertyOwner->full_name))?$value->cancelProp->propertyOwner->full_name:'';
                        $cacnceledTdAssValue[]  = (isset($value->cancelProp->assessed_value_for_all_kind))?Helper::decimal_format($value->cancelProp->assessed_value_for_all_kind):0;
                         $array = explode("-",$value->cancelProp->pr_tax_arp_no);
                if(count($array) > 2){
                    unset($array[0]);
                }
                $cacnceledArpArray[] = implode("-",$array);
        } 

        $data = [
                    'rptProperty' => $rptProperty, 
                    'prop_index_no' => $prop_index_no,
                    'own_name' => $own_name, 
                    'own_address' => $own_address, 
                    'RptPropertyAppraisals' => $RptPropertyAppraisals, 
                    'RptPlantTreesAppraisals' => $RptPlantTreesAppraisal,
                    'admin_name' => $admin_name,
                    'admin_address' => $admin_address,
                    'recommendPosition'=>$recommendPosition,
                    'apprisedPosition'=>$apprisedPosition,
                    'approvedPosition'=>$approvedPosition,
                    'registered_by'=>$registered_by,
                    'RptPropertyApproval'=>$RptPropertyApproval,
                    'landDetails'=>$landDetails,
                    'Land_own_name'=>$Land_own_name,
                    'buildingKind'=>$buildingKind,
                    'BuildingType'=>$BuildingType,
                    'BuildingUnitValue'=>$BuildingUnitValue,
                    'RptPropertyBuildingFloorAdItem'=>$RptPropertyBuildingFloorAdItem,
                    'cancelTdByThis' => implode(" ",$cacnceledTdArray),
                    'cancelTdByOwner' => implode("; ",$cacnceledTdOwnerArray),
                    'cacnceledTdAssValue' => implode("; ",$cacnceledTdAssValue),
                    'cacnceledPinArray' => implode("; ",$cacnceledPinArray),
                    'cacnceledArpArray' => implode("; ",$cacnceledArpArray),
                    'cancelComTdArray'  => implode("; ",$cancelComTdArray),
                    'cancelPropEffectivity'  => implode("; ",$cancelPropEffectivity),
                    'landRef'           => $landReference
                ];
        
        $documentFileName = "Tax-Declaration-Building.pdf";
        $document = new PDF( [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => '3',
            'margin_top' => '20',
            'margin_bottom' => '20',
            'margin_footer' => '2',
        ]);     
 
        // Set some header informations for output
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
        ];	
        $html = view('inquiries.by_apr_no.printFAASBuilding', $data)->render();
        // $html_back = view('inquiries.by_apr_no.printFAASBack', $data)->render();

        $document->WriteHTML($html);
        // $document->WriteHTML($html_back);
         
        // Save PDF on your public storage 
        Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "S"));
         
        // Get file back from storage with the give header informations
        return Storage::disk('public')->download($documentFileName, 'Request', $header); //


    }
    public function pdfFaas(Request $request,$id)
    {
		
        $propAdditionalItems = [];
        $additionalItems   = [];
        $floorRoofWallData = [];
        $rptProperty = $this->InquiriesByArpNoRepository->listItemsById($id);
        $adminBrgy = $this->InquiriesByArpNoRepository->listBarangayId($rptProperty->admin_barangay_id);
        $own_address = $this->InquiriesByArpNoRepository->listBarangayId($rptProperty->propertyOwner_barangay_id);
       
        $prop_index_no="106".($rptProperty->dist_code != null ? "-".$rptProperty->dist_code : "").($rptProperty->brgy_code != null ? "-".$rptProperty->brgy_code : "").($rptProperty->rp_section_no != null ? "-".$rptProperty->rp_section_no : "").($rptProperty->rp_pin_no != null ? "-".$rptProperty->rp_pin_no : "").($rptProperty->rp_suffix != null ? "-".$rptProperty->rp_suffix : "");


         $rptProperty = RptProperty::where('rpt_properties.id',$id)
                        ->select('rpt_properties.*','bk.bk_building_kind_desc')
                        ->leftJoin('rpt_building_kinds as bk','bk.id','=','rpt_properties.bk_building_kind_code')
                        ->first();
                       // dd($rptProperty->propertyOwner);
        $own_name = isset($rptProperty->propertyOwner->full_name) ? $rptProperty->propertyOwner->full_name : '';
        $own_address=isset($rptProperty->propertyOwner->standard_address) ? $rptProperty->propertyOwner->standard_address : '';
        $admin_address=isset($rptProperty->propertyAdmin->standard_address) ? $rptProperty->propertyAdmin->standard_address : '';
        $admin_name = isset($rptProperty->propertyAdmin->full_name) ? $rptProperty->propertyAdmin->full_name : '';
        $RptPropertyAppraisals=RptPropertyAppraisal::where('rp_code',$id)
                               ->addSelect([
                                'plantsTreeTotal' => RptPlantTreesAppraisal::select(DB::raw("SUM(rpta_market_value) AS plantsTreeTotal"))
                                       ->whereColumn('rpa_code', 'rpt_property_appraisals.id')
                              ])
                               ->get();
        $RptPlantTreesAppraisal=RptPlantTreesAppraisal::where('rp_code',$id)->get();
		
        $registered_by = $this->InquiriesByArpNoRepository->CreatelistHrDescId($rptProperty->rp_registered_by);
		
        $RptPropertyApproval=RptPropertyApproval::where('rp_code',$id)->first();
        $apprisedPosition = $this->InquiriesByArpNoRepository->listHrDescId($RptPropertyApproval->rp_app_appraised_by);
        $recommendPosition = $this->InquiriesByArpNoRepository->listHrDescId($RptPropertyApproval->rp_app_recommend_by);
        $approvedPosition = $this->InquiriesByArpNoRepository->listHrDescId($RptPropertyApproval->rp_app_approved_by);
        $landReference    = RptProperty::where('id',$rptProperty->rp_code_lref)->first();
        $buildReference    = RptProperty::where('id',$rptProperty->rp_code_bref)->first();
        $history = RptPropertyHistory::with([
                        'activeProp.revisionYearDetails',
                        'cancelProp.revisionYearDetails',
                        'activeProp.barangay',
                        'cancelProp.barangay',
                        'cancelProp.propertyOwner',
                        'cancelProp.landAppraisals.actualUses'
                    ])
                    ->join('rpt_properties as cancelProp','cancelProp.id','=','rpt_property_histories.rp_code_cancelled')
                    //->where('rpt_property_histories.rp_property_code',$rptProperty->rp_property_code)
                    //->where('cancelProp.pk_is_active',0)
                    ->orderBy('rpt_property_histories.id','DESC');
                   // dd($rptProperty);
        if($rptProperty->uc_code == config('constants.update_codes_land.CS')){
            $countForConsolidation = DB::table('rpt_property_approvals')->where('rp_app_cancel_by_td_id',$rptProperty->id)->count();
            $history->where('rpt_property_histories.rp_property_code',$rptProperty->rp_property_code_new);
            $history->take(($countForConsolidation != null)?$countForConsolidation:1);
       }else{
            $history->where('rpt_property_histories.rp_property_code',$rptProperty->rp_property_code);
            $history->take(1);
       }
       $history = $history->get();
        $cacnceledPinArray = [];
        $cacnceledTdArray = [];
        $cacnceledTdOwnerArray = [];
        $cacnceledTdAssValue = [];
        $cacnceledArpArray = [];
        $cancelComTdArray = [];
        $cancelPropEffectivity = [];
        //dd($history);
        foreach ($history as $key => $value) {
            $cacnceledPinArray[] = (isset($value->cancelProp->rp_pin_declaration_no))?$value->cancelProp->rp_pin_declaration_no:'';
                        $cacnceledTdArray[] = (isset($value->cancelProp->rp_tax_declaration_aform))?$value->cancelProp->rp_tax_declaration_aform:'';
                        $cancelPropEffectivity[] = (isset($value->cancelProp->rp_app_effective_year))?$value->cancelProp->rp_app_effective_year:'';
                        $cancelComTdArray[] = (isset($value->cancelProp->rp_tax_declaration_no))?$value->cancelProp->rp_tax_declaration_no:'';
                        $cacnceledTdOwnerArray[] = (isset($value->cancelProp->propertyOwner->full_name))?$value->cancelProp->propertyOwner->full_name:'';
                        $cacnceledTdAssValue[]  = (isset($value->cancelProp->assessed_value_for_all_kind))?Helper::decimal_format($value->cancelProp->assessed_value_for_all_kind):0;
                         $array = explode("-",$value->cancelProp->pr_tax_arp_no);
                if(count($array) > 2){
                    unset($array[0]);
                }
                $cacnceledArpArray[] = implode("-",$array);
                    } 
        if(isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'B'){
        $arrbuildingroof  = [];
        $arrbuildingfloor = [];
        $arrbuildingfwall = [];
        $rptPropObj = new RptProperty;
        foreach ($rptPropObj->getPropertyBuildingroof() as $val) {
                $arrbuildingroof[$val->id]=$val->rbr_building_roof_desc;
            }
            foreach ($rptPropObj->getPropertyBuildingrfloor() as $val) {
                $arrbuildingfloor[$val->id]=$val->rbf_building_flooring_desc;
            }
            foreach ($rptPropObj->getPropertyBuildingwall() as $val) {
                $arrbuildingfwall[$val->id]=$val->rbw_building_walling_desc;
            }
           // dd(array_keys($arrbuildingroof));
        $countArray = [count($arrbuildingroof),count($arrbuildingfloor),count($arrbuildingfwall)];
        $newMergedData= [];
        for ($i=0; $i < MAX($countArray); $i++) { 
            $roofIdKeys = array_keys($arrbuildingroof);
            $roofData   = [];
            if(isset($roofIdKeys[$i])){
                $key = $roofIdKeys[$i];
                $roofData = [
                    'id' => $key,
                    'desc' => $arrbuildingroof[$key]
                ];
            }

            $floorIdKeys = array_keys($arrbuildingfloor);
            $floorData   = [];
            if(isset($floorIdKeys[$i])){
                $key = $floorIdKeys[$i];
                $floorData = [
                    'id' => $key,
                    'desc' => $arrbuildingfloor[$key]
                ];
            }

            $wallIdKeys = array_keys($arrbuildingfwall);
            $wallData   = [];
            if(isset($wallIdKeys[$i])){
                $key = $wallIdKeys[$i];
                $wallData = [
                    'id' => $key,
                    'desc' => $arrbuildingfwall[$key]
                ];
            }
            $m = [
                'roof' => $roofData,
                'floor' => $floorData,
                'wall' => $wallData
            ];
            $newMergedData[] = $m;
            
        }
        $floorRoofWallData = $newMergedData;
        $additionalItems = $this->additionalItems;
        $propAdditionalItems = DB::table('rpt_property_building_floor_ad_items')->where('rp_code',$rptProperty->id)->pluck('bei_extra_item_desc','bei_extra_item_code')->toArray();
       
                } 
         $annotationData   = DB::table('rpt_property_annotations')->select(DB::raw('GROUP_CONCAT(rpa_annotation_desc SEPARATOR"; ") as annotation'))->where('rp_code',$id)->first();
        $annotation = (isset($annotationData->annotation))?'"'.$annotationData->annotation.'"':'';                  
        
        $data = [
                    'rptProperty' => $rptProperty, 
                    'prop_index_no' => $prop_index_no,
                    'own_name' => $own_name, 
                    'own_address' => $own_address, 
                    'RptPropertyAppraisals' => $RptPropertyAppraisals, 
                    'RptPlantTreesAppraisals' => $RptPlantTreesAppraisal,
                    'admin_name' => $admin_name,
                    'admin_address' => $admin_address,
                    'recommendPosition'=>$recommendPosition,
                    'apprisedPosition'=>$apprisedPosition,
                    'approvedPosition'=>$approvedPosition,
                    'registered_by'=>$registered_by,
                    'RptPropertyApproval'=>$RptPropertyApproval,
                    'cancelTdByThis' => implode(" / ",$cacnceledTdArray),
                    'cancelTdByOwner' => implode(" / ",$cacnceledTdOwnerArray),
                    'cacnceledTdAssValue' => implode(" / ",$cacnceledTdAssValue),
                    'cacnceledPinArray' => implode(" / ",$cacnceledPinArray),
                    'cacnceledArpArray' => implode(" / ",$cacnceledArpArray),
                    'cancelComTdArray'  => implode(" / ",$cancelComTdArray),
                    'cancelPropEffectivity'  => implode(" / ",$cancelPropEffectivity),
                    'landRef'           => $landReference,
                    'buildRef'    => $buildReference,
                    'floorRoofWallData' => $floorRoofWallData,
                    'additionalItems'  => array_chunk($additionalItems, 5),
                    'propAdditionalItems' => $propAdditionalItems,
                    'annotation' => $annotation
                ];
                //dd(array_chunk($additionalItems, 5));
               /*if(isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'M'){
                    return view('inquiries.by_apr_no.printFAASmachinery', $data);
                }else{
                    return view('inquiries.by_apr_no.printFAAS2', $data);
                }*/
    
        // return view('inquiries.by_apr_no.printTax')->with(compact('rptProperty','prop_index_no','own_name','own_address','RptPropertyAppraisals'));


        // Setup a filename 
        $documentFileName = "Tax-Declaration.pdf";
        // Create the mPDF document
        $document = new PDF([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => '3',
            'margin_top' => '20',
            'margin_bottom' => '20',
            'margin_footer' => '2',
            'default_font' => 'arial'
        ]);     
 
        // Set some header informations for output
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
        ];	
        if(isset($rptProperty->propertyKindDetails->pk_code) && $rptProperty->propertyKindDetails->pk_code == 'M'){
					
					 $html = view('inquiries.by_apr_no.printFAASmachinery', $data);
                }else{
					
                    $html = view('inquiries.by_apr_no.printFAAS2', $data);
                }
        // $html_back = view('inquiries.by_apr_no.printFAASBack', $data)->render();

        $document->WriteHTML($html);
        // $document->WriteHTML($html_back);
         
        // Save PDF on your public storage 
        Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "S"));
         
        // Get file back from storage with the give header informations
        return Storage::disk('public')->download($documentFileName, 'Request', $header); //


    }
    
    public function printTax(Request $request,$id)
    {
        // $rptProperty = RptProperty::find($id);
        $rptProperty = $this->InquiriesByArpNoRepository->listItemsById($id);
        $adminBrgy = $this->InquiriesByArpNoRepository->listBarangayId($rptProperty->admin_barangay_id);
        $own_address = $this->InquiriesByArpNoRepository->listBarangayId($rptProperty->propertyOwner_barangay_id);
        // print_r($rptProperty);exit;
        $prop_index_no="106".($rptProperty->dist_code != null ? "-".$rptProperty->dist_code : "").($rptProperty->brgy_code != null ? "-".$rptProperty->brgy_code : "").($rptProperty->rp_section_no != null ? "-".$rptProperty->rp_section_no : "").($rptProperty->rp_pin_no != null ? "-".$rptProperty->rp_pin_no : "").($rptProperty->rp_suffix != null ? "-".$rptProperty->rp_suffix : "");

        $rptProperty = RptProperty::where('id',$id)->first();

        /*$prop_index_no="106".($rptProperty->dist_code != null ? "-".$rptProperty->dist_code : "").($rptProperty->brgy_code != null ? "-".$rptProperty->brgy_code : "").($rptProperty->rp_section_no != null ? "-".$rptProperty->rp_section_no : "").($rptProperty->rp_pin_no != null ? "-".$rptProperty->rp_pin_no : "").($rptProperty->rp_suffix != null ? "-".$rptProperty->rp_suffix : "");*/
        $prop_index_no='';


		$own_name = isset($rptProperty->propertyOwner->full_name) ? $rptProperty->propertyOwner->full_name : '';
		$own_address=isset($rptProperty->propertyOwner->standard_address) ? $rptProperty->propertyOwner->standard_address : '';
        $admin_address=isset($rptProperty->propertyAdmin->standard_address) ? $rptProperty->propertyAdmin->standard_address : '';
        $admin_name = isset($rptProperty->propertyAdmin->full_name) ? $rptProperty->propertyAdmin->full_name : '';
        $RptPropertyAppraisals=RptPropertyAppraisal::where('rp_code',$id)->get();
        // print_r($RptPropertyAppraisals->);exit;
        $RptPropertyMachineAppraisal=RptPropertyMachineAppraisal::where('rp_code',$id)->get();
        $RptPropertyClassId=RptPropertyMachineAppraisal::where('rp_code',$id)->first();
        $RptPropertyClass="";
        //dd($RptPropertyMachineAppraisal);
        //dd($rptProperty->propertyOwner);
        if(isset($RptPropertyClassId)){
          $RptPropertyClass=RptPropertyClass::where('id',$RptPropertyClassId->pc_class_code)->first();  
        }
        
         // print_r($RptPropertyClass->pc_class_description);exit;
        $locality=RptLocality::where('mun_no',$rptProperty->loc_local_code)->value('loc_local_name');
        // $recommendPosition =NULL;  $apprisedPosition =NULL; $approvedPosition=NULL; 
        // if($rptProperty->propertyApproval != NULL){
        //  $recommendPosition = $this->InquiriesByArpNoRepository->listHrDescId($rptProperty->propertyApproval->recommendBy->id);
        // $apprisedPosition = $this->InquiriesByArpNoRepository->listHrDescId($rptProperty->propertyApproval->apprisedBy->id);
        // $approvedPosition = $this->InquiriesByArpNoRepository->listHrDescId($rptProperty->propertyApproval->approveBy->id);
        $RptPropertyApproval=RptPropertyApproval::where('rp_code',$id)->first();
         // print_r($id);exit;
        $apprisedPosition = $this->InquiriesByArpNoRepository->listHrDescId($RptPropertyApproval->rp_app_appraised_by);
        // print_r($apprisedPosition);exit;
        $recommendPosition = $this->InquiriesByArpNoRepository->listHrDescId($RptPropertyApproval->rp_app_recommend_by);
		//print_r($recommendPosition);die;
        //dd($recommendPosition->standard_name);
        $approvedPosition = $this->InquiriesByArpNoRepository->listHrDescId($RptPropertyApproval->rp_app_approved_by);
        //dd($apprisedPosition);
        $history = RptPropertyHistory::with([
                        'activeProp.revisionYearDetails',
                        'cancelProp.revisionYearDetails',
                        'activeProp.barangay',
                        'cancelProp.barangay',
                        'cancelProp.propertyOwner',
                        'cancelProp.landAppraisals.actualUses'
                    ])
                    ->join('rpt_properties as cancelProp','cancelProp.id','=','rpt_property_histories.rp_code_cancelled')
                    ->where('rpt_property_histories.rp_property_code',$rptProperty->rp_property_code)
                    //->where('cancelProp.pk_is_active',0)
                    ->orderBy('rpt_property_histories.id','DESC');
        if($rptProperty->uc_code == config('constants.update_codes_land.CS')){
            $countForConsolidation = DB::table('rpt_property_approvals')->where('rp_app_cancel_by_td_id',$rptProperty->id)->count();
            $history->where('rpt_property_histories.rp_property_code',$rptProperty->rp_property_code_new);
            $history->take(($countForConsolidation != null)?$countForConsolidation:1);
       }else{
            $history->where('rpt_property_histories.rp_property_code',$rptProperty->rp_property_code);
            $history->take(1);
       }
       $history = $history->get();
        $cacnceledTdArray = [];
        $cacnceledTdOwnerArray = [];
        $cacnceledTdAssValue = [];
        foreach ($history as $key => $value) {
                        $cacnceledTdArray[] = (isset($value->cancelProp->rp_tax_declaration_aform))?$value->cancelProp->rp_tax_declaration_aform:'';
                        $cacnceledTdOwnerArray[] = (isset($value->cancelProp->propertyOwner->full_name))?$value->cancelProp->propertyOwner->full_name:'';
                        $cacnceledTdAssValue[]  = (isset($value->cancelProp->assessed_value_for_all_kind))?Helper::decimal_format($value->cancelProp->assessed_value_for_all_kind):0;
                    }            
        $annotationData   = DB::table('rpt_property_annotations')->select(DB::raw('GROUP_CONCAT(rpa_annotation_desc SEPARATOR"; ") as annotation'))->where('rp_code',$id)->first();
        $annotation = (isset($annotationData->annotation))?'"'.$annotationData->annotation.'"':'';
        $data = [
                    'rptProperty' => $rptProperty, 
                    'locality'    =>$locality, 
                    'prop_index_no' => $prop_index_no,
                    'own_name' => $own_name, 
                    'own_address' => $own_address, 
                    'admin_name' => $admin_name, 
                    'admin_address' => $admin_address, 
                    'RptPropertyAppraisals' => $RptPropertyAppraisals, 
                    'recommendPosition'=>$recommendPosition,
                    'apprisedPosition'=>$apprisedPosition,
                    'approvedPosition'=>$approvedPosition,
                    'RptPropertyApproval'=>$RptPropertyApproval,
                    'RptPropertyMachineAppraisal'=>$RptPropertyMachineAppraisal,
                    'RptPropertyClass'=>$RptPropertyClass,
                    'cancelTdByThis' => implode(" ",$cacnceledTdArray),
                    'cancelTdByOwner' => implode("; ",$cacnceledTdOwnerArray),
                    'cacnceledTdAssValue' => implode("; ",$cacnceledTdAssValue),
                    'annotation' => $annotation
                ];

        return view('inquiries.by_apr_no.printTax2', $data);
        // return view('inquiries.by_apr_no.printTax')->with(compact('rptProperty','prop_index_no','own_name','own_address','RptPropertyAppraisals'));


        // Setup a filename 
        $documentFileName = "Tax-Declaration.pdf";
 
        // Create the mPDF document
        // $document = new PDF( [
        //     'mode' => 'utf-8',
        //     'format' => 'A4',
        //     'margin_header' => '3',
        //     'margin_top' => '20',
        //     'margin_bottom' => '20',
        //     'margin_footer' => '2',
        // ]);     
        //$document = new \Mpdf\Mpdf(['format' => 'A5','default_font' => 'arial']);
        $document = new \Mpdf\Mpdf(['format' => [215.9,330.2],'default_font' => 'arial']);
        
        $document->watermarkImgBehind = true;
        $document->showWatermarkImage = true;
        // Set some header informations for output
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
        ];	
		
        $html = view('inquiries.by_apr_no.printTax2', $data)->render();
        $document->WriteHTML($html);
         
        // Save PDF on your public storage 
        Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "S"));
         
        // Get file back from storage with the give header informations
        return Storage::disk('public')->download($documentFileName, 'Request', $header); //


    }
    

    public function getPageCount($keywords) 
    {
        return $this->InquiriesByArpNoRepository->listCount($keywords);
    }
    
    public function store(Request $request): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');
        if($request->paye_type == 1)
        {
            $rows = $this->InquiriesByArpNoRepository->validate($request->paye_type,$request->hr_employee_id,);
            if ($rows > 0) {
                return response()->json([
                    'title' => 'Oh snap!',
                    'text' => 'You cannot create a payee with an existing employee id.',
                    'type' => 'error',
                    'class' => 'btn-danger'
                ]);
            }
            $hr_employee_id=$request->hr_employee_id;
            $scp_id=NULL;
        }
        elseif($request->paye_type == 2)
        {
            $rows = $this->InquiriesByArpNoRepository->validate($request->paye_type,$request->scp_id,);
            if ($rows > 0) {
                return response()->json([
                    'title' => 'Oh snap!',
                    'text' => 'You cannot create a payee with an existing supplier id.',
                    'type' => 'error',
                    'class' => 'btn-danger'
                ]);
            }
            $scp_id=$request->scp_id;
            $hr_employee_id=NULL;  
        }
        else{
            $hr_employee_id=NULL;
            $scp_id=NULL;
        }

        $addr = array();
        $full_brgy_det=$this->InquiriesByArpNoRepository->brgyDetails($request->brgy_code);
        if ($request->paye_address_lotno !== NULL) { $addr[] = $request->paye_address_lotno; }
        if ($request->paye_address_street !== NULL) { $addr[] = $request->paye_address_street; }
        if ($request->paye_address_subdivision !== NULL) { $addr[] = $request->paye_address_subdivision; }
        if ($full_brgy_det !== NULL) { $addr[] = $full_brgy_det; }

        $details = array(
            'paye_type' => $request->paye_type,
            'scp_id' => $scp_id,
            'hr_employee_id' => $hr_employee_id,
            'paye_name' => $request->paye_name,
            'paye_address_lotno' => $request->paye_address_lotno,
            'paye_address_street' => $request->paye_address_street,
            'paye_address_subdivision' => $request->paye_address_subdivision,
            'paye_full_address' => implode(', ',$addr) .' '. trim($request->get('address')),
            'brgy_code' => $request->brgy_code,
            'paye_telephone_no' => $request->paye_telephone_no,
            'paye_mobile_no' => $request->paye_mobile_no,
            'paye_email_address' => $request->paye_email_address,
            'paye_fax_no' => $request->paye_fax_no,
            'paye_tin_no' => $request->paye_tin_no,
            'paye_remarks' => $request->paye_remarks,
            'paye_status' => 1,
            'created_at' => $this->carbon::now(),
            'paye_generated_by' => Auth::user()->id
        );

        return response()->json(
            [
                'data' => $this->InquiriesByArpNoRepository->create($details),
                'title' => 'Well done!',
                'text' => 'Payee has been created successfully.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function find(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'data' => $this->InquiriesByArpNoRepository->find($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {   
        // $this->validated('edit acctg account group submajor');

        // $rows = $this->InquiriesByArpNoRepository->validate($request->get('code'), $request->acctg_account_group_id, $request->acctg_account_group_major_id, $id);
        // if ($rows > 0) {
        //     return response()->json([
        //         'title' => 'Oh snap!',
        //         'text' => 'You cannot update a submajor account group with an existing code.',
        //         'type' => 'error',
        //         'class' => 'btn-danger'
        //     ]);
        // }
        $this->is_permitted('update');
        $o_data=$this->InquiriesByArpNoRepository->find($id);
        if($request->paye_type == 1)
        {
            if($o_data->hr_employee_id != $request->hr_employee_id)
            {
                $rows = $this->InquiriesByArpNoRepository->validate($request->paye_type,$request->hr_employee_id);
                if ($rows > 0) {
                    return response()->json([
                        'title' => 'Oh snap!',
                        'text' => 'You cannot update a payee with an existing employee id.',
                        'type' => 'error',
                        'class' => 'btn-danger'
                    ]);
                }
            }
           
            $hr_employee_id=$request->hr_employee_id;
            $scp_id=NULL;
        }
        elseif($request->paye_type == 2)
        {
            if($o_data->scp_id != $request->scp_id)
            {
                $rows = $this->InquiriesByArpNoRepository->validate($request->paye_type,$request->scp_id);
                if ($rows > 0) {
                    return response()->json([
                        'title' => 'Oh snap!',
                        'text' => 'You cannot update a payee with an existing supplier id.',
                        'type' => 'error',
                        'class' => 'btn-danger'
                    ]);
                }
            }
            $scp_id=$request->scp_id;
            $hr_employee_id=NULL;
        }
        else{
            $hr_employee_id=NULL;
            $scp_id=NULL;
        }

        $details = array(
            'paye_type' => $request->paye_type,
            'scp_id' => $scp_id,
            'hr_employee_id' => $hr_employee_id,
            'paye_name' => $request->paye_name,
            'paye_address_lotno' => $request->paye_address_lotno,
            'paye_address_street' => $request->paye_address_street,
            'paye_address_subdivision' => $request->paye_address_subdivision,
            'brgy_code' => $request->brgy_code,
            'paye_telephone_no' => $request->paye_telephone_no,
            'paye_mobile_no' => $request->paye_mobile_no,
            'paye_email_address' => $request->paye_email_address,
            'paye_fax_no' => $request->paye_fax_no,
            'paye_tin_no' => $request->paye_tin_no,
            'paye_remarks' => $request->paye_remarks,
            'paye_status' => 1,
            'updated_at' => $this->carbon::now(),
            'paye_modified_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->InquiriesByArpNoRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The Payee has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted('update');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'paye_modified_by' => Auth::user()->id,
            'paye_status' => 0
        );

        return response()->json([
            'data' => $this->InquiriesByArpNoRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The Payee has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function restore(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted('update');
        $details = array(
            'updated_at' => $this->carbon::now(),
            'paye_modified_by' => Auth::user()->id,
            'paye_status' => 1
        );

        return response()->json([
            'data' => $this->InquiriesByArpNoRepository->update($id, $details),
            'title' => 'Well done!',
            'text' => 'The Payee has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function fetch_group_code(Request $request)
    {   
        // $account = (!empty($request->get('account'))) ? $this->InquiriesByArpNoRepository->findAcctGrp($request->get('account'))->code : '';
        // $major = (!empty($request->get('major'))) ? $this->InquiriesByArpNoRepository->findMajorAcctGrp($request->get('major'))->prefix : '';
        // return response()->json([
        //     'account' => $account,
        //     'major' => $major
        // ]);
        return (new HrEmployee)->empDataById($request->emp_id);
    }
    public function  fetch_sup_data(Request $request)
    {   
        return (new GsoSupplierContactPerson)->supDataById($request->sup_id);
    }
   

    public function reload_major_account(Request $request, $account) 
    {   
        $this->validated('edit acctg account group submajor');
        return response()->json([
            'data' => $this->InquiriesByArpNoRepository->reload_major_account($account)
        ]);
    }
}
