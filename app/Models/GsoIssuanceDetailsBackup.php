<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoIssuanceDetails extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_issuance_details';
    
    public $timestamps = false;

    public function gso_issuance()
    {
        return $this->belongsTo('App\Models\GsoIssuance', 'issue_id', 'id');
    }

}
