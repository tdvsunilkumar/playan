<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CboPayee extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cbo_payee';
    
    public $timestamps = false;

    public function allPayees($vars = '')
    {
        $payees = self::where('paye_status', 1)->orderBy('id', 'asc')->get();
    
        $pays = array();
        if (!empty($vars)) {
            $pays[] = array('' => 'select a '.$vars);
        } else {
            $pays[] = array('' => 'select a payee');
        }
        foreach ($payees as $payee) {
            $pays[] = array(
                $payee->id => $payee->paye_name
            );
        }

        $payees = array();
        foreach($pays as $pay) {
            foreach($pay as $key => $val) {
                $payees[$key] = $val;
            }
        }

        return $payees;
    }
}
