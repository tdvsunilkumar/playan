<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\AccountRreceivablesPropertyController;
use App\Helpers\Helper;
use App\Http\Controllers\BillingFormController;
use App\Http\Controllers\RptPropertyMachineryController;
use Illuminate\Http\Request;

class RptProperty extends Model
{
    use HasFactory;

    public $activeMuncipality = [];

    public $activeBarangay    = [];

    public $revisionYear    = [];

    protected $fillable = [
        
        'rp_property_code',
        'pk_id',
        'rvy_revision_year_id',
        'rvy_revision_code',
        'brgy_code_id',
        'brgy_no',
        'rp_td_no',
        'rp_suffix',
        'rp_tax_declaration_no',
        'loc_local_code',
        'dist_code',
        'rp_section_no',
        'rp_pin_no',
        'rp_pin_suffix',
        'rp_oct_tct_cloa_no',
        'rp_cadastral_lot_no',
        'rpo_code',
        'rp_administrator_code',
        'loc_group_brgy_no',
        'rp_location_number_n_street',
        'rp_bound_north',
        'rp_bound_south',
        'rp_bound_east',
        'rp_bound_west',
        'uc_code',
        'rp_app_taxability',
        'rp_app_effective_year',
        'rp_app_effective_quarter',
        'rp_app_posting_date',
        'rp_app_memoranda',
        'rp_app_extension_section',
        'rp_app_assessor_lot_no',
        'rp_code_lref',
        'rp_td_no_lref',
        'rp_suffix_lref',
        'rp_section_no_lref',
        'rp_pin_no_lref',
        'rp_pin_suffix_lref',
        'rpo_code_lref',
        'rp_oct_tct_cloa_no_lref',
        'rp_cadastral_lot_no_lref',
        'rp_total_land_area',
        'rp_code_bref',
        'rp_section_no_bref',
        'rp_pin_no_bref',
        'rbf_building_roof_desc1',
        'rbf_building_roof_desc2',
        'rbf_building_roof_desc3',
        'rbf_building_floor_desc1',
        'rbf_building_floor_desc2',
        'rbf_building_floor_desc3',
        'rbf_building_wall_desc1',
        'rbf_building_wall_desc2',
        'rbf_building_wall_desc3',
        'bk_building_kind_code',
        'pc_class_code',
        'rp_bulding_permit_no',
        'rp_building_name',
        'rp_building_cct_no',
        'rp_building_unit_no',
        'rp_building_age',
        'rp_building_no_of_storey',
        'rp_constructed_month',
        'rp_constructed_year',
        'rp_occupied_month',
        'rp_occupied_year',
        'rp_building_completed_year',
        'rp_building_completed_percent',
        'rp_building_gf_area',
        'rp_building_total_area',
        'rp_depreciation_rate',
        'rp_accum_depreciation',
        'rpb_accum_deprec_market_value',
        'al_assessment_level',
        'rpb_assessed_value',
        'rp_modified_by',
        'rp_registered_by',
        'rp_is_active'
    ];

    protected $table = 'rpt_properties';

    protected $with = ['propertyOwner'];

    protected $appends = ['barangay_details','landAppraisals_details', 'revision_year_code','property_owner_details','property_admin_details','total_land_area','td_no','taxpayer_name','pin','market_value','assessed_value','pc_class_description','complete_pin','treasurer_name','market_value_for_all_kind','assessed_value_for_all_kind','class_for_kind','basic_due','sef_due','sh_due','total_due','administrator_name'];


    public function __construct(){
        $munciObject             = new ProfileMunicipality;
        $revYear                 = new RevisionYear;
        $this->revisionYear      = $revYear->getActiveRevisionYear();
        $this->activeBarangay    = $munciObject->getRptActiveMuncipalityBarngyCodes();
        $this->activeMuncipality = $munciObject->getActiveMuncipalityCode();
    }

    public function insertNewArrayItem($array = '',$after = '',$newKey = ''){
        $index = array_search($after,array_keys($array));
        $res = array_slice($array, 0, $index, true) + array($newKey => "") + array_slice($array, $index, count($array)-$index, true);
        return $res;
    }

    public function setValueToCommonColumns($data = [],$pkId='',$status = 1){

        if($pkId == 2){
            $preSelectedBrgy = (session()->has('landSelectedBrgy'))?session()->get('landSelectedBrgy'):0;
        }if($pkId == 1){
            $preSelectedBrgy = (session()->has('buildingSelectedBrgy'))?session()->get('buildingSelectedBrgy'):0;
        }if($pkId == 3){
            $preSelectedBrgy = (session()->has('machineSelectedBrgy'))?session()->get('machineSelectedBrgy'):0;
        }
        if($preSelectedBrgy == 0 || $this->activeMuncipality == null){
            return [];
        }
        $loacality = DB::table('rpt_locality')->where('department',1)->where('mun_no',$this->activeMuncipality->id)->first();
        if($loacality == null){
            return [];
        }
        $brgyToUse = $this->activeBarangay->where('id',$preSelectedBrgy)->first();
        $distDetails = DB::table('rpt_district')->where('id',(isset($brgyToUse->dist_code))?$brgyToUse->dist_code:0)->first();
        if($distDetails == null){
            return [];
        }
        $revisionYear = $this->revisionYear;
        if($revisionYear == null){
            return [];
        }
        unset($data['id']);
        unset($data['rp_property_code']);
        unset($data['rp_tax_declaration_no']);
        unset($data['rp_td_no']);
        unset($data['loc_local_code_name']);
        unset($data['dist_code_name']);
        unset($data['brgy_code_and_desc']);
        unset($data['update_code']);
        unset($data['property_owner_address']);
        unset($data['rp_administrator_code_address']);
        unset($data['created_against']);
        unset($data['rp_assessed_value']);


        if($pkId == 1){
            $data = $this->insertNewArrayItem($data,'rp_constructed_month','rp_constructed_year');
            $data = $this->insertNewArrayItem($data,'rp_occupied_month','rp_occupied_year');
            unset($data['land_owner']);
            unset($data['land_location']);
            unset($data['rp_oct_tct_cloa_no']);
            unset($data['rp_cadastral_lot_no']);
            unset($data['rp_total_land_area']);
            unset($data['is_manual_permit']);
            unset($data['permit_id']);
            unset($data['rp_td_no_lref']);
            unset($data['rp_suffix_lref']);
            unset($data['rp_oct_tct_cloa_no_lref']);
            unset($data['rpo_code_lref']);
            unset($data['rp_cadastral_lot_no_lref']);
            unset($data['rp_building_no_of_storey']);
            unset($data['rp_building_gf_area']);
            unset($data['rp_building_total_area']);
            unset($data['rp_depreciation_rate']);
            unset($data['rp_accum_depreciation']);
            unset($data['rpb_accum_deprec_market_value']);
            unset($data['al_assessment_level']);
            unset($data['rpb_assessed_value']);
            unset($data['bk_building_kind_code']);
            unset($data['rp_building_age']);
            unset($data['rp_building_completed_percent']);
            $data['rp_app_appraised_by'] = '';
            $data['rp_app_recommend_by'] = '';
            $data['rp_app_approved_by'] = '';
            $data['client_name'] = '';
            $data['employee_name'] = '';
            $data['roof_description'] = '';
            $data['floor_description'] = '';
            $data['wall_description'] = '';
            $data['land_reference'] = '';
            $data['class'] = '';
            $data['kinds'] = '';
            $data['previous_owner_reference_tds'] = '';
            $data = $this->insertNewArrayItem($data,'client_name','previous_owner_reference');
            $data = $this->insertNewArrayItem($data,'pc_class_code','bk_building_kind_code');
       }
       if($pkId == 2){
        $data['rp_app_appraised_by'] = '';
        $data['rp_app_recommend_by'] = '';
        $data['rp_app_approved_by'] = '';
        $data['client_name'] = '';
        $data['employee_name'] = '';
        $data['previous_owner_reference_tds'] = '';
        $data = $this->insertNewArrayItem($data,'client_name','previous_owner_reference');
       }
       if($pkId == 3){
        unset($data['land_owner']);
        unset($data['rp_section_no_bref']);
        unset($data['rp_pin_no_bref']);
        unset($data['rp_pin_suffix_bref']);
        unset($data['rp_section_no_lref']);
        unset($data['rp_pin_no_lref']);
        unset($data['building_owner']);
        unset($data['rp_pin_suffix_lref']);
        unset($data['rpo_code_lref']);
        $data['rp_app_appraised_by'] = '';
        $data['rp_app_recommend_by'] = '';
        $data['rp_app_approved_by'] = '';
        $data['client_name'] = '';
        $data['employee_name'] = '';
        $data['land_reference'] = '';
        $data['building_reference'] = '';
        $data['classes'] = '';
        $data['previous_owner_reference_tds'] = '';
        $data = $this->insertNewArrayItem($data,'client_name','previous_owner_reference');
        }
        $data['rvy_revision_year_id'] = $revisionYear->id;
        $data['pk_id'] = $pkId;
        $data['brgy_code_id'] = $preSelectedBrgy;
        $data['loc_local_code'] = $loacality->loc_local_code;
        $data['dist_code'] = (isset($distDetails->dist_code))?$distDetails->dist_code:'';;
        $data['loc_group_brgy_no'] = (isset($brgyToUse->brgy_name))?$brgyToUse->brgy_name:'';
        //dd($data);
        return $data;
    }
    public function updateData($id,$columns, $updateCode = ''){
        return DB::table('rpt_properties')->where('id',$id)->update($columns);
    }

    public function checkSHTandSEFEligibility($value=''){
        dd($this->revisionYearDetails);
    }
    public function getRefreshEmployee(){
         return DB::table('hr_employees')->select('*')->orderBy('id','DESC')->get();
    }
    public function allRptProperty($vars = ''){
        $rpt_properties = self::where('pk_id',1)->where('pk_is_active',1)->where('is_deleted',0)->orderBy('id')->get();
        $items = array();
        if (!empty($vars)) {
            $items[] = array('' => 'select a '.$vars);
        } else {
            $items[] = array('' => 'Please select...');
        }
        foreach ($rpt_properties as $rpt_property) {
            $items[] = array(
                $rpt_property->id => $rpt_property->rp_tax_declaration_no
            );
        }
  
        $data = array();
        foreach($items as $item) {
            foreach($item as $key => $val) {
                $data[$key] = $val;
            }
        }
  
        return $data;
   }
   /*public function find($id){
    return DB::table('rpt_properties AS rp')
           ->leftJoin('clients AS cc', 'cc.id', '=', 'rp.rpo_code')
           ->where('rp.id',$id)->select('rp.*','cc.full_name','cc.rpo_custom_last_name','cc.rpo_first_name','cc.rpo_middle_name','cc.suffix')->first();
   }*/
    public function getEmployee(){
         return DB::table('hr_employees')->select('*')->join('rpt_appraisers as ra',function($j){
            $j->on('ra.ra_appraiser_id','=','hr_employees.id')->where('ra.ra_is_active',1);
        })->groupBy('ra.ra_appraiser_id')->get();
    }
    public function getclients(){
         return DB::table('clients')->select('id','full_name','rpo_custom_last_name','rpo_first_name','rpo_middle_name','suffix')->where('is_rpt',1)->where('is_active',1)->get();
    }
    public function getctoCashier(){
         return DB::table('cto_cashier')->select('id','cashier_or_date','ctc_place_of_issuance','or_no')->where('payee_type',1)->get();
    }
    public function getctoCashierDetails($id){
         return DB::table('cto_cashier as cto')
                    ->select('cto.id','cto.cashier_or_date','cto.ctc_place_of_issuance','cto.or_no')->where('cto.payee_type',1)->where('cto.status',1)->where('cto.tfoc_is_applicable',7)->where('cto.client_citizen_id',$id)->get();
    }
    public function getctoCashierIsseueanceDetails($id){
          return DB::table('cto_cashier as cto')
                    ->leftjoin('cto_cashier_details AS ctod', 'ctod.cashier_id', '=', 'cto.id')
                    ->select('cto.id','cto.cashier_or_date','cto.ctc_place_of_issuance','cto.or_no','ctod.id as ctodetailsId')->where('cto.payee_type',1)->where('cto.id',$id)->first();
    }
    public function getTaxDeclaresionNODetails($id,$brgy_code_id,$rvy_revision_year_id){
          $queryObj =  DB::table('rpt_properties')
                    ->select('id','rp_td_no','rp_tax_declaration_no')
                    ->where('brgy_code_id',$brgy_code_id)
                    ->where('rvy_revision_year_id',$rvy_revision_year_id)
                    ->where('pk_id',config('constants.rptKinds.L'))
                    ->where('pk_is_active',1)
                    ->where('is_deleted',0);
        if($id != ''){
            $queryObj->where('rpo_code',$id);
        }
        return $queryObj->get();
    }
    public function getBuildTaxDeclaresionNODetails($id,$brgy_code_id,$rvy_revision_year_id){
          return DB::table('rpt_properties')
                    ->select('id','rp_td_no','rp_tax_declaration_no')
                    ->where('rpo_code',$id)
                    ->where('brgy_code_id',$brgy_code_id)
                    ->where('rvy_revision_year_id',$rvy_revision_year_id)
                    ->where('pk_id',config('constants.rptKinds.B'))
                    ->where('pk_is_active',1)
                    ->where('is_deleted',0)
                    ->get();
    }
    public function getTaxDeclaresionNOBuildingDetails($id,$brgy_code_id,$rvy_revision_year_id){
          return DB::table('rpt_properties')
                    ->select('id','rp_td_no','rp_tax_declaration_no')->where('rpo_code',$id)->where('brgy_code_id',$brgy_code_id)->where('rvy_revision_year_id',$rvy_revision_year_id)->where('pk_id',1)->where('pk_is_active',1)->where('is_deleted',0)->get();
    }
    public function getBuildReferencesForMachine($brgy,$revisionyear){
          $sql =  DB::table('rpt_properties')
                    ->select('id','rp_td_no','rp_tax_declaration_no')->where('pk_id',1)->where('pk_is_active',1)->where('is_deleted',0);
          if($revisionyear != null){
            $sql->where('rvy_revision_year_id',$revisionyear);
          }
          if($brgy != null){
            $sql->where('brgy_code_id',$brgy);
          }
          return $sql->get();
    }
    public function getLandReferencesForMachine($brgy,$revisionyear){
          $sql =  DB::table('rpt_properties')
                    ->select('id','rp_td_no','rp_tax_declaration_no','brgy_code_id')
                    ->where('pk_id',2)
                    ->where('pk_is_active',1)
                    ->where('is_deleted',0);
          if($revisionyear != null){
            $sql->where('rvy_revision_year_id',$revisionyear);
          }
          if($brgy != null){
            $sql->where('brgy_code_id',$brgy);
          }
          return  $sql->get();
    }
    public function getpreviousOwnerRefrences($brgy,$revisionyear,$pkId){
           $sql =   DB::table('rpt_properties')
                    ->select('id','rp_td_no','rp_tax_declaration_no')->where('pk_id',$pkId)->where('pk_is_active',1)->where('is_deleted',0);
           if($revisionyear != null){
            $sql->where('rvy_revision_year_id',$revisionyear);
          }
          if($brgy != null){
            $sql->where('brgy_code_id',$brgy);
          }
          return  $sql->get();
    }

       public function getRelevantRpCodes($propCode = '',$rpCode = ''){
        $initialId = $rpCode;
        $chain = [];
        $rpCodes = DB::table('rpt_properties as rp')
                          ->select('rp.id','rp.pk_is_active','rp.created_against',
                            DB::raw('CASE WHEN rp.pk_is_active = 9 THEN rp.created_against ELSE rpa.rp_app_cancel_by_td_id END as rp_app_cancel_by_td_id')
                           )
                          ->join('rpt_property_approvals as rpa','rpa.rp_code','=','rp.id')
                          ->where(DB::raw('CASE WHEN rp.uc_code = 7 THEN rp.rp_property_code_new ELSE rp.rp_property_code END'),$propCode)
                          ->whereIn('rp.pk_is_active',[1,0])
                          ->orderBy('rp.id','desc')
                          ->get();
                          /*dd($this->findChain($rpCodes,305));*/
                         // dd($rpCodes->toArray());
        foreach ($rpCodes as $key => $value) {
            $id = $value->id;
            $rp_app_cancel_by_td_id = explode(",",$value->rp_app_cancel_by_td_id);
            if($rpCode == $id){
                $chain[]   = $rpCode;
            }
            if(in_array($initialId, $rp_app_cancel_by_td_id)){
                $chain[]   = $id;
                $initialId = $id;
                
            }
        }
        $previousOwners = DB::table('rpt_properties as rp')->select('id')->whereIn('created_against',$chain)->where('pk_is_active',9)->pluck('id');
        $newData = collect($chain)->merge($previousOwners)->sort();
        return $chain;
        //dd($newData);
    }

    public function updateChain($id = ''){
        $prop = DB::table('rpt_properties')->select('rp_property_code','created_against','id')->where('id',$id)->first();
        $accountReceiabable = DB::table('cto_accounts_receivables')->select('rp_code_chain')->where('rp_code',$prop->created_against)->first();
        $dataInARray = json_decode($accountReceiabable->rp_code_chain);
        $dataInARray[] = $prop->id;
        DB::table('cto_accounts_receivables')->where('rp_code',$prop->created_against)->update([
            'rp_code_chain'=>json_encode($dataInARray),
            'updated_at' => date("Y-m-d H:i:s"),
            'updated_by' => \Auth::user()->id,
        ]);
    }

