<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgGeneralJournal extends Model
{
    protected $guarded = ['id'];

    public $table = 'acctg_general_journals';
    
    public $timestamps = false;

    public function fund_code()
    {
        return $this->belongsTo('App\Models\AcctgFundCode', 'fund_code_id', 'id');
    }

    public function payee()
    {
        return $this->belongsTo('App\Models\CboPayee', 'payee_id', 'id');
    }

    public function fixed_asset()
    {
        return $this->belongsTo('App\Models\GsoPropertyAccountability', 'fixed_asset_id', 'id');
    }

    public function division()
    {
        return $this->belongsTo('App\Models\AcctgDepartmentDivision', 'division_id', 'id');
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
