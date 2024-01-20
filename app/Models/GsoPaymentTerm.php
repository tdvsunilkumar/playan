<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPaymentTerm extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'gso_payment_terms';
    
    public $timestamps = false;

    public function allPaymentTerms($vars = '')
    {
        $termz = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $terms = array();
        if (!empty($vars)) {
            $terms[] = array('' => 'select a '.$vars);
        } else {
            $terms[] = array('' => 'select a payment term');
        }
        foreach ($termz as $term) {
            $terms[] = array(
                $term->id => $term->description
            );
        }

        $termz = array();
        foreach($terms as $term) {
            foreach($term as $key => $val) {
                $termz[$key] = $val;
            }
        }

        return $termz;
    }
}
