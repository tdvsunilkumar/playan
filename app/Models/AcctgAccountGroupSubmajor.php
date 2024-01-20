<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgAccountGroupSubmajor extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'acctg_account_groups_submajors';
    
    public $timestamps = false;

    public function account_group()
    {
        return $this->belongsTo('App\Models\AcctgAccountGroup', 'acctg_account_group_id', 'id');
    }
    
    public function major_account_group()
    {
        return $this->belongsTo('App\Models\AcctgAccountGroupMajor', 'acctg_account_group_major_id', 'id');
    }

    public function reload_submajor_account($account, $major)
    {
        $majors = self::where(['acctg_account_group_id' => $account, 'acctg_account_group_major_id' => $major, 'is_active' => 1])
        ->orderBy('id', 'asc')
        ->get();

        return $majors;
    }
}
