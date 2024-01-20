<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcoService extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'eco_services';
    
    public $timestamps = false;

    public function allServices($type = 0)
    {
        $services = self::where('is_active', 1)->where('is_cemetery', $type)->orderBy('id', 'asc')->get();
    
        $servs = array();
        $servs[] = array('' => 'select a service');
        foreach ($services as $service) {
            $servs[] = array(
                $service->id => $service->tfoc_name
            );
        }

        $services = array();
        foreach($servs as $dep) {
            foreach($dep as $key => $val) {
                $services[$key] = $val;
            }
        }

        return $services;
    }

    public function tfoc()
    {
        return $this->belongsTo('App\Models\Engneering\CtoTfoc', 'tfoc_id', 'id');
    }
}
