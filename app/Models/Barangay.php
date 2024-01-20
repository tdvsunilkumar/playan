<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Barangay extends Model
{

  public $table = 'barangays';

    public function updateData($id,$columns){
         $data = tap(DB::table('barangays')->where('id',$id))->update($columns);
         $respinseModel = $data->first();
        return $respinseModel->id;
    }

    public function region(){
        return $this->belongsTo(ProfileRegion::class,'reg_no')->withDefault();;
    }

    public function province(){
        return $this->belongsTo(ProfileProvince::class,'prov_no')->withDefault();;
    }

    public function locality(){
        return $this->belongsTo(Locality::class,'mun_no','mun_no');
    }

    public function municipality(){
        return $this->belongsTo(ProfileMunicipality::class,'mun_no')->withDefault();
    }
    
    public function addData($postdata){
		      DB::table('barangays')->insert($postdata);
          return DB::getPdo()->lastInsertId();
	}
     public function updateActiveInactive($id,$columns){
      return DB::table('barangays')->where('id',$id)->update($columns);
    }  
    public function getBarngayMunProvRegionList($search=""){
      $page=1;
      if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
      }
      $length = 20;
      $offset = ($page - 1) * $length;

      $sql = DB::table('profile_municipalities AS pm')
        ->join('profile_regions AS pr', 'pr.id', '=', 'pm.reg_no')
        ->join('profile_provinces AS pp', 'pp.id', '=', 'pm.prov_no')
        ->select('pm.id','pm.mun_desc','pm.mun_no','pp.prov_desc','pp.prov_no','pr.reg_region','pr.reg_no','pm.is_active')->where('pm.is_active',1);
      if(!empty($search)){
        $sql->where(function ($sql) use($search) {
          if(is_numeric($search)){
            $sql->Where('pm.id',$search);
          }else{
            $sql->orWhere(DB::raw("CONCAT(LOWER(pm.mun_desc), ', ', LOWER(pp.prov_desc), ', ', LOWER(pr.reg_region))"), 'like', "%" . strtolower($search) . "%");
          }
        });
      }
      $sql->orderBy('mun_desc','ASC');
      $data_cnt=$sql->count();
      $sql->offset((int)$offset)->limit((int)$length);

      $data=$sql->get();
      return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
	public function getBarangay(){
        // return DB::table('barangays')->select('*')->get();
       return DB::table('barangays AS bgf')
              ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
              ->select('bgf.id','pm.mun_desc','pp.prov_desc','pr.reg_region','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','brgy_display_for_rpt','brgy_code','bgf.is_active')->where('bgf.is_active',1)->get();
    }
      public function getList($request){


        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $barangayId =$request->input('barangayId');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }


        $columns = array( 
          0 =>"mun_desc",
          1 =>"reg_region",
          2 =>"prov_desc",  
          3 =>"mun_desc",
          4 =>"brgy_code",
          5 =>"brgy_name",
          6 =>"brgy_office",
          7 =>"brgy_display_for_bplo",
          8 =>"brgy_display_for_rpt",
          9 =>"is_active"

         );
         $sql = DB::table('barangays AS bgf')
              ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
              ->select('bgf.id','pm.mun_desc','pp.prov_desc','pr.reg_region','brgy_code','brgy_name','brgy_office','brgy_display_for_bplo','brgy_display_for_rpt','brgy_code','bgf.is_active');
       
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(pr.reg_region)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(pm.mun_desc)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(pp.prov_desc)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(brgy_code)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(brgy_name)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(brgy_office)'),'like',"%".strtolower($q)."%")
                     ->orWhere(DB::raw('LOWER(brgy_display_for_bplo)'),'like',"%".strtolower($q)."%");
                     

            });
        }
        if(!empty($barangayId) && isset($barangayId)){
            $sql->where('pm.mun_desc','=',$barangayId);  
        }
        
      
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else

          $sql->orderBy('bgf.id','ASC');

          $sql->orderBy('id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
    public function getProfileProvince(){
         return DB::table('profile_regions')->select('id','reg_region')->where('is_active',1)->get();
    }
    public function getDistrictCodes($id){
         return DB::table('rpt_district')->select('id','dist_code','dist_name')->where('is_active',1)->where('loc_local_code','=',$id)->get();
    }
    public function getprofileRegioncodebyid($id){
        return DB::table('profile_provinces')->select('id','prov_desc')->where('is_active',1)->where('reg_no','=',$id)->get();
    }
    public function getprofileProvcodeId($id){
        return DB::table('profile_municipalities')->select('id','mun_desc','mun_no')->where('is_active',1)->where('prov_no','=',$id)->get();
    }

    public function getBarangayname($id){
      $sql = DB::table('barangays')->select('brgy_name');
      $sql->where('id',$id);
       return $sql->first();
    }


    public function getActiveBarangayCode($value=''){
      $sql = DB::table('barangays AS bgf')
              ->join('profile_regions AS pr', 'pr.id', '=', 'bgf.reg_no')
              ->join('profile_provinces AS pp', 'pp.id', '=', 'bgf.prov_no')
              ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgf.mun_no')
              ->leftJoin('rpt_district as rd', 'rd.id', '=', 'bgf.dist_code')
              ->leftJoin('rpt_locality as loc', 'loc.id', '=', 'rd.loc_local_code')
              ->select('bgf.id','pm.mun_desc','pp.prov_desc','pr.reg_region','bgf.brgy_code','bgf.brgy_name','bgf.brgy_office','bgf.brgy_display_for_bplo','bgf.brgy_display_for_rpt','bgf.brgy_code','bgf.is_active','pm.id as loc_local_code_id','pm.mun_no as loc_local_code','pm.mun_desc as loc_local_name','rd.dist_code','rd.dist_name');
            $sql->where(function ($sql) use($value) {
                if($value != ""){
                  $sql->where('bgf.id',$value);
                }
            });
        
              //dd($sql->first());
              return $sql->first();
    }

    public function setRptActiveForOnlyOne($id = null){
      $barangay = $this->find($id);

      if($barangay->brgy_display_for_rpt == 1){
        try {
          //dd($this->where('id','!=',$barangay->id)->get());
          $this->where('id','!=',$barangay->id)->update(['brgy_display_for_rpt'=>0]);
        } catch (\Exception $e) {
          echo $e->getMessage();exit;
        }
      }
      if($barangay->brgy_display_for_bplo == 1){
        try {
          //dd($this->where('id','!=',$barangay->id)->get());
          $this->where('id','!=',$barangay->id)->update(['brgy_display_for_bplo'=>0]);
        } catch (\Exception $e) {
          echo $e->getMessage();exit;
        }
      }
    }
    

    public function allBarangays($vars = '')
    {
        $barangays = self::select([
            'barangays.id as brgyID',
            'barangays.brgy_name as brgyName',
            'barangays.brgy_office as brgyOffice', 
            'profile_municipalities.mun_desc as municipal',
            'profile_provinces.prov_desc as province',
            'profile_regions.reg_region as region'
        ])
        ->join('profile_regions', function($join)
        {
            $join->on('profile_regions.id', '=', 'barangays.reg_no');
        })
        ->join('profile_municipalities', function($join)
        {
            $join->on('profile_municipalities.id', '=', 'barangays.mun_no');
        })
        ->join('profile_provinces', function($join)
        {
            $join->on('profile_provinces.id', '=', 'barangays.prov_no');
        })
        ->where('barangays.is_active', 1)
        ->orderBy('barangays.brgy_name', 'asc')->get();
    
        $brgys = array();
        if (!empty($vars)) {
            $brgys[] = array('' => 'select a '.$vars);
        } else {
            $brgys[] = array('' => 'select a barangay...');
        }
        foreach ($barangays as $barangay) {
            //$brgy = (strlen($barangay->brgyOffice) > 0) ? $barangay->brgyName . ' '. $barangay->brgyOffice : $barangay->brgyName;
			$brgy =  $barangay->brgyName;
            $brgys[] = array(
                $barangay->brgyID => $brgy . ', ' . $barangay->municipal . ', ' . $barangay->province . ', ' . $barangay->region
            );
        }

        $barangays = array();
        foreach($brgys as $brgy) {
            foreach($brgy as $key => $val) {
                $barangays[$key] = $val;
            }
        }

        return $barangays;
    }
    public function findDetails($id)
    {
            $barangay = self::select([
              'barangays.id as brgyID',
              'barangays.brgy_name as brgyName',
              'barangays.brgy_office as brgyOffice', 
              'profile_municipalities.mun_desc as municipal',
              'profile_provinces.prov_desc as province',
              'profile_regions.reg_region as region'
          ])
          ->leftJoin('profile_regions', function($join)
          {
              $join->on('profile_regions.id', '=', 'barangays.reg_no');
          })
          ->leftJoin('profile_municipalities', function($join)
          {
              $join->on('profile_municipalities.id', '=', 'barangays.mun_no');
          })
          ->leftJoin('profile_provinces', function($join)
          {
              $join->on('profile_provinces.id', '=', 'barangays.prov_no');
          })
          ->where('barangays.id', $id)
          ->first();
          // has to fix -> $brgy = (strlen($barangay->brgyOffice) > 0) ? $barangay->brgyName . ' '. $barangay->brgyOffice : $barangay->brgyName;
          //code by lanie
          $brgys = '';
          if ($barangay) {
            $brgy =  $barangay->brgyName;
            //$brgy = (strlen($barangay->brgyOffice) > 0) ? $barangay->brgyName . ' '. $barangay->brgyOffice : $barangay->brgyName;
            $brgys = (!empty($brgy) ? $brgy . ', ' : '').(!empty($barangay->municipal) ? $barangay->municipal . ', ' : ''). (!empty($barangay->province) ? $barangay->province . ', ' : ''). (!empty($barangay->region) ? $barangay->region : '');
          }
          //code by lanie end
          // $brgy = (strlen($barangay->brgyOffice) > 0) ? $barangay->brgyName . ' '. $barangay->brgyOffice : $barangay->brgyName;
          // $brgys = (!empty($brgy) ? $brgy . ',' : '').(!empty($barangay->municipal) ? $barangay->municipal . ',' : ''). (!empty($barangay->province) ? $barangay->province . ',' : ''). (!empty($barangay->region) ? $barangay->region : '');
          // // $brgys = $brgy.', '.$barangay->municipal . ', ' . $barangay->province . ', ' . $barangay->region;
          return $brgys;
    }
    
	public function BarangayDetails($id)
    {
         return   $barangay = self::select([
              'barangays.id as brgyID',
              'barangays.brgy_name as brgyName',
              'barangays.brgy_office as brgyOffice', 
              'profile_municipalities.mun_desc as municipal',
              'profile_provinces.prov_desc as province',
              'profile_regions.reg_region as region'
          ])
          ->leftJoin('profile_regions', function($join)
          {
              $join->on('profile_regions.id', '=', 'barangays.reg_no');
          })
          ->leftJoin('profile_municipalities', function($join)
          {
              $join->on('profile_municipalities.id', '=', 'barangays.mun_no');
          })
          ->leftJoin('profile_provinces', function($join)
          {
              $join->on('profile_provinces.id', '=', 'barangays.prov_no');
          })
          ->where('barangays.id', $id)
          ->first();
    }
}
