<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\SocialWelfare\Citizen;

class HoRecordCard extends Model
{
    public $table = 'ho_record_cards';
    
    public function patient() 
    { 
        return $this->hasOne(Citizen::class, 'id', 'cit_id','cit_date_of_birth'); 
    }
    public function guardians() 
    { 
        return $this->hasMany(HoGuardian::class, 'rec_card_id', 'id'); 
    }
    public function medicalrecord() 
    { 
        $record = $this->hasMany(Ho_Medical_Record::class, 'rec_card_id', 'id');
        $record->getQuery()->where('med_rec_status','=', 1);
        return $record;
    }
    public function updateData($id,$columns){
        return DB::table('ho_record_cards')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('ho_record_cards')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateActiveInactive($id,$columns){
      return DB::table('ho_record_cards')->where('id',$id)->update($columns);
    }

    public function updateGuardianActiveInactive($id,$columns){
      return DB::table('ho_guardians')->where('id',$id)->update($columns);
    }
    
    public function getCitizenDetails($id){
        return  DB::table('citizens')
                ->select('*')
                ->leftjoin('countries', 'countries.id', '=', 'citizens.country_id')
                ->leftjoin('barangays', 'barangays.id', '=', 'citizens.brgy_id')
                ->select('citizens.*','countries.nationality','barangays.brgy_name')
                ->where('citizens.id',$id)->first();
    }
    public function getGuardianDetails($id){
      return  DB::table('citizens')
              ->select('*')
              ->where('citizens.id',$id)->first();
  }
    
    public function getCitizen($search="")
    {
          $page=1;
          if(isset($_REQUEST['page'])){
          $page = (int)$_REQUEST['page'];
          }
          $length = 20;
          $offset = ($page - 1) * $length;
          $sql = self::join('citizens', 'ho_record_cards.cit_id','citizens.id')->where([['cit_is_active',1],['rec_card_status',1]]);
          if(!empty($search)){
                $sql->where(function ($sql) use($search) {
                      if(is_numeric($search)){
                            $sql->Where('citizens.id',$search);
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
    public function deleteRecordCard($id){
      return DB::table('ho_record_cards')->where('id', $id)->delete();
  }
  public function getServices(){
    return DB::table('ho_services')->select('id','ho_service_name')->where('ho_is_active',1)->get();
  }
  
  public function addGuardian($data){
    if (isset($data['guardian'])) {
      foreach ($data['guardian'] as $key => $value) {
          if (isset($value['cit_id'])){
              $add = HoGuardian::updateOrCreate(
                  [
                      'rec_card_id' => $data['id'],
                      'id' => $key,
                  ],
                  [
                      'cit_id' => $value['cit_id'],
                      'updated_at' => date('Y-m-d H:i:s'),
                      'guardian_status' => 1,
                  ]
              );
          }
      }
    }
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
                1 =>"rec_card_num",
                2 =>"cit_fullname",
                3 =>"brgy_name", 
                4 =>"cit_age",
                5 =>"cit_gender",
                6 =>"cit_philhealth_no", 
              );

              $sql = self::
                    leftjoin('citizens AS ctz', 'ctz.id', '=', 'ho_record_cards.cit_id')
                    ->leftjoin('barangays', 'barangays.id', '=', 'ctz.brgy_id')
                    // ->leftjoin('ho_medical_records AS hmr', 'hmr.rec_card_id', '=', 'ho_record_cards.id')
                    // ->leftjoin('ho_medical_record_diagnoses AS hmd', 'hmd.med_rec_id', '=', 'hmr.id')
                    // ->leftjoin('ho_diagnoses AS hd', 'hd.id', '=', 'hmd.disease_id')
                    // ->select('ho_record_cards.*','ho_record_cards.id as record_id','ctz.cit_first_name','ctz.cit_age','ctz.cit_last_name','ctz.brgy_id','ctz.cit_gender','hd.diag_name','hmd.*','hmr.*');
                    ->select('ho_record_cards.*','ho_record_cards.id as record_id','ctz.cit_fullname','ctz.cit_age','ctz.cit_last_name','ctz.cit_last_name','barangays.brgy_name','ctz.cit_gender','ctz.cit_philhealth_no','ctz.id as cit_id');
              if(!empty($q) && isset($q)){
                $sql = $sql->where(function ($sql) use($q) {
                      $sql->where(DB::raw('LOWER(ho_record_cards.rec_card_num)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(ctz.cit_fullname)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(barangays.brgy_name)'),'like',"%".strtolower($q)."%");
                });
              }

              /*  #######  Set Order By  ###### */
              if(isset($params['order'][0]['column']))
                $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
              else
                $sql->orderBy('ho_record_cards.created_at','DESC');

              /*  #######  Get count without limit  ###### */
              $data_cnt=$sql->count();
              /*  #######  Set Offset & Limit  ###### */
              $sql->offset((int)$params['start'])->limit((int)$params['length']);
              $data=$sql->get();
              return array("data_cnt"=>$data_cnt,"data"=>$data);
            }
      
}
