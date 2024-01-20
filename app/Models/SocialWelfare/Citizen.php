<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Barangay;
use App\Models\Country;
use App\Models\HrEmployee;
use App\Models\ProfileMunicipality;
use App\Models\ProfileProvince;
use App\Models\ProfileRegion;
use App\Models\Locality;
use App\Models\HoRecordCard;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use App\Traits\ModelUpdateCreate;
class Citizen extends Model
{
      use ModelUpdateCreate;
      
      protected $guarded = ['id'];

      public $timestamps = false;

      public $table = 'citizens';

      public function locality()//get rpt_locality table 
      {
          $department = 3; //department id for locality
          return Locality::where('department',$department)->first();
      }

      public function addData($postdata){
            if (isset($postdata['brgy_id'])) {
                  $house_lot = isset($postdata['cit_house_lot_no'])?$postdata['cit_house_lot_no'].', ':'';
                  $street = isset($postdata['cit_street_name'])?$postdata['cit_street_name'].', ':'';
                  $subdivision = isset($postdata['cit_subdivision'])?$postdata['cit_subdivision'].', ':'';
                  $fulladdress = $house_lot.$street.$subdivision.self::getAdditionalAdd($postdata['brgy_id']);
                  $postdata = array_merge($postdata,[
                        'cit_full_address'=>$fulladdress
                  ]);
            }
            $this->insert($postdata);
            return DB::getPdo()->lastInsertId();
      }
      public function medical_record()
      {
            return $this->hasOne(HoRecordCard::class, 'cit_id', 'id');
      }
      public function brgy() 
      {
            return $this->hasOne(Barangay::class, 'id', 'brgy_id');
      }
      public function getAdditionalAdd($id)
      {
            $brgy = Barangay::find($id);
            return $brgy->brgy_name . ', ' . $brgy->municipality->mun_desc .', ' . $brgy->province->prov_desc  .', ' . $brgy->region->reg_region;
      }
      public function brgy_name()
      {
            return $this->brgy->brgy_name . ', ' . $this->brgy->municipality->mun_desc .', '. $this->brgy->province->prov_desc  .', '  . $this->brgy->region->reg_region ;
      }
      public function getBarangayNameAttribute()
      {
            // dd($this->brgy->brgy_name);
            return $this->brgy->brgy_name . ', ' . $this->brgy->municipality->mun_desc .', '. $this->brgy->province->prov_desc  .', '  . $this->brgy->region->reg_region ;
      }
      public function full_add()
      {
            $address = '';
            if ($this->cit_house_lot_no) {
                  $address .= $this->cit_house_lot_no . ', ';
            }
            if ($this->cit_street_name) {
                  $address .= $this->cit_street_name . ', ';
            }
            
            if ($this->cit_subdivision) {
                  $address .= $this->cit_subdivision . ', ';
            }
            return   $address. $this->brgy->brgy_name . ', ' . $this->brgy->province->prov_desc  .', ' . $this->brgy->municipality->mun_desc .', ' . $this->brgy->region->reg_region ;
      }
      public function education()
      {
            $id = $this->cea_id;
            return config('constants.citEducationalAttainment')[$id];
      }
      public function status()
      {
            $id = $this->ccs_id;
            return config('constants.citCivilStatus')[$id];
      }
      public function gender()
      {
            $id = $this->cit_gender;
            return config('constants.citGender')[$id];
      }
      public function getGenderAttribute()
      {
            $id = $this->cit_gender;
            return config('constants.citGender')[$id];
      }
      public function age()
      {
            return Carbon::parse($this->cit_date_of_birth)->age;
      }
      public function getAgeAttribute()
      {
            return Carbon::parse($this->cit_date_of_birth)->age;
      }
      function getAgeHumanAttribute()
      {
            return Carbon::parse($this->cit_date_of_birth)->diffForHumans(['syntax' => CarbonInterface::DIFF_ABSOLUTE]) . ' old'; 
      }
      function getCitAgeDaysAttribute()
      {
            return Carbon::parse($this->cit_date_of_birth)->diff(Carbon::now())->days; 
      }
      public function getEditDetails($id){
            return $this->where('id',$id)->first();
      }

