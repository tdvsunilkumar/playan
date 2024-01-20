<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Ho_Treatment extends Model
{
    public $table = 'ho_treatments';
    public $timestamps = false;
    protected $fillable = ['med_rec_id','treat_medication','cit_id','cit_age','cit_age_days','treat_management','updated_at','updated_by','treat_is_active'];
    public function deleteTreatment($id){
        return DB::table('ho_treatments')->where('med_rec_id', $id)->delete();
    }
    public function updateMedicalData($id,$columns){
        return DB::table('ho_treatments')->where('id',$id)->update($columns);
      }
      public function addMedicalData($postdata){
          DB::table('ho_treatments')->insert($postdata);
          return DB::getPdo()->lastInsertId();
      }
}
