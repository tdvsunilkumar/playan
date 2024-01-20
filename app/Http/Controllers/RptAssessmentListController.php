<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommonModelmaster;
use App\Models\BploApplication;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\RptProperty;
use App\Models\Barangay;
use App\Models\RptPropertyAppraisal;
use App\Models\RptPlantTreesAppraisal;
use App\Models\ProfileMunicipality;
use App\Models\RptPropertyApproval;
use App\Models\RevisionYear;
use App\Models\RptPropertyHistory;
use App\Models\RptPropertySworn;
use App\Models\RptPropertyStatus;
use App\Models\RptPropertyAnnotation;
use App\Models\RptLandUnitValue;
use App\Models\RptPlantTressUnitValue;
use App\Models\RptBuildingUnitValue;
use App\Models\RptAssessmentLevel;
use App\Models\RptBuildingFloorValue;
use App\Models\RptPropertyMachineAppraisal;
use App\Models\RptAssessmentList;
use App\Models\RptPropertyOwner;
use App\Models\RptCtoTaxRevenue;
use App\Models\User;
use App\Helpers\Helper;
use App\Http\Controllers\RptPropertyController;
use DB;
use Carbon\Carbon;

class RptAssessmentListController extends Controller
{
	 public $arrBarangay = [];
	  public $arrTaxDeclaration = [];
      private $slugs;
    public function __construct(){
		$this->_rptproperty = new RptProperty();
        $this->_revisionyear = new RevisionYear;
        $this->_muncipality = new ProfileMunicipality;
        $this->_barangay   = new Barangay;
        foreach ($this->_rptproperty->getTaxdecwithName() as $val) {
            $this->arrTaxDeclaration[$val->id]='['.$val->rp_tax_declaration_no.']=>['.$val->rpo_first_name.' '.$val->rpo_middle_name.' '.$val->rpo_custom_last_name.']';
        }foreach ($this->_muncipality->getRptActiveMuncipalityBarngyCodes() as $val) {
            $this->arrBarangay[$val->id]=$val->brgy_code.'-'.$val->brgy_name;
        }
        $this->slugs = 'rpt-assessment-list';
	}

	public function index(Request $request)
    { 
        $this->is_permitted($this->slugs, 'read');
    	$arrTaxDeclaration = $this->arrTaxDeclaration;
        $arrBarangay = $this->arrBarangay;
        return view('assessmentlist.index',compact('arrTaxDeclaration','arrBarangay'));
        
    }
    public function getList(Request $request){
        //$request->request->add(['property_kind' => $this->propertyKind]);
        $data=$this->_rptproperty->getAssessmentList($request);
        //dd($data); 
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0";    
        $count = $request->start+1;
        foreach ($data['data'] as $row){
            $arr[$i]['no']=$count;
            $arr[$i]['td_no']=$row->rp_tax_declaration_no;
            $taxpayer_name = wordwrap($row->taxpayer_name, 30, "<br />\n");
            $arr[$i]['taxpayer_name']="<div class='showLess'>".$taxpayer_name."</div>";
            $arr[$i]['brgy_name']=$row->brgy_name;
            $arr[$i]['class'] = $row->propertyKindDetails->pk_code.'-'.$row->class_for_kind->pc_class_code;
			 $arr[$i]['pin']=$row->rp_pin_declaration_no;
            $arr[$i]['effectivity']=$row->rp_app_effective_year;
            $arr[$i]['created_date']=date("d M, Y",strtotime($row->created_at));
            $reg_emp_name = wordwrap($row->reg_emp_name, 20, "<br />\n");
            $arr[$i]['reg_emp_name']="<div class='showLess'>".$reg_emp_name."</div>";
            // $rp_cadastral_lot_no = wordwrap($row->rp_cadastral_lot_no.",".$row->rp_building_cct_no.",".$row->rp_building_unit_no, 30, "<br />\n");
            $arr[$i]['rp_cadastral_lot_no']="<div class='showLess'>".$row->rp_lot_cct_unit_desc."</div>";
            $arr[$i]['market_value']=Helper::money_format($row->rp_market_value_adjustment);
            $arr[$i]['assessed_value']=Helper::money_format($row->rp_assessed_value);
            $uc_code = $row->updatecode->uc_code.'-'.$row->updatecode->uc_description;
            // $uc_code = wordwrap($row->updatecode->uc_code.'-'.$row->updatecode->uc_description, 2, "<br />\n");
            $arr[$i]['uc_code']="<span class='showLess2'>".$uc_code."</span>";
            $arr[$i]['pk_is_active'] = ($row->pk_is_active == 1 ? '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>');
            // $arr[$i]['action']='
            //     <div class="action-btn bg-info ms-2">
            //         <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptproperty/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Update Application">
            //             <i class="ti-pencil text-white"></i>
            //         </a>
            //     </div><div class="action-btn bg-info ms-2">
            //             <a href="#" title="Print Payment"  data-title="Print Payment" class="mx-3 btn btn-sm print align-items-center" id="'.$row->id.'">
            //                 <i class="ti ti-printer text-white"></i>
            //             </a>
            //      </div>';
            //$arr[$i]['action']  = $this->updateCodeSelectList($row->id); 
                 if($row->pk_is_active == 1){
                 $arr[$i]['action']  = '
                    <div class="action-btn bg-primary ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="print" data-propertyid="'.$row->id.'"  data-size="xxll"  title="Print Tax Declaration"  data-title="Print Tax Declaration">

                            <i class="ti-printer text-white"></i>
                        </a>
                    </div>
                    <div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="printfaas" data-propertyid="'.$row->id.'"  data-size="xxll"  title="Print FAAS"  data-title="Print FAAS">

                            <i class="ti-printer text-white"></i>
                        </a>
                    </div>
                    <div class="action-btn bg-info ms-2">
                       
                       <a href="' . route('realpropertytaxpayerfile.printbill', ['id' => $row->rpo_code]) . '" target="_blank" class="mx-3 btn btn-sm align-items-center realpropertyaction" data-actionname="da" data-tax="' . $row->rp_tax_declaration_no . '" data-propertyid="' . $row->id . '" data-count="' . $count . '" data-size="xxll" title="Tax Order Of Payment(RPTOP)" data-title="Tax Order Of Payment(RPTOP)">
                        <i class="ti-receipt text-white"></i>
                    </a>
                    </div>';
                    }
                    
                    else{
                         $arr[$i]['action']  = '
                    <div class="action-btn bg-primary ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="print" data-propertyid="'.$row->id.'"  data-size="xxll"  title="Print Tax Declaration"  data-title="Print Tax Declaration">

                            <i class="ti-printer text-white"></i>
                        </a>
                    </div>
                    <div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center realpropertyaction" data-actionname="printfaas" data-propertyid="'.$row->id.'"  data-size="xxll"  title="Print FAAS"  data-title="Print FAAS">

                            <i class="ti-printer text-white"></i>
                        </a>
                    </div>';
                    }
                   
            $i++;
            $count++;
        }
        
        $totalRecords = $data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }
}
