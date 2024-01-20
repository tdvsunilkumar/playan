<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgPaymentType extends Model
{
    protected $guarded = ['id'];

    public $table = 'acctg_payment_types';
    
    public $timestamps = false;

    public function allPaymentType($vars = '')
    {
        $payment_types = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $types = array();
        if (!empty($vars)) {
            $types[] = array('' => 'select a '.$vars);
        } else {
            $types[] = array('' => 'select a fund code');
        }
        foreach ($payment_types as $payment_type) {
            $types[] = array(
                $payment_type->id => $payment_type->code . ' - ' . $payment_type->name
            );
        }

        $payment_types = array();
        foreach($types as $type) {
            foreach($type as $key => $val) {
                $payment_types[$key] = $val;
            }
        }

        return $payment_types;
    }
}
