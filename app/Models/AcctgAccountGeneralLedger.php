<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgAccountGeneralLedger extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'acctg_account_general_ledgers';
    
    public $timestamps = false;

    public function account_group()
    {
        return $this->belongsTo('App\Models\AcctgAccountGroup', 'acctg_account_group_id', 'id');
    }
    
    public function major_account_group()
    {
        return $this->belongsTo('App\Models\AcctgAccountGroupMajor', 'acctg_account_group_major_id', 'id');
    }

    public function submajor_account_group()
    {
        return $this->belongsTo('App\Models\AcctgAccountGroupSubmajor', 'acctg_account_group_submajor_id', 'id');
    }

    public function fund_code()
    {
        return $this->belongsTo('App\Models\AcctgFundCode', 'acctg_fund_code_id', 'id');
    }

    public function subsidiaries()
    {
        return $this->hasMany('App\Models\AcctgAccountSubsidiaryLedger', 'gl_account_id', 'id');
    }

    public function allGLAccounts($vars = '')
    {
        $gl_accounts = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $gls = array();
        if (!empty($vars)) {
            $gls[] = array('' => 'select a '.$vars);
        } else {
            $gls[] = array('' => 'select a gl account');
        }
        foreach ($gl_accounts as $gl_account) {
            $gls[] = array(
                $gl_account->id => $gl_account->code . ' - ' . $gl_account->description
            );
        }

        $gl_accounts = array();
        foreach($gls as $gl) {
            foreach($gl as $key => $val) {
                $gl_accounts[$key] = $val;
            }
        }

        return $gl_accounts;
    }
}
