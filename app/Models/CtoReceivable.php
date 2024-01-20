<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\ModelUpdateCreate;
use Illuminate\Database\Eloquent\Model;
use DB;

class CtoReceivable extends Model
{   
    use HasFactory;
    use ModelUpdateCreate;
    
    protected $guarded = ['id'];

    public $table = 'cto_receivables';
    
    public $timestamps = false;

    public function gl_account()
    {   
        return $this->belongsTo('App\Models\AcctgAccountGeneralLedger', 'gl_account_id', 'id');
    }

    public function fund()
    {   
        return $this->belongsTo('App\Models\AcctgFundCode', 'fund_code_id', 'id');
    }
}
