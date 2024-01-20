<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'citizens';
    
    public $timestamps = false;

    public function allCitizens($vars = '')
    {
        $citizens = self::where('deleted_at', NULL)->orderBy('id', 'asc')->get();
    
        $citiz = array();
        if (!empty($vars)) {
            $citiz[] = array('' => 'select a '.$vars);
        } else {
            $citiz[] = array('' => 'select a citizen');
        }
        foreach ($citizens as $citizen) {
            $citiz[] = array(
                $citizen->id => ucwords($citizen->cit_fullname)
            );
        }

        $citizens = array();
        foreach($citiz as $citi) {
            foreach($citi as $key => $val) {
                $citizens[$key] = $val;
            }
        }

        return $citizens;
    }
}
