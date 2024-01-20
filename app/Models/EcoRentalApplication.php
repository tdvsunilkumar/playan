<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcoRentalApplication extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'eco_rental_application';
    
    public $timestamps = false;
    
    public function gl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountGeneralLedger', 'gl_account_id', 'id');
    }

    public function sl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountSubsidiaryLedger', 'sl_account_id', 'id');
    }

    public function requestor()
    {
        return $this->belongsTo('App\Models\Citizen', 'requestor_id', 'id');
    }

    public function top_transaction()
    {
        return $this->belongsTo('App\Models\CtoTopTransactionType', 'top_transaction_type_id', 'id');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Models\CtoTopTransaction', 'top_transaction_id', 'id');
    }

    public function officer()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'sent_by', 'id');
    }

    public function discount()
    {
        return $this->belongsTo('App\Models\EcoRentalDiscount', 'discount_id', 'id');
    }
}