      public function country() 
      { 
            return $this->hasOne(Country::class, 'id', 'country_id'); 
      } 

      public function nationality()
      {
            return $this->country ? $this->country->nationality : '';
      }
      public function fullname()
      {
            return $this->cit_first_name.' '.$this->cit_middle_name.' '.$this->cit_last_name.' '.$this->cit_suffix_name;
      }
      public function getCitizenDetails($id){
        return DB::table('citizens')->select('doc_json')->where('id',$id)->first();
      }
      public function updateCitizen($id,$columns){
        return DB::table('citizens')->where('id',$id)->update($columns);
      }
      public function getCitizenEdit($id){
        return DB::table('citizens')->select('*')->where('id',(int)$id)->first();
      }
      public function updateData($id,$columns){
            if (isset($columns['brgy_id'])) {
                  $house_lot = isset($columns['cit_house_lot_no'])?$columns['cit_house_lot_no'].', ':'';
                  $street = isset($columns['cit_street_name'])?$columns['cit_street_name'].', ':'';
                  $subdivision = isset($columns['cit_subdivision'])?$columns['cit_subdivision'].', ':'';
                  $fulladdress = $house_lot.$street.$subdivision.self::getAdditionalAdd($columns['brgy_id']);
                  $columns = array_merge($columns,[
                        'cit_full_address'=>$fulladdress
                  ]);
            }
            return self::find($id)->update($columns);
      }

