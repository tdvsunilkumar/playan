<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtoDenominationBill extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cto_denomination_bills';
    
    public $timestamps = false;

    public function collection()
    {
        return $this->belongsTo('App\Models\CtoCollection', 'collection_id', 'id');
    }

    public function denomination()
    {
        return $this->belongsTo('App\Models\CtoDenominationBill', 'denomination_id', 'id');
    }
}
