<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

use App\Traits\ModelUpdateCreate;

class Ho_Medical_Record_Diagnosis extends Model
{
    use ModelUpdateCreate;

    public $table = 'ho_medical_record_diagnoses';
    public $timestamps = false;
    protected $fillable = ['med_rec_id','disease_id','cit_id','cit_age','cit_age_days','cit_gender','is_specified','updated_by','updated_at','is_active'];
    public function deleteDiagnosis($id){
        return DB::table('ho_medical_record_diagnoses')->where('med_rec_id', $id)->delete();
    }
    public function updateMedicalData($id,$columns){
        return DB::table('ho_medical_record_diagnoses')->where('id',$id)->update($columns);
      }
      public function addMedicalData($postdata){
          DB::table('ho_medical_record_diagnoses')->insert($postdata);
          return DB::getPdo()->lastInsertId();
      }

      public function diagnose() 
    { 
        return $this->hasOne(HoDiagnosis::class, 'id', 'disease_id'); 
    }
}
