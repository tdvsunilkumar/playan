<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgVoucherDocument extends Model
{
    protected $guarded = ['id'];

    public $table = 'acctg_vouchers_documents';
    
    public $timestamps = false;

    public function voucher()
    {
        return $this->belongsTo('App\Models\AcctgVoucher', 'voucher_id', 'id');
    }

    public function prepared()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'prepared_by', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'approval_by', 'user_id');
    }

    public function approved()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'approved_by', 'user_id');
    }

    public function disapproved()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'disapproved_by', 'user_id');
    }
}
