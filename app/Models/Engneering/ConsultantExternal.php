<?php

namespace App\Models\Engneering;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Barangay;

class ConsultantExternal extends Model
{
    public $table = 'consultants';

    public function updateActiveInactive($id,$columns){
     return DB::table('consultants')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('consultants')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('consultants')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('consultants')->where('id',$id)->first();
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
		  1 =>"ept_id",
		  2 =>"esp_id",
		  3 =>"firstname",
		  4 =>"middlename",
		  5 =>"lastname",
		  6 =>"fullname",
		  7 =>"suffix",
		  8 =>"title",
		  9 =>"gender",
		  10 =>"birthdate",
		  11 =>"house_lot_no",
		  12 =>"street_name",
		  13 =>"subdivision",
		  14 =>"brgy_code",
		  15 =>"country",
		  16 =>"email_address",
		  17 =>"telephone_no",
		  18 =>"mobile_no",
		  19 =>"ptr_no",
		  20 =>"ptr_date_issued",
		  21 =>"prc_no",
		  22 =>"prc_validity",
		  23 =>"prc_date_issued" ,
		  24 =>"tin_no",
		  25 =>"iapoa_no",
		  26 =>"is_active",
		  27 =>"iapoa_or_no"
        );

        $sql = DB::table('consultants')
              ->select('*');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(firstname)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(middlename)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(lastname)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(fullname)'),'like',"%".strtolower($q)."%")				 
                ->orWhere(DB::raw('LOWER(suffix)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(title)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(gender)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(birthdate)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(house_lot_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(street_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(subdivision)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(brgy_code)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(country)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(email_address)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(telephone_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(mobile_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(ptr_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(ptr_date_issued)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(prc_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(prc_validity)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(prc_date_issued)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(tin_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(iapoa_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(is_active)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(iapoa_or_no)'),'like',"%".strtolower($q)."%")
                ;
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
	  
	  public function alllists(){
        return DB::table('barangays')->select('id','brgy_code')->get();
    }

    // attributes
    public function getCurrentAddressAttribute()
    {
        $brgy = ($this->brgy_code)?', '.Barangay::findDetails($this->brgy_code):'';
        return $this->house_lot_no.', '.$this->street_name.', '.$this->subdivision.$brgy;
    }
}
