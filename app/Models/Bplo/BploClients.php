<?php

namespace App\Models\Bplo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BploClients extends Model
{
    public $table = 'clients';

    protected $appends = ['standard_name','standard_address'];

    public function getStandardNameAttribute(){
      $name = '';
      if($this->rpo_first_name == null){
        $name .= $this->rpo_middle_name.' '.$this->rpo_custom_last_name.', '.$this->suffix;
      }else if($this->rpo_middle_name == null){
        $name .= $this->rpo_first_name.' '.$this->rpo_custom_last_name.', '.$this->suffix;
      }else if($this->suffix == null){
        $name .= $this->rpo_first_name.' '.$this->rpo_middle_name.' '.$this->rpo_custom_last_name;
      }else if($this->rpo_first_name == null && $this->rpo_middle_name == null && $this->suffix == null){
        $name .= $this->rpo_custom_last_name;
      }else{
        $name .= $this->rpo_first_name.' '.$this->rpo_middle_name.' '.$this->rpo_custom_last_name.', '.$this->suffix;
      }

      return $name;
    }

    public function barangy(){
        return $this->belongsTo(Barangay::class,'p_barangay_id_no');
    }

    public function getStandardAddressAttribute(){
      $barangyAddress = [];
      $barangyName     = ($this->barangy != null)?$this->barangy->brgy_name:'';
      $muncipality     = ($this->barangy != null && $this->barangy->municipality != null)?$this->barangy->municipality->mun_desc:'';
      $province        = ($this->barangy != null && $this->barangy->province != null)?$this->barangy->province->prov_desc:'';
      $region          = ($this->barangy != null && $this->barangy->region != null)?$this->barangy->region->reg_region:'';
      
      //$barangyAdd       = implode(', ',array_filter($barangyAddress, 'strlen'));
      //dd($barangyAdd);
      $name = [];
      if($this->rpo_address_house_lot_no == null){
        $barangyAddress[] = $this->rpo_address_street_name;
        $barangyAddress[] = $this->rpo_address_subdivision;
      }else if($this->rpo_address_street_name == null){
        $barangyAddress[] = $this->rpo_address_house_lot_no;
        $barangyAddress[] = $this->rpo_address_subdivision;
      }else if($this->rpo_address_subdivision == null){
        $barangyAddress[] = $this->rpo_address_house_lot_no;
        $barangyAddress[] = $this->rpo_address_street_name;
      }else{
        $barangyAddress[] = $this->rpo_address_house_lot_no;
        $barangyAddress[] = $this->rpo_address_street_name;
        $barangyAddress[] = $this->rpo_address_subdivision;
      }
      $barangyAddress[] = $barangyName;
      $barangyAddress[] = $muncipality;
      $barangyAddress[] = $province;
      $barangyAddress[] = $region;

      return implode(', ',array_filter($barangyAddress, 'strlen'));;
    }
    
      public function updateData($id,$columns){
        return DB::table('clients')->where('id',$id)->update($columns);
    }

    public function completeName($value=''){
      return $this->rpo_first_name.' '.$this->rpo_custom_last_name;
    }

    public function address($value=''){
      return $this->completeName().', '.$this->rpo_address_house_lot_no;
    }