      public function getList($request)
      {
            $params = $columns = $totalRecords = $data = array();
            $params = $_REQUEST;
            $q=$request->input('q');
            if(!isset($params['start']) && !isset($params['length'])){
                  $params['start']="0";
                  $params['length']="10";
            }
            $columns = array( 
                  1 =>"cit_fullname",
                  2 =>"brgy_id",
                  3 =>"cit_date_of_birth",   
                  4 =>"cit_gender",   
                  5 =>"cit_is_active",   
            );
            $sql = $this->select('citizens.*','brgy_name')->leftjoin('barangays', 'barangays.id', '=', 'citizens.brgy_id');
            if(!empty($q) && isset($q)){
                  $sql = $sql->where(function ($query) use($q) {
                        $query->where(DB::raw('LOWER(cit_fullname)'),'like',"%".strtolower($q)."%");
                        $query->orWhere(DB::raw('LOWER(cit_date_of_birth)'),'like',"%".strtolower($q)."%");
                  })->orWhereHas('brgy', function ($query) use($q){
                        $query->where('brgy_name','like',"%".strtolower($q)."%");
            });
      }
           /*  #######  Set Order By  ###### */
            if(isset($params['order'][0]['column'])){
                  if ($columns[$params['order'][0]['column']] === 'brgy_id') {
                        $type = $params['order'][0]['dir'];
                        $sql = $sql->orderBy('brgy_name',$type);
                        // dd($sql->get());
                  } else {
                        $sql = $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
                  }
            } else {
                  $sql = $sql->orderBy('citizens.id','DESC');
            }

           /*  #######  Get count without limit  ###### */
            $data_cnt=$sql->count();
            /*  #######  Set Offset & Limit  ###### */
            $sql->offset((int)$params['start'])->limit((int)$params['length']);
            $data=$sql->get();
            // foreach ($data as $key => $value) {
            //       $data[$key]['brgy_name'] = $value->brgy->brgy_name;
            // }
            return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

      public function getCitizen($search="")
      {
            $page=1;
            if(isset($_REQUEST['page'])){
            $page = (int)$_REQUEST['page'];
            }
            $length = 20;
            $offset = ($page - 1) * $length;
            $sql = self::where('cit_is_active',1);
            if(!empty($search)){
                  $sql->where(function ($sql) use($search) {
                        if(is_numeric($search)){
                              $sql->Where('id',$search);
                        }else{
                              $sql->where(DB::raw('LOWER(cit_fullname)'),'like',"%".strtolower($search)."%");
                        }
                  });
            }
            $sql->orderBy('cit_fullname','ASC');
            $data_cnt=$sql->count();
            $sql->offset((int)$offset)->limit((int)$length);
            
            $data=$sql->get();
            return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
      public function getCitizenMunicipalOnly($search="")
      {
            $page=1;
            if(isset($_REQUEST['page'])){
            $page = (int)$_REQUEST['page'];
            }
            $length = 20;
            $offset = ($page - 1) * $length;

            $mun_id = self::locality()->mun_no;
            $brgys = Barangay::where([['mun_no',$mun_id],['is_active', 1]])->get()->map(function ($brgy) {
                  return $brgy->id ;
              });

            $sql = self::where('cit_is_active',1)->whereIn('brgy_id',$brgys);
            if(!empty($search)){
                  $sql->where(function ($sql) use($search) {
                        if(is_numeric($search)){
                              $sql->Where('id',$search);
                        }else{
                              $sql->where(DB::raw('LOWER(cit_fullname)'),'like',"%".strtolower($search)."%");
                        }
                  });
            }
            $sql->orderBy('cit_fullname','ASC');
            $data_cnt=$sql->count();
            $sql->offset((int)$offset)->limit((int)$length);
            
            $data=$sql->get();
            return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

      public function selectBrgy($request)
      {
            $data = [];
            $q=$request->input('q');
            $brgy = Barangay::where('is_active', 1)->take(5)->get();
            if(!empty($q) && isset($q)){
                  $brgy = Barangay::where('is_active', 1)->where(DB::raw('LOWER(brgy_name)'),'like',"%".strtolower($q)."%")->take(5)->get();
            }
            foreach ($brgy as $key => $value) {
                  $data += [$value->id => $value->brgy_name .', ' . $value->municipality->mun_desc .', ' . $value->province->prov_desc .', '. $value->region->reg_region];
            }
            return $data;
      }
      public function getBrgy()
      {
            $data = [];
            $brgy = Barangay::where('is_active', 1)->get(); //if they want all brgy
            foreach ($brgy as $key => $value) {
                  $data += [$value->id => $value->brgy_name .', ' . $value->municipality->mun_desc .', ' . $value->province->prov_desc .', '. $value->region->reg_region];
            }
            return $data;
      }
      public function getBrgyMunicipalOnly()
      {
            $data = [];
            $mun_id = self::locality()->mun_no;
            $brgy = Barangay::where([['mun_no',$mun_id],['is_active', 1]])->get();
            foreach ($brgy as $key => $value) {
                  $data += [$value->id => $value->brgy_name .', ' . $value->municipality->mun_desc .', ' . $value->province->prov_desc .', '. $value->region->reg_region];
            }
            return $data;
      }

      public function getNationality()
      {
            $data = [];
            $brgy = Country::where('is_active', 1)->get();
            foreach ($brgy as $key => $value) {
                  $data += [$value->id => $value->nationality];
            }
            return $data;
      }
      public function selectNationality($request)
      {
            $data = [];
            $q=$request->input('q');
            $brgy = Country::where('is_active', 1)->take(5)->get();
            if(!empty($q) && isset($q)){
                  $brgy = Country::where('is_active', 1)->where(DB::raw('LOWER(nationality)'),'like',"%".strtolower($q)."%")->take(5)->get();
            }
            foreach ($brgy as $key => $value) {
                  $data += [$value->id => $value->nationality];
            }
            return $data;
      }

      public function allCitizens($vars = '')
      {
            $citizens = self::where('cit_is_active', 1)->orderBy('id', 'asc')->get();
      
            $cits = array();
            if (!empty($vars)) {
                  $cits[] = array('' => 'select a '.$vars);
            } else {
                  $cits[] = array('' => 'select a citizen');
            }
            foreach ($citizens as $citzen) {
                  $cits[] = array(
                        $citzen->id => $citzen->cit_fullname
                  );
            }

            $citizens = array();
            foreach($cits as $cit) {
                  foreach($cit as $key => $val) {
                        $citizens[$key] = $val;
                  }
            }

            return $citizens;
      }  
}
