<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgExpandedWithholdingTax extends Model
{
    protected $guarded = ['id'];

    public $table = 'acctg_expanded_withholding_taxes';
    
    public $timestamps = false;

    public function gl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountGeneralLedger', 'gl_account_id', 'id');
    }

    public function allEWT($vars = '', $setup = 0)
    {   
        if ($setup > 0) {
            $ewts = self::where(['is_setup' => 1, 'is_active' => 1])->orderBy('id', 'asc')->get();
        } else {
            $ewts = self::where('is_active', 1)->orderBy('id', 'asc')->get();
        }
    
        $ews = array();
        if (!empty($vars)) {
            $ews[] = array('' => 'select a '.$vars);
        } else {
            $ews[] = array('' => 'select a department');
        }
        foreach ($ewts as $ewt) {
            $ews[] = array(
                $ewt->id => $ewt->code . ' - ' . $ewt->name
            );
        }

        $ewts = array();
        foreach($ews as $ew) {
            foreach($ew as $key => $val) {
                $ewts[$key] = $val;
            }
        }

        return $ewts;
    }
}
