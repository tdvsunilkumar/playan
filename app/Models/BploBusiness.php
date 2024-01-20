<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bplo\BploBusinessType;
use App\Models\Bplo\CtoPaymentMode;
use App\Models\Bplo\BploBusinessLocation;
use Carbon\Carbon;
use DB;

class BploBusiness extends Model
{
    protected $guarded = ['id'];

    public $table = 'bplo_business';
    
    public $timestamps = false; 
    public function getBussClientDetails($id){
        return DB::table('bplo_business as bb')
        ->leftjoin('rpt_locality as rl','bb.locality_id', '=', 'rl.id')
        ->leftjoin('clients as cl','bb.client_id', '=', 'cl.id')
        ->select('loc_local_code','rpo_custom_last_name','busns_id_no','busns_id','bb.client_id','bb.application_date','bb.busn_name','cl.full_name','cl.p_mobile_no',
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
    public function reload_busn_psic_by_Busn_id($id){
        return DB::table('bplo_business_psic')->where('busn_id',$id)->get();
    }
    public function BploBusinessHistoryByBusnId($id,$year, $app_code = 0){
        $query = DB::table('bplo_business_history')
        ->where('busn_id', $id)
        ->where('busn_tax_year', $year);
        if ($app_code > 0) {
            $query->where('app_code', $app_code);
        }
        
        $result = $query->orderBy('id', 'DESC')->get();
        
        return $result;
    }
    public function BploBusnPsicHistoryByPsic($busn_psic_id,$year, $app_code = 0){
        $query = DB::table('bplo_business_psic_history')
                ->where('busn_psic_id', $busn_psic_id)
                ->where('busp_tax_year', $year)
                ->when($app_code > 0, function ($query) use ($app_code) {
                    return $query->where('app_code', $app_code);
                })
                ->first();
            return $query;
    }
    public function reload_busn_req_doc($id){
        return DB::table('bplo_business_psic_req')->where('busn_id',$id)->get();
    }
    public function BploBusnPsicReqHistoryByPsicReq($id,$year, $app_code = 0){
        $query = DB::table('bplo_business_psic_req_history')
                    ->where('busn_psic_req_id',$id)
                    ->where('busreq_year',$year) 
                    ->when($app_code > 0, function ($query) use ($app_code) {
                        return $query->where('app_type_id', $app_code);
                    })
                    ->first();
            return $query;            
    }

    public function reload_busn_measure_pax($id){
        return DB::table('bplo_business_measure_pax')->where('busn_id',$id)->get();
    }
    public function BploBusnMeasurePaxHistoryByBuspx($id,$year, $app_code = 0){
        $query = DB::table('bplo_business_measure_pax_history')
                ->where('buspx_id',$id)
                ->where('buspx_year',$year)
                ->when($app_code > 0, function ($query) use ($app_code) {
                    return $query->where('app_code', $app_code);
                })->first();
        return $query;    
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
         DB::table('bplo_business_measure_pax')->where('id',$id)->update($columns);
        return DB::table('bplo_business_measure_pax')->where('id',$id)->first();
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
        return DB::table('bplo_business_psic_req')->where('req_code','=',$reid)->where('busn_id','=',$busnid)->where('busn_psic_id','=',$psicid)->orderBy('id','desc')->get();
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
        $locality=$this->allLocality();
        $data= DB::table('bplo_business')
                ->select('*', DB::raw("$locality->id as locality_id"),DB::raw("'$locality->loc_local_name' as locality"),DB::raw("$locality->loc_local_code as loc_local_id"))
                ->where('id',$id)->first();
        return $data;
    }
    public function findBploBusnById($id) 
    {
        $data= DB::table('bplo_business')
               ->where('id',$id)->first();
        return $data;
    }
    public function findForUpdateHistory($id){
        $excludedColumns = ['id','created_by','created_at']; // Replace these with the actual column names you want to exclude
        return DB::table('bplo_business')
                    ->where('id',$id)
                    ->select(array_diff(DB::getSchemaBuilder()->getColumnListing('bplo_business'), $excludedColumns))
                    ->first();
    }
    
    public function getBsnType()
    {
        return (new BploBusinessType)->allBusinessType();
    }
    public function getBsnActivity()
    {
        return (new BploBusinessLocation)->allBusinessLocations();
    }
    public function getOwnerName()
    {
        return (new RptPropertyOwner)->allOwner();
    }
    public function getOwnerNameById($id)
    {
        return (new RptPropertyOwner)->find($id);
    }
    public function getRptInfoById($id)
    {
        return (new RptProperty)->find($id);
    }
    
    public function getBarangay()
    {
        return (new Barangay)->allBarangays();
    }
    public function allLocality()
    {
        return (new Locality)->bploLocality();
    }
    
    public function reload_barangay()
    {
        return (new Barangay)->getBarangay();
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
        public function busnType(){
            return $this->belongsTo(BploBusinessType::class,'btype_id')->withDefault();
        }
       public function reload_summary($busn_id)
       { 
            $bploBusiness=self::where('id',$busn_id)->first();
            return $bploBusiness;
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
       
      
    public function listItems($request)
    {             
       $columns = array( 
            0 =>"id",
            1 =>"busn_id_no",
            2 =>"busn_name",  
       );
       $start     = $request->get('start');
       $limit     = $request->get('length');
       $column    = (!isset($request->get('order')['0']['column'])) ? 'bplo_business.id' : $columns[$request->get('order')['0']['column']];
       $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
       $keywords  = $request->get('search')['value'];

       $res = self::select([
           '*',
       ])
    //    ->leftJoin('hr_employees', function($join)
    //    {
    //        $join->on('hr_employees.id', '=', 'gso_departmental_requests.employee_id');
    //    })
    //    ->leftJoin('hr_designations', function($join)
    //    {
    //        $join->on('hr_designations.id', '=', 'gso_departmental_requests.designation_id');
    //    })
    //    ->leftJoin('acctg_departments', function($join)
    //    {
    //        $join->on('acctg_departments.id', '=', 'gso_departmental_requests.department_id');
    //    })
    //    ->leftJoin('acctg_departments_divisions', function($join)
    //    {
    //        $join->on('acctg_departments_divisions.id', '=', 'gso_departmental_requests.division_id');
    //    })
    //    ->leftJoin('gso_purchase_request_types', function($join)
    //    {
    //        $join->on('gso_purchase_request_types.id', '=', 'gso_departmental_requests.request_type_id');
    //    })
    //    ->leftJoin('gso_purchase_types', function($join)
    //    {
    //        $join->on('gso_purchase_types.id', '=', 'gso_departmental_requests.purchase_type_id');
    //    })
       ->where(function($q) use ($keywords) {
           if (!empty($keywords)) {
               $q->where('bplo_business.busn_name', 'like', '%' . $keywords . '%');
           }
       })
       ->orderBy($column, $order);
       $count = $res->count();
       $res   = $res->skip($start)->take($limit)->get();


       return (object) array('count' => $count, 'data' => $res);
    }

    public function getList1($request)
    {             
       $columns = array( 
            0 =>"id",
            1 =>"busn_id_no",
            2 =>"busn_name",  
       );
       $start     = $request->get('start');
       $limit     = $request->get('length');
       $column    = (!isset($request->get('order')['0']['column'])) ? 'bplo_business.id' : $columns[$request->get('order')['0']['column']];
       $order     = (!isset($request->get('order')['0']['dir'])) ? 'desc' : $request->get('order')['0']['dir'];
       $keywords  = $request->get('search')['value'];

       $res = self::select([
           '*',
       ])
       ->where(function($q) use ($keywords) {
           if (!empty($keywords)) {
               $q->where('bplo_business.busn_name', 'like', '%' . $keywords . '%');
           }
       })
       ->orderBy($column, $order);
       $count = $res->count();
       $res   = $res->skip($start)->take($limit)->get();
       return array("data_cnt"=> $count,"data"=>$res);
    }
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $from_date=(!empty($request->input('from_date')))?Carbon::parse($request->input('from_date'))->format('Y-m-d'):'';
        $to_date=(!empty($request->input('to_date')))?Carbon::parse($request->input('to_date'))->format('Y-m-d'):'';
        $brgy=$request->input('brgy');
        $flt_status=$request->input('flt_status');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="8";
        }
        
        $columns = array( 
          1 =>"busns_id_no",  
          2 => 'cc.full_name',
          3 =>"busn_name",
          4 =>"officeBrgy.brgy_name",
          5 =>"bat.app_type",
          6 =>"bb.created_at", 
          7 =>"bb.busn_app_status", 
          8 =>"bb.busn_app_method", 
         );    
        $sql = DB::table('bplo_business AS bb')
            ->leftJoin('clients AS cc', 'cc.id', '=', 'bb.client_id')
            ->leftJoin('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
            ->leftJoin('cto_payment_mode AS cpm', 'cpm.id', '=', 'bb.pm_id')
            ->leftJoin('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
            ->leftJoin('barangays AS officeBrgy', 'officeBrgy.id', '=', 'bb.busn_office_barangay_id')
            ->leftJoin('profile_regions AS pr', 'pr.id', '=', 'officeBrgy.reg_no')
            ->leftJoin('profile_provinces AS pp', 'pp.id', '=', 'officeBrgy.prov_no')
            ->leftJoin('profile_municipalities AS pm', 'pm.id', '=', 'officeBrgy.mun_no')
            ->leftJoin('cto_cashier AS ccash', function ($join) {
                $join->on('ccash.busn_id', '=', 'bb.id')
                    ->where('ccash.id', function ($subquery) {
                        $subquery->select(DB::raw('MAX(id)'))
                            ->from('cto_cashier')
                            ->whereColumn('busn_id', '=', 'bb.id');
                    });
            })
            ->select('bb.*', 'bbt.btype_desc', 'officeBrgy.brgy_name as office_brgy_name', 'ccash.cashier_or_date as last_pay_date', 'pm.mun_desc as office_mun_desc', 'pp.prov_desc as office_prov_desc', 'pr.reg_region as office_reg_region',
               'cc.full_name' ,'cc.rpo_custom_last_name', 'cc.rpo_first_name', 'cc.rpo_middle_name', 'cc.p_telephone_no', 'cc.p_mobile_no', 'bat.app_type', 'cpm.pm_desc');    
        if(!empty($from_date) && isset($from_date)){
                $sql->whereDate('bb.application_date','>=',$from_date);
        }
        if(!empty($to_date) && isset($to_date)){
                        $sql->whereDate('bb.application_date','<=',$to_date);
                }
        if(!empty($brgy) && isset($brgy)){
                $sql->where('bb.busn_office_barangay_id',$brgy);
        } 
        if($flt_status != 9 && isset($flt_status) ){
                $sql->where('bb.busn_app_status',$flt_status);
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
            $sql->where(function ($sql) use($q,$que) {
                if(isset($que))
                {
                    $sql->where(DB::raw('busn_app_status'),$que); 
                }
                else{
                    $sql->where(DB::raw('LOWER(bb.busns_id_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
					 ->orWhere(DB::raw('LOWER(cc.full_name)'),'like',"%".strtolower($q)."%")
                    // ->orWhere(DB::raw("CONCAT(cc.rpo_first_name, ' ',cc.rpo_middle_name,' ',cc.rpo_custom_last_name)"), 'LIKE', "%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(officeBrgy.brgy_name, ', ',pm.mun_desc,', ',pp.prov_desc,', ', pr.reg_region)"), 'LIKE', "%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bat.app_type)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_app_method)'),'like',"%".strtolower($q)."%");
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

    public function getListByClientId($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $client_id=$request->input('client_id');
        // dd($client_id);
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="3";
        }
        
        $columns = array( 
          1 =>"busns_id_no",  
          2 => DB::raw("CONCAT(cc.rpo_first_name, ' ', cc.rpo_middle_name, ' ', cc.rpo_custom_last_name)"),
          3 =>"busn_name",
          4 =>"officeBrgy.brgy_name",
          5 =>"bat.app_type",
          6 =>"bb.created_at", 
          7 =>"bb.busn_app_status", 
          8 =>"bb.busn_app_method", 
         );    
        $sql = DB::table('bplo_business AS bb')
            ->leftJoin('clients AS cc', 'cc.id', '=', 'bb.client_id')
            ->leftJoin('bplo_application_type AS bat', 'bat.id', '=', 'bb.app_code')
            ->leftJoin('cto_payment_mode AS cpm', 'cpm.id', '=', 'bb.pm_id')
            ->leftJoin('bplo_business_type AS bbt', 'bbt.id', '=', 'bb.btype_id')
            ->leftJoin('barangays AS officeBrgy', 'officeBrgy.id', '=', 'bb.busn_office_barangay_id')
            ->leftJoin('profile_regions AS pr', 'pr.id', '=', 'officeBrgy.reg_no')
            ->leftJoin('profile_provinces AS pp', 'pp.id', '=', 'officeBrgy.prov_no')
            ->leftJoin('profile_municipalities AS pm', 'pm.id', '=', 'officeBrgy.mun_no')
            ->leftJoin('cto_cashier AS ccash', function ($join) {
                $join->on('ccash.busn_id', '=', 'bb.id')
                    ->where('ccash.id', function ($subquery) {
                        $subquery->select(DB::raw('MAX(id)'))
                            ->from('cto_cashier')
                            ->whereColumn('busn_id', '=', 'bb.id');
                    });
            })
            ->select('bb.*', 'bbt.btype_desc', 'officeBrgy.brgy_name as office_brgy_name', 'ccash.cashier_or_date as last_pay_date', 'pm.mun_desc as office_mun_desc', 'pp.prov_desc as office_prov_desc', 'pr.reg_region as office_reg_region',
                'cc.rpo_custom_last_name', 'cc.rpo_first_name', 'cc.rpo_middle_name', 'cc.p_telephone_no', 'cc.p_mobile_no', 'bat.app_type', 'cpm.pm_desc');    
        $sql->where('bb.client_id',$client_id);      
        if(!empty($q) && isset($q)){
            $sql->where(DB::raw('LOWER(bb.busns_id_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(cc.rpo_first_name, ' ',cc.rpo_middle_name,' ',cc.rpo_custom_last_name)"), 'LIKE', "%".strtolower($q)."%")
                    ->orWhere(DB::raw("CONCAT(officeBrgy.brgy_name, ', ',pm.mun_desc,', ',pp.prov_desc,', ', pr.reg_region)"), 'LIKE', "%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bat.app_type)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bb.busn_app_method)'),'like',"%".strtolower($q)."%");
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

    public function reload_busn_plan($busn_id)
    { 
        return (new BploBusinessPsic)->reload_busn_plan($busn_id);
    }

    public function reload_measure_pax($busn_plan_id)
    { 
        $bploBusinessPsic=BploBusinessPsic::where('id',$busn_plan_id)->first();
        $BploBusiness=BploBusiness::where('id',$bploBusinessPsic->busn_id)->first();
        $ptfocJsons=DB::table('psic_tfocs')
        ->where('ptfoc_access_type',2)
        ->where('app_code',$BploBusiness->app_code)
        ->where('cctype_id',3)
        ->where('subclass_id',$bploBusinessPsic->subclass_id)
        ->when(DB::raw('ptfoc_effectivity_date IS NOT NULL'), function ($query) {
            $query->where('ptfoc_effectivity_date', '<=', now());
        })
        ->select('ptfoc_json','tfoc_id')
        ->where('ptfoc_is_active',1)->get();
        $chargeIds = [];
        $data1=[];
        $i=0;
        foreach ($ptfocJsons as $json) {
            if($json->ptfoc_json != NULL)
            {
                $data = json_decode($json->ptfoc_json, true);
                    foreach ($data as $item) {
                        $cto_charge_descriptions=DB::table('cto_charge_descriptions')->where('id',$item['charge_id'])->where('req_measure_pax',1)->where('is_active',1)->first();
                        if(!empty($cto_charge_descriptions))
                        {
                            $chargeIds[$item['charge_id']]['charge_id'] = $item['charge_id'];
                            $chargeIds[$item['charge_id']]['tfoc_id'] = $json->tfoc_id;
                            $chargeIds[$item['charge_id']]['charge_desc']=$cto_charge_descriptions->charge_desc;
                        }
                    }
                
            }
          
        }
        return $chargeIds;
    }
    public function find_measure_pax($id)
    { 
        return BploBusinessMeasurePax::where('id',$id)->first();
    }
    

    public function reload_requirments($busn_plan_id)
    { 
        $bploBusinessPsic=BploBusinessPsic::where('id',$busn_plan_id)->first();
        $BploBusiness=BploBusiness::where('id',$bploBusinessPsic->busn_id)->first();
        $data = [];
        $reqs= DB::table('requirements as rq')
        ->leftjoin('bplo_requirement_relations as brr','brr.requirement_id', '=', 'rq.id')
        ->leftjoin('bplo_requirements as br','br.id', '=', 'brr.bplo_requirement_id')
        ->where('br.apptype_id',$BploBusiness->app_code)
        ->where('rq.is_active',1)
        ->where('brr.is_active',1)
        ->where('brr.subclass_id',$bploBusinessPsic->subclass_id)
        ->select('rq.id','brr.id as req_rel_id','rq.req_description','brr.bplo_requirement_id')
        ->get();
        $bploBusinessPsicReq = BploBusinessPsicReq::where('busn_id', $bploBusinessPsic->busn_id)->where('busn_psic_id',$busn_plan_id)->pluck('req_code');
        if(!empty($reqs))
        {
            foreach ($reqs as $req) {
                if (!$bploBusinessPsicReq->contains($req->id)) {
                    $data[$req->req_rel_id]['req_rel_id'] = $req->req_rel_id;
                    $data[$req->req_rel_id]['req_description'] = $req->req_description;
                    $data[$req->req_rel_id]['br_code'] = $req->bplo_requirement_id;
                    $data[$req->req_rel_id]['req_code'] = $req->id;
                    $data[$req->req_rel_id]['psic_sub_class'] = $req->id;
                }
                
            }
        }
        return $data;
    }
    public function checkAllDocument($busn_id)
    { 
        $bploBusinessPsic = BploBusinessPsic::where('busn_id', $busn_id)->get();
        $missingReqLineBusiness = [];
    
        foreach ($bploBusinessPsic as $item) {
            $requirements = $this->reload_requirments($item->id);
            $bploBusinessPsicReq = BploBusinessPsicReq::where('busn_id', $busn_id)->where('busn_psic_id',$item->id)->pluck('req_code');
            $countMissReq=0;
            foreach ($requirements as $requirement) {
                $reqCode = $requirement['req_code'];
                if (!$bploBusinessPsicReq->contains($reqCode)) {
                    $countMissReq++;
                }
            }
            if($countMissReq > 0)
            {
                $missingReqLineBusiness[] = $item->PsicSubclass->subclass_description;
            }
        }
    
        return $missingReqLineBusiness;
    }
    
    public function load_floor_val($rp_code)
    { 
        $rptProperty=RptProperty::where('id',$rp_code)->first();
        $rpt_building_floor_values=DB::table('rpt_building_floor_values')->where('rp_code',$rp_code)->where('rp_property_code',$rptProperty->rp_property_code)->get();
        return $rpt_building_floor_values;
    }
    
    public function checkRetireExist($busn_id,$app_code=0){
        $year = date("Y");
        return DB::table('bplo_business_retirement')->select('id')->where('busn_id',(int)$busn_id)->where('prev_app_code',(int)$app_code)->where('retire_year',(int)$year)->exists();
    }
    public function checkCurrentYearDetails($busn_id=0,$app_code=0,$year=0){
        return DB::table('bplo_business_permit_issuance')->select('id')->where('busn_id',$busn_id)->where('bpi_year',$year)->where('app_type_id',$app_code)->exists();
    }
    public function checkBploIsuuance($busn_id=0,$app_code=0,$year=0){
        return DB::table('bplo_business_permit_issuance')->where('busn_id',$busn_id)->where('bpi_year',$year)->where('app_type_id',$app_code)->first();
    }
    public function getBploDocReqByDept($dept_id){
        return DB::table('bplo_endorsing_dept')->where('edept_status',1)->where('id',$dept_id)->first();
    }
    public function getUplodedDocByReqId($busn_id,$year,$dept){
        return DB::table('bplo_business_endorsement')
                    ->where('documetary_req_json','!=',null)
                    ->where('busn_id',$busn_id)->where('bend_year',$year)->where('endorsing_dept_id',$dept)->first();
    }
    public function getBploEndsByBusnId($busn_id,$year,$dept){
        return DB::table('bplo_business_endorsement')
                    ->where('busn_id',$busn_id)->where('bend_year',$year)->where('endorsing_dept_id',$dept)->first();
    }

    public function getTop5Data($columnName)
    {
        return BploBusiness::select($columnName, DB::raw('COUNT(*) as count'))
            ->groupBy($columnName)
            ->orderByDesc('count')
            ->take(5)
            ->get();
    }
    public function getTop5BusnIdsByYear($year)
    {
        $topBusnIds = DB::table('cto_cashier')
            ->leftjoin('bplo_business','bplo_business.id', '=', 'cto_cashier.busn_id')
            ->select('busn_id','bplo_business.busn_name', DB::raw('SUM(cto_cashier.total_paid_amount) as total_paid_amount'))
            ->where('cto_cashier.cashier_year', $year)
            ->where('cto_cashier.ocr_id', 0)
            ->where('cto_cashier.status', 1)
            ->groupBy('cto_cashier.busn_id')
            ->orderByDesc('cto_cashier.total_paid_amount')
            ->take(5)
            ->get();

        return $topBusnIds;
    }
    public function getAllBoloBusiness($year)
    {
        return BploBusiness::where('busn_tax_year',$year)->where('is_active',1)->get();
    }
    public function getAllBploBusnEnd($year)
    {
        return DB::table('bplo_business_endorsement')
        ->leftjoin('bplo_business', 'bplo_business.id', '=', 'bplo_business_endorsement.busn_id')
        ->leftjoin('bplo_endorsing_dept', 'bplo_endorsing_dept.id', '=', 'bplo_business_endorsement.endorsing_dept_id')
        ->where('bplo_business_endorsement.bend_year', $year)
        ->where('bplo_business.is_active', 1)
        ->select('bplo_business_endorsement.*','bplo_endorsing_dept.edept_name','bplo_business.busn_office_barangay_id')
        ->get();
    }
    public function getAllEndDept()
    {
        return DB::table('bplo_endorsing_dept')->get();
    }
}
