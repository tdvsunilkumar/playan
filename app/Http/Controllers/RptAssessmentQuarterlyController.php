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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataTableExport;
class RptAssessmentQuarterlyController extends Controller
{
    public $arrBarangay = [];
    public $arrTaxDeclaration = [];
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
    }

    public function index(Request $request)
    { 
        $arrTaxDeclaration = $this->arrTaxDeclaration;
        $arrBarangay = $this->arrBarangay;
        return view('rptassquarterly.index',compact('arrTaxDeclaration','arrBarangay'));
        
    }
    public function getList(Request $request){
        $data=$this->_rptproperty->getQtrlyAssessmentList($request);
        //dd($data); 
		$arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
			$sr_no=$sr_no+1;
            $arr[$i]['no']=$sr_no;
            $arr[$i]['class']=$row->pc_class_description;
            $arr[$i]['units']=Helper::decimal_format($row->realpropertyUnits);
            $arr[$i]['landarea']=number_format($row->totalAreaInSqm,3).' Sq. m.';
            $arr[$i]['land_market_value'] = Helper::decimal_format($row->landMarketValue);
            $arr[$i]['build_market_value_less'] = Helper::decimal_format($row->buildingMarketValueLess);
            $arr[$i]['build_market_value_above'] = Helper::decimal_format($row->buildingMarketValueabove);
            $arr[$i]['machine_market_value'] = Helper::decimal_format($row->machineMarketValue);
            $arr[$i]['other_mvalue'] = Helper::decimal_format('0.00');
            $arr[$i]['total_market_value'] = Helper::decimal_format($row->totalMarketValue);
            $arr[$i]['land_assess_value'] = Helper::decimal_format($row->landAssessedValue);
            $arr[$i]['build_assess_value_less'] = Helper::decimal_format($row->buildingAssessedValueLess);
            $arr[$i]['build_assess_value_above'] = Helper::decimal_format($row->buildingAssessedValueAbove);
            $arr[$i]['machine_assess_value'] = Helper::decimal_format($row->machineAssessedValue);
            $arr[$i]['other_avalue'] = Helper::decimal_format('0.00');
            $arr[$i]['total_assess_value'] = Helper::decimal_format($row->totalAssessedValue);
            $i++;
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






