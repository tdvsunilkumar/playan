<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use App\Models\SocialWelfare\Assistance;

class AssistanceDependent extends Model
{
    public $table = 'welfare_swa_dependent';
    protected $fillable = ['wswa_id','wsd_cit','cit_id','wsd_relation', 'wsd_is_active'];
    public $timestamps = false;
    public function assistDetail() 
      { 
          return $this->belongsTo(Assistance::class, 'wswa_id', 'id'); 
      }
      public function dependent() 
      { 
          return $this->belongsTo(Citizen::class, 'wsd_cit', 'id'); 
      }
}
