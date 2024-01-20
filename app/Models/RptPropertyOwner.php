<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;



class RptPropertyOwner extends Model
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
        return self::where('id', $id)->first();
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
         return DB::table('countries')->select('id','country_name','nationality')->where('is_active',1)->orderby('is_default','Desc')->get();
    } 
    public function getProfile(){
        return DB::table('profiles')->select('id','p_first_name','p_middle_name','p_family_name')->get();
    }
	public function getClientslist($request){
      $term=$request->input('term');
        $query = DB::table('clients')->select('id','full_name as text')->where('is_rpt',0)->where('full_name','<>',NULL);
                           
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(full_name)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;
    }
    public function getBarangay(){
        //return DB::table('barangays')->select('id','brgy_code','brgy_name')->get();
        return DB::table('barangays AS bgf')
              ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
              ->select('bgf.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','pp.prov_no','pr.reg_region','pr.reg_no','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','brgy_code','bgf.is_active')->where('bgf.is_active',1)->get();
    }
    
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

    public function getList($request){
		
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"id",  
          1 =>'full_name',
          2 =>'address',
          3 =>"p_mobile_no",
          4 =>"is_active", 
        );
        
        $sql = DB::table('clients AS rpt')
              ->join('barangays AS b', 'b.id', '=', 'rpt.p_barangay_id_no')
              ->join('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
              ->select('rpt.id','rpt.account_no','rpt.suffix','b.brgy_code','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','rpt.full_address','rpt.rpo_custom_last_name','rpt.full_name',
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
              ->where('is_rpt',1);

        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(b.brgy_code)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw("CONCAT(rpt.rpo_first_name, ' ', COALESCE(rpt.rpo_middle_name, ''), ' ',rpt.rpo_custom_last_name)"), 'LIKE', "%{$q}%")
                    ->orWhere(DB::raw("CONCAT(rpt.rpo_first_name, ' ', COALESCE(rpt.rpo_middle_name, ''), ' ', COALESCE(rpt.rpo_custom_last_name), ', ', rpt.suffix)"), 'LIKE', "%{$q}%")
                    ->orWhere(DB::raw("CONCAT(b.brgy_name,',',pm.mun_desc)"), 'LIKE', "%{$q}%")
                    ->orWhere(DB::raw("CONCAT(b.brgy_name,', ',pm.mun_desc)"), 'LIKE', "%{$q}%")
                    ->orWhere(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(rpt.full_address)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(rpt.full_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_first_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_address_house_lot_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_address_street_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere('rpt.p_mobile_no','like',"%".$q."%")
                    ->orWhere(DB::raw('LOWER(pm.mun_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pp.prov_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pr.reg_region)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.p_tin_no)'),'like',"%".strtolower($q)."%");
            });
        } 

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('rpt.id','ASC');

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
          1 => 'full_name',
          2 => 'address',
          3 =>"p_mobile_no",
          4 =>"p_email_address",
        );
        

        $sql = DB::table('clients AS rpt')
              ->join('barangays AS b', 'b.id', '=', 'rpt.p_barangay_id_no')
              ->join('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
              ->select('rpt.id','rpt.suffix','b.brgy_code','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','rpt.rpo_custom_last_name','rpt.full_name','rpt.full_address',
              'rpt.rpo_first_name','rpt.rpo_middle_name','rpt.rpo_address_house_lot_no','rpt.rpo_address_street_name','rpt.rpo_address_subdivision','rpt.p_mobile_no','rpt.is_active','rpt.p_email_address',
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
                    ->orWhere(DB::raw('LOWER(rpt.full_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(rpt.full_address)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(rpt.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_first_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_address_house_lot_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_address_street_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.p_tin_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere('rpt.p_mobile_no','like',"%".$q."%")
                    ->orWhere(DB::raw('LOWER(rpt.p_email_address)'),'like',"%".strtolower($q)."%");
                  
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

      public function getAllClients($request){
        $term=$request->input('term');
        $query = DB::table('clients')->select('id','full_name as text')->where('is_rpt',1);
                           
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(full_name)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;                
    }
}
