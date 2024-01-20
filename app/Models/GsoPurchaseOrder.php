<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPurchaseOrder extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'gso_purchase_orders';
    
    public $timestamps = false;

    public function rfq()
    {
        return $this->belongsTo('App\Models\BacRfq', 'rfq_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\GsoSupplier', 'supplier_id', 'id');
    }

    public function po_type()
    {
        return $this->belongsTo('App\Models\GsoPurchaseOrderType', 'purchase_order_type_id', 'id');
    }

    public function procurement()
    {
        return $this->belongsTo('App\Models\BacProcurementMode', 'procurement_mode_id', 'id');
    }

    public function delivery_term()
    {
        return $this->belongsTo('App\Models\GsoDeliveryTerm', 'delivery_term_id', 'id');
    }

    public function payment_term()
    {
        return $this->belongsTo('App\Models\GsoPaymentTerm', 'payment_term_id', 'id');
    }

    public function fund_by()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'funding_by', 'id');
    }

    public function fund_designation()
    {
        return $this->belongsTo('App\Models\HrDesignation', 'funding_designation', 'id');
    }

    public function approve_by()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'approval_by', 'id');
    }

    public function approve_designation()
    {
        return $this->belongsTo('App\Models\HrDesignation', 'approval_designation', 'id');
    }

    public function inserted()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function modified()
    {
        return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }
}
