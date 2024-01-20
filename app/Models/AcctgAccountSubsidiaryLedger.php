<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AcctgAccountGeneralLedger;

class AcctgAccountSubsidiaryLedger extends Model
{
    protected $guarded = ['id'];

    public $table = 'acctg_account_subsidiary_ledgers';
    
    public $timestamps = false;

    public function gl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountGeneralLedger', 'gl_account_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo('App\Models\AcctgBank', 'bank_id', 'id');
    }

    public function allSLAccounts($vars = '')
    {   
        $sl_accounts = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $sls = array();
        if (!empty($vars)) {
            $sls[] = array('' => 'select a '.$vars);
        } else {
            $sls[] = array('' => 'select a gl account');
        }
        foreach ($sl_accounts as $sl_account) {
            $sls[] = array(
                $sl_account->id => $sl_account->code . ' - ' . $sl_account->description
            );
        }

        $sl_accounts = array();
        foreach($sls as $sl) {
            foreach($sl as $key => $val) {
                $sl_accounts[$key] = $val;
            }
        }

        return $sl_accounts;
    }

    public function allSLAccountsGroup($vars = '')
    {   
        $gl_accounts = AcctgAccountGeneralLedger::where(['is_with_sl' => 1, 'is_active' => 1])->orderBy('description', 'asc')->get();

        $group = array(); 
        if (!empty($gl_accounts)) {
            $index = 0;
            foreach ($gl_accounts as $gl_account) {
                $group[$index] = (object) array(
                    'text' => $gl_account->description
                );   

                $sl_accounts = self::where(['gl_account_id' => $gl_account->id, 'is_active' => 1])->orderBy('code', 'asc')->get();
                $children = array();
                if (!empty($sl_accounts)) {
                    foreach ($sl_accounts as $sl_account) {
                        $children[] = (object) array(
                            'id' => $sl_account->id,
                            'gl_code' => $gl_account->code,
                            'hidden' => $sl_account->is_hidden,
                            'code' => $sl_account->code,
                            'text' => $sl_account->description
                        );
                    }
                }
                $group[$index]->children = $children; 
                $index++;
            }
        }

        return $group;
    }

    public function allGLSLAccounts()
    {
        $sl_accounts = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $sls = array();
        if (!empty($vars)) {
            $sls[] = array('' => 'select a '.$vars);
        } else {
            $sls[] = array('' => 'select a gl account');
        }
        foreach ($sl_accounts as $sl_account) {
            $sls[] = array(
                $sl_account->id => '['.$sl_account->gl_account->code . ' - ' . $sl_account->gl_account->description.'] => ['. $sl_account->prefix . ' - '. $sl_account->description .']'
            );
        }

        $sl_accounts = array();
        foreach($sls as $sl) {
            foreach($sl as $key => $val) {
                $sl_accounts[$key] = $val;
            }
        }

        return $sl_accounts;
    }

    public function allBankSLAccounts($vars = '')
    {
        $gl_accounts = AcctgAccountGeneralLedger::where(['is_cash_in_bank' => 1, 'is_with_sl' => 1, 'is_active' => 1])->orderBy('description', 'asc')->get();

        $group = array(); 
        if (!empty($gl_accounts)) {
            $index = 0;
            foreach ($gl_accounts as $gl_account) {
                $group[$index] = (object) array(
                    'text' => $gl_account->description
                );   

                $sl_accounts = self::where(['gl_account_id' => $gl_account->id, 'is_active' => 1])->orderBy('code', 'asc')->get();
                $children = array();
                if (!empty($sl_accounts)) {
                    foreach ($sl_accounts as $sl_account) {
                        $children[] = (object) array(
                            'id' => $sl_account->id,
                            'gl_code' => $gl_account->code,
                            'hidden' => $sl_account->is_hidden,
                            'code' => $sl_account->code,
                            'text' => $sl_account->description
                        );
                    }
                }
                $group[$index]->children = $children; 
                $index++;
            }
        }

        return $group;
    }

    public function allPaymentSLAccounts($vars = '')
    {
        $gl_accounts = AcctgAccountGeneralLedger::where(['is_payment' => 1, 'is_with_sl' =>  1, 'is_active' => 1])->orderBy('description', 'asc')->get();

        $group = array(); 
        if (!empty($gl_accounts)) {
            $index = 0;
            foreach ($gl_accounts as $gl_account) {
                $group[$index] = (object) array(
                    'text' => $gl_account->description
                );   

                $sl_accounts = self::where(['gl_account_id' => $gl_account->id, 'is_active' => 1])->orderBy('code', 'asc')->get();
                $children = array();
                if (!empty($sl_accounts)) {
                    foreach ($sl_accounts as $sl_account) {
                        $children[] = (object) array(
                            'id' => $sl_account->id,
                            'gl_code' => $gl_account->code,
                            'hidden' => $sl_account->is_hidden,
                            'code' => $sl_account->code,
                            'text' => $sl_account->description
                        );
                    }
                }
                $group[$index]->children = $children; 
                $index++;
            }
        }

        return $group;
    }

    public function reload_parent($gl_account, $sl)
    {
        $arr = array(); $arr[] = $sl;
        if ($sl > 0) {
            $parents = self::where([
                'gl_account_id' => $gl_account, 
                'is_parent' => 1, 
                'is_active' => 1
            ])
            ->where('id', '!=', $sl)
            ->where('sl_parent_id', '!=', $sl)
            ->orderBy('id', 'asc')->get();
        } else {
            $parents = self::where([
                'gl_account_id' => $gl_account, 
                'is_parent' => 1, 
                'is_active' => 1
            ])
            ->where('id', '!=', $sl)
            ->orderBy('id', 'asc')->get();
        }

        return $parents;
    }
}
