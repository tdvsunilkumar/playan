<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsAction extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'sms_actions';
    
    public $timestamps = false;

    public function allSmsActions($vars = '')
    {
        $actions = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $acts = array();
        if (!empty($vars)) {
            $acts[] = array('' => 'select a '.$vars);
        } else {
            $acts[] = array('' => 'select an action');
        }
        foreach ($actions as $action) {
            $acts[] = array(
                $action->id => $action->code
            );
        }

        $actions = array();
        foreach($acts as $act) {
            foreach($act as $key => $val) {
                $actions[$key] = $val;
            }
        }

        return $actions;
    }
}
