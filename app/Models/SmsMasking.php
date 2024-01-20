<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsMasking extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'sms_maskings';
    
    public $timestamps = false;

    public function allMaskings($vars = '')
    {
        $maskings = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $masks = array();
        if (!empty($vars)) {
            $masks[] = array('' => 'select a '.$vars);
        } else {
            $masks[] = array('' => 'select a masking code');
        }
        foreach ($maskings as $masking) {
            $masks[] = array(
                $masking->id => $masking->code
            );
        }

        $maskings = array();
        foreach($masks as $mask) {
            foreach($mask as $key => $val) {
                $maskings[$key] = $val;
            }
        }

        return $maskings;
    }
}
