<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BploApplicationType extends Model
{
    protected $guarded = ['id'];

    public $table = 'bplo_application_type';
    
    public $timestamps = false;

    public function allAppType($vars = '')
  {
    
      $app_types = self::where('id','!=',3)->orderBy('id')->get();
      $brgys = array();
      foreach ($app_types as $app_type) {
          $brgys[] = array(
              $app_type->id => $app_type->app_type
          );
      }

      $app_types = array();
      foreach($brgys as $brgy) {
          foreach($brgy as $key => $val) {
              $app_types[$key] = $val;
          }
      }

      return $app_types;
  }
}