    public function updateAccountReceiaveableDetails($id = '',$flag = false){
        $data = DB::table('rpt_properties')
                     ->leftJoin('rpt_property_kinds AS pk', 'pk.id', '=', 'rpt_properties.pk_id')
                     ->leftJoin('rpt_property_appraisals', 'rpt_property_appraisals.rp_code', '=', 'rpt_properties.id')
                     ->leftJoin('rpt_property_machine_appraisals as ma', 'ma.rp_code', '=', 'rpt_properties.id')
                     ->select('rpt_properties.rpo_code','rpt_properties.rp_app_effective_year','rpt_properties.rp_property_code','rpt_properties.id','rpt_properties.rvy_revision_year_id','rpt_properties.brgy_code_id','rpt_properties.pk_id',
                DB::raw("CASE 
                            WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_assessed_value)) 
                            WHEN pk.pk_code = 'B' THEN SUM(COALESCE(rpt_properties.rpb_assessed_value)) 
                            WHEN pk.pk_code = 'M' THEN SUM(COALESCE(ma.rpm_assessed_value)) 
                            END as assessedValue")
                         )
                     ->where('rpt_properties.id',$id)->first();
                     //dd($data);
        $receeableTotalRecordsObj = DB::table('cto_accounts_receivables')->where('pcs_id',2);             
        $alreadyExistOrNot = $receeableTotalRecordsObj->where('rp_property_code',$data->rp_property_code)->where('rp_code',$data->id)->first();
        $dataToUpdate = [
            'rp_assessed_value' => $data->assessedValue,
            'is_active' => 1,
            'updated_at' => date("Y-m-d H:i:s"),
            'updated_by' => \Auth::user()->id,
        ];
        if($flag){
            $dataToUpdate['is_active'] = 0;
            $this->updateCtoReceiveables($data->id,false);
        }
        //dd($dataToUpdate);
        DB::table('cto_accounts_receivables')->where('rp_code',$data->id)->update($dataToUpdate);             

    }

    public function updateCtoReceiveables($id = '',$flag = false){
        $query = DB::table('cto_receivables')->where('application_id',$id)->where('pcs_id',2);
        if($query->count() > 0){
        if(!$flag){
            $dataToUpdate = [
                'is_active' => 0,
                'updated_at' => date("Y-m-d H:i:s"),
                'updated_by' => \Auth::user()->id,
            ];
        }if($flag){
            $dataToUpdate = [
                'is_active' => 1,
                'updated_at' => date("Y-m-d H:i:s"),
                'updated_by' => \Auth::user()->id,
            ];
        }
        $query->update($dataToUpdate);
    }
        
    }

    public function disableDirectCancellation($id = ''){
        /*$propDetailsObj = DB::table('rpt_property_approvals')->where('rp_code',$id)->first();
        $propDetailsObj*/
        $dataToUpdateInApproval = [
            'rp_app_cancel_is_direct' => 0,
            'rp_app_cancel_type' => '',
            'rp_app_cancel_date' => '',
            'rp_app_cancel_by_td_id' => '',
            'rp_app_cancel_remarks' => ''
        ];
         DB::table('rpt_property_approvals')->where('rp_code',$id)->update($dataToUpdateInApproval);
         DB::table('cto_accounts_receivables')->where('rp_code',$id)->update(
            [
                'is_active'=>1,
                'updated_at' => date("Y-m-d H:i:s"),
                'updated_by' => \Auth::user()->id,
            ]
        );
         $this->updateCtoReceiveables($id,true);
    }

    public function makeMemorandaEmpty($id=''){
        DB::table('rpt_properties')->where('id',$id)->update(['rp_app_memoranda' => '']);
    }

    public function addDataInAccountReceivable($id = '',$parentId = '',$updateCode = '',$csTds = [], $prevChain = []){
        $data = DB::table('rpt_properties')
                     ->leftJoin('rpt_property_kinds AS pk', 'pk.id', '=', 'rpt_properties.pk_id')
                     ->leftJoin('rpt_property_appraisals', 'rpt_property_appraisals.rp_code', '=', 'rpt_properties.id')
                     ->leftJoin('rpt_property_machine_appraisals as ma', 'ma.rp_code', '=', 'rpt_properties.id')
                     ->select('rpt_properties.rpo_code','rpt_properties.rp_app_effective_year','rpt_properties.rp_property_code','rpt_properties.id','rpt_properties.rvy_revision_year_id','rpt_properties.brgy_code_id','rpt_properties.pk_id',
                DB::raw("CASE 
                            WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_assessed_value)) 
                            WHEN pk.pk_code = 'B' THEN SUM(COALESCE(rpt_properties.rpb_assessed_value)) 
                            WHEN pk.pk_code = 'M' THEN SUM(COALESCE(ma.rpm_assessed_value)) 
                            END as assessedValue")
                         )
                     ->where('rpt_properties.id',$id)->first();

        $chain = $this->getRelevantRpCodes($data->rp_property_code,$data->id);  
        //dd($chain);           
        $receeableTotalRecordsObj = DB::table('cto_accounts_receivables')->where('pcs_id',2);
        $arNo = $receeableTotalRecordsObj->count()+1;
        if($parentId > 0){
            $alreadyExistOrNot = $receeableTotalRecordsObj->where('rp_code',$parentId)->first();
        }else{
            $alreadyExistOrNot = $receeableTotalRecordsObj->where('rp_code',$parentId)->first();
        }
        $controlNo = date("Y")."-".str_pad($arNo, 5, '0', STR_PAD_LEFT);
        $dataToSave = [
            'ar_year' => date("Y"),
            'ar_month' => date("m"),
            'ar_no' => $arNo,
            'ar_control_no' => $controlNo,
            'ar_date' => date("Y-m-d"),
            'payee_type' => 1,
            'taxpayer_id' => $data->rpo_code,
            'pcs_id' => 2,
            'effectivity_year' => $data->rp_app_effective_year,
            'rp_property_code' => $data->rp_property_code,
            'rp_code' => $data->id,
            'pk_id' => $data->pk_id,
            'rvy_revision_year_id' => $data->rvy_revision_year_id,
            'brgy_code_id' => $data->brgy_code_id,
            'rp_assessed_value' =>  $data->assessedValue,
            'rp_code_chain' => json_encode($chain),
            'status' => 0,
            'created_by' => \Auth::user()->id,
            'updated_by' => \Auth::user()->id,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ];

        if($alreadyExistOrNot == null && $updateCode == ''){
            $allModes = Helper::billing_quarters();
            $sdModes = array_keys($allModes);
            DB::table('cto_accounts_receivables')->insert($dataToSave);
            $lastInsertId = DB::getPdo()->lastInsertId();
            //dd($lastInsertId);
        }else if($alreadyExistOrNot == null && $updateCode == 'SD'){
            $newArray = array_merge($prevChain,$chain);
            $collapsedArray = collect($newArray)->sort()->unique()->toArray();
            $dataToSave['rp_code_chain']  = json_encode(array_values($collapsedArray));
            DB::table('cto_accounts_receivables')->insert($dataToSave);
            $lastInsertId = DB::getPdo()->lastInsertId();
            $this->updateCtoReceiveables($parentId,false);
        }else if($alreadyExistOrNot != null && $updateCode == 'CS' && !empty($csTds)){
            $previousChain = json_decode($alreadyExistOrNot->rp_code_chain);
            $previousChain[] = (int)$data->id;
            $chainForCs = [];
            //dd($csTds);
            foreach ($csTds as $trd) {
                $propData = DB::table('cto_accounts_receivables')->select('rp_code_chain')->where('rp_code',$trd)->first();
                $chainForCsData = json_decode($propData->rp_code_chain);  
               // dd($chainForCsData);
                $chainForCs[] = $chainForCsData;
            }

            $collapsedArray = collect($chainForCs)->collapse();
            $mergeWithChain = $collapsedArray->merge(collect($previousChain))->sort()->unique()->toArray();
            foreach ($csTds as $key => $value) {
                $previousChain[] = $value;
                if($key == 0){
                    $dataToUpdate = [
                'taxpayer_id' => $data->rpo_code,
                'effectivity_year' => $data->rp_app_effective_year,
                'rp_code' => $data->id,
                'rp_property_code' => $data->rp_property_code,
                'rvy_revision_year_id' => $data->rvy_revision_year_id,
                'rp_assessed_value' => $data->assessedValue,
                'rp_code_chain' => json_encode(array_values($mergeWithChain)),
                'updated_at' => date("Y-m-d H:i:s"),
                'updated_by' => \Auth::user()->id,
            ];
            DB::table('cto_accounts_receivables')->where('rp_code',$value)->update($dataToUpdate);
                }else{
                    $cancelDataToUpdate = [
                'is_active' => 0,
                'updated_at' => date("Y-m-d H:i:s"),
                'updated_by' => \Auth::user()->id,
            ];
            DB::table('cto_accounts_receivables')->where('rp_code',$value)->update($cancelDataToUpdate);
            DB::table('cto_accounts_receivable_details')->where('rp_code',$value)->update(['rp_property_code'=>$data->rp_property_code]);
                }
                $this->updateCtoReceiveables($value,false);
            }

        }else{
            $previousChain = json_decode($alreadyExistOrNot->rp_code_chain);
            $newArray = array_merge($previousChain,$chain);
            $collapsedArray = collect($newArray)->sort()->unique()->toArray();
            $dataToUpdate = [
                'taxpayer_id' => $data->rpo_code,
                'effectivity_year' => $data->rp_app_effective_year,
                'rp_code' => $data->id,
                'rvy_revision_year_id' => $data->rvy_revision_year_id,
                'rp_assessed_value' => $data->assessedValue,
                'rp_code_chain' => json_encode(array_values($collapsedArray)),
                'updated_at' => date("Y-m-d H:i:s"),
                'updated_by' => \Auth::user()->id,
            ];
            DB::table('cto_accounts_receivables')->where('id',$alreadyExistOrNot->id)->update($dataToUpdate);
            $this->updateCtoReceiveables($parentId,false);
        }
    }

    public function getTaxDeclaresionNODetailsAll($brgy_code_id,$rvy_revision_year_id){
          return DB::table('rpt_properties')
                    ->select('id','rp_tax_declaration_no')->where('brgy_code_id',$brgy_code_id)->where('rvy_revision_year_id',$rvy_revision_year_id)->where('pk_id',2)->where('pk_is_active',1)->where('is_deleted',0)->get();
    }
    public function getTaxdeclarationland(){
        return DB::table('rpt_properties')
                    ->select('id','rp_td_no','rp_tax_declaration_no')->where('pk_id',2)->where('is_deleted',0)->where('pk_is_active',1)
                    ->orderBy('id','desc')
                    ->limit(50)
                    ->get();
    }
    public function getTaxdeclarationbuilding(){
         return DB::table('rpt_properties')
                    ->select('id','rp_td_no','rp_tax_declaration_no')->where('pk_id',1)->where('is_deleted',0)->where('pk_is_active',1)->get();
    }

    public function getClientDetails($clientId){
        return DB::table('clients AS c')
               ->select('c.id','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.suffix')->where('c.id',(int)$clientId)->get();
    }
    public function getClientAll(){
        return DB::table('clients AS c')
               ->select('c.id','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.suffix')->where('c.is_rpt',1)->get();
    }
    public function gettaxDetails($id){
          return DB::table('rpt_properties as rptp')
                    ->join('barangays AS b', 'b.id', '=', 'rptp.brgy_code_id')
                    ->join('rpt_locality AS l', 'l.mun_no', '=', 'b.mun_no')
                    ->join('clients AS c', 'c.id', '=', 'rptp.rpo_code')
                    ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rptp.id')
                    ->select(
                        'rptp.id',
                        'c.id as rpo_code',
                        'c.full_name',
                        'c.rpo_custom_last_name',
                        'c.rpo_first_name',
                        'c.rpo_middle_name',
                        'c.suffix',
                        'l.loc_local_name',
                        'rptp.rp_cadastral_lot_no',
                        DB::raw("SUM(COALESCE(rpt_property_appraisals.rpa_total_land_area)) as rp_total_land_area"),
                        'rptp.rp_section_no',
                        'rptp.rp_app_assessor_lot_no',
                        'rptp.rp_pin_declaration_no as rp_pin_no',
                        'rptp.rp_suffix as rp_suffix_lref',
                        'rptp.rp_td_no as rp_td_no_lref',
                        'rptp.rp_oct_tct_cloa_no as rp_oct_tct_cloa_no_lref',
                        'rptp.rp_section_no as rp_section_no_for_build',
                        'rptp.rp_pin_no as rp_pin_no_for_build',
                    )->where('l.department',1)->where('rptp.id',$id)->first();
    }
    public function gettaxDetailsId($id){
          return DB::table('rpt_properties')
                    ->select('id','rp_td_no','rp_tax_declaration_no')
                    ->where('id',$id)
                    ->get();
    }

    
    public function getPropertydoclinkbyid($id){
       return DB::table('rpt_properties_doc_link')->where('rp_property_code',$id)->where('type','2')->get()->toArray();
    }
    public function getRpCodebyid($id){
            return DB::table('rpt_properties')
                    ->where('id',$id)
                    ->first();
    }
    public function deletePropertydocbyid($id){
        DB::table('rpt_properties_doc_link')->where('id',$id)->delete();
    }
    public function getPropertydocbyidtodelete($id){
        return DB::table('rpt_properties_doc_link')->where('id',$id)->where('type','1')->first();
    }
    public function getPropertydocbyid($id){
       return DB::table('rpt_properties_doc_link')->where('rp_property_code',$id)->where('type','1')->get()->toArray();
    }
    public function AddPropertydoclinkData($postdata){
        DB::table('rpt_properties_doc_link')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function UpdatePropertydoclinkData($id,$columns){
        return DB::table('rpt_properties_doc_link')->where('id',$id)->update($columns);
    }
    public function basicFeeRates(){
        $billObj = new RptCtoBilling;
        //dd($this->class_for_kind);
        if($this->class_for_kind != null){
            return $billObj->getBasicRates($this->class_for_kind->id);
        }else{
            return [];
        }
        
    }

    public function checkLastPaidTax($id = ''){
        $rpPropCOdesArray = [];
        if(is_array($id)){
            $propData = [];
            $rpPropCOdesArray = DB::table('rpt_properties')->whereIn('id',$id)->pluck('rp_property_code')->toArray();
        }else{
            $propData = $this->find($id);
        }
        
        $data = DB::table('cto_cashier_details as ccd')
                    ->select(
                        DB::raw('MAX(rcbd.cbd_covered_year) as lastPaymentYear')
                    )
                    ->where('ccd.tfoc_is_applicable',2)
                    ->join('rpt_cto_billing_details as rcbd',function($j)use($propData,$id,$rpPropCOdesArray){
                        $j->on('rcbd.id','=','ccd.cbd_code');

                        if(is_array($id)){
                            $j->whereIn('rcbd.rp_property_code',$rpPropCOdesArray);
                        }else{
                            $j->where('rcbd.rp_property_code',$propData->rp_property_code);
                        }
                        
                    })
                    ->join('cto_cashier as cc',function($j){
                        $j->on('cc.id','=','ccd.cashier_id')->where('cc.status',1)->where('cc.tfoc_is_applicable',2);
                    })->first();

        return $data;            
    }

    public function getBasicRatesData($id = ''){
        $assessedValue = "CASE 
                                        WHEN pk.pk_code = 'L' THEN (SELECT SUM(COALESCE(rpt_property_appraisals.rpa_assessed_value,0)) FROM rpt_property_appraisals WHERE rpt_property_appraisals.rp_code = rp.id)
                                        WHEN pk.pk_code = 'B' THEN rp.rpb_assessed_value
                                        WHEN pk.pk_code = 'M' THEN (SELECT SUM(COALESCE(rpt_property_machine_appraisals.rpm_assessed_value,0)) FROM rpt_property_machine_appraisals WHERE rpt_property_machine_appraisals.rp_code = rp.id) END";
        return DB::table('rpt_properties as rp')
                                  ->select(
                                    DB::raw("(COALESCE(taxrate.bsst_basic_rate,0)/100)*".$assessedValue." as basic_due"),
                                    DB::raw("(COALESCE(taxrate.bsst_sef_rate,0)/100)*".$assessedValue." as sef_due"),
                                    DB::raw("(COALESCE(taxrate.bsst_sh_rate,0)/100)*".$assessedValue." as sh_due"),
                                    'ry.has_tax_sef','ry.has_tax_sh','taxrate.assessed_value_max_amount',DB::raw($assessedValue.' as assessedValue')
                                  )
                                  ->where('rp.id',$id)
                                  ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
                                  ->join('rpt_revision_year AS ry', 'ry.id', '=', 'rp.rvy_revision_year_id')
                                  ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rp.id')
                                  ->leftJoin('rpt_property_machine_appraisals','rpt_property_machine_appraisals.rp_code','=','rp.id')
                                  ->leftJoin('rpt_cto_taxrates as taxrate','taxrate.pc_class_code','=',DB::raw("CASE 
                                            WHEN pk.pk_code = 'L' THEN rpt_property_appraisals.pc_class_code
                                            WHEN pk.pk_code = 'B' THEN rp.pc_class_code
                                            WHEN pk.pk_code = 'M' THEN rpt_property_machine_appraisals.pc_class_code
                                            ELSE NULL END"))->where('taxrate.is_active',1)
                                  ->groupBy('rp.id')
                                  ->first();
    }

    public function calculatePenaltyFee($td = '',$year = '',$sdModeId = 14, $manualPenalty = 'nos',$flag = false){
        $rptPropObj     = $this->getBasicRatesData($td);
        $shtMax      = (isset($rptPropObj->assessed_value_max_amount) && $rptPropObj->assessed_value_max_amount > 0)?$rptPropObj->assessed_value_max_amount:0;
        $asseValue   = $rptPropObj->assessedValue;
        $penalityRate = (!$flag)?0:$manualPenalty;
        $discountRate = 0;
        $basicAmount  = $newBasicAmount = $rptPropObj->basic_due;
        $sefAmount    = $newsefAmount = ($rptPropObj->has_tax_sef == 1)?$rptPropObj->sef_due:0;
        $shAmount     = $newshAmount = ($asseValue > $shtMax)?(($rptPropObj->has_tax_sh == 1)?$rptPropObj->sh_due:0):0;

        if($sdModeId != 14){
            $basicAmount  = $rptPropObj->basic_due/4;
            $sefAmount    = ($rptPropObj->has_tax_sef == 1)?$rptPropObj->sef_due/4:0;
            $shAmount     = ($asseValue > $shtMax)?(($rptPropObj->has_tax_sh == 1)?$rptPropObj->sh_due/4:0):0;
        }
        $basicInterst = 0;
        $sefInterst   = 0;
        $shInterst    = 0;
        $basicDisc    = 0;
        $sefDisc      = 0;
        $shDisc       = 0;
        $allModesData = DB::table('schedule_descriptions')->select('id','sd_mode')->where('is_active',1)->get();
        $dataToReturn = [
            'basicAmount'    => 0,
            'basicSefAmount' => 0,
            'basicShAMount'  => 0,
            'basicPenalty'   => 0,
            'sefPenalty'     => 0,
            'shPenalty'      => 0,
            'basicDisc'      => 0,
            'sefDisc'        => 0,
            'shDisc'         => 0,
        ];
        $sdModeData = $allModesData->where('sd_mode',$sdModeId)->first();
        $billObj = new RptCtoBilling;
        $penelityDueDateData = $billObj->getPaymentScheduledData($year,$sdModeData->id);
        if($year < date("Y")){
            $getPenalityRateDate = $billObj->getPenalityRateData($year,$sdModeData->id);
                    if($getPenalityRateDate != null){
                        $penalityRate        = (!$flag)?$getPenalityRateDate->cps_maximum_penalty:$manualPenalty;
                        $penalityEligibility = 1;
              }

        }else{
            if(isset($penelityDueDateData->rcpsched_penalty_due_date) && $penelityDueDateData->rcpsched_penalty_due_date != ''){
                 if(Carbon::today()->gt($penelityDueDateData->rcpsched_penalty_due_date)){
                    $getPenalityRateDate = $billObj->getPenalityRateData($year,$sdModeData->id);
                    if($getPenalityRateDate != null){
                        $penalityRate        = (!$flag)?$getPenalityRateDate->cps_maximum_penalty:$manualPenalty;
                        $penalityEligibility = 1;
                     }
                    
                 }if($flag && $sdModeId != 11){
                    $penalityRate  = $manualPenalty;
                    $penalityEligibility = 1;
                 }
                
             }
        }
        
        if(isset($penelityDueDateData->rcpsched_discount_due_date) && $penelityDueDateData->rcpsched_discount_due_date != ''){
                if(Carbon::today()->lte($penelityDueDateData->rcpsched_discount_due_date)){
                    $discountRate  = $penelityDueDateData->rcpsched_discount_rate;
                    $discountEligibility = 1;
                }
             }  
             if($penalityRate > 0){
                 $basicInterst = $basicAmount*($penalityRate/100);
                 $sefInterst   = $sefAmount*($penalityRate/100);
                 $shInterst   = $shAmount*($penalityRate/100);
             }if($discountRate > 0){
                 /*$basicDisc = $basicAmount*($discountRate/100);
                 $sefDisc   = $sefAmount*($discountRate/100);
                 $shDisc   = $shAmount*($discountRate/100);*/
                 $basicDisc = $newBasicAmount*($discountRate/100);
                 $sefDisc   = $newsefAmount*($discountRate/100);
                 $shDisc   =  $newshAmount*($discountRate/100);
             }

        return $dataToReturn = [
            'basicAmount'    => $basicAmount,
            'basicSefAmount' => $sefAmount,
            'basicShAMount'  => $shAmount,
            'basicPenalty'   => $basicInterst,
            'sefPenalty'     => $sefInterst,
            'shPenalty'      => $shInterst,
            'basicDisc'      => $basicDisc,
            'sefDisc'        => $sefDisc,
            'shDisc'         => $shDisc,
            'penalityRate'   => $penalityRate,
        ];
    }

    public function calculatePenaltyDiscMonthly($td = '',$year = '',$month = '',$sdModeId = ''){
        $rptPropObj     = $this->getBasicRatesData($td);
        $penalityRate = 0;
        $discountRate = 0;
        $basicAmount  = $rptPropObj->basic_due;
        $sefAmount    = ($rptPropObj->has_tax_sef == 1)?$rptPropObj->sef_due:0;
        $shAmount     = ($rptPropObj->has_tax_sh == 1)?$rptPropObj->sh_due:0;

        $basicInterst = 0;
        $sefInterst   = 0;
        $shInterst    = 0;
        $basicDisc    = 0;
        $sefDisc      = 0;
        $shDisc       = 0;
        $allModesData = DB::table('schedule_descriptions')->select('id','sd_mode')->where('is_active',1)->get();
        $dataToReturn = [
            'basicAmount'    => 0,
            'basicSefAmount' => 0,
            'basicShAMount'  => 0,
            'basicPenalty'   => 0,
            'sefPenalty'     => 0,
            'shPenalty'      => 0,
            'basicDisc'      => 0,
            'sefDisc'        => 0,
            'shDisc'         => 0,
        ];
        $sdModeData = $allModesData->where('sd_mode',$sdModeId)->first();
        $billObj = new RptCtoBilling;
        if($year == date("Y")){
            $penelityDueDateData = $billObj->getPaymentScheduledData($year,$sdModeData->id);
        }else{
            $penelityDueDateData = [];
        }
        
        
        if(empty($penelityDueDateData)){
            $getPenalityRateDate = DB::table('rpt_cto_penalty_tables')->where('cpt_effective_year',$year)->first();
                    if($getPenalityRateDate != null){
                        $monthProp = 'cpt_month_'.$month;
                        $penalityRate        = $getPenalityRateDate->$monthProp;
                        $penalityEligibility = 1;
              }

        }else{
            if(isset($penelityDueDateData->rcpsched_penalty_due_date) && $penelityDueDateData->rcpsched_penalty_due_date != ''){
                 if(Carbon::today()->gt($penelityDueDateData->rcpsched_penalty_due_date)){
                    $getPenalityRateDate = DB::table('rpt_cto_penalty_tables')->where('cpt_effective_year',$year)->first();
                    if($getPenalityRateDate != null){
                        $monthProp = 'cpt_month_'.$month;
                        $penalityRate        = $getPenalityRateDate->$monthProp;
                        $penalityEligibility = 1;
                     }
                    
                 }
                
             }
        }
        //dd($penalityRate);
        if(isset($penelityDueDateData->rcpsched_discount_due_date) && $penelityDueDateData->rcpsched_discount_due_date != ''){
                if(Carbon::today()->lte($penelityDueDateData->rcpsched_discount_due_date)){
                    $discountRate  = $penelityDueDateData->rcpsched_discount_rate;
                    $discountEligibility = 1;
                }
             }  
             //dd($penalityRate);
             if($penalityRate > 0){
                 $basicInterst = $basicAmount*($penalityRate/100);
                 $sefInterst   = $sefAmount*($penalityRate/100);
                 $shInterst   = $shAmount*($penalityRate/100);
             }if($discountRate > 0){
                 $basicDisc = $basicAmount*($discountRate/100);
                 $sefDisc   = $sefAmount*($discountRate/100);
                 $shDisc   = $shAmount*($discountRate/100);
             }

        return $dataToReturn = [
            'basicAmount'    => $basicAmount,
            'basicSefAmount' => $sefAmount,
            'basicShAMount'  => $shAmount,
            'basicPenalty'   => $basicInterst,
            'sefPenalty'     => $sefInterst,
            'shPenalty'      => $shInterst,
            'basicDisc'      => $basicDisc,
            'sefDisc'        => $sefDisc,
            'shDisc'         => $shDisc,
            'penalityRate'   => $penalityRate,
            'totalDue'       => ($basicAmount+$basicInterst-$basicDisc)+($sefAmount+$sefInterst-$sefDisc)+($shAmount+$shInterst-$shDisc)
        ];
    }

    public function calculateLAPpraisalAndUpdate($appraisalId = '', $update = ''){
         $landAppraisal = DB::table('rpt_property_appraisals')
                             ->addSelect([
                    'plantsTreeTotal' => RptPlantTreesAppraisal::select(DB::raw("SUM(rpta_market_value) AS plantsTreeTotal"))
                   ->whereColumn('rpa_code', 'rpt_property_appraisals.id')
                   ->where('rpa_code',$appraisalId)
                ])
                             ->where('id',$appraisalId)
                             ->first();
         if($landAppraisal != null){
            $landArea           = $landAppraisal->rpa_total_land_area;
            $landUnitValue      = $landAppraisal->lav_unit_value;
            $marketValue        = $landArea*$landUnitValue;
            $adjustedPlantValue = $landAppraisal->plantsTreeTotal;
            $adjusFactors       = $landAppraisal->rpa_adjustment_factor_a+$landAppraisal->rpa_adjustment_factor_b+$landAppraisal->rpa_adjustment_factor_c;
            $factorAdjusValue   = ($adjusFactors/100)*$marketValue;
            $totalAdjustedMarketValue = $marketValue+$adjustedPlantValue+$factorAdjusValue;
            $assessedValue      = ($landAppraisal->al_assessment_level/100)*$totalAdjustedMarketValue;
            $dataToUpdate = [
                'rpa_base_market_value'                     => $marketValue,
                'rpa_adjusted_market_value'                 => $marketValue+$factorAdjusValue,
                'rpa_adjusted_plant_tree_value'             => $adjustedPlantValue,
                'rpa_adjusted_total_planttree_market_value' => $totalAdjustedMarketValue,
                'rpa_assessed_value'                        => $assessedValue, 
                'rpa_adjustment_factor_a'                   => $landAppraisal->rpa_adjustment_factor_a,
                'rpa_adjustment_factor_b'                   => $landAppraisal->rpa_adjustment_factor_b,
                'rpa_adjustment_factor_c'                   => $landAppraisal->rpa_adjustment_factor_c,
                'rpa_adjustment_percent'                    => 100+$adjusFactors,
                'rpa_adjustment_value'                      => $factorAdjusValue
            ];
            $this->updateLandAppraisalDetail($appraisalId,$dataToUpdate);

         }
    }

    public function calculateTotalTaxDue($td = '',$year = '',$sdModeId = 14){
        $data = $this->calculatePenaltyFee($td,$year,$sdModeId);
        $basicTax = $data['basicAmount']+$data['basicPenalty']-$data['basicDisc'];
        $sefTax   = $data['basicSefAmount']+$data['sefPenalty']-$data['sefDisc'];
        $shTax    = $data['basicShAMount']+$data['shPenalty']-$data['shDisc'];
        return $basicTax+$sefTax+$shTax;
    }

    public function getBasicDueAttribute($value=''){
        $basicRates = $this->basicFeeRates();
        if($basicRates != null){
            return $this->rp_assessed_value*($basicRates->bsst_basic_rate/100);
         }else{
            return 0;
         }
    }

    public function getSefDueAttribute($value=''){
        $basicRates = $this->basicFeeRates();
        $eligibleForSef = (isset($this->revisionYearDetails->has_tax_sef))?$this->revisionYearDetails->has_tax_sef:1;
        if($basicRates != null && $eligibleForSef == 1){
            return $this->rp_assessed_value*($basicRates->bsst_sef_rate/100);
         }else{
            return 0;
         }
    }

    public function getShDueAttribute($value=''){
        $basicRates = $this->basicFeeRates();
        $maxAssValue = $basicRates->assessed_value_max_amount;
        $eligibleForSht = (isset($this->revisionYearDetails->has_tax_sef))?$this->revisionYearDetails->has_tax_sh:1;
        if($basicRates != null && $this->rp_assessed_value >= $maxAssValue && $eligibleForSht == 1){
            return $this->rp_assessed_value*($basicRates->bsst_sh_rate/100);
         }else{
            return 0;
         }
    }

    public function getTotalDueAttribute($value=''){
        return $this->basic_due+$this->sef_due+$this->sh_due;
    }

    public function getPcClassDescriptionAttribute()
    {
        if($this->propertyClass != null){
            return $this->propertyClass->pc_class_code.'-'.$this->propertyClass->pc_class_description;  
        }else{
            return "";
        }
          
    }

    public function getClassForKindAttribute()
    {
        if($this->propertyKindDetails->pk_code == "L"){
            return (!$this->landAppraisals->isEmpty())?$this->landAppraisals[0]->class:'';
        }if($this->propertyKindDetails->pk_code == "B"){
            return $this->propertyClass;
        }if($this->propertyKindDetails->pk_code == "M"){
            return (!$this->machineAppraisals->isEmpty())?$this->machineAppraisals[0]->class:'';
        }
          
    }

    public function barangay(){
        return $this->belongsTo(Barangay::class,'brgy_code_id');
    }

    public function propertyClass(){
        return $this->belongsTo(RptPropertyClass::class,'pc_class_code');
    }

    public function updatecode(){
        return $this->belongsTo(RptUpdateCode::class,'uc_code');
    }

    public function locality(){
        return $this->belongsTo(ProfileMunicipality::class,'loc_local_code');
    }

    public function getPinAttribute(){

        return $this->rp_section_no.'-'.$this->rp_pin_no;
    }

    public function getTreasurerNameAttribute(){

        return $this->rp_section_no.'-'.$this->rp_pin_no;
    }

    public function getCompletePinAttribute(){
        $completePinArray = [];
        if(isset($this->locality->mun_no) && $this->locality->mun_no != ''){
            $completePinArray[] = $this->locality->mun_no;
        }if(isset($this->dist_code) && $this->dist_code != ''){
            $completePinArray[] = $this->dist_code;
        }if(isset($this->barangay->brgy_code) && $this->barangay->brgy_code != ''){
            $completePinArray[] = $this->barangay->brgy_code;
        }if(isset($this->rp_section_no) && $this->rp_section_no != ''){
            $completePinArray[] = $this->rp_section_no;
        }if(isset($this->rp_pin_no) && $this->rp_pin_no != ''){
            $completePinArray[] = $this->rp_pin_no;
        }
        return implode("-",$completePinArray);
    }

    public function getMarketValueAttribute(){
        return $this->landAppraisals->sum('rpa_adjusted_market_value');
    }

    public function getMarketValueForAllKindAttribute($value=''){
        //dd($this->propertyKindDetails);
        if($this->propertyKindDetails->pk_code == "L"){
            return $this->landAppraisals->sum('rpa_adjusted_market_value');
        }if($this->propertyKindDetails->pk_code == "B"){
            return $this->rpb_accum_deprec_market_value;
        }if($this->propertyKindDetails->pk_code == "M"){
            return $this->machineAppraisals->sum('rpma_market_value');
        }
    }

    public function getAssessedValueForAllKindAttribute($value=''){
        //dd($this->propertyKindDetails->pk_code);
        if($this->propertyKindDetails->pk_code == "L"){
            return $this->landAppraisals->sum('rpa_assessed_value');
        }if($this->propertyKindDetails->pk_code == "B"){
            return $this->rpb_assessed_value;
        }if($this->propertyKindDetails->pk_code == "M"){
            return $this->machineAppraisals->sum('rpm_assessed_value');
        }
    }

    public function getAssessedValueAttribute(){
        return $this->landAppraisals->sum('rpa_assessed_value');
    }

    public function getTdNoAttribute(){
        $tdArray = [];
        $revisionYear = ($this->revisionYearDetails != null)?$this->revisionYearDetails->rvy_revision_year:'';
        $brgyCode     = ($this->barangay != null)?$this->barangay->brgy_code:'';
        $rpTdNo       = $this->rp_td_no;
        if($revisionYear != ''){
            $tdArray[] = $revisionYear;
        }if($brgyCode != ''){
            $tdArray[] = $brgyCode;
        }if($rpTdNo != ''){
            $tdArray[] = $rpTdNo;
        }
        return implode("-",$tdArray);
    }

    public function getTaxpayerNameAttribute(){
        return ($this->propertyOwner != null)?$this->propertyOwner->full_name:'';
    }

    public function getAdministratorNameAttribute(){
        return ($this->propertyAdmin != null)?$this->propertyAdmin->full_name:'';
    }

    public function getRevisionYearCodeAttribute(){
        return $this->revisionYearDetails;
    }

    public function getPropertyOwnerDetailsAttribute(){
        return $this->propertyOwner;
    }

    public function getTotalLandAreaAttribute(){
        return $this->landAppraisals->sum('rpa_total_land_area');
    }

    public function getPropertyAdminDetailsAttribute(){
        return $this->propertyAdmin;
    }

    public function getLandAppraisalsDetailsAttribute(){
        return $this->landAppraisals;
    }

    public function getBarangayDetailsAttribute(){
        return $this->barangay;
    }

    public function revisionYearDetails(){
        return $this->belongsTo(RevisionYear::class,'rvy_revision_year_id');
    }

    public function propertyOwner(){
        return $this->belongsTo(RptPropertyOwner::class,'rpo_code');
    }

    public function propertyAdmin(){
        return $this->belongsTo(RptPropertyOwner::class,'rp_administrator_code');
    }

    public function propertyKindDetails(){
        return $this->belongsTo(RptPropertyKind::class,'pk_id');
    }

    public function rptBuildingKindDetails(){
        return $this->belongsTo(RptBuildingKind::class,'bk_building_kind_code');
    }

    public function landAppraisals(){
        return $this->hasMany(RptPropertyAppraisal::class,'rp_code');
    }

    public function plantTreeAppraisals(){
        return $this->hasMany(RptPlantTreesAppraisal::class,'rp_code');
    }

     public function additionalItems(){
        return $this->hasMany(RptPropertyBuildingFloorAdItem::class,'rp_code');
    }

    public function propertyApproval(){
        return $this->hasOne(RptPropertyApproval::class,'rp_code');
    }

    public function swornStatement(){
        return $this->hasOne(RptPropertySworn::class,'rp_code');
    }

     public function propertyStatus(){
        return $this->hasOne(RptPropertyStatus::class,'rp_code');
    }

    public function propertyAnnotations(){
        return $this->hasMany(RptPropertyAnnotation::class,'rp_code');
    }

    public function addData($postdata, $updateCode = ''){
        DB::table('rpt_properties')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function addAnnotationData($postdata, $updateCode = ''){
        DB::table('rpt_property_annotations')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function addAdditionalItemsData($postdata, $updateCode = ''){
        DB::table('rpt_property_building_floor_ad_items')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    } 
    
    public function getApprovalFormTds($kind = 'L',$id = 0){
        $allTdsObj = RptProperty::where('pk_is_active',1);
        if($kind == 'L'){
             $allTdsObj = $allTdsObj->where('pk_id',2);
        }if($kind == 'B'){
             $allTdsObj = $allTdsObj->where('pk_id',1);
        }if($kind == 'M'){
             $allTdsObj = $allTdsObj->where('pk_id',3);
        }
        if($id > 0){
            $allTdsObj = $allTdsObj->where('id','!=',$id);
        }
        $allTdsObj = $allTdsObj->get();
        $allTds    = [];
        foreach ($allTdsObj as $key => $value) {
            $allTds[$value->id] = $value->rp_tax_declaration_no;
        }
        return $allTds;
    }

    public function getPreviousOwnerTds($id = ''){
        $singlePropDetails = RptProperty::find($id);
        $allTdsObj = RptProperty::where('is_deleted',0)->where('rp_property_code',(isset($singlePropDetails->rp_property_code))?$singlePropDetails->rp_property_code:'')->get();
        $allTds    = [];
        foreach ($allTdsObj as $key => $value) {
            $allTds[$value->id] = $value->rp_tax_declaration_no;
        }
        return $allTds;
    }

    public function checkForUniquePinLand($data = []){
        $locality = DB::table('rpt_locality')->where('mun_no',(isset($data['loc_local_code']))?$data['loc_local_code']:0)->first();
        $localCode = (isset($locality->loc_local_code))?$locality->loc_local_code:'';
        $districtCode = (isset($data['dist_code']))?$data['dist_code']:'';
        $brgy   = (isset($data['brgy_code']))?$data['brgy_code']:''; 
        $sectionNo   = (isset($data['rp_section_no']))?$data['rp_section_no']:'';
        $pinNo   = (isset($data['rp_pin_no']))?$data['rp_pin_no']:'';
        $completePin = $localCode.'-'.$districtCode.'-'.$brgy.'-'.$sectionNo.'-'.$pinNo;
        $checkRecord = DB::table('rpt_properties')->where('rp_pin_declaration_no',$completePin)->where('pk_is_active',1)->where('is_deleted',0)->where('id','!=',$data['id'])->first();
        if($checkRecord == null){
            return false;
        }else{
            return true;
        }
    }

    public function addPropertyStatusData($postdata, $updateCode = ''){
        DB::table('rpt_property_statuses')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function updatePropertyStatusData($id,$columns){
        return DB::table('rpt_property_statuses')->where('id',$id)->update($columns);
    }

    public function addPropertySwornData($postdata, $updateCode = ''){
        DB::table('rpt_property_sworns')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function updatePropertySwornData($id,$columns){
        return DB::table('rpt_property_sworns')->where('id',$id)->update($columns);
    }

    public function addMachineAppraisalDetail($postdata, $updateCode = ''){
        DB::table('rpt_property_machine_appraisals')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function updateMachineAppraisalDetail($id,$columns){
        return DB::table('rpt_property_machine_appraisals')->where('id',$id)->update($columns);
    }

    public function addLandAppraisalDetail($postdata,$updateCode = ''){
        DB::table('rpt_property_appraisals')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function updateLandAppraisalDetail($id,$columns,$updateCode = ''){
        return DB::table('rpt_property_appraisals')->where('id',$id)->update($columns);
         
    }

    public function addPlantsTreesFactors($postdata,$updateCode = ''){
        return DB::table('rpt_plant_trees_appraisals')->insert($postdata);
    }

    public function addPropertyHistory($postdata){
         return DB::table('rpt_property_histories')->insert($postdata);
    }

    public function updatePropertyHistory($id, $columns){
         return DB::table('rpt_property_histories')->where('id',$id)->update($columns);
    }


    public function updatePlantsTreesFactors($id,$columns){
         return DB::table('rpt_plant_trees_appraisals')->where('id',$id)->update($columns);
    }

    public function addApprovalForm($postdata){
         return DB::table('rpt_property_approvals')->insert($postdata);
    }

    public function updateApprovalForm($id,$columns){
         return DB::table('rpt_property_approvals')->where('id',$id)->update($columns);
    }


    public function getLandAppraisalDetals($id = '',$updateCode = ''){
            $table = DB::table('rpt_property_appraisals as ap');
        return $table->join('rpt_property_classes AS class', 'ap.pc_class_code', '=', 'class.id')
                     ->leftJoin('rpt_property_subclassifications AS sub', 'ap.ps_subclass_code', '=', 'sub.id')
                     ->join('rpt_property_actual_uses AS au', 'ap.pau_actual_use_code', '=', 'au.id')
                     ->select('ap.*','class.pc_class_description','sub.ps_subclass_desc','au.pau_actual_use_desc')
                     ->where('ap.rp_code',(int)$id)
                     ->get();
    }

    public function getPalntsTreesAppraisalDetails($id = ''){
        $sql = DB::table('rpt_plant_trees_appraisals as pta')
                   ->join('rpt_property_classes AS class', 'pta.pc_class_code', '=', 'class.id')
                   ->join('rpt_property_subclassifications AS sub', 'pta.ps_subclass_code', '=', 'sub.id')
                   ->join('rpt_plant_tress AS pt', 'pta.rp_planttree_code', '=', 'pt.id')
                   ->join('rpt_revision_year AS ry', 'pta.rvy_revision_code', '=', 'ry.id')
                   ->select('pta.*','class.pc_class_description','sub.ps_subclass_desc','pt.pt_ptrees_description','ry.rvy_revision_year', 'ry.rvy_revision_code as revision_year_code_ry');
                $sql->where(function ($sql) use($id) {
                  if($id != '0'){
                    $sql->where('pta.rpa_code',$id);
                  }else{
                    $sql->whereNull('pta.rp_code');
                    $sql->where('pta.rpta_registered_by',\Auth::user()->creatorId());
                  }
                });           
        return $sql->get();
    }

    public function getprofiles($id=0){
        if($id > 0){
             return RptPropertyOwner::where('is_rpt',1)->get();
         }else{
             return RptPropertyOwner::where('is_active',1)->where('is_rpt',1)->get();
         }
    }


    public function getRevisionYears($value=''){
        return DB::table('rpt_revision_year')->get()->toArray();
    }

    public function getPlantTreeCodes($value=''){
        return DB::table('rpt_plant_tress')->get()->toArray();
    }

    public function getHrEmplyees($value=''){
        return DB::table('hr_employees')
		->select('hr_employees.id','hr_employees.fullname')->join('rpt_appraisers as ra',function($j){
            $j->on('ra.ra_appraiser_id','=','hr_employees.id')->where('ra.ra_is_active',1);
        })->groupBy('ra.ra_appraiser_id')->get()->toArray();
    }

    public function getPropertyKinds($value=''){
        return DB::table('rpt_property_kinds')->get()->toArray();
    }

    public function getPropKindCodes($value=''){
        return DB::table('rpt_property_kinds')->get()->toArray();
    }

    public function getKindIdByCode($value=''){
        $sql = DB::table('rpt_property_kinds')->where('pk_code',$value)->get()->first();
        if($sql != null){
            return $sql->id;
        }else{
            return "";
        }
    }

    public function getStrippingCodes($value=''){
        return DB::table('rpt_land_strippings')->where('rls_is_active',1)->get()->toArray();
    }

    public function getBarangay(){
        return DB::table('barangays')->select('id','brgy_code','brgy_name')->where('brgy_display_for_bplo',1)->get();
    }

    public function getPropClasses(){
        return DB::table('rpt_property_classes')->select('id','pc_class_code','pc_class_no','pc_class_description','pc_unit_value_option','pc_taxability_option')->get();
    }

    public function getDefaultOwner(){
        return DB::table('profiles')->where('brgy_display_for_bplo',1)->get();
    }

    public function getSubClassesList($id=''){
        return DB::table('rpt_property_subclassifications')->select(DB::raw("CONCAT(ps_subclass_code,'-',ps_subclass_desc) as ps_subclass_code"),'id','ps_subclass_desc')->where('pc_class_code',$id)->where('ps_is_active',1)->get();
    }

    public function getPlantTreeUnitValue($request){
            $activeMunci = $this->activeMuncipality;
            $searchBrgy  = $this->activeBarangay->where('id',$request->barangy);
            if($activeMunci != null && $activeMunci->loc_group_default_barangay_id > 0 && !$searchBrgy->isEmpty()){
                $defaultBrgyGroup = $activeMunci->loc_group_default_barangay_id;
            }else{
                $defaultBrgyGroup = 0;
            }
            //dd($defaultBrgyGroup);
            $sql = DB::table('rpt_plant_tress_unit_values')
               ->where('pt_ptrees_code',$request->platTreeId)
               ->where('pc_class_code',$request->classId)
               ->where('ps_subclass_code',$request->subClassId)
               ->where('rvy_revision_year',$request->revisionYearId)
               ->where('loc_group_brgy_no',($defaultBrgyGroup != 0)?$defaultBrgyGroup:$request->barangy)
               ->where('is_approve',1)
               ->first();
               if($sql == null){
                return false;
               }else{
                return $sql;
               }

                                       
    }

    public function getAssessementLevel($request){
        $activeMunci = $this->activeMuncipality;
            $searchBrgy  = $this->activeBarangay->where('id',$request->barangay);
            if($activeMunci != null && $activeMunci->loc_group_default_barangay_id > 0 && !$searchBrgy->isEmpty()){
                $defaultBrgyGroup = $activeMunci->loc_group_default_barangay_id;
            }else{
                $defaultBrgyGroup = 0;
            }
        $propertyKindDetails = DB::table('rpt_property_kinds')->where('id',$request->propertyKind)->first();
        $sql = RptAssessmentLevel::with(['assessementRelations'=>function ($query)use($request){
            $query->where('minimum_unit_value','<=',$request->totalMarketValue);
            $query->where('maximum_unit_value','>=',$request->totalMarketValue);
        }])
               ->where('pk_code',$request->propertyKind)
               ->where('rvy_revision_year',$request->propertyRevisionYear)
               ->where('loc_group_brgy_no',($defaultBrgyGroup != 0)?$defaultBrgyGroup:$request->barangay)
               ->where('is_active',1)
               ->where('is_approve',1)
               ->where('pc_class_code',$request->propertyClass);
        if($propertyKindDetails != null && $propertyKindDetails->pk_code != "M"){
            $sql->where('pau_actual_use_code',$request->propertyActualUseCode);
        }
               $data = $sql->first();
               if($data == null){
                return false;
               }else{
                return $data;
               }                       
    }

    public function getLandUnitValue($request){
            $activeMunci = $this->activeMuncipality;
            $searchBrgy  = $this->activeBarangay->where('id',$request->barangayId);
            if($activeMunci != null && $activeMunci->loc_group_default_barangay_id > 0 && !$searchBrgy->isEmpty()){
                $defaultBrgyGroup = $activeMunci->loc_group_default_barangay_id;
            }else{
                $defaultBrgyGroup = 0;
            }
            $sql = DB::table('rpt_land_unit_values AS ut')
               ->join('barangays AS b', 'b.id', '=', 'ut.loc_group_brgy_no')
               ->join('rpt_property_classes AS class', 'class.id', '=', 'ut.pc_class_code')
               ->join('rpt_property_subclassifications AS sub', 'sub.id', '=', 'ut.ps_subclass_code')
               ->join('rpt_property_actual_uses AS act', 'act.id', '=', 'ut.pau_actual_use_code')
               ->join('rpt_revision_year AS year', 'year.id', '=', 'ut.rvy_revision_year')
               ->select('ut.*')
               ->where('b.id',($defaultBrgyGroup != 0)?$defaultBrgyGroup:$request->barangayId)
               ->where('year.id',$request->revisionYearId)
               ->where('class.id',$request->classId)
               ->where('sub.id',$request->subCkassId)
               ->where('act.id',$request->actualUseCodeId)
               ->where('is_approve',1)
               ->first();
               if($sql == null){
                return false;
               }else{
                return $sql;
               }
                                       
    }

    public function getprofileData($id=''){
      return RptPropertyOwner::where('id',$id)->first();
    }
    

    public function getActualUsesList($id=''){
        return DB::table('rpt_property_actual_uses')->select('id',DB::raw("CONCAT(pau_actual_use_code,'-',pau_actual_use_desc) as pau_actual_use_code"),'pau_actual_use_desc','pau_with_land_stripping')->where('pc_class_code',$id)->where('pau_is_active',1)->get();
    }

    public function getUpdateCodes($kind = ''){
        $query = DB::table('rpt_update_codes')->where('uc_is_active',1);
        if($kind == 'L'){
            $query = $query->where('uc_usage_land',1);
        }if($kind == 'M'){
            $query = $query->where('uc_usage_machine',1);
        }if($kind == 'B'){
            $query = $query->where('uc_usage_building',1);
        }
        return $query->get();
    }

    public function getUpdateCodesForCancellation($kind = ''){
         $query = DB::table('rpt_update_codes')->where('uc_is_active',1)->where('direct_cancellation',1);
        if($kind == 'L'){
            $query = $query->where('uc_usage_land',1);
        }if($kind == 'M'){
            $query = $query->where('uc_usage_machine',1);
        }if($kind == 'B'){
            $query = $query->where('uc_usage_building',1);
        }
        return $query->get();
    }

    public function searchByTdNo($rpTdNo,$brngy, $revisionYear){
        return $this->with(['landAppraisals','propertyOwner','propertyKindDetails','floorValues','machineAppraisals'])->where('rp_td_no',$rpTdNo)
                                                ->where('brgy_code_id',$brngy)
                                                ->where('rvy_revision_year_id',$revisionYear)
                                                ->where('is_deleted',0)
                                                ->where('pk_is_active',1)
                                                ->first();
    }

    public function searchByTdNoforMultiple($rpTdNo,$brngy, $revisionYear){
        return $this->with(['landAppraisals','propertyOwner','propertyKindDetails','floorValues','machineAppraisals'])->where('rp_td_no',$rpTdNo)
                                                ->where('rvy_revision_year_id',$revisionYear)
                                                ->where('is_deleted',0)
                                                ->where('pk_is_active',1)
                                                ->first();
    }

    public function getBarangayByid($rpTdNo){
        return $this->where('rp_td_no',$rpTdNo)
                                                ->where('is_deleted',0)
                                                ->where('pk_is_active',1)
                                                ->first();
    }

    public function getBuildingUpdateCodes(){
        return DB::table('rpt_update_codes')->where('uc_usage_building',1)->where('uc_is_active',1)->get();
    }
    public function getMachineryUpdateCodes(){
        return DB::table('rpt_update_codes')->where('uc_usage_machine',1)->where('uc_is_active',1)->get();
    }
    

    public function getUpdateCodeById($value=''){
        $sql = DB::table('rpt_update_codes')->where('id',$value)->get()->first();
        if($sql != null){
            return $sql->uc_code;
        }else{
            return "";
        }
    }

    public function getLocalityCodes($value=''){
       return DB::table('rpt_locality')->select('id','loc_local_code','loc_local_name','loc_address')->where('is_active',1)->get();
    }

    public function getDistrictCodesBasedOnLocality($id=''){
        return DB::table('rpt_district')->select('id','loc_local_code','dist_code','dist_name')->where('loc_local_code',$id)->where('is_active',1)->get();
    }

    public function getClassDetails($id=''){
        return DB::table('rpt_property_classes')->where('id',$id)->where('pc_is_active',1)->first();
    }

    public function getRevisionYearDetails($id=''){
        return DB::table('rpt_revision_year')->where('id',$id)->first();
    }

    public function getLandStrippingDetails($id=''){
        return DB::table('rpt_land_strippings')->where('id',$id)->where('rls_is_active',1)->first();
    }
    
    public function getDistrictCodes($value=''){
       return DB::table('rpt_district')->select('id','loc_local_code','dist_code','dist_name')->where('is_active',1)->get();
    }



    public function getSinglePropertyDetails($id=''){
        $sql = DB::table('rpt_properties AS bgf')
                 ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'bgf.pk_id')
                 ->join('rpt_revision_year AS ry', 'ry.id', '=', 'bgf.rvy_revision_year_id')
                 ->join('barangays AS bgy', 'bgy.id', '=', 'bgf.brgy_code_id')
                 ->join('clients AS pr', 'pr.id', '=', 'bgf.rpo_code')
                 ->join('rpt_update_codes AS uc', 'uc.id', '=', 'bgf.uc_code')
                 ->selectRaw('bgf.id,bgf.rp_property_code,bgf.rp_location_number_n_street,bgf.rvy_revision_code,bgf.rp_td_no,bgf.rp_suffix,bgf.pk_is_active,bgf.rp_tax_declaration_no,bgf.loc_local_code,bgf.dist_code,bgf.rp_section_no,bgf.rp_pin_no,bgf.rp_pin_suffix,bgf.rp_oct_tct_cloa_no,bgf.rp_cadastral_lot_no,bgf.rpo_code,bgf.rp_administrator_code,pk.pk_code,pk.pk_description,ry.rvy_revision_year,ry.rvy_revision_code,bgy.brgy_code,bgy.brgy_name,pr.full_name,pr.rpo_first_name,pr. rpo_custom_last_name,pr.rpo_address_street_name,pr.rpo_address_subdivision,uc.uc_code,uc.uc_description, CONCAT(bgy.brgy_code, bgy.brgy_name) as barangay')
                 ->where('bgf.id',$id)
                 ->first();
        return  $sql;        
    }

    public function getApprovalFormDetails($id=''){
        $sql = DB::table('rpt_property_approvals AS bgf')
                 ->join('rpt_properties AS rp', 'rp.id', '=', 'bgf.rp_code')
                 ->leftJoin('users AS us', 'us.id', '=', 'bgf.rp_app_cancel_by')
                 ->select('bgf.*','rp.rp_app_effective_year','rp.rp_app_effective_quarter','rp.rp_app_posting_date','rp.rp_app_memoranda','rp.rp_app_extension_section','rp.pk_is_active','rp.uc_code','rp.rp_app_assessor_lot_no','rp.rp_app_taxability','us.name')
                 ->where('bgf.rp_code',$id)
                 ->first();
        return  $sql;        
    }
    public function getMuncipLityCodeDetails($id=''){
        return DB::table('rpt_locality')->select('id','loc_local_code','loc_local_name','loc_address')->where('id',(int)$id)->where('is_active',1)->first();
    }

    public function getRelatedBuiMachineList($request)
    {
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $columns = array( 
          1 => "rp_tax_declaration_no",
          2 => "c.full_name",
          3 => "brgy_name",
          4 => "rp_pin_declaration_no",
          5 => "rp_cadastral_lot_no",
          6 => "rp.rp_market_value_adjustment",
          7 => "rp.rp_assessed_value",
          8 => "uc.uc_code",
          9 => "rp_app_effective_year",
          10 => "reg_emp_name",
          11 => "created_at",
          12 => "rpt_properties.pk_is_active"
         );
        $sql = DB::table('rpt_properties as rp')
                      ->select(
                        'rp.rp_tax_declaration_no',
                        'rp.rp_pin_declaration_no',
                        'rp.pk_is_active',
                        'pk.pk_description',
                        'c.full_name as customername',
                        'rp.rp_assessed_value as assessedValue',
                        'rp.rp_market_value_adjustment as marketValue',
                        DB::raw("CASE  
                     WHEN pk.pk_code = 'B' THEN COALESCE(rp.al_assessment_level)
                     WHEN pk.pk_code = 'M' THEN MAX(COALESCE(rpt_property_machine_appraisals.al_assessment_level))
                     END as assessementLevel"),
                      )
                      ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
                      ->leftJoin('rpt_property_machine_appraisals','rpt_property_machine_appraisals.rp_code','=','rp.id') 
                      ->leftJoin('rpt_building_floor_values','rpt_building_floor_values.rp_code','=','rp.id')
                      ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
                      ->where('rp.rp_code_lref',$request->id)
                      ->whereIn('rp.pk_is_active',[1,0])
                      ->where('rp.is_deleted',0)
                      ->groupBy('rp.id');
     
        $sql->orderBy('rp.id','DESC');
        $data_cnt=count($sql->get());
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function getList($request){
  
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $propertyKind = ($request->has('property_kind'))?$request->property_kind:'';
        if(is_numeric($propertyKind)){
            $propertyKindId = $propertyKind;
        }else{
            $propertyKindId = $this->getKindIdByCode($propertyKind);
        }
        
        //dd($propertyKindId);
        $q=$request->input('q');
        $year = $request->input('year');
        $status = $request->input('status');
        //dd($status);
        $barangy = $request->input('barangay');
        $request->session()->put('landSelectedBrgy',$barangy);
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        if($request->status == '2'){
            $columns = array( 
          1 => "id",     
          2 => "rp_td_no",
          3 => DB::raw("CASE 
                 WHEN pr.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,'')))
                 WHEN pr.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,'')))
                 WHEN pr.suffix IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,'')))
                 WHEN pr.rpo_first_name IS NULL AND pr.rpo_middle_name IS NULL AND pr.suffix IS NULL THEN COALESCE(pr.rpo_custom_last_name,'')
                 ELSE TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,''))) END
                 "),
          4 => "rp_pin_declaration_no",
          5 => DB::raw("(SELECT SUM(rpa_adjusted_market_value) FROM rpt_property_appraisals WHERE rpt_property_appraisals.rp_code = rpt_properties.id)"),
          6 => DB::raw("(SELECT SUM(rpa_assessed_value) FROM rpt_property_appraisals WHERE rpt_property_appraisals.rp_code = rpt_properties.id)"),
          7=>'pk_is_active'
         );
        }else{
            $columns = array( 
          1 => "rp_td_no",
          2 => DB::raw("CASE 
          
                 WHEN pr.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,'')))
                 WHEN pr.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,'')))
                 WHEN pr.suffix IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,'')))
                 WHEN pr.rpo_first_name IS NULL AND pr.rpo_middle_name IS NULL AND pr.suffix IS NULL THEN COALESCE(pr.rpo_custom_last_name,'')
                 ELSE TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,''))) END
                 "),
          3 => "bgy.brgy_name",
          4 => "rp_pin_declaration_no",
          5 => "rp_cadastral_lot_no",
          6 => DB::raw("(SELECT SUM(rpa_adjusted_market_value) FROM rpt_property_appraisals WHERE rpt_property_appraisals.rp_code = rpt_properties.id)"),
          7 => DB::raw("(SELECT SUM(rpa_assessed_value) FROM rpt_property_appraisals WHERE rpt_property_appraisals.rp_code = rpt_properties.id)"),
          8 => "uc.uc_description",
          9 => 'rp_app_effective_year',
          10=>'rp_modified_by',
          11=>'created_at',
          12=>'pk_is_active'
         );
        }
        
           $sql = $this->select('rpt_properties.*','brgy_name','ra.rp_app_cancel_is_direct')
                 ->addSelect(
                    [
                        'reg_emp_name' => DB::table('hr_employees AS emp')->select('fullname')
                                       ->whereColumn('rp_modified_by', 'emp.user_id'),
                        'market_value' => RptPropertyAppraisal::select(DB::raw("SUM(rpa_adjusted_market_value) AS market_value"))
                                       ->whereColumn('rp_code', 'rpt_properties.id'),
                        'assessed_value' => RptPropertyAppraisal::select(DB::raw("SUM(rpa_assessed_value)"))
                                       ->whereColumn('rp_code', 'rpt_properties.id')             
                ]
            )
                 ->with([
                    'propertyOwner',
                    'landAppraisals',
                    'updatecode'
                ])
                 ->join('barangays AS bgy', 'bgy.id', '=', 'rpt_properties.brgy_code_id')
                 ->join('clients AS pr', 'pr.id', '=', 'rpt_properties.rpo_code')
                 ->join('rpt_update_codes AS uc', 'uc.id', '=', 'rpt_properties.uc_code')
                 ->join('rpt_property_approvals AS ra', 'ra.rp_code', '=', 'rpt_properties.id')
                 ->where('is_deleted',0)
                 ->where('pk_id',$propertyKindId)
                 ->where('pk_is_active',(int)$status);
        if(!empty($q) && isset($q)){
            if(is_numeric($q)){
                    $sql->havingRaw('market_value = '.$q.' OR assessed_value = '.$q);
                }
            $sql->where(function ($sql) use($q) {
                if(is_numeric($q)){
                    $sql->havingRaw('market_value = '.$q.' OR assessed_value = '.$q);
                }else{
                    //dd(strtolower($q));
                    $sql->orWhere(DB::raw('LOWER(pr.rpo_first_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(pr.full_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(pr.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(pr.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(rp_pin_declaration_no)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(rpt_properties.rp_td_no)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(pr.suffix)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(rp_cadastral_lot_no)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(uc.uc_code)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(rpt_properties.rp_tax_declaration_no)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw("CONCAT(rpt_properties.rp_section_no,'-',rpt_properties.rp_pin_no)"),'like',"%".$q."%")
                        ;
                }
                     
            });
           
        }
        if(!empty($year) && isset($year)){
            $sql->where(function ($sql) use($year) {
                $sql->where('rvy_revision_year_id',$year);

            });
        }if(!empty($barangy) && isset($barangy)){
            $sql->where(function ($sql) use($barangy) {
                $sql->where('brgy_code_id',$barangy);

            });
        }/*if(!empty($status) && isset($status)){
            $sql->where(function ($sql) use($status) {
                $sql->where('pk_is_active',(int)$status);

            });
        }*/

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column'])){
                $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }else{
            $sql->orderBy('rpt_properties.id','DESC');
        }
    //   return $columns[$params['order'][0]['column']];
        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        //dd($data);
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

      public function generateTaxDeclarationAform($id = ''){
          $data = DB::table('rpt_properties as rp')
                     ->select(
                        'rp.id',
                        'ry.rvy_revision_year',
                        'rp.rp_td_no',
                        'brgy.brgy_code',
                        /*'clss.pc_class_code',*/
                        DB::raw( "(CASE 
                WHEN pk.pk_code = 'L' THEN CONCAT(clss.pc_class_code,'L') 
                WHEN pk.pk_code = 'B' THEN CONCAT(clss.pc_class_code,'B')
                WHEN pk.pk_code = 'M' THEN CONCAT(clss.pc_class_code,'M')
                END) as pc_class_code"),
                        DB::raw( "(CASE 
                WHEN pk.pk_code = 'L' THEN CONCAT(COALESCE(rp.rp_cadastral_lot_no,''),';',COALESCE(rp.rp_oct_tct_cloa_no,'')) 
                WHEN pk.pk_code = 'B' THEN CONCAT(COALESCE(rp.rp_building_cct_no,''),';',COALESCE(rp.rp_building_unit_no,''))
                WHEN pk.pk_code = 'M' THEN GROUP_CONCAT(DISTINCT rpt_property_machine_appraisals.rpma_description SEPARATOR ';')
                END) as rp_lot_cct_unit_desc"),
                        'clss.pc_class_description',
                        DB::raw("(SELECT MAX(CASE 
                            WHEN rpt_property_appraisals.lav_unit_measure = '2' THEN (rpt_property_appraisals.rpa_total_land_area*10000) ELSE rpt_property_appraisals.rpa_total_land_area
                            END) from rpt_property_appraisals where rpt_property_appraisals.rp_code = rp.id) as sdfdsfds")
                    )
                     ->where('rp.id',$id)
                     ->join('rpt_revision_year as ry','ry.id','=','rp.rvy_revision_year_id')
                     ->join('barangays as brgy','brgy.id','=','rp.brgy_code_id')
                     ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
                     ->leftJoin('rpt_property_appraisals',function($j){
                        $j->on('rpt_property_appraisals.rp_code','=','rp.id')
                          ->whereRaw("(CASE 
                            WHEN rpt_property_appraisals.lav_unit_measure = '2' THEN (rpt_property_appraisals.rpa_total_land_area*10000) ELSE rpt_property_appraisals.rpa_total_land_area
                            END) = (SELECT MAX(CASE 
                            WHEN rpt_property_appraisals.lav_unit_measure = '2' THEN (rpt_property_appraisals.rpa_total_land_area*10000) ELSE rpt_property_appraisals.rpa_total_land_area
                            END) from rpt_property_appraisals where rpt_property_appraisals.rp_code = rp.id)");
                     })
                     ->leftJoin('rpt_property_machine_appraisals','rpt_property_machine_appraisals.rp_code','=','rp.id')
                     ->leftJoin('rpt_property_classes AS clss', 'clss.id', '=',DB::raw( "(CASE 
                WHEN pk.pk_code = 'L' THEN rpt_property_appraisals.pc_class_code 
                WHEN pk.pk_code = 'B' THEN rp.pc_class_code
                WHEN pk.pk_code = 'M' THEN rpt_property_machine_appraisals.pc_class_code
                END)"))
                     ->first();        
          $arFormNo = [];
          $revisionYear = (isset($data->rvy_revision_year))?$data->rvy_revision_year:'';
          $class       = $data->pc_class_code;
          $brgyCode = (isset($data->brgy_code))?$data->brgy_code:'';
          $tdNo     = str_pad($data->rp_td_no, 5, '0', STR_PAD_LEFT);
          if($revisionYear != ''){
            $arFormNo[] = $revisionYear;
          }if($class != ''){
            $arFormNo[] = $class;
          }if($brgyCode != ''){
            $arFormNo[] = $brgyCode;
          }if($tdNo != ''){
            $arFormNo[] = $tdNo;
          }

          $arFormNoText = implode("-",$arFormNo);
          $dataToUpdate = [
            'rp_lot_cct_unit_desc' => (isset($data->rp_lot_cct_unit_desc))?$data->rp_lot_cct_unit_desc:'',
            'rp_tax_declaration_aform' => $arFormNoText,
            'rp_modified_by' => \Auth::user()->id,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->updateData($data->id,$dataToUpdate);
        $this->updateClassedInMainTable($data->id);
      }

      public function syncAssedMarketValueToMainTable($id = ''){
          $data = DB::table('rpt_properties')
                     ->leftJoin('rpt_property_kinds AS pk', 'pk.id', '=', 'rpt_properties.pk_id')
                     ->leftJoin('rpt_property_appraisals', 'rpt_property_appraisals.rp_code', '=', 'rpt_properties.id')
                     ->leftJoin('rpt_property_machine_appraisals as ma', 'ma.rp_code', '=', 'rpt_properties.id')
                     ->leftJoin('rpt_building_floor_values as fv', 'fv.rp_code', '=', 'rpt_properties.id')
                     ->select('rpt_properties.rpo_code','rpt_properties.rp_app_effective_year','rpt_properties.rp_property_code','rpt_properties.id','rpt_properties.rvy_revision_year_id','rpt_properties.brgy_code_id','rpt_properties.pk_id','pk.pk_code',DB::raw('COALESCE(rpt_properties.rp_depreciation_rate,0) as rp_depreciation_rate'),
                DB::raw("CASE 
                            WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_assessed_value,0)) 
                            WHEN pk.pk_code = 'B' THEN SUM(COALESCE(fv.rpb_assessed_value,0)) 
                            WHEN pk.pk_code = 'M' THEN SUM(COALESCE(ma.rpm_assessed_value,0)) 
                            END as assessedValue"),
                DB::raw("CASE 
                            WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_base_market_value,0)) 
                            WHEN pk.pk_code = 'B' THEN SUM(COALESCE(fv.rpbfv_total_floor_market_value,0)) 
                            WHEN pk.pk_code = 'M' THEN SUM(COALESCE(ma.rpma_base_market_value,0)) 
                            END as marketValue"),
                DB::raw("CASE 
                            WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_adjusted_market_value,0)) 
                            WHEN pk.pk_code = 'B' THEN SUM(COALESCE(fv.rpb_assessed_value,0)) 
                            WHEN pk.pk_code = 'M' THEN SUM(COALESCE(ma.rpma_market_value,0)) 
                            END as marketAdjustedValue")
                         )
                     ->where('rpt_properties.id',$id)->first();
                    //dd($data);
         if($data != null){
            if($data->pk_code != 'B'){
                $dataToUpdate = [
                'rp_market_value' => $data->marketValue,
                'rp_market_value_adjustment' => $data->marketAdjustedValue,
                'rp_assessed_value' => $data->assessedValue
               ]; 
            }else{
                $depRate = $data->rp_depreciation_rate;
                $adjustment = $data->marketValue*$depRate/100;
                $dataToUpdate = [
                'rpb_accum_deprec_market_value' => $data->marketValue,
                'rp_market_value_adjustment' => $data->marketValue-$adjustment,
                'rpb_assessed_value' => $data->assessedValue,
                'rp_market_value' => $data->marketValue,
                'rp_market_value_adjustment' => $data->marketValue-$adjustment,
                'rp_assessed_value' => $data->assessedValue
               ];
            }
            //dd($dataToUpdate);
            DB::table('rpt_properties')->where('id',$id)->update($dataToUpdate);
         }
                  
      }


      public function generateTaxDeclarationAndPropertyCode($id = '', $flag = false){
        $data = RptProperty::with([
                'revisionYearDetails' => function($query){
                    $query->select('id','rvy_revision_year');
                },
                'barangay',
                'updatecode',
                'locality'
            ])->where('id',$id)->first();
        //dd($data->barangay);
        /* Records for revision year */
        $noOdRs = RptProperty::where('rvy_revision_year_id',$data->rvy_revision_year_id)
                              ->where('rp_td_no','!=','')
                              ->orderBy('rp_td_no','DESC')
                              ->first();
        if(isset($noOdRs->rp_td_no) && $noOdRs->rp_td_no != ''){
            $noOdRs = $noOdRs->rp_td_no+1;
        }else{
            $noOdRs = 1;
        }
        /* Records for revision year */
        $rpTdNo = str_pad($noOdRs, 5, '0', STR_PAD_LEFT);
        $updateCode = $data->updatecode->uc_code;
        $distCode = $data->dist_code;
        $brgyCode = (isset($data->barangay->brgy_code))?$data->barangay->brgy_code:'';
        $revisionYear = (isset($data->revisionYearDetails->rvy_revision_year))?$data->revisionYearDetails->rvy_revision_year:'';
        $locality     = (isset($data->locality->mun_no))?$data->locality->mun_no:'';
        $taxDeclarationArray = [];
        $prTaxArpNoArray     = [];
        $pinDecalaNoArray    = [];
        if($locality != ''){
            $pinDecalaNoArray[] = $locality;
        }
        if($revisionYear != ''){
            $taxDeclarationArray[] = $revisionYear;
            $prTaxArpNoArray[]     = $revisionYear;
            //$pinDecalaNoArray[]    = $revisionYear;
        }if($distCode != ''){
            $prTaxArpNoArray[]     = $distCode.$brgyCode;
            $pinDecalaNoArray[]    = $distCode;
        }if($brgyCode != ''){
            $taxDeclarationArray[] = $brgyCode;
            $pinDecalaNoArray[]    = $brgyCode;
        }if($rpTdNo != ''){
            $rp_Td_No = $rpTdNo;
            $taxDeclarationArray[] = $rp_Td_No;
            $prTaxArpNoArray[]     = $rp_Td_No;
        }if($data->rp_section_no != ''){
            $pinDecalaNoArray[]    = $data->rp_section_no;
        }if($data->rp_pin_no != ''){
            $pinDecalaNoArray[]    = $data->rp_pin_no;
        }if($data->rp_pin_suffix != ''){
            $pinDecalaNoArray[]    = $data->rp_pin_suffix;
        }

        $taxDeclarationNumber = implode("-", $taxDeclarationArray);
        $prTaxArpNo           = implode("-", $prTaxArpNoArray);
        $pinDecalaNo          = implode("-", $pinDecalaNoArray);
        $locality = $data->locality->mun_no;
        $allPropRecords = RptProperty::all()->count();
        $propertyCode   = $allPropRecords;
        //$propertyCode = $brgyCode.$locality.$distCode.$rpTdNo;
        $dataToUpdate = [
            'rp_property_code' => $propertyCode,
            'rp_td_no' => $rpTdNo,
            'rp_tax_declaration_no' => $taxDeclarationNumber,
            'pr_tax_arp_no'         => $prTaxArpNo
        ];
        if($updateCode != 'DC' || $flag){
            unset($dataToUpdate['rp_property_code']);
        }
        $this->updateData($data->id,$dataToUpdate);
        $this->updatePinDeclarationNumber($data->id);
    }

      public function updatePinDeclarationNumber($id){
            $data = RptProperty::with([
                'revisionYearDetails' => function($query){
                    $query->select('id','rvy_revision_year');
                },
                'barangay',
                'updatecode',
                'locality'
            ])->where('id',$id)->first();

            $locality     = (isset($data->locality->mun_no))?$data->locality->mun_no:'';
            $distCode = $data->dist_code;
            $brgyCode = (isset($data->barangay->brgy_code))?$data->barangay->brgy_code:'';
            $pinDecalaNoArray    = [];
            if($locality != ''){
                $pinDecalaNoArray[] = $locality;
            }if($distCode != ''){
                $prTaxArpNoArray[]     = $distCode.$brgyCode;
                $pinDecalaNoArray[]    = $distCode;
            }if($brgyCode != ''){
                $taxDeclarationArray[] = $brgyCode;
                $pinDecalaNoArray[]    = $brgyCode;
            }if($data->rp_section_no != ''){
                $pinDecalaNoArray[]    = $data->rp_section_no;
            }if($data->rp_pin_no != ''){
                $pinDecalaNoArray[]    = $data->rp_pin_no;
            }if($data->rp_pin_suffix != ''){
                $pinDecalaNoArray[]    = $data->rp_pin_suffix;
            }
            $pinDecalaNo          = implode("-", $pinDecalaNoArray);
            $dataToUpdate = [
            'rp_pin_declaration_no' => $pinDecalaNo
             ];
            $this->updateData($data->id,$dataToUpdate);
        
      }
      /* For Building */

      public function getBuildingList($request){
      
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $propertyKind = ($request->has('property_kind'))?$request->property_kind:'';
        $propertyKindId = $this->getKindIdByCode($propertyKind);
        if(is_numeric($propertyKind)){
            $propertyKindId = $propertyKind;
        }else{
            $propertyKindId = $this->getKindIdByCode($propertyKind);
        }
        //dd($propertyKindId);
        $q=$request->input('q');
        $year = $request->input('year');
        $status = $request->input('status');
        $barangy = $request->input('barangay');
         $request->session()->put('buildingSelectedBrgy',$barangy);
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        if($request->status == '2'){
            $columns = array( 
          1 => "id",     
          2 => "rp_td_no",
          3 => DB::raw("CASE 
                 WHEN pr.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,'')))
                 WHEN pr.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,'')))
                 WHEN pr.suffix IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,'')))
                 WHEN pr.rpo_first_name IS NULL AND pr.rpo_middle_name IS NULL AND pr.suffix IS NULL THEN COALESCE(pr.rpo_custom_last_name,'')
                 ELSE TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,''))) END
                 "),
          4 => "rp_pin_declaration_no",
          5 => "rpb_accum_deprec_market_value",
          6 => "rpb_assessed_value",
          7 => 'pk_is_active'
         );
        }else{
        $columns = array( 
          1 => "rp_td_no",
          2 => DB::raw("CASE 
                 WHEN pr.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,'')))
                 WHEN pr.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,'')))
                 WHEN pr.suffix IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,'')))
                 WHEN pr.rpo_first_name IS NULL AND pr.rpo_middle_name IS NULL AND pr.suffix IS NULL THEN COALESCE(pr.rpo_custom_last_name,'')
                 ELSE TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,''))) END
                 "),
          3 => "bgy.brgy_name",
          4 => "rp_pin_declaration_no",
          5 => "rp_building_unit_no",
          6 => "rpb_accum_deprec_market_value",
          7 => "rpb_assessed_value",
          8 => "uc.uc_description",
          9 => 'rp_app_effective_year',
          10=>'rp_modified_by',
          11=>'created_at',
          12=>'pk_is_active'
         );
    }
           $sql = $this->select(
            'rpt_properties.*',
            'brgy_name',
            'ra.rp_app_cancel_is_direct',
            DB::raw("CONCAT(rp_building_cct_no,'|',rp_building_unit_no) as cct_unit_no")
            )
          ->addSelect(
                [
                    'reg_emp_name' => DB::table('hr_employees AS emp')->select('fullname')
                    ->whereColumn('rp_modified_by', 'emp.user_id'),        
                ]
            )
                 ->with([
                    'propertyOwner',
                    'floorValues'
                ])
                 ->join('clients AS pr', 'pr.id', '=', 'rpt_properties.rpo_code')
                 ->join('barangays AS bgy', 'bgy.id', '=', 'rpt_properties.brgy_code_id')
                 ->join('rpt_update_codes AS uc', 'uc.id', '=', 'rpt_properties.uc_code')
                 ->join('rpt_property_approvals AS ra', 'ra.rp_code', '=', 'rpt_properties.id')
                 ->where('is_deleted',0)
                 ->where('pk_id',$propertyKindId)
                 ->where('pk_is_active',(int)$status);

                 if(!empty($q) && isset($q)){
            if(is_numeric($q)){
                    $sql->havingRaw('buil_market_value = '.$q.' OR buil_assessed_value = '.$q);
                }
            $sql->where(function ($sql) use($q) {
                if(is_numeric($q)){
                    $sql->havingRaw('market_value = '.$q.' OR assessed_value = '.$q);
                }else{
                    //dd(strtolower($q));
                    $sql->orWhere(DB::raw('LOWER(pr.rpo_first_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(pr.full_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(pr.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(pr.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(rpt_properties.rp_tax_declaration_no)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(rpt_properties.rp_pin_declaration_no)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(pr.suffix)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw("CONCAT(rpt_properties.rp_section_no,'-',rpt_properties.rp_pin_no)"),'like',"%".$q."%");
                } 
            });
           
        }
        if(!empty($year) && isset($year)){
            $sql->where(function ($sql) use($year) {
                $sql->where('rpt_properties.rvy_revision_year_id',$year);

            });
        }if(!empty($barangy) && isset($barangy)){
            $sql->where(function ($sql) use($barangy) {
                $sql->where('rpt_properties.brgy_code_id',$barangy);

            });
        }/*if(!empty($status) && isset($status)){
            $sql->where(function ($sql) use($status) {
                $sql->where('rpt_properties.pk_is_active',$status);

            });
        }*/

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column'])){
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
      }else{
          $sql->orderBy('rpt_properties.id','DESC');
      }
    //   return $columns[$params['order'][0]['column']];
        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        //dd($data);
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
      public function getMachineList($request){
      //dd($request->all());
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $propertyKind = ($request->has('property_kind'))?$request->property_kind:'';
        $propertyKindId = $this->getKindIdByCode($propertyKind);
        if(is_numeric($propertyKind)){
            $propertyKindId = $propertyKind;
        }else{
            $propertyKindId = $this->getKindIdByCode($propertyKind);
        }
        $q=$request->input('q');
        $year = $request->input('year');
        $status = $request->input('status');
        $barangy = $request->input('barangay');
         $request->session()->put('machineSelectedBrgy',$barangy);
         //dd(session()->get('machineSelectedBrgy'));
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        if($request->status == '2'){
            $columns = array( 
          1 => "id",     
          2 => "rp_td_no",
          3 => DB::raw("CASE 
                 WHEN pr.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,'')))
                 WHEN pr.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,'')))
                 WHEN pr.suffix IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,'')))
                 WHEN pr.rpo_first_name IS NULL AND pr.rpo_middle_name IS NULL AND pr.suffix IS NULL THEN COALESCE(pr.rpo_custom_last_name,'')
                 ELSE TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,''))) END
                 "),
          4 => "rp_pin_declaration_no",
          5 => DB::raw("(SELECT SUM(rpma_market_value) FROM rpt_property_machine_appraisals WHERE rpt_property_machine_appraisals.rp_code = rpt_properties.id)"),
          6 => DB::raw("(SELECT SUM(rpm_assessed_value) FROM rpt_property_machine_appraisals WHERE rpt_property_machine_appraisals.rp_code = rpt_properties.id)"),
          7=>'pk_is_active'
         );
        }else{
        $columns = array( 
          1 => "rp_td_no",
          2 => DB::raw("CASE 
                 WHEN pr.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,'')))
                 WHEN pr.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,'')))
                 WHEN pr.suffix IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,'')))
                 WHEN pr.rpo_first_name IS NULL AND pr.rpo_middle_name IS NULL AND pr.suffix IS NULL THEN COALESCE(pr.rpo_custom_last_name,'')
                 ELSE TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,''))) END
                 "),
          3 => "b.brgy_name",
          4 => "rp_pin_declaration_no",
          5 => DB::raw("(SELECT GROUP_CONCAT(DISTINCT rpma_description SEPARATOR '; ') FROM rpt_property_machine_appraisals WHERE rpt_property_machine_appraisals.rp_code = rpt_properties.id)"),
          6 => DB::raw("(SELECT SUM(rpma_market_value) FROM rpt_property_machine_appraisals WHERE rpt_property_machine_appraisals.rp_code = rpt_properties.id)"),
          7 => DB::raw("(SELECT SUM(rpm_assessed_value) FROM rpt_property_machine_appraisals WHERE rpt_property_machine_appraisals.rp_code = rpt_properties.id)"),
          8 => "uc.uc_description",
          9 => 'rp_app_effective_year',
          10=>'rp_modified_by',
          11=>'created_at',
          12=>'pk_is_active'
         );
    }
           $sql = $this->select('rpt_properties.*','brgy_name','ra.rp_app_cancel_is_direct',DB::raw("CASE 
        WHEN pr.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,'')))WHEN pr.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_custom_last_name,''),', ',COALESCE(pr.suffix,'')))WHEN pr.suffix IS NULL THEN TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,'')))WHEN pr.rpo_first_name IS NULL AND pr.rpo_middle_name IS NULL AND pr.suffix IS NULL THEN COALESCE(pr.rpo_custom_last_name,'')ELSE TRIM(CONCAT(COALESCE(pr.rpo_first_name,''),' ',COALESCE(pr.rpo_middle_name,''),' ',COALESCE(pr.rpo_custom_last_name,''))) END as ownar_name"))
                 ->addSelect(
                    [
                        'reg_emp_name' => DB::table('hr_employees AS emp')->select('fullname')
                                       ->whereColumn('rp_modified_by', 'emp.user_id'),
                        'description' => RptPropertyMachineAppraisal::select(DB::raw("GROUP_CONCAT(DISTINCT  rpma_description SEPARATOR '; ')"))->whereColumn('rp_code', 'rpt_properties.id')->groupBy('rp_code'),
                        'machine_market_value' => RptPropertyMachineAppraisal::select(DB::raw("SUM(rpma_market_value) AS machine_market_value"))
                                       ->whereColumn('rp_code', 'rpt_properties.id'),
                        'machine_assessed_value' => RptPropertyMachineAppraisal::select(DB::raw("SUM(rpm_assessed_value)"))
                                       ->whereColumn('rp_code', 'rpt_properties.id')             
                ]
            )
                 ->with([
                    'propertyOwner',
                    'floorValues'
                ])
                 ->join('barangays AS b', 'b.id', '=', 'rpt_properties.brgy_code_id')
                 ->join('clients AS pr', 'pr.id', '=', 'rpt_properties.rpo_code')
                 ->join('rpt_update_codes AS uc', 'uc.id', '=', 'rpt_properties.uc_code')
                 ->join('rpt_property_approvals AS ra', 'ra.rp_code', '=', 'rpt_properties.id')
                 ->where('is_deleted',0)
                 ->where('pk_id',$propertyKindId)  
                 ->where('pk_is_active',(int)$status);
        if(!empty($q) && isset($q)){
            if(is_numeric($q)){
                    $sql->havingRaw('machine_market_value = '.$q.' OR machine_assessed_value = '.$q);
                }
            $sql->where(function ($sql) use($q) {
                if(is_numeric($q)){
                    $sql->havingRaw('market_value = '.$q.' OR assessed_value = '.$q);
                }else{
                    //dd(strtolower($q));
                    $sql->orWhere(DB::raw('LOWER(pr.rpo_first_name)'),'like',"%".strtolower($q)."%")
                         ->orWhere(DB::raw("CONCAT(pr.rpo_first_name, ' ', COALESCE(pr.rpo_middle_name, ''), ' ',pr.rpo_custom_last_name)"), 'LIKE', "%{$q}%")
                        ->orWhere(DB::raw("CONCAT(pr.rpo_first_name, ' ', COALESCE(pr.rpo_middle_name, ''), ' ', COALESCE(pr.rpo_custom_last_name), ', ', pr.suffix)"), 'LIKE', "%{$q}%") 
                        ->orWhere(DB::raw('LOWER(rpt_properties.rp_tax_declaration_no)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(rpt_properties.rp_pin_declaration_no)'),'like',"%".strtolower($q)."%")
                         ->orWhere(DB::raw('LOWER(pr.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(pr.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(pr.suffix)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw("CONCAT(rpt_properties.rp_section_no,'-',rpt_properties.rp_pin_no)"),'like',"%".$q."%");
                }
                     
            });
           
        }         
      
        if(!empty($year) && isset($year)){
            $sql->where(function ($sql) use($year) {
                $sql->where('rvy_revision_year_id',$year);

            });
        }if(!empty($barangy) && isset($barangy)){
            $sql->where(function ($sql) use($barangy) {
                $sql->where('brgy_code_id',$barangy);

            });
        }/*if(!empty($status) && isset($status)){
            $sql->where(function ($sql) use($status) {
                $sql->where('rpt_properties.pk_is_active',$status);

            });
        }*/

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column'])){
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
      }else{
          $sql->orderBy('rpt_properties.id','DESC');
      }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        //dd($data);
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

      public function addFloorValueDetail($postdata,$updateCode = ''){
        DB::table('rpt_building_floor_values')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function updateFloorValueDetail($id,$columns,$updateCode = ''){
        return DB::table('rpt_building_floor_values')->where('id',$id)->update($columns);
         
    }

      public function getPropertyBuildingKinds($value=''){
        return RptBuildingKind::where('bk_is_active',1)->get();
      }

      public function getBuildingUnitValue($request){
            $activeMunci = $this->activeMuncipality;
            $searchBrgy  = $this->activeBarangay->where('id',$request->baranGy);
            if($activeMunci != null && $activeMunci->loc_group_default_barangay_id > 0 && !$searchBrgy->isEmpty()){
                $defaultBrgyGroup = $activeMunci->loc_group_default_barangay_id;
            }else{
                $defaultBrgyGroup = 0;
            }
          return RptBuildingUnitValue::where('loc_group_brgy_no',($defaultBrgyGroup != 0)?$defaultBrgyGroup:$request->baranGy)
                                     ->where('bk_building_kind_code',$request->buildingKing)
                                     ->where('bt_building_type_code',$request->bt_building_type_code)
                                     ->where('rvy_revision_year',$request->revisionYearId)
                                     ->where('is_approve',1)
                                     ->where('buv_is_active',1)
                                     ->first();
      }

      public function getBuildAdditionalItems($value='')
      {
          return RptBuildingExtraItem::where('bei_is_active',1)->get();
      }

      public function floorValues(){
        return $this->hasMany(RptBuildingFloorValue::class,'rp_code');
    }

    public function machineAppraisals(){
        return $this->hasMany(RptPropertyMachineAppraisal::class,'rp_code');
    }

      public function buildingReffernceLand(){
        return $this->belongsTo(RptProperty::class,'rp_code_lref')->where('is_deleted',0);
    }

    public function machineReffernceLand(){

        return $this->belongsTo(RptProperty::class,'rp_code_lref')->where('is_deleted',0);
    }

    public function machineReffernceBuild(){
        return $this->belongsTo(RptProperty::class,'rp_code_bref')->where('is_deleted',0);
    }

      public function getBuildingTypes($buildKind = '',$revisionyear = ''){
        $activeMunci = $this->activeMuncipality;
            $searchBrgy  = $this->activeBarangay->where('id',session()->get('buildingSelectedBrgy'));
            if($activeMunci != null && $activeMunci->loc_group_default_barangay_id > 0 && !$searchBrgy->isEmpty()){
                $defaultBrgyGroup = $activeMunci->loc_group_default_barangay_id;
            }else{
                $defaultBrgyGroup = 0;
            }
        $data = DB::table('rpt_building_unit_values as rbuv')
                   ->select('rbt.id','rbt.bt_building_type_code','rbt.bt_building_type_desc')
                   ->where('rbuv.bk_building_kind_code',$buildKind)
                   ->join('rpt_building_types as rbt','rbt.id','=','rbuv.bt_building_type_code');
        if(isset($revisionyear->id) && $revisionyear->id != ''){
           $data->where('rbuv.rvy_revision_year',$revisionyear->id);
        }if(session()->has('buildingSelectedBrgy') && session()->get('buildingSelectedBrgy') != ''){
           $data->where('rbuv.loc_group_brgy_no',($defaultBrgyGroup != 0)?$defaultBrgyGroup:session()->get('buildingSelectedBrgy'));
        }           

        return $data->get()->toArray();           
     }

     public function getPropertyActualUse($id=''){
            $activeMunci = $this->activeMuncipality;
            $searchBrgy  = $this->activeBarangay->where('id',session()->get('buildingSelectedBrgy'));
            if($activeMunci != null && $activeMunci->loc_group_default_barangay_id > 0 && !$searchBrgy->isEmpty()){
                $defaultBrgyGroup = $activeMunci->loc_group_default_barangay_id;
            }else{
                $defaultBrgyGroup = 0;
            }
            $data = DB::table('rpt_assessment_levels as ral')
                   ->select('rpau.id','rpau.pau_actual_use_code','rpau.pau_actual_use_desc')
                   ->where('ral.pc_class_code',$id)
                   ->where('ral.pk_code',1)
                   ->join('rpt_property_actual_uses as rpau','rpau.id','=','ral.pau_actual_use_code');
        if(isset($revisionyear->id) && $revisionyear->id != ''){
           $data->where('ral.rvy_revision_year',$revisionyear->id);
        }if(session()->has('buildingSelectedBrgy') && session()->get('buildingSelectedBrgy') != ''){
           $data->where('ral.loc_group_brgy_no',($defaultBrgyGroup != 0)?$defaultBrgyGroup:session()->get('buildingSelectedBrgy'));
        }           

        return $data->get()->toArray(); 
     }

     public function getPropertyActualUseForExcelUpload(){
            $activeMunci = $this->activeMuncipality;
            $searchBrgy  = $this->activeBarangay->where('id',session()->get('buildingSelectedBrgy'));
            if($activeMunci != null && $activeMunci->loc_group_default_barangay_id > 0 && !$searchBrgy->isEmpty()){
                $defaultBrgyGroup = $activeMunci->loc_group_default_barangay_id;
            }else{
                $defaultBrgyGroup = 0;
            }
            $data = DB::table('rpt_assessment_levels as ral')
                   ->select('rpau.id','rpau.pau_actual_use_code','rpau.pau_actual_use_desc','class.pc_class_description')
                   ->where('ral.pk_code',1)
                   ->join('rpt_property_actual_uses as rpau','rpau.id','=','ral.pau_actual_use_code')
                   ->join('rpt_property_classes as class','class.id','=','ral.pc_class_code');
        if(isset($revisionyear->id) && $revisionyear->id != ''){
           $data->where('ral.rvy_revision_year',$revisionyear->id);
        }if(session()->has('buildingSelectedBrgy') && session()->get('buildingSelectedBrgy') != ''){
           $data->where('ral.loc_group_brgy_no',($defaultBrgyGroup != 0)?$defaultBrgyGroup:session()->get('buildingSelectedBrgy'));
        }           

        return $data->get()->toArray(); 
     }

     public function getPropertyClassesForExcelUpload(){
            $activeMunci = $this->activeMuncipality;
            $searchBrgy  = $this->activeBarangay->where('id',session()->get('buildingSelectedBrgy'));
            if($activeMunci != null && $activeMunci->loc_group_default_barangay_id > 0 && !$searchBrgy->isEmpty()){
                $defaultBrgyGroup = $activeMunci->loc_group_default_barangay_id;
            }else{
                $defaultBrgyGroup = 0;
            }
            $data = DB::table('rpt_assessment_levels as ral')
                   ->select('class.id','class.pc_class_description')
                   ->where('ral.pk_code',1)
                   ->join('rpt_property_classes as class','class.id','=','ral.pc_class_code');
        if(isset($revisionyear->id) && $revisionyear->id != ''){
           $data->where('ral.rvy_revision_year',$revisionyear->id);
        }if(session()->has('buildingSelectedBrgy') && session()->get('buildingSelectedBrgy') != ''){
           $data->where('ral.loc_group_brgy_no',($defaultBrgyGroup != 0)?$defaultBrgyGroup:session()->get('buildingSelectedBrgy'));
        }           

        return $data->get()->toArray(); 
     }

     public function getPropertyBuildKindsForExcelUpload(){
            $activeMunci = $this->activeMuncipality;
            $searchBrgy  = $this->activeBarangay->where('id',session()->get('buildingSelectedBrgy'));
            if($activeMunci != null && $activeMunci->loc_group_default_barangay_id > 0 && !$searchBrgy->isEmpty()){
                $defaultBrgyGroup = $activeMunci->loc_group_default_barangay_id;
            }else{
                $defaultBrgyGroup = 0;
            }
            $data = DB::table('rpt_building_unit_values as ruv')
                   ->select('bk.id','bk.bk_building_kind_desc')
                   ->where('ruv.buv_is_active',1)
                   ->where('ruv.is_approve',1)
                   ->join('rpt_building_kinds as bk','bk.id','=','ruv.bk_building_kind_code')
                   ->groupBy('bk.id');
        if(isset($revisionyear->id) && $revisionyear->id != ''){
           $data->where('ruv.rvy_revision_year',$revisionyear->id);
        }if(session()->has('buildingSelectedBrgy') && session()->get('buildingSelectedBrgy') != ''){
           $data->where('ruv.loc_group_brgy_no',($defaultBrgyGroup != 0)?$defaultBrgyGroup:session()->get('buildingSelectedBrgy'));
        }           

        return $data->get()->toArray(); 
     }

     public function getPropertyBuildingroof($value=''){
        return DB::table('rpt_building_roofings')->select('id','rbr_building_roof_desc')->where('is_active','1')->get()->toArray();
     }

     public function getPropertyBuildingrfloor($value=''){
        return DB::table('rpt_building_floorings')->select('id','rbf_building_flooring_desc')->where('rbf_is_active','1')->get()->toArray();
     }

     public function getPropertyBuildingwall($value=''){
        return DB::table('rpt_building_wallings')->select('id','rbw_building_walling_desc')->where('rbw_is_active','1')->get()->toArray();
     }

     /* For Building */

     /* For Machinary */
     public function getMachineryAppraisalDetals($id = ''){
        return DB::table('rpt_property_appraisals as ap')
                   ->join('rpt_property_classes AS class', 'ap.pc_class_code', '=', 'class.id')
                   ->join('rpt_property_subclassifications AS sub', 'ap.ps_subclass_code', '=', 'sub.id')
                   ->join('rpt_property_actual_uses AS au', 'ap.pau_actual_use_code', '=', 'au.id')
                   ->select('ap.*','class.pc_class_description','sub.ps_subclass_desc','au.pau_actual_use_desc')
                   ->where('ap.rp_code',(int)$id)
                   ->get();
    }
    /* For Machinary */
   //convert int to word
    public function numberToWord($num = '')
    {
        $num    = ( string ) ( ( int ) $num );
        
        if( ( int ) ( $num ) && ctype_digit( $num ) )
        {
            $words  = array( );
             
            $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
             
            $list1  = array('','one','two','three','four','five','six','seven',
                'eight','nine','ten','eleven','twelve','thirteen','fourteen',
                'fifteen','sixteen','seventeen','eighteen','nineteen');
             
            $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
                'seventy','eighty','ninety','hundred');
             
            $list3  = array('','thousand','million','billion','trillion',
                'quadrillion','quintillion','sextillion','septillion',
                'octillion','nonillion','decillion','undecillion',
                'duodecillion','tredecillion','quattuordecillion',
                'quindecillion','sexdecillion','septendecillion',
                'octodecillion','novemdecillion','vigintillion');
             
            $num_length = strlen( $num );
            $levels = ( int ) ( ( $num_length + 2 ) / 3 );
            $max_length = $levels * 3;
            $num    = substr( '00'.$num , -$max_length );
            $num_levels = str_split( $num , 3 );
             
            foreach( $num_levels as $num_part )
            {
                $levels--;
                $hundreds   = ( int ) ( $num_part / 100 );
                $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
                $tens       = ( int ) ( $num_part % 100 );
                $singles    = '';
                 
                if( $tens < 20 ) { $tens = ( $tens ? ' ' . $list1[$tens] . ' ' : '' ); } else { $tens = ( int ) ( $tens / 10 ); $tens = ' ' . $list2[$tens] . ' '; $singles = ( int ) ( $num_part % 10 ); $singles = ' ' . $list1[$singles] . ' '; } $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' ); } $commas = count( $words ); if( $commas > 1 )
            {
                $commas = $commas - 1;
            }
             
            $words  = implode( ', ' , $words );
             
            $words  = trim( str_replace( ' ,' , ',' , ucwords( $words ) )  , ', ' );
            if( $commas )
            {
                $words  = str_replace( ',' , ' and' , $words );
            }
             
            return $words;
        }
        else if( ! ( ( int ) $num ) )
        {
            return 'Zero';
        }
        return '';
    }

    public function getpropertiesbyPin($pid,$id){
          return DB::table('rpt_properties')
                    ->select('id','rp_pin_no')->where('id','<>',$id)->where('rp_pin_no',$pid)->where('pk_is_active',1)->get();
    }
    public function getTaxDeclarationnumofnewtd($rp_property_code){
            return DB::table('rpt_properties')
                    ->select('id','rp_tax_declaration_no')->where('rp_property_code',$rp_property_code)->where('uc_code',11)->where('pk_is_active',1)->first();
    }

    public function getLandTds($revisionYear = ''){
        //dd(session()->get('buildingSelectedBrgy'));
        $query = DB::table('rpt_properties')
        ->where('rvy_revision_year_id',$revisionYear->id)
        ->where('brgy_code_id',session()->get('buildingSelectedBrgy'))
        ->where('pk_is_active',1)
                 ->select('rpt_properties.id','rpt_properties.rp_tax_declaration_no','rpt_properties.rp_td_no')
                 ->get();            
        return $query;                
    }

    public function getBuildingpropertycount($id, $propId){
        //dd($id);
        $query = DB::table('rpt_properties')
        ->where('pk_id',config('constants.rptKinds.B'))
        ->where('rp_code_lref',$id)->select('id')/*->groupBy('rp_property_code')*/;
        if($propId > 0){
            $query->where('id','!=',$propId);  
        }
        //dd($query->get());
         $count = $query->get();            
        return count($count); 
    }

    public function getMachinerypropertycount($id, $propId){
        $query = DB::table('rpt_properties')
        ->where('pk_id',config('constants.rptKinds.M'))
        ->where('rp_code_lref',$id)->select('id')->groupBy('rp_property_code');
        if($propId > 0){
            $query->where('id','!=',$propId);  
        }
         $count = $query->get();            
        return count($count); 
    }

    public function getQtrlyAssessmentList($request){
        
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $year=$request->input('year');
        $qtr=$request->input('qtr');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $additionalWhereCond = '';
        if(!empty($year) && isset($year)){
            $additionalCondYear = "DATE_FORMAT(rp.created_at,'%Y') = ".$year;
            $additionalCondYearN = " AND DATE_FORMAT(created_at,'%Y') = ".$year;
        }
        if(!empty($qtr) && isset($qtr)){
            if($qtr == 1){
                $range = '(1,2,3)';
            }if($qtr == 2){
                $range = '(4,5,6)';
            }if($qtr == 3){
                $range = '(7,8,9)';
            }if($qtr == 4){
                $range = '(10,11,12)';
            }if($qtr == 'all'){
                $range = '(1,2,3,4,5,6,7,8,9,10,11,12)';
            }
            $additionalCondQtr = "DATE_FORMAT(rp.created_at,'%m') IN ".$range;
            $additionalCondQtrN = " AND DATE_FORMAT(created_at,'%m') IN ".$range;
        }
       // dd($additionalCondQtr);
        $buildMarketValueLessQuery = 'SELECT SUM(rpb_accum_deprec_market_value) FROM rpt_properties WHERE pc_class_code = rpt_property_classes.id AND rpb_accum_deprec_market_value < 175000 AND pk_is_active = 1 AND is_deleted = 0';
        $buildMarketValueAboveQuery = 'SELECT SUM(rpb_accum_deprec_market_value) FROM rpt_properties WHERE pc_class_code = rpt_property_classes.id AND rpb_accum_deprec_market_value > 175000 AND pk_is_active = 1 AND is_deleted = 0';
        $buildAssValueLessQuery = 'SELECT SUM(rpb_assessed_value) FROM rpt_properties WHERE pc_class_code = rpt_property_classes.id AND rpb_assessed_value < 175000 AND pk_is_active = 1 AND is_deleted = 0';
        $buildAssValueAboveQuery = 'SELECT SUM(rpb_assessed_value) FROM rpt_properties WHERE pc_class_code = rpt_property_classes.id AND rpb_assessed_value > 175000 AND pk_is_active = 1 AND is_deleted = 0';
        if(isset($additionalCondYearN) && !empty($additionalCondYearN)){
            $buildMarketValueLessQuery .= $additionalCondYearN;
            $buildMarketValueAboveQuery .= $additionalCondYearN;
            $buildAssValueLessQuery .= $additionalCondYearN;
            $buildAssValueAboveQuery .= $additionalCondYearN;
        }
        if(isset($additionalCondQtrN) && !empty($additionalCondQtrN)){
            $buildMarketValueLessQuery .= $additionalCondQtrN;
            $buildMarketValueAboveQuery .= $additionalCondQtrN;
            $buildAssValueLessQuery .= $additionalCondQtrN;
            $buildAssValueAboveQuery .= $additionalCondQtrN;
        }
        //dd($buildMarketValueLessQuery);
        $columns = array( 
          1 => "rpt_property_classes.pc_class_description",
          2 => DB::raw('COUNT(DISTINCT(rp.id))'),
          3 => DB::raw('CASE WHEN pa.lav_unit_measure = 2 THEN (SUM(COALESCE(pa.rpa_total_land_area,0))*10000) ELSE SUM(COALESCE(pa.rpa_total_land_area,0)) END'),
          4 => DB::raw('SUM(COALESCE(pa.rpa_adjusted_market_value,0))'),
          5 => DB::raw('rpb_accum_deprec_market_value'),
          6 =>DB::raw('rpb_accum_deprec_market_value'),
          7 => DB::raw('SUM(COALESCE(ma.rpma_market_value,0))'),
          8 => "rpt_property_classes.id",
          9 => DB::raw('(SUM(COALESCE(ma.rpma_market_value,0))+SUM(COALESCE(pa.rpa_adjusted_market_value,0))+SUM(COALESCE(rp.rpb_accum_deprec_market_value,0)))'),
          10 => DB::raw('SUM(COALESCE(pa.rpa_assessed_value,0))'),

          11 => DB::raw('rpb_assessed_value'),
          12 => DB::raw('rpb_assessed_value'),

          13 => DB::raw('SUM(COALESCE(ma.rpm_assessed_value,0))'),
          14 => "rpt_property_classes.id",
          15 => DB::raw('(SUM(COALESCE(ma.rpm_assessed_value,0))+SUM(COALESCE(pa.rpa_assessed_value,0))+SUM(COALESCE(rp.rpb_assessed_value,0)))'),
         );
            $sql = DB::table('rpt_properties as rp')
                 ->leftJoin('rpt_property_appraisals as pa',function($j){
                    $j->on('pa.rp_code','=','rp.id');
                 }) 
                 ->leftJoin('rpt_property_machine_appraisals as ma',function($j){
                    $j->on('ma.rp_code','=','rp.id');
                 })
                 ->join('rpt_property_classes',function($j){
                    $j->orOn('rpt_property_classes.id','=','pa.pc_class_code')
                        ->orOn('rpt_property_classes.id','=','ma.pc_class_code')
                        ->orOn('rpt_property_classes.id','=','rp.pc_class_code');

                 })
                 ->select(
                    'rpt_property_classes.pc_class_description','rpt_property_classes.id','pa.id as paid','ma.id as maid','rp.pk_id',
                    DB::raw('(SUM(COALESCE(ma.rpm_assessed_value,0))+SUM(COALESCE(pa.rpa_assessed_value,0))+SUM(COALESCE(rp.rpb_assessed_value,0))) as totalAssessedValue'),
                    DB::raw('(SUM(COALESCE(ma.rpma_market_value,0))+SUM(COALESCE(pa.rpa_adjusted_market_value,0))+SUM(COALESCE(rp.rpb_accum_deprec_market_value,0))) as totalMarketValue'),
                    DB::raw('CASE WHEN pa.lav_unit_measure = 2 THEN (SUM(COALESCE(pa.rpa_total_land_area,0))*10000) ELSE SUM(COALESCE(pa.rpa_total_land_area,0)) END as totalAreaInSqm'),
                    DB::raw('COUNT(DISTINCT(rp.id)) as realpropertyUnits'),
                    DB::raw('SUM(COALESCE(pa.rpa_adjusted_market_value,0)) as landMarketValue'),
                    DB::raw('('.$buildMarketValueLessQuery.')'.' as buildingMarketValueLess'),
                    DB::raw('('.$buildMarketValueAboveQuery.')'.' as buildingMarketValueabove'),
                    DB::raw('SUM(COALESCE(rp.rpb_accum_deprec_market_value,0))'),
                    DB::raw('SUM(COALESCE(ma.rpma_market_value,0)) as machineMarketValue'),
                    DB::raw('SUM(COALESCE(rp.rpb_accum_deprec_market_value,0))'),
                    DB::raw('SUM(COALESCE(ma.rpm_assessed_value,0)) as machineAssessedValue'),
                    DB::raw('SUM(COALESCE(pa.rpa_assessed_value,0)) as landAssessedValue'),
                    DB::raw('SUM(COALESCE(rp.rpb_assessed_value,0)) as buildingAssessedValue'),

                    DB::raw('('.$buildAssValueLessQuery.')'.' as buildingAssessedValueLess'),
                    DB::raw('('.$buildAssValueAboveQuery.')'.' as buildingAssessedValueAbove'),
                    
                    )
                 ->where('rp.pk_is_active',1)
                 ->where('rp.is_deleted',0)
                 ->groupBy('rpt_property_classes.id');     
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q,$columns) {
                    $sql->orWhere($columns[1],'like',"%".strtolower($q)."%");
                        
            });
           
        }
        if(isset($additionalCondYear) && !empty($additionalCondYear)){
            $sql->where(function ($sql) use($additionalCondYear) {
                    $sql->whereRaw($additionalCondYear);
                        
            });
           
        }
        if(isset($additionalCondQtr) && !empty($additionalCondQtr)){
            $sql->where(function ($sql) use($additionalCondQtr) {
                    $sql->whereRaw($additionalCondQtr);
                        
            });
           
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column'])){
                $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }else{
            $sql->orderBy('rpt_property_classes.id','DESC');
        }
    //   return $columns[$params['order'][0]['column']];
        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->get()->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
		
        $data=$sql->get();
        //dd($data);
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

  public function getAssessmentList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $status = $request->input('pk_is_active');
        $q=$request->input('q');
        $taxdeclairdetail = $request->input('taxdeclairdetail');
        $barangy = $request->input('barangay');
        $request->session()->put('landSelectedBrgy',$barangy);
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $columns = array( 
          1 => "rpt_properties.rp_tax_declaration_no",
          2 => "pr.full_name",
          3 => "brgy_name",
          5 => "rp_pin_declaration_no",
		  6 => "rp_cadastral_lot_no",
          7 => "market_value",
          8 => "assessed_value",
          9 => "uc.uc_code",
          10 => "rp_app_effective_year",
          11 => "reg_emp_name",
          12 => "created_at",
          13 => "rpt_properties.pk_is_active"
         );
           $sql = $this->select('rpt_properties.*','brgy_name')
                 ->addSelect(
                    [
                        'reg_emp_name' => DB::table('hr_employees AS emp')->select('fullname')
                                       ->whereColumn('rp_modified_by', 'emp.user_id')             
                ]
            )
                 ->with([
                    'propertyOwner',
                    'landAppraisals',
                    'updatecode'

                ])
                 ->join('barangays AS bgy', 'bgy.id', '=', 'rpt_properties.brgy_code_id')
                 ->join('clients AS pr', 'pr.id', '=', 'rpt_properties.rpo_code')
                 ->join('rpt_update_codes AS uc', 'uc.id', '=', 'rpt_properties.uc_code')
                 ->where('is_deleted',0)
                 ->where('pk_is_active',(int)$status);
        if(!empty($q) && isset($q)){
            if(is_numeric($q)){
                    $sql->havingRaw('market_value = '.$q.' OR assessed_value = '.$q);
                }
            $sql->where(function ($sql) use($q) {
                    $sql->orWhere(DB::raw('LOWER(pr.rpo_first_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(pr.full_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(rpt_properties.rp_tax_declaration_no)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(pr.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(pr.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(pr.suffix)'),'like',"%".strtolower($q)."%")
                         ->orWhere(DB::raw('LOWER(brgy_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(rp_cadastral_lot_no)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(uc.uc_code)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(rpt_properties.rp_app_effective_year)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw("CONCAT(rpt_properties.rp_section_no,'-',rpt_properties.rp_pin_no)"),'like',"%".$q."%");
            });
           
        }
        if(!empty($taxdeclairdetail) && isset($taxdeclairdetail)){
            $sql->where(function ($sql) use($taxdeclairdetail) {
                $sql->where('rpt_properties.id',$taxdeclairdetail);

            });
        }if(!empty($barangy) && isset($barangy)){
            $sql->where(function ($sql) use($barangy) {
                $sql->where('brgy_code_id',$barangy);

            });
        }/*if(!empty($status) && isset($status)){
            
                $sql->where('pk_is_active',(int)$status);

            
        }*/

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column'])){
            if($params['order'][0]['column'] == 2){
                $sql->orderByRaw('COALESCE(rpo_first_name, COALESCE(rpo_middle_name, rpo_custom_last_name)) '.$params['order'][0]['dir'].'');
            }else{
                $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
            }
        }else{
            $sql->orderBy('rpt_properties.id','DESC');
        }
    //   return $columns[$params['order'][0]['column']];
        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        //dd($data);
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

   public  function getTaxdecwithName(){
      $query = DB::table('rpt_properties')->leftjoin('clients as c','rpt_properties.rpo_code','=','c.id')
        ->where('is_deleted',0)
        ->where('pk_is_active',1)
                 ->select('rpt_properties.id','rpt_properties.rp_tax_declaration_no','rpo_custom_last_name','rpo_first_name','rpo_middle_name')
                 ->get();            
        return $query;                
   }

   public function checkToVerifyPsw($id = ''){
          $data = DB::table('rpt_properties as rp')
                    ->join('rpt_property_approvals as rpa','rpa.rp_code','=','rp.id')
                    ->select('rp.pk_is_active','rpa.rp_app_cancel_is_direct','rp.pk_id')
                    ->where('rp.id',$id)
                    ->first();          
          if($data != null){
            $sessionKey = ($data->pk_id == 2)?'verifyPswLand':(($data->pk_id == 1) ? 'verifyPswBuilding' : 'verifyPswMachine');
            $pswVeriStatus = (session()->has($sessionKey))?((session()->get($sessionKey) == true)?true:false):false;
            if($data->pk_is_active == 0 && $data->rp_app_cancel_is_direct == 0 && $pswVeriStatus == false){
                return true;
            }if($data->pk_is_active == 0 && $data->rp_app_cancel_is_direct == 0 && $pswVeriStatus == true){
                return false;
            }

          }          
      }  

   public function getbuilPermits($request=''){
        $term=$request->input('term');
        $query = DB::table('eng_bldg_permit_apps')->select('ebpa_permit_no as text','id')->where('ebpa_permit_no','!=','');
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(rp_tax_declaration_no)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;
       }    

   public function updateClassedInMainTable($rpCode=''){
       $rpObj = DB::table('rpt_properties')->where('id',$rpCode);
       $data  = $rpObj->select('id','pk_id','pc_class_code')->first();
       if($data != null){
       if($data->pk_id == 2){
          $classesQuery = DB::table('rpt_property_appraisals as rpa')
                     ->select(
                        DB::raw("GROUP_CONCAT(DISTINCT pc.pc_class_code SEPARATOR '; ') as rp_class")
                     )
                     ->where('rpa.rp_code',$rpCode)
                     ->join('rpt_property_classes as pc','pc.id','=','rpa.pc_class_code')
                     ->first();
          $classes = $classesQuery->rp_class;
       }if($data->pk_id == 3){
          $classesQuery = DB::table('rpt_property_machine_appraisals as ma')
                     ->select(
                        DB::raw("GROUP_CONCAT(DISTINCT pc.pc_class_code SEPARATOR '; ') as rp_class")
                     )
                     ->where('ma.rp_code',$rpCode)
                     ->join('rpt_property_classes as pc','pc.id','=','ma.pc_class_code')
                     ->first();
          $classes = $classesQuery->rp_class;
       }if($data->pk_id == 1){
          $classesQuery = DB::table('rpt_property_classes as pc')->select('pc.pc_class_code as rp_class')->where('pc.id',$data->pc_class_code)->first();
          $classes = $classesQuery->rp_class;
       }
       $this->updateData($data->id,['rp_class' => $classes]);
   }
   }

   public function getTdsForAjaxSelectList($request=''){
       $term=$request->input('term');
       $pkid=$request->input('pk_id');
       $rpocode=$request->input('rpo_code');
       $column=$request->input('column');
       $brgy=($request->has('brgy_code_id'))?$request->input('brgy_code_id'):0;
       $callFrom=($request->has('callfrom'))?$request->input('callfrom'):'';
       $billingmode = ($request->has('billingmode'))?$request->input('billingmode'):'';
       $revision=($request->has('rvy_revision_year_id'))?$request->input('rvy_revision_year_id'):0;
        $query = DB::table('rpt_properties as rp')->join('clients as c','c.id','=','rp.rpo_code')->where('rp.is_deleted',0)->orderBy('rp.id','desc');
        if($column == 'id'){
            $query->select('rp.id','rp.rp_tax_declaration_no as text');
        }if($column == 'tdwithcustomer'){
            $query->select('rp.id',DB::raw("CONCAT('[',rp.rp_tax_declaration_no,'=>',c.full_name,']') as text"));
        }if($callFrom == 'billing'){
            $query->select('rp.rp_td_no as id','rp.rp_tax_declaration_no as text');
        }
        if($callFrom == 'billing'){
            $query->whereIn('rp.pk_is_active',[1]);
        }if($callFrom == 'consolidation'){
            $query->whereIn('rp.pk_is_active',[1]);
        }else{
            $query->whereIn('rp.pk_is_active',[1,0]);
        }
        if($callFrom == 'billing' && $billingmode != ''){
            if($billingmode == 0){
                $query->where('rp.brgy_code_id',session()->get('billingSelectedBrgy'));
            }

        }
        if($column == 'id' || $callFrom == 'billing'){
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(rp.rp_tax_declaration_no)'),'like',"%".strtolower($term)."%");
            });

        } 
        }
        if($column == 'tdwithcustomer'){
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(rp.rp_tax_declaration_no)'),'like',"%".strtolower($term)."%")
                ->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($term)."%");
            });

        } 
        }
             
        if(isset($pkid) && $pkid > 0){
            $query->where(function ($sql) use($pkid) {   
                $sql->where('rp.pk_id',$pkid);
            });

        } 
        if(isset($rpocode) && $rpocode > 0){
            $query->where(function ($sql) use($rpocode) {   
                $sql->where('rp.rpo_code',$rpocode);
            });

        }
        if(isset($brgy) && $brgy > 0){
            $query->where(function ($sql) use($brgy) {   
                $sql->where('rp.brgy_code_id',$brgy);
            });

        }if(isset($revision) && $revision > 0){
            $query->where(function ($sql) use($revision) {   
                $sql->where('rp.rvy_revision_year_id',$revision);
            });

        }
        $data = $query->simplePaginate(20);             
        return $data;
   }

   public function dashboardStdQuery(){
       $query = DB::table('rpt_properties as rp')
                    ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
                    ->where('rp.is_deleted',0)
                    ->where('rp.pk_is_active',1);
       return $query; 
   }

   public function loadDashboardData($data = []){
      // $standardQuery = 
       $data['activeLandTds'] = $this->dashboardStdQuery()->select(
                                        DB::raw('count(rp.id) as count'),
                                    )->where('rp.pk_id',2)
                                    ->first();
        $data['activeBuildTds'] = $this->dashboardStdQuery()->select(
                                        DB::raw('count(rp.id) as count'),
                                    )->where('rp.pk_id',1)
                                    ->first();  
        $data['activeMachineTds'] = $this->dashboardStdQuery()->select(
                                        DB::raw('count(rp.id) as count'),
                                    )->where('rp.pk_id',3)
                                    ->first();     
        $data['discoveryTds'] = $this->dashboardStdQuery()->select(
                                        DB::raw('count(rp.id) as count'),
                                    )->join('rpt_update_codes as uc',function($j){
                                        $j->on('rp.uc_code','=','uc.id')->where('uc.uc_new_fresh',1)->where('uc.uc_is_active',1);
                                    })
                                    ->first();    
        $data['newTds'] = $this->dashboardStdQuery()->select(
                                        'c.full_name','rp.rp_tax_declaration_aform','rp.rp_pin_declaration_no','rp.rp_assessed_value','rp.created_at'
                                    )->join('clients as c',function($j){
                                        $j->on('rp.rpo_code','=','c.id');
                                    })->orderBy('rp.id','DESC')->limit(5)->get();      
       $data['directCancelTds'] = DB::table('rpt_properties as rp')->select(
                                        DB::raw('count(rp.id) as count'),
                                    )->join('rpt_property_approvals as rpa','rpa.rp_code','=','rp.id')
                                     ->where('rpa.rp_app_cancel_is_direct',1)
                                     ->where('rp.pk_is_active',0)
                                     ->where('rp.is_deleted',0)
                                    ->first();      
       $data['grCount'] = $this->dashboardStdQuery()->select(
                                        DB::raw('count(rp.id) as count'),
                                    )->where('rp.uc_code',config('constants.update_codes_land.GR'))
                                    ->first();                                                                                          
       $data['agCount'] = $this->dashboardStdQuery()->select(
                                        Db::raw('count(rp.id) as count')
                                    )
       ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rp.id')
       ->leftJoin('rpt_property_machine_appraisals','rpt_property_machine_appraisals.rp_code','=','rp.id')
       ->join('rpt_property_classes as class','class.id','=',DB::raw("CASE 
                                                WHEN pk.pk_code = 'L' THEN rpt_property_appraisals.pc_class_code
                                                WHEN pk.pk_code = 'B' THEN rp.pc_class_code
                                                WHEN pk.pk_code = 'M' THEN rpt_property_machine_appraisals.pc_class_code
                                                ELSE NULL END"))
       ->where(DB::raw('LOWER(class.pc_class_description)'),'like',"%".'Agricultural'."%")->first();

        $data['resiCount'] = $this->dashboardStdQuery()->select(
                                        Db::raw('count(rp.id) as count')
                                    )
       ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rp.id')
       ->leftJoin('rpt_property_machine_appraisals','rpt_property_machine_appraisals.rp_code','=','rp.id')
       ->join('rpt_property_classes as class','class.id','=',DB::raw("CASE 
                                                WHEN pk.pk_code = 'L' THEN rpt_property_appraisals.pc_class_code
                                                WHEN pk.pk_code = 'B' THEN rp.pc_class_code
                                                WHEN pk.pk_code = 'M' THEN rpt_property_machine_appraisals.pc_class_code
                                                ELSE NULL END"))
       ->where(DB::raw('LOWER(class.pc_class_description)'),'like',"%".'Residential'."%")->first();

       $data['comCount'] = $this->dashboardStdQuery()->select(
                                        Db::raw('count(rp.id) as count')
                                    )
       ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rp.id')
       ->leftJoin('rpt_property_machine_appraisals','rpt_property_machine_appraisals.rp_code','=','rp.id')
       ->join('rpt_property_classes as class','class.id','=',DB::raw("CASE 
                                                WHEN pk.pk_code = 'L' THEN rpt_property_appraisals.pc_class_code
                                                WHEN pk.pk_code = 'B' THEN rp.pc_class_code
                                                WHEN pk.pk_code = 'M' THEN rpt_property_machine_appraisals.pc_class_code
                                                ELSE NULL END"))
       ->where(DB::raw('LOWER(class.pc_class_description)'),'like',"%".'Commercial'."%")->first();

       $data['indCount'] = $this->dashboardStdQuery()->select(
                                        Db::raw('count(rp.id) as count')
                                    )
       ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rp.id')
       ->leftJoin('rpt_property_machine_appraisals','rpt_property_machine_appraisals.rp_code','=','rp.id')
       ->join('rpt_property_classes as class','class.id','=',DB::raw("CASE 
                                                WHEN pk.pk_code = 'L' THEN rpt_property_appraisals.pc_class_code
                                                WHEN pk.pk_code = 'B' THEN rp.pc_class_code
                                                WHEN pk.pk_code = 'M' THEN rpt_property_machine_appraisals.pc_class_code
                                                ELSE NULL END"))
       ->where(DB::raw('LOWER(class.pc_class_description)'),'like',"%".'Industrial'."%")->first();

       $data['zClearanceLand'] = 0;
       $data['landWithBuilding'] = 0;
       $data['newTaxpayer'] = $this->dashboardStdQuery()->select(
                                        'c.full_name','rp.rp_tax_declaration_aform','rp.rp_pin_declaration_no',
                                        DB::raw('SUM(rp.rp_assessed_value) as rp_assessed_value')
                                    )->join('clients as c',function($j){
                                        $j->on('rp.rpo_code','=','c.id');
                                    })->groupBy('rp.rpo_code')
                                    ->orderBy(DB::raw('SUM(rp.rp_assessed_value)'),'DESC')
                                    
                                    ->limit(5)
                                    ->get();
       $data['active'] = $this->dashboardStdQuery()->select(
                                        DB::raw('count(rp.id) as count'),
                                    )->first();       
       $data['cancelled'] = DB::table('rpt_properties as rp')
                                ->join('rpt_revision_year AS rry',function($j){
                                    $j->on('rry.id', '=', 'rp.rvy_revision_year_id')->where('rry.is_active',1)->where('rry.is_default_value',1);
                                })
                                ->where('rp.is_deleted',0)
                                ->where('rp.pk_is_active',0)
                                ->select(
                                        DB::raw('count(rp.id) as count'),
                                    )
                                ->first();     
        $data['onlineTds'] = DB::table('rpt_property_online_accesses')
                                ->select(DB::raw('count(id) as count'))
                                ->where('is_active',1)
                                ->first();     
        $data['offlineTds'] = $this->dashboardStdQuery()
                                ->select(DB::raw('count(rp.id) as count'))
                                ->whereNotIn('rp.id',function($q){
                                    $q->select('rp_code')->from('rpt_property_online_accesses')->whereRaw('rpt_property_online_accesses.rp_code = rp.id');
                                })
                                ->first();        
        $data['topBrgy'] = $this->dashboardStdQuery()
                                ->select('rp.rp_tax_declaration_aform','rp.rp_pin_declaration_no','brgy.brgy_name',
                                 DB::raw('SUM(rp.rp_assessed_value) as rp_assessed_value'))
                                ->join('barangays as brgy','brgy.id','=','rp.brgy_code_id')
                                ->groupBy('rp.brgy_code_id')
                                ->orderBy(DB::raw('SUM(rp.rp_assessed_value)'),'DESC')
                                ->limit(5)
                                ->get();                                                                                                
        $data['noLandHoldings'] = DB::table('rpt_property_certs as rpc')
                                ->select(
                                    DB::raw('count(rpc.id) as count')
                                )
                                ->where('rpc.rpc_year',date("Y"))  
                                ->where('rpc.rpc_cert_type',2)
                                ->where('rpc.status',1)
                                ->first();    
        $data['propHoldings'] = DB::table('rpt_property_certs as rpc')
                                ->select(
                                    DB::raw('count(rpc.id) as count')
                                )
                                ->where('rpc.rpc_year',date("Y"))  
                                ->where('rpc.rpc_cert_type',1)
                                ->where('rpc.status',1)
                                ->first();     
        $data['noImprovment'] = DB::table('rpt_property_certs as rpc')
                                ->select(
                                    DB::raw('count(rpc.id) as count')
                                )
                                ->where('rpc.rpc_year',date("Y"))  
                                ->where('rpc.rpc_cert_type',3)
                                ->where('rpc.status',1)
                                ->first();      
        $data['taxClearance'] = DB::table('rpt_property_certs as rpc')
                                ->select(
                                    DB::raw('count(rpc.id) as count')
                                )
                                ->join('rpt_property_tax_cert_details as rpcd','rpcd.rptc_code','=','rpc.id')
                                ->where('rpc.rpc_year',date("Y"))  
                                ->first();   
        $data['topTxns'] = $this->dashboardStdQuery()
                                ->select(
                                    DB::raw('CONCAT(uc.uc_code,"-",uc.uc_description) as code'),DB::raw('count(uc.id) as count')
                                   )
                                ->join('rpt_update_codes as uc','uc.id','=','rp.uc_code')
                                ->whereRaw('DATE_FORMAT(rp.created_at,"%Y%") = '.date("Y"))
                                ->groupBy('rp.uc_code')
                                ->orderBy(DB::raw('count(uc.id)'),'DESC')
                                ->limit('5')
                                ->get();     
        $data['buildWithBusiness'] = DB::table('bplo_business as bb')
                                ->select(
                                    'rp.rp_tax_declaration_aform',DB::raw('count(bb.id) as count')
                                   )
                                ->join('rpt_properties as rp','rp.id','=','bb.rp_code')
                                ->where('bb.busn_tax_year',date("Y"))
                                ->where('bb.is_active',1)
                                ->groupBy('bb.rp_code')
                                ->orderBy(DB::raw('count(bb.id)'),'DESC')
                                ->limit('5')
                                ->get();                                                                                                      
        return $data;
                                }

    public function getDataForLandAppraisalBulkUpload($value=''){
        $arrBusn=DB::table('rpt_properties AS rp')
                      ->join('clients AS cl','rp.rpo_code','=','cl.id')
                      ->select(DB::raw('CONCAT("[",rp.id,"]","=>","[",rp.rp_tax_declaration_no,"]") as rp_tax_declaration_no'),'cl.full_name AS client_name')
                      ->where('rp.pk_id',2)
                      ->where('rp.pk_is_active',1)
                      ->where('rp.is_deleted',0)
                      ->orderBy('rp.id','desc')
                      ->limit(50)
                      ->get()
                      ->toArray();
        $landUnitValue = DB::table('rpt_land_unit_values AS uv')
                      ->join('rpt_property_classes AS pc','uv.pc_class_code','=','pc.id')
                      ->join('rpt_property_subclassifications AS pcs','uv.ps_subclass_code','=','pcs.id')
                      ->join('rpt_property_actual_uses AS au','uv.pau_actual_use_code','=','au.id')
                      ->select(
                        DB::raw('CONCAT("[",uv.id,"]","=>","[",pc.pc_class_description,"=>",pcs.ps_subclass_desc,"=>",au.pau_actual_use_desc,"=>",lav_unit_value,"]") as class_subclse_actualuses'),'uv.lav_unit_value as unitvalue')
                      ->where('uv.is_approve',1)
                      ->get()
                      ->toArray();
        return ['tds' => $arrBusn,'landUnitValues' => $landUnitValue];
    } 

    public function getDataForBuildAppraisalBulkUpload($value=''){
        $arrBusn=DB::table('rpt_properties AS rp')
                      ->join('clients AS cl','rp.rpo_code','=','cl.id')
                      ->join('rpt_building_kinds as bk','rp.bk_building_kind_code','=','bk.id')
                      ->join('rpt_property_classes as rpc','rp.pc_class_code','=','rpc.id')
                      ->select(DB::raw('CONCAT("[",rp.id,"]","=>","[",rp.rp_tax_declaration_no,"]") as rp_tax_declaration_no'),'cl.full_name AS client_name',DB::raw('CONCAT("[",bk.id,"]=>[",bk.bk_building_kind_desc,"]") as bk_building_kind_desc'),DB::raw('CONCAT("[",rpc.id,"]=>[",rpc.pc_class_description,"]") as pc_class_description'))
                      ->where('rp.pk_id',1)
                      ->where('rp.pk_is_active',1)
                      ->where('rp.is_deleted',0)
                      ->orderBy('rp.id','desc')
                      ->limit(50)
                      ->get()
                      ->toArray();
        $landUnitValue = DB::table('rpt_building_unit_values AS uv')
                      ->join('rpt_building_kinds AS bk','uv.bk_building_kind_code','=','bk.id')
                      ->join('rpt_building_types AS bt','uv.bt_building_type_code','=','bt.id')
                      ->select(
                        DB::raw('CONCAT("[",uv.id,"]","=>","[",bk.bk_building_kind_desc,"=>",bt.bt_building_type_desc,"=>",uv.buv_minimum_unit_value,",",uv.buv_maximum_unit_value,"]") as class_subclse_actualuses'),'uv.buv_minimum_unit_value as unitvalue')
                      ->where('uv.is_approve',1)
                      ->get()
                      ->toArray();
        return ['tds' => $arrBusn,'floorUnitValues' => $landUnitValue];
    }   

    public function getDataForMachineAppraisalBulkUpload($value=''){
        $arrBusn=DB::table('rpt_properties AS rp')
                      ->join('clients AS cl','rp.rpo_code','=','cl.id')
                      ->select(DB::raw('CONCAT("[",rp.id,"]","=>","[",rp.rp_tax_declaration_no,"]") as rp_tax_declaration_no'),'cl.full_name AS client_name')
                      ->where('rp.pk_id',3)
                      ->where('rp.pk_is_active',1)
                      ->where('rp.is_deleted',0)
                      ->orderBy('rp.id','desc')
                      ->limit(50)
                      ->get()
                      ->toArray();
        return $arrBusn;
    }

    public function createDataForApproval($data = ''){
        $approvalData = [
            'pk_code' => ($data['pk_id'] == 1)?(($data['pk_id'] == 1)?'B':'L'):'M',
            'rp_app_appraised_by' => $data['rp_app_appraised_by'],
            'rp_app_appraised_date' => date("Y-m-d"),
            'rp_app_recommend_by' => $data['rp_app_recommend_by'],
            'rp_app_recommend_date' => date("Y-m-d"),
            'rp_app_approved_by' => $data['rp_app_approved_by'],
            'rp_app_approved_date' => date("Y-m-d"),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' =>date("Y-m-d H:i:s")
        ];
        return $approvalData;
    }

    public function fliterData($data = '',$columns){
        $combinedData = array_combine($columns,$data);
        if(isset($combinedData['pk_id']) && $combinedData['pk_id'] == 3){
            unset($combinedData['client_name']);
            unset($combinedData['employee_name']);
            unset($combinedData['land_reference']);
            unset($combinedData['building_reference']);
            unset($combinedData['classes']);
            unset($combinedData['previous_owner_reference_tds']);
        }
        if(isset($combinedData['pk_id']) && $combinedData['pk_id'] == 2){
            unset($combinedData['client_name']);
            unset($combinedData['employee_name']);
            unset($combinedData['previous_owner_reference_tds']);
        }
        if(isset($combinedData['pk_id']) && $combinedData['pk_id'] == 1){
             unset($combinedData['client_name']);
            unset($combinedData['employee_name']);
            unset($combinedData['roof_description']);
            unset($combinedData['floor_description']);
            unset($combinedData['wall_description']);
            unset($combinedData['land_reference']);
            unset($combinedData['class']);
            unset($combinedData['kinds']);
            unset($combinedData['previous_owner_reference_tds']);
            $combinedData['rp_building_completed_percent'] = 100;
        }
        if(isset($combinedData['pk_id']) && $combinedData['pk_is_active'] == 1){
            unset($combinedData['previous_owner_reference']);
        }

        $newData = [];

        foreach($combinedData as $key=>$value){
            if (preg_match('/[\[\]\()]/', $value)){
                preg_match_all("/\[[^\]]*\]/", $value, $matches);
                $newData[$key] = isset($matches[0][0])?str_replace(array( '[', ']' ), '', $matches[0][0]):'';
            }else{
                $newData[$key] = $value;
            }
        }
        $newData['rp_app_effective_year'] = date("Y")+1;
        $newData['rp_app_effective_quarter'] = 1;
        $newData['rp_app_posting_date'] = date("Y-m-d H:i:s");
        $newData['is_deleted'] = 0;
        $newData['rp_modified_by'] = \Auth::user()->id;
        $newData['rp_registered_by'] = \Auth::user()->id;
        $newData['created_at'] = date("Y-m-d H:i:s");
        $newData['updated_at'] = date("Y-m-d H:i:s");
        return $newData;
    }

    public function checkRequiredFields($data){
        $tempData = $data;
        if(isset($tempData['pk_id']) && $tempData['pk_id'] == 3){
            $notRequiredFields = ['rp_suffix','rp_code_bref','rp_code_lref','rp_administrator_code','rp_pin_suffix','is_deleted'];
         }else if(isset($tempData['pk_id']) && $tempData['pk_id'] == 2){
            $notRequiredFields = ['rp_suffix','rp_pin_suffix','rp_location_number_n_street','rp_administrator_code','rp_bound_north','rp_bound_east','is_deleted','rp_bound_south','rp_bound_west'];
         }else if(isset($tempData['pk_id']) && $tempData['pk_id'] == 1){
            $notRequiredFields = ['rp_suffix','rp_location_number_n_street','rp_administrator_code','rp_pin_suffix','is_deleted','rp_bulding_permit_no','rp_building_cct_no','rp_building_unit_no','rbf_building_roof_desc2','rbf_building_roof_desc3','rbf_building_floor_desc2','rbf_building_floor_desc3','rbf_building_wall_desc2','rbf_building_wall_desc3'];
         }else{
            $notRequiredFields = [];
         }
        if(isset($tempData['pk_id']) && $tempData['pk_is_active'] == 1){
            $notRequiredFields[] = 'previous_owner_reference';
        }
        $validationArr = [];
        foreach ($tempData as $key => $value) {
            if(!in_array($key, $notRequiredFields) && $value == null){
                $validationArr[] = $key;
            }
            if(isset($tempData['pk_id']) && $tempData['pk_id'] == 3){
            if($key == 'rp_code_bref' && $value != ''){
                $collectRelatedData = DB::table('rpt_properties')->where('id',$value)->first();
                $data['rp_section_no_bref'] = (isset($collectRelatedData->rp_section_no))?$collectRelatedData->rp_section_no:'';
                $data['rp_pin_no_bref'] = (isset($collectRelatedData->rp_pin_no))?$collectRelatedData->rp_pin_no:'';
                $data['rp_pin_suffix_bref'] = (isset($collectRelatedData->rp_pin_suffix))?$collectRelatedData->rp_pin_suffix:'';
            }if($key == 'rp_pin_suffix'){
                if($data['rp_code_lref'] != ''){
                    $suffixData = $this->getMachinerypropertycount($data['rp_code_lref'],0);
                    $suffixData += 1;
                    $suffix = 'M'.$suffixData;
                }else{
                    $suffixData = DB::table('rpt_properties')->where('pk_is_active',1)->where('pk_id',3)->where('is_deleted',0)->count();
                    $suffixData += 1;
                    $suffix = 'M'.$suffixData;
                }
                $data['rp_pin_suffix'] = $suffix;
            }
            }
            if(isset($tempData['pk_id']) && $tempData['pk_id'] == 3){
            if($key == 'rp_code_lref' && $value != ''){
                $collectRelatedData = DB::table('rpt_properties')->where('id',$value)->first();
                $data['rp_section_no_lref'] = (isset($collectRelatedData->rp_section_no))?$collectRelatedData->rp_section_no:'';
                $data['rp_pin_no_lref'] = (isset($collectRelatedData->rp_pin_no))?$collectRelatedData->rp_pin_no:'';
                $data['rpo_code_lref'] = (isset($collectRelatedData->rpo_code))?$collectRelatedData->rpo_code:'';
            }
            }
            if(isset($tempData['pk_id']) && $tempData['pk_id'] == 1){
                if($key == 'rp_code_lref' && $value != ''){
                $collectRelatedData = DB::table('rpt_properties')->join('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rpt_properties.id')->select('rpt_properties.*',DB::raw('SUM(rpt_property_appraisals.rpa_total_land_area) as landARea'))->where('rpt_properties.id',$value)->first();
                $data['rp_td_no_lref'] = (isset($collectRelatedData->rp_td_no))?$collectRelatedData->rp_td_no:'';
                $data['rpo_code_lref'] = (isset($collectRelatedData->rpo_code))?$collectRelatedData->rpo_code:'';
                $data['rp_oct_tct_cloa_no_lref'] = (isset($collectRelatedData->rp_oct_tct_cloa_no))?$collectRelatedData->rp_oct_tct_cloa_no:'';
                $data['rp_cadastral_lot_no_lref'] = (isset($collectRelatedData->rp_cadastral_lot_no))?$collectRelatedData->rp_cadastral_lot_no:'';
                $data['rp_total_land_area'] = (isset($collectRelatedData->landARea))?$collectRelatedData->landARea:'';
            }
            if($key == 'rp_occupied_year' && $value != ''){
                $data['rp_building_age'] = (isset($data['rp_occupied_year']) && $data['rp_occupied_year'] != '')?(date("Y")-$data['rp_occupied_year']):0;
            }
            
            }
            if(isset($tempData['pk_id']) && $tempData['pk_id'] == 2){
            
            }
            if($key == 'pk_is_active' && $value == 9){
                $collectRelatedData = DB::table('rpt_properties')->where('id',(isset($tempData['previous_owner_reference']))?$tempData['previous_owner_reference']:'')->first();
                $data['created_against'] = $tempData['previous_owner_reference'];
                $data['rp_property_code'] = (isset($collectRelatedData->rp_property_code))?$collectRelatedData->rp_property_code:'';
            }
            
        }
       // dd($validationArr);
        if(!empty($validationArr)){
            return ['status' => false,'data' => []];
        }else{
            return ['status' => true,'data' => $data];
        }
        
    }

     public function fliterApraisalData($data = '',$columns){
        $combinedData = array_combine($columns,$data);
        $newData = [];
        foreach($combinedData as $key=>$value){
            if (preg_match('/[\[\]\']/', $value)){
                preg_match_all("/\[[^\]]*\]/", $value, $matches);
                $newData[$key] = isset($matches[0][0])?str_replace(array( '[', ']' ), '', $matches[0][0]):'';
            }else{
                $newData[$key] = $value;
            }
        }
        //dd($newData);
        $propDetails = DB::table('rpt_properties')
                           ->select('rpt_properties.*','rvy.rvy_revision_year')
                           ->join('rpt_revision_year as rvy','rvy.id','=','rpt_properties.rvy_revision_year_id')
                           ->where('rpt_properties.id',$newData['rp_code'])
                           ->first();
        if($propDetails != null){
        if($propDetails->pk_id == 3){
            unset($newData['rp_tax_declaration_no']);
        }
        if($propDetails->pk_id == 3){
            $newData['rvy_revision_year'] = $propDetails->rvy_revision_year;
            $newData['rvy_revision_code'] = $propDetails->rvy_revision_code;
            $newData['pc_class_code'] = $propDetails->pc_class_code;
            $newData['rpma_modified_by'] = \Auth::user()->id;
            $newData['rpma_registered_by'] = \Auth::user()->id;
        }
        if($propDetails->pk_id == 2){
            unset($newData['rp_tax_declaration_no']);
            unset($newData['client_name']);
            unset($newData['class_subclse_actualuses']);
            unset($newData['unitvalue']);
            $newData['rvy_revision_year'] = $propDetails->rvy_revision_year;
            $newData['rvy_revision_code'] = $propDetails->rvy_revision_code;
        }
        if($propDetails->pk_id == 1){
            unset($newData['rp_tax_declaration_no']);
            unset($newData['class_subclse_actualuses']);
            unset($newData['unitvalue']);
            unset($newData['actualuses']);
            unset($newData['pc_class_code']);
            unset($newData['pc_building_kind']);
            unset($newData['class']);
            unset($newData['kind']);
        }
        $newData['rp_code'] = $propDetails->id;
        $newData['rp_property_code'] = $propDetails->rp_property_code;
        $newData['pk_code'] = ($propDetails->pk_id == 1)?'B':(($propDetails->pk_id == 2)?'L':'M');
        $newData['created_at'] = date("Y-m-d H:i:s");
        $newData['updated_at'] = date("Y-m-d H:i:s");
        return $newData;
    }else{
        return $combinedData;
    }
    }

    public function checkAppraisalRequiredFields($data){
        $tempData = $data;
        $propDetails = DB::table('rpt_properties')
                           ->select('rpt_properties.*')
                           ->where('rpt_properties.id',$tempData['rp_code'])
                           ->first();
        if($propDetails != null){
        if($propDetails->pk_id == 3){
            $notRequiredFields = ['rpma_brand_model','rpma_capacity_hp','rpma_date_acquired','rpma_condition','rpma_estimated_life','rpma_remaining_life','rpma_date_installed','rpma_date_operated','rpma_remarks','rpma_freight_cost','rpma_insurance_cost','rpma_installation_cost','rpma_other_cost'];
         }else if($propDetails->pk_id == 2){
            $notRequiredFields = ['rpa_adjustment_factor_a','rpa_adjustment_factor_b','rpa_adjustment_factor_c'];
         }else if($propDetails->pk_id == 1){
            $notRequiredFields = ['rpbfv_floor_additional_value','rpbfv_floor_adjustment_value'];
         }
        $validationArr = [];
        foreach ($tempData as $key => $value) {
            if(!in_array($key, $notRequiredFields) && $value == null){
                $validationArr[] = $key;
            }
            if($propDetails->pk_id == 3){
            if($key == 'rpma_acquisition_cost' && $value != ''){
                $acqCost = ($tempData['rpma_acquisition_cost'] != null)?$tempData['rpma_acquisition_cost']:0;
                $freistCost = ($tempData['rpma_freight_cost'] != null)?$tempData['rpma_freight_cost']:0;
                $InsuCost = ($tempData['rpma_insurance_cost'] != null)?$tempData['rpma_insurance_cost']:0;
                $installCost = ($tempData['rpma_installation_cost'] != null)?$tempData['rpma_installation_cost']:0;
                $otherCost = ($tempData['rpma_other_cost'] != null)?$tempData['rpma_other_cost']:0;
                $baseMarketValue = $acqCost+$freistCost+$InsuCost+$installCost+$otherCost;
                $data['rpma_base_market_value'] = $baseMarketValue;
                $data['rpma_market_value'] = $baseMarketValue;
                unset($data['class_subclse_actualuses']);
            }
            }

            
            if($propDetails->pk_id == 1){
                if($key == 'rpbfv_floor_unit_value' && $value != ''){
                 $landUnitValueData = DB::table('rpt_building_unit_values')->where('id',$value)->first();
                 
                 $data['bt_building_type_code'] = $landUnitValueData->bt_building_type_code;
                 $data['bk_building_kind_code'] = $landUnitValueData->bk_building_kind_code;
                 $data['rpbfv_floor_unit_value'] = $landUnitValueData->buv_minimum_unit_value;
                 $data['rpbfv_floor_base_market_value'] = $landUnitValueData->buv_minimum_unit_value*$data['rpbfv_floor_area'];
                 /* Check Class and Building Kind Matches with TD*/
                 if(isset($data['bk_building_kind_code']) && $data['bk_building_kind_code'] != $propDetails->bk_building_kind_code){
                    $validationArr[] = 'Building Kind not matching';
                 }
                 $classDetails = DB::table('rpt_property_actual_uses')->where('id',isset($data['pau_actual_use_code'])?$data['pau_actual_use_code']:'')->where('pc_class_code',$propDetails->pc_class_code)->first();
                 if($classDetails == null){
                    $validationArr[] = 'Building Class not matching';
                 }
                 $lastActualUses = DB::table('rpt_building_floor_values')->where('rp_code',$propDetails->id)->first();
                 if($lastActualUses != null && $lastActualUses->pau_actual_use_code != $data['pau_actual_use_code']){
                     $validationArr[] = 'Building Actual Uses not matching';
                 }
                 /* Check Class and Building Kind Matches with TD*/
                 $data['al_assessment_level'] = 0;
                 $calcuMarketValue = $landUnitValueData->buv_minimum_unit_value*$data['rpbfv_floor_area'];
                 $data['rpbfv_floor_base_market_value'] = $calcuMarketValue;
                 $data['rpbfv_total_floor_market_value'] = $calcuMarketValue;
                 
                 $requestObj = new Request;
                 $requestObj->merge([
                'propertyKind' => 2,
                'propertyClass' => $propDetails->pc_class_code,
                'propertyActualUseCode' => $data['pau_actual_use_code'],
                'propertyRevisionYear' => $propDetails->rvy_revision_year_id,
                'barangay'             => $propDetails->brgy_code_id,
                'totalMarketValue'     => $data['rpbfv_floor_base_market_value']
            ]);
                 
            $arrassessementLevel = $this->getAssessementLevel($requestObj);
            //dd($arrassessementLevel);
            if($arrassessementLevel != false){
            if(!$arrassessementLevel->assessementRelations->isEmpty()){
                $ass = $arrassessementLevel->assessementRelations;
                $data['al_assessment_level'] = $ass[0]->assessment_level;
            }
           }
            }
            
            }
            if($propDetails->pk_id == 2){
                if($key == 'lav_unit_value' && $value != ''){
                 $landUnitValueData = DB::table('rpt_land_unit_values')->where('id',$value)->first();
                 //dd($landUnitValueData);
                 $data['pc_class_code'] = $landUnitValueData->pc_class_code;
                 $data['ps_subclass_code'] = $landUnitValueData->ps_subclass_code;
                 $data['pau_actual_use_code'] = $landUnitValueData->pau_actual_use_code;
                 $data['lav_unit_value'] = $landUnitValueData->lav_unit_value;
                 $data['lav_unit_measure'] = $landUnitValueData->lav_unit_measure;
                 $data['rpa_taxable'] = 1;
                 $data['al_assessment_level'] = 0;
                 $data['rpa_base_market_value'] = $landUnitValueData->lav_unit_value*$data['rpa_total_land_area'];
                 $requestObj = new Request;
                 $requestObj->merge([
                'propertyKind' => 2,
                'propertyClass' => $landUnitValueData->pc_class_code,
                'propertyActualUseCode' => $landUnitValueData->pau_actual_use_code,
                'propertyRevisionYear' => $propDetails->rvy_revision_year_id,
                'barangay'             => $propDetails->brgy_code_id,
                'totalMarketValue'     => $data['rpa_base_market_value']
            ]);
                 
            $arrassessementLevel = $this->getAssessementLevel($requestObj);
            if($arrassessementLevel != false){
            if(!$arrassessementLevel->assessementRelations->isEmpty()){
                $ass = $arrassessementLevel->assessementRelations;
                $data['al_assessment_level'] = $ass[0]->assessment_level;
            }
           }
            }
            
            }
            
        }
        if(!empty($validationArr)){
            return ['status' => false,'data' => []];
        }else{
            return ['status' => true,'data' => $data];
        }
    }else{
        return ['status' => false,'data' => []];
        
    }                       
    }
    
}

