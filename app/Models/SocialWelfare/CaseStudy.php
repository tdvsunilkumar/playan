<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;
use DB;
class CaseStudy extends Model
{
    use ModelUpdateCreate;
    protected $guarded = ['id'];
    public $timestamps = false;
    public $table = 'welfare_social_welfare_social_case';
    
    public function addData($postdata){
        self::create($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function updateData($id,$columns){
        self::where('id',$id)->update($columns);
        return $id;
    }

    public function family()
    {
        return $this->hasMany(CaseStudyFamily::class, 'wswsc_id', 'id'); 
    }
    public function treatment()
    {
        return $this->hasMany(CaseStudyTreatment::class, 'wswsc_id', 'id'); 
    }
}
