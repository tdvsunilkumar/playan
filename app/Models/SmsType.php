<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsType extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'sms_types';
    
    public $timestamps = false;

    public function allSmsTypes($vars = '')
    {
        $typez = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $typz = array();
        if (!empty($vars)) {
            $typz[] = array('' => 'select a '.$vars);
        } else {
            $typz[] = array('' => 'select sms type');
        }
        foreach ($typez as $type) {
            $typz[] = array(
                $type->id => $type->code
            );
        }

        $typez = array();
        foreach($typz as $typ) {
            foreach($typ as $key => $val) {
                $typez[$key] = $val;
            }
        }

        return $typez;
    }
}
