<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;



class OnlineRptPropertyOwner extends Model
{

    public $table = 'clients';

    /* To generate control number */

    public $totalCountOfProp = [];

    public $totalSavedCount = [];

    /* To generate control number */

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

    public function addDataAssessmentNotice($postdata){
        DB::table('rpt_properties_assessment_notices')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function generateControlNo($request, $countExceeded,$changeInDate){
      $noOfCountsOfCO = DB::table('rpt_properties_assessment_notices')->count();
      $controlNo = date("Y").'-'.date("m").str_pad($noOfCountsOfCO+1, 5, '0', STR_PAD_LEFT);
      $subtractedOrNot = 0;
      //dd($controlNo);
      if($countExceeded){
        $allAvailableProps = $this->totalCountOfProp->pluck('id')->toArray();
        $savedProper       = $this->totalSavedCount->pluck('rp_code')->toArray();
        $newAddedProp      = array_merge(array_diff($allAvailableProps, $savedProper), array_diff($savedProper, $allAvailableProps));
        $properties        = DB::table('rpt_properties')->select('id','rp_property_code')->whereIn('id',$newAddedProp)->get();
         foreach ($properties as $key => $prop) {
          if(in_array($prop->id,$savedProper)){
            // Delete it because it is removed.
            DB::table('rpt_properties_assessment_notices')->where('rpo_code',$request->id)->where('rp_code',$prop->id)->delete();
            $subtractedOrNot = 1;
          }else{
            // Add it because it is new added.
            $dataToSave = [
              'rpo_code' => $request->id,
              'rp_code' => $prop->id,
              'rp_property_code' => $prop->rp_property_code,
              'ntob_year' => date("Y"),
              'ntob_month' => date("m"),
              'ntob_control_no' => $controlNo,
              'type' => 1,
              'rp_registered_by' => \Auth::user()->id,
              'created_at' => date("Y-m-d H:i:s")
            ];
            $checkAlreadyExist = DB::table('rpt_properties_assessment_notices')
                                     ->where('rpo_code',$request->id)
                                     ->where('rp_code',$prop->id)
                                     ->first();
            if($checkAlreadyExist == null){
              $this->addDataAssessmentNotice($dataToSave);
            }
            
          }
        }

      }
      if($changeInDate == 1 || $subtractedOrNot == 1){
        $dataToSave = [
              'rpo_code' => $request->id,
              'rp_code' => '',
              'rp_property_code' => '',
              'ntob_year' => date("Y"),
              'ntob_month' => date("m"),
              'ntob_control_no' => $controlNo,
              'type' => ($subtractedOrNot)?2:0,
              'rp_registered_by' => \Auth::user()->id,
              'created_at' => date("Y-m-d H:i:s")
            ];
        $this->addDataAssessmentNotice($dataToSave);
      }
      
    }

    public function eligibleToGenearteCoNumb($request=''){
        $eligibleOrNot = 0;
        $countExceeded = 0;
        $changeInDate  = 0;
        $lastRecord = DB::table('rpt_properties_assessment_notices')
                             ->where('rpo_code',$request->id)
                             ->orderBy('id','DESC')
                             ->first();  
        $this->totalCountOfProp = DB::table('rpt_properties')
                             ->where('rpo_code',$request->id)
                             ->where('is_deleted',0);
        $totalCountOfProp =   $this->totalCountOfProp->count();   
        $this->totalSavedCount = DB::table('rpt_properties_assessment_notices')
                             ->where('rpo_code',$request->id)
                             ->where('type',1);          
        $totalSavedCount  = $this->totalSavedCount->count();
        if($totalCountOfProp != $totalSavedCount){
           $eligibleOrNot  = 1;
           $countExceeded = 1;
        }
                              
        if($lastRecord == null || strtotime(date("d-m-Y")) > strtotime($lastRecord->created_at)){
          $eligibleOrNot = 1;
          $changeInDate  = 1;
        }
        if($eligibleOrNot == 1){
          $this->generateControlNo($request,$countExceeded,$changeInDate);
        }
        /*dd($eligibleOrNot);
        dd($request->id);*/
        
    }

    public function barangy(){
        return $this->belongsTo(Barangay::class,'p_barangay_id_no');
    }
    public function find($id){
        $remortServer = DB::connection('remort_server');
        return $remortServer->table('clients')->where('id', $id)->first();
    }
    public function allOwner($vars = '')
    {
        $owners = self::where('is_active', 1)->where('is_bplo',1)->orderBy('id', 'asc')->get();

        $designs = array();
        if (!empty($vars)) {
            $designs[] = array('' => 'select a '.$vars);
        } else {
            $designs[] = array('' => 'select an Owner');
        }
        foreach ($owners as $row) {
            $designs[] = array(
              $row->id => (!empty($row->rpo_first_name) ? $row->rpo_first_name . ' ' : '') . (!empty($row->rpo_middle_name) ? $row->rpo_middle_name . ' ' : '') . (!empty($row->rpo_custom_last_name) ? $row->rpo_custom_last_name : '') . (!empty($row->suffix) ? ', '.$row->suffix  : '')
            );
        }

        $owners = array();
        foreach($designs as $design) {
            foreach($design as $key => $val) {
                $owners[$key] = $val;
            }
        }

        return $owners;
    }
    public function getClients($vars = '')
    {
        return self::where('is_active', 1)->orderBy('id', 'asc')->get();
    }

    public function checkClientisused($id){
      return DB::table('rpt_properties')->where('rpo_code',$id)->get();
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
     
    public function getCountries(){
        $remortServer = DB::connection('remort_server');
         return $remortServer->table('countries')->select('id','country_name','nationality')->where('is_active',1)->orderby('is_default','Desc')->get();
    } 
    public function getProfile(){
        return DB::table('profiles')->select('id','p_first_name','p_middle_name','p_family_name')->get();
    }
	public function getClientslist(){
        $remortServer = DB::connection('remort_server');
    	return $remortServer->table('clients')->select('id','rpo_custom_last_name','rpo_first_name','rpo_middle_name','suffix')->where('is_rpt',0)->where('rpo_first_name','<>',NULL)->get();
    }
    // public function getBarangay(){
    //     //return DB::table('barangays')->select('id','brgy_code','brgy_name')->get();
    //     return DB::table('barangays AS bgf')
    //           ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
    //           ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
    //           ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
    //           ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','brgy_code','bgf.is_active')->where('bgf.is_active',1)->get();
    // }
    
     public function getProfileDetails($id){
        return DB::table('profiles AS p')
        ->join('barangays AS b', 'b.id', '=', 'p.p_barangay_id_no')
        ->select('b.id','b.brgy_code','b.brgy_name','p.p_mobile_no','p.p_fax_no','p.p_tin_no','p.p_email_address')->where('p.id',(int)$id)->first();
    }
	public function getClientsDetails($id){
     	//echo "here"; exit;
        return DB::table('clients')
              ->select('*')->where('id',(int)$id)->first();
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
    

    public function getList($request)
    {
        $remortServer = DB::connection('remort_server');
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $flt_Status=$request->input('flt_Status');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"rpt.id",  
          1 => 'rpt.full_name',
          2 => 'rpt.rpo_address_house_lot_no',
          3 => 'rpt.gender',
          4 => 'rpt.p_email_address',
          5 =>"rpt.p_mobile_no",
          6 => 'rpt.created_at',
          7 =>"rpt.is_approved", 
        );
        
        $sql = $remortServer->table('clients AS rpt')
              ->join('barangays AS b', 'b.id', '=', 'rpt.p_barangay_id_no')
              ->join('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
              ->select('rpt.id','rpt.suffix','b.brgy_code','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','rpt.rpo_custom_last_name',
              'rpt.rpo_first_name','rpt.rpo_middle_name','rpt.rpo_address_house_lot_no','rpt.rpo_address_street_name','rpt.rpo_address_subdivision','rpt.p_mobile_no','rpt.p_email_address','rpt.gender','rpt.created_at','rpt.is_active','rpt.is_approved','rpt.full_name'
              ,DB::raw("CASE 
              WHEN rpt.rpo_address_house_lot_no IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(rpt.rpo_address_street_name,''),', ',COALESCE(rpt.rpo_address_subdivision,''),', ',COALESCE(b.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
              WHEN rpt.rpo_address_street_name IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(rpt.rpo_address_house_lot_no,''),', ',COALESCE(rpt.rpo_address_subdivision,''),', ',COALESCE(b.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
              WHEN rpt.rpo_address_subdivision IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(rpt.rpo_address_house_lot_no,''),', ',COALESCE(rpt.rpo_address_street_name,''))),',','')
              WHEN rpt.rpo_address_house_lot_no IS NULL AND rpt.rpo_address_street_name IS NULL AND rpt.rpo_address_subdivision IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(b.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
              ELSE REPLACE(TRIM(CONCAT(COALESCE(rpt.rpo_address_house_lot_no,''),', ',COALESCE(rpt.rpo_address_street_name,''),', ',COALESCE(rpt.rpo_address_subdivision,''),', ',COALESCE(b.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','') END as address
              "));
        $sql->where('rpt.is_active',1);
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(b.brgy_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_first_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_address_house_lot_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_address_street_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere('rpt.p_mobile_no','like',"%".$q."%")
                    ->orWhere(DB::raw('LOWER(rpt.p_tin_no)'),'like',"%".strtolower($q)."%");
            });
        } 
        // $sql->whereIn("is_approved",[0,2]);
        if(isset($flt_Status)){
          $sql->where('rpt.is_approved',$flt_Status);
      }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
        {
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        }
        else{
          $sql->orderBy('rpt.id','ASC');
        }

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

      public function getRptopList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        //$alphabet=$request->input('alphabet');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"id",  
          1 => 'customername',
          2 => 'address',
          3 =>"p_mobile_no",
        );
        

        $sql = DB::table('clients AS rpt')
              ->join('barangays AS b', 'b.id', '=', 'rpt.p_barangay_id_no')
              ->join('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
              ->select('rpt.id','rpt.suffix','b.brgy_code','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','rpt.rpo_custom_last_name',
              'rpt.rpo_first_name','rpt.rpo_middle_name','rpt.rpo_address_house_lot_no','rpt.rpo_address_street_name','rpt.rpo_address_subdivision','rpt.p_mobile_no','rpt.is_active',
              DB::raw("CASE 
            WHEN rpt.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(rpt.rpo_middle_name,''),' ',COALESCE(rpt.rpo_custom_last_name,''),', ',COALESCE(rpt.suffix,'')))
            WHEN rpt.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(rpt.rpo_first_name,''),' ',COALESCE(rpt.rpo_custom_last_name,''),', ',COALESCE(rpt.suffix,'')))
            WHEN rpt.suffix IS NULL THEN TRIM(CONCAT(COALESCE(rpt.rpo_first_name,''),' ',COALESCE(rpt.rpo_middle_name,''),' ',COALESCE(rpt.rpo_custom_last_name,'')))
            WHEN rpt.rpo_first_name IS NULL AND rpt.rpo_middle_name IS NULL AND rpt.suffix IS NULL THEN COALESCE(rpt.rpo_custom_last_name,'')
            ELSE TRIM(CONCAT(COALESCE(rpt.rpo_first_name,''),' ',COALESCE(rpt.rpo_middle_name,''),' ',COALESCE(rpt.rpo_custom_last_name,''),', ',COALESCE(rpt.suffix,''))) END as customername
            "),DB::raw("CASE 
            WHEN rpt.rpo_address_house_lot_no IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(rpt.rpo_address_street_name,''),', ',COALESCE(rpt.rpo_address_subdivision,''),', ',COALESCE(b.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
            WHEN rpt.rpo_address_street_name IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(rpt.rpo_address_house_lot_no,''),', ',COALESCE(rpt.rpo_address_subdivision,''),', ',COALESCE(b.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
            WHEN rpt.rpo_address_subdivision IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(rpt.rpo_address_house_lot_no,''),', ',COALESCE(rpt.rpo_address_street_name,''))),',','')
            WHEN rpt.rpo_address_house_lot_no IS NULL AND rpt.rpo_address_street_name IS NULL AND rpt.rpo_address_subdivision IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(b.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
            ELSE REPLACE(TRIM(CONCAT(COALESCE(rpt.rpo_address_house_lot_no,''),', ',COALESCE(rpt.rpo_address_street_name,''),', ',COALESCE(rpt.rpo_address_subdivision,''),', ',COALESCE(b.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','') END as address
            "))
              ->whereExists(function($query)
                {
                    $query->select(DB::raw(1))
                          ->from('rpt_properties')
                          ->whereRaw('rpt_properties.rpo_code = rpt.id');
                });
              //->where('rpt.id',2);
       
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->orWhere(DB::raw('LOWER(b.brgy_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_first_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_address_house_lot_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_address_street_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.p_tin_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere('rpt.p_mobile_no','like',"%".$q."%");
                  
            });
        }
        /*if(!empty($alphabet) && isset($alphabet)){
             $sql->havingRaw('customername LIKE %'.$alphabet);
        }*/

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

      public function approve($request,$id)
      {
          $remortServer = DB::connection('remort_server');
          //try {
            if($request->search == null){
              DB::beginTransaction();
              $rowToUpdate = $remortServer->table('clients')->where('id',$id)->first();
              $rowAttributes = get_object_vars($rowToUpdate);
              $email_val=DB::table('clients')->where('p_email_address',$rowAttributes['p_email_address'])->first();
              if($email_val != null){
                DB::commit();
                return array("msg"=>"Email id is already exist in palayan system.","success"=>false);
              }
              unset($rowAttributes['id']);
              unset($rowAttributes['client_frgn_id']);
              unset($rowAttributes['is_approved']);
              unset($rowAttributes['remarks']);
              unset($rowAttributes['full_name']);
              unset($rowAttributes['account_no']);
              $rowAttributes['is_synced'] = 1;
              $rowAttributes['is_rpt'] = 1;
              $rowAttributes['is_bplo'] = 1;
              $rowAttributes['is_engg'] = 1;
              $rowAttributes['rpo_custom_last_name'] = utf8_encode($rowToUpdate->rpo_custom_last_name);
              $rowAttributes['rpo_first_name'] = utf8_encode($rowToUpdate->rpo_first_name);
              $rowAttributes['rpo_middle_name'] = utf8_encode($rowToUpdate->rpo_middle_name);
              $rowAttributes['is_fire_safety'] = 1;
              $currentYear = date('Y');
              $previousClientNo = $this->getMaxClientNoForYear($currentYear);
              $clientNo = $previousClientNo + 1;
              $rowAttributes['client_year'] = $currentYear;
              $rowAttributes['client_no'] = $clientNo;

              DB::table('clients')->insert($rowAttributes);
              $last_insert_id=DB::getPdo()->lastInsertId();
              $remortServer->table('clients')->where('id',$id)->update(['is_approved' => 1,'client_frgn_id' => $last_insert_id,'is_rpt' => 1,'is_engg' => 1,'is_bplo' => 1,'is_fire_safety' => 1,'client_year' => $currentYear,'client_no' => $clientNo]);
              DB::commit();
              return array("data"=>$last_insert_id,"success"=>true);
            }else{
              DB::beginTransaction();
              $u_data=array(
                        'p_mobile_no' => $request->p_mobile_no_ext,
                        'p_email_address' => $request->p_email_address_ext,
                        'is_synced' => 1
                      );       
              DB::table('clients')->where('id',$request->search)->update($u_data);
              $rowToUpdate = DB::table('clients')->where('id',$request->search)->first();
              $rowAttributes = get_object_vars($rowToUpdate);
              unset($rowAttributes['id']);
              unset($rowAttributes['created_at']);
              unset($rowAttributes['updated_at']);
              unset($rowAttributes['is_synced']);
              unset($rowAttributes['full_name']);
              unset($rowAttributes['account_no']);
              
             
              $pre_rmt_client=$remortServer->table('clients')->where('client_frgn_id',$request->search)->first();
              
              $cur_rmt_client=$remortServer->table('clients')->where('id',$id)->first();
              //dd($cur_rmt_client);
              $rowAttributes['is_approved']=1;
              if(isset($pre_rmt_client)){
                $rowAttributes['password']=$cur_rmt_client->password;
                $email_val=$remortServer->table('clients')->where('p_email_address',$rowAttributes['p_email_address'])->get();
                if(!empty($email_val))
                {
                  if($email_val->count() <= 1){
                    if($email_val[0]->client_frgn_id != $request->search){
                      DB::commit();
                      return array("msg"=>"Sugested client's email id is already exist in the webportal.","success"=>false);
                    }
                  }else{
                    DB::commit();
                    return array("msg"=>"Sugested client's email id is already exist in the webportal.","success"=>false);
                  }
                }
                  
                $remortServer->table('clients')->where('client_frgn_id',$request->search)->update($rowAttributes);
                $remortServer->table('clients')->where('id',$id)->update(['is_approved' => 3]);
              }else{
                $rowAttributes['client_frgn_id']=$request->search;
                $rowAttributes['is_rpt']=1;
                $rowAttributes['is_engg']=1;
                $rowAttributes['is_fire_safety']=1;
                $rowAttributes['is_bplo']=1;
                $email_val=$remortServer->table('clients')->where('p_email_address',$rowAttributes['p_email_address'])->first();
                if($email_val != null){
                  DB::commit();
                  return array("msg"=>"Sugested client's email id is already exist in the webportal.","success"=>false);
                }
                $remortServer->table('clients')->where('id',$id)->update($rowAttributes);

              }
              

              DB::commit();
              return array("data"=>$request->search,"success"=>true);
            }
              
          /*} catch (\Exception $e) {
              // Rollback the transaction if an exception occurs
              DB::rollback();
              // Handle the exception
          }   */ 
      } 
      
      public function decline($request,$id)
      {
          $remortServer = DB::connection('remort_server');
          try {
              DB::beginTransaction();
               $remortServer->table('clients')->where('id',$id)->update(['is_approved' => 2,'remarks' => $request->input('remarks')]);
              DB::commit();
              return $id;
          } catch (\Exception $e) {
              // Rollback the transaction if an exception occurs
              DB::rollback();
              // Handle the exception
          }    
      } 
      public function getClientDetails($id){
        return DB::table('clients')->where('id',$id)->first();
      } 
}
