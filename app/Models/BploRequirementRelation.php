<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BploRequirementRelation extends Model
{
    protected $guarded = ['id'];

    public $table = 'bplo_requirement_relations';
    
    public $timestamps = false;

    public function Requirements(){
        return $this->belongsTo(Requirements::class,'requirement_id');
    }
    public function BploRequirements(){
        return $this->belongsTo(BploRequirements::class,'bplo_requirement_id');
    }
}
