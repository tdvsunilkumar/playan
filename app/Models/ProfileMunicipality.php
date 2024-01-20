<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class ProfileMunicipality extends Model
{

  public $table = 'profile_municipalities';
    public function updateDataMenuPermission($id,$columns){
      return DB::table('menu_modules')->where('id',$id)->update($columns);
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('profile_municipalities')->where('id',$id)->update($columns);
    }   
     public function updateData($id,$columns){
        $data = tap(DB::table('profile_municipalities')->where('id',$id))->update($columns);
        $respinseModel = $data->first();
        return $respinseModel->id;
    }

    public function newLocality(){
        return $this->belongsTo(Locality::class,'mun_no','loc_local_code');
    }

    public function provinceDetails(){
        return $this->belongsTo(ProfileProvince::class,'prov_no');
    }
    public function addData($postdata){
               DB::table('profile_municipalities')->insert($postdata);
               return DB::getPdo()->lastInsertId();
    }
    
    public function ProfileProvinceData($id){
        return DB::table('profile_regions')->select('id','reg_region','reg_description','uacs_code')->where('id',(int)$id)->where('is_active',1)->first();
    }
    public function getProfileProvince(){
         return DB::table('profile_regions')->select('id','reg_region','reg_description')->where('is_active',1)->get();
    }
    public function getProfileBarangay($mun_no){
      return DB::table('barangays')->where('mun_no',$mun_no)->select('id','brgy_code','brgy_name')->where('brgy_display_for_rpt',1)->where('is_active',1)->get();
    }
    public function getprofileRegioncodebyid($id){
        return DB::table('profile_provinces')->select('id','prov_desc','uacs_code')->where('is_active',1)->where('reg_no','=',$id)->get();
    }
    
    public function getHrEmployeeCode(){
        return DB::table('hr_employees')->select('id','firstname','middlename','lastname','suffix','title')->get();
    }
    public function getMunId(){
        return DB::table('profile_municipalities')->select('id','mun_no','mun_desc')->where('is_active',1)->where('mun_display_for_rpt',1)->get();
    }
     public function getlocalitydata(){
        return DB::table('rpt_locality')->whereIn('department',array(1,2,3,4,5,6,7,8))->get()->toArray();
    }
    public function updatelocality($id,$columns){
        return DB::table('rpt_locality')->where('id',$id)->update($columns);
    }
    public function remotraddLocalityData($postdata){
       $remortServer = DB::connection('remort_server');
         $remortServer->table('rpt_locality')->insert($postdata);
    }
    public function remoteupdatelocality($id,$columns){
      $remortServer = DB::connection('remort_server');
        return $remortServer->table('rpt_locality')->where('id',$id)->update($columns);
    }
    public function addLocalityData($postdata){
       DB::table('rpt_locality')->insert($postdata);
      return DB::getPdo()->lastInsertId();
    }
     public function checkLocalityExist($dept_id){
        //->where('mun_no',$mumid)
        return DB::table('rpt_locality')->select('*')->where('department',$dept_id)->get()->toArray();
    }
     public function getOfficerPosition($id){
        return DB::table('hr_employees as emp')->join('hr_designations AS des', 'des.id', '=', 'emp.hr_designation_id')->select('emp.id','description')->where('emp.id',(int)$id)->first();
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
          1 =>"reg_region",
          2 =>"prov_desc",
          3 =>"mun_no",
          4 =>"mun_desc",
          5 =>"mun_zip_code",
          6 =>"mun_area_code",
          7 =>"mun_display_for_bplo",
          8 =>"mun_display_for_rpt",
          9 =>"mun_display_for_welfare",
          10=>"mun_display_for_accounting",
          11=>"mun_display_for_economic",
          12=>"mun_display_for_cpdo",
          13=>"mun_display_for_eng",
          14=>"mun_display_for_occupancy",
          15=>"is_active"
          
         );
         $sql = DB::table('profile_municipalities AS bgf')
              ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
              ->select('bgf.id','mun_code','mun_no','mun_zip_code','mun_area_code','mun_display_for_bplo','mun_display_for_rpt','mun_display_for_welfare','mun_display_for_accounting','mun_desc','pp.prov_desc','pr.reg_region','pr.reg_description','bgf.is_active','mun_display_for_economic','mun_display_for_cpdo','mun_display_for_eng','mun_display_for_occupancy');
        // return DB::table('bplo_system_parameters')->select('locality','name')->get();
        //$sql->where('bgf.created_by', '=', \Auth::user()->creatorId());
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(mun_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pr.reg_region)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pr.reg_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pp.prov_desc)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(mun_zip_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(mun_area_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(mun_desc)'),'like',"%".strtolower($q)."%");
                   
                    
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('bgf.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }


      public function getActiveMuncipalityCode($value=''){
      $sql = DB::table('profile_municipalities as mun')
                  ->select('mun.*','loc.loc_group_default_barangay_id')
                  ->join('rpt_locality as loc',function($j){
                    $j->on('loc.mun_no','=','mun.id')->where('loc.department',1);
                  });
            $sql->where(function ($sql) use($value) {
                if($value != ""){
                  $sql->where('mun.id',$value);
                }else{
                  $sql->where('mun.mun_display_for_rpt',1);
                }
            });
        
              
              return $sql->first();
      }

      public function getRptActiveMuncipalityBarngyCodes(){
        $activeMuncipalityCode = $this->getActiveMuncipalityCode();
        if($activeMuncipalityCode != null){
          $data = DB::table('barangays')->where('mun_no',$activeMuncipalityCode->id)->get();
          return $data;
        }
        return (object)[];
      }

      public function getBrgyForAjaxSelectList($request){
       $activeMuncipalityCode = $this->getActiveMuncipalityCode();
       $term=$request->input('term');
       $query = DB::table('barangays')->select('id',DB::raw("CONCAT(brgy_code,'-',brgy_name) as text"))->where('mun_no',$activeMuncipalityCode->id);
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(brgy_code)'),'like',"%".strtolower($term)."%")
                ->orWhere(DB::raw('LOWER(brgy_name)'),'like',"%".strtolower($term)."%");
            });

        }  
        
        $data = $query->simplePaginate(20);             
        return $data;
      }

      public function setRptActiveForOnlyOne($id = null){
      $munciparity = $this->find($id);

      if($munciparity->mun_display_for_rpt == 1){
        try {
          //dd($this->where('id','!=',$barangay->id)->get());
          $this->where('id','!=',$munciparity->id)->update(['mun_display_for_rpt'=>0]);
        } catch (\Exception $e) {
          echo $e->getMessage();exit;
        }
      }
      if($munciparity->mun_display_for_bplo == 1){
        try {
          //dd($this->where('id','!=',$barangay->id)->get());
          $this->where('id','!=',$munciparity->id)->update(['mun_display_for_bplo'=>0]);
        } catch (\Exception $e) {
          echo $e->getMessage();exit;
        }
      }
    }

    public function province($id)
    {
      return ProfileProvince::find($id);
    }
    public function region($id)
    {
      return ProfileRegion::find($id);
    }
}