    public function addData($postdata){
        DB::table('clients')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateActiveInactive($id,$columns){
    return DB::table('clients')->where('id',$id)->update($columns);
    }

    public function checkClienthasapplication($id){
      return DB::table('bplo_business')->where('client_id',$id)->get();
    } 
     
    public function getCountries(){
         return DB::table('countries')->select('id','country_name','nationality')->where('is_active',1)->orderby('is_default','Desc')->get();
    } 
    public function getProfile(){
        return DB::table('profiles')->select('id','p_first_name','p_middle_name','p_family_name')->get();
    }
    public function getClients(){
    	return DB::table('clients')->select('id','rpo_custom_last_name','rpo_first_name','rpo_middle_name')->where('is_engg',0)->where('rpo_first_name','<>',NULL)->get();
    }
    
    public function getProfileDetails($id){
     	//echo "here"; exit;
        return DB::table('clients')
              ->select('*')->where('id',(int)$id)->first();
    }
    public function getMaxClientNoForYear($year) {
      $maxClientNo = DB::table('clients')
          ->where('client_year', $year)
          ->max('client_no');
  
      // Check if $maxClientNo is null (no data for the year)
      if ($maxClientNo === null) {
          return 0;
      }
  
      return $maxClientNo;
  }
  public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    
    $columns = array( 
      0 =>"brgy_code",  
      1 =>"ownar_name",
      2 =>"address",
      3 =>"p_mobile_no",
      5 =>"is_active",
      
     );
    


   $sql = DB::table('clients AS rpt')
      ->leftJoin('barangays AS b', 'b.id', '=', 'rpt.p_barangay_id_no')
      ->leftJoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
      ->leftJoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
      ->leftJoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
      ->select('rpt.id','rpt.account_no','rpt.suffix', 'b.brgy_code', 'b.brgy_name', 'pm.mun_desc', 'pp.prov_desc', 'pr.reg_region', 'rpt.rpo_custom_last_name', 'rpt.is_bplo', 'rpt.rpo_first_name', 'rpt.rpo_middle_name', 'rpt.rpo_address_house_lot_no', 'rpt.rpo_address_street_name', 'rpt.rpo_address_subdivision', 'rpt.p_mobile_no', 'rpt.is_active',
          DB::raw("CASE 
        WHEN rpt.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(rpt.rpo_middle_name,''),' ',COALESCE(rpt.rpo_custom_last_name,''),', ',COALESCE(rpt.suffix,'')))
        WHEN rpt.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(rpt.rpo_first_name,''),' ',COALESCE(rpt.rpo_custom_last_name,''),', ',COALESCE(rpt.suffix,'')))
        WHEN rpt.suffix IS NULL THEN TRIM(CONCAT(COALESCE(rpt.rpo_first_name,''),' ',COALESCE(rpt.rpo_middle_name,''),' ',COALESCE(rpt.rpo_custom_last_name,'')))
        WHEN rpt.rpo_first_name IS NULL AND rpt.rpo_middle_name IS NULL AND rpt.suffix IS NULL THEN COALESCE(rpt.rpo_custom_last_name,'')
        ELSE TRIM(CONCAT(COALESCE(rpt.rpo_first_name,''),' ',COALESCE(rpt.rpo_middle_name,''),' ',COALESCE(rpt.rpo_custom_last_name,''),', ',COALESCE(rpt.suffix,''))) END as ownar_name
        "),DB::raw("CASE 
        WHEN rpt.rpo_address_house_lot_no IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(rpt.rpo_address_street_name,''),', ',COALESCE(rpt.rpo_address_subdivision,''),', ',COALESCE(b.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
        WHEN rpt.rpo_address_street_name IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(rpt.rpo_address_house_lot_no,''),', ',COALESCE(rpt.rpo_address_subdivision,''),', ',COALESCE(b.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
        WHEN rpt.rpo_address_subdivision IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(rpt.rpo_address_house_lot_no,''),', ',COALESCE(rpt.rpo_address_street_name,''))),',','')
        WHEN rpt.rpo_address_house_lot_no IS NULL AND rpt.rpo_address_street_name IS NULL AND rpt.rpo_address_subdivision IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(b.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
        ELSE REPLACE(TRIM(CONCAT(COALESCE(rpt.rpo_address_house_lot_no,''),', ',COALESCE(rpt.rpo_address_street_name,''),', ',COALESCE(rpt.rpo_address_subdivision,''),', ',COALESCE(b.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','') END as address
        "))
      ->where('rpt.is_bplo', 1);

    if(!empty($q) && isset($q)){
        $sql->where(function ($sql) use($q) {
            $sql->where(DB::raw('LOWER(b.brgy_code)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
				 ->orWhere(DB::raw("CONCAT(rpt.rpo_first_name, ' ', COALESCE(rpt.rpo_middle_name, ''), ' ', COALESCE(rpt.rpo_custom_last_name))"), 'LIKE', "%{$q}%")
                ->orWhere(DB::raw('LOWER(rpt.rpo_address_house_lot_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(rpt.account_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(rpt.rpo_address_street_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(rpt.rpo_address_subdivision)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(pp.prov_desc)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(pr.reg_region)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(pm.mun_desc)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(rpt.p_mobile_no)'),'like',"%".strtolower($q)."%")
				->orWhere(DB::raw('LOWER(rpt.is_active)'),'like',"%".strtolower($q)."%");
        });
    }

    /*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('rpt.id','DESC');
      

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
  }
  public function getEstabilshment($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $client_id=$request->input('client_id');
    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      1 =>"busn_name"
    );
    $sql = DB::table('bplo_business AS b')
    ->select('b.busn_name','b.id AS busn_id','b.app_code')->where('b.client_id',$client_id);

    /*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('b.id','DESC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
  }
  public function getLastORDetails($busn_id){
    return DB::table('cto_cashier')->select('cashier_or_date','or_no')->where('busn_id',(int)$busn_id)->orderBy('id','DESC')->first();
  }
  public function getPaymentStatus($busn_id){
    return DB::table('cto_bplo_final_assessment_details')->select('payment_status')->where('busn_id',(int)$busn_id)->orderBy('id','DESC')->first();
  }
  public function getBploBusinessList($search='',$is_checked=false,$client_id=0){
    $page=1;
    if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
    }
    $length = 20;
    $offset = ($page - 1) * $length;

    if(!$is_checked){
      $sql = DB::table('bplo_business AS bb')->whereNotExists(function ($query) {
          $query->select(DB::raw(1))
            ->from('bplo_online_accesss AS oa')
            ->whereColumn('oa.busn_id', '=', 'bb.id');
      })->select('id','busn_name','busns_id_no');
    }else{
      $sql = DB::table('bplo_business AS bb')->where('bb.client_id',(int)$client_id);
    }

    $sql->where('bb.busns_id_no','<>','')->where('bb.busns_id_no','!=','0')->where('bb.is_active','1')->whereIn('bb.app_code',[1,2]);

    if(!empty($search)){
      $sql->where(function ($sql) use($search) {
        $sql->where(DB::raw('LOWER(busn_name)'),'like',"%".strtolower($search)."%")
          ->orWhere(DB::raw('LOWER(busns_id_no)'),'like',"%".strtolower($search)."%");
      });
    }
    $sql->orderBy('busn_name','ASC');
    $data_cnt=$sql->count();
    $sql->offset((int)$offset)->limit((int)$length);

    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
  }
  public function getBusniessOnlineAccess($id){
    return DB::table('bplo_online_accesss as oa')
      ->leftJoin('bplo_business AS bb', 'bb.id', '=', 'oa.busn_id')
      ->leftJoin('clients AS c', 'c.id', '=', 'oa.taxpayer_id')
      ->leftJoin('barangays AS bgy', 'bgy.id', '=', 'bb.busn_office_main_barangay_id')
      ->select(
          'oa.id',
          'busn_name',
          'busn_trade_name',
          'bb.busn_tin_no',
          'bb.busn_registration_no',
          'bb.busns_id_no',
          'c.full_name',
          'bgy.brgy_name',
          'oa.is_active'
      )
      ->where('oa.client_id', $id)
      ->get()
      ->toArray();
  }

  public function getBusinessDtls($id){
    return DB::table('bplo_business as bb')
      ->leftJoin('clients AS c', 'c.id', '=', 'bb.client_id')
      ->leftJoin('barangays AS bgy', 'bgy.id', '=', 'bb.busn_office_main_barangay_id')
      ->leftJoin('bplo_business_type AS bt', 'bt.id', '=', 'bb.btype_id')
      ->select(
          'busn_name',
          'busn_trade_name',
          'busn_tin_no',
          'busn_registration_no',
          'busns_id_no',
          'c.full_name',
          'bgy.brgy_name',
          'btype_desc'
      )
      ->where('bb.id', $id)
      ->first();
  }
  public function checkAllTopPaidTransaction($bus_id){
    $currentDate= date("Y-m-d");
    return DB::table('cto_bplo_final_assessment_details')
      ->select('top_transaction_no','payment_status')
      ->where('busn_id',(int)$bus_id)->where('top_transaction_no','>',0)->where('assess_due_date','<=',$currentDate)->orderBy('top_transaction_no','DESC')->limit(1)->first();
  }

}
