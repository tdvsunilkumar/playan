<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgFundCode extends Model
{
    protected $guarded = ['id'];

    public $table = 'acctg_fund_codes';
    
    public $timestamps = false;

    public function allFundCodes($vars = '')
    {
        $fund_codes = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $funds = array();
        if (!empty($vars)) {
            $funds[] = array('' => 'select a '.$vars);
        } else {
            $funds[] = array('' => 'select a fund code');
        }
        foreach ($fund_codes as $fund_code) {
            $funds[] = array(
                $fund_code->id => $fund_code->code . ' - ' . $fund_code->description
            );
        }

        $fund_codes = array();
        foreach($funds as $fund) {
            foreach($fund as $key => $val) {
                $fund_codes[$key] = $val;
            }
        }

        return $fund_codes;
    }
}
