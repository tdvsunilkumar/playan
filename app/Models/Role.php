<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'role';
    
    public $timestamps = false;

    public function inserted()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function modified()
    {
        return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }

    public function allRoles($rolex)
    {    
        $roles = self::where('is_active', 1)->orderBy('id', 'asc');
        if ($rolex <> 1) {
            $roles = $roles->where('id', '!=', 1);
        }
        $roles = $roles->get();

        $rols = array();
        $rols[] = array('' => 'select a role');
        foreach ($roles as $role) {
            $rols[] = array(
                $role->id => $role->name
            );
        }

        $roles = array();
        foreach($rols as $rol) {
            foreach($rol as $key => $val) {
                $roles[$key] = $val;
            }
        }

        return $roles;
    }
}
