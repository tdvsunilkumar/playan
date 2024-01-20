<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgExpandedVatableTax extends Model
{
    protected $guarded = ['id'];

    public $table = 'acctg_expanded_vatable_taxes';
    
    public $timestamps = false;
    
    public function gl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountGeneralLedger', 'gl_account_id', 'id');
    }

    public function allEVAT($vars = '')
    {   
        $evatz = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $evats = array();
        if (!empty($vars)) {
            $evats[] = array('' => 'select a '.$vars);
        } else {
            $evats[] = array('' => 'select an evat');
        }
        foreach ($evatz as $evat) {
            $evats[] = array(
                $evat->id => $evat->code . ' - ' . $evat->name
            );
        }

        $evatz = array();
        foreach($evats as $ev) {
            foreach($ev as $key => $val) {
                $evatz[$key] = $val;
            }
        }

        return $evatz;
    }
}
