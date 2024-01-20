<?php

namespace App\Models\Cpdo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CpdoClients extends Model
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
     
    public function getCountries(){
         return DB::table('countries')->select('id','nationality')->where('is_active',1)->get();
    } 
    public function getProfile(){
        return DB::table('profiles')->select('id','p_first_name','p_middle_name','p_family_name')->get();
    }
    public function getClients(){
    	return DB::table('clients')->select('id','full_name','rpo_custom_last_name','rpo_first_name','rpo_middle_name','suffix')->where('is_engg',0)->where('rpo_first_name','<>',NULL)->get();
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
          0 =>"brgy_code",  
          1 =>"rpt.full_name",
          2 =>"rpt.rpo_address_house_lot_no",
          3 =>"p_mobile_no",
          4 =>"is_active",
           
        );
        

        $sql = DB::table('clients AS rpt')
              ->leftjoin('barangays AS b', 'b.id', '=', 'rpt.p_barangay_id_no')
              ->leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
              ->leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
              ->leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
              ->select('rpt.id','rpt.account_no','rpt.suffix','b.brgy_code','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','rpt.rpo_custom_last_name','rpt.full_name',
              'rpt.rpo_first_name','rpt.rpo_middle_name','rpt.full_address','rpt.rpo_address_house_lot_no','rpt.rpo_address_street_name','rpt.rpo_address_subdivision','rpt.p_mobile_no','rpt.is_active')->where('is_engg','1');

       
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(b.brgy_code)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(rpt.account_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(rpt.full_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(rpt.full_address)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(rpt.suffix)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(rpt.rpo_middle_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(rpt.rpo_first_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw("CONCAT(rpt.rpo_first_name, ' ',rpt.rpo_middle_name,' ',rpt.rpo_custom_last_name,' ',rpt.suffix)"), 'LIKE', "%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_address_house_lot_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_address_street_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.p_mobile_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(rpt.p_tin_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(rpt.is_active)'),'like',"%".strtolower($q)."%");
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
      
      public function getListFireSafety($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"brgy_code",  
          1 =>"rpo_custom_last_name",
          2 =>"rpo_address_house_lot_no",
          3 =>"p_mobile_no",
          4 =>"is_active",
           
        );
        

        $sql = DB::table('clients AS rpt')
              ->leftjoin('barangays AS b', 'b.id', '=', 'rpt.p_barangay_id_no')
              ->leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
              ->leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
              ->leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
              ->select('rpt.id','rpt.full_name','rpt.suffix','b.brgy_code','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','rpt.rpo_custom_last_name',
              'rpt.rpo_first_name','rpt.rpo_middle_name','rpt.rpo_address_house_lot_no','rpt.rpo_address_street_name','rpt.rpo_address_subdivision','rpt.p_mobile_no','rpt.is_active')->where('is_fire_safety','1');

       
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(b.brgy_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_first_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_address_house_lot_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_address_street_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.p_tin_no)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(rpt.full_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(rpt.suffix)'),'like',"%".strtolower($q)."%");
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
}
