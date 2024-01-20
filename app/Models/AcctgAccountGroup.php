<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgAccountGroup extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'acctg_account_groups';
    
    public $timestamps = false;

    public function allAccountGroups($vars = '')
    {
        $accountGroups = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $acct_grps = array();
        if (!empty($vars)) {
            $acct_grps[] = array('' => 'select a '.$vars);
        } else {
            $acct_grps[] = array('' => 'select an account group');
        }
        foreach ($accountGroups as $accountGroup) {
            $acct_grps[] = array(
                $accountGroup->id => $accountGroup->code . ' - ' . $accountGroup->description
            );
        }

        $accountGroups = array();
        foreach($acct_grps as $acct_grp) {
            foreach($acct_grp as $key => $val) {
                $accountGroups[$key] = $val;
            }
        }

        return $accountGroups;
    }
}
