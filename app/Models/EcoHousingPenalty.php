<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcoHousingPenalty extends Model
{
    protected $guarded = ['id'];

    public $table = 'eco_housing_penalties';
    
    public $timestamps = false;

    public function allEWT($vars = '', $setup = 0)
    {   
        $penalties = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $pens = array();
        if (!empty($vars)) {
            $pens[] = array('' => 'select a '.$vars);
        } else {
            $pens[] = array('' => 'select a department');
        }
        foreach ($penalties as $penalty) {
            $pens[] = array(
                $penalty->id => $penalty->code . ' - ' . $penalty->name
            );
        }

        $penalties = array();
        foreach($pens as $pen) {
            foreach($pen as $key => $val) {
                $penalties[$key] = $val;
            }
        }

        return $penalties;
    }
}
