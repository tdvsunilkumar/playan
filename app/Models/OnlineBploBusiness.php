<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bplo\BploBusinessType;
use App\Models\Bplo\CtoPaymentMode;
use App\Models\Bplo\BploBusinessLocation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use DB;
use Session;

class OnlineBploBusiness extends Model
{
    protected $guarded = ['id'];
    protected $connection = 'remort_server';
    public $table = 'bplo_business';
    public $timestamps = false; 
    public function getBussClientDetails($id){
        return DB::table('bplo_business as bb')
        ->leftjoin('rpt_locality as rl','bb.locality_id', '=', 'rl.id')
        ->leftjoin('clients as cl','bb.client_id', '=', 'cl.id')
        ->select('loc_local_code','rpo_custom_last_name','busns_id_no','busns_id','bb.client_id',
            DB::raw("(SELECT COUNT(id) FROM bplo_business 
                  WHERE busn_app_status>=2 AND id!=".(int)$id.") as totalApproved")
        )->where('bb.id',(int)$id)->first();
    }
    public function updateActiveInactive($id,$columns){
        return DB::table('bplo_business')->where('id',$id)->update($columns);
    }

    public function updateData($id,array $columns){
        BploBusiness::where('id',$id)->update($columns);
        return BploBusiness::where('id',$id)->first();
    }
    public function updateBploHistoryData($id,$postdata){
        return DB::table('bplo_business_history')->where('id',$id)->update($postdata);
    }
    public function addData($postdata){
        DB::table('bplo_business')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function addBploBusinessHistory($postdata){
        return DB::table('bplo_business_history')->insert($postdata);
    }
    public function addBploBusnPsicHistory($postdata){
        return DB::table('bplo_business_psic_history')->insert($postdata);
    }
    public function addBploBusnPsicReqHistory($postdata){
        return DB::table('bplo_business_psic_req_history')->insert($postdata);
    }
    public function addBploMeasurePaxHistory($postdata){
        return DB::table('bplo_business_measure_pax_history')->insert($postdata);
    }
    public function BploBusinessHistoryByBusnId($id){
        return DB::table('bplo_business_history')->where('busn_id',$id)->orderBy('id','DESC')->get();
    }
    public function BploBusnLatestHistoryByBusnId($id,$year){
        return DB::table('bplo_business_history')->where('busn_id',$id)->where('busn_tax_year',$year)->orderBy('id','DESC')->first();
    }
    public function add_bsn_plan($postdata){
        DB::table('bplo_business_psic')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function update_bsn_plan($id,$postdata){
        return DB::table('bplo_business_psic')->where('id',$id)->update($postdata);
    }
    public function update_measure_pax($id,array $columns){
        return DB::table('bplo_business_measure_pax')->where('id',$id)->update($columns);
    }
    public function getBussinessClasses($id){  
        return DB::table('bplo_business_psic as bp')->leftjoin('psic_subclasses as ps','bp.subclass_id', '=', 'ps.id')->select('bp.id','ps.subclass_description')->where('busn_id',$id)->get();

    }
    public function getclassbypsicclass($id){
        return DB::table('bplo_business_psic')->select('subclass_id')->where('id',$id)->first();
    }
    public function getRequirements($subclass_id,$bussinessid,$apptype){
            return DB::table('requirements as rq')
            ->leftjoin('bplo_requirement_relations as brr','brr.requirement_id', '=', 'rq.id')
            ->leftjoin('bplo_requirements as br','br.id', '=', 'brr.bplo_requirement_id')
            ->select('rq.id','rq.req_description','brr.bplo_requirement_id')
            ->where('br.apptype_id',$apptype)
            ->where('brr.is_active',1)
            ->where('rq.is_active',1)
            ->where('brr.subclass_id',$subclass_id)->groupby('rq.id')->get();
    }
    public function add_measure_pax($postdata){
        DB::table('bplo_business_measure_pax')->insert($postdata);
        return DB::table('bplo_business_measure_pax')->orderBy('id','desc')->first();
    }

    public function checkExistpbsireqdoc($reid,$busnid,$psicid){
        return DB::table('bplo_business_psic_req')->where('req_code','=',$reid)->where('busn_id','=',$busnid)->orderBy('id','desc')->get();
    }

    public function getAllreqbybusnid($busnid){
        return DB::table('bplo_business_psic_req as bbpr')->leftjoin('requirements as rq','bbpr.req_code', '=', 'rq.id')->select('rq.req_description','bbpr.attachment','bbpr.id','bbpr.busn_psic_id')->where('busn_id','=',$busnid)->orderBy('bbpr.id','desc')->get();
    }

    public function add_req_doc($postdata){
        DB::table('bplo_business_psic_req')->insert($postdata);
        return DB::table('bplo_business_psic_req')->orderBy('id','desc')->first();
    }

    public function getRequirementsbyid($id){
        return DB::table('bplo_business_psic_req')->where('id','=',$id)->orderBy('id','desc')->get();
    }

    public function deleteRequirementsbyid($id){
        return DB::table('bplo_business_psic_req')->where('id','=',$id)->delete();
    }
        
    public function create(array $details) 
    {
        DB::table('bplo_business')->insert($details);
        return DB::table('bplo_business')->orderBy('id','desc')->first();
    }
       
    public function getEditDetails($id){
        return DB::table('bplo_business')->where('id',$id)->first();
    }
    public function find($id) 
    {
        $remortServer = DB::connection('remort_server');
        $locality=$this->allLocality();
        $data= $remortServer->table('bplo_business')
                ->select('*', $remortServer->raw("$locality->id as locality_id"),$remortServer->raw("'$locality->loc_local_name' as locality"),$remortServer->raw("$locality->loc_local_code as loc_local_id"))
                ->where('frgn_busn_id',$id)->first();
        return $data;
    }
    public function findForUpdateHistory($id){
        $excludedColumns = ['id','created_by','created_at']; // Replace these with the actual column names you want to exclude
        return DB::table('bplo_business')
                    ->where('id',$id)
                    ->select(array_diff(DB::getSchemaBuilder()->getColumnListing('bplo_business'), $excludedColumns))
                    ->first();
    }
    
    public function getBsnType($vars = '')
    {
        $remortServer = DB::connection('remort_server');
         $bplo_business_types = $remortServer->table('bplo_business_type')->where('id', $vars)->where('btype_status', 1)->orderBy('id', 'asc')->get();
   
         return array("data"=>$bplo_business_types);
    }
    public function getBsnActivity($id = "")
    {
        $remortServer = DB::connection('remort_server');
        $bplo_business_locs = $remortServer->table('bplo_business_locations')->where('id', $id)->where('busloc_status', 1)->orderBy('id', 'asc')->get();
        return array("data"=>$bplo_business_locs);
    }
    public function getOwnerName($id = null) {
        $remortServer = DB::connection('remort_server');
        $query = $remortServer->table('clients')->orderBy('full_name', 'asc');
        
        if ($id !== null) {
            $query->where('client_frgn_id', $id);
        }
        
        $owners = $query->get();
        
        return array("data" => $owners);
    }
    public function getClientName()
    {
        $owners = DB::table('clients')->orderBy('full_name', 'asc')->get();
        return $owners;
    }
    public function allAppType($vars = '')
    {
        $remortServer = DB::connection('remort_server');
        $app_types = $remortServer->table('bplo_application_type')->where('id',$vars)->orderBy('id')->get();
        return array("data"=>$app_types);
    }
    public function allPayMode($vars = ''){
        $pay_modes = DB::table('cto_payment_mode')->where('id',$vars)->where('pm_status',1)->orderBy('id')->get();
        return array("data"=>$pay_modes);
   }
   public function getBarangay($search=""){
        $remortServer = DB::connection('remort_server');
        $page=1;
        if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
        }
        $length = 20;
        $offset = ($page - 1) * $length;

        $sql = $remortServer->table('barangays AS bgf')
        ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
        ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
        ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
        ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','bgf.is_active')->where('bgf.is_active',1);
        if(!empty($search)){
        $sql->where(function ($sql) use($search,$remortServer) {
            if(is_numeric($search)){
            $sql->Where('bgf.id',$search);
            }else{
            $sql->where($remortServer->raw('LOWER(brgy_name)'),'like',"%".strtolower($search)."%")
            ->orWhere($remortServer->raw('LOWER(pm.mun_desc)'),'like',"%".strtolower($search)."%")
            ->orWhere($remortServer->raw('LOWER(pp.prov_desc)'),'like',"%".strtolower($search)."%")
            ->orWhere($remortServer->raw('LOWER(pr.reg_region)'),'like',"%".strtolower($search)."%");
            }
        });
        }
        $sql->orderBy('brgy_name','ASC');
        $data_cnt=$sql->count();
        $sql->offset((int)$offset)->limit((int)$length);

        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }


    public function getOwnerNameById($id)
    {
        $remortServer = DB::connection('remort_server');
        return $remortServer->table('clients')->where('client_frgn_id', $id)->first();
    }
    public function getRptInfoById($id)
    {
        return (new RptProperty)->find($id);
    }
    
    public function allLocality()
    {
        $remortServer = DB::connection('remort_server');
        return $remortServer->table('rpt_locality')->where('department', 2)->first();
    }
    

    public function refresh_client()
    {
        return (new RptPropertyOwner)->getClients();
    }
       
       public function client(){
            return $this->belongsTo(RptPropertyOwner::class,'client_id')->withDefault();
        }
        public function mainBarangay(){
            return $this->belongsTo(Barangay::class,'busn_office_main_barangay_id')->withDefault();
        }
        public function busnBarangay(){
            return $this->belongsTo(Barangay::class,'busn_office_barangay_id')->withDefault();
        }
        
        public function applicationType(){
            return $this->belongsTo(BploApplicationType::class,'app_code')->withDefault();
        }
        public function paymendMode(){
            return $this->belongsTo(CtoPaymentMode::class,'pm_id')->withDefault();
        }
        
    
       public function reload_summary($busn_id)
       { 
            $remortServer = DB::connection('remort_server');
            $bploBusiness=$remortServer->table('bplo_business AS bp')
                            ->leftjoin('clients AS cc', 'cc.id', '=', 'bp.client_id')
                            ->leftJoin('barangays AS mainBrgy', 'mainBrgy.id', '=', 'bp.busn_office_main_barangay_id')
                            ->leftJoin('profile_regions AS mainPr', 'mainPr.id', '=', 'mainBrgy.reg_no')
                            ->leftJoin('profile_provinces AS mainPp', 'mainPp.id', '=', 'mainBrgy.prov_no')
                            ->leftJoin('profile_municipalities AS mainPm', 'mainPm.id', '=', 'mainBrgy.mun_no')

                            ->leftJoin('barangays AS busnBrgy', 'busnBrgy.id', '=', 'bp.busn_office_barangay_id')
                            ->leftJoin('profile_regions AS busnPr', 'busnPr.id', '=', 'busnBrgy.reg_no')
                            ->leftJoin('profile_provinces AS busnPp', 'busnPp.id', '=', 'busnBrgy.prov_no')
                            ->leftJoin('profile_municipalities AS busnPm', 'busnPm.id', '=', 'busnBrgy.mun_no')

                            ->select('bp.*','cc.gender','cc.gender','cc.p_telephone_no','cc.p_mobile_no','cc.p_email_address','cc.rpo_custom_last_name','cc.rpo_first_name','cc.rpo_middle_name','cc.suffix','cc.rpo_middle_name','cc.rpo_middle_name','cc.rpo_middle_name','cc.rpo_middle_name'
                                    ,'mainBrgy.brgy_name as main_brgy_name', 'mainPr.reg_region as main_reg_region', 'mainPp.prov_desc as main_prov_desc', 'mainPm.mun_desc as main_mun_desc' , 'mainPm.mun_zip_code as main_mun_zip_code'
                                    ,'busnBrgy.brgy_name as busn_brgy_name', 'busnPr.reg_region as busn_reg_region', 'busnPp.prov_desc as busn_prov_desc', 'busnPm.mun_desc as busn_mun_desc', 'busnPm.mun_zip_code as busn_mun_zip_code')
                            ->where('bp.frgn_busn_id',$busn_id)->first();
            return $bploBusiness;
       }

       public function reload_busn_plan($busn_id)
        {
            $remortServer = DB::connection('remort_server');
            $items = $remortServer->table('bplo_business_psic AS bbp')
                    ->leftjoin('psic_subclasses AS psc', 'psc.id', '=', 'bbp.subclass_id')
                    ->leftjoin('bplo_business AS bb', 'bb.id', '=', 'bbp.busn_id')
                    ->leftjoin('psic_sections AS ps', 'ps.id', '=', 'psc.section_id')
                    ->leftjoin('psic_divisions AS pd', 'psc.division_id', '=', 'pd.id')
                    ->leftjoin('psic_groups AS pg', 'psc.group_id', '=', 'pg.id')
                    ->leftjoin('psic_classes AS pc', 'psc.group_id', '=', 'pc.id')
                    ->select('bbp.id as ID','bbp.*','subclass_code','subclass_description')
                    ->where('bbp.busn_id',$busn_id)
                    ->orderBy('id', 'asc')
                    ->get();
    
            return $items;
        }

        public function busn_plan_sum($busn_id)
        {
            $remortServer = DB::connection('remort_server');
            $items = $remortServer->table('bplo_business_psic AS bbp')
                    ->where('bbp.busn_id',$busn_id)
                    ->sum('bbp.busp_capital_investment');
    
            return $items;
        }
     
       public function reload_address($busn_id)
       { 
            $bploBusiness=DB::table('bplo_business')
                        ->leftJoin('barangays', function($join)
                        {
                            $join->on('barangays.id', '=', 'bplo_business.busn_office_main_barangay_id');
                        })
                        ->leftJoin('profile_municipalities', function($join)
                        {
                            $join->on('profile_municipalities.id', '=', 'barangays.mun_no');
                        })
                        ->leftJoin('profile_provinces', function($join)
                        {
                            $join->on('profile_provinces.id', '=', 'barangays.prov_no');
                        })
                        ->leftJoin('profile_regions', function($join)
                        {
                            $join->on('profile_regions.id', '=', 'barangays.reg_no');
                        })
                        ->select('bplo_business.*','barangays.brgy_name','profile_municipalities.mun_desc','profile_municipalities.mun_zip_code','profile_provinces.prov_desc','profile_regions.reg_region')
                        ->where('bplo_business.id',$busn_id)->first();
           
            return $bploBusiness;
       }
    public function getList($request){
        $remortServer = DB::connection('remort_server');
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $from_date=$request->input('from_date');
        $to_date=$request->input('to_date');
        $brgy=$request->input('brgy');
        $flt_Status=$request->input('flt_Status');
        $from_date = Carbon::parse($from_date)->format('Y-m-d');
        $to_date = Carbon::parse($to_date)->format('Y-m-d');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="8";
        }
        
        $columns = array( 
          1 =>"busns_id_no",  
          2 => $remortServer->raw("CONCAT(cc.rpo_first_name, ' ', cc.rpo_middle_name, ' ', cc.rpo_custom_last_name)"),
          3 =>"busn_name",
          4 =>"officeBrgy.brgy_name",
          5 =>"bat.app_type",
          6 =>"bb.created_at", 
          7 =>"bb.busn_app_status", 
          8 =>"bb.busn_app_method", 
         );    

        $sql = $remortServer->table('bplo_business AS bb')
            ->leftJoin('clients AS cc', 'cc.client_frgn_id', '=', 'bb.client_id')
            ->leftJoin('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
            ->leftJoin('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
            ->leftJoin('barangays AS officeBrgy', 'officeBrgy.id', '=', 'bb.busn_office_barangay_id')
            ->leftJoin('profile_regions AS pr', 'pr.id', '=', 'officeBrgy.reg_no')
            ->leftJoin('profile_provinces AS pp', 'pp.id', '=', 'officeBrgy.prov_no')
            ->leftJoin('profile_municipalities AS pm', 'pm.id', '=', 'officeBrgy.mun_no')
            ->leftJoin('cto_cashier AS ccash', function ($join) use ($remortServer) {
                $join->on('ccash.busn_id', '=', 'bb.id')
                    ->where('ccash.id', function ($subquery) use ($remortServer) {
                        $subquery->select($remortServer->raw('MAX(id)'))
                            ->from('cto_cashier')
                            ->whereColumn('busn_id', '=', 'bb.id');
                    });
            })
            ->select('bb.*', 'bbt.btype_desc', 'officeBrgy.brgy_name as office_brgy_name', 'ccash.cashier_or_date as last_pay_date', 'pm.mun_desc as office_mun_desc', 'pp.prov_desc as office_prov_desc', 'pr.reg_region as office_reg_region',
                'cc.rpo_custom_last_name', 'cc.rpo_first_name', 'cc.rpo_middle_name','cc.full_name', 'cc.p_telephone_no', 'cc.p_mobile_no', 'bat.app_type');    
        $sql->where('bb.busn_app_status',1);
        $sql->where('bb.is_approved','!=',1);
        if(!empty($from_date) && isset($from_date)){
                    $sql->whereDate('bb.application_date','>=',$from_date);
            }
        if(!empty($to_date) && isset($to_date)){
                        $sql->whereDate('bb.application_date','<=',$to_date);
                }
        if(!empty($brgy) && isset($brgy)){
                $sql->where('bb.busn_office_barangay_id',$brgy);
        } 
        if(isset($flt_Status)){
            $sql->where('bb.is_approved',$flt_Status);
        } 
              
        if(!empty($q) && isset($q)){
            switch (strtolower($q)) {
                case 'not completed':
                    $que = 0;
                    break;
                case 'completed/for verification':
                    $que = 1;
                    break;
                case 'for endorsement':
                    $que = 2;
                    break;
                case 'for assessment':
                    $que = 3;
                    break;
                case 'for payment':
                    $que = 4;
                    break;
                case 'for issuance':
                    $que = 5;
                    break;
                case 'license issued':
                    $que = 6;
                    break;
                case 'declined':
                    $que = 7;
                    break;
                case 'cancelled permit':
                    $que = 8;
                    break;
                default:
                    $que = null;
                    break;
            }
            $sql->where(function ($sql) use($q,$que,$remortServer) {
                if(isset($que))
                {
                    $sql->where($remortServer->raw('busn_app_status'),$que); 
                }
                else{
                    $sql->where($remortServer->raw('LOWER(bb.busns_id_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere($remortServer->raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere($remortServer->raw("CONCAT(cc.rpo_first_name, ' ',cc.rpo_middle_name,' ',cc.rpo_custom_last_name)"), 'LIKE', "%".strtolower($q)."%")
                    ->orWhere($remortServer->raw("CONCAT(officeBrgy.brgy_name, ', ',pm.mun_desc,', ',pp.prov_desc,', ', pr.reg_region)"), 'LIKE', "%".strtolower($q)."%")
                    ->orWhere($remortServer->raw('LOWER(bat.app_type)'),'like',"%".strtolower($q)."%")
                    ->orWhere($remortServer->raw('LOWER(bb.busn_app_method)'),'like',"%".strtolower($q)."%");
                }
                
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else
        {
            $sql->orderBy('bb.id','DESC');
        }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

    public function psic_subclass_lists($request)
    {             
       return PsicSubclass::getList($request);
    }
    public function bploBusnPsicList($request,$busn_id)
        {
            $remortServer = DB::connection('remort_server');
            $params = $columns = $totalRecords = $data = array();
            $params = $_REQUEST;
            $q=$request->input('q');
        
            if(!isset($params['start']) && !isset($params['length'])){
              $params['start']="0";
              $params['length']="10";
            }
        
            $columns = array( 
              0 =>"psc.subclass_code",
              1 =>"psc.subclass_description",
              2 =>"bbp.busp_no_units",
              3 =>"bbp.busp_capital_investment",
              4 =>"bbp.busp_essential",	
              5 =>"bbp.busp_non_essential"   
            );
        
            $sql = $remortServer->table('bplo_business_psic AS bbp')
                  ->leftjoin('psic_subclasses AS psc', 'psc.id', '=', 'bbp.subclass_id')
                  ->leftjoin('bplo_business AS bb', 'bb.id', '=', 'bbp.busn_id')
                  ->leftjoin('psic_sections AS ps', 'ps.id', '=', 'psc.section_id')
                  ->leftjoin('psic_divisions AS pd', 'psc.division_id', '=', 'pd.id')
                  ->leftjoin('psic_groups AS pg', 'psc.group_id', '=', 'pg.id')
                  ->leftjoin('psic_classes AS pc', 'psc.group_id', '=', 'pc.id')
                  ->select('bbp.id as ID','subclass_code','subclass_description','busp_no_units','busp_capital_investment','busp_essential','busp_non_essential')
                  ->where('bbp.busn_id',$busn_id);
        
            //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
                if(!empty($q) && isset($q)){
                    $sql->where(function ($sql) use($q,$remortServer) {
                        $sql->where($remortServer->raw('LOWER(psc.subclass_code)'),'like',"%".strtolower($q)."%")
                            ->orWhere($remortServer->raw('LOWER(psc.subclass_description)'),'like',"%".strtolower($q)."%");
                    });
                }
                /*  #######  Set Order By  ###### */
            if(isset($params['order'][0]['column']))
              $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
            else
              $sql->orderBy('id','ASC');
        
            /*  #######  Get count without limit  ###### */
            $data_cnt=$sql->count();
            /*  #######  Set Offset & Limit  ###### */
            $sql->offset((int)$params['start'])->limit((int)$params['length']);
            $data=$sql->get();
            return array("data_cnt"=>$data_cnt,"data"=>$data);
        }
    public function bploMeasurePaxList($request,$busn_id)
        {
            $remortServer = DB::connection('remort_server');
            $params = $columns = $totalRecords = $data = array();
            $params = $_REQUEST;
            $q=$request->input('q');
        
            if(!isset($params['start']) && !isset($params['length'])){
              $params['start']="0";
              $params['length']="10";
            }
        
            $columns = array( 
              0 =>"bbm.buspx_no_units",
              1 =>"bbm.buspx_capacity",
              2 =>"ccd.charge_desc",
              3 =>"psc.subclass_description", 
            );
        
            $sql = $remortServer->table('bplo_business_measure_pax AS bbm')
                  ->leftjoin('psic_subclasses AS psc', 'psc.id', '=', 'bbm.subclass_id')
                  ->leftjoin('bplo_business AS bb', 'bb.id', '=', 'bbm.busn_id')
                  ->leftjoin('bplo_business_psic AS bbp', 'bbp.id', '=', 'bbm.busn_psic_id')
                  ->leftjoin('cto_charge_descriptions AS ccd', 'ccd.id', '=', 'bbm.buspx_charge_id')
                  ->leftjoin('psic_sections AS ps', 'ps.id', '=', 'psc.section_id')
                  ->leftjoin('psic_divisions AS pd', 'psc.division_id', '=', 'pd.id')
                  ->leftjoin('psic_groups AS pg', 'psc.group_id', '=', 'pg.id')
                  ->leftjoin('psic_classes AS pc', 'psc.group_id', '=', 'pc.id')
                  ->select('bbm.id as ID','subclass_description','buspx_no_units','buspx_capacity','charge_desc')
                  ->where('bbm.busn_id',$busn_id);
        
            //$sql->where('psc.subclass_generated_by', '=', \Auth::user()->creatorId());
                if(!empty($q) && isset($q)){
                    $sql->where(function ($sql) use($q,$remortServer) {
                        $sql->where($remortServer->raw('LOWER(bbm.buspx_no_units)'),'like',"%".strtolower($q)."%")
                            ->orWhere($remortServer->raw('LOWER(bbm.buspx_capacity)'),'like',"%".strtolower($q)."%")
                            ->orWhere($remortServer->raw('LOWER(ccd.charge_desc)'),'like',"%".strtolower($q)."%")
                            ->orWhere($remortServer->raw('LOWER(psc.subclass_description)'),'like',"%".strtolower($q)."%");
    
                    });
                }
                /*  #######  Set Order By  ###### */
            if(isset($params['order'][0]['column']))
              $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
            else
              $sql->orderBy('id','ASC');
        
            /*  #######  Get count without limit  ###### */
            $data_cnt=$sql->count();
            /*  #######  Set Offset & Limit  ###### */
            $sql->offset((int)$params['start'])->limit((int)$params['length']);
            $data=$sql->get();
            return array("data_cnt"=>$data_cnt,"data"=>$data);
        }    
    public function bploRequirmentDocList($request,$busn_id)
        {
            $remortServer = DB::connection('remort_server');
            $params = $columns = $totalRecords = $data = array();
            $params = $_REQUEST;
            $q=$request->input('q');
        
            if(!isset($params['start']) && !isset($params['length'])){
              $params['start']="0";
              $params['length']="10";
            }
        
            $columns = array( 
              0 =>"ps.subclass_description",
              1 =>"bbpr.attachment",
              2 =>"req.req_description" 
            );
        
            $sql = $remortServer->table('bplo_business_psic_req AS bbpr')
                  ->join('requirements AS req', 'req.id', '=', 'bbpr.req_code')
                  ->leftjoin('bplo_business_psic AS bbp', 'bbp.frgn_busn_psic_id', '=', 'bbpr.busn_psic_id')
                  ->leftjoin('psic_subclasses AS ps', 'ps.id', '=', 'bbp.subclass_id')
                  ->select('bbpr.id as ID','attachment','req.req_description','ps.subclass_description')
                  ->where('bbpr.busn_id',$busn_id);
    
                if(!empty($q) && isset($q)){
                    $sql->where(function ($sql) use($q,$remortServer) {
                        $sql->where($remortServer->raw('LOWER(bbpr.attachment)'),'like',"%".strtolower($q)."%")
                            ->orWhere($remortServer->raw('LOWER(req.req_description)'),'like',"%".strtolower($q)."%")
                            ->orWhere(DB::raw('LOWER(ps.subclass_description)'),'like',"%".strtolower($q)."%");
                    });
                }
                /*  #######  Set Order By  ###### */
            if(isset($params['order'][0]['column']))
            {
              $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
            }
            else
            {
              $sql->orderBy('id','ASC');
            }
        
            /*  #######  Get count without limit  ###### */
            $data_cnt=$sql->count();
            /*  #######  Set Offset & Limit  ###### */
            $sql->offset((int)$params['start'])->limit((int)$params['length']);
            $data=$sql->get();
            return array("data_cnt"=>$data_cnt,"data"=>$data);
        } 
    public function approve($id)
    {
        $remortServer = DB::connection('remort_server');
        if(Session::get('IS_SYNC_TO_TAXPAYER')){    
            DB::beginTransaction();
            $rowToUpdate = $remortServer->table('bplo_business')->where('frgn_busn_id',$id)->first();
            $id=$rowToUpdate->id;
            $rowAttributes = get_object_vars($rowToUpdate);
            $frgn_busn_id=$rowAttributes['frgn_busn_id'];
            unset($rowAttributes['id']);
            unset($rowAttributes['frgn_busn_id']);
            unset($rowAttributes['is_approved']);
            unset($rowAttributes['busn_bldg_property_index_no']);
            unset($rowAttributes['busn_bldg_tax_declaration_no']);
            $rowAttributes['online_busn_bldg_property_index_no'] = $rowToUpdate->busn_bldg_property_index_no;
            $rowAttributes['online_busn_bldg_tax_declaration_no'] = $rowToUpdate->busn_bldg_tax_declaration_no;
            $rowAttributes['is_synced'] = 1;

            if($rowToUpdate->app_code == 1){
                DB::table('bplo_business')->insert($rowAttributes);
                $l_bplo_busn_id=DB::getPdo()->lastInsertId();
            }else{
                DB::table('bplo_business')->where('frgn_busn_id',$frgn_busn_id)->update($rowAttributes);
                $l_bplo_busn_id=$frgn_busn_id;
                DB::table('bplo_business_psic_req')->where('busn_id',$l_bplo_busn_id)->delete();
                DB::table('bplo_business_measure_pax')->where('busn_id',$l_bplo_busn_id)->delete();
            }    
            $rowAttributes['busn_id'] = $l_bplo_busn_id;
            $this->addBploBusinessHistory($rowAttributes);//adding bplo business history
            $bplo_business_psic = $remortServer->table('bplo_business_psic')->where('busn_id',$frgn_busn_id)->get();
            $ext_busn_psic_id=array();
            foreach($bplo_business_psic as $key=>$item)
            {
                $psic_id=$item->id;
                $psicRowAttributes = get_object_vars($item);
                $frgn_busn_psic_id=$psicRowAttributes['frgn_busn_psic_id'];
                unset($psicRowAttributes['id']);
                unset($psicRowAttributes['busn_id']);
                unset($psicRowAttributes['frgn_busn_psic_id']);
                $psicRowAttributes['busn_id'] = $l_bplo_busn_id;
                $psicRowAttributes['is_synced'] = 1;
                if($rowToUpdate->app_code == 1){
                    DB::table('bplo_business_psic')->insert($psicRowAttributes);
                    $l_busn_psic_id=DB::getPdo()->lastInsertId();
                }else{
                    $bplo_business_psic_u=DB::table('bplo_business_psic')->where('id',$frgn_busn_psic_id)->first();
                    if(!empty($bplo_business_psic_u)){
                        $bplo_business_psic_u->update($psicRowAttributes);
                        $l_busn_psic_id=$frgn_busn_psic_id;
                    }else{
                        DB::table('bplo_business_psic')->insert($psicRowAttributes);
                        $l_busn_psic_id=DB::getPdo()->lastInsertId();
                    }
                }  
                $psicRowAttributes['busn_psic_id']=$l_busn_psic_id;
                $psicRowAttributes['busp_tax_year']=$rowToUpdate->busn_tax_year;
                $this->addBploBusnPsicHistory($psicRowAttributes);//adding bplo business psic history
                $ext_busn_psic_id[$key]=$l_busn_psic_id;

                $bplo_business_psic_req = $remortServer->table('bplo_business_psic_req')->where('busn_psic_id',$frgn_busn_psic_id)->get();
                foreach($bplo_business_psic_req as $item){
                    $psicReqRowAttributes = get_object_vars($item);
                    unset($psicReqRowAttributes['id']);
                    unset($psicReqRowAttributes['busn_id']);
                    unset($psicReqRowAttributes['busn_psic_id']);
                    $psicReqRowAttributes['busn_id'] = $l_bplo_busn_id;
                    $psicReqRowAttributes['busn_psic_id'] = $l_busn_psic_id;
                    $psicReqRowAttributes['is_synced'] = 1;

                    // $destinationPath =  public_path().'/uploads/bplo_business_req_doc/'.$item->attachment;
                    // $fileContents = file_get_contents($destinationPath);
                    // $remotePath = 'public/uploads/bplo_business_req_doc/'.$item->attachment;
                    // $error = Storage::disk('remote')->put($remotePath, $fileContents);

                    $remotePath = 'public/uploads/bplo_business_req_doc/' . $item->attachment;
                    // Retrieve the file contents from the remote server
                    $fileContents = Storage::disk('remote')->get($remotePath);
                    if ($fileContents !== false) {
                        // Define the local path where you want to save the file
                        $localPath = public_path() . '/uploads/bplo_business_req_doc/'.$item->attachment;
                        $result = file_put_contents($localPath, $fileContents);
                        // Use file_put_contents to save the retrieved file contents locally
                        /*if (file_put_contents($localPath, $fileContents) !== false) {
                            // File was successfully transferred from remote server to local path
                        } */
                    }

                    DB::table('bplo_business_psic_req')->insert($psicReqRowAttributes);
                    $l_busn_psic_req_id=DB::getPdo()->lastInsertId();
                    $psicReqRowAttributes['busn_psic_req_id']=$l_busn_psic_req_id;
                    $this->addBploBusnPsicReqHistory($psicReqRowAttributes);//adding bplo business psic req history
                    $remortServer->table('bplo_business_psic_req')->where('id',$item->id)->update(['busn_id' => $l_bplo_busn_id,'busn_psic_id' => $l_busn_psic_id]);
                }
                $bplo_business_measure_pax = $remortServer->table('bplo_business_measure_pax')->where('busn_psic_id',$frgn_busn_psic_id)->get();
                foreach($bplo_business_measure_pax as $item)
                {
                    $measurePaxRowAttributes = get_object_vars($item);
                    unset($measurePaxRowAttributes['id']);
                    unset($measurePaxRowAttributes['busn_id']);
                    unset($measurePaxRowAttributes['busn_psic_id']);
                    $measurePaxRowAttributes['busn_id'] = $l_bplo_busn_id;
                    $measurePaxRowAttributes['busn_psic_id'] = $l_busn_psic_id;
                    $measurePaxRowAttributes['is_synced'] = 1;
                    DB::table('bplo_business_measure_pax')->insert($measurePaxRowAttributes);
                    $l_busn_measure_pax=DB::getPdo()->lastInsertId();
                    $measurePaxRowAttributes['buspx_id']=$l_busn_measure_pax;
                    $measurePaxRowAttributes['buspx_year']=$rowToUpdate->busn_tax_year;
                    $this->addBploMeasurePaxHistory($measurePaxRowAttributes);//adding bplo business measure pax history
                    $remortServer->table('bplo_business_measure_pax')->where('id',$item->id)->update(['busn_id' => $l_bplo_busn_id,'busn_psic_id' => $l_busn_psic_id]);
                }
                $remortServer->table('bplo_business_psic')->where('id',$psic_id)->update(['busn_id' => $l_bplo_busn_id,'frgn_busn_psic_id' => $l_busn_psic_id]);
            }
            DB::table('bplo_business_psic')->where('busn_id',$l_bplo_busn_id)->whereNotIn('id',$ext_busn_psic_id)->delete();
            $remortServer->table('bplo_business')->where('id',$id)->update(['frgn_busn_id' => $l_bplo_busn_id,'is_approved' => 1]);

            // Online Access table update only for New
            if($rowToUpdate->app_code == 1){
                $this->updateOnlineAccess($l_bplo_busn_id,$rowToUpdate->client_id);
            }

            DB::commit();
            return $l_bplo_busn_id;
        }    


        try {

        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Handle the exception
        }    
    }    
    public function updateOnlineAccess($busn_id,$client_id){
        $data = array();
        $data['client_id'] = $client_id;
        $data['busn_id'] = $busn_id;
        $data['taxpayer_id'] = $client_id;
        $data['is_active'] = 1;
        $data['created_by']=\Auth::user()->id;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_by']=\Auth::user()->id;
        $data['updated_at'] = date('Y-m-d H:i:s');
        DB::table('bplo_online_accesss')->insert($data);
        $lastId= DB::getPdo()->lastInsertId();

        if(Session::get('IS_SYNC_TO_TAXPAYER')){
            $remortServer = DB::connection('remort_server');
            $data['frgn_id'] = $lastId;
            $data['is_synced'] = 1;
            $remortServer->table('bplo_online_accesss')->insert($data);
            DB::table('bplo_online_accesss')->where('id',$lastId)->update(array('is_synced'=>1));
        }
    }
    public function decline($id)
    {
        $remortServer = DB::connection('remort_server');
        try {
            DB::beginTransaction();
            $update_status=$remortServer->table('bplo_business')->where('frgn_busn_id',$id)->update(['is_approved' => 2]);
            DB::commit();
            return $update_status;
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();
            // Handle the exception
        }    
    } 

  public function getRptPropertyList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $client_id=$request->input('client_id');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }
    $columns = array( 
      0 =>"id",  
      1 => 'rp.rp_tax_declaration_no',
      2 => 'c.full_name',
      3 =>"bgy.brgy_name",
      4 =>"rp.rp_pin_declaration_no",
      5 =>"cctUnitNo",
      6 =>"propertyClass",
      7 =>"assessedValue"
    );
    

    $sql =DB::table('rpt_properties as rp')
            ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
            ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
            ->join('barangays AS bgy', 'bgy.id', '=', 'rp.brgy_code_id')
            ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rp.id')
            ->leftJoin('rpt_property_machine_appraisals as ma','ma.rp_code','=','rp.id')
            ->select('rp.rp_tax_declaration_no','rp.pk_is_active','rp.rp_pin_declaration_no','rp.rp_property_code','rp.rpo_code','bgy.brgy_name','c.full_name',
                    DB::raw("CASE WHEN rp.pk_id = 2 THEN rp.rp_cadastral_lot_no WHEN rp.pk_id = 1 THEN CONCAT(COALESCE(rp.rp_building_cct_no,''),';',COALESCE(rp.rp_building_unit_no,'')) WHEN rp.pk_id = 3 THEN GROUP_CONCAT(DISTINCT rpma_description SEPARATOR ';') END as cctUnitNo"),
                    DB::raw("CASE 
                WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_assessed_value)) 
                WHEN pk.pk_code = 'B' THEN SUM(COALESCE(rp.rpb_assessed_value))
                WHEN pk.pk_code = 'M' THEN SUM(COALESCE(ma.rpm_assessed_value))
                END as assessedValue"),
                    DB::raw("CASE 
                                WHEN pk.pk_code = 'L' THEN rpt_property_appraisals.pc_class_code 
                                WHEN pk.pk_code = 'B' THEN rp.pc_class_code
                                WHEN pk.pk_code = 'M' THEN ma.pc_class_code
                                END as propertyClass"),
                    DB::raw("(SELECT pc_class_description FROM rpt_property_classes WHERE id = propertyClass) as propertyClass")
                );
    $sql->where('rp.pk_is_active',1)->groupBy('rp.id');            
    if(!empty($client_id) && isset($client_id)){            
        $sql->where('rp.rpo_code',$client_id);            
    }            
    if(!empty($q) && isset($q)){
        $sql->where(function ($sql) use($q) {
            $sql->orWhere(DB::raw('LOWER(bgy.brgy_code)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(bgy.brgy_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(c.rpo_address_house_lot_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(c.rpo_address_street_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(c.p_tin_no)'),'like',"%".strtolower($q)."%")
                ->orWhere('c.p_mobile_no','like',"%".$q."%");
        });
    }
    /*if(!empty($alphabet) && isset($alphabet)){
         $sql->havingRaw('customername LIKE %'.$alphabet);
    }*/

    /*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('rp.id','DESC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
  }
    
}
