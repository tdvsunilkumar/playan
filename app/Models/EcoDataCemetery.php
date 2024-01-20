<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barangay;
use DB;

class EcoDataCemetery extends Model
{
      public $table = 'eco_data_cemeteries';
  public function updateActiveInactive($id,$columns){
    return DB::table('eco_data_cemeteries')->where('id',$id)->update($columns);
  }  
    public function updateData($id,$columns){
        return DB::table('eco_data_cemeteries')->where('id',$id)->update($columns);
    }
    public function updateDefaultall($data){
      return DB::table('eco_data_cemeteries')->update($data);
    }
    public function addData($postdata){
        return DB::table('eco_data_cemeteries')->insert($postdata);
    }

    // public function getBarangay(){
    //      return DB::table('rpt_locality AS loc')
    //      ->join('barangays AS b', 'b.mun_no', '=', 'loc.mun_no')
    //      ->select('b.id','b.brgy_code','b.brgy_name')->where('b.is_active',1)->where('loc.department',5)->get();

    public function getMuncipality(){
      return DB::table('rpt_locality')->select('mun_no')->where('department','5')->first();
    }
    public function getBarangay($munid){
         return DB::table('barangays')
         ->select('id','brgy_code','brgy_name')->where('is_active',1)->where('mun_no',$munid)->get();
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
          1 =>"brgy_name",  
          2 =>"cem_name",  
          3 =>"remark",
          4 =>"status"
         );
        
         $sql = DB::table('eco_data_cemeteries AS eco')
                  ->join('barangays AS b', 'b.id', '=', 'eco.brgy_id')
                  ->select('eco.id','b.brgy_name','cem_name','remark','status');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cem_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(remark)'),'like',"%".strtolower($q)."%");
                   
                    
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('eco.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

  public function allCemeteryLocations($vars = '')
  { 
      $barangays = Barangay::select([
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
      ->whereIn('barangays.id', 
          self::select('brgy_id')->where('status', 1)->get()
      )
      ->where('barangays.is_active', 1)
      ->orderBy('barangays.brgy_name', 'asc')->get();

      $brgys = array();
      if (!empty($vars)) {
          $brgys[] = array('' => 'select a '.$vars);
      } else {
          $brgys[] = array('' => 'select a barangay...');
      }
      foreach ($barangays as $barangay) {
          $brgy = (strlen($barangay->brgyOffice) > 0) ? $barangay->brgyName . ' '. $barangay->brgyOffice : $barangay->brgyName;
          $brgys[] = array(
              $barangay->brgyID => ucwords(strtolower($brgy)) 
            //   . ', ' . $barangay->municipal . ', ' . $barangay->province . ', ' . $barangay->region
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

  public function allCemeteryNames()
  {
      $cemeteries = self::where('status', 1)->orderBy('id', 'asc')->get();
  
      $cems = array();
      $cems[] = array('' => 'select a cemetery');
      foreach ($cemeteries as $cemetery) {
          $cems[] = array(
              $cemetery->id => $cemetery->cem_name
          );
      }

      $cemeteries = array();
      foreach($cems as $cem) {
          foreach($cem as $key => $val) {
              $cemeteries[$key] = $val;
          }
      }

      return $cemeteries;
  }
}
