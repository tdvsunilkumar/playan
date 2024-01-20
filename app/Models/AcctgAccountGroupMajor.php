<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgAccountGroupMajor extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'acctg_account_groups_majors';
    
    public $timestamps = false;

    public function account_group()
    {
        return $this->belongsTo('App\Models\AcctgAccountGroup', 'acctg_account_group_id', 'id');
    }

    public function reload_major_account($account)
    {
        $majors = self::where(['acctg_account_group_id' => $account, 'is_active' => 1])
        ->orderBy('id', 'asc')
        ->get();

        return $majors;
    }
}
