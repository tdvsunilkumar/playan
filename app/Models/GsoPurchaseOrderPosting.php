<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPurchaseOrderPosting extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'gso_purchase_orders_posting';
    
    public $timestamps = false;

    public function purchased()
    {
        return $this->belongsTo('App\Models\GsoPurchaseOrder', 'purchase_order_id', 'id');
    }

    public function inspector()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'inspected_by', 'id');
    }

    public function inspector_designation()
    {
        return $this->belongsTo('App\Models\HrDeisgnation', 'inspected_by_designation', 'id');
    }

    public function receiver()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'received_by', 'id');
    }

    public function receiver_designation()
    {
        return $this->belongsTo('App\Models\HrDeisgnation', 'received_by_designation', 'id');
    }
}
