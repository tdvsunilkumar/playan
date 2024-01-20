<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoItemHistory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_item_history';
    
    public $timestamps = false;

    public function gso_item()
    {
        return $this->belongsTo('App\Models\GsoItem', 'item_id', 'id');
    }

    public function issuer()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'trans_by', 'id');
    }

    public function receiver()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'rcv_by', 'id');
    }
}
