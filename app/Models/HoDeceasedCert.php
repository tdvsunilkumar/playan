<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HoDeceasedCert extends Model
{
  public $table = 'ho_deceased_cert';

    public function requestor() 
    { 
      return $this->hasOne(Citizen::class, 'id', 'requester_id'); 
    }
    public function service() 
    { 
      return $this->hasOne(HealthSafetySetupDataService::class, 'id', 'form_type'); 
    }

    public function employee() 
    { 
      return $this->hasOne(HrEmployee::class, 'id', 'health_officer_id'); 
    }
    
    public function deceased(){
      return $this->hasOne(Citizen::class, 'id', 'deceased_id'); 
    }

    public function brgy_add(){//brgy_add->region->
      return $this->hasOne(Barangay::class, 'id', 'brgy_id'); 
    }

    public function death_add(){//death_add->region->
      return $this->hasOne(Barangay::class, 'id', 'transfer_add_id'); 
    }

    public function transfer_add(){//transfer_add->region->
      return $this->hasOne(Barangay::class, 'id', 'transfer_add_id'); 
    }
    
    public function getBrgyAddressAttribute(){//brgy_address
      $val = Barangay::find($this->brgy_id);
	    $address =(!empty($val->brgy_name) ? $val->brgy_name. ',' : '') . (!empty($val->municipality->mun_desc) ? $val->municipality->mun_desc. ',' : '') . (!empty($val->province->prov_desc) ? $val->province->prov_desc . '' : ''). (!empty($val->region->reg_region) ? $val->region->reg_region . '' : '');
      return  $address;
    }
    public function getBrgyTransferAttribute(){//brgy_transfer
      $val = Barangay::find($this->transfer_add_id);
      $address =(!empty($val->brgy_name) ? $val->brgy_name. ',' : '') . (!empty($val->municipality->mun_desc) ? $val->municipality->mun_desc. ',' : '') . (!empty($val->province->prov_desc) ? $val->province->prov_desc . '' : ''). (!empty($val->region->reg_region) ? $val->region->reg_region . '' : '');
      return $address;
	  
    }
    public function permit() 
    { 
        return $this->hasOne(RequestPermit::class, 'id', 'req_permit_id'); 
    }
    public function updateData($id,$columns){
        return DB::table('ho_deceased_cert')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('ho_deceased_cert')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
	public function getBarangay(){
		return DB::table('barangays As b')
		->Leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
		->Leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
	    ->Leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
		->select('b.id','b.brgy_name','pm.mun_desc','pr.reg_region','pp.prov_desc')->get();	
	}
    public function updateActiveInactive($id,$columns){
      return DB::table('ho_deceased_cert')->where('id',$id)->update($columns);
    }
    public function getCitizenId(){
      return DB::table('citizens')->select('id','cit_fullname')->where('cit_is_active',1)->get();
    }
    public function deleteCertificateReq($id){
      return DB::table('ho_add_deceaseds')->where('id', $id)->delete();
    }
    public function getAddDeceasecadaverData($id){
      return DB::table('ho_add_deceaseds')->where('deceased_cert_id',$id)->get();
    }
    public function updateDeceasecadaverData($id,$columns){
      return DB::table('ho_add_deceaseds')->where('id',$id)->update($columns);
    }
    public function addDeceasecadaverData($postdata){
      DB::table('ho_add_deceaseds')->insert($postdata);
      return DB::getPdo()->lastInsertId();
    }

    public function getFormTypeId(){
      return DB::table('ho_services')
      ->select('id','ho_service_name')
      ->where('ho_service_department',5)
      ->where('ho_is_active',1)
      ->get();
    }

    public function getTopTransId($id){
      $top_type_id = DB::table('ho_services')
      ->select('top_transaction_type_id')
      ->where('ho_service_department',5)
      ->where('id',$id)
      ->where('ho_is_active',1)
      ->first();
      return ($top_type_id)? $top_type_id->top_transaction_type_id: '';
    }
    public function selectHRemployees($id){
        return DB::table('hr_employees')->select('user_id')->where('is_active',1)->where('id',$id)->first()->user_id;
    }
    public function gethelofficerId(){
        return DB::table('hr_employees')
        ->select('id','fullname')
        ->get();
      }
      public function getRelationId(){
        return DB::table('ho_relation_to_deceaseds')
        ->select('id','relation')
        ->get();
      }
      public function getPosition($id){
        $data= HrEmployee::where('id',$id)->first();
        return $data->designation->description;
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
                0 =>"", 
                1 =>"chp_id",
                2 =>"chc_id",
                3 =>"chr_range",
                4 =>"hr_is_active"
              );
              $sql = DB::table('ho_deceased_cert AS hdc')
                    ->leftjoin('citizens', 'citizens.id', '=', 'hdc.requester_id')
                    ->leftjoin('barangays', 'barangays.id', '=', 'hdc.brgy_id')
                    ->leftjoin('ho_services', 'ho_services.id', '=', 'hdc.form_type')
                    ->select('hdc.*','barangays.brgy_name','citizens.cit_fullname','ho_services.ho_service_name','ho_services.top_transaction_type_id');
              if(!empty($q) && isset($q)){
                  $sql->where(function ($sql) use($q) {
                      $sql->where(DB::raw('LOWER(hdc.requester_id)'),'like',"%".strtolower($q)."%")
                          ->orWhere(DB::raw('LOWER(hdc.health_officer_id)'),'like',"%".strtolower($q)."%");
                });
              }

              /*  #######  Set Order By  ###### */
              if(isset($params['order'][0]['column']))
                $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
              else
                $sql->orderBy('hdc.id','DESC');

              /*  #######  Get count without limit  ###### */
              $data_cnt=$sql->count();
              /*  #######  Set Offset & Limit  ###### */
              $sql->offset((int)$params['start'])->limit((int)$params['length']);
              $data=$sql->get();
              return array("data_cnt"=>$data_cnt,"data"=>$data);
            }
			
    public function updateusersavedataData($id,$columns){
        return DB::table('user_last_save_data')->where('id',$id)->update($columns);
    }
    public function addusersaveData($data){
        DB::table('user_last_save_data')->insert($data);
        return DB::getPdo()->lastInsertId();
    }
    public function CheckFormdataExist($formid,$userid){
        return DB::table('user_last_save_data')->where('form_id',$formid)->where('user_id',$userid)->get();
    }
}
