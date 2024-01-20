<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcoCemeteryApplication extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'eco_cemetery_application';
    
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
        // return $this->belongsTo('App\Models\SocialWelfare\Citizen', 'requestor_id', 'id');
        return $this->belongsTo('App\Models\SocialWelfare\Citizen', 'requestor_id', 'id');
    }

    public function expired()
    {
        // return $this->belongsTo('App\Models\SocialWelfare\Citizen', 'expired_id', 'id');
        return $this->belongsTo('App\Models\Citizen', 'expired_id', 'id');
    }

    public function top_transaction()
    {
        return $this->belongsTo('App\Models\CtoTopTransactionType', 'top_transaction_type_id', 'id');
    }

    public function cemetery_location()
    {
        return $this->belongsTo('App\Models\Barangay', 'location_id', 'id');
    }

    public function officer()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'sent_by', 'id');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Models\CtoTopTransaction', 'top_transaction_id', 'id');
    }

    public function tfoc()
    {
        return $this->belongsTo('App\Models\CtoTfoc', 'tfoc_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo('App\Models\EcoService', 'service_id', 'id');
    }
}
