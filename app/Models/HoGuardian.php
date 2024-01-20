<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SocialWelfare\Citizen;
use DB;

use App\Traits\ModelUpdateCreate;

class HoGuardian extends Model
{
  use ModelUpdateCreate;

  public $table = 'ho_guardians';
  public $timestamps = false;
  protected $fillable = ['rec_card_id','cit_id','updated_at','guardian_status',];
  public function citizen() 
    { 
        return $this->hasOne(Citizen::class, 'id', 'cit_id'); 
    }
  public function updateServiceData($id,$columns){
        return DB::table('ho_guardians')->where('id',$id)->update($columns);
      }
      public function addServiceData($postdata){
          DB::table('ho_guardians')->insert($postdata);
          return DB::getPdo()->lastInsertId();
      }
      public function getAddMoreDataServices($id){
        return DB::table('ho_guardians')
        ->leftjoin('ho_record_cards', 'ho_record_cards.id', '=', 'ho_guardians.rec_card_id')
        ->join('citizens', 'citizens.id', '=', 'ho_guardians.cit_id')
        ->where('ho_guardians.rec_card_id',$id)
        ->get();
    }
    public function getAddMoreDataCitizens($id){
        return DB::table('ho_guardians')
        ->select('*')
        ->where('ho_guardians.rec_card_id',$id)
        ->first();
    }
    public function deleteRecordCard($id){
        return DB::table('ho_guardians')->where('id', $id)->delete();
    }
}
