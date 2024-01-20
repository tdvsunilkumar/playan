<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgBank extends Model
{
    protected $guarded = ['id'];

    public $table = 'acctg_banks';
    
    public $timestamps = false;

    public function allBanks($vars = '')
    {
        $banks = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $bankx = array();
        if (!empty($vars)) {
            $bankx[] = array('' => 'select a '.$vars);
        } else {
            $bankx[] = array('' => 'select a bank');
        }
        foreach ($banks as $bank) {
            $bankx[] = array(
                $bank->id => $bank->bank_account_no . ' - ' . $bank->bank_account_name .' ('.$bank->bank_name.')'
            );
        }

        $banks = array();
        foreach($bankx as $bnk) {
            foreach($bnk as $key => $val) {
                $banks[$key] = $val;
            }
        }

        return $banks;
    }
}
